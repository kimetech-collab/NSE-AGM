<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'programme_date',
        'start_time',
        'end_time',
        'location',
        'track',
        'speaker_name',
        'description',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'programme_date' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query
            ->orderBy('programme_date')
            ->orderBy('start_time')
            ->orderBy('sort_order')
            ->orderBy('title');
    }
}
