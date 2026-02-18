<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingItem extends Model
{
    use HasFactory;

    protected $fillable = ['pricing_version_id','name','description','price_cents','currency'];

    public function version()
    {
        return $this->belongsTo(PricingVersion::class, 'pricing_version_id');
    }
}
