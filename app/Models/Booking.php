<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'agency_name',
        'event_name',
        'contact_person',
        'phone',
        'email',
        'booking_date',
        'time_start',
        'time_end',
        'purpose',
        'status',
        'approved_by',
        'approved_at',
        'admin_emailed',
        'attachment_path',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'approved_at' => 'datetime',
        'admin_emailed' => 'boolean',
    ];

    /**
     * Get the admin who approved this booking
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get formatted time range
     */
    public function getFormattedTimeAttribute(): string
    {
        $start = Carbon::parse($this->time_start)->format('g:i A');
        $end = Carbon::parse($this->time_end)->format('g:i A');
        return "{$start} - {$end}";
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->booking_date->format('F d, Y');
    }

    /**
     * Check if booking is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if booking is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Approve the booking
     */
    public function approve(int $userId): void
    {
        $this->status = 'approved';
        $this->approved_by = $userId;
        $this->approved_at = now('Asia/Manila');
        $this->save();
    }

    /**
     * Reject the booking
     */
    public function reject(): void
    {
        $this->status = 'rejected';
        $this->save();
    }
}
