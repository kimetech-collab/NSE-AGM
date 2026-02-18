<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingVersion extends Model
{
    use HasFactory;

    protected $fillable = ['version_name', 'starts_at', 'ends_at'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(PricingItem::class);
    }
}
