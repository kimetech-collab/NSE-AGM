<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use App\Mail\PaymentConfirmed as PaymentConfirmedMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SendPaymentConfirmationEmailListener
{
    public function handle(PaymentConfirmed $event): void
    {
        $idempotencyKey = sprintf(
            'payment_confirmed_mail_sent_%d_%d',
            $event->registration->id,
            $event->transaction->id
        );

        if (! Cache::add($idempotencyKey, true, now()->addHours(1))) {
            return;
        }

        Mail::to($event->registration->email)->queue(
            new PaymentConfirmedMail($event->registration, $event->transaction)
        );
    }
}

