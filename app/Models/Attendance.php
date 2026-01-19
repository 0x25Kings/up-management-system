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

    /**
     * Get the intern that owns the attendance
     */
    public function intern()
    {
        return $this->belongsTo(Intern::class);
    }

    /**
     * Calculate hours worked between time_in and time_out
     */
    public function calculateHoursWorked(): float
    {
        if (!$this->time_in) {
            return 0;
        }

        $attendanceDate = $this->date ? Carbon::parse($this->date)->format('Y-m-d') : Carbon::now('Asia/Manila')->format('Y-m-d');
        $timeIn = Carbon::parse($attendanceDate . ' ' . $this->time_in, 'Asia/Manila');
        $timeOut = $this->time_out
            ? Carbon::parse($attendanceDate . ' ' . $this->time_out, 'Asia/Manila')
            : Carbon::now('Asia/Manila');

        return round($timeOut->diffInSeconds($timeIn, true) / 3600, 2);
    }

    /**
     * Get current hours worked (live calculation for display)
     * Uses time_out if available, otherwise uses current time
     */
    public function getCurrentHoursWorkedAttribute(): float
    {
        if (!$this->time_in) {
            return 0;
        }

        $attendanceDate = $this->date ? Carbon::parse($this->date)->format('Y-m-d') : Carbon::now('Asia/Manila')->format('Y-m-d');
        $timeIn = Carbon::parse($attendanceDate . ' ' . $this->time_in, 'Asia/Manila');

        if ($this->time_out) {
            // Use stored hours_worked if already timed out
            return (float) $this->hours_worked;
        }

        // Calculate live hours if still working
        $now = Carbon::now('Asia/Manila');
        return round($now->diffInSeconds($timeIn, true) / 3600, 2);
    }

    /**
     * Get formatted time in
     */
    public function getFormattedTimeInAttribute(): string
    {
        return $this->time_in ? Carbon::parse($this->time_in)->format('h:i A') : '--:--';
    }

    /**
     * Get formatted time out
     */
    public function getFormattedTimeOutAttribute(): string
    {
        return $this->time_out ? Carbon::parse($this->time_out)->format('h:i A') : '--:--';
    }

    /**
     * Get raw time in as ISO string for JavaScript
     */
    public function getRawTimeInAttribute(): string
    {
        if (!$this->time_in) {
            return '';
        }

        $date = $this->date ? Carbon::parse($this->date)->format('Y-m-d') : now('Asia/Manila')->format('Y-m-d');
        return Carbon::parse($date . ' ' . $this->time_in, 'Asia/Manila')->toIso8601String();
    }

    /**
     * Calculate and set overtime/undertime based on hours worked
     */
    public function calculateOvertimeUndertime(): void
    {
        $hoursWorked = (float) $this->hours_worked;

        if ($hoursWorked >= self::REQUIRED_HOURS) {
            $this->attributes['overtime_hours'] = round($hoursWorked - self::REQUIRED_HOURS, 2);
            $this->attributes['undertime_hours'] = 0;
        } else {
            $this->attributes['overtime_hours'] = 0;
            $this->attributes['undertime_hours'] = round(self::REQUIRED_HOURS - $hoursWorked, 2);
        }
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
     */
    public function getEffectiveHoursAttribute(): float
    {
        $baseHours = min((float) $this->hours_worked, self::REQUIRED_HOURS);

        if ($this->overtime_approved) {
            return (float) $this->hours_worked;
        }

        return $baseHours;
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
