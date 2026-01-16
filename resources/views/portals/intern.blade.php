<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Intern Portal - UP Cebu Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #F3F4F6;
            min-height: 100vh;
        }

        /* Registration Page Styles */
        .registration-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
        }

        .registration-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .registration-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .form-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .form-header .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 36px;
            color: #7B1D3A;
        }

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
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #E5E7EB;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #7B1D3A;
            box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(123, 29, 58, 0.4);
        }

        .divider {
            text-align: center;
            margin: 24px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #E5E7EB;
        }

        .divider span {
            background: white;
            padding: 0 16px;
            color: #9CA3AF;
            font-size: 14px;
            position: relative;
        }

        .access-code-section {
            background: #F9FAFB;
            padding: 20px;
            border-radius: 12px;
            margin-top: 16px;
        }

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
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(100px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideOut {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(100px); }
        }
        .modal-header {
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
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

        /* Dashboard Styles */
        .sidebar {
            background: linear-gradient(180deg, #7B1D3A 0%, #5a1428 100%);
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-logo {
            padding: 24px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo img {
            height: 48px;
            margin: 0 auto 12px;
        }

        .sidebar-logo h3 {
            color: white;
            font-size: 14px;
            font-weight: 600;
        }

        .sidebar-logo p {
            color: #FFBF00;
            font-size: 12px;
        }

        .intern-info {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .intern-avatar {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 28px;
            font-weight: 700;
            color: #7B1D3A;
        }

        .intern-name {
            color: white;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .intern-code {
            color: #FFBF00;
            font-size: 12px;
            font-family: monospace;
        }

        .sidebar-menu {
            padding: 16px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .menu-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 18px;
        }

        .menu-item:hover {
            background: rgba(255, 191, 0, 0.1);
            color: white;
        }

        .menu-item.active {
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            color: #7B1D3A;
            font-weight: 600;
        }

        .main-content {
            margin-left: 260px;
            padding: 24px;
            min-height: 100vh;
        }

        .page-header {
            background: white;
            padding: 20px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #1F2937;
        }

        .page-subtitle {
            color: #6B7280;
            font-size: 14px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 16px;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1F2937;
        }

        .stat-card .stat-label {
            color: #6B7280;
            font-size: 14px;
            margin-top: 4px;
        }

        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        .content-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-card-title {
            font-size: 18px;
            font-weight: 600;
            color: #1F2937;
        }

        .content-card-body {
            padding: 24px;
        }

        .progress-bar-container {
            background: #E5E7EB;
            border-radius: 10px;
            height: 20px;
            overflow: hidden;
            margin-top: 8px;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            border-radius: 10px;
            transition: width 0.5s ease;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .profile-item {
            padding: 16px;
            background: #F9FAFB;
            border-radius: 10px;
        }

        .profile-item label {
            font-size: 12px;
            color: #6B7280;
            text-transform: uppercase;
            font-weight: 600;
        }

        .profile-item p {
            font-size: 16px;
            color: #1F2937;
            font-weight: 500;
            margin-top: 4px;
        }

        .page-content {
            display: none;
        }

        .page-content.active {
            display: block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(123, 29, 58, 0.3);
        }

        .btn-secondary {
            background: #F3F4F6;
            color: #374151;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #D1FAE5;
            color: #065F46;
        }

        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
        }

        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @if(isset($showPending) && $showPending)
    <!-- Pending Approval Page -->
    <div class="registration-page">
        <div class="registration-container">
            <div class="registration-card" style="text-align: center; padding: 60px 40px;">
                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #FEF3C7, #FDE68A); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <i class="fas fa-clock" style="font-size: 48px; color: #D97706;"></i>
                </div>
                <h2 style="color: #1F2937; font-size: 24px; font-weight: 700; margin-bottom: 12px;">Pending Approval</h2>
                <p style="color: #6B7280; font-size: 15px; margin-bottom: 24px; max-width: 400px; margin-left: auto; margin-right: auto;">
                    Your registration is being reviewed by the administrator. Please check back later or contact your school coordinator.
                </p>

                <div style="background: #F9FAFB; border-radius: 12px; padding: 20px; margin-bottom: 24px; text-align: left;">
                    <h4 style="color: #7B1D3A; font-size: 14px; font-weight: 600; margin-bottom: 16px;">
                        <i class="fas fa-info-circle" style="margin-right: 8px;"></i>Your Registration Details
                    </h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
                        <div>
                            <div style="color: #6B7280; margin-bottom: 2px;">Reference Code</div>
                            <div style="color: #1F2937; font-weight: 600;">{{ $intern->reference_code }}</div>
                        </div>
                        <div>
                            <div style="color: #6B7280; margin-bottom: 2px;">Name</div>
                            <div style="color: #1F2937; font-weight: 600;">{{ $intern->name }}</div>
                        </div>
                        <div>
                            <div style="color: #6B7280; margin-bottom: 2px;">School</div>
                            <div style="color: #1F2937; font-weight: 600;">{{ $intern->schoolRelation->name ?? $intern->school }}</div>
                        </div>
                        <div>
                            <div style="color: #6B7280; margin-bottom: 2px;">Submitted On</div>
                            <div style="color: #1F2937; font-weight: 600;">{{ $intern->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('intern.clear') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: #E5E7EB; color: #374151; border: none; padding: 12px 24px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i>Switch Account
                    </button>
                </form>

                <div style="text-align: center; margin-top: 24px;">
                    <a href="{{ route('home') }}" style="color: #6B7280; text-decoration: none; font-size: 14px;">
                        <i class="fas fa-arrow-left" style="margin-right: 6px;"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    @elseif(isset($showRejected) && $showRejected)
    <!-- Rejected Page -->
    <div class="registration-page">
        <div class="registration-container">
            <div class="registration-card" style="text-align: center; padding: 60px 40px;">
                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #FEE2E2, #FECACA); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <i class="fas fa-times-circle" style="font-size: 48px; color: #DC2626;"></i>
                </div>
                <h2 style="color: #1F2937; font-size: 24px; font-weight: 700; margin-bottom: 12px;">Application Rejected</h2>
                <p style="color: #6B7280; font-size: 15px; margin-bottom: 24px; max-width: 400px; margin-left: auto; margin-right: auto;">
                    Unfortunately, your application has been rejected. Please review the reason below and contact your school coordinator if you have any questions.
                </p>

                <div style="background: #FEE2E2; border-radius: 12px; padding: 20px; margin-bottom: 24px; text-align: left;">
                    <h4 style="color: #991B1B; font-size: 14px; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>Rejection Reason
                    </h4>
                    <p style="color: #7F1D1D; font-size: 14px; margin: 0;">{{ $intern->rejection_reason ?? 'No reason provided.' }}</p>
                </div>

                <div style="background: #F9FAFB; border-radius: 12px; padding: 20px; margin-bottom: 24px; text-align: left;">
                    <h4 style="color: #7B1D3A; font-size: 14px; font-weight: 600; margin-bottom: 16px;">
                        <i class="fas fa-info-circle" style="margin-right: 8px;"></i>Your Application Details
                    </h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
                        <div>
                            <div style="color: #6B7280; margin-bottom: 2px;">Reference Code</div>
                            <div style="color: #1F2937; font-weight: 600;">{{ $intern->reference_code }}</div>
                        </div>
                        <div>
                            <div style="color: #6B7280; margin-bottom: 2px;">Name</div>
                            <div style="color: #1F2937; font-weight: 600;">{{ $intern->name }}</div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('intern.clear') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-redo" style="margin-right: 8px;"></i>Try Another Account
                    </button>
                </form>

                <div style="text-align: center; margin-top: 24px;">
                    <a href="{{ route('home') }}" style="color: #6B7280; text-decoration: none; font-size: 14px;">
                        <i class="fas fa-arrow-left" style="margin-right: 6px;"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    @elseif(!$showDashboard)
    <!-- Intern Portal Landing Page -->
    <div class="registration-page">
        <div class="registration-container">
            <div class="registration-card">
                <div class="form-header">
                    <div class="logo">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h1 style="color: #7B1D3A; font-size: 24px; font-weight: 700;">Intern Portal</h1>
                    <p style="color: #6B7280; margin-top: 8px;">University of the Philippines Cebu</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Access with Reference Code (Primary Section) -->
                <div class="access-code-section" style="background: linear-gradient(135deg, rgba(123, 29, 58, 0.05) 0%, rgba(255, 191, 0, 0.1) 100%); border: 2px solid rgba(123, 29, 58, 0.15); margin-top: 0; margin-bottom: 24px;">
                    <form action="{{ route('intern.access') }}" method="POST">
                        @csrf
                        <h4 style="font-size: 16px; font-weight: 600; color: #7B1D3A; margin-bottom: 16px;">
                            <i class="fas fa-key" style="color: #FFBF00; margin-right: 8px;"></i>
                            Access Your Dashboard
                        </h4>
                        <p style="font-size: 13px; color: #6B7280; margin-bottom: 16px;">
                            Already registered? Enter your reference code to access your intern dashboard.
                        </p>
                        <div style="display: flex; gap: 12px;">
                            <input type="text" name="reference_code" placeholder="Enter reference code (e.g., INT-2026-XXXXXX)" style="flex: 1; padding: 14px 16px; border: 2px solid #E5E7EB; border-radius: 10px; font-size: 14px;">
                            <button type="submit" class="btn-primary" style="padding: 14px 24px;">
                                <i class="fas fa-sign-in-alt" style="margin-right: 6px;"></i> Access
                            </button>
                        </div>
                    </form>
                </div>

                <div class="divider">
                    <span>New intern?</span>
                </div>

                <!-- Register Button -->
                <button type="button" onclick="openRegistrationModal()" class="btn-submit" style="background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%); color: #7B1D3A;">
                    <i class="fas fa-user-plus" style="margin-right: 8px;"></i>
                    Register as New Intern
                </button>

                <div style="text-align: center; margin-top: 24px;">
                    <a href="{{ route('home') }}" style="color: #6B7280; text-decoration: none; font-size: 14px;">
                        <i class="fas fa-arrow-left" style="margin-right: 6px;"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Modal -->
    <div id="registrationModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <h2><i class="fas fa-user-plus" style="margin-right: 8px;"></i>New Intern Registration</h2>
                    <p style="font-size: 13px; opacity: 0.8; margin-top: 4px;">University of the Philippines Cebu</p>
                </div>
                <button class="modal-close" onclick="closeRegistrationModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('intern.register') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label><i class="fas fa-user" style="color: #7B1D3A; margin-right: 6px;"></i>Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-birthday-cake" style="color: #7B1D3A; margin-right: 6px;"></i>Age</label>
                            <input type="number" name="age" value="{{ old('age') }}" placeholder="Age" min="16" max="60" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-venus-mars" style="color: #7B1D3A; margin-right: 6px;"></i>Gender</label>
                            <select name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-envelope" style="color: #7B1D3A; margin-right: 6px;"></i>Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="your.email@example.com" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-phone" style="color: #7B1D3A; margin-right: 6px;"></i>Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="09XX XXX XXXX" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-university" style="color: #7B1D3A; margin-right: 6px;"></i>School / University</label>
                        <select name="school_id" required>
                            <option value="">Select your school</option>
                            @foreach($schools ?? [] as $school)
                                @php
                                    $isFull = $school->max_interns && !$school->hasCapacity();
                                    $capacityInfo = $school->max_interns
                                        ? " - {$school->getRemainingSlots()} slots remaining"
                                        : '';
                                @endphp
                                @if(!$isFull)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }} ({{ $school->required_hours }} hrs required{{ $capacityInfo }})
                                </option>
                                @endif
                            @endforeach
                        </select>
                        @if(($schools ?? collect())->isEmpty())
                        <p style="font-size: 12px; color: #DC2626; margin-top: 6px;">
                            <i class="fas fa-exclamation-circle"></i> No schools available. Please contact the administrator.
                        </p>
                        @endif
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-graduation-cap" style="color: #7B1D3A; margin-right: 6px;"></i>Course / Program</label>
                            <input type="text" name="course" value="{{ old('course') }}" placeholder="e.g., BS Computer Science" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-layer-group" style="color: #7B1D3A; margin-right: 6px;"></i>Year Level</label>
                            <select name="year_level">
                                <option value="">Select Year</option>
                                <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                <option value="5th Year" {{ old('year_level') == '5th Year' ? 'selected' : '' }}>5th Year</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane" style="margin-right: 8px;"></i>
                        Submit Registration
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRegistrationModal() {
            document.getElementById('registrationModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeRegistrationModal() {
            document.getElementById('registrationModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function closeModalOnOverlay(event) {
            if (event.target === document.getElementById('registrationModal')) {
                closeRegistrationModal();
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeRegistrationModal();
            }
        });

        @if($errors->any())
            openRegistrationModal();
        @endif
    </script>
    @else





    <!-- Dashboard -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="/images/UP logo.png" alt="UP Logo">
            <h3>University of the Philippines Cebu</h3>
            <p>Intern Portal</p>
        </div>

        <div class="intern-info">
            <div class="intern-avatar">{{ strtoupper(substr($intern->name, 0, 1)) }}</div>
            <div class="intern-name">{{ $intern->name }}</div>
            <div class="intern-code">{{ $intern->reference_code }}</div>
        </div>

        <nav class="sidebar-menu">
            <a class="menu-item active" data-page="dashboard" onclick="showPage('dashboard'); setActiveMenu(this);">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a class="menu-item" data-page="profile" onclick="showPage('profile'); setActiveMenu(this);">
                <i class="fas fa-user"></i>
                <span>My Profile</span>
            </a>
            <a class="menu-item" data-page="attendance" onclick="showPage('attendance'); setActiveMenu(this);">
                <i class="fas fa-clock"></i>
                <span>Attendance</span>
            </a>
            <a class="menu-item" data-page="tasks" onclick="showPage('tasks'); setActiveMenu(this);">
                <i class="fas fa-tasks"></i>
                <span>My Tasks</span>
            </a>
            <a class="menu-item" data-page="reports" onclick="showPage('reports'); setActiveMenu(this);">
                <i class="fas fa-file-alt"></i>
                <span>Reports</span>
            </a>
            <a class="menu-item" data-page="schedule" onclick="showPage('schedule'); setActiveMenu(this);">
                <i class="fas fa-calendar"></i>
                <span>Schedule</span>
            </a>
            <a class="menu-item" data-page="documents" onclick="showPage('documents'); setActiveMenu(this);">
                <i class="fas fa-folder"></i>
                <span>Documents</span>
            </a>
        </nav>

        <div style="padding: 20px; margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1);">
            <form action="{{ route('intern.clear') }}" method="POST">
                @csrf
                <button type="submit" class="menu-item" style="width: 100%; background: none; border: none; text-align: left; color: rgba(255,255,255,0.6);">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Switch Account</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Dashboard Page -->
        <div id="dashboard" class="page-content active">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Welcome back, {{ explode(' ', $intern->name)[0] }}!</h1>
                    <p class="page-subtitle">Here's your internship overview</p>
                </div>
                <div>
                    <span style="background: #D1FAE5; color: #065F46; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                        <i class="fas fa-circle" style="font-size: 8px; margin-right: 6px;"></i>
                        {{ $intern->status }}
                    </span>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #DBEAFE; color: #2563EB;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value">{{ $intern->completed_hours }}</div>
                    <div class="stat-label">Hours Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #FEF3C7; color: #D97706;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stat-value">{{ $intern->remaining_hours }}</div>
                    <div class="stat-label">Hours Remaining</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #D1FAE5; color: #059669;">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-value">0</div>
                    <div class="stat-label">Tasks Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #EDE9FE; color: #7C3AED;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-value">0</div>
                    <div class="stat-label">Reports Submitted</div>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-header">
                    <h3 class="content-card-title">Internship Progress</h3>
                    @php
                        // Calculate actual completed hours from attendance records
                        $actualCompletedHours = 0;
                        if ($attendanceHistory && $attendanceHistory->count() > 0) {
                            foreach ($attendanceHistory as $record) {
                                if ($record->time_in && $record->time_out) {
                                    $timeIn = \Carbon\Carbon::parse($record->time_in);
                                    $timeOut = \Carbon\Carbon::parse($record->time_out);
                                    $hoursWorked = round($timeOut->diffInMinutes($timeIn) / 60, 2);
                                    if ($hoursWorked >= 8) {
                                        $actualCompletedHours += 8; // Count as full 8-hour day
                                    } else {
                                        $actualCompletedHours += $hoursWorked;
                                    }
                                }
                            }
                        }
                        $progressPercentage = $intern->required_hours > 0 ? round(($actualCompletedHours / $intern->required_hours) * 100, 1) : 0;
                    @endphp
                    <span style="font-size: 24px; font-weight: 700; color: #7B1D3A;">{{ min($progressPercentage, 100) }}%</span>
                </div>
                <div class="content-card-body">
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: {{ min($progressPercentage, 100) }}%;"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 12px; color: #6B7280; font-size: 14px;">
                        <span>{{ number_format($actualCompletedHours, 2) }} hours completed</span>
                        <span>{{ $intern->required_hours }} hours required</span>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">Quick Actions</h3>
                    </div>
                    <div class="content-card-body">
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <button class="btn-primary" style="justify-content: flex-start; display: flex; align-items: center;">
                                <i class="fas fa-clock" style="margin-right: 10px;"></i>
                                Log Attendance
                            </button>
                            <button class="btn-primary" style="justify-content: flex-start; display: flex; align-items: center;">
                                <i class="fas fa-file-upload" style="margin-right: 10px;"></i>
                                Submit Daily Report
                            </button>
                            <button class="btn-secondary" style="justify-content: flex-start; display: flex; align-items: center;">
                                <i class="fas fa-download" style="margin-right: 10px;"></i>
                                Download Forms
                            </button>
                        </div>
                    </div>
                </div>

                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="content-card-title">Recent Activity</h3>
                    </div>
                    <div class="content-card-body">
                        <div style="text-align: center; padding: 30px; color: #9CA3AF;">
                            <i class="fas fa-inbox" style="font-size: 40px; margin-bottom: 12px;"></i>
                            <p>No recent activity</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Page -->
        <div id="profile" class="page-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">My Profile</h1>
                    <p class="page-subtitle">View and manage your information</p>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-header">
                    <h3 class="content-card-title">Personal Information</h3>
                    <button class="btn-secondary" onclick="toggleEditProfile()">
                        <i class="fas fa-edit" style="margin-right: 6px;"></i>
                        Edit
                    </button>
                </div>
                <div class="content-card-body">
                    <div class="profile-grid">
                        <div class="profile-item">
                            <label>Full Name</label>
                            <p>{{ $intern->name }}</p>
                        </div>
                        <div class="profile-item">
                            <label>Reference Code</label>
                            <p style="font-family: monospace; color: #7B1D3A;">{{ $intern->reference_code }}</p>
                        </div>
                        <div class="profile-item">
                            <label>Email Address</label>
                            <p>{{ $intern->email }}</p>
                        </div>
                        <div class="profile-item">
                            <label>Phone Number</label>
                            <p>{{ $intern->phone }}</p>
                        </div>
                        <div class="profile-item">
                            <label>Age</label>
                            <p>{{ $intern->age }} years old</p>
                        </div>
                        <div class="profile-item">
                            <label>Gender</label>
                            <p>{{ $intern->gender }}</p>
                        </div>
                        <div class="profile-item">
                            <label>School</label>
                            <p>{{ $intern->school }}</p>
                        </div>
                        <div class="profile-item">
                            <label>Course</label>
                            <p>{{ $intern->course }}</p>
                        </div>
                        <div class="profile-item">
                            <label>Year Level</label>
                            <p>{{ $intern->year_level ?? 'Not specified' }}</p>
                        </div>
                        <div class="profile-item">
                            <label>Start Date</label>
                            <p>{{ $intern->start_date ? $intern->start_date->format('F d, Y') : 'Not set' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Page -->
        <div id="attendance" class="page-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Time & Attendance</h1>
                    <p class="page-subtitle">Log your daily attendance</p>
                </div>
            </div>

            <!-- Current Time Display -->
            <div class="content-card" style="background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white;">
                <div class="content-card-body" style="text-align: center; padding: 40px;">
                    <p style="font-size: 14px; opacity: 0.8; margin-bottom: 8px;">
                        <i class="fas fa-map-marker-alt" style="margin-right: 6px;"></i>
                        Philippine Standard Time (UTC+8)
                    </p>
                    <div id="currentTime" style="font-size: 56px; font-weight: 700; font-family: 'Courier New', monospace; margin-bottom: 8px;">
                        --:--:-- --
                    </div>
                    <div id="currentDate" style="font-size: 18px; opacity: 0.9;">
                        Loading...
                    </div>

                    <div id="attendanceButtonsContainer" style="margin-top: 32px; display: flex; justify-content: center; gap: 16px;">
                        <!-- Time In Button -->
                        <form action="{{ route('intern.timein') }}" method="POST" id="timeInForm" style="display: {{ (!$todayAttendance || !$todayAttendance->time_in) ? 'block' : 'none' }};">
                            @csrf
                            <button type="submit" id="timeInBtn" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; border: none; padding: 16px 48px; border-radius: 12px; font-size: 18px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-sign-in-alt" id="timeInIcon"></i>
                                <span id="timeInText">TIME IN</span>
                            </button>
                        </form>

                        <!-- Time Out Button -->
                        <form action="{{ route('intern.timeout') }}" method="POST" id="timeOutForm" style="display: {{ ($todayAttendance && $todayAttendance->time_in && !$todayAttendance->time_out) ? 'block' : 'none' }};">
                            @csrf
                            <button type="submit" id="timeOutBtn" style="background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%); color: white; border: none; padding: 16px 48px; border-radius: 12px; font-size: 18px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-sign-out-alt" id="timeOutIcon"></i>
                                <span id="timeOutText">TIME OUT</span>
                            </button>
                        </form>

                        <!-- Already completed for today -->
                        <div id="attendanceComplete" style="display: {{ ($todayAttendance && $todayAttendance->time_in && $todayAttendance->time_out) ? 'block' : 'none' }}; background: rgba(255,255,255,0.2); padding: 16px 48px; border-radius: 12px;">
                            <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                            Attendance Complete for Today
                        </div>
                    </div>

                    <!-- Today's Summary -->
                    @if($todayAttendance)
                    @php
                        // Calculate hours for today dynamically from time_in and time_out
                        $todayHours = 0;
                        if ($todayAttendance->time_in) {
                            $timeIn = \Carbon\Carbon::parse($todayAttendance->time_in);
                            if ($todayAttendance->time_out) {
                                $timeOut = \Carbon\Carbon::parse($todayAttendance->time_out);
                                $todayHours = round($timeOut->diffInMinutes($timeIn) / 60, 2);
                            } else {
                                // If no time out, calculate from current time
                                $now = \Carbon\Carbon::now('Asia/Manila');
                                $todayHours = round($now->diffInMinutes($timeIn) / 60, 2);
                            }
                        }
                    @endphp
                    <div id="todaySummary" style="margin-top: 32px; display: flex; justify-content: center; gap: 40px;">
                        <div>
                            <p style="font-size: 12px; opacity: 0.7;">TIME IN</p>
                            <p id="summaryTimeIn" style="font-size: 24px; font-weight: 600;">{{ $todayAttendance->formatted_time_in }}</p>
                        </div>
                        <div>
                            <p style="font-size: 12px; opacity: 0.7;">TIME OUT</p>
                            <p id="summaryTimeOut" style="font-size: 24px; font-weight: 600;">{{ $todayAttendance->formatted_time_out }}</p>
                        </div>
                        <div>
                            <p style="font-size: 12px; opacity: 0.7;">HOURS TODAY</p>
                            <p id="summaryHours" style="font-size: 24px; font-weight: 600;"
                               data-time-in="{{ $todayAttendance->time_in }}"
                               data-time-out="{{ $todayAttendance->time_out }}"
                               data-is-working="{{ ($todayAttendance->time_in && !$todayAttendance->time_out) ? 'true' : 'false' }}">
                                {{ number_format($todayHours, 2) }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Attendance History -->
            <div class="content-card">
                <div class="content-card-header">
                    <h3 class="content-card-title">Attendance History</h3>
                </div>
                <div class="content-card-body">
                    @if($attendanceHistory->count() > 0)
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #F9FAFB; text-align: left;">
                                <th style="padding: 12px 16px; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase;">Date</th>
                                <th style="padding: 12px 16px; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase;">Time In</th>
                                <th style="padding: 12px 16px; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase;">Time Out</th>
                                <th style="padding: 12px 16px; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase;">Hours</th>
                                <th style="padding: 12px 16px; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase;">Over/Under</th>
                                <th style="padding: 12px 16px; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendanceHistory as $record)
                            @php
                                // Calculate hours from time_in and time_out
                                $hoursWorked = 0;
                                $displayStatus = 'Absent';

                                if ($record->time_in) {
                                    $timeIn = \Carbon\Carbon::parse($record->time_in);
                                    $timeOut = $record->time_out ? \Carbon\Carbon::parse($record->time_out) : null;

                                    if ($timeOut) {
                                        $hoursWorked = round($timeOut->diffInMinutes($timeIn) / 60, 2);
                                    } else {
                                        // If no time out, calculate from current time
                                        $now = \Carbon\Carbon::now('Asia/Manila');
                                        $hoursWorked = round($now->diffInMinutes($timeIn) / 60, 2);
                                    }

                                    // Determine status based on hours and time in
                                    if ($timeOut) {
                                        if ($hoursWorked >= 8) {
                                            $displayStatus = 'Present';
                                        } elseif ($hoursWorked > 0) {
                                            $displayStatus = 'Undertime';
                                        }
                                    } else {
                                        $displayStatus = 'Present'; // Still working
                                    }

                                    // Check if late (after 9 AM)
                                    if ($timeIn->hour >= 9 && $timeOut) {
                                        $displayStatus = 'Late';
                                    }
                                }
                            @endphp
                            <tr style="border-bottom: 1px solid #E5E7EB;">
                                <td style="padding: 16px;">
                                    <div style="font-weight: 600; color: #1F2937;">{{ $record->date->format('M d, Y') }}</div>
                                    <div style="font-size: 12px; color: #6B7280;">{{ $record->date->format('l') }}</div>
                                </td>
                                <td style="padding: 16px; color: #059669; font-weight: 500;">{{ $record->formatted_time_in }}</td>
                                <td style="padding: 16px; color: #DC2626; font-weight: 500;">{{ $record->formatted_time_out }}</td>
                                <td style="padding: 16px; font-weight: 600;">{{ number_format($hoursWorked, 2) }} hrs</td>
                                <td style="padding: 16px;">
                                    @if($record->time_out)
                                        @if($record->hasUndertime())
                                            <span style="background: #FEE2E2; color: #991B1B; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                                <i class="fas fa-arrow-down"></i> -{{ number_format($record->undertime_hours, 2) }} hrs
                                            </span>
                                        @elseif($record->hasOvertime())
                                            @if($record->overtime_approved)
                                                <span style="background: #D1FAE5; color: #065F46; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                                    <i class="fas fa-check-circle"></i> +{{ number_format($record->overtime_hours, 2) }} hrs
                                                </span>
                                            @else
                                                <span style="background: #FEF3C7; color: #92400E; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                                    <i class="fas fa-clock"></i> +{{ number_format($record->overtime_hours, 2) }} hrs (Pending)
                                                </span>
                                            @endif
                                        @else
                                            <span style="background: #DBEAFE; color: #1E40AF; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                                <i class="fas fa-check"></i> On Target
                                            </span>
                                        @endif
                                    @else
                                        <span style="color: #9CA3AF; font-size: 12px;">--</span>
                                    @endif
                                </td>
                                <td style="padding: 16px;">
                                    <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
                                        @if($displayStatus === 'Present') background: #D1FAE5; color: #065F46;
                                        @elseif($displayStatus === 'Late') background: #FEF3C7; color: #92400E;
                                        @elseif($displayStatus === 'Undertime') background: #FEE2E2; color: #991B1B;
                                        @else background: #F3F4F6; color: #6B7280; @endif">
                                        {{ $displayStatus }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div style="text-align: center; padding: 50px; color: #9CA3AF;">
                        <i class="fas fa-calendar-times" style="font-size: 50px; margin-bottom: 16px;"></i>
                        <p style="font-size: 16px;">No attendance records yet</p>
                        <p style="font-size: 14px; margin-top: 8px;">Start logging your attendance using the "Time In" button</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tasks Page -->
        <div id="tasks" class="page-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">My Tasks</h1>
                    <p class="page-subtitle">View and manage your assigned tasks</p>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-body">
                    @if(($tasks ?? collect())->count() > 0)
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 2px solid #E5E7EB; background: #F9FAFB;">
                                    <th style="text-align: left; padding: 14px 16px; font-weight: 700; color: #1F2937; font-size: 13px;">Task Title</th>
                                    <th style="text-align: left; padding: 14px 16px; font-weight: 700; color: #1F2937; font-size: 13px;">Description</th>
                                    <th style="text-align: center; padding: 14px 16px; font-weight: 700; color: #1F2937; font-size: 13px;">Priority</th>
                                    <th style="text-align: center; padding: 14px 16px; font-weight: 700; color: #1F2937; font-size: 13px;">Due Date</th>
                                    <th style="text-align: center; padding: 14px 16px; font-weight: 700; color: #1F2937; font-size: 13px;">Status</th>
                                    <th style="text-align: center; padding: 14px 16px; font-weight: 700; color: #1F2937; font-size: 13px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                <tr style="border-bottom: 1px solid #E5E7EB; transition: background 0.2s;" onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">
                                    <td style="padding: 14px 16px; font-weight: 600; color: #1F2937;">
                                        {{ $task->title }}
                                    </td>
                                    <td style="padding: 14px 16px; color: #6B7280; font-size: 13px;">
                                        {{ strlen($task->description ?? '') > 40 ? substr($task->description, 0, 40) . '...' : $task->description }}
                                    </td>
                                    <td style="padding: 14px 16px; text-align: center;">
                                        <span style="display: inline-block; background:
                                            @if($task->priority === 'High') #FEE2E2; color: #991B1B;
                                            @elseif($task->priority === 'Medium') #FEF3C7; color: #92400E;
                                            @else #D1FAE5; color: #065F46;
                                            @endif
                                            padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 700;">
                                            {{ $task->priority }}
                                        </span>
                                    </td>
                                    <td style="padding: 14px 16px; text-align: center; color: #1F2937; font-weight: 500;">
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                        @php
                                            $daysLeft = now('Asia/Manila')->diffInDays($task->due_date, false);
                                            $isOverdue = $daysLeft < 0;
                                        @endphp
                                        @if($isOverdue && $task->status !== 'Completed')
                                            <div style="font-size: 11px; color: #991B1B; margin-top: 2px;">
                                                <i class="fas fa-exclamation-circle"></i> Overdue
                                            </div>
                                        @elseif($daysLeft === 0 && $task->status !== 'Completed')
                                            <div style="font-size: 11px; color: #92400E; margin-top: 2px;">
                                                <i class="fas fa-hourglass-end"></i> Due Today
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding: 14px 16px; text-align: center;">
                                        <span style="display: inline-block; background:
                                            @if($task->status === 'Completed') #D1FAE5; color: #065F46;
                                            @elseif($task->status === 'In Progress') #FEF3C7; color: #92400E;
                                            @else #E5E7EB; color: #6B7280;
                                            @endif
                                            padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 700;">
                                            {{ $task->status }}
                                        </span>
                                    </td>
                                    <td style="padding: 14px 16px; text-align: center;">
                                        @if($task->status === 'Completed')
                                            <span style="color: #6B7280; font-size: 12px;">
                                                <i class="fas fa-check-circle"></i> Completed
                                            </span>
                                        @elseif($task->status === 'In Progress')
                                            <button onclick="completeTask({{ $task->id }})" style="background: #10B981; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 11px; cursor: pointer; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10B981'">
                                                <i class="fas fa-check"></i> Complete
                                            </button>
                                        @else
                                            <button onclick="startTask({{ $task->id }})" style="background: #7B1D3A; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 11px; cursor: pointer; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='#5a1428'" onmouseout="this.style.background='#7B1D3A'">
                                                <i class="fas fa-play"></i> Start
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center; color: #6B7280; font-size: 12px;">
                        <div>
                            Total Tasks: <strong>{{ $tasks->count() }}</strong>
                            | Completed: <strong>{{ $tasks->where('status', 'Completed')->count() }}</strong>
                            | Pending: <strong>{{ $tasks->whereNotIn('status', ['Completed'])->count() }}</strong>
                        </div>
                    </div>
                    @else
                    <div style="text-align: center; padding: 50px; color: #9CA3AF;">
                        <i class="fas fa-clipboard-list" style="font-size: 50px; margin-bottom: 16px; display: block;"></i>
                        <p style="font-size: 16px; font-weight: 600;">No tasks assigned yet</p>
                        <p style="font-size: 14px; margin-top: 8px;">Tasks assigned by your supervisor will appear here</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Reports Page -->
        <div id="reports" class="page-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Reports</h1>
                    <p class="page-subtitle">Submit and view your internship reports</p>
                </div>
                <button class="btn-primary">
                    <i class="fas fa-plus" style="margin-right: 6px;"></i>
                    Submit Report
                </button>
            </div>

            <div class="content-card">
                <div class="content-card-body">
                    <div style="text-align: center; padding: 50px; color: #9CA3AF;">
                        <i class="fas fa-file-alt" style="font-size: 50px; margin-bottom: 16px;"></i>
                        <p style="font-size: 16px;">No reports submitted yet</p>
                        <p style="font-size: 14px; margin-top: 8px;">Click "Submit Report" to create your first report</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Page -->
        <div id="schedule" class="page-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Schedule</h1>
                    <p class="page-subtitle">View your internship schedule and events</p>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-body">
                    <div style="text-align: center; padding: 50px; color: #9CA3AF;">
                        <i class="fas fa-calendar" style="font-size: 50px; margin-bottom: 16px;"></i>
                        <p style="font-size: 16px;">No scheduled events</p>
                        <p style="font-size: 14px; margin-top: 8px;">Your schedule and events will appear here</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Page -->
        <div id="documents" class="page-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Documents</h1>
                    <p class="page-subtitle">Upload and manage your documents</p>
                </div>
                <button class="btn-primary">
                    <i class="fas fa-upload" style="margin-right: 6px;"></i>
                    Upload Document
                </button>
            </div>

            <div class="content-card">
                <div class="content-card-body">
                    <div style="text-align: center; padding: 50px; color: #9CA3AF;">
                        <i class="fas fa-folder-open" style="font-size: 50px; margin-bottom: 16px;"></i>
                        <p style="font-size: 16px;">No documents uploaded yet</p>
                        <p style="font-size: 14px; margin-top: 8px;">Upload your internship requirements and documents here</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function showPage(pageId, updateMenu = true) {
            // Hide all pages
            document.querySelectorAll('.page-content').forEach(page => {
                page.classList.remove('active');
            });

            // Show selected page
            const targetPage = document.getElementById(pageId);
            if (targetPage) {
                targetPage.classList.add('active');
            }

            // Update menu active state
            if (updateMenu) {
                document.querySelectorAll('.menu-item').forEach(item => {
                    item.classList.remove('active');
                    // Check if this menu item corresponds to the page
                    if (item.getAttribute('onclick') && item.getAttribute('onclick').includes("'" + pageId + "'")) {
                        item.classList.add('active');
                    }
                });
            }
        }

        // Check URL for page parameter and auto-navigate
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const page = urlParams.get('page');
            if (page) {
                showPage(page, false);
                // Update menu active state
                document.querySelectorAll('.menu-item').forEach(item => {
                    item.classList.remove('active');
                    if (item.getAttribute('data-page') === page) {
                        item.classList.add('active');
                    }
                });
            }
        });

        // Set active menu item
        function setActiveMenu(element) {
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            element.classList.add('active');
        }

        function toggleEditProfile() {
            alert('Edit profile feature coming soon!');
        }

        // Prevent spam on attendance buttons
        let isSubmitting = false;

        // Initialize attendance form handlers
        document.addEventListener('DOMContentLoaded', function() {
            const timeInForm = document.getElementById('timeInForm');
            const timeOutForm = document.getElementById('timeOutForm');

            if (timeInForm) {
                timeInForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleAttendanceSubmit(this, 'in');
                });
            }

            if (timeOutForm) {
                timeOutForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleAttendanceSubmit(this, 'out');
                });
            }
        });

        function handleAttendanceSubmit(form, type) {
            if (isSubmitting) {
                return false; // Prevent double submission
            }

            isSubmitting = true;

            const btn = form.querySelector('button');
            const icon = type === 'in' ? document.getElementById('timeInIcon') : document.getElementById('timeOutIcon');
            const text = type === 'in' ? document.getElementById('timeInText') : document.getElementById('timeOutText');

            // Disable button and show loading state
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.style.cursor = 'not-allowed';

            if (icon) {
                icon.className = 'fas fa-spinner fa-spin';
            }
            if (text) {
                text.textContent = type === 'in' ? 'TIMING IN...' : 'TIMING OUT...';
            }

            // Get form data
            const formData = new FormData(form);

            // Submit via AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if (type === 'in') {
                        // Transition from Time In to Time Out
                        document.getElementById('timeInForm').style.display = 'none';
                        document.getElementById('timeOutForm').style.display = 'block';

                        // Reset Time In button for future and prepare Time Out button
                        resetButton(btn, icon, text, type);

                        // Update the today's summary with live tracking enabled
                        updateTodaySummary(data.time_in, null, null, data.raw_time_in);

                        // Show success notification
                        showAttendanceNotification('Time In recorded successfully!', 'success');
                    } else {
                        // Transition from Time Out to Complete
                        document.getElementById('timeOutForm').style.display = 'none';
                        document.getElementById('attendanceComplete').style.display = 'block';

                        // Update the today's summary (stops live tracking)
                        updateTodaySummary(data.time_in, data.time_out, data.hours_worked);

                        // Show success notification
                        showAttendanceNotification('Time Out recorded successfully!', 'success');

                        // Reset Time Out button for future use
                        resetButton(btn, icon, text, type);

                        // Reload page after time out to show updated status
                        if (data.reload) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        }
                    }
                } else {
                    // Show error
                    showAttendanceNotification(data.message || 'An error occurred', 'error');
                    resetButton(btn, icon, text, type);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAttendanceNotification('An error occurred. Please try again.', 'error');
                resetButton(btn, icon, text, type);
            })
            .finally(() => {
                isSubmitting = false;
            });

            return false;
        }

        function resetButton(btn, icon, text, type) {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
            if (icon) {
                icon.className = type === 'in' ? 'fas fa-sign-in-alt' : 'fas fa-sign-out-alt';
            }
            if (text) {
                text.textContent = type === 'in' ? 'TIME IN' : 'TIME OUT';
            }
        }

        function updateTodaySummary(timeIn, timeOut, hoursWorked, rawTimeIn = null) {
            // Check if summary section exists, if not create it
            let summaryContainer = document.getElementById('todaySummary');

            if (!summaryContainer) {
                // Create the summary section
                const buttonContainer = document.getElementById('attendanceButtonsContainer');
                summaryContainer = document.createElement('div');
                summaryContainer.id = 'todaySummary';
                summaryContainer.style.cssText = 'margin-top: 32px; display: flex; justify-content: center; gap: 40px;';
                summaryContainer.innerHTML = `
                    <div>
                        <p style="font-size: 12px; opacity: 0.7;">TIME IN</p>
                        <p id="summaryTimeIn" style="font-size: 24px; font-weight: 600;">--:--</p>
                    </div>
                    <div>
                        <p style="font-size: 12px; opacity: 0.7;">TIME OUT</p>
                        <p id="summaryTimeOut" style="font-size: 24px; font-weight: 600;">--:--</p>
                    </div>
                    <div>
                        <p style="font-size: 12px; opacity: 0.7;">HOURS TODAY</p>
                        <p id="summaryHours" style="font-size: 24px; font-weight: 600;" data-is-working="false" data-time-in="">0.00</p>
                    </div>
                `;
                buttonContainer.parentNode.insertBefore(summaryContainer, buttonContainer.nextSibling);
            }

            // Update values
            if (timeIn) {
                const timeInEl = document.getElementById('summaryTimeIn');
                if (timeInEl) timeInEl.textContent = timeIn;
            }
            if (timeOut) {
                const timeOutEl = document.getElementById('summaryTimeOut');
                if (timeOutEl) timeOutEl.textContent = timeOut;
            }

            const hoursEl = document.getElementById('summaryHours');
            if (hoursEl) {
                if (hoursWorked !== undefined && hoursWorked !== null) {
                    hoursEl.textContent = parseFloat(hoursWorked).toFixed(2);
                    hoursEl.dataset.isWorking = 'false'; // Stop live updates when timed out
                }

                // Set up live tracking if rawTimeIn is provided (just timed in)
                if (rawTimeIn && !timeOut) {
                    hoursEl.dataset.timeIn = rawTimeIn;
                    hoursEl.dataset.isWorking = 'true';
                }
            }
        }

        function showAttendanceNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 16px 24px;
                border-radius: 12px;
                color: white;
                font-weight: 600;
                z-index: 10000;
                animation: slideIn 0.3s ease;
                display: flex;
                align-items: center;
                gap: 10px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            `;

            if (type === 'success') {
                notification.style.background = 'linear-gradient(135deg, #10B981 0%, #059669 100%)';
                notification.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            } else {
                notification.style.background = 'linear-gradient(135deg, #EF4444 0%, #DC2626 100%)';
                notification.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            }

            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Real-time Philippine Clock
        function updateClock() {
            const options = {
                timeZone: 'Asia/Manila',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };

            const dateOptions = {
                timeZone: 'Asia/Manila',
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };

            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', options);
            const dateString = now.toLocaleDateString('en-US', dateOptions);

            const timeElement = document.getElementById('currentTime');
            const dateElement = document.getElementById('currentDate');

            if (timeElement) timeElement.textContent = timeString;
            if (dateElement) dateElement.textContent = dateString;
        }

        // Update live hours worked while still working
        function updateLiveHours() {
            const hoursElement = document.getElementById('summaryHours');
            if (!hoursElement) return;

            const isWorking = hoursElement.dataset.isWorking === 'true';
            const timeIn = hoursElement.dataset.timeIn;

            if (!isWorking || !timeIn) return;

            // Parse time_in (format: HH:MM:SS)
            const today = new Date();
            const [hours, minutes, seconds] = timeIn.split(':').map(Number);

            // Create time_in date in Manila timezone
            const timeInDate = new Date();
            timeInDate.setHours(hours, minutes, seconds, 0);

            // Get current Manila time
            const manilaTime = new Date(today.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));

            // Calculate difference in hours
            const diffMs = manilaTime - timeInDate;
            const diffHours = Math.max(0, diffMs / (1000 * 60 * 60));

            hoursElement.textContent = diffHours.toFixed(2);
        }

        // Update clock and hours every second
        updateClock();
        updateLiveHours();
        setInterval(updateClock, 1000);
        setInterval(updateLiveHours, 1000);

        // Start task (update status to 'In Progress')
        function startTask(taskId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch(`/admin/tasks/${taskId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    status: 'In Progress'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Task started! Good luck!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to start task'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error starting task: ' + error.message);
            });
        }

        // Complete task (update status to 'Completed')
        function completeTask(taskId) {
            if (!confirm('Mark this task as completed?')) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch(`/admin/tasks/${taskId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Congratulations! Task completed!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to complete task'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error completing task: ' + error.message);
            });
        }

        // Update task status
        function updateTaskStatus(taskId, status) {
            if (!confirm('Update task status to ' + status + '?')) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch(`/admin/tasks/${taskId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Task updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update task'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating task: ' + error.message);
            });
        }
    </script>
    @endif
</body>
</html>
