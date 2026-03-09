@extends('startup.layout')

@section('title', 'Request MOA')
@section('page-title', 'Request MOA')

@section('content')
    <!-- Page Header -->
    <div class="page-header-card">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-file-signature"></i>
            </div>
            <div>
                <h1>Request MOA</h1>
                <p>Submit a Memorandum of Agreement request for formal partnership</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h2><i class="fas fa-handshake"></i> MOA Request Form</h2>
            <p>Complete this form to request a Memorandum of Agreement</p>
        </div>

        <div class="form-card-body">
            <!-- Template Download Box -->
            <div class="template-box">
                <div class="template-icon">
                    <i class="fas fa-file-download"></i>
                </div>
                <div class="template-info">
                    <h4>Download MOA Template</h4>
                    <p>Get the standard MOA template before filling out this form</p>
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
                    <h4>Important Information</h4>
                    <ul>
                        <li>MOA processing typically takes 5-10 business days</li>
                        <li>You will be notified via email once the MOA is ready for signing</li>
                        <li>Ensure all company details are accurate before submission</li>
                        <li>Supporting documents help expedite the process</li>
                    </ul>
                </div>
            </div>

            <form action="{{ route('startup.moa.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- MOA Purpose -->
                <div class="form-group">
                    <label>Purpose of MOA <span>*</span></label>
                    <select name="moa_purpose" class="form-select @error('moa_purpose') error @enderror" required>
                        <option value="">-- Select Purpose --</option>
                        <option value="incubation" {{ old('moa_purpose') == 'incubation' ? 'selected' : '' }}>📋 Incubation Agreement</option>
                        <option value="partnership" {{ old('moa_purpose') == 'partnership' ? 'selected' : '' }}>🤝 Business Partnership</option>
                        <option value="collaboration" {{ old('moa_purpose') == 'collaboration' ? 'selected' : '' }}>👥 Research Collaboration</option>
                        <option value="funding" {{ old('moa_purpose') == 'funding' ? 'selected' : '' }}>💰 Funding Agreement</option>
                        <option value="mentorship" {{ old('moa_purpose') == 'mentorship' ? 'selected' : '' }}>🎓 Mentorship Program</option>
                        <option value="renewal" {{ old('moa_purpose') == 'renewal' ? 'selected' : '' }}>🔄 MOA Renewal</option>
                        <option value="other" {{ old('moa_purpose') == 'other' ? 'selected' : '' }}>📝 Other</option>
                    </select>
                    @error('moa_purpose')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Proposed Duration -->
                <div class="form-group">
                    <label>Proposed MOA Duration <span>*</span></label>
                    <select name="moa_duration" class="form-select @error('moa_duration') error @enderror" required>
                        <option value="">-- Select Duration --</option>
                        <option value="6_months" {{ old('moa_duration') == '6_months' ? 'selected' : '' }}>6 Months</option>
                        <option value="1_year" {{ old('moa_duration') == '1_year' ? 'selected' : '' }}>1 Year</option>
                        <option value="2_years" {{ old('moa_duration') == '2_years' ? 'selected' : '' }}>2 Years</option>
                        <option value="3_years" {{ old('moa_duration') == '3_years' ? 'selected' : '' }}>3 Years</option>
                        <option value="5_years" {{ old('moa_duration') == '5_years' ? 'selected' : '' }}>5 Years</option>
                    </select>
                    @error('moa_duration')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- MOA Details -->
                <div class="form-group">
                    <label>MOA Details & Objectives <span>*</span></label>
                    <textarea name="moa_details" class="form-textarea @error('moa_details') error @enderror" 
                              placeholder="Please describe in detail:
• The objectives of this MOA
• Expected outcomes and deliverables
• Any specific terms or conditions you'd like to include
• Parties involved (if any third party)"
                              style="min-height: 180px;"
                              required>{{ old('moa_details') }}</textarea>
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        Provide comprehensive details to help us prepare the MOA accurately
                    </div>
                    @error('moa_details')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Supporting Document Upload -->
                <div class="form-group">
                    <label>Supporting Documents</label>
                    <div class="file-upload" id="dropZone">
                        <div class="upload-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h4>Drag & drop files here or <span>browse</span></h4>
                        <p>PDF, DOC, DOCX (max 10MB) - Business plans, proposals, etc.</p>
                        <input type="file" name="document" id="fileInput" accept=".pdf,.doc,.docx" style="display: none;">
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
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        Optional: Attach any supporting documents that may help process your request
                    </div>
                    @error('document')
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
                        Submit Request
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
