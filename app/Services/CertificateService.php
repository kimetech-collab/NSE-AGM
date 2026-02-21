<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Registration;
use App\Support\EventDates;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CertificateService
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

    public function eventEndAt(): \Carbon\CarbonInterface
    {
        return EventDates::get('event_end_at');
    }

    public function isEligible(Registration $registration): bool
    {
        if ($registration->attendance_status === 'physical') {
            return true;
        }

        if ($registration->attendance_status === 'virtual') {
            return true;
        }

        return $registration->attendance_seconds >= 600;
    }

    public function canIssue(Registration $registration): bool
    {
        return now()->greaterThanOrEqualTo($this->eventEndAt()) && $this->isEligible($registration);
    }

    public function generateCertificate(Registration $registration, bool $force = false): ?Certificate
    {
        if ($registration->certificate) {
            return $registration->certificate;
        }

        if (! $force && ! $this->canIssue($registration)) {
            return null;
        }

        $certificateId = $this->generateCertificateId();

        $cert = Certificate::create([
            'registration_id' => $registration->id,
            'certificate_id' => $certificateId,
            'status' => 'issued',
            'issued_at' => now(),
        ]);

        if (Schema::hasTable('audit_logs')) {
            try {
                $this->audit->logCertificate('issued', $cert->id, [
                    'registration_id' => $registration->id,
                    'certificate_id' => $certificateId,
                    'forced' => $force,
                    'issued_via' => $force ? 'super_admin_manual' : 'automatic_flow',
                ]);
            } catch (\Throwable $e) {
                Log::error('Audit log failed for certificate issue', ['error' => $e->getMessage()]);
            }
        }

        return $cert;
    }

    public function revoke(Certificate $certificate, ?string $reason = null): Certificate
    {
        $certificate->update([
            'status' => 'revoked',
            'revoked_at' => now(),
            'metadata' => array_merge($certificate->metadata ?? [], [
                'revoked_reason' => $reason,
            ]),
        ]);

        if (Schema::hasTable('audit_logs')) {
            try {
                $this->audit->logCertificate('revoked', $certificate->id, [
                    'registration_id' => $certificate->registration_id,
                    'certificate_id' => $certificate->certificate_id,
                    'reason' => $reason,
                ]);
            } catch (\Throwable $e) {
                Log::error('Audit log failed for certificate revoke', ['error' => $e->getMessage()]);
            }
        }

        return $certificate;
    }

    public function renderPdf(Certificate $certificate): string
    {
        $registration = $certificate->registration;

        return Pdf::loadView('pdf.certificate', [
            'certificate' => $certificate,
            'registration' => $registration,
        ])->setPaper('a4', 'landscape')->output();
    }

    protected function generateCertificateId(): string
    {
        $eventYear = EventDates::get('event_start_at')->format('Y');

        do {
            $code = 'NSE59-' . $eventYear . '-' . Str::upper(Str::random(6));
        } while (Certificate::where('certificate_id', $code)->exists());

        return $code;
    }
}
