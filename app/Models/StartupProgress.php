<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StartupProgress extends Model
{
    use HasFactory;

    protected $table = 'startup_progress';

    protected $fillable = [
        'startup_id',
        'title',
        'description',
        'milestone_type',
        'file_path',
        'original_filename',
        'status',
        'admin_comment',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the startup that owns this progress update
     */
    public function startup(): BelongsTo
    {
        return $this->belongsTo(Startup::class);
    }

    /**
     * Get the admin who reviewed this progress
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get milestone type label
     */
    public function getMilestoneTypeLabelAttribute(): string
    {
        return match($this->milestone_type) {
            'development' => 'Product Development',
            'funding' => 'Funding/Investment',
            'partnership' => 'Partnership',
            'launch' => 'Product Launch',
            'achievement' => 'Achievement/Award',
            'other' => 'Other Update',
            default => 'Update',
        };
    }

    /**
     * Get status color for badges
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'submitted' => 'yellow',
            'reviewed' => 'blue',
            'acknowledged' => 'green',
            default => 'gray',
        };
    }
}
