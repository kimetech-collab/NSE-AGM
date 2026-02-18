<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\QrScan;
use App\Models\Registration;
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

            $qrScansToday = 0;
            $checkinsToday = 0;
            if (Schema::hasTable('qr_scans')) {
                $qrScansToday = QrScan::where('scanned_at', '>=', $today)->count();
                $checkinsToday = QrScan::where('scanned_at', '>=', $today)
                    ->where('status', 'valid')
                    ->count();
            }

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

            return [
                'total' => $total,
                'paid' => $paid,
                'unpaid' => $unpaid,
                'refunded' => $refunded,
                'revenue' => $revenue,
                'avgTicket' => $avgTicket,
                'todayRegs' => $todayRegs,
                'last7Regs' => $last7Regs,
                'qrScansToday' => $qrScansToday,
                'checkinsToday' => $checkinsToday,
                'labels' => $labels,
                'series' => $series,
            ];
        });

        return view('admin.dashboard', $kpi);
    }
}
