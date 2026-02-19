<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Registration;
use App\Services\CertificateService;
use Illuminate\Http\Request;

class CertificatesController extends Controller
{
    public function index(Request $request, CertificateService $certificates)
    {
        $query = Certificate::with('registration')->orderBy('created_at', 'desc');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
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
        $certificates->generateCertificate($registration, true);

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
}
