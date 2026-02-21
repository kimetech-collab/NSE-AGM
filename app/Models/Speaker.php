<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'title',
        'organization',
        'bio',
        'email',
        'phone',
        'photo_url',
        'website_url',
        'twitter_url',
        'linkedin_url',
        'expertise_topics',
        'session_title',
        'session_description',
        'session_time',
        'sort_order',
        'is_active',
        'is_keynote',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_keynote' => 'boolean',
        'expertise_topics' => 'array',
    ];

    /**
     * Get the speaker's full name
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Scope to get active speakers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get keynote speakers
     */
    public function scopeKeynote($query)
    {
        return $query->where('is_keynote', true);
    }

    /**
     * Scope to get invited speakers (non-keynote)
     */
    public function scopeInvited($query)
    {
        return $query->where('is_keynote', false);
    }

    /**
     * Get speakers ordered by sort order and name
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('is_keynote', 'DESC')
            ->orderBy('sort_order')
            ->orderBy('first_name')
            ->orderBy('last_name');
    }

    /**
     * Search speakers by name or organization
     */
    public function scopeSearch($query, $term)
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            // Use FULLTEXT search for MySQL/MariaDB, LIKE for others
            if (config('database.default') === 'mysql' || config('database.default') === 'mariadb') {
                $q->whereRaw("MATCH(first_name, last_name, organization, bio) AGAINST(? IN BOOLEAN MODE)", [$term]);
            } else {
                // Fallback to LIKE for SQLite and other databases
                $q->where('first_name', 'LIKE', "%{$term}%")
                    ->orWhere('last_name', 'LIKE', "%{$term}%")
                    ->orWhere('organization', 'LIKE', "%{$term}%")
                    ->orWhere('expertise_topics', 'LIKE', "%{$term}%");
            }
        });
    }
}
