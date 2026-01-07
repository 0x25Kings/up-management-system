<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Intern;
use App\Models\Attendance;
use Carbon\Carbon;

class InternController extends Controller
{
    /**
     * Show the intern portal (form or dashboard based on session)
     */
    public function index(Request $request)
    {
        // Check if intern is already registered in this session
        $internId = $request->session()->get('intern_id');
        
        if ($internId) {
            $intern = Intern::with(['attendances' => function($query) {
                $query->orderBy('date', 'desc')->limit(10);
            }])->find($internId);
            
            if ($intern) {
                $todayAttendance = $intern->today_attendance;
                $attendanceHistory = $intern->attendances()->orderBy('date', 'desc')->limit(10)->get();
                
                return view('portals.intern', [
                    'intern' => $intern,
                    'showDashboard' => true,
                    'todayAttendance' => $todayAttendance,
                    'attendanceHistory' => $attendanceHistory,
                ]);
            }
        }

        return view('portals.intern', [
            'intern' => null,
            'showDashboard' => false,
            'todayAttendance' => null,
            'attendanceHistory' => collect(),
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
            'school' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'year_level' => 'nullable|string|max:50',
        ]);

        // Generate reference code
        $validated['reference_code'] = Intern::generateReferenceCode();
        $validated['start_date'] = now();

        // Create the intern
        $intern = Intern::create($validated);

        // Store intern ID in session
        $request->session()->put('intern_id', $intern->id);

        return redirect()->route('intern.portal')
            ->with('success', 'Registration successful! Your reference code is: ' . $intern->reference_code);
    }

    /**
     * Access portal with reference code
     */
    public function accessWithCode(Request $request)
    {
        $request->validate([
            'reference_code' => 'required|string',
        ]);

        $intern = Intern::where('reference_code', $request->reference_code)->first();

        if (!$intern) {
            return back()->withErrors(['reference_code' => 'Invalid reference code. Please check and try again.']);
        }

        // Store intern ID in session
        $request->session()->put('intern_id', $intern->id);

        return redirect()->route('intern.portal')
            ->with('success', 'Welcome back, ' . $intern->name . '!');
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
     * Time In
     */
    public function timeIn(Request $request)
    {
        $internId = $request->session()->get('intern_id');
        
        if (!$internId) {
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
                    return back()->with('error', 'You have already timed in today.');
                }
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Record already exists, fetch it and check
                $attendance = Attendance::where('intern_id', $intern->id)
                    ->where('date', $today)
                    ->first();
                    
                if ($attendance && $attendance->time_in) {
                    return back()->with('error', 'You have already timed in today.');
                }
                
                return back()->with('error', 'An error occurred. Please try again.');
            }
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
        
        if (!$internId) {
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
            return back()->with('error', 'You need to time in first.');
        }

        if ($attendance->time_out) {
            return back()->with('error', 'You have already timed out today.');
        }

        // Calculate hours worked
        $timeIn = Carbon::parse($attendance->time_in);
        $hoursWorked = round($now->diffInMinutes($timeIn) / 60, 2);

        // Update attendance record
        $attendance->update([
            'time_out' => $now->format('H:i:s'),
            'hours_worked' => $hoursWorked,
        ]);

        // Update intern's completed hours
        $intern->increment('completed_hours', floor($hoursWorked));

        return redirect()->route('intern.portal', ['page' => 'attendance'])
            ->with('success', 'Time Out recorded at ' . $now->format('h:i A') . '. Hours worked: ' . $hoursWorked);
    }
}
