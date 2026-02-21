@extends('layouts.admin')

@section('admin_content')
    <div class="p-8">
        <x-admin.page-header
            title="Dashboard"
            subtitle="Real-time operational overview"
        />

        <!-- Quick Stats Row 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-admin.stat-card
                label="Total Registrations"
                :value="$total"
            />

            <!-- Paid Registrations -->
            <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Paid Registrations</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $paid }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $total > 0 ? round(($paid / $total) * 100) : 0 }}% conversion</p>
                    </div>
                    <div class="bg-emerald-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </x-admin.panel>

            <!-- Unpaid/Pending -->
            <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Unpaid/Pending</p>
                        <p class="text-3xl font-bold text-amber-600 mt-2">{{ $unpaid }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $total > 0 ? round(($unpaid / $total) * 100) : 0 }}% of total</p>
                    </div>
                    <div class="bg-amber-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </x-admin.panel>

            <!-- Total Revenue -->
            <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Revenue</p>
                        <p class="text-3xl font-bold text-blue-700 mt-2">₦{{ number_format($revenue, 0) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Avg: ₦{{ number_format($avgTicket, 0) }}/ticket</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </x-admin.panel>
        </div>

        <!-- Quick Stats Row 2 - Activity -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <!-- Today's Activity -->
            <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                <p class="text-sm font-semibold text-gray-900 mb-4">Today's Activity</p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">New Registrations</span>
                        <span class="font-bold text-lg text-nse-green-700">{{ $todayRegs }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Check-ins</span>
                        <span class="font-bold text-lg text-blue-700">{{ $checkinsToday }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Unique Participants</span>
                        <span class="font-bold text-lg text-purple-700">{{ $uniqueCheckinsToday }}</span>
                    </div>
                </div>
            </x-admin.panel>

            <!-- Weekly Metrics -->
            <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                <p class="text-sm font-semibold text-gray-900 mb-4">Last 7 Days</p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Registrations</span>
                        <span class="font-bold text-lg text-nse-green-700">{{ $last7Regs }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Avg/day</span>
                        <span class="font-bold text-lg text-nse-green-600">{{ round($last7Regs / 7) }}</span>
                    </div>
                </div>
            </x-admin.panel>

            <!-- Attendance Metrics -->
            <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                <p class="text-sm font-semibold text-gray-900 mb-4">Attendance</p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Total Check-ins</span>
                        <span class="font-bold text-lg text-blue-700">{{ $totalCheckIns }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Conversion Rate</span>
                        <span class="font-bold text-lg text-purple-700">{{ $attendanceRate }}%</span>
                    </div>
                </div>
            </x-admin.panel>

            <!-- Registration Window Status -->
            <x-admin.panel class="p-6 shadow-sm hover:shadow-md transition">
                <p class="text-sm font-semibold text-gray-900 mb-4">Registration Window</p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Status</span>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
                            {{ $registrationPhase === 'open' ? 'bg-emerald-100 text-emerald-700' : ($registrationPhase === 'upcoming' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($registrationPhase) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Opens</span>
                        <span class="font-semibold text-sm text-gray-800">{{ $registrationOpenAt->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Closes</span>
                        <span class="font-semibold text-sm text-gray-800">{{ $registrationCloseAt->format('M d, Y') }}</span>
                    </div>
                    @if($registrationPhase === 'upcoming')
                        <div class="text-xs text-gray-500">{{ $daysToOpen }} day(s) until opening.</div>
                    @elseif($registrationPhase === 'open')
                        <div class="text-xs text-gray-500">{{ $daysToClose }} day(s) until closing.</div>
                    @else
                        <div class="text-xs text-gray-500">Window closed. Update dates in System Settings.</div>
                    @endif
                </div>
            </x-admin.panel>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Registration Trend -->
            <x-admin.panel class="p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-900 mb-4">Registrations - Last 14 Days</p>
                <div class="flex items-end gap-1 h-48">
                    @php
                        $max = max(1, max($series ?: [0]));
                    @endphp
                    @foreach($series as $i => $value)
                        @php
                            $height = $max > 0 ? max(12, (int) round(($value / $max) * 100)) : 12;
                        @endphp
                        <div class="flex-1">
                            <div class="w-full bg-linear-to-t from-nse-green-600 to-nse-green-400 rounded-sm hover:from-nse-green-700 hover:to-nse-green-500 transition cursor-pointer group relative"
                                 style="height: {{ $height }}%; min-height: 4px;">
                                <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-2 py-1 rounded text-xs whitespace-nowrap transition">
                                    {{ $value }} registrations
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 grid grid-cols-7 text-[10px] text-gray-500 gap-1">
                    @foreach(array_slice($labels, -7) as $label)
                        <div class="text-center">{{ \Carbon\Carbon::parse($label)->format('M d') }}</div>
                    @endforeach
                </div>
            </x-admin.panel>

            <!-- Attendance Conversion -->
            <x-admin.panel class="p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-900 mb-6">Attendance Overview</p>
                <div class="space-y-4">
                    <!-- Paid to Check-in Conversion -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Check-in Conversion</span>
                            <span class="font-bold text-lg text-purple-700">{{ $attendanceRate }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="bg-linear-to-r from-purple-500 to-purple-700 h-full rounded-full transition-all duration-500" style="width: {{ $attendanceRate }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $uniqueParticipants }} / {{ $paid }} paid participants</p>
                    </div>

                    <!-- Payment Completion -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Payment Completion</span>
                            <span class="font-bold text-lg text-emerald-700">{{ $total > 0 ? round(($paid / $total) * 100) : 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="bg-linear-to-r from-emerald-500 to-emerald-700 h-full rounded-full transition-all duration-500" style="width: {{ $total > 0 ? round(($paid / $total) * 100) : 0 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $paid }} / {{ $total }} registrations</p>
                    </div>
                </div>
            </x-admin.panel>
        </div>
    </div>
@endsection
