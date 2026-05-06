<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Menampilkan daftar lokasi.
     */
    public function index()
    {
        $locations = Location::with(['parent'])->withCount('items')->latest()->paginate(10);
        $allLocations = Location::whereNull('parent_id')->get(); // For parent selection (only top level to keep it simple)
        return view('locations.index', compact('locations', 'allLocations'));
    }

    /**
     * Show the form for creating a new location.
     */
    public function create()
    {
        return view('locations.create');
    }

    /**
     * Menyimpan lokasi baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name',
            'address' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:locations,id',
        ]);

        Location::create($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Location successfully added.');
    }

    /**
     * Menampilkan detail satu lokasi.
     */
    public function show(Location $location)
    {
        $location->load('items.asset');
        return view('locations.show', compact('location'));
    }

    /**
     * Menampilkan form edit lokasi.
     */
    public function edit(Location $location)
    {
        $allLocations = Location::whereNull('parent_id')->get();
        return view('locations.edit', compact('location', 'allLocations'));
    }

    /**
     * Memperbarui data lokasi di database.
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,' . $location->id,
            'address' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:locations,id|not_in:' . $location->id,
        ]);

        $location->update($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Location successfully updated.');
    }

    public function destroy(Location $location)
    {
        if ($location->items()->count() > 0) {
            return redirect()->back()->with('error', 'Failed to delete! This location still has registered physical items.');
        }

        $location->delete();

        return redirect()->route('locations.index')
            ->with('success', 'Location successfully removed.');
    }
}
