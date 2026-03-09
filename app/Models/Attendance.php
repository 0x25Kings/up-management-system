<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'intern_id',
        'date',
        'time_in',
        'time_out',
        'hours_worked',
        'overtime_hours',
        'undertime_hours',
        'overtime_approved',
        'approved_by',
        'approved_at',
        'notes',
        'overtime_notes',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'hours_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'undertime_hours' => 'decimal:2',
        'overtime_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    // Required hours per day
    const REQUIRED_HOURS = 8;

    // Lunch break times (12:00 PM - 1:00 PM)
    const LUNCH_START_HOUR = 12;
    const LUNCH_END_HOUR = 13;
    const LUNCH_DURATION_HOURS = 1;

    /**
     * Get the intern that owns the attendance
     */
    public function intern()
    {
        return $this->belongsTo(Intern::class);
    }

    /**
     * Boot the model — auto-calculate overtime/undertime whenever a record with
     * both time_in and time_out is saved through Eloquent (prevents stale DB records).
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Attendance $attendance) {
            // Only (re)calculate when time_out is present and hours_worked is meaningful
            if ($attendance->time_out && (float) ($attendance->hours_worked ?? 0) > 0) {
                // Skip if the change originated inside calculateOvertimeUndertime itself
                // (it only touches overtime_hours/undertime_hours/overtime_approved, not hours_worked)
                if ($attendance->isDirty('hours_worked') || $attendance->isDirty('time_out')) {
                    $attendance->calculateOvertimeUndertime();
                }
            }
        });
    }

    /**
     * Calculate lunch break deduction based on time in and time out
     * Returns the number of hours to deduct for lunch
     */
    public static function calculateLunchDeduction(Carbon $timeIn, Carbon $timeOut): float
    {
        // Get lunch start and end times for the same day
        $lunchStart = $timeIn->copy()->setTime(self::LUNCH_START_HOUR, 0, 0);
        $lunchEnd = $timeIn->copy()->setTime(self::LUNCH_END_HOUR, 0, 0);

        // Check if the work period overlaps with lunch
        // Time in before lunch end AND time out after lunch start = overlap exists
        if ($timeIn < $lunchEnd && $timeOut > $lunchStart) {
            // Calculate actual overlap
            $overlapStart = $timeIn > $lunchStart ? $timeIn : $lunchStart;
            $overlapEnd = $timeOut < $lunchEnd ? $timeOut : $lunchEnd;

            // Use absolute value to ensure positive result
            $overlapMinutes = abs($overlapEnd->diffInMinutes($overlapStart));

            // Return the overlap in hours (max 1 hour)
            return min($overlapMinutes / 60, self::LUNCH_DURATION_HOURS);
        }

        return 0;
    }

    /**
     * Calculate hours worked between time_in and time_out
     * Deducts lunch break (12 PM - 1 PM) if applicable
     * Returns 0 if time_out is not set
     */
    public function calculateHoursWorked(): float
    {
        if (!$this->time_in || !$this->time_out) {
            return 0;
        }

        $attendanceDate = $this->date ? Carbon::parse($this->date)->format('Y-m-d') : Carbon::now('Asia/Manila')->format('Y-m-d');
        $timeIn = Carbon::parse($attendanceDate . ' ' . $this->time_in, 'Asia/Manila');
        $timeOut = Carbon::parse($attendanceDate . ' ' . $this->time_out, 'Asia/Manila');

        // Calculate total hours
        $totalHours = $timeOut->diffInSeconds($timeIn, true) / 3600;

        // Deduct lunch break if applicable
        $lunchDeduction = self::calculateLunchDeduction($timeIn, $timeOut);

        $netHours = $totalHours - $lunchDeduction;

        return round(max(0, $netHours), 2);
    }

    /**
     * Static method to calculate hours worked from time strings
     * Used by controllers before saving attendance
     */
    public static function calculateHoursFromTimes(string $timeInStr, string $timeOutStr, string $date = null): float
    {
        $attendanceDate = $date ?? Carbon::now('Asia/Manila')->format('Y-m-d');
        $timeIn = Carbon::parse($attendanceDate . ' ' . $timeInStr, 'Asia/Manila');
        $timeOut = Carbon::parse($attendanceDate . ' ' . $timeOutStr, 'Asia/Manila');

        // Calculate total hours
        $totalHours = $timeOut->diffInSeconds($timeIn, true) / 3600;

        // Deduct lunch break if applicable
        $lunchDeduction = self::calculateLunchDeduction($timeIn, $timeOut);

        $netHours = $totalHours - $lunchDeduction;

        return round(max(0, $netHours), 2);
    }

    /**
     * Get current hours worked
     * Returns 0 if time_out is not set (intern hasn't timed out yet)
     */
    public function getCurrentHoursWorkedAttribute(): float
    {
        if (!$this->time_in || !$this->time_out) {
            return 0;
        }

        // Use stored hours_worked if already timed out
        return (float) $this->hours_worked;
    }

    /**
     * Get formatted time in
     */
    public function getFormattedTimeInAttribute(): string
    {
        // Treat null and midnight placeholder as no time
        if (!$this->time_in || $this->time_in === '00:00:00') {
            return '--:--';
        }

        // Parse with date context to ensure proper timezone handling
        $date = $this->date ? Carbon::parse($this->date)->format('Y-m-d') : now('Asia/Manila')->format('Y-m-d');
        return Carbon::parse($date . ' ' . $this->time_in, 'Asia/Manila')->format('h:i A');
    }

    /**
     * Get formatted time out
     */
    public function getFormattedTimeOutAttribute(): string
    {
        if (!$this->time_out) {
            return '--:--';
        }

        // Parse with date context to ensure proper timezone handling
        $date = $this->date ? Carbon::parse($this->date)->format('Y-m-d') : now('Asia/Manila')->format('Y-m-d');
        return Carbon::parse($date . ' ' . $this->time_out, 'Asia/Manila')->format('h:i A');
    }

    /**
     * Get raw time in as ISO string for JavaScript
     */
    public function getRawTimeInAttribute(): string
    {
        // Treat null and midnight placeholder as no time
        if (!$this->time_in || $this->time_in === '00:00:00') {
            return '';
        }

        $date = $this->date ? Carbon::parse($this->date)->format('Y-m-d') : now('Asia/Manila')->format('Y-m-d');
        return Carbon::parse($date . ' ' . $this->time_in, 'Asia/Manila')->toIso8601String();
    }

    /**
     * Calculate and set overtime/undertime based on hours worked
     * Uses settings for threshold and cap
     */
    public function calculateOvertimeUndertime(): void
    {
        $hoursWorked = (float) $this->hours_worked;

        // Get threshold from settings (default 8.5 hours - only count OT after this)
        $threshold = (float) Setting::get('overtime_threshold', 8.5);
        // Get max daily OT cap from settings (default 4 hours)
        $maxOT = (float) Setting::get('overtime_max_daily', 4);
        // Check if auto-approve is enabled
        $autoApprove = Setting::get('overtime_auto_approve', false);

        if ($hoursWorked >= $threshold) {
            // Calculate raw overtime (hours above threshold)
            $rawOT = $hoursWorked - self::REQUIRED_HOURS;
            // Apply cap
            $cappedOT = min($rawOT, $maxOT);

            $this->attributes['overtime_hours'] = round($cappedOT, 2);
            $this->attributes['undertime_hours'] = 0;

            // Auto-approve if setting enabled
            if ($autoApprove && $cappedOT > 0 && !$this->overtime_approved) {
                $this->attributes['overtime_approved'] = true;
                $this->attributes['approved_at'] = now();
            }
        } elseif ($hoursWorked >= self::REQUIRED_HOURS) {
            // Between required hours and threshold - no overtime counted
            $this->attributes['overtime_hours'] = 0;
            $this->attributes['undertime_hours'] = 0;
        } else {
            $this->attributes['overtime_hours'] = 0;
            $this->attributes['undertime_hours'] = round(self::REQUIRED_HOURS - $hoursWorked, 2);
        }
    }

    /**
     * Get regular hours (capped at required hours)
     */
    public function getRegularHoursAttribute(): float
    {
        return min((float) $this->hours_worked, self::REQUIRED_HOURS);
    }

    /**
     * Get display string with split hours (e.g., "8.00 hrs + 3.00 OT")
     */
    public function getHoursDisplayAttribute(): string
    {
        $regular = $this->regular_hours;
        $ot = (float) $this->overtime_hours;

        if ($ot > 0) {
            return number_format($regular, 2) . ' hrs + ' . number_format($ot, 2) . ' OT';
        }

        return number_format((float) $this->hours_worked, 2) . ' hrs';
    }

    /**
     * Check if this attendance has overtime
     */
    public function hasOvertime(): bool
    {
        return (float) $this->overtime_hours > 0;
    }

    /**
     * Check if this attendance has undertime
     */
    public function hasUndertime(): bool
    {
        return (float) $this->undertime_hours > 0;
    }

    /**
     * Check if overtime is pending approval
     */
    public function isOvertimePending(): bool
    {
        return $this->hasOvertime() && !$this->overtime_approved;
    }

    /**
     * Get the effective hours (only approved overtime counts)
     * Returns hours that should be counted towards intern's total
     */
    public function getEffectiveHoursAttribute(): float
    {
        $hoursWorked = (float) ($this->hours_worked ?? 0);

        // If no hours worked, return 0
        if ($hoursWorked <= 0) {
            return 0;
        }

        // If overtime is approved, count all hours worked
        if ($this->overtime_approved) {
            return $hoursWorked;
        }

        // Otherwise, cap at required hours (8)
        return min($hoursWorked, self::REQUIRED_HOURS);
    }

    /**
     * Get the total hours worked for the day (not capped)
     * This is the actual time between time_in and time_out
     */
    public function getTotalRenderedHoursAttribute(): float
    {
        return (float) ($this->hours_worked ?? 0);
    }

    /**
     * Get display hours for admin (shows negative for undertime)
     */
    public function getDisplayHoursAttribute(): string
    {
        if ($this->hasUndertime()) {
            return '-' . number_format((float) $this->undertime_hours, 2);
        }
        return number_format((float) $this->hours_worked, 2);
    }

    /**
     * Approve overtime
     */
    public function approveOvertime(int $approvedBy): void
    {
        $this->overtime_approved = true;
        $this->approved_by = $approvedBy;
        $this->approved_at = now();
        $this->save();
    }

    /**
     * Get the user who approved the overtime
     */
    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }
}
