@extends('layouts.admin')

@section('admin_content')
    <div class="p-6">
        <x-admin.page-header
            title="Change Password"
            subtitle="Update your password to keep your account secure"
        />

        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Password Change Form -->
            <div class="lg:col-span-2">
                <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Password</h3>
                    
                    <form method="POST" action="{{ route('admin.account.password.update') }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="text-xs text-gray-600 mb-1 block">Current Password</label>
                            <input 
                                type="password" 
                                id="current_password" 
                                name="current_password" 
                                required
                                autofocus
                                autocomplete="current-password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nse-green-500 focus:border-nse-green-500"
                            >
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="text-xs text-gray-600 mb-1 block">New Password</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                autocomplete="new-password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nse-green-500 focus:border-nse-green-500"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters long</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="text-xs text-gray-600 mb-1 block">Confirm New Password</label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required
                                autocomplete="new-password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nse-green-500 focus:border-nse-green-500"
                            >
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-nse-green-700 text-white text-sm font-medium rounded-lg hover:bg-nse-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-nse-green-500 transition"
                            >
                                Update Password
                            </button>
                        </div>
                    </form>
                </x-admin.panel>
            </div>

            <!-- Password Security Sidebar -->
            <div class="space-y-6">
                <!-- Password Tips Card -->
                <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Password Tips</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-nse-green-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-gray-700">Use at least 8 characters</p>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-nse-green-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-gray-700">Mix uppercase and lowercase letters</p>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-nse-green-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-gray-700">Include numbers and symbols</p>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-nse-green-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-gray-700">Avoid common words or patterns</p>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-nse-green-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-gray-700">Don't reuse passwords from other sites</p>
                        </div>
                    </div>
                </x-admin.panel>

                <!-- Security Notice Card -->
                <x-admin.panel class="p-6 bg-blue-50 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start gap-3">
                        <div class="bg-blue-100 rounded-full p-2 shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-900 mb-1">Security Notice</h4>
                            <p class="text-xs text-blue-800">
                                Changing your password will not log you out of your current session. Make sure to update your password on all devices.
                            </p>
                        </div>
                    </div>
                </x-admin.panel>

                <!-- Quick Links Card -->
                <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Settings</h3>
                    
                    <div class="space-y-2">
                        <a href="{{ route('admin.account.profile') }}" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Edit Profile</span>
                        </a>

                        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        <a href="{{ route('two-factor.show') }}" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span>Two-Factor Auth</span>
                        </a>
                        @endif

                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 13a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </div>
                </x-admin.panel>
            </div>
        </div>
    </div>
@endsection
