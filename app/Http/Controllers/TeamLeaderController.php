<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use App\Models\Task;
use App\Models\Attendance;
use App\Models\TeamLeaderReport;
use App\Models\School;
use App\Models\Booking;
use App\Models\BlockedDate;
use App\Models\StartupSubmission;
use App\Models\RoomIssue;
use App\Models\Event;
use App\Models\UserPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TeamLeaderController extends Controller
{
    /**
     * Get the authenticated team leader's school ID
     */
    protected function getSchoolId()
    {
        return Auth::user()->school_id;
    }

    /**
     * Get interns for the team leader's school
     */
    protected function getSchoolInterns()
    {
        return Intern::where('school_id', $this->getSchoolId())
            ->where('approval_status', 'approved');
    }

    /**
     * Display the team leader dashboard
     */
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $schoolId = $this->getSchoolId();
        $school = School::find($schoolId);

        if (!$school) {
            return redirect()->route('login')->with('error', 'No school assigned to your account.');
        }

        // Get user's viewable and editable modules
        $viewableModules = $user->getViewableModules();
        $editableModules = $user->getEditableModules();
        $availableModules = UserPermission::getAvailableModules();

        // Get intern statistics
        $totalInterns = $this->getSchoolInterns()->count();
        $activeInterns = $this->getSchoolInterns()->where('status', 'Active')->count();
        $completedInterns = $this->getSchoolInterns()->where('status', 'Completed')->count();

        // Get task statistics
        $internIds = $this->getSchoolInterns()->pluck('id');
        $totalTasks = Task::whereIn('intern_id', $internIds)->count();
        $pendingTasks = Task::whereIn('intern_id', $internIds)->where('status', 'Pending')->count();
        $inProgressTasks = Task::whereIn('intern_id', $internIds)->where('status', 'In Progress')->count();
        $completedTasks = Task::whereIn('intern_id', $internIds)->where('status', 'Completed')->count();
        $overdueTasks = Task::whereIn('intern_id', $internIds)
            ->where('status', '!=', 'Completed')
            ->where('due_date', '<', Carbon::today())
            ->count();

        // Today's attendance
        $today = Carbon::today('Asia/Manila');
        $presentToday = Attendance::whereIn('intern_id', $internIds)
            ->where('date', $today->toDateString())
            ->where('status', 'Present')
            ->count();

        // Recent tasks
        $recentTasks = Task::whereIn('intern_id', $internIds)
            ->with('intern')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Interns with low progress
        $internsNeedingAttention = $this->getSchoolInterns()
            ->where('status', 'Active')
            ->get()
            ->filter(function ($intern) {
                $progress = $intern->required_hours > 0
                    ? ($intern->completed_hours / $intern->required_hours) * 100
                    : 0;
                return $progress < 50;
            })
            ->take(5);

        // Pending reports
        $pendingReports = TeamLeaderReport::where('team_leader_id', $user->id)
            ->where('status', 'draft')
            ->count();

        // Recent reports
        $recentReports = TeamLeaderReport::where('team_leader_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // All reports for reports page
        $allReports = TeamLeaderReport::where('team_leader_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // All interns for interns page
        $allInterns = $this->getSchoolInterns()->orderBy('name')->get();

        // All tasks for tasks page
        $allTasks = Task::whereIn('intern_id', $internIds)
            ->with('intern')
            ->orderBy('due_date', 'asc')
            ->get();

        // Today's attendance for attendance page
        $todayAttendances = Attendance::whereIn('intern_id', $internIds)
            ->where('date', $today->toDateString())
            ->with('intern')
            ->get();

        // Get all active interns for attendance
        $activeInterns = $this->getSchoolInterns()->where('status', 'Active')->get();

        // Absent today
        $absentToday = $activeInterns->count() - $presentToday;

        // Late today (time_in after 8:00 AM)
        $lateToday = $todayAttendances->filter(function ($a) {
            return $a->time_in && Carbon::parse($a->time_in)->format('H:i') > '08:00';
        })->count();

        // Load data for permitted modules
        $schedulerData = [];
        $incubateeData = [];
        $issuesData = [];

        // If user has scheduler access, load bookings and events
        if (in_array('scheduler', $viewableModules)) {
            $schedulerData = [
                'bookings' => Booking::orderBy('booking_date', 'desc')->limit(10)->get(),
                'events' => Event::orderBy('start_date', 'desc')->limit(10)->get(),
                'blockedDates' => BlockedDate::orderBy('date', 'desc')->get(),
                'pendingBookings' => Booking::where('status', 'pending')->count(),
            ];
        }

        // If user has incubatee tracker access
        if (in_array('incubatee_tracker', $viewableModules)) {
            $incubateeData = [
                'moaRequests' => StartupSubmission::where('type', 'moa')
                    ->with('reviewer')
                    ->orderBy('created_at', 'desc')
                    ->get(),
                'paymentSubmissions' => StartupSubmission::where('type', 'finance')
                    ->with('reviewer')
                    ->orderBy('created_at', 'desc')
                    ->get(),
                'totalSubmissions' => StartupSubmission::count(),
                'pendingSubmissions' => StartupSubmission::where('status', 'pending')->count(),
                'pendingMoaCount' => StartupSubmission::where('type', 'moa')->where('status', 'pending')->count(),
                'pendingPaymentCount' => StartupSubmission::where('type', 'finance')->where('status', 'pending')->count(),
                'activeIncubatees' => StartupSubmission::where('type', 'moa')
                    ->whereIn('status', ['approved', 'completed'])
                    ->distinct('company_name')
                    ->count('company_name'),
            ];
        }

        // If user has issues management access
        if (in_array('issues_management', $viewableModules)) {
            $allIssues = RoomIssue::orderBy('created_at', 'desc')->get();
            $issuesData = [
                'issues' => $allIssues,
                'totalIssues' => $allIssues->count(),
                'pendingIssues' => $allIssues->where('status', 'pending')->count(),
                'inProgressIssues' => $allIssues->where('status', 'in_progress')->count(),
                'resolvedIssues' => $allIssues->where('status', 'resolved')->count(),
            ];
        }

        return view('team-leader.dashboard', compact(
            'user',
            'school',
            'totalInterns',
            'activeInterns',
            'completedInterns',
            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'overdueTasks',
            'presentToday',
            'recentTasks',
            'internsNeedingAttention',
            'pendingReports',
            'recentReports',
            'allReports',
            'allInterns',
            'allTasks',
            'todayAttendances',
            'absentToday',
            'lateToday',
            'today',
            'viewableModules',
            'editableModules',
            'availableModules',
            'schedulerData',
            'incubateeData',
            'issuesData'
        ));
    }

    /**
     * Display list of interns
     */
    public function interns(Request $request)
    {
        $query = $this->getSchoolInterns();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('course', 'like', "%{$search}%");
            });
        }

        $interns = $query->orderBy('name')->paginate(15);
        $school = School::find($this->getSchoolId());

        return view('team-leader.interns.index', compact('interns', 'school'));
    }

    /**
     * View intern details
     */
    public function showIntern(Intern $intern)
    {
        // Check if intern belongs to team leader's school
        if ($intern->school_id !== $this->getSchoolId()) {
            abort(403, 'Unauthorized access to this intern.');
        }

        $tasks = $intern->tasks()->orderBy('created_at', 'desc')->get();
        $attendances = $intern->attendances()->orderBy('date', 'desc')->limit(30)->get();

        return view('team-leader.interns.show', compact('intern', 'tasks', 'attendances'));
    }

    /**
     * Display task management
     */
    public function tasks(Request $request)
    {
        $internIds = $this->getSchoolInterns()->pluck('id');
        $query = Task::whereIn('intern_id', $internIds)->with('intern');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        // Filter by intern
        if ($request->has('intern_id') && $request->intern_id) {
            $query->where('intern_id', $request->intern_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $tasks = $query->orderBy('due_date', 'asc')->paginate(15);
        $interns = $this->getSchoolInterns()->orderBy('name')->get();
        $school = School::find($this->getSchoolId());

        return view('team-leader.tasks.index', compact('tasks', 'interns', 'school'));
    }

    /**
     * Show create task form
     */
    public function createTask()
    {
        $interns = $this->getSchoolInterns()->where('status', 'Active')->orderBy('name')->get();
        return view('team-leader.tasks.create', compact('interns'));
    }

    /**
     * Store a new task
     */
    public function storeTask(Request $request)
    {
        $request->validate([
            'intern_id' => 'required|exists:interns,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        // Verify intern belongs to team leader's school
        $intern = Intern::findOrFail($request->intern_id);
        if ($intern->school_id !== $this->getSchoolId()) {
            abort(403, 'Cannot assign task to intern from another school.');
        }

        Task::create([
            'intern_id' => $request->intern_id,
            'title' => $request->title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'priority' => $request->priority,
            'status' => 'Not Started',
            'due_date' => $request->due_date,
            'assigned_by' => Auth::id(),
            'progress' => 0,
        ]);

        return redirect()->route('team-leader.dashboard')->with('success', 'Task created successfully.');
    }

    /**
     * Show edit task form
     */
    public function editTask(Task $task)
    {
        // Verify task belongs to an intern from team leader's school
        if ($task->intern->school_id !== $this->getSchoolId()) {
            abort(403, 'Unauthorized access to this task.');
        }

        $interns = $this->getSchoolInterns()->where('status', 'Active')->orderBy('name')->get();
        return view('team-leader.tasks.edit', compact('task', 'interns'));
    }

    /**
     * Update a task
     */
    public function updateTask(Request $request, Task $task)
    {
        // Verify task belongs to an intern from team leader's school
        if ($task->intern->school_id !== $this->getSchoolId()) {
            abort(403, 'Unauthorized access to this task.');
        }

        $request->validate([
            'intern_id' => 'required|exists:interns,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|in:Not Started,Pending,In Progress,Completed,On Hold',
            'due_date' => 'required|date',
            'progress' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        // Verify new intern belongs to team leader's school
        $intern = Intern::findOrFail($request->intern_id);
        if ($intern->school_id !== $this->getSchoolId()) {
            abort(403, 'Cannot reassign task to intern from another school.');
        }

        $task->update([
            'intern_id' => $request->intern_id,
            'title' => $request->title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'progress' => $request->progress,
            'notes' => $request->notes,
            'completed_date' => $request->status === 'Completed' ? Carbon::today() : null,
        ]);

        return redirect()->route('team-leader.dashboard')->with('success', 'Task updated successfully.');
    }

    /**
     * Delete a task
     */
    public function deleteTask(Task $task)
    {
        // Verify task belongs to an intern from team leader's school
        if ($task->intern->school_id !== $this->getSchoolId()) {
            abort(403, 'Unauthorized access to this task.');
        }

        $task->delete();

        return redirect()->route('team-leader.dashboard')->with('success', 'Task deleted successfully.');
    }

    /**
     * Display reports
     */
    public function reports()
    {
        $reports = TeamLeaderReport::where('team_leader_id', Auth::id())
            ->with('school')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('team-leader.reports.index', compact('reports'));
    }

    /**
     * Show create report form
     */
    public function createReport()
    {
        $school = School::find($this->getSchoolId());
        $interns = $this->getSchoolInterns()->get();
        $internIds = $interns->pluck('id');

        // Get task statistics for pre-filling
        $taskStats = [
            'total' => Task::whereIn('intern_id', $internIds)->count(),
            'completed' => Task::whereIn('intern_id', $internIds)->where('status', 'Completed')->count(),
            'in_progress' => Task::whereIn('intern_id', $internIds)->where('status', 'In Progress')->count(),
            'pending' => Task::whereIn('intern_id', $internIds)->where('status', 'Pending')->count(),
        ];

        $reportTypes = TeamLeaderReport::getReportTypes();

        return view('team-leader.reports.create', compact('school', 'interns', 'taskStats', 'reportTypes'));
    }

    /**
     * Store a new report
     */
    public function storeReport(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'report_type' => 'required|in:weekly,monthly,performance,attendance,custom',
            'summary' => 'required|string',
            'accomplishments' => 'nullable|string',
            'challenges' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'status' => 'required|in:draft,submitted',
        ]);

        $schoolId = $this->getSchoolId();
        $internIds = $this->getSchoolInterns()->pluck('id');

        // Calculate task statistics
        $taskStats = [
            'total' => Task::whereIn('intern_id', $internIds)->count(),
            'completed' => Task::whereIn('intern_id', $internIds)->where('status', 'Completed')->count(),
            'in_progress' => Task::whereIn('intern_id', $internIds)->where('status', 'In Progress')->count(),
            'pending' => Task::whereIn('intern_id', $internIds)->where('status', 'Pending')->count(),
            'completion_rate' => Task::whereIn('intern_id', $internIds)->count() > 0
                ? round((Task::whereIn('intern_id', $internIds)->where('status', 'Completed')->count() / Task::whereIn('intern_id', $internIds)->count()) * 100, 1)
                : 0,
        ];

        TeamLeaderReport::create([
            'team_leader_id' => Auth::id(),
            'school_id' => $schoolId,
            'title' => $request->title,
            'report_type' => $request->report_type,
            'summary' => $request->summary,
            'accomplishments' => $request->accomplishments,
            'challenges' => $request->challenges,
            'recommendations' => $request->recommendations,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'task_statistics' => $taskStats,
            'status' => $request->status,
        ]);

        $message = $request->status === 'submitted'
            ? 'Report submitted successfully.'
            : 'Report saved as draft.';

        return redirect()->route('team-leader.reports')->with('success', $message);
    }

    /**
     * View a report
     */
    public function showReport(TeamLeaderReport $report)
    {
        // Verify report belongs to this team leader
        if ($report->team_leader_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this report.');
        }

        return view('team-leader.reports.show', compact('report'));
    }

    /**
     * Edit a report
     */
    public function editReport(TeamLeaderReport $report)
    {
        // Verify report belongs to this team leader
        if ($report->team_leader_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this report.');
        }

        // Can only edit drafts
        if ($report->status !== 'draft') {
            return redirect()->route('team-leader.reports.show', $report)
                ->with('error', 'Cannot edit a submitted report.');
        }

        $reportTypes = TeamLeaderReport::getReportTypes();
        return view('team-leader.reports.edit', compact('report', 'reportTypes'));
    }

    /**
     * Update a report
     */
    public function updateReport(Request $request, TeamLeaderReport $report)
    {
        // Verify report belongs to this team leader
        if ($report->team_leader_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this report.');
        }

        // Can only edit drafts
        if ($report->status !== 'draft') {
            return redirect()->route('team-leader.reports.show', $report)
                ->with('error', 'Cannot edit a submitted report.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'report_type' => 'required|in:weekly,monthly,performance,attendance,custom',
            'summary' => 'required|string',
            'accomplishments' => 'nullable|string',
            'challenges' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'status' => 'required|in:draft,submitted',
        ]);

        $report->update($request->only([
            'title',
            'report_type',
            'summary',
            'accomplishments',
            'challenges',
            'recommendations',
            'period_start',
            'period_end',
            'status',
        ]));

        $message = $request->status === 'submitted'
            ? 'Report submitted successfully.'
            : 'Report saved as draft.';

        return redirect()->route('team-leader.reports')->with('success', $message);
    }

    /**
     * Delete a report
     */
    public function deleteReport(TeamLeaderReport $report)
    {
        // Verify report belongs to this team leader
        if ($report->team_leader_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this report.');
        }

        // Can only delete drafts
        if ($report->status !== 'draft') {
            return redirect()->route('team-leader.reports')
                ->with('error', 'Cannot delete a submitted report.');
        }

        $report->delete();

        return redirect()->route('team-leader.reports')->with('success', 'Report deleted successfully.');
    }

    /**
     * View attendance for team
     */
    public function attendance(Request $request)
    {
        $internIds = $this->getSchoolInterns()->pluck('id');
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today('Asia/Manila');

        $attendances = Attendance::whereIn('intern_id', $internIds)
            ->where('date', $date->toDateString())
            ->with('intern')
            ->get();

        $interns = $this->getSchoolInterns()->where('status', 'Active')->get();

        // Calculate stats
        $present = $attendances->where('status', 'Present')->count();
        $absent = $interns->count() - $present;
        $late = $attendances->filter(function ($a) {
            return $a->time_in && Carbon::parse($a->time_in)->format('H:i') > '08:00';
        })->count();

        return view('team-leader.attendance.index', compact(
            'attendances',
            'interns',
            'date',
            'present',
            'absent',
            'late'
        ));
    }

    /**
     * Quick update task status (AJAX)
     */
    public function updateTaskStatus(Request $request, Task $task)
    {
        // Verify task belongs to an intern from team leader's school
        if ($task->intern->school_id !== $this->getSchoolId()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed,On Hold',
        ]);

        $task->update([
            'status' => $request->status,
            'completed_date' => $request->status === 'Completed' ? Carbon::today() : null,
        ]);

        return response()->json(['success' => true, 'task' => $task]);
    }

    /**
     * Quick update task progress (AJAX)
     */
    public function updateTaskProgress(Request $request, Task $task)
    {
        // Verify task belongs to an intern from team leader's school
        if ($task->intern->school_id !== $this->getSchoolId()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $task->update(['progress' => $request->progress]);

        return response()->json(['success' => true, 'task' => $task]);
    }

    /**
     * Get tasks data for live updates (AJAX)
     */
    public function getTasksData()
    {
        $internIds = $this->getSchoolInterns()->pluck('id');

        $tasks = Task::whereIn('intern_id', $internIds)
            ->with('intern:id,name')
            ->orderBy('due_date', 'asc')
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'intern_name' => $task->intern->name ?? 'N/A',
                    'intern_initial' => strtoupper(substr($task->intern->name ?? 'N', 0, 1)),
                    'priority' => $task->priority,
                    'due_date' => $task->due_date ? $task->due_date->format('M d, Y') : 'No due date',
                    'is_overdue' => $task->due_date && $task->due_date->lt(Carbon::today()) && $task->status !== 'Completed',
                    'progress' => $task->progress ?? 0,
                    'status' => $task->status,
                    'notes' => $task->notes,
                ];
            });

        return response()->json([
            'success' => true,
            'tasks' => $tasks,
            'updated_at' => Carbon::now('Asia/Manila')->format('h:i:s A'),
        ]);
    }

    /**
     * Get dashboard statistics for live updates (AJAX)
     */
    public function getDashboardStats()
    {
        $internIds = $this->getSchoolInterns()->pluck('id');

        $stats = [
            'pending_tasks' => Task::whereIn('intern_id', $internIds)->where('status', 'Not Started')->count(),
            'in_progress_tasks' => Task::whereIn('intern_id', $internIds)->where('status', 'In Progress')->count(),
            'completed_tasks' => Task::whereIn('intern_id', $internIds)->where('status', 'Completed')->count(),
            'overdue_tasks' => Task::whereIn('intern_id', $internIds)
                ->where('status', '!=', 'Completed')
                ->where('due_date', '<', Carbon::today())
                ->count(),
            'total_interns' => $this->getSchoolInterns()->count(),
            'active_interns' => $this->getSchoolInterns()->where('status', 'Active')->count(),
            'present_today' => Attendance::whereIn('intern_id', $internIds)
                ->where('date', Carbon::today('Asia/Manila')->toDateString())
                ->where('status', 'Present')
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'updated_at' => Carbon::now('Asia/Manila')->format('h:i:s A'),
        ]);
    }

    /**
     * Get interns data for live updates (AJAX)
     */
    public function getInternsData()
    {
        $interns = $this->getSchoolInterns()
            ->orderBy('name')
            ->get()
            ->map(function ($intern) {
                $progress = $intern->required_hours > 0
                    ? round(($intern->completed_hours / $intern->required_hours) * 100, 1)
                    : 0;
                return [
                    'id' => $intern->id,
                    'name' => $intern->name,
                    'email' => $intern->email,
                    'phone' => $intern->phone,
                    'course' => $intern->course ?? 'N/A',
                    'year_level' => $intern->year_level,
                    'completed_hours' => number_format($intern->completed_hours, 1),
                    'required_hours' => $intern->required_hours,
                    'progress' => $progress,
                    'status' => $intern->status,
                    'start_date' => $intern->start_date ? Carbon::parse($intern->start_date)->format('M d, Y') : null,
                    'end_date' => $intern->end_date ? Carbon::parse($intern->end_date)->format('M d, Y') : null,
                    'initial' => strtoupper(substr($intern->name, 0, 1)),
                    'profile_picture_url' => $intern->profile_picture ? asset('storage/' . $intern->profile_picture) : null,
                ];
            });

        return response()->json([
            'success' => true,
            'interns' => $interns,
            'updated_at' => Carbon::now('Asia/Manila')->format('h:i:s A'),
        ]);
    }

    /**
     * Get today's attendance data for live updates (AJAX)
     */
    public function getAttendanceData()
    {
        $internIds = $this->getSchoolInterns()->pluck('id');
        $today = Carbon::today('Asia/Manila');

        $attendances = Attendance::whereIn('intern_id', $internIds)
            ->where('date', $today->toDateString())
            ->with('intern:id,name,course')
            ->get()
            ->map(function ($attendance) {
                $timeIn = $attendance->time_in ? Carbon::parse($attendance->time_in) : null;
                $timeOut = $attendance->time_out ? Carbon::parse($attendance->time_out) : null;
                $hoursWorked = $timeIn && $timeOut ? round($timeOut->diffInMinutes($timeIn) / 60, 2) : 0;
                $isLate = $timeIn && $timeIn->format('H:i') > '08:00';

                return [
                    'id' => $attendance->id,
                    'intern_id' => $attendance->intern_id,
                    'intern_name' => $attendance->intern->name ?? 'N/A',
                    'intern_course' => $attendance->intern->course ?? '',
                    'intern_initial' => strtoupper(substr($attendance->intern->name ?? 'N', 0, 1)),
                    'time_in' => $timeIn ? $timeIn->format('h:i A') : '--:--',
                    'time_out' => $timeOut ? $timeOut->format('h:i A') : null,
                    'is_still_working' => $timeIn && !$timeOut,
                    'hours_worked' => $hoursWorked,
                    'is_late' => $isLate,
                    'status' => $attendance->status,
                ];
            });

        $activeInterns = $this->getSchoolInterns()->where('status', 'Active')->count();
        $presentCount = $attendances->where('status', 'Present')->count();
        $lateCount = $attendances->filter(fn($a) => $a['is_late'])->count();

        return response()->json([
            'success' => true,
            'attendances' => $attendances,
            'stats' => [
                'present' => $presentCount,
                'absent' => $activeInterns - $presentCount,
                'late' => $lateCount,
            ],
            'updated_at' => Carbon::now('Asia/Manila')->format('h:i:s A'),
        ]);
    }

    /**
     * Update a startup submission status (for incubatee tracker)
     */
    public function updateSubmission(Request $request, StartupSubmission $submission)
    {
        // Check if user has edit access to incubatee_tracker
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $editableModules = $user->getEditableModules();

        if (!in_array('incubatee_tracker', $editableModules)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to edit incubatee submissions.'
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,under_review,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $submission->status = $validated['status'];
        $submission->admin_notes = $validated['admin_notes'] ?? $submission->admin_notes;
        $submission->reviewed_by = $user->id;
        $submission->reviewed_at = Carbon::now();
        $submission->save();

        return response()->json([
            'success' => true,
            'message' => 'Submission updated successfully.',
            'submission' => $submission
        ]);
    }

    /**
     * Update a room issue status (for issues management)
     */
    public function updateRoomIssue(Request $request, RoomIssue $roomIssue)
    {
        // Check if user has edit access to issues_management
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $editableModules = $user->getEditableModules();

        if (!in_array('issues_management', $editableModules)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to edit room issues.'
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $roomIssue->status = $validated['status'];
        $roomIssue->admin_notes = $validated['admin_notes'] ?? $roomIssue->admin_notes;
        $roomIssue->resolved_by = $user->id;

        if ($validated['status'] === 'resolved' || $validated['status'] === 'closed') {
            $roomIssue->resolved_at = Carbon::now();
        }

        $roomIssue->save();

        return response()->json([
            'success' => true,
            'message' => 'Room issue updated successfully.',
            'issue' => $roomIssue
        ]);
    }

    /**
     * Upload profile picture for team leader
     */
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Delete old profile picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Store new profile picture
        $path = $request->file('profile_picture')->store('profile-pictures', 'public');

        $user->update(['profile_picture' => $path]);

        // Sync profile picture to linked Intern account if exists
        $linkedIntern = Intern::where('email', $user->email)->first();
        if ($linkedIntern) {
            // Delete old intern profile picture if different
            if ($linkedIntern->profile_picture && $linkedIntern->profile_picture !== $path) {
                Storage::disk('public')->delete($linkedIntern->profile_picture);
            }
            $linkedIntern->update(['profile_picture' => $path]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile picture updated successfully',
            'image_url' => asset('storage/' . $path)
        ]);
    }

    /**
     * Show the password reset form for team leaders
     */
    public function showResetPasswordForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect()->route('admin.login')->with('error', 'Invalid password reset link.');
        }

        // Check if token exists and is valid
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRecord) {
            return redirect()->route('admin.login')->with('error', 'Invalid or expired password reset link.');
        }

        // Check if token matches
        if (!Hash::check($token, $resetRecord->token)) {
            return redirect()->route('admin.login')->with('error', 'Invalid password reset link.');
        }

        // Check if token is not expired (24 hours)
        if (Carbon::parse($resetRecord->created_at)->addHours(24)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('admin.login')->with('error', 'Password reset link has expired. Please request a new one.');
        }

        return view('team-leader.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    /**
     * Handle password reset for team leaders
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Find the reset token record
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Invalid or expired password reset link.']);
        }

        // Verify token
        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['token' => 'Invalid password reset link.']);
        }

        // Check expiration (24 hours)
        if (Carbon::parse($resetRecord->created_at)->addHours(24)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['token' => 'Password reset link has expired.']);
        }

        // Update the user's password
        $user = User::where('email', $request->email)
            ->where('role', User::ROLE_TEAM_LEADER)
            ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No team leader account found with this email.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete the reset token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('admin.login')->with('success', 'Password has been reset successfully. You can now log in with your new password.');
    }


    public function switchToIntern(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Find the linked intern by name (full name match) with reference code starting with INT
        $intern = Intern::where('name', $user->name)
            ->where('reference_code', 'like', 'INT-%')
            ->where('approval_status', 'approved')
            ->first();

        // If not found by name, try by email
        if (!$intern) {
            $intern = Intern::where('email', $user->email)
                ->where('reference_code', 'like', 'INT-%')
                ->where('approval_status', 'approved')
                ->first();
        }

        if (!$intern) {
            return redirect()->back()
                ->with('error', 'No linked intern account found. Please contact the administrator.');
        }

        // Logout from team leader auth but keep the session alive for intern portal
        Auth::logout();

        // Set intern session (intern portal uses session-based access, not Auth)
        $request->session()->put('intern_id', $intern->id);
        $request->session()->regenerateToken();

        return redirect()->route('intern.portal')
            ->with('success', 'Switched to Intern Portal. Welcome, ' . $intern->name . '!');

    }
}
