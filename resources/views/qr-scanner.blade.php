@extends('layouts.app')

@section('content')
<div class="fixed inset-0 bg-black/95 z-50 flex flex-col">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-4 py-4 flex items-center justify-between">
        <h1 class="text-white text-xl font-bold">QR Code Scanner</h1>
        <button onclick="closeScanner()" class="text-white hover:bg-white/20 p-2 rounded-lg transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Main Scanner Area -->
    <div class="flex-1 flex flex-col items-center justify-center relative overflow-hidden">
        <!-- Video Stream -->
        <video
            id="scannerVideo"
            class="absolute inset-0 w-full h-full object-cover"
            playsinline
        ></video>

        <!-- Scanner Overlay Frame -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div class="relative w-80 h-80">
                <!-- Scan Frame -->
                <div class="absolute inset-0 border-4 border-cyan-400 rounded-2xl shadow-lg shadow-cyan-500/50"></div>

                <!-- Corner Brackets -->
                <div class="absolute top-0 left-0 w-12 h-12 border-t-4 border-l-4 border-emerald-400 rounded-tl-xl"></div>
                <div class="absolute top-0 right-0 w-12 h-12 border-t-4 border-r-4 border-emerald-400 rounded-tr-xl"></div>
                <div class="absolute bottom-0 left-0 w-12 h-12 border-b-4 border-l-4 border-emerald-400 rounded-bl-xl"></div>
                <div class="absolute bottom-0 right-0 w-12 h-12 border-b-4 border-r-4 border-emerald-400 rounded-br-xl"></div>

                <!-- Scanning Line Animation -->
                <div 
                    id="scanLine"
                    class="absolute left-0 right-0 h-1 bg-gradient-to-r from-transparent via-cyan-400 to-transparent animate-pulse"
                    style="top: 50%; animation: scan 2s infinite;"
                ></div>
            </div>
        </div>

        <!-- Dimmed Area Outside Frame -->
        <div class="absolute inset-0 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <mask id="scannerMask">
                        <rect width="100" height="100" fill="white"/>
                        <circle cx="50" cy="50" r="30" fill="black"/>
                    </mask>
                </defs>
                <rect width="100" height="100" fill="rgba(0,0,0,0.7)" mask="url(#scannerMask)"/>
            </svg>
        </div>
    </div>

    <!-- Footer with Controls & Status -->
    <div class="bg-slate-900 border-t border-white/10 px-4 py-6">
        <!-- Status Display -->
        <div id="scanStatus" class="text-center mb-6">
            <p class="text-gray-400 text-sm mb-2">Point camera at QR code</p>
            <div id="scanResult" class="min-h-6"></div>
        </div>

        <!-- Control Buttons -->
        <div class="flex gap-3 justify-center">
            <button 
                id="torchBtn"
                onclick="toggleTorch()"
                class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200 flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2c1.1 0 2 .9 2 2v3h-4V4c0-1.1.9-2 2-2zm9 7h-6V7c0-.55.45-1 1-1s1 .45 1 1v1h4V7c0-.55.45-1 1-1s1 .45 1 1v2zm-15 1H1c-.55 0-1 .45-1 1v2c0 .55.45 1 1 1h4v8c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2v-8h4c.55 0 1-.45 1-1v-2c0-.55-.45-1-1-1h-6v-1c0-.55-.45-1-1-1s-1 .45-1 1v1H6v-1c0-.55-.45-1-1-1s-1 .45-1 1v1z"/>
                </svg>
                <span id="torchText">Torch On</span>
            </button>

            <button 
                onclick="closeScanner()"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200 flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Close
            </button>
        </div>

        <!-- Info -->
        <p class="text-gray-500 text-xs text-center mt-4">
            Scanner will automatically detect and process QR codes
        </p>
    </div>
</div>

<!-- Loading Indicator -->
<div id="loadingIndicator" class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 hidden">
    <div class="text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-cyan-400 mx-auto mb-4"></div>
        <p class="text-white text-sm">Initializing camera...</p>
    </div>
</div>

<!-- jsQR Library -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

<style>
@keyframes scan {
    0%, 100% { top: 30%; opacity: 0.5; }
    50% { top: 70%; opacity: 1; }
}
</style>

<script>
const video = document.getElementById('scannerVideo');
const canvas = document.createElement('canvas');
const ctx = canvas.getContext('2d');
let scanning = true;
let torchEnabled = false;
const scannedCodes = new Set();
const debounceTime = 1500; // Prevent duplicate scans within 1.5 seconds
let lastScanTime = 0;

const purpose = new URLSearchParams(window.location.search).get('purpose') || 'check-in';
const token = new URLSearchParams(window.location.search).get('token');

async function initializeScanner() {
    document.getElementById('loadingIndicator').classList.remove('hidden');

    try {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'environment',
                width: { ideal: 1280 },
                height: { ideal: 720 },
            },
            audio: false,
        });

        video.srcObject = stream;
        video.onloadedmetadata = () => {
            video.play();
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            document.getElementById('loadingIndicator').classList.add('hidden');
            scanQRCodes();
        };
    } catch (error) {
        console.error('Camera access error:', error);
        document.getElementById('scanResult').innerHTML = `
            <div class="text-red-400 text-center">
                <p class="font-semibold">Camera access denied</p>
                <p class="text-sm mt-1">Please enable camera permissions to use the scanner</p>
            </div>
        `;
        document.getElementById('loadingIndicator').classList.add('hidden');
    }
}

function scanQRCodes() {
    if (!scanning) return;

    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height);

        if (code && code.data) {
            const now = Date.now();
            if (now - lastScanTime > debounceTime && !scannedCodes.has(code.data)) {
                scannedCodes.add(code.data);
                lastScanTime = now;
                handleQRScan(code.data);
            }
        }
    }

    requestAnimationFrame(scanQRCodes);
}

async function handleQRScan(qrData) {
    document.getElementById('scanResult').innerHTML = `
        <div class="text-yellow-400 text-center">
            <p class="text-sm font-semibold">QR Code detected...</p>
            <p class="text-xs mt-1">Processing: ${qrData.substring(0, 30)}...</p>
        </div>
    `;

    try {
        const response = await fetch('{{ route("qr.process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                qr_data: qrData,
                purpose: purpose,
                token: token,
            }),
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // Success - show green status
            document.getElementById('scanResult').innerHTML = `
                <div class="text-green-400 text-center animate-pulse">
                    <p class="text-sm font-semibold">✓ ${data.message || 'Successfully processed'}</p>
                    <p class="text-xs mt-1">${data.details || ''}</p>
                </div>
            `;

            // Auto-close after 2 seconds on success
            setTimeout(() => {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    closeScanner();
                }
            }, 2000);
        } else {
            // Error - show red status and allow retry
            document.getElementById('scanResult').innerHTML = `
                <div class="text-red-400 text-center">
                    <p class="text-sm font-semibold">✗ ${data.message || 'Processing failed'}</p>
                    <p class="text-xs mt-1">${data.details || 'Try again'}</p>
                </div>
            `;
            scannedCodes.delete(qrData); // Allow retry
        }
    } catch (error) {
        console.error('QR processing error:', error);
        document.getElementById('scanResult').innerHTML = `
            <div class="text-red-400 text-center">
                <p class="text-sm font-semibold">Error processing QR code</p>
                <p class="text-xs mt-1">Please try again</p>
            </div>
        `;
        scannedCodes.delete(qrData); // Allow retry
    }
}

async function toggleTorch() {
    try {
        const stream = video.srcObject;
        const track = stream.getVideoTracks()[0];
        const capabilities = track.getCapabilities();

        if (!capabilities.torch) {
            alert('Torch not available on this device');
            return;
        }

        torchEnabled = !torchEnabled;
        await track.applyConstraints({
            advanced: [{ torch: torchEnabled }],
        });

        const btn = document.getElementById('torchBtn');
        const text = document.getElementById('torchText');
        if (torchEnabled) {
            btn.classList.remove('bg-yellow-600', 'hover:bg-yellow-700');
            btn.classList.add('bg-orange-600', 'hover:bg-orange-700');
            text.textContent = 'Torch Off';
        } else {
            btn.classList.remove('bg-orange-600', 'hover:bg-orange-700');
            btn.classList.add('bg-yellow-600', 'hover:bg-yellow-700');
            text.textContent = 'Torch On';
        }
    } catch (error) {
        console.error('Torch error:', error);
    }
}

function closeScanner() {
    scanning = false;
    const stream = video.srcObject;
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
    history.back();
}

// Initialize on load
document.addEventListener('DOMContentLoaded', initializeScanner);

// Cleanup on unload
window.addEventListener('beforeunload', () => {
    scanning = false;
    const stream = video.srcObject;
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
});
</script>

@endsection
