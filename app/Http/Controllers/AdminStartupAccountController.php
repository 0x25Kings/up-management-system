<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminStartupAccountController extends Controller
{
    /**
     * Get all startup accounts
     */
    public function index(Request $request)
    {
        $query = Startup::with('creator');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('startup_code', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Return as simple array for frontend compatibility
        $startups = $query->latest()->get();

        return response()->json($startups);
    }

    /**
     * Create a new startup account
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'room_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
        ]);

        $startupCode = Startup::generateStartupCode();

        $startup = Startup::create([
            'startup_code' => $startupCode,
            'password' => null, // No password - startup will set on first login
            'password_set' => false,
            'company_name' => $validated['company_name'],
            'contact_person' => $validated['contact_person'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'room_number' => $validated['room_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => 'active',
            'moa_status' => 'none',
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Startup account created successfully! Give the startup code to the company.',
            'startup' => $startup,
            'startup_code' => $startupCode,
        ]);
    }

    /**
     * Get a specific startup account
     */
    public function show(Startup $startup)
    {
        $startup->load(['creator', 'submissions', 'roomIssues']);

        return response()->json([
            'success' => true,
            'startup' => [
                'id' => $startup->id,
                'startup_code' => $startup->startup_code,
                'company_name' => $startup->company_name,
                'contact_person' => $startup->contact_person,
                'email' => $startup->email,
                'phone' => $startup->phone,
                'room_number' => $startup->room_number,
                'address' => $startup->address,
                'description' => $startup->description,
                'status' => $startup->status,
                'status_color' => $startup->status_color,
                'moa_status' => $startup->moa_status,
                'moa_status_color' => $startup->moa_status_color,
                'moa_expiry' => $startup->moa_expiry ? Carbon::parse($startup->moa_expiry)->format('M d, Y') : null,
                'created_by' => $startup->creator?->name,
                'created_at' => $startup->created_at->format('M d, Y h:i A'),
                'submission_count' => $startup->submissions->count(),
                'room_issue_count' => $startup->roomIssues->count(),
            ],
        ]);
    }

    /**
     * Update a startup account
     */
    public function update(Request $request, Startup $startup)
    {
        $validated = $request->validate([
            'company_name' => 'sometimes|required|string|max:255',
            'contact_person' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'room_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'status' => 'sometimes|in:active,inactive,suspended',
            'moa_status' => 'sometimes|in:none,pending,active,expired',
            'moa_expiry' => 'nullable|date',
        ]);

        $startup->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Startup account updated successfully!',
            'startup' => [
                'id' => $startup->id,
                'startup_code' => $startup->startup_code,
                'company_name' => $startup->company_name,
                'status' => $startup->status,
                'moa_status' => $startup->moa_status,
            ],
        ]);
    }

    /**
     * Reset startup password - generates a temporary password that startup must change on next login
     */
    public function resetPassword(Startup $startup)
    {
        // Generate a temporary password
        $tempPassword = Startup::generatePassword();

        $startup->update([
            'password' => $tempPassword, // Will be hashed by model mutator
            'password_set' => false, // Force startup to set new password on next login
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully! The startup will need to set a new password on their next login.',
            'temp_password' => $tempPassword,
        ]);
    }

    /**
     * Delete a startup account
     */
    public function destroy(Startup $startup)
    {
        $companyName = $startup->company_name;
        $startup->delete();

        return response()->json([
            'success' => true,
            'message' => "Startup account '{$companyName}' deleted successfully!",
        ]);
    }

    /**
     * Toggle startup status (activate/deactivate)
     */
    public function toggleStatus(Startup $startup)
    {
        $newStatus = $startup->status === 'active' ? 'inactive' : 'active';
        $startup->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => "Startup account " . ($newStatus === 'active' ? 'activated' : 'deactivated') . " successfully!",
            'status' => $newStatus,
        ]);
    }

    /**
     * Get startup statistics
     */
    public function statistics()
    {
        $totalStartups = Startup::count();
        $activeStartups = Startup::where('status', 'active')->count();
        $inactiveStartups = Startup::where('status', 'inactive')->count();
        $suspendedStartups = Startup::where('status', 'suspended')->count();
        $activeMoa = Startup::where('moa_status', 'active')->count();
        $pendingMoa = Startup::where('moa_status', 'pending')->count();

        return response()->json([
            'success' => true,
            'statistics' => [
                'total' => $totalStartups,
                'active' => $activeStartups,
                'inactive' => $inactiveStartups,
                'suspended' => $suspendedStartups,
                'active_moa' => $activeMoa,
                'pending_moa' => $pendingMoa,
            ],
        ]);
    }
}
