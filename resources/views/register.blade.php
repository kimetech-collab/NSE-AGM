@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Register</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <h3 class="font-semibold mb-2">Please fix the following errors:</h3>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf
            <div class="mb-3">
                <label class="block font-semibold">Name</label>
                <input name="name" value="{{ old('name') }}" class="border p-2 w-full" required />
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label class="block font-semibold">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" class="border p-2 w-full" required />
                @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_member" {{ old('is_member') ? 'checked' : '' }} class="mr-2" /> I am an NSE member
                </label>
                @error('is_member') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label class="block font-semibold">Membership number (optional)</label>
                <input name="membership_number" value="{{ old('membership_number') }}" class="border p-2 w-full" />
                @error('membership_number') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label class="block font-semibold">Pricing item (select)</label>
                <select name="pricing_item_id" class="border p-2 w-full">
                    <option value="">-- Select an option --</option>
                    <option value="1" {{ old('pricing_item_id') == 1 ? 'selected' : '' }}>Early Bird — NGN 10,000</option>
                    <option value="2" {{ old('pricing_item_id') == 2 ? 'selected' : '' }}>Standard — NGN 15,000</option>
                </select>
                @error('pricing_item_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Continue</button>
        </form>
    </div>
@endsection
