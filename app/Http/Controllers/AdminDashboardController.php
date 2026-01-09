<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Intern;
use App\Models\Attendance;
use App\Models\Task;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function index()
    {
        // Get intern statistics
        $totalInterns = Intern::count();
        $activeInterns = Intern::where('status', 'Active')->count();
        
        // Get all interns with their latest attendance
        $interns = Intern::with(['attendances' => function($query) {
            $query->orderBy('date', 'desc')->limit(1);
        }])->orderBy('created_at', 'desc')->get();

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

        return view('admin.dashboard', [
            'user' => Auth::user(),
            'totalInterns' => $totalInterns,
            'activeInterns' => $activeInterns,
            'interns' => $interns,
            'todayAttendances' => $todayAttendances,
            'attendanceHistory' => $attendanceHistory,
            'internsBySchool' => $internsBySchool,
            'tasks' => $tasks,
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
        if ($intern) {
            $intern->increment('completed_hours', floor($attendance->overtime_hours));
        }

        return response()->json([
            'success' => true,
            'message' => 'Overtime approved successfully. ' . $attendance->overtime_hours . ' hours added.',
            'overtime_hours' => $attendance->overtime_hours
        ]);
    }
}
