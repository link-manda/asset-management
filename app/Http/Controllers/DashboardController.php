<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with asset statistics and charts.
     */
    public function index()
    {
        // 1. Total statistik dasar (Berdasarkan Unit Fisik)
        $totalItems = AssetItem::count();
        $totalValue = AssetItem::sum('purchase_price');
        $totalBookValue = AssetItem::all()->sum('current_value');

        // 2. Distribusi Status (Berdasarkan Unit Fisik)
        $statusCounts = AssetItem::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        $allStatuses = ['Available', 'Deployed', 'Maintenance', 'Broken', 'Lost', 'Disposed'];
        $stats = [];
        foreach ($allStatuses as $status) {
            $stats[$status] = $statusCounts[$status] ?? 0;
        }

        // 3. Data Grafik: Nilai Aset per Kategori (Doughnut)
        $categoryData = Category::leftJoin('assets', 'categories.id', '=', 'assets.category_id')
            ->leftJoin('asset_items', 'assets.id', '=', 'asset_items.asset_id')
            ->select('categories.name', DB::raw('SUM(COALESCE(asset_items.purchase_price, 0)) as total_value'))
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // 4. Data Grafik: Tren Akuisisi Aset 6 Bulan Terakhir (Line)
        $monthlyTrend = AssetItem::select(
                DB::raw("DATE_FORMAT(purchase_date, '%Y-%m') as month"),
                DB::raw('SUM(purchase_price) as total_value')
            )
            ->whereNotNull('purchase_date')
            ->where('purchase_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 5. Data Grafik: Biaya Maintenance 6 Bulan Terakhir (Bar)
        $maintenanceTrend = \App\Models\AssetMaintenance::select(
                DB::raw("DATE_FORMAT(maintenance_date, '%Y-%m') as month"),
                DB::raw('SUM(cost) as total_cost')
            )
            ->whereNotNull('maintenance_date')
            ->where('maintenance_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('dashboard.index', [
            'totalAssets'    => $totalItems,
            'totalValue'     => $totalValue,
            'totalBookValue' => $totalBookValue,
            'stats'          => $stats,
            'categoryLabels' => $categoryData->pluck('name'),
            'categoryValues' => $categoryData->pluck('total_value'),
            'trendLabels'    => $monthlyTrend->pluck('month'),
            'trendValues'    => $monthlyTrend->pluck('total_value'),
            'maintLabels'    => $maintenanceTrend->pluck('month'),
            'maintValues'    => $maintenanceTrend->pluck('total_cost'),
            'statusLabels'   => array_keys($stats),
            'statusValues'   => array_values($stats),
        ]);
    }
}
