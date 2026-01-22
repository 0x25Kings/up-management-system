@extends('startup.layout')

@section('title', 'Upload Document')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('startup.dashboard') }}"><i class="fas fa-home"></i></a>
        <span>/</span>
        <span>Upload Document</span>
    </div>

    <!-- Page Header -->
    <div class="page-header-card">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <div>
                <h1>Upload Document</h1>
                <p>Submit your important documents securely to the UP Cebu Incubator</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h2><i class="fas fa-file-alt"></i> Document Submission Form</h2>
            <p>Fill out the form below to upload your document</p>
        </div>

        <div class="form-card-body">
            <!-- Info Box -->
            <div class="info-box">
                <div class="info-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div>
                    <h4>Accepted File Types</h4>
                    <ul>
                        <li>PDF documents (.pdf)</li>
                        <li>Word documents (.doc, .docx)</li>
                        <li>Images (.jpg, .png)</li>
                        <li>Maximum file size: 10MB</li>
                    </ul>
                </div>
            </div>

            <form action="{{ route('startup.document.submit') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                <!-- Document Type -->
                <div class="form-group">
                    <label>Document Type <span>*</span></label>
                    <select name="document_type" class="form-select @error('document_type') error @enderror" required>
                        <option value="">-- Select Document Type --</option>
                        <option value="business_permit" {{ old('document_type') == 'business_permit' ? 'selected' : '' }}>Business Permit</option>
                        <option value="dti_registration" {{ old('document_type') == 'dti_registration' ? 'selected' : '' }}>DTI Registration</option>
                        <option value="bir_registration" {{ old('document_type') == 'bir_registration' ? 'selected' : '' }}>BIR Registration</option>
                        <option value="financial_report" {{ old('document_type') == 'financial_report' ? 'selected' : '' }}>Financial Report</option>
                        <option value="progress_report" {{ old('document_type') == 'progress_report' ? 'selected' : '' }}>Progress Report</option>
                        <option value="pitch_deck" {{ old('document_type') == 'pitch_deck' ? 'selected' : '' }}>Pitch Deck</option>
                        <option value="business_plan" {{ old('document_type') == 'business_plan' ? 'selected' : '' }}>Business Plan</option>
                        <option value="other" {{ old('document_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('document_type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Document Title -->
                <div class="form-group">
                    <label>Document Title <span>*</span></label>
                    <input type="text" name="title" class="form-input @error('title') error @enderror" 
                           placeholder="Enter a descriptive title for your document" 
                           value="{{ old('title') }}" required>
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="form-group">
                    <label>Document File <span>*</span></label>
                    <div class="file-upload" id="dropZone">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <h4>Drag & drop your file here or <span>browse</span></h4>
                        <p>PDF, DOC, DOCX, JPG, PNG (max 10MB)</p>
                        <input type="file" name="document" id="fileInput" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                               style="display: none;" required>
                    </div>
                    <div id="filePreview" class="file-preview" style="display: none;">
                        <div class="file-icon">
                            <i class="fas fa-file"></i>
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

                <!-- Notes -->
                <div class="form-group">
                    <label>Additional Notes</label>
                    <textarea name="notes" class="form-textarea @error('notes') error @enderror" 
                              placeholder="Add any additional information or context about this document...">{{ old('notes') }}</textarea>
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        Optional: Provide any relevant details about this document
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
                        <i class="fas fa-cloud-upload-alt"></i>
                        Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>
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

        // Update icon based on file type
        const icon = filePreview.querySelector('.file-icon i');
        if (file.type.includes('pdf')) {
            icon.className = 'fas fa-file-pdf';
        } else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
            icon.className = 'fas fa-file-word';
        } else if (file.type.includes('image')) {
            icon.className = 'fas fa-file-image';
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
