<?php

namespace App\Providers;

use App\Events\RegistrationCreated;
use App\Events\RegistrationOtpResent;
use App\Events\PaymentConfirmed;
use App\Events\RefundInitiated;
use App\Listeners\LogRegistrationAuditListener;
use App\Listeners\SendEmailVerificationOtpListener;
use App\Listeners\SendPaymentConfirmationEmailListener;
use App\Listeners\SendRefundInitiatedEmailListener;
use App\Listeners\InvalidatePaymentCacheListener;
use App\Listeners\LogPaymentAuditListener;
use App\Listeners\GenerateQRTicketListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Disable listener auto-discovery to avoid duplicate listener registration.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = false;

    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        RegistrationCreated::class => [
            SendEmailVerificationOtpListener::class,
            LogRegistrationAuditListener::class,
        ],
        RegistrationOtpResent::class => [
            SendEmailVerificationOtpListener::class,
            LogRegistrationAuditListener::class,
        ],
        PaymentConfirmed::class => [
            GenerateQRTicketListener::class,
            SendPaymentConfirmationEmailListener::class,
            InvalidatePaymentCacheListener::class,
            LogPaymentAuditListener::class,
        ],
        RefundInitiated::class => [
            SendRefundInitiatedEmailListener::class,
            InvalidatePaymentCacheListener::class,
            LogPaymentAuditListener::class,
        ],
    ];

}
