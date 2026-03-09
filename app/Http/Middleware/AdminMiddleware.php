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
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
            }
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access admin area.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if user is admin (including super_admin) or team leader
        if (!$user->isAdmin() && !$user->isTeamLeader()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            }
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'You do not have the required privileges.');
        }

        return $next($request);
    }
}
