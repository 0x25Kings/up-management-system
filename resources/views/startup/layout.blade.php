<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/upLogo.png') }}">
    <title>@yield('title') - {{ ucwords(strtolower($startup->company_name)) }} - UP Cebu Startup Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
            min-height: 100vh; 
        }

        :root {
            --maroon: #7B1D3A;
            --maroon-dark: #5a1428;
            --maroon-light: #A62450;
            --gold: #FFBF00;
            --gold-dark: #D4A500;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 280px;
            background: linear-gradient(180deg, #7B1D3A 0%, #5a1428 50%, #3d0e1a 100%);
            color: white;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--gold) 0%, #D4A500 100%);
            border-radius: 10px;
            transition: background 0.3s;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #FFD700 0%, var(--gold) 100%);
        }

        /* Firefox scrollbar */
        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: var(--gold) rgba(0, 0, 0, 0.2);
        }

        .sidebar-header {
            padding: 28px 24px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
        }

        .sidebar-header img {
            height: 52px;
            margin: 0 auto 14px;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-header:hover img {
            transform: scale(1.05);
            filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.4));
        }

        .sidebar-header h3 {
            color: white;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .sidebar-header p {
            font-size: 12px;
            color: var(--gold);
            font-weight: 500;
        }

        .company-card {
            margin: 20px 16px;
            padding: 20px;
            background: linear-gradient(135deg, rgba(255, 191, 0, 0.15) 0%, rgba(255, 165, 0, 0.1) 100%);
            border: 1px solid rgba(255, 191, 0, 0.3);
            border-radius: 16px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .company-card:hover {
            background: linear-gradient(135deg, rgba(255, 191, 0, 0.2) 0%, rgba(255, 165, 0, 0.15) 100%);
            border-color: rgba(255, 191, 0, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 191, 0, 0.15);
        }

        .company-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 26px;
            font-weight: 800;
            color: #7B1D3A;
            box-shadow: 0 6px 16px rgba(255, 191, 0, 0.4);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .company-card:hover .company-avatar {
            transform: scale(1.08);
            box-shadow: 0 8px 24px rgba(255, 191, 0, 0.5);
        }

        .company-card h3 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .company-card p {
            font-size: 12px;
            opacity: 0.7;
        }

        .company-card .code {
            font-size: 10px;
            background: rgba(255, 191, 0, 0.2);
            color: var(--gold);
            padding: 4px 12px;
            border-radius: 20px;
            margin-top: 10px;
            display: inline-block;
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        .nav-menu { padding: 8px 0; }

        .nav-section {
            padding: 16px 20px 8px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.35);
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 24px;
            margin: 2px 12px;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 191, 0, 0.2) 0%, rgba(255, 165, 0, 0.1) 100%);
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            z-index: -1;
        }

        .nav-item:hover::before {
            width: 100%;
        }

        .nav-item:hover {
            color: white;
            transform: translateX(6px);
        }

        .nav-item:hover i {
            transform: scale(1.15);
            color: var(--gold);
        }

        .nav-item.active {
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            color: #7B1D3A;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(255, 191, 0, 0.4);
            transform: translateX(0);
        }

        .nav-item.active::before {
            display: none;
        }

        .nav-item.active:hover {
            transform: translateX(0);
            box-shadow: 0 6px 20px rgba(255, 191, 0, 0.5);
        }

        .nav-item.active i {
            color: #7B1D3A;
        }

        .nav-item i { 
            width: 20px; 
            text-align: center; 
            font-size: 16px; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-footer {
            padding: 20px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 14px;
            background: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .logout-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(239, 68, 68, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .logout-btn:hover::before {
            left: 100%;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.3);
            color: #FCA5A5;
            transform: scale(1.02);
        }

        .logout-btn:active {
            transform: scale(0.98);
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.3);
            color: #FCA5A5;
        }

        /* Top Header Bar */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0;
            position: relative;
            z-index: 100;
        }

        .top-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .navbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            color: #6B7280;
        }

        .navbar-breadcrumb a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: rgba(123, 29, 58, 0.1);
            border-radius: 8px;
            color: var(--maroon);
            text-decoration: none;
            transition: all 0.3s;
        }

        .navbar-breadcrumb a:hover {
            background: var(--maroon);
            color: white;
            transform: scale(1.05);
        }

        .navbar-breadcrumb a i {
            font-size: 16px;
        }

        .navbar-breadcrumb .separator {
            color: #D1D5DB;
            font-size: 14px;
        }

        .navbar-breadcrumb .page-title {
            color: #6B7280;
            font-weight: 500;
        }

        .profile-dropdown {
            position: relative;
            z-index: 101;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            outline: none;
        }

        .profile-btn:hover {
            border-color: var(--maroon);
            box-shadow: 0 4px 12px rgba(123, 29, 58, 0.15);
        }

        .profile-btn .avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }

        .profile-btn .info {
            text-align: left;
        }

        .profile-btn .info .name {
            font-weight: 600;
            color: #1F2937;
            font-size: 14px;
        }

        .profile-btn .info .role {
            font-size: 11px;
            color: #6B7280;
        }

        .profile-btn i.fa-chevron-down {
            color: #9CA3AF;
            font-size: 12px;
            transition: transform 0.3s;
        }

        .profile-dropdown.active .profile-btn i.fa-chevron-down {
            transform: rotate(180deg);
        }

        .profile-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
            z-index: 1000;
        }

        .profile-dropdown.active .profile-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-menu-header {
            padding: 16px;
            border-bottom: 1px solid #E5E7EB;
            text-align: center;
        }

        .profile-menu-header .avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
            margin: 0 auto 10px;
        }

        .profile-menu-header .name {
            font-weight: 600;
            color: #1F2937;
            font-size: 15px;
        }

        .profile-menu-header .email {
            font-size: 12px;
            color: #6B7280;
            margin-top: 2px;
        }

        .profile-menu-items {
            padding: 8px;
        }

        .profile-menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            color: #374151;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 14px;
        }

        .profile-menu-item:hover {
            background: #F3F4F6;
            color: var(--maroon);
        }

        .profile-menu-item i {
            width: 18px;
            text-align: center;
            color: #6B7280;
        }

        .profile-menu-item:hover i {
            color: var(--maroon);
        }

        .profile-menu-item.danger {
            color: #DC2626;
        }

        .profile-menu-item.danger:hover {
            background: #FEE2E2;
            color: #DC2626;
        }

        .profile-menu-item.danger i {
            color: #DC2626;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 28px;
            min-height: 100vh;
        }

        /* Page Header */
        .page-header-card {
            background: linear-gradient(135deg, #7B1D3A 0%, #A62450 50%, #7B1D3A 100%);
            border-radius: 20px;
            padding: 32px 36px;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(123, 29, 58, 0.3);
        }

        .page-header-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 191, 0, 0.15) 0%, transparent 70%);
        }

        .page-header-card .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .page-header-card .header-icon {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
        }

        .page-header-card h1 {
            font-size: 26px;
            font-weight: 800;
            color: white;
            margin-bottom: 6px;
        }

        .page-header-card p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 15px;
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .breadcrumb a {
            color: var(--maroon);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .breadcrumb a:hover { color: var(--maroon-light); }
        .breadcrumb span { color: #9CA3AF; }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #E5E7EB;
            width: 100%;
            overflow: hidden;
        }

        .form-card-header {
            padding: 24px 32px;
            background: linear-gradient(135deg, #FAFAFA 0%, #F5F5F5 100%);
            border-bottom: 1px solid #E5E7EB;
        }

        .form-card-header h2 {
            font-size: 18px;
            font-weight: 700;
            color: #1F2937;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-card-header h2 i { color: var(--maroon); }
        .form-card-header p { font-size: 14px; color: #6B7280; margin-top: 4px; }

        .form-card-body { padding: 32px; }

        /* Form Elements */
        .form-group { margin-bottom: 24px; }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .form-group label span { color: #EF4444; }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s;
            background: white;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--maroon);
            box-shadow: 0 0 0 4px rgba(123, 29, 58, 0.1);
        }

        .form-input:hover, .form-select:hover, .form-textarea:hover {
            border-color: #CBD5E1;
        }

        .form-textarea {
            resize: vertical;
            min-height: 140px;
        }

        .form-input.error, .form-select.error, .form-textarea.error {
            border-color: #EF4444;
        }

        .error-message {
            color: #EF4444;
            font-size: 13px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .error-message::before {
            content: '\f06a';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
        }

        .form-hint {
            font-size: 12px;
            color: #6B7280;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* File Upload */
        .file-upload {
            border: 2px dashed #D1D5DB;
            border-radius: 16px;
            padding: 40px 32px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: linear-gradient(135deg, #FAFAFA 0%, #F5F5F5 100%);
        }

        .file-upload:hover {
            border-color: var(--maroon);
            background: linear-gradient(135deg, #FDF2F4 0%, #FCE7F3 100%);
        }

        .file-upload.dragover {
            border-color: var(--maroon);
            background: rgba(123, 29, 58, 0.08);
            border-style: solid;
        }

        .file-upload .upload-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #E5E7EB 0%, #D1D5DB 100%);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 28px;
            color: #6B7280;
            transition: all 0.3s;
        }

        .file-upload:hover .upload-icon {
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-light) 100%);
            color: white;
            transform: scale(1.05);
        }

        .file-upload h4 {
            font-size: 16px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 6px;
        }

        .file-upload h4 span { color: var(--maroon); }

        .file-upload p {
            color: #6B7280;
            font-size: 13px;
        }

        .file-preview {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
            border: 1px solid #86EFAC;
            border-radius: 12px;
            margin-top: 16px;
        }

        .file-preview .file-icon {
            width: 48px;
            height: 48px;
            background: #166534;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .file-preview .file-info { flex: 1; }
        .file-preview .file-name { font-size: 14px; font-weight: 600; color: #166534; }
        .file-preview .file-size { font-size: 12px; color: #15803D; margin-top: 2px; }

        .file-preview .remove-file {
            width: 36px;
            height: 36px;
            background: white;
            border: 1px solid #FCA5A5;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #EF4444;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-preview .remove-file:hover {
            background: #FEE2E2;
        }

        /* Buttons */
        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(123, 29, 58, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(123, 29, 58, 0.35);
        }

        .btn-secondary {
            background: #F3F4F6;
            color: #374151;
            border: 2px solid #E5E7EB;
        }

        .btn-secondary:hover {
            background: #E5E7EB;
            border-color: #D1D5DB;
        }

        .form-actions {
            display: flex;
            gap: 16px;
            margin-top: 32px;
            padding-top: 32px;
            border-top: 1px solid #E5E7EB;
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
            border: 1px solid #FCD34D;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .info-box .info-icon {
            width: 44px;
            height: 44px;
            background: #D97706;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            flex-shrink: 0;
        }

        .info-box h4 { font-size: 14px; font-weight: 700; color: #92400E; margin-bottom: 6px; }
        .info-box ul { font-size: 13px; color: #92400E; padding-left: 16px; margin: 0; }
        .info-box li { margin-bottom: 4px; }

        /* Template Box */
        .template-box {
            background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%);
            border: 1px solid #7DD3FC;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .template-box .template-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .template-box .template-info { flex: 1; }
        .template-box h4 { font-size: 14px; font-weight: 700; color: #0369A1; }
        .template-box p { font-size: 13px; color: #0369A1; opacity: 0.8; margin-top: 2px; }

        /* Alert */
        .alert {
            padding: 18px 24px;
            border-radius: 14px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #D1FAE5, #A7F3D0);
            color: #065F46;
            border: 1px solid #6EE7B7;
        }

        .alert-error {
            background: linear-gradient(135deg, #FEE2E2, #FECACA);
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }

        .alert-warning {
            background: linear-gradient(135deg, #FEF3C7, #FDE68A);
            color: #92400E;
            border: 1px solid #FCD34D;
        }

        /* Logout Confirmation Modal */
        .logout-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .logout-modal-overlay.active {
            display: flex;
        }

        .logout-modal {
            background: white;
            border-radius: 20px;
            padding: 32px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .logout-modal-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #FEE2E2, #FECACA);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .logout-modal-icon i {
            font-size: 32px;
            color: #DC2626;
        }

        .logout-modal h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 8px;
        }

        .logout-modal p {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 24px;
        }

        .logout-modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .logout-modal-btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .logout-modal-btn.cancel {
            background: #F3F4F6;
            color: #374151;
        }

        .logout-modal-btn.cancel:hover {
            background: #E5E7EB;
        }

        .logout-modal-btn.confirm {
            background: linear-gradient(135deg, #DC2626, #B91C1C);
            color: white;
        }

        .logout-modal-btn.confirm:hover {
            background: linear-gradient(135deg, #B91C1C, #991B1B);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--maroon);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.open { transform: translateX(0); }

            .main-content {
                margin-left: 0;
                padding: 16px;
            }

            .mobile-menu-btn { display: block; }

            .form-card { max-width: 100%; }

            .page-header-card { padding: 24px; }
            .page-header-card h1 { font-size: 20px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="document.querySelector('.sidebar').classList.toggle('open')">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/upLogo.png') }}" alt="UP Logo">
            <h3>Startup Portal</h3>
            <p>UP Cebu Incubator</p>
        </div>

        <nav class="nav-menu">
            <div class="nav-section">Main Menu</div>
            <a href="{{ route('startup.dashboard') }}" class="nav-item {{ request()->routeIs('startup.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                Dashboard
            </a>
            <a href="{{ route('startup.upload-document') }}" class="nav-item {{ request()->routeIs('startup.upload-document') ? 'active' : '' }}">
                <i class="fas fa-cloud-upload-alt"></i>
                Upload Document
            </a>
            <a href="{{ route('startup.report-issue') }}" class="nav-item {{ request()->routeIs('startup.report-issue') ? 'active' : '' }}">
                <i class="fas fa-exclamation-triangle"></i>
                Report Issue
            </a>
            <a href="{{ route('startup.request-moa') }}" class="nav-item {{ request()->routeIs('startup.request-moa') ? 'active' : '' }}">
                <i class="fas fa-file-signature"></i>
                Request MOA
            </a>
            <a href="{{ route('startup.submit-payment') }}" class="nav-item {{ request()->routeIs('startup.submit-payment') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i>
                Submit Payment
            </a>

            <div class="nav-section">History & Records</div>
            <a href="{{ route('startup.track') }}" class="nav-item {{ request()->routeIs('startup.track*') ? 'active' : '' }}">
                <i class="fas fa-search-location"></i>
                Track Submissions
            </a>
            <a href="{{ route('startup.submissions') }}" class="nav-item {{ request()->routeIs('startup.submissions') ? 'active' : '' }}">
                <i class="fas fa-folder-open"></i>
                My Submissions
            </a>
            <a href="{{ route('startup.progress') }}" class="nav-item {{ request()->routeIs('startup.progress') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                Project Progress
            </a>
            <a href="{{ route('startup.room-issues') }}" class="nav-item {{ request()->routeIs('startup.room-issues') ? 'active' : '' }}">
                <i class="fas fa-tools"></i>
                Room Issues
            </a>
        </nav>

        <div class="sidebar-footer">
            <button type="button" class="logout-btn" onclick="showLogoutModal()">
                <i class="fas fa-sign-out-alt"></i>
                Sign Out
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header with Profile -->
        <div class="top-header">
            <!-- Navbar Breadcrumb -->
            <div class="top-header-left">
                <div class="navbar-breadcrumb">
                    <a href="{{ route('startup.dashboard') }}" title="Go to Dashboard">
                        <i class="fas fa-home"></i>
                    </a>
                    <span class="separator">/</span>
                    <span class="page-title">@yield('page-title', 'Page')</span>
                </div>
            </div>
            
            <div class="profile-dropdown" id="profileDropdown">
                <button type="button" class="profile-btn" id="profileBtn">
                    <div class="avatar">
                        @php
                            $words = explode(' ', $startup->company_name);
                            $initials = '';
                            foreach ($words as $word) {
                                if (!empty($word)) {
                                    $initials .= strtoupper(substr($word, 0, 1));
                                }
                                if (strlen($initials) >= 2) break;
                            }
                            echo $initials ?: strtoupper(substr($startup->company_name, 0, 2));
                        @endphp
                    </div>
                    <div class="info">
                        <div class="name">{{ Str::limit(ucwords(strtolower($startup->company_name)), 20) }}</div>
                        <div class="role">{{ $startup->startup_code }}</div>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="profile-menu">
                    <div class="profile-menu-header">
                        <div class="avatar">
                            @php
                                $words = explode(' ', $startup->company_name);
                                $initials = '';
                                foreach ($words as $word) {
                                    if (!empty($word)) {
                                        $initials .= strtoupper(substr($word, 0, 1));
                                    }
                                    if (strlen($initials) >= 2) break;
                                }
                                echo $initials ?: strtoupper(substr($startup->company_name, 0, 2));
                            @endphp
                        </div>
                        <div class="name">{{ ucwords(strtolower($startup->company_name)) }}</div>
                        <div class="email">{{ $startup->email }}</div>
                    </div>
                    <div class="profile-menu-items">
                        <a href="{{ route('startup.profile') }}" class="profile-menu-item">
                            <i class="fas fa-user-circle"></i>
                            Company Profile
                        </a>
                        <a href="{{ route('startup.profile') }}" class="profile-menu-item">
                            <i class="fas fa-cog"></i>
                            Settings
                        </a>
                        <button type="button" class="profile-menu-item danger" style="width: 100%; border: none; background: none; cursor: pointer;" onclick="showLogoutModal()">
                            <i class="fas fa-sign-out-alt"></i>
                            Sign Out
                        </button>
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

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        // Profile dropdown toggle
        document.addEventListener('DOMContentLoaded', function() {
            const profileBtn = document.getElementById('profileBtn');
            const profileDropdown = document.getElementById('profileDropdown');
            
            if (profileBtn) {
                profileBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    profileDropdown.classList.toggle('active');
                });
            }
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (profileDropdown && !profileDropdown.contains(event.target)) {
                    profileDropdown.classList.remove('active');
                }
            });
        });

        // Logout Modal Functions
        function showLogoutModal() {
            document.getElementById('logoutModal').classList.add('active');
        }

        function hideLogoutModal() {
            document.getElementById('logoutModal').classList.remove('active');
        }

        function confirmLogout() {
            document.getElementById('logoutForm').submit();
        }

        // Close modal when clicking overlay
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('logoutModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        hideLogoutModal();
                    }
                });
            }
        });
    </script>

    <!-- Logout Confirmation Modal -->
    <div class="logout-modal-overlay" id="logoutModal">
        <div class="logout-modal">
            <div class="logout-modal-icon">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <h3>Sign Out</h3>
            <p>Are you sure you want to sign out of your account?</p>
            <div class="logout-modal-actions">
                <button type="button" class="logout-modal-btn cancel" onclick="hideLogoutModal()">
                    Cancel
                </button>
                <button type="button" class="logout-modal-btn confirm" onclick="confirmLogout()">
                    Sign Out
                </button>
            </div>
            <form id="logoutForm" action="{{ route('startup.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
