@extends('startup.layout')

@section('title', 'My Submissions')
@section('page-title', 'My Submissions')

@push('styles')
<style>
    /* Stats Summary */
    .stats-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-mini-card {
        background: white;
        padding: 20px;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #E5E7EB;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.3s;
    }

    .stat-mini-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .stat-mini-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-mini-content {
        flex: 1;
    }

    .stat-mini-value {
        font-size: 24px;
        font-weight: 700;
        color: #1F2937;
    }

    .stat-mini-label {
        font-size: 12px;
        color: #6B7280;
        margin-top: 2px;
    }

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        background: white;
        border-radius: 14px;
        padding: 6px;
        margin-bottom: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #E5E7EB;
        overflow-x: auto;
    }

    .filter-tab {
        flex: 1;
        min-width: 120px;
        padding: 14px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        background: transparent;
        color: #6B7280;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        position: relative;
    }

    .filter-tab:hover {
        background: #F3F4F6;
        color: #374151;
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #7B1D3A, #5a1428);
        color: white;
        box-shadow: 0 4px 15px rgba(123, 29, 58, 0.3);
    }

    .filter-tab .tab-icon {
        font-size: 16px;
    }

    .filter-tab .tab-count {
        font-size: 11px;
        background: rgba(0,0,0,0.1);
        padding: 2px 8px;
        border-radius: 10px;
    }

    .filter-tab.active .tab-count {
        background: rgba(255,255,255,0.2);
    }

    .submissions-table {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        background: #F9FAFB;
        padding: 14px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table td {
        padding: 16px 20px;
        border-top: 1px solid #F3F4F6;
        font-size: 14px;
        color: #374151;
    }

    .table tr {
        transition: all 0.3s;
    }

    .table tr:hover td {
        background: #F9FAFB;
    }

    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    .type-document { background: linear-gradient(135deg, #EDE9FE, #DDD6FE); color: #6D28D9; }
    .type-moa { background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #B45309; }
    .type-finance { background: linear-gradient(135deg, #D1FAE5, #A7F3D0); color: #047857; }

    .type-badge i {
        font-size: 14px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-pending { background: #FEF3C7; color: #D97706; }
    .status-under_review { background: #DBEAFE; color: #2563EB; }
    .status-approved { background: #D1FAE5; color: #059669; }
    .status-rejected { background: #FEE2E2; color: #DC2626; }

    .status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .tracking-code {
        font-family: 'Courier New', monospace;
        font-size: 13px;
        background: #F3F4F6;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        color: #374151;
        border: 1px solid #E5E7EB;
    }

    .submission-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .submission-title {
        font-weight: 600;
        color: #1F2937;
    }

    .submission-subtitle {
        font-size: 12px;
        color: #9CA3AF;
    }

    .page-header {
        margin-bottom: 24px;
    }

    .page-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .page-header p {
        font-size: 14px;
        color: #6B7280;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .empty-state i {
        font-size: 48px;
        color: #D1D5DB;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 18px;
        color: #374151;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #6B7280;
        margin-bottom: 20px;
    }

    .empty-state .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid #F3F4F6;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .stats-summary {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .filter-tab {
            min-width: auto;
            padding: 10px 14px;
            font-size: 12px;
        }

        .table th, .table td {
            padding: 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>My Submissions</h1>
        <p>View all your submitted documents, MOA requests, and payments</p>
    </div>
</div>

@php
    $totalAll = $startup->submissions()->count();
    $totalDocs = $startup->submissions()->where('type', 'document')->count();
    $totalMoa = $startup->submissions()->where('type', 'moa')->count();
    $totalPayments = $startup->submissions()->where('type', 'finance')->count();
    $pendingCount = $startup->submissions()->where('status', 'pending')->count();
@endphp

<!-- Stats Summary -->
<div class="stats-summary">
    <div class="stat-mini-card">
        <div class="stat-mini-icon" style="background: linear-gradient(135deg, #EDE9FE, #DDD6FE); color: #7C3AED;">
            <i class="fas fa-folder-open"></i>
        </div>
        <div class="stat-mini-content">
            <div class="stat-mini-value">{{ $totalAll }}</div>
            <div class="stat-mini-label">Total Submissions</div>
        </div>
    </div>
    <div class="stat-mini-card">
        <div class="stat-mini-icon" style="background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #D97706;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-mini-content">
            <div class="stat-mini-value">{{ $pendingCount }}</div>
            <div class="stat-mini-label">Pending Review</div>
        </div>
    </div>
    <div class="stat-mini-card">
        <div class="stat-mini-icon" style="background: linear-gradient(135deg, #D1FAE5, #A7F3D0); color: #059669;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-mini-content">
            <div class="stat-mini-value">{{ $startup->submissions()->where('status', 'approved')->count() }}</div>
            <div class="stat-mini-label">Approved</div>
        </div>
    </div>
    <div class="stat-mini-card">
        <div class="stat-mini-icon" style="background: linear-gradient(135deg, #DBEAFE, #BFDBFE); color: #2563EB;">
            <i class="fas fa-sync"></i>
        </div>
        <div class="stat-mini-content">
            <div class="stat-mini-value">{{ $startup->submissions()->where('status', 'under_review')->count() }}</div>
            <div class="stat-mini-label">Under Review</div>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <a href="{{ route('startup.submissions') }}" class="filter-tab {{ !$type ? 'active' : '' }}">
        <i class="fas fa-th-list tab-icon"></i>
        All
        <span class="tab-count">{{ $totalAll }}</span>
    </a>
    <a href="{{ route('startup.submissions', ['type' => 'document']) }}" class="filter-tab {{ $type === 'document' ? 'active' : '' }}">
        <i class="fas fa-file-alt tab-icon"></i>
        Documents
        <span class="tab-count">{{ $totalDocs }}</span>
    </a>
    <a href="{{ route('startup.submissions', ['type' => 'moa']) }}" class="filter-tab {{ $type === 'moa' ? 'active' : '' }}">
        <i class="fas fa-file-contract tab-icon"></i>
        MOA
        <span class="tab-count">{{ $totalMoa }}</span>
    </a>
    <a href="{{ route('startup.submissions', ['type' => 'finance']) }}" class="filter-tab {{ $type === 'finance' ? 'active' : '' }}">
        <i class="fas fa-credit-card tab-icon"></i>
        Payments
        <span class="tab-count">{{ $totalPayments }}</span>
    </a>
</div>

<div class="submissions-table">
    @if($submissions->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Tracking Code</th>
                    <th>Type</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Submitted</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submissions as $submission)
                    <tr>
                        <td>
                            <span class="tracking-code">{{ $submission->tracking_code }}</span>
                        </td>
                        <td>
                            @if($submission->type === 'document')
                                <span class="type-badge type-document">
                                    <i class="fas fa-file-alt"></i> Document
                                </span>
                            @elseif($submission->type === 'moa')
                                <span class="type-badge type-moa">
                                    <i class="fas fa-file-contract"></i> MOA
                                </span>
                            @else
                                <span class="type-badge type-finance">
                                    <i class="fas fa-credit-card"></i> Payment
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($submission->type === 'document')
                                {{ $submission->document_type }}
                            @elseif($submission->type === 'moa')
                                {{ $submission->moa_purpose }}
                            @else
                                {{ $submission->invoice_number }} - ₱{{ number_format($submission->amount, 2) }}
                            @endif
                        </td>
                        <td>
                            <span class="status-badge status-{{ $submission->status }}">
                                {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                            </span>
                        </td>
                        <td>
                            <div>{{ $submission->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 12px; color: #9CA3AF;">{{ $submission->created_at->format('h:i A') }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($submissions->hasPages())
            <div class="pagination-wrapper">
                {{ $submissions->appends(['type' => $type])->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No submissions yet</h3>
            <p>You haven't submitted any {{ $type ? str_replace('_', ' ', $type) . 's' : 'items' }} yet.</p>
        </div>
    @endif
</div>
@endsection
