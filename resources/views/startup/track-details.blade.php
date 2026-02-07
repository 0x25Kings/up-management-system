@extends('startup.layout')

@section('title', 'Tracking Details')
@section('page-title', $item->tracking_code)

@push('styles')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #6B7280;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 20px;
        transition: all 0.2s;
    }

    .back-link:hover {
        color: #7B1D3A;
    }

    .detail-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #E5E7EB;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .detail-header {
        padding: 28px 32px;
        background: linear-gradient(135deg, #7B1D3A 0%, #A62450 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .detail-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 40%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 191, 0, 0.15) 0%, transparent 70%);
    }

    .detail-header-content {
        position: relative;
        z-index: 1;
    }

    .detail-code {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 14px;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .detail-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .detail-type {
        font-size: 14px;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-status-badge {
        position: absolute;
        top: 28px;
        right: 32px;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-status-badge.pending {
        background: #FEF3C7;
        color: #92400E;
    }

    .detail-status-badge.in_progress,
    .detail-status-badge.under_review {
        background: #DBEAFE;
        color: #1E40AF;
    }

    .detail-status-badge.approved,
    .detail-status-badge.resolved {
        background: #D1FAE5;
        color: #065F46;
    }

    .detail-status-badge.rejected,
    .detail-status-badge.cancelled {
        background: #FEE2E2;
        color: #991B1B;
    }

    .detail-body {
        padding: 32px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 32px;
    }

    .info-item {
        padding: 16px;
        background: #F9FAFB;
        border-radius: 12px;
    }

    .info-item label {
        display: block;
        font-size: 12px;
        color: #6B7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .info-item .value {
        font-size: 15px;
        color: #1F2937;
        font-weight: 500;
    }

    .description-section h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .description-section h3 i {
        color: #7B1D3A;
    }

    .description-text {
        background: #F9FAFB;
        padding: 20px;
        border-radius: 12px;
        font-size: 14px;
        color: #374151;
        line-height: 1.7;
    }

    /* Timeline Section */
    .timeline-section {
        background: white;
        border-radius: 20px;
        border: 1px solid #E5E7EB;
        padding: 28px 32px;
    }

    .timeline-section h3 {
        font-size: 17px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .timeline-section h3 i {
        color: #7B1D3A;
    }

    .progress-timeline {
        position: relative;
        padding-left: 40px;
    }

    .progress-timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #E5E7EB;
    }

    .timeline-step {
        position: relative;
        padding-bottom: 32px;
    }

    .timeline-step:last-child {
        padding-bottom: 0;
    }

    .timeline-step-icon {
        position: absolute;
        left: -40px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        z-index: 1;
    }

    .timeline-step-icon.completed {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
    }

    .timeline-step-icon.current {
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        color: white;
        animation: pulse 2s infinite;
    }

    .timeline-step-icon.pending {
        background: #F3F4F6;
        color: #9CA3AF;
        border: 2px solid #E5E7EB;
    }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5); }
        50% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
    }

    .timeline-step-content h4 {
        font-size: 15px;
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .timeline-step-content p {
        font-size: 13px;
        color: #6B7280;
    }

    .timeline-step-content .timestamp {
        font-size: 12px;
        color: #9CA3AF;
        margin-top: 6px;
    }

    /* Admin Response */
    .admin-response {
        margin-top: 32px;
        padding: 24px;
        background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
        border-radius: 16px;
        border-left: 4px solid #4F46E5;
    }

    .admin-response h4 {
        font-size: 14px;
        font-weight: 700;
        color: #4338CA;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .admin-response p {
        font-size: 14px;
        color: #374151;
        line-height: 1.7;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .detail-status-badge {
            position: static;
            margin-top: 16px;
            display: inline-block;
        }
    }
</style>
@endpush

@section('content')
<a href="{{ route('startup.track') }}" class="back-link">
    <i class="fas fa-arrow-left"></i>
    Back to Track Submissions
</a>

<div class="detail-card">
    <div class="detail-header">
        <div class="detail-header-content">
            <span class="detail-code">{{ $item->tracking_code }}</span>
            <h1 class="detail-title">
                @if($itemType === 'room_issue')
                    {{ $item->issue_type_label }} - Room {{ $item->room_number }}
                @elseif($item->type === 'document')
                    {{ $item->document_type }}
                @elseif($item->type === 'moa')
                    MOA Request
                @else
                    Payment Submission
                @endif
            </h1>
            <div class="detail-type">
                <i class="fas {{ 
                    $itemType === 'room_issue' ? 'fa-tools' : 
                    ($item->type === 'document' ? 'fa-file-alt' : 
                    ($item->type === 'moa' ? 'fa-file-signature' : 'fa-credit-card'))
                }}"></i>
                {{ $itemType === 'room_issue' ? 'Room Issue Report' : ucfirst($item->type) . ' Submission' }}
            </div>
        </div>
        <span class="detail-status-badge {{ $item->status }}">{{ $item->status_label }}</span>
    </div>

    <div class="detail-body">
        <div class="info-grid">
            <div class="info-item">
                <label>Submitted On</label>
                <div class="value">{{ $item->created_at->format('F d, Y') }} at {{ $item->created_at->format('h:i A') }}</div>
            </div>
            <div class="info-item">
                <label>Last Updated</label>
                <div class="value">{{ $item->updated_at->diffForHumans() }}</div>
            </div>
            @if($itemType === 'room_issue')
                <div class="info-item">
                    <label>Room Number</label>
                    <div class="value">{{ $item->room_number }}</div>
                </div>
                <div class="info-item">
                    <label>Priority</label>
                    <div class="value" style="text-transform: capitalize;">{{ $item->priority }}</div>
                </div>
            @elseif($item->type === 'finance')
                <div class="info-item">
                    <label>Amount</label>
                    <div class="value">₱{{ number_format($item->amount, 2) }}</div>
                </div>
                @if($item->invoice_number)
                <div class="info-item">
                    <label>Invoice Number</label>
                    <div class="value">{{ $item->invoice_number }}</div>
                </div>
                @endif
            @elseif($item->type === 'moa')
                <div class="info-item">
                    <label>Purpose</label>
                    <div class="value">{{ $item->moa_purpose ?? 'N/A' }}</div>
                </div>
            @endif
        </div>

        <div class="description-section">
            <h3><i class="fas fa-align-left"></i> Description</h3>
            <div class="description-text">
                {{ $itemType === 'room_issue' ? $item->description : ($item->notes ?? $item->moa_purpose ?? 'No description provided.') }}
            </div>
        </div>

        @if($item->admin_notes)
            <div class="admin-response">
                <h4><i class="fas fa-reply"></i> Admin Response</h4>
                <p>{{ $item->admin_notes }}</p>
            </div>
        @endif
    </div>
</div>

<!-- Progress Timeline -->
<div class="timeline-section">
    <h3><i class="fas fa-stream"></i> Status Timeline</h3>
    
    <div class="progress-timeline">
        <div class="timeline-step">
            <div class="timeline-step-icon completed">
                <i class="fas fa-check"></i>
            </div>
            <div class="timeline-step-content">
                <h4>Submitted</h4>
                <p>Your {{ $itemType === 'room_issue' ? 'room issue report' : 'submission' }} has been received.</p>
                <div class="timestamp">{{ $item->created_at->format('M d, Y h:i A') }}</div>
            </div>
        </div>

        @php
            $isInProgress = in_array($item->status, ['in_progress', 'under_review']);
            $isCompleted = in_array($item->status, ['approved', 'resolved', 'rejected', 'cancelled']);
        @endphp

        <div class="timeline-step">
            <div class="timeline-step-icon {{ $isInProgress ? 'current' : ($isCompleted ? 'completed' : 'pending') }}">
                @if($isCompleted)
                    <i class="fas fa-check"></i>
                @elseif($isInProgress)
                    <i class="fas fa-spinner fa-spin"></i>
                @else
                    <i class="fas fa-clock"></i>
                @endif
            </div>
            <div class="timeline-step-content">
                <h4>Under Review</h4>
                <p>Admin is reviewing your {{ $itemType === 'room_issue' ? 'issue report' : 'submission' }}.</p>
                @if($isInProgress || $isCompleted)
                    <div class="timestamp">In progress</div>
                @endif
            </div>
        </div>

        <div class="timeline-step">
            <div class="timeline-step-icon {{ $isCompleted ? 'completed' : 'pending' }}">
                @if($isCompleted)
                    <i class="fas {{ in_array($item->status, ['approved', 'resolved']) ? 'fa-check' : 'fa-times' }}"></i>
                @else
                    <i class="fas fa-flag-checkered"></i>
                @endif
            </div>
            <div class="timeline-step-content">
                <h4>{{ in_array($item->status, ['rejected', 'cancelled']) ? 'Rejected' : ($itemType === 'room_issue' ? 'Resolved' : 'Approved') }}</h4>
                <p>
                    @if(in_array($item->status, ['approved', 'resolved']))
                        Your {{ $itemType === 'room_issue' ? 'issue has been resolved' : 'submission has been approved' }}.
                    @elseif(in_array($item->status, ['rejected', 'cancelled']))
                        Your {{ $itemType === 'room_issue' ? 'issue was cancelled' : 'submission was rejected' }}.
                    @else
                        Awaiting admin decision.
                    @endif
                </p>
                @if($item->reviewed_at ?? $item->resolved_at)
                    <div class="timestamp">{{ ($item->reviewed_at ?? $item->resolved_at)->format('M d, Y h:i A') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
