<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

    public function index(Request $request)
    {
        $query = User::query();

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

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', 'string', Rule::in(array_keys($this->roles()))],
        ]);

        if ((int) $request->user()->id === (int) $user->id && $data['role'] !== User::ROLE_SUPER_ADMIN) {
            return redirect()->back()->with('error', 'You cannot remove your own super admin role.');
        }

        $before = $user->toArray();
        $user->update(['role' => $data['role']]);

        if (\Illuminate\Support\Facades\Schema::hasTable('audit_logs')) {
            $this->audit->logUser('role_changed', $user->id, [
                'changed_by' => $request->user()->id,
                'old_role' => $before['role'] ?? null,
                'new_role' => $data['role'],
            ], $before, $user->fresh()->toArray());
        }

        return redirect()->back()->with('success', "Role updated for {$user->email}.");
    }

    protected function roles(): array
    {
        return [
            User::ROLE_SUPER_ADMIN => 'Super Admin',
            User::ROLE_FINANCE_ADMIN => 'Finance Admin',
            User::ROLE_REGISTRATION_ADMIN => 'Registration Admin',
            User::ROLE_ACCREDITATION_OFFICER => 'Accreditation Officer',
            User::ROLE_SUPPORT_AGENT => 'Support Agent',
            User::ROLE_REGISTRANT => 'Registrant',
        ];
    }
}
