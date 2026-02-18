@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
        
        <!-- Navigation Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('admin.registrations.index') }}" class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition transform hover:scale-105">
                <div class="text-2xl mb-2">📋</div>
                <h3 class="text-sm font-semibold text-gray-700">Registrations</h3>
                <p class="text-xs text-gray-500">Manage participant registrations</p>
            </a>
            
            <a href="{{ route('admin.finance.index') }}" class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition transform hover:scale-105">
                <div class="text-2xl mb-2">💰</div>
                <h3 class="text-sm font-semibold text-gray-700">Finance</h3>
                <p class="text-xs text-gray-500">Payment & refund management</p>
            </a>
            
            <a href="{{ route('admin.audit.index') }}" class="p-4 bg-white rounded-lg shadow hover:shadow-lg transition transform hover:scale-105">
                <div class="text-2xl mb-2">📊</div>
                <h3 class="text-sm font-semibold text-gray-700">Audit Logs</h3>
                <p class="text-xs text-gray-500">System activity & changes</p>
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="p-4 bg-white rounded shadow">
                <h3 class="text-sm text-gray-500">Total Registrations</h3>
                <div class="text-3xl font-bold">{{ $total }}</div>
            </div>
            <div class="p-4 bg-white rounded shadow">
                <h3 class="text-sm text-gray-500">Paid Registrations</h3>
                <div class="text-3xl font-bold">{{ $paid }}</div>
            </div>
        </div>
    </div>
@endsection
