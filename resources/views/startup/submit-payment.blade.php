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
                               style="display: none;">
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
                        <!-- Image Preview (Left side) -->
                        <div style="background: white; border-radius: 12px; padding: 20px; border: 1px solid #FCD34D;">
                            <h5 style="font-size: 13px; font-weight: 600; color: #92400E; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                                <i class="fas fa-image"></i> Your Uploaded Proof
                            </h5>
                            <div id="imagePreviewContainer" style="text-align: center;">
                                <img id="proofPreview" src="" alt="Payment Proof" style="max-width: 100%; max-height: 280px; border-radius: 8px; border: 2px solid #E5E7EB; cursor: pointer;" onclick="openImageModal()">
                                <div id="pdfPreview" style="display: none; padding: 30px; background: #F9FAFB; border-radius: 8px;">
                                    <i class="fas fa-file-pdf" style="font-size: 48px; color: #EF4444;"></i>
                                    <p style="margin-top: 10px; color: #6B7280; font-size: 13px;">PDF Document Uploaded</p>
                                </div>
                            </div>
                            <p style="font-size: 11px; color: #6B7280; text-align: center; margin-top: 10px;">
                                <i class="fas fa-search-plus"></i> Click image to enlarge
                            </p>
                        </div>

                        <!-- Entered Details (Right side) -->
                        <div style="background: white; border-radius: 12px; padding: 20px; border: 1px solid #FCD34D;">
                            <h5 style="font-size: 13px; font-weight: 600; color: #92400E; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                                <i class="fas fa-keyboard"></i> Your Entered Details
                            </h5>
                            <div>
                                <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px dashed #E5E7EB; align-items: center;">
                                    <span style="color: #6B7280; font-size: 13px;"><i class="fas fa-hashtag" style="width: 16px; color: #9CA3AF;"></i> Invoice/Ref #:</span>
                                    <span id="verifyInvoice" style="font-weight: 600; color: #1F2937; font-family: monospace; background: #F3F4F6; padding: 4px 10px; border-radius: 6px;"></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px dashed #E5E7EB; align-items: center;">
                                    <span style="color: #6B7280; font-size: 13px;"><i class="fas fa-peso-sign" style="width: 16px; color: #9CA3AF;"></i> Amount Paid:</span>
                                    <span id="verifyAmount" style="font-weight: 700; color: #059669; font-size: 18px; background: #ECFDF5; padding: 4px 12px; border-radius: 6px;"></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px dashed #E5E7EB; align-items: center;">
                                    <span style="color: #6B7280; font-size: 13px;"><i class="fas fa-credit-card" style="width: 16px; color: #9CA3AF;"></i> Payment Method:</span>
                                    <span id="verifyMethod" style="font-weight: 600; color: #1F2937; background: #F3F4F6; padding: 4px 10px; border-radius: 6px;"></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 12px 0; align-items: center;">
                                    <span style="color: #6B7280; font-size: 13px;"><i class="fas fa-calendar" style="width: 16px; color: #9CA3AF;"></i> Payment Date:</span>
                                    <span id="verifyDate" style="font-weight: 600; color: #1F2937; background: #F3F4F6; padding: 4px 10px; border-radius: 6px;"></span>
                                </div>
                            </div>
                            
                            <!-- Match Checklist -->
                            <div style="margin-top: 16px; padding: 14px; background: #FEF9C3; border-radius: 8px; border: 1px solid #FDE047;">
                                <p style="font-size: 12px; font-weight: 600; color: #854D0E; margin-bottom: 8px;">
                                    <i class="fas fa-clipboard-check"></i> Please verify these match your receipt:
                                </p>
                                <ul style="font-size: 12px; color: #713F12; margin: 0; padding-left: 18px;">
                                    <li>Amount shown on receipt</li>
                                    <li>Transaction/Reference number</li>
                                    <li>Date of payment</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Image Quality Warning -->
                    <div id="qualityWarning" style="display: none; margin-top: 16px; background: #FEE2E2; border: 1px solid #FECACA; border-radius: 10px; padding: 14px; align-items: center; gap: 12px;">
                        <i class="fas fa-exclamation-triangle" style="color: #DC2626; font-size: 20px;"></i>
                        <div>
                            <strong style="color: #991B1B;">Image Quality Issue</strong>
                            <p style="color: #B91C1C; font-size: 13px; margin: 0;">The uploaded image appears to be low resolution. Please upload a clearer image for faster verification.</p>
                        </div>
                    </div>

                    <!-- Amount Verification Section -->
                    <div style="margin-top: 20px; background: white; border-radius: 10px; padding: 20px; border: 2px solid #F59E0B;">
                        <h5 style="font-size: 14px; font-weight: 700; color: #92400E; margin-bottom: 16px;">
                            <i class="fas fa-robot"></i> Automatic Amount Verification
                        </h5>
                        
                        <!-- OCR Processing Status -->
                        <div id="ocrStatus" style="display: none; padding: 16px; border-radius: 10px; margin-bottom: 16px; text-align: center;">
                            <!-- Updated by JS -->
                        </div>
                        
                        <!-- Side by Side Comparison -->
                        <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 16px; align-items: center; margin-bottom: 16px;">
                            <!-- Form Amount -->
                            <div style="background: #F3F4F6; border-radius: 10px; padding: 16px; text-align: center;">
                                <p style="font-size: 11px; color: #6B7280; margin-bottom: 4px; text-transform: uppercase; font-weight: 600;">Amount You Entered</p>
                                <p id="displayFormAmount" style="font-size: 24px; font-weight: 700; color: #1F2937; margin: 0;">₱0.00</p>
                            </div>
                            
                            <!-- Match Indicator -->
                            <div id="matchIndicator" style="text-align: center;">
                                <div style="width: 50px; height: 50px; background: #E5E7EB; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-hourglass-half" style="color: #9CA3AF; font-size: 20px;"></i>
                                </div>
                                <p style="font-size: 10px; color: #9CA3AF; margin-top: 4px;">Waiting for<br>image scan</p>
                            </div>
                            
                            <!-- Detected Receipt Amount -->
                            <div style="background: #FEF3C7; border-radius: 10px; padding: 16px; text-align: center;">
                                <p style="font-size: 11px; color: #92400E; margin-bottom: 4px; text-transform: uppercase; font-weight: 600;">
                                    <i class="fas fa-magic"></i> Detected from Receipt
                                </p>
                                <p id="detectedAmount" style="font-size: 24px; font-weight: 700; color: #92400E; margin: 0;">Scanning...</p>
                                <input type="hidden" id="confirmAmount" name="confirm_amount" value="">
                            </div>
                        </div>
                        
                        <!-- Match Result Message -->
                        <div id="amountMatchResult" style="display: none; padding: 14px; border-radius: 10px; text-align: center;">
                            <!-- Updated by JS -->
                        </div>
                    </div>

                    <!-- Confirmation Checkbox -->
                    <div style="margin-top: 20px; background: white; border-radius: 10px; padding: 16px; border: 2px solid #FCD34D;">
                        <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                            <input type="checkbox" name="proof_verified" id="proofVerified" value="1"
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

    <!-- Image Modal for enlarged view -->
    <div id="imageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; justify-content: center; align-items: center; cursor: pointer;" onclick="closeImageModal()">
        <div style="position: relative; max-width: 90%; max-height: 90%;">
            <img id="modalImage" src="" style="max-width: 100%; max-height: 90vh; border-radius: 8px; box-shadow: 0 20px 60px rgba(0,0,0,0.5);">
            <button type="button" onclick="closeImageModal()" style="position: absolute; top: -40px; right: 0; background: white; border: none; border-radius: 50%; width: 36px; height: 36px; cursor: pointer; font-size: 18px;">
                <i class="fas fa-times"></i>
            </button>
            <p style="text-align: center; color: white; margin-top: 12px; font-size: 13px;">
                <i class="fas fa-info-circle"></i> Click anywhere to close
            </p>
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
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .spinner {
        animation: spin 1s linear infinite;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
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
            // For PDF, show error since OCR only works on images
            showOcrError('PDF files cannot be scanned. Please upload an image (JPG/PNG) of your receipt.');
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
                    
                    // Run OCR on the image
                    runOCR(e.target.result);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            icon.className = 'fas fa-file';
        }

        // Show verification section and update details
        updateVerificationDetails();
        updateDisplayFormAmount();
        verificationSection.style.display = 'block';
    }

    // OCR Function to extract amount from image
    async function runOCR(imageData) {
        const ocrStatus = document.getElementById('ocrStatus');
        const detectedAmount = document.getElementById('detectedAmount');
        const matchIndicator = document.getElementById('matchIndicator');
        const manualOverride = document.getElementById('manualOverride');
        
        // Show scanning status
        ocrStatus.style.display = 'block';
        ocrStatus.style.background = '#EFF6FF';
        ocrStatus.style.border = '1px solid #3B82F6';
        ocrStatus.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: center; gap: 12px;">
                <div class="spinner" style="width: 24px; height: 24px; border: 3px solid #3B82F6; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <div>
                    <p style="color: #1D4ED8; font-weight: 600; margin: 0;">Scanning your receipt...</p>
                    <p style="color: #3B82F6; font-size: 12px; margin: 0;">Extracting amount using AI</p>
                </div>
            </div>
        `;
        
        matchIndicator.innerHTML = `
            <div style="width: 50px; height: 50px; background: #3B82F6; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <div class="spinner" style="width: 24px; height: 24px; border: 3px solid white; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
            </div>
            <p style="font-size: 10px; color: #3B82F6; margin-top: 4px;">Scanning...</p>
        `;
        
        detectedAmount.textContent = 'Scanning...';
        
        try {
            // Use Tesseract.js to extract text
            const result = await Tesseract.recognize(imageData, 'eng', {
                logger: m => {
                    if (m.status === 'recognizing text') {
                        const progress = Math.round(m.progress * 100);
                        ocrStatus.innerHTML = `
                            <div style="display: flex; align-items: center; justify-content: center; gap: 12px;">
                                <div class="spinner" style="width: 24px; height: 24px; border: 3px solid #3B82F6; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                                <div>
                                    <p style="color: #1D4ED8; font-weight: 600; margin: 0;">Scanning your receipt... ${progress}%</p>
                                    <p style="color: #3B82F6; font-size: 12px; margin: 0;">Extracting amount using AI</p>
                                </div>
                            </div>
                        `;
                    }
                }
            });
            
            const text = result.data.text;
            console.log('OCR Result:', text);
            
            // Extract amounts from the text
            const amounts = extractAmounts(text);
            console.log('Detected amounts:', amounts);
            
            if (amounts.length > 0) {
                // Find the most likely amount (usually the largest or most prominent)
                const formAmount = parseFloat(document.querySelector('input[name="amount"]').value) || 0;
                
                // Try to find an exact or close match first
                let bestMatch = amounts[0];
                for (const amt of amounts) {
                    if (Math.abs(amt - formAmount) < 0.01) {
                        bestMatch = amt;
                        break;
                    }
                }
                
                // Use the largest amount if no close match (usually the total)
                if (Math.abs(bestMatch - formAmount) >= 0.01) {
                    bestMatch = Math.max(...amounts);
                }
                
                document.getElementById('confirmAmount').value = bestMatch;
                detectedAmount.textContent = '₱' + bestMatch.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                ocrStatus.style.background = '#ECFDF5';
                ocrStatus.style.border = '1px solid #10B981';
                ocrStatus.innerHTML = `
                    <p style="color: #059669; font-weight: 600; margin: 0;">
                        <i class="fas fa-check-circle"></i> Amount detected from receipt!
                    </p>
                `;
                
                // Check if it matches
                checkAmountMatch();
            } else {
                // No amounts found
                showOcrError('Could not detect amount from receipt.');
            }
            
        } catch (error) {
            console.error('OCR Error:', error);
            showOcrError('Error scanning receipt.');
        }
    }
    
    function extractAmounts(text) {
        const amounts = [];
        const totalAmounts = []; // Amounts found near "total" or "amount" keywords - highest priority
        
        // Clean up OCR text (handle line breaks, extra spaces)
        const cleanText = text.replace(/\r\n/g, '\n').replace(/\s+/g, ' ');
        
        // Split into lines for line-by-line analysis
        const lines = text.split(/[\r\n]+/);
        
        console.log('OCR Text:', text);
        console.log('Lines:', lines);
        
        // PRIMARY: Look for lines containing "total" or "amount" keywords
        const primaryKeywords = ['total', 'amount'];
        
        for (const line of lines) {
            const lowerLine = line.toLowerCase();
            
            // Check if line contains "total" or "amount"
            for (const keyword of primaryKeywords) {
                if (lowerLine.includes(keyword)) {
                    console.log(`Found "${keyword}" in line:`, line);
                    
                    // Extract any amount from this line
                    const amountPatterns = [
                        /[₱P]?\s*(\d{1,3}(?:,\d{3})*\.\d{2})/g,  // 1,234.56 or ₱1,234.56
                        /(\d{1,3}(?:,\d{3})*\.\d{2})/g,          // Plain decimal
                        /[₱P]?\s*(\d+\.\d{2})/g,                  // Simple decimal like 500.00
                    ];
                    
                    for (const pattern of amountPatterns) {
                        let match;
                        while ((match = pattern.exec(line)) !== null) {
                            const numStr = match[1].replace(/,/g, '');
                            const num = parseFloat(numStr);
                            if (!isNaN(num) && num > 0 && num < 10000000) {
                                if (!totalAmounts.some(a => Math.abs(a - num) < 0.01)) {
                                    console.log(`Extracted amount from "${keyword}" line:`, num);
                                    totalAmounts.push(num);
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // If we found amounts on lines with "total" or "amount", use those
        if (totalAmounts.length > 0) {
            console.log('Found amounts near total/amount keywords:', totalAmounts);
            return totalAmounts.sort((a, b) => b - a); // Return largest first
        }
        
        // SECONDARY: Try pattern matching for "total: X" or "amount: X" formats
        const keywordPatterns = [
            /total[:\s]+[₱P]?\s*(\d{1,3}(?:,\d{3})*\.\d{2})/gi,
            /amount[:\s]+[₱P]?\s*(\d{1,3}(?:,\d{3})*\.\d{2})/gi,
            /total[:\s]+[₱P]?\s*(\d+\.\d{2})/gi,
            /amount[:\s]+[₱P]?\s*(\d+\.\d{2})/gi,
            /[₱P]\s*(\d{1,3}(?:,\d{3})*\.\d{2})\s*total/gi,
            /[₱P]\s*(\d{1,3}(?:,\d{3})*\.\d{2})\s*amount/gi,
        ];
        
        for (const pattern of keywordPatterns) {
            let match;
            while ((match = pattern.exec(cleanText)) !== null) {
                const numStr = match[1].replace(/,/g, '');
                const num = parseFloat(numStr);
                if (!isNaN(num) && num > 0 && num < 10000000) {
                    if (!totalAmounts.some(a => Math.abs(a - num) < 0.01)) {
                        totalAmounts.push(num);
                    }
                }
            }
        }
        
        if (totalAmounts.length > 0) {
            console.log('Found amounts via pattern matching:', totalAmounts);
            return totalAmounts.sort((a, b) => b - a);
        }
        
        // TERTIARY: Look for amounts with PHP/₱/P currency indicators
        const currencyPatterns = [
            /[₱][\s]*(\d{1,3}(?:,\d{3})*(?:\.\d{2}))/g,
            /PHP[\s]*(\d{1,3}(?:,\d{3})*(?:\.\d{2}))/gi,
            /[P][\s]*(\d{1,3}(?:,\d{3})*\.\d{2})/g,
        ];
        
        for (const pattern of currencyPatterns) {
            let match;
            while ((match = pattern.exec(text)) !== null) {
                const numStr = match[1].replace(/,/g, '');
                const num = parseFloat(numStr);
                if (!isNaN(num) && num > 0 && num < 10000000) {
                    if (!amounts.some(a => Math.abs(a - num) < 0.01)) {
                        amounts.push(num);
                    }
                }
            }
        }
        
        if (amounts.length > 0) {
            console.log('Found currency amounts:', amounts);
            return amounts.sort((a, b) => b - a);
        }
        
        // LAST RESORT: Look for decimal numbers that look like money (X.XX format)
        const decimalPattern = /(\d{1,3}(?:,\d{3})*\.\d{2})/g;
        let match;
        while ((match = decimalPattern.exec(text)) !== null) {
            const numStr = match[1].replace(/,/g, '');
            const num = parseFloat(numStr);
            if (!isNaN(num) && num >= 1 && num < 10000000) {
                if (!amounts.some(a => Math.abs(a - num) < 0.01)) {
                    amounts.push(num);
                }
            }
        }
        
        console.log('Found decimal amounts (fallback):', amounts);
        return amounts.sort((a, b) => b - a);
    }
    
    function showOcrError(message) {
        const ocrStatus = document.getElementById('ocrStatus');
        const detectedAmount = document.getElementById('detectedAmount');
        const matchIndicator = document.getElementById('matchIndicator');
        
        ocrStatus.style.display = 'block';
        ocrStatus.style.background = '#FEE2E2';
        ocrStatus.style.border = '1px solid #EF4444';
        ocrStatus.innerHTML = `
            <p style="color: #DC2626; font-weight: 600; margin: 0;">
                <i class="fas fa-exclamation-circle"></i> ${message}
            </p>
            <p style="color: #B91C1C; font-size: 12px; margin: 4px 0 0 0;">Please upload a clearer image of your receipt.</p>
        `;
        
        matchIndicator.innerHTML = `
            <div style="width: 50px; height: 50px; background: #EF4444; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-exclamation" style="color: white; font-size: 20px;"></i>
            </div>
            <p style="font-size: 10px; color: #DC2626; margin-top: 4px;">Scan failed</p>
        `;
        
        detectedAmount.textContent = 'Not detected';
    }

    function removeFile() {
        fileInput.value = '';
        dropZone.style.display = 'block';
        filePreview.style.display = 'none';
        verificationSection.style.display = 'none';
        document.getElementById('proofVerified').checked = false;
        document.getElementById('confirmAmount').value = '';
        document.getElementById('amountMatchResult').style.display = 'none';
        document.getElementById('ocrStatus').style.display = 'none';
        document.getElementById('detectedAmount').textContent = 'Scanning...';
        // Reset match indicator
        document.getElementById('matchIndicator').innerHTML = `
            <div style="width: 50px; height: 50px; background: #E5E7EB; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-hourglass-half" style="color: #9CA3AF; font-size: 20px;"></i>
            </div>
            <p style="font-size: 10px; color: #9CA3AF; margin-top: 4px;">Waiting for<br>image scan</p>
        `;
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
    document.querySelector('input[name="amount"]').addEventListener('input', function() {
        updateVerificationDetails();
        updateDisplayFormAmount();
        checkAmountMatch();
    });
    document.querySelector('select[name="payment_method"]').addEventListener('change', updateVerificationDetails);
    document.querySelector('input[name="payment_date"]').addEventListener('change', updateVerificationDetails);

    // Amount verification
    const confirmAmountInput = document.getElementById('confirmAmount');
    const matchIndicator = document.getElementById('matchIndicator');
    const amountMatchResult = document.getElementById('amountMatchResult');
    const displayFormAmount = document.getElementById('displayFormAmount');

    // Update the displayed form amount
    function updateDisplayFormAmount() {
        const amount = parseFloat(document.querySelector('input[name="amount"]').value) || 0;
        displayFormAmount.textContent = '₱' + amount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function checkAmountMatch() {
        const originalAmount = parseFloat(document.querySelector('input[name="amount"]').value) || 0;
        const confirmAmount = parseFloat(confirmAmountInput.value) || 0;

        updateDisplayFormAmount();

        if (confirmAmountInput.value === '') {
            // Reset to waiting state
            matchIndicator.innerHTML = `
                <div style="width: 50px; height: 50px; background: #E5E7EB; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-hourglass-half" style="color: #9CA3AF; font-size: 20px;"></i>
                </div>
                <p style="font-size: 10px; color: #9CA3AF; margin-top: 4px;">Waiting for<br>image scan</p>
            `;
            amountMatchResult.style.display = 'none';
            return;
        }

        amountMatchResult.style.display = 'block';

        if (Math.abs(originalAmount - confirmAmount) < 0.01) {
            // Amounts match - SUCCESS
            matchIndicator.innerHTML = `
                <div style="width: 50px; height: 50px; background: #10B981; border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: pulse 1s ease-in-out;">
                    <i class="fas fa-check" style="color: white; font-size: 24px;"></i>
                </div>
                <p style="font-size: 10px; color: #059669; margin-top: 4px; font-weight: 600;">MATCH!</p>
            `;
            amountMatchResult.style.background = '#ECFDF5';
            amountMatchResult.style.border = '2px solid #10B981';
            amountMatchResult.innerHTML = `
                <i class="fas fa-check-circle" style="color: #059669; font-size: 20px;"></i>
                <p style="color: #059669; font-weight: 700; font-size: 14px; margin: 8px 0 0 0;">
                    ✓ Amounts Match! You may proceed with submission.
                </p>
            `;
        } else {
            // Amounts DON'T match - ERROR
            matchIndicator.innerHTML = `
                <div style="width: 50px; height: 50px; background: #EF4444; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times" style="color: white; font-size: 24px;"></i>
                </div>
                <p style="font-size: 10px; color: #DC2626; margin-top: 4px; font-weight: 600;">MISMATCH</p>
            `;
            amountMatchResult.style.background = '#FEE2E2';
            amountMatchResult.style.border = '2px solid #EF4444';
            amountMatchResult.innerHTML = `
                <i class="fas fa-exclamation-triangle" style="color: #DC2626; font-size: 20px;"></i>
                <p style="color: #DC2626; font-weight: 700; font-size: 14px; margin: 8px 0 4px 0;">
                    ✗ Amount Mismatch Detected!
                </p>
                <p style="color: #991B1B; font-size: 13px; margin: 0;">
                    Form amount: <strong>₱${originalAmount.toLocaleString('en-PH', {minimumFractionDigits: 2})}</strong> 
                    &nbsp;≠&nbsp; 
                    Receipt amount: <strong>₱${confirmAmount.toLocaleString('en-PH', {minimumFractionDigits: 2})}</strong>
                </p>
                <p style="color: #B91C1C; font-size: 12px; margin: 8px 0 0 0;">
                    Please correct the amount in the form above, or re-check your receipt.
                </p>
            `;
        }
    }

    // Form validation before submission
    document.querySelector('form').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('fileInput');
        const proofVerified = document.getElementById('proofVerified');
        const confirmAmountInput = document.getElementById('confirmAmount');
        const originalAmount = parseFloat(document.querySelector('input[name="amount"]').value) || 0;
        const confirmAmount = parseFloat(confirmAmountInput.value) || 0;
        
        // Check if file is uploaded
        if (!fileInput.files || fileInput.files.length === 0) {
            e.preventDefault();
            alert('Please upload your payment proof before submitting.');
            return false;
        }

        // Check if confirm amount is entered
        if (confirmAmountInput.value === '') {
            e.preventDefault();
            alert('Please enter the amount shown on your receipt to verify it matches.');
            confirmAmountInput.focus();
            return false;
        }

        // Check if amounts match
        if (Math.abs(originalAmount - confirmAmount) >= 0.01) {
            e.preventDefault();
            alert('The amount on your receipt (₱' + confirmAmount.toLocaleString('en-PH', {minimumFractionDigits: 2}) + ') does not match the amount you entered in the form (₱' + originalAmount.toLocaleString('en-PH', {minimumFractionDigits: 2}) + '). Please correct this before submitting.');
            confirmAmountInput.focus();
            return false;
        }
        
        // Check if verification checkbox is checked
        if (!proofVerified.checked) {
            e.preventDefault();
            alert('Please verify that your payment proof is clear and matches the details you entered.');
            proofVerified.focus();
            return false;
        }
        
        return true;
    });

    // Image modal functions
    function openImageModal() {
        const proofPreview = document.getElementById('proofPreview');
        const modalImage = document.getElementById('modalImage');
        const imageModal = document.getElementById('imageModal');
        
        if (proofPreview.src) {
            modalImage.src = proofPreview.src;
            imageModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeImageModal() {
        const imageModal = document.getElementById('imageModal');
        imageModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
@endpush
