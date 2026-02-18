<?php

namespace App\Listeners;

use App\Events\RegistrationCreated;
use App\Events\RegistrationOtpResent;
use App\Services\AuditService;

class LogRegistrationAuditListener
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

    public function handle(RegistrationCreated|RegistrationOtpResent $event): void
    {
        $action = $event instanceof RegistrationCreated ? 'created' : 'otp_resent';

        $this->audit->logRegistration($action, $event->registration->id, [
            'email' => $event->registration->email,
            'is_member' => $event->registration->is_member,
        ]);
    }
}

