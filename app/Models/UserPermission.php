<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    /**
     * Available modules that can be granted to Team Leaders
     */
    const MODULES = [
        'scheduler' => [
            'name' => 'Scheduler',
            'icon' => 'fas fa-calendar-alt',
            'description' => 'Manage room bookings and calendar events'
        ],
        'research_tracking' => [
            'name' => 'Research Tracking',
            'icon' => 'fas fa-flask',
            'description' => 'View and manage research projects'
        ],
        'incubatee_tracker' => [
            'name' => 'Incubatee Tracker',
            'icon' => 'fas fa-rocket',
            'description' => 'Track startup submissions and progress'
        ],
        'issues_management' => [
            'name' => 'Issues & Complaints',
            'icon' => 'fas fa-exclamation-triangle',
            'description' => 'Handle room issues and complaints'
        ],
        'digital_records' => [
            'name' => 'Digital Records',
            'icon' => 'fas fa-file-alt',
            'description' => 'Access and manage digital documents'
        ],
        'intern_management' => [
            'name' => 'Full Intern Management',
            'icon' => 'fas fa-briefcase',
            'description' => 'Full access to all intern data (not just own school)'
        ],
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'module',
        'can_view',
        'can_edit',
        'granted_by',
        'granted_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'can_view' => 'boolean',
        'can_edit' => 'boolean',
        'granted_at' => 'datetime',
    ];

    /**
     * Get the user this permission belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who granted this permission
     */
    public function grantedByUser()
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    /**
     * Get module info
     */
    public function getModuleInfoAttribute()
    {
        return self::MODULES[$this->module] ?? null;
    }

    /**
     * Get all available modules
     */
    public static function getAvailableModules(): array
    {
        return self::MODULES;
    }

    /**
     * Scope to get permissions for a specific module
     */
    public function scopeForModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope to get viewable permissions
     */
    public function scopeViewable($query)
    {
        return $query->where('can_view', true);
    }

    /**
     * Scope to get editable permissions
     */
    public function scopeEditable($query)
    {
        return $query->where('can_edit', true);
    }
}
