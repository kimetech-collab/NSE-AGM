@extends('layouts.admin')

@section('admin_content')
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-3">
            <x-admin.page-header title="FAQs" subtitle="Manage frequently asked questions by category." />
            <a href="{{ route('admin.faqs.create') }}" class="inline-flex items-center px-4 py-2 bg-nse-green-700 text-white text-sm rounded hover:bg-nse-green-800">Add FAQ</a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">{{ session('error') }}</div>
        @endif

        <x-admin.table tableClass="min-w-full bg-white text-sm">
            <thead class="bg-nse-neutral-50">
                <tr>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-left">Question</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Order</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqItems as $item)
                    <tr class="border-t border-nse-neutral-200">
                        <td class="px-4 py-3"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-nse-neutral-100 text-nse-neutral-700">{{ $item->category }}</span></td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-nse-neutral-900">{{ $item->question }}</div>
                            <div class="text-xs text-nse-neutral-600 mt-1 line-clamp-2">{{ $item->answer }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-nse-neutral-100 text-nse-neutral-700' }}">{{ $item->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $item->sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.faqs.edit', $item) }}" class="px-3 py-1 text-xs bg-nse-neutral-100 text-nse-neutral-700 rounded hover:bg-nse-neutral-200">Edit</a>
                                <form method="POST" action="{{ route('admin.faqs.delete', $item) }}" onsubmit="return confirm('Delete this FAQ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 text-xs bg-red-50 text-red-700 rounded hover:bg-red-100">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-nse-neutral-500">No FAQs yet.</td></tr>
                @endforelse
            </tbody>
        </x-admin.table>
    </div>
@endsection
