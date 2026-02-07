@extends('startup.layout')

@section('title', 'Project Progress')
@section('page-title', 'Project Progress')

@push('styles')
<style>
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

    .progress-form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .progress-form-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, #EDE9FE 0%, #DDD6FE 100%);
        border-bottom: 1px solid #E5E7EB;
    }

    .progress-form-header h2 {
        font-size: 17px;
        font-weight: 700;
        color: #5B21B6;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .progress-form-body {
        padding: 24px;
    }

    .progress-form-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    .progress-input, .progress-select, .progress-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #E5E7EB;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
    }

    .progress-input:focus, .progress-select:focus, .progress-textarea:focus {
        outline: none;
        border-color: #7B1D3A;
        box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1);
    }

    .progress-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .progress-file-upload {
        border: 2px dashed #D1D5DB;
        border-radius: 10px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: #FAFAFA;
    }

    .progress-file-upload:hover {
        border-color: #7B1D3A;
        background: #FDF2F4;
    }

    .progress-file-upload i {
        font-size: 32px;
        color: #9CA3AF;
        margin-bottom: 12px;
    }

    .progress-file-upload p {
        font-size: 14px;
        color: #6B7280;
    }

    .progress-file-upload span {
        color: #7B1D3A;
        font-weight: 600;
    }

    .progress-file-preview {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: #D1FAE5;
        border-radius: 8px;
        margin-top: 12px;
    }

    .progress-file-preview i {
        color: #059669;
    }

    .progress-file-preview span {
        flex: 1;
        color: #065F46;
        font-weight: 500;
    }

    .progress-file-preview button {
        background: none;
        border: none;
        color: #DC2626;
        cursor: pointer;
        padding: 4px 8px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #E5E7EB;
    }

    .progress-list-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .progress-list-header {
        padding: 20px 24px;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .progress-list-header h2 {
        font-size: 17px;
        font-weight: 700;
        color: #1F2937;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .progress-list-header h2 i {
        color: #7C3AED;
    }

    .progress-item {
        display: flex;
        gap: 16px;
        padding: 20px 24px;
        border-bottom: 1px solid #F3F4F6;
        transition: all 0.3s;
    }

    .progress-item:last-child {
        border-bottom: none;
    }

    .progress-item:hover {
        background: #FAFAFA;
    }

    .progress-item-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .progress-item-icon.development { background: linear-gradient(135deg, #DBEAFE, #BFDBFE); color: #2563EB; }
    .progress-item-icon.funding { background: linear-gradient(135deg, #D1FAE5, #A7F3D0); color: #059669; }
    .progress-item-icon.partnership { background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #D97706; }
    .progress-item-icon.launch { background: linear-gradient(135deg, #FCE7F3, #FBCFE8); color: #DB2777; }
    .progress-item-icon.achievement { background: linear-gradient(135deg, #EDE9FE, #DDD6FE); color: #7C3AED; }
    .progress-item-icon.other { background: linear-gradient(135deg, #F3F4F6, #E5E7EB); color: #6B7280; }

    .progress-item-content {
        flex: 1;
        min-width: 0;
    }

    .progress-item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .progress-item-title {
        font-weight: 600;
        color: #1F2937;
        font-size: 16px;
    }

    .progress-item-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-submitted { background: #FEF3C7; color: #92400E; }
    .badge-reviewed { background: #DBEAFE; color: #1E40AF; }
    .badge-acknowledged { background: #D1FAE5; color: #065F46; }

    .progress-item-desc {
        font-size: 14px;
        color: #6B7280;
        line-height: 1.6;
        margin-bottom: 12px;
    }

    .progress-item-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 12px;
        color: #9CA3AF;
    }

    .progress-item-meta a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        background: #F3F4F6;
        border-radius: 6px;
        color: #4B5563;
        text-decoration: none;
        transition: all 0.3s;
    }

    .progress-item-meta a:hover {
        background: #E5E7EB;
        color: #1F2937;
    }

    .admin-response {
        margin-top: 16px;
        padding: 16px;
        background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%);
        border-radius: 10px;
        border-left: 4px solid #0284C7;
    }

    .admin-response-label {
        font-size: 12px;
        font-weight: 600;
        color: #0369A1;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .admin-response-text {
        font-size: 14px;
        color: #0C4A6E;
        line-height: 1.5;
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
    }

    /* Pagination Styles */
    .pagination-wrapper {
        padding: 20px 24px;
        border-top: 1px solid #E5E7EB;
        display: flex;
        justify-content: center;
    }

    .pagination-wrapper nav {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .pagination-wrapper nav > div:first-child {
        display: none;
    }

    .pagination-wrapper nav span,
    .pagination-wrapper nav a {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 12px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination-wrapper nav a {
        background: white;
        border: 1px solid #E5E7EB;
        color: #374151;
    }

    .pagination-wrapper nav a:hover {
        background: #7B1D3A;
        border-color: #7B1D3A;
        color: white;
    }

    .pagination-wrapper nav span[aria-current="page"] span {
        background: #7B1D3A;
        color: white;
        border: 1px solid #7B1D3A;
    }

    .pagination-wrapper nav span[aria-disabled="true"] {
        background: #F9FAFB;
        border: 1px solid #E5E7EB;
        color: #D1D5DB;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Project Progress</h1>
    <p>Share your milestones and progress updates with the UP Cebu Incubator</p>
</div>

<!-- Add Progress Form -->
<div class="progress-form-card">
    <div class="progress-form-header">
        <h2><i class="fas fa-plus-circle"></i> Submit Progress Update</h2>
    </div>
    <div class="progress-form-body">
        <form action="{{ route('startup.progress.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="progress-form-row">
                <select name="milestone_type" class="progress-select" required>
                    <option value="">Select Milestone Type</option>
                    <option value="development">Product Development</option>
                    <option value="funding">Funding/Investment</option>
                    <option value="partnership">Partnership</option>
                    <option value="launch">Product Launch</option>
                    <option value="achievement">Achievement/Award</option>
                    <option value="other">Other Update</option>
                </select>
                <input type="text" name="title" class="progress-input" placeholder="Title of your progress update" required>
            </div>
            <textarea name="description" class="progress-textarea" placeholder="Describe your progress, achievements, or any updates you'd like to share with the admin..." required></textarea>
            
            <div style="margin-top: 16px;">
                <label style="font-weight: 600; color: #374151; margin-bottom: 8px; display: block;">Attachment (Optional)</label>
                <div class="progress-file-upload" onclick="document.getElementById('progressFile').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p><span>Click to upload</span> or drag a file here</p>
                    <p style="font-size: 12px; color: #9CA3AF; margin-top: 4px;">PDF, DOC, DOCX, JPG, PNG (max 10MB)</p>
                    <input type="file" name="attachment" id="progressFile" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="display:none;">
                </div>
                <div id="progressFilePreview" class="progress-file-preview" style="display: none;">
                    <i class="fas fa-file"></i>
                    <span id="progressFileName"></span>
                    <button type="button" onclick="removeProgressFile()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Submit Progress Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Progress History -->
<div class="progress-list-card">
    <div class="progress-list-header">
        <h2><i class="fas fa-history"></i> Progress History</h2>
        <span style="font-size: 13px; color: #6B7280;">{{ $progressUpdates->total() }} updates</span>
    </div>
    
    @forelse($progressUpdates as $progress)
        <div class="progress-item">
            <div class="progress-item-icon {{ $progress->milestone_type }}">
                <i class="fas {{ 
                    $progress->milestone_type === 'development' ? 'fa-code' : 
                    ($progress->milestone_type === 'funding' ? 'fa-dollar-sign' : 
                    ($progress->milestone_type === 'partnership' ? 'fa-handshake' : 
                    ($progress->milestone_type === 'launch' ? 'fa-rocket' : 
                    ($progress->milestone_type === 'achievement' ? 'fa-trophy' : 'fa-star')))) 
                }}"></i>
            </div>
            <div class="progress-item-content">
                <div class="progress-item-header">
                    <div class="progress-item-title">{{ $progress->title }}</div>
                    <span class="progress-item-badge badge-{{ $progress->status }}">
                        {{ $progress->status === 'submitted' ? 'Pending Review' : ucfirst($progress->status) }}
                    </span>
                </div>
                <div class="progress-item-desc">{{ $progress->description }}</div>
                <div class="progress-item-meta">
                    <span><i class="fas fa-tag"></i> {{ $progress->milestone_type_label }}</span>
                    <span><i class="fas fa-clock"></i> {{ $progress->created_at->format('M d, Y') }} • {{ $progress->created_at->diffForHumans() }}</span>
                    @if($progress->file_path)
                        <a href="{{ Storage::url($progress->file_path) }}" target="_blank">
                            <i class="fas fa-paperclip"></i> {{ $progress->original_filename }}
                        </a>
                    @endif
                </div>
                @if($progress->admin_comment)
                    <div class="admin-response">
                        <div class="admin-response-label">
                            <i class="fas fa-comment-dots"></i> Admin Response
                        </div>
                        <div class="admin-response-text">{{ $progress->admin_comment }}</div>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-chart-line"></i>
            <h3>No progress updates yet</h3>
            <p>Submit your first progress update to share your milestones with the admin.</p>
        </div>
    @endforelse

    @if($progressUpdates->hasPages())
        <div class="pagination-wrapper">
            {{ $progressUpdates->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    const progressFile = document.getElementById('progressFile');
    if (progressFile) {
        progressFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('progressFileName').textContent = file.name;
                document.getElementById('progressFilePreview').style.display = 'flex';
                document.querySelector('.progress-file-upload').style.display = 'none';
            }
        });
    }

    function removeProgressFile() {
        document.getElementById('progressFile').value = '';
        document.getElementById('progressFilePreview').style.display = 'none';
        document.querySelector('.progress-file-upload').style.display = 'block';
    }
</script>
@endpush
