<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use App\Events\RefundInitiated;
use App\Services\AuditService;

class LogPaymentAuditListener
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

    public function handle(PaymentConfirmed|RefundInitiated $event): void
    {
        if ($event instanceof PaymentConfirmed) {
            $this->audit->logPayment('confirmed', $event->transaction->id, [
                'registration_id' => $event->registration->id,
                'reference' => $event->transaction->provider_reference,
            ]);
            return;
        }

        $this->audit->logRefund('initiated', $event->transaction->id, [
            'registration_id' => $event->registration->id,
            'reference' => $event->transaction->provider_reference,
            'paystack_response' => $event->refundData,
        ]);
    }
}

