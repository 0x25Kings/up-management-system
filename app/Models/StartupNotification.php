<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StartupNotification extends Model
{
    protected $fillable = [
        'startup_id',
        'type',
        'title',
        'message',
        'icon',
        'color',
        'link',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function startup(): BelongsTo
    {
        return $this->belongsTo(Startup::class);
    }

    /**
     * Create a notification for a startup
     */
    public static function notify(int $startupId, string $type, string $title, string $message, ?string $link = null, string $icon = 'fa-bell', string $color = '#7B1D3A'): self
    {
        return self::create([
            'startup_id' => $startupId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'icon' => $icon,
            'color' => $color,
        ]);
    }

    /**
     * Scope unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Mark as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
