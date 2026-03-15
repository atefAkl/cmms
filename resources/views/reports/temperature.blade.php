<!DOCTYPE html>
<html>
<head>
    <title>Temperature Trends Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Temperature Trends Report</h2>
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Room</th>
                <th>Temperature (&deg;C)</th>
                <th>Date</th>
                <th>Inspector</th>
            </tr>
        </thead>
        <tbody>
            @foreach($readings as $reading)
                <tr>
                    <td>{{ $reading->room->name ?? 'N/A' }}</td>
                    <td>{{ $reading->temperature }}</td>
                    <td>{{ $reading->recorded_at }}</td>
                    <td>{{ $reading->recorder->name ?? 'System' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
