<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Registration;
use App\Models\PaymentTransaction;

class PaystackWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_webhook_is_idempotent_and_updates_registration()
    {
        // Arrange: set secret
        $secret = 'test-paystack-secret';
        $this->app['config']->set('services.paystack.secret', $secret);

        // Create a registration pending payment
        $registration = Registration::create([
            'name' => 'Webhook User',
            'email' => 'webhook@example.com',
            'is_member' => false,
            'pricing_item_id' => 1,
            'price_cents' => 1500000,
            'currency' => 'NGN',
            'payment_status' => 'pending',
        ]);

        $data = [
            'reference' => 'ref_'.uniqid(),
            'amount' => $registration->price_cents,
            'currency' => $registration->currency,
            'status' => 'success',
            'metadata' => ['registration_id' => $registration->id],
        ];

        $payload = ['event' => 'charge.success', 'data' => $data];
        $json = json_encode($payload);
        $signature = hash_hmac('sha512', $json, $secret);

        // Act: first webhook
        $res1 = $this->withHeaders(['X-Paystack-Signature' => $signature])
            ->postJson('/paystack/webhook', $payload);

        $res1->assertStatus(200);

        // Assert: transaction created and registration updated
        $this->assertDatabaseHas('payment_transactions', ['provider_reference' => $data['reference'], 'status' => 'success']);
        $reg = Registration::find($registration->id);
        $this->assertEquals('paid', $reg->payment_status);
        $this->assertNotNull($reg->ticket_token);

        $countAfterFirst = PaymentTransaction::where('provider_reference', $data['reference'])->count();
        $this->assertEquals(1, $countAfterFirst);

        // Act: duplicate webhook (same payload + signature)
        $res2 = $this->withHeaders(['X-Paystack-Signature' => $signature])
            ->postJson('/paystack/webhook', $payload);

        $res2->assertStatus(200);

        // Assert: still only one transaction (idempotent)
        $countAfterSecond = PaymentTransaction::where('provider_reference', $data['reference'])->count();
        $this->assertEquals(1, $countAfterSecond);
    }
}
