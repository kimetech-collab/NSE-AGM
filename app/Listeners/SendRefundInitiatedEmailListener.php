<?php

namespace App\Listeners;

use App\Events\RefundInitiated;
use App\Mail\RefundInitiated as RefundInitiatedMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SendRefundInitiatedEmailListener
{
    public function handle(RefundInitiated $event): void
    {
        $idempotencyKey = sprintf(
            'refund_initiated_mail_sent_%d_%d',
            $event->registration->id,
            $event->transaction->id
        );

        if (! Cache::add($idempotencyKey, true, now()->addHours(1))) {
            return;
        }

        Mail::to($event->registration->email)->queue(
            new RefundInitiatedMail($event->registration, $event->transaction, $event->refundData)
        );
    }
}

