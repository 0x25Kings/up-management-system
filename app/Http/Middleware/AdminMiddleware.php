<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access admin area.');
        }

        $user = Auth::user();

        // Check if user is admin or team leader
        if (!$user->is_admin && !$user->isTeamLeader()) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'You do not have the required privileges.');
        }

        return $next($request);
    }
}
