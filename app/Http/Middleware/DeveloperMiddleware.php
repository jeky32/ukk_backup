<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeveloperMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan punya role developer atau designer
        if (!auth()->check() || (!auth()->user()->isDeveloper() && !auth()->user()->isDesigner())) {
            abort(403, 'Unauthorized access. Developer/Designer only.');
        }

        return $next($request);
    }
}
