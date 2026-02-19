@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-5xl mx-auto">
        <h1 class="text-2xl font-bold mb-2">Accreditation Scanner</h1>
        <p class="text-sm text-nse-neutral-600 mb-6">Scan attendee QR codes for instant check-in validation.</p>

        {{-- Full-Screen Overlay (hidden by default) --}}
        <div id="scan-overlay" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="opacity: 0.95;">
            <div class="text-center text-white">
                <div id="overlay-content"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white border border-nse-neutral-200 rounded-lg p-4">
                <h2 class="text-sm font-semibold mb-3">Camera Scan</h2>
                <div class="relative bg-nse-neutral-50 border border-nse-neutral-200 rounded-lg overflow-hidden">
                    <video id="qr-video" class="w-full h-64 object-cover" autoplay muted playsinline></video>
                    <div class="absolute inset-0 border-2 border-nse-green-700/60 m-6 rounded-lg pointer-events-none"></div>
                </div>
                <div class="mt-3 flex gap-2">
                    <button id="qr-start" type="button" class="px-3 py-2 bg-nse-green-700 text-white rounded text-sm">Start Camera</button>
                    <button id="qr-stop" type="button" class="px-3 py-2 bg-nse-neutral-50 border rounded text-sm">Stop</button>
                </div>
                <p class="text-xs text-nse-neutral-600 mt-2">Uses native BarcodeDetector when available. Fallback to manual entry if unsupported.</p>
            </div>

            <div class="bg-white border border-nse-neutral-200 rounded-lg p-4">
                <h2 class="text-sm font-semibold mb-3">Manual Scan</h2>
                <form id="manual-scan-form" class="flex gap-3">
                    @csrf
                    <input id="qr-token" type="text" name="token" class="border p-2 flex-1" placeholder="Scan or paste QR token" required />
                    <button type="submit" class="px-4 py-2 bg-nse-green-700 text-white rounded">Scan</button>
                </form>

                <div class="mt-6 text-sm">
                    <a class="text-nse-green-700 underline" href="{{ route('admin.accreditation.offline') }}">Download offline cache (JSON)</a>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white border border-nse-neutral-200 rounded-lg p-4">
            <h2 class="text-sm font-semibold mb-3">Offline Sync</h2>
            <p class="text-xs text-nse-neutral-600 mb-3">Upload scan JSON from offline devices to sync check-ins.</p>
            <textarea id="offline-json" class="w-full border rounded p-2 text-xs h-32" placeholder='{"scans":[{"token":"...","scanned_at":"2026-02-18T09:30:00Z","meta":{"device":"tablet-1"}}]}'></textarea>
            <div class="mt-3 flex gap-2">
                <button id="offline-sync" type="button" class="px-3 py-2 bg-nse-green-700 text-white rounded text-sm">Sync Offline Scans</button>
                <span id="offline-status" class="text-xs text-nse-neutral-600"></span>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const video = document.getElementById('qr-video');
            const startBtn = document.getElementById('qr-start');
            const stopBtn = document.getElementById('qr-stop');
            const tokenInput = document.getElementById('qr-token');
            const manualForm = document.getElementById('manual-scan-form');
            const scanOverlay = document.getElementById('scan-overlay');
            const overlayContent = document.getElementById('overlay-content');
            let stream = null;
            let detector = null;
            let scanning = false;
            let dismissTimeout = null;

            function getOverlayHTML(result) {
                const status = result.status || '';
                const message = result.message || '';
                const name = result.registration?.name || '';
                const firstScan = result.first_scan_at || '';
                
                let icon = '';
                let color = 'bg-gray-600';
                let title = '';

                switch (status) {
                    case 'valid':
                        icon = '<svg class="w-24 h-24 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>';
                        color = 'bg-green-600';
                        title = '✓ Valid';
                        break;
                    case 'already_checked_in':
                        icon = '<svg class="w-24 h-24 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>';
                        color = 'bg-amber-500';
                        title = 'Already Checked In';
                        break;
                    case 'unpaid':
                        icon = '<svg class="w-24 h-24 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M13.477 14.894A5 5 0 115.873 6.12a.75.75 0 10-1.06-1.061A6.5 6.5 0 1114.561 13.927l1.414-1.414a.75.75 0 10-1.06-1.061l2.5-2.5a.75.75 0 010 1.061l-2.5 2.5a.75.75 0 01-1.06 0z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M5.75 10a.75.75 0 01.75-.75h4.131l-1.44-1.44a.75.75 0 111.06-1.061l2.5 2.5a.75.75 0 010 1.061l-2.5 2.5a.75.75 0 11-1.06-1.061l1.44-1.44H6.5a.75.75 0 01-.75-.75z" clip-rule="evenodd"/></svg>';
                        color = 'bg-red-600';
                        title = '✗ Unpaid';
                        break;
                    case 'refunded':
                        icon = '<svg class="w-24 h-24 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
                        color = 'bg-gray-600';
                        title = '🚫 Refunded';
                        break;
                    default:
                        icon = '<svg class="w-24 h-24 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
                        color = 'bg-red-600';
                        title = 'QR Not Found';
                }

                scanOverlay.className = `fixed inset-0 z-50 flex items-center justify-center ${color}`;
                return `
                    <div class="mb-6">
                        ${icon}
                        <h2 class="text-4xl font-bold mb-2">${title}</h2>
                    </div>
                    ${name ? `<p class="text-2xl font-semibold mb-3">${name}</p>` : ''}
                    <p class="text-lg opacity-90">${message}</p>
                    ${firstScan ? `<p class="text-sm opacity-75 mt-3">First check-in: ${firstScan}</p>` : ''}
                    <p class="text-xs opacity-60 mt-6">Closes automatically in a few seconds...</p>
                `;
            }

            function showOverlay(result) {
                overlayContent.innerHTML = getOverlayHTML(result);
                scanOverlay.classList.remove('hidden');
                
                if (dismissTimeout) clearTimeout(dismissTimeout);
                dismissTimeout = setTimeout(() => {
                    scanOverlay.classList.add('hidden');
                    startCamera();
                }, 4000);
            }

            async function startCamera() {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) return;
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                    video.srcObject = stream;
                    scanning = true;
                    if ('BarcodeDetector' in window) {
                        detector = new BarcodeDetector({ formats: ['qr_code'] });
                        scanLoop();
                    }
                } catch (e) {
                    console.error('Camera access denied:', e);
                }
            }

            async function scanLoop() {
                if (!scanning || !detector) return;
                try {
                    const barcodes = await detector.detect(video);
                    if (barcodes.length) {
                        const token = barcodes[0].rawValue;
                        tokenInput.value = token;
                        performScan(token);
                        scanning = false;
                        stopCamera();
                        return;
                    }
                } catch (e) {}
                requestAnimationFrame(scanLoop);
            }

            function stopCamera() {
                scanning = false;
                if (stream) {
                    stream.getTracks().forEach(t => t.stop());
                    stream = null;
                }
                video.srcObject = null;
            }

            async function performScan(token) {
                try {
                    const resp = await fetch("{{ route('admin.accreditation.scan') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ token }),
                    });
                    const result = await resp.json();
                    showOverlay(result);
                } catch (e) {
                    console.error('Scan failed:', e);
                    showOverlay({ status: 'invalid', message: 'Scan failed. Try again.', ok: false });
                }
            }

            startBtn?.addEventListener('click', startCamera);
            stopBtn?.addEventListener('click', stopCamera);
            scanOverlay?.addEventListener('click', () => {
                scanOverlay.classList.add('hidden');
                startCamera();
            });

            manualForm?.addEventListener('submit', (e) => {
                e.preventDefault();
                const token = tokenInput.value.trim();
                if (token) {
                    performScan(token);
                    tokenInput.value = '';
                }
            });

            const syncBtn = document.getElementById('offline-sync');
            const statusEl = document.getElementById('offline-status');
            syncBtn?.addEventListener('click', async () => {
                statusEl.textContent = 'Syncing...';
                try {
                    const payload = JSON.parse(document.getElementById('offline-json').value || '{}');
                    const resp = await fetch("{{ route('admin.accreditation.sync') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify(payload),
                    });
                    const data = await resp.json();
                    statusEl.textContent = resp.ok ? `Synced ${data.processed || 0} scans.` : 'Sync failed.';
                } catch (e) {
                    statusEl.textContent = 'Invalid JSON or network error.';
                }
            });
        })();
    </script>
@endsection
