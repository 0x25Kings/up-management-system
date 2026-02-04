<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Intern;
use App\Models\Attendance;
use App\Models\Task;
use App\Models\Booking;
use App\Models\BlockedDate;
use App\Models\StartupSubmission;
use App\Models\RoomIssue;
use App\Models\School;
use App\Models\Document;
use App\Models\User;
use App\Models\TeamLeaderReport;



use App\Models\UserPermission;
use App\Models\Setting;

use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function index()
    {
        // Get intern statistics (only approved interns)
        $totalInterns = Intern::approved()->count();
        $activeInterns = Intern::approved()->where('status', 'Active')->count();
        $pendingInternApprovals = Intern::pending()->count();

        // Get all approved interns with their latest attendance
        $interns = Intern::approved()
            ->with(['attendances' => function($query) {
                $query->orderBy('date', 'desc')->limit(1);
            }, 'schoolRelation'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get pending interns for approval
        $pendingInterns = Intern::pending()
            ->with('schoolRelation')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all schools with statistics
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

        // Get today's attendance records
        $today = Carbon::now('Asia/Manila')->toDateString();
        $todayAttendances = Attendance::with('intern')
            ->whereDate('date', $today)
            ->orderBy('time_in', 'desc')
            ->get();

        // Get all attendance history (last 30 days)
        $attendanceHistory = Attendance::with('intern')
            ->orderBy('date', 'desc')
            ->orderBy('time_in', 'desc')
            ->limit(100)
            ->get();

        // Group interns by school for task assignment
        $internsBySchool = $interns->groupBy('school');

        // Get all tasks with intern and assigned by user
        $tasks = Task::with(['intern', 'assignedBy'])
            ->orderBy('due_date', 'asc')
            ->get();

        // Get booking statistics
        $pendingBookings = Booking::where('status', 'pending')->count();
        $todayBookings = Booking::whereDate('booking_date', $today)
            ->where('status', 'approved')
            ->count();
        $upcomingBookings = Booking::where('booking_date', '>=', $today)
            ->where('status', 'approved')
            ->orderBy('booking_date', 'asc')
            ->orderBy('time_start', 'asc')
            ->limit(10)
            ->get();
        $allBookings = Booking::with('approvedBy')
            ->orderBy('booking_date', 'desc')
            ->get();

        // Get blocked dates
        $blockedDates = BlockedDate::orderBy('blocked_date', 'asc')->get();

        // Get startup submissions (documents for research tracking)
        $startupDocuments = StartupSubmission::where('type', 'document')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get MOA requests for incubatee tracking
        $moaRequests = StartupSubmission::where('type', 'moa')
            ->with('reviewer')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get payment submissions for incubatee tracking
        $paymentSubmissions = StartupSubmission::where('type', 'finance')
            ->with('reviewer')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all startup submissions for overview
        $allStartupSubmissions = StartupSubmission::with('reviewer')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get room issues for issues & complaints
        $roomIssues = RoomIssue::with('resolver')
            ->orderBy('created_at', 'desc')
            ->get();

        // Startup statistics
        $pendingSubmissions = StartupSubmission::where('status', 'pending')->count();
        $pendingMoaCount = StartupSubmission::where('type', 'moa')->where('status', 'pending')->count();
        $pendingPaymentCount = StartupSubmission::where('type', 'finance')->where('status', 'pending')->count();
        $activeIncubatees = StartupSubmission::where('type', 'moa')
            ->whereIn('status', ['approved', 'completed'])
            ->distinct('company_name')
            ->count('company_name');

        // Room issue statistics
        $openIssues = RoomIssue::whereIn('status', ['pending', 'in_progress'])->count();
        $inProgressIssues = RoomIssue::where('status', 'in_progress')->count();
        $resolvedThisMonth = RoomIssue::where('status', 'resolved')
            ->whereMonth('resolved_at', Carbon::now()->month)
            ->whereYear('resolved_at', Carbon::now()->year)
            ->count();

        // Digital records count
        $totalDocuments = Document::count();

        return view('admin.dashboard', [
            'user' => Auth::user(),
            'totalInterns' => $totalInterns,
            'activeInterns' => $activeInterns,
            'pendingInternApprovals' => $pendingInternApprovals,
            'interns' => $interns,
            'pendingInterns' => $pendingInterns,
            'schools' => $schools,
            'todayAttendances' => $todayAttendances,
            'attendanceHistory' => $attendanceHistory,
            'internsBySchool' => $internsBySchool,
            'tasks' => $tasks,
            'pendingBookings' => $pendingBookings,
            'todayBookings' => $todayBookings,
            'upcomingBookings' => $upcomingBookings,
            'allBookings' => $allBookings,
            'blockedDates' => $blockedDates,
            // Startup data
            'startupDocuments' => $startupDocuments,
            'moaRequests' => $moaRequests,
            'paymentSubmissions' => $paymentSubmissions,
            'allStartupSubmissions' => $allStartupSubmissions,
            'roomIssues' => $roomIssues,
            'pendingSubmissions' => $pendingSubmissions,
            'pendingMoaCount' => $pendingMoaCount,
            'pendingPaymentCount' => $pendingPaymentCount,
            'activeIncubatees' => $activeIncubatees,
            'openIssues' => $openIssues,
            'inProgressIssues' => $inProgressIssues,
            'resolvedThisMonth' => $resolvedThisMonth,
            'totalDocuments' => $totalDocuments,
        ]);
    }

    /**
     * Approve overtime for an attendance record
     */
    public function approveOvertime(Request $request, Attendance $attendance)
    {
        // Check if attendance has overtime to approve
        if (!$attendance->hasOvertime()) {
            return response()->json([
                'success' => false,
                'message' => 'This attendance record has no overtime to approve.'
            ], 400);
        }

        // Check if already approved
        if ($attendance->overtime_approved) {
            return response()->json([
                'success' => false,
                'message' => 'Overtime has already been approved.'
            ], 400);
        }

        // Approve the overtime
        $attendance->approveOvertime(Auth::id());

        // Add the extra hours to intern's completed hours
        $intern = $attendance->intern;
        if ($intern && $attendance->overtime_hours) {
            $intern->increment('completed_hours', floor((float)$attendance->overtime_hours));
        }

        return response()->json([
            'success' => true,
            'message' => 'Overtime approved successfully. ' . $attendance->overtime_hours . ' hours added.',
            'overtime_hours' => $attendance->overtime_hours
        ]);
    }

    /**
     * List all team leaders
     */
    public function teamLeaders()
    {
        $teamLeaders = User::where('role', User::ROLE_TEAM_LEADER)
            ->with('school')
            ->orderBy('name')
            ->get();

        $schools = School::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.team-leaders.index', compact('teamLeaders', 'schools'));
    }

    /**
     * Store a new team leader
     */
    public function storeTeamLeader(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'school_id' => 'required|exists:schools,id',
        ]);

        // Check if school already has a team leader
        $existingTeamLeader = User::where('role', User::ROLE_TEAM_LEADER)
            ->where('school_id', $request->school_id)
            ->first();

        if ($existingTeamLeader) {
            return redirect()->back()
                ->with('error', 'This school already has a team leader assigned.')
                ->withInput();
        }

        $teamLeader = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_TEAM_LEADER,
            'school_id' => $request->school_id,
            'is_admin' => false,
            'reference_code' => User::generateReferenceCode(),
            'is_active' => true,
        ]);

        return redirect()->route('admin.team-leaders.index')
            ->with('success', 'Team Leader created successfully. Reference Code: ' . $teamLeader->reference_code);
    }

    /**
     * Update a team leader
     */
    public function updateTeamLeader(Request $request, User $user)
    {
        if ($user->role !== User::ROLE_TEAM_LEADER) {
            return redirect()->back()->with('error', 'Invalid team leader.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'school_id' => 'required|exists:schools,id',
        ]);

        // Check if school already has a different team leader
        $existingTeamLeader = User::where('role', User::ROLE_TEAM_LEADER)
            ->where('school_id', $request->school_id)
            ->where('id', '!=', $user->id)
            ->first();

        if ($existingTeamLeader) {
            return redirect()->back()
                ->with('error', 'This school already has a team leader assigned.')
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'school_id' => $request->school_id,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.team-leaders.index')
            ->with('success', 'Team Leader updated successfully.');
    }

    /**
     * Delete a team leader
     */
    public function deleteTeamLeader(User $user)
    {
        if ($user->role !== User::ROLE_TEAM_LEADER) {
            return redirect()->back()->with('error', 'Invalid team leader.');
        }

        $user->delete();

        return redirect()->route('admin.team-leaders.index')
            ->with('success', 'Team Leader deleted successfully.');
    }

    /**
     * Toggle team leader active status
     */
    public function toggleTeamLeaderStatus(User $user)
    {
        if ($user->role !== User::ROLE_TEAM_LEADER) {
            return redirect()->back()->with('error', 'Invalid team leader.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.team-leaders.index')
            ->with('success', "Team Leader {$user->name} has been {$status}.");
    }

    /**
     * Get team leaders data for AJAX (inline dashboard)
     */
    public function getTeamLeadersData()
    {
        $teamLeaders = User::where('role', User::ROLE_TEAM_LEADER)
            ->with('school')
            ->orderBy('name')
            ->get()
            ->map(function ($tl) {
                return [
                    'id' => $tl->id,
                    'name' => $tl->name,
                    'email' => $tl->email,
                    'reference_code' => $tl->reference_code,
                    'school_id' => $tl->school_id,
                    'school_name' => $tl->school->name ?? 'Not assigned',
                    'is_active' => $tl->is_active,
                    'interns_count' => \App\Models\Intern::where('school_id', $tl->school_id)->count(),
                    'reports_count' => $tl->teamLeaderReports()->count(),
                    'created_at' => $tl->created_at->format('M d, Y'),
                ];
            });

        $schools = School::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'teamLeaders' => $teamLeaders,
            'schools' => $schools,
        ]);
    }

    /**
     * Get team reports data for AJAX (inline dashboard)
     */
    public function getTeamReportsData()
    {
        $reports = TeamLeaderReport::with(['teamLeader', 'school'])
            ->where('status', '!=', 'draft')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'title' => $report->title,
                    'report_type' => $report->report_type,
                    'team_leader_name' => $report->teamLeader->name ?? 'N/A',
                    'team_leader_code' => $report->teamLeader->reference_code ?? 'N/A',
                    'school_name' => $report->school->name ?? 'N/A',
                    'status' => $report->status,
                    'summary' => $report->summary,
                    'accomplishments' => $report->accomplishments,
                    'challenges' => $report->challenges,
                    'recommendations' => $report->recommendations,
                    'admin_feedback' => $report->admin_feedback,
                    'task_statistics' => $report->task_statistics,
                    'period_start' => $report->period_start ? \Carbon\Carbon::parse($report->period_start)->format('M d, Y') : null,
                    'period_end' => $report->period_end ? \Carbon\Carbon::parse($report->period_end)->format('M d, Y') : null,
                    'created_at' => $report->created_at->format('M d, Y h:i A'),
                    'reviewed_at' => $report->reviewed_at ? $report->reviewed_at->format('M d, Y h:i A') : null,
                ];
            });

        $pendingCount = TeamLeaderReport::where('status', 'submitted')->count();

        return response()->json([
            'reports' => $reports,
            'pendingCount' => $pendingCount,
        ]);
    }

    /**
     * Store team leader via AJAX
     */
    public function storeTeamLeaderAjax(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'school_id' => 'required|exists:schools,id',
        ]);

        // Check if school already has a team leader
        $existingTeamLeader = User::where('role', User::ROLE_TEAM_LEADER)
            ->where('school_id', $request->school_id)
            ->first();

        if ($existingTeamLeader) {
            return response()->json(['error' => 'This school already has a team leader assigned.'], 422);
        }

        $teamLeader = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_TEAM_LEADER,
            'school_id' => $request->school_id,
            'is_admin' => false,
            'reference_code' => User::generateReferenceCode(),
            'is_active' => true,
        ]);

        // Handle permissions if provided
        if ($request->has('permissions')) {
            $teamLeader->syncModulePermissions($request->permissions, Auth::id());
        }

        return response()->json([
            'success' => true,
            'message' => 'Team Leader created successfully.',
            'reference_code' => $teamLeader->reference_code,
        ]);
    }

    /**
     * Update team leader via AJAX
     */
    public function updateTeamLeaderAjax(Request $request, User $user)
    {
        if ($user->role !== User::ROLE_TEAM_LEADER) {
            return response()->json(['error' => 'Invalid team leader.'], 422);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'school_id' => 'nullable|exists:schools,id',
        ]);

        // Only check for duplicate team leader if school_id is being changed
        if ($request->filled('school_id') && $request->school_id != $user->school_id) {
            $existingTeamLeader = User::where('role', User::ROLE_TEAM_LEADER)
                ->where('school_id', $request->school_id)
                ->where('id', '!=', $user->id)
                ->first();

            if ($existingTeamLeader) {
                return response()->json(['error' => 'This school already has a team leader assigned.'], 422);
            }

            $user->update(['school_id' => $request->school_id]);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Handle permissions
        if ($request->has('permissions')) {
            $user->syncModulePermissions($request->permissions, Auth::id());
        }

        return response()->json(['success' => true, 'message' => 'Team Leader updated successfully.']);
    }

    /**
     * Toggle team leader status via AJAX
     */
    public function toggleTeamLeaderStatusAjax(User $user)
    {
        if ($user->role !== User::ROLE_TEAM_LEADER) {
            return response()->json(['error' => 'Invalid team leader.'], 422);
        }

        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $user->is_active,
            'message' => 'Team Leader ' . ($user->is_active ? 'activated' : 'deactivated') . ' successfully.',
        ]);
    }

    /**
     * Delete team leader via AJAX
     */
    public function deleteTeamLeaderAjax(User $user)
    {
        if ($user->role !== User::ROLE_TEAM_LEADER) {
            return response()->json(['error' => 'Invalid team leader.'], 422);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'Team Leader deleted successfully.']);
    }

    /**
     * Review report via AJAX
     */
    public function reviewTeamLeaderReportAjax(Request $request, TeamLeaderReport $report)
    {
        $request->validate([
            'status' => 'required|in:reviewed,acknowledged',
            'admin_feedback' => 'nullable|string',
        ]);

        $report->update([
            'status' => $request->status,
            'admin_feedback' => $request->admin_feedback,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Report reviewed successfully.']);
    }

    /**
     * List team leader reports
     */
    public function teamLeaderReports()
    {
        $reports = TeamLeaderReport::with(['teamLeader', 'school'])
            ->where('status', '!=', 'draft')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $pendingCount = TeamLeaderReport::where('status', 'submitted')->count();

        return view('admin.team-reports.index', compact('reports', 'pendingCount'));
    }

    /**
     * Show a specific team leader report
     */
    public function showTeamLeaderReport(TeamLeaderReport $report)
    {
        $report->load(['teamLeader', 'school', 'reviewer']);

        return view('admin.team-reports.show', compact('report'));
    }

    /**
     * Review a team leader report
     */
    public function reviewTeamLeaderReport(Request $request, TeamLeaderReport $report)
    {
        $request->validate([
            'status' => 'required|in:reviewed,acknowledged',
            'admin_feedback' => 'nullable|string',
        ]);

        $report->update([
            'status' => $request->status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_feedback' => $request->admin_feedback,
        ]);

        return redirect()->route('admin.team-reports.index')
            ->with('success', 'Report ' . $request->status . ' successfully.');
    }

    /**
     * Get interns by school for team leader selection
     */
    public function getInternsBySchool($schoolId)
    {
        $interns = Intern::where('school_id', $schoolId)
            ->where('status', 'Active')
            ->where('approval_status', 'approved')
            ->orderBy('name')
            ->get()
            ->map(function ($intern) {
                return [
                    'id' => $intern->id,
                    'name' => $intern->name,
                    'email' => $intern->email,
                    'course' => $intern->course,
                    'completed_hours' => $intern->completed_hours,
                    'required_hours' => $intern->required_hours,
                    'progress' => $intern->required_hours > 0
                        ? round(($intern->completed_hours / $intern->required_hours) * 100)
                        : 0,
                    'reference_code' => $intern->reference_code,
                ];
            });

        return response()->json(['interns' => $interns]);
    }

    /**
     * Promote an intern to team leader
     */
    public function promoteInternToTeamLeader(Request $request)
    {
        $request->validate([
            'intern_id' => 'required|exists:interns,id',
            'password' => 'required|string|min:8',
        ]);

        $intern = Intern::findOrFail($request->intern_id);

        // Check if school already has a team leader
        $existingTeamLeader = User::where('role', User::ROLE_TEAM_LEADER)
            ->where('school_id', $intern->school_id)
            ->first();

        if ($existingTeamLeader) {
            return response()->json([
                'error' => 'This school already has a team leader assigned: ' . $existingTeamLeader->name
            ], 422);
        }

        // Check if a user with this email already exists
        $existingUser = User::where('email', $intern->email)->first();

        if ($existingUser) {
            // Update existing user to team leader
            $existingUser->update([
                'role' => User::ROLE_TEAM_LEADER,
                'school_id' => $intern->school_id,
                'password' => Hash::make($request->password),
                'reference_code' => User::generateReferenceCode(),
                'is_admin' => false, // Ensure TL is not marked as admin
                'is_active' => true,
            ]);
            $teamLeader = $existingUser;
        } else {
            // Create new user as team leader
            $teamLeader = User::create([
                'name' => $intern->name,
                'email' => $intern->email,
                'password' => Hash::make($request->password),
                'role' => User::ROLE_TEAM_LEADER,
                'school_id' => $intern->school_id,
                'is_admin' => false,
                'reference_code' => User::generateReferenceCode(),
                'is_active' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $intern->name . ' has been promoted to Team Leader!',
            'reference_code' => $teamLeader->reference_code,
            'team_leader' => [
                'id' => $teamLeader->id,
                'name' => $teamLeader->name,
                'email' => $teamLeader->email,
                'reference_code' => $teamLeader->reference_code,
            ]
        ]);
    }

    /**
     * Get available modules for permissions
     */
    public function getAvailableModules()
    {
        return response()->json([
            'modules' => UserPermission::getAvailableModules()
        ]);
    }

    /**
     * Get team leader permissions
     */
    public function getTeamLeaderPermissions(User $user)
    {
        if ($user->role !== User::ROLE_TEAM_LEADER) {
            return response()->json(['error' => 'Invalid team leader.'], 422);
        }

        return response()->json([
            'permissions' => $user->getPermissionsArray(),
            'modules' => UserPermission::getAvailableModules()
        ]);
    }

    /**
     * Update team leader permissions
     */
    public function updateTeamLeaderPermissions(Request $request, User $user)
    {
        if ($user->role !== User::ROLE_TEAM_LEADER) {
            return response()->json(['error' => 'Invalid team leader.'], 422);
        }

        $permissions = $request->input('permissions', []);
        $user->syncModulePermissions($permissions, Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Permissions updated successfully.',
            'permissions' => $user->getPermissionsArray()
        ]);
    }

    /**
     * Get recent activity for real-time updates
     */
    public function getRecentActivity()
    {
        $activities = [];
        $timezone = 'Asia/Manila';

        // Get recent attendance records (time-in/time-out) - last 30 days
        $recentAttendances = Attendance::with('intern')
            ->whereDate('date', '>=', Carbon::now($timezone)->subDays(30))
            ->orderBy('updated_at', 'desc')
            ->limit(15)
            ->get();

        foreach ($recentAttendances as $attendance) {
            if ($attendance->intern) {
                $action = $attendance->time_out ? 'Time Out Recorded' : 'Time In Recorded';
                $activities[] = [
                    'id' => 'attendance_' . $attendance->id,
                    'name' => $attendance->intern->name,
                    'initial' => strtoupper(substr($attendance->intern->name, 0, 1)),
                    'system' => 'Intern Management',
                    'action' => $action,
                    'status' => 'Active',
                    'status_class' => 'status-active',
                    'date' => $attendance->updated_at,
                    'date_formatted' => $attendance->updated_at->setTimezone($timezone)->format('M d, g:i A'),
                ];
            }
        }

        // Get recent task updates - last 30 days
        $recentTasks = Task::with('intern')
            ->whereDate('updated_at', '>=', Carbon::now($timezone)->subDays(30))
            ->orderBy('updated_at', 'desc')
            ->limit(15)
            ->get();

        foreach ($recentTasks as $task) {
            if ($task->intern) {
                $status = ucfirst($task->status);
                $statusClass = $task->status === 'completed' ? 'status-completed' :
                              ($task->status === 'in_progress' ? 'status-active' : 'status-pending');
                $activities[] = [
                    'id' => 'task_' . $task->id,
                    'name' => $task->intern->name,
                    'initial' => strtoupper(substr($task->intern->name, 0, 1)),
                    'system' => 'Task Management',
                    'action' => 'Task: ' . $task->title,
                    'status' => $status,
                    'status_class' => $statusClass,
                    'date' => $task->updated_at,
                    'date_formatted' => $task->updated_at->setTimezone($timezone)->format('M d, g:i A'),
                ];
            }
        }

        // Get recent bookings - last 30 days
        $recentBookings = Booking::whereDate('updated_at', '>=', Carbon::now($timezone)->subDays(30))
            ->orderBy('updated_at', 'desc')
            ->limit(15)
            ->get();

        foreach ($recentBookings as $booking) {
            $status = ucfirst($booking->status);
            $statusClass = $booking->status === 'approved' ? 'status-completed' :
                          ($booking->status === 'pending' ? 'status-pending' : 'status-active');
            $activities[] = [
                'id' => 'booking_' . $booking->id,
                'name' => $booking->agency_name ?? $booking->contact_person ?? 'Unknown',
                'initial' => strtoupper(substr($booking->agency_name ?? $booking->contact_person ?? 'U', 0, 1)),
                'system' => 'Scheduler',
                'action' => 'Booking: ' . ($booking->event_name ?? 'Event'),
                'status' => $status,
                'status_class' => $statusClass,
                'date' => $booking->updated_at,
                'date_formatted' => $booking->updated_at->setTimezone($timezone)->format('M d, g:i A'),
            ];
        }

        // Get recent startup submissions - last 30 days
        $recentSubmissions = StartupSubmission::with('startup')
            ->whereDate('updated_at', '>=', Carbon::now($timezone)->subDays(30))
            ->orderBy('updated_at', 'desc')
            ->limit(15)
            ->get();

        foreach ($recentSubmissions as $submission) {
            $status = ucfirst($submission->status);
            $statusClass = $submission->status === 'approved' ? 'status-completed' :
                          ($submission->status === 'pending' ? 'status-pending' : 'status-active');
            // Try multiple fields to get the name
            $name = $submission->company_name
                ?? $submission->contact_person
                ?? ($submission->startup ? $submission->startup->startup_name : null)
                ?? $submission->title
                ?? 'Unknown';
            $activities[] = [
                'id' => 'submission_' . $submission->id,
                'name' => $name,
                'initial' => strtoupper(substr($name, 0, 1)),
                'system' => 'Incubatee Tracker',
                'action' => ucfirst($submission->type ?? 'Document') . ' Submission',
                'status' => $status,
                'status_class' => $statusClass,
                'date' => $submission->updated_at,
                'date_formatted' => $submission->updated_at->setTimezone($timezone)->format('M d, g:i A'),
            ];
        }

        // Get recent room issues - last 30 days
        $recentIssues = RoomIssue::with('startup')
            ->whereDate('updated_at', '>=', Carbon::now($timezone)->subDays(30))
            ->orderBy('updated_at', 'desc')
            ->limit(15)
            ->get();

        foreach ($recentIssues as $issue) {
            $status = ucfirst(str_replace('_', ' ', $issue->status));
            $statusClass = $issue->status === 'resolved' ? 'status-completed' :
                          ($issue->status === 'pending' ? 'status-pending' : 'status-active');
            // Use contact_person, company_name, or startup name
            $name = $issue->contact_person
                ?? $issue->company_name
                ?? ($issue->startup ? $issue->startup->startup_name : null)
                ?? 'Unknown';
            $activities[] = [
                'id' => 'issue_' . $issue->id,
                'name' => $name,
                'initial' => strtoupper(substr($name, 0, 1)),
                'system' => 'Issues Management',
                'action' => 'Issue: ' . ($issue->issue_type ?? $issue->category ?? 'Report'),
                'status' => $status,
                'status_class' => $statusClass,
                'date' => $issue->updated_at,
                'date_formatted' => $issue->updated_at->setTimezone($timezone)->format('M d, g:i A'),
            ];
        }

        // Get recent intern registrations - last 30 days
        $recentInterns = Intern::whereDate('updated_at', '>=', Carbon::now($timezone)->subDays(30))
            ->orderBy('updated_at', 'desc')
            ->limit(15)
            ->get();

        foreach ($recentInterns as $intern) {
            $status = ucfirst($intern->status);
            $statusClass = $intern->status === 'Active' ? 'status-active' :
                          ($intern->status === 'Completed' ? 'status-completed' : 'status-pending');
            $action = $intern->approval_status === 'pending' ? 'Registration Pending' :
                     ($intern->created_at->eq($intern->updated_at) ? 'Registered' : 'Profile Updated');
            $activities[] = [
                'id' => 'intern_' . $intern->id,
                'name' => $intern->name,
                'initial' => strtoupper(substr($intern->name, 0, 1)),
                'system' => 'Intern Management',
                'action' => $action,
                'status' => $status,
                'status_class' => $statusClass,
                'date' => $intern->updated_at,
                'date_formatted' => $intern->updated_at->setTimezone($timezone)->format('M d, g:i A'),
            ];
        }

        // Sort all activities by date descending and take top 10
        usort($activities, function($a, $b) {
            return $b['date']->timestamp - $a['date']->timestamp;
        });

        $activities = array_slice($activities, 0, 10);

        // Remove Carbon objects for JSON response
        foreach ($activities as &$activity) {
            unset($activity['date']);
        }

        return response()->json($activities);
    }

    /**
     * Get chart data for real-time dashboard updates
     */
    public function getChartData()
    {
        $timezone = 'Asia/Manila';

        // Get monthly intern data for the last 6 months
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now($timezone)->subMonths($i);
            $monthName = $date->format('M');
            $monthStart = $date->startOfMonth()->toDateString();
            $monthEnd = $date->endOfMonth()->toDateString();

            // Count active interns for this month
            $activeInterns = Intern::approved()
                ->where('status', 'Active')
                ->where('created_at', '<=', $monthEnd)
                ->count();

            // Count completed tasks for this month
            $completedTasks = Task::where('status', 'completed')
                ->whereBetween('updated_at', [$monthStart, $monthEnd])
                ->count();

            $monthlyData[] = [
                'month' => $monthName,
                'activeInterns' => $activeInterns,
                'completedTasks' => $completedTasks
            ];
        }

        // Get system usage data (pie chart)
        $totalInterns = Intern::approved()->count();
        $totalTasks = Task::count();
        $totalBookings = Booking::count();
        $totalSubmissions = StartupSubmission::count();
        $totalDocuments = Document::count();

        $total = $totalInterns + $totalTasks + $totalBookings + $totalSubmissions + $totalDocuments;

        // Calculate percentages (avoid division by zero)
        if ($total > 0) {
            $systemUsage = [
                'internManagement' => round(($totalInterns / $total) * 100),
                'taskManagement' => round(($totalTasks / $total) * 100),
                'scheduler' => round(($totalBookings / $total) * 100),
                'incubateeTracker' => round(($totalSubmissions / $total) * 100),
                'digitalRecords' => round(($totalDocuments / $total) * 100)
            ];
        } else {
            $systemUsage = [
                'internManagement' => 20,
                'taskManagement' => 20,
                'scheduler' => 20,
                'incubateeTracker' => 20,
                'digitalRecords' => 20
            ];
        }

        // Get real-time stats
        $stats = [
            'totalInterns' => $totalInterns,
            'activeInterns' => Intern::approved()->where('status', 'Active')->count(),
            'pendingTasks' => Task::where('status', 'pending')->count(),
            'completedTasks' => Task::where('status', 'completed')->count(),
            'pendingBookings' => Booking::where('status', 'pending')->count(),
            'todayAttendance' => Attendance::whereDate('date', Carbon::now($timezone)->toDateString())->count(),
        ];

        return response()->json([
            'monthlyData' => $monthlyData,
            'systemUsage' => $systemUsage,
            'stats' => $stats,
            'lastUpdated' => Carbon::now($timezone)->format('M d, g:i A')
        ]);
    }

    /**
     * Export interns data to CSV
     */
    public function exportInterns()
    {
        $interns = Intern::approved()->with('schoolRelation')->get();

        $filename = 'interns_export_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($interns) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['ID', 'Name', 'Email', 'School', 'Status', 'Required Hours', 'Completed Hours', 'Start Date', 'End Date', 'Created At']);

            foreach ($interns as $intern) {
                fputcsv($file, [
                    $intern->id,
                    $intern->name,
                    $intern->email,
                    $intern->schoolRelation ? $intern->schoolRelation->name : $intern->school,
                    $intern->status,
                    $intern->required_hours,
                    $intern->completed_hours,
                    $intern->start_date,
                    $intern->end_date,
                    $intern->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export attendance data to CSV
     */
    public function exportAttendance()
    {
        $attendances = Attendance::with('intern')->orderBy('date', 'desc')->get();

        $filename = 'attendance_export_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['ID', 'Intern Name', 'Date', 'Time In', 'Time Out', 'Hours Worked', 'Overtime', 'Undertime', 'Status']);

            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->id,
                    $attendance->intern ? $attendance->intern->name : 'N/A',
                    $attendance->date,
                    $attendance->time_in,
                    $attendance->time_out,
                    $attendance->hours_worked,
                    $attendance->overtime ?? 0,
                    $attendance->undertime ?? 0,
                    $attendance->status ?? 'Present'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export tasks data to CSV
     */
    public function exportTasks()
    {
        $tasks = Task::with(['intern', 'assignedBy'])->orderBy('created_at', 'desc')->get();

        $filename = 'tasks_export_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($tasks) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['ID', 'Title', 'Description', 'Assigned To', 'Assigned By', 'Due Date', 'Status', 'Priority', 'Created At']);

            foreach ($tasks as $task) {
                fputcsv($file, [
                    $task->id,
                    $task->title,
                    $task->description,
                    $task->intern ? $task->intern->name : 'N/A',
                    $task->assignedBy ? $task->assignedBy->name : 'Admin',
                    $task->due_date,
                    $task->status,
                    $task->priority ?? 'Normal',
                    $task->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export bookings data to CSV
     */
    public function exportBookings()
    {
        $bookings = Booking::with('approvedBy')->orderBy('booking_date', 'desc')->get();

        $filename = 'bookings_export_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['ID', 'Contact Person', 'Email', 'Phone', 'Organization', 'Room', 'Purpose', 'Booking Date', 'Start Time', 'End Time', 'Status', 'Approved By', 'Created At']);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->contact_person,
                    $booking->email,
                    $booking->phone ?? 'N/A',
                    $booking->organization ?? 'N/A',
                    $booking->room,
                    $booking->purpose,
                    $booking->booking_date,
                    $booking->time_start,
                    $booking->time_end,
                    $booking->status,
                    $booking->approvedBy ? $booking->approvedBy->name : 'N/A',
                    $booking->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get all settings
     */
    public function getSettings()
    {
        $settings = Setting::getAllSettings();
        $defaults = Setting::getDefaults();
        
        // Merge defaults with saved settings
        $merged = [];
        foreach ($defaults as $key => $default) {
            $merged[$key] = array_key_exists($key, $settings) ? $settings[$key] : $default['value'];
        }
        
        return response()->json(['success' => true, 'settings' => $merged]);
    }

    /**
     * Save settings
     */
    public function saveSettings(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        $defaults = Setting::getDefaults();
        
        foreach ($validated['settings'] as $key => $value) {
            $type = isset($defaults[$key]) ? $defaults[$key]['type'] : 'string';
            Setting::set($key, $value, $type);
        }

        return response()->json(['success' => true, 'message' => 'Settings saved successfully!']);
    }

    /**
     * Reset settings to defaults
     */
    public function resetSettings()
    {
        $defaults = Setting::getDefaults();
        
        foreach ($defaults as $key => $default) {
            Setting::set($key, $default['value'], $default['type']);
        }

        return response()->json(['success' => true, 'message' => 'Settings reset to defaults!']);
    }

    /**
     * Clear old data (maintenance)
     */
    public function clearOldData(Request $request)
    {
        $validated = $request->validate([
            'data_type' => 'required|in:attendance,tasks,bookings,all',
            'older_than_days' => 'required|integer|min:30',
        ]);

        $cutoffDate = Carbon::now()->subDays($validated['older_than_days']);
        $deleted = 0;

        switch ($validated['data_type']) {
            case 'attendance':
                $deleted = Attendance::where('date', '<', $cutoffDate)->delete();
                break;
            case 'tasks':
                $deleted = Task::where('status', 'Completed')
                    ->where('updated_at', '<', $cutoffDate)
                    ->delete();
                break;
            case 'bookings':
                $deleted = Booking::where('booking_date', '<', $cutoffDate)
                    ->whereIn('status', ['approved', 'rejected', 'completed', 'cancelled'])
                    ->delete();
                break;
            case 'all':
                $deleted += Attendance::where('date', '<', $cutoffDate)->delete();
                $deleted += Task::where('status', 'Completed')
                    ->where('updated_at', '<', $cutoffDate)
                    ->delete();
                $deleted += Booking::where('booking_date', '<', $cutoffDate)
                    ->whereIn('status', ['approved', 'rejected', 'completed', 'cancelled'])
                    ->delete();
                break;
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deleted} old records.",
            'deleted' => $deleted
        ]);
    }

    /**
     * Upload admin profile picture
     */
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                $oldPath = storage_path('app/public/' . $user->profile_picture);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Store new profile picture
            $file = $request->file('profile_picture');
            $filename = 'admin_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile-pictures', $filename, 'public');

            // Update user's profile picture
            $user->profile_picture = $path;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully',
                'image_url' => asset('storage/' . $path)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file uploaded'
        ], 400);
    }
}
