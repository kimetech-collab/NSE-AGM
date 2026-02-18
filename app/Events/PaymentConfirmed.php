<?php

namespace App\Events;

use App\Models\PaymentTransaction;
use App\Models\Registration;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmed
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public PaymentTransaction $transaction,
        public Registration $registration
    ) {
    }
}

