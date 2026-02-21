@extends('layouts.admin')

@section('admin_content')
    <div class="p-6">
        <x-admin.page-header
            title="Profile Settings"
            subtitle="Update your personal information"
        />

        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Information Form -->
            <div class="lg:col-span-2">
                <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Information</h3>
                    
                    <form method="POST" action="{{ route('admin.account.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Profile Photo -->
                        <div>
                            <label class="text-xs text-gray-600 mb-1 block">Profile Photo</label>
                            <div class="flex items-start gap-4">
                                <!-- Current Photo Preview -->
                                <div class="shrink-0">
                                    @if ($user->profile_photo)
                                        <img src="{{ $user->profilePhotoUrl() }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-lg object-cover border-2 border-gray-200">
                                    @else
                                        <div class="w-24 h-24 rounded-lg bg-gray-100 flex items-center justify-center border-2 border-gray-200">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Upload Input -->
                                <div class="flex-1">
                                    <input 
                                        type="file" 
                                        id="profile_photo" 
                                        name="profile_photo" 
                                        accept="image/jpeg,image/png,image/jpg"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                    >
                                    <p class="mt-1 text-xs text-gray-500">JPG, JPEG or PNG. Max 2MB.</p>
                                    @error('profile_photo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="text-xs text-gray-600 mb-1 block">Full Name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $user->name) }}"
                                required
                                autofocus
                                autocomplete="name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nse-green-500 focus:border-nse-green-500"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="text-xs text-gray-600 mb-1 block">Email Address</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}"
                                required
                                autocomplete="email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nse-green-500 focus:border-nse-green-500"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            @if (!$user->hasVerifiedEmail())
                                <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <p class="text-sm text-yellow-800">
                                        Your email address is unverified. 
                                        <a href="{{ route('verification.notice') }}" class="font-medium underline hover:no-underline">
                                            Click here to re-send the verification email.
                                        </a>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-nse-green-700 text-white text-sm font-medium rounded-lg hover:bg-nse-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-nse-green-500 transition"
                            >
                                Update Profile
                            </button>
                        </div>
                    </form>
                </x-admin.panel>
            </div>

            <!-- Account Overview Sidebar -->
            <div class="space-y-6">
                <!-- Account Info Card -->
                <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Info</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs text-gray-600 mb-1 block">Account Status</label>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </div>

                        <div>
                            <label class="text-xs text-gray-600 mb-1 block">Role</label>
                            <p class="text-sm font-medium text-gray-900">
                                {{ match($user->role) {
                                    'super_admin' => 'Super Admin',
                                    'finance_admin' => 'Finance Admin',
                                    'registration_admin' => 'Registration Admin',
                                    'accreditation_officer' => 'Accreditation Officer',
                                    'support_agent' => 'Support Agent',
                                    default => 'User'
                                } }}
                            </p>
                        </div>

                        <div>
                            <label class="text-xs text-gray-600 mb-1 block">Member Since</label>
                            <p class="text-sm text-gray-900">{{ $user->created_at->format('M j, Y') }}</p>
                        </div>

                        <div>
                            <label class="text-xs text-gray-600 mb-1 block">Email Verified</label>
                            @if ($user->hasVerifiedEmail())
                                <div class="flex items-center gap-2 text-green-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-medium">Verified</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2 text-yellow-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-medium">Not Verified</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </x-admin.panel>

                <!-- Quick Links Card -->
                <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    
                    <div class="space-y-2">
                        <a href="{{ route('admin.account.password') }}" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Change Password</span>
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
