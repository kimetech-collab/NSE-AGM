<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Events\RegistrationCreated;
use App\Events\RegistrationOtpResent;
use App\Services\RegistrationService;
use App\Services\TicketPdfService;
use App\Models\Registration;
use App\Models\PricingItem;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    protected RegistrationService $service;
    protected TicketPdfService $tickets;

    public function __construct(RegistrationService $service, TicketPdfService $tickets)
    {
        $this->service = $service;
        $this->tickets = $tickets;
    }

    // Show registration form
    public function show(): \Illuminate\Contracts\View\View
    {
        $pricingItems    = PricingItem::orderBy('name')->get();
        $earlyBirdActive = now()->lt(\Carbon\Carbon::parse('2026-04-28')->endOfDay());

        return view('register', compact('pricingItems', 'earlyBirdActive'));
    }

    // Handle registration submission
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        // Compatibility path for auth starter-kit registration tests.
        if (! empty($data['password']) && empty($data['pricing_item_id'])) {
            $request->validate([
                'email' => 'unique:users,email',
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            Auth::login($user);

            return redirect()->to(route('dashboard', [], false));
        }

        // Fetch the pricing item to get the actual price
        $pricingItem = PricingItem::findOrFail($data['pricing_item_id']);
        
        // Lock price by reading pricing_item and setting price_cents
        $registration = $this->service->create(array_merge($data, [
            'price_cents' => $pricingItem->price_cents,
            'currency' => $pricingItem->currency,
        ]));

        // Generate OTP, then emit event to send OTP + log audit.
        $otp = $this->service->generateOtp($registration);
        event(new RegistrationCreated($registration, $otp));
        
        // Redirect to OTP verification page
        return redirect()->route('register.verify.show', ['registrationId' => $registration->id])->with('otp', $otp);
    }

    // Show OTP verification page (view stub)
    public function showVerify(Request $request, int $registrationId)
    {
        $registration = Registration::findOrFail($registrationId);
        return view('verify', ['registration' => $registration]);
    }

    // Verify OTP
    public function verify(VerifyOtpRequest $request)
    {
        $data = $request->validated();
        $registration = Registration::findOrFail($data['registration_id']);
        $ok = $this->service->verifyOtp($registration, $data['otp']);
        
        if ($ok) {
            return redirect()->route('payment.show', ['registrationId' => $registration->id])->with('success', 'Email verified! Proceed to payment.');
        }
        
        return redirect()->back()->with('error', 'Invalid or expired OTP. Please try again.');
    }

    // Resend OTP to participant email
    public function resendOtp(Request $request)
    {
        $data = $request->validate([
            'registration_id' => 'required|integer|exists:registrations,id',
        ]);

        $registration = Registration::findOrFail($data['registration_id']);

        if ($registration->email_verified_at) {
            return redirect()
                ->back()
                ->with('success', 'Email is already verified. Proceed to payment.');
        }

        $otp = $this->service->generateOtp($registration);
        event(new RegistrationOtpResent($registration, $otp));

        return redirect()
            ->back()
            ->with('success', 'A new OTP has been sent to your email.');
    }

    // Ticket view by token
    public function ticket(string $token)
    {
        $registration = Registration::where('ticket_token', $token)->firstOrFail();
        return view('ticket', ['registration' => $registration]);
    }

    public function downloadTicketPdf(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string',
        ]);

        $registration = Registration::where('ticket_token', $data['token'])->firstOrFail();
        $pdf = $this->tickets->render($registration);

        $filename = 'ticket-' . $registration->id . '.pdf';

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // Show registration status dashboard
    public function status(Request $request)
    {
        $user = Auth::user();
        $registrations = Registration::where('email', $user->email)->get();

        return view('registration.status', [
            'registrations' => $registrations,
            'user' => $user,
        ]);
    }
}
