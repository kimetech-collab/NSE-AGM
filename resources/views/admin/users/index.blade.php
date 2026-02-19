@extends('layouts.app')

@section('content')
    <div class="py-8 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-nse-neutral-900">User & Role Management</h1>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white border border-nse-neutral-200 rounded-lg p-4 grid grid-cols-1 md:grid-cols-3 gap-3">
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

        <div class="bg-white border border-nse-neutral-200 rounded-lg overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-nse-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Current Role</th>
                        <th class="px-4 py-3 text-left">Update Role</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr class="border-t border-nse-neutral-200">
                            <td class="px-4 py-3">{{ $u->name }}</td>
                            <td class="px-4 py-3">{{ $u->email }}</td>
                            <td class="px-4 py-3">{{ $roles[$u->role] ?? $u->role }}</td>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-nse-neutral-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $users->links() }}
        </div>
    </div>
@endsection
