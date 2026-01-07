<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Intern;
use App\Models\Attendance;
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

        return view('admin.dashboard', [
            'user' => Auth::user(),
            'totalInterns' => $totalInterns,
            'activeInterns' => $activeInterns,
            'interns' => $interns,
            'todayAttendances' => $todayAttendances,
            'attendanceHistory' => $attendanceHistory,
            'internsBySchool' => $internsBySchool,
        ]);
    }
}
