<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !$request->user()->isStudent()) {
            if (!Auth::check()) {
                return redirect()->route('login');
             } else {
                // Logged in, but not a student - maybe redirect to role choice or show error
                return redirect()->route('choose-role.show')->with('error', 'Access denied: Student area only.');
             }
        }
        return $next($request);
    }
}
