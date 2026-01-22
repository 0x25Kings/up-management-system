@extends('startup.layout')

@section('title', 'Submit Payment')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('startup.dashboard') }}"><i class="fas fa-home"></i></a>
        <span>/</span>
        <span>Submit Payment</span>
    </div>

    <!-- Page Header -->
    <div class="page-header-card">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <div>
                <h1>Submit Payment</h1>
                <p>Upload your payment proof for incubator fees and services</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h2><i class="fas fa-receipt"></i> Payment Submission Form</h2>
            <p>Submit your payment proof for verification</p>
        </div>

        <div class="form-card-body">
            <!-- Payment Info Box -->
            <div class="info-box">
                <div class="info-icon">
                    <i class="fas fa-university"></i>
                </div>
                <div>
                    <h4>Payment Details</h4>
                    <ul>
                        <li><strong>Bank:</strong> Land Bank of the Philippines</li>
                        <li><strong>Account Name:</strong> UP Cebu Trust Fund</li>
                        <li><strong>Account Number:</strong> 1234-5678-9012</li>
                        <li><strong>Note:</strong> Include your company name as reference</li>
                    </ul>
                </div>
            </div>

            <form action="{{ route('startup.payment.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Invoice Number -->
                    <div class="form-group">
                        <label>Invoice/Reference Number <span>*</span></label>
                        <input type="text" name="invoice_number" class="form-input @error('invoice_number') error @enderror" 
                               placeholder="e.g., INV-2024-001" value="{{ old('invoice_number') }}" required>
                        @error('invoice_number')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Amount Paid -->
                    <div class="form-group">
                        <label>Amount Paid <span>*</span></label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #6B7280; font-weight: 600;">₱</span>
                            <input type="number" name="amount" class="form-input @error('amount') error @enderror" 
                                   style="padding-left: 36px;"
                                   placeholder="0.00" value="{{ old('amount') }}" step="0.01" min="0" required>
                        </div>
                        @error('amount')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="form-group">
                    <label>Payment Method <span>*</span></label>
                    <select name="payment_method" class="form-select @error('payment_method') error @enderror" required>
                        <option value="">-- Select Payment Method --</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                        <option value="bank_deposit" {{ old('payment_method') == 'bank_deposit' ? 'selected' : '' }}>💵 Bank Deposit (Over-the-counter)</option>
                        <option value="gcash" {{ old('payment_method') == 'gcash' ? 'selected' : '' }}>📱 GCash</option>
                        <option value="maya" {{ old('payment_method') == 'maya' ? 'selected' : '' }}>📱 Maya (PayMaya)</option>
                        <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>📄 Check Payment</option>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>💰 Cash (In-person)</option>
                    </select>
                    @error('payment_method')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Payment Date -->
                <div class="form-group">
                    <label>Payment Date <span>*</span></label>
                    <input type="date" name="payment_date" class="form-input @error('payment_date') error @enderror" 
                           value="{{ old('payment_date', date('Y-m-d')) }}" required>
                    @error('payment_date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Payment Proof Upload -->
                <div class="form-group">
                    <label>Payment Proof <span>*</span></label>
                    <div class="file-upload" id="dropZone">
                        <div class="upload-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h4>Drag & drop your receipt here or <span>browse</span></h4>
                        <p>JPG, PNG, PDF (max 5MB) - Screenshot or photo of payment confirmation</p>
                        <input type="file" name="payment_proof" id="fileInput" accept=".jpg,.jpeg,.png,.pdf" 
                               style="display: none;" required>
                    </div>
                    <div id="filePreview" class="file-preview" style="display: none;">
                        <div class="file-icon">
                            <i class="fas fa-file-image"></i>
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
                        Upload a clear image or PDF of your payment receipt/confirmation
                    </div>
                    @error('payment_proof')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label>Additional Notes</label>
                    <textarea name="notes" class="form-textarea @error('notes') error @enderror" 
                              placeholder="Any additional information about this payment (e.g., what the payment is for, transaction reference, etc.)">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Image Preview with Verification Section -->
                <div id="verificationSection" style="display: none; background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); border: 2px solid #F59E0B; border-radius: 16px; padding: 24px; margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                        <div style="width: 44px; height: 44px; background: #F59E0B; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-search-dollar" style="color: white; font-size: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="color: #92400E; font-size: 16px; font-weight: 700; margin: 0;">Verify Your Payment Proof</h4>
                            <p style="color: #B45309; font-size: 13px; margin: 0;">Please ensure the details below match your uploaded receipt</p>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                        <!-- Entered Details -->
                        <div style="background: white; border-radius: 12px; padding: 20px; border: 1px solid #FCD34D;">
                            <h5 style="font-size: 13px; font-weight: 600; color: #92400E; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                                <i class="fas fa-keyboard"></i> Your Entered Details
                            </h5>
                            <div style="space-y: 12px;">
                                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px dashed #E5E7EB;">
                                    <span style="color: #6B7280; font-size: 13px;">Invoice Number:</span>
                                    <span id="verifyInvoice" style="font-weight: 600; color: #1F2937; font-family: monospace;"></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px dashed #E5E7EB;">
                                    <span style="color: #6B7280; font-size: 13px;">Amount Paid:</span>
                                    <span id="verifyAmount" style="font-weight: 600; color: #059669; font-size: 16px;"></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px dashed #E5E7EB;">
                                    <span style="color: #6B7280; font-size: 13px;">Payment Method:</span>
                                    <span id="verifyMethod" style="font-weight: 600; color: #1F2937;"></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                                    <span style="color: #6B7280; font-size: 13px;">Payment Date:</span>
                                    <span id="verifyDate" style="font-weight: 600; color: #1F2937;"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Image Preview -->
                        <div style="background: white; border-radius: 12px; padding: 20px; border: 1px solid #FCD34D;">
                            <h5 style="font-size: 13px; font-weight: 600; color: #92400E; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                                <i class="fas fa-image"></i> Your Uploaded Proof
                            </h5>
                            <div id="imagePreviewContainer" style="text-align: center;">
                                <img id="proofPreview" src="" alt="Payment Proof" style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 2px solid #E5E7EB;">
                                <div id="pdfPreview" style="display: none; padding: 30px; background: #F9FAFB; border-radius: 8px;">
                                    <i class="fas fa-file-pdf" style="font-size: 48px; color: #EF4444;"></i>
                                    <p style="margin-top: 10px; color: #6B7280; font-size: 13px;">PDF Document Uploaded</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Quality Warning -->
                    <div id="qualityWarning" style="display: none; margin-top: 16px; background: #FEE2E2; border: 1px solid #FECACA; border-radius: 10px; padding: 14px; display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-exclamation-triangle" style="color: #DC2626; font-size: 20px;"></i>
                        <div>
                            <strong style="color: #991B1B;">Image Quality Issue</strong>
                            <p style="color: #B91C1C; font-size: 13px; margin: 0;">The uploaded image appears to be low resolution. Please upload a clearer image for faster verification.</p>
                        </div>
                    </div>

                    <!-- Confirmation Checkbox -->
                    <div style="margin-top: 20px; background: white; border-radius: 10px; padding: 16px; border: 2px solid #FCD34D;">
                        <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                            <input type="checkbox" name="proof_verified" id="proofVerified" required 
                                   style="width: 22px; height: 22px; margin-top: 2px; accent-color: #7B1D3A;">
                            <div>
                                <span style="font-weight: 600; color: #1F2937; font-size: 14px;">
                                    I confirm that the payment proof I uploaded is clear, readable, and matches the details I entered above.
                                </span>
                                <p style="font-size: 12px; color: #6B7280; margin-top: 4px;">
                                    <i class="fas fa-info-circle"></i> 
                                    Submissions with unclear or mismatched proofs will be rejected and may delay your verification.
                                </p>
                            </div>
                        </label>
                        @error('proof_verified')
                            <div class="error-message" style="margin-top: 8px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('startup.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Submit Payment
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
        #verificationSection > div[style*="grid"] {
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
    const verificationSection = document.getElementById('verificationSection');
    const proofPreview = document.getElementById('proofPreview');
    const pdfPreview = document.getElementById('pdfPreview');
    const qualityWarning = document.getElementById('qualityWarning');

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
            proofPreview.style.display = 'none';
            pdfPreview.style.display = 'block';
        } else if (file.type.includes('image')) {
            icon.className = 'fas fa-file-image';
            proofPreview.style.display = 'block';
            pdfPreview.style.display = 'none';
            
            // Show image preview
            const reader = new FileReader();
            reader.onload = function(e) {
                proofPreview.src = e.target.result;
                
                // Check image quality
                const img = new Image();
                img.onload = function() {
                    if (img.width < 400 || img.height < 300) {
                        qualityWarning.style.display = 'flex';
                    } else {
                        qualityWarning.style.display = 'none';
                    }
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            icon.className = 'fas fa-file';
        }

        // Show verification section and update details
        updateVerificationDetails();
        verificationSection.style.display = 'block';
    }

    function removeFile() {
        fileInput.value = '';
        dropZone.style.display = 'block';
        filePreview.style.display = 'none';
        verificationSection.style.display = 'none';
        document.getElementById('proofVerified').checked = false;
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function updateVerificationDetails() {
        const invoice = document.querySelector('input[name="invoice_number"]').value || '-';
        const amount = document.querySelector('input[name="amount"]').value || '0';
        const methodSelect = document.querySelector('select[name="payment_method"]');
        const method = methodSelect.options[methodSelect.selectedIndex]?.text || '-';
        const date = document.querySelector('input[name="payment_date"]').value || '-';

        document.getElementById('verifyInvoice').textContent = invoice;
        document.getElementById('verifyAmount').textContent = '₱' + parseFloat(amount).toLocaleString('en-PH', {minimumFractionDigits: 2});
        document.getElementById('verifyMethod').textContent = method.replace(/^[^\s]+\s/, ''); // Remove emoji
        document.getElementById('verifyDate').textContent = date ? new Date(date).toLocaleDateString('en-PH', {year: 'numeric', month: 'long', day: 'numeric'}) : '-';
    }

    // Update verification details when form fields change
    document.querySelector('input[name="invoice_number"]').addEventListener('input', updateVerificationDetails);
    document.querySelector('input[name="amount"]').addEventListener('input', updateVerificationDetails);
    document.querySelector('select[name="payment_method"]').addEventListener('change', updateVerificationDetails);
    document.querySelector('input[name="payment_date"]').addEventListener('change', updateVerificationDetails);
</script>
@endpush
