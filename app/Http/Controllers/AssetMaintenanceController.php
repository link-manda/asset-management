<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetMaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = AssetMaintenance::with('asset')->latest()->paginate(10);
        return view('maintenances.index', compact('maintenances'));
    }

    public function create()
    {
        $assets = Asset::where('status', '!=', 'Broken')->get();
        return view('maintenances.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'maintenance_date' => 'required|date',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|in:Scheduled,In Progress,Completed',
        ]);

        DB::transaction(function () use ($request) {
            $maintenance = AssetMaintenance::create($request->all());

            // Jika status In Progress atau Scheduled, ubah aset jadi Maintenance
            // (Kecuali jika sudah Completed saat input awal, tapi biasanya In Progress dulu)
            if ($request->status != 'Completed') {
                $maintenance->asset->update(['status' => 'Maintenance']);
            }
        });

        return redirect()->route('maintenances.index')->with('success', 'Maintenance scheduled successfully.');
    }

    public function edit(AssetMaintenance $maintenance)
    {
        $assets = Asset::all();
        return view('maintenances.edit', compact('maintenance', 'assets'));
    }

    public function update(Request $request, AssetMaintenance $maintenance)
    {
        $request->validate([
            'maintenance_date' => 'required|date',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|in:Scheduled,In Progress,Completed',
        ]);

        DB::transaction(function () use ($request, $maintenance) {
            $oldStatus = $maintenance->status;
            $maintenance->update($request->all());

            if ($request->status == 'Completed' && $oldStatus != 'Completed') {
                $maintenance->asset->update(['status' => 'Available']);
            } elseif ($request->status != 'Completed' && $oldStatus == 'Completed') {
                // Jika ditarik balik jadi In Progress
                $maintenance->asset->update(['status' => 'Maintenance']);
            }
        });

        return redirect()->route('maintenances.index')->with('success', 'Maintenance updated successfully.');
    }

    public function destroy(AssetMaintenance $maintenance)
    {
        DB::transaction(function () use ($maintenance) {
            $asset = $maintenance->asset;
            $maintenance->delete();

            // Jika tidak ada maintenance aktif lagi untuk asset ini, kembalikan statusnya
            if ($asset->status == 'Maintenance' && !$asset->maintenances()->where('status', '!=', 'Completed')->exists()) {
                $asset->update(['status' => 'Available']);
            }
        });

        return redirect()->route('maintenances.index')->with('success', 'Maintenance record deleted.');
    }
}
