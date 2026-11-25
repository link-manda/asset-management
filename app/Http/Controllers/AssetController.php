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
     * index: Display asset list (Master) with pagination.
     */
    public function index(Request $request)
    {
        $assets = Asset::with(['category', 'uom', 'items'])
            ->filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $statuses = ['Available', 'Deployed', 'Maintenance', 'Broken', 'Disposed'];

        return view('assets.index', compact('assets', 'categories', 'locations', 'statuses'));
    }

    /**
     * create: Show the form to add a new asset.
     */
    public function create()
    {
        $categories = Category::all();
        $locations = Location::all();
        $uoms = UnitOfMeasurement::all();
        return view('assets.create', compact('categories', 'locations', 'uoms'));
    }

    /**
     * store: Save Master Asset and its physical items in one transaction.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Master
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'uom_id'        => 'required|exists:unit_of_measurements,id',
            'price'         => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
            'asset_code'    => 'required|string|unique:assets,asset_code',
            'purchase_date' => 'required|date',
            // Physical Units
            'items'         => 'required|array|min:1',
            'items.*.serial_number' => 'nullable|string',
            'items.*.location_id'   => 'required|exists:locations,id',
            'items.*.condition'     => 'required|string',
            'items.*.fiscal_group'  => 'nullable|string',
            'items.*.useful_life_months' => 'nullable|integer',
            'items.*.residual_value'      => 'nullable|numeric',
            // Images
            'images'        => 'nullable|array|max:4',
            'images.*'      => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            // 1. Create Master Asset
            $asset = Asset::create([
                'category_id' => $validated['category_id'],
                'uom_id'      => $validated['uom_id'],
                'name'        => $validated['name'],
                'asset_code'  => $validated['asset_code'],
                'price'       => $validated['price'],
                'notes'       => $validated['notes'],
            ]);

            // 2. Resolve Prefix Category
            $category = Category::find($validated['category_id']);
            $prefix = strtoupper(substr($category->name, 0, 3));

            // 3. Create Physical Units
            foreach ($validated['items'] as $index => $itemData) {
                // Generate Item Code
                $count = AssetItem::whereHas('asset', function($q) use ($category) {
                    $q->where('category_id', $category->id);
                })->count() + $index + 1;
                
                $itemCode = $prefix . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);

                AssetItem::create([
                    'asset_id'      => $asset->id,
                    'item_code'     => $itemCode,
                    'serial_number' => $itemData['serial_number'],
                    'location_id'   => $itemData['location_id'],
                    'status'        => 'Available',
                    'condition'     => $itemData['condition'],
                    'purchase_date' => $validated['purchase_date'],
                    'purchase_price'=> $validated['price'],
                    'useful_life_months' => $itemData['useful_life_months'] ?? 0,
                    'residual_value'     => $itemData['residual_value'] ?? 0,
                    'fiscal_group'       => $itemData['fiscal_group'] ?? null,
                ]);
            }

            // 4. Handle Images
            $this->handleImageUploads($request, $asset);

            return redirect()->route('assets.index')
                ->with('success', 'Master Asset and ' . count($validated['items']) . ' Physical Units successfully registered.');
        });
    }

    /**
     * show: Display Master Asset details and list of Physical Units.
     */
    public function show(Asset $asset)
    {
        $asset->load(['category', 'uom', 'images', 'items.location', 'items.currentAssignment.user', 'assignments.user', 'maintenances']);
        $users = User::all();
        $locations = Location::all();
        return view('assets.show', compact('asset', 'users', 'locations'));
    }

    /**
     * edit: Form to edit master asset.
     */
    public function edit(Asset $asset)
    {
        $categories = Category::all();
        $uoms = UnitOfMeasurement::all();
        return view('assets.edit', compact('asset', 'categories', 'uoms'));
    }

    /**
     * update: Update Master Asset data.
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
            ->with('success', 'Master Asset information successfully updated.');
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
     * destroy: Delete Master Asset and all physical units.
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')
            ->with('success', 'Asset and all physical units successfully removed.');
    }
}
