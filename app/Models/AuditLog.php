<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class AuditLog extends Model
{
    use HasFactory;

    const UPDATED_AT = null; // Immutable audit logs

    protected $fillable = [
        'actor_id',
        'action',
        'entity_type',
        'entity_id',
        'before_state',
        'after_state',
        'metadata',
        'ip_address',
        'user_agent',
        'status',
        'error_message',
    ];

    protected $casts = [
        'before_state' => 'array',
        'after_state' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relationship: Actor (User who performed the action)
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * Get formatted action name (human-readable)
     */
    public function getFormattedActionAttribute(): string
    {
        $actions = [
            'registration.created' => 'Registration Created',
            'registration.updated' => 'Registration Updated',
            'payment.received' => 'Payment Received',
            'refund.initiated' => 'Refund Initiated',
            'refund.completed' => 'Refund Completed',
            'certificate.issued' => 'Certificate Issued',
            'certificate.revoked' => 'Certificate Revoked',
            'user.created' => 'User Created',
            'user.updated' => 'User Updated',
            'user.deleted' => 'User Deleted',
            'role.assigned' => 'Role Assigned',
            'settings.updated' => 'Settings Updated',
        ];

        return $actions[$this->action] ?? ucwords(str_replace('.', ' / ', $this->action));
    }

    /**
     * Get status badge class for display
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'Success' => 'bg-green-100 text-green-800',
            'Failure' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Format timestamp for display
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at?->format('M d, Y @ H:i:s') ?? '';
    }

    /**
     * Get relative time (e.g. "2 hours ago")
     */
    public function getRelativeTimeAttribute(): string
    {
        return $this->created_at?->diffForHumans() ?? '';
    }

    /**
     * Get changes between before and after states
     */
    public function getChangesAttribute(): array
    {
        if (!$this->before_state || !$this->after_state) {
            return [];
        }

        $changes = [];
        foreach ($this->after_state as $key => $after) {
            $before = $this->before_state[$key] ?? null;
            if ($before !== $after) {
                $changes[$key] = [
                    'from' => $before,
                    'to' => $after,
                ];
            }
        }

        return $changes;
    }

    /**
     * Get actor name (User.name or "System")
     */
    public function getActorNameAttribute(): string
    {
        return $this->actor?->name ?? ($this->actor_id ? 'User #' . $this->actor_id : 'System');
    }

    /**
     * Scope: Filter by action
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Filter by entity
     */
    public function scopeByEntity($query, string $entityType, ?int $entityId = null)
    {
        $query->where('entity_type', $entityType);
        if ($entityId) {
            $query->where('entity_id', $entityId);
        }
        return $query;
    }

    /**
     * Scope: Filter by actor
     */
    public function scopeByActor($query, int $actorId)
    {
        return $query->where('actor_id', $actorId);
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeByDateRange($query, Carbon $from, Carbon $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Recent first
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
