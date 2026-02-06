<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/upLogo.png') }}">
    <title>Dashboard - {{ ucwords(strtolower($startup->company_name)) }} - UP Cebu Startup Portal</title>
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
            background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, #7B1D3A 0%, #5a1428 50%, #3d0e1a 100%);
            width: 280px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 1000;
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
            background: linear-gradient(180deg, #FFBF00 0%, #D4A500 100%);
            border-radius: 10px;
            transition: background 0.3s;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #FFD700 0%, #FFBF00 100%);
        }

        /* Firefox scrollbar */
        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: #FFBF00 rgba(0, 0, 0, 0.2);
        }

        .sidebar-header {
            padding: 28px 24px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar-header img {
            height: 52px;
            margin: 0 auto 14px;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        .sidebar-header h3 {
            color: white;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .sidebar-header p {
            color: #FFBF00;
            font-size: 12px;
            font-weight: 500;
        }

        .company-card {
            margin: 20px 16px;
            padding: 20px;
            background: linear-gradient(135deg, rgba(255, 191, 0, 0.15) 0%, rgba(255, 165, 0, 0.1) 100%);
            border: 1px solid rgba(255, 191, 0, 0.3);
            border-radius: 16px;
            text-align: center;
        }

        .company-avatar {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            font-size: 30px;
            font-weight: 800;
            color: #7B1D3A;
            box-shadow: 0 8px 20px rgba(255, 191, 0, 0.4);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .company-name {
            color: white;
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .company-code {
            display: inline-block;
            background: rgba(255, 191, 0, 0.2);
            color: #FFBF00;
            font-size: 11px;
            font-family: 'Courier New', monospace;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .room-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 12px;
            padding: 6px 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
        }

        .sidebar-menu {
            padding: 8px 0;
        }

        .menu-section {
            padding: 16px 20px 8px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.35);
            font-weight: 700;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            margin: 2px 12px;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border-radius: 12px;
        }

        .menu-item i {
            width: 22px;
            margin-right: 14px;
            font-size: 17px;
            text-align: center;
        }

        .menu-item span {
            font-size: 14px;
            font-weight: 500;
        }

        .menu-item:hover {
            background: rgba(255, 191, 0, 0.15);
            color: white;
            transform: translateX(4px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            color: #7B1D3A;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(255, 191, 0, 0.4);
        }

        .menu-badge {
            margin-left: auto;
            background: #FFBF00;
            color: #7B1D3A;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 12px;
            font-weight: 700;
        }

        .sidebar-footer {
            padding: 20px 16px;
            margin-top: auto;
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
            transition: all 0.3s;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.3);
            color: #FCA5A5;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 28px;
            min-height: 100vh;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #7B1D3A 0%, #A62450 50%, #7B1D3A 100%);
            border-radius: 20px;
            padding: 32px 36px;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(123, 29, 58, 0.3);
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 191, 0, 0.15) 0%, transparent 70%);
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 40%;
            height: 150%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
        }

        .welcome-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-text h1 {
            font-size: 28px;
            font-weight: 800;
            color: white;
            margin-bottom: 8px;
        }

        .welcome-text p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 15px;
        }

        .welcome-insights {
            display: flex;
            gap: 12px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .insight-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 13px;
            color: white;
            transition: all 0.3s ease;
        }

        .insight-badge:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        .insight-badge i {
            color: #FFBF00;
        }

        .insight-badge.alert {
            background: rgba(239, 68, 68, 0.3);
            border-color: rgba(239, 68, 68, 0.4);
        }

        .insight-badge.alert i {
            color: #FCA5A5;
        }

        .insight-badge.success {
            background: rgba(16, 185, 129, 0.3);
            border-color: rgba(16, 185, 129, 0.4);
        }

        .insight-badge.success i {
            color: #6EE7B7;
        }

        .welcome-date {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 14px 24px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }

        .welcome-date .date-day {
            font-size: 32px;
            font-weight: 800;
            color: #FFBF00;
            line-height: 1;
        }

        .welcome-date .date-info {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 4px;
        }

        /* MOA Status Card */
        .moa-card {
            background: white;
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #E5E7EB;
            position: relative;
            overflow: hidden;
        }

        .moa-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
        }

        .moa-card.active::before { background: linear-gradient(180deg, #10B981, #059669); }
        .moa-card.pending::before { background: linear-gradient(180deg, #F59E0B, #D97706); }
        .moa-card.expired::before { background: linear-gradient(180deg, #EF4444, #DC2626); }
        .moa-card.none::before { background: linear-gradient(180deg, #9CA3AF, #6B7280); }

        .moa-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .moa-card.active .moa-icon { background: linear-gradient(135deg, #D1FAE5, #A7F3D0); color: #059669; }
        .moa-card.pending .moa-icon { background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #D97706; }
        .moa-card.expired .moa-icon { background: linear-gradient(135deg, #FEE2E2, #FECACA); color: #DC2626; }
        .moa-card.none .moa-icon { background: linear-gradient(135deg, #F3F4F6, #E5E7EB); color: #6B7280; }

        .moa-info { flex: 1; }

        .moa-info h4 {
            font-size: 17px;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 4px;
        }

        .moa-info p {
            font-size: 14px;
            color: #6B7280;
        }

        .moa-action .btn-primary {
            padding: 12px 24px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #E5E7EB;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: block;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-card:hover .stat-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.1;
            transform: translate(30%, -30%);
        }

        .stat-card:nth-child(1)::after { background: #7C3AED; }
        .stat-card:nth-child(2)::after { background: #F59E0B; }
        .stat-card:nth-child(3)::after { background: #10B981; }
        .stat-card:nth-child(4)::after { background: #EF4444; }

        .stat-card:nth-child(1):hover { border-color: #7C3AED; }
        .stat-card:nth-child(2):hover { border-color: #F59E0B; }
        .stat-card:nth-child(3):hover { border-color: #10B981; }
        .stat-card:nth-child(4):hover { border-color: #EF4444; }

        .stat-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            transition: all 0.3s ease;
        }

        .stat-arrow {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 32px;
            height: 32px;
            background: #F3F4F6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6B7280;
            opacity: 0;
            transform: translateX(-8px);
            transition: all 0.3s ease;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: #1F2937;
            line-height: 1;
        }

        .stat-value.animate {
            animation: countUp 0.6s ease-out forwards;
        }

        @keyframes countUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stat-label {
            color: #6B7280;
            font-size: 14px;
            font-weight: 500;
            margin-top: 6px;
        }

        .stat-trend {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .stat-trend.up {
            background: #D1FAE5;
            color: #059669;
        }

        .stat-trend.neutral {
            background: #F3F4F6;
            color: #6B7280;
        }

        /* Two Column Grid */
        .two-col-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 28px;
        }

        /* Content Card */
        .content-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #E5E7EB;
            overflow: hidden;
        }

        .content-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #FAFAFA 0%, #F5F5F5 100%);
        }

        .content-card-title {
            font-size: 17px;
            font-weight: 700;
            color: #1F2937;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .content-card-title i {
            color: #7B1D3A;
            font-size: 16px;
        }

        .view-all-link {
            color: #7B1D3A;
            font-size: 13px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
        }

        .view-all-link:hover {
            color: #A62450;
            gap: 10px;
        }

        .content-card-body {
            padding: 0;
        }

        /* Activity List */
        .activity-list {
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px 24px;
            border-bottom: 1px solid #F3F4F6;
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
        }

        .activity-item::before {
            content: '';
            position: absolute;
            left: 44px;
            top: 54px;
            bottom: -18px;
            width: 2px;
            background: linear-gradient(180deg, #E5E7EB 0%, transparent 100%);
        }

        .activity-item:last-child::before {
            display: none;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background: linear-gradient(90deg, #FAFAFA 0%, white 100%);
            transform: translateX(4px);
        }

        .activity-item:hover .activity-icon {
            transform: scale(1.1);
        }

        .activity-item:hover .activity-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        .activity-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .activity-icon.doc { background: linear-gradient(135deg, #EDE9FE, #DDD6FE); color: #7C3AED; }
        .activity-icon.moa { background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #D97706; }
        .activity-icon.fin { background: linear-gradient(135deg, #D1FAE5, #A7F3D0); color: #059669; }
        .activity-icon.issue { background: linear-gradient(135deg, #FEE2E2, #FECACA); color: #DC2626; }

        .activity-content {
            flex: 1;
            min-width: 0;
        }

        .activity-title {
            font-size: 14px;
            font-weight: 600;
            color: #1F2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .activity-meta {
            font-size: 12px;
            color: #9CA3AF;
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .activity-arrow {
            width: 28px;
            height: 28px;
            background: #F3F4F6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9CA3AF;
            font-size: 12px;
            opacity: 0;
            transform: translateX(-4px);
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .activity-status {
            font-size: 11px;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .status-pending { background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #92400E; }
        .status-under_review { background: linear-gradient(135deg, #DBEAFE, #BFDBFE); color: #1E40AF; }
        .status-approved { background: linear-gradient(135deg, #D1FAE5, #A7F3D0); color: #065F46; }
        .status-rejected { background: linear-gradient(135deg, #FEE2E2, #FECACA); color: #991B1B; }
        .status-in_progress { background: linear-gradient(135deg, #DBEAFE, #BFDBFE); color: #1E40AF; }
        .status-resolved { background: linear-gradient(135deg, #D1FAE5, #A7F3D0); color: #065F46; }

        .empty-state {
            padding: 48px 24px;
            text-align: center;
            color: #9CA3AF;
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 16px;
            opacity: 0.4;
        }

        .empty-state p {
            font-size: 14px;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(123, 29, 58, 0.35);
        }

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

        /* Responsive */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 1024px) {
            .two-col-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 16px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .welcome-content {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }

            .welcome-banner {
                padding: 24px;
            }

            .welcome-text h1 {
                font-size: 22px;
            }

            .moa-card {
                flex-direction: column;
                text-align: center;
            }

            .moa-action {
                margin-top: 16px;
            }
        }

        /* Mobile Menu Toggle */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: #7B1D3A;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
        }

        /* Top Header Bar */
        .top-header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 20px;
            padding: 0;
            position: relative;
            z-index: 100;
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
            border-color: #7B1D3A;
            box-shadow: 0 4px 12px rgba(123, 29, 58, 0.15);
        }

        .profile-btn .avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #7B1D3A 0%, #A62450 100%);
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
            background: linear-gradient(135deg, #7B1D3A 0%, #A62450 100%);
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
            color: #7B1D3A;
        }

        .profile-menu-item i {
            width: 18px;
            text-align: center;
            color: #6B7280;
        }

        .profile-menu-item:hover i {
            color: #7B1D3A;
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

        /* Project Progress Panel */
        .progress-panel {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #E5E7EB;
            margin-bottom: 28px;
            overflow: hidden;
        }

        .progress-panel-header {
            padding: 20px 24px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #EDE9FE 0%, #DDD6FE 100%);
        }

        .progress-panel-title {
            font-size: 17px;
            font-weight: 700;
            color: #5B21B6;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .progress-form {
            padding: 24px;
            border-bottom: 1px solid #E5E7EB;
            background: #FAFAFA;
        }

        .progress-form-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .progress-input, .progress-select, .progress-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #E5E7EB;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            background: white;
        }

        .progress-input:focus, .progress-select:focus, .progress-textarea:focus {
            outline: none;
            border-color: #7B1D3A;
            box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1);
        }

        .progress-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .progress-file-upload {
            border: 2px dashed #D1D5DB;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }

        .progress-file-upload:hover {
            border-color: #7B1D3A;
            background: #FDF2F4;
        }

        .progress-file-upload i {
            font-size: 24px;
            color: #9CA3AF;
            margin-bottom: 8px;
        }

        .progress-file-upload p {
            font-size: 13px;
            color: #6B7280;
        }

        .progress-file-upload span {
            color: #7B1D3A;
            font-weight: 600;
        }

        .progress-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 16px;
        }

        .progress-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .progress-item {
            display: flex;
            gap: 16px;
            padding: 20px 24px;
            border-bottom: 1px solid #F3F4F6;
            transition: all 0.3s;
        }

        .progress-item:last-child {
            border-bottom: none;
        }

        .progress-item:hover {
            background: #FAFAFA;
        }

        .progress-item-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .progress-item-icon.development { background: linear-gradient(135deg, #DBEAFE, #BFDBFE); color: #2563EB; }
        .progress-item-icon.funding { background: linear-gradient(135deg, #D1FAE5, #A7F3D0); color: #059669; }
        .progress-item-icon.partnership { background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #D97706; }
        .progress-item-icon.launch { background: linear-gradient(135deg, #FCE7F3, #FBCFE8); color: #DB2777; }
        .progress-item-icon.achievement { background: linear-gradient(135deg, #EDE9FE, #DDD6FE); color: #7C3AED; }
        .progress-item-icon.other { background: linear-gradient(135deg, #F3F4F6, #E5E7EB); color: #6B7280; }

        .progress-item-content {
            flex: 1;
            min-width: 0;
        }

        .progress-item-title {
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 4px;
        }

        .progress-item-desc {
            font-size: 14px;
            color: #6B7280;
            line-height: 1.5;
            margin-bottom: 8px;
        }

        .progress-item-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 12px;
            color: #9CA3AF;
        }

        .progress-item-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .progress-status-submitted { background: #FEF3C7; color: #92400E; }
        .progress-status-reviewed { background: #DBEAFE; color: #1E40AF; }
        .progress-status-acknowledged { background: #D1FAE5; color: #065F46; }

        .progress-admin-comment {
            margin-top: 12px;
            padding: 12px;
            background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%);
            border-radius: 8px;
            border-left: 3px solid #0284C7;
        }

        .progress-admin-comment-label {
            font-size: 11px;
            font-weight: 600;
            color: #0369A1;
            margin-bottom: 4px;
        }

        .progress-admin-comment-text {
            font-size: 13px;
            color: #0C4A6E;
        }

        .file-attached {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: #F3F4F6;
            border-radius: 6px;
            font-size: 12px;
            color: #4B5563;
            text-decoration: none;
            transition: all 0.3s;
        }

        .file-attached:hover {
            background: #E5E7EB;
            color: #1F2937;
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
    </style>
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

        <nav class="sidebar-menu">
            <div class="menu-section">Main Menu</div>
            <a href="{{ route('startup.dashboard') }}" class="menu-item active">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="{{ route('startup.upload-document') }}" class="menu-item">
                <i class="fas fa-cloud-upload-alt"></i>
                <span>Upload Document</span>
            </a>
            <a href="{{ route('startup.report-issue') }}" class="menu-item">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Report Issue</span>
            </a>
            <a href="{{ route('startup.request-moa') }}" class="menu-item">
                <i class="fas fa-file-signature"></i>
                <span>Request MOA</span>
            </a>
            <a href="{{ route('startup.submit-payment') }}" class="menu-item">
                <i class="fas fa-credit-card"></i>
                <span>Submit Payment</span>
            </a>
            <div class="menu-section">History & Records</div>
            <a href="{{ route('startup.submissions') }}" class="menu-item">
                <i class="fas fa-folder-open"></i>
                <span>My Submissions</span>
                @if($pendingCount > 0)
                    <span class="menu-badge">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('startup.progress') }}" class="menu-item">
                <i class="fas fa-chart-line"></i>
                <span>Project Progress</span>
            </a>
            
            <a href="{{ route('startup.room-issues') }}" class="menu-item">
                <i class="fas fa-tools"></i>
                <span>Room Issues</span>
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

        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="welcome-content">
                <div class="welcome-text">
                    <h1>Welcome back, {{ ucwords(strtolower($startup->company_name)) }}! 👋</h1>
                    <p>Here's your startup dashboard overview. Manage your documents, track issues, and stay updated.</p>
                    
                    <!-- Insight Badges -->
                    <div class="welcome-insights">
                        @php
                            $pendingSubmissions = $startup->submissions()->where('status', 'pending')->count();
                            $pendingIssues = $startup->roomIssues()->where('status', 'pending')->count();
                            $totalItems = $documentCount + $moaCount + $paymentCount;
                        @endphp
                        
                        @if($pendingSubmissions > 0)
                            <a href="{{ route('startup.submissions') }}" class="insight-badge alert">
                                <i class="fas fa-clock"></i>
                                {{ $pendingSubmissions }} pending {{ Str::plural('submission', $pendingSubmissions) }}
                            </a>
                        @endif
                        
                        @if($pendingIssues > 0)
                            <a href="{{ route('startup.room-issues') }}" class="insight-badge alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $pendingIssues }} unresolved {{ Str::plural('issue', $pendingIssues) }}
                            </a>
                        @endif
                        
                        @if($pendingSubmissions == 0 && $pendingIssues == 0)
                            <span class="insight-badge success">
                                <i class="fas fa-check-circle"></i>
                                All caught up!
                            </span>
                        @endif
                        
                        <span class="insight-badge">
                            <i class="fas fa-folder"></i>
                            {{ $totalItems }} total {{ Str::plural('submission', $totalItems) }}
                        </span>
                        
                        @if($startup->moa_status === 'active')
                            <span class="insight-badge success">
                                <i class="fas fa-file-contract"></i>
                                MOA Active
                            </span>
                        @elseif($startup->moa_status === 'expired')
                            <span class="insight-badge alert">
                                <i class="fas fa-file-contract"></i>
                                MOA Expired
                            </span>
                        @endif
                    </div>
                </div>
                <div class="welcome-date">
                    <div class="date-day">{{ now()->format('d') }}</div>
                    <div class="date-info">{{ now()->format('F Y') }}</div>
                </div>
            </div>
        </div>

        <!-- MOA Status Card -->
        <div class="moa-card {{ $startup->moa_status }}">
            <div class="moa-icon">
                <i class="fas fa-file-contract"></i>
            </div>
            <div class="moa-info">
                @if($startup->moa_status === 'active')
                    <h4><i class="fas fa-check-circle" style="color: #10B981; margin-right: 8px;"></i>MOA Status: Active</h4>
                    <p>Your Memorandum of Agreement is active{{ $startup->moa_expiry ? ' until ' . $startup->moa_expiry->format('F d, Y') : '' }}</p>
                @elseif($startup->moa_status === 'pending')
                    <h4><i class="fas fa-clock" style="color: #F59E0B; margin-right: 8px;"></i>MOA Status: Pending Review</h4>
                    <p>Your MOA request is currently being reviewed by the administration</p>
                @elseif($startup->moa_status === 'expired')
                    <h4><i class="fas fa-exclamation-circle" style="color: #EF4444; margin-right: 8px;"></i>MOA Status: Expired</h4>
                    <p>Your MOA has expired. Please submit a renewal request to continue using the facilities.</p>
                @else
                    <h4><i class="fas fa-info-circle" style="color: #6b7280; margin-right: 8px;"></i>MOA Status: Not Submitted</h4>
                    <p>You haven't submitted a Memorandum of Agreement yet. Submit one to formalize your incubation.</p>
                @endif
            </div>
            <div class="moa-action">
                @if($startup->moa_status !== 'active')
                    <a href="{{ route('startup.request-moa') }}" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        {{ $startup->moa_status === 'expired' ? 'Renew MOA' : ($startup->moa_status === 'pending' ? 'View Status' : 'Request MOA') }}
                    </a>
                @endif
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <a href="{{ route('startup.submissions', ['type' => 'document']) }}" class="stat-card">
                <div class="stat-arrow"><i class="fas fa-arrow-right"></i></div>
                <div class="stat-header">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #EDE9FE, #DDD6FE); color: #7C3AED;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
                <div class="stat-value animate" data-count="{{ $documentCount }}">{{ $documentCount }}</div>
                <div class="stat-label">Documents Submitted</div>
                <div class="stat-trend neutral"><i class="fas fa-folder"></i> Click to view</div>
            </a>
            <a href="{{ route('startup.submissions', ['type' => 'moa']) }}" class="stat-card">
                <div class="stat-arrow"><i class="fas fa-arrow-right"></i></div>
                <div class="stat-header">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #D97706;">
                        <i class="fas fa-file-signature"></i>
                    </div>
                </div>
                <div class="stat-value animate" data-count="{{ $moaCount }}">{{ $moaCount }}</div>
                <div class="stat-label">MOA Requests</div>
                <div class="stat-trend neutral"><i class="fas fa-folder"></i> Click to view</div>
            </a>
            <a href="{{ route('startup.submissions', ['type' => 'finance']) }}" class="stat-card">
                <div class="stat-arrow"><i class="fas fa-arrow-right"></i></div>
                <div class="stat-header">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #D1FAE5, #A7F3D0); color: #059669;">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
                <div class="stat-value animate" data-count="{{ $paymentCount }}">{{ $paymentCount }}</div>
                <div class="stat-label">Payment Submissions</div>
                <div class="stat-trend neutral"><i class="fas fa-folder"></i> Click to view</div>
            </a>
            <a href="{{ route('startup.room-issues') }}" class="stat-card">
                <div class="stat-arrow"><i class="fas fa-arrow-right"></i></div>
                <div class="stat-header">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #FEE2E2, #FECACA); color: #DC2626;">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
                <div class="stat-value animate" data-count="{{ $roomIssueCount }}">{{ $roomIssueCount }}</div>
                <div class="stat-label">Room Issues Reported</div>
                @if($roomIssueCount > 0)
                    <div class="stat-trend up"><i class="fas fa-exclamation-circle"></i> {{ $startup->roomIssues()->where('status', 'pending')->count() }} pending</div>
                @else
                    <div class="stat-trend neutral"><i class="fas fa-check"></i> All clear</div>
                @endif
            </a>
        </div>

        <!-- Two Column Grid -->
        <div class="two-col-grid">
            <!-- Recent Submissions -->
            <div class="content-card">
                <div class="content-card-header">
                    <h3 class="content-card-title"><i class="fas fa-history"></i> Recent Submissions</h3>
                    <a href="{{ route('startup.submissions') }}" class="view-all-link">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="content-card-body">
                    <div class="activity-list">
                        @forelse($recentSubmissions as $submission)
                            <div class="activity-item">
                                <div class="activity-icon {{ $submission->type === 'document' ? 'doc' : ($submission->type === 'moa' ? 'moa' : 'fin') }}">
                                    <i class="fas {{ $submission->type === 'document' ? 'fa-file-alt' : ($submission->type === 'moa' ? 'fa-file-signature' : 'fa-receipt') }}"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">
                                        {{ $submission->type === 'document' ? $submission->document_type : ($submission->type === 'moa' ? 'MOA Request' : 'Payment - ' . $submission->invoice_number) }}
                                    </div>
                                    <div class="activity-meta">
                                        <span><i class="fas fa-hashtag"></i> {{ $submission->tracking_code }}</span>
                                        <span>•</span>
                                        <span>{{ $submission->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <span class="activity-status status-{{ $submission->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                </span>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>No submissions yet. Start by uploading a document!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Room Issues -->
            <div class="content-card">
                <div class="content-card-header">
                    <h3 class="content-card-title"><i class="fas fa-tools"></i> Room Issues</h3>
                    <a href="{{ route('startup.room-issues') }}" class="view-all-link">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="content-card-body">
                    <div class="activity-list">
                        @forelse($recentRoomIssues as $issue)
                            <div class="activity-item">
                                <div class="activity-icon issue">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">{{ ucfirst($issue->issue_type) }} - Room {{ $issue->room_number }}</div>
                                    <div class="activity-meta">
                                        <span><i class="fas fa-hashtag"></i> {{ $issue->tracking_code }}</span>
                                        <span>•</span>
                                        <span>{{ $issue->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <span class="activity-status status-{{ $issue->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                                </span>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-clipboard-check"></i>
                                <p>No room issues reported. Everything running smoothly!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

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

            // Close logout modal when clicking overlay
            const logoutModal = document.getElementById('logoutModal');
            if (logoutModal) {
                logoutModal.addEventListener('click', function(e) {
                    if (e.target === logoutModal) {
                        hideLogoutModal();
                    }
                });
            }
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
    </script>
</body>
</html>
