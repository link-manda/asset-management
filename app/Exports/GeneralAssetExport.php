<?php

namespace App\Exports;

use App\Models\AssetItem;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralAssetExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return AssetItem::with(['asset.category', 'location'])
            ->when($this->request->category_id, function ($q) {
                $q->whereHas('asset', function ($sq) {
                    $sq->where('category_id', $this->request->category_id);
                });
            })
            ->when($this->request->location_id, function ($q) {
                $q->where('location_id', $this->request->location_id);
            })
            ->when($this->request->status, function ($q) {
                $q->where('status', $this->request->status);
            })
            ->when($this->request->search, function ($q) {
                $q->where('item_code', 'like', '%' . $this->request->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $this->request->search . '%')
                  ->orWhereHas('asset', function ($sq) {
                      $sq->where('name', 'like', '%' . $this->request->search . '%');
                  });
            });
    }

    public function headings(): array
    {
        return [
            'Item Code',
            'Asset Name',
            'Category',
            'Brand',
            'Serial Number',
            'Location',
            'Status',
            'Condition',
            'Purchase Date',
            'Purchase Price',
            'Book Value (Current)',
        ];
    }

    public function map($item): array
    {
        return [
            $item->item_code,
            $item->asset->name,
            $item->asset->category->name ?? '-',
            $item->asset->brand ?? '-',
            $item->serial_number,
            $item->location->name ?? '-',
            $item->status,
            $item->condition,
            $item->purchase_date ? $item->purchase_date->format('Y-m-d') : '-',
            $item->purchase_price,
            $item->current_value,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
