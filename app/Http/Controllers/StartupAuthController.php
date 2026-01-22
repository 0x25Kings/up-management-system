<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StartupAuthController extends Controller
{
    /**
     * Show the startup login form
     */
    public function showLoginForm()
    {
        // If already logged in, redirect to dashboard
        if (session('startup_id')) {
            return redirect()->route('startup.dashboard');
        }

        return view('startup.login');
    }

    /**
     * Handle startup code verification (step 1)
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'startup_code' => 'required|string',
        ]);

        $startup = Startup::where('startup_code', strtoupper($request->startup_code))->first();

        if (!$startup) {
            return back()
                ->withInput($request->only('startup_code'))
                ->withErrors(['startup_code' => 'Invalid startup code.']);
        }

        if (!$startup->isActive()) {
            return back()
                ->withInput($request->only('startup_code'))
                ->withErrors(['startup_code' => 'Your account has been deactivated. Please contact the administrator.']);
        }

        // Check if password is set
        if (!$startup->password_set) {
            // Redirect to set password page
            session(['startup_setup_id' => $startup->id]);
            return redirect()->route('startup.set-password');
        }

        // Password is set, show login form with password field
        return view('startup.login-password', [
            'startup' => $startup,
            'startup_code' => $startup->startup_code,
        ]);
    }

    /**
     * Show the set password form for new accounts
     */
    public function showSetPasswordForm()
    {
        $startupId = session('startup_setup_id');
        
        if (!$startupId) {
            return redirect()->route('startup.login')
                ->withErrors(['startup_code' => 'Please enter your startup code first.']);
        }

        $startup = Startup::find($startupId);
        
        if (!$startup) {
            session()->forget('startup_setup_id');
            return redirect()->route('startup.login')
                ->withErrors(['startup_code' => 'Invalid startup code.']);
        }

        if ($startup->password_set) {
            session()->forget('startup_setup_id');
            return redirect()->route('startup.login')
                ->with('info', 'Password already set. Please login with your password.');
        }

        return view('startup.set-password', compact('startup'));
    }

    /**
     * Handle password setup for new accounts
     */
    public function setPassword(Request $request)
    {
        $startupId = session('startup_setup_id');
        
        if (!$startupId) {
            return redirect()->route('startup.login')
                ->withErrors(['startup_code' => 'Please enter your startup code first.']);
        }

        $startup = Startup::find($startupId);
        
        if (!$startup) {
            session()->forget('startup_setup_id');
            return redirect()->route('startup.login')
                ->withErrors(['startup_code' => 'Invalid startup code.']);
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Set the password
        $startup->update([
            'password' => Hash::make($request->password),
            'password_set' => true,
        ]);

        // Clear setup session and log in
        session()->forget('startup_setup_id');
        session(['startup_id' => $startup->id]);

        return redirect()->route('startup.dashboard')
            ->with('success', 'Password created successfully! Welcome to your dashboard, ' . $startup->company_name . '!');
    }

    /**
     * Handle startup login with password
     */
    public function login(Request $request)
    {
        $request->validate([
            'startup_code' => 'required|string',
            'password' => 'required|string',
        ]);

        $startup = Startup::where('startup_code', strtoupper($request->startup_code))->first();

        if (!$startup) {
            return back()
                ->withInput($request->only('startup_code'))
                ->withErrors(['startup_code' => 'Invalid startup code.']);
        }

        if (!$startup->password_set) {
            session(['startup_setup_id' => $startup->id]);
            return redirect()->route('startup.set-password');
        }

        if (!$startup->checkPassword($request->password)) {
            return back()
                ->withInput($request->only('startup_code'))
                ->withErrors(['password' => 'Invalid password.']);
        }

        if (!$startup->isActive()) {
            return back()
                ->withInput($request->only('startup_code'))
                ->withErrors(['startup_code' => 'Your account has been deactivated. Please contact the administrator.']);
        }

        // Store startup ID in session
        session(['startup_id' => $startup->id]);

        return redirect()->route('startup.dashboard')
            ->with('success', 'Welcome back, ' . $startup->company_name . '!');
    }

    /**
     * Handle startup logout
     */
    public function logout(Request $request)
    {
        session()->forget('startup_id');
        session()->forget('startup_setup_id');

        return redirect()->route('startup.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
