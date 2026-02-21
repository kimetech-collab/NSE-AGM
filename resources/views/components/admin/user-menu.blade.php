<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <!-- User Menu Button -->
    <button 
        @click="open = !open"
        type="button"
        class="flex items-center gap-3 px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition"
        aria-label="User menu"
        aria-expanded="false"
    >
        @if(auth()->user()->profile_photo)
            <img src="{{ auth()->user()->profilePhotoUrl() }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
        @else
            <div class="w-8 h-8 rounded-full bg-nse-green-700 text-white flex items-center justify-center text-sm font-semibold">
                {{ auth()->user()->initials() }}
            </div>
        @endif
        <div class="text-left hidden sm:block">
            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500">{{ $roles[auth()->user()->role] ?? 'Admin' }}</p>
        </div>
        <svg class="w-4 h-4 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-64 rounded-lg shadow-lg bg-white border border-gray-200 z-50"
        style="display: none;"
    >
        <!-- User Info -->
        <div class="px-4 py-3 border-b border-gray-200">
            <div class="flex items-center gap-3">
                @if(auth()->user()->profile_photo)
                    <img src="{{ auth()->user()->profilePhotoUrl() }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                @else
                    <div class="w-10 h-10 rounded-full bg-nse-green-700 text-white flex items-center justify-center text-base font-semibold">
                        {{ auth()->user()->initials() }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    <p class="text-xs text-nse-green-700 font-medium mt-0.5">{{ $roles[auth()->user()->role] ?? 'Admin' }}</p>
                </div>
            </div>
        </div>

        <!-- Menu Items -->
        <div class="py-2">
            <!-- My Profile -->
            <a href="{{ route('admin.users.show', auth()->user()) }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>My Profile</span>
            </a>

            <!-- Account Settings -->
            <a href="{{ route('admin.account.profile') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Account Settings</span>
            </a>

            <!-- Change Password -->
            <a href="{{ route('admin.account.password') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Change Password</span>
            </a>

            <!-- Two-Factor Auth -->
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <a href="{{ route('two-factor.show') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Two-Factor Auth</span>
            </a>
            @endif
        </div>

        <div class="border-t border-gray-200 py-2">
            <!-- Admin Dashboard (if not already there) -->
            @if(!request()->routeIs('admin.*'))
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 13a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/>
                </svg>
                <span>Admin Dashboard</span>
            </a>
            @endif

            <!-- Return to Portal -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Return to Portal</span>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-700 hover:bg-red-50 transition">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>

@php
    $roles = [
        'super_admin' => 'Super Admin',
        'finance_admin' => 'Finance Admin',
        'registration_admin' => 'Registration Admin',
        'accreditation_officer' => 'Accreditation Officer',
        'support_agent' => 'Support Agent',
        'registrant' => 'Registrant',
    ];
@endphp
