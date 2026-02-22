<?php

namespace App\Http\Middleware;

use App\Services\AuditService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class LogAdminRouteAccess
{
    public function __construct(
        protected AuditService $audit
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);
        } catch (\Throwable $e) {
            if ($this->shouldLog($request, 500, false)) {
                $this->writeAudit($request, 500, false, $e->getMessage());
            }
            throw $e;
        }

        $ok = $response->getStatusCode() < 400;
        if ($this->shouldLog($request, $response->getStatusCode(), $ok)) {
            $this->writeAudit($request, $response->getStatusCode(), $ok);
        }

        return $response;
    }

    protected function shouldLog(Request $request, int $statusCode, bool $ok): bool
    {
        $routeName = $request->route()?->getName() ?? '';

        if (str_starts_with($routeName, 'admin.audit')) {
            return false;
        }

        if (! $ok || $statusCode >= 400) {
            return true;
        }

        return in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true);
    }

    protected function writeAudit(Request $request, int $statusCode, bool $ok, ?string $error = null): void
    {
        if (! Schema::hasTable('audit_logs')) {
            return;
        }

        $route = $request->route();
        $routeName = $route?->getName() ?? 'unknown';
        $uri = $route?->uri() ?? $request->path();

        $this->audit->log(
            'admin.route.accessed',
            'AdminRoute',
            0,
            [
                'route_name' => $routeName,
                'uri' => $uri,
                'method' => $request->method(),
                'status_code' => $statusCode,
            ],
            null,
            null,
            $ok,
            $error
        );
    }
}
