<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetSummaryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;
    protected $groupBy;

    public function __construct($data, $groupBy)
    {
        $this->data = $data;
        $this->groupBy = $groupBy;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            ucfirst($this->groupBy),
            'Total Units',
            'Available',
            'Deployed',
            'Maintenance',
            'Investment Value',
            'Current Book Value',
        ];
    }

    public function map($row): array
    {
        return [
            $row->label,
            $row->total_units,
            $row->available_units,
            $row->deployed_units,
            $row->maintenance_units,
            $row->total_investment,
            $row->current_book_value,
        ];
    }
}
