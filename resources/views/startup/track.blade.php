@extends('startup.layout')

@section('title', 'Track Submissions')
@section('page-title', 'Track Submissions')

@push('styles')
<style>
    .stats-row {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-mini {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        border: 1px solid #E5E7EB;
        transition: all 0.3s ease;
    }

    .stat-mini:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-mini .stat-number {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .stat-mini .stat-label {
        font-size: 12px;
        color: #6B7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-mini.total .stat-number { color: #7B1D3A; }
    .stat-mini.pending .stat-number { color: #F59E0B; }
    .stat-mini.progress .stat-number { color: #3B82F6; }
    .stat-mini.approved .stat-number { color: #10B981; }
    .stat-mini.rejected .stat-number { color: #EF4444; }

    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        border: 1px solid #E5E7EB;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .filter-select {
        padding: 10px 14px;
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        font-size: 14px;
        color: #374151;
        background: white;
        min-width: 140px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-select:focus {
        outline: none;
        border-color: #7B1D3A;
        box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1);
    }

    .search-box {
        flex: 1;
        min-width: 200px;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #F9FAFB;
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        padding: 0 14px;
        transition: all 0.2s;
    }

    .search-box:focus-within {
        background: white;
        border-color: #7B1D3A;
        box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1);
    }

    .search-box i {
        color: #9CA3AF;
    }

    .search-box input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 10px 0;
        font-size: 14px;
        outline: none;
    }

    .search-btn {
        background: #7B1D3A;
        color: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .search-btn:hover {
        background: #5a1428;
        transform: scale(1.05);
    }

    .clear-filters-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        background: #FEE2E2;
        color: #DC2626;
        border: 1px solid #FECACA;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .clear-filters-btn:hover {
        background: #DC2626;
        color: white;
    }

    .timeline-container {
        background: white;
        border-radius: 16px;
        border: 1px solid #E5E7EB;
        overflow: hidden;
    }

    .timeline-header {
        padding: 20px 24px;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .timeline-header h2 {
        font-size: 17px;
        font-weight: 700;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .timeline-header h2 i {
        color: #7B1D3A;
    }

    .timeline-list {
        overflow: hidden;
    }

    .timeline-item {
        display: flex;
        gap: 20px;
        padding: 24px;
        border-bottom: 1px solid #F3F4F6;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .timeline-item:hover {
        background: #F9FAFB;
    }

    .timeline-item:last-child {
        border-bottom: none;
    }

    .timeline-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .timeline-icon.document {
        background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
        color: #4F46E5;
    }

    .timeline-icon.moa {
        background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
        color: #059669;
    }

    .timeline-icon.finance {
        background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
        color: #D97706;
    }

    .timeline-icon.room_issue {
        background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
        color: #DC2626;
    }

    .timeline-content {
        flex: 1;
        min-width: 0;
    }

    .timeline-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 8px;
    }

    .timeline-title {
        font-size: 15px;
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 2px;
    }

    .timeline-code {
        font-size: 12px;
        color: #7B1D3A;
        font-family: 'Courier New', monospace;
        font-weight: 600;
        background: rgba(123, 29, 58, 0.08);
        padding: 3px 8px;
        border-radius: 6px;
    }

    .timeline-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .timeline-status.pending {
        background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
        color: #92400E;
    }

    .timeline-status.in_progress,
    .timeline-status.under_review {
        background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);
        color: #1E40AF;
    }

    .timeline-status.approved,
    .timeline-status.resolved {
        background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
        color: #065F46;
    }

    .timeline-status.rejected,
    .timeline-status.cancelled {
        background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
        color: #991B1B;
    }

    .timeline-desc {
        font-size: 13px;
        color: #6B7280;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .timeline-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 12px;
        color: #9CA3AF;
    }

    .timeline-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .timeline-progress {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }

    .timeline-progress.pending { background: #F59E0B; }
    .timeline-progress.in_progress,
    .timeline-progress.under_review { background: #3B82F6; }
    .timeline-progress.approved,
    .timeline-progress.resolved { background: #10B981; }
    .timeline-progress.rejected,
    .timeline-progress.cancelled { background: #EF4444; }

    .empty-state {
        padding: 60px 40px;
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
    }

    .view-link {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #7B1D3A;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        margin-top: 8px;
        opacity: 0;
        transition: all 0.3s;
    }

    .timeline-item:hover .view-link {
        opacity: 1;
    }

    .view-link:hover {
        color: #5a1428;
    }

    /* Pagination Styles */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        border-top: 1px solid #E5E7EB;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pagination-info {
        font-size: 14px;
        color: #6B7280;
        font-weight: 500;
    }

    .pagination-wrapper nav {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .pagination-wrapper nav > div:first-child {
        display: none;
    }

    .pagination-wrapper nav > div:last-child span,
    .pagination-wrapper nav > div:last-child a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 12px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .pagination-wrapper nav > div:last-child a {
        background: #F3F4F6;
        color: #374151;
        border: 1px solid #E5E7EB;
    }

    .pagination-wrapper nav > div:last-child a:hover {
        background: #7B1D3A;
        color: white;
        border-color: #7B1D3A;
    }

    .pagination-wrapper nav > div:last-child span[aria-current="page"] span {
        background: #7B1D3A;
        color: white;
        border: 1px solid #7B1D3A;
    }

    .pagination-wrapper nav > div:last-child span[aria-disabled="true"] {
        background: #F9FAFB;
        color: #D1D5DB;
        border: 1px solid #E5E7EB;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-card {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group {
            width: 100%;
        }

        .filter-select {
            flex: 1;
        }

        .search-box {
            width: 100%;
        }

        .pagination-wrapper nav > div:last-child a,
        .pagination-wrapper nav > div:last-child span {
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            font-size: 13px;
        }
    }
</style>
@endpush

@section('content')
<!-- Stats Row -->
<div class="stats-row">
    <div class="stat-mini total">
        <div class="stat-number">{{ $stats['total'] }}</div>
        <div class="stat-label">Total</div>
    </div>
    <div class="stat-mini pending">
        <div class="stat-number">{{ $stats['pending'] }}</div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat-mini progress">
        <div class="stat-number">{{ $stats['in_progress'] }}</div>
        <div class="stat-label">In Progress</div>
    </div>
    <div class="stat-mini approved">
        <div class="stat-number">{{ $stats['approved'] }}</div>
        <div class="stat-label">Approved</div>
    </div>
    <div class="stat-mini rejected">
        <div class="stat-number">{{ $stats['rejected'] }}</div>
        <div class="stat-label">Rejected</div>
    </div>
</div>

<!-- Filters -->
<form action="{{ route('startup.track') }}" method="GET" class="filter-card">
    <div class="filter-group">
        <label>Type:</label>
        <select name="type" class="filter-select" onchange="this.form.submit()">
            <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types</option>
            <option value="document" {{ $type === 'document' ? 'selected' : '' }}>Documents</option>
            <option value="moa" {{ $type === 'moa' ? 'selected' : '' }}>MOA Requests</option>
            <option value="finance" {{ $type === 'finance' ? 'selected' : '' }}>Payments</option>
            <option value="room_issue" {{ $type === 'room_issue' ? 'selected' : '' }}>Room Issues</option>
        </select>
    </div>
    <div class="filter-group">
        <label>Status:</label>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
    </div>
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" name="search" placeholder="Search by tracking code, type, or description..." value="{{ $search }}">
        <button type="submit" class="search-btn"><i class="fas fa-arrow-right"></i></button>
    </div>
    @if($search || $type !== 'all' || $status !== 'all')
        <a href="{{ route('startup.track') }}" class="clear-filters-btn" title="Clear all filters">
            <i class="fas fa-times"></i> Clear
        </a>
    @endif
</form>

<!-- Timeline -->
<div class="timeline-container">
    <div class="timeline-header">
        <h2><i class="fas fa-stream"></i> Submission Timeline</h2>
        <span style="font-size: 13px; color: #6B7280;">{{ $stats['total'] }} items</span>
    </div>

    <div class="timeline-list">
        @forelse($paginatedItems as $item)
            <div class="timeline-item" onclick="window.location='{{ route('startup.track.details', $item['tracking_code']) }}'">
                <div class="timeline-progress {{ $item['status'] }}"></div>
                <div class="timeline-icon {{ $item['category'] }}">
                    <i class="fas {{ 
                        $item['category'] === 'document' ? 'fa-file-alt' : 
                        ($item['category'] === 'moa' ? 'fa-file-signature' : 
                        ($item['category'] === 'finance' ? 'fa-credit-card' : 'fa-tools'))
                    }}"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-top">
                        <div>
                            <div class="timeline-title">{{ $item['title'] }}</div>
                            <span class="timeline-code">{{ $item['tracking_code'] }}</span>
                        </div>
                        <span class="timeline-status {{ $item['status'] }}">{{ $item['status_label'] }}</span>
                    </div>
                    <div class="timeline-desc">{{ Str::limit($item['description'], 100) }}</div>
                    <div class="timeline-meta">
                        <span><i class="fas fa-calendar"></i> {{ $item['created_at']->format('M d, Y') }}</span>
                        <span><i class="fas fa-clock"></i> {{ $item['created_at']->diffForHumans() }}</span>
                        @if($item['reviewed_at'] ?? $item['resolved_at'] ?? null)
                            <span><i class="fas fa-check-circle"></i> Responded {{ ($item['reviewed_at'] ?? $item['resolved_at'])->diffForHumans() }}</span>
                        @endif
                    </div>
                    <a href="{{ route('startup.track.details', $item['tracking_code']) }}" class="view-link">
                        View Details <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No submissions found</h3>
                <p>{{ $search ? 'No items match your search.' : 'You haven\'t made any submissions yet.' }}</p>
            </div>
        @endforelse
    </div>

    <div class="pagination-wrapper">
        <div class="pagination-info">
            Showing {{ $paginatedItems->firstItem() ?? 0 }}–{{ $paginatedItems->lastItem() ?? 0 }} of {{ $paginatedItems->total() }} items
        </div>
        @if($paginatedItems->hasPages())
            <div class="pagination-links">
                {{ $paginatedItems->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
