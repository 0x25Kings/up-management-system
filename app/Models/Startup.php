<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Startup extends Model
{
    use HasFactory;

    protected $fillable = [
        'startup_code',
        'password',
        'password_set',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'room_number',
        'address',
        'description',
        'status',
        'moa_status',
        'moa_expiry',
        'created_by',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'moa_expiry' => 'date',
        'password_set' => 'boolean',
    ];

    /**
     * Generate a unique startup code
     */
    public static function generateStartupCode(): string
    {
        $year = date('Y');
        $random = strtoupper(Str::random(6));
        $code = "STU-{$year}-{$random}";

        // Ensure uniqueness
        while (self::where('startup_code', $code)->exists()) {
            $random = strtoupper(Str::random(6));
            $code = "STU-{$year}-{$random}";
        }

        return $code;
    }

    /**
     * Generate a random password
     */
    public static function generatePassword(): string
    {
        $words = ['Startup', 'Innovation', 'Growth', 'Success', 'Venture'];
        $word = $words[array_rand($words)];
        $number = rand(100, 999);
        $special = ['!', '@', '#', '$'][array_rand(['!', '@', '#', '$'])];
        
        return $word . $number . $special;
    }

    /**
     * Set the password attribute (auto-hash)
     */
    public function setPasswordAttribute($value)
    {
        // Only hash if not already hashed
        if ($value && !preg_match('/^\$2[ayb]\$.{56}$/', $value)) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /**
     * Check if the given password matches
     */
    public function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Get the admin who created this startup account
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all submissions for this startup
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(StartupSubmission::class);
    }

    /**
     * Get all room issues for this startup
     */
    public function roomIssues(): HasMany
    {
        return $this->hasMany(RoomIssue::class);
    }

    /**
     * Check if startup is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if MOA is active and not expired
     */
    public function hasMoaActive(): bool
    {
        if ($this->moa_status !== 'active') {
            return false;
        }

        if ($this->moa_expiry && $this->moa_expiry->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'green',
            'inactive' => 'gray',
            'suspended' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get MOA status badge color
     */
    public function getMoaStatusColorAttribute(): string
    {
        return match($this->moa_status) {
            'none' => 'gray',
            'pending' => 'yellow',
            'active' => 'green',
            'expired' => 'red',
            default => 'gray',
        };
    }

    /**
     * Scope to get only active startups
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
