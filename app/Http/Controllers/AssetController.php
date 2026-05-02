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
            'purchase_date' => 'required|date',
            'price'         => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
            'asset_code'    => 'nullable|string|unique:assets,asset_code',
            // Physical Items
            'items'         => 'required|array|min:1',
            'items.*.serial_number' => 'nullable|string',
            'items.*.location_id'   => 'required|exists:locations,id',
            'items.*.condition'     => 'required|string',
            'items.*.residual_value'=> 'nullable|numeric|min:0',
            'items.*.useful_life_months' => 'nullable|integer|min:1',
            'images'        => 'nullable|array|max:4',
            'images.*'      => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            // 1. Generate Master Code if empty
            if (empty($validated['asset_code'])) {
                $category = Category::find($validated['category_id']);
                $prefix = strtoupper(substr($category->name, 0, 3));
                $validated['asset_code'] = $prefix . '-' . strtoupper(bin2hex(random_bytes(2)));
            }

            // 2. Create Master Asset
            $asset = Asset::create([
                'name' => $validated['name'],
                'asset_code' => $validated['asset_code'],
                'category_id' => $validated['category_id'],
                'uom_id' => $validated['uom_id'],
                'price' => $validated['price'],
                'notes' => $validated['notes'],
            ]);

            // 3. Create Physical Items
            foreach ($validated['items'] as $index => $itemData) {
                // Generate Item Code (Barcode)
                $sequence = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                $itemCode = $asset->asset_code . '-' . $sequence;

                AssetItem::create([
                    'asset_id'      => $asset->id,
                    'item_code'     => $itemCode,
                    'serial_number' => $itemData['serial_number'],
                    'location_id'   => $itemData['location_id'],
                    'status'        => 'Available',
                    'condition'     => $itemData['condition'],
                    'purchase_date' => $validated['purchase_date'],
                    'purchase_price'=> $validated['price'],
                    'residual_value' => $itemData['residual_value'] ?? 0,
                    'useful_life_months' => $itemData['useful_life_months'] ?? 0,
                ]);
            }

            // 4. Handle Images
            $this->handleImageUploads($request, $asset);

            return redirect()->route('assets.index')
                ->with('success', 'Master Asset dan ' . count($validated['items']) . ' Unit Fisik berhasil didaftarkan.');
        });
    }

    /**
     * show: Menampilkan detail Master Asset dan daftar Unit Fisik.
     */
    public function show(Asset $asset)
    {
        $asset->load(['category', 'uom', 'images', 'items.location', 'items.currentAssignment.user', 'assignments.user', 'maintenances']);
        $users = User::all();
        $locations = Location::all();
        return view('assets.show', compact('asset', 'users', 'locations'));
    }

    /**
     * edit: Form edit master asset.
     */
    public function edit(Asset $asset)
    {
        $categories = Category::all();
        $uoms = UnitOfMeasurement::all();
        return view('assets.edit', compact('asset', 'categories', 'uoms'));
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

        $asset->update($validated);

        // Handle Images
        $this->handleImageUploads($request, $asset);

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Data Master Asset berhasil diperbarui.');
    }

    /**
     * handleImageUploads: Helper to process image uploads for store/update.
     */
    private function handleImageUploads(Request $request, Asset $asset)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('assets/images', 'public');
                \App\Models\AssetImage::create([
                    'asset_id' => $asset->id,
                    'image_path' => $path,
                ]);
            }
        }
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
}
