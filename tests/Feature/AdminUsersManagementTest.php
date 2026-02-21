<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUsersManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_view_users_management_page(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSeeText('Role Management');
    }

    public function test_non_super_admin_cannot_view_users_management_page(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_REGISTRANT,
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_super_admin_can_update_user_role_and_audit_is_written(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
            'email_verified_at' => now(),
            'two_factor_confirmed_at' => now(),
        ]);

        $target = User::factory()->create([
            'role' => User::ROLE_REGISTRANT,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.users.role.update', $target), [
                'role' => User::ROLE_SUPPORT_AGENT,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'role' => User::ROLE_SUPPORT_AGENT,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'user.role_changed',
            'entity_type' => 'User',
            'entity_id' => $target->id,
            'actor_id' => $admin->id,
        ]);
    }
}
