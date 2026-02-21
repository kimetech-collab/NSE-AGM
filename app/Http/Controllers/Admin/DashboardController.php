<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\QrScan;
use App\Models\Registration;
use App\Models\CheckIn;
use App\Support\EventDates;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $kpi = Cache::remember('admin_kpi_dashboard', 300, function () {
            $total = Registration::count();
            $paid = Registration::where('payment_status', 'paid')->count();
            $unpaid = Registration::whereIn('payment_status', ['pending', 'failed'])->count();
            $refunded = Registration::where('payment_status', 'refunded')->count();

            $revenueCents = (int) PaymentTransaction::where('status', 'success')->sum('amount_cents');
            $revenue = $revenueCents / 100;
            $paidCount = (int) PaymentTransaction::where('status', 'success')->count();
            $avgTicket = $paidCount > 0 ? round($revenue / $paidCount, 2) : 0.0;

            $today = now()->startOfDay();
            $todayRegs = Registration::where('registration_timestamp', '>=', $today)->count();
            $last7Regs = Registration::where('registration_timestamp', '>=', now()->subDays(6)->startOfDay())->count();

            // Check-ins metrics
            $checkinsToday = CheckIn::where('created_at', '>=', $today)->count();
            $uniqueCheckinsToday = CheckIn::where('created_at', '>=', $today)
                ->distinct('registration_id')
                ->count('registration_id');
            $totalCheckIns = CheckIn::count();
            $uniqueParticipants = CheckIn::distinct('registration_id')->count('registration_id');

            // QR scans (legacy)
            $qrScansToday = 0;
            if (Schema::hasTable('qr_scans')) {
                $qrScansToday = QrScan::where('scanned_at', '>=', $today)->count();
            }

            // Revenue series
            $start = now()->subDays(13)->startOfDay();
            $daily = Registration::where('registration_timestamp', '>=', $start)
                ->select(DB::raw('date(registration_timestamp) as day'), DB::raw('count(*) as total'))
                ->groupBy('day')
                ->orderBy('day')
                ->get()
                ->keyBy('day');

            $labels = [];
            $series = [];
            for ($i = 13; $i >= 0; $i--) {
                $day = now()->subDays($i)->toDateString();
                $labels[] = $day;
                $series[] = (int) ($daily[$day]->total ?? 0);
            }

            // Attendance conversion
            $attendanceRate = $paid > 0 ? round(($uniqueParticipants / $paid) * 100) : 0;

            $registrationOpenAt = EventDates::registrationOpenAt();
            $registrationCloseAt = EventDates::registrationCloseAt();
            $now = now();
            $registrationWindowOpen = EventDates::registrationWindowOpen();
            $registrationPhase = $now->lt($registrationOpenAt)
                ? 'upcoming'
                : ($now->gt($registrationCloseAt) ? 'closed' : 'open');
            $daysToOpen = $now->lt($registrationOpenAt) ? (int) ceil($now->diffInHours($registrationOpenAt) / 24) : 0;
            $daysToClose = $now->lt($registrationCloseAt) ? (int) ceil($now->diffInHours($registrationCloseAt) / 24) : 0;

            return [
                'total' => $total,
                'paid' => $paid,
                'unpaid' => $unpaid,
                'refunded' => $refunded,
                'revenue' => $revenue,
                'avgTicket' => $avgTicket,
                'todayRegs' => $todayRegs,
                'last7Regs' => $last7Regs,
                'checkinsToday' => $checkinsToday,
                'uniqueCheckinsToday' => $uniqueCheckinsToday,
                'totalCheckIns' => $totalCheckIns,
                'uniqueParticipants' => $uniqueParticipants,
                'attendanceRate' => $attendanceRate,
                'qrScansToday' => $qrScansToday,
                'labels' => $labels,
                'series' => $series,
                'registrationWindowOpen' => $registrationWindowOpen,
                'registrationPhase' => $registrationPhase,
                'registrationOpenAt' => $registrationOpenAt,
                'registrationCloseAt' => $registrationCloseAt,
                'daysToOpen' => $daysToOpen,
                'daysToClose' => $daysToClose,
            ];
        });

        return view('admin.dashboard', $kpi);
    }
}
