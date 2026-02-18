<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\PaymentTransaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    // Show payment page
    public function show(Request $request)
    {
        $registrationId = (int)$request->input('registrationId');
        $registration = Registration::findOrFail($registrationId);
        return view('payment', ['registration' => $registration]);
    }

    // Initiate payment: create transaction and return Paystack checkout URL
    public function initiate(Request $request)
    {
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
                $paystack_transaction_id = $paystackResponse['data']['id'] ?? null;
                
                if ($checkout_url && $paystack_transaction_id) {
                    // Update transaction with Paystack transaction ID
                    $transaction->update(['paystack_transaction_id' => $paystack_transaction_id]);
                    
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

        // Verify transaction with Paystack API
        $paystackSecret = config('services.paystack.secret') ?? env('PAYSTACK_SECRET');
        $response = Http::timeout(10)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $paystackSecret,
            ])
            ->get('https://api.paystack.co/transaction/verify/' . $reference);

        if ($response->successful() && $response['status'] && $response['data']['status'] === 'success') {
            // Find transaction and registration to mark as paid
            $transaction = PaymentTransaction::where('provider_reference', $reference)->first();
            if ($transaction && $transaction->registration) {
                $paystack_transaction_id = $response['data']['id'] ?? null;
                $update_data = ['status' => 'success'];
                
                if ($paystack_transaction_id) {
                    $update_data['paystack_transaction_id'] = $paystack_transaction_id;
                }
                
                $transaction->update($update_data);
                $transaction->registration->update([
                    'payment_status' => 'paid',
                    'ticket_token' => hash('sha256', $reference . $transaction->registration->id),
                ]);
                return redirect('/ticket/' . $transaction->registration->ticket_token)
                    ->with('success', 'Payment successful! Your ticket is ready.');
            }
        }

        return view('payment-error', ['message' => 'Payment verification failed.']);
    }
}
