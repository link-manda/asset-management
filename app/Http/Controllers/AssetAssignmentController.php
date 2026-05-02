<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetAssignmentController extends Controller
{
    /**
     * index: Menampilkan seluruh riwayat penugasan aset.
     */
    public function index()
    {
        $assignments = AssetAssignment::with(['asset', 'user'])->latest()->paginate(10);
        return view('assignments.index', compact('assignments'));
    }

    public function create(Asset $asset)
    {
        $users = \App\Models\User::all();
        return view('assets.checkout', compact('asset', 'users'));
    }

    /**
     * checkoutStore: Gunakan DB::transaction. Simpan data ke asset_assignments 
     * lalu update status di tabel assets jadi 'Deployed'.
     */
    public function checkoutStore(Request $request, Asset $asset)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'assigned_date' => 'required|date',
            'condition_on_checkout' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $asset) {
            AssetAssignment::create([
                'asset_id' => $asset->id,
                'assigned_to' => $request->assigned_to,
                'assigned_date' => $request->assigned_date,
                'condition_on_checkout' => $request->condition_on_checkout,
            ]);
            
            $asset->update(['status' => 'Deployed']);
        });

        return redirect()->back()->with('success', 'Asset berhasil di-checkout.');
    }

    /**
     * checkinStore: Gunakan DB::transaction. Update return_date di asset_assignments, 
     * lalu kembalikan status assets jadi 'Available'.
     */
    public function checkinStore(Request $request, Asset $asset)
    {
        $request->validate([
            'return_date' => 'required|date',
            'condition_on_return' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $asset) {
            $assignment = $asset->currentAssignment;
            
            if ($assignment) {
                $assignment->update([
                    'return_date' => $request->return_date,
                    'condition_on_return' => $request->condition_on_return,
                ]);
            }
            
            $asset->update(['status' => 'Available']);
        });

        return redirect()->back()->with('success', 'Asset berhasil di-checkin.');
    }
}
