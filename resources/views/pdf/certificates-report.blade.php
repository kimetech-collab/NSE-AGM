<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificates Issuance Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        h1 { font-size: 16px; margin: 0 0 12px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Certificates Issuance Report</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Certificate ID</th>
                <th>Registrant</th>
                <th>Email</th>
                <th>Status</th>
                <th>Issued At</th>
                <th>Revoked At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $row['id'] }}</td>
                    <td>{{ $row['certificate_id'] }}</td>
                    <td>{{ $row['registrant_name'] }}</td>
                    <td>{{ $row['registrant_email'] }}</td>
                    <td>{{ $row['status'] }}</td>
                    <td>{{ $row['issued_at'] }}</td>
                    <td>{{ $row['revoked_at'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No certificates found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
