<?php

namespace App\Listeners;

use App\Events\RegistrationCreated;
use App\Events\RegistrationOtpResent;
use App\Mail\RegistrationOtp as RegistrationOtpMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SendEmailVerificationOtpListener
{
    public function handle(RegistrationCreated|RegistrationOtpResent $event): void
    {
        // Guard against accidental duplicate listener registration/dispatch.
        $idempotencyKey = sprintf(
            'registration_otp_mail_sent_%d_%s',
            $event->registration->id,
            $event->otp
        );

        if (! Cache::add($idempotencyKey, true, now()->addMinutes(10))) {
            return;
        }

        Mail::to($event->registration->email)->queue(
            new RegistrationOtpMail($event->registration, $event->otp)
        );
    }
}
