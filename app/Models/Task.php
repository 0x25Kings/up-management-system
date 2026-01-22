<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    protected $fillable = [
        'intern_id',
        'checklist',
        'title',
        'description',
        'requirements',
        'priority',
        'status',
        'due_date',
        'completed_date',
        'assigned_by',
        'progress',
        'notes',
        'documents',
        'started_at',
        'group_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_date' => 'date',
        'documents' => 'array',
        'checklist' => 'array',
    ];

    /**
     * Get the intern assigned this task
     */
    public function intern()
    {
        return $this->belongsTo(Intern::class);
    }

    /**
     * Get the admin who assigned this task
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Check if task is overdue
     */
    public function isOverdue(): bool
    {
        if (!$this->due_date || $this->status === 'Completed') {
            return false;
        }
        return Carbon::now('Asia/Manila')->toDateString() > $this->due_date->toDateString();
    }

    /**
     * Get days remaining
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->due_date || $this->status === 'Completed') {
            return null;
        }
        return now('Asia/Manila')->diffInDays($this->due_date, false);
    }

    /**
     * Mark task as completed
     */
    public function markCompleted(): void
    {
        $this->status = 'Completed';
        $this->completed_date = now('Asia/Manila');
        $this->save();
    }

    /**
     * Get all group members for this task
     */
    public function groupMembers()
    {
        if (!$this->group_id) {
            return collect([$this]);
        }
        return self::where('group_id', $this->group_id)->with('intern')->get();
    }

    /**
     * Check if this is a group task
     */
    public function isGroupTask(): bool
    {
        return $this->group_id !== null && self::where('group_id', $this->group_id)->count() > 1;
    }
}
