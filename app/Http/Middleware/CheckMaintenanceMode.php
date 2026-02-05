<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     * Check if maintenance mode is enabled and block non-admin users.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        $maintenanceMode = Setting::get('maintenance_mode', false);

        if ($maintenanceMode) {
            // Allow admin users to pass through
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                
                // Admins can always access
                if ($user->is_admin || $user->isSuperAdmin()) {
                    return $next($request);
                }
            }

            // For AJAX/API requests, return JSON response
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'System is currently under maintenance. Please try again later.',
                    'maintenance' => true
                ], 503);
            }

            // For regular requests, show maintenance page
            return response()->view('maintenance', [
                'message' => 'We are currently performing scheduled maintenance.',
                'contact_email' => Setting::get('contact_email', ''),
                'system_name' => Setting::get('system_name', 'UP Management System'),
            ], 503);
        }

        return $next($request);
    }
}
