<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/upLogo.png') }}">
    <title>Startup Portal - UP Cebu Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F3F4F6; min-height: 100vh; }

        /* Color Variables */
        :root {
            --maroon: #7B1D3A;
            --maroon-dark: #5a1428;
            --gold: #FFBF00;
            --gold-dark: #D4A500;
            --forest-green: #228B22;
            --forest-green-dark: #1B6B1B;
            --white: #FFFFFF;
        }

        /* Hero Section */
        .portal-hero {
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            min-height: 35vh;
            display: flex;
            align-items: center;
            padding-top: 60px;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(123, 29, 58, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
            font-weight: 700;
            font-size: 18px;
        }
        .navbar-brand img { height: 40px; }
        .navbar-back {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            opacity: 0.9;
            transition: opacity 0.3s;
        }
        .navbar-back:hover { opacity: 1; }

        /* Action Cards */
        .action-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
            text-align: center;
        }
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            border-color: var(--gold);
        }
        .action-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 28px;
            color: var(--maroon);
        }
        .action-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--maroon);
            margin-bottom: 8px;
        }
        .action-card p {
            color: #6B7280;
            font-size: 14px;
        }

        /* Tracking Section */
        .tracking-section {
            background: linear-gradient(135deg, rgba(123, 29, 58, 0.05) 0%, rgba(255, 191, 0, 0.1) 100%);
            border: 2px solid rgba(123, 29, 58, 0.15);
            border-radius: 16px;
            padding: 28px;
        }
        .tracking-input {
            display: flex;
            gap: 12px;
        }
        .tracking-input input {
            flex: 1;
            padding: 14px 18px;
            border: 2px solid #E5E7EB;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
        }
        .tracking-input input:focus {
            outline: none;
            border-color: var(--maroon);
            box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1);
        }
        .tracking-result {
            margin-top: 20px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            display: none;
        }
        .tracking-result.show { display: block; }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }
        .modal-overlay.active { display: flex; }
        .modal-content {
            background: white;
            border-radius: 20px;
            max-width: 550px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        }
        @keyframes modalSlideIn {
            from { opacity: 0; transform: translateY(-30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .modal-header {
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            padding: 24px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 20px 20px 0 0;
        }
        .modal-header h2 { font-size: 20px; font-weight: 700; margin: 0; }
        .modal-close {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        .modal-close:hover { background: rgba(255,255,255,0.3); transform: rotate(90deg); }
        .modal-body { padding: 30px; }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #E5E7EB;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--maroon);
            box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1);
        }
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .file-upload {
            border: 2px dashed #E5E7EB;
            border-radius: 10px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .file-upload:hover {
            border-color: var(--maroon);
            background: rgba(123, 29, 58, 0.02);
        }
        .file-upload i {
            font-size: 36px;
            color: #9CA3AF;
            margin-bottom: 12px;
        }
        .file-upload p {
            color: #6B7280;
            font-size: 14px;
        }
        .file-upload input { display: none; }
        .file-name {
            margin-top: 8px;
            font-size: 13px;
            color: var(--maroon);
            font-weight: 500;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(123, 29, 58, 0.4);
        }
        .btn-secondary {
            background: #F3F4F6;
            color: #374151;
            padding: 14px 28px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
        }
        .btn-download {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            color: var(--maroon);
            padding: 14px 28px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 191, 0, 0.4);
        }
        .btn-forest {
            background: linear-gradient(135deg, var(--forest-green) 0%, var(--forest-green-dark) 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 15px;
        }
        .btn-forest:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(34, 139, 34, 0.4);
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .alert-success {
            background: #D1FAE5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }
        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }
        .tracking-code-display {
            background: linear-gradient(135deg, var(--forest-green) 0%, var(--forest-green-dark) 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin-top: 12px;
        }
        .tracking-code-display .code {
            font-size: 24px;
            font-weight: 700;
            font-family: monospace;
            letter-spacing: 2px;
        }
        .tracking-code-display p {
            font-size: 13px;
            opacity: 0.9;
            margin-top: 8px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .status-pending { background: #FEF3C7; color: #92400E; }
        .status-under_review, .status-in_progress { background: #DBEAFE; color: #1E40AF; }
        .status-approved, .status-completed, .status-resolved { background: #D1FAE5; color: #065F46; }
        .status-rejected, .status-closed { background: #FEE2E2; color: #991B1B; }

        /* Footer */
        .portal-footer {
            background: var(--maroon-dark);
            color: white;
            padding: 30px;
            text-align: center;
            margin-top: 60px;
        }

        /* Section Title Colors */
        .section-title {
            color: var(--maroon);
        }

        @media (max-width: 768px) {
            .form-row { grid-template-columns: 1fr; }
            .tracking-input { flex-direction: column; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">
            <i class="fas fa-rocket"></i>
            <span>UP Cebu Startup Portal</span>
        </a>
        <a href="{{ route('home') }}" class="navbar-back">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </nav>

    <!-- Hero Section -->
    <section class="portal-hero">
        <div class="max-w-6xl mx-auto px-8 text-center text-white">
            <div class="inline-block px-4 py-2 rounded-full text-sm font-bold mb-6" style="background: linear-gradient(135deg, #FFBF00 0%, #D4A500 100%); color: #7B1D3A;">
                <i class="fas fa-rocket mr-2"></i> STARTUP PORTAL
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Startup & Incubatee Services</h1>
            <p class="text-xl opacity-90 max-w-2xl mx-auto">
                Submit documents, report issues, and track your requests - no login required.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16">
        <div class="max-w-6xl mx-auto px-8">
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle text-xl"></i>
                    <div>
                        <strong>{{ session('success') }}</strong>
                        @if(session('tracking_code'))
                            <div class="tracking-code-display">
                                <p style="margin-bottom: 8px; opacity: 0.9;">Your Tracking Code:</p>
                                <div class="code">{{ session('tracking_code') }}</div>
                                <p>Save this code to track your submission status.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Section Title -->
            <h2 class="text-2xl font-bold text-center mb-4" style="color: #7B1D3A;">What Would You Like To Do?</h2>
            <p class="text-center text-gray-600 mb-10">Select an action below to submit your request</p>

            <!-- Action Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <!-- Upload Document -->
                <div class="action-card" onclick="openModal('documentModal')">
                    <div class="action-icon"><i class="fas fa-file-upload"></i></div>
                    <h3>Upload Document</h3>
                    <p>Submit business plans, reports, and other documents</p>
                </div>

                <!-- Report Room Issue -->
                <div class="action-card" onclick="openModal('roomIssueModal')">
                    <div class="action-icon"><i class="fas fa-tools"></i></div>
                    <h3>Report Room Issue</h3>
                    <p>Report maintenance or facility issues</p>
                </div>

                <!-- Request MOA -->
                <div class="action-card" onclick="openModal('moaModal')">
                    <div class="action-icon"><i class="fas fa-file-contract"></i></div>
                    <h3>Request MOA</h3>
                    <p>Request Memorandum of Agreement</p>
                </div>

                <!-- Submit Payment -->
                <div class="action-card" onclick="openModal('financeModal')">
                    <div class="action-icon"><i class="fas fa-receipt"></i></div>
                    <h3>Submit Payment</h3>
                    <p>Submit payment proof and receipts</p>
                </div>
            </div>

            <!-- Tracking Section -->
            <div class="tracking-section">
                <div class="flex items-center gap-3 mb-4">
                    <i class="fas fa-search text-2xl" style="color: #7B1D3A;"></i>
                    <h3 class="text-xl font-bold" style="color: #7B1D3A;">Track Your Request</h3>
                </div>
                <p class="text-gray-600 mb-4">Enter your tracking code to check the status of your submission.</p>
                
                <div class="tracking-input">
                    <input type="text" id="trackingCodeInput" placeholder="Enter tracking code (e.g., DOC-2026-ABC123)" onkeypress="if(event.key==='Enter') trackSubmission()">
                    <button class="btn-primary" onclick="trackSubmission()">
                        <i class="fas fa-search mr-2"></i> Track
                    </button>
                </div>

                <!-- Tracking Result -->
                <div class="tracking-result" id="trackingResult">
                    <div id="trackingResultContent"></div>
                </div>
            </div>

            <!-- Download MOA Template Section -->
            <div class="mt-8 text-center p-8 bg-white rounded-2xl shadow-lg">
                <h3 class="text-xl font-bold mb-3" style="color: #7B1D3A;">
                    <i class="fas fa-download mr-2"></i> Need the MOA Template?
                </h3>
                <p class="text-gray-600 mb-6">Download the official MOA template before submitting your request.</p>
                <a href="{{ route('startup.moa-template') }}" class="btn-download">
                    <i class="fas fa-file-pdf mr-2"></i> Download MOA Template
                </a>
            </div>

            <!-- Contact Info -->
            <div class="mt-8 text-center p-8 rounded-2xl border-2" style="background: linear-gradient(135deg, rgba(123, 29, 58, 0.05) 0%, rgba(255, 191, 0, 0.1) 100%); border-color: rgba(123, 29, 58, 0.15);">
                <h3 class="text-xl font-bold mb-3" style="color: #7B1D3A;">
                    <i class="fas fa-headset mr-2"></i> Need Help?
                </h3>
                <p class="text-gray-600 mb-4">Contact our support team for any inquiries.</p>
                <div class="flex flex-wrap justify-center gap-6">
                    <div>
                        <i class="fas fa-envelope mr-2" style="color: #228B22;"></i>
                        <span class="text-gray-700">incubation@up.edu.ph</span>
                    </div>
                    <div>
                        <i class="fas fa-phone mr-2" style="color: #228B22;"></i>
                        <span class="text-gray-700">(032) 232-XXXX</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Document Upload Modal -->
    <div id="documentModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h2><i class="fas fa-file-upload mr-2"></i>Upload Document</h2>
            </div>
            <div class="modal-body">
                <form action="{{ route('startup.document') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Company Name *</label>
                            <input type="text" name="company_name" required placeholder="Your company name">
                        </div>
                        <div class="form-group">
                            <label>Contact Person *</label>
                            <input type="text" name="contact_person" required placeholder="Your full name">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" required placeholder="your@email.com">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" placeholder="09XX XXX XXXX">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Document Type *</label>
                        <select name="document_type" required>
                            <option value="">Select document type</option>
                            <option value="business_plan">Business Plan</option>
                            <option value="financial_report">Financial Report</option>
                            <option value="progress_report">Progress Report</option>
                            <option value="pitch_deck">Pitch Deck</option>
                            <option value="registration">Company Registration</option>
                            <option value="other">Other Document</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Upload Document *</label>
                        <div class="file-upload" onclick="document.getElementById('documentFile').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload or drag and drop</p>
                            <p style="font-size: 12px; color: #9CA3AF;">PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG (Max 10MB)</p>
                            <input type="file" id="documentFile" name="document" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" onchange="showFileName(this, 'documentFileName')">
                            <div class="file-name" id="documentFileName"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Notes (Optional)</label>
                        <textarea name="notes" placeholder="Any additional information..."></textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        <i class="fas fa-paper-plane mr-2"></i> Submit Document
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Room Issue Modal -->
    <div id="roomIssueModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header" style="background: linear-gradient(135deg, #228B22 0%, #1B6B1B 100%);">
                <h2><i class="fas fa-tools mr-2"></i>Report Room Issue</h2>
            </div>
            <div class="modal-body">
                <form action="{{ route('startup.room-issue') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Company Name *</label>
                            <input type="text" name="company_name" required placeholder="Your company name">
                        </div>
                        <div class="form-group">
                            <label>Contact Person *</label>
                            <input type="text" name="contact_person" required placeholder="Your full name">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" required placeholder="your@email.com">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" placeholder="09XX XXX XXXX">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Room Number *</label>
                            <select name="room_number" required>
                                <option value="">Select room</option>
                                <option value="Room 101">Room 101</option>
                                <option value="Room 102">Room 102</option>
                                <option value="Room 103">Room 103</option>
                                <option value="Room 201">Room 201</option>
                                <option value="Room 202">Room 202</option>
                                <option value="Room 203">Room 203</option>
                                <option value="Common Area">Common Area</option>
                                <option value="Conference Room">Conference Room</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Issue Type *</label>
                            <select name="issue_type" required>
                                <option value="">Select issue type</option>
                                <option value="electrical">Electrical</option>
                                <option value="plumbing">Plumbing</option>
                                <option value="aircon">AC/Ventilation</option>
                                <option value="internet">Internet/Network</option>
                                <option value="furniture">Furniture</option>
                                <option value="cleaning">Cleaning</option>
                                <option value="security">Security</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Priority</label>
                        <select name="priority">
                            <option value="low">Low - Can wait</option>
                            <option value="medium" selected>Medium - Needs attention soon</option>
                            <option value="high">High - Urgent</option>
                            <option value="urgent">Urgent - Critical issue</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description *</label>
                        <textarea name="description" required placeholder="Describe the issue in detail..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Photo (Optional)</label>
                        <div class="file-upload" onclick="document.getElementById('issuePhoto').click()">
                            <i class="fas fa-camera"></i>
                            <p>Click to upload a photo of the issue</p>
                            <p style="font-size: 12px; color: #9CA3AF;">JPG, PNG (Max 5MB)</p>
                            <input type="file" id="issuePhoto" name="photo" accept=".jpg,.jpeg,.png" onchange="showFileName(this, 'issuePhotoName')">
                            <div class="file-name" id="issuePhotoName"></div>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary w-full" style="background: linear-gradient(135deg, #228B22 0%, #1B6B1B 100%);">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Report Issue
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- MOA Request Modal -->
    <div id="moaModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header" style="background: linear-gradient(135deg, #FFBF00 0%, #D4A500 100%); color: #7B1D3A;">
                <h2><i class="fas fa-file-contract mr-2"></i>Request MOA</h2>
            </div>
            <div class="modal-body">
                <form action="{{ route('startup.moa') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Company Name *</label>
                            <input type="text" name="company_name" required placeholder="Your company name">
                        </div>
                        <div class="form-group">
                            <label>Contact Person *</label>
                            <input type="text" name="contact_person" required placeholder="Your full name">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" required placeholder="your@email.com">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" placeholder="09XX XXX XXXX">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>MOA Purpose *</label>
                        <select name="moa_purpose" required>
                            <option value="">Select purpose</option>
                            <option value="incubation">Incubation Agreement</option>
                            <option value="partnership">Partnership Agreement</option>
                            <option value="collaboration">Research Collaboration</option>
                            <option value="sponsorship">Sponsorship Agreement</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Details *</label>
                        <textarea name="moa_details" required placeholder="Provide details about the MOA request, including scope, duration, and specific terms needed..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Additional Notes</label>
                        <textarea name="notes" placeholder="Any other information..."></textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full" style="background: linear-gradient(135deg, #FFBF00 0%, #D4A500 100%); color: #7B1D3A;">
                        <i class="fas fa-paper-plane mr-2"></i> Submit MOA Request
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Finance/Payment Modal -->
    <div id="financeModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header" style="background: linear-gradient(135deg, #228B22 0%, #1B6B1B 100%);">
                <h2><i class="fas fa-receipt mr-2"></i>Submit Payment Proof</h2>
            </div>
            <div class="modal-body">
                <form action="{{ route('startup.payment') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Company Name *</label>
                            <input type="text" name="company_name" required placeholder="Your company name">
                        </div>
                        <div class="form-group">
                            <label>Contact Person *</label>
                            <input type="text" name="contact_person" required placeholder="Your full name">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" required placeholder="your@email.com">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" placeholder="09XX XXX XXXX">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Invoice/Reference Number *</label>
                            <input type="text" name="invoice_number" required placeholder="e.g., INV-2026-001">
                        </div>
                        <div class="form-group">
                            <label>Amount (PHP) *</label>
                            <input type="number" name="amount" required step="0.01" min="0" placeholder="0.00">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Payment Proof *</label>
                        <div class="file-upload" onclick="document.getElementById('paymentProof').click()">
                            <i class="fas fa-file-invoice"></i>
                            <p>Upload receipt or proof of payment</p>
                            <p style="font-size: 12px; color: #9CA3AF;">PDF, JPG, PNG (Max 5MB)</p>
                            <input type="file" id="paymentProof" name="payment_proof" required accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName(this, 'paymentProofName')">
                            <div class="file-name" id="paymentProofName"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Notes (Optional)</label>
                        <textarea name="notes" placeholder="Any additional information about the payment..."></textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full" style="background: linear-gradient(135deg, #228B22 0%, #1B6B1B 100%);">
                        <i class="fas fa-paper-plane mr-2"></i> Submit Payment Proof
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="portal-footer">
        <p>&copy; {{ date('Y') }} University of the Philippines Cebu. All rights reserved.</p>
        <p style="opacity: 0.7; font-size: 13px; margin-top: 8px;">Startup & Incubation Portal</p>
    </footer>

    <script>
        // Modal Functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function closeModalOnOverlay(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        // Show file name
        function showFileName(input, displayId) {
            const display = document.getElementById(displayId);
            if (input.files.length > 0) {
                display.textContent = input.files[0].name;
            } else {
                display.textContent = '';
            }
        }

        // Track submission
        async function trackSubmission() {
            const trackingCode = document.getElementById('trackingCodeInput').value.trim();
            const resultDiv = document.getElementById('trackingResult');
            const contentDiv = document.getElementById('trackingResultContent');

            if (!trackingCode) {
                alert('Please enter a tracking code.');
                return;
            }

            try {
                const response = await fetch('{{ route('startup.track') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ tracking_code: trackingCode })
                });

                const data = await response.json();

                if (data.success) {
                    let html = '';
                    
                    if (data.type === 'room_issue') {
                        html = `
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center" style="background: rgba(34, 139, 34, 0.1);">
                                    <i class="fas fa-tools" style="color: #228B22;"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-bold text-lg" style="color: #7B1D3A;">Room Issue Report</h4>
                                        <span class="status-badge status-${data.data.status}">${data.data.status_label}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mb-3">Tracking Code: <strong>${data.data.tracking_code}</strong></p>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div><span class="text-gray-500">Company:</span> <strong>${data.data.company_name}</strong></div>
                                        <div><span class="text-gray-500">Room:</span> <strong>${data.data.room_number}</strong></div>
                                        <div><span class="text-gray-500">Issue Type:</span> <strong>${data.data.issue_type}</strong></div>
                                        <div><span class="text-gray-500">Submitted:</span> <strong>${data.data.submitted_at}</strong></div>
                                    </div>
                                    ${data.data.admin_notes ? `<div class="mt-4 p-3 rounded-lg" style="background: rgba(255, 191, 0, 0.1);"><strong style="color: #7B1D3A;">Admin Notes:</strong><p class="text-sm mt-1" style="color: #5a1428;">${data.data.admin_notes}</p></div>` : ''}
                                    ${data.data.resolved_at ? `<p class="mt-3 text-sm" style="color: #228B22;"><i class="fas fa-check-circle mr-1"></i> Resolved on ${data.data.resolved_at}</p>` : ''}
                                </div>
                            </div>
                        `;
                    } else {
                        const typeIcons = {
                            'document': 'fa-file-alt',
                            'moa': 'fa-file-contract',
                            'finance': 'fa-receipt'
                        };
                        const typeColors = {
                            'document': '#7B1D3A',
                            'moa': '#FFBF00',
                            'finance': '#228B22'
                        };
                        const typeBgColors = {
                            'document': 'rgba(123, 29, 58, 0.1)',
                            'moa': 'rgba(255, 191, 0, 0.2)',
                            'finance': 'rgba(34, 139, 34, 0.1)'
                        };
                        const icon = typeIcons[data.data.type] || 'fa-file';
                        const color = typeColors[data.data.type] || '#7B1D3A';
                        const bgColor = typeBgColors[data.data.type] || 'rgba(123, 29, 58, 0.1)';

                        html = `
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center" style="background: ${bgColor};">
                                    <i class="fas ${icon}" style="color: ${color};"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-bold text-lg" style="color: #7B1D3A;">${data.data.type_label}</h4>
                                        <span class="status-badge status-${data.data.status}">${data.data.status_label}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mb-3">Tracking Code: <strong>${data.data.tracking_code}</strong></p>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div><span class="text-gray-500">Company:</span> <strong>${data.data.company_name}</strong></div>
                                        <div><span class="text-gray-500">Submitted:</span> <strong>${data.data.submitted_at}</strong></div>
                                        ${data.data.document_type ? `<div><span class="text-gray-500">Document:</span> <strong>${data.data.document_type}</strong></div>` : ''}
                                        ${data.data.moa_purpose ? `<div><span class="text-gray-500">Purpose:</span> <strong>${data.data.moa_purpose}</strong></div>` : ''}
                                        ${data.data.invoice_number ? `<div><span class="text-gray-500">Invoice:</span> <strong>${data.data.invoice_number}</strong></div>` : ''}
                                        ${data.data.amount ? `<div><span class="text-gray-500">Amount:</span> <strong>₱${parseFloat(data.data.amount).toLocaleString()}</strong></div>` : ''}
                                    </div>
                                    ${data.data.admin_notes ? `<div class="mt-4 p-3 rounded-lg" style="background: rgba(255, 191, 0, 0.1);"><strong style="color: #7B1D3A;">Admin Notes:</strong><p class="text-sm mt-1" style="color: #5a1428;">${data.data.admin_notes}</p></div>` : ''}
                                    ${data.data.reviewed_at ? `<p class="mt-3 text-sm" style="color: #228B22;"><i class="fas fa-check-circle mr-1"></i> Reviewed on ${data.data.reviewed_at}</p>` : ''}
                                </div>
                            </div>
                        `;
                    }

                    contentDiv.innerHTML = html;
                    resultDiv.classList.add('show');
                } else {
                    contentDiv.innerHTML = `
                        <div class="text-center py-6">
                            <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-600">${data.message}</p>
                            <p class="text-sm text-gray-400 mt-2">Please check your tracking code and try again.</p>
                        </div>
                    `;
                    resultDiv.classList.add('show');
                }
            } catch (error) {
                console.error('Error:', error);
                contentDiv.innerHTML = `
                    <div class="text-center py-6 text-red-600">
                        <i class="fas fa-exclamation-circle text-4xl mb-4"></i>
                        <p>An error occurred. Please try again later.</p>
                    </div>
                `;
                resultDiv.classList.add('show');
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.active').forEach(modal => {
                    modal.classList.remove('active');
                });
                document.body.style.overflow = 'auto';
            }
        });
    </script>
</body>
</html>
