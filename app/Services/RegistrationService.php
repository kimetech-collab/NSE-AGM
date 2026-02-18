<?php

namespace App\Services;

use App\Models\Registration;

class RegistrationService
{
    /**
     * Create a registration and return the model instance.
     * Price should be locked by the caller using registration timestamp.
     */
    public function create(array $data): Registration
    {
        return Registration::create($data);
    }

    public function generateOtp(Registration $registration): string
    {
        // simple 6-digit otp for MVP; replace with more robust flow later
        $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        // store OTP in cache with TTL (e.g., 10 minutes) — callers should queue email
        cache()->put('registration_otp_'.$registration->id, $otp, now()->addMinutes(10));
        return $otp;
    }

    public function verifyOtp(Registration $registration, string $otp): bool
    {
        $key = 'registration_otp_'.$registration->id;
        $cached = cache()->get($key);
        if ($cached && hash_equals($cached, $otp)) {
            cache()->forget($key);
            $registration->update(['email_verified_at' => now()]);
            return true;
        }
        return false;
    }
}
