<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Registration;
use App\Models\PricingVersion;
use App\Models\PricingItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EndToEndFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $pv = PricingVersion::create(['version_name' => 'mvp']);

        PricingItem::create([
            'pricing_version_id' => $pv->id,
            'name' => 'Early Bird',
            'price_cents' => 1000000,
            'currency' => 'NGN',
        ]);

        PricingItem::create([
            'pricing_version_id' => $pv->id,
            'name' => 'Standard',
            'price_cents' => 1500000,
            'currency' => 'NGN',
        ]);
    }

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
            'profile_photo' => UploadedFile::fake()->image('profile.jpg'),
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

        $response->assertRedirect('/payment?registrationId=' . $registration->id);
        $registration->refresh();
        $this->assertNotNull($registration->email_verified_at);

        // Step 4: Access payment page
        $response = $this->get('/payment?registrationId=' . $registration->id);
        $response->assertOk();
        $response->assertSee('₦'); // Currency symbol displayed
        $response->assertSee($registration->name); // Participant name shown
    }

    public function test_incorrect_otp_fails()
    {
        // Register first
        $response = $this->post('/register', [
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'is_member' => false,
            'pricing_item_id' => 2,
            'profile_photo' => UploadedFile::fake()->image('profile.jpg'),
        ]);

        $registration = Registration::where('email', 'bob@example.com')->first();

        // Try wrong OTP
        $response = $this->post('/email/verify', [
            'registration_id' => $registration->id,
            'otp' => '000000',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $registration->refresh();
        $this->assertNull($registration->email_verified_at);
    }
}
