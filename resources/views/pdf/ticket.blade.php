<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NSE AGM Ticket</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1C1C1C; }
        .page { border: 1px solid #DEE2DD; padding: 22px; }
        .brand-bar { height: 6px; background: #2B6B2B; margin: -22px -22px 16px -22px; }
        .header { margin-bottom: 16px; }
        .title { font-size: 18px; font-weight: 700; color: #2B6B2B; }
        .subtitle { font-size: 11px; color: #555F54; margin-top: 2px; }
        .badge { display: inline-block; padding: 3px 8px; font-size: 10px; border-radius: 10px; border: 1px solid #CDE7CF; background: #E8F5E9; color: #2E7D32; }
        .grid { width: 100%; border-collapse: collapse; }
        .grid td { vertical-align: top; }
        .section { margin-bottom: 10px; }
        .label { font-weight: 700; color: #555F54; }
        .value { color: #1C1C1C; }
        .qr-box { border: 1px solid #DEE2DD; background: #F7F8F6; padding: 10px; text-align: center; }
        .qr-image { border: 1px solid #C8971F; background: #ffffff; padding: 6px; display: inline-block; }
        .qr-caption { margin-top: 6px; font-size: 10px; color: #555F54; }
        .token { margin-top: 6px; font-size: 9px; color: #8A948A; word-break: break-all; }
        .footer { margin-top: 16px; font-size: 10px; color: #555F54; border-top: 1px solid #DEE2DD; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="brand-bar"></div>

        <div class="header">
            <div class="title">NSE AGM Accreditation Ticket</div>
            <div class="subtitle">59th Annual General Meeting &amp; International Conference · November 1–4, 2026</div>
        </div>

        @php
            $status = strtolower($registration->payment_status ?? 'unknown');
            $badgeStyles = match ($status) {
                'paid' => 'background:#E8F5E9;color:#2E7D32;border:1px solid #CDE7CF;',
                'pending', 'initialized', 'processing' => 'background:#FFF8E1;color:#F57F17;border:1px solid #F0D9A7;',
                'refunded' => 'background:#F7F8F6;color:#555F54;border:1px solid #DEE2DD;',
                default => 'background:#E3F2FD;color:#1565C0;border:1px solid #B6D7F5;',
            };
        @endphp

        <table class="grid">
            <tr>
                <td style="width: 62%; padding-right: 16px;">
                    <div class="section">
                        <div class="label">Participant</div>
                        <div class="value">{{ $registration->name }}</div>
                    </div>
                    <div class="section">
                        <div class="label">Email</div>
                        <div class="value">{{ $registration->email }}</div>
                    </div>
                    <div class="section">
                        <div class="label">Payment Status</div>
                        <div class="value">
                            <span class="badge" style="{{ $badgeStyles }}">{{ ucfirst($status) }}</span>
                        </div>
                    </div>
                    <div class="section">
                        <div class="label">Ticket ID</div>
                        <div class="value">NSE59-{{ str_pad((string) $registration->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="section">
                        <div class="label">Accreditation</div>
                        <div class="value">Present this QR at the registration desk for check-in.</div>
                    </div>
                </td>
                <td style="width: 38%;">
                    <div class="qr-box">
                        @if(! empty($qrImage))
                            <div class="qr-image">
                                <img src="{{ $qrImage }}" alt="Accreditation QR Code" style="width: 160px; height: 160px;">
                            </div>
                        @else
                            <div class="qr-image" style="width: 160px; height: 160px; line-height: 160px;">
                                QR unavailable
                            </div>
                        @endif
                        <div class="qr-caption">Scan for accreditation</div>
                        <div class="token">{{ $registration->ticket_token ?? 'Not issued' }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer">
            Generated: {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>
</body>
</html>
