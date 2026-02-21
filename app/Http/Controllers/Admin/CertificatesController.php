<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Registration;
use App\Services\AuditService;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Schema;

class CertificatesController extends Controller
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

    public function index(Request $request, CertificateService $certificates)
    {
        $query = Certificate::with('registration')->orderBy('created_at', 'desc');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($q = trim((string) $request->input('q'))) {
            $query->where(function ($builder) use ($q) {
                $builder->where('certificate_id', 'like', "%{$q}%")
                    ->orWhereHas('registration', function ($r) use ($q) {
                        $r->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        $items = $query->paginate(25)->withQueryString();

        $eligible = Registration::where(function ($q) {
                $q->where('attendance_status', 'physical')
                    ->orWhere('attendance_status', 'virtual')
                    ->orWhere('attendance_seconds', '>=', 600);
            })
            ->whereDoesntHave('certificate')
            ->count();

        return view('admin.certificates.index', [
            'certificates' => $items,
            'eligibleCount' => $eligible,
            'eventEndAt' => $certificates->eventEndAt(),
        ]);
    }

    public function generateBatch(CertificateService $certificates)
    {
        $eligible = Registration::where(function ($q) {
                $q->where('attendance_status', 'physical')
                    ->orWhere('attendance_status', 'virtual')
                    ->orWhere('attendance_seconds', '>=', 600);
            })
            ->whereDoesntHave('certificate')
            ->get();

        $created = 0;
        foreach ($eligible as $registration) {
            $cert = $certificates->generateCertificate($registration);
            if ($cert) {
                $created++;
            }
        }

        return redirect()->back()->with('success', "Generated {$created} certificates.");
    }

    public function issue(Request $request, CertificateService $certificates)
    {
        $data = $request->validate([
            'registration_id' => 'required|integer|exists:registrations,id',
        ]);

        $registration = Registration::findOrFail($data['registration_id']);
        $existing = $registration->certificate;
        $certificate = $certificates->generateCertificate($registration, true);

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('certificate.issue_requested', 'Registration', $registration->id, [
                'forced' => true,
                'already_issued' => (bool) $existing,
                'certificate_id' => $certificate?->certificate_id,
            ]);
        }

        return redirect()->back()->with('success', 'Certificate issued.');
    }

    public function revoke(Request $request, Certificate $certificate, CertificateService $certificates)
    {
        $data = $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        $certificates->revoke($certificate, $data['reason'] ?? null);

        return redirect()->back()->with('success', 'Certificate revoked.');
    }

    public function export(Request $request)
    {
        $query = Certificate::with('registration')->orderBy('created_at', 'desc');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($q = trim((string) $request->input('q'))) {
            $query->where(function ($builder) use ($q) {
                $builder->where('certificate_id', 'like', "%{$q}%")
                    ->orWhereHas('registration', function ($r) use ($q) {
                        $r->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        $rows = $query->limit(5000)->get()->map(function (Certificate $c) {
            return [
                'id' => $c->id,
                'certificate_id' => $c->certificate_id,
                'registrant_name' => $c->registration->name ?? '',
                'registrant_email' => $c->registration->email ?? '',
                'status' => $c->status,
                'issued_at' => optional($c->issued_at)->format('Y-m-d H:i:s'),
                'revoked_at' => optional($c->revoked_at)->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        $format = $request->input('format', 'csv');
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('pdf.certificates-report', ['rows' => $rows]);
            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="certificates-report.pdf"',
            ]);
        }

        $csv = "id,certificate_id,registrant_name,registrant_email,status,issued_at,revoked_at\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map(fn ($v) => '"'.str_replace('"', '""', (string) $v).'"', $row))."\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="certificates-report.csv"',
        ]);
    }
}
