@extends('layouts.admin')

@section('admin_content')
    <div class="p-6 max-w-4xl mx-auto">
        <x-admin.page-header
            title="Virtual Stream Settings"
            subtitle="Configure platform links for virtual attendance."
        />

        <x-admin.panel class="p-4">
        <form method="POST" action="{{ route('admin.stream.update') }}" class="grid grid-cols-1 gap-4">
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
                <p class="text-sm text-gray-600">Event dates and certificate release schedule are managed in <a href="{{ route('admin.settings.index') }}" class="text-nse-green-700 underline">System Settings</a>.</p>
            </div>
            <div>
                <button class="px-4 py-2 bg-nse-green-700 text-white rounded">Save Settings</button>
            </div>
        </form>
        </x-admin.panel>
    </div>
@endsection
