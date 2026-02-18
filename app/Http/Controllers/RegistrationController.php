<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Services\RegistrationService;
use App\Mail\RegistrationOtp as RegistrationOtpMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Registration;
use App\Models\PricingItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    protected RegistrationService $service;

    public function __construct(RegistrationService $service)
    {
        $this->service = $service;
    }

    // Show registration form (blade view can be created later)
    public function show(): \Illuminate\Contracts\View\View
    {
        return view('register');
    }

    // Handle registration submission
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        // Fetch the pricing item to get the actual price
        $pricingItem = PricingItem::findOrFail($data['pricing_item_id']);
        
        // Lock price by reading pricing_item and setting price_cents
        $registration = $this->service->create(array_merge($data, [
            'price_cents' => $pricingItem->price_cents,
            'currency' => $pricingItem->currency,
        ]));

        // generate OTP and queue email
        $otp = $this->service->generateOtp($registration);
        Mail::to($registration->email)->queue(new RegistrationOtpMail($registration, $otp));
        
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

    // Ticket view by token
    public function ticket(string $token)
    {
        $registration = Registration::where('ticket_token', $token)->firstOrFail();
        return view('ticket', ['registration' => $registration]);
    }
}
