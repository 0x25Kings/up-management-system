<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use App\Models\Task;
use App\Models\Attendance;
use App\Models\TeamLeaderReport;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        $schoolId = $this->getSchoolId();
        $school = School::find($schoolId);

        if (!$school) {
            return redirect()->route('login')->with('error', 'No school assigned to your account.');
        }

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
            ->whereDate('date', $today)
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
            ->whereDate('date', $today)
            ->with('intern')
            ->get();

        // Get all active interns for attendance
        $activeInterns = $this->getSchoolInterns()->where('status', 'Active')->get();

        // Absent today
        $absentToday = $activeInterns->count() - $presentToday;

        // Late today (time_in after 8:00 AM)
        $lateToday = $todayAttendances->filter(function ($a) {
            return $a->time_in && \Carbon\Carbon::parse($a->time_in)->format('H:i') > '08:00';
        })->count();

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
            'today'
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
            'priority' => 'required|in:Low,Medium,High',
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
            'priority' => 'required|in:Low,Medium,High',
            'status' => 'required|in:Not Started,In Progress,Completed,On Hold',
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

        return redirect()->route('team-leader.tasks')->with('success', 'Task updated successfully.');
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

        return redirect()->route('team-leader.tasks')->with('success', 'Task deleted successfully.');
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
            ->whereDate('date', $date)
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
                ->whereDate('date', Carbon::today('Asia/Manila'))
                ->where('status', 'Present')
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'updated_at' => Carbon::now('Asia/Manila')->format('h:i:s A'),
        ]);
    }
}
