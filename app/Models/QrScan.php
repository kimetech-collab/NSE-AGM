<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrScan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'registration_id',
        'scanned_by',
        'status',
        'token',
        'metadata',
        'scanned_at',
        'ip_address',
    ];

    protected $casts = [
        'metadata' => 'array',
        'scanned_at' => 'datetime',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
