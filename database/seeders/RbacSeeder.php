<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('permissions')) {
            return;
        }

        $permissions = [
            ['name' => 'Manage registrations', 'slug' => 'registrations.manage', 'module' => 'registrations'],
            ['name' => 'Manage finance', 'slug' => 'finance.manage', 'module' => 'finance'],
            ['name' => 'Manage accreditation', 'slug' => 'accreditation.manage', 'module' => 'accreditation'],
            ['name' => 'Manage certificates', 'slug' => 'certificates.manage', 'module' => 'certificates'],
            ['name' => 'Manage stream settings', 'slug' => 'stream.manage', 'module' => 'configuration'],
            ['name' => 'Manage pricing', 'slug' => 'pricing.manage', 'module' => 'configuration'],
            ['name' => 'Manage sponsors', 'slug' => 'sponsors.manage', 'module' => 'configuration'],
            ['name' => 'Manage speakers', 'slug' => 'speakers.manage', 'module' => 'configuration'],
            ['name' => 'Manage programme', 'slug' => 'programme.manage', 'module' => 'configuration'],
            ['name' => 'Manage faqs', 'slug' => 'faqs.manage', 'module' => 'configuration'],
            ['name' => 'Manage venues', 'slug' => 'venues.manage', 'module' => 'configuration'],
            ['name' => 'Manage settings', 'slug' => 'settings.manage', 'module' => 'configuration'],
            ['name' => 'Manage users', 'slug' => 'users.manage', 'module' => 'users'],
            ['name' => 'View audit logs', 'slug' => 'audit.view', 'module' => 'audit'],
        ];

        $permissionIds = [];
        foreach ($permissions as $permission) {
            $model = Permission::query()->firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
            $permissionIds[$permission['slug']] = $model->id;
        }

        $rolePermissions = [
            User::ROLE_SUPER_ADMIN => array_keys($permissionIds),
            User::ROLE_REGISTRATION_ADMIN => ['registrations.manage', 'certificates.manage', 'audit.view'],
            User::ROLE_FINANCE_ADMIN => ['finance.manage', 'certificates.manage', 'audit.view'],
            User::ROLE_ACCREDITATION_OFFICER => ['accreditation.manage'],
            User::ROLE_SUPPORT_AGENT => ['registrations.manage'],
            User::ROLE_REGISTRANT => [],
        ];

        foreach ($rolePermissions as $slug => $permissionSlugs) {
            $role = Role::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => str($slug)->replace('_', ' ')->title()->toString(),
                    'is_system' => true,
                ]
            );

            $role->permissions()->sync(
                collect($permissionSlugs)
                    ->map(fn (string $permissionSlug) => $permissionIds[$permissionSlug] ?? null)
                    ->filter()
                    ->values()
                    ->all()
            );
        }

        User::query()->get(['id', 'role'])->each(function (User $user): void {
            if (! $user->role) {
                return;
            }

            $role = Role::query()->where('slug', $user->role)->first();
            if ($role && ! $user->roles()->whereKey($role->id)->exists()) {
                $user->roles()->attach($role->id);
            }
        });
    }
}
