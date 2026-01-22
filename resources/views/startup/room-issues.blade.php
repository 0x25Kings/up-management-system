@extends('startup.layout')

@section('title', 'Room Issues')

@push('styles')
<style>
    .issues-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
    }

    .issue-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: all 0.3s;
    }

    .issue-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .issue-header {
        padding: 16px 20px;
        border-bottom: 1px solid #F3F4F6;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .issue-type {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .issue-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .issue-icon.electrical { background: #FEF3C7; color: #D97706; }
    .issue-icon.plumbing { background: #DBEAFE; color: #2563EB; }
    .issue-icon.aircon { background: #CFFAFE; color: #0891B2; }
    .issue-icon.internet { background: #EDE9FE; color: #7C3AED; }
    .issue-icon.furniture { background: #FEE2E2; color: #DC2626; }
    .issue-icon.cleaning { background: #D1FAE5; color: #059669; }
    .issue-icon.security { background: #F3F4F6; color: #374151; }
    .issue-icon.other { background: #F3F4F6; color: #6B7280; }

    .issue-type-text h4 {
        font-size: 15px;
        font-weight: 600;
        color: #1F2937;
    }

    .issue-type-text span {
        font-size: 12px;
        color: #6B7280;
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
    }

    .status-pending { background: #FEF3C7; color: #D97706; }
    .status-in_progress { background: #DBEAFE; color: #2563EB; }
    .status-resolved { background: #D1FAE5; color: #059669; }
    .status-closed { background: #F3F4F6; color: #6B7280; }

    .issue-body {
        padding: 16px 20px;
    }

    .issue-description {
        font-size: 14px;
        color: #4B5563;
        line-height: 1.6;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .issue-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        font-size: 13px;
        color: #6B7280;
    }

    .issue-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .priority-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
    }

    .priority-low { background: #D1FAE5; color: #059669; }
    .priority-medium { background: #FEF3C7; color: #D97706; }
    .priority-high { background: #FED7AA; color: #C2410C; }
    .priority-urgent { background: #FEE2E2; color: #DC2626; }

    .issue-footer {
        padding: 12px 20px;
        background: #F9FAFB;
        font-size: 12px;
        color: #6B7280;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .tracking-code {
        font-family: monospace;
        background: white;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 60px 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="breadcrumb">
    <a href="{{ route('startup.dashboard') }}">Dashboard</a>
    <span>/</span>
    <span>Room Issues</span>
</div>

<div class="page-header">
    <div>
        <h1>Room Issues</h1>
        <p>View all your reported room issues and their status</p>
    </div>
    <a href="{{ route('startup.report-issue') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Report New Issue
    </a>
</div>

@if($roomIssues->count() > 0)
    <div class="issues-grid">
        @foreach($roomIssues as $issue)
            <div class="issue-card">
                <div class="issue-header">
                    <div class="issue-type">
                        <div class="issue-icon {{ $issue->issue_type }}">
                            @switch($issue->issue_type)
                                @case('electrical')
                                    <i class="fas fa-bolt"></i>
                                    @break
                                @case('plumbing')
                                    <i class="fas fa-faucet"></i>
                                    @break
                                @case('aircon')
                                    <i class="fas fa-snowflake"></i>
                                    @break
                                @case('internet')
                                    <i class="fas fa-wifi"></i>
                                    @break
                                @case('furniture')
                                    <i class="fas fa-couch"></i>
                                    @break
                                @case('cleaning')
                                    <i class="fas fa-broom"></i>
                                    @break
                                @case('security')
                                    <i class="fas fa-lock"></i>
                                    @break
                                @default
                                    <i class="fas fa-tools"></i>
                            @endswitch
                        </div>
                        <div class="issue-type-text">
                            <h4>{{ ucfirst($issue->issue_type) }}</h4>
                            <span>Room {{ $issue->room_number }}</span>
                        </div>
                    </div>
                    <span class="status-badge status-{{ $issue->status }}">
                        {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                    </span>
                </div>
                <div class="issue-body">
                    <p class="issue-description">{{ $issue->description }}</p>
                    <div class="issue-meta">
                        <div class="issue-meta-item">
                            <span class="priority-badge priority-{{ $issue->priority }}">
                                <i class="fas fa-flag"></i>
                                {{ ucfirst($issue->priority) }}
                            </span>
                        </div>
                        @if($issue->resolved_at)
                            <div class="issue-meta-item">
                                <i class="fas fa-check-circle" style="color: #059669;"></i>
                                Resolved {{ $issue->resolved_at->diffForHumans() }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="issue-footer">
                    <span class="tracking-code">{{ $issue->tracking_code }}</span>
                    <span>{{ $issue->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        @endforeach
    </div>

    @if($roomIssues->hasPages())
        <div class="pagination-wrapper">
            {{ $roomIssues->links() }}
        </div>
    @endif
@else
    <div class="empty-state">
        <i class="fas fa-clipboard-check"></i>
        <h3>No room issues reported</h3>
        <p>You haven't reported any room issues yet.</p>
        <a href="{{ route('startup.report-issue') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Report an Issue
        </a>
    </div>
@endif
@endsection
