<?php

namespace App\Http\Controllers;

use App\Models\AssetItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetSummaryController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getSummaryData($request);
        $groupBy = $request->get('by', 'category');
        return view('reports.summary', compact('data', 'groupBy'));
    }

    private function getSummaryData(Request $request)
    {
        $groupBy = $request->get('by', 'category');
        $query = AssetItem::query()
            ->select([
                DB::raw('COUNT(*) as total_units'),
                DB::raw('SUM(CASE WHEN status = "Available" THEN 1 ELSE 0 END) as available_units'),
                DB::raw('SUM(CASE WHEN status = "Deployed" THEN 1 ELSE 0 END) as deployed_units'),
                DB::raw('SUM(CASE WHEN status = "Maintenance" THEN 1 ELSE 0 END) as maintenance_units'),
                DB::raw('SUM(purchase_price) as total_investment'),
            ]);

        if ($groupBy === 'category') {
            $query->join('assets', 'asset_items.asset_id', '=', 'assets.id')
                  ->join('categories', 'assets.category_id', '=', 'categories.id')
                  ->addSelect('categories.name as label')
                  ->groupBy('categories.name');
        } elseif ($groupBy === 'location') {
            $query->join('locations', 'asset_items.location_id', '=', 'locations.id')
                  ->addSelect('locations.name as label')
                  ->groupBy('locations.name');
        } else {
            // Fallback for empty/unimplemented groupings
            $query->addSelect(DB::raw('"Unimplemented" as label'))
                  ->groupBy('label');
        }

        return $query->get()->map(function($row) {
            // Simplification: use total_investment as current_book_value for now
            // In Task 5/6 we can refine this if needed
            $row->current_book_value = $row->total_investment;
            return $row;
        });
    }

    public function exportExcel(Request $request) {}
    public function exportCsv(Request $request) {}
    public function exportPdf(Request $request) {}
}
