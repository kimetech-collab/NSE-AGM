@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-2">Virtual Stream Settings</h1>
        <p class="text-sm text-nse-neutral-600 mb-6">Configure platform links for virtual attendance.</p>

        <form method="POST" action="{{ route('admin.stream.update') }}" class="bg-white p-4 rounded border border-nse-neutral-200 grid grid-cols-1 gap-4">
            @csrf
            <div>
                <label class="text-sm text-gray-600">Stream Enabled</label>
                <select name="stream_enabled" class="w-full border rounded px-3 py-2">
                    <option value="1" @selected(($settings['stream_enabled'] ?? '1') === '1')>Enabled</option>
                    <option value="0" @selected(($settings['stream_enabled'] ?? '1') === '0')>Disabled</option>
                </select>
            </div>
            <div>
                <label class="text-sm text-gray-600">Primary Platform</label>
                <input type="text" name="stream_platform" value="{{ $settings['stream_platform'] ?? 'Zoom' }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Primary Stream URL</label>
                <input type="url" name="stream_primary_url" value="{{ $settings['stream_primary_url'] ?? '' }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Backup Stream URL</label>
                <input type="url" name="stream_backup_url" value="{{ $settings['stream_backup_url'] ?? '' }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="text-sm text-gray-600">Event End (Certificate Release)</label>
                <input type="datetime-local" name="event_end_at" value="{{ isset($settings['event_end_at']) ? \Carbon\Carbon::parse($settings['event_end_at'])->format('Y-m-d\\TH:i') : '' }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <button class="px-4 py-2 bg-nse-green-700 text-white rounded">Save Settings</button>
            </div>
        </form>
    </div>
@endsection
