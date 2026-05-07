<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetSummaryController extends Controller
{
    public function index(Request $request)
    {
        return view('reports.summary');
    }

    public function exportExcel(Request $request) {}
    public function exportCsv(Request $request) {}
    public function exportPdf(Request $request) {}
}
