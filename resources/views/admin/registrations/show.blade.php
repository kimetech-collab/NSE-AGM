@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Registration #{{ $registration->id }}</h1>

        <div class="bg-white p-4 rounded shadow">
            <p><strong>Name:</strong> {{ $registration->name }}</p>
            <p><strong>Email:</strong> {{ $registration->email }}</p>
            <p><strong>Member:</strong> {{ $registration->is_member ? 'Yes' : 'No' }}</p>
            <p><strong>Payment status:</strong> {{ $registration->payment_status }}</p>
            <p><strong>Ticket:</strong> {{ $registration->ticket_token ?? 'Not issued' }}</p>
        </div>

        <div class="mt-6 bg-white p-4 rounded shadow">
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
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.registrations.export', ['format' => 'csv']) }}" class="inline-block bg-gray-200 px-3 py-2">Export CSV</a>
            <a href="{{ route('admin.registrations.export', ['format' => 'pdf']) }}" class="inline-block bg-gray-200 px-3 py-2 ml-2">Export PDF</a>
        </div>
    </div>
@endsection
