@extends('layouts.admin')

@section('admin_content')
    @php
        $registrationOpenAt = filled($settings['registration_open_at'] ?? null)
            ? \Carbon\Carbon::parse($settings['registration_open_at'])
            : \App\Support\EventDates::get('registration_open_at');
        $earlyBirdDeadlineAt = filled($settings['early_bird_deadline_at'] ?? null)
            ? \Carbon\Carbon::parse($settings['early_bird_deadline_at'])
            : \App\Support\EventDates::get('early_bird_deadline_at');
        $registrationCloseAt = filled($settings['registration_close_at'] ?? null)
            ? \Carbon\Carbon::parse($settings['registration_close_at'])
            : \App\Support\EventDates::get('registration_close_at');
        $eventStartAt = filled($settings['event_start_at'] ?? null)
            ? \Carbon\Carbon::parse($settings['event_start_at'])
            : \App\Support\EventDates::get('event_start_at');
        $eventEndAt = filled($settings['event_end_at'] ?? null)
            ? \Carbon\Carbon::parse($settings['event_end_at'])
            : \App\Support\EventDates::get('event_end_at');
    @endphp

    <div class="py-8 space-y-6">
        <x-admin.page-header
            title="System Settings"
            subtitle="Global platform and security configuration."
        />

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf

            <x-admin.panel class="p-5 space-y-4">
                <h2 class="text-lg font-semibold text-nse-neutral-900">Event & Certificate</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Registration Opens At</label>
                        <input
                            type="datetime-local"
                            name="registration_open_at"
                            value="{{ $registrationOpenAt->format('Y-m-d\\TH:i') }}"
                            data-picker="datetime"
                            class="w-full border border-nse-neutral-300 rounded px-3 py-2"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Early Bird Deadline</label>
                        <input
                            type="datetime-local"
                            name="early_bird_deadline_at"
                            value="{{ $earlyBirdDeadlineAt->format('Y-m-d\\TH:i') }}"
                            data-picker="datetime"
                            class="w-full border border-nse-neutral-300 rounded px-3 py-2"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Registration Closes At</label>
                        <input
                            type="datetime-local"
                            name="registration_close_at"
                            value="{{ $registrationCloseAt->format('Y-m-d\\TH:i') }}"
                            data-picker="datetime"
                            class="w-full border border-nse-neutral-300 rounded px-3 py-2"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Event Start Date/Time</label>
                        <input
                            type="datetime-local"
                            name="event_start_at"
                            value="{{ $eventStartAt->format('Y-m-d\\TH:i') }}"
                            data-picker="datetime"
                            class="w-full border border-nse-neutral-300 rounded px-3 py-2"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Event End Date/Time</label>
                        <input
                            type="datetime-local"
                            name="event_end_at"
                            value="{{ $eventEndAt->format('Y-m-d\\TH:i') }}"
                            data-picker="datetime"
                            class="w-full border border-nse-neutral-300 rounded px-3 py-2"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Certificate Release Mode</label>
                        <select name="certificate_release_mode" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                            <option value="manual" @selected(($settings['certificate_release_mode'] ?? 'manual') === 'manual')>Manual</option>
                            <option value="automatic" @selected(($settings['certificate_release_mode'] ?? 'manual') === 'automatic')>Automatic</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Public Certificate Verification</label>
                        <select name="certificate_public_verify_enabled" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                            <option value="1" @selected(($settings['certificate_public_verify_enabled'] ?? '1') === '1')>Enabled</option>
                            <option value="0" @selected(($settings['certificate_public_verify_enabled'] ?? '1') === '0')>Disabled</option>
                        </select>
                    </div>
                </div>
            </x-admin.panel>

            <x-admin.panel class="p-5 space-y-4">
                <h2 class="text-lg font-semibold text-nse-neutral-900">Stream</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Stream Status</label>
                        <select name="stream_enabled" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                            <option value="1" @selected(($settings['stream_enabled'] ?? '1') === '1')>Enabled</option>
                            <option value="0" @selected(($settings['stream_enabled'] ?? '1') === '0')>Disabled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Primary Platform</label>
                        <select name="stream_platform" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                            @foreach(['Zoom', 'YouTube', 'Jitsi', 'Teams'] as $platform)
                                <option value="{{ $platform }}" @selected(($settings['stream_platform'] ?? 'Zoom') === $platform)>{{ $platform }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Primary URL</label>
                        <input type="url" name="stream_primary_url" value="{{ $settings['stream_primary_url'] ?? '' }}" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Backup URL</label>
                        <input type="url" name="stream_backup_url" value="{{ $settings['stream_backup_url'] ?? '' }}" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                    </div>
                </div>
            </x-admin.panel>

            <x-admin.panel class="p-5 space-y-4">
                <h2 class="text-lg font-semibold text-nse-neutral-900">Admin Security</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Admin MFA Requirement</label>
                        <select name="admin_mfa_required" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                            <option value="1" @selected(($settings['admin_mfa_required'] ?? '1') === '1')>Required</option>
                            <option value="0" @selected(($settings['admin_mfa_required'] ?? '1') === '0')>Not Required</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Admin MFA Method</label>
                        <select name="admin_mfa_method" class="w-full border border-nse-neutral-300 rounded px-3 py-2">
                            <option value="totp" @selected(($settings['admin_mfa_method'] ?? 'totp') === 'totp')>Authenticator App (TOTP)</option>
                            <option value="email_otp" @selected(($settings['admin_mfa_method'] ?? 'totp') === 'email_otp')>Email OTP</option>
                        </select>
                    </div>
                </div>
            </x-admin.panel>

            <div>
                <button type="submit" class="px-5 py-2.5 bg-nse-green-700 text-white rounded">Save Settings</button>
            </div>
        </form>
    </div>
@endsection
