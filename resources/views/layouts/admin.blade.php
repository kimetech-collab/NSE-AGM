@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <!-- Admin Header -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="px-6 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Administration Panel</h1>
                <p class="text-sm text-gray-600">Manage NSE-AGM Portal operations</p>
            </div>
            <div class="flex items-center gap-4">
                @if(auth()->user())
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->roles?->pluck('name')->join(', ') ?? 'Admin' }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="flex">
        <!-- Sidebar Navigation -->
        <div class="w-64 bg-white border-r border-gray-200 min-h-screen">
            <nav class="p-6 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 4v4m0 0v2m0-2v-4m9-4v10a1 1 0 01-1 1h-12a1 1 0 01-1-1V9"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Management</p>

                    @if(auth()->user()->hasRole('super_admin','registration_admin','support_agent'))
                        <a href="{{ route('admin.registrations.index') }}" 
                           class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.registrations*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2H0v-2a9 9 0 0118 0z"/>
                            </svg>
                            <span>Registrations</span>
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('super_admin','accreditation_officer'))
                        <a href="{{ route('admin.accreditation.index') }}" 
                           class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.accreditation*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Check-ins & QR</span>
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('super_admin','finance_admin'))
                        <a href="{{ route('admin.finance.index') }}" 
                           class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.finance*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Finance</span>
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('super_admin','finance_admin','registration_admin'))
                        <a href="{{ route('admin.certificates.index') }}" 
                           class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.certificates*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span>Certificates</span>
                        </a>
                    @endif
                </div>

                @if(auth()->user()->hasRole('super_admin'))
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <p class="px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Configuration</p>

                        <a href="{{ route('admin.stream.index') }}" 
                           class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.stream*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span>Stream Settings</span>
                        </a>

                        <a href="{{ route('admin.pricing.index') }}" 
                           class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.pricing*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>Pricing</span>
                        </a>

                        <a href="{{ route('admin.sponsors.index') }}" 
                           class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.sponsors*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>Sponsors</span>
                        </a>

                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.users*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.048M12 9v.75m6.223.285a9 9 0 01-11.334 0M3.5 15h17m-17 2.5h17"/>
                            </svg>
                            <span>Users & Roles</span>
                        </a>

                        <a href="{{ route('admin.settings.index') }}" 
                           class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.settings*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Settings</span>
                        </a>

                        <a href="{{ route('admin.audit.index') }}" 
                           class="flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.audit*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Audit Logs</span>
                        </a>
                    </div>
                @endif
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            @yield('admin_content')
        </div>
    </div>
</div>
@endsection
