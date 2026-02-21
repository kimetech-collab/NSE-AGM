<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Finance Transactions Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        h1 { font-size: 16px; margin: 0 0 12px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Finance Transactions Report</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Reference</th>
                <th>Registrant</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $row['id'] }}</td>
                    <td>{{ $row['reference'] }}</td>
                    <td>{{ $row['registrant_name'] }}</td>
                    <td>{{ $row['registrant_email'] }}</td>
                    <td>{{ $row['amount'] }} {{ $row['currency'] }}</td>
                    <td>{{ $row['status'] }}</td>
                    <td>{{ $row['created_at'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No transactions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
