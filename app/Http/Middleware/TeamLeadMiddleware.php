<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamLeadMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan punya role teamlead
        if (!auth()->check() || !auth()->user()->isTeamLead()) {
            abort(403, 'Unauthorized access. TeamLead only.');
        }

        return $next($request);
    }
}
