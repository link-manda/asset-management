<?php

namespace App\Http\Controllers;

use App\Models\AssetItem;
use App\Models\AssetMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AssetMaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $maintenances = AssetMaintenance::with('item.asset')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                        ->orWhereHas('item', function ($sq) use ($search) {
                            $sq->where('item_code', 'like', "%{$search}%")
                                ->orWhereHas('asset', fn ($aq) => $aq->where('name', 'like', "%{$search}%"));
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

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
        $validated = $request->validate([
            'asset_item_id' => [
                'required',
                Rule::exists('asset_items', 'id')->whereIn('status', ['Available', 'Deployed']),
            ],
            'maintenance_date' => 'required|date',
            'type' => 'required|in:Repair,Routine,Upgrade',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|in:Scheduled,In Progress,Completed',
        ], [
            'asset_item_id.exists' => 'The selected unit is not available for maintenance.',
        ]);

        DB::transaction(function () use ($validated) {
            $item = AssetItem::lockForUpdate()->findOrFail($validated['asset_item_id']);

            if (! in_array($item->status, ['Available', 'Deployed'], true)) {
                throw ValidationException::withMessages([
                    'asset_item_id' => 'The selected unit is not available for maintenance.',
                ]);
            }

            if ($item->maintenances()->where('status', '!=', 'Completed')->exists()) {
                throw ValidationException::withMessages([
                    'asset_item_id' => 'This unit already has an active maintenance record.',
                ]);
            }

            AssetMaintenance::create([
                ...$validated,
                'previous_status' => $item->status,
            ]);

            if ($validated['status'] !== 'Completed') {
                $item->update(['status' => 'Maintenance']);
            }
        });

        return redirect()->route('maintenances.index')->with('success', 'Maintenance scheduled successfully.');
    }

    public function edit(AssetMaintenance $maintenance)
    {
        return view('maintenances.edit', compact('maintenance'));
    }

    public function update(Request $request, AssetMaintenance $maintenance)
    {
        $validated = $request->validate([
            'maintenance_date' => 'required|date',
            'type' => 'required|in:Repair,Routine,Upgrade',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|in:Scheduled,In Progress,Completed',
        ]);

        DB::transaction(function () use ($validated, $maintenance) {
            $item = $maintenance->item()->lockForUpdate()->firstOrFail();
            $previousStatus = $maintenance->previous_status;

            $maintenance->update($validated);

            if ($validated['status'] === 'Completed') {
                $hasOtherActive = $item->maintenances()
                    ->where('id', '!=', $maintenance->id)
                    ->where('status', '!=', 'Completed')
                    ->exists();

                if (! $hasOtherActive && $item->status === 'Maintenance') {
                    $item->update(['status' => $this->restoreStatus($item, $previousStatus)]);
                }

                return;
            }

            if ($item->status !== 'Maintenance') {
                if (! $maintenance->previous_status) {
                    $maintenance->update(['previous_status' => $item->status]);
                }

                $item->update(['status' => 'Maintenance']);
            }
        });

        return redirect()->route('maintenances.index')->with('success', 'Maintenance updated successfully.');
    }

    public function destroy(AssetMaintenance $maintenance)
    {
        DB::transaction(function () use ($maintenance) {
            $item = $maintenance->item()->lockForUpdate()->firstOrFail();
            $previousStatus = $maintenance->previous_status;

            $maintenance->delete();

            $hasOtherActive = $item->maintenances()->where('status', '!=', 'Completed')->exists();

            if (! $hasOtherActive && $item->status === 'Maintenance') {
                $item->update(['status' => $this->restoreStatus($item, $previousStatus)]);
            }
        });

        return redirect()->route('maintenances.index')->with('success', 'Maintenance record deleted.');
    }

    private function restoreStatus(AssetItem $item, ?string $previousStatus): string
    {
        if ($previousStatus === 'Deployed') {
            return $item->currentAssignment()->exists() ? 'Deployed' : 'Available';
        }

        if ($previousStatus === 'Available') {
            return 'Available';
        }

        return $item->currentAssignment()->exists() ? 'Deployed' : 'Available';
    }
}
