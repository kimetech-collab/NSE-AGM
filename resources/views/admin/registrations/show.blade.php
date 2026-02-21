@extends('layouts.admin')

@section('admin_content')
    <div class="p-6">
        <x-admin.page-header
            title="Registration #{{ $registration->id }}"
            subtitle="View and update participant registration details."
        />

        <x-admin.panel class="p-4 shadow-sm">
            <div class="flex gap-4 mb-4">
                @if($registration->profile_photo)
                    <img src="{{ $registration->profilePhotoUrl() }}" alt="{{ $registration->name }}" class="w-24 h-24 rounded-lg object-cover border-2 border-nse-green-200">
                @else
                    <div class="w-24 h-24 rounded-lg bg-gray-100 flex items-center justify-center border-2 border-gray-200">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                @endif
                <div>
                    <p><strong>Name:</strong> {{ $registration->name }}</p>
                    <p><strong>Email:</strong> {{ $registration->email }}</p>
                    <p><strong>Member:</strong> {{ $registration->is_member ? 'Yes' : 'No' }}</p>
                    <p><strong>Payment status:</strong> {{ $registration->payment_status }}</p>
                    <p><strong>Ticket:</strong> {{ $registration->ticket_token ?? 'Not issued' }}</p>
                </div>
            </div>
        </x-admin.panel>

        <x-admin.panel class="mt-6 p-4 shadow-sm">
            <h2 class="text-lg font-semibold mb-3">Edit Registration</h2>
            <form method="POST" action="{{ route('admin.registrations.update', $registration->id) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $registration->name) }}" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $registration->email) }}" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Is Member</label>
                    <select name="is_member" class="w-full border rounded px-3 py-2">
                        <option value="1" @selected(old('is_member', $registration->is_member ? '1' : '0') === '1')>Yes</option>
                        <option value="0" @selected(old('is_member', $registration->is_member ? '1' : '0') === '0')>No</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Membership Number</label>
                    <input type="text" name="membership_number" value="{{ old('membership_number', $registration->membership_number) }}" class="w-full border rounded px-3 py-2">
                </div>
                <div class="md:col-span-2">
                    <button class="px-4 py-2 bg-nse-green-700 text-white rounded">Save Changes</button>
                </div>
            </form>
        </x-admin.panel>

        <div class="mt-4">
            <a href="{{ route('admin.registrations.export', ['format' => 'csv']) }}" class="inline-block bg-gray-200 px-3 py-2">Export CSV</a>
            <a href="{{ route('admin.registrations.export', ['format' => 'pdf']) }}" class="inline-block bg-gray-200 px-3 py-2 ml-2">Export PDF</a>
        </div>
    </div>
@endsection
