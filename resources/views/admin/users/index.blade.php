@extends('layouts.admin')

@section('admin_content')
    <div class="p-6">
        <x-admin.page-header
            title="User & Role Management"
            subtitle="Search users and view their profiles{{ auth()->user()->hasRole('super_admin') ? '. Create users and update role assignments.' : '.' }}"
        />

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Create User Form - Super Admin Only -->
        @if(auth()->user()->hasRole('super_admin'))
        <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Create New User</h3>
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="name" class="block text-xs text-nse-neutral-600 mb-1">Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full border border-nse-neutral-300 rounded px-3 py-2"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-xs text-nse-neutral-600 mb-1">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full border border-nse-neutral-300 rounded px-3 py-2"
                        >
                    </div>

                    <div>
                        <label for="role" class="block text-xs text-nse-neutral-600 mb-1">Role</label>
                        <select
                            id="role"
                            name="role"
                            required
                            class="w-full border border-nse-neutral-300 rounded px-3 py-2"
                        >
                            <option value="">Select role...</option>
                            @foreach($roles as $value => $label)
                                @if($value !== 'registrant')
                                    <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-nse-green-700 text-white text-sm rounded hover:bg-nse-green-800">
                        Create User
                    </button>
                </div>

                <p class="text-sm text-nse-neutral-600">
                    Note: A random temporary password will be generated and displayed after user creation. The user should change it upon first login.
                </p>
            </form>
        </x-admin.panel>
        @endif

        <x-admin.filter-bar class="mt-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Search name or email"
                class="border border-nse-neutral-300 rounded px-3 py-2"
            >

            <select name="role" class="border border-nse-neutral-300 rounded px-3 py-2">
                <option value="">All roles</option>
                @foreach($roles as $value => $label)
                    <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-nse-green-700 text-white rounded">Filter</button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-white border border-nse-neutral-300 rounded">Reset</a>
            </div>
        </form>
        </x-admin.filter-bar>

        <x-admin.table class="mt-6" tableClass="min-w-full bg-white">
                <thead class="bg-nse-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Current Role</th>
                        @if(auth()->user()->hasRole('super_admin'))
                        <th class="px-4 py-3 text-left">Update Role</th>
                        @endif
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr class="border-t border-nse-neutral-200">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($u->profile_photo)
                                        <img src="{{ $u->profilePhotoUrl() }}" alt="{{ $u->name }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-nse-green-700 text-white flex items-center justify-center text-xs font-semibold">
                                            {{ $u->initials() }}
                                        </div>
                                    @endif
                                    <span>{{ $u->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $u->email }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-nse-green-100 text-nse-green-800">
                                    {{ $roles[$u->role] ?? $u->role }}
                                </span>
                            </td>
                            @if(auth()->user()->hasRole('super_admin'))
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.users.role.update', $u) }}" class="flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" class="border border-nse-neutral-300 rounded px-2 py-1">
                                        @foreach($roles as $value => $label)
                                            <option value="{{ $value }}" @selected($u->role === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-3 py-1 bg-nse-green-700 text-white rounded">Save</button>
                                </form>
                            </td>
                            @endif
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.users.show', $u) }}" class="text-nse-green-700 hover:text-nse-green-800 font-medium">
                                    View Profile
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->hasRole('super_admin') ? 5 : 4 }}" class="px-4 py-8 text-center text-nse-neutral-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
        </x-admin.table>

        <x-admin.pagination-footer :paginator="$users" class="" />
    </div>
@endsection
