@extends('startup.layout')

@section('title', 'Submit MOA')
@section('page-title', 'Submit MOA')

@section('content')
    <!-- Page Header -->
    <div class="page-header-card">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-file-upload"></i>
            </div>
            <div>
                <h1>Submit MOA Document</h1>
                <p>Upload your signed Memorandum of Agreement for review</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; color: #166534; font-size: 14px;">
            <i class="fas fa-check-circle" style="font-size: 18px;"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; color: #991B1B; font-size: 14px;">
            <i class="fas fa-exclamation-circle" style="font-size: 18px;"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h2><i class="fas fa-upload"></i> Upload MOA Document</h2>
            <p>Download the template, fill it out, and upload your signed MOA document</p>
        </div>

        <div class="form-card-body">
            <!-- Steps Guide -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px;">
                <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 12px; padding: 20px; text-align: center;">
                    <div style="width: 40px; height: 40px; background: #3B82F6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; margin: 0 auto 10px;">1</div>
                    <h4 style="font-size: 14px; font-weight: 700; color: #1E40AF; margin: 0 0 4px;">Download Template</h4>
                    <p style="font-size: 12px; color: #3B82F6; margin: 0;">Get the MOA template below</p>
                </div>
                <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 12px; padding: 20px; text-align: center;">
                    <div style="width: 40px; height: 40px; background: #16A34A; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; margin: 0 auto 10px;">2</div>
                    <h4 style="font-size: 14px; font-weight: 700; color: #166534; margin: 0 0 4px;">Fill & Sign</h4>
                    <p style="font-size: 12px; color: #16A34A; margin: 0;">Complete and sign the document</p>
                </div>
                <div style="background: #FDF4FF; border: 1px solid #E9D5FF; border-radius: 12px; padding: 20px; text-align: center;">
                    <div style="width: 40px; height: 40px; background: #9333EA; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; margin: 0 auto 10px;">3</div>
                    <h4 style="font-size: 14px; font-weight: 700; color: #7E22CE; margin: 0 0 4px;">Upload & Submit</h4>
                    <p style="font-size: 12px; color: #9333EA; margin: 0;">Upload the signed document</p>
                </div>
            </div>

            <!-- Template Download Box -->
            <div class="template-box">
                <div class="template-icon">
                    <i class="fas fa-file-download"></i>
                </div>
                <div class="template-info">
                    <h4>Download MOA Template</h4>
                    <p>Use this official template to prepare your Memorandum of Agreement</p>
                </div>
                <a href="{{ asset('documents/moa-template.docx') }}" class="btn btn-secondary" download>
                    <i class="fas fa-download"></i>
                    Download
                </a>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <div class="info-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <h4>Submission Guidelines</h4>
                    <ul>
                        <li>Ensure the MOA document is properly filled out and signed</li>
                        <li>Upload as PDF for best results (DOC/DOCX also accepted)</li>
                        <li>Maximum file size: 10MB</li>
                        <li>Your submission will be reviewed by the admin within 5-10 business days</li>
                    </ul>
                </div>
            </div>

            <form action="{{ route('startup.submit-moa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- MOA Document Upload (Required) -->
                <div class="form-group">
                    <label>MOA Document <span>*</span></label>
                    <div class="file-upload" id="dropZone">
                        <div class="upload-icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <h4>Drag & drop your MOA document here or <span>browse</span></h4>
                        <p>PDF, DOC, DOCX (max 10MB)</p>
                        <input type="file" name="document" id="fileInput" accept=".pdf,.doc,.docx" style="display: none;" required>
                    </div>
                    <div id="filePreview" class="file-preview" style="display: none;">
                        <div class="file-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="file-info">
                            <div class="file-name" id="fileName"></div>
                            <div class="file-size" id="fileSize"></div>
                        </div>
                        <button type="button" class="remove-file" onclick="removeFile()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @error('document')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Optional Notes -->
                <div class="form-group">
                    <label>Additional Notes</label>
                    <textarea name="notes" class="form-textarea @error('notes') error @enderror"
                              placeholder="Any additional information or notes about your MOA submission (optional)"
                              style="min-height: 100px;">{{ old('notes') }}</textarea>
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        Optional: Add any relevant notes for the admin reviewer
                    </div>
                    @error('notes')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('startup.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Submit MOA
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Previous Submissions -->
    @if(isset($moaSubmissions) && $moaSubmissions->count() > 0)
        <div class="form-card" style="margin-top: 24px;">
            <div class="form-card-header">
                <h2><i class="fas fa-history"></i> Previous Submissions</h2>
                <p>Your past MOA submissions and their status</p>
            </div>
            <div class="form-card-body" style="padding: 0;">
                @foreach($moaSubmissions as $moa)
                    <div style="padding: 16px 32px; border-bottom: 1px solid #F3F4F6; display: flex; align-items: center; gap: 16px;">
                        <div style="width: 42px; height: 42px; min-width: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;
                            @if($moa->status === 'approved') background: #F0FDF4; color: #16A34A;
                            @elseif($moa->status === 'pending') background: #FEF3C7; color: #D97706;
                            @elseif($moa->status === 'rejected') background: #FEE2E2; color: #DC2626;
                            @else background: #F3F4F6; color: #6B7280;
                            @endif">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
                                <span style="font-size: 14px; font-weight: 700; color: #1F2937;">{{ $moa->tracking_code }}</span>
                                <span style="font-size: 11px; padding: 2px 8px; border-radius: 20px; font-weight: 600;
                                    @if($moa->status === 'approved') background: #DCFCE7; color: #166534;
                                    @elseif($moa->status === 'pending') background: #FEF3C7; color: #92400E;
                                    @elseif($moa->status === 'rejected') background: #FEE2E2; color: #991B1B;
                                    @else background: #F3F4F6; color: #374151;
                                    @endif">{{ ucfirst($moa->status) }}</span>
                            </div>
                            <div style="font-size: 12px; color: #9CA3AF;">Submitted {{ $moa->created_at->format('M d, Y') }}</div>
                            @if($moa->status === 'rejected' && $moa->rejection_remarks)
                                <div style="margin-top: 6px; font-size: 12px; color: #991B1B; background: #FEF2F2; padding: 6px 10px; border-radius: 6px;">
                                    <i class="fas fa-comment-alt" style="margin-right: 4px;"></i> {{ $moa->rejection_remarks }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    // Click to upload
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag and drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            showFilePreview(e.dataTransfer.files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
            showFilePreview(fileInput.files[0]);
        }
    });

    function showFilePreview(file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        dropZone.style.display = 'none';
        filePreview.style.display = 'flex';

        const icon = filePreview.querySelector('.file-icon i');
        if (file.type.includes('pdf')) {
            icon.className = 'fas fa-file-pdf';
        } else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
            icon.className = 'fas fa-file-word';
        } else {
            icon.className = 'fas fa-file';
        }
    }

    function removeFile() {
        fileInput.value = '';
        dropZone.style.display = 'block';
        filePreview.style.display = 'none';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
</script>
@endpush
