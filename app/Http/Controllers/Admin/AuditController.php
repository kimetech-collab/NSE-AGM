<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuditController extends Controller
{
    private function shouldExcludeRouteAccess(Request $request): bool
    {
        return $request->has('exclude_route_access')
            ? $request->boolean('exclude_route_access')
            : true;
    }

    /**
     * Display a listing of audit logs
     */
    public function index(Request $request)
    {
        try {
            $query = AuditLog::query();

            // Filter by actor/user
            if ($request->filled('actor_id')) {
                $query->byActor($request->actor_id);
            }

            // Filter by action
            if ($request->filled('action')) {
                $query->byAction($request->action);
            }

            if ($this->shouldExcludeRouteAccess($request)) {
                $query->where('action', '!=', 'admin.route.accessed');
            }

            // Filter by entity type
            if ($request->filled('entity_type')) {
                $query->where('entity_type', $request->entity_type);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->byStatus($request->status);
            }

            // Filter by date range
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $from = Carbon::createFromFormat('Y-m-d', $request->date_from)->startOfDay();
                $to = Carbon::createFromFormat('Y-m-d', $request->date_to)->endOfDay();
                $query->byDateRange($from, $to);
            }

            // Filter by entity_id
            if ($request->filled('entity_id')) {
                $query->where('entity_id', $request->entity_id);
            }

            // Search in metadata
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('action', 'like', "%{$search}%")
                        ->orWhere('entity_type', 'like', "%{$search}%")
                        ->orWhereJsonContains('metadata', $search);
                });
            }

            // Get unique actions for filter dropdown
            $actions = AuditLog::select('action')
                ->distinct()
                ->orderBy('action')
                ->pluck('action')
                ->filter()
                ->values();

            // Get unique entity types for filter dropdown
            $entityTypes = AuditLog::select('entity_type')
                ->distinct()
                ->orderBy('entity_type')
                ->pluck('entity_type')
                ->filter()
                ->values();

            // Get users for actor filter
            $users = User::whereIn('id', 
                AuditLog::select('actor_id')->distinct()->pluck('actor_id')->filter()
            )
                ->orderBy('name')
                ->get();

            // Order and paginate
            $logs = $query->latest()->paginate(50);

            return view('admin.audit.index', compact('logs', 'actions', 'entityTypes', 'users'));
        } catch (\Exception $e) {
            Log::error('Error loading audit logs', ['error' => $e->getMessage()]);
            return view('admin.audit.index', [
                'logs' => collect(),
                'actions' => collect(),
                'entityTypes' => collect(),
                'users' => collect(),
            ])->with('error', 'Error loading audit logs. Please ensure the migration has been run.');
        }
    }

    /**
     * Show a specific audit log entry
     */
    public function show($auditLog)
    {
        // Handle both AuditLog instance and ID
        if (!$auditLog instanceof AuditLog) {
            $auditLog = AuditLog::findOrFail($auditLog);
        }

        // Get related audit logs for the same entity
        $relatedLogs = AuditLog::where('entity_type', $auditLog->entity_type)
            ->where('entity_id', $auditLog->entity_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.audit.show', compact('auditLog', 'relatedLogs'));
    }

    /**
     * Export audit logs to CSV
     */
    public function export(Request $request)
    {
        $query = AuditLog::query();

        // Apply same filters as index
        if ($request->filled('actor_id')) {
            $query->byActor($request->actor_id);
        }
        if ($request->filled('action')) {
            $query->byAction($request->action);
        }
        if ($this->shouldExcludeRouteAccess($request)) {
            $query->where('action', '!=', 'admin.route.accessed');
        }
        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->entity_type);
        }
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $from = Carbon::createFromFormat('Y-m-d', $request->date_from)->startOfDay();
            $to = Carbon::createFromFormat('Y-m-d', $request->date_to)->endOfDay();
            $query->byDateRange($from, $to);
        }
        if ($request->filled('entity_id')) {
            $query->where('entity_id', $request->entity_id);
        }

        $logs = $query->latest()->get();

        $filename = 'audit-logs-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'ID',
                'Timestamp',
                'Actor',
                'Action',
                'Entity Type',
                'Entity ID',
                'Status',
                'IP Address',
                'Changes',
                'Metadata',
                'Error Message',
            ]);

            // Data rows
            foreach ($logs as $log) {
                $changes = json_encode($log->changes);
                $metadata = json_encode($log->metadata ?? []);

                fputcsv($handle, [
                    $log->id,
                    $log->formatted_date,
                    $log->actor_name,
                    $log->formatted_action,
                    $log->entity_type,
                    $log->entity_id,
                    $log->status,
                    $log->ip_address,
                    $changes,
                    $metadata,
                    $log->error_message,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Get audit trail for a specific entity (JSON response for AJAX)
     */
    public function trail(string $entityType, int $entityId)
    {
        $logs = AuditLog::byEntity($entityType, $entityId)
            ->latest()
            ->get();

        return response()->json([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'logs' => $logs->map(fn ($log) => [
                'id' => $log->id,
                'timestamp' => $log->formatted_date,
                'actor' => $log->actor_name,
                'action' => $log->formatted_action,
                'status' => $log->status,
                'changes' => $log->changes,
                'metadata' => $log->metadata,
            ]),
        ]);
    }
}
