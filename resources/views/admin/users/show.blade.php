@extends('layouts.admin')

@section('admin_content')
    <div class="p-6">
        <!-- Back Button & Header -->
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm text-nse-green-700 hover:text-nse-green-800 mb-3">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Users
            </a>
            <x-admin.page-header
                title="User Profile"
                subtitle="Detailed information about this user and their activity"
            />
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- User Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Profile Information -->
            <x-admin.panel class="md:col-span-2 p-6 shadow-sm hover:shadow-md transition">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Information</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0">
                            @if($user->profile_photo)
                                <img src="{{ $user->profilePhotoUrl() }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-nse-green-200">
                            @else
                                <div class="w-20 h-20 rounded-full bg-nse-green-700 text-white flex items-center justify-center text-2xl font-bold">
                                    {{ $user->initials() }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 space-y-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Name</label>
                                <p class="text-base font-semibold text-gray-900">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Email</label>
                                <p class="text-sm text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">User ID</label>
                                <p class="text-sm text-gray-900">#{{ $user->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-admin.panel>

            <!-- Account Status -->
            <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Status</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Role</label>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-nse-green-100 text-nse-green-800">
                                {{ $roles[$user->role] ?? $user->role }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Email Verified</label>
                        <p class="text-sm">
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center text-green-700">
                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Yes
                                </span>
                            @else
                                <span class="inline-flex items-center text-red-700">
                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    No
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Two-Factor Auth</label>
                        <p class="text-sm">
                            @if($user->two_factor_confirmed_at)
                                <span class="inline-flex items-center text-green-700">
                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Enabled
                                </span>
                            @else
                                <span class="text-nse-neutral-500">Disabled</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Member Since</label>
                        <p class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Last Updated</label>
                        <p class="text-sm text-gray-900">{{ $user->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </x-admin.panel>
        </div>

        <!-- Registrations -->
        <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Event Registrations</h3>
                <span class="px-2.5 py-0.5 text-xs font-semibold bg-nse-green-100 text-nse-green-800 rounded-full">
                    {{ $user->registrations->count() }}
                </span>
            </div>
            
            @if($user->registrations->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No registrations found for this user</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs text-gray-600">Registration ID</th>
                                <th class="px-4 py-2 text-left text-xs text-gray-600">Date</th>
                                <th class="px-4 py-2 text-left text-xs text-gray-600">Payment Status</th>
                                <th class="px-4 py-2 text-left text-xs text-gray-600">Amount</th>
                                <th class="px-4 py-2 text-left text-xs text-gray-600">Attendance</th>
                                <th class="px-4 py-2 text-left text-xs text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->registrations as $registration)
                                <tr class="border-t">
                                    <td class="px-4 py-2 text-sm">
                                        <span class="font-mono text-gray-900">#{{ $registration->id }}</span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900">
                                        {{ $registration->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-2 text-sm">
                                        @php
                                            $statusColors = [
                                                'completed' => 'bg-green-100 text-green-800',
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'failed' => 'bg-red-100 text-red-800',
                                                'refunded' => 'bg-gray-100 text-gray-800',
                                            ];
                                            $color = $statusColors[$registration->payment_status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $color }}">
                                            {{ ucfirst($registration->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900">
                                        {{ $registration->currency }} {{ number_format($registration->price_cents / 100, 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-sm">
                                        @if($registration->attendance_status)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst($registration->attendance_status) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm">
                                        <a href="{{ route('admin.registrations.show', $registration) }}" class="text-nse-green-700 hover:text-nse-green-800 text-sm">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-admin.panel>

        <!-- Activity Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                <p class="text-sm text-gray-600">Total Registrations</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $user->registrations->count() }}</p>
            </x-admin.panel>

            <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                <p class="text-sm text-gray-600">Completed Payments</p>
                <p class="mt-2 text-3xl font-bold text-green-700">
                    {{ $user->registrations->where('payment_status', 'completed')->count() }}
                </p>
            </x-admin.panel>

            <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                <p class="text-sm text-gray-600">Pending Payments</p>
                <p class="mt-2 text-3xl font-bold text-yellow-700">
                    {{ $user->registrations->where('payment_status', 'pending')->count() }}
                </p>
            </x-admin.panel>

            <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                <p class="text-sm text-gray-600">Total Spent</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                        @php
                            $totalCents = $user->registrations->where('payment_status', 'completed')->sum('price_cents');
                            $currency = $user->registrations->first()->currency ?? 'NGN';
                        @endphp
                    {{ $currency }} {{ number_format($totalCents / 100, 2) }}
                </p>
            </x-admin.panel>
        </div>
    </div>
@endsection
