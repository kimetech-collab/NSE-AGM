<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Registration;
use App\Models\User;
use App\Services\QRService;
use App\Models\PricingVersion;
use App\Models\PricingItem;

class AccreditationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_scan_valid_ticket()
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
            'name' => 'QR User',
            'email' => 'qr@example.com',
            'is_member' => false,
            'pricing_item_id' => 1,
            'price_cents' => 1500000,
            'currency' => 'NGN',
            'payment_status' => 'paid',
        ]);

        $token = app(QRService::class)->generateToken($registration);

        $this->actingAs($admin)
            ->post(route('admin.accreditation.scan'), ['token' => $token])
            ->assertStatus(302)
            ->assertSessionHas('scan_result');
    }

    public function test_offline_cache_returns_paid_registrations()
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
            'name' => 'Paid User',
            'email' => 'paid@example.com',
            'is_member' => false,
            'pricing_item_id' => 1,
            'price_cents' => 1500000,
            'currency' => 'NGN',
            'payment_status' => 'paid',
        ]);

        app(QRService::class)->generateToken($registration);

        $this->actingAs($admin)
            ->get(route('admin.accreditation.offline'))
            ->assertOk()
            ->assertJsonFragment(['email' => 'paid@example.com']);
    }
}
