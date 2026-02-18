<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use App\Events\RefundInitiated;
use Illuminate\Support\Facades\Cache;

class InvalidatePaymentCacheListener
{
    public function handle(PaymentConfirmed|RefundInitiated $event): void
    {
        Cache::forget('admin_kpi_dashboard');
    }
}
