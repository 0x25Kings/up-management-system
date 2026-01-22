@extends('startup.layout')

@section('title', 'My Submissions')

@push('styles')
<style>
    .filter-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
        border: 2px solid #E5E7EB;
        background: white;
        color: #6B7280;
    }

    .filter-btn:hover {
        border-color: var(--maroon);
        color: var(--maroon);
    }

    .filter-btn.active {
        background: var(--maroon);
        border-color: var(--maroon);
        color: white;
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

    .table tr:hover td {
        background: #F9FAFB;
    }

    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
    }

    .type-document { background: #EDE9FE; color: #7C3AED; }
    .type-moa { background: #FEF3C7; color: #D97706; }
    .type-finance { background: #D1FAE5; color: #059669; }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
    }

    .status-pending { background: #FEF3C7; color: #D97706; }
    .status-under_review { background: #DBEAFE; color: #2563EB; }
    .status-approved { background: #D1FAE5; color: #059669; }
    .status-rejected { background: #FEE2E2; color: #DC2626; }

    .tracking-code {
        font-family: monospace;
        font-size: 12px;
        background: #F3F4F6;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
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

    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid #F3F4F6;
        display: flex;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="breadcrumb">
    <a href="{{ route('startup.dashboard') }}">Dashboard</a>
    <span>/</span>
    <span>My Submissions</span>
</div>

<div class="page-header">
    <div>
        <h1>My Submissions</h1>
        <p>View all your submitted documents, MOA requests, and payments</p>
    </div>
</div>

<div class="filter-bar">
    <a href="{{ route('startup.submissions') }}" class="filter-btn {{ !$type ? 'active' : '' }}">
        <i class="fas fa-list"></i> All
    </a>
    <a href="{{ route('startup.submissions', ['type' => 'document']) }}" class="filter-btn {{ $type === 'document' ? 'active' : '' }}">
        <i class="fas fa-file-alt"></i> Documents
    </a>
    <a href="{{ route('startup.submissions', ['type' => 'moa']) }}" class="filter-btn {{ $type === 'moa' ? 'active' : '' }}">
        <i class="fas fa-file-contract"></i> MOA Requests
    </a>
    <a href="{{ route('startup.submissions', ['type' => 'finance']) }}" class="filter-btn {{ $type === 'finance' ? 'active' : '' }}">
        <i class="fas fa-credit-card"></i> Payments
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
            <a href="{{ route('startup.dashboard') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Make a Submission
            </a>
        </div>
    @endif
</div>
@endsection
