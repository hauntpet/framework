<?php
namespace Haunt\Framework\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Haunt\Framework\Facades\Haunt;
use Symfony\Component\HttpFoundation\Response;

class IsInstalledMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if(!Haunt::isInstalled()) {
            return redirect('/install');
        }

        return $next($request);
    }
}
