<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StartupSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'startup_id',
        'tracking_code',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'type',
        'title',
        'document_type',
        'file_path',
        'original_filename',
        'moa_purpose',
        'moa_details',
        'moa_duration',
        'invoice_number',
        'amount',
        'payment_method',
        'payment_date',
        'payment_proof_path',
        'notes',
        'status',
        'admin_notes',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Generate a unique tracking code
     */
    public static function generateTrackingCode(string $type): string
    {
        $prefix = match($type) {
            'document' => 'DOC',
            'moa' => 'MOA',
            'finance' => 'FIN',
            default => 'SUB',
        };

        $year = date('Y');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));

        return "{$prefix}-{$year}-{$random}";
    }

    /**
     * Get the user who reviewed this submission
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the startup this submission belongs to
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
            'under_review' => 'blue',
            'approved' => 'green',
            'rejected' => 'red',
            'completed' => 'green',
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
            'under_review' => 'Under Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'completed' => 'Completed',
            default => 'Unknown',
        };
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'document' => 'Document Upload',
            'moa' => 'MOA Request',
            'finance' => 'Payment Proof',
            default => 'Submission',
        };
    }
}
