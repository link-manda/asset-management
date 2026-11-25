<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #999; }
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .status-Available { background-color: #dcfce7; color: #166534; }
        .status-Deployed { background-color: #e0f2fe; color: #075985; }
        .status-Maintenance { background-color: #fef9c3; color: #854d0e; }
        .status-Disposed { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <h2>General Asset Report</h2>
        <p>Generated on: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Item Code</th>
                <th>Asset Name</th>
                <th>SN</th>
                <th>Category</th>
                <th>Location</th>
                <th>Status</th>
                <th class="text-end">Book Value</th>
            </tr>
        </thead>
        <tbody>
            @php $totalValue = 0; @endphp
            @foreach($items as $index => $item)
                @php $totalValue += $item->current_value; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="font-family: monospace;">{{ $item->item_code }}</td>
                    <td>{{ $item->asset->name }}</td>
                    <td>{{ $item->serial_number ?? '-' }}</td>
                    <td>{{ $item->asset->category->name ?? '-' }}</td>
                    <td>{{ $item->location->name ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge status-{{ $item->status }}">{{ $item->status }}</span>
                    </td>
                    <td class="text-end">Rp {{ number_format($item->current_value, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="7" class="text-end">TOTAL BOOK VALUE</td>
                <td class="text-end">Rp {{ number_format($totalValue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Page 1 of 1 - Asset Management System
    </div>
</body>
</html>
