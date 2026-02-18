<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Exception;

class AuditService
{
    /**
     * Log an audit entry
     */
    public function log(
        string $action,
        string $entityType,
        int|string $entityId,
        array $metadata = [],
        ?array $beforeState = null,
        ?array $afterState = null,
        bool $success = true,
        ?string $errorMessage = null
    ): AuditLog {
        try {
            return AuditLog::create([
                'actor_id' => Auth::id(),
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'before_state' => $beforeState,
                'after_state' => $afterState,
                'metadata' => $metadata,
                'ip_address' => Request::ip(),
                'user_agent' => Request::header('User-Agent'),
                'status' => $success ? 'Success' : 'Failure',
                'error_message' => $errorMessage,
            ]);
        } catch (Exception $e) {
            \Log::error('Failed to create audit log', [
                'action' => $action,
                'entity' => $entityType,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Log a registration action
     */
    public function logRegistration(
        string $action,
        int $registrationId,
        array $metadata = [],
        ?array $beforeState = null,
        ?array $afterState = null
    ): AuditLog {
        return $this->log(
            'registration.' . $action,
            'Registration',
            $registrationId,
            $metadata,
            $beforeState,
            $afterState
        );
    }

    /**
     * Log a payment action
     */
    public function logPayment(
        string $action,
        int $transactionId,
        array $metadata = [],
        ?array $beforeState = null,
        ?array $afterState = null
    ): AuditLog {
        return $this->log(
            'payment.' . $action,
            'PaymentTransaction',
            $transactionId,
            $metadata,
            $beforeState,
            $afterState
        );
    }

    /**
     * Log a refund action
     */
    public function logRefund(
        string $action,
        int $refundId,
        array $metadata = [],
        ?array $beforeState = null,
        ?array $afterState = null
    ): AuditLog {
        return $this->log(
            'refund.' . $action,
            'Refund',
            $refundId,
            $metadata,
            $beforeState,
            $afterState
        );
    }

    /**
     * Log a certificate action
     */
    public function logCertificate(
        string $action,
        int $certificateId,
        array $metadata = [],
        ?array $beforeState = null,
        ?array $afterState = null
    ): AuditLog {
        return $this->log(
            'certificate.' . $action,
            'Certificate',
            $certificateId,
            $metadata,
            $beforeState,
            $afterState
        );
    }

    /**
     * Log a user action
     */
    public function logUser(
        string $action,
        int $userId,
        array $metadata = [],
        ?array $beforeState = null,
        ?array $afterState = null
    ): AuditLog {
        return $this->log(
            'user.' . $action,
            'User',
            $userId,
            $metadata,
            $beforeState,
            $afterState
        );
    }

    /**
     * Log a settings action
     */
    public function logSettings(
        string $action,
        int $settingId,
        array $metadata = [],
        ?array $beforeState = null,
        ?array $afterState = null
    ): AuditLog {
        return $this->log(
            'settings.' . $action,
            'SystemSettings',
            $settingId,
            $metadata,
            $beforeState,
            $afterState
        );
    }

    /**
     * Log a failed action
     */
    public function logFailure(
        string $action,
        string $entityType,
        int|string $entityId,
        string $errorMessage,
        array $metadata = []
    ): AuditLog {
        return $this->log(
            $action,
            $entityType,
            $entityId,
            $metadata,
            null,
            null,
            false,
            $errorMessage
        );
    }

    /**
     * Get audit logs for a specific record
     */
    public function getAuditTrail(string $entityType, int $entityId, int $limit = 50)
    {
        return AuditLog::byEntity($entityType, $entityId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit logs for a specific user/actor
     */
    public function getUserAuditTrail(int $actorId, int $limit = 50)
    {
        return AuditLog::byActor($actorId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get all audit logs for a date range
     */
    public function getAuditsByDateRange(\Carbon\Carbon $from, \Carbon\Carbon $to, int $limit = 100)
    {
        return AuditLog::byDateRange($from, $to)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
