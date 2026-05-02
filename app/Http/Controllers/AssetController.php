<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetItem;
use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use App\Models\UnitOfMeasurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    /**
     * index: Menampilkan daftar aset (Master) dengan paginasi.
     */
    public function index()
    {
        $assets = Asset::with(['category', 'uom', 'items'])->latest()->paginate(10);
        return view('assets.index', compact('assets'));
    }

    /**
     * create: Menampilkan form tambah aset.
     */
    public function create()
    {
        $categories = Category::all();
        $locations = Location::all();
        $uoms = UnitOfMeasurement::all();
        return view('assets.create', compact('categories', 'locations', 'uoms'));
    }

    /**
     * store: Membuat Master Asset dan Unit Fisik (Items) secara otomatis.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'uom_id'        => 'required|exists:unit_of_measurements,id',
            'price'         => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
            'asset_code'    => 'nullable|string|unique:assets,asset_code',
            // Bulk initialization via distributions
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

            $asset = Asset::create($validated);

            foreach ($validated['distributions'] as $dist) {
                for ($i = 0; $i < $dist['quantity']; $i++) {
                    AssetItem::create([
                        'asset_id'      => $asset->id,
                        'item_code'     => 'SN-' . strtoupper(bin2hex(random_bytes(4))),
                        'location_id'   => $dist['location_id'],
                        'status'        => $dist['status'],
                        'condition'     => 'Good',
                        'purchase_price'=> $asset->price,
                    ]);
                }
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
                ->with('success', 'Master Asset dan Unit Fisik berhasil didaftarkan.');
        });
    }

    /**
     * show: Menampilkan detail Master Asset dan daftar Unit Fisik.
     */
    public function show(Asset $asset)
    {
        $asset->load(['category', 'uom', 'items.location', 'assignments.user', 'maintenances']);
        $users = User::all();
        return view('assets.show', compact('asset', 'users'));
    }

    /**
     * edit: Mengambil data aset untuk dropdown.
     */
    public function edit(Asset $asset)
    {
        $categories = Category::all();
        $locations = Location::all();
        $uoms = UnitOfMeasurement::all();
        $asset->load('items');
        return view('assets.edit', compact('asset', 'categories', 'locations', 'uoms'));
    }

    /**
     * update: Update data Master Asset.
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'uom_id'        => 'required|exists:unit_of_measurements,id',
            'price'         => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
            'asset_code'    => 'required|string|unique:assets,asset_code,' . $asset->id,
            'images'        => 'nullable|array|max:4',
            'images.*'      => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        return DB::transaction(function () use ($validated, $asset, $request) {
            $asset->update($validated);

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
                ->with('success', 'Data Master Asset berhasil diperbarui.');
        });
    }

    /**
     * destroy: Menghapus Master Asset dan seluruh unit fisiknya.
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')
            ->with('success', 'Asset dan seluruh unit fisik berhasil dihapus.');
    }

    /**
     * printLabel: Menampilkan halaman cetak label.
     */
    public function printLabel(Asset $asset)
    {
        return view('assets.print-label', ['assets' => collect([$asset])]);
    }

    /**
     * bulkPrint: Cetak label massal.
     */
    public function bulkPrint(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu aset untuk dicetak.');
        }

        $assets = Asset::whereIn('id', $ids)->get();
        return view('assets.print-label', compact('assets'));
    }
}
