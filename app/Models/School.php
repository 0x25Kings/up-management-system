<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'required_hours',
        'max_interns',
        'contact_person',
        'contact_email',
        'contact_phone',
        'status',
        'notes',
    ];

    /**
     * Get all interns from this school
     */
    public function interns()
    {
        return $this->hasMany(Intern::class);
    }

    /**
     * Get only approved interns from this school
     */
    public function approvedInterns()
    {
        return $this->hasMany(Intern::class)->where('approval_status', 'approved');
    }

    /**
     * Get only active interns from this school
     */
    public function activeInterns()
    {
        return $this->hasMany(Intern::class)
            ->where('approval_status', 'approved')
            ->where('status', 'Active');
    }

    /**
     * Get pending interns from this school
     */
    public function pendingInterns()
    {
        return $this->hasMany(Intern::class)->where('approval_status', 'pending');
    }

    /**
     * Get total rendered hours by all approved interns in this school
     */
    public function getTotalRenderedHoursAttribute(): float
    {
        return $this->approvedInterns()->sum('completed_hours');
    }

    /**
     * Get count of approved interns
     */
    public function getInternCountAttribute(): int
    {
        return $this->approvedInterns()->count();
    }

    /**
     * Get count of pending interns
     */
    public function getPendingCountAttribute(): int
    {
        return $this->pendingInterns()->count();
    }

    /**
     * Get average progress of all approved interns
     */
    public function getAverageProgressAttribute(): float
    {
        $interns = $this->approvedInterns;
        if ($interns->isEmpty()) {
            return 0;
        }
        
        $totalProgress = $interns->sum(function ($intern) {
            return $intern->progress_percentage;
        });
        
        return round($totalProgress / $interns->count(), 1);
    }

    /**
     * Check if school is active
     */
    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    /**
     * Scope for active schools
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Check if school has capacity for more interns
     */
    public function hasCapacity(): bool
    {
        if ($this->max_interns === null) {
            return true; // No limit set
        }
        return $this->intern_count < $this->max_interns;
    }

    /**
     * Get remaining slots
     */
    public function getRemainingSlots(): ?int
    {
        if ($this->max_interns === null) {
            return null; // Unlimited
        }
        return max(0, $this->max_interns - $this->intern_count);
    }

    /**
     * Get remaining slots attribute
     */
    public function getRemainingSlotsAttribute(): ?int
    {
        return $this->getRemainingSlots();
    }
}
