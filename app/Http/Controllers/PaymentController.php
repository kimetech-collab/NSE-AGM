<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\PaymentTransaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Services\PaymentService;
use App\Support\EventDates;

class PaymentController extends Controller
{
    // Show payment page
    public function show(Request $request)
    {
        $registrationId = (int)$request->input('registrationId');
        $registration = Registration::findOrFail($registrationId);
        $registrationWindowOpen = EventDates::registrationWindowOpen();

        return view('payment', [
            'registration' => $registration,
            'registrationWindowOpen' => $registrationWindowOpen,
            'registrationOpenAt' => EventDates::registrationOpenAt(),
            'registrationCloseAt' => EventDates::registrationCloseAt(),
        ]);
    }

    // Initiate payment: create transaction and return Paystack checkout URL
    public function initiate(Request $request)
    {
        if (! EventDates::registrationWindowOpen()) {
            $message = 'Payment initiation is currently unavailable. Registration window: ' .
                EventDates::registrationOpenAt()->format('M j, Y g:i A') . ' to ' .
                EventDates::registrationCloseAt()->format('M j, Y g:i A') . '.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 422);
            }

            return redirect()->back()->with('error', $message);
        }

        $registrationId = (int)$request->input('registration_id');
        $registration = Registration::findOrFail($registrationId);

        $reference = 'nseagm_' . Str::random(12);

        // Create payment transaction record
        $transaction = PaymentTransaction::create([
            'registration_id' => $registration->id,
            'provider' => 'paystack',
            'provider_reference' => $reference,
            'amount_cents' => $registration->price_cents,
            'currency' => $registration->currency,
            'status' => 'pending',
            'payload' => [
                'registration_id' => $registration->id,
                'email' => $registration->email,
                'name' => $registration->name,
            ],
        ]);

        // Call Paystack API to initialize transaction
        try {
            $paystackSecret = config('services.paystack.secret') ?? env('PAYSTACK_SECRET');
            $paystackResponse = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $paystackSecret,
                ])
                ->post('https://api.paystack.co/transaction/initialize', [
                    'amount' => $registration->price_cents,
                    'email' => $registration->email,
                    'reference' => $reference,
                    'callback_url' => route('payment.callback', [], true),
                    'metadata' => [
                        'registration_id' => $registration->id,
                        'name' => $registration->name,
                    ],
                ]);

            if ($paystackResponse->successful() && ($paystackResponse['status'] ?? false)) {
                $checkout_url = $paystackResponse['data']['authorization_url'] ?? null;
                
                if ($checkout_url) {
                    // Keep an allowed status value until callback/webhook confirms success.
                    $transaction->update([
                        'status' => 'pending',
                    ]);
                    
                    \Log::info('Payment initialized successfully', [
                        'reference' => $reference,
                        'registration_id' => $registration->id,
                    ]);
                    
                    return response()->json([
                        'success' => true,
                        'checkout_url' => $checkout_url,
                        'reference' => $reference,
                    ]);
                }
            }

            // Log failed response
            \Log::warning('Paystack API response not successful', [
                'status' => $paystackResponse->status(),
                'body' => $paystackResponse->json(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment initialization failed. Please try again.',
            ], 400);

        } catch (\Exception $e) {
            \Log::error('Paystack API Error', [
                'error' => $e->getMessage(),
                'reference' => $reference,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Handle successful payment redirect from Paystack
    public function handleCallback(Request $request)
    {
        $reference = $request->input('reference');
        if (!$reference) {
            return view('payment-error', ['message' => 'No reference provided.']);
        }

        // Webhook is source of truth; callback verifies as fallback if webhook delayed.
        $transaction = PaymentTransaction::where('provider_reference', $reference)->first();

        if ($transaction && $transaction->registration && $transaction->status === 'success') {
            return redirect('/ticket/' . $transaction->registration->ticket_token)
                ->with('success', 'Payment successful! Your ticket is ready.');
        }

        $verify = app(PaymentService::class)->verifyReference($reference);
        if (($verify['ok'] ?? false) && ! empty($verify['registration']) && $verify['registration']->ticket_token) {
            return redirect('/ticket/' . $verify['registration']->ticket_token)
                ->with('success', 'Payment successful! Your ticket is ready.');
        }

        return view('payment-pending', ['reference' => $reference]);
    }
}
