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
}
