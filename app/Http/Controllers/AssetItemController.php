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
        $item->load(['asset.category', 'location', 'assignments.user', 'maintenances']);
        return view('inventory.show', compact('item'));
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
                    'purchase_date' => now(),
                    'purchase_price' => $asset->price,
                ]);
            }
        });

        return back()->with('success', $validated['quantity'] . ' Unit baru berhasil ditambahkan ke katalog.');
    }
}
