@extends('layouts.public')

@section('title', 'Speakers — NSE 59th AGM Portal')

@section('content')
<section class="bg-white py-16 sm:py-20" aria-labelledby="speakers-page-heading">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <p class="text-nse-neutral-500 text-xs font-semibold uppercase tracking-widest mb-2">Expert Presenters</p>
            <h1 id="speakers-page-heading" class="text-3xl font-bold text-nse-neutral-900">Our Speakers</h1>
            <p class="text-sm text-nse-neutral-600 mt-3 max-w-2xl mx-auto">
                Hear from industry leaders, visionary entrepreneurs, and expert professionals who will share their insights and experiences at the NSE 59th AGM & International Conference.
            </p>
        </div>

        <!-- Search & Filter -->
        @if($speakers->count() > 0)
        <div class="mb-10 bg-nse-neutral-50 border border-nse-neutral-200 rounded-lg p-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input 
                        type="text" 
                        id="speaker-search" 
                        placeholder="Search speakers by name or organization..."
                        class="w-full border border-nse-neutral-300 rounded px-3 py-2 text-sm"
                        aria-label="Search speakers"
                    >
                </div>
                <div class="flex gap-2">
                    <button 
                        data-filter="all" 
                        class="speaker-filter px-4 py-2 rounded text-sm font-medium transition-colors border border-nse-green-700 text-white bg-nse-green-700 hover:bg-nse-green-800"
                    >
                        All
                    </button>
                    <button 
                        data-filter="keynote" 
                        class="speaker-filter px-4 py-2 rounded text-sm font-medium transition-colors border border-nse-neutral-300 text-nse-neutral-700 hover:border-nse-green-700 hover:text-nse-green-700"
                    >
                        Keynote
                    </button>
                    <button 
                        data-filter="invited" 
                        class="speaker-filter px-4 py-2 rounded text-sm font-medium transition-colors border border-nse-neutral-300 text-nse-neutral-700 hover:border-nse-green-700 hover:text-nse-green-700"
                    >
                        Speakers
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Keynote Speakers Section -->
        @if($keynote_speakers->count() > 0)
        <div class="mb-16">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-nse-neutral-900 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 bg-nse-green-600 rounded-full"></span>
                    Keynote Speakers
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" role="list" aria-label="Keynote speakers list">
                @foreach($keynote_speakers as $speaker)
                    <article 
                        role="listitem" 
                        class="speaker-card bg-white border border-nse-neutral-200 rounded-lg overflow-hidden"
                        data-name="{{ $speaker->full_name }}"
                        data-organization="{{ $speaker->organization }}"
                        data-category="keynote"
                    >
                        <!-- Photo -->
                        <div class="relative bg-nse-neutral-50 h-64 flex items-center justify-center overflow-hidden">
                            @if($speaker->photo_url)
                                <img 
                                    src="{{ $speaker->photo_url }}" 
                                    alt="{{ $speaker->full_name }}" 
                                    class="w-full h-full object-cover"
                                />
                            @else
                                <div class="flex flex-col items-center justify-center text-nse-neutral-400">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-xs mt-2">No photo</span>
                                </div>
                            @endif
                            <div class="absolute top-3 right-3 bg-nse-green-100 text-nse-green-800 px-2.5 py-1 rounded-full text-xs font-semibold">
                                Keynote
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex flex-col h-full">
                            <div>
                                <h3 class="text-lg font-semibold text-nse-neutral-900">{{ $speaker->full_name }}</h3>
                                @if($speaker->title)
                                    <p class="text-sm font-semibold text-nse-green-600 mt-1">{{ $speaker->title }}</p>
                                @endif
                                @if($speaker->organization)
                                    <p class="text-sm text-nse-neutral-600 mt-1">{{ $speaker->organization }}</p>
                                @endif
                            </div>

                            @if($speaker->bio)
                                <p class="text-nse-neutral-600 text-sm mt-4 line-clamp-3">{{ $speaker->bio }}</p>
                            @endif

                            @if($speaker->expertise_topics && is_array($speaker->expertise_topics) && count($speaker->expertise_topics) > 0)
                                <div class="flex flex-wrap gap-2 mt-4">
                                    @foreach(array_slice($speaker->expertise_topics, 0, 3) as $topic)
                                        <span class="inline-block bg-nse-green-50 text-nse-green-700 text-xs px-2.5 py-1 rounded-full">
                                            {{ $topic }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            @if($speaker->session_title)
                                <div class="mt-4 pt-4 border-t border-nse-neutral-200">
                                    <p class="text-xs text-nse-neutral-500 font-semibold uppercase tracking-wider">Session</p>
                                    <p class="text-sm font-semibold text-nse-neutral-900 mt-1">{{ $speaker->session_title }}</p>
                                </div>
                            @endif

                            <!-- Social Links -->
                            @if($speaker->website_url || $speaker->twitter_url || $speaker->linkedin_url)
                                <div class="flex gap-3 mt-auto pt-4">
                                    @if($speaker->website_url)
                                        <a 
                                            href="{{ $speaker->website_url }}" 
                                            target="_blank" 
                                            rel="noopener noreferrer"
                                            class="text-nse-neutral-400 hover:text-nse-green-600 transition-colors"
                                            aria-label="Visit {{ $speaker->full_name }}'s website"
                                        >
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm0 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0 1c-4.968 0-9 4.032-9 9s4.032 9 9 9 9-4.032 9-9-4.032-9-9-9zm0 2c3.859 0 7 3.141 7 7s-3.141 7-7 7-7-3.141-7-7 3.141-7 7-7zm-4 7c0-2.209 1.791-4 4-4s4 1.791 4 4-1.791 4-4 4-4-1.791-4-4z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($speaker->twitter_url)
                                        <a 
                                            href="{{ $speaker->twitter_url }}" 
                                            target="_blank" 
                                            rel="noopener noreferrer"
                                            class="text-nse-neutral-400 hover:text-nse-green-600 transition-colors"
                                            aria-label="Visit {{ $speaker->full_name }} on Twitter"
                                        >
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2s9 5 20 5a9.5 9.5 0 00-9-5.5c4.75 2.25 7-7 7-7s1.1 1.5 1.1 1.5z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($speaker->linkedin_url)
                                        <a 
                                            href="{{ $speaker->linkedin_url }}" 
                                            target="_blank" 
                                            rel="noopener noreferrer"
                                            class="text-nse-neutral-400 hover:text-nse-green-600 transition-colors"
                                            aria-label="Visit {{ $speaker->full_name }} on LinkedIn"
                                        >
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Invited Speakers Section -->
        @if($invited_speakers->count() > 0)
        <div>
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-nse-neutral-900 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 bg-nse-neutral-400 rounded-full"></span>
                    Invited Speakers
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" role="list" aria-label="Invited speakers list">
                @foreach($invited_speakers as $speaker)
                    <article 
                        role="listitem" 
                        class="speaker-card bg-white border border-nse-neutral-200 rounded-lg overflow-hidden"
                        data-name="{{ $speaker->full_name }}"
                        data-organization="{{ $speaker->organization }}"
                        data-category="invited"
                    >
                        <!-- Photo -->
                        <div class="relative bg-nse-neutral-50 h-64 flex items-center justify-center overflow-hidden">
                            @if($speaker->photo_url)
                                <img 
                                    src="{{ $speaker->photo_url }}" 
                                    alt="{{ $speaker->full_name }}" 
                                    class="w-full h-full object-cover"
                                />
                            @else
                                <div class="flex flex-col items-center justify-center text-nse-neutral-400">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-xs mt-2">No photo</span>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex flex-col h-full">
                            <div>
                                <h3 class="text-lg font-semibold text-nse-neutral-900">{{ $speaker->full_name }}</h3>
                                @if($speaker->title)
                                    <p class="text-sm font-semibold text-nse-green-600 mt-1">{{ $speaker->title }}</p>
                                @endif
                                @if($speaker->organization)
                                    <p class="text-sm text-nse-neutral-600 mt-1">{{ $speaker->organization }}</p>
                                @endif
                            </div>

                            @if($speaker->bio)
                                <p class="text-nse-neutral-600 text-sm mt-4 line-clamp-3">{{ $speaker->bio }}</p>
                            @endif

                            @if($speaker->expertise_topics && is_array($speaker->expertise_topics) && count($speaker->expertise_topics) > 0)
                                <div class="flex flex-wrap gap-2 mt-4">
                                    @foreach(array_slice($speaker->expertise_topics, 0, 3) as $topic)
                                        <span class="inline-block bg-nse-neutral-100 text-nse-neutral-700 text-xs px-2.5 py-1 rounded-full">
                                            {{ $topic }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            @if($speaker->session_title)
                                <div class="mt-4 pt-4 border-t border-nse-neutral-200">
                                    <p class="text-xs text-nse-neutral-500 font-semibold uppercase tracking-wider">Session</p>
                                    <p class="text-sm font-semibold text-nse-neutral-900 mt-1">{{ $speaker->session_title }}</p>
                                </div>
                            @endif

                            <!-- Social Links -->
                            @if($speaker->website_url || $speaker->twitter_url || $speaker->linkedin_url)
                                <div class="flex gap-3 mt-auto pt-4">
                                    @if($speaker->website_url)
                                        <a 
                                            href="{{ $speaker->website_url }}" 
                                            target="_blank" 
                                            rel="noopener noreferrer"
                                            class="text-nse-neutral-400 hover:text-nse-green-600 transition-colors"
                                            aria-label="Visit {{ $speaker->full_name }}'s website"
                                        >
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm0 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0 1c-4.968 0-9 4.032-9 9s4.032 9 9 9 9-4.032 9-9-4.032-9-9-9zm0 2c3.859 0 7 3.141 7 7s-3.141 7-7 7-7-3.141-7-7 3.141-7 7-7zm-4 7c0-2.209 1.791-4 4-4s4 1.791 4 4-1.791 4-4 4-4-1.791-4-4z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($speaker->twitter_url)
                                        <a 
                                            href="{{ $speaker->twitter_url }}" 
                                            target="_blank" 
                                            rel="noopener noreferrer"
                                            class="text-nse-neutral-400 hover:text-nse-green-600 transition-colors"
                                            aria-label="Visit {{ $speaker->full_name }} on Twitter"
                                        >
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2s9 5 20 5a9.5 9.5 0 00-9-5.5c4.75 2.25 7-7 7-7s1.1 1.5 1.1 1.5z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($speaker->linkedin_url)
                                        <a 
                                            href="{{ $speaker->linkedin_url }}" 
                                            target="_blank" 
                                            rel="noopener noreferrer"
                                            class="text-nse-neutral-400 hover:text-nse-green-600 transition-colors"
                                            aria-label="Visit {{ $speaker->full_name }} on LinkedIn"
                                        >
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Empty State -->
        @if($speakers->count() === 0)
        <div class="text-center py-12 bg-nse-neutral-50 border border-nse-neutral-200 rounded-lg">
            <svg class="w-16 h-16 text-nse-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0v2h5v-2a9 9 0 00-9-9H6a9 9 0 00-9 9v2h5v-2z"/>
            </svg>
            <h3 class="text-lg font-semibold text-nse-neutral-900 mb-2">No Speakers Yet</h3>
            <p class="text-nse-neutral-600">Our speaker lineup will be announced soon. Check back soon!</p>
        </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('speaker-search');
        const filterButtons = document.querySelectorAll('.speaker-filter');
        const speakerCards = document.querySelectorAll('.speaker-card');
        let activeFilterCategory = 'all';

        function filterSpeakers() {
            const searchTerm = searchInput.value.toLowerCase();

            speakerCards.forEach(card => {
                const name = card.dataset.name.toLowerCase();
                const organization = (card.dataset.organization || '').toLowerCase();
                const category = card.dataset.category;

                const matchesSearch = name.includes(searchTerm) || organization.includes(searchTerm);
                const matchesCategory = activeFilterCategory === 'all' || category === activeFilterCategory;

                if (matchesSearch && matchesCategory) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Search functionality
        if (searchInput) {
            searchInput.addEventListener('input', filterSpeakers);
        }

        // Filter buttons
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => {
                    btn.classList.remove('border-nse-green-700', 'bg-nse-green-700', 'text-white');
                    btn.classList.add('border-nse-neutral-300', 'text-nse-neutral-700');
                });

                this.classList.remove('border-nse-neutral-300', 'text-nse-neutral-700');
                this.classList.add('border-nse-green-700', 'bg-nse-green-700', 'text-white');

                activeFilterCategory = this.dataset.filter;
                filterSpeakers();
            });
        });
    });
</script>
@endpush
@endsection
