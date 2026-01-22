@extends('startup.layout')

@section('title', 'Report Issue')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('startup.dashboard') }}"><i class="fas fa-home"></i></a>
        <span>/</span>
        <span>Report Issue</span>
    </div>

    <!-- Page Header -->
    <div class="page-header-card">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h1>Report Room Issue</h1>
                <p>Report any maintenance issues or problems in your designated room</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h2><i class="fas fa-tools"></i> Issue Report Form</h2>
            <p>Please provide detailed information about the issue</p>
        </div>

        <div class="form-card-body">
            <!-- Info Box -->
            <div class="info-box">
                <div class="info-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <h4>How to Report an Issue</h4>
                    <ul>
                        <li>Provide accurate room number and location</li>
                        <li>Select the appropriate priority level</li>
                        <li>Describe the issue in detail</li>
                        <li>Attach photos if possible for faster resolution</li>
                    </ul>
                </div>
            </div>

            <form action="{{ route('startup.issue.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Room Number -->
                    <div class="form-group">
                        <label>Room Number <span>*</span></label>
                        <input type="text" name="room_number" class="form-input @error('room_number') error @enderror" 
                               placeholder="e.g., Room 101" value="{{ old('room_number') }}" required>
                        @error('room_number')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div class="form-group">
                        <label>Priority Level <span>*</span></label>
                        <select name="priority" class="form-select @error('priority') error @enderror" required>
                            <option value="">-- Select Priority --</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>🟢 Low - Minor inconvenience</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>🟡 Medium - Affects work</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🟠 High - Urgent attention needed</option>
                            <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>🔴 Critical - Immediate action required</option>
                        </select>
                        @error('priority')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Issue Type -->
                <div class="form-group">
                    <label>Issue Type <span>*</span></label>
                    <select name="issue_type" class="form-select @error('issue_type') error @enderror" required>
                        <option value="">-- Select Issue Type --</option>
                        <option value="electrical" {{ old('issue_type') == 'electrical' ? 'selected' : '' }}>⚡ Electrical Issue</option>
                        <option value="plumbing" {{ old('issue_type') == 'plumbing' ? 'selected' : '' }}>🔧 Plumbing Issue</option>
                        <option value="aircon" {{ old('issue_type') == 'aircon' ? 'selected' : '' }}>❄️ Air Conditioning</option>
                        <option value="furniture" {{ old('issue_type') == 'furniture' ? 'selected' : '' }}>🪑 Furniture/Fixtures</option>
                        <option value="internet" {{ old('issue_type') == 'internet' ? 'selected' : '' }}>📶 Internet/Network</option>
                        <option value="security" {{ old('issue_type') == 'security' ? 'selected' : '' }}>🔒 Security/Access</option>
                        <option value="cleaning" {{ old('issue_type') == 'cleaning' ? 'selected' : '' }}>🧹 Cleaning/Sanitation</option>
                        <option value="other" {{ old('issue_type') == 'other' ? 'selected' : '' }}>📋 Other</option>
                    </select>
                    @error('issue_type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Issue Description -->
                <div class="form-group">
                    <label>Issue Description <span>*</span></label>
                    <textarea name="description" class="form-textarea @error('description') error @enderror" 
                              placeholder="Please describe the issue in detail. Include location specifics, when the issue started, and any other relevant information..."
                              required>{{ old('description') }}</textarea>
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        The more details you provide, the faster we can resolve the issue
                    </div>
                    @error('description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Photo Upload -->
                <div class="form-group">
                    <label>Photo Evidence</label>
                    <div class="file-upload" id="dropZone">
                        <div class="upload-icon">
                            <i class="fas fa-camera"></i>
                        </div>
                        <h4>Drag & drop photos here or <span>browse</span></h4>
                        <p>JPG, PNG (max 5MB) - Recommended for faster resolution</p>
                        <input type="file" name="photo" id="fileInput" accept=".jpg,.jpeg,.png" style="display: none;">
                    </div>
                    <div id="filePreview" class="file-preview" style="display: none;">
                        <div class="file-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <div class="file-info">
                            <div class="file-name" id="fileName"></div>
                            <div class="file-size" id="fileSize"></div>
                        </div>
                        <button type="button" class="remove-file" onclick="removeFile()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @error('photo')
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
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    @media (max-width: 768px) {
        .form-card-body > form > div[style*="grid"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

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
