<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamLeaderReport extends Model
{
    protected $fillable = [
        'team_leader_id',
        'school_id',
        'title',
        'report_type',
        'summary',
        'accomplishments',
        'challenges',
        'recommendations',
        'period_start',
        'period_end',
        'intern_highlights',
        'task_statistics',
        'attachments',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_feedback',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'intern_highlights' => 'array',
        'task_statistics' => 'array',
        'attachments' => 'array',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Report type options
     */
    const TYPE_WEEKLY = 'weekly';
    const TYPE_MONTHLY = 'monthly';
    const TYPE_PERFORMANCE = 'performance';
    const TYPE_ATTENDANCE = 'attendance';
    const TYPE_CUSTOM = 'custom';

    /**
     * Status options
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_ACKNOWLEDGED = 'acknowledged';

    /**
     * Get the team leader who created this report
     */
    public function teamLeader()
    {
        return $this->belongsTo(User::class, 'team_leader_id');
    }

    /**
     * Get the school this report is about
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the admin who reviewed this report
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Check if report is submitted
     */
    public function isSubmitted(): bool
    {
        return $this->status !== self::STATUS_DRAFT;
    }

    /**
     * Check if report is reviewed
     */
    public function isReviewed(): bool
    {
        return in_array($this->status, [self::STATUS_REVIEWED, self::STATUS_ACKNOWLEDGED]);
    }

    /**
     * Submit the report
     */
    public function submit(): void
    {
        $this->status = self::STATUS_SUBMITTED;
        $this->save();
    }

    /**
     * Mark as reviewed by admin
     */
    public function markReviewed(User $admin, ?string $feedback = null): void
    {
        $this->status = self::STATUS_REVIEWED;
        $this->reviewed_by = $admin->id;
        $this->reviewed_at = now();
        $this->admin_feedback = $feedback;
        $this->save();
    }

    /**
     * Get report types for dropdown
     */
    public static function getReportTypes(): array
    {
        return [
            self::TYPE_WEEKLY => 'Weekly Report',
            self::TYPE_MONTHLY => 'Monthly Report',
            self::TYPE_PERFORMANCE => 'Performance Report',
            self::TYPE_ATTENDANCE => 'Attendance Report',
            self::TYPE_CUSTOM => 'Custom Report',
        ];
    }

    /**
     * Get status options for dropdown
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_REVIEWED => 'Reviewed',
            self::STATUS_ACKNOWLEDGED => 'Acknowledged',
        ];
    }
}
