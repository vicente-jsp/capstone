<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !$request->user()->isAdmin()) {
            // Redirect them to login or a general dashboard/home
            // Or abort with 403 Forbidden
            // abort(403, 'Unauthorized action.');
            return redirect('/dashboard')->with('error', 'Access denied: Administrators only.');
        }
        return $next($request);
    }
}
