<?php

namespace App\Http\Controllers;

use App\Models\UnitOfMeasurement;
use Illuminate\Http\Request;

class UnitOfMeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $uoms = UnitOfMeasurement::withCount('assets')->latest()->paginate(10);
        return view('uoms.index', compact('uoms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('uoms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:unit_of_measurements,name',
            'symbol' => 'nullable|string|max:50',
        ]);

        UnitOfMeasurement::create($validated);

        return redirect()->route('uoms.index')
            ->with('success', 'Satuan (UoM) berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitOfMeasurement $uom)
    {
        return view('uoms.edit', compact('uom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnitOfMeasurement $uom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:unit_of_measurements,name,' . $uom->id,
            'symbol' => 'nullable|string|max:50',
        ]);

        $uom->update($validated);

        return redirect()->route('uoms.index')
            ->with('success', 'Satuan (UoM) berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitOfMeasurement $uom)
    {
        if ($uom->assets()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal menghapus! Satuan ini masih digunakan oleh aset.');
        }

        $uom->delete();

        return redirect()->route('uoms.index')
            ->with('success', 'Satuan (UoM) berhasil dihapus.');
    }
}
