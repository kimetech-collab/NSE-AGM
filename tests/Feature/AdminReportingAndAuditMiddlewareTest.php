<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\PaymentTransaction;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportingAndAuditMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_finance_exports_csv_and_pdf(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_FINANCE_ADMIN,
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => now(),
        ]);

        $registration = Registration::create([
            'name' => 'Fin User',
            'email' => 'fin@example.com',
            'is_member' => false,
            'price_cents' => 100000,
            'currency' => 'NGN',
            'payment_status' => 'paid',
        ]);

        PaymentTransaction::create([
            'registration_id' => $registration->id,
            'provider' => 'paystack',
            'provider_reference' => 'REF-EXPORT-1',
            'amount_cents' => 100000,
            'currency' => 'NGN',
            'status' => 'success',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.finance.export', ['format' => 'csv']))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $this->actingAs($admin)
            ->get(route('admin.finance.export', ['format' => 'pdf']))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_certificates_exports_csv_and_pdf(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_REGISTRATION_ADMIN,
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => now(),
        ]);

        $registration = Registration::create([
            'name' => 'Cert User',
            'email' => 'cert@example.com',
            'is_member' => false,
            'price_cents' => 100000,
            'currency' => 'NGN',
            'payment_status' => 'paid',
            'attendance_status' => 'physical',
        ]);

        Certificate::create([
            'registration_id' => $registration->id,
            'certificate_id' => 'NSE59-2026-ABC123',
            'status' => 'issued',
            'issued_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.certificates.export', ['format' => 'csv']))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $this->actingAs($admin)
            ->get(route('admin.certificates.export', ['format' => 'pdf']))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_admin_middleware_logs_admin_route_access(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.settings.index'))
            ->assertOk();

        $this->assertDatabaseHas('audit_logs', [
            'actor_id' => $admin->id,
            'action' => 'admin.route.accessed',
            'entity_type' => 'AdminRoute',
            'status' => 'Success',
        ]);
    }
}
