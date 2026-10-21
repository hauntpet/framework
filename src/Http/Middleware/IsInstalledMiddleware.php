<?php

namespace Haunt\Framework\Http\Middleware;

use Closure;
use Haunt\Framework\Facades\Haunt;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsInstalledMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Haunt::isInstalled()) {
            abort(403, 'Haunt has not been installed.');
        }

        return $next($request);
    }
}
