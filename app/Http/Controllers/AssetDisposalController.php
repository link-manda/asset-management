<?php

namespace App\Http\Controllers;

use App\Models\AssetItem;
use App\Models\AssetDisposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AssetDisposalController extends Controller
{
    /**
     * Menampilkan daftar disposal
     */
    public function index()
    {
        $disposals = AssetDisposal::with(['item.asset', 'creator'])->latest()->paginate(15);
        return view('disposals.index', compact('disposals'));
    }

    /**
     * Memproses disposal berdasarkan barcode unit fisik
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode'       => 'required|exists:asset_items,item_code',
            'disposal_date' => 'required|date',
            'reason'        => 'required|in:Sold,Broken,Lost,Scrapped,Donated',
            'selling_price' => 'nullable|numeric|min:0',
            'notes'         => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $item = AssetItem::where('item_code', $validated['barcode'])->firstOrFail();

            if ($item->status === 'Disposed') {
                return back()->with('error', 'Unit ini sudah di-dispose sebelumnya.');
            }

            // Ubah status unit menjadi Disposed
            $item->update(['status' => 'Disposed']);

            // Simpan record Disposal
            AssetDisposal::create([
                'asset_item_id'  => $item->id,
                'disposal_date'  => $validated['disposal_date'],
                'reason'         => $validated['reason'],
                'selling_price'  => $validated['selling_price'],
                'notes'          => $validated['notes'],
                'created_by'     => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('disposals.index')
                ->with('success', 'Unit ' . $validated['barcode'] . ' berhasil di-dispose.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
