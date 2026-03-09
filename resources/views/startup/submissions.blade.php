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
        vertical-align: top;
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
                    <th style="text-align: center;">Actions</th>
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
                            <div class="submission-details">
                                @if($submission->type === 'document')
                                    <span class="submission-title">{{ $submission->document_type }}</span>
                                    @if($submission->original_filename)
                                        <span class="submission-subtitle"><i class="fas fa-paperclip" style="margin-right: 3px;"></i>{{ $submission->original_filename }}</span>
                                    @endif
                                @elseif($submission->type === 'moa')
                                    <span class="submission-title">{{ $submission->moa_purpose === 'document_submission' ? 'MOA Document Submission' : ucfirst(str_replace('_', ' ', $submission->moa_purpose ?? 'MOA')) }}</span>
                                    @if($submission->moa_duration)
                                        <span class="submission-subtitle"><i class="fas fa-clock" style="margin-right: 3px;"></i>{{ str_replace('_', ' ', $submission->moa_duration) }}</span>
                                    @endif
                                    {{-- Payment Period --}}
                                    @if($submission->payment_start_date && $submission->payment_end_date)
                                        <div style="margin-top: 6px; display: inline-flex; align-items: center; gap: 5px; background: #EFF6FF; color: #1E40AF; padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 600;">
                                            <i class="fas fa-calendar-alt"></i>
                                            Payment: {{ $submission->payment_start_date->format('M d, Y') }} — {{ $submission->payment_end_date->format('M d, Y') }}
                                            @if($submission->payment_end_date->isPast())
                                                <span style="background: #FEE2E2; color: #991B1B; padding: 1px 6px; border-radius: 4px; margin-left: 2px; font-size: 10px;">Overdue</span>
                                            @endif
                                        </div>
                                    @endif
                                    {{-- Rejection Remarks --}}
                                    @if($submission->status === 'rejected' && $submission->rejection_remarks)
                                        <div style="margin-top: 6px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 6px; padding: 6px 10px; font-size: 12px;">
                                            <span style="color: #991B1B; font-weight: 700;"><i class="fas fa-comment-alt" style="margin-right: 3px;"></i>Reason:</span>
                                            <span style="color: #7F1D1D;">{{ $submission->rejection_remarks }}</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="submission-title">{{ $submission->invoice_number ?? $submission->title ?? 'Payment' }}</span>
                                    <span class="submission-subtitle" style="font-size: 15px; font-weight: 700; color: #1F2937;">₱{{ number_format($submission->amount ?? 0, 2) }}</span>
                                    @if($submission->payment_method)
                                        <span class="submission-subtitle"><i class="fas fa-wallet" style="margin-right: 3px;"></i>{{ ucfirst($submission->payment_method) }}</span>
                                    @endif
                                    @if($submission->notes)
                                        <span class="submission-subtitle" style="max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $submission->notes }}</span>
                                    @endif
                                @endif
                            </div>
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
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                {{-- View uploaded file --}}
                                @if($submission->file_path)
                                    @if($submission->type === 'finance')
                                        <button onclick="viewProof('{{ \Storage::disk(config('filesystems.upload_disk'))->url($submission->file_path) }}', '{{ $submission->title ?? 'Payment Proof' }}')" style="width: 34px; height: 34px; border: 1px solid #E5E7EB; border-radius: 8px; background: white; display: inline-flex; align-items: center; justify-content: center; color: #7B1D3A; cursor: pointer; transition: all 0.3s; font-size: 14px;" title="View Proof">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <a href="{{ \Storage::disk(config('filesystems.upload_disk'))->url($submission->file_path) }}" target="_blank" style="width: 34px; height: 34px; border: 1px solid #E5E7EB; border-radius: 8px; background: white; display: inline-flex; align-items: center; justify-content: center; color: #7B1D3A; text-decoration: none; transition: all 0.3s; font-size: 14px;" title="View Submission">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                @endif

                                {{-- Download admin-uploaded MOA document --}}
                                @if($submission->type === 'moa' && $submission->admin_moa_document_path)
                                    <a href="{{ route('startup.download-moa-document', $submission->id) }}" style="display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; border: none; border-radius: 8px; background: linear-gradient(135deg, #16A34A, #059669); color: white; text-decoration: none; transition: all 0.3s; font-size: 12px; font-weight: 600;" title="Download MOA">
                                        <i class="fas fa-download"></i> MOA
                                    </a>
                                @elseif($submission->type === 'moa' && $submission->status === 'approved' && !$submission->admin_moa_document_path)
                                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 8px; background: #FEF3C7; color: #92400E; border-radius: 6px; font-size: 10px; font-weight: 500;">
                                        <i class="fas fa-clock"></i> Awaiting upload
                                    </span>
                                @endif
                            </div>
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

<!-- Proof Viewer Modal -->
<div id="proofModal" onclick="closeProofModal(event)" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: white; border-radius: 16px; max-width: 800px; width: 90%; max-height: 90vh; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.3); position: relative;" onclick="event.stopPropagation();">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid #E5E7EB;">
            <h3 id="proofModalTitle" style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">Payment Proof</h3>
            <div style="display: flex; gap: 8px; align-items: center;">
                <a id="proofDownloadBtn" href="#" target="_blank" style="width: 36px; height: 36px; border: 1px solid #E5E7EB; border-radius: 8px; background: white; display: inline-flex; align-items: center; justify-content: center; color: #7B1D3A; text-decoration: none; transition: all 0.3s;" title="Open in new tab">
                    <i class="fas fa-external-link-alt"></i>
                </a>
                <button onclick="closeProofModal()" style="width: 36px; height: 36px; border: 1px solid #E5E7EB; border-radius: 8px; background: white; display: inline-flex; align-items: center; justify-content: center; color: #6B7280; cursor: pointer; transition: all 0.3s;" title="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div id="proofModalBody" style="padding: 24px; display: flex; align-items: center; justify-content: center; max-height: calc(90vh - 80px); overflow: auto; background: #F9FAFB;">
        </div>
    </div>
</div>

<script>
    function viewProof(url, title) {
        const modal = document.getElementById('proofModal');
        const body = document.getElementById('proofModalBody');
        const modalTitle = document.getElementById('proofModalTitle');
        const downloadBtn = document.getElementById('proofDownloadBtn');

        modalTitle.textContent = title;
        downloadBtn.href = url;

        const ext = url.split('.').pop().toLowerCase().split('?')[0];

        if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'].includes(ext)) {
            body.innerHTML = '<img src="' + url + '" style="max-width: 100%; max-height: 70vh; border-radius: 8px; object-fit: contain;" alt="Proof">';
        } else if (ext === 'pdf') {
            body.innerHTML = '<iframe src="' + url + '" style="width: 100%; height: 70vh; border: none; border-radius: 8px;"></iframe>';
        } else {
            body.innerHTML = '<div style="text-align: center; padding: 40px;">' +
                '<i class="fas fa-file" style="font-size: 48px; color: #9CA3AF; margin-bottom: 16px;"></i>' +
                '<p style="color: #6B7280; margin-bottom: 16px;">This file cannot be previewed directly.</p>' +
                '<a href="' + url + '" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #7B1D3A, #A62450); color: white; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 13px;">' +
                '<i class="fas fa-download"></i> Download File</a></div>';
        }

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeProofModal(event) {
        if (event && event.target !== document.getElementById('proofModal')) return;
        document.getElementById('proofModal').style.display = 'none';
        document.getElementById('proofModalBody').innerHTML = '';
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeProofModal();
    });
</script>
@endsection
