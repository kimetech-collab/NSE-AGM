<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

    public function index(Request $request)
    {
        $query = User::query()->with('roles:id,slug,name');

        if ($search = trim((string) $request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        return view('admin.users.index', [
            'users' => $query->orderByDesc('id')->paginate(25)->withQueryString(),
            'roles' => $this->roles(),
        ]);
    }

    public function store(Request $request)
    {
        $allowedRoles = array_keys($this->roles());

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['nullable', 'string', Rule::in($allowedRoles)],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', Rule::in($allowedRoles)],
        ]);

        $selectedRoles = $this->normalizeRoleSelection($data);
        if (empty($selectedRoles)) {
            return redirect()->back()->withInput()->withErrors([
                'roles' => 'Select at least one role.',
            ]);
        }

        $primaryRole = $this->determinePrimaryRole($selectedRoles);

        // Generate a random password
        $password = Str::random(length: 8);
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $primaryRole,
            'password' => Hash::make($password),
        ]);

        $this->syncUserRolesPivot($user, $selectedRoles);

        if (\Illuminate\Support\Facades\Schema::hasTable('audit_logs')) {
            $this->audit->logUser('user_created', $user->id, [
                'created_by' => $request->user()->id,
                'roles' => $selectedRoles,
            ], null, $user->toArray());
        }

        return redirect()->back()->with('success', "User created successfully. Email: {$user->email}, Temporary Password: {$password}");
    }

    public function show(User $user)
    {
        $user->load([
            'registrations' => function ($query) {
                $query->orderByDesc('created_at');
            },
        ]);

        return view('admin.users.show', [
            'user' => $user,
            'roles' => $this->roles(),
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $allowedRoles = array_keys($this->roles());

        $data = $request->validate([
            'role' => ['nullable', 'string', Rule::in($allowedRoles)],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', Rule::in($allowedRoles)],
        ]);

        $selectedRoles = $this->normalizeRoleSelection($data);
        if (empty($selectedRoles)) {
            return redirect()->back()->withErrors([
                'roles' => 'Select at least one role.',
            ]);
        }

        $primaryRole = $this->determinePrimaryRole($selectedRoles);

        if ((int) $request->user()->id === (int) $user->id && ! in_array(User::ROLE_SUPER_ADMIN, $selectedRoles, true)) {
            return redirect()->back()->with('error', 'You cannot remove your own super admin role.');
        }

        $before = $user->toArray();
        $beforeRoles = $user->roles()->pluck('slug')->values()->all();

        $user->update(['role' => $primaryRole]);

        $this->syncUserRolesPivot($user, $selectedRoles);

        if (\Illuminate\Support\Facades\Schema::hasTable('audit_logs')) {
            $this->audit->logUser('role_changed', $user->id, [
                'changed_by' => $request->user()->id,
                'old_role' => $before['role'] ?? null,
                'new_role' => $primaryRole,
                'old_roles' => $beforeRoles,
                'new_roles' => $selectedRoles,
            ], $before, $user->fresh()->toArray());
        }

        return redirect()->back()->with('success', "Roles updated for {$user->email}.");
    }

    protected function roles(): array
    {
        $base = [
            User::ROLE_SUPER_ADMIN => 'Super Admin',
            User::ROLE_FINANCE_ADMIN => 'Finance Admin',
            User::ROLE_REGISTRATION_ADMIN => 'Registration Admin',
            User::ROLE_ACCREDITATION_OFFICER => 'Accreditation Officer',
            User::ROLE_SUPPORT_AGENT => 'Support Agent',
            User::ROLE_REGISTRANT => 'Registrant',
        ];

        if (! Schema::hasTable('roles')) {
            return $base;
        }

        $custom = Role::query()
            ->orderBy('name')
            ->pluck('name', 'slug')
            ->toArray();

        return array_replace($base, $custom);
    }

    protected function syncUserRolesPivot(User $user, array $roleSlugs): void
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('role_user')) {
            return;
        }

        $roleIds = Role::query()
            ->whereIn('slug', $roleSlugs)
            ->pluck('id')
            ->all();

        if (empty($roleIds)) {
            return;
        }

        $user->roles()->sync($roleIds);
    }

    protected function normalizeRoleSelection(array $data): array
    {
        $roles = $data['roles'] ?? [];
        if (empty($roles) && ! empty($data['role'])) {
            $roles = [$data['role']];
        }

        return array_values(array_unique(array_filter($roles, fn ($role) => is_string($role) && $role !== '')));
    }

    protected function determinePrimaryRole(array $selectedRoles): string
    {
        $priority = [
            User::ROLE_SUPER_ADMIN,
            User::ROLE_FINANCE_ADMIN,
            User::ROLE_REGISTRATION_ADMIN,
            User::ROLE_ACCREDITATION_OFFICER,
            User::ROLE_SUPPORT_AGENT,
            User::ROLE_REGISTRANT,
        ];

        foreach ($priority as $role) {
            if (in_array($role, $selectedRoles, true)) {
                return $role;
            }
        }

        return $selectedRoles[0];
    }
}
