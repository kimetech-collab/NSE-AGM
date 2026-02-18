<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\QrScan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QRService
{
    public function generateToken(Registration $registration): string
    {
        if ($registration->ticket_token) {
            return $registration->ticket_token;
        }

        $token = hash('sha256', Str::random(40));
        $registration->update(['ticket_token' => $token]);

        return $token;
    }

    public function validateToken(string $token): array
    {
        $registration = Registration::where('ticket_token', $token)->first();

        if (! $registration) {
            return ['ok' => false, 'status' => 'invalid', 'message' => 'Invalid QR token.'];
        }

        if ($registration->payment_status === 'refunded') {
            return ['ok' => false, 'status' => 'refunded', 'message' => 'Payment was refunded.'];
        }

        if ($registration->payment_status !== 'paid') {
            return ['ok' => false, 'status' => 'unpaid', 'message' => 'Payment not completed.'];
        }

        return ['ok' => true, 'status' => 'valid', 'registration' => $registration];
    }

    public function logScan(string $token, array $result, array $metadata = []): ?QrScan
    {
        if (empty($result['registration']) && $result['status'] !== 'invalid') {
            return null;
        }

        $registrationId = $result['registration']->id ?? null;
        if (! $registrationId) {
            return QrScan::create([
                'registration_id' => null,
                'scanned_by' => Auth::id(),
                'status' => 'invalid',
                'token' => $token,
                'metadata' => $metadata,
                'scanned_at' => now(),
                'ip_address' => request()->ip(),
            ]);
        }

        return QrScan::create([
            'registration_id' => $registrationId ?? 0,
            'scanned_by' => Auth::id(),
            'status' => $result['status'],
            'token' => $token,
            'metadata' => $metadata,
            'scanned_at' => now(),
            'ip_address' => request()->ip(),
        ]);
    }
}
