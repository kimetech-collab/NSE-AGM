<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NSE Certificate</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1C1C1C; }
        .page { border: 2px solid #2B6B2B; padding: 32px; }
        .title { font-size: 22px; font-weight: 700; color: #2B6B2B; text-align: center; }
        .subtitle { text-align: center; font-size: 12px; color: #555F54; margin-top: 6px; }
        .name { margin-top: 24px; font-size: 20px; font-weight: 700; text-align: center; }
        .body { margin-top: 18px; font-size: 12px; line-height: 1.6; text-align: center; }
        .footer { margin-top: 28px; font-size: 10px; color: #555F54; text-align: center; }
        .cert { margin-top: 18px; text-align: center; font-size: 11px; color: #8A948A; }
        .line { width: 120px; height: 2px; background: #C8971F; margin: 14px auto; }
    </style>
</head>
<body>
    <div class="page">
        <div class="title">Certificate of Participation</div>
        <div class="subtitle">NSE 59th Annual General Meeting & International Conference 2026</div>
        <div class="line"></div>

        <div class="body">This is to certify that</div>
        <div class="name">{{ $registration->name }}</div>
        <div class="body">
            has successfully participated in the NSE 59th AGM & International Conference
            and satisfied the attendance requirement for CPD certification.
        </div>

        <div class="cert">Certificate ID: {{ $certificate->certificate_id }}</div>
        <div class="footer">
            Issued: {{ optional($certificate->issued_at)->format('M d, Y') }} · Verified at /verify/{{ $certificate->certificate_id }}
        </div>
    </div>
</body>
</html>
