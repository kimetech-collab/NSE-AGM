@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto p-6">
        <header class="mb-12">
            <h1 class="text-4xl font-bold mb-2">NSE 59th AGM Portal</h1>
            <p class="text-gray-600">MVP Deployment — Day 1 (Feb 18, 2026)</p>
        </header>

        <nav class="flex gap-4 mb-8">
            @auth
                <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 border border-gray-300 rounded">Log in</a>
            @endauth
        </nav>

        <div class="grid md:grid-cols-2 gap-6 mb-12">
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-2xl font-bold mb-3">Register for Event</h2>
                <p class="text-gray-600 mb-4">NSE member or participant registration with 3 steps.</p>
                <a href="{{ route('register.show') }}" class="inline-block px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">Start Registration</a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-2xl font-bold mb-3">Admin Dashboard</h2>
                <p class="text-gray-600 mb-4">View KPIs, manage registrations and finances.</p>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Go to Admin</a>
                @else
                    <p class="text-sm text-gray-500">Login required. (Use Laravel Fortify or seed test user)</p>
                @endauth
            </div>
        </div>

        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
            <h3 class="font-bold mb-3">Quick Links & Notes</h3>
            <ul class="space-y-2 text-sm">
                <li>📋 <a href="{{ route('register.show') }}" class="text-blue-600 underline">Start a registration flow</a> — 3 steps with OTP verification</li>
                <li>🎫 View ticket after payment (QR token issued)</li>
                <li>📊 <a href="{{ route('admin.dashboard') }}" class="text-blue-600 underline">Admin dashboard</a> (auth required) shows KPI counts cached 1 min</li>
                <li>💳 Paystack webhook endpoint: <code class="bg-white px-2 py-1 rounded text-xs">/paystack/webhook</code> (idempotent, signed)</li>
                <li>✅ All tests pass (2 registration + OTP, 1 webhook idempotency test)</li>
            </ul>
        </div>

        <footer class="mt-12 text-gray-500 text-sm border-t pt-6">
            <p><strong>MVP Status:</strong> Day 1 (Feb 18) — Registrations, OTP, Payment webhook, Admin UI scaffolded. Testing complete. Ready for frontend refinement and load testing.</p>
        </footer>
    </div>
@endsection
