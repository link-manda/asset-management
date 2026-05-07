<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; }
        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 11px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #f8f9fa; font-weight: bold; text-transform: uppercase; }
        .text-start { text-align: left; }
        .text-end { text-align: right; }
        .font-bold { font-weight: bold; }
        .bg-light { background-color: #fdfdfd; }
    </style>
</head>
<body>
    <h2>Asset Summary Report (By {{ ucfirst($groupBy) }})</h2>
    <p style="text-align: right; font-size: 10px;">Generated on: {{ now()->format('d M Y H:i:s') }}</p>
    <table>
        <thead>
            <tr>
                <th class="text-start">{{ ucfirst($groupBy) }}</th>
                <th>Total Units</th>
                <th>Available</th>
                <th>Deployed</th>
                <th>Maintenance</th>
                <th class="text-end">Investment Value</th>
                <th class="text-end">Book Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr class="bg-light">
                    <td class="text-start font-bold">{{ $row->label }}</td>
                    <td>{{ number_format($row->total_units) }}</td>
                    <td>{{ number_format($row->available_units) }}</td>
                    <td>{{ number_format($row->deployed_units) }}</td>
                    <td>{{ number_format($row->maintenance_units) }}</td>
                    <td class="text-end">Rp {{ number_format($row->total_investment, 0, ',', '.') }}</td>
                    <td class="text-end font-bold">Rp {{ number_format($row->current_book_value, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
