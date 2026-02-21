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
            $this->writeAudit($request, 500, false, $e->getMessage());
            throw $e;
        }

        $ok = $response->getStatusCode() < 400;
        $this->writeAudit($request, $response->getStatusCode(), $ok);

        return $response;
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
