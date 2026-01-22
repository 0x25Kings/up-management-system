<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Intern;
use App\Models\Attendance;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class InternController extends Controller
{
    /**
     * Show the intern portal (form or dashboard based on session)
     */
    public function index(Request $request)
    {
        // Get active schools for registration dropdown
        $schools = \App\Models\School::active()->orderBy('name')->get();

        // Check if intern is already registered in this session
        $internId = $request->session()->get('intern_id');

        if ($internId) {
            $intern = Intern::with(['attendances' => function($query) {
                $query->orderBy('date', 'desc')->limit(10);
            }, 'schoolRelation'])->find($internId);

            if ($intern) {
                // Check if intern is pending approval
                if ($intern->isPending()) {
                    return view('portals.intern', [
                        'intern' => $intern,
                        'showDashboard' => false,
                        'showPending' => true,
                        'todayAttendance' => null,
                        'attendanceHistory' => collect(),
                        'tasks' => collect(),
                        'schools' => $schools,
                    ]);
                }

                // Check if intern is rejected
                if ($intern->isRejected()) {
                    return view('portals.intern', [
                        'intern' => $intern,
                        'showDashboard' => false,
                        'showRejected' => true,
                        'todayAttendance' => null,
                        'attendanceHistory' => collect(),
                        'tasks' => collect(),
                        'schools' => $schools,
                    ]);
                }

                // Intern is approved - show dashboard
                $todayAttendance = $intern->today_attendance;
                $attendanceHistory = $intern->attendances()->orderBy('date', 'desc')->limit(10)->get();
                $tasks = $intern->tasks()->orderBy('due_date', 'asc')->get();

                return view('portals.intern', [
                    'intern' => $intern,
                    'showDashboard' => true,
                    'todayAttendance' => $todayAttendance,
                    'attendanceHistory' => $attendanceHistory,
                    'tasks' => $tasks,
                    'schools' => $schools,
                ]);
            }
        }

        return view('portals.intern', [
            'intern' => null,
            'showDashboard' => false,
            'todayAttendance' => null,
            'attendanceHistory' => collect(),
            'tasks' => collect(),
            'schools' => $schools,
        ]);
    }

    /**
     * Register a new intern
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:16|max:60',
            'gender' => 'required|in:Male,Female,Other',
            'email' => 'required|email|unique:interns,email',
            'phone' => 'required|string|max:20',
            'school_id' => 'required|exists:schools,id',
            'course' => 'required|string|max:255',
            'year_level' => 'nullable|string|max:50',
        ]);

        // Get school and check capacity
        $school = \App\Models\School::find($validated['school_id']);

        // Check if school is at capacity
        if ($school->max_interns && !$school->hasCapacity()) {
            return back()->withInput()->withErrors([
                'school_id' => "Sorry, {$school->name} has reached its maximum capacity of {$school->max_interns} interns. Please contact the administrator."
            ]);
        }

        // Generate reference code
        $validated['reference_code'] = Intern::generateReferenceCode();
        $validated['school'] = $school->name; // Keep school name for backward compatibility
        $validated['required_hours'] = $school->required_hours;
        $validated['approval_status'] = 'pending';
        // Don't set start_date until approved

        // Create the intern
        $intern = Intern::create($validated);

        // Store intern ID in session
        $request->session()->put('intern_id', $intern->id);

        return redirect()->route('intern.portal')
            ->with('success', 'Registration submitted successfully! Your reference code is: ' . $intern->reference_code . '. Please wait for admin approval.');
    }

    /**
     * Access portal with reference code
     */
    public function accessWithCode(Request $request)
    {
        $request->validate([
            'reference_code' => 'required|string',
        ]);

        $referenceCode = strtoupper(trim($request->reference_code));

        // Check if it's a Team Leader reference code
        if (str_starts_with($referenceCode, 'TL-')) {
            return $this->handleTeamLeaderAccess($request, $referenceCode);
        }

        // Regular intern access
        $intern = Intern::where('reference_code', $referenceCode)->first();

        if (!$intern) {
            return back()->withErrors(['reference_code' => 'Invalid reference code. Please check and try again.']);
        }

        // Store intern ID in session
        $request->session()->put('intern_id', $intern->id);

        return redirect()->route('intern.portal')
            ->with('success', 'Welcome back, ' . $intern->name . '!');
    }

    /**
     * Handle Team Leader access with reference code and password
     */
    private function handleTeamLeaderAccess(Request $request, string $referenceCode)
    {
        // Find the team leader by reference code
        $teamLeader = \App\Models\User::where('reference_code', $referenceCode)
            ->where('role', \App\Models\User::ROLE_TEAM_LEADER)
            ->first();

        if (!$teamLeader) {
            return back()->withErrors(['reference_code' => 'Invalid Team Leader reference code.']);
        }

        // Check if password was provided
        if (!$request->password) {
            // Return with a flag to show password field
            return back()
                ->withInput(['reference_code' => $referenceCode])
                ->with('show_password', true)
                ->with('tl_name', $teamLeader->name);
        }

        // Verify password
        if (!\Illuminate\Support\Facades\Hash::check($request->password, $teamLeader->password)) {
            return back()
                ->withInput(['reference_code' => $referenceCode])
                ->with('show_password', true)
                ->with('tl_name', $teamLeader->name)
                ->withErrors(['password' => 'Incorrect password.']);
        }

        // Check if account is active
        if (!$teamLeader->isActive()) {
            return back()->withErrors(['reference_code' => 'Your Team Leader access has been suspended. Please contact the administrator.']);
        }

        // Log in the team leader
        \Illuminate\Support\Facades\Auth::login($teamLeader);
        $request->session()->regenerate();

        return redirect()->route('team-leader.dashboard')
            ->with('success', 'Welcome back, ' . $teamLeader->name . '!');
    }

    /**
     * Clear intern session (switch user)
     */
    public function clearSession(Request $request)
    {
        $request->session()->forget('intern_id');
        return redirect()->route('intern.portal');
    }

    /**
     * Update intern profile
     */
    public function updateProfile(Request $request)
    {
        $internId = $request->session()->get('intern_id');

        if (!$internId) {
            return redirect()->route('intern.portal');
        }

        $intern = Intern::findOrFail($internId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'school' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'year_level' => 'nullable|string|max:50',
        ]);

        $intern->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update intern profile picture
     */
    public function updateProfilePicture(Request $request)
    {
        $internId = $request->session()->get('intern_id');

        if (!$internId) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired'
            ], 401);
        }

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $intern = Intern::findOrFail($internId);

        // Delete old profile picture if exists
        if ($intern->profile_picture) {
            Storage::disk('public')->delete($intern->profile_picture);
        }

        // Store new profile picture
        $path = $request->file('profile_picture')->store('profile-pictures', 'public');

        $intern->update(['profile_picture' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Profile picture updated successfully',
            'image_url' => asset('storage/' . $path)
        ]);
    }

    /**
     * Time In
     */
    public function timeIn(Request $request)
    {
        $internId = $request->session()->get('intern_id');

        $expectsJson = $request->ajax() || $request->wantsJson() || $request->expectsJson();

        if (!$internId) {
            if ($expectsJson) {
                return response()->json(['success' => false, 'message' => 'Session expired. Please login again.'], 401);
            }
            return redirect()->route('intern.portal');
        }

        $intern = Intern::findOrFail($internId);
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();

        // Check if already has attendance for today using whereDate for reliable comparison
        $attendance = Attendance::where('intern_id', $intern->id)
            ->where('date', $today)
            ->first();

        if ($attendance) {
            // Already has an attendance record for today
            if ($attendance->time_in) {
                if ($expectsJson) {
                    return response()->json(['success' => false, 'message' => 'You have already timed in today.']);
                }
                return back()->with('error', 'You have already timed in today.');
            }
            // Update existing record with time_in (rare case - record exists but no time_in)
            $attendance->time_in = $now->format('H:i:s');
            $attendance->status = $now->hour >= 9 ? 'Late' : 'Present';
            $attendance->save();
        } else {
            // Create new attendance record using firstOrCreate to prevent race conditions
            try {
                $attendance = Attendance::firstOrCreate(
                    ['intern_id' => $intern->id, 'date' => $today],
                    [
                        'time_in' => $now->format('H:i:s'),
                        'status' => $now->hour >= 9 ? 'Late' : 'Present',
                    ]
                );

                // If record was found (not created), update it
                if (!$attendance->wasRecentlyCreated && !$attendance->time_in) {
                    $attendance->time_in = $now->format('H:i:s');
                    $attendance->status = $now->hour >= 9 ? 'Late' : 'Present';
                    $attendance->save();
                } elseif (!$attendance->wasRecentlyCreated && $attendance->time_in) {
                    if ($expectsJson) {
                        return response()->json(['success' => false, 'message' => 'You have already timed in today.']);
                    }
                    return back()->with('error', 'You have already timed in today.');
                }
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Record already exists, fetch it and check
                $attendance = Attendance::where('intern_id', $intern->id)
                    ->where('date', $today)
                    ->first();

                if ($attendance && $attendance->time_in) {
                    if ($expectsJson) {
                        return response()->json(['success' => false, 'message' => 'You have already timed in today.']);
                    }
                    return back()->with('error', 'You have already timed in today.');
                }

                if ($expectsJson) {
                    return response()->json(['success' => false, 'message' => 'An error occurred. Please try again.']);
                }
                return back()->with('error', 'An error occurred. Please try again.');
            }
        }

        // Return JSON for AJAX requests
        if ($expectsJson) {
            return response()->json([
                'success' => true,
                'message' => 'Time In recorded at ' . $now->format('h:i A'),
                'time_in' => $now->format('h:i A'),
                'raw_time_in' => $now->toIso8601String(),
                'raw_time_in_hms' => $now->format('H:i:s')
            ]);
        }

        return redirect()->route('intern.portal', ['page' => 'attendance'])
            ->with('success', 'Time In recorded at ' . $now->format('h:i A'));
    }

    /**
     * Time Out
     */
    public function timeOut(Request $request)
    {
        $internId = $request->session()->get('intern_id');
        $expectsJson = $request->ajax() || $request->wantsJson() || $request->expectsJson();

        if (!$internId) {
            if ($expectsJson) {
                return response()->json(['success' => false, 'message' => 'Session expired. Please login again.'], 401);
            }
            return redirect()->route('intern.portal');
        }

        $intern = Intern::findOrFail($internId);
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();

        // Check if has timed in today
        $attendance = Attendance::where('intern_id', $intern->id)
            ->where('date', $today)
            ->first();

        if (!$attendance || !$attendance->time_in) {
            if ($expectsJson) {
                return response()->json(['success' => false, 'message' => 'You need to time in first.']);
            }
            return back()->with('error', 'You need to time in first.');
        }

        if ($attendance->time_out) {
            if ($expectsJson) {
                return response()->json(['success' => false, 'message' => 'You have already timed out today.']);
            }
            return back()->with('error', 'You have already timed out today.');
        }

        // Calculate hours worked (store times are Manila-local HH:MM:SS; attach date + timezone for accurate diffs)
        $attendanceDate = $attendance->date ? Carbon::parse($attendance->date)->format('Y-m-d') : $today;
        $timeIn = Carbon::parse($attendanceDate . ' ' . $attendance->time_in, 'Asia/Manila');
        $hoursWorked = round($now->diffInSeconds($timeIn, true) / 3600, 2);

        // Update attendance record
        $attendance->update([
            'time_out' => $now->format('H:i:s'),
            'hours_worked' => $hoursWorked,
        ]);

        // Calculate overtime/undertime
        $attendance->calculateOvertimeUndertime();
        $attendance->save();

        // Update intern's completed hours (only count effective hours)
        $intern->increment('completed_hours', floor($attendance->effective_hours));

        // Prepare message based on overtime/undertime status
        $message = 'Time Out recorded at ' . $now->format('h:i A') . '. Hours worked: ' . $hoursWorked;
        if ($attendance->hasUndertime()) {
            $message .= ' (Undertime: ' . $attendance->undertime_hours . ' hrs)';
        } elseif ($attendance->hasOvertime()) {
            $message .= ' (Overtime: ' . $attendance->overtime_hours . ' hrs - Pending Approval)';
        }

        // Return JSON for AJAX requests with reload flag
        if ($expectsJson) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'time_in' => Carbon::parse($attendance->time_in)->format('h:i A'),
                'time_out' => $now->format('h:i A'),
                'hours_worked' => $hoursWorked,
                'overtime_hours' => $attendance->overtime_hours,
                'undertime_hours' => $attendance->undertime_hours,
                'reload' => true  // Flag to trigger page reload
            ]);
        }

        return redirect()->route('intern.portal', ['page' => 'attendance'])
            ->with('success', $message);
    }

    /**
     * Get intern details for admin view
     */
    public function show($id)
    {
        try {
            $intern = Intern::with('schoolRelation')->findOrFail($id);

            return response()->json([
                'success' => true,
                'intern' => [
                    'id' => $intern->id,
                    'name' => $intern->name,
                    'email' => $intern->email,
                    'phone' => $intern->phone,
                    'age' => $intern->age,
                    'gender' => $intern->gender,
                    'school' => $intern->schoolRelation->name ?? $intern->school ?? 'N/A',
                    'course' => $intern->course,
                    'year_level' => $intern->year_level,
                    'address' => $intern->address,
                    'reference_code' => $intern->reference_code,
                    'status' => $intern->status,
                    'completed_hours' => $intern->completed_hours,
                    'required_hours' => $intern->required_hours,
                    'progress_percentage' => $intern->progress_percentage,
                    'start_date' => $intern->start_date,
                    'end_date' => $intern->end_date,
                    'created_at' => $intern->created_at,
                    'profile_picture' => $intern->profile_picture,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Intern not found'
            ], 404);
        }
    }

    /**
     * Get a specific task for the intern
     */
    public function getTask(Request $request, Task $task)
    {
        $internId = $request->session()->get('intern_id');

        if (!$internId) {
            return response()->json(['success' => false, 'message' => 'Session expired'], 401);
        }

        // Verify the task belongs to this intern
        if ($task->intern_id !== (int) $internId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }

    /**
     * Update a task for the intern (progress, notes, documents, status)
     */
    public function updateTask(Request $request, Task $task)
    {
        $internId = $request->session()->get('intern_id');

        if (!$internId) {
            return response()->json(['success' => false, 'message' => 'Session expired'], 401);
        }

        // Verify the task belongs to this intern
        if ($task->intern_id !== (int) $internId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'sometimes|in:Not Started,In Progress,Completed,On Hold',
            'progress' => 'sometimes|integer|min:0|max:100',
            'notes' => 'nullable|string',
            'documents.*' => 'nullable|file|max:10240', // Max 10MB per file
            'checklist' => 'nullable',
        ]);

        $hasExistingChecklist = is_array($task->checklist) && count($task->checklist) > 0;

        $incomingChecklist = null;
        if ($request->filled('checklist')) {
            $incomingRaw = $request->input('checklist');
            $incoming = is_string($incomingRaw) ? json_decode($incomingRaw, true) : $incomingRaw;

            if (is_array($incoming)) {
                $items = [];
                foreach ($incoming as $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $label = trim((string) ($item['label'] ?? ''));
                    if ($label === '') {
                        continue;
                    }
                    $items[] = [
                        'label' => mb_substr($label, 0, 200),
                        'done' => !empty($item['done']),
                    ];
                }

                $incomingChecklist = count($items) > 0 ? $items : [];
            }
        }

        // If admin didn't provide a checklist, intern must create one before saving progress/status.
        if (!$hasExistingChecklist && (array_key_exists('progress', $validated) || $request->has('status'))) {
            if (!is_array($incomingChecklist) || count($incomingChecklist) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Checklist is required for this task. Please create at least one checklist item.'
                ], 422);
            }
        }

        // Apply checklist + compute progress from it.
        if (is_array($incomingChecklist)) {
            if ($hasExistingChecklist) {
                // Preserve existing labels; only update done state by index.
                $newChecklist = [];
                foreach (($task->checklist ?? []) as $index => $existingItem) {
                    $label = is_array($existingItem) && array_key_exists('label', $existingItem)
                        ? (string) $existingItem['label']
                        : '';

                    $done = false;
                    if (array_key_exists($index, $incomingChecklist) && is_array($incomingChecklist[$index])) {
                        $done = !empty($incomingChecklist[$index]['done']);
                    }

                    $newChecklist[] = [
                        'label' => $label,
                        'done' => $done,
                    ];
                }
            } else {
                // Task has no checklist yet; intern can create it.
                $newChecklist = $incomingChecklist;
                $hasExistingChecklist = count($newChecklist) > 0;
            }

            if (isset($newChecklist) && is_array($newChecklist) && count($newChecklist) > 0) {
                $doneCount = collect($newChecklist)->where('done', true)->count();
                $total = count($newChecklist);
                $progress = $total > 0 ? (int) round(($doneCount / $total) * 100) : 0;

                $validated['checklist'] = $newChecklist;
                $validated['progress'] = $progress;

                if ($doneCount >= $total) {
                    $validated['status'] = 'Completed';
                } elseif ($doneCount > 0) {
                    if (!($request->has('status') && in_array($request->status, ['On Hold', 'Completed'], true))) {
                        $validated['status'] = 'In Progress';
                    }
                }
            }
        }

        // Handle document uploads
        if ($request->hasFile('documents')) {
            $uploadedDocs = [];
            $path = 'tasks/documents';

            foreach ($request->file('documents') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs($path, $filename, 'public');
                $uploadedDocs[] = "{$path}/{$filename}";
            }

            // If task already has documents, merge them
            $existingDocs = $task->documents ?? [];
            $validated['documents'] = array_merge($existingDocs, $uploadedDocs);
        }

        // If status is being changed to 'In Progress', record the start time
        if (
            (array_key_exists('status', $validated) && $validated['status'] === 'In Progress')
            && $task->status !== 'In Progress'
        ) {
            $validated['started_at'] = now('Asia/Manila');
        }

        // If intern sets status to 'Completed', treat it as a completion request (admin approves by setting completed_date)
        if ($request->has('status') && $request->status === 'Completed') {
            $validated['progress'] = 100;
        }

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'task' => $task->fresh()
        ]);
    }

    /**
     * Mark a task as completed for the intern
     */
    public function completeTask(Request $request, Task $task)
    {
        $internId = $request->session()->get('intern_id');

        if (!$internId) {
            return response()->json(['success' => false, 'message' => 'Session expired'], 401);
        }

        // Verify the task belongs to this intern
        if ($task->intern_id !== (int) $internId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Require checklist before allowing completion submission
        $hasChecklist = is_array($task->checklist) && count($task->checklist) > 0;
        if (!$hasChecklist) {
            return response()->json([
                'success' => false,
                'message' => 'Checklist is required for this task. Please create at least one checklist item before submitting completion.'
            ], 422);
        }

        $task->update([
            'status' => 'Completed',
            'progress' => 100
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task submitted as completed and is pending admin approval'
        ]);
    }
}
