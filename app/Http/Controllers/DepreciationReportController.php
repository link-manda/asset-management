<?php

namespace App\Http\Controllers;

use App\Models\AssetItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DepreciationReportController extends Controller
{
    /**
     * Menampilkan laporan penyusutan aset.
     */
    public function index(Request $request)
    {
        $query = AssetItem::with(['asset.category', 'location']);

        // Filter Kategori
        if ($request->filled('category_id')) {
            $query->whereHas('asset', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter Status (Hanya yang belum di-dispose biasanya untuk laporan nilai buku aktif)
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

        return view('reports.depreciation', compact('items', 'categories', 'statuses'));
    }

    /**
     * Export laporan ke CSV (Simpel tanpa library berat).
     */
    public function export(Request $request)
    {
        $query = AssetItem::with(['asset.category']);

        if ($request->filled('category_id')) {
            $query->whereHas('asset', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $items = $query->get();

        $fileName = 'Laporan_Penyusutan_' . date('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Item Code', 'Nama Aset', 'Kategori', 'Tgl Beli', 'Harga Perolehan', 'Umur (Bulan)', 'Nilai Sisa', 'Akumulasi Penyusutan', 'Nilai Buku'];

        $callback = function() use($items, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($items as $item) {
                $accumulated = $item->purchase_price - $item->current_value;
                
                fputcsv($file, [
                    $item->item_code,
                    $item->asset->name,
                    $item->asset->category->name,
                    $item->purchase_date->format('Y-m-d'),
                    $item->purchase_price,
                    $item->useful_life_months,
                    $item->residual_value,
                    round($accumulated, 2),
                    round($item->current_value, 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
