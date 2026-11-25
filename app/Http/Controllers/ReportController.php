<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetItem;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\GeneralAssetExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function generalReport(Request $request)
    {
        // 1. Statistics for Widgets
        $stats = [
            'total_units' => AssetItem::count(),
            'total_investment' => AssetItem::sum('purchase_price'),
            'maintenance_count' => AssetItem::where('status', 'Maintenance')->count(),
            'disposed_count' => AssetItem::where('status', 'Disposed')->count(),
        ];

        // 2. Current Book Value (Aggregated from items)
        // Note: For large datasets, this should be cached or stored in a column
        $stats['total_book_value'] = AssetItem::all()->sum(function ($item) {
            return $item->current_value;
        });

        // 3. Distribution Data for Charts
        $statusDistribution = AssetItem::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $categoryDistribution = Asset::select('categories.name', DB::raw('count(asset_items.id) as total'))
            ->join('categories', 'assets.category_id', '=', 'categories.id')
            ->join('asset_items', 'assets.id', '=', 'asset_items.asset_id')
            ->groupBy('categories.name')
            ->get();

        // 4. Data for Table with Filters
        $query = AssetItem::with(['asset.category', 'location'])
            ->when($request->category_id, function ($q) use ($request) {
                $q->whereHas('asset', function ($sq) use ($request) {
                    $sq->where('category_id', $request->category_id);
                });
            })
            ->when($request->location_id, function ($q) use ($request) {
                $q->where('location_id', $request->location_id);
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where('item_code', 'like', '%' . $request->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('asset', function ($sq) use ($request) {
                      $sq->where('name', 'like', '%' . $request->search . '%');
                  });
            });

        $items = $query->latest()->paginate(15)->withQueryString();

        $categories = Category::all();
        $locations = Location::all();

        return view('reports.general', compact(
            'stats', 
            'statusDistribution', 
            'categoryDistribution', 
            'items',
            'categories',
            'locations'
        ));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new GeneralAssetExport($request), 'laporan_aset_umum_' . now()->format('YmdHis') . '.xlsx');
    }

    public function exportCsv(Request $request)
    {
        return Excel::download(new GeneralAssetExport($request), 'laporan_aset_umum_' . now()->format('YmdHis') . '.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPdf(Request $request)
    {
        $query = AssetItem::with(['asset.category', 'location'])
            ->when($request->category_id, function ($q) use ($request) {
                $q->whereHas('asset', function ($sq) use ($request) {
                    $sq->where('category_id', $request->category_id);
                });
            })
            ->when($request->location_id, function ($q) use ($request) {
                $q->where('location_id', $request->location_id);
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where('item_code', 'like', '%' . $request->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('asset', function ($sq) use ($request) {
                      $sq->where('name', 'like', '%' . $request->search . '%');
                  });
            });

        $items = $query->latest()->get();

        $pdf = Pdf::loadView('reports.general-pdf', compact('items'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan_aset_umum_' . now()->format('YmdHis') . '.pdf');
    }
}
