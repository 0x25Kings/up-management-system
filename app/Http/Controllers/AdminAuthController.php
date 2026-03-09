<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminAuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        // If already logged in, redirect appropriately
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check Team Leader first (to avoid confusion with is_admin flag)
            if ($user->isTeamLeader()) {
                return redirect()->route('team-leader.dashboard');
            }
            
            // Then check for Super Admin
            if ($user->isSuperAdmin() || $user->is_admin) {
                return redirect()->route('admin.dashboard');
            }
        }
        
        return view('admin.login');
    }

    /**
     * Handle admin login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is super admin
            if ($user->isSuperAdmin() || $user->is_admin) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }
            
            // Check if user is team leader
            if ($user->isTeamLeader()) {
                // Check if team leader account is active
                if (!$user->isActive()) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Your Team Leader access has been suspended. Please contact the administrator.',
                    ])->withInput($request->only('email'));
                }
                
                $request->session()->regenerate();
                return redirect()->intended(route('team-leader.dashboard'));
            }

            // If not admin or team leader, logout and show error
            Auth::logout();
            return back()->withErrors([
                'email' => 'You do not have admin or team leader privileges.',
            ])->withInput($request->only('email'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        // Check if this is a team leader logout (redirect to intern portal)
        $redirectToIntern = $request->has('redirect_to') && $request->input('redirect_to') === 'intern';
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect team leaders to intern portal instead of admin login
        if ($redirectToIntern) {
            return redirect()->route('intern.portal');
        }

        return redirect()->route('admin.login');
    }
}
