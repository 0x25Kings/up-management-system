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
}
