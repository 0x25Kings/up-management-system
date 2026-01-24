<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Intern extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_code',
        'profile_picture',
        'name',
        'age',
        'gender',
        'email',
        'password',
        'phone',
        'school',
        'school_id',
        'course',
        'year_level',
        'start_date',
        'end_date',
        'required_hours',
        'completed_hours',
        'status',
        'approval_status',
        'rejection_reason',
        'approved_at',
        'approved_by',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the school this intern belongs to
     */
    public function schoolRelation()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * Get the user who approved this intern
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all attendances for this intern
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get all tasks assigned to this intern
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get all document folders for this intern
     */
    public function documentFolders()
    {
        return $this->hasMany(DocumentFolder::class);
    }

    /**
     * Get all documents for this intern
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Check if intern is pending approval
     */
    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if intern is approved
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if intern is rejected
     */
    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }

    /**
     * Scope for approved interns only
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope for pending interns only
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Get today's attendance record
     */
    public function getTodayAttendanceAttribute()
    {
        return $this->attendances()
            ->whereDate('date', Carbon::now('Asia/Manila')->toDateString())
            ->first();
    }

    /**
     * Check if intern has timed in today
     */
    public function hasTimedInToday(): bool
    {
        $today = $this->today_attendance;
        return $today && $today->time_in !== null;
    }

    /**
     * Check if intern has timed out today
     */
    public function hasTimedOutToday(): bool
    {
        $today = $this->today_attendance;
        return $today && $today->time_out !== null;
    }

    /**
     * Generate a unique reference code for the intern
     */
    public static function generateReferenceCode(): string
    {
        do {
            $code = 'INT-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (self::where('reference_code', $code)->exists());

        return $code;
    }

    /**
     * Get the progress percentage
     */
    public function getProgressPercentageAttribute(): int
    {
        if ($this->required_hours == 0) return 0;
        return min(100, round(($this->completed_hours / $this->required_hours) * 100));
    }

    /**
     * Get remaining hours
     */
    public function getRemainingHoursAttribute(): int
    {
        return max(0, $this->required_hours - $this->completed_hours);
    }

    /**
     * Auto-update task progress based on attendance records
     * Called daily to increment progress for active tasks
     */
    public function autoUpdateTaskProgress()
    {
        $activeTasks = $this->tasks()
            ->where('status', 'In Progress')
            ->whereNull('completed_date')
            ->get();

        foreach ($activeTasks as $task) {
            // Count unique days with attendance since task started
            $daysWorked = $this->attendances()
                ->whereDate('date', '>=', $task->started_at ?? $task->created_at)
                ->distinct('date')
                ->count();

            if ($daysWorked > 0) {
                // Estimate task duration in days (if available from due_date)
                $totalDays = $task->started_at
                    ? $task->due_date->diffInDays($task->started_at) + 1
                    : 5; // Default 5 days if no due date

                // Calculate progress: (days worked / total estimated days) * 100
                $estimatedProgress = min(95, round(($daysWorked / max($totalDays, 1)) * 100));

                // Only update if progress increased
                if ($estimatedProgress > $task->progress) {
                    $task->update(['progress' => $estimatedProgress]);
                }
            }
        }
    }
}
