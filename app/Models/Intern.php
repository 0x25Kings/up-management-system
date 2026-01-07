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
        'name',
        'age',
        'gender',
        'email',
        'phone',
        'school',
        'course',
        'year_level',
        'start_date',
        'end_date',
        'required_hours',
        'completed_hours',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get all attendances for this intern
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get today's attendance record
     */
    public function getTodayAttendanceAttribute()
    {
        return $this->attendances()
            ->where('date', Carbon::now('Asia/Manila')->toDateString())
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
}
