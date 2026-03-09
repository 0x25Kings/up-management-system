<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'startup_id',
        'tracking_code',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'room_number',
        'issue_type',
        'description',
        'photo_path',
        'priority',
        'status',
        'admin_notes',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Generate a unique tracking code
     */
    public static function generateTrackingCode(): string
    {
        $year = date('Y');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));

        return "ROOM-{$year}-{$random}";
    }

    /**
     * Get the user who resolved this issue
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Get the startup this issue belongs to
     */
    public function startup(): BelongsTo
    {
        return $this->belongsTo(Startup::class);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'in_progress' => 'blue',
            'resolved' => 'green',
            'closed' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
            default => 'Unknown',
        };
    }

    /**
     * Get issue type label
     */
    public function getIssueTypeLabelAttribute(): string
    {
        return match($this->issue_type) {
            'electrical' => 'Electrical',
            'plumbing' => 'Plumbing',
            'aircon' => 'AC/Ventilation',
            'internet' => 'Internet/Network',
            'furniture' => 'Furniture',
            'cleaning' => 'Cleaning',
            'security' => 'Security',
            'other' => 'Other',
            default => 'Unknown',
        };
    }

    /**
     * Get priority badge color
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'gray',
            'medium' => 'yellow',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'gray',
        };
    }
}
