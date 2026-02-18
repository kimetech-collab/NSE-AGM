@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
        <div class="grid grid-cols-2 gap-4">
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
