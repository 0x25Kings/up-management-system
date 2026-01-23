@extends('startup.layout')

@section('title', 'Room Issues')

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .page-header p {
        color: #6B7280;
        font-size: 14px;
    }

    .issues-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    .issue-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        overflow: hidden;
        transition: all 0.3s;
        border: 1px solid #E5E7EB;
    }

    .issue-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .issue-header {
        padding: 16px 20px;
        border-bottom: 1px solid #F3F4F6;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }

    .issue-type {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .issue-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
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
        margin-bottom: 2px;
    }

    .issue-type-text span {
        font-size: 13px;
        color: #6B7280;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
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
        gap: 12px;
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
        gap: 5px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }

    .priority-low { background: #D1FAE5; color: #059669; }
    .priority-medium { background: #FEF3C7; color: #D97706; }
    .priority-high { background: #FED7AA; color: #C2410C; }
    .priority-urgent { background: #FEE2E2; color: #DC2626; }

    .issue-footer {
        padding: 14px 20px;
        background: #F9FAFB;
        border-top: 1px solid #F3F4F6;
        font-size: 12px;
        color: #6B7280;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .issue-footer .view-details-btn {
        background: linear-gradient(135deg, #7B1D3A, #5a1428);
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .issue-footer .view-details-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(123, 29, 58, 0.3);
    }

    .tracking-code {
        font-family: 'Courier New', monospace;
        background: white;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        color: #374151;
        border: 1px solid #E5E7EB;
    }

    /* Modal Styles */
    .issue-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        backdrop-filter: blur(4px);
    }

    .issue-modal-overlay.active {
        display: flex;
    }

    .issue-modal {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 560px;
        max-height: 85vh;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
        animation: modalSlide 0.3s ease;
    }

    @keyframes modalSlide {
        from { opacity: 0; transform: translateY(-20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .issue-modal-header {
        background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
        padding: 20px 24px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .issue-modal-header h3 {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .issue-modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 18px;
        transition: background 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .issue-modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .issue-modal-body {
        padding: 24px;
        overflow-y: auto;
        max-height: calc(85vh - 150px);
    }

    .detail-section {
        margin-bottom: 20px;
    }

    .detail-section:last-child {
        margin-bottom: 0;
    }

    .detail-label {
        font-size: 11px;
        font-weight: 700;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .detail-value {
        font-size: 14px;
        color: #1F2937;
        line-height: 1.6;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .status-card {
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .status-card.pending {
        background: linear-gradient(135deg, #FEF3C7, #FDE68A);
        border: 1px solid #F59E0B;
    }

    .status-card.in_progress {
        background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
        border: 1px solid #3B82F6;
    }

    .status-card.resolved {
        background: linear-gradient(135deg, #D1FAE5, #A7F3D0);
        border: 1px solid #10B981;
    }

    .status-card.closed {
        background: linear-gradient(135deg, #F3F4F6, #E5E7EB);
        border: 1px solid #9CA3AF;
    }

    .resolution-notes {
        background: linear-gradient(135deg, #F0FDF4, #DCFCE7);
        border: 1px solid #86EFAC;
        border-radius: 12px;
        padding: 16px;
        margin-top: 16px;
    }

    .resolution-notes .notes-header {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #166534;
        font-weight: 700;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .resolution-notes .notes-content {
        color: #15803D;
        font-size: 14px;
        line-height: 1.7;
    }

    .photo-preview {
        margin-top: 16px;
    }

    .photo-preview img {
        max-width: 100%;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
    }

    .issue-modal-footer {
        padding: 16px 24px;
        background: #F9FAFB;
        border-top: 1px solid #E5E7EB;
        text-align: right;
    }

    .issue-modal-footer button {
        padding: 12px 28px;
        background: linear-gradient(135deg, #7B1D3A, #5a1428);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .issue-modal-footer button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(123, 29, 58, 0.3);
    }

    /* Priority badges in modal */
    .priority-badge.priority-low {
        background: #D1FAE5;
        color: #047857;
    }
    .priority-badge.priority-medium {
        background: #DBEAFE;
        color: #2563EB;
    }
    .priority-badge.priority-high {
        background: #FEF3C7;
        color: #D97706;
    }
    .priority-badge.priority-urgent {
        background: #FEE2E2;
        color: #DC2626;
    }

    .empty-state {
        background: white;
        border-radius: 20px;
        padding: 80px 40px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #E5E7EB;
    }

    .empty-state i {
        font-size: 64px;
        color: #D1D5DB;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 20px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #6B7280;
        margin-bottom: 28px;
        font-size: 15px;
    }

    .pagination-wrapper {
        margin-top: 28px;
        display: flex;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .issues-grid {
            grid-template-columns: 1fr;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .issue-modal {
            max-width: 100%;
            max-height: 90vh;
            border-radius: 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="breadcrumb">
    <a href="{{ route('startup.dashboard') }}">Dashboard</a>
    <span>/</span>
    <span>Room Issues</span>
</div>

<div class="page-header-card">
    <div class="header-content">
        <div class="header-icon">
            <i class="fas fa-tools"></i>
        </div>
        <div>
            <h1>Room Issues</h1>
            <p>View and track all your reported room issues</p>
        </div>
    </div>
</div>

<div class="page-header" style="margin-bottom: 24px;">
    <div></div>
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
                    <button class="view-details-btn" onclick="viewIssueDetails({{ $issue->id }})">
                        <i class="fas fa-eye"></i> View Details
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    @if($roomIssues->hasPages())
        <div class="pagination-wrapper">
            {{ $roomIssues->links() }}
        </div>
    @endif

<!-- Issue Details Modal -->
<div id="issueDetailsModal" class="issue-modal-overlay">
    <div class="issue-modal">
        <div class="issue-modal-header">
            <h3><i class="fas fa-clipboard-list" style="margin-right: 10px;"></i>Issue Details</h3>
            <button class="issue-modal-close" onclick="closeIssueModal()">&times;</button>
        </div>
        <div class="issue-modal-body" id="issueModalContent">
            <!-- Content loaded dynamically -->
        </div>
        <div class="issue-modal-footer">
            <button onclick="closeIssueModal()">Close</button>
        </div>
    </div>
</div>

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

@push('scripts')
<script>
    const issuesData = @json($roomIssues->keyBy('id'));

    function viewIssueDetails(issueId) {
        const issue = issuesData[issueId];
        if (!issue) {
            alert('Issue not found');
            return;
        }

        const statusColors = {
            'pending': { bg: '#FEF3C7', text: '#D97706', label: 'Pending' },
            'in_progress': { bg: '#DBEAFE', text: '#2563EB', label: 'In Progress' },
            'resolved': { bg: '#D1FAE5', text: '#059669', label: 'Resolved' },
            'closed': { bg: '#F3F4F6', text: '#6B7280', label: 'Closed' }
        };

        const priorityLabels = {
            'low': 'Low',
            'medium': 'Medium',
            'high': 'High',
            'urgent': 'Urgent'
        };

        const typeIcons = {
            'electrical': 'fa-bolt',
            'plumbing': 'fa-faucet',
            'aircon': 'fa-snowflake',
            'internet': 'fa-wifi',
            'furniture': 'fa-couch',
            'cleaning': 'fa-broom',
            'security': 'fa-lock',
            'other': 'fa-tools'
        };

        const status = statusColors[issue.status] || statusColors['pending'];
        const createdDate = new Date(issue.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        const resolvedDate = issue.resolved_at ? new Date(issue.resolved_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : null;

        let content = `
            <div class="status-card ${issue.status}">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 12px; opacity: 0.8;">Tracking Code</div>
                        <div style="font-size: 18px; font-weight: 700; font-family: monospace;">${issue.tracking_code}</div>
                    </div>
                    <span class="status-badge status-${issue.status}" style="font-size: 13px; padding: 6px 14px;">
                        ${status.label}
                    </span>
                </div>
            </div>

            <div class="detail-grid">
                <div class="detail-section">
                    <div class="detail-label">Issue Type</div>
                    <div class="detail-value">
                        <i class="fas ${typeIcons[issue.issue_type] || 'fa-tools'}" style="margin-right: 6px; color: #7B1D3A;"></i>
                        ${issue.issue_type.charAt(0).toUpperCase() + issue.issue_type.slice(1)}
                    </div>
                </div>
                <div class="detail-section">
                    <div class="detail-label">Room Number</div>
                    <div class="detail-value"><strong>${issue.room_number}</strong></div>
                </div>
                <div class="detail-section">
                    <div class="detail-label">Priority</div>
                    <div class="detail-value">
                        <span class="priority-badge priority-${issue.priority}">
                            <i class="fas fa-flag"></i> ${priorityLabels[issue.priority] || issue.priority}
                        </span>
                    </div>
                </div>
                <div class="detail-section">
                    <div class="detail-label">Reported On</div>
                    <div class="detail-value">${createdDate}</div>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-label">Description</div>
                <div class="detail-value" style="background: #F9FAFB; padding: 14px 16px; border-radius: 10px; white-space: pre-wrap; border: 1px solid #E5E7EB;">${issue.description}</div>
            </div>
        `;

        // Show photo if exists
        if (issue.photo_path) {
            content += `
                <div class="detail-section photo-preview">
                    <div class="detail-label">Photo Evidence</div>
                    <a href="/storage/${issue.photo_path}" target="_blank">
                        <img src="/storage/${issue.photo_path}" alt="Issue Photo">
                    </a>
                </div>
            `;
        }

        // Show resolution notes if resolved/closed
        if (issue.admin_notes && (issue.status === 'resolved' || issue.status === 'closed' || issue.status === 'in_progress')) {
            content += `
                <div class="resolution-notes">
                    <div class="notes-header">
                        <i class="fas fa-comment-alt"></i>
                        Admin Response / Resolution Notes
                    </div>
                    <div class="notes-content">${issue.admin_notes}</div>
                </div>
            `;
        }

        // Show resolved date if applicable
        if (resolvedDate) {
            content += `
                <div class="detail-section" style="margin-top: 16px;">
                    <div class="detail-label">Resolved On</div>
                    <div class="detail-value" style="color: #059669;">
                        <i class="fas fa-check-circle" style="margin-right: 6px;"></i>
                        ${resolvedDate}
                    </div>
                </div>
            `;
        }

        document.getElementById('issueModalContent').innerHTML = content;
        document.getElementById('issueDetailsModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeIssueModal() {
        document.getElementById('issueDetailsModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close modal on overlay click
    document.getElementById('issueDetailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeIssueModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeIssueModal();
        }
    });
</script>
@endpush
