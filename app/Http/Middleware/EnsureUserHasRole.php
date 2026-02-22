<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        if (empty($roles) || $user->hasRole(...$roles)) {
            return $next($request);
        }

        $rolePermissionMap = method_exists($user, 'rolePermissionMap') ? $user::rolePermissionMap() : [];
        foreach ($roles as $role) {
            $requiredPermissions = $rolePermissionMap[$role] ?? [];
            if (! empty($requiredPermissions) && $user->hasPermission(...$requiredPermissions)) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to access this resource.');
    }
}
