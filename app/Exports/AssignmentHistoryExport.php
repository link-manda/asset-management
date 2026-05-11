<?php

namespace App\Exports;

use App\Models\AssetAssignment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssignmentHistoryExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected $searchTerm;

    public function __construct($searchTerm = null)
    {
        $this->searchTerm = $searchTerm;
    }

    public function query()
    {
        $query = AssetAssignment::with(['item.asset', 'user']);

        if (!empty($this->searchTerm)) {
            $query->search($this->searchTerm);
        }
        
        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'Asset Name',
            'Item Code (Barcode)',
            'Master Code',
            'Borrower / User Name',
            'Role / Jabatan',
            'Assigned Date (Checkout)',
            'Return Date (Checkin)',
            'Condition (OUT)',
            'Condition (IN)',
        ];
    }

    public function map($assignment): array
    {
        return [
            $assignment->item->asset->name ?? '-',
            $assignment->item->item_code ?? '-',
            $assignment->item->asset->asset_code ?? '-',
            $assignment->user->name ?? '-',
            $assignment->user->getRoleNames()->first() ?? '-',
            $assignment->assigned_date ? $assignment->assigned_date->format('Y-m-d') : '-',
            $assignment->return_date ? $assignment->return_date->format('Y-m-d') : 'Currently Deployed',
            $assignment->condition_on_checkout ?? '-',
            $assignment->condition_on_return ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
