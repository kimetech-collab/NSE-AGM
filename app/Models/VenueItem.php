<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'title',
        'content',
        'meta',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'meta' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('section')->orderBy('sort_order')->orderBy('title');
    }
}
