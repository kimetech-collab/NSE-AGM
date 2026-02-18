<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrations Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        h1 { font-size: 16px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f3f4f6; font-weight: bold; }
        .muted { color: #666; font-size: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>NSE Registrations Export</h1>
    <div class="muted">Generated: {{ now()->format('Y-m-d H:i:s') }}</div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Member</th>
                <th>Membership #</th>
                <th>Status</th>
                <th>Registered</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row['id'] }}</td>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['email'] }}</td>
                    <td>{{ $row['is_member'] }}</td>
                    <td>{{ $row['membership_number'] }}</td>
                    <td>{{ $row['status'] }}</td>
                    <td>{{ $row['registered_at'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
