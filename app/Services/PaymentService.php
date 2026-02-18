<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use App\Models\Registration;

class PaymentService
{
    /**
     * Handle incoming Paystack webhook (sandbox-ready stub).
     * Performs basic HMAC verification and idempotency handling.
     */
    public function handleWebhook(Request $request)
    {
        // Basic verification: HMAC signature header (X-Paystack-Signature)
        $signature = $request->header('X-Paystack-Signature');
        $secret = config('services.paystack.secret') ?? env('PAYSTACK_SECRET');
        if (!$secret || !$signature) {
            return response('Missing signature', 400);
        }

        $hash = hash_hmac('sha512', $request->getContent(), $secret);
        if (!hash_equals($hash, $signature)) {
            return response('Invalid signature', 403);
        }

        $data = $request->input('data', []);
        $reference = $data['reference'] ?? null;
        if (!$reference) {
            return response('Missing reference', 400);
        }

        // Idempotency: if transaction exists with provider_reference, ignore
        $existing = PaymentTransaction::where('provider_reference', $reference)->first();
        if ($existing && $existing->status === 'success') {
            return response('Already processed', 200);
        }

        // Create or update transaction record
        $tx = PaymentTransaction::updateOrCreate(
            ['provider_reference' => $reference],
            [
                'provider' => 'paystack',
                'paystack_transaction_id' => $data['id'] ?? null,
                'amount_cents' => (int)($data['amount'] ?? 0),
                'currency' => $data['currency'] ?? 'NGN',
                'status' => ($data['status'] ?? 'pending') === 'success' ? 'success' : 'failed',
                'payload' => $data,
            ]
        );

        // If success, update registration
        if ($tx->status === 'success' && ($meta = $data['metadata'] ?? null) && isset($meta['registration_id'])) {
            $registration = Registration::find($meta['registration_id']);
            if ($registration) {
                $registration->update(['payment_status' => 'paid', 'ticket_token' => hash('sha256', $reference)]);
            }
        }

        return response('OK', 200);
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
        }

        return ['success' => true, 'data' => $refundJson];
    }
}
