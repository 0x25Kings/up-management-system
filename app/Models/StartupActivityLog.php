<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StartupActivityLog extends Model
{
    protected $fillable = [
        'startup_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function startup(): BelongsTo
    {
        return $this->belongsTo(Startup::class);
    }

    /**
     * Log an activity for a startup
     */
    public static function log(int $startupId, string $action, string $description, ?array $metadata = null): self
    {
        return self::create([
            'startup_id' => $startupId,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
        ]);
    }

    /**
     * Get icon for action type
     */
    public function getIconAttribute(): string
    {
        return match($this->action) {
            'login' => 'fa-sign-in-alt',
            'logout' => 'fa-sign-out-alt',
            'profile_update' => 'fa-user-edit',
            'photo_upload' => 'fa-camera',
            'password_change' => 'fa-key',
            'document_submit' => 'fa-file-upload',
            'moa_submit' => 'fa-file-signature',
            'payment_submit' => 'fa-credit-card',
            'issue_submit' => 'fa-exclamation-triangle',
            'progress_submit' => 'fa-chart-line',
            default => 'fa-circle',
        };
    }

    /**
     * Get color for action type
     */
    public function getColorAttribute(): string
    {
        return match($this->action) {
            'login' => '#10B981',
            'logout' => '#6B7280',
            'profile_update', 'photo_upload' => '#3B82F6',
            'password_change' => '#F59E0B',
            'document_submit', 'moa_submit', 'payment_submit' => '#7B1D3A',
            'issue_submit' => '#EF4444',
            'progress_submit' => '#8B5CF6',
            default => '#9CA3AF',
        };
    }
}
