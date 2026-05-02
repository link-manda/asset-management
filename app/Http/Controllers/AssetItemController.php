<?php

namespace App\Http\Controllers;

use App\Models\AssetItem;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetItemController extends Controller
{
    /**
     * Display a global listing of all physical items.
     */
    public function index(Request $request)
    {
        $query = AssetItem::with(['asset.category', 'location', 'currentAssignment.user']);

        // Filter Search (Scanner friendly)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('item_code', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhereHas('asset', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                        ->orWhere('asset_code', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Category
        if ($request->filled('category_id')) {
            $query->whereHas('asset', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter Location
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        $items = $query->latest()->paginate(20)->withQueryString();

        $categories = Category::all();
        $locations = Location::all();
        $statuses = ['Available', 'Deployed', 'Maintenance', 'Broken', 'Disposed'];

        return view('inventory.index', compact('items', 'categories', 'locations', 'statuses'));
    }

    /**
     * Show detail of a specific physical item.
     */
    public function show(AssetItem $item)
    {
        $item->load(['asset.images', 'asset.category', 'location', 'assignments.user', 'maintenances']);
        
        $schedule = [];
        if ($item->purchase_price && $item->useful_life_months && $item->purchase_date) {
            $purchasePrice = (float) $item->purchase_price;
            $residualValue = (float) ($item->residual_value ?? 0);
            $usefulLife = (int) $item->useful_life_months;
            
            $depreciationPerMonth = ($purchasePrice - $residualValue) / $usefulLife;
            $currentBookValue = $purchasePrice;
            $accumulated = 0;
            $startDate = \Carbon\Carbon::parse($item->purchase_date);

            for ($i = 1; $i <= $usefulLife; $i++) {
                $periodDate = $startDate->copy()->addMonths($i);
                $monthYear = $periodDate->translatedFormat('F Y');
                
                $accumulated += $depreciationPerMonth;
                $beginningValue = $currentBookValue;
                $currentBookValue -= $depreciationPerMonth;

                $schedule[] = [
                    'period' => $i,
                    'month_year' => $monthYear,
                    'beginning_value' => $beginningValue,
                    'depreciation_expense' => $depreciationPerMonth,
                    'accumulated_depreciation' => $accumulated,
                    'ending_book_value' => max($currentBookValue, $residualValue),
                ];
            }
        }

        return view('inventory.show', compact('item', 'schedule'));
    }

    /**
     * Bulk print labels for selected items.
     */
    public function bulkPrint(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu item untuk dicetak.');
        }

        $items = AssetItem::whereIn('id', $ids)->with('asset')->get();
        return view('assets.print-label', compact('items'));
    }

    /**
     * Store new items for an existing asset.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'location_id' => 'required|exists:locations,id',
            'quantity' => 'required|integer|min:1|max:50',
            'condition' => 'required|string',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'residual_value' => 'nullable|numeric|min:0',
            'useful_life_months' => 'nullable|integer|min:1',
        ]);

        $asset = \App\Models\Asset::find($validated['asset_id']);

        DB::transaction(function () use ($validated, $asset) {
            // Get current max sequence for this asset
            $lastItem = AssetItem::where('asset_id', $asset->id)
                ->where('item_code', 'like', $asset->asset_code . '-%')
                ->orderBy('item_code', 'desc')
                ->first();

            $lastSequence = 0;
            if ($lastItem) {
                $parts = explode('-', $lastItem->item_code);
                $lastSequence = (int) end($parts);
            }

            for ($i = 0; $i < $validated['quantity']; $i++) {
                $sequence = str_pad($lastSequence + $i + 1, 3, '0', STR_PAD_LEFT);
                $itemCode = $asset->asset_code . '-' . $sequence;

                AssetItem::create([
                    'asset_id' => $asset->id,
                    'item_code' => $itemCode,
                    'location_id' => $validated['location_id'],
                    'status' => 'Available',
                    'condition' => $validated['condition'],
                    'purchase_date' => $validated['purchase_date'] ?? now(),
                    'purchase_price' => $validated['purchase_price'] ?? $asset->price,
                    'residual_value' => $validated['residual_value'] ?? 0,
                    'useful_life_months' => $validated['useful_life_months'] ?? ($asset->category->default_useful_life_months ?? 0),
                ]);
            }
        });

        return back()->with('success', $validated['quantity'] . ' Unit baru berhasil ditambahkan ke katalog.');
    }
}
