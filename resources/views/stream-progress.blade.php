@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Card Container -->
        <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Stream Progress</h1>
                <p class="text-gray-300 text-sm">Live attendance tracker</p>
            </div>

            <!-- Progress Ring -->
            <div class="flex justify-center mb-8">
                <div class="relative w-48 h-48">
                    <!-- Background circle -->
                    <svg class="absolute inset-0 w-full h-full transform -rotate-90" viewBox="0 0 180 180">
                        <circle cx="90" cy="90" r="80" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="8" />
                        <!-- Progress circle -->
                        <circle 
                            id="progressCircle"
                            cx="90" cy="90" r="80" 
                            fill="none" 
                            stroke="url(#progressGradient)" 
                            stroke-width="8"
                            stroke-linecap="round"
                            stroke-dasharray="0 502.4"
                            class="transition-all duration-500"
                        />
                        <defs>
                            <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                    </svg>

                    <!-- Center display -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <div id="timeDisplay" class="text-4xl font-bold text-white font-mono">
                            00:00:00
                        </div>
                        <div class="text-gray-400 text-xs mt-2">elapsed time</div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="bg-white/5 rounded-lg p-4 text-center border border-white/10">
                    <div id="hourDisplay" class="text-2xl font-bold text-green-400">00</div>
                    <div class="text-xs text-gray-400 mt-1">hours</div>
                </div>
                <div class="bg-white/5 rounded-lg p-4 text-center border border-white/10">
                    <div id="minDisplay" class="text-2xl font-bold text-blue-400">00</div>
                    <div class="text-xs text-gray-400 mt-1">minutes</div>
                </div>
                <div class="bg-white/5 rounded-lg p-4 text-center border border-white/10">
                    <div id="secDisplay" class="text-2xl font-bold text-purple-400">00</div>
                    <div class="text-xs text-gray-400 mt-1">seconds</div>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-green-500/10 border border-green-500/20 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-green-300 text-sm font-medium">Attendance in progress</span>
                </div>
            </div>

            <!-- Exit Button -->
            <button 
                id="exitButton"
                onclick="handleExit()"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                End Session
            </button>

            <!-- Footer Info -->
            <div class="mt-6 p-4 bg-white/5 rounded-lg border border-white/10">
                <p class="text-gray-400 text-xs text-center">
                    Your attendance is being tracked in real-time.<br>
                    Keep this window open to maintain your session.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
const token = new URLSearchParams(window.location.search).get('token');
const sessionId = new URLSearchParams(window.location.search).get('session_id');

let elapsedTime = 0;
const maxDuration = 8 * 3600; // 8 hours in seconds

// Initialize progress tracker
async function initializeProgress() {
    if (!token || !sessionId) {
        alert('Invalid access parameters');
        return;
    }

    // Fetch initial progress
    await updateProgress();

    // Update every second
    setInterval(updateProgress, 1000);

    // Heartbeat every 30 seconds
    setInterval(sendHeartbeat, 30000);
}

async function updateProgress() {
    try {
        const response = await fetch('{{ route("stream.progress") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                token: token,
                session_id: sessionId,
            }),
        });

        if (!response.ok) throw new Error('Progress fetch failed');

        const data = await response.json();
        elapsedTime = data.elapsed;

        // Update display
        const hours = Math.floor(elapsedTime / 3600);
        const minutes = Math.floor((elapsedTime % 3600) / 60);
        const seconds = elapsedTime % 60;

        document.getElementById('timeDisplay').textContent = 
            `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

        document.getElementById('hourDisplay').textContent = String(hours).padStart(2, '0');
        document.getElementById('minDisplay').textContent = String(minutes).padStart(2, '0');
        document.getElementById('secDisplay').textContent = String(seconds).padStart(2, '0');

        // Update progress circle
        const circumference = 2 * Math.PI * 80;
        const progress = Math.min(elapsedTime / maxDuration, 1);
        const dashOffset = circumference * (1 - progress);
        document.getElementById('progressCircle').style.strokeDasharray = `${circumference * progress} ${circumference}`;
    } catch (error) {
        console.error('Error updating progress:', error);
    }
}

async function sendHeartbeat() {
    try {
        await fetch('{{ route("stream.heartbeat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                token: token,
                session_id: sessionId,
            }),
        });
    } catch (error) {
        console.error('Heartbeat error:', error);
    }
}

async function handleExit() {
    if (!confirm('Are you sure you want to end your attendance session?')) {
        return;
    }

    try {
        const response = await fetch('{{ route("stream.end") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                token: token,
                session_id: sessionId,
            }),
        });

        if (response.ok) {
            alert('Session ended. Thank you for attending!');
            window.location.href = '{{ route("home") }}';
        } else {
            alert('Error ending session');
        }
    } catch (error) {
        console.error('Error ending session:', error);
        alert('Failed to end session. Please try again.');
    }
}

// Start on load
document.addEventListener('DOMContentLoaded', initializeProgress);

// Warn user before closing
window.addEventListener('beforeunload', (e) => {
    if (elapsedTime > 0) {
        e.preventDefault();
        e.returnValue = 'Your attendance session is still active. Are you sure you want to leave?';
    }
});
</script>

@endsection
