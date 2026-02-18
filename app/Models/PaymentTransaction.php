<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['registration_id','provider','provider_reference','paystack_transaction_id','amount_cents','currency','status','payload'];

    protected $casts = [
        'payload' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
