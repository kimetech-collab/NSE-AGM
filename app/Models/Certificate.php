<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'certificate_id',
        'status',
        'issued_at',
        'revoked_at',
        'metadata',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'revoked_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
