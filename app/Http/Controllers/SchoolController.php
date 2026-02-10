<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Intern;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    /**
     * Get all schools with statistics
     */
    public function index()
    {
        $schools = School::withCount([
            'interns as total_interns' => function ($query) {
                $query->where('approval_status', 'approved');
            },
            'interns as pending_interns' => function ($query) {
                $query->where('approval_status', 'pending');
            },
            'interns as active_interns' => function ($query) {
                $query->where('approval_status', 'approved')->where('status', 'Active');
            }
        ])->get();

        // Add total rendered hours to each school
        $schools->each(function ($school) {
            $school->total_rendered_hours = Intern::where('school_id', $school->id)
                ->where('approval_status', 'approved')
                ->sum('completed_hours');
        });

        return response()->json($schools);
    }

    /**
     * Get active schools for dropdown
     */
    public function getActiveSchools()
    {
        $schools = School::active()
            ->select('id', 'name', 'required_hours')
            ->orderBy('name')
            ->get();

        return response()->json($schools);
    }

    /**
     * Store a new school
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:schools,name',
            'required_hours' => 'required|integer|min:1|max:2000',
            'max_interns' => 'nullable|integer|min:1|max:500',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        $school = School::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'School created successfully!',
            'school' => $school
        ]);
    }

    /**
     * Update a school
     */
    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:schools,name,' . $school->id,
            'required_hours' => 'required|integer|min:1|max:2000',
            'max_interns' => 'nullable|integer|min:1|max:500',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'status' => 'nullable|in:Active,Inactive',
            'notes' => 'nullable|string|max:1000',
        ]);

        $school->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'School updated successfully!',
            'school' => $school
        ]);
    }

    /**
     * Delete a school
     */
    public function destroy(School $school)
    {
        // Check if school has any interns
        $internCount = $school->interns()->count();

        if ($internCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete school. It has {$internCount} intern(s) assigned."
            ], 400);
        }

        $school->delete();

        return response()->json([
            'success' => true,
            'message' => 'School deleted successfully!'
        ]);
    }

    /**
     * Toggle school status (Active/Inactive)
     */
    public function toggleStatus(School $school)
    {
        $school->status = $school->status === 'Active' ? 'Inactive' : 'Active';
        $school->save();

        return response()->json([
            'success' => true,
            'message' => "School status changed to {$school->status}",
            'status' => $school->status
        ]);
    }

    /**
     * Mark eligible interns from a school as accomplished/completed
     * Interns who have completed their required hours will be marked as "Completed"
     */
    public function accomplish(School $school)
    {
        // Get interns who have completed their required hours
        $eligibleInterns = $school->approvedInterns()
            ->where('status', 'Active')
            ->get()
            ->filter(function ($intern) use ($school) {
                return $intern->completed_hours >= $school->required_hours;
            });

        if ($eligibleInterns->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No eligible interns found. Interns must have completed their required hours to be marked as completed.'
            ], 400);
        }

        $count = 0;
        foreach ($eligibleInterns as $intern) {
            $intern->status = 'Completed';
            $intern->save();
            $count++;
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} intern(s) from {$school->name} have been marked as Completed!",
            'count' => $count
        ]);
    }

    /**
     * Get pending interns for approval
     */
    public function getPendingInterns()
    {
        $pendingInterns = Intern::with('schoolRelation')
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($intern) {
                return [
                    'id' => $intern->id,
                    'name' => $intern->name,
                    'email' => $intern->email,
                    'school' => $intern->school,
                    'school_name' => $intern->schoolRelation ? $intern->schoolRelation->name : $intern->school,
                    'course' => $intern->course,
                    'created_at' => $intern->created_at->toISOString(),
                ];
            });

        return response()->json(['interns' => $pendingInterns]);
    }

    /**
     * Approve an intern
     */
    public function approveIntern(Request $request, Intern $intern)
    {
        if ($intern->approval_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This intern has already been processed.'
            ], 400);
        }

        // Check school capacity
        if ($intern->school_id) {
            $school = School::find($intern->school_id);
            if ($school && !$school->hasCapacity()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot approve intern. {$school->name} has reached its maximum capacity of {$school->max_interns} interns."
                ], 400);
            }
        }

        $intern->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'start_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Intern {$intern->name} has been approved!"
        ]);
    }

    /**
     * Reject an intern
     */
    public function rejectIntern(Request $request, Intern $intern)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($intern->approval_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This intern has already been processed.'
            ], 400);
        }

        $intern->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Intern {$intern->name} has been rejected."
        ]);
    }

    /**
     * Approve all pending interns from a school
     */
    public function approveAllBySchool(Request $request, School $school)
    {
        $pendingInterns = Intern::where('school_id', $school->id)
            ->where('approval_status', 'pending')
            ->get();

        if ($pendingInterns->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No pending interns found for this school.'
            ], 400);
        }

        $pendingCount = $pendingInterns->count();

        // Check school capacity if max_interns is set
        if ($school->max_interns) {
            $remainingSlots = $school->getRemainingSlots();

            if ($remainingSlots <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => "{$school->name} has reached its maximum capacity of {$school->max_interns} interns. Cannot approve more."
                ], 400);
            }

            if ($pendingCount > $remainingSlots) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot approve all {$pendingCount} interns. {$school->name} only has {$remainingSlots} remaining slot(s) out of {$school->max_interns} maximum. Please approve individually."
                ], 400);
            }
        }

        // Approve all pending interns
        Intern::where('school_id', $school->id)
            ->where('approval_status', 'pending')
            ->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'start_date' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => "{$pendingCount} intern(s) from {$school->name} have been approved!"
        ]);
    }
}
