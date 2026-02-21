@extends('layouts.app')

@section('hideGlobalChrome', '1')

@section('content')
<div id="admin-shell" class="min-h-screen bg-slate-50">
    <!-- Admin Header -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-start gap-3">
                <button
                    id="admin-sidebar-mobile-toggle"
                    type="button"
                    class="lg:hidden inline-flex items-center justify-center p-2 rounded-md border border-gray-200 text-gray-700 hover:bg-gray-100"
                    aria-label="Open sidebar"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <button
                    id="admin-sidebar-collapse-toggle"
                    type="button"
                    class="hidden lg:inline-flex items-center justify-center p-2 rounded-md border border-gray-200 text-gray-700 hover:bg-gray-100"
                    aria-label="Collapse sidebar"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7M20 19l-7-7 7-7" />
                    </svg>
                </button>

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Administration Panel</h1>
                    <p class="text-sm text-gray-600">Manage NSE-AGM Portal operations</p>
                </div>
            </div>
            
            <!-- Admin User Menu -->
            @if(auth()->user())
                <x-admin.user-menu />
            @endif
        </div>
    </div>

    <div id="admin-sidebar-backdrop" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

    <div class="flex min-h-[calc(100vh-73px)]">
        <!-- Sidebar Navigation -->
        <div id="admin-sidebar" class="fixed top-0 left-0 h-screen w-64 bg-white border-r border-gray-200 z-40 transform -translate-x-full transition-all duration-200 ease-out lg:translate-x-0 lg:sticky lg:top-18.25 lg:h-[calc(100vh-73px)] lg:w-64 lg:z-0">
            <nav class="p-6 space-y-2 h-full overflow-y-auto">
                <div class="flex items-center justify-between lg:hidden mb-4">
                    <div class="text-sm font-semibold text-gray-700">Menu</div>
                    <button id="admin-sidebar-mobile-close" type="button" class="inline-flex items-center justify-center p-2 rounded-md border border-gray-200 text-gray-700 hover:bg-gray-100" aria-label="Close sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                         title="Dashboard"
                         aria-label="Dashboard"
                   class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 4v4m0 0v2m0-2v-4m9-4v10a1 1 0 01-1 1h-12a1 1 0 01-1-1V9"/>
                    </svg>
                    <span class="admin-nav-label">Dashboard</span>
                </a>

                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="admin-nav-section px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Management</p>

                    @if(auth()->user()->hasRole('super_admin','registration_admin','support_agent'))
                        <a href="{{ route('admin.registrations.index') }}" 
                           title="Registrations"
                           aria-label="Registrations"
                           class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.registrations*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2H0v-2a9 9 0 0118 0z"/>
                            </svg>
                            <span class="admin-nav-label">Registrations</span>
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('super_admin','accreditation_officer'))
                        <a href="{{ route('admin.accreditation.index') }}" 
                           title="Check-ins and QR"
                           aria-label="Check-ins and QR"
                           class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.accreditation*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="admin-nav-label">Check-ins & QR</span>
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('super_admin','finance_admin'))
                        <a href="{{ route('admin.finance.index') }}" 
                           title="Finance"
                           aria-label="Finance"
                           class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.finance*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="admin-nav-label">Finance</span>
                        </a>
                    @endif

                    @if(auth()->user()->hasRole('super_admin','finance_admin','registration_admin'))
                        <a href="{{ route('admin.certificates.index') }}" 
                           title="Certificates"
                           aria-label="Certificates"
                           class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.certificates*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span class="admin-nav-label">Certificates</span>
                        </a>
                    @endif
                </div>

                @if(auth()->user()->hasRole('super_admin'))
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <p class="admin-nav-section px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Configuration</p>

                        <a href="{{ route('admin.stream.index') }}" 
                                    title="Stream Settings"
                                    aria-label="Stream Settings"
                           class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.stream*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span class="admin-nav-label">Stream Settings</span>
                        </a>

                        <a href="{{ route('admin.pricing.index') }}" 
                                    title="Pricing"
                                    aria-label="Pricing"
                           class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.pricing*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span class="admin-nav-label">Pricing</span>
                        </a>

                        <a href="{{ route('admin.sponsors.index') }}" 
                                    title="Sponsors"
                                    aria-label="Sponsors"
                           class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.sponsors*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="admin-nav-label">Sponsors</span>
                        </a>

                        <a href="{{ route('admin.speakers.index') }}" 
                                    title="Speakers"
                                    aria-label="Speakers"
                           class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.speakers*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            <span class="admin-nav-label">Speakers</span>
                        </a>

                        <a href="{{ route('admin.users.index') }}" 
                                    title="Users and Roles"
                                    aria-label="Users and Roles"
                           class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.users*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.048M12 9v.75m6.223.285a9 9 0 01-11.334 0M3.5 15h17m-17 2.5h17"/>
                            </svg>
                            <span class="admin-nav-label">Users & Roles</span>
                        </a>

                        <a href="{{ route('admin.settings.index') }}" 
                                    title="Settings"
                                    aria-label="Settings"
                           class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.settings*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="admin-nav-label">Settings</span>
                        </a>

                        <a href="{{ route('admin.audit.index') }}" 
                                    title="Audit Logs"
                                    aria-label="Audit Logs"
                           class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.audit*') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="admin-nav-label">Audit Logs</span>
                        </a>
                    </div>
                @endif

                <!-- My Account Section -->
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="admin-nav-section px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">My Account</p>

                    <a href="{{ route('admin.users.show', auth()->user()) }}" 
                       title="My Profile"
                       aria-label="My Profile"
                       class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.users.show') && request()->route('user')->id === auth()->id() ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="admin-nav-label">My Profile</span>
                    </a>

                    <a href="{{ route('admin.account.profile') }}" 
                       title="Account Settings"
                       aria-label="Account Settings"
                       class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.account.profile') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="admin-nav-label">Account Settings</span>
                    </a>

                    <a href="{{ route('admin.account.password') }}" 
                       title="Change Password"
                       aria-label="Change Password"
                       class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.account.password') ? 'bg-nse-green-700 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span class="admin-nav-label">Change Password</span>
                    </a>

                    <a href="{{ route('dashboard') }}" 
                       title="Return to Portal"
                       aria-label="Return to Portal"
                       class="admin-nav-link flex items-center gap-3 px-4 py-2 rounded-lg transition text-gray-700 hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="admin-nav-label">Return to Portal</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="admin-nav-link w-full flex items-center gap-3 px-4 py-2 rounded-lg transition text-red-700 hover:bg-red-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="admin-nav-label">Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div id="admin-main" class="flex-1 min-w-0">
            @yield('admin_content')
        </div>
    </div>

    <footer class="bg-white border-t border-gray-200 px-6 py-3 text-xs text-gray-600">
        <div class="flex items-center justify-between">
            <p>© {{ now()->year }} NSE AGM Portal — Admin</p>
            <p>Secure administrative access</p>
        </div>
    </footer>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
    @media (min-width: 1024px) {
        #admin-shell.sidebar-collapsed #admin-sidebar {
            width: 5rem;
        }

        #admin-shell.sidebar-collapsed .admin-nav-label,
        #admin-shell.sidebar-collapsed .admin-nav-section {
            display: none;
        }

        #admin-shell.sidebar-collapsed .admin-nav-link {
            justify-content: center;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const shell = document.getElementById('admin-shell');
        const sidebar = document.getElementById('admin-sidebar');
        const backdrop = document.getElementById('admin-sidebar-backdrop');
        const mobileToggle = document.getElementById('admin-sidebar-mobile-toggle');
        const mobileClose = document.getElementById('admin-sidebar-mobile-close');
        const collapseToggle = document.getElementById('admin-sidebar-collapse-toggle');
        const navLinks = document.querySelectorAll('.admin-nav-link');

        const STORAGE_KEY = 'adminSidebarCollapsed';

        const isDesktop = () => window.matchMedia('(min-width: 1024px)').matches;

        const openMobileSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
        };

        const closeMobileSidebar = () => {
            if (!isDesktop()) {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        };

        const applyCollapsedState = (collapsed) => {
            shell.classList.toggle('sidebar-collapsed', collapsed);
            try {
                localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0');
            } catch (e) {}
        };

        const initialCollapsed = (() => {
            try {
                return localStorage.getItem(STORAGE_KEY) === '1';
            } catch (e) {
                return false;
            }
        })();

        applyCollapsedState(initialCollapsed);

        mobileToggle?.addEventListener('click', openMobileSidebar);
        mobileClose?.addEventListener('click', closeMobileSidebar);
        backdrop?.addEventListener('click', closeMobileSidebar);

        collapseToggle?.addEventListener('click', () => {
            applyCollapsedState(!shell.classList.contains('sidebar-collapsed'));
        });

        navLinks.forEach((link) => {
            link.addEventListener('click', () => {
                closeMobileSidebar();
            });
        });

        window.addEventListener('resize', () => {
            if (isDesktop()) {
                backdrop.classList.add('hidden');
                sidebar.classList.remove('-translate-x-full');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });

        if (typeof flatpickr !== 'undefined') {
            document.querySelectorAll('input[data-picker="date"]').forEach((el) => {
                flatpickr(el, {
                    dateFormat: 'Y-m-d',
                    allowInput: true,
                });
            });

            document.querySelectorAll('input[data-picker="datetime"]').forEach((el) => {
                flatpickr(el, {
                    enableTime: true,
                    time_24hr: true,
                    dateFormat: 'Y-m-d\\TH:i',
                    allowInput: true,
                });
            });
        }
    });
</script>
@endsection
