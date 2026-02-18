<?php

namespace App\Mail;

use App\Models\PaymentTransaction;
use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RefundInitiated extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Registration $registration,
        public PaymentTransaction $transaction,
        public array $refundData = []
    ) {
    }

    public function build(): self
    {
        return $this->subject('Refund Initiated')
            ->view('emails.refund_initiated')
            ->with([
                'registration' => $this->registration,
                'transaction' => $this->transaction,
                'refundData' => $this->refundData,
            ]);
    }
}

