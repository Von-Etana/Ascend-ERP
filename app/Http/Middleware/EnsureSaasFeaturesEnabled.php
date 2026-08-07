<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSaasFeaturesEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (saas_features_enabled()) {
            return $next($request);
        }

        return $request->user()
            ? redirect()->route('portal.dashboard')
            : redirect()->route('login');
    }
}
