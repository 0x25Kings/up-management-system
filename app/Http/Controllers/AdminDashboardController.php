<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Intern;
use App\Models\Attendance;
use App\Models\Task;
use App\Models\Booking;
use App\Models\BlockedDate;
use App\Models\StartupSubmission;
use App\Models\RoomIssue;
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

        return view('admin.dashboard', [
            'user' => Auth::user(),
            'totalInterns' => $totalInterns,
            'activeInterns' => $activeInterns,
            'interns' => $interns,
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
