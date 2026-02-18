@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-nse-neutral-900">Admin Dashboard</h1>
            <p class="text-sm text-nse-neutral-600">Operational overview and KPIs</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('admin.registrations.index') }}" class="p-4 bg-white rounded-lg shadow-sm border border-nse-neutral-200 hover:border-nse-green-700 hover:shadow-md transition">
                <div class="text-sm font-semibold text-nse-green-700">Registrations</div>
                <p class="text-xs text-nse-neutral-600">Manage participant registrations</p>
            </a>

            <a href="{{ route('admin.finance.index') }}" class="p-4 bg-white rounded-lg shadow-sm border border-nse-neutral-200 hover:border-nse-green-700 hover:shadow-md transition">
                <div class="text-sm font-semibold text-nse-green-700">Finance</div>
                <p class="text-xs text-nse-neutral-600">Payment & refund management</p>
            </a>

            <a href="{{ route('admin.accreditation.index') }}" class="p-4 bg-white rounded-lg shadow-sm border border-nse-neutral-200 hover:border-nse-green-700 hover:shadow-md transition">
                <div class="text-sm font-semibold text-nse-green-700">Accreditation</div>
                <p class="text-xs text-nse-neutral-600">QR scanning and check-in</p>
            </a>

            <a href="{{ route('admin.audit.index') }}" class="p-4 bg-white rounded-lg shadow-sm border border-nse-neutral-200 hover:border-nse-green-700 hover:shadow-md transition">
                <div class="text-sm font-semibold text-nse-green-700">Audit Logs</div>
                <p class="text-xs text-nse-neutral-600">System activity and changes</p>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="p-4 bg-white rounded-lg border border-nse-neutral-200">
                <div class="text-xs text-nse-neutral-600">Total Registrations</div>
                <div class="text-3xl font-bold text-nse-green-700">{{ $total }}</div>
            </div>
            <div class="p-4 bg-white rounded-lg border border-nse-neutral-200">
                <div class="text-xs text-nse-neutral-600">Paid Registrations</div>
                <div class="text-3xl font-bold text-nse-green-700">{{ $paid }}</div>
            </div>
            <div class="p-4 bg-white rounded-lg border border-nse-neutral-200">
                <div class="text-xs text-nse-neutral-600">Unpaid / Pending</div>
                <div class="text-3xl font-bold text-nse-gold-700">{{ $unpaid }}</div>
            </div>
            <div class="p-4 bg-white rounded-lg border border-nse-neutral-200">
                <div class="text-xs text-nse-neutral-600">Refunded</div>
                <div class="text-3xl font-bold text-nse-neutral-600">{{ $refunded }}</div>
            </div>
            <div class="p-4 bg-white rounded-lg border border-nse-neutral-200">
                <div class="text-xs text-nse-neutral-600">Avg Ticket Size (₦)</div>
                <div class="text-3xl font-bold text-nse-green-700">{{ number_format($avgTicket, 2) }}</div>
            </div>
            <div class="p-4 bg-white rounded-lg border border-nse-neutral-200">
                <div class="text-xs text-nse-neutral-600">Registrations Today</div>
                <div class="text-3xl font-bold text-nse-green-700">{{ $todayRegs }}</div>
            </div>
            <div class="p-4 bg-white rounded-lg border border-nse-neutral-200">
                <div class="text-xs text-nse-neutral-600">Registrations (Last 7 Days)</div>
                <div class="text-3xl font-bold text-nse-green-700">{{ $last7Regs }}</div>
            </div>
            <div class="p-4 bg-white rounded-lg border border-nse-neutral-200">
                <div class="text-xs text-nse-neutral-600">QR Scans Today</div>
                <div class="text-3xl font-bold text-nse-green-700">{{ $qrScansToday }}</div>
            </div>
            <div class="p-4 bg-white rounded-lg border border-nse-neutral-200">
                <div class="text-xs text-nse-neutral-600">Check-ins Today</div>
                <div class="text-3xl font-bold text-nse-green-700">{{ $checkinsToday }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="p-5 bg-white rounded-lg border border-nse-neutral-200">
                <div class="text-sm font-semibold text-nse-neutral-900 mb-4">Total Revenue (₦)</div>
                <div class="text-4xl font-bold text-nse-green-700 mb-2">
                    {{ number_format($revenue, 2) }}
                </div>
                <p class="text-xs text-nse-neutral-600">Successful Paystack transactions only.</p>
            </div>

            <div class="p-5 bg-white rounded-lg border border-nse-neutral-200">
                <div class="text-sm font-semibold text-nse-neutral-900 mb-4">Daily Registrations (Last 14 days)</div>
                <div class="flex items-end gap-1 h-28">
                    @php
                        $max = max($series ?: [1]);
                    @endphp
                    @foreach($series as $i => $value)
                        @php
                            $height = $max > 0 ? max(8, (int) round(($value / $max) * 100)) : 8;
                        @endphp
                        <div class="flex-1">
                            <div class="w-full bg-nse-green-700/80 rounded-sm" style="height: {{ $height }}%"></div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-2 grid grid-cols-7 text-[10px] text-nse-neutral-400 gap-1">
                    @foreach(array_slice($labels, -7) as $label)
                        <div>{{ \Carbon\Carbon::parse($label)->format('M d') }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
