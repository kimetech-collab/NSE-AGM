@extends('layouts.admin')

@section('title', 'Audit Logs')

@section('admin_content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Audit Logs</h1>
            <p class="mt-2 text-gray-600">Track all system activities and administrative actions</p>
        </div>

        @if ($errors->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-red-800">
                    <strong>⚠️ Error:</strong> {{ $errors->first('error') }}
                </p>
                <p class="text-red-700 text-sm mt-2">Have you run the migration? <code class="bg-red-100 px-2 py-1 rounded">php artisan migrate</code></p>
            </div>
        @endif

        <!-- Filters Card -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Filters</h2>
            <form method="GET" action="{{ route('admin.audit.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Actor Filter -->
                    <div>
                        <label for="actor_id" class="block text-sm font-medium text-gray-700">Actor/User</label>
                        <select name="actor_id" id="actor_id" class="mt-1 block w-full rounded-md border gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Users</option>
                            @forelse ($users as $user)
                                <option value="{{ $user->id }}" {{ request('actor_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @empty
                                <option disabled>No users found</option>
                            @endforelse
                        </select>
                    </div>

                    <!-- Action Filter -->
                    <div>
                        <label for="action" class="block text-sm font-medium text-gray-700">Action</label>
                        <select name="action" id="action" class="mt-1 block w-full rounded-md border gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Actions</option>
                            @forelse ($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('.', ' / ', $action)) }}
                                </option>
                            @empty
                                <option disabled>No actions found</option>
                            @endforelse
                        </select>
                    </div>

                    <!-- Entity Type Filter -->
                    <div>
                        <label for="entity_type" class="block text-sm font-medium text-gray-700">Entity Type</label>
                        <select name="entity_type" id="entity_type" class="mt-1 block w-full rounded-md border gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Entities</option>
                            @forelse ($entityTypes as $type)
                                <option value="{{ $type }}" {{ request('entity_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @empty
                                <option disabled>No entities found</option>
                            @endforelse
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Statuses</option>
                            <option value="Success" {{ request('status') == 'Success' ? 'selected' : '' }}>Success</option>
                            <option value="Failure" {{ request('status') == 'Failure' ? 'selected' : '' }}>Failure</option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            data-picker="date"
                            class="mt-1 block w-full rounded-md border gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                            data-picker="date"
                            class="mt-1 block w-full rounded-md border gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <!-- Entity ID -->
                    <div>
                        <label for="entity_id" class="block text-sm font-medium text-gray-700">Entity ID</label>
                        <input type="text" name="entity_id" id="entity_id" value="{{ request('entity_id') }}"
                            placeholder="Search entity ID..."
                            class="mt-1 block w-full rounded-md border gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Search text..."
                            class="mt-1 block w-full rounded-md border gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        🔍 Filter
                    </button>
                    <a href="{{ route('admin.audit.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Clear
                    </a>
                    <a href="{{ route('admin.audit.export', request()->query()) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        📥 Export CSV
                    </a>
                </div>
            </form>
        </div>

        <!-- Audit Logs Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Timestamp
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actor
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Entity
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                IP Address
                            </th>
                            <th scope="col" class="relative px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="text-gray-900 font-medium">{{ $log->formatted_date }}</div>
                                    <div class="text-gray-500 text-xs">{{ $log->relative_time }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="text-gray-900">{{ $log->actor_name }}</div>
                                    @if ($log->actor)
                                        <div class="text-gray-500 text-xs">ID: {{ $log->actor_id }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="text-gray-900">{{ $log->formatted_action }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="text-gray-900">{{ $log->entity_type }}</div>
                                    <div class="text-gray-500 text-xs">ID: {{ $log->entity_id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $log->status_badge }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.audit.show', $log->id) }}" class="text-blue-600 hover:text-blue-900">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    No audit logs found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
