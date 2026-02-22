@extends('layouts.admin')

@section('admin_content')
    <div class="p-6 space-y-6">
        <x-admin.page-header title="Roles & Permissions" subtitle="Create custom roles and manage permission access for admins." />

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">{{ session('error') }}</div>
        @endif

        <x-admin.panel class="p-6">
            <h3 class="text-base font-semibold text-nse-neutral-900 mb-4">Create Custom Role</h3>
            <form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="name" placeholder="Role name" class="border border-nse-neutral-300 rounded px-3 py-2" required>
                    <input type="text" name="slug" placeholder="role_slug (optional)" class="border border-nse-neutral-300 rounded px-3 py-2">
                    <input type="text" name="description" placeholder="Description (optional)" class="border border-nse-neutral-300 rounded px-3 py-2">
                </div>

                <div>
                    <p class="text-xs text-nse-neutral-600 mb-2">Permissions</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                        @foreach($permissions as $permission)
                            <label class="inline-flex items-center gap-2 text-sm text-nse-neutral-700">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="rounded border-nse-neutral-300">
                                {{ $permission->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="px-4 py-2 bg-nse-green-700 text-white text-sm rounded hover:bg-nse-green-800">Create Role</button>
            </form>
        </x-admin.panel>

        @foreach($roles as $role)
            <x-admin.panel class="p-6">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div class="text-sm text-nse-neutral-600">Role slug: <span class="font-mono text-nse-neutral-800">{{ $role->slug }}</span></div>
                    @if(! $role->is_system)
                        <form method="POST" action="{{ route('admin.roles.delete', $role) }}" onsubmit="return confirm('Delete this role?');">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-2 text-sm bg-red-50 text-red-700 rounded hover:bg-red-100">Delete</button>
                        </form>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input type="text" name="name" value="{{ $role->name }}" class="border border-nse-neutral-300 rounded px-3 py-2" required>
                        <input type="text" value="{{ $role->slug }}" class="border border-nse-neutral-200 bg-nse-neutral-50 rounded px-3 py-2 text-nse-neutral-600" disabled>
                    </div>

                    <textarea name="description" rows="2" class="w-full border border-nse-neutral-300 rounded px-3 py-2" placeholder="Description">{{ $role->description }}</textarea>

                    <div>
                        <p class="text-xs text-nse-neutral-600 mb-2">Permissions</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            @foreach($permissions as $permission)
                                <label class="inline-flex items-center gap-2 text-sm text-nse-neutral-700">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked($role->permissions->contains('id', $permission->id)) class="rounded border-nse-neutral-300">
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="px-4 py-2 bg-nse-green-700 text-white text-sm rounded hover:bg-nse-green-800">Save Role</button>
                </form>
            </x-admin.panel>
        @endforeach
    </div>
@endsection
