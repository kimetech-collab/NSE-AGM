@extends('layouts.admin')

@section('admin_content')
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-3">
            <x-admin.page-header
                title="Programme"
                subtitle="Configure daily schedules, sessions, and event lineup."
            />
            <a href="{{ route('admin.programme.create') }}" class="inline-flex items-center px-4 py-2 bg-nse-green-700 text-white text-sm rounded hover:bg-nse-green-800">
                Add Programme Item
            </a>
        </div>

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

        <x-admin.table tableClass="min-w-full bg-white text-sm">
            <thead class="bg-nse-neutral-50">
                <tr>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Time</th>
                    <th class="px-4 py-3 text-left">Title</th>
                    <th class="px-4 py-3 text-left">Track / Location</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($programmeItems as $item)
                    <tr class="border-t border-nse-neutral-200">
                        <td class="px-4 py-3 whitespace-nowrap">{{ $item->programme_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $item->start_time }}
                            @if($item->end_time)
                                - {{ $item->end_time }}
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-nse-neutral-900">{{ $item->title }}</div>
                            @if($item->category)
                                <div class="text-xs text-nse-neutral-600 mt-0.5">{{ $item->category }}</div>
                            @endif
                            @if($item->speaker_name)
                                <div class="text-xs text-nse-neutral-600 mt-0.5">Speaker: {{ $item->speaker_name }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-nse-neutral-900">{{ $item->track ?: '—' }}</div>
                            <div class="text-xs text-nse-neutral-600 mt-0.5">{{ $item->location ?: '—' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-nse-neutral-100 text-nse-neutral-700' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($item->is_featured)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-nse-gold-100 text-nse-gold-800">Featured</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.programme.edit', $item) }}" class="px-3 py-1 text-xs bg-nse-neutral-100 text-nse-neutral-700 rounded hover:bg-nse-neutral-200">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.programme.delete', $item) }}" onsubmit="return confirm('Delete this programme item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 text-xs bg-red-50 text-red-700 rounded hover:bg-red-100">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-nse-neutral-500">
                            No programme items yet. Start by adding the first schedule entry.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </x-admin.table>
    </div>
@endsection
