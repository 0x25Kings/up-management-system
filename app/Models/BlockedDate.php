<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedDate extends Model
{
    protected $fillable = [
        'blocked_date',
        'reason',
        'description',
        'created_by',
    ];

    protected $casts = [
        'blocked_date' => 'date',
    ];

    /**
     * Reason labels for display
     */
    public const REASON_LABELS = [
        'unavailable' => 'Not Available',
        'no_work' => 'No Work',
        'holiday' => 'Holiday',
        'sick' => 'Sick Day',
        'maintenance' => 'Maintenance',
        'other' => 'Other',
    ];

    /**
     * Reason colors for UI
     */
    public const REASON_COLORS = [
        'unavailable' => '#EF4444',
        'no_work' => '#F59E0B',
        'holiday' => '#8B5CF6',
        'sick' => '#EC4899',
        'maintenance' => '#6B7280',
        'other' => '#3B82F6',
    ];

    /**
     * Get the user who created this blocked date
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the reason label
     */
    public function getReasonLabelAttribute(): string
    {
        return self::REASON_LABELS[$this->reason] ?? 'Unknown';
    }

    /**
     * Get the reason color
     */
    public function getReasonColorAttribute(): string
    {
        return self::REASON_COLORS[$this->reason] ?? '#6B7280';
    }

    /**
     * Check if a date is blocked
     */
    public static function isBlocked($date): bool
    {
        return self::whereDate('blocked_date', $date)->exists();
    }

    /**
     * Get blocked date info
     */
    public static function getBlockedInfo($date)
    {
        return self::whereDate('blocked_date', $date)->first();
    }
}
