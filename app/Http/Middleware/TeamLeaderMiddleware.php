<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TeamLeaderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Redirect team leaders to intern portal when session expires
            return redirect()->route('intern.portal')
                ->with('error', 'Your session has expired. Please login again.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Allow super admins to access team leader routes (for testing/oversight)
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user is a team leader
        if (!$user->isTeamLeader()) {
            abort(403, 'Access denied. Team Leader privileges required.');
        }

        // Check if team leader account is active
        if (!$user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('intern.portal')
                ->with('error', 'Your Team Leader access has been suspended. Please contact the administrator.');
        }

        // Note: School assignment is checked in the controller methods as needed

        return $next($request);
    }
}
