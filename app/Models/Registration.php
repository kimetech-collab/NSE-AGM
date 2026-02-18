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
    ];

    protected $casts = [
        'is_member' => 'boolean',
        'registration_timestamp' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}
