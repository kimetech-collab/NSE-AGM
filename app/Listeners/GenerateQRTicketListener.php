<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use App\Services\QRService;

class GenerateQRTicketListener
{
    public function __construct(
        protected QRService $qr
    ) {
    }

    public function handle(PaymentConfirmed $event): void
    {
        $this->qr->generateToken($event->registration);
    }
}
