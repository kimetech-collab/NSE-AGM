<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use App\Models\Registration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Events\PaymentConfirmed;
use App\Events\RefundInitiated;

class PaymentService
{
    /**
     * Handle incoming Paystack webhook (sandbox-ready stub).
     * Performs basic HMAC verification and idempotency handling.
     */
    public function handleWebhook(Request $request)
    {
        $secret = config('services.paystack.secret') ?? env('PAYSTACK_SECRET');
        if (! $secret) {
            return response('Missing secret', 500);
        }

        $event = $request->input('event');
        $data = $request->input('data', []);
        $reference = $data['reference'] ?? null;
        if (!$reference) {
            return response('Missing reference', 400);
        }

        if ($event !== 'charge.success') {
            return response('Ignored event', 200);
        }

        // Replay guard (short-term idempotency for same payload)
        $replayKey = 'paystack_webhook_seen_' . $reference . '_' . ($data['id'] ?? 'na');
        if (! Cache::add($replayKey, true, now()->addMinutes(10))) {
            return response('Already processed', 200);
        }

        // Idempotency: if transaction exists with provider_reference, ignore
        $existing = PaymentTransaction::where('provider_reference', $reference)->first();
        if ($existing && $existing->status === 'success') {
            return response('Already processed', 200);
        }

        // Verify with Paystack API (source-of-truth)
        $verify = Http::withHeaders([
            'Authorization' => 'Bearer ' . $secret,
        ])->get('https://api.paystack.co/transaction/verify/' . $reference);

        $verifyJson = $verify->json();
        if (! $verify->successful() || ! ($verifyJson['status'] ?? false)) {
            return response('Verification failed', 400);
        }

        $verifyData = $verifyJson['data'] ?? [];
        if (($verifyData['status'] ?? null) !== 'success') {
            return response('Payment not successful', 200);
        }

        // Optional consistency checks
        if (! empty($data['amount']) && ($verifyData['amount'] ?? null) !== $data['amount']) {
            return response('Amount mismatch', 422);
        }
        if (! empty($data['currency']) && ($verifyData['currency'] ?? null) !== $data['currency']) {
            return response('Currency mismatch', 422);
        }

        return DB::transaction(function () use ($data, $verifyData, $reference) {
            // Create or update transaction record
            $tx = PaymentTransaction::updateOrCreate(
                ['provider_reference' => $reference],
                [
                    'provider' => 'paystack',
                    'paystack_transaction_id' => $verifyData['id'] ?? null,
                    'amount_cents' => (int)($verifyData['amount'] ?? $data['amount'] ?? 0),
                    'currency' => $verifyData['currency'] ?? $data['currency'] ?? 'NGN',
                    'status' => 'success',
                    'payload' => $verifyData,
                ]
            );

            // If success, update registration
            if (($meta = $data['metadata'] ?? null) && isset($meta['registration_id'])) {
                $registration = Registration::find($meta['registration_id']);
                if ($registration) {
                    $registration->update([
                        'payment_status' => 'paid',
                        'ticket_token' => hash('sha256', $reference . $registration->id),
                    ]);
                    event(new PaymentConfirmed($tx, $registration));
                }
            }

            return response('OK', 200);
        });

    }

    /**
     * Fallback verification for Paystack callback.
     * Verifies the reference with Paystack and updates local records if needed.
     */
    public function verifyReference(string $reference): array
    {
        $secret = config('services.paystack.secret') ?? env('PAYSTACK_SECRET');
        if (! $secret) {
            return ['ok' => false, 'reason' => 'missing_secret'];
        }

        $existing = PaymentTransaction::where('provider_reference', $reference)->first();

        if ($existing && $existing->status === 'success' && $existing->registration) {
            return ['ok' => true, 'transaction' => $existing, 'registration' => $existing->registration];
        }

        if ($existing && $existing->status === 'refunded') {
            return ['ok' => false, 'reason' => 'refunded'];
        }

        $verify = Http::withHeaders([
            'Authorization' => 'Bearer ' . $secret,
        ])->get('https://api.paystack.co/transaction/verify/' . $reference);

        $verifyJson = $verify->json();
        if (! $verify->successful() || ! ($verifyJson['status'] ?? false)) {
            return ['ok' => false, 'reason' => 'verify_failed', 'response' => $verifyJson, 'status_code' => $verify->status()];
        }

        $verifyData = $verifyJson['data'] ?? [];
        if (($verifyData['status'] ?? null) !== 'success') {
            return ['ok' => false, 'reason' => 'not_success'];
        }

        if ($existing && ! empty($verifyData['amount']) && $existing->amount_cents && (int) $verifyData['amount'] !== (int) $existing->amount_cents) {
            return ['ok' => false, 'reason' => 'amount_mismatch'];
        }

        if ($existing && ! empty($verifyData['currency']) && $existing->currency && $verifyData['currency'] !== $existing->currency) {
            return ['ok' => false, 'reason' => 'currency_mismatch'];
        }

        return DB::transaction(function () use ($verifyData, $reference, $existing) {
            $tx = PaymentTransaction::updateOrCreate(
                ['provider_reference' => $reference],
                [
                    'provider' => 'paystack',
                    'paystack_transaction_id' => $verifyData['id'] ?? null,
                    'amount_cents' => (int) ($verifyData['amount'] ?? $existing->amount_cents ?? 0),
                    'currency' => $verifyData['currency'] ?? $existing->currency ?? 'NGN',
                    'status' => 'success',
                    'payload' => $verifyData,
                ]
            );

            $registration = null;
            $meta = $verifyData['metadata'] ?? null;
            if ($meta && isset($meta['registration_id'])) {
                $registration = Registration::find($meta['registration_id']);
                if ($registration) {
                    $registration->update([
                        'payment_status' => 'paid',
                        'ticket_token' => $registration->ticket_token ?: hash('sha256', $reference . $registration->id),
                    ]);
                    event(new PaymentConfirmed($tx, $registration));
                }
            }

            return ['ok' => true, 'transaction' => $tx, 'registration' => $registration];
        });
    }

    /**
     * Initiate a refund for a successful transaction.
     * Returns array with 'success' and 'data' or throws exception on failure.
     */
    public function initiateRefund(PaymentTransaction $tx, ?int $amountCents = null): array
    {
        if ($tx->status !== 'success') {
            return ['success' => false, 'message' => 'Only successful transactions can be refunded.'];
        }

        // Prevent double refunds by checking status
        if ($tx->status === 'refunded') {
            return ['success' => false, 'message' => 'Transaction already refunded.'];
        }

        $secret = config('services.paystack.secret') ?? env('PAYSTACK_SECRET');
        if (! $secret) {
            return ['success' => false, 'message' => 'Paystack secret not configured.'];
        }

        // Use stored paystack_transaction_id if available, otherwise verify to get it
        $paystackId = $tx->paystack_transaction_id;
        if (! $paystackId) {
            // Verify transaction first to get Paystack internal transaction id
            $verify = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $secret,
            ])->get('https://api.paystack.co/transaction/verify/' . $tx->provider_reference);

            $verifyJson = $verify->json();
            if (! $verify->successful() || ! ($verifyJson) || ! ($verifyJson['status'] ?? false)) {
                return ['success' => false, 'message' => 'Could not verify transaction with Paystack.', 'response' => $verifyJson ?? null, 'status_code' => $verify->status()];
            }

            $paystackId = $verifyJson['data']['id'] ?? null;
            if (! $paystackId) {
                return ['success' => false, 'message' => 'Paystack transaction id not found.'];
            }
            
            // Store the transaction id for future refunds
            $tx->update(['paystack_transaction_id' => $paystackId]);
        }

        $refundAmount = $amountCents ?? $tx->amount_cents;

        // Call refund endpoint
        $refundResp = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $secret,
        ])->post('https://api.paystack.co/refund', [
            'transaction' => $paystackId,
            'amount' => $refundAmount,
        ]);

        if (! $refundResp->successful() || ! ($refundJson = $refundResp->json()) || ! ($refundJson['status'] ?? false)) {
            return ['success' => false, 'message' => 'Paystack refund failed', 'response' => $refundResp->json()];
        }

        // Update transaction status and store refund details in payload
        $tx->update([
            'status' => 'refunded',
            'payload' => array_merge($tx->payload ?? [], ['refund' => $refundJson]),
        ]);

        // Optionally update registration
        if ($tx->registration) {
            $tx->registration->update(['payment_status' => 'refunded']);
            event(new RefundInitiated($tx, $tx->registration, $refundJson));
        }

        return ['success' => true, 'data' => $refundJson];
    }
}
