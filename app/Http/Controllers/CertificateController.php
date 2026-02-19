<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Registration;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CertificateController extends Controller
{
    public function verifyLookup(Request $request)
    {
        if (! Schema::hasTable('certificates')) {
            return view('verify-certificate', [
                'certificate' => null,
                'error' => 'Certificate module is not initialized yet.',
            ]);
        }

        $certificate = null;
        $lookup = trim((string) $request->input('certificate_id', ''));
        if ($lookup !== '') {
            $certificate = Certificate::where('certificate_id', $lookup)->first();
        }

        return view('verify-certificate', [
            'certificate' => $certificate,
            'lookup' => $lookup,
        ]);
    }

    public function show(Request $request, CertificateService $certificates)
    {
        $token = $request->input('token');
        if (! $token) {
            return view('certificate', ['error' => 'Missing access token.']);
        }

        $registration = Registration::where('ticket_token', $token)->first();
        if (! $registration) {
            return view('certificate', ['error' => 'Invalid access token.']);
        }

        $certificate = null;
        if (Schema::hasTable('certificates')) {
            $certificate = $registration->certificate;
        }
        $eligible = $certificates->canIssue($registration);

        return view('certificate', [
            'registration' => $registration,
            'certificate' => $certificate,
            'eligible' => $eligible,
            'eventEndAt' => $certificates->eventEndAt(),
        ]);
    }

    public function download(Request $request, CertificateService $certificates)
    {
        $data = $request->validate([
            'token' => 'required|string',
        ]);

        if (! Schema::hasTable('certificates')) {
            return redirect()->back()->with('error', 'Certificate table not found. Run migrations.');
        }

        $registration = Registration::where('ticket_token', $data['token'])->firstOrFail();
        $certificate = $registration->certificate ?: $certificates->generateCertificate($registration);

        if (! $certificate) {
            return redirect()->back()->with('error', 'Certificate not available yet.');
        }

        if ($certificate->status === 'revoked') {
            return redirect()->back()->with('error', 'Certificate has been revoked.');
        }

        $pdf = $certificates->renderPdf($certificate);
        $filename = 'certificate-' . $certificate->certificate_id . '.pdf';

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function verify(string $certificateId)
    {
        if (! Schema::hasTable('certificates')) {
            return view('verify-certificate', [
                'certificate' => null,
                'error' => 'Certificate module is not initialized yet.',
                'lookup' => $certificateId,
            ]);
        }

        $certificate = Certificate::where('certificate_id', $certificateId)->first();

        return view('verify-certificate', [
            'certificate' => $certificate,
            'lookup' => $certificateId,
        ]);
    }
}
