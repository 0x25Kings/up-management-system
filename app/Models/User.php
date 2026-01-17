<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Role constants
     */
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_TEAM_LEADER = 'team_leader';
    const ROLE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
        'school_id',
        'reference_code',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Generate a unique reference code for team leaders
     */
    public static function generateReferenceCode(): string
    {
        $year = date('Y');
        $prefix = 'TL';
        
        // Get the last reference code for this year
        $lastCode = static::where('reference_code', 'like', "{$prefix}-{$year}-%")
            ->orderBy('reference_code', 'desc')
            ->value('reference_code');
        
        if ($lastCode) {
            $lastNumber = (int) substr($lastCode, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return sprintf('%s-%s-%04d', $prefix, $year, $newNumber);
    }

    /**
     * Check if user account is active
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Check if user is an admin (Super Admin)
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true || $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user is a Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN || $this->is_admin === true;
    }

    /**
     * Check if user is a Team Leader
     */
    public function isTeamLeader(): bool
    {
        return $this->role === self::ROLE_TEAM_LEADER;
    }

    /**
     * Get the school assigned to this team leader
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the interns managed by this team leader (from their school)
     */
    public function managedInterns()
    {
        if (!$this->isTeamLeader() || !$this->school_id) {
            return Intern::query()->whereRaw('1 = 0'); // Return empty query
        }
        return Intern::where('school_id', $this->school_id);
    }

    /**
     * Get reports submitted by this team leader
     */
    public function teamLeaderReports()
    {
        return $this->hasMany(TeamLeaderReport::class, 'team_leader_id');
    }

    /**
     * Get reports reviewed by this admin
     */
    public function reviewedReports()
    {
        return $this->hasMany(TeamLeaderReport::class, 'reviewed_by');
    }

    /**
     * Check if team leader can manage a specific intern
     */
    public function canManageIntern(Intern $intern): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->isTeamLeader() && $this->school_id === $intern->school_id) {
            return true;
        }

        return false;
    }

    /**
     * Get all team leaders
     */
    public static function teamLeaders()
    {
        return static::where('role', self::ROLE_TEAM_LEADER);
    }

    /**
     * Get user's module permissions
     */
    public function permissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    /**
     * Check if user has permission to view a module
     */
    public function canViewModule(string $module): bool
    {
        // Super Admin has access to everything
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->permissions()
            ->where('module', $module)
            ->where('can_view', true)
            ->exists();
    }

    /**
     * Check if user has permission to edit a module
     */
    public function canEditModule(string $module): bool
    {
        // Super Admin has access to everything
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->permissions()
            ->where('module', $module)
            ->where('can_edit', true)
            ->exists();
    }

    /**
     * Get all modules this user can view
     */
    public function getViewableModules(): array
    {
        if ($this->isSuperAdmin()) {
            return array_keys(UserPermission::MODULES);
        }

        return $this->permissions()
            ->where('can_view', true)
            ->pluck('module')
            ->toArray();
    }

    /**
     * Get all modules this user can edit
     */
    public function getEditableModules(): array
    {
        if ($this->isSuperAdmin()) {
            return array_keys(UserPermission::MODULES);
        }

        return $this->permissions()
            ->where('can_edit', true)
            ->pluck('module')
            ->toArray();
    }

    /**
     * Grant permission to a module
     */
    public function grantModulePermission(string $module, bool $canView = true, bool $canEdit = false, ?int $grantedBy = null): UserPermission
    {
        return $this->permissions()->updateOrCreate(
            ['module' => $module],
            [
                'can_view' => $canView,
                'can_edit' => $canEdit,
                'granted_by' => $grantedBy,
                'granted_at' => now(),
            ]
        );
    }

    /**
     * Revoke permission from a module
     */
    public function revokeModulePermission(string $module): bool
    {
        return $this->permissions()->where('module', $module)->delete() > 0;
    }

    /**
     * Sync all module permissions
     */
    public function syncModulePermissions(array $permissions, ?int $grantedBy = null): void
    {
        // Remove all existing permissions
        $this->permissions()->delete();

        // Add new permissions
        foreach ($permissions as $module => $access) {
            if (!empty($access['can_view']) || !empty($access['can_edit'])) {
                $this->grantModulePermission(
                    $module,
                    !empty($access['can_view']),
                    !empty($access['can_edit']),
                    $grantedBy
                );
            }
        }
    }

    /**
     * Get permissions as array for forms
     */
    public function getPermissionsArray(): array
    {
        $result = [];
        foreach (UserPermission::MODULES as $module => $info) {
            $permission = $this->permissions()->where('module', $module)->first();
            $result[$module] = [
                'can_view' => $permission ? $permission->can_view : false,
                'can_edit' => $permission ? $permission->can_edit : false,
            ];
        }
        return $result;
    }
}
