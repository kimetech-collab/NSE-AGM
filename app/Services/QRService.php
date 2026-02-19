<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\QrScan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class QRService
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

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

        if (Schema::hasTable('qr_scans')) {
            $firstScan = QrScan::where('registration_id', $registration->id)
                ->where('status', 'valid')
                ->orderBy('scanned_at', 'asc')
                ->first();

            if ($firstScan) {
                return [
                    'ok' => false,
                    'status' => 'already_checked_in',
                    'message' => 'Already checked in.',
                    'registration' => $registration,
                    'first_scan_at' => $firstScan->scanned_at,
                ];
            }
        }

        return ['ok' => true, 'status' => 'valid', 'registration' => $registration];
    }

    public function logScan(string $token, array $result, array $metadata = [], ?string $scannedAt = null): ?QrScan
    {
        if (empty($result['registration']) && $result['status'] !== 'invalid') {
            return null;
        }

        $scanTime = now();
        if ($scannedAt) {
            try {
                $scanTime = Carbon::parse($scannedAt);
            } catch (\Throwable $e) {
                $scanTime = now();
            }
        }
        $registrationId = $result['registration']->id ?? null;
        if (! $registrationId) {
            $scan = QrScan::create([
                'registration_id' => null,
                'scanned_by' => Auth::id(),
                'status' => 'invalid',
                'token' => $token,
                'metadata' => $metadata,
                'scanned_at' => $scanTime,
                'ip_address' => request()->ip(),
            ]);

            if (Schema::hasTable('audit_logs')) {
                try {
                    $this->audit->log('accreditation.scan', 'Registration', 0, [
                        'token' => $token,
                        'status' => 'invalid',
                        'metadata' => $metadata,
                        'scanned_at' => $scanTime->toIso8601String(),
                    ], null, null, false, 'Invalid token');
                } catch (\Throwable $e) {
                    \Log::error('Audit log failed for accreditation scan', ['error' => $e->getMessage()]);
                }
            }

            return $scan;
        }

        if (($result['status'] ?? null) === 'valid') {
            $registration = Registration::find($registrationId);
            if ($registration && $registration->attendance_status !== 'physical') {
                $registration->update([
                    'attendance_status' => 'physical',
                    'attendance_eligible_at' => $registration->attendance_eligible_at ?? now(),
                    'attendance_last_at' => now(),
                ]);
            }
        }

        $scan = QrScan::create([
            'registration_id' => $registrationId ?? 0,
            'scanned_by' => Auth::id(),
            'status' => $result['status'],
            'token' => $token,
            'metadata' => $metadata,
            'scanned_at' => $scanTime,
            'ip_address' => request()->ip(),
        ]);

        if (Schema::hasTable('audit_logs')) {
            try {
                $this->audit->log('accreditation.scan', 'Registration', $registrationId, [
                    'token' => $token,
                    'status' => $result['status'],
                    'metadata' => $metadata,
                    'scanned_at' => $scanTime->toIso8601String(),
                ], null, null, $result['ok'] ?? false);
            } catch (\Throwable $e) {
                \Log::error('Audit log failed for accreditation scan', ['error' => $e->getMessage()]);
            }
        }

        return $scan;
    }
}
