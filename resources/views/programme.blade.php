@extends('layouts.public')

@section('title', 'Conference Programme — NSE 59th AGM & Conference')

@section('content')
<section class="bg-white py-16 sm:py-20" aria-labelledby="programme-heading">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="text-center mb-10">
            <p class="text-nse-neutral-500 text-xs font-semibold uppercase tracking-widest mb-2">Conference Schedule</p>
            <h1 id="programme-heading" class="text-3xl font-bold text-nse-neutral-900">Programme of Events</h1>
            <p class="text-sm text-nse-neutral-600 mt-3 max-w-2xl mx-auto">
                Explore daily activities, technical sessions, keynotes, and other conference events.
            </p>
        </div>

        @if($programmeItems->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10 text-center">
                <div class="bg-nse-neutral-50 border border-nse-neutral-200 rounded-lg p-4">
                    <p class="text-2xl font-bold text-nse-green-700">{{ $groupedSchedule->count() }}</p>
                    <p class="text-xs text-nse-neutral-600 mt-1">Schedule Days</p>
                </div>
                <div class="bg-nse-neutral-50 border border-nse-neutral-200 rounded-lg p-4">
                    <p class="text-2xl font-bold text-nse-gold-700">{{ $programmeItems->count() }}</p>
                    <p class="text-xs text-nse-neutral-600 mt-1">Total Sessions</p>
                </div>
                <div class="bg-nse-neutral-50 border border-nse-neutral-200 rounded-lg p-4">
                    <p class="text-2xl font-bold text-nse-green-700">{{ $programmeItems->where('is_featured', true)->count() }}</p>
                    <p class="text-xs text-nse-neutral-600 mt-1">Featured Events</p>
                </div>
                <div class="bg-nse-neutral-50 border border-nse-neutral-200 rounded-lg p-4">
                    <p class="text-2xl font-bold text-nse-gold-700">{{ $programmeItems->pluck('track')->filter()->unique()->count() }}</p>
                    <p class="text-xs text-nse-neutral-600 mt-1">Tracks</p>
                </div>
            </div>

            <div class="space-y-6" x-data="{ activeDay: 0 }">
                @foreach($groupedSchedule as $index => $day)
                    <div class="bg-white rounded-lg border border-nse-neutral-200 overflow-hidden shadow-sm">
                        <button
                            @click="activeDay = activeDay === {{ $index }} ? -1 : {{ $index }}"
                            :aria-expanded="activeDay === {{ $index }}"
                            class="w-full flex items-center justify-between px-6 py-4 bg-nse-neutral-50 hover:bg-nse-neutral-100 transition-colors"
                        >
                            <div class="text-left">
                                <p class="text-sm font-bold text-nse-green-700 uppercase tracking-wide">Day {{ $index + 1 }}</p>
                                <p class="text-lg font-bold text-nse-neutral-900">{{ \Illuminate\Support\Carbon::parse($day['date'])->format('F j, Y') }}</p>
                                <p class="text-xs text-nse-neutral-600 mt-0.5">{{ $day['items']->count() }} event(s)</p>
                            </div>
                            <svg class="w-5 h-5 text-nse-neutral-600 transition-transform duration-200" :class="{ 'rotate-180': activeDay === {{ $index }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        </button>

                        <div x-show="activeDay === {{ $index }}" x-transition class="px-6 py-6 border-t border-nse-neutral-200 bg-white space-y-4">
                            @foreach($day['items'] as $item)
                                <div class="flex gap-4 {{ $loop->last ? '' : 'pb-4 border-b border-nse-neutral-100' }}">
                                    <div class="text-right shrink-0 w-24">
                                        <p class="font-mono font-bold text-nse-green-700">{{ $item->start_time }}</p>
                                        @if($item->end_time)
                                            <p class="text-xs text-nse-neutral-500">to {{ $item->end_time }}</p>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 flex-wrap mb-1">
                                            <p class="font-bold text-nse-neutral-900">{{ $item->title }}</p>
                                            @if($item->is_featured)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-nse-gold-100 text-nse-gold-800">Featured</span>
                                            @endif
                                            @if($item->category)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-nse-neutral-100 text-nse-neutral-700">{{ $item->category }}</span>
                                            @endif
                                        </div>

                                        @if($item->description)
                                            <p class="text-sm text-nse-neutral-700">{{ $item->description }}</p>
                                        @endif

                                        <div class="mt-2 text-xs text-nse-neutral-600 space-y-0.5">
                                            @if($item->track)
                                                <p><span class="font-semibold text-nse-neutral-700">Track:</span> {{ $item->track }}</p>
                                            @endif
                                            @if($item->location)
                                                <p><span class="font-semibold text-nse-neutral-700">Location:</span> {{ $item->location }}</p>
                                            @endif
                                            @if($item->speaker_name)
                                                <p><span class="font-semibold text-nse-neutral-700">Speaker:</span> {{ $item->speaker_name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-nse-neutral-50 border border-nse-neutral-200 rounded-lg">
                <svg class="w-14 h-14 text-nse-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-semibold text-nse-neutral-900 mb-2">Programme will be published soon</h3>
                <p class="text-sm text-nse-neutral-600">Conference sessions and daily activities will appear here once configured by the admin team.</p>
            </div>
        @endif

        <div class="mt-10 text-center bg-nse-green-700 text-white rounded-lg p-8">
            <h2 class="text-xl font-bold mb-2">Ready to participate?</h2>
            <p class="text-sm text-white/90 mb-4">Register now to access full conference activities and session updates.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2 rounded bg-white text-nse-green-700 font-semibold hover:bg-nse-neutral-100">Register</a>
        </div>
    </div>
</section>
@endsection
