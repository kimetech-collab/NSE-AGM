<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\PaymentTransaction;
use App\Models\Registration;
use App\Models\PricingVersion;
use App\Models\PricingItem;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Http;

class RefundTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create minimal pricing data required by validation
        $pv = PricingVersion::create(['version_name' => 'mvp']);
        PricingItem::create([
            'pricing_version_id' => $pv->id,
            'name' => 'Early Bird',
            'price_cents' => 1000000,
            'currency' => 'NGN',
        ]);
    }

    public function test_refund_uses_stored_paystack_transaction_id()
    {
        // Create a successful payment transaction with stored Paystack ID
        $registration = Registration::create([
            'email' => 'test@example.com',
            'name' => 'Test User',
            'phone' => '+2341234567890',
            'membership_number' => 'NSE123',
            'pricing_item_id' => 1,
            'price_cents' => 1000000,
            'currency' => 'NGN',
            'payment_status' => 'paid',
            'email_verified_at' => now(),
        ]);

        $transaction = PaymentTransaction::create([
            'registration_id' => $registration->id,
            'provider' => 'paystack',
            'provider_reference' => 'nseagm_test123',
            'paystack_transaction_id' => 98765,
            'amount_cents' => 1000000,
            'currency' => 'NGN',
            'status' => 'success',
            'payload' => ['test' => 'data'],
        ]);

        // Mock successful refund response
        Http::fake([
            'https://api.paystack.co/refund' => Http::response([
                'status' => true,
                'message' => 'Refund initiated',
                'data' => [
                    'reference' => 'REF-'.time(),
                    'transaction' => 98765,
                    'amount' => 1000000,
                ],
            ]),
        ]);

        // Initiate refund
        $service = new PaymentService();
        $result = $service->initiateRefund($transaction);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('data', $result);

        // Verify transaction status updated
        $this->assertEquals('refunded', $transaction->fresh()->status);
        $this->assertEquals('refunded', $registration->fresh()->payment_status);
    }

    public function test_refund_fetches_paystack_id_if_not_stored()
    {
        // Create a transaction without stored Paystack ID
        $registration = Registration::create([
            'email' => 'test@example.com',
            'name' => 'Test User',
            'phone' => '+2341234567890',
            'membership_number' => 'NSE123',
            'pricing_item_id' => 1,
            'price_cents' => 1000000,
            'currency' => 'NGN',
            'payment_status' => 'paid',
            'email_verified_at' => now(),
        ]);

        $transaction = PaymentTransaction::create([
            'registration_id' => $registration->id,
            'provider' => 'paystack',
            'provider_reference' => 'nseagm_test123',
            'amount_cents' => 1000000,
            'currency' => 'NGN',
            'status' => 'success',
            'payload' => ['test' => 'data'],
        ]);

        // Mock Paystack verify and refund responses
        Http::fake([
            'https://api.paystack.co/transaction/verify/*' => Http::response([
                'status' => true,
                'message' => 'Authorization URL created',
                'data' => [
                    'id' => 98765,
                    'reference' => 'nseagm_test123',
                    'amount' => 1000000,
                    'status' => 'success',
                ],
            ]),
            'https://api.paystack.co/refund' => Http::response([
                'status' => true,
                'message' => 'Refund initiated',
                'data' => [
                    'reference' => 'REF-'.time(),
                    'transaction' => 98765,
                    'amount' => 1000000,
                ],
            ]),
        ]);

        // Initiate refund
        $service = new PaymentService();
        $result = $service->initiateRefund($transaction);

        $this->assertTrue($result['success']);

        // Verify that Paystack ID was fetched and stored
        $this->assertEquals(98765, $transaction->fresh()->paystack_transaction_id);
        $this->assertEquals('refunded', $transaction->fresh()->status);
    }

    public function test_refund_fails_with_appropriate_message_when_paystack_verify_fails()
    {
        // Create a transaction without stored Paystack ID
        $registration = Registration::create([
            'email' => 'test@example.com',
            'name' => 'Test User',
            'phone' => '+2341234567890',
            'membership_number' => 'NSE123',
            'pricing_item_id' => 1,
            'price_cents' => 1000000,
            'currency' => 'NGN',
            'payment_status' => 'paid',
            'email_verified_at' => now(),
        ]);

        $transaction = PaymentTransaction::create([
            'registration_id' => $registration->id,
            'provider' => 'paystack',
            'provider_reference' => 'nseagm_invalid',
            'amount_cents' => 1000000,
            'currency' => 'NGN',
            'status' => 'success',
            'payload' => ['test' => 'data'],
        ]);

        // Mock Paystack verify failure
        Http::fake([
            'https://api.paystack.co/transaction/verify/*' => Http::response([
                'status' => false,
                'message' => 'Transaction reference not found.',
                'type' => 'validation_error',
                'code' => 'transaction_not_found',
            ], 404),
        ]);

        // Initiate refund
        $service = new PaymentService();
        $result = $service->initiateRefund($transaction);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Could not verify transaction', $result['message']);
        $this->assertArrayHasKey('response', $result);

        // Ensure transaction remains in success state (not marked as refunded)
        $this->assertEquals('success', $transaction->fresh()->status);
    }
}


