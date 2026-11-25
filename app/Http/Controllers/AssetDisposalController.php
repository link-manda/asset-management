<?php

namespace App\Http\Controllers;

use App\Models\AssetDisposal;
use App\Models\AssetItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssetDisposalController extends Controller
{
    /**
     * Menampilkan daftar disposal
     */
    public function index(Request $request)
    {
        $disposals = AssetDisposal::with(['item.asset', 'creator'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->whereHas('item', function ($sq) use ($search) {
                    $sq->where('item_code', 'like', "%{$search}%")
                        ->orWhereHas('asset', fn ($aq) => $aq->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('reason'), fn ($query) => $query->where('reason', $request->reason))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('disposals.index', compact('disposals'));
    }

    /**
     * Memproses disposal berdasarkan barcode unit fisik
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|exists:asset_items,item_code',
            'disposal_date' => 'required|date',
            'reason' => 'required|in:Sold,Broken,Lost,Scrapped,Donated',
            'selling_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $item = AssetItem::where('item_code', $validated['barcode'])->firstOrFail();

            if ($item->status === 'Disposed') {
                return back()->with('error', 'This unit has already been disposed of.');
            }

            // Ubah status unit menjadi Disposed
            $item->update(['status' => 'Disposed']);

            // Simpan record Disposal
            AssetDisposal::create([
                'asset_item_id' => $item->id,
                'disposal_date' => $validated['disposal_date'],
                'reason' => $validated['reason'],
                'selling_price' => $validated['selling_price'],
                'notes' => $validated['notes'],
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('disposals.index')
                ->with('success', 'Unit '.$validated['barcode'].' has been successfully disposed of.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'An error occurred: '.$e->getMessage());
        }

    }
}
