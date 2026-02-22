<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RolesController extends Controller
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

    public function index()
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('permissions')) {
            return view('admin.roles.index', [
                'roles' => collect(),
                'permissions' => collect(),
            ])->with('error', 'RBAC tables not found. Run migrations.');
        }

        return view('admin.roles.index', [
            'roles' => Role::query()->with('permissions')->orderBy('is_system', 'desc')->orderBy('name')->get(),
            'permissions' => Permission::query()->orderBy('module')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:roles,slug'],
            'description' => ['nullable', 'string', 'max:1000'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', Rule::exists('permissions', 'id')],
        ]);

        $role = Role::create([
            'name' => $data['name'],
            'slug' => $data['slug'] ?: Str::slug($data['name'], '_'),
            'description' => $data['description'] ?? null,
            'is_system' => false,
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('role.created', 'Role', $role->id, $role->toArray());
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created.');
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', Rule::exists('permissions', 'id')],
        ]);

        $before = $role->toArray();

        $role->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
        $role->permissions()->sync($data['permissions'] ?? []);

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('role.updated', 'Role', $role->id, ['slug' => $role->slug], $before, $role->fresh()->toArray());
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role updated.');
    }

    public function destroy(Role $role)
    {
        if ($role->is_system) {
            return redirect()->route('admin.roles.index')->with('error', 'System roles cannot be deleted.');
        }

        $before = $role->toArray();
        $id = $role->id;
        $role->delete();

        if (Schema::hasTable('audit_logs')) {
            $this->audit->log('role.deleted', 'Role', $id, $before);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted.');
    }
}
