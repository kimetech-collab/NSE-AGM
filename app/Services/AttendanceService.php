<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\VirtualAttendanceSession;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function startSession(Registration $registration, string $sessionId, ?string $platform = null, array $metadata = []): VirtualAttendanceSession
    {
        return VirtualAttendanceSession::updateOrCreate(
            ['registration_id' => $registration->id, 'session_id' => $sessionId],
            [
                'platform' => $platform,
                'started_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'metadata' => $metadata,
            ]
        );
    }

    public function heartbeat(Registration $registration, string $sessionId, int $seconds = 60): array
    {
        $session = VirtualAttendanceSession::where('registration_id', $registration->id)
            ->where('session_id', $sessionId)
            ->first();

        if (! $session) {
            return ['ok' => false, 'message' => 'Session not found'];
        }

        if ($session->last_heartbeat_at && $session->last_heartbeat_at->gt(now()->subSeconds(50))) {
            return ['ok' => true, 'message' => 'Duplicate heartbeat ignored'];
        }

        return DB::transaction(function () use ($registration, $session, $seconds) {
            $session->update([
                'last_heartbeat_at' => now(),
                'total_seconds' => $session->total_seconds + $seconds,
            ]);

            $registration->attendance_seconds += $seconds;
            $registration->attendance_last_at = now();

            if ($registration->attendance_seconds >= 600 && $registration->attendance_status !== 'virtual') {
                $registration->attendance_status = 'virtual';
                $registration->attendance_eligible_at = now();
            }

            $registration->save();

            return ['ok' => true, 'message' => 'Heartbeat recorded'];
        });
    }

    public function endSession(Registration $registration, string $sessionId): array
    {
        $session = VirtualAttendanceSession::where('registration_id', $registration->id)
            ->where('session_id', $sessionId)
            ->first();

        if (! $session) {
            return ['ok' => false, 'message' => 'Session not found'];
        }

        $session->update(['ended_at' => now()]);

        return ['ok' => true, 'message' => 'Session ended'];
    }
}
