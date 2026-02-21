<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Registration;
use App\Models\User;
use App\Services\QRService;
use App\Models\PricingVersion;
use App\Models\PricingItem;

class AccreditationEnhancementsTest extends TestCase
{
    use RefreshDatabase;

    public function test_scan_response_includes_profile_photo_and_membership_number()
    {
        Storage::fake('public');
        
        $admin = User::factory()->create([
            'role' => 'accreditation_officer',
            'two_factor_confirmed_at' => now(),
        ]);

        $pv = PricingVersion::create(['version_name' => 'mvp']);
        PricingItem::create([
            'pricing_version_id' => $pv->id,
            'name' => 'Standard',
            'price_cents' => 1500000,
            'currency' => 'NGN',
        ]);

        $photo = UploadedFile::fake()->image('profile.jpg');
        $photoPath = $photo->store('profile-photos', 'public');

        $registration = Registration::create([
            'name' => 'Test Member',
            'email' => 'member@example.com',
            'is_member' => true,
            'membership_number' => 'NSE12345',
            'pricing_item_id' => 1,
            'price_cents' => 1500000,
            'currency' => 'NGN',
            'payment_status' => 'paid',
            'profile_photo' => $photoPath,
        ]);

        $token = app(QRService::class)->generateToken($registration);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.accreditation.scan'), ['token' => $token])
            ->assertOk()
            ->assertJson([
                'ok' => true,
                'status' => 'valid',
            ]);

        $data = $response->json();
        
        $this->assertArrayHasKey('registration', $data);
        $this->assertEquals('Test Member', $data['registration']['name']);
        $this->assertEquals('member@example.com', $data['registration']['email']);
        $this->assertTrue($data['registration']['is_member']);
        $this->assertEquals('NSE12345', $data['registration']['membership_number']);
        $this->assertArrayHasKey('profile_photo_url', $data['registration']);
        $this->assertNotEmpty($data['registration']['profile_photo_url']);
    }

    public function test_scan_response_handles_non_member_without_photo()
    {
        $admin = User::factory()->create([
            'role' => 'accreditation_officer',
            'two_factor_confirmed_at' => now(),
        ]);

        $pv = PricingVersion::create(['version_name' => 'mvp']);
        PricingItem::create([
            'pricing_version_id' => $pv->id,
            'name' => 'Standard',
            'price_cents' => 1500000,
            'currency' => 'NGN',
        ]);

        $registration = Registration::create([
            'name' => 'Non Member',
            'email' => 'nonmember@example.com',
            'is_member' => false,
            'membership_number' => null,
            'pricing_item_id' => 1,
            'price_cents' => 1500000,
            'currency' => 'NGN',
            'payment_status' => 'paid',
            'profile_photo' => null,
        ]);

        $token = app(QRService::class)->generateToken($registration);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.accreditation.scan'), ['token' => $token])
            ->assertOk()
            ->assertJson([
                'ok' => true,
                'status' => 'valid',
            ]);

        $data = $response->json();
        
        $this->assertArrayHasKey('registration', $data);
        $this->assertEquals('Non Member', $data['registration']['name']);
        $this->assertFalse($data['registration']['is_member']);
        $this->assertNull($data['registration']['membership_number']);
        $this->assertArrayHasKey('profile_photo_url', $data['registration']);
    }
}
