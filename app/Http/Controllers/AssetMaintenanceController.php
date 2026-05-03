<?php

namespace App\Http\Controllers;

use App\Models\AssetItem;
use App\Models\AssetMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetMaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = AssetMaintenance::with('item.asset')->latest()->paginate(10);
        return view('maintenances.index', compact('maintenances'));
    }

    public function create()
    {
        $items = AssetItem::whereIn('status', ['Available', 'Deployed'])
            ->with(['asset.category', 'location'])
            ->get();
            
        return view('maintenances.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_item_id' => 'required|exists:asset_items,id',
            'maintenance_date' => 'required|date',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|in:Scheduled,In Progress,Completed',
        ]);

        DB::transaction(function () use ($request) {
            $maintenance = AssetMaintenance::create($request->all());

            if ($request->status != 'Completed') {
                $maintenance->item->update(['status' => 'Maintenance']);
            }
        });

        return redirect()->route('maintenances.index')->with('success', 'Maintenance scheduled successfully.');
    }

    public function edit(AssetMaintenance $maintenance)
    {
        $items = AssetItem::with('asset')->get();
        return view('maintenances.edit', compact('maintenance', 'items'));
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
                $maintenance->item->update(['status' => 'Available']);
            } elseif ($request->status != 'Completed' && $oldStatus == 'Completed') {
                $maintenance->item->update(['status' => 'Maintenance']);
            }
        });

        return redirect()->route('maintenances.index')->with('success', 'Maintenance updated successfully.');
    }

    public function destroy(AssetMaintenance $maintenance)
    {
        DB::transaction(function () use ($maintenance) {
            $item = $maintenance->item;
            $maintenance->delete();

            if ($item->status == 'Maintenance' && !$item->maintenances()->where('status', '!=', 'Completed')->exists()) {
                $item->update(['status' => 'Available']);
            }
        });

        return redirect()->route('maintenances.index')->with('success', 'Maintenance record deleted.');
    }
}
