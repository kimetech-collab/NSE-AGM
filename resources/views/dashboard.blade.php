@extends('layouts.app')

@section('content')
    <div class="py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-nse-neutral-900">Dashboard</h1>
            <p class="text-sm text-nse-neutral-600">Welcome back. Access your registration, ticket, stream, and certificates.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('register') }}" class="block p-4 rounded-lg border border-nse-neutral-200 bg-white hover:border-nse-green-700 transition">
                <div class="text-sm font-semibold text-nse-green-700">New Registration</div>
                <p class="text-xs text-nse-neutral-600 mt-1">Start a participant registration flow.</p>
            </a>

            <a href="{{ route('pricing') }}" class="block p-4 rounded-lg border border-nse-neutral-200 bg-white hover:border-nse-green-700 transition">
                <div class="text-sm font-semibold text-nse-green-700">Pricing</div>
                <p class="text-xs text-nse-neutral-600 mt-1">View membership and attendee pricing matrix.</p>
            </a>

            <a href="{{ route('stream.show') }}" class="block p-4 rounded-lg border border-nse-neutral-200 bg-white hover:border-nse-green-700 transition">
                <div class="text-sm font-semibold text-nse-green-700">Virtual Attendance</div>
                <p class="text-xs text-nse-neutral-600 mt-1">Join live stream using your ticket token.</p>
            </a>

            <a href="{{ route('certificate.verify.lookup') }}" class="block p-4 rounded-lg border border-nse-neutral-200 bg-white hover:border-nse-green-700 transition">
                <div class="text-sm font-semibold text-nse-green-700">Verify Certificate</div>
                <p class="text-xs text-nse-neutral-600 mt-1">Public lookup for certificate authenticity.</p>
            </a>
        </div>

        @if(auth()->user() && auth()->user()->hasRole('super_admin','finance_admin','registration_admin','accreditation_officer','support_agent'))
            <div class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-nse-green-700 text-white rounded hover:bg-nse-green-900 transition">
                    Open Admin Dashboard
                </a>
            </div>
        @endif
    </div>
@endsection
