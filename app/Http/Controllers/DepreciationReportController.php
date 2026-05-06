<?php

namespace App\Http\Controllers;

use App\Models\AssetItem;
use App\Models\Category;
use App\Exports\DepreciationExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DepreciationReportController extends Controller
{
    /**
     * Menampilkan laporan penyusutan aset.
     */
    public function index(Request $request)
    {
        $query = AssetItem::with(['asset.category', 'location']);
        $mode = $request->get('mode', 'commercial'); // Default commercial

        // Filter Kategori
        if ($request->filled('category_id')) {
            $query->whereHas('asset', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'Disposed');
        }

        // Filter Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('item_code', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhereHas('asset', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $items = $query->latest('purchase_date')->paginate(20)->withQueryString();
        
        $categories = Category::all();
        $statuses = ['Available', 'Deployed', 'Maintenance', 'Broken'];

        return view('reports.depreciation', compact('items', 'categories', 'statuses', 'mode'));
    }

    /**
     * Export laporan ke Excel/CSV.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $mode = $request->get('mode', 'commercial');
        $fileName = 'Laporan_Penyusutan_' . date('Ymd_His');
        
        if ($format == 'xlsx') {
            return Excel::download(new DepreciationExport($request), $fileName . '.xlsx');
        }

        if ($format == 'pdf') {
            $query = AssetItem::with(['asset.category', 'location']);

            // Filter Kategori
            if ($request->filled('category_id')) {
                $query->whereHas('asset', function($q) use ($request) {
                    $q->where('category_id', $request->category_id);
                });
            }

            // Filter Status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            } else {
                $query->where('status', '!=', 'Disposed');
            }

            // Filter Pencarian
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('item_code', 'like', "%{$search}%")
                      ->orWhere('serial_number', 'like', "%{$search}%")
                      ->orWhereHas('asset', function($sq) use ($search) {
                          $sq->where('name', 'like', "%{$search}%");
                      });
                });
            }

            $items = $query->latest('purchase_date')->get();

            $pdf = Pdf::loadView('reports.depreciation-pdf', compact('items', 'mode'))
                      ->setPaper('a4', 'landscape');

            return $pdf->download($fileName . '.pdf');
        }
        
        return Excel::download(new DepreciationExport($request), $fileName . '.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
