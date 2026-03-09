<?php

namespace App\Http\Middleware;

use App\Models\Startup;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StartupAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startupId = session('startup_id');

        if (!$startupId) {
            return redirect()->route('startup.login')
                ->with('error', 'Please login to access the startup portal.');
        }

        $startup = Startup::find($startupId);

        if (!$startup) {
            session()->forget('startup_id');
            return redirect()->route('startup.login')
                ->with('error', 'Startup account not found. Please login again.');
        }

        if (!$startup->isActive()) {
            session()->forget('startup_id');
            return redirect()->route('startup.login')
                ->with('error', 'Your startup account has been deactivated. Please contact the administrator.');
        }

        // Share startup with all views
        view()->share('startup', $startup);
        
        // Add startup to request for controller access
        $request->merge(['startup' => $startup]);

        return $next($request);
    }
}
