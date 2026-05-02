<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use App\Models\AssetAssignment;
use App\Models\UnitOfMeasurement;
use App\Models\AssetStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    /**
     * index: Menampilkan daftar aset dengan paginasi dan eager loading relasi.
     */
    public function index()
    {
        $assets = Asset::with(['category', 'uom', 'stocks.location'])->latest()->paginate(10);
        return view('assets.index', compact('assets'));
    }

    /**
     * create: Menampilkan form tambah aset beserta list kategori & lokasi.
     */
    public function create()
    {
        $categories = Category::all();
        $locations = Location::all();
        $uoms = UnitOfMeasurement::all();
        return view('assets.create', compact('categories', 'locations', 'uoms'));
    }

    /**
     * store: Validasi data dan auto-generate asset_code (AST-YYYYMMDDHHMMSS).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'uom_id'        => 'required|exists:unit_of_measurements,id',
            'purchase_date' => 'required|date',
            'price'         => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
            'asset_code'    => 'nullable|string|unique:assets,asset_code',
            // Distribution validation
            'distributions' => 'required|array|min:1',
            'distributions.*.location_id' => 'required|exists:locations,id',
            'distributions.*.quantity'    => 'required|integer|min:1',
            'distributions.*.status'      => 'required|in:Available,Deployed,Maintenance,Broken,Lost',
            'images'        => 'nullable|array|max:4',
            'images.*'      => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            if (empty($validated['asset_code'])) {
                $validated['asset_code'] = 'AST-' . now()->format('YmdHis');
            }

            // Status default untuk record utama (bisa disesuaikan nanti)
            $validated['status'] = 'Available'; 

            $asset = Asset::create($validated);

            foreach ($validated['distributions'] as $dist) {
                AssetStock::create([
                    'asset_id'    => $asset->id,
                    'location_id' => $dist['location_id'],
                    'quantity'    => $dist['quantity'],
                    'status'      => $dist['status'],
                ]);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('assets/images', 'public');
                    \App\Models\AssetImage::create([
                        'asset_id' => $asset->id,
                        'image_path' => $path,
                    ]);
                }
            }

            return redirect()->route('assets.index')
                ->with('success', 'Asset berhasil ditambahkan dengan stok terdistribusi.');
        });
    }

    /**
     * show: Menampilkan detail spesifik 1 aset beserta riwayat peminjaman.
     */
    public function show(Asset $asset)
    {
        $asset->load(['category', 'uom', 'stocks.location', 'assignments.user', 'currentAssignment.user']);
        $users = User::all();
        return view('assets.show', compact('asset', 'users'));
    }

    /**
     * edit: Mengambil data aset beserta list kategori & lokasi untuk dropdown.
     */
    public function edit(Asset $asset)
    {
        $categories = Category::all();
        $locations = Location::all();
        $uoms = UnitOfMeasurement::all();
        $asset->load('stocks');
        return view('assets.edit', compact('asset', 'categories', 'locations', 'uoms'));
    }

    /**
     * update: Validasi dan update data aset.
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'uom_id'        => 'required|exists:unit_of_measurements,id',
            'purchase_date' => 'required|date',
            'price'         => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
            'asset_code'    => 'required|string|unique:assets,asset_code,' . $asset->id,
            // Distribution validation
            'distributions' => 'required|array|min:1',
            'distributions.*.location_id' => 'required|exists:locations,id',
            'distributions.*.quantity'    => 'required|integer|min:1',
            'distributions.*.status'      => 'required|in:Available,Deployed,Maintenance,Broken,Lost',
            'images'        => 'nullable|array|max:4',
            'images.*'      => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        return DB::transaction(function () use ($validated, $asset, $request) {
            $asset->update($validated);

            // Sync Stocks: Hapus yang lama dan buat yang baru (simple approach)
            $asset->stocks()->delete();

            foreach ($validated['distributions'] as $dist) {
                AssetStock::create([
                    'asset_id'    => $asset->id,
                    'location_id' => $dist['location_id'],
                    'quantity'    => $dist['quantity'],
                    'status'      => $dist['status'],
                ]);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('assets/images', 'public');
                    \App\Models\AssetImage::create([
                        'asset_id' => $asset->id,
                        'image_path' => $path,
                    ]);
                }
            }

            return redirect()->route('assets.index')
                ->with('success', 'Data asset dan distribusi stok berhasil diperbarui.');
        });
    }

    /**
     * destroy: Menghapus data aset.
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Asset berhasil dihapus.');
    }

    /**
     * printLabel: Menampilkan halaman cetak label untuk satu aset.
     */
    public function printLabel(Asset $asset)
    {
        return view('assets.print-label', ['assets' => collect([$asset])]);
    }

    /**
     * bulkPrint: Menampilkan halaman cetak label untuk banyak aset.
     */
    public function bulkPrint(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu aset untuk dicetak.');
        }

        // Limit untuk mencegah overload sesuai permintaan user
        $limit = 50;
        if (count($ids) > $limit) {
            return back()->with('error', "Batas maksimal cetak massal adalah {$limit} aset.");
        }

        $assets = Asset::whereIn('id', $ids)->get();
        return view('assets.print-label', compact('assets'));
    }
}
