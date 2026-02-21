@extends('layouts.admin')

@section('title', 'Audit Log Details')

@section('admin_content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.audit.index') }}" class="text-blue-600 hover:text-blue-900">← Back to Audit Logs</a>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">Audit Log Details</h1>
        </div>

        <!-- Main Details Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Entry #{{ $auditLog->id }}</h2>
            </div>

            <div class="px-6 py-4 space-y-6">
                <!-- Status and Timestamp -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="mt-2">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $auditLog->status_badge }}">
                                {{ $auditLog->status }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Timestamp</label>
                        <div class="mt-2 text-gray-900">{{ $auditLog->formatted_date }}</div>
                    </div>
                </div>

                <!-- Action and Actor -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Action</label>
                        <div class="mt-2 text-gray-900">{{ $auditLog->formatted_action }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Actor (User)</label>
                        <div class="mt-2 text-gray-900">{{ $auditLog->actor_name }}</div>
                        @if ($auditLog->actor)
                            <div class="text-sm text-gray-500">ID: {{ $auditLog->actor_id }} | Email: {{ $auditLog->actor->email }}</div>
                        @endif
                    </div>
                </div>

                <!-- Entity -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Entity Type</label>
                        <div class="mt-2 text-gray-900">{{ $auditLog->entity_type }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Entity ID</label>
                        <div class="mt-2 text-gray-900">{{ $auditLog->entity_id }}</div>
                    </div>
                </div>

                <!-- IP Address and User Agent -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">IP Address</label>
                        <div class="mt-2 text-gray-900 font-mono text-sm">{{ $auditLog->ip_address ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">User Agent</label>
                        <div class="mt-2 text-gray-900 font-mono text-xs wrap-break-word">{{ $auditLog->user_agent ?? 'N/A' }}</div>
                    </div>
                </div>

                <!-- Error Message (if present) -->
                @if ($auditLog->error_message)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Error Message</label>
                        <div class="mt-2 bg-red-50 border border-red-200 rounded px-4 py-3">
                            <p class="text-red-800 text-sm font-mono">{{ $auditLog->error_message }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Changes Section -->
        @if ($auditLog->before_state || $auditLog->after_state)
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Changes</h2>
                </div>

                <div class="px-6 py-4">
                    @if (count($auditLog->changes) > 0)
                        <div class="space-y-4">
                            @foreach ($auditLog->changes as $field => $change)
                                <div class="border border-gray-200 rounded p-4">
                                    <div class="text-sm font-medium text-gray-700 mb-3">{{ ucwords(str_replace('_', ' ', $field)) }}</div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1">Before</div>
                                            <div class="bg-red-50 border border-red-200 rounded px-3 py-2 text-sm font-mono text-red-900">
                                                {{ is_array($change['from']) ? json_encode($change['from']) : ($change['from'] ?? 'null') }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1">After</div>
                                            <div class="bg-green-50 border border-green-200 rounded px-3 py-2 text-sm font-mono text-green-900">
                                                {{ is_array($change['to']) ? json_encode($change['to']) : ($change['to'] ?? 'null') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-gray-500 text-sm">
                            No changes recorded (metadata-only action)
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Metadata Section -->
        @if ($auditLog->metadata)
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Additional Metadata</h2>
                </div>

                <div class="px-6 py-4">
                    <pre class="bg-gray-50 rounded p-4 text-sm font-mono text-gray-900 overflow-auto">{{ json_encode($auditLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
        @endif

        <!-- Related Audit Logs -->
        @if ($relatedLogs->count() > 1)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Related Audit Logs for {{ $auditLog->entity_type }} #{{ $auditLog->entity_id }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $relatedLogs->count() }} total entries</p>
                </div>

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
                                    Status
                                </th>
                                <th scope="col" class="relative px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($relatedLogs as $related)
                                <tr class="hover:bg-gray-50 {{ $related->id === $auditLog->id ? 'bg-blue-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="text-gray-900">{{ $related->formatted_date }}</div>
                                        <div class="text-gray-500 text-xs">{{ $related->relative_time }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="text-gray-900">{{ $related->actor_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="text-gray-900">{{ $related->formatted_action }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $related->status_badge }}">
                                            {{ $related->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.audit.show', $related->id) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $related->id === $auditLog->id ? 'Current' : 'View' }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
