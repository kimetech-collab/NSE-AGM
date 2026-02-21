<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrScan;
use App\Models\Registration;
use App\Services\QRService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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

        if ($request->expectsJson() || $request->wantsJson() || $request->isJson()) {
            return response()->json($result);
        }

        return redirect()->back()->with('scan_result', $result);
    }

    public function offlineCache()
    {
        $items = Registration::where('payment_status', 'paid')
            ->whereNotNull('ticket_token')
            ->select(['id', 'name', 'email', 'ticket_token', 'payment_status'])
            ->orderBy('id')
            ->get();

        $checkedIds = [];
        $firstScans = collect();
        if (Schema::hasTable('qr_scans') && $items->isNotEmpty()) {
            $checkedIds = QrScan::whereIn('registration_id', $items->pluck('id'))
                ->where('status', 'valid')
                ->distinct()
                ->pluck('registration_id')
                ->all();

            $firstScans = QrScan::whereIn('registration_id', $items->pluck('id'))
                ->where('status', 'valid')
                ->selectRaw('registration_id, MIN(scanned_at) as first_scan_at')
                ->groupBy('registration_id')
                ->get()
                ->keyBy('registration_id');
        }

        $items = $items->map(function ($item) use ($checkedIds, $firstScans) {
            $firstScan = $firstScans[$item->id]->first_scan_at ?? null;
            return [
                'id' => $item->id,
                'name' => $item->name,
                'email' => $item->email,
                'ticket_token' => $item->ticket_token,
                'payment_status' => $item->payment_status,
                'checked_in' => in_array($item->id, $checkedIds, true),
                'first_scan_at' => $firstScan,
            ];
        });

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
            $this->qr->logScan(
                $scan['token'],
                $result,
                array_merge(['source' => 'offline_sync'], $scan['meta'] ?? []),
                $scan['scanned_at'] ?? null
            );
            $processed++;
        }

        return response()->json(['processed' => $processed]);
    }
}
