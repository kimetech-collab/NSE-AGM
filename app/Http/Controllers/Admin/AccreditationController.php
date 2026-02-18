<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Services\QRService;
use Illuminate\Http\Request;

class AccreditationController extends Controller
{
    public function __construct(
        protected QRService $qr
    ) {
    }

    public function index()
    {
        return view('admin.accreditation.index');
    }

    public function scan(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string',
        ]);

        $result = $this->qr->validateToken($data['token']);
        $this->qr->logScan($data['token'], $result, ['source' => 'admin_scan']);

        return redirect()->back()->with('scan_result', $result);
    }

    public function offlineCache()
    {
        $items = Registration::where('payment_status', 'paid')
            ->whereNotNull('ticket_token')
            ->select(['id', 'name', 'email', 'ticket_token', 'payment_status'])
            ->orderBy('id')
            ->get();

        return response()->json([
            'generated_at' => now()->toIso8601String(),
            'count' => $items->count(),
            'items' => $items,
        ]);
    }

    public function syncCache(Request $request)
    {
        $payload = $request->validate([
            'scans' => 'required|array',
            'scans.*.token' => 'required|string',
            'scans.*.scanned_at' => 'nullable|date',
            'scans.*.meta' => 'nullable|array',
        ]);

        $processed = 0;

        foreach ($payload['scans'] as $scan) {
            $result = $this->qr->validateToken($scan['token']);
            $this->qr->logScan($scan['token'], $result, array_merge(['source' => 'offline_sync'], $scan['meta'] ?? []));
            $processed++;
        }

        return response()->json(['processed' => $processed]);
    }
}
