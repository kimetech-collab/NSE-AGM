<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Mail\RegistrationOtp;
use App\Models\Registration;
use App\Models\PricingVersion;
use App\Models\PricingItem;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // create minimal pricing data required by validation
        $pv = PricingVersion::create(['version_name' => 'mvp']);
        PricingItem::create([
            'pricing_version_id' => $pv->id,
            'name' => 'Early Bird',
            'price_cents' => 1000000,
            'currency' => 'NGN',
        ]);
    }

    public function test_user_can_register_and_receive_otp()
    {
        Mail::fake();
        Storage::fake('public');

        $payload = [
            'name' => 'Test User',
            'email' => 'test+1@example.com',
            'is_member' => false,
            'pricing_item_id' => 1,
            'profile_photo' => UploadedFile::fake()->image('profile.jpg'),
        ];

        $response = $this->post('/register', $payload);
        $response->assertStatus(302); // Redirect to verify page
        $response->assertRedirect();

        $this->assertDatabaseHas('registrations', ['email' => 'test+1@example.com']);
        Mail::assertQueued(RegistrationOtp::class);
    }

    public function test_user_can_verify_otp()
    {
        Mail::fake();
        Storage::fake('public');

        $payload = [
            'name' => 'Verify User',
            'email' => 'verify@example.com',
            'is_member' => false,
            'pricing_item_id' => 1,
            'profile_photo' => UploadedFile::fake()->image('profile.jpg'),
        ];

        $response = $this->post('/register', $payload);
        $response->assertStatus(302);
        
        $registration = Registration::where('email', 'verify@example.com')->first();
        $this->assertNotNull($registration);

        // Get the OTP from the session (stored in redirect)
        $otp = cache()->get('registration_otp_'.$registration->id);
        $this->assertNotNull($otp);

        $verifyResponse = $this->post('/email/verify', [
            'registration_id' => $registration->id,
            'otp' => $otp,
        ]);

        $verifyResponse->assertStatus(302); // Redirect to payment page
        $verifyResponse->assertRedirect();

        $this->assertNotNull(Registration::find($registration->id)->email_verified_at);
    }

    public function test_user_can_resend_otp()
    {
        Mail::fake();
        Storage::fake('public');

        $payload = [
            'name' => 'Resend User',
            'email' => 'resend@example.com',
            'is_member' => false,
            'pricing_item_id' => 1,
            'profile_photo' => UploadedFile::fake()->image('profile.jpg'),
        ];

        $this->post('/register', $payload)->assertStatus(302);

        $registration = Registration::where('email', 'resend@example.com')->first();
        $this->assertNotNull($registration);

        $firstOtp = cache()->get('registration_otp_'.$registration->id);
        $this->assertNotNull($firstOtp);

        $response = $this->post('/email/verify/resend', [
            'registration_id' => $registration->id,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $secondOtp = cache()->get('registration_otp_'.$registration->id);
        $this->assertNotNull($secondOtp);
        $this->assertNotEquals($firstOtp, $secondOtp);

        Mail::assertQueued(RegistrationOtp::class, 2);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'registration.otp_resent',
            'entity_type' => 'Registration',
            'entity_id' => $registration->id,
        ]);
    }
}
