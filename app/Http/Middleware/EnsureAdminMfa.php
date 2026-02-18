<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminMfa
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Require confirmed 2FA for admin area access.
        if (! $user->two_factor_confirmed_at) {
            return redirect()
                ->route('two-factor.show')
                ->with('error', 'Please enable and confirm two-factor authentication to access admin routes.');
        }

        return $next($request);
    }
}
