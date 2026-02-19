<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\CheckIn;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QRScannerController extends Controller
{
    protected AttendanceService $attendance;

    public function __construct(AttendanceService $attendance)
    {
        $this->attendance = $attendance;
    }

    // Display full-screen QR scanner
    public function show(Request $request)
    {
        $purpose = $request->input('purpose', 'check-in'); // check-in, verify, ticket, etc.
        $token = $request->input('token');

        // Validate scanner access
        if ($purpose === 'check-in' && !$token) {
            return view('qr-scanner', ['error' => 'Missing access token']);
        }

        return view('qr-scanner', [
            'purpose' => $purpose,
            'token' => $token,
        ]);
    }

    // Process scanned QR code
    public function process(Request $request)
    {
        $data = $request->validate([
            'qr_data' => 'required|string|max:500',
            'purpose' => 'required|string|in:check-in,verify,ticket,registration',
            'token' => 'nullable|string',
        ]);

        try {
            $purpose = $data['purpose'];
            $qrData = $data['qr_data'];

            // Route to appropriate handler
            return match ($purpose) {
                'check-in' => $this->handleCheckIn($qrData, $data['token']),
                'verify' => $this->handleVerify($qrData),
                'ticket' => $this->handleTicket($qrData),
                'registration' => $this->handleRegistration($qrData),
                default => $this->errorResponse('Invalid purpose'),
            };
        } catch (\Exception $e) {
            \Log::error('QR Scanner Error', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);

            return $this->errorResponse('Error processing QR code: ' . $e->getMessage());
        }
    }

    protected function handleCheckIn(string $qrData, ?string $token)
    {
        // Extract registration ID or email from QR code
        // Format: ticket-{id}-{token} or email@domain.com
        
        if (filter_var($qrData, FILTER_VALIDATE_EMAIL)) {
            $registration = Registration::where('email', $qrData)
                ->where('payment_status', 'paid')
                ->latest()
                ->first();
        } else {
            // Try to parse ticket token format
            $registration = Registration::where('ticket_token', $qrData)
                ->where('payment_status', 'paid')
                ->first();
        }

        if (!$registration) {
            return $this->errorResponse('Registration not found or not paid', 'Invalid QR code');
        }

        // Check if already checked in today
        $today = Carbon::now()->toDateString();
        $existingCheckIn = CheckIn::where('registration_id', $registration->id)
            ->whereDate('created_at', $today)
            ->first();

        if ($existingCheckIn) {
            return $this->successResponse(
                'Already checked in',
                'Check-in recorded at ' . $existingCheckIn->created_at->format('H:i:s'),
                $registration
            );
        }

        // Create check-in record
        $checkIn = CheckIn::create([
            'registration_id' => $registration->id,
            'check_in_time' => now(),
            'source' => 'qr_scan',
        ]);

        // Start attendance session if streaming
        if ($token) {
            $this->attendance->startSession($registration, $token, 'qr_check_in', [
                'source' => 'qr_scan',
            ]);
        }

        return $this->successResponse(
            'Check-in successful',
            $registration->participant_name . ' checked in at ' . $checkIn->check_in_time->format('H:i:s'),
            $registration
        );
    }

    protected function handleVerify(string $qrData)
    {
        // Verify certificate or registration
        $registration = Registration::where('ticket_token', $qrData)
            ->orWhere('id', $qrData)
            ->first();

        if (!$registration) {
            return $this->errorResponse('Registration not found', 'No matching record');
        }

        $details = sprintf(
            '%s | %s | Status: %s',
            $registration->participant_name,
            $registration->email,
            ucfirst($registration->payment_status)
        );

        return $this->successResponse(
            'Registration verified',
            $details,
            $registration
        );
    }

    protected function handleTicket(string $qrData)
    {
        $registration = Registration::where('ticket_token', $qrData)
            ->where('payment_status', 'paid')
            ->first();

        if (!$registration) {
            return $this->errorResponse('Ticket not found or not paid', 'Invalid ticket');
        }

        return $this->successResponse(
            'Ticket verified',
            'Valid ticket for ' . $registration->participant_name,
            $registration,
            route('ticket.view', ['token' => $qrData])
        );
    }

    protected function handleRegistration(string $qrData)
    {
        $registration = Registration::where('ticket_token', $qrData)
            ->first();

        if (!$registration) {
            return $this->errorResponse('Registration not found', 'No matching record');
        }

        return $this->successResponse(
            'Registration found',
            'ID: ' . $registration->id . ' | ' . $registration->participant_name,
            $registration
        );
    }

    protected function successResponse($message, $details = '', $registration = null, $redirectUrl = null)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'details' => $details,
            'registration_id' => $registration?->id,
            'redirect_url' => $redirectUrl,
        ]);
    }

    protected function errorResponse($message, $details = '')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'details' => $details,
        ], 422);
    }
}
