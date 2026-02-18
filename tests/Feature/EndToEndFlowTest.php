<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Registration;
use Illuminate\Support\Facades\Mail;

class EndToEndFlowTest extends TestCase
{
    public function test_full_registration_flow_without_payment()
    {
        Mail::fake();

        // Step 1: Register
        $response = $this->post('/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'is_member' => true,
            'membership_number' => 'NSE123',
            'pricing_item_id' => 1,
        ]);

        $response->assertRedirect();
        $registration = Registration::where('email', 'jane@example.com')->first();
        $this->assertNotNull($registration);
        $this->assertEquals(1000000, $registration->price_cents); // Early Bird price

        // Step 2: Get OTP from cache (in real flow, user receives it in email)
        $otp = cache('registration_otp_' . $registration->id);
        $this->assertNotNull($otp);

        // Step 3: Verify OTP
        $response = $this->post('/email/verify', [
            'registration_id' => $registration->id,
            'otp' => $otp,
        ]);

        $response->assertRedirect('/payment');
        $registration->refresh();
        $this->assertNotNull($registration->email_verified_at);

        // Step 4: Access payment page
        $response = $this->get('/payment?registrationId=' . $registration->id);
        $response->assertOk();
        $response->assertSee('NGN 10,000'); // Correct amount displayed
    }

    public function test_incorrect_otp_fails()
    {
        // Register first
        $response = $this->post('/register', [
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'is_member' => false,
            'pricing_item_id' => 2,
        ]);

        $registration = Registration::where('email', 'bob@example.com')->first();

        // Try wrong OTP
        $response = $this->post('/email/verify', [
            'registration_id' => $registration->id,
            'otp' => '000000',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $registration->refresh();
        $this->assertNull($registration->email_verified_at);
    }
}
