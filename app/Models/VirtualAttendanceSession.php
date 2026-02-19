<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualAttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'session_id',
        'platform',
        'started_at',
        'last_heartbeat_at',
        'ended_at',
        'total_seconds',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'last_heartbeat_at' => 'datetime',
        'ended_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
