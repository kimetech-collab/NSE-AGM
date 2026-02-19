@extends('layouts.admin')

@section('admin_content')
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Dashboard</h2>
            <p class="text-gray-600 text-sm mt-1">Real-time operational overview</p>
        </div>

        <!-- Quick Stats Row 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Registrations -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Registrations</p>
                        <p class="text-3xl font-bold text-nse-green-700 mt-2">{{ $total }}</p>
                    </div>
                    <div class="bg-nse-green-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-nse-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2H0v-2a9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Paid Registrations -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Paid Registrations</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $paid }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ round(($paid/$total)*100) }}% conversion</p>
                    </div>
                    <div class="bg-emerald-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Unpaid/Pending -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Unpaid/Pending</p>
                        <p class="text-3xl font-bold text-amber-600 mt-2">{{ $unpaid }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ round(($unpaid/$total)*100) }}% of total</p>
                    </div>
                    <div class="bg-amber-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm hover:shadow-md transition">
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
            </div>
        </div>

        <!-- Quick Stats Row 2 - Activity -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Today's Activity -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm hover:shadow-md transition">
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
            </div>

            <!-- Weekly Metrics -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm hover:shadow-md transition">
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
            </div>

            <!-- Attendance Metrics -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm hover:shadow-md transition">
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
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Registration Trend -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-900 mb-4">Registrations - Last 14 Days</p>
                <div class="flex items-end gap-1 h-48">
                    @php
                        $max = max($series ?: [1]);
                    @endphp
                    @foreach($series as $i => $value)
                        @php
                            $height = $max > 0 ? max(12, (int) round(($value / $max) * 100)) : 12;
                        @endphp
                        <div class="flex-1">
                            <div class="w-full bg-gradient-to-t from-nse-green-600 to-nse-green-400 rounded-sm hover:from-nse-green-700 hover:to-nse-green-500 transition cursor-pointer group relative"
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
            </div>

            <!-- Attendance Conversion -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-900 mb-6">Attendance Overview</p>
                <div class="space-y-4">
                    <!-- Paid to Check-in Conversion -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Check-in Conversion</span>
                            <span class="font-bold text-lg text-purple-700">{{ $attendanceRate }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-700 h-full rounded-full transition-all duration-500" style="width: {{ $attendanceRate }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $uniqueParticipants }} / {{ $paid }} paid participants</p>
                    </div>

                    <!-- Payment Completion -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Payment Completion</span>
                            <span class="font-bold text-lg text-emerald-700">{{ round(($paid/$total)*100) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="bg-gradient-to-r from-emerald-500 to-emerald-700 h-full rounded-full transition-all duration-500" style="width: {{ round(($paid/$total)*100) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $paid }} / {{ $total }} registrations</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
