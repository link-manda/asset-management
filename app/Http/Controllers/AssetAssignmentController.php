<?php

namespace App\Http\Controllers;

use App\Models\AssetItem;
use App\Models\AssetAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssignmentHistoryExport;

class AssetAssignmentController extends Controller
{
    /**
     * index: Menampilkan seluruh riwayat penugasan aset.
     */
    public function index(Request $request)
    {
        $query = AssetAssignment::with(['item.asset', 'user']);

        // Filter Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Export Logic
        if ($request->export === 'excel') {
            return Excel::download(new AssignmentHistoryExport($request->search), 'assignment_history_' . now()->format('YmdHis') . '.xlsx');
        }

        if ($request->export === 'pdf') {
            $assignments = $query->latest()->take(500)->get();
            $pdf = Pdf::loadView('assignments.export-pdf', compact('assignments'))
                      ->setPaper('a4', 'landscape');
            return $pdf->download('assignment_history_' . now()->format('YmdHis') . '.pdf');
        }

        $assignments = $query->latest()->paginate(10)->withQueryString();
        return view('assignments.index', compact('assignments'));
    }

    /**
     * create: Form checkout untuk unit spesifik.
     */
    public function create(AssetItem $item)
    {
        $users = User::all();
        return view('assets.checkout', compact('item', 'users'));
    }

    /**
     * checkoutStore: Proses peminjaman unit fisik.
     */
    public function checkoutStore(Request $request, AssetItem $item)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'assigned_date' => 'required|date',
            'condition_on_checkout' => 'required|string',
        ]);

        if ($item->status !== 'Available') {
            return back()->with('error', 'This unit is currently not available for checkout.');
        }

        DB::transaction(function () use ($request, $item) {
            AssetAssignment::create([
                'asset_item_id' => $item->id,
                'assigned_to' => $request->assigned_to,
                'assigned_date' => $request->assigned_date,
                'condition_on_checkout' => $request->condition_on_checkout,
            ]);
            
            $item->update(['status' => 'Deployed']);
        });

        return redirect()->route('assets.show', $item->asset_id)
            ->with('success', 'Unit ' . $item->item_code . ' has been successfully checked out.');
    }

    /**
     * checkinStore: Proses pengembalian unit fisik.
     */
    public function checkinStore(Request $request, AssetItem $item)
    {
        $request->validate([
            'return_date' => 'required|date',
            'condition_on_return' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $item) {
            $assignment = $item->currentAssignment;
            
            if ($assignment) {
                $assignment->update([
                    'return_date' => $request->return_date,
                    'condition_on_return' => $request->condition_on_return,
                ]);
            }
            
            $item->update(['status' => 'Available']);
        });

        return redirect()->back()->with('success', 'Unit ' . $item->item_code . ' has been successfully checked in.');
    }
}
