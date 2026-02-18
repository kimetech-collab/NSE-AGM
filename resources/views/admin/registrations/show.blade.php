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

        <div class="mt-4">
            <a href="{{ route('admin.registrations.export') }}" class="inline-block bg-gray-200 px-3 py-2">Export CSV</a>
        </div>
    </div>
@endsection
