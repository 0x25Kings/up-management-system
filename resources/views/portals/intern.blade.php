<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/upLogo.png') }}">
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .registration-container {
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
        }

        .registration-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 640px) {
            .registration-page {
                padding: 16px;
                align-items: flex-start;
                padding-top: 40px;
            }

            .registration-card {
                padding: 24px;
                border-radius: 16px;
            }

            .form-header .logo {
                width: 64px;
                height: 64px;
                font-size: 28px;
            }

            .form-header h1 {
                font-size: 20px !important;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .btn-submit {
                padding: 12px;
                font-size: 14px;
            }

            .access-code-section {
                padding: 16px;
            }

            .access-code-section > form > div[style*="display: flex"] {
                flex-direction: column;
            }

            .access-code-section input {
                width: 100%;
            }

            .access-code-section button {
                width: 100%;
            }

            /* Team Leader Password Field Mobile Fix */
            .access-code-section input[type="password"] {
                width: 100% !important;
                height: 50px !important;
                min-height: 50px !important;
                padding: 14px 16px !important;
                font-size: 16px !important;
                box-sizing: border-box !important;
                -webkit-appearance: none !important;
                appearance: none !important;
            }

            .tl-password-row {
                flex-direction: column !important;
                flex-wrap: wrap !important;
            }

            .tl-password-row input,
            .tl-password-row button {
                width: 100% !important;
            }

            .tl-password-input {
                height: 50px !important;
                min-height: 50px !important;
                font-size: 16px !important;
            }

            .tl-login-btn {
                width: 100% !important;
                justify-content: center !important;
            }

            /* Pending/Rejected pages responsive */
            .registration-card[style*="padding: 60px 40px"] {
                padding: 32px 20px !important;
            }

            .registration-card [style*="width: 100px"][style*="height: 100px"] {
                width: 80px !important;
                height: 80px !important;
            }

            .registration-card [style*="font-size: 48px"] {
                font-size: 36px !important;
            }

            .registration-card h2[style*="font-size: 24px"] {
                font-size: 20px !important;
            }

            .registration-card [style*="grid-template-columns: 1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
        }

        .form-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .form-header .logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 32px;
            color: #7B1D3A;
            box-shadow: 0 4px 15px rgba(255, 191, 0, 0.4);
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

        /* Top Header Styles */
        .top-header {
            background: white;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
            border-radius: 12px;
        }

        .header-title {
            font-size: 24px;
            font-weight: 700;
            color: #1F2937;
        }

        .header-subtitle {
            color: #6B7280;
            font-size: 14px;
        }

        /* Profile Dropdown Styles */
        .intern-profile-dropdown {
            position: relative;
            z-index: 1001;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7B1D3A;
            font-weight: 700;
            font-size: 16px;
        }

        .user-avatar-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-name {
            font-weight: 700;
            color: #1F2937;
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            color: #7B1D3A;
            font-weight: 600;
        }

        .intern-profile-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            min-width: 240px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
            z-index: 1002;
        }

        .intern-profile-dropdown.active .intern-profile-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .intern-profile-menu-header {
            padding: 16px;
            border-bottom: 1px solid #E5E7EB;
            text-align: center;
        }

        .intern-profile-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFBF00;
            font-weight: 700;
            font-size: 18px;
            margin: 0 auto 10px;
        }

        .intern-profile-avatar-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .intern-profile-name {
            font-weight: 600;
            color: #1F2937;
            font-size: 15px;
        }

        .intern-profile-email {
            font-size: 12px;
            color: #6B7280;
            margin-top: 2px;
        }

        .intern-profile-menu-items {
            padding: 8px;
        }

        .intern-profile-menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            color: #374151;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 14px;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
            text-align: left;
        }

        .intern-profile-menu-item:hover {
            background: #F3F4F6;
            color: #7B1D3A;
        }

        .intern-profile-menu-item i {
            width: 18px;
            text-align: center;
            color: #6B7280;
        }

        .intern-profile-menu-item:hover i {
            color: #7B1D3A;
        }

        .intern-profile-dropdown.active .user-info i.fa-chevron-down {
            transform: rotate(180deg);
        }

        .user-info i.fa-chevron-down {
            transition: transform 0.3s;
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
            width: 100%;
            display: block;
            position: relative;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            border-radius: 10px;
            transition: width 0.5s ease;
            min-width: 0;
            display: block;
            position: absolute;
            top: 0;
            left: 0;
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

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 1100;
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 15px rgba(123, 29, 58, 0.3);
            transition: all 0.3s ease;
        }

        .mobile-menu-btn:hover {
            transform: scale(1.05);
        }

        .mobile-menu-btn.active {
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            color: #7B1D3A;
            display: none;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
        }

        .sidebar-close-btn {
            display: none;
            position: absolute;
            top: 16px;
            right: 16px;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: white;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .sidebar-close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
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

            /* Quick actions and activity grid */
            [style*="grid-template-columns: repeat(2, 1fr)"][style*="gap: 20px"] {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: flex;
            }

            .sidebar-overlay {
                display: block;
                pointer-events: none;
            }

            .sidebar-overlay.active {
                pointer-events: auto;
            }

            .sidebar-close-btn {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 16px;
                padding-top: 16px;
            }

            .top-header {
                padding: 12px 16px;
                margin-bottom: 16px;
            }

            .header-title {
                font-size: 18px;
            }

            .header-subtitle {
                font-size: 12px;
            }

            .user-info > div {
                display: none;
            }

            .user-info i.fa-chevron-down {
                display: none;
            }

            .intern-profile-menu {
                right: -8px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .stat-card {
                padding: 16px;
            }

            .stat-card .stat-icon {
                width: 44px;
                height: 44px;
                font-size: 18px;
            }

            .stat-card .stat-value {
                font-size: 24px;
            }

            .stat-card .stat-label {
                font-size: 13px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .profile-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                padding: 16px;
                margin-bottom: 16px;
            }

            .page-title {
                font-size: 20px;
            }

            .content-card {
                border-radius: 10px;
            }

            .content-card-header {
                padding: 16px;
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .content-card-title {
                font-size: 16px;
            }

            .content-card-body {
                padding: 16px;
            }

            /* Modal responsive */
            .modal-content {
                width: 95%;
                max-height: 90vh;
                margin: 16px;
            }

            .modal-header {
                padding: 20px;
            }

            .modal-header h2 {
                font-size: 18px;
            }

            .modal-body {
                padding: 20px;
            }

            /* Table responsive */
            table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            th, td {
                padding: 10px 12px;
                font-size: 13px;
                white-space: nowrap;
            }

            /* Buttons on mobile */
            .btn-primary, .btn-secondary {
                padding: 12px 16px;
                font-size: 14px;
            }

            /* Quick action buttons in content card */
            .content-card-body .btn-primary,
            .content-card-body .btn-secondary {
                padding: 14px 16px;
                font-size: 14px;
                border-radius: 10px;
            }

            .content-card-body .btn-primary i,
            .content-card-body .btn-secondary i {
                font-size: 16px;
            }

            /* Task and Activity cards */
            .task-card, .activity-item {
                padding: 14px;
            }

            /* Profile grid items */
            .profile-item {
                padding: 14px;
            }

            .profile-item label {
                font-size: 11px;
            }

            .profile-item p {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .mobile-menu-btn {
                top: 12px;
                left: 12px;
                width: 44px;
                height: 44px;
                font-size: 18px;
            }

            .main-content {
                padding: 12px;
                padding-top: 72px;
            }

            .page-header {
                padding: 14px;
            }

            .page-title {
                font-size: 18px;
            }

            .page-subtitle {
                font-size: 13px;
            }

            .stat-card {
                padding: 14px;
            }

            .stat-card .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .stat-card .stat-value {
                font-size: 22px;
            }

            .content-card-header,
            .content-card-body {
                padding: 14px;
            }

            .intern-avatar {
                width: 56px;
                height: 56px;
                font-size: 22px;
            }

            .intern-name {
                font-size: 14px;
            }

            .intern-code {
                font-size: 11px;
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
                    <form action="{{ route('intern.access') }}" method="POST" id="accessForm">
                        @csrf
                        <h4 style="font-size: 16px; font-weight: 600; color: #7B1D3A; margin-bottom: 16px;">
                            <i class="fas fa-key" style="color: #FFBF00; margin-right: 8px;"></i>
                            Access Your Dashboard
                        </h4>
                        <p style="font-size: 13px; color: #6B7280; margin-bottom: 16px;">
                            Already registered? Enter your reference code to access your dashboard.
                            <br><span style="color: #9CA3AF; font-size: 12px;">Intern: INT-XXXX-XXXXXX | Team Leader: TL-XXXX-XXXX</span>
                        </p>

                        @if(session('show_password'))
                        <!-- Team Leader Password Entry -->
                        <div style="background: #F0FDF4; border: 2px solid #86EFAC; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #FFBF00, #FFA500); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-tie" style="color: #7B1D3A;"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #166534;">Team Leader Access</div>
                                    <div style="font-size: 13px; color: #15803D;">{{ session('tl_name') }}</div>
                                </div>
                            </div>
                            <input type="hidden" name="reference_code" value="{{ old('reference_code') }}">
                            <div style="display: flex; gap: 12px; flex-wrap: wrap;" class="tl-password-row">
                                <input type="password" name="password" placeholder="Enter your password" class="tl-password-input" style="flex: 1; min-width: 150px; padding: 12px 16px; border: 2px solid #E5E7EB; border-radius: 10px; font-size: 14px; box-sizing: border-box;" autofocus>
                                <button type="submit" class="btn-primary tl-login-btn" style="padding: 12px 20px; background: linear-gradient(135deg, #7B1D3A, #5a1428); white-space: nowrap; flex-shrink: 0;">
                                    <i class="fas fa-unlock" style="margin-right: 6px;"></i> Login
                                </button>
                            </div>
                            @error('password')
                                <div style="color: #DC2626; font-size: 13px; margin-top: 8px;">{{ $message }}</div>
                            @enderror
                            <a href="{{ route('intern.portal') }}" style="display: inline-block; margin-top: 12px; color: #6B7280; font-size: 13px; text-decoration: none;">
                                <i class="fas fa-arrow-left" style="margin-right: 4px;"></i> Use different code
                            </a>
                        </div>
                        @else
                        <!-- Regular Reference Code Entry -->
                        <div style="display: flex; gap: 12px;">
                            <input type="text" name="reference_code" value="{{ old('reference_code') }}" placeholder="Enter reference code (e.g., INT-2026-XXXXXX)" style="flex: 1; padding: 14px 16px; border: 2px solid #E5E7EB; border-radius: 10px; font-size: 14px;">
                            <button type="submit" class="btn-primary" style="padding: 14px 24px;">
                                <i class="fas fa-sign-in-alt" style="margin-right: 6px;"></i> Access
                            </button>
                        </div>
                        @error('reference_code')
                            <div style="color: #DC2626; font-size: 13px; margin-top: 8px;">{{ $message }}</div>
                        @enderror
                        @endif
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
                                @if($school && is_object($school))
                                    @php
                                        $isFull = ($school->max_interns ?? null) && !$school->hasCapacity();
                                        $remainingSlots = $school->getRemainingSlots();
                                        $capacityInfo = ($school->max_interns ?? null)
                                            ? ($isFull ? " - FULL" : " - {$remainingSlots} slots remaining")
                                            : '';
                                    @endphp
                                    <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }} {{ $isFull ? 'disabled' : '' }}>
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

        // Only open registration modal if there are registration form errors (not reference code errors)
        @if($errors->any() && !$errors->has('reference_code') && !$errors->has('password'))
            openRegistrationModal();
        @endif
    </script>
    @else





    <!-- Dashboard -->
    <!-- Mobile Menu Toggle Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn" onclick="toggleMobileSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <!-- Mobile Close Button -->
        <button class="sidebar-close-btn" onclick="closeMobileSidebar()">
            <i class="fas fa-times"></i>
        </button>

        <div class="sidebar-logo">
            <img src="/images/UP logo.png" alt="UP Logo">
            <h3>University of the Philippines Cebu</h3>
            <p>Intern Portal</p>
        </div>

        <nav class="sidebar-menu">
            <a class="menu-item active" data-page="dashboard" onclick="showPage('dashboard'); setActiveMenu(this);">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a class="menu-item" data-page="attendance" onclick="showPage('attendance'); setActiveMenu(this);">
                <i class="fas fa-clock"></i>
                <span>Attendance</span>
            </a>
            <a class="menu-item" data-page="tasks" onclick="showPage('tasks'); setActiveMenu(this);">
                <i class="fas fa-tasks"></i>
                <span>My Tasks</span>
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
        <!-- Top Header with Profile Dropdown -->
        <div class="top-header">
            <div>
                <h1 class="header-title" id="pageTitle">Dashboard</h1>
                <p class="header-subtitle">{{ $intern->school }}</p>
            </div>
            <div style="display: flex; align-items: center; gap: 20px;">
                <div class="intern-profile-dropdown" id="internProfileDropdown">
                    <button type="button" class="user-info" id="internProfileBtn" style="cursor: pointer; border: none; background: transparent; padding: 0;">
                        @if($intern->profile_picture)
                            <img src="{{ asset('storage/' . $intern->profile_picture) }}" alt="Profile" class="user-avatar-img">
                        @else
                            <div class="user-avatar">{{ strtoupper(substr($intern->name, 0, 1)) }}</div>
                        @endif
                        <div>
                            <div class="user-name">{{ $intern->name }}</div>
                            <div class="user-role">Intern</div>
                        </div>
                        <i class="fas fa-chevron-down" style="color: #9CA3AF; font-size: 12px; margin-left: 8px;"></i>
                    </button>
                    <div class="intern-profile-menu">
                        <div class="intern-profile-menu-header">
                            @if($intern->profile_picture)
                                <img src="{{ asset('storage/' . $intern->profile_picture) }}" alt="Profile" class="intern-profile-avatar-img">
                            @else
                                <div class="intern-profile-avatar">{{ strtoupper(substr($intern->name, 0, 1)) }}</div>
                            @endif
                            <div class="intern-profile-name">{{ $intern->name }}</div>
                            <div class="intern-profile-email">{{ $intern->email }}</div>
                        </div>
                        <div class="intern-profile-menu-items">
                            <a href="#" class="intern-profile-menu-item" onclick="navigateToProfile(event)">
                                <i class="fas fa-user-circle"></i>
                                My Profile
                            </a>
                            <form action="{{ route('intern.clear') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="intern-profile-menu-item" style="color: #DC2626;">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    <div class="stat-value">
                        @php
                            $actualCompletedHours = 0;
                            if ($attendanceHistory && $attendanceHistory->count() > 0) {
                                foreach ($attendanceHistory as $record) {
                                    if ($record->time_in && $record->time_out) {
                                        $actualCompletedHours += max(0, (float) $record->effective_hours);
                                    }
                                }
                            }
                            echo number_format(max(0, $actualCompletedHours), 2);
                        @endphp
                    </div>
                    <div class="stat-label">Hours Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #FEF3C7; color: #D97706;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stat-value">
                        @php
                            echo max(0, (float) $intern->required_hours - max(0, (float) $actualCompletedHours));
                        @endphp
                    </div>
                    <div class="stat-label">Hours Remaining</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #D1FAE5; color: #059669;">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-value">
                        @php
                            $completedTasks = 0;
                            if ($intern->tasks) {
                                $completedTasks = $intern->tasks
                                    ->filter(fn($t) => $t->status === 'Completed' && !empty($t->completed_date))
                                    ->count();
                            }
                            echo $completedTasks;
                        @endphp
                    </div>
                    <div class="stat-label">Tasks Completed (Approved)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #EDE9FE; color: #7C3AED;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-value">
                        @php
                            $totalTasks = 0;
                            if ($intern->tasks) {
                                $totalTasks = $intern->tasks->count();
                            }
                            echo $totalTasks;
                        @endphp
                    </div>
                    <div class="stat-label">Total Tasks Assigned</div>
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
                                    $actualCompletedHours += max(0, (float) $record->effective_hours);
                                }
                            }
                        }
                        $progressPercentage = $intern->required_hours > 0 ? round(($actualCompletedHours / $intern->required_hours) * 100, 1) : 0;
                    @endphp
                    <span data-progress-text style="font-size: 24px; font-weight: 700; color: #7B1D3A;">{{ min($progressPercentage, 100) }}%</span>
                </div>
                <div class="content-card-body">
                    <div class="progress-bar-container">
                        <div class="progress-bar" data-progress-bar style="width: {{ min($progressPercentage, 100) }}%;"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 12px; color: #6B7280; font-size: 14px;">
                        <span data-completed-hours>{{ number_format(max(0, $actualCompletedHours), 2) }} hrs</span>
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
                            @php
                                $hasTimeIn = $todayAttendance && $todayAttendance->time_in;
                                $hasTimeOut = $todayAttendance && $todayAttendance->time_out;
                            @endphp

                            @if(!$hasTimeIn)
                            <button type="button" class="btn-primary" style="justify-content: flex-start; display: flex; align-items: center; cursor: pointer;" onclick="showPage('attendance');">
                                <i class="fas fa-clock" style="margin-right: 10px;"></i>
                                Log Time In
                            </button>
                            @elseif(!$hasTimeOut)
                            <button type="button" class="btn-primary" style="justify-content: flex-start; display: flex; align-items: center; cursor: pointer; background: #F59E0B;" onclick="showPage('attendance');">
                                <i class="fas fa-sign-out-alt" style="margin-right: 10px;"></i>
                                Log Time Out
                            </button>
                            @else
                            <button type="button" class="btn-secondary" style="justify-content: flex-start; display: flex; align-items: center; cursor: not-allowed; opacity: 0.6;" disabled>
                                <i class="fas fa-check-circle" style="margin-right: 10px;"></i>
                                Attendance Logged Today
                            </button>
                            @endif

                            @php
                                $pendingTasks = $intern->tasks ? $intern->tasks->whereIn('status', ['Not Started', 'In Progress'])->count() : 0;
                            @endphp
                            <button type="button" class="btn-primary" style="justify-content: flex-start; display: flex; align-items: center; cursor: pointer;" onclick="showPage('tasks');">
                                <i class="fas fa-tasks" style="margin-right: 10px;"></i>
                                View Tasks (<span data-pending-tasks>{{ $pendingTasks }} pending</span>)
                            </button>
                            <button type="button" class="btn-secondary" style="justify-content: flex-start; display: flex; align-items: center;">
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
                        @php
                            $recentActivities = [];

                            // Get today's attendance
                            if ($todayAttendance && $todayAttendance->time_in) {
                                $recentActivities[] = [
                                    'type' => 'attendance',
                                    'icon' => 'fa-clock',
                                    'title' => 'Time In Logged',
                                    'description' => 'Logged in at ' . \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A'),
                                    'time' => \Carbon\Carbon::parse($todayAttendance->time_in)->diffForHumans(),
                                    'timestamp' => $todayAttendance->time_in
                                ];
                            }

                            if ($todayAttendance && $todayAttendance->time_out) {
                                $recentActivities[] = [
                                    'type' => 'attendance',
                                    'icon' => 'fa-sign-out-alt',
                                    'title' => 'Time Out Logged',
                                    'description' => 'Logged out at ' . \Carbon\Carbon::parse($todayAttendance->time_out)->format('h:i A'),
                                    'time' => \Carbon\Carbon::parse($todayAttendance->time_out)->diffForHumans(),
                                    'timestamp' => $todayAttendance->time_out
                                ];
                            }

                            // Get recent document uploads
                            $recentDocuments = \App\Models\Document::where('intern_id', $intern->id)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

                            foreach ($recentDocuments as $document) {
                                $recentActivities[] = [
                                    'type' => 'document',
                                    'icon' => 'fa-file-upload',
                                    'title' => 'Document Uploaded',
                                    'description' => $document->name . ' (' . $document->file_size . ')',
                                    'time' => $document->created_at->diffForHumans(),
                                    'timestamp' => $document->created_at
                                ];
                            }

                            // Get recent folder creations
                            $recentFolders = \App\Models\DocumentFolder::where('intern_id', $intern->id)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

                            foreach ($recentFolders as $folder) {
                                $recentActivities[] = [
                                    'type' => 'folder',
                                    'icon' => 'fa-folder-plus',
                                    'title' => 'Folder Created',
                                    'description' => $folder->name,
                                    'time' => $folder->created_at->diffForHumans(),
                                    'timestamp' => $folder->created_at
                                ];
                            }

                            // Get recent tasks
                            if ($intern->tasks) {
                                foreach ($intern->tasks->sortByDesc('updated_at')->take(5) as $task) {
                                    if ($task->status === 'Completed' && !empty($task->completed_date)) {
                                        $recentActivities[] = [
                                            'type' => 'task',
                                            'icon' => 'fa-check-circle',
                                            'title' => 'Task Completed',
                                            'description' => $task->title,
                                            'time' => $task->updated_at->diffForHumans(),
                                            'timestamp' => $task->updated_at
                                        ];
                                    } elseif ($task->status === 'Completed' && empty($task->completed_date)) {
                                        $recentActivities[] = [
                                            'type' => 'task',
                                            'icon' => 'fa-clock',
                                            'title' => 'Task Submitted',
                                            'description' => $task->title,
                                            'time' => $task->updated_at->diffForHumans(),
                                            'timestamp' => $task->updated_at
                                        ];
                                    }
                                }
                            }

                            // Get recent attendance
                            if ($attendanceHistory) {
                                foreach ($attendanceHistory->sortByDesc('created_at')->take(5) as $attendance) {
                                    if ($attendance->time_in && $attendance->time_out && $attendance->date !== today()->toDateString()) {
                                        $recentActivities[] = [
                                            'type' => 'attendance',
                                            'icon' => 'fa-calendar-check',
                                            'title' => 'Attendance Logged',
                                            'description' => \Carbon\Carbon::parse($attendance->date)->format('F d, Y'),
                                            'time' => \Carbon\Carbon::parse($attendance->date)->diffForHumans(),
                                            'timestamp' => $attendance->created_at
                                        ];
                                    }
                                }
                            }

                            // Sort by timestamp (most recent first) and limit to 5
                            $recentActivities = collect($recentActivities)->sortByDesc(function($activity) {
                                return $activity['timestamp'] ?? null;
                            })->take(5);
                        @endphp

                        @if($recentActivities->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @foreach($recentActivities as $activity)
                            @php
                                $iconBg = match($activity['type']) {
                                    'document' => '#DBEAFE',
                                    'folder' => '#FEF3C7',
                                    'task' => '#D1FAE5',
                                    'attendance' => '#EDE9FE',
                                    default => '#F3F4F6'
                                };
                                $iconColor = match($activity['type']) {
                                    'document' => '#3B82F6',
                                    'folder' => '#F59E0B',
                                    'task' => '#10B981',
                                    'attendance' => '#7C3AED',
                                    default => '#6B7280'
                                };
                            @endphp
                            <div style="padding: 12px; background: #F9FAFB; border-radius: 8px; border-left: 4px solid {{ $iconColor }};">
                                <div style="display: flex; align-items: flex-start; gap: 12px;">
                                    <div style="width: 40px; height: 40px; background: {{ $iconBg }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: {{ $iconColor }}; flex-shrink: 0;">
                                        <i class="fas {{ $activity['icon'] }}"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #1F2937; font-size: 13px;">{{ $activity['title'] }}</div>
                                        <div style="color: #6B7280; font-size: 12px; margin-top: 2px;">{{ $activity['description'] }}</div>
                                        <div style="color: #9CA3AF; font-size: 11px; margin-top: 4px;">{{ $activity['time'] }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div style="text-align: center; padding: 30px; color: #9CA3AF;">
                            <i class="fas fa-inbox" style="font-size: 40px; margin-bottom: 12px;"></i>
                            <p>No recent activity</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Page -->
        <div id="profile" class="page-content">
            <!-- Profile Picture Section -->
            <div class="content-card" style="margin-bottom: 20px;">
                <div class="content-card-body" style="text-align: center; padding: 40px;">
                    <div style="position: relative; display: inline-block; margin-bottom: 20px;">
                        <div id="profilePicturePreview" style="width: 150px; height: 150px; border-radius: 50%; background: linear-gradient(135deg, #7B1D3A, #5a1428); display: flex; align-items: center; justify-content: center; margin: 0 auto; overflow: hidden; box-shadow: 0 8px 24px rgba(123, 29, 58, 0.3);">
                            @if($intern->profile_picture)
                                <img src="{{ asset('storage/' . $intern->profile_picture) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="font-size: 48px; color: #FFBF00; font-weight: 700;">{{ substr($intern->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <label for="profilePictureInput" style="position: absolute; bottom: 5px; right: 5px; background: #FFBF00; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.2); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            <i class="fas fa-camera" style="color: #7B1D3A;"></i>
                        </label>
                        <input type="file" id="profilePictureInput" accept="image/*" style="display: none;" onchange="uploadProfilePicture(this)">
                    </div>
                    <h3 style="font-size: 20px; font-weight: 700; color: #1F2937; margin-bottom: 4px;">{{ $intern->name }}</h3>
                    <p style="color: #6B7280; font-size: 14px;">{{ $intern->course }} - {{ $intern->school }}</p>
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

            <!-- Edit Profile Modal -->
            <div id="editProfileModal" class="modal-overlay" onclick="closeEditProfileModal(event)">
                <div class="modal-content" onclick="event.stopPropagation()">
                    <div class="modal-header">
                        <div>
                            <h2><i class="fas fa-user-edit" style="margin-right: 8px;"></i>Edit Profile</h2>
                            <p style="font-size: 13px; opacity: 0.8; margin-top: 4px;">Update your personal information</p>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form id="editProfileForm" action="{{ route('intern.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label><i class="fas fa-user" style="color: #7B1D3A; margin-right: 6px;"></i>Full Name</label>
                                <input type="text" name="name" id="editName" value="{{ $intern->name }}" placeholder="Enter your full name" required>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-phone" style="color: #7B1D3A; margin-right: 6px;"></i>Phone Number</label>
                                <input type="text" name="phone" id="editPhone" value="{{ $intern->phone }}" placeholder="09XX XXX XXXX" required>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-university" style="color: #7B1D3A; margin-right: 6px;"></i>School</label>
                                <input type="text" name="school" id="editSchool" value="{{ $intern->school }}" placeholder="Enter your school" required>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-graduation-cap" style="color: #7B1D3A; margin-right: 6px;"></i>Course</label>
                                <input type="text" name="course" id="editCourse" value="{{ $intern->course }}" placeholder="Enter your course" required>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-layer-group" style="color: #7B1D3A; margin-right: 6px;"></i>Year Level</label>
                                <select name="year_level" id="editYearLevel">
                                    <option value="">Select Year Level</option>
                                    <option value="1st Year" {{ $intern->year_level == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2nd Year" {{ $intern->year_level == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3rd Year" {{ $intern->year_level == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4th Year" {{ $intern->year_level == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                    <option value="5th Year" {{ $intern->year_level == '5th Year' ? 'selected' : '' }}>5th Year</option>
                                    <option value="Graduate" {{ $intern->year_level == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                </select>
                            </div>

                            <div style="display: flex; gap: 12px; margin-top: 24px;">
                                <button type="button" onclick="closeEditProfileModal()" style="flex: 1; padding: 14px; border: 2px solid #E5E7EB; background: white; border-radius: 10px; font-weight: 600; color: #6B7280; cursor: pointer; transition: all 0.3s ease;">
                                    Cancel
                                </button>
                                <button type="submit" id="editProfileSubmitBtn" style="flex: 1; padding: 14px; background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); border: none; border-radius: 10px; font-weight: 600; color: white; cursor: pointer; transition: all 0.3s ease;">
                                    <i class="fas fa-save" style="margin-right: 6px;"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Page -->
        <div id="attendance" class="page-content">
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
                        // Calculate hours for today only if time_out exists
                        $todayHours = null;
                        if ($todayAttendance->time_in && $todayAttendance->time_out) {
                            $attendanceDate = $todayAttendance->date ? $todayAttendance->date->toDateString() : \Carbon\Carbon::now('Asia/Manila')->toDateString();
                            $timeIn = \Carbon\Carbon::parse($attendanceDate . ' ' . $todayAttendance->time_in, 'Asia/Manila');
                            $timeOut = \Carbon\Carbon::parse($attendanceDate . ' ' . $todayAttendance->time_out, 'Asia/Manila');
                            $todayHours = round($timeOut->diffInSeconds($timeIn, true) / 3600, 2);
                            $todayHours = max(0, $todayHours);
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
                                         data-raw-time-in="{{ $todayAttendance->raw_time_in }}"
                               data-time-out="{{ $todayAttendance->time_out }}"
                               data-is-working="false">
                                {{ $todayHours !== null ? number_format($todayHours, 2) : '--' }}
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
                                $hoursWorked = null;
                                $displayStatus = 'Absent';

                                // Check if the record has time_in
                                if ($record->time_in) {
                                    $attendanceDate = $record->date ? $record->date->toDateString() : \Carbon\Carbon::now('Asia/Manila')->toDateString();
                                    $timeIn = \Carbon\Carbon::parse($attendanceDate . ' ' . $record->time_in, 'Asia/Manila');
                                    $timeOut = $record->time_out ? \Carbon\Carbon::parse($attendanceDate . ' ' . $record->time_out, 'Asia/Manila') : null;

                                    if ($timeOut) {
                                        // Calculate hours if there's both time_in and time_out
                                        $hoursWorked = round($timeOut->diffInSeconds($timeIn, true) / 3600, 2);
                                        $hoursWorked = max(0, $hoursWorked);

                                        // Determine status based on hours and time in
                                        if ($hoursWorked >= 8) {
                                            $displayStatus = 'Present';
                                        } elseif ($hoursWorked > 0) {
                                            $displayStatus = 'Undertime';
                                        }

                                        // Check if late (after 9 AM)
                                        if ($timeIn->hour >= 9) {
                                            $displayStatus = 'Late';
                                        }
                                    } else {
                                        // Has time_in but no time_out
                                        // Check if it's today - show In Progress, otherwise Absent (forgot to time out)
                                        $isToday = $record->date && $record->date->isToday();
                                        $displayStatus = $isToday ? 'In Progress' : 'Absent';
                                    }
                                } else {
                                    // No time_in at all = Absent
                                    $displayStatus = 'Absent';
                                }
                            @endphp
                            <tr style="border-bottom: 1px solid #E5E7EB;">
                                <td style="padding: 16px;">
                                    <div style="font-weight: 600; color: #1F2937;">{{ $record->date->format('M d, Y') }}</div>
                                    <div style="font-size: 12px; color: #6B7280;">{{ $record->date->format('l') }}</div>
                                </td>
                                <td style="padding: 16px; color: #059669; font-weight: 500;">{{ $record->formatted_time_in }}</td>
                                <td style="padding: 16px; color: #DC2626; font-weight: 500;">{{ $record->formatted_time_out }}</td>
                                <td style="padding: 16px; font-weight: 600;">{{ $hoursWorked !== null ? number_format($hoursWorked, 2) . ' hrs' : '--' }}</td>
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
                                        @elseif($displayStatus === 'In Progress') background: #DBEAFE; color: #1E40AF;
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
            <div class="content-card">
                <div class="content-card-body">
                    @if(($tasks ?? collect())->count() > 0)
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);">
                                    <th style="text-align: left; padding: 16px 20px; font-weight: 600; color: white; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Task</th>
                                    <th style="text-align: center; padding: 16px 20px; font-weight: 600; color: white; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Priority</th>
                                    <th style="text-align: center; padding: 16px 20px; font-weight: 600; color: white; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Due Date</th>
                                    <th style="text-align: center; padding: 16px 20px; font-weight: 600; color: white; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                                    <th style="text-align: center; padding: 16px 20px; font-weight: 600; color: white; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                @php
                                    $isPendingAdminApproval = $task->status === 'Completed' && empty($task->completed_date);
                                    $daysLeft = now('Asia/Manila')->startOfDay()->diffInDays(\Carbon\Carbon::parse($task->due_date)->startOfDay(), false);
                                    $isOverdue = $daysLeft < 0;
                                @endphp
                                <tr style="border-bottom: 1px solid #E5E7EB; transition: all 0.2s;" onmouseover="this.style.background='#FDF2F4'" onmouseout="this.style.background='white'">
                                    <td style="padding: 18px 20px;">
                                        <div style="font-weight: 600; color: #1F2937; font-size: 14px; margin-bottom: 4px;">{{ $task->title }}</div>
                                        @if($task->description)
                                            <div style="color: #6B7280; font-size: 12px; line-height: 1.4;">{{ strlen($task->description) > 60 ? substr($task->description, 0, 60) . '...' : $task->description }}</div>
                                        @endif
                                    </td>
                                    <td style="padding: 18px 20px; text-align: center;">
                                        <span style="display: inline-flex; align-items: center; gap: 6px; background:
                                            @if($task->priority === 'High') linear-gradient(135deg, #DC2626, #B91C1C);
                                            @elseif($task->priority === 'Medium') linear-gradient(135deg, #F59E0B, #D97706);
                                            @else linear-gradient(135deg, #10B981, #059669);
                                            @endif
                                            color: white; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <i class="fas @if($task->priority === 'High') fa-fire @elseif($task->priority === 'Medium') fa-minus-circle @else fa-leaf @endif" style="font-size: 10px;"></i>
                                            {{ $task->priority }}
                                        </span>
                                    </td>
                                    <td style="padding: 18px 20px; text-align: center;">
                                        <div style="font-weight: 600; color: #1F2937;">{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</div>
                                        @if($isOverdue && $task->status !== 'Completed')
                                            <div style="font-size: 11px; color: #991B1B; margin-top: 4px; background: #FEE2E2; padding: 2px 8px; border-radius: 10px; display: inline-block;">
                                                <i class="fas fa-exclamation-circle"></i> {{ abs($daysLeft) }} day(s) overdue
                                            </div>
                                        @elseif($daysLeft === 0 && $task->status !== 'Completed')
                                            <div style="font-size: 11px; color: #92400E; margin-top: 4px; background: #FEF3C7; padding: 2px 8px; border-radius: 10px; display: inline-block;">
                                                <i class="fas fa-hourglass-end"></i> Due Today
                                            </div>
                                        @elseif($daysLeft > 0 && $daysLeft <= 3 && $task->status !== 'Completed')
                                            <div style="font-size: 11px; color: #1E40AF; margin-top: 4px; background: #DBEAFE; padding: 2px 8px; border-radius: 10px; display: inline-block;">
                                                <i class="fas fa-clock"></i> {{ $daysLeft }} day(s) left
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding: 18px 20px; text-align: center;">
                                        <span style="display: inline-flex; align-items: center; gap: 6px; background:
                                            @if($isPendingAdminApproval) linear-gradient(135deg, #DBEAFE, #BFDBFE); color: #1E40AF;
                                            @elseif($task->status === 'Completed') linear-gradient(135deg, #D1FAE5, #A7F3D0); color: #065F46;
                                            @elseif($task->status === 'In Progress') linear-gradient(135deg, #FEF3C7, #FDE68A); color: #92400E;
                                            @else linear-gradient(135deg, #F3F4F6, #E5E7EB); color: #6B7280;
                                            @endif
                                            padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></span>
                                            {{ $isPendingAdminApproval ? 'Awaiting Approval' : $task->status }}
                                        </span>
                                    </td>
                                    <td style="padding: 18px 20px; text-align: center;">
                                        @if($task->status === 'Completed')
                                            <div style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 500;
                                                @if($isPendingAdminApproval) background: #EFF6FF; color: #1E40AF;
                                                @else background: #ECFDF5; color: #059669; @endif">
                                                @if($isPendingAdminApproval)
                                                    <i class="fas fa-paper-plane"></i> Submitted
                                                @else
                                                    <i class="fas fa-check-double"></i> Approved
                                                @endif
                                            </div>
                                        @elseif($task->status === 'In Progress')
                                            <div style="display: flex; gap: 8px; justify-content: center;">
                                                <button onclick="updateTask({{ $task->id }})" style="background: linear-gradient(135deg, #3B82F6, #2563EB); color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 12px; cursor: pointer; font-weight: 600; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(59,130,246,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                                    <i class="fas fa-edit"></i> Update
                                                </button>
                                                <button onclick="completeTask({{ $task->id }})" style="background: linear-gradient(135deg, #10B981, #059669); color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 12px; cursor: pointer; font-weight: 600; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(16,185,129,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                                    <i class="fas fa-paper-plane"></i> Submit
                                                </button>
                                            </div>
                                        @else
                                            <button onclick="startTask({{ $task->id }})" style="background: linear-gradient(135deg, #7B1D3A, #5a1428); color: white; border: none; padding: 8px 20px; border-radius: 8px; font-size: 12px; cursor: pointer; font-weight: 600; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(123,29,58,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                                <i class="fas fa-play"></i> Start Task
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div style="text-align: center; padding: 80px 40px;">
                        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #F3E8FF, #DDD6FE); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                            <i class="fas fa-clipboard-list" style="font-size: 40px; color: #7B1D3A;"></i>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">No Tasks Assigned Yet</h3>
                        <p style="font-size: 14px; color: #6B7280; max-width: 300px; margin: 0 auto;">When your supervisor assigns tasks to you, they will appear here for you to manage.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Schedule Page -->
        <div id="schedule" class="page-content">
            <div class="content-card">
                <div class="content-card-body">
                    <!-- Calendar Header -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #E5E7EB;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <button onclick="previousMonth()" style="background: #F3F4F6; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer; color: #374151; transition: all 0.3s;">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <h2 id="calendarMonthYear" style="font-size: 22px; font-weight: 700; color: #1F2937; margin: 0; min-width: 200px; text-align: center;">January 2026</h2>
                            <button onclick="nextMonth()" style="background: #F3F4F6; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer; color: #374151; transition: all 0.3s;">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <button onclick="goToToday()" style="background: linear-gradient(135deg, #7B1D3A, #5a1428); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                            <i class="fas fa-calendar-day" style="margin-right: 6px;"></i>Today
                        </button>
                    </div>

                    <!-- Calendar Grid -->
                    <div id="scheduleCalendar" style="background: white; border-radius: 8px; overflow: hidden; border: 1px solid #E5E7EB;">
                        <!-- Weekday Headers -->
                        <div style="display: grid; grid-template-columns: repeat(7, 1fr); background: #F9FAFB; border-bottom: 2px solid #E5E7EB;">
                            <div style="padding: 12px; text-align: center; font-weight: 700; font-size: 14px; color: #6B7280;">Sun</div>
                            <div style="padding: 12px; text-align: center; font-weight: 700; font-size: 14px; color: #6B7280;">Mon</div>
                            <div style="padding: 12px; text-align: center; font-weight: 700; font-size: 14px; color: #6B7280;">Tue</div>
                            <div style="padding: 12px; text-align: center; font-weight: 700; font-size: 14px; color: #6B7280;">Wed</div>
                            <div style="padding: 12px; text-align: center; font-weight: 700; font-size: 14px; color: #6B7280;">Thu</div>
                            <div style="padding: 12px; text-align: center; font-weight: 700; font-size: 14px; color: #6B7280;">Fri</div>
                            <div style="padding: 12px; text-align: center; font-weight: 700; font-size: 14px; color: #6B7280;">Sat</div>
                        </div>

                        <!-- Calendar Days Grid -->
                        <div id="calendarDaysGrid" style="display: grid; grid-template-columns: repeat(7, 1fr);">
                            <div style="text-align: center; padding: 50px; color: #9CA3AF; grid-column: span 7;">
                                <i class="fas fa-spinner fa-spin" style="font-size: 50px; margin-bottom: 16px;"></i>
                                <p style="font-size: 16px;">Loading calendar...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Page -->
        <div id="documents" class="page-content">
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 20px;">
                <button class="btn-primary" onclick="showCreateFolderModal()">
                    <i class="fas fa-folder-plus" style="margin-right: 6px;"></i>
                    New Folder
                </button>
                <button class="btn-primary" onclick="document.getElementById('fileUpload').click()">
                    <i class="fas fa-upload" style="margin-right: 6px;"></i>
                    Upload Document
                </button>
                <input type="file" id="fileUpload" style="display: none;" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.ppt,.pptx,.csv" onchange="handleFileUpload(event)">
            </div>

            <div id="documentContainer" class="content-card">
                <div class="content-card-body" id="documentsContent">
                    <div style="text-align: center; padding: 50px; color: #9CA3AF;">
                        <i class="fas fa-folder-open" style="font-size: 50px; margin-bottom: 16px;"></i>
                        <p style="font-size: 16px;">No folders or documents yet</p>
                        <p style="font-size: 14px; margin-top: 8px;">Create a folder or upload documents to get started</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Folder Modal -->
        <div id="createFolderModal" class="modal" style="display: none;">
            <div class="modal-content" style="width: 90%; max-width: 500px; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                <div class="modal-header" style="padding: 20px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="margin: 0; font-size: 18px; font-weight: 600;">Create New Folder</h2>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    <div class="form-group">
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Folder Name</label>
                        <input type="text" id="folderName" style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px;" placeholder="Enter folder name">
                    </div>
                    <div class="form-group">
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Description (Optional)</label>
                        <textarea id="folderDescription" style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px; resize: vertical; min-height: 80px;" placeholder="Add a description..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 15px 20px; border-top: 1px solid #E5E7EB; display: flex; justify-content: flex-end; gap: 10px;">
                    <button onclick="closeCreateFolderModal()" style="padding: 10px 20px; background-color: #E5E7EB; color: #374151; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">Cancel</button>
                    <button onclick="createFolder()" style="padding: 10px 20px; background-color: #7B1D3A; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">Create Folder</button>
                </div>
            </div>
        </div>

        <style>
            .folder-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 16px;
                margin-bottom: 24px;
            }

            .folder-card {
                cursor: pointer;
                transition: all 0.3s ease;
                position: relative;
                user-select: none;
                text-align: center;
            }

            .folder-card:hover {
                transform: scale(1.05);
            }

            .folder-card-header {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 8px;
            }

            .folder-icon {
                font-size: 64px;
            }

            .folder-menu {
                background: none;
                border: none;
                cursor: pointer;
                font-size: 18px;
                color: #6B7280;
                padding: 4px 8px;
                border-radius: 4px;
                transition: background-color 0.2s;
            }

            .folder-menu:hover {
                background-color: rgba(0, 0, 0, 0.1);
            }

            .folder-name {
                font-weight: 600;
                font-size: 14px;
                color: #1F2937;
                margin-bottom: 4px;
                word-break: break-word;
            }

            .folder-count {
                font-size: 12px;
                color: #9CA3AF;
            }

            .documents-list {
                background: white;
                border-radius: 8px;
                padding: 16px;
            }

            .document-item {
                display: flex;
                align-items: center;
                padding: 12px;
                border-bottom: 1px solid #E5E7EB;
                transition: background-color 0.2s;
            }

            .document-item:last-child {
                border-bottom: none;
            }

            .document-item:hover {
                background-color: #F9FAFB;
            }

            .document-icon {
                font-size: 24px;
                width: 40px;
                text-align: center;
                margin-right: 12px;
                color: #6B7280;
            }

            .document-info {
                flex: 1;
            }

            .document-name {
                font-weight: 500;
                font-size: 14px;
                color: #1F2937;
                margin-bottom: 4px;
                word-break: break-all;
            }

            .document-meta {
                font-size: 12px;
                color: #9CA3AF;
            }

            .document-actions {
                display: flex;
                gap: 8px;
            }

            .doc-btn {
                background: none;
                border: none;
                cursor: pointer;
                color: #6B7280;
                font-size: 16px;
                padding: 4px 8px;
                border-radius: 4px;
                transition: all 0.2s;
            }

            .doc-btn:hover {
                background-color: #E5E7EB;
                color: #1F2937;
            }

            .modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
            }

            .modal-content {
                background: white;
                border-radius: 8px;
            }

            .modal-header {
                border-bottom: 1px solid #E5E7EB;
            }

            .modal-body {
                padding: 20px;
            }

            .modal-footer {
                border-top: 1px solid #E5E7EB;
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                padding: 15px 20px;
            }

            .color-preview {
                width: 100%;
                height: 4px;
                border-radius: 2px;
                margin-top: 8px;
            }
        </style>

        <script>
            let currentFolderId = null;
            let allFolders = [];
            let allDocuments = [];

            // Color change preview
            document.addEventListener('DOMContentLoaded', function() {
                const colorInput = document.getElementById('folderColor');
                if (colorInput) {
                    colorInput.addEventListener('change', function() {
                        updateColorPreview();
                    });
                }
                loadDocuments();
            });

            function updateColorPreview() {
                const color = document.getElementById('folderColor').value;
                const previews = document.querySelectorAll('[data-color-preview]');
                previews.forEach(preview => {
                    preview.style.backgroundColor = color;
                });
            }

            function showCreateFolderModal() {
                document.getElementById('createFolderModal').style.display = 'flex';
            }

            function closeCreateFolderModal() {
                document.getElementById('createFolderModal').style.display = 'none';
                document.getElementById('folderName').value = '';
                document.getElementById('folderDescription').value = '';
            }

            function createFolder() {
                const name = document.getElementById('folderName').value.trim();
                const description = document.getElementById('folderDescription').value.trim();

                if (!name) {
                    alert('Please enter a folder name');
                    return;
                }

                console.log('Creating folder:', { name, description, currentFolderId });

                fetch('{{ route("documents.folder.create") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: name,
                        description: description,
                        parent_folder_id: currentFolderId
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Error response:', text);
                            throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        closeCreateFolderModal();
                        loadDocuments();
                        showNotification('Folder created successfully', 'success');
                    } else {
                        showNotification(data.message || 'Failed to create folder', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error creating folder: ' + error.message, 'error');
                });
            }

            function handleFileUpload(event) {
                const files = event.target.files;
                if (files.length === 0) return;

                const maxSizeMB = 50; // 50MB limit
                const maxSizeBytes = maxSizeMB * 1024 * 1024;
                const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'rar', 'ppt', 'pptx', 'csv'];

                for (let file of files) {
                    // Check file size on client side
                    if (file.size > maxSizeBytes) {
                        const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
                        showNotification(`File "${file.name}" is too large (${fileSizeMB} MB). Maximum allowed size is ${maxSizeMB} MB.`, 'error');
                        continue;
                    }

                    // Check file extension
                    const extension = file.name.split('.').pop().toLowerCase();
                    if (!allowedExtensions.includes(extension)) {
                        showNotification(`File "${file.name}" has an unsupported format. Allowed formats: ${allowedExtensions.join(', ')}`, 'error');
                        continue;
                    }

                    uploadDocument(file);
                }

                // Reset input
                event.target.value = '';
            }

            function uploadDocument(file) {
                const formData = new FormData();
                formData.append('file', file);
                if (currentFolderId) {
                    formData.append('folder_id', currentFolderId);
                }

                fetch('{{ route("documents.upload") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => {
                    // Try to parse as JSON first
                    return response.text().then(text => {
                        let data;
                        try {
                            data = JSON.parse(text);
                        } catch (e) {
                            // Not JSON - might be PHP/server error
                            if (response.status === 413) {
                                throw new Error(`File "${file.name}" is too large. Maximum allowed size is 50 MB.`);
                            } else if (response.status === 422) {
                                throw new Error(`File "${file.name}" could not be uploaded. Please check the file size (max 50 MB) and format.`);
                            } else if (text.includes('upload_max_filesize') || text.includes('post_max_size')) {
                                throw new Error(`File "${file.name}" is too large. Maximum allowed size is 50 MB.`);
                            }
                            throw new Error(`Upload failed for "${file.name}". Please try again.`);
                        }

                        if (!response.ok) {
                            // Handle validation errors from Laravel
                            if (data.errors) {
                                const errorMessages = Object.values(data.errors).flat();
                                // Make file size errors user-friendly
                                const friendlyMessages = errorMessages.map(msg => {
                                    if (msg.includes('may not be greater than')) {
                                        return `File "${file.name}" is too large. Maximum allowed size is 50 MB.`;
                                    }
                                    return msg;
                                });
                                throw new Error(friendlyMessages.join(', '));
                            }
                            throw new Error(data.message || `Upload failed for "${file.name}"`);
                        }
                        return data;
                    });
                })
                .then(data => {
                    if (data.success) {
                        loadDocuments();
                        showNotification('Document uploaded successfully', 'success');
                    } else {
                        showNotification(data.message || 'Failed to upload document', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification(error.message, 'error');
                });
            }

            function loadDocuments() {
                const baseUrl = '{{ url("/intern/documents") }}';
                const url = currentFolderId
                    ? `${baseUrl}/folder/${currentFolderId}`
                    : baseUrl;

                fetch(url, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        renderDocuments(data);
                    } else {
                        console.error('Error loading documents:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading documents:', error);
                    showNotification('Error loading documents: ' + error.message, 'error');
                });
            }

            function renderDocuments(data) {
                const container = document.getElementById('documentsContent');
                let html = '';

                // Add back button if in a subfolder
                if (currentFolderId) {
                    html += '<div style="margin-bottom: 20px;">';
                    html += '<button onclick="currentFolderId = null; loadDocuments()" style="display: inline-flex; align-items: center; padding: 10px 16px; background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; font-size: 14px; box-shadow: 0 2px 8px rgba(123, 29, 58, 0.2); transition: all 0.3s ease;" onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 12px rgba(123, 29, 58, 0.3)\';" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 2px 8px rgba(123, 29, 58, 0.2)\';">';
                    html += '<i class="fas fa-arrow-left" style="margin-right: 8px;"></i>';
                    html += '<span>Back to Parent Folder</span>';
                    html += '</button>';
                    html += '</div>';
                }

                // Render folders
                if (data.folders && data.folders.length > 0) {
                    html += '<div class="folder-grid">';
                    data.folders.forEach(folder => {
                        const docsCount = (typeof folder.item_count === 'number')
                            ? folder.item_count
                            : (folder.documents ? folder.documents.length : 0);
                        html += `
                            <div class="folder-card" ondblclick="openFolder(${folder.id})" style="position: relative;">
                                <div style="position: absolute; top: 5px; right: 5px; z-index: 10;">
                                    <button class="folder-menu" onclick="event.stopPropagation(); toggleFolderMenu(${folder.id})">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div id="menu-${folder.id}" class="folder-menu-dropdown" style="display: none; position: absolute; right: 0; top: 30px; background: white; border: 1px solid #E5E7EB; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10; min-width: 150px;">
                                        <button onclick="openFolder(${folder.id})" style="display: block; width: 100%; text-align: left; padding: 10px 16px; border: none; background: none; cursor: pointer; font-size: 14px; color: #374151;">Open</button>
                                    </div>
                                </div>
                                <div class="folder-card-header">
                                    <div class="folder-icon" style="color: ${folder.color};"><i class="fas fa-folder"></i></div>
                                    <div class="folder-name" style="font-weight: 600; font-size: 13px; color: #1F2937;">${escapeHtml(folder.name)}</div>
                                    <div class="folder-count" style="font-size: 11px; color: #9CA3AF;">${docsCount} item${docsCount !== 1 ? 's' : ''}</div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                }

                // Render documents
                if (data.documents && data.documents.length > 0) {
                    html += '<div style="margin-top: 24px;"><h3 style="font-size: 16px; font-weight: 600; margin-bottom: 12px; color: #1F2937;">Documents</h3>';
                    html += '<div class="documents-list">';
                    data.documents.forEach(doc => {
                        const iconClass = getFileIcon(doc.file_type);
                        // For files in shared folders, use path instead of id
                        const downloadId = doc.id || btoa(doc.path);
                        const displayDate = doc.created_at ? new Date(doc.created_at).toLocaleDateString() : 'N/A';
                        html += `
                            <div class="document-item">
                                <div class="document-icon"><i class="${iconClass}"></i></div>
                                <div class="document-info">
                                    <div class="document-name">${escapeHtml(doc.name)}</div>
                                    <div class="document-meta">${doc.file_size || doc.size || 'N/A'} • ${displayDate}</div>
                                </div>
                                <div class="document-actions">
                                    <button class="doc-btn" onclick="downloadDocument('${downloadId}')" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div></div>';
                }

                // Show empty state if no content
                if ((!data.folders || data.folders.length === 0) && (!data.documents || data.documents.length === 0)) {
                    html = '<div style="text-align: center; padding: 50px; color: #9CA3AF;">';
                    html += '<i class="fas fa-folder-open" style="font-size: 50px; margin-bottom: 16px;"></i>';
                    html += '<p style="font-size: 16px;">No items in this folder</p>';
                    html += '<p style="font-size: 14px; margin-top: 8px;">Upload documents or create subfolders</p>';
                    html += '</div>';
                }

                container.innerHTML = html;
            }

            function toggleFolderMenu(folderId) {
                const menu = document.getElementById(`menu-${folderId}`);
                const allMenus = document.querySelectorAll('.folder-menu-dropdown');
                allMenus.forEach(m => m.style.display = 'none');
                if (menu) menu.style.display = 'block';
            }

            function openFolder(folderId) {
                currentFolderId = folderId;
                loadDocuments();
            }



            function downloadDocument(documentId) {
                const baseUrl = '{{ url("/intern/documents") }}';
                window.location.href = `${baseUrl}/${documentId}/download`;
            }

            function getFileIcon(mimeType) {
                const iconMap = {
                    'application/pdf': 'fas fa-file-pdf',
                    'application/msword': 'fas fa-file-word',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'fas fa-file-word',
                    'application/vnd.ms-excel': 'fas fa-file-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'fas fa-file-excel',
                    'text/plain': 'fas fa-file-alt',
                    'image/jpeg': 'fas fa-file-image',
                    'image/png': 'fas fa-file-image',
                    'image/gif': 'fas fa-file-image',
                    'application/zip': 'fas fa-file-archive',
                    'application/x-rar-compressed': 'fas fa-file-archive'
                };
                return iconMap[mimeType] || 'fas fa-file';
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function showNotification(message, type = 'info') {
                // Use existing notification system if available, otherwise create a toast
                if (window.showAlert) {
                    window.showAlert(message, type);
                } else {
                    // Create a simple toast notification
                    const toast = document.createElement('div');
                    toast.style.cssText = `
                        position: fixed;
                        bottom: 20px;
                        right: 20px;
                        padding: 16px 20px;
                        border-radius: 8px;
                        color: white;
                        font-size: 14px;
                        z-index: 9999;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                        animation: slideIn 0.3s ease-out;
                    `;

                    if (type === 'success') {
                        toast.style.backgroundColor = '#10B981';
                    } else if (type === 'error') {
                        toast.style.backgroundColor = '#EF4444';
                    } else if (type === 'warning') {
                        toast.style.backgroundColor = '#F59E0B';
                    } else {
                        toast.style.backgroundColor = '#3B82F6';
                    }

                    toast.textContent = message;
                    document.body.appendChild(toast);

                    setTimeout(() => {
                        toast.style.animation = 'slideOut 0.3s ease-out';
                        setTimeout(() => toast.remove(), 300);
                    }, 4000);
                }
            }

            // Add animations
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn {
                    from {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOut {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);

            // Close menus when clicking outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.folder-menu-dropdown').forEach(m => m.style.display = 'none');
            });
        </script>
    </main>

    <script>
        // Mobile Sidebar Toggle Functions
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuBtn = document.getElementById('mobileMenuBtn');

            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
            menuBtn.classList.toggle('active');

            if (sidebar.classList.contains('open')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuBtn = document.getElementById('mobileMenuBtn');

            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            menuBtn.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Auto-close sidebar when clicking menu items on mobile
        document.querySelectorAll('.sidebar .menu-item').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    setTimeout(closeMobileSidebar, 150);
                }
            });
        });

        // Close sidebar on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeMobileSidebar();
            }
        });

        // Calendar variables
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();
        let allEvents = [];
        let allBookings = [];

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

            // Update page title in header
            const pageTitles = {
                'dashboard': 'Dashboard',
                'profile': 'My Profile',
                'attendance': 'Time & Attendance',
                'tasks': 'My Tasks',
                'schedule': 'Schedule',
                'documents': 'Documents'
            };
            const pageTitle = document.getElementById('pageTitle');
            if (pageTitle && pageTitles[pageId]) {
                pageTitle.textContent = pageTitles[pageId];
            }

            // Load page-specific data
            if (pageId === 'schedule') {
                loadEvents();
                loadBookings();
            }
        }

        // Load events for schedule page
        function loadEvents() {
            fetch('{{ url("/intern/events") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                allEvents = data.events || [];
                renderCalendar();
            })
            .catch(error => {
                console.error('Error loading events:', error);
            });
        }

        // Load approved bookings
        function loadBookings() {
            fetch('{{ url("/bookings") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(bookings => {
                allBookings = bookings || [];
                renderCalendar();
            })
            .catch(error => {
                console.error('Error loading bookings:', error);
            });
        }

        function renderCalendar() {
            const monthNames = ["January", "February", "March", "April", "May", "June",
                               "July", "August", "September", "October", "November", "December"];

            // Update header
            document.getElementById('calendarMonthYear').textContent = `${monthNames[currentMonth]} ${currentYear}`;

            // Get first day of month and number of days
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const daysInPrevMonth = new Date(currentYear, currentMonth, 0).getDate();

            const today = new Date();
            const todayString = today.toISOString().split('T')[0];

            let html = '';

            // Previous month days
            for (let i = firstDay - 1; i >= 0; i--) {
                html += `<div style="min-height: 120px; padding: 8px; border: 1px solid #E5E7EB; background: #FAFAFA; color: #D1D5DB; display: flex; flex-direction: column;">
                    <div style="font-size: 14px; font-weight: 600; margin-bottom: 4px;">${daysInPrevMonth - i}</div>
                </div>`;
            }

            // Current month days
            for (let day = 1; day <= daysInMonth; day++) {
                const dateString = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isToday = dateString === todayString;

                // Find events for this day
                const dayEvents = allEvents.filter(event => {
                    const eventStart = new Date(event.start_date).toISOString().split('T')[0];
                    const eventEnd = new Date(event.end_date).toISOString().split('T')[0];
                    return dateString >= eventStart && dateString <= eventEnd;
                });

                // Find bookings for this day
                const dayBookings = allBookings.filter(booking => {
                    return booking.date === dateString;
                });

                let itemsHtml = '';
                let totalItems = dayEvents.length + dayBookings.length;
                let displayCount = 0;
                const maxDisplay = 2;

                // Display events
                dayEvents.slice(0, maxDisplay - displayCount).forEach(event => {
                    const startDate = new Date(event.start_date).toISOString().split('T')[0];
                    const isStartDay = dateString === startDate;
                    const eventStart = new Date(event.start_date);
                    const timeStr = (!event.all_day && isStartDay) ? eventStart.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' }) : '';
                    const eventLabel = isStartDay ? escapeHtml(event.title) : `↔ ${escapeHtml(event.title)}`;

                    itemsHtml += `
                        <div onclick="showEventDetails(${event.id})" style="background: ${event.color}20; border-left: 3px solid ${event.color}; padding: 3px 5px; margin-bottom: 3px; border-radius: 4px; cursor: pointer; font-size: 10px; line-height: 1.3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${escapeHtml(event.title)}">
                            ${timeStr ? `<span style="font-weight: 600; display: block;">${timeStr}</span>` : ''}${eventLabel}
                        </div>
                    `;
                    displayCount++;
                });

                // Display bookings
                dayBookings.slice(0, maxDisplay - displayCount).forEach(booking => {
                    itemsHtml += `
                        <div onclick="showBookingDetails(${booking.id})" style="background: #DBEAFE; border-left: 3px solid #3B82F6; padding: 3px 5px; margin-bottom: 3px; border-radius: 4px; cursor: pointer; font-size: 10px; line-height: 1.3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${escapeHtml(booking.agency)} - ${escapeHtml(booking.event)}">
                            <i class="fas fa-building" style="font-size: 8px; margin-right: 2px;"></i><span style="font-weight: 600;">${booking.time ? booking.time.split(' - ')[0] : ''}</span> ${escapeHtml(booking.agency)}
                        </div>
                    `;
                    displayCount++;
                });

                if (totalItems > maxDisplay) {
                    itemsHtml += `<div style="font-size: 9px; color: #6B7280; padding: 2px 5px; text-align: center; background: #F3F4F6; border-radius: 3px; margin-top: 2px; cursor: pointer;" onclick="showDayDetails('${dateString}')">+${totalItems - maxDisplay} more</div>`;
                }

                const bgColor = isToday ? '#FEF3C7' : 'white';
                const dayColor = isToday ? '#7B1D3A' : '#1F2937';

                html += `<div style="min-height: 120px; padding: 8px; border: 1px solid #E5E7EB; background: ${bgColor}; cursor: pointer; transition: background 0.2s; display: flex; flex-direction: column;" onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='${bgColor}'">
                    <div style="font-size: 14px; font-weight: ${isToday ? '700' : '600'}; color: ${dayColor}; margin-bottom: 6px; flex-shrink: 0;">${day}</div>
                    <div style="flex: 1; overflow-y: auto; overflow-x: hidden;">
                        ${itemsHtml}
                    </div>
                </div>`;
            }

            // Next month days to fill grid
            const totalCells = Math.ceil((firstDay + daysInMonth) / 7) * 7;
            const remainingCells = totalCells - (firstDay + daysInMonth);
            for (let i = 1; i <= remainingCells; i++) {
                html += `<div style="min-height: 120px; padding: 8px; border: 1px solid #E5E7EB; background: #FAFAFA; color: #D1D5DB; display: flex; flex-direction: column;">
                    <div style="font-size: 14px; font-weight: 600; margin-bottom: 4px;">${i}</div>
                </div>`;
            }

            document.getElementById('calendarDaysGrid').innerHTML = html;
        }

        function previousMonth() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar();
        }

        function nextMonth() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar();
        }

        function goToToday() {
            const today = new Date();
            currentMonth = today.getMonth();
            currentYear = today.getFullYear();
            renderCalendar();
        }

        function showEventDetails(eventId) {
            const event = allEvents.find(e => e.id === eventId);
            if (!event) return;

            const startDate = new Date(event.start_date);
            const endDate = new Date(event.end_date);
            const startDateOnly = startDate.toISOString().split('T')[0];
            const endDateOnly = endDate.toISOString().split('T')[0];
            const isMultiDay = startDateOnly !== endDateOnly;

            let dateInfo = startDate.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
            if (isMultiDay) {
                const endFormatted = endDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
                dateInfo += ` - ${endFormatted}`;
            }

            const timeStr = event.all_day ? 'All Day' : `${startDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })} - ${endDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })}`;

            const modalHtml = `
                <div id="eventDetailsModal" onclick="closeEventModal()" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 10000; display: flex; align-items: center; justify-content: center; padding: 20px;">
                    <div onclick="event.stopPropagation()" style="background: white; border-radius: 16px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                        <div style="background: linear-gradient(135deg, ${event.color}, ${event.color}dd); padding: 24px; color: white; border-radius: 16px 16px 0 0;">
                            <h2 style="margin: 0; font-size: 24px; font-weight: 700;">${escapeHtml(event.title)}</h2>
                        </div>
                        <div style="padding: 24px;">
                            ${event.description ? `<p style="color: #6B7280; font-size: 15px; margin-bottom: 20px; line-height: 1.6;">${escapeHtml(event.description)}</p>` : ''}

                            <div style="display: flex; flex-direction: column; gap: 16px;">
                                <div style="display: flex; align-items: start; gap: 12px;">
                                    <div style="width: 40px; height: 40px; background: ${event.color}20; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-calendar" style="color: ${event.color}; font-size: 16px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 2px;">Date</div>
                                        <div style="font-size: 15px; font-weight: 600; color: #1F2937;">${dateInfo}</div>
                                    </div>
                                </div>

                                <div style="display: flex; align-items: start; gap: 12px;">
                                    <div style="width: 40px; height: 40px; background: ${event.color}20; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-clock" style="color: ${event.color}; font-size: 16px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 2px;">Time</div>
                                        <div style="font-size: 15px; font-weight: 600; color: #1F2937;">${timeStr}</div>
                                    </div>
                                </div>

                                ${event.location ? `
                                <div style="display: flex; align-items: start; gap: 12px;">
                                    <div style="width: 40px; height: 40px; background: ${event.color}20; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-map-marker-alt" style="color: ${event.color}; font-size: 16px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 2px;">Location</div>
                                        <div style="font-size: 15px; font-weight: 600; color: #1F2937;">${escapeHtml(event.location)}</div>
                                    </div>
                                </div>
                                ` : ''}
                            </div>

                            <button onclick="closeEventModal()" style="width: 100%; margin-top: 24px; padding: 12px; background: linear-gradient(135deg, #7B1D3A, #5a1428); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px; transition: all 0.3s;">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }

        function closeEventModal() {
            const modal = document.getElementById('eventDetailsModal');
            if (modal) {
                modal.remove();
            }
        }

        function showDayDetails(dateString) {
            const date = new Date(dateString + 'T00:00:00');
            const dateInfo = date.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });

            // Find all events and bookings for this day
            const dayEvents = allEvents.filter(event => {
                const eventStart = new Date(event.start_date).toISOString().split('T')[0];
                const eventEnd = new Date(event.end_date).toISOString().split('T')[0];
                return dateString >= eventStart && dateString <= eventEnd;
            });

            const dayBookings = allBookings.filter(booking => booking.date === dateString);

            let itemsHtml = '';

            if (dayEvents.length > 0) {
                itemsHtml += '<div style="margin-bottom: 20px;"><h3 style="font-size: 16px; font-weight: 700; color: #1F2937; margin-bottom: 12px;"><i class="fas fa-calendar-alt" style="margin-right: 8px; color: #7B1D3A;"></i>Events</h3>';
                dayEvents.forEach(event => {
                    const startDate = new Date(event.start_date).toISOString().split('T')[0];
                    const isStartDay = dateString === startDate;
                    const eventStart = new Date(event.start_date);
                    const timeStr = (!event.all_day && isStartDay) ? eventStart.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' }) : 'All Day';

                    itemsHtml += `
                        <div onclick="closeEventModal(); setTimeout(() => showEventDetails(${event.id}), 100);" style="background: ${event.color}10; border-left: 4px solid ${event.color}; padding: 12px; margin-bottom: 10px; border-radius: 8px; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.background='${event.color}20'" onmouseout="this.style.background='${event.color}10'">
                            <div style="font-weight: 600; color: #1F2937; margin-bottom: 4px;">${escapeHtml(event.title)}</div>
                            <div style="font-size: 13px; color: #6B7280;"><i class="fas fa-clock" style="margin-right: 4px;"></i>${timeStr}</div>
                            ${event.location ? `<div style="font-size: 13px; color: #6B7280; margin-top: 4px;"><i class="fas fa-map-marker-alt" style="margin-right: 4px;"></i>${escapeHtml(event.location)}</div>` : ''}
                        </div>
                    `;
                });
                itemsHtml += '</div>';
            }

            if (dayBookings.length > 0) {
                itemsHtml += '<div><h3 style="font-size: 16px; font-weight: 700; color: #1F2937; margin-bottom: 12px;"><i class="fas fa-building" style="margin-right: 8px; color: #3B82F6;"></i>Bookings</h3>';
                dayBookings.forEach(booking => {
                    itemsHtml += `
                        <div onclick="closeEventModal(); setTimeout(() => showBookingDetails(${booking.id}), 100);" style="background: #F0F9FF; border-left: 4px solid #3B82F6; padding: 12px; margin-bottom: 10px; border-radius: 8px; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.background='#DBEAFE'" onmouseout="this.style.background='#F0F9FF'">
                            <div style="font-weight: 600; color: #1F2937; margin-bottom: 4px;">${escapeHtml(booking.agency)}</div>
                            <div style="font-size: 13px; color: #6B7280;"><i class="fas fa-calendar-check" style="margin-right: 4px;"></i>${escapeHtml(booking.event)}</div>
                            <div style="font-size: 13px; color: #6B7280; margin-top: 4px;"><i class="fas fa-clock" style="margin-right: 4px;"></i>${booking.time || 'Not specified'}</div>
                        </div>
                    `;
                });
                itemsHtml += '</div>';
            }

            const modalHtml = `
                <div id="eventDetailsModal" onclick="closeEventModal()" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 10000; display: flex; align-items: center; justify-content: center; padding: 20px;">
                    <div onclick="event.stopPropagation()" style="background: white; border-radius: 16px; max-width: 700px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                        <div style="background: linear-gradient(135deg, #7B1D3A, #5a1428); padding: 24px; color: white; border-radius: 16px 16px 0 0;">
                            <div>
                                <h2 style="margin: 0; font-size: 24px; font-weight: 700;">${dateInfo}</h2>
                                <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">${dayEvents.length + dayBookings.length} item(s)</p>
                            </div>
                        </div>
                        <div style="padding: 24px;">
                            ${itemsHtml}
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }

        function showBookingDetails(bookingId) {
            const booking = allBookings.find(b => b.id === bookingId);
            if (!booking) return;

            const bookingDate = new Date(booking.date);
            const dateInfo = bookingDate.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });

            const modalHtml = `
                <div id="eventDetailsModal" onclick="closeEventModal()" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 10000; display: flex; align-items: center; justify-content: center; padding: 20px;">
                    <div onclick="event.stopPropagation()" style="background: white; border-radius: 16px; max-width: 600px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                        <div style="background: linear-gradient(135deg, #3B82F6, #2563EB); padding: 24px; color: white; border-radius: 16px 16px 0 0;">
                            <div>
                                <div style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.2); border-radius: 6px; font-size: 11px; font-weight: 600; margin-bottom: 8px;">
                                    <i class="fas fa-calendar-check" style="margin-right: 4px;"></i>BOOKING
                                </div>
                                <h2 style="margin: 0; font-size: 24px; font-weight: 700;">${escapeHtml(booking.agency)}</h2>
                            </div>
                        </div>
                        <div style="padding: 24px;">
                            <div style="background: #F0F9FF; border-left: 4px solid #3B82F6; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
                                <div style="font-size: 13px; color: #1E40AF; font-weight: 600;">Event</div>
                                <div style="font-size: 15px; color: #1F2937; margin-top: 4px;">${escapeHtml(booking.event)}</div>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 16px;">
                                <div style="display: flex; align-items: start; gap: 12px;">
                                    <div style="width: 40px; height: 40px; background: #DBEAFE; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-calendar" style="color: #3B82F6; font-size: 16px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 2px;">Date</div>
                                        <div style="font-size: 15px; font-weight: 600; color: #1F2937;">${dateInfo}</div>
                                    </div>
                                </div>

                                <div style="display: flex; align-items: start; gap: 12px;">
                                    <div style="width: 40px; height: 40px; background: #DBEAFE; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-clock" style="color: #3B82F6; font-size: 16px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 2px;">Time</div>
                                        <div style="font-size: 15px; font-weight: 600; color: #1F2937;">${booking.time || 'Not specified'}</div>
                                    </div>
                                </div>

                                <div style="display: flex; align-items: start; gap: 12px;">
                                    <div style="width: 40px; height: 40px; background: #DBEAFE; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user" style="color: #3B82F6; font-size: 16px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 2px;">Contact Person</div>
                                        <div style="font-size: 15px; font-weight: 600; color: #1F2937;">${escapeHtml(booking.contact_person || 'N/A')}</div>
                                    </div>
                                </div>

                                ${booking.purpose ? `
                                <div style="display: flex; align-items: start; gap: 12px;">
                                    <div style="width: 40px; height: 40px; background: #DBEAFE; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-info-circle" style="color: #3B82F6; font-size: 16px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 2px;">Purpose</div>
                                        <div style="font-size: 15px; font-weight: 600; color: #1F2937;">${escapeHtml(booking.purpose)}</div>
                                    </div>
                                </div>
                                ` : ''}
                            </div>

                            <button onclick="closeEventModal()" style="width: 100%; margin-top: 24px; padding: 12px; background: linear-gradient(135deg, #3B82F6, #2563EB); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px; transition: all 0.3s;">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }

        function displayEvents(events) {
            // This function is kept for backwards compatibility but now uses calendar rendering
            allEvents = events;
            renderCalendar();
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
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

        // Profile dropdown toggle
        const internProfileBtn = document.getElementById('internProfileBtn');
        const internProfileDropdown = document.getElementById('internProfileDropdown');

        if (internProfileBtn && internProfileDropdown) {
            internProfileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                internProfileDropdown.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!internProfileDropdown.contains(e.target)) {
                    internProfileDropdown.classList.remove('active');
                }
            });
        }

        // Navigate to profile page from dropdown
        function navigateToProfile(event) {
            event.preventDefault();
            internProfileDropdown.classList.remove('active');
            showPage('profile');
            // Update page title
            document.getElementById('pageTitle').textContent = 'My Profile';
        }

        function toggleEditProfile() {
            document.getElementById('editProfileModal').classList.add('active');
        }

        function closeEditProfileModal(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('editProfileModal').classList.remove('active');
        }

        // Handle edit profile form submission
        document.addEventListener('DOMContentLoaded', function() {
            const editProfileForm = document.getElementById('editProfileForm');
            if (editProfileForm) {
                editProfileForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const submitBtn = document.getElementById('editProfileSubmitBtn');
                    const originalContent = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 6px;"></i>Saving...';
                    submitBtn.disabled = true;

                    const formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success !== false) {
                            showNotification('Profile updated successfully!', 'success');
                            closeEditProfileModal();
                            // Reload to show updated data
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showNotification(data.message || 'Failed to update profile', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An error occurred while updating profile', 'error');
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalContent;
                        submitBtn.disabled = false;
                    });
                });
            }
        });

        // Prevent spam on attendance buttons
        let isSubmitting = false;

        // Initialize attendance form handlers
        document.addEventListener('DOMContentLoaded', function() {
            const timeInForm = document.getElementById('timeInForm');
            const timeOutForm = document.getElementById('timeOutForm');

            console.log('Initializing attendance forms:', { timeInForm: !!timeInForm, timeOutForm: !!timeOutForm });

            if (timeInForm) {
                timeInForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Time In form submitted');
                    handleAttendanceSubmit(this, 'in');
                });
            }

            if (timeOutForm) {
                timeOutForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Time Out form submitted');
                    handleAttendanceSubmit(this, 'out');
                });
            }

            // Initialize task modal
            createTaskStartModal();
        });

        function handleAttendanceSubmit(form, type) {
            console.log('handleAttendanceSubmit called:', type);
            if (isSubmitting) {
                console.log('Already submitting, skipping');
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
            console.log('Submitting attendance:', type, form.action);
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
                console.log('Response status:', response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Error response:', text);
                        throw new Error('Network response was not ok: ' + response.status);
                    });
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
                        <p id="summaryHours" style="font-size: 24px; font-weight: 600;" data-is-working="false" data-time-in="" data-raw-time-in="">0.00</p>
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
                    const safeHours = Math.max(0, parseFloat(hoursWorked) || 0);
                    hoursEl.textContent = safeHours.toFixed(2);
                    hoursEl.dataset.isWorking = 'false'; // Stop live updates when timed out
                }

                // Set up live tracking if rawTimeIn is provided (just timed in)
                if (rawTimeIn && !timeOut) {
                    hoursEl.dataset.rawTimeIn = rawTimeIn;
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
            const timeIn = hoursElement.dataset.rawTimeIn || hoursElement.dataset.timeIn;

            if (!isWorking || !timeIn) return;

            // Prefer ISO timestamps (includes date + timezone offset)
            let timeInMs = new Date(timeIn).getTime();

            // Fallback: HH:MM:SS (best-effort)
            if (Number.isNaN(timeInMs)) {
                const parts = String(timeIn).split(':').map(Number);
                const h = parts[0] || 0;
                const m = parts[1] || 0;
                const s = parts[2] || 0;
                const now = new Date();
                timeInMs = new Date(now.getFullYear(), now.getMonth(), now.getDate(), h, m, s, 0).getTime();
            }

            const diffMs = Date.now() - timeInMs;
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
            // Ensure modal exists
            if (!document.getElementById('taskStartModal')) {
                createTaskStartModal();
            }
            // Open modal for task details
            document.getElementById('isUpdateOnly').value = '0';
            openTaskStartModal(taskId);
        }

        function updateTask(taskId) {
            // Ensure modal exists
            if (!document.getElementById('taskStartModal')) {
                createTaskStartModal();
            }
            // Open modal in update-only mode (don't change status)
            document.getElementById('isUpdateOnly').value = '1';
            openTaskStartModal(taskId);
        }

        function openTaskStartModal(taskId) {
            const modal = document.getElementById('taskStartModal');
            const isUpdateOnly = document.getElementById('isUpdateOnly').value === '1';
            if (modal) {
                modal.classList.add('active');
                document.getElementById('taskStartId').value = taskId;
                document.getElementById('startTaskForm').reset();

                // Always fetch task details to support checklist-based progress
                fetch(`/intern/tasks/${taskId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.task) return;

                    const task = data.task;
                    document.getElementById('taskNotes').value = task.notes || '';

                    // Checklist UI
                    const checklistWrap = document.getElementById('taskChecklistWrap');
                    const checklistList = document.getElementById('taskChecklist');
                    const progressGroup = document.getElementById('taskProgressGroup');
                    const progressInput = document.getElementById('taskProgress');
                    const checklistProgressText = document.getElementById('taskChecklistProgressText');
                    const checklistStateInput = document.getElementById('taskChecklistState');

                    const checklistEditor = document.getElementById('taskChecklistEditor');
                    const checklistEditorHint = document.getElementById('taskChecklistEditorHint');

                    const checklist = Array.isArray(task.checklist) ? task.checklist : null;

                    // Intern is required to use a checklist.
                    if (progressGroup) progressGroup.style.display = 'none';
                    if (checklistWrap) checklistWrap.style.display = 'block';
                    if (checklistList) checklistList.innerHTML = '';

                    // If admin provided checklist, hide editor. If not, intern must create it.
                    const hasChecklist = !!(checklist && checklist.length > 0);
                    if (checklistEditor) {
                        checklistEditor.value = hasChecklist
                            ? (checklist.map(i => (i && i.label) ? String(i.label) : '').filter(Boolean).join('\n'))
                            : '';
                        checklistEditor.style.display = hasChecklist ? 'none' : 'block';
                    }
                    if (checklistEditorHint) {
                        checklistEditorHint.style.display = hasChecklist ? 'none' : 'block';
                    }

                    const buildChecklistFromEditor = () => {
                        const text = checklistEditor ? String(checklistEditor.value || '') : '';
                        const lines = text.split(/\r\n|\r|\n/).map(l => l.trim()).filter(Boolean);
                        return lines.map(label => ({ label, done: false }));
                    };

                    const renderChecklist = (items) => {
                        if (!checklistList) return;
                        checklistList.innerHTML = '';

                        items.forEach((item, idx) => {
                            const label = (item && item.label) ? String(item.label) : `Item ${idx + 1}`;
                            const done = !!(item && item.done);
                            const row = document.createElement('label');
                            row.style.cssText = 'display:flex; gap:10px; align-items:flex-start; padding:10px 12px; border:1px solid #E5E7EB; border-radius:8px; cursor:pointer;';
                            row.innerHTML = `
                                <input type="checkbox" data-checklist-index="${idx}" ${done ? 'checked' : ''} style="margin-top:3px; width:16px; height:16px;">
                                <div style="flex:1;">
                                    <div style="font-weight:600; color:#1F2937; font-size:13px;">${escapeHtml(label)}</div>
                                </div>
                            `;
                            checklistList.appendChild(row);
                        });
                    };

                    // Base items come from admin checklist OR intern editor
                    let baseItems = hasChecklist ? checklist.map(i => ({
                        label: (i && i.label) ? String(i.label) : '',
                        done: !!(i && i.done)
                    })) : buildChecklistFromEditor();

                    renderChecklist(baseItems);

                    const recompute = () => {
                        const prevBoxes = checklistList ? checklistList.querySelectorAll('input[type="checkbox"][data-checklist-index]') : [];
                        const prevChecked = Array.from(prevBoxes).map(b => !!b.checked);

                        // For admin checklist, preserve labels; for intern-created checklist, rebuild labels from editor each time.
                        const currentBase = hasChecklist ? baseItems : buildChecklistFromEditor();
                        if (!hasChecklist) {
                            // Re-render if editor changed item count
                            if (currentBase.length !== baseItems.length) {
                                baseItems = currentBase;
                                renderChecklist(baseItems);

                                // Restore checked state for overlapping indices
                                const newBoxes = checklistList ? checklistList.querySelectorAll('input[type="checkbox"][data-checklist-index]') : [];
                                newBoxes.forEach((box, idx) => {
                                    if (idx < prevChecked.length) {
                                        box.checked = prevChecked[idx];
                                    }
                                });
                            } else {
                                baseItems = currentBase;
                            }
                        }

                        const currentBoxes = checklistList ? checklistList.querySelectorAll('input[type="checkbox"][data-checklist-index]') : [];
                        const total = currentBoxes.length;
                        let doneCount = 0;

                        const newChecklist = baseItems.map((it) => ({
                            label: it && it.label ? String(it.label) : '',
                            done: false
                        }));

                        currentBoxes.forEach((box) => {
                            const index = Number(box.getAttribute('data-checklist-index'));
                            const isDone = !!box.checked;
                            if (!Number.isNaN(index) && newChecklist[index]) {
                                newChecklist[index].done = isDone;
                            }
                            if (isDone) doneCount++;
                        });

                        const percent = total > 0 ? Math.round((doneCount / total) * 100) : 0;
                        if (checklistProgressText) checklistProgressText.textContent = `${percent}% (${doneCount}/${total} done)`;
                        if (progressInput) progressInput.value = percent;
                        if (checklistStateInput) checklistStateInput.value = JSON.stringify(newChecklist);
                    };

                    checklistList.onchange = recompute;
                    if (checklistEditor) {
                        checklistEditor.oninput = recompute;
                    }
                    recompute();
                })
                .catch(error => console.error('Error fetching task:', error));

                // Update button text based on mode
                document.getElementById('submitBtnText').textContent = isUpdateOnly ? 'Update Progress' : 'Start Task';
                const submitBtn = document.getElementById('submitBtn');
                if (isUpdateOnly) {
                    submitBtn.style.background = '#3B82F6';
                } else {
                    submitBtn.style.background = '#7B1D3A';
                }
            } else {
                // Create modal if it doesn't exist
                createTaskStartModal();
                openTaskStartModal(taskId);
            }
        }

        function closeTaskStartModal() {
            const modal = document.getElementById('taskStartModal');
            if (modal) {
                modal.classList.remove('active');
            }
        }

        function createTaskStartModal() {
            const modalHTML = `
                <div id="taskStartModal" class="modal-overlay" onclick="if(event.target === this) closeTaskStartModal()">
                    <div class="modal-content" style="max-width: 600px;">
                        <div class="modal-header" style="background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white;">
                            <h2 style="margin: 0;"><i class="fas fa-play" style="margin-right: 8px;"></i>Start Task</h2>
                        </div>
                        <div class="modal-body">
                            <form id="startTaskForm" onsubmit="submitTaskStart(event)">
                                <input type="hidden" id="taskStartId">
                                <input type="hidden" id="isUpdateOnly" value="0">
                                <input type="hidden" id="taskChecklistState" name="checklist">

                                <div id="taskProgressGroup" class="form-group" style="margin-bottom: 20px;">
                                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1F2937; margin-bottom: 8px;">
                                        <i class="fas fa-bars" style="color: #7B1D3A; margin-right: 6px;"></i>
                                        Update Progress (%)
                                    </label>
                                    <input type="number" id="taskProgress" name="progress" min="0" max="100" value="0" placeholder="0-100" style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px;">
                                    <small style="color: #6B7280; font-size: 12px; margin-top: 4px; display: block;">Progress will be recorded and visible to admin</small>
                                </div>

                                <div id="taskChecklistWrap" class="form-group" style="margin-bottom: 20px; display: none;">
                                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1F2937; margin-bottom: 8px;">
                                        <i class="fas fa-list-check" style="color: #7B1D3A; margin-right: 6px;"></i>
                                        Task Checklist
                                    </label>
                                    <textarea id="taskChecklistEditor" placeholder="Add checklist items (one per line)\nExample:\nPlanning\nDesigning\nImplementation" style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px; min-height: 90px; resize: vertical; display: none;"></textarea>
                                    <small id="taskChecklistEditorHint" style="color: #92400E; font-size: 12px; margin-top: 6px; display: none;">Admin didn't provide a checklist. Please create one here (required).</small>
                                    <div id="taskChecklist" style="display:flex; flex-direction:column; gap:10px;"></div>
                                    <small id="taskChecklistProgressText" style="color: #6B7280; font-size: 12px; margin-top: 8px; display: block;">0% (0/0 done)</small>
                                </div>

                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1F2937; margin-bottom: 8px;">
                                        <i class="fas fa-paperclip" style="color: #7B1D3A; margin-right: 6px;"></i>
                                        Upload Documents (Optional)
                                    </label>
                                    <input type="file" id="taskDocuments" name="documents" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.png" style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px;">
                                    <small style="color: #6B7280; font-size: 12px; margin-top: 4px; display: block;">Allowed: PDF, DOC, DOCX, XLS, XLSX, TXT, JPG, PNG</small>
                                </div>

                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1F2937; margin-bottom: 8px;">
                                        <i class="fas fa-comment" style="color: #7B1D3A; margin-right: 6px;"></i>
                                        Notes / Comments
                                    </label>
                                    <textarea id="taskNotes" name="notes" placeholder="Add any notes or comments..." style="width: 100%; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px; min-height: 100px; resize: vertical;"></textarea>
                                </div>

                                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                                    <button type="button" onclick="closeTaskStartModal()" style="padding: 10px 20px; background: #F3F4F6; color: #6B7280; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                                        Cancel
                                    </button>
                                    <button type="submit" id="submitBtn" style="padding: 10px 20px; background: #7B1D3A; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                                        <i class="fas fa-check" style="margin-right: 6px;"></i><span id="submitBtnText">Start Task</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }

        function submitTaskStart(event) {
            event.preventDefault();
            const taskId = document.getElementById('taskStartId').value;
            const progress = document.getElementById('taskProgress').value;
            const notes = document.getElementById('taskNotes').value;
            const documentsInput = document.getElementById('taskDocuments');
            const isUpdateOnly = document.getElementById('isUpdateOnly').value === '1';
            const checklistState = document.getElementById('taskChecklistState')?.value || '';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            // If checklist UI is shown, require at least 1 checklist item
            const checklistWrap = document.getElementById('taskChecklistWrap');
            if (checklistWrap && checklistWrap.style.display !== 'none') {
                try {
                    const parsed = checklistState ? JSON.parse(checklistState) : [];
                    if (!Array.isArray(parsed) || parsed.length === 0) {
                        alert('Please create at least 1 checklist item before saving progress.');
                        return;
                    }
                } catch (e) {
                    alert('Please create at least 1 checklist item before saving progress.');
                    return;
                }
            }

            const formData = new FormData();

            // Only set status if this is a start action
            if (!isUpdateOnly) {
                formData.append('status', 'In Progress');
            }

            // Progress is derived from checklist when checklist exists/required
            formData.append('progress', progress || 0);
            formData.append('notes', notes);

            if (checklistState) {
                formData.append('checklist', checklistState);
            }

            // Add documents if selected
            if (documentsInput.files.length > 0) {
                for (let i = 0; i < documentsInput.files.length; i++) {
                    formData.append('documents[]', documentsInput.files[i]);
                }
            }
            formData.append('_method', 'PUT');
            formData.append('_token', csrfToken);

            fetch(`/intern/tasks/${taskId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeTaskStartModal();
                    const message = isUpdateOnly
                        ? 'Task progress updated successfully!'
                        : 'Task started successfully! Your progress and documents have been recorded.';
                    alert(message);

                    // If starting task, update the buttons in the table
                    if (!isUpdateOnly) {
                        // Find the button container for this task
                        const allRows = document.querySelectorAll('tr');
                        allRows.forEach(row => {
                            const btn = row.querySelector(`button[onclick="startTask(${taskId})"]`);
                            if (btn) {
                                const cell = btn.closest('td');
                                if (cell) {
                                    // Update status badge in the same row
                                    const statusCell = row.querySelector('td:nth-child(5)');
                                    if (statusCell) {
                                        statusCell.innerHTML = `
                                            <span style="display: inline-block; background: #FEF3C7; color: #92400E;
                                                padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 700;">
                                                In Progress
                                            </span>
                                        `;
                                    }

                                    // Replace with Update and Complete buttons
                                    cell.innerHTML = `
                                        <div style="display: flex; gap: 6px; justify-content: center;">
                                            <button onclick="updateTask(${taskId})" style="background: #3B82F6; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 11px; cursor: pointer; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">
                                                <i class="fas fa-sync"></i> Update
                                            </button>
                                            <button onclick="completeTask(${taskId})" style="background: #10B981; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 11px; cursor: pointer; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10B981'">
                                                <i class="fas fa-check"></i> Submit
                                            </button>
                                        </div>
                                    `;
                                }
                            }
                        });
                    }
                } else {
                    alert('Error: ' + (data.message || 'Failed to update task'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating task: ' + error.message);
            });
        }

        // Submit task as completed (pending admin approval)
        function completeTask(taskId) {
            if (!confirm('Submit this task as completed for admin approval?')) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch(`/intern/tasks/${taskId}/complete`, {
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
                    alert('Submitted! This is waiting for admin approval.');
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

            fetch(`/intern/tasks/${taskId}`, {
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

        // Refresh CSRF token periodically to prevent "Page Expired" errors
        function refreshCsrfToken() {
            fetch('/intern', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newToken = doc.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                if (newToken) {
                    // Update all CSRF token meta tags
                    const metaTag = document.querySelector('meta[name="csrf-token"]');
                    if (metaTag) {
                        metaTag.setAttribute('content', newToken);
                    }

                    // Update all CSRF input fields
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = newToken;
                    });
                }
            })
            .catch(error => {
                console.error('Error refreshing CSRF token:', error);
            });
        }

        // Refresh CSRF token every 30 minutes
        setInterval(refreshCsrfToken, 30 * 60 * 1000);

        // Upload profile picture
        function uploadProfilePicture(input) {
            if (!input.files || !input.files[0]) {
                return;
            }

            const file = input.files[0];
            const maxSize = 5 * 1024 * 1024; // 5MB

            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file');
                return;
            }

            // Validate file size
            if (file.size > maxSize) {
                alert('Image size must be less than 5MB');
                return;
            }

            // Show preview immediately
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profilePicturePreview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">`;
            };
            reader.readAsDataURL(file);

            // Upload to server
            const formData = new FormData();
            formData.append('profile_picture', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');

            fetch('/intern/update-profile-picture', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile picture updated successfully!');
                    // Reload to show the new picture everywhere
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to upload image'));
                    // Reload to restore original image
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error uploading profile picture');
                // Reload to restore original image
                location.reload();
            });
        }

        // Handle logout form submission with error handling
        const logoutForms = document.querySelectorAll('form[action*="clear"]');
        logoutForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // If there's any error, just redirect to the portal page
                const submitHandler = () => {
                    setTimeout(() => {
                        window.location.href = '/intern';
                    }, 100);
                };

                // Set a timeout fallback
                setTimeout(submitHandler, 1000);
            });
        });

        // ==========================================
        // REAL-TIME STATUS POLLING
        // ==========================================
        let realtimePollingInterval = null;
        let lastTasksHash = '';
        let lastAttendanceState = '';

        function startRealtimePolling() {
            // Poll every 10 seconds for real-time updates
            realtimePollingInterval = setInterval(fetchRealtimeStatus, 10000);
            // Also fetch immediately
            fetchRealtimeStatus();
        }

        function stopRealtimePolling() {
            if (realtimePollingInterval) {
                clearInterval(realtimePollingInterval);
                realtimePollingInterval = null;
            }
        }

        async function fetchRealtimeStatus() {
            try {
                const response = await fetch('/intern/realtime-status', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (!response.ok) {
                    if (response.status === 401) {
                        // Session expired, stop polling
                        stopRealtimePolling();
                        return;
                    }
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                if (data.success) {
                    updateUIWithRealtimeData(data);
                }
            } catch (error) {
                console.error('Real-time fetch error:', error);
            }
        }

        function updateUIWithRealtimeData(data) {
            // Update intern progress
            if (data.intern) {
                updateInternProgress(data.intern);
            }

            // Update attendance state
            if (data.attendance) {
                updateAttendanceUI(data.attendance);
            }

            // Update task stats
            if (data.tasks) {
                updateTaskStats(data.tasks);
            }

            // Check for task changes and notify
            if (data.recent_tasks) {
                checkForTaskUpdates(data.recent_tasks);
            }
        }

        function updateInternProgress(intern) {
            // Update completed hours display
            const completedHoursEl = document.querySelector('[data-completed-hours]');
            if (completedHoursEl) {
                completedHoursEl.textContent = intern.completed_hours + ' hrs';
            }

            // Update progress bar
            const progressBar = document.querySelector('.progress-fill, [data-progress-bar]');
            if (progressBar) {
                progressBar.style.width = intern.progress_percentage + '%';
            }

            // Update progress text
            const progressText = document.querySelector('[data-progress-text]');
            if (progressText) {
                progressText.textContent = intern.progress_percentage + '%';
            }
        }

        function updateAttendanceUI(attendance) {
            const currentState = JSON.stringify(attendance);
            if (currentState === lastAttendanceState) return;
            lastAttendanceState = currentState;

            const timeInForm = document.getElementById('timeInForm');
            const timeOutForm = document.getElementById('timeOutForm');
            const attendanceComplete = document.getElementById('attendanceComplete');
            const summaryTimeIn = document.getElementById('summaryTimeIn');
            const summaryTimeOut = document.getElementById('summaryTimeOut');
            const summaryHours = document.getElementById('summaryHours');

            if (attendance.has_timed_in && attendance.has_timed_out) {
                // Attendance complete
                if (timeInForm) timeInForm.style.display = 'none';
                if (timeOutForm) timeOutForm.style.display = 'none';
                if (attendanceComplete) attendanceComplete.style.display = 'block';
            } else if (attendance.has_timed_in && !attendance.has_timed_out) {
                // Timed in, waiting for time out
                if (timeInForm) timeInForm.style.display = 'none';
                if (timeOutForm) timeOutForm.style.display = 'block';
                if (attendanceComplete) attendanceComplete.style.display = 'none';
            } else {
                // Not timed in yet
                if (timeInForm) timeInForm.style.display = 'block';
                if (timeOutForm) timeOutForm.style.display = 'none';
                if (attendanceComplete) attendanceComplete.style.display = 'none';
            }

            // Update time displays
            if (summaryTimeIn && attendance.time_in) {
                summaryTimeIn.textContent = attendance.time_in;
            }
            if (summaryTimeOut && attendance.time_out) {
                summaryTimeOut.textContent = attendance.time_out;
            }
            if (summaryHours) {
                // Only show hours if time_out exists (hours_today is not null)
                if (attendance.hours_today !== null) {
                    summaryHours.textContent = attendance.hours_today.toFixed(2);
                } else {
                    summaryHours.textContent = '--';
                }
                summaryHours.dataset.isWorking = 'false'; // Don't do live tracking
            }
        }

        function updateTaskStats(tasks) {
            // Update pending tasks count in quick actions
            const pendingBadge = document.querySelector('[data-pending-tasks]');
            if (pendingBadge) {
                pendingBadge.textContent = tasks.pending + ' pending';
            }

            // Update task overview cards if they exist
            const totalTasksEl = document.querySelector('[data-total-tasks]');
            const pendingTasksEl = document.querySelector('[data-pending-tasks-count]');
            const completedTasksEl = document.querySelector('[data-completed-tasks]');
            const overdueTasksEl = document.querySelector('[data-overdue-tasks]');

            if (totalTasksEl) totalTasksEl.textContent = tasks.total;
            if (pendingTasksEl) pendingTasksEl.textContent = tasks.pending;
            if (completedTasksEl) completedTasksEl.textContent = tasks.completed;
            if (overdueTasksEl) overdueTasksEl.textContent = tasks.overdue;
        }

        function checkForTaskUpdates(recentTasks) {
            const currentHash = JSON.stringify(recentTasks);
            if (lastTasksHash && lastTasksHash !== currentHash) {
                // Tasks have changed, show notification
                showRealtimeNotification('Tasks Updated', 'Your task list has been updated.', 'info');
            }
            lastTasksHash = currentHash;
        }

        function showRealtimeNotification(title, message, type = 'info') {
            // Create toast notification for real-time updates
            const toast = document.createElement('div');
            toast.className = 'realtime-toast';
            toast.innerHTML = `
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fas ${type === 'info' ? 'fa-info-circle' : type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"
                       style="font-size: 20px; color: ${type === 'info' ? '#3B82F6' : type === 'success' ? '#10B981' : '#F59E0B'};"></i>
                    <div>
                        <div style="font-weight: 600; color: #1F2937;">${title}</div>
                        <div style="font-size: 13px; color: #6B7280;">${message}</div>
                    </div>
                </div>
            `;
            toast.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: white;
                padding: 16px 20px;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.15);
                z-index: 10000;
                animation: slideInRight 0.3s ease;
                max-width: 350px;
            `;

            document.body.appendChild(toast);

            // Remove after 4 seconds
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // Add animation keyframes for real-time notifications
        const realtimeStyles = document.createElement('style');
        realtimeStyles.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(realtimeStyles);

        // Start real-time polling when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startRealtimePolling();
        });

        // Stop polling when page is hidden, resume when visible
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopRealtimePolling();
            } else {
                startRealtimePolling();
            }
        });

        // Clean up on page unload
        window.addEventListener('beforeunload', function() {
            stopRealtimePolling();
        });
    </script>
    @endif
</body>
</html>
