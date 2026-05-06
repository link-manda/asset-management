<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Assignment History Export</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        h2 { text-align: center; color: #111; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #f4f4f4; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        .text-center { text-align: center; }
        .deployed { color: #0284c7; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Asset Assignment History Report</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Asset Name</th>
                <th>Item Code</th>
                <th>Borrower</th>
                <th>Role</th>
                <th>Assigned Date</th>
                <th>Return Date</th>
                <th>Condition (Out)</th>
                <th>Condition (In)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $index => $assignment)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $assignment->item->asset->name ?? '-' }}<br><small>{{ $assignment->item->asset->asset_code ?? '' }}</small></td>
                    <td>{{ $assignment->item->item_code ?? '-' }}</td>
                    <td>{{ $assignment->user->name ?? '-' }}</td>
                    <td>{{ $assignment->user->role ?? '-' }}</td>
                    <td>{{ $assignment->assigned_date ? \Carbon\Carbon::parse($assignment->assigned_date)->format('d M Y') : '-' }}</td>
                    <td>
                        @if($assignment->return_date)
                            {{ \Carbon\Carbon::parse($assignment->return_date)->format('d M Y') }}
                        @else
                            <span class="deployed">Deployed</span>
                        @endif
                    </td>
                    <td>{{ $assignment->condition_on_checkout ?? '-' }}</td>
                    <td>{{ $assignment->condition_on_return ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>