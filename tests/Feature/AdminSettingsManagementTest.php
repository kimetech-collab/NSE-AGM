<?php

namespace Tests\Feature;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSettingsManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_view_settings_page(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSeeText('System Settings');
    }

    public function test_non_super_admin_cannot_view_settings_page(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_FINANCE_ADMIN,
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.settings.index'))
            ->assertForbidden();
    }

    public function test_super_admin_can_update_settings_and_audit_is_written(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => now(),
        ]);

        $payload = [
            'event_end_at' => now()->addDays(7)->toDateTimeString(),
            'stream_enabled' => '1',
            'stream_platform' => 'Zoom',
            'stream_primary_url' => 'https://example.com/stream',
            'stream_backup_url' => 'https://backup.example.com/stream',
            'admin_mfa_required' => '0',
            'admin_mfa_method' => 'totp',
            'certificate_public_verify_enabled' => '1',
            'certificate_release_mode' => 'manual',
        ];

        $this->actingAs($admin)
            ->post(route('admin.settings.update'), $payload)
            ->assertRedirect(route('admin.settings.index'));

        $this->assertDatabaseHas('system_settings', [
            'key' => 'admin_mfa_required',
            'value' => '0',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'settings.updated',
            'entity_type' => 'SystemSettings',
            'actor_id' => $admin->id,
        ]);
    }

    public function test_admin_mfa_toggle_off_allows_admin_without_two_factor(): void
    {
        SystemSetting::create(['key' => 'admin_mfa_required', 'value' => '0']);

        $admin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => null,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }
}
