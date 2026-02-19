<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckIn extends Model
{
    protected $table = 'check_ins';

    protected $fillable = [
        'registration_id',
        'check_in_time',
        'source',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}
