<?php

namespace App\Mail;

use App\Models\PaymentTransaction;
use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmed extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Registration $registration,
        public PaymentTransaction $transaction
    ) {
    }

    public function build(): self
    {
        return $this->subject('Payment Confirmed')
            ->view('emails.payment_confirmed')
            ->with([
                'registration' => $this->registration,
                'transaction' => $this->transaction,
            ]);
    }
}
