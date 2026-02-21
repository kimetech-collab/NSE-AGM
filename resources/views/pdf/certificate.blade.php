<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NSE Certificate</title>
    <style>
        @page { size: A4 landscape; margin: 10mm; }
        body { margin: 0; font-family: DejaVu Sans, sans-serif; color: #1F2937; }
        .page {
            height: 190mm;
            border: 3px solid #0F6A3E;
            padding: 8mm;
            box-sizing: border-box;
        }
        .inner {
            border: 1.5px solid #C8971F;
            height: 100%;
            padding: 7mm 9mm;
            box-sizing: border-box;
        }
        .brand {
            text-align: center;
            font-size: 10px;
            color: #4B5563;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }
        .font-english-gothic {
            font-family: 'Old English Text MT', 'Blackletter', 'Cloister Black', serif;
            letter-spacing: 0.4px;
        }
        .title {
            text-align: center;
            margin-top: 3mm;
            font-size: 30px;
            font-weight: 700;
            color: #0F6A3E;
            letter-spacing: 1px;
        }
        .subtitle {
            text-align: center;
            margin-top: 1.5mm;
            font-size: 11px;
            color: #374151;
        }
        .divider {
            width: 220px;
            height: 2px;
            background: #C8971F;
            margin: 3.5mm auto 0;
        }
        .photo-wrap {
            position: absolute;
            top: 14mm;
            right: 11mm;
            width: 24mm;
            text-align: center;
        }
        .profile-photo {
            width: 22mm;
            height: 22mm;
            object-fit: cover;
            border: 2px solid #C8971F;
            border-radius: 4px;
        }
        .content {
            text-align: center;
            margin-top: 7mm;
            padding: 0 12mm;
        }
        .presented {
            font-size: 11px;
            color: #4B5563;
            margin-bottom: 2mm;
        }
        .name {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
            margin: 0;
            line-height: 1.2;
        }
        .name-line {
            width: 70%;
            margin: 2.5mm auto 3.5mm;
            border-top: 1px solid #C8971F;
        }
        .body {
            font-size: 11px;
            line-height: 1.6;
            color: #374151;
            margin: 0 auto;
            max-width: 85%;
        }
        .meta {
            margin-top: 4.5mm;
            text-align: center;
            font-size: 10px;
            color: #4B5563;
        }
        .meta strong { color: #111827; }
        .signatures {
            width: 100%;
            margin-top: 6mm;
            border-collapse: collapse;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 9px;
            color: #4B5563;
        }
        .sig-line {
            width: 65%;
            border-top: 1px solid #9CA3AF;
            margin: 0 auto 2mm;
            height: 1px;
        }
        .footer {
            margin-top: 5mm;
            text-align: center;
            font-size: 9px;
            color: #6B7280;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="inner" style="position: relative;">
            @if($registration->profile_photo)
                <div class="photo-wrap">
                    <img src="{{ $registration->profilePhotoUrl() }}" alt="{{ $registration->name }}" class="profile-photo">
                </div>
            @endif

            <div class="brand font-english-gothic">Nigerian Society of Engineers</div>
            <div class="title">Certificate of Participation</div>
            <div class="subtitle">NSE 59th Annual General Meeting & International Conference {{ \App\Support\EventDates::get('event_end_at')->year }}</div>
            <div class="divider"></div>

            <div class="content">
                <p class="presented">This certificate is proudly presented to</p>
                <p class="name">{{ $registration->name }}</p>
                <div class="name-line"></div>

                <p class="body">
                    for successful participation in the NSE 59th AGM & International Conference,
                    having satisfied the attendance requirements for Continuing Professional Development (CPD).
                </p>

                <div class="meta">
                    <strong>Certificate ID:</strong> {{ $certificate->certificate_id }}
                    &nbsp;&nbsp;•&nbsp;&nbsp;
                    <strong>Date Issued:</strong> {{ optional($certificate->issued_at)->format('M d, Y') }}
                    @if(!empty($registration->membership_number))
                        &nbsp;&nbsp;•&nbsp;&nbsp;
                        <strong>Membership No:</strong> {{ $registration->membership_number }}
                    @endif
                </div>

                <table class="signatures">
                    <tr>
                        <td>
                            <div class="sig-line"></div>
                            Conference Chairman
                        </td>
                        <td>
                            <div class="sig-line"></div>
                            NSE President
                        </td>
                    </tr>
                </table>
            </div>

            <div class="footer">
                Verify authenticity at /verify/{{ $certificate->certificate_id }}
            </div>
        </div>
    </div>
</body>
</html>
