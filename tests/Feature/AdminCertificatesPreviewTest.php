<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCertificatesPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_see_preview_link_for_issued_certificate(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_REGISTRATION_ADMIN,
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => now(),
        ]);

        $registration = Registration::create([
            'name' => 'Preview User',
            'email' => 'preview@example.com',
            'is_member' => false,
            'price_cents' => 100000,
            'currency' => 'NGN',
            'payment_status' => 'paid',
            'attendance_status' => 'physical',
            'ticket_token' => 'preview-token-123',
        ]);

        Certificate::create([
            'registration_id' => $registration->id,
            'certificate_id' => 'NSE59-2026-PREVIEW1',
            'status' => 'issued',
            'issued_at' => now(),
        ]);

        $previewUrl = route('certificate.show', ['token' => $registration->ticket_token]);

        $this->actingAs($admin)
            ->get(route('admin.certificates.index'))
            ->assertOk()
            ->assertSee($previewUrl, false)
            ->assertSeeText('Preview');
    }

    public function test_admin_does_not_see_preview_link_for_revoked_certificate(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_REGISTRATION_ADMIN,
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => now(),
        ]);

        $registration = Registration::create([
            'name' => 'Revoked User',
            'email' => 'revoked@example.com',
            'is_member' => false,
            'price_cents' => 100000,
            'currency' => 'NGN',
            'payment_status' => 'paid',
            'attendance_status' => 'physical',
            'ticket_token' => 'revoked-token-123',
        ]);

        Certificate::create([
            'registration_id' => $registration->id,
            'certificate_id' => 'NSE59-2026-REVOKED1',
            'status' => 'revoked',
            'issued_at' => now(),
            'revoked_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.certificates.index'))
            ->assertOk()
            ->assertDontSeeText('Preview');
    }
}
