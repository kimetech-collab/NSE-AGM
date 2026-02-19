@extends('layouts.public')

@section('title', 'Virtual Stream Access')

@section('content')
    <section class="bg-nse-neutral-50 py-6 border-b border-nse-neutral-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-10">
            <h1 class="text-2xl font-bold text-nse-neutral-900">Virtual Attendance</h1>
            <p class="text-sm text-nse-neutral-600 mt-2">Join the live stream to qualify for your certificate. You need at least 10 minutes of attendance.</p>

            {{-- Attendance Progress Tracker --}}
            <div class="mt-6 p-4 bg-white rounded-lg border border-nse-neutral-200">
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-nse-neutral-700">Your attendance progress</span>
                            <span class="text-sm font-mono text-nse-green-700" x-data="{ minutes: 0, seconds: 0 }" x-init="
                                const token = @json($token ?? null);
                                if (!token) return;
                                fetch('{{ route('stream.progress') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ token }) })
                                  .then(r => r.json())
                                  .then(d => { this.minutes = Math.floor(d.total_seconds / 60); this.seconds = d.total_seconds % 60; })
                                  .catch(() => {});
                                setInterval(() => {
                                  fetch('{{ route('stream.progress') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ token }) })
                                    .then(r => r.json())
                                    .then(d => { this.minutes = Math.floor(d.total_seconds / 60); this.seconds = d.total_seconds % 60; })
                                    .catch(() => {});
                                }, 10000);
                            "><span x-text="String(minutes).padStart(2,'0')">00</span>:<span x-text="String(seconds).padStart(2,'0')">00</span> / 10:00</span>
                        </div>
                        <div class="w-full bg-nse-neutral-200 rounded-full h-2 overflow-hidden">
                            <div x-data="{ width: 0 }" x-init="
                                const token = @json($token ?? null);
                                if (!token) return;
                                fetch('{{ route('stream.progress') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ token }) })
                                  .then(r => r.json())
                                  .then(d => { this.width = Math.min(100, (d.total_seconds / 600) * 100); })
                                  .catch(() => {});
                                setInterval(() => {
                                  fetch('{{ route('stream.progress') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ token }) })
                                    .then(r => r.json())
                                    .then(d => { this.width = Math.min(100, (d.total_seconds / 600) * 100); })
                                    .catch(() => {});
                                }, 10000);
                            " class="h-full bg-nse-green-700 transition-all duration-300" :style="{ width: width + '%' }"></div>
                        </div>
                    </div>
                    <div class="text-right text-xs">
                        <div class="text-nse-neutral-500" x-data="{ pct: 0 }" x-init="
                            const token = @json($token ?? null);
                            if (!token) return;
                            fetch('{{ route('stream.progress') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ token }) })
                              .then(r => r.json())
                              .then(d => { this.pct = Math.min(100, Math.round((d.total_seconds / 600) * 100)); })
                              .catch(() => {});
                            setInterval(() => {
                              fetch('{{ route('stream.progress') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ token }) })
                                .then(r => r.json())
                                .then(d => { this.pct = Math.min(100, Math.round((d.total_seconds / 600) * 100)); })
                                .catch(() => {});
                            }, 10000);
                        "><span x-text="pct">0</span>% complete</div>
                        <div x-data="{ ready: false }" x-init="
                            const token = @json($token ?? null);
                            if (!token) return;
                            fetch('{{ route('stream.progress') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ token }) })
                              .then(r => r.json())
                              .then(d => { this.ready = d.total_seconds >= 600; })
                              .catch(() => {});
                            setInterval(() => {
                              fetch('{{ route('stream.progress') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ token }) })
                                .then(r => r.json())
                                .then(d => { this.ready = d.total_seconds >= 600; })
                                .catch(() => {});
                            }, 10000);
                        " class="text-xs font-semibold" :class="ready ? 'text-nse-green-700' : 'text-nse-neutral-500'" x-text="ready ? '✓ Eligible' : 'In progress'">In progress</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-10 py-10">

            @if (!empty($error))
                <div class="mt-6 p-4 bg-red-100 text-red-800 rounded">
                    {{ $error }}
                </div>
            @else
                @if (! $streamEnabled)
                    <div class="mt-6 p-4 bg-nse-neutral-50 text-nse-neutral-700 rounded border border-nse-neutral-200">
                        Stream access is currently disabled. Please check back later.
                    </div>
                @else
                    <div class="mt-6 p-4 bg-nse-neutral-50 rounded border border-nse-neutral-200">
                        <div class="text-sm text-nse-neutral-600">Primary Platform: {{ $platform }}</div>
                    </div>

                    @if ($primaryUrl)
                        <div class="mt-4 aspect-video bg-black/5 rounded overflow-hidden border border-nse-neutral-200">
                            <iframe src="{{ $primaryUrl }}" class="w-full h-full" allowfullscreen></iframe>
                        </div>
                    @else
                        <div class="mt-4 p-4 bg-yellow-100 text-yellow-800 rounded">
                            Stream URL not configured.
                        </div>
                    @endif

                    @if ($backupUrl)
                        <div class="mt-4 text-sm">
                            Backup link: <a href="{{ $backupUrl }}" class="text-nse-green-700 underline" target="_blank" rel="noopener">Open backup stream</a>
                        </div>
                    @endif
                @endif
            @endif
        </div>
    </section>

    @if (empty($error) && $streamEnabled)
        <script>
            (function () {
                const token = @json($token ?? null);
                if (!token) return;

                const key = 'nse_stream_session_id';
                const existing = localStorage.getItem(key);
                const sessionId = existing || @json($sessionId ?? '');
                if (sessionId) localStorage.setItem(key, sessionId);

                function post(path, payload) {
                    return fetch(path, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify(payload),
                    });
                }

                post("{{ route('stream.start') }}", {
                    token,
                    session_id: sessionId,
                    platform: @json($platform ?? null),
                });

                const heartbeat = setInterval(() => {
                    post("{{ route('stream.heartbeat') }}", {
                        token,
                        session_id: sessionId,
                    });
                }, 60000);

                window.addEventListener('beforeunload', () => {
                    clearInterval(heartbeat);
                    fetch("{{ route('stream.end') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            token,
                            session_id: sessionId,
                        }),
                        keepalive: true,
                    });
                });
            })();
        </script>
    @endif
@endsection
