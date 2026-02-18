<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Cache::remember('admin_kpi_total_regs', 60, function () {
            return Registration::count();
        });

        $paid = Cache::remember('admin_kpi_paid_regs', 60, function () {
            return Registration::where('payment_status', 'paid')->count();
        });

        return view('admin.dashboard', ['total' => $total, 'paid' => $paid]);
    }
}
