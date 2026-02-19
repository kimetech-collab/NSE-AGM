<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'email', 'is_member', 'membership_number',
        'pricing_version_id', 'pricing_item_id', 'price_cents', 'currency',
        'registration_timestamp', 'email_verified_at', 'payment_status', 'ticket_token',
        'attendance_seconds', 'attendance_status', 'attendance_last_at', 'attendance_eligible_at',
    ];

    protected $casts = [
        'is_member' => 'boolean',
        'registration_timestamp' => 'datetime',
        'email_verified_at' => 'datetime',
        'attendance_last_at' => 'datetime',
        'attendance_eligible_at' => 'datetime',
    ];

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function qrScans()
    {
        return $this->hasMany(QrScan::class);
    }

    public function attendanceSessions()
    {
        return $this->hasMany(VirtualAttendanceSession::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class);
    }
}