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
        'notes',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'hours_worked' => 'decimal:2',
    ];

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
        if (!$this->time_in || !$this->time_out) {
            return 0;
        }

        $timeIn = Carbon::parse($this->time_in);
        $timeOut = Carbon::parse($this->time_out);

        return round($timeOut->diffInMinutes($timeIn) / 60, 2);
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
}
