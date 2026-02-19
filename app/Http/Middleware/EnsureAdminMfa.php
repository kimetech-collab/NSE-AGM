<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminMfa
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (Schema::hasTable('system_settings')) {
            $required = SystemSetting::where('key', 'admin_mfa_required')->value('value');
            if ($required === '0') {
                return $next($request);
            }
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
