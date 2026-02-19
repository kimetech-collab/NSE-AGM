<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\SystemSetting;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class StreamController extends Controller
{
    public function show(Request $request)
    {
        $token = $request->input('token');
        if (! $token) {
            return view('stream', ['error' => 'Missing access token.']);
        }

        $registration = Registration::where('ticket_token', $token)->first();
        if (! $registration) {
            return view('stream', ['error' => 'Invalid access token.']);
        }

        if ($registration->payment_status !== 'paid') {
            return view('stream', ['error' => 'Payment not confirmed.']);
        }

        $settings = collect();
        if (Schema::hasTable('system_settings')) {
            $settings = SystemSetting::whereIn('key', [
                'stream_enabled',
                'stream_platform',
                'stream_primary_url',
                'stream_backup_url',
            ])->pluck('value', 'key');
        }

        return view('stream', [
            'registration' => $registration,
            'token' => $token,
            'streamEnabled' => ($settings['stream_enabled'] ?? '1') === '1',
            'platform' => $settings['stream_platform'] ?? 'Zoom',
            'primaryUrl' => $settings['stream_primary_url'] ?? null,
            'backupUrl' => $settings['stream_backup_url'] ?? null,
            'sessionId' => Str::uuid()->toString(),
        ]);
    }

    public function start(Request $request, AttendanceService $attendance)
    {
        $data = $request->validate([
            'token' => 'required|string',
            'session_id' => 'required|string|max:64',
            'platform' => 'nullable|string|max:32',
        ]);

        $registration = Registration::where('ticket_token', $data['token'])->firstOrFail();

        $attendance->startSession($registration, $data['session_id'], $data['platform'] ?? null, [
            'source' => 'stream',
        ]);

        return response()->json(['ok' => true]);
    }

    public function heartbeat(Request $request, AttendanceService $attendance)
    {
        $data = $request->validate([
            'token' => 'required|string',
            'session_id' => 'required|string|max:64',
        ]);

        $registration = Registration::where('ticket_token', $data['token'])->firstOrFail();
        $result = $attendance->heartbeat($registration, $data['session_id'], 60);

        return response()->json($result);
    }

    public function end(Request $request, AttendanceService $attendance)
    {
        $data = $request->validate([
            'token' => 'required|string',
            'session_id' => 'required|string|max:64',
        ]);

        $registration = Registration::where('ticket_token', $data['token'])->firstOrFail();
        $result = $attendance->endSession($registration, $data['session_id']);

        return response()->json($result);
    }

    public function progress(Request $request, AttendanceService $attendance)
    {
        $data = $request->validate([
            'token' => 'required|string',
            'session_id' => 'required|string|max:64',
        ]);

        $registration = Registration::where('ticket_token', $data['token'])->firstOrFail();
        $elapsed = $attendance->getSessionDuration($registration, $data['session_id']);

        return response()->json([
            'elapsed' => $elapsed,
            'formatted' => gmdate('H:i:s', $elapsed),
        ]);
    }

    public function showProgress(Request $request)
    {
        $token = $request->input('token');
        $sessionId = $request->input('session_id');

        if (!$token || !$sessionId) {
            return view('stream-progress', ['error' => 'Missing access parameters.']);
        }

        $registration = Registration::where('ticket_token', $token)->first();
        if (!$registration) {
            return view('stream-progress', ['error' => 'Invalid access token.']);
        }

        return view('stream-progress', [
            'token' => $token,
            'sessionId' => $sessionId,
        ]);
    }
}
