<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyPaystackSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('X-Paystack-Signature');
        $secret = config('services.paystack.secret') ?? env('PAYSTACK_SECRET');

        if (! $secret || ! $signature) {
            return response('Missing signature', 400);
        }

        $computed = hash_hmac('sha512', $request->getContent(), $secret);

        if (! hash_equals($computed, $signature)) {
            return response('Invalid signature', 403);
        }

        return $next($request);
    }
}
