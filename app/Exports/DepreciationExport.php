<?php

namespace App\Exports;

use App\Models\AssetItem;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DepreciationExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = AssetItem::with(['asset.category', 'location']);

        if ($this->request->filled('category_id')) {
            $query->whereHas('asset', function($q) {
                $q->where('category_id', $this->request->category_id);
            });
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        } else {
            $query->where('status', '!=', 'Disposed');
        }

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function($q) use ($search) {
                $q->where('item_code', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhereHas('asset', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Item Code',
            'Nama Aset',
            'Serial Number',
            'Kategori',
            'Lokasi',
            'Tanggal Perolehan',
            'Harga Perolehan',
            'Umur Komersial (Bln)',
            'Nilai Sisa Komersial',
            'Nilai Buku Komersial',
            'Kelompok Fiskal',
            'Umur Fiskal (Bln)',
            'Nilai Buku Fiskal',
            'Selisih (K-F)',
            'Status'
        ];
    }

    public function map($item): array
    {
        $commValue = (float) $item->commercial_value;
        $fiscalValue = (float) $item->fiscal_value;
        $diff = $commValue - $fiscalValue;

        return [
            $item->item_code,
            $item->asset->name,
            $item->serial_number ?? '-',
            $item->asset->category->name ?? '-',
            $item->location->name ?? '-',
            $item->purchase_date->format('Y-m-d'),
            $item->purchase_price,
            $item->useful_life_months,
            $item->residual_value,
            round($commValue, 2),
            $item->effective_fiscal_group ?? '-',
            $item->fiscal_useful_life,
            round($fiscalValue, 2),
            round($diff, 2),
            $item->status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
