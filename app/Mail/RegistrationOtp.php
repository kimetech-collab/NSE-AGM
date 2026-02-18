<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Registration;

class RegistrationOtp extends Mailable
{
    use Queueable, SerializesModels;

    public Registration $registration;
    public string $otp;

    public function __construct(Registration $registration, string $otp)
    {
        $this->registration = $registration;
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Your registration OTP')
            ->view('emails.registration_otp')
            ->with(['registration' => $this->registration, 'otp' => $this->otp]);
    }
}
