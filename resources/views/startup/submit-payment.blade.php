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

            <!-- Validation Error Alert -->
            @if ($errors->any())
                <div id="validationErrorAlert" style="background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%); border: 2px solid #EF4444; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                    <div style="display: flex; align-items: flex-start; gap: 16px;">
                        <div style="width: 48px; height: 48px; background: #EF4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-exclamation-triangle" style="color: white; font-size: 20px;"></i>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="color: #991B1B; font-size: 16px; font-weight: 700; margin: 0 0 8px 0;">
                                <i class="fas fa-times-circle"></i> Submission Failed
                            </h4>
                            <p style="color: #B91C1C; font-size: 14px; margin: 0 0 12px 0;">
                                Please correct the following errors and try again:
                            </p>
                            <ul style="margin: 0; padding-left: 20px; color: #DC2626; font-size: 13px;">
                                @foreach ($errors->all() as $error)
                                    <li style="margin-bottom: 4px;">{{ $error }}</li>
                                @endforeach
                            </ul>
                            <p style="color: #B91C1C; font-size: 12px; margin: 12px 0 0 0; padding-top: 12px; border-top: 1px solid #FECACA;">
                                <i class="fas fa-info-circle"></i> <strong>Note:</strong> Your other form fields have been preserved. Please re-upload your payment proof file.
                            </p>
                        </div>
                        <button type="button" onclick="document.getElementById('validationErrorAlert').style.display='none'" style="background: none; border: none; color: #991B1B; cursor: pointer; font-size: 18px; padding: 4px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div style="background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); border: 2px solid #10B981; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 48px; height: 48px; background: #10B981; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-check" style="color: white; font-size: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="color: #065F46; font-size: 16px; font-weight: 700; margin: 0;">
                                Success!
                            </h4>
                            <p style="color: #047857; font-size: 14px; margin: 4px 0 0 0;">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form id="paymentForm" action="{{ route('startup.payment.submit') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Reference Number -->
                    <div class="form-group">
                        <label>Reference Number <span>*</span></label>
                        <input type="text" name="invoice_number" class="form-input @error('invoice_number') error @enderror" 
                               placeholder="e.g., REF-2024-001" value="{{ old('invoice_number') }}" required>
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
                    <!-- Hidden input for form submission -->
                    <input type="hidden" name="payment_method" id="paymentMethodInput" value="{{ old('payment_method') }}" required>
                    
                    <!-- Custom Dropdown -->
                    <div class="custom-select-wrapper @error('payment_method') error @enderror" id="paymentMethodDropdown">
                        <div class="custom-select-trigger" onclick="togglePaymentDropdown()">
                            <span class="custom-select-value" id="paymentMethodDisplay">
                                <span style="color: #9CA3AF;">-- Select Payment Method --</span>
                            </span>
                            <i class="fas fa-chevron-down custom-select-arrow"></i>
                        </div>
                        <div class="custom-select-options" id="paymentMethodOptions">
                            <div class="custom-select-option" data-value="bank_transfer" onclick="selectPaymentMethod('bank_transfer', '🏦', 'Bank Transfer')">
                                <span class="option-icon">🏦</span>
                                <span class="option-text">Bank Transfer</span>
                            </div>
                            <div class="custom-select-option" data-value="bank_deposit" onclick="selectPaymentMethod('bank_deposit', '💵', 'Bank Deposit (Over-the-counter)')">
                                <span class="option-icon">💵</span>
                                <span class="option-text">Bank Deposit (Over-the-counter)</span>
                            </div>
                            <div class="custom-select-option" data-value="gcash" onclick="selectPaymentMethod('gcash', 'gcash', 'GCash')">
                                <img src="{{ asset('images/gcashicon.png') }}" alt="GCash" class="option-icon-img">
                                <span class="option-text">GCash</span>
                            </div>
                            <div class="custom-select-option" data-value="maya" onclick="selectPaymentMethod('maya', 'maya', 'Maya (PayMaya)')">
                                <img src="{{ asset('images/mayaIcon.avif') }}" alt="Maya" class="option-icon-img">
                                <span class="option-text">Maya (PayMaya)</span>
                            </div>
                            <div class="custom-select-option" data-value="check" onclick="selectPaymentMethod('check', '📄', 'Check Payment')">
                                <span class="option-icon">📄</span>
                                <span class="option-text">Check Payment</span>
                            </div>
                            <div class="custom-select-option" data-value="cash" onclick="selectPaymentMethod('cash', '💰', 'Cash (In-person)')">
                                <span class="option-icon">💰</span>
                                <span class="option-text">Cash (In-person)</span>
                            </div>
                        </div>
                    </div>
                    @error('payment_method')
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
                                    <span style="color: #6B7280; font-size: 13px;"><i class="fas fa-hashtag" style="width: 16px; color: #9CA3AF;"></i> Ref #:</span>
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

                            </div>
                            
                            <!-- Match Checklist -->
                            <div style="margin-top: 16px; padding: 14px; background: #FEF9C3; border-radius: 8px; border: 1px solid #FDE047;">
                                <p style="font-size: 12px; font-weight: 600; color: #854D0E; margin-bottom: 8px;">
                                    <i class="fas fa-clipboard-check"></i> Please verify these match your receipt:
                                </p>
                                <ul style="font-size: 12px; color: #713F12; margin: 0; padding-left: 18px;">
                                    <li>Amount shown on receipt</li>
                                    <li>Transaction/Reference number</li>
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

                    <!-- OCR Verification Section -->
                    <div style="margin-top: 20px; background: white; border-radius: 10px; padding: 20px; border: 2px solid #F59E0B;">
                        <h5 style="font-size: 14px; font-weight: 700; color: #92400E; margin-bottom: 16px;">
                            <i class="fas fa-robot"></i> Automatic Receipt Verification
                        </h5>
                        
                        <!-- OCR Processing Status -->
                        <div id="ocrStatus" style="display: none; padding: 16px; border-radius: 10px; margin-bottom: 16px; text-align: center;">
                            <!-- Updated by JS -->
                        </div>

                        <!-- Reference Number Verification -->
                        <div style="padding: 16px; background: #F9FAFB; border-radius: 10px; border: 1px solid #E5E7EB;">
                            <h6 style="font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 12px;">
                                <i class="fas fa-hashtag"></i> Reference Number Verification
                            </h6>
                            <p style="font-size: 11px; color: #6B7280; margin-bottom: 12px;">
                                <i class="fas fa-info-circle"></i> The reference number on your receipt must exactly match the reference number you entered.
                            </p>
                            <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 12px; align-items: center;">
                                <div style="background: #F3F4F6; border-radius: 8px; padding: 12px; text-align: center;">
                                    <p style="font-size: 10px; color: #6B7280; margin-bottom: 4px; text-transform: uppercase; font-weight: 600;">You Entered</p>
                                    <p id="displayFormInvoice" style="font-size: 14px; font-weight: 700; color: #1F2937; margin: 0; font-family: monospace;">-</p>
                                </div>
                                <div id="invoiceMatchIndicator" style="text-align: center;">
                                    <div style="width: 36px; height: 36px; background: #E5E7EB; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-hourglass-half" style="color: #9CA3AF; font-size: 14px;"></i>
                                    </div>
                                </div>
                                <div style="background: #FEF3C7; border-radius: 8px; padding: 12px; text-align: center;">
                                    <p style="font-size: 10px; color: #92400E; margin-bottom: 4px; text-transform: uppercase; font-weight: 600;">
                                        <i class="fas fa-magic"></i> Detected from Receipt
                                    </p>
                                    <p id="detectedInvoice" style="font-size: 14px; font-weight: 700; color: #92400E; margin: 0; font-family: monospace;">Scanning...</p>
                                    <input type="hidden" id="confirmInvoice" name="confirm_invoice" value="">
                                </div>
                            </div>
                            <div id="invoiceMatchResult" style="display: none; padding: 10px; border-radius: 8px; text-align: center; margin-top: 12px; font-size: 13px;">
                                <!-- Updated by JS -->
                            </div>
                        </div>

                        <!-- Amount Paid Verification -->
                        <div style="padding: 16px; background: #F0FDF4; border-radius: 10px; border: 1px solid #BBF7D0; margin-top: 12px;">
                            <h6 style="font-size: 12px; font-weight: 600; color: #166534; margin-bottom: 12px;">
                                <i class="fas fa-peso-sign"></i> Amount Paid
                            </h6>
                            <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 12px; align-items: center;">
                                <div style="background: white; border-radius: 8px; padding: 12px; text-align: center; border: 1px solid #BBF7D0;">
                                    <p style="font-size: 10px; color: #6B7280; margin-bottom: 4px; text-transform: uppercase; font-weight: 600;">You Entered</p>
                                    <p id="displayFormAmount" style="font-size: 16px; font-weight: 700; color: #166534; margin: 0;">₱0.00</p>
                                </div>
                                <div id="amountMatchIndicator" style="text-align: center;">
                                    <div style="width: 36px; height: 36px; background: #E5E7EB; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-hourglass-half" style="color: #9CA3AF; font-size: 14px;"></i>
                                    </div>
                                </div>
                                <div style="background: #DCFCE7; border-radius: 8px; padding: 12px; text-align: center; border: 1px solid #BBF7D0;">
                                    <p style="font-size: 10px; color: #166534; margin-bottom: 4px; text-transform: uppercase; font-weight: 600;">
                                        <i class="fas fa-magic"></i> Detected from Receipt
                                    </p>
                                    <p id="detectedAmount" style="font-size: 16px; font-weight: 700; color: #166534; margin: 0;">Scanning...</p>
                                </div>
                            </div>
                            <div id="amountMatchResult" style="display: none; padding: 10px; border-radius: 8px; text-align: center; margin-top: 12px; font-size: 13px;">
                                <!-- Updated by JS -->
                            </div>
                            <input type="hidden" id="confirmAmount" name="confirm_amount" value="">
                        </div>

                        <!-- Payment Method Verification -->
                        <div style="padding: 16px; background: #EFF6FF; border-radius: 10px; border: 1px solid #BFDBFE; margin-top: 12px;">
                            <h6 style="font-size: 12px; font-weight: 600; color: #1E40AF; margin-bottom: 12px;">
                                <i class="fas fa-credit-card"></i> Payment Method
                            </h6>
                            <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 12px; align-items: center;">
                                <div style="background: white; border-radius: 8px; padding: 12px; text-align: center; border: 1px solid #BFDBFE;">
                                    <p style="font-size: 10px; color: #6B7280; margin-bottom: 4px; text-transform: uppercase; font-weight: 600;">You Entered</p>
                                    <p id="displayFormMethod" style="font-size: 14px; font-weight: 700; color: #1E40AF; margin: 0;">-</p>
                                </div>
                                <div id="methodMatchIndicator" style="text-align: center;">
                                    <div style="width: 36px; height: 36px; background: #E5E7EB; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-hourglass-half" style="color: #9CA3AF; font-size: 14px;"></i>
                                    </div>
                                </div>
                                <div style="background: #DBEAFE; border-radius: 8px; padding: 12px; text-align: center; border: 1px solid #BFDBFE;">
                                    <p style="font-size: 10px; color: #1E40AF; margin-bottom: 4px; text-transform: uppercase; font-weight: 600;">
                                        <i class="fas fa-magic"></i> Detected from Receipt
                                    </p>
                                    <p id="detectedMethod" style="font-size: 14px; font-weight: 700; color: #1E40AF; margin: 0;">Scanning...</p>
                                </div>
                            </div>
                            <div id="methodMatchResult" style="display: none; padding: 10px; border-radius: 8px; text-align: center; margin-top: 12px; font-size: 13px;">
                                <!-- Updated by JS -->
                            </div>
                            <input type="hidden" id="confirmMethod" name="confirm_method" value="">
                    </div>

                    <!-- Confirmation Checkbox -->
                    <div id="checkboxContainer" style="margin-top: 20px; background: white; border-radius: 10px; padding: 16px; border: 2px solid #FCD34D; transition: all 0.3s ease;">
                        <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                            <input type="checkbox" name="proof_verified" id="proofVerified" value="1"
                                   style="width: 22px; height: 22px; margin-top: 2px; accent-color: #7B1D3A; transition: all 0.2s ease;"
                                   {{ old('proof_verified') ? 'checked' : '' }}>
                            <div>
                                <span id="checkboxLabel" style="font-weight: 600; color: #1F2937; font-size: 14px; transition: color 0.3s ease;">
                                    I confirm that the payment proof I uploaded is clear, readable, and matches the details I entered above.
                                </span>
                                <p id="checkboxHint" style="font-size: 12px; color: #6B7280; margin-top: 4px; transition: color 0.3s ease;">
                                    <i class="fas fa-info-circle"></i> 
                                    Submissions with unclear or mismatched proofs will be rejected and may delay your verification.
                                </p>
                                <!-- Live validation feedback -->
                                <p id="checkboxFeedback" style="display: none; font-size: 12px; margin-top: 8px; padding: 8px 12px; border-radius: 6px; transition: all 0.3s ease;"></p>
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
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }
        to { 
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Custom Payment Method Dropdown */
    .custom-select-wrapper {
        position: relative;
        width: 100%;
    }

    .custom-select-trigger {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        font-size: 14px;
        background: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s;
    }

    .custom-select-trigger:hover {
        border-color: #CBD5E1;
    }

    .custom-select-wrapper.open .custom-select-trigger {
        border-color: #7B1D3A;
        box-shadow: 0 0 0 4px rgba(123, 29, 58, 0.1);
    }

    .custom-select-wrapper.error .custom-select-trigger {
        border-color: #EF4444;
    }

    .custom-select-value {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .custom-select-arrow {
        color: #6B7280;
        transition: transform 0.3s;
    }

    .custom-select-wrapper.open .custom-select-arrow {
        transform: rotate(180deg);
    }

    .custom-select-options {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        background: white;
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        z-index: 100;
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .custom-select-wrapper.open .custom-select-options {
        max-height: 350px;
        overflow-y: auto;
        opacity: 1;
    }

    .custom-select-option {
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: all 0.2s;
        border-bottom: 1px solid #F3F4F6;
    }

    .custom-select-option:last-child {
        border-bottom: none;
    }

    .custom-select-option:hover {
        background: linear-gradient(135deg, #FDF2F4 0%, #FCE7EB 100%);
    }

    .custom-select-option.selected {
        background: linear-gradient(135deg, #7B1D3A 0%, #9B2C50 100%);
        color: white;
    }

    .custom-select-option.selected .option-text {
        color: white;
    }

    .option-icon {
        font-size: 20px;
        width: 28px;
        text-align: center;
    }

    .option-icon-img {
        height: 24px;
        width: auto;
        max-width: 28px;
        object-fit: contain;
    }

    .option-text {
        font-size: 14px;
        font-weight: 500;
        color: #1F2937;
    }

    .selected-icon-img {
        height: 20px;
        width: auto;
        object-fit: contain;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
<script>
    // Custom Payment Method Dropdown Functions
    const paymentMethodDropdown = document.getElementById('paymentMethodDropdown');
    const paymentMethodInput = document.getElementById('paymentMethodInput');
    const paymentMethodDisplay = document.getElementById('paymentMethodDisplay');
    const paymentMethodOptions = document.getElementById('paymentMethodOptions');

    function togglePaymentDropdown() {
        paymentMethodDropdown.classList.toggle('open');
    }

    function selectPaymentMethod(value, icon, text) {
        paymentMethodInput.value = value;
        
        // Update display based on icon type
        if (icon === 'gcash') {
            paymentMethodDisplay.innerHTML = `
                <img src="/images/gcashicon.png" alt="GCash" class="selected-icon-img">
                <span>${text}</span>
            `;
        } else if (icon === 'maya') {
            paymentMethodDisplay.innerHTML = `
                <img src="/images/mayaIcon.avif" alt="Maya" class="selected-icon-img">
                <span>${text}</span>
            `;
        } else {
            paymentMethodDisplay.innerHTML = `
                <span style="font-size: 18px;">${icon}</span>
                <span>${text}</span>
            `;
        }
        
        // Update selected state
        document.querySelectorAll('.custom-select-option').forEach(opt => {
            opt.classList.remove('selected');
            if (opt.getAttribute('data-value') === value) {
                opt.classList.add('selected');
            }
        });
        
        // Close dropdown
        paymentMethodDropdown.classList.remove('open');
        
        // Remove error state if present
        paymentMethodDropdown.classList.remove('error');
        
        // Update verification details and trigger method match check
        if (typeof updateVerificationDetails === 'function') {
            updateVerificationDetails();
        }
        if (typeof updateDisplayFormMethod === 'function') {
            updateDisplayFormMethod();
        }
        if (typeof checkMethodMatch === 'function') {
            checkMethodMatch();
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!paymentMethodDropdown.contains(e.target)) {
            paymentMethodDropdown.classList.remove('open');
        }
    });

    // Initialize with old value if exists
    document.addEventListener('DOMContentLoaded', function() {
        const oldValue = paymentMethodInput.value;
        if (oldValue) {
            const methodMap = {
                'bank_transfer': { icon: '🏦', text: 'Bank Transfer' },
                'bank_deposit': { icon: '💵', text: 'Bank Deposit (Over-the-counter)' },
                'gcash': { icon: 'gcash', text: 'GCash' },
                'maya': { icon: 'maya', text: 'Maya (PayMaya)' },
                'check': { icon: '📄', text: 'Check Payment' },
                'cash': { icon: '💰', text: 'Cash (In-person)' }
            };
            
            if (methodMap[oldValue]) {
                selectPaymentMethod(oldValue, methodMap[oldValue].icon, methodMap[oldValue].text);
            }
        }
    });

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
                    
                    // Enhance image quality before OCR
                    enhanceImageForOCR(img).then(enhancedImageData => {
                        // Run OCR on the enhanced image
                        runOCR(enhancedImageData);
                    });
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
        updateDisplayFormMethod();
        verificationSection.style.display = 'block';
    }

    // Image Enhancement Function for better OCR accuracy
    async function enhanceImageForOCR(img) {
        return new Promise((resolve) => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            
            // Calculate optimal size - upscale small images for better OCR
            let width = img.width;
            let height = img.height;
            const minDimension = 1500; // Minimum dimension for good OCR
            
            if (width < minDimension && height < minDimension) {
                const scale = minDimension / Math.min(width, height);
                width = Math.round(width * scale);
                height = Math.round(height * scale);
            }
            
            // Limit maximum size to prevent memory issues
            const maxDimension = 3000;
            if (width > maxDimension || height > maxDimension) {
                const scale = maxDimension / Math.max(width, height);
                width = Math.round(width * scale);
                height = Math.round(height * scale);
            }
            
            canvas.width = width;
            canvas.height = height;
            
            // Draw the image with high quality interpolation
            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = 'high';
            ctx.drawImage(img, 0, 0, width, height);
            
            // Get image data for processing
            let imageData = ctx.getImageData(0, 0, width, height);
            let data = imageData.data;
            
            // Step 1: Convert to grayscale (improves OCR significantly)
            for (let i = 0; i < data.length; i += 4) {
                // Use luminosity method for better grayscale
                const gray = Math.round(0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2]);
                data[i] = gray;     // R
                data[i + 1] = gray; // G
                data[i + 2] = gray; // B
                // Alpha stays the same
            }
            
            // Step 2: Increase contrast using histogram stretching
            let min = 255, max = 0;
            for (let i = 0; i < data.length; i += 4) {
                if (data[i] < min) min = data[i];
                if (data[i] > max) max = data[i];
            }
            
            const range = max - min || 1;
            const contrastFactor = 1.3; // Boost contrast
            
            for (let i = 0; i < data.length; i += 4) {
                // Stretch histogram
                let value = ((data[i] - min) / range) * 255;
                
                // Apply additional contrast
                value = ((value - 128) * contrastFactor) + 128;
                
                // Clamp values
                value = Math.max(0, Math.min(255, Math.round(value)));
                
                data[i] = value;
                data[i + 1] = value;
                data[i + 2] = value;
            }
            
            // Step 3: Apply sharpening using unsharp mask
            ctx.putImageData(imageData, 0, 0);
            imageData = ctx.getImageData(0, 0, width, height);
            data = imageData.data;
            
            const sharpenedData = new Uint8ClampedArray(data);
            const sharpenAmount = 0.5;
            
            // Simple sharpen kernel
            for (let y = 1; y < height - 1; y++) {
                for (let x = 1; x < width - 1; x++) {
                    const idx = (y * width + x) * 4;
                    
                    // Get surrounding pixels
                    const top = ((y - 1) * width + x) * 4;
                    const bottom = ((y + 1) * width + x) * 4;
                    const left = (y * width + (x - 1)) * 4;
                    const right = (y * width + (x + 1)) * 4;
                    
                    // Apply sharpening
                    const laplacian = 4 * data[idx] - data[top] - data[bottom] - data[left] - data[right];
                    const sharpened = data[idx] + sharpenAmount * laplacian;
                    
                    sharpenedData[idx] = Math.max(0, Math.min(255, sharpened));
                    sharpenedData[idx + 1] = Math.max(0, Math.min(255, sharpened));
                    sharpenedData[idx + 2] = Math.max(0, Math.min(255, sharpened));
                }
            }
            
            // Copy sharpened data back
            for (let i = 0; i < data.length; i++) {
                data[i] = sharpenedData[i];
            }
            
            // Step 4: Apply adaptive thresholding for cleaner text (optional binarization)
            // This helps with low contrast receipts
            const windowSize = 15;
            const C = 10; // Threshold offset
            
            // Calculate local means using integral image for efficiency
            const integralImage = new Float64Array(width * height);
            
            for (let y = 0; y < height; y++) {
                let rowSum = 0;
                for (let x = 0; x < width; x++) {
                    const idx = (y * width + x) * 4;
                    rowSum += data[idx];
                    
                    if (y === 0) {
                        integralImage[y * width + x] = rowSum;
                    } else {
                        integralImage[y * width + x] = integralImage[(y - 1) * width + x] + rowSum;
                    }
                }
            }
            
            // Apply adaptive threshold
            const halfWindow = Math.floor(windowSize / 2);
            
            for (let y = 0; y < height; y++) {
                for (let x = 0; x < width; x++) {
                    const idx = (y * width + x) * 4;
                    
                    // Calculate window bounds
                    const x1 = Math.max(0, x - halfWindow);
                    const y1 = Math.max(0, y - halfWindow);
                    const x2 = Math.min(width - 1, x + halfWindow);
                    const y2 = Math.min(height - 1, y + halfWindow);
                    
                    // Calculate local mean using integral image
                    const count = (x2 - x1 + 1) * (y2 - y1 + 1);
                    let sum = integralImage[y2 * width + x2];
                    if (x1 > 0) sum -= integralImage[y2 * width + (x1 - 1)];
                    if (y1 > 0) sum -= integralImage[(y1 - 1) * width + x2];
                    if (x1 > 0 && y1 > 0) sum += integralImage[(y1 - 1) * width + (x1 - 1)];
                    
                    const localMean = sum / count;
                    
                    // Apply threshold with some tolerance to preserve gray levels
                    const threshold = localMean - C;
                    const pixelValue = data[idx];
                    
                    // Soft thresholding to preserve some gray levels
                    let newValue;
                    if (pixelValue < threshold - 20) {
                        newValue = 0; // Dark text
                    } else if (pixelValue > threshold + 20) {
                        newValue = 255; // Light background
                    } else {
                        // Keep intermediate values for smoother text edges
                        newValue = Math.round(((pixelValue - (threshold - 20)) / 40) * 255);
                    }
                    
                    data[idx] = newValue;
                    data[idx + 1] = newValue;
                    data[idx + 2] = newValue;
                }
            }
            
            // Put enhanced image back on canvas
            ctx.putImageData(imageData, 0, 0);
            
            // Return as data URL
            const enhancedDataUrl = canvas.toDataURL('image/png', 1.0);
            
            console.log(`Image enhanced: ${img.width}x${img.height} -> ${width}x${height}`);
            
            resolve(enhancedDataUrl);
        });
    }

    // OCR Function to extract ONLY reference number from image
    async function runOCR(imageData) {
        const ocrStatus = document.getElementById('ocrStatus');
        const detectedInvoice = document.getElementById('detectedInvoice');
        const detectedAmount = document.getElementById('detectedAmount');
        const detectedMethod = document.getElementById('detectedMethod');
        const invoiceMatchIndicator = document.getElementById('invoiceMatchIndicator');
        const amountMatchIndicator = document.getElementById('amountMatchIndicator');
        const methodMatchIndicator = document.getElementById('methodMatchIndicator');
        
        // Show scanning status
        ocrStatus.style.display = 'block';
        ocrStatus.style.background = '#EFF6FF';
        ocrStatus.style.border = '1px solid #3B82F6';
        ocrStatus.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: center; gap: 12px;">
                <div class="spinner" style="width: 24px; height: 24px; border: 3px solid #3B82F6; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <div>
                    <p style="color: #1D4ED8; font-weight: 600; margin: 0;">Scanning receipt...</p>
                    <p style="color: #3B82F6; font-size: 12px; margin: 0;">Extracting details using AI</p>
                </div>
            </div>
        `;
        
        // Set all indicators to scanning state
        const scanningIndicator = `
            <div style="width: 36px; height: 36px; background: #3B82F6; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <div class="spinner" style="width: 16px; height: 16px; border: 2px solid white; border-top-color: transparent; border-radius: 50%;"></div>
            </div>
        `;
        invoiceMatchIndicator.innerHTML = scanningIndicator;
        amountMatchIndicator.innerHTML = scanningIndicator;
        methodMatchIndicator.innerHTML = scanningIndicator;
        detectedInvoice.textContent = 'Scanning...';
        detectedAmount.textContent = 'Scanning...';
        detectedMethod.textContent = 'Scanning...';
        
        try {
            // Use Tesseract.js to extract text with optimized settings
            const result = await Tesseract.recognize(imageData, 'eng', {
                logger: m => {
                    if (m.status === 'recognizing text') {
                        const progress = Math.round(m.progress * 100);
                        ocrStatus.innerHTML = `
                            <div style="display: flex; align-items: center; justify-content: center; gap: 12px;">
                                <div class="spinner" style="width: 24px; height: 24px; border: 3px solid #3B82F6; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                                <div>
                                    <p style="color: #1D4ED8; font-weight: 600; margin: 0;">Scanning receipt... ${progress}%</p>
                                    <p style="color: #3B82F6; font-size: 12px; margin: 0;">Looking for payment details</p>
                                </div>
                            </div>
                        `;
                    }
                }
            });
            
            const text = result.data.text;
            console.log('OCR Result:', text);
            
            // Extract reference numbers, amounts, and payment method
            const referenceNumbers = extractInvoiceNumbers(text);
            const amounts = extractAmounts(text);
            const paymentMethod = detectPaymentMethod(text);
            
            console.log('OCR extracted reference numbers:', referenceNumbers);
            console.log('OCR extracted amounts:', amounts);
            console.log('OCR detected payment method:', paymentMethod);
            
            // Process reference numbers - ONLY validation that matters
            if (referenceNumbers.length > 0) {
                const formReference = document.querySelector('input[name="invoice_number"]').value.trim();
                const normalizedFormRef = formReference.toUpperCase().replace(/[^A-Z0-9]/g, '');
                
                let detectedRef = null;
                let exactMatch = false;
                
                // Look for EXACT match first
                for (const ref of referenceNumbers) {
                    const normalizedRef = ref.toUpperCase().replace(/[^A-Z0-9]/g, '');
                    if (normalizedRef === normalizedFormRef) {
                        detectedRef = ref;
                        exactMatch = true;
                        break;
                    }
                }
                
                // If no exact match, use the first detected reference
                if (!detectedRef) {
                    detectedRef = referenceNumbers[0];
                }
                
                document.getElementById('confirmInvoice').value = detectedRef;
                detectedInvoice.textContent = detectedRef;
                
                // Check reference number match
                checkInvoiceMatch();
            } else {
                detectedInvoice.textContent = 'Not found';
                setIndicatorNotFound(invoiceMatchIndicator);
            }
            
            // Process amounts (display only, no validation)
            if (amounts.length > 0) {
                const bestAmount = amounts[0];
                document.getElementById('confirmAmount').value = bestAmount;
                detectedAmount.textContent = '₱' + bestAmount.toLocaleString('en-PH', {minimumFractionDigits: 2});
                checkAmountMatch();
            } else {
                detectedAmount.textContent = 'Not found';
                setIndicatorNotFound(amountMatchIndicator);
            }
            
            // Process payment method (display only, no validation)
            if (paymentMethod) {
                document.getElementById('confirmMethod').value = paymentMethod.value;
                detectedMethod.textContent = paymentMethod.label;
                checkMethodMatch();
            } else {
                detectedMethod.textContent = 'Not found';
                setIndicatorNotFound(methodMatchIndicator);
            }
            
            // Update OCR status based on reference number detection
            if (referenceNumbers.length > 0) {
                ocrStatus.style.background = '#ECFDF5';
                ocrStatus.style.border = '1px solid #10B981';
                ocrStatus.innerHTML = `
                    <p style="color: #059669; font-weight: 600; margin: 0;">
                        <i class="fas fa-check-circle"></i> Receipt scanned successfully!
                    </p>
                `;
            } else {
                showOcrError('Could not detect reference number from receipt. Please ensure the reference number is clearly visible.');
            }
            
        } catch (error) {
            console.error('OCR Error:', error);
            showOcrError('Error scanning receipt.');
        }
    }
    
    function setIndicatorNotFound(indicator) {
        indicator.innerHTML = `
            <div style="width: 36px; height: 36px; background: #9CA3AF; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-question" style="color: white; font-size: 14px;"></i>
            </div>
        `;
    }
    
    // Extract reference numbers from OCR text
    // Extract reference numbers - ONLY look for specific labels:
    // "Reference Number", "Ref. No.", "Ref No.", "Transaction Ref. No."
    function extractInvoiceNumbers(text) {
        const references = [];
        const lines = text.split(/[\r\n]+/);
        
        console.log('Searching for reference numbers in OCR text...');
        
        // ONLY these specific patterns - look for labeled reference numbers
        const allowedPatterns = [
            // "Reference Number", "Reference No.", "Reference No"
            /reference\s*(?:number|no\.?)[\s:]*([A-Z0-9][A-Z0-9\-\_\s]{4,30})/gi,
            // "Ref. No.", "Ref No.", "Ref. No", "Ref No"
            /ref\.?\s*no\.?[\s:]*([A-Z0-9][A-Z0-9\-\_\s]{4,30})/gi,
            // "Transaction Ref. No.", "Transaction Ref No.", "Transaction Reference No."
            /transaction\s*ref(?:erence)?\.?\s*(?:no\.?)?[\s:]*([A-Z0-9][A-Z0-9\-\_\s]{4,30})/gi,
        ];
        
        // Search line by line for better accuracy
        for (const line of lines) {
            const trimmedLine = line.trim();
            if (!trimmedLine) continue;
            
            for (const pattern of allowedPatterns) {
                pattern.lastIndex = 0; // Reset regex state
                let match;
                while ((match = pattern.exec(trimmedLine)) !== null) {
                    let ref = match[1].trim();
                    // Clean up: remove trailing/leading spaces and normalize
                    ref = ref.replace(/\s+/g, '').toUpperCase();
                    
                    // Filter out invalid matches
                    if (ref.length >= 5 && 
                        ref.length <= 30 &&
                        !ref.match(/^\d{1,2}[\-\/]\d{1,2}[\-\/]\d{2,4}$/) && // Not a date
                        !ref.match(/^\d{1,3}(\.\d{2})?$/) && // Not a small number/amount
                        !ref.match(/^0+$/) && // Not all zeros
                        !references.some(r => r.toUpperCase() === ref)) {
                        references.push(ref);
                        console.log('Found reference number:', ref, 'from line:', trimmedLine);
                    }
                }
            }
        }
        
        // If no labeled reference found, try to find it in the full text
        if (references.length === 0) {
            for (const pattern of allowedPatterns) {
                pattern.lastIndex = 0;
                let match;
                while ((match = pattern.exec(text)) !== null) {
                    let ref = match[1].trim().replace(/\s+/g, '').toUpperCase();
                    if (ref.length >= 5 && 
                        ref.length <= 30 &&
                        !ref.match(/^\d{1,2}[\-\/]\d{1,2}[\-\/]\d{2,4}$/) &&
                        !ref.match(/^\d{1,3}(\.\d{2})?$/) &&
                        !ref.match(/^0+$/) &&
                        !references.some(r => r.toUpperCase() === ref)) {
                        references.push(ref);
                        console.log('Found reference number (full text):', ref);
                    }
                }
            }
        }
        
        console.log('Extracted references:', references);
        return references;
    }
    
    // Extract amounts from OCR text
    function extractAmounts(text) {
        const amounts = [];
        const lines = text.split(/[\r\n]+/);
        
        // Look for amounts near keywords like "total", "amount", "paid"
        const amountKeywords = ['total', 'amount', 'paid', 'payment', 'sum'];
        
        for (const line of lines) {
            const lowerLine = line.toLowerCase();
            
            // Check if line contains amount-related keywords
            for (const keyword of amountKeywords) {
                if (lowerLine.includes(keyword)) {
                    // Extract amounts from this line
                    const amountPatterns = [
                        /[₱P]?\s*(\d{1,3}(?:,\d{3})*\.\d{2})/g,
                        /(\d{1,3}(?:,\d{3})*\.\d{2})/g,
                        /[₱P]?\s*(\d+\.\d{2})/g,
                    ];
                    
                    for (const pattern of amountPatterns) {
                        let match;
                        while ((match = pattern.exec(line)) !== null) {
                            const numStr = match[1].replace(/,/g, '');
                            const num = parseFloat(numStr);
                            if (!isNaN(num) && num > 0 && num < 10000000) {
                                if (!amounts.some(a => Math.abs(a - num) < 0.01)) {
                                    amounts.push(num);
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // If no keyword-based amounts found, look for currency amounts
        if (amounts.length === 0) {
            const currencyPatterns = [
                /[₱][\s]*(\d{1,3}(?:,\d{3})*(?:\.\d{2})?)/g,
                /PHP[\s]*(\d{1,3}(?:,\d{3})*(?:\.\d{2})?)/gi,
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
        }
        
        // Last resort: look for decimal numbers
        if (amounts.length === 0) {
            const decimalPattern = /(\d{1,3}(?:,\d{3})*\.\d{2})/g;
            let match;
            while ((match = decimalPattern.exec(text)) !== null) {
                const numStr = match[1].replace(/,/g, '');
                const num = parseFloat(numStr);
                if (!isNaN(num) && num >= 100 && num < 10000000) {
                    if (!amounts.some(a => Math.abs(a - num) < 0.01)) {
                        amounts.push(num);
                    }
                }
            }
        }
        
        return amounts.sort((a, b) => b - a);
    }
    
    // Detect payment method from OCR text
    function detectPaymentMethod(text) {
        const lowerText = text.toLowerCase();
        
        const methodPatterns = [
            { value: 'gcash', label: 'GCash', keywords: ['gcash', 'g-cash', 'globe gcash'] },
            { value: 'maya', label: 'Maya/PayMaya', keywords: ['maya', 'paymaya', 'pay maya'] },
            { value: 'bank_transfer', label: 'Bank Transfer', keywords: ['bank transfer', 'online transfer', 'fund transfer', 'instapay', 'pesonet'] },
            { value: 'bank_deposit', label: 'Bank Deposit', keywords: ['deposit slip', 'cash deposit', 'bank deposit', 'over the counter'] },
            { value: 'check', label: 'Check Payment', keywords: ['check', 'cheque'] },
            { value: 'cash', label: 'Cash', keywords: ['cash payment', 'paid cash'] },
        ];
        
        const bankNames = ['bpi', 'bdo', 'metrobank', 'landbank', 'land bank', 'pnb', 'unionbank', 'union bank', 
                          'security bank', 'rcbc', 'eastwest', 'chinabank', 'china bank', 'psbank'];
        
        let detectedMethod = null;
        let highestScore = 0;
        
        for (const method of methodPatterns) {
            let score = 0;
            for (const keyword of method.keywords) {
                if (lowerText.includes(keyword)) {
                    score += keyword.length;
                }
            }
            if (score > highestScore) {
                highestScore = score;
                detectedMethod = method;
            }
        }
        
        // If no specific method found but bank name detected, assume bank transfer
        if (!detectedMethod) {
            for (const bank of bankNames) {
                if (lowerText.includes(bank)) {
                    return { value: 'bank_transfer', label: 'Bank Transfer' };
                }
            }
        }
        
        return detectedMethod;
    }

    
    function showOcrError(message) {
        const ocrStatus = document.getElementById('ocrStatus');
        const detectedInvoice = document.getElementById('detectedInvoice');
        const detectedAmount = document.getElementById('detectedAmount');
        const detectedMethod = document.getElementById('detectedMethod');
        const invoiceMatchIndicator = document.getElementById('invoiceMatchIndicator');
        const amountMatchIndicator = document.getElementById('amountMatchIndicator');
        const methodMatchIndicator = document.getElementById('methodMatchIndicator');
        
        ocrStatus.style.display = 'block';
        ocrStatus.style.background = '#FEE2E2';
        ocrStatus.style.border = '1px solid #EF4444';
        ocrStatus.innerHTML = `
            <p style="color: #DC2626; font-weight: 600; margin: 0;">
                <i class="fas fa-exclamation-circle"></i> ${message}
            </p>
            <p style="color: #B91C1C; font-size: 12px; margin: 4px 0 0 0;">Please upload a clearer image of your receipt with a visible reference number.</p>
        `;
        
        const errorIndicator = `
            <div style="width: 36px; height: 36px; background: #EF4444; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-exclamation" style="color: white; font-size: 14px;"></i>
            </div>
        `;
        
        invoiceMatchIndicator.innerHTML = errorIndicator;
        detectedInvoice.textContent = 'Not detected';
        
        // Set amount and method to not found state
        const notFoundIndicator = `
            <div style="width: 36px; height: 36px; background: #9CA3AF; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-question" style="color: white; font-size: 14px;"></i>
            </div>
        `;
        amountMatchIndicator.innerHTML = notFoundIndicator;
        methodMatchIndicator.innerHTML = notFoundIndicator;
        detectedAmount.textContent = 'Not detected';
        detectedMethod.textContent = 'Not detected';
    }
    
    // Check reference number match - EXACT MATCH REQUIRED
    function checkInvoiceMatch() {
        const formInvoice = document.querySelector('input[name="invoice_number"]').value.trim();
        const confirmInvoice = document.getElementById('confirmInvoice').value.trim();
        const invoiceMatchIndicator = document.getElementById('invoiceMatchIndicator');
        const invoiceMatchResult = document.getElementById('invoiceMatchResult');
        const displayFormInvoice = document.getElementById('displayFormInvoice');
        
        displayFormInvoice.textContent = formInvoice || '-';
        
        if (!confirmInvoice || confirmInvoice === '') {
            invoiceMatchResult.style.display = 'none';
            return;
        }
        
        // Normalize for comparison - EXACT MATCH ONLY
        const normalizedForm = formInvoice.toUpperCase().replace(/[^A-Z0-9]/g, '');
        const normalizedConfirm = confirmInvoice.toUpperCase().replace(/[^A-Z0-9]/g, '');
        
        invoiceMatchResult.style.display = 'block';
        
        // EXACT MATCH REQUIRED - reference numbers must match exactly
        const isExactMatch = normalizedForm === normalizedConfirm;
        
        if (isExactMatch) {
            invoiceMatchIndicator.innerHTML = `
                <div style="width: 36px; height: 36px; background: #10B981; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check" style="color: white; font-size: 14px;"></i>
                </div>
            `;
            invoiceMatchResult.style.background = '#ECFDF5';
            invoiceMatchResult.style.border = '1px solid #10B981';
            invoiceMatchResult.innerHTML = `<span style="color: #059669;"><i class="fas fa-check-circle"></i> Reference number matches!</span>`;
        } else {
            invoiceMatchIndicator.innerHTML = `
                <div style="width: 36px; height: 36px; background: #F59E0B; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-exclamation" style="color: white; font-size: 14px;"></i>
                </div>
            `;
            invoiceMatchResult.style.background = '#FEF3C7';
            invoiceMatchResult.style.border = '1px solid #F59E0B';
            invoiceMatchResult.innerHTML = `<span style="color: #B45309;"><i class="fas fa-exclamation-triangle"></i> Reference number doesn't match. Please verify.</span>`;
        }
    }
    
    // Check amount match (display only, no blocking validation)
    function checkAmountMatch() {
        const formAmount = parseFloat(document.querySelector('input[name="amount"]').value) || 0;
        const confirmAmount = parseFloat(document.getElementById('confirmAmount').value) || 0;
        const amountMatchIndicator = document.getElementById('amountMatchIndicator');
        const amountMatchResult = document.getElementById('amountMatchResult');
        
        if (!confirmAmount || confirmAmount === 0) {
            amountMatchResult.style.display = 'none';
            return;
        }
        
        amountMatchResult.style.display = 'block';
        
        // Check if amounts are close (within 1 peso tolerance)
        const isMatch = Math.abs(formAmount - confirmAmount) < 1;
        
        if (isMatch) {
            amountMatchIndicator.innerHTML = `
                <div style="width: 36px; height: 36px; background: #10B981; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check" style="color: white; font-size: 14px;"></i>
                </div>
            `;
            amountMatchResult.style.background = '#ECFDF5';
            amountMatchResult.style.border = '1px solid #10B981';
            amountMatchResult.innerHTML = `<span style="color: #059669;"><i class="fas fa-check-circle"></i> Amount matches!</span>`;
        } else {
            amountMatchIndicator.innerHTML = `
                <div style="width: 36px; height: 36px; background: #F59E0B; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-exclamation" style="color: white; font-size: 14px;"></i>
                </div>
            `;
            amountMatchResult.style.background = '#FEF3C7';
            amountMatchResult.style.border = '1px solid #F59E0B';
            amountMatchResult.innerHTML = `<span style="color: #B45309;"><i class="fas fa-exclamation-triangle"></i> Amount differs. Form: ₱${formAmount.toLocaleString('en-PH', {minimumFractionDigits: 2})} | Receipt: ₱${confirmAmount.toLocaleString('en-PH', {minimumFractionDigits: 2})}</span>`;
        }
    }
    
    // Check payment method match (display only, no blocking validation)
    function checkMethodMatch() {
        const formMethod = document.getElementById('paymentMethodInput').value;
        const confirmMethod = document.getElementById('confirmMethod').value;
        const methodMatchIndicator = document.getElementById('methodMatchIndicator');
        const methodMatchResult = document.getElementById('methodMatchResult');
        
        if (!confirmMethod || confirmMethod === '') {
            methodMatchResult.style.display = 'none';
            return;
        }
        
        methodMatchResult.style.display = 'block';
        
        // Check if methods match (allow some flexibility)
        const similarMethods = {
            'bank_transfer': ['bank_transfer', 'bank_deposit'],
            'bank_deposit': ['bank_transfer', 'bank_deposit'],
            'gcash': ['gcash'],
            'maya': ['maya'],
            'check': ['check'],
            'cash': ['cash']
        };
        
        const isMatch = formMethod === confirmMethod || 
                       (similarMethods[formMethod] && similarMethods[formMethod].includes(confirmMethod));
        
        if (isMatch) {
            methodMatchIndicator.innerHTML = `
                <div style="width: 36px; height: 36px; background: #10B981; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check" style="color: white; font-size: 14px;"></i>
                </div>
            `;
            methodMatchResult.style.background = '#ECFDF5';
            methodMatchResult.style.border = '1px solid #10B981';
            methodMatchResult.innerHTML = `<span style="color: #059669;"><i class="fas fa-check-circle"></i> Payment method matches!</span>`;
        } else {
            methodMatchIndicator.innerHTML = `
                <div style="width: 36px; height: 36px; background: #F59E0B; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-exclamation" style="color: white; font-size: 14px;"></i>
                </div>
            `;
            methodMatchResult.style.background = '#FEF3C7';
            methodMatchResult.style.border = '1px solid #F59E0B';
            methodMatchResult.innerHTML = `<span style="color: #B45309;"><i class="fas fa-exclamation-triangle"></i> Payment method differs. Please verify.</span>`;
        }
    }


    function removeFile() {
        fileInput.value = '';
        dropZone.style.display = 'block';
        filePreview.style.display = 'none';
        verificationSection.style.display = 'none';
        document.getElementById('proofVerified').checked = false;
        
        // Reset all confirm values
        document.getElementById('confirmAmount').value = '';
        document.getElementById('confirmInvoice').value = '';
        document.getElementById('confirmMethod').value = '';
        
        // Reset all result displays
        document.getElementById('invoiceMatchResult').style.display = 'none';
        document.getElementById('amountMatchResult').style.display = 'none';
        document.getElementById('methodMatchResult').style.display = 'none';
        document.getElementById('ocrStatus').style.display = 'none';
        
        // Reset all detected values
        document.getElementById('detectedInvoice').textContent = 'Scanning...';
        document.getElementById('detectedAmount').textContent = 'Scanning...';
        document.getElementById('detectedMethod').textContent = 'Scanning...';
        
        // Reset all match indicators
        const waitingIndicator = `
            <div style="width: 36px; height: 36px; background: #E5E7EB; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-hourglass-half" style="color: #9CA3AF; font-size: 14px;"></i>
            </div>
        `;
        document.getElementById('invoiceMatchIndicator').innerHTML = waitingIndicator;
        document.getElementById('amountMatchIndicator').innerHTML = waitingIndicator;
        document.getElementById('methodMatchIndicator').innerHTML = waitingIndicator;
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
        const methodValue = document.getElementById('paymentMethodInput').value;
        const methodLabels = {
            'bank_transfer': 'Bank Transfer',
            'bank_deposit': 'Bank Deposit',
            'gcash': 'GCash',
            'maya': 'Maya',
            'check': 'Check Payment',
            'cash': 'Cash'
        };
        const method = methodLabels[methodValue] || '-';

        document.getElementById('verifyInvoice').textContent = invoice;
        document.getElementById('verifyAmount').textContent = '₱' + parseFloat(amount).toLocaleString('en-PH', {minimumFractionDigits: 2});
        document.getElementById('verifyMethod').textContent = method;
    }

    // Update verification details when form fields change
    document.querySelector('input[name="invoice_number"]').addEventListener('input', function() {
        updateVerificationDetails();
        updateDisplayFormInvoice();
        checkInvoiceMatch();
    });
    document.querySelector('input[name="amount"]').addEventListener('input', function() {
        updateVerificationDetails();
        updateDisplayFormAmount();
    });
    // Note: Payment method change is handled in selectPaymentMethod function
    // which calls updateVerificationDetails() and updateDisplayFormMethod()
    
    // Update display functions
    function updateDisplayFormInvoice() {
        const invoice = document.querySelector('input[name="invoice_number"]').value || '-';
        document.getElementById('displayFormInvoice').textContent = invoice;
    }
    
    function updateDisplayFormAmount() {
        const amount = parseFloat(document.querySelector('input[name="amount"]').value) || 0;
        document.getElementById('displayFormAmount').textContent = '₱' + amount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
    
    function updateDisplayFormMethod() {
        const methodValue = document.getElementById('paymentMethodInput').value;
        const methodLabels = {
            'bank_transfer': 'Bank Transfer',
            'bank_deposit': 'Bank Deposit',
            'gcash': 'GCash',
            'maya': 'Maya',
            'check': 'Check Payment',
            'cash': 'Cash'
        };
        const method = methodLabels[methodValue] || '-';
        document.getElementById('displayFormMethod').textContent = method;
    }

    // ============ LIVE VALIDATION SYSTEM ============
    
    // Track if user has attempted to submit (to show validation after first attempt)
    let hasAttemptedSubmit = false;
    
    // Live checkbox validation with visual feedback
    document.getElementById('proofVerified').addEventListener('change', function() {
        updateCheckboxValidation();
        // Close any existing error popup when checkbox is checked
        if (this.checked) {
            closeMismatchPopup();
        }
    });
    
    function updateCheckboxValidation() {
        const checkbox = document.getElementById('proofVerified');
        const container = document.getElementById('checkboxContainer');
        const label = document.getElementById('checkboxLabel');
        const hint = document.getElementById('checkboxHint');
        const feedback = document.getElementById('checkboxFeedback');
        
        if (checkbox.checked) {
            // Valid state - green styling
            container.style.background = 'linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%)';
            container.style.border = '2px solid #10B981';
            label.style.color = '#065F46';
            hint.style.color = '#059669';
            feedback.style.display = 'block';
            feedback.style.background = '#D1FAE5';
            feedback.style.color = '#065F46';
            feedback.innerHTML = '<i class="fas fa-check-circle"></i> Great! You\'re ready to submit.';
        } else if (hasAttemptedSubmit) {
            // Invalid state after submit attempt - red styling
            container.style.background = 'linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%)';
            container.style.border = '2px solid #EF4444';
            label.style.color = '#991B1B';
            hint.style.color = '#B91C1C';
            feedback.style.display = 'block';
            feedback.style.background = '#FECACA';
            feedback.style.color = '#991B1B';
            feedback.innerHTML = '<i class="fas fa-exclamation-circle"></i> Please check this box to continue.';
        } else {
            // Default state - yellow styling
            container.style.background = 'white';
            container.style.border = '2px solid #FCD34D';
            label.style.color = '#1F2937';
            hint.style.color = '#6B7280';
            feedback.style.display = 'none';
        }
    }
    
    // Live validation for required fields
    function validateFieldLive(input, fieldName) {
        const value = input.value.trim();
        const formGroup = input.closest('.form-group');
        
        if (!formGroup) return;
        
        // Remove existing live error
        const existingError = formGroup.querySelector('.live-error');
        if (existingError) existingError.remove();
        
        if (hasAttemptedSubmit && !value) {
            input.style.borderColor = '#EF4444';
            input.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
            const error = document.createElement('div');
            error.className = 'live-error';
            error.style.cssText = 'color: #DC2626; font-size: 12px; margin-top: 4px; display: flex; align-items: center; gap: 4px;';
            error.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${fieldName} is required`;
            formGroup.appendChild(error);
        } else if (value) {
            input.style.borderColor = '#10B981';
            input.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.1)';
        } else {
            input.style.borderColor = '';
            input.style.boxShadow = '';
        }
    }
    
    // Add live validation to form fields
    document.querySelector('input[name="invoice_number"]').addEventListener('input', function() {
        validateFieldLive(this, 'Reference Number');
        checkInvoiceMatch(); // Also update OCR match in real-time
    });
    
    document.querySelector('input[name="amount"]').addEventListener('input', function() {
        validateFieldLive(this, 'Amount');
        checkAmountMatch(); // Also update OCR match in real-time
    });
    
    // Payment method validation is handled in selectPaymentMethod function
    
    // Flag to track if we're doing a validated submit
    let isValidatedSubmit = false;
    
    // Form validation before submission - validates ALL fields to prevent page reload
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        // If this is a validated submit, allow it to proceed
        if (isValidatedSubmit) {
            return true;
        }
        
        // ALWAYS prevent default first, then decide to submit manually
        e.preventDefault();
        
        // Mark that user has attempted to submit - enables live validation feedback
        hasAttemptedSubmit = true;
        
        const fileInput = document.getElementById('fileInput');
        const proofVerified = document.getElementById('proofVerified');
        const confirmInvoiceInput = document.getElementById('confirmInvoice');
        
        // Get all form values
        const formInvoice = document.querySelector('input[name="invoice_number"]').value.trim();
        const formAmount = document.querySelector('input[name="amount"]').value.trim();
        const formMethod = document.getElementById('paymentMethodInput').value;
        const formNotes = document.querySelector('textarea[name="notes"]').value;
        const confirmInvoice = confirmInvoiceInput.value.trim();
        
        // Collect all validation errors
        const errors = [];
        
        // === VALIDATE REFERENCE NUMBER ===
        if (!formInvoice) {
            errors.push({ field: 'Reference Number', message: 'Reference Number is required.' });
        } else if (formInvoice.length > 100) {
            errors.push({ field: 'Reference Number', message: 'Reference Number must not exceed 100 characters.' });
        }
        
        // === VALIDATE AMOUNT ===
        if (!formAmount) {
            errors.push({ field: 'Amount Paid', message: 'Amount Paid is required.' });
        } else {
            const amountNum = parseFloat(formAmount);
            if (isNaN(amountNum)) {
                errors.push({ field: 'Amount Paid', message: 'Amount Paid must be a valid number.' });
            } else if (amountNum <= 0) {
                errors.push({ field: 'Amount Paid', message: 'Amount Paid must be greater than zero.' });
            }
        }
        
        // === VALIDATE PAYMENT METHOD ===
        if (!formMethod) {
            errors.push({ field: 'Payment Method', message: 'Please select a Payment Method.' });
        }
        
        // === VALIDATE NOTES LENGTH ===
        if (formNotes && formNotes.length > 1000) {
            errors.push({ field: 'Notes', message: 'Notes must not exceed 1000 characters.' });
        }
        
        // === VALIDATE FILE UPLOAD ===
        if (!fileInput.files || fileInput.files.length === 0) {
            errors.push({ field: 'Payment Proof', message: 'Please upload your payment proof.' });
        } else {
            const file = fileInput.files[0];
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!allowedTypes.includes(file.type)) {
                errors.push({ field: 'Payment Proof', message: 'File must be JPG, PNG, or PDF format.' });
            }
            if (file.size > maxSize) {
                errors.push({ field: 'Payment Proof', message: 'File size must not exceed 5MB.' });
            }
        }
        
        // === CHECK VERIFICATION CHECKBOX EARLY - This is critical to prevent page reload ===
        if (!proofVerified.checked) {
            errors.push({ field: 'Confirmation', message: 'Please check the confirmation box to verify your payment proof is clear and readable.' });
        }
        
        // If there are basic validation errors, show them and stop
        if (errors.length > 0) {
            // Update live validation UI for all fields
            updateCheckboxValidation();
            validateFieldLive(document.querySelector('input[name="invoice_number"]'), 'Reference Number');
            validateFieldLive(document.querySelector('input[name="amount"]'), 'Amount');
            
            showValidationErrorsPopup(errors);
            return false;
        }

        // === CHECK OCR COMPLETION (only for image files, not PDFs) ===
        const fileUploaded = fileInput.files && fileInput.files.length > 0;
        const isPDF = fileUploaded && fileInput.files[0].type === 'application/pdf';
        const ocrCompleted = confirmInvoice && confirmInvoice !== '' && confirmInvoice !== 'Not found' && confirmInvoice !== 'Not detected';
        
        // Only check OCR for images (PDFs can't be scanned)
        if (!isPDF && !ocrCompleted) {
            showMismatchPopup(
                'Reference Number Not Detected', 
                'Please wait for the receipt to be scanned, or upload a clearer image. We need to detect and verify the reference number from your receipt.', 
                'warning'
            );
            return false;
        }

        // === REFERENCE NUMBER VERIFICATION - EXACT MATCH REQUIRED (skip for PDFs since OCR doesn't work) ===
        if (!isPDF) {
            const normalizedForm = formInvoice.toUpperCase().replace(/[^A-Z0-9]/g, '');
            const normalizedConfirm = confirmInvoice.toUpperCase().replace(/[^A-Z0-9]/g, '');
            
            // Collect all mismatches
            const mismatches = [];
            
            // Check reference number match
            if (normalizedForm !== normalizedConfirm) {
                mismatches.push({
                    field: 'Reference Number',
                    entered: formInvoice || '(empty)',
                    detected: confirmInvoice,
                    icon: 'fa-hashtag'
                });
            }
            
            // Check amount match (if amount was detected)
            const confirmAmountValue = document.getElementById('confirmAmount').value;
            if (confirmAmountValue && confirmAmountValue !== '') {
                const formAmountNum = parseFloat(formAmount) || 0;
                const confirmAmountNum = parseFloat(confirmAmountValue) || 0;
                
                // Amount must match within 1 peso tolerance
                if (Math.abs(formAmountNum - confirmAmountNum) >= 1) {
                    mismatches.push({
                        field: 'Amount Paid',
                        entered: '₱' + formAmountNum.toLocaleString('en-PH', {minimumFractionDigits: 2}),
                        detected: '₱' + confirmAmountNum.toLocaleString('en-PH', {minimumFractionDigits: 2}),
                        icon: 'fa-peso-sign'
                    });
                }
            }
            
            // Check payment method match (if method was detected)
            const confirmMethodValue = document.getElementById('confirmMethod').value;
            if (confirmMethodValue && confirmMethodValue !== '') {
                // Allow similar methods (bank_transfer and bank_deposit are considered compatible)
                const similarMethods = {
                    'bank_transfer': ['bank_transfer', 'bank_deposit'],
                    'bank_deposit': ['bank_transfer', 'bank_deposit'],
                    'gcash': ['gcash'],
                    'maya': ['maya'],
                    'check': ['check'],
                    'cash': ['cash']
                };
                
                const isMethodMatch = formMethod === confirmMethodValue || 
                    (similarMethods[formMethod] && similarMethods[formMethod].includes(confirmMethodValue));
                
                if (!isMethodMatch) {
                    const methodLabels = {
                        'bank_transfer': 'Bank Transfer',
                        'bank_deposit': 'Bank Deposit',
                        'gcash': 'GCash',
                        'maya': 'Maya/PayMaya',
                        'check': 'Check',
                        'cash': 'Cash'
                    };
                    mismatches.push({
                        field: 'Payment Method',
                        entered: methodLabels[formMethod] || formMethod,
                        detected: methodLabels[confirmMethodValue] || confirmMethodValue,
                        icon: 'fa-credit-card'
                    });
                }
            }
            
            // If there are any mismatches, block submission
            if (mismatches.length > 0) {
                const title = mismatches.length === 1 
                    ? `${mismatches[0].field} Mismatch!`
                    : 'Payment Details Mismatch!';
                const message = mismatches.length === 1
                    ? `Your payment cannot be submitted because the ${mismatches[0].field.toLowerCase()} does not match:`
                    : 'Your payment cannot be submitted because the following details do not match your receipt:';
                
                showMismatchPopup(title, message, 'error', mismatches, []);
                return false;
            }
        }
        
        // All validations passed - set flag and submit
        isValidatedSubmit = true;
        this.submit();
    });
    
    // Show validation errors in a popup
    function showValidationErrorsPopup(errors) {
        const existingPopup = document.getElementById('mismatchPopup');
        if (existingPopup) {
            existingPopup.remove();
        }
        
        const errorListHTML = errors.map(err => 
            `<li style="margin-bottom: 8px;"><strong>${err.field}:</strong> ${err.message}</li>`
        ).join('');
        
        const popup = document.createElement('div');
        popup.id = 'mismatchPopup';
        popup.innerHTML = `
            <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; display: flex; align-items: center; justify-content: center; animation: fadeIn 0.2s ease-out;">
                <div style="background: white; border-radius: 16px; max-width: 500px; width: 90%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: slideUp 0.3s ease-out; overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%); border-bottom: 2px solid #EF4444; padding: 20px 24px; display: flex; align-items: center; gap: 16px;">
                        <div style="width: 52px; height: 52px; background: #EF4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-exclamation-triangle" style="color: white; font-size: 24px;"></i>
                        </div>
                        <div>
                            <h3 style="color: #991B1B; font-size: 18px; font-weight: 700; margin: 0;">Please Fix These Errors</h3>
                            <p style="color: #B91C1C; font-size: 13px; margin: 4px 0 0 0;">Your form could not be submitted</p>
                        </div>
                    </div>
                    <div style="padding: 20px 24px;">
                        <ul style="margin: 0; padding-left: 20px; color: #DC2626; font-size: 14px; line-height: 1.6;">
                            ${errorListHTML}
                        </ul>
                        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                            <button type="button" onclick="closeMismatchPopup()" style="background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">
                                <i class="fas fa-edit"></i> I'll Fix These
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(popup);
        
        popup.querySelector('div').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMismatchPopup();
            }
        });
        
        document.addEventListener('keydown', function closeOnEscape(e) {
            if (e.key === 'Escape') {
                closeMismatchPopup();
                document.removeEventListener('keydown', closeOnEscape);
            }
        });
    }
    
    // Custom popup notification for mismatches
    function showMismatchPopup(title, message, type, mismatches = [], notDetected = []) {
        // Remove existing popup if any
        const existingPopup = document.getElementById('mismatchPopup');
        if (existingPopup) {
            existingPopup.remove();
        }
        
        const bgColor = type === 'error' ? '#FEE2E2' : '#FEF3C7';
        const borderColor = type === 'error' ? '#EF4444' : '#F59E0B';
        const iconColor = type === 'error' ? '#DC2626' : '#D97706';
        const icon = type === 'error' ? 'fa-times-circle' : 'fa-exclamation-triangle';
        const titleColor = type === 'error' ? '#991B1B' : '#92400E';
        const textColor = type === 'error' ? '#B91C1C' : '#B45309';
        
        let mismatchHTML = '';
        if (mismatches.length > 0) {
            mismatchHTML = `
                <div style="margin-top: 16px; background: white; border-radius: 8px; overflow: hidden; border: 1px solid ${borderColor};">
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <thead>
                            <tr style="background: ${bgColor};">
                                <th style="padding: 10px 12px; text-align: left; color: ${titleColor}; font-weight: 600;">Field</th>
                                <th style="padding: 10px 12px; text-align: left; color: ${titleColor}; font-weight: 600;">You Entered</th>
                                <th style="padding: 10px 12px; text-align: left; color: ${titleColor}; font-weight: 600;">Detected from Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${mismatches.map(m => `
                                <tr style="border-top: 1px solid #E5E7EB;">
                                    <td style="padding: 10px 12px; font-weight: 500; color: #374151;">
                                        <i class="fas ${m.icon || 'fa-info-circle'}" style="color: #6B7280; margin-right: 6px;"></i>${m.field}
                                    </td>
                                    <td style="padding: 10px 12px; color: #DC2626; font-family: monospace; background: #FEF2F2;">
                                        <i class="fas fa-times" style="margin-right: 4px;"></i>${m.entered}
                                    </td>
                                    <td style="padding: 10px 12px; color: #059669; font-family: monospace; background: #ECFDF5;">
                                        <i class="fas fa-check" style="margin-right: 4px;"></i>${m.detected}
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }
        
        let notDetectedHTML = '';
        if (notDetected.length > 0) {
            notDetectedHTML = `
                <div style="margin-top: 12px; padding: 12px; background: #F3F4F6; border-radius: 8px; border: 1px solid #D1D5DB;">
                    <p style="font-size: 12px; color: #4B5563; margin: 0;">
                        <i class="fas fa-question-circle" style="color: #9CA3AF;"></i> 
                        <strong>Could not automatically detect:</strong> ${notDetected.join(', ')}
                    </p>
                </div>
            `;
        }
        
        const actionText = type === 'error' 
            ? '<i class="fas fa-edit"></i> I\'ll Correct the Details' 
            : '<i class="fas fa-check"></i> I Understand';
        
        const instructionText = type === 'error'
            ? `<p style="margin-top: 12px; font-size: 12px; color: #6B7280;">
                   <i class="fas fa-info-circle"></i> <strong>To submit your payment:</strong> Please correct the mismatched fields in the form to match what appears on your receipt, or upload a different receipt that matches your entered details.
               </p>`
            : '';
        
        const popup = document.createElement('div');
        popup.id = 'mismatchPopup';
        popup.innerHTML = `
            <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; display: flex; align-items: center; justify-content: center; animation: fadeIn 0.2s ease-out;">
                <div style="background: white; border-radius: 16px; max-width: 560px; width: 90%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: slideUp 0.3s ease-out; overflow: hidden;">
                    <!-- Header -->
                    <div style="background: ${bgColor}; border-bottom: 2px solid ${borderColor}; padding: 20px 24px; display: flex; align-items: center; gap: 16px;">
                        <div style="width: 52px; height: 52px; background: ${borderColor}; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas ${icon}" style="color: white; font-size: 26px;"></i>
                        </div>
                        <div>
                            <h3 style="margin: 0; color: ${titleColor}; font-size: 18px; font-weight: 700;">${title}</h3>
                            <p style="margin: 4px 0 0 0; color: ${textColor}; font-size: 14px;">${message}</p>
                        </div>
                    </div>
                    
                    <!-- Body -->
                    <div style="padding: 20px 24px; max-height: 400px; overflow-y: auto;">
                        ${mismatchHTML}
                        ${notDetectedHTML}
                        ${instructionText}
                        
                        <!-- Action Button -->
                        <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 12px;">
                            <button type="button" onclick="closeMismatchPopup()" style="padding: 12px 24px; background: ${borderColor}; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                                ${actionText}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(popup);
        
        // Close on backdrop click
        popup.querySelector('div').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMismatchPopup();
            }
        });
        
        // Close on Escape key
        document.addEventListener('keydown', function closeOnEscape(e) {
            if (e.key === 'Escape') {
                closeMismatchPopup();
                document.removeEventListener('keydown', closeOnEscape);
            }
        });
    }
    
    function closeMismatchPopup() {
        const popup = document.getElementById('mismatchPopup');
        if (popup) {
            popup.style.opacity = '0';
            setTimeout(() => popup.remove(), 200);
        }
    }

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
