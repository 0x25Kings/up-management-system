@php
/**
 * @var \Illuminate\Support\Collection<int, \App\Models\Attendance> $todayAttendances
 * @var \Illuminate\Support\Collection<int, \App\Models\Attendance> $attendanceHistory
 * @var \Illuminate\Support\Collection<int, \App\Models\Intern> $interns
 * @var \Illuminate\Support\Collection<int, \App\Models\Task> $tasks
 * @var \Illuminate\Support\Collection<int, \App\Models\StartupSubmission> $progressUpdates
 */
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/upLogo.png') }}">
    <title>Admin Dashboard - UP Cebu Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #7B1D3A;
            --accent-color: #FFBF00;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #F9FAFB;
        }

        body.compact-mode .sidebar {
            width: 220px;
            min-width: 220px;
            max-width: 220px;
        }

        body.compact-mode .main-content {
            margin-left: 220px;
        }

        body.no-animations * {
            transition: none !important;
            animation: none !important;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, #5a1428 50%, #4a1020 100%);
            width: 260px;
            min-width: 260px;
            max-width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }

        .sidebar.sidebar-gradient {
            background: linear-gradient(180deg, var(--primary-color) 0%, #5a1428 50%, #4a1020 100%);
        }

        .sidebar.sidebar-solid {
            background: var(--primary-color);
        }

        .sidebar.sidebar-dark {
            background: linear-gradient(180deg, #1F2937 0%, #111827 100%);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 191, 0, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 191, 0, 0.5);
        }

        .sidebar-logo {
            padding: 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar-logo img {
            height: 48px;
            width: auto;
            margin: 0 auto 10px auto;
            display: block;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        .sidebar-logo h3 {
            color: white;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 6px;
            text-align: center;
            line-height: 1.4;
            letter-spacing: 0.3px;
        }

        .sidebar-logo p {
            color: #FFBF00;
            font-size: 11px;
            text-align: center;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            padding: 12px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 11px 20px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            position: relative;
            margin: 2px 10px;
            border-radius: 10px;
        }

        .menu-item i:first-child {
            width: 22px;
            margin-right: 14px;
            font-size: 17px;
            transition: transform 0.25s ease, color 0.25s ease;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
        }

        .menu-item:hover i:first-child {
            transform: scale(1.1);
            color: #FFBF00;
        }

        .menu-item.active {
            background: linear-gradient(135deg, #FFBF00 0%, #FFD54F 100%);
            color: #7B1D3A;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255, 191, 0, 0.3);
        }

        .menu-item.active i:first-child {
            color: #7B1D3A;
            transform: scale(1);
        }

        .menu-item-parent {
            cursor: pointer;
        }

        .menu-item .dropdown-icon {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .menu-item .dropdown-icon.open {
            transform: rotate(180deg);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(0, 0, 0, 0.15);
            margin: 0 12px;
            border-radius: 0 0 10px 10px;
        }

        .submenu.open {
            max-height: 400px;
        }

        .submenu-item {
            display: flex;
            align-items: center;
            padding: 11px 20px 11px 48px;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 13px;
            font-weight: 500;
            position: relative;
        }

        .submenu-item::before {
            content: '';
            position: absolute;
            left: 28px;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transition: all 0.25s ease;
        }

        .submenu-item:hover {
            background: rgba(255, 191, 0, 0.1);
            color: #FFBF00;
        }

        .submenu-item:hover::before {
            background: #FFBF00;
            box-shadow: 0 0 8px rgba(255, 191, 0, 0.5);
        }

        .submenu-item i {
            width: 16px;
            margin-right: 10px;
            font-size: 12px;
            display: none;
        }

        .page-content {
            display: none;
            padding: 24px 32px;
        }

        .page-content.active {
            display: block;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 10px 20px;
            border: 2px solid #E5E7EB;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 600;
            color: #6B7280;
        }

        .filter-tab.active {
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            color: #7B1D3A;
            border-color: #FFBF00;
        }

        .filter-tab:hover {
            border-color: #FFBF00;
        }

        .intern-table-wrapper {
            max-height: 600px;
            overflow-y: auto;
        }

        .school-group {
            margin-bottom: 24px;
        }

        .school-header {
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
            color: white;
            padding: 16px 24px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .school-header:hover {
            background: linear-gradient(135deg, #5a1428 0%, #3d0e1c 100%);
        }

        .school-header h4 {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
        }

        .school-badge {
            background: rgba(255, 191, 0, 0.2);
            color: #FFBF00;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .progress-bar-container {
            width: 100%;
            height: 8px;
            background: #E5E7EB;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            transition: width 0.3s ease;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        /* Time & Attendance Styles */
        .time-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .time-stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid;
        }

        .time-stat-card.required { border-left-color: #3B82F6; }
        .time-stat-card.completed { border-left-color: #10B981; }
        .time-stat-card.remaining { border-left-color: #FFBF00; }
        .time-stat-card.overtime { border-left-color: #7B1D3A; }

        .time-stat-label {
            font-size: 12px;
            color: #6B7280;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .time-stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1F2937;
        }

        .overtime-alert {
            background: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .overtime-alert i {
            color: #F59E0B;
            font-size: 20px;
        }

        .overtime-alert-text {
            flex: 1;
        }

        .overtime-alert-title {
            font-weight: 700;
            color: #92400E;
            margin-bottom: 4px;
        }

        .overtime-alert-desc {
            font-size: 14px;
            color: #78350F;
        }

        .time-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            border-bottom: 2px solid #E5E7EB;
        }

        .time-tab {
            padding: 12px 24px;
            border: none;
            background: none;
            font-size: 14px;
            font-weight: 600;
            color: #6B7280;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: all 0.3s ease;
        }

        .time-tab.active {
            color: #7B1D3A;
            border-bottom-color: #7B1D3A;
        }

        .time-tab:hover {
            color: #7B1D3A;
        }

        .hours-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .hours-badge.normal { background: #D1FAE5; color: #065F46; }
        .hours-badge.overtime { background: #FEF3C7; color: #92400E; }
        .hours-badge.deficit { background: #FEE2E2; color: #991B1B; }

        .overtime-request-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 16px;
            border: 1px solid #E5E7EB;
        }

        .overtime-request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .overtime-request-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .overtime-hours {
            font-size: 24px;
            font-weight: 700;
            color: #7B1D3A;
        }

        .overtime-actions {
            display: flex;
            gap: 8px;
        }

        .btn-approve {
            padding: 8px 20px;
            background: #10B981;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-approve:hover {
            background: #059669;
        }

        .btn-reject {
            padding: 8px 20px;
            background: #EF4444;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-reject:hover {
            background: #DC2626;
        }

        .time-in-out-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #F3F4F6;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        .time-in-out-badge i {
            font-size: 10px;
        }

        .time-in-out-badge.time-in { color: #10B981; }
        .time-in-out-badge.time-out { color: #EF4444; }

        /* Filter Bar Styles */
        .filter-bar {
            background: white;
            padding: 20px 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .filter-group {
            display: flex;
            gap: 12px;
            align-items: center;
            flex: 0 1 auto;
            min-width: auto;
            white-space: nowrap;
        }

        .filter-label {
            font-size: 14px;
            font-weight: 600;
            color: #6B7280;
            white-space: nowrap;
        }

        .filter-select {
            padding: 8px 12px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            font-size: 14px;
            color: #1F2937;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: #7B1D3A;
            box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1);
        }

        .filter-search {
            position: relative;
            flex: 1 1 250px;
            min-width: 250px;
            margin-left: auto;
        }

        .filter-search input {
            width: 100%;
            padding: 8px 12px 8px 36px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            font-size: 14px;
            color: #1F2937;
            transition: all 0.3s ease;
        }

        .filter-search input:focus {
            outline: none;
            border-color: #7B1D3A;
            box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1);
        }

        .filter-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6B7280;
        }

        .filter-btn {
            padding: 8px 16px;
            background: #7B1D3A;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .filter-btn:hover {
            background: #5a1428;
        }

        .filter-btn.secondary {
            background: white;
            color: #6B7280;
            border: 1px solid #E5E7EB;
        }

        .filter-btn.secondary:hover {
            background: #F9FAFB;
            color: #1F2937;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(4px);
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from { opacity: 0; transform: translateY(-30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #1F2937;
        }

        .modal-close {
            width: 36px;
            height: 36px;
            border: none;
            background: #F3F4F6;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: #E5E7EB;
        }

        .modal-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 8px;
        }

        .form-label.required::after {
            content: '*';
            color: #EF4444;
            margin-left: 4px;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            font-size: 14px;
            color: #1F2937;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #7B1D3A;
            box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1);
        }

        /* Startup Modal Input Styles */
        #startupModal input:focus,
        #startupModal select:focus {
            outline: none;
            border-color: #7B1D3A !important;
            box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.15);
        }

        #startupModal input:hover,
        #startupModal select:hover {
            border-color: #CBD5E1;
        }

        #startupModal button[type="button"]:hover {
            transform: translateY(-1px);
        }

        #startupModal button[type="submit"]:hover {
            background: linear-gradient(135deg, #8B2344 0%, #B63460 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(123, 29, 58, 0.3);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-radio-group {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }

        .form-radio-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 14px;
            color: #6B7280;
        }

        .form-radio-label input[type="radio"] {
            cursor: pointer;
        }

        .intern-select-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            max-height: 200px;
            overflow-y: auto;
            padding: 12px;
            background: #F9FAFB;
            border-radius: 8px;
            margin-top: 8px;
        }

        .intern-checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .intern-checkbox-label:hover {
            background: #F3F4F6;
        }

        .intern-checkbox-label input[type="checkbox"] {
            cursor: pointer;
        }

        .modal-footer {
            padding: 16px 20px;
            border-top: 1px solid #E5E7EB;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .close-modal {
            font-size: 28px;
            color: #6B7280;
            cursor: pointer;
            line-height: 1;
            transition: all 0.3s ease;
        }

        .close-modal:hover {
            color: #1F2937;
        }

        .btn-cancel {
            padding: 10px 20px;
            background: #F3F4F6;
            color: #6B7280;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #E5E7EB;
        }

        .btn-submit {
            padding: 10px 20px;
            background: linear-gradient(135deg, #7B1D3A, #A62450);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(123, 29, 58, 0.3);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-modal {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-modal.primary {
            background: #7B1D3A;
            color: white;
        }

        .btn-modal.primary:hover {
            background: #5a1428;
        }

        .btn-modal.secondary {
            background: #F3F4F6;
            color: #6B7280;
        }

        .btn-modal.secondary:hover {
            background: #E5E7EB;
        }

        /* Responsive Modal Styles for Small Laptops */
        @media (max-width: 1366px) {
            .modal-content {
                max-width: 600px;
                max-height: 85vh;
            }

            .modal-body {
                padding: 16px;
            }

            .form-group {
                margin-bottom: 12px;
            }

            .form-label {
                font-size: 13px;
                margin-bottom: 6px;
            }

            .form-input {
                padding: 8px 10px;
                font-size: 13px;
            }

            .form-textarea {
                min-height: 80px;
            }

            .intern-select-grid {
                grid-template-columns: 1fr;
                max-height: 150px;
            }
        }

        /* Activity Log Responsive Styles */
        @media (max-width: 1366px) {
            #activity-log > div:nth-child(3) {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        @media (max-width: 768px) {
            #activity-log > div:nth-child(3) {
                grid-template-columns: 1fr !important;
            }
        }

        /* Incubatee Tracker Responsive Styles */
        @media (max-width: 1200px) {
            #incubatee-tracker > div:nth-child(2) {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            #issues-management > div:nth-child(2) {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        @media (max-width: 768px) {
            #incubatee-tracker > div:nth-child(2) {
                grid-template-columns: 1fr !important;
            }
            #issues-management > div:nth-child(2) {
                grid-template-columns: 1fr !important;
            }
            #incubatee-tracker > div:first-child {
                flex-direction: column !important;
                gap: 16px;
            }
            #incubatee-tracker > div:first-child button {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 1024px) {
            .modal-content {
                max-width: 90%;
                max-height: 80vh;
            }

            .modal-header {
                padding: 16px;
            }

            .modal-title {
                font-size: 18px;
            }

            .modal-footer {
                padding: 12px 16px;
            }

            .form-radio-group {
                flex-direction: column;
                gap: 10px;
            }
        }

        .detail-section {
            margin-bottom: 24px;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .detail-value {
            font-size: 14px;
            color: #1F2937;
        }

        .assigned-members {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .member-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #F3F4F6;
            border-radius: 6px;
            font-size: 13px;
        }

        .member-badge.leader {
            background: #FEF3C7;
            color: #92400E;
            font-weight: 600;
        }

        /* Kanban Board Styles */
        .kanban-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            padding: 16px 0;
            min-height: 600px;
        }

        .kanban-column {
            background: #F9FAFB;
            border-radius: 12px;
            padding: 14px;
            min-height: 500px;
            display: flex;
            flex-direction: column;
        }

        .kanban-column.drag-over {
            background: #EEF2FF;
            border: 2px dashed #7B1D3A;
        }

        .kanban-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 2px solid #E5E7EB;
            flex-shrink: 0;
        }

        .kanban-header h4 {
            font-size: 14px;
            font-weight: 700;
            color: #1F2937;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .kanban-count {
            background: #7B1D3A;
            color: white;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 700;
        }

        .kanban-cards {
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex: 1;
            overflow-y: auto;
            min-height: 100px;
        }

        .kanban-card {
            background: white;
            border-radius: 8px;
            padding: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
            cursor: grab;
            transition: all 0.2s ease;
            border-left: 3px solid #7B1D3A;
            min-height: 140px;
            display: flex;
            flex-direction: column;
        }

        .kanban-card:active {
            cursor: grabbing;
        }

        .kanban-card.dragging {
            opacity: 0.5;
            transform: rotate(3deg);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .kanban-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .kanban-card.success {
            border-left-color: #10B981;
            background: linear-gradient(135deg, #ECFDF5 0%, #ffffff 100%);
        }

        .kanban-card.rejected {
            border-left-color: #EF4444;
            background: linear-gradient(135deg, #FEF2F2 0%, #ffffff 100%);
        }

        .kanban-card.reviewing {
            border-left-color: #3B82F6;
            background: linear-gradient(135deg, #EFF6FF 0%, #ffffff 100%);
        }

        .kanban-card.pending {
            border-left-color: #F59E0B;
            background: linear-gradient(135deg, #FFFBEB 0%, #ffffff 100%);
        }

        /* Responsive Kanban */
        @media (max-width: 1200px) {
            .kanban-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .kanban-container {
                grid-template-columns: 1fr;
            }
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
            gap: 6px;
        }

        .card-header h5 {
            font-size: 13px;
            font-weight: 700;
            color: #1F2937;
            margin: 0;
            flex: 1;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-description {
            font-size: 11px;
            color: #6B7280;
            margin-bottom: 8px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-meta {
            display: flex;
            gap: 10px;
            font-size: 10px;
            color: #6B7280;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }

        .card-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .card-progress {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .card-progress span {
            font-size: 12px;
            font-weight: 600;
            color: #7B1D3A;
            min-width: 35px;
        }

        .card-team {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #E5E7EB;
        }

        .team-avatars {
            display: flex;
            gap: 4px;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFBF00, #FFA500);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7B1D3A;
            font-weight: 700;
            font-size: 12px;
            border: 2px solid white;
        }

        .training-status,
        .design-links,
        .dev-status,
        .test-status {
            font-size: 11px;
            color: #10B981;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .design-links {
            color: #6366F1;
        }

        .dev-status {
            color: #F59E0B;
        }

        .test-status {
            color: #8B5CF6;
        }

        .launch-action {
            margin-top: 12px;
        }

        .btn-promote {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-promote:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .priority-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .priority-badge.priority-critical {
            background: #991B1B;
            color: white;
        }

        .priority-badge.priority-high {
            background: #DC2626;
            color: white;
        }

        .priority-badge.priority-medium {
            background: #F59E0B;
            color: white;
        }

        .priority-badge.priority-low {
            background: #228B22;
            color: white;
        }

        .btn-action {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: #3B82F6;
            color: white;
        }

        .btn-view:hover {
            background: #2563EB;
        }

        .btn-edit {
            background: #10B981;
            color: white;
        }

        .btn-edit:hover {
            background: #059669;
        }

        .btn-delete {
            background: #DC2626;
            color: white;
        }

        .btn-delete:hover {
            background: #B91C1C;
        }

        .btn-action:disabled,
        .btn-action.disabled {
            background: #E5E7EB !important;
            color: #9CA3AF !important;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .btn-action:disabled:hover,
        .btn-action.disabled:hover {
            background: #E5E7EB !important;
            transform: none;
        }

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        .top-header {
            background: white;
            padding: 12px 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .breadcrumb {
            color: #6B7280;
            font-size: 14px;
        }

        .breadcrumb span {
            color: #7B1D3A;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-wrapper {
            position: relative;
        }

        .notification-btn {
            position: relative;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #F3F4F6;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #E5E7EB;
        }

        .notification-btn:hover {
            background: #7B1D3A;
            color: white;
            border-color: #7B1D3A;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(123, 29, 58, 0.25);
        }

        .notification-btn i {
            font-size: 18px;
            color: #6B7280;
            transition: color 0.25s ease;
        }

        .notification-btn:hover i {
            color: white;
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            border-radius: 10px;
            border: 2px solid white;
            font-size: 11px;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        }

        .notification-badge.hidden {
            display: none;
        }

        .notification-dropdown {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            width: 380px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(0, 0, 0, 0.05);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .notification-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .notification-header {
            padding: 16px 20px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h4 {
            font-size: 16px;
            font-weight: 700;
            color: #1F2937;
            margin: 0;
        }

        .notification-mark-read {
            font-size: 12px;
            color: #7B1D3A;
            font-weight: 600;
            cursor: pointer;
            transition: color 0.2s;
        }

        .notification-mark-read:hover {
            color: #5a1428;
        }

        .notification-list {
            max-height: 360px;
            overflow-y: auto;
        }

        .notification-list::-webkit-scrollbar {
            width: 6px;
        }

        .notification-list::-webkit-scrollbar-thumb {
            background: #D1D5DB;
            border-radius: 3px;
        }

        .notification-item {
            padding: 14px 20px;
            display: flex;
            gap: 14px;
            cursor: pointer;
            transition: background 0.2s;
            border-bottom: 1px solid #F3F4F6;
        }

        .notification-item:hover {
            background: #F9FAFB;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-icon.booking {
            background: #DBEAFE;
            color: #2563EB;
        }

        .notification-icon.startup {
            background: #D1FAE5;
            color: #059669;
        }

        .notification-icon.issue {
            background: #FEE2E2;
            color: #DC2626;
        }

        .notification-icon.intern {
            background: #FEF3C7;
            color: #D97706;
        }

        .notification-icon.report {
            background: #E0E7FF;
            color: #4F46E5;
        }

        .notification-icon.payment {
            background: #FCE7F3;
            color: #DB2777;
        }

        .notification-icon.moa {
            background: #CCFBF1;
            color: #0D9488;
        }

        .notification-icon.document {
            background: #F3E8FF;
            color: #7C3AED;
        }

        .notification-icon.absent {
            background: #FEE2E2;
            color: #B91C1C;
        }

        .notification-icon.task {
            background: #D1FAE5;
            color: #047857;
        }

        .notification-icon.upload {
            background: #DBEAFE;
            color: #1D4ED8;
        }

        .notification-icon.tlactivity {
            background: #E0E7FF;
            color: #4338CA;
        }

        .notification-icon.progress {
            background: #FEF3C7;
            color: #B45309;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-size: 14px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notification-text {
            font-size: 13px;
            color: #6B7280;
            line-height: 1.4;
        }

        .notification-time {
            font-size: 11px;
            color: #9CA3AF;
            margin-top: 4px;
        }

        .notification-empty {
            padding: 40px 20px;
            text-align: center;
            color: #9CA3AF;
        }

        .notification-empty i {
            font-size: 40px;
            margin-bottom: 12px;
            color: #D1D5DB;
        }

        .notification-footer {
            padding: 12px 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
        }

        .notification-footer a {
            font-size: 13px;
            color: #7B1D3A;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .notification-footer a:hover {
            color: #5a1428;
        }

        .user-profile {
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
        }

        /* Admin Profile Dropdown Styles */
        .admin-profile-dropdown {
            position: relative;
            z-index: 1001;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
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

        .admin-profile-menu {
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

        .admin-profile-dropdown.active .admin-profile-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .admin-profile-menu-header {
            padding: 16px;
            border-bottom: 1px solid #E5E7EB;
            text-align: center;
        }

        .admin-profile-avatar {
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

        .admin-profile-avatar-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .admin-profile-name {
            font-weight: 600;
            color: #1F2937;
            font-size: 15px;
        }

        .admin-profile-email {
            font-size: 12px;
            color: #6B7280;
            margin-top: 2px;
        }

        .admin-profile-menu-items {
            padding: 8px;
        }

        .admin-profile-menu-item {
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

        .admin-profile-menu-item:hover {
            background: #F3F4F6;
            color: #7B1D3A;
        }

        .admin-profile-menu-item i {
            width: 18px;
            text-align: center;
            color: #6B7280;
        }

        .admin-profile-menu-item:hover i {
            color: #7B1D3A;
        }

        .admin-profile-dropdown.active .user-info i.fa-chevron-down {
            transform: rotate(180deg);
        }

        .user-info i.fa-chevron-down {
            transition: transform 0.3s;
        }

        .dashboard-content {
            padding: 24px;
            padding-left: 32px;
            padding-right: 32px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #7B1D3A;
            margin-bottom: 16px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 8px;
        }

        .stat-change {
            font-size: 12px;
            font-weight: 600;
        }

        .stat-change.positive {
            color: #10B981;
        }

        .stat-change.negative {
            color: #EF4444;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .chart-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .chart-title {
            font-size: 18px;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 20px;
        }

        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table-card > div:has(table),
        .table-card > table {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-card table {
            width: 100%;
            min-width: max-content;
        }

        /* Table Pagination Styles */
        .table-pagination button {
            transition: all 0.2s ease;
        }

        .table-pagination button:hover:not(:disabled) {
            background: #E5E7EB !important;
        }

        .table-pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed !important;
        }

        .table-header {
            padding: 20px 24px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 18px;
            font-weight: 700;
            color: #1F2937;
        }

        .view-all-btn {
            color: #7B1D3A;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .view-all-btn:hover {
            color: #FFBF00;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #F9FAFB;
            padding: 14px 24px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            color: #6B7280;
            text-transform: uppercase;
        }

        td {
            padding: 16px 24px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 14px;
            color: #1F2937;
        }

        .intern-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .intern-name {
            font-weight: 600;
        }

        .intern-school {
            font-size: 12px;
            color: #6B7280;
        }

        tr:hover {
            background: #F9FAFB;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #D1FAE5;
            color: #065F46;
        }

        .status-pending {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-completed {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .quick-actions {
            position: fixed;
            bottom: 32px;
            right: 32px;
        }

        .fab-button {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            color: #7B1D3A;
            border: none;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(255, 191, 0, 0.4);
            transition: all 0.3s ease;
        }

        .fab-button:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(255, 191, 0, 0.6);
        }

        /* Calendar Styles */
        .calendar-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .calendar-sidebar {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .mini-calendar-card,
        .event-filter-card,
        .upcoming-events-card {
            background: white;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .mini-calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .mini-calendar-title {
            font-size: 14px;
            font-weight: 700;
            color: #1F2937;
        }

        .cal-nav-btn {
            background: none;
            border: none;
            color: #6B7280;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .cal-nav-btn:hover {
            background: #F3F4F6;
            color: #1F2937;
        }

        .mini-calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            margin-bottom: 4px;
        }

        .day-label {
            text-align: center;
            font-size: 11px;
            font-weight: 600;
            color: #6B7280;
            padding: 4px 0;
        }

        .mini-calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }

        .mini-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            color: #1F2937;
        }

        .mini-day:hover {
            background: #F3F4F6;
        }

        .mini-day.other-month {
            color: #D1D5DB;
        }

        .mini-day.today {
            background: #7B1D3A;
            color: white;
            font-weight: 700;
        }

        .mini-day.has-events {
            position: relative;
        }

        .mini-day.has-events::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: #FFBF00;
            border-radius: 50%;
        }

        .filter-checkboxes {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13px;
            color: #374151;
        }

        .filter-checkbox-label input[type="checkbox"] {
            cursor: pointer;
        }

        .event-color-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        .upcoming-events-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .upcoming-event-item {
            display: flex;
            gap: 12px;
            padding: 12px;
            background: #F9FAFB;
            border-radius: 6px;
            border-left: 3px solid #3B82F6;
        }

        .event-time {
            font-size: 11px;
            font-weight: 600;
            color: #6B7280;
            white-space: nowrap;
        }

        .event-info {
            flex: 1;
        }

        .event-title {
            font-size: 13px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 4px;
        }

        .event-location {
            font-size: 11px;
            color: #6B7280;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .calendar-main {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #E5E7EB;
        }

        .full-calendar {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
        }

        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0;
            background: #F9FAFB;
            border-bottom: 1px solid #E5E7EB;
        }

        .weekday {
            background: #F9FAFB;
            padding: 12px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            color: #6B7280;
            border-right: 1px solid #E5E7EB;
            box-sizing: border-box;
        }

        .weekday:last-child {
            border-right: none;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0;
            background: white;
        }

        .calendar-day {
            background: white;
            min-height: 100px;
            max-height: 150px;
            padding: 8px;
            position: relative;
            cursor: pointer;
            transition: all 0.2s;
            overflow-y: auto;
            border-right: 1px solid #E5E7EB;
            border-bottom: 1px solid #E5E7EB;
            box-sizing: border-box;
        }

        .calendar-day:nth-child(7n) {
            border-right: none;
        }

        .calendar-day:hover {
            background: #F9FAFB;
        }

        .calendar-day.other-month {
            background: #FAFAFA;
        }

        .calendar-day.other-month .day-number {
            color: #D1D5DB;
        }

        .calendar-day.current-day {
            background: #FEF3C7;
        }

        .calendar-day.current-day .day-number {
            color: #7B1D3A;
            font-weight: 700;
        }

        .calendar-day.blocked-day {
            position: relative;
        }

        .day-number {
            font-size: 14px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 4px;
        }

        .event-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
            margin: 2px;
        }

        .calendar-event {
            background: #DBEAFE;
            border-left: 3px solid #3B82F6;
            border-radius: 4px;
            padding: 4px 6px;
            margin-bottom: 4px;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.2s;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .calendar-event:hover {
            transform: translateX(2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            white-space: normal;
            z-index: 10;
        }

        .calendar-event.meeting {
            background: #DBEAFE;
            border-left-color: #3B82F6;
        }

        .calendar-event.training {
            background: #FEF3C7;
            border-left-color: #F59E0B;
        }

        .calendar-event.deadline {
            background: #D1FAE5;
            border-left-color: #10B981;
        }

        .calendar-event.booking {
            background: #EDE9FE;
            border-left-color: #8B5CF6;
        }

        .calendar-event.payment {
            background: #FEE2E2;
            border-left-color: #EF4444;
        }

        .calendar-event .event-time {
            font-weight: 600;
            color: #374151;
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .calendar-event .event-name {
            color: #1F2937;
            font-weight: 500;
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .room-status {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .room-status.available {
            background: #D1FAE5;
            color: #059669;
        }

        .room-status.booked {
            background: #FEE2E2;
            color: #DC2626;
        }

        .badge-count {
            background: #DC2626;
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 12px;
            margin-left: 8px;
        }

        .booking-tab-content {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Clickable calendar day styles */
        .clickable-day {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .clickable-day:hover {
            background: #F3F4F6 !important;
            transform: scale(1.02);
        }

        .mini-day {
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .mini-day:hover:not(.other-month) {
            background: #7B1D3A20;
            transform: scale(1.1);
        }

        .mini-day.blocked {
            position: relative;
        }

        .blocked-day {
            position: relative;
        }

        .calendar-event.blocked {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 4px;
            margin-bottom: 4px;
        }

        .filter-tabs {
            display: flex;
            gap: 8px;
        }

        .filter-tab {
            padding: 8px 16px;
            border: none;
            background: white;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            color: #6B7280;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-tab:hover {
            background: #F3F4F6;
            color: #1F2937;
        }

        .filter-tab.active {
            background: linear-gradient(135deg, #7B1D3A, #A02347);
            color: white;
        }

        /* File Management Styles */
        .file-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
            padding: 8px;
        }

        .file-item {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .file-item:hover {
            border-color: #7B1D3A;
            box-shadow: 0 4px 12px rgba(123, 29, 58, 0.15);
            transform: translateY(-2px);
        }

        .file-item.folder-item:hover .folder-icon {
            color: #7B1D3A;
        }

        .file-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            border-radius: 8px;
            color: white;
        }

        .folder-icon {
            background: none !important;
            color: #FFBF00;
            font-size: 48px;
        }

        .file-name {
            font-size: 13px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 4px;
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .file-meta {
            font-size: 11px;
            color: #6B7280;
        }

        .file-type-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
        }

        .file-type-badge.pdf {
            background: #FEE2E2;
            color: #DC2626;
        }

        .file-type-badge.doc {
            background: #DBEAFE;
            color: #2563EB;
        }

        .file-type-badge.excel {
            background: #D1FAE5;
            color: #059669;
        }

        .btn-download {
            background: #10B981;
            color: white;
        }

        .btn-download:hover {
            background: #059669;
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
            background: linear-gradient(135deg, #5a1428 0%, #3d0e1c 100%);
            transform: scale(1.05);
        }

        .mobile-menu-btn.active {
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            color: #7B1D3A;
        }

        /* Mobile Overlay */
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

        /* Sidebar Close Button (mobile only) */
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

        @media (max-width: 968px) {
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
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }

            .calendar-layout {
                grid-template-columns: 1fr;
            }

            .calendar-sidebar {
                order: 2;
            }

            .calendar-main {
                order: 1;
            }

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                width: 100%;
                min-width: 100%;
                justify-content: space-between;
            }

            .filter-search {
                width: 100%;
                margin-left: 0;
                min-width: 100%;
                flex: none;
            }

            .filter-btn {
                width: 100%;
            }

            .dashboard-content {
                padding: 24px;
                padding-left: 24px;
                padding-right: 24px;
            }

            .page-content {
                padding: 24px;
            }

            .top-header {
                padding: 16px;
                padding-left: 72px;
            }

            .header-actions {
                gap: 12px;
            }

            .user-profile > div:last-child {
                display: none;
            }

            .user-info > div {
                display: none;
            }

            .user-info i.fa-chevron-down {
                display: none;
            }

            .admin-profile-menu {
                right: -8px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .stat-card {
                padding: 16px;
            }

            .stat-value {
                font-size: 24px;
            }

            .time-stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .notification-dropdown {
                width: calc(100vw - 32px);
                right: -60px;
            }

            .modal-content {
                width: 95%;
                margin: 16px;
                max-height: calc(100vh - 32px);
            }

            .intern-select-grid {
                grid-template-columns: 1fr;
            }

            .breadcrumb {
                font-size: 12px;
            }

            /* Table responsive */
            .table-card {
                border-radius: 8px;
            }

            .table-header {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
                padding: 16px;
            }

            .table-title {
                font-size: 16px;
            }

            th, td {
                padding: 12px 16px;
                font-size: 13px;
            }

            th {
                font-size: 11px;
            }

            /* Settings page grid */
            .settings-grid {
                grid-template-columns: 1fr !important;
            }

            /* Charts */
            .charts-grid {
                grid-template-columns: 1fr;
            }

            .chart-card {
                padding: 16px;
            }

            /* Quick actions grid */
            .quick-actions-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            /* Activity list */
            .activity-list {
                max-height: 400px;
            }
        }

        @media (max-width: 640px) {
            .mobile-menu-btn {
                top: 12px;
                left: 12px;
                width: 44px;
                height: 44px;
            }

            .top-header {
                padding: 12px;
                padding-left: 64px;
            }

            .dashboard-content {
                padding: 16px;
            }

            .page-content {
                padding: 16px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .time-stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 14px;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .stat-value {
                font-size: 20px;
            }

            .stat-label {
                font-size: 12px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 6px;
            }

            .table-wrapper {
                overflow-x: auto;
            }

            .table-wrapper table {
                min-width: 600px;
            }

            .header-actions {
                gap: 8px;
            }

            .notification-btn {
                width: 38px;
                height: 38px;
            }

            .user-avatar {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }

            .filter-tabs {
                gap: 8px;
            }

            .filter-tab {
                padding: 8px 14px;
                font-size: 12px;
            }

            .quick-action-card {
                padding: 16px;
            }

            .activity-card {
                padding: 16px;
            }

            /* Quick actions single column on mobile */
            .quick-actions-grid {
                grid-template-columns: 1fr;
            }

            /* Table header on mobile */
            .table-header {
                padding: 12px;
            }

            .table-title {
                font-size: 14px;
            }

            th, td {
                padding: 10px 12px;
                font-size: 12px;
            }

            th {
                font-size: 10px;
            }

            /* School header */
            .school-header {
                padding: 12px 16px;
                flex-direction: column;
                gap: 8px;
                align-items: flex-start;
            }

            .school-header h4 {
                font-size: 14px;
            }

            /* Buttons on mobile */
            .btn-action {
                padding: 6px 10px;
                font-size: 11px;
            }

            .btn-view, .btn-edit, .btn-approve, .btn-reject {
                padding: 6px 10px;
                font-size: 11px;
            }

            /* Overtime cards */
            .overtime-request-card {
                padding: 14px;
            }

            .overtime-request-header {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .overtime-actions {
                width: 100%;
            }

            .overtime-actions button {
                flex: 1;
            }
        }

        /* Responsive for Small Laptops (1024px - 1366px) */
        @media (max-width: 1366px) {
            .table-card {
                overflow: visible;
            }

            .table-card > div,
            .table-card > div:has(table) {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table-card table {
                min-width: 900px;
            }
        }

        /* Responsive Grid Classes for Inline Styles */
        @media (max-width: 968px) {
            /* 4-column grids become 2-column */
            [style*="grid-template-columns: repeat(4, 1fr)"],
            [style*="grid-template-columns:repeat(4, 1fr)"] {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            /* Page header flex layouts */
            [style*="display: flex"][style*="justify-content: space-between"][style*="align-items: start"],
            [style*="display: flex"][style*="justify-content: space-between"][style*="align-items: center"] {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 16px !important;
            }

            /* Table cards */
            .table-card {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table-card table {
                min-width: 800px;
            }

            /* Time tabs */
            .time-tabs {
                flex-wrap: wrap;
                gap: 8px;
            }

            .time-tab {
                flex: 1 1 auto;
                min-width: calc(50% - 4px);
                justify-content: center;
                text-align: center;
                padding: 10px 12px;
                font-size: 12px;
            }

            .time-tab i {
                display: none;
            }

            /* Filter tabs */
            .filter-tabs {
                flex-wrap: wrap !important;
                gap: 8px !important;
            }

            .filter-tab {
                flex: 1 1 auto;
                min-width: calc(50% - 4px);
                text-align: center;
                padding: 10px 12px !important;
                font-size: 12px !important;
            }

            /* Calendar Layout */
            .calendar-layout {
                grid-template-columns: 1fr !important;
            }

            .calendar-header {
                flex-direction: column;
                gap: 12px;
                align-items: stretch !important;
            }

            .calendar-nav {
                justify-content: center;
            }

            /* Settings page layout - 2 columns to 1 */
            [style*="grid-template-columns: 280px 1fr"] {
                grid-template-columns: 1fr !important;
            }

            /* Settings navigation becomes horizontal scrollable */
            [style*="position: sticky"][style*="top: 20px"] {
                position: static !important;
                display: flex;
                overflow-x: auto;
                gap: 8px;
                padding: 12px !important;
                -webkit-overflow-scrolling: touch;
            }

            .settings-nav-item {
                min-width: max-content;
                white-space: nowrap;
            }

            /* Calendar weekdays */
            .calendar-weekdays {
                font-size: 11px;
            }

            .calendar-weekdays .weekday {
                padding: 8px 4px;
            }

            .calendar-day {
                min-height: 60px;
                padding: 4px;
            }

            .calendar-day-number {
                font-size: 12px;
            }

            /* Booking & Event cards */
            .booking-card,
            .event-card {
                flex-direction: column !important;
            }

            .booking-card > div:first-child,
            .event-card > div:first-child {
                width: 100% !important;
            }

            /* Action button groups */
            [style*="display: flex"][style*="gap: 8px"]:has(button) {
                flex-wrap: wrap;
            }

            /* Pagination */
            [style*="display: flex"][style*="justify-content: center"][style*="gap: 8px"] {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 640px) {
            /* 4-column and 2-column grids become single column */
            [style*="grid-template-columns: repeat(4, 1fr)"],
            [style*="grid-template-columns:repeat(4, 1fr)"],
            [style*="grid-template-columns: repeat(2, 1fr)"],
            [style*="grid-template-columns:repeat(2, 1fr)"] {
                grid-template-columns: 1fr !important;
            }

            /* Page titles */
            [style*="font-size: 28px"] {
                font-size: 22px !important;
            }

            [style*="font-size: 24px"] {
                font-size: 20px !important;
            }

            /* Time tabs single column */
            .time-tab {
                min-width: 100%;
            }

            /* Filter tabs single column */
            .filter-tab {
                min-width: 100% !important;
            }

            /* Table minimum width reduced */
            .table-card table {
                min-width: 600px;
            }

            /* Modal adjustments */
            .modal-content {
                margin: 8px;
                max-height: calc(100vh - 16px);
            }

            .modal-header {
                padding: 16px;
            }

            .modal-body {
                padding: 16px;
            }

            .modal-footer {
                padding: 16px;
                flex-direction: column;
            }

            .modal-footer button {
                width: 100%;
            }

            /* Form groups in modals */
            .form-radio-group {
                flex-direction: column;
                gap: 12px;
            }

            /* Stat cards inside inline styles */
            [style*="padding: 20px"][style*="border-radius: 12px"][style*="box-shadow"] {
                padding: 14px !important;
            }

            [style*="width: 48px"][style*="height: 48px"] {
                width: 40px !important;
                height: 40px !important;
            }

            [style*="font-size: 28px"][style*="font-weight: 700"] {
                font-size: 22px !important;
            }

            /* Alerts and notifications */
            [style*="padding: 16px 20px"][style*="border-radius: 12px"][style*="display: flex"][style*="align-items: center"] {
                flex-direction: column !important;
                text-align: center;
                gap: 12px !important;
            }

            [style*="padding: 16px 20px"][style*="border-radius: 12px"] button {
                margin-left: 0 !important;
                width: 100%;
            }

            /* Calendar on mobile */
            .calendar-weekdays {
                font-size: 10px;
            }

            .calendar-weekdays .weekday {
                padding: 6px 2px;
            }

            .calendar-day {
                min-height: 50px;
                padding: 2px;
            }

            .calendar-day-number {
                font-size: 11px;
            }

            .calendar-event {
                font-size: 9px;
                padding: 2px 4px;
            }

            /* Settings on mobile */
            .settings-nav-item {
                padding: 10px 14px !important;
                font-size: 12px;
            }

            .settings-nav-item i {
                font-size: 14px;
            }

            .settings-group {
                margin-bottom: 16px;
            }

            .settings-label {
                font-size: 13px;
            }

            .settings-input, .settings-select {
                padding: 10px 12px;
                font-size: 14px;
            }

            /* Card headers on mobile */
            [style*="padding: 20px 24px"][style*="border-bottom"][style*="display: flex"] {
                flex-direction: column !important;
                gap: 12px !important;
                padding: 14px 16px !important;
            }

            /* Search boxes */
            .search-box {
                max-width: 100% !important;
                width: 100%;
            }
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column-reverse;
            gap: 12px;
            max-width: 400px;
        }

        @media (max-width: 640px) {
            .toast-container {
                left: 16px;
                right: 16px;
                bottom: 16px;
                max-width: none;
            }

            .toast {
                min-width: auto;
                width: 100%;
            }
        }

        .toast {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: toastSlideIn 0.3s ease, toastFadeOut 0.3s ease 4.7s forwards;
            min-width: 320px;
        }

        .toast.toast-success {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
        }

        .toast.toast-error {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: white;
        }

        .toast.toast-warning {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: white;
        }

        .toast.toast-info {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            color: white;
        }

        .toast-icon {
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .toast-message {
            font-size: 13px;
            opacity: 0.9;
            line-height: 1.4;
        }

        .toast-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background 0.2s;
        }

        .toast-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 0 0 12px 12px;
            animation: toastProgress 5s linear forwards;
        }

        @keyframes toastSlideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes toastFadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }

        @keyframes toastProgress {
            from { width: 100%; }
            to { width: 0%; }
        }

        /* Confirmation Modal */
        .confirm-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            animation: fadeIn 0.2s ease;
        }

        .confirm-modal-overlay.active {
            display: flex;
        }

        .confirm-modal {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalPopIn 0.3s ease;
            overflow: hidden;
        }

        @keyframes modalPopIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .confirm-modal-header {
            padding: 24px 24px 0;
            text-align: center;
        }

        .confirm-modal-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 28px;
        }

        .confirm-modal-icon.warning {
            background: #FEF3C7;
            color: #D97706;
        }

        .confirm-modal-icon.danger {
            background: #FEE2E2;
            color: #DC2626;
        }

        .confirm-modal-icon.info {
            background: #DBEAFE;
            color: #2563EB;
        }

        .confirm-modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 8px;
        }

        .confirm-modal-body {
            padding: 16px 24px 24px;
            text-align: center;
        }

        .confirm-modal-message {
            color: #6B7280;
            font-size: 14px;
            line-height: 1.6;
        }

        .confirm-modal-footer {
            padding: 0 24px 24px;
            display: flex;
            gap: 12px;
        }

        .confirm-modal-btn {
            flex: 1;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .confirm-modal-btn.cancel {
            background: #F3F4F6;
            color: #4B5563;
        }

        .confirm-modal-btn.cancel:hover {
            background: #E5E7EB;
        }

        .confirm-modal-btn.confirm {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: white;
        }

        .confirm-modal-btn.confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .confirm-modal-btn.confirm.success {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }

        .confirm-modal-btn.confirm.success:hover {
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        /* ========== Settings Page Styles ========== */
        .settings-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 14px 16px;
            background: transparent;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #4B5563;
            text-align: left;
            transition: all 0.2s;
        }

        .settings-nav-item:hover {
            background: #F3F4F6;
            color: #1F2937;
        }

        .settings-nav-item.active {
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
            color: white;
        }

        .settings-nav-item i {
            width: 20px;
            font-size: 15px;
        }

        .settings-tab {
            display: none;
        }

        .settings-tab.active {
            display: block;
        }

        .settings-group {
            margin-bottom: 24px;
        }

        .settings-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .settings-input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
            background: #FAFAFA;
        }

        .settings-input:focus {
            outline: none;
            border-color: #7B1D3A;
            box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1);
            background: white;
        }

        .settings-hint {
            font-size: 12px;
            color: #9CA3AF;
            margin-top: 6px;
        }

        .settings-toggle-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 26px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #D1D5DB;
            transition: 0.3s;
            border-radius: 26px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .toggle-switch input:checked + .toggle-slider {
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
        }

        .toggle-switch input:checked + .toggle-slider:before {
            transform: translateX(22px);
        }

        .settings-btn {
            padding: 10px 16px;
            background: #7B1D3A;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 13px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .settings-btn:hover {
            background: #5a1428;
            transform: translateY(-1px);
        }

        .settings-btn.warning {
            background: #F59E0B;
        }

        .settings-btn.warning:hover {
            background: #D97706;
        }

        .settings-btn.danger {
            background: #EF4444;
        }

        .settings-btn.danger:hover {
            background: #DC2626;
        }

        /* Compact mode */
        body.compact-mode .page-content {
            padding: 16px;
        }

        body.compact-mode .stats-grid {
            gap: 12px;
        }

        body.compact-mode .stat-card {
            padding: 16px;
        }

        /* No animations mode */
        body.no-animations * {
            animation: none !important;
            transition: none !important;
        }

        /* Pulse animation for email pending badge */
        @keyframes pulse-amber {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4);
            }
            50% {
                box-shadow: 0 0 0 6px rgba(245, 158, 11, 0);
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn" onclick="toggleMobileSidebar()">
        <i class="fas fa-bars" id="mobileMenuIcon"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <!-- Mobile Close Button -->
        <button class="sidebar-close-btn" onclick="closeMobileSidebar()">
            <i class="fas fa-times"></i>
        </button>

        <div class="sidebar-logo">
            <img src="images/UP logo.png" alt="UP Logo">
            <h3>University of the Philippines Cebu</h3>
            <p>InIT Admin Panel</p>
        </div>

        <nav class="sidebar-menu">
            <a href="#" class="menu-item active" onclick="loadPage(event, 'dashboard-overview')">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <div class="menu-item-parent">
                <a href="#" class="menu-item" onclick="toggleSubmenu(event, 'internSubmenu')">
                    <i class="fas fa-briefcase"></i>
                    <span>Intern Management</span>
                    <i class="fas fa-chevron-down dropdown-icon" id="internSubmenuIcon"></i>
                </a>
                <div class="submenu" id="internSubmenu">
                    <a href="#" class="submenu-item" onclick="loadPage(event, 'intern-list')">
                        <i class="fas fa-list"></i>
                        <span>Intern List</span>
                    </a>

                    <a href="#" class="submenu-item" onclick="loadPage(event, 'time-attendance')">
                        <i class="fas fa-clock"></i>
                        <span>Time & Attendance</span>
                    </a>

                    <a href="#" class="submenu-item" onclick="loadPage(event, 'task-assignment')">
                        <i class="fas fa-tasks"></i>
                        <span>Task Assignment</span>
                    </a>

                    <a href="#" class="submenu-item" onclick="loadPage(event, 'team-leaders')">
                        <i class="fas fa-user-tie"></i>
                        <span>Team Leaders</span>
                    </a>

                    <a href="#" class="submenu-item" onclick="loadPage(event, 'team-reports')">
                        <i class="fas fa-file-signature"></i>
                        <span>Team Reports</span>
                    </a>

                </div>
            </div>
            <div class="menu-item-parent">
                <a href="#" class="menu-item" onclick="toggleSubmenu(event, 'startupSubmenu')">
                    <i class="fas fa-rocket"></i>
                    <span>Startup Management</span>
                    <i class="fas fa-chevron-down dropdown-icon" id="startupSubmenuIcon"></i>
                </a>
                <div class="submenu" id="startupSubmenu">
                    <a href="#" class="submenu-item" onclick="loadPage(event, 'manage-startups')">
                        <i class="fas fa-building"></i>
                        <span>Manage Startups</span>
                    </a>
                    <a href="#" class="submenu-item" onclick="loadPage(event, 'project-progress')">
                        <i class="fas fa-tasks"></i>
                        <span>Project Progress</span>
                    </a>
                    <a href="#" class="submenu-item" onclick="loadPage(event, 'research-tracking')">
                        <i class="fas fa-flask"></i>
                        <span>Research Tracking</span>
                    </a>
                    <a href="#" class="submenu-item" onclick="loadPage(event, 'incubatee-tracker')">
                        <i class="fas fa-chart-line"></i>
                        <span>Incubatee Tracker</span>
                    </a>
                    <a href="#" class="submenu-item" onclick="loadPage(event, 'issues-management')">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Issues & Complaints</span>
                    </a>
                </div>
            </div>
            <a href="#" class="menu-item" onclick="loadPage(event, 'digital-records')">
                <i class="fas fa-file-alt"></i>
                <span>Digital Records</span>
            </a>

            <a href="#" class="menu-item" onclick="loadPage(event, 'scheduler')">
                <i class="fas fa-calendar-alt"></i>
                <span>Scheduler</span>
            </a>
            <a href="#" class="menu-item" onclick="loadPage(event, 'admin-settings')">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm" style="margin: 0;">
                @csrf
                <a href="#" class="menu-item" style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="breadcrumb">
                Dashboard > <span>Overview</span>
            </div>
            <div class="header-actions">
                <div class="notification-wrapper">
                    <div class="notification-btn" onclick="toggleNotificationDropdown()">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge" id="notificationBadge">0</span>
                    </div>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h4>Notifications</h4>
                            <span class="notification-mark-read" onclick="markAllAsRead()">Mark all as read</span>
                        </div>
                        <div class="notification-list" id="notificationList">
                            <div class="notification-empty">
                                <i class="fas fa-bell-slash"></i>
                                <p>No new notifications</p>
                            </div>
                        </div>
                        <div class="notification-footer">
                            <a href="#" onclick="loadPage(event, 'activity-log')">View All Activity</a>
                        </div>
                    </div>
                </div>
                <div class="admin-profile-dropdown" id="adminProfileDropdown">
                    <button type="button" class="user-info" id="adminProfileBtn" style="cursor: pointer; border: none; background: transparent; padding: 0;">
                        @if(Auth::user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile" class="user-avatar-img" id="userAvatar">
                        @else
                            <div class="user-avatar" id="userAvatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        @endif
                        <div>
                            <div class="user-name" id="userName">{{ Auth::user()->name }}</div>
                            <div class="user-role" id="userRole">Administrator</div>
                        </div>
                        <i class="fas fa-chevron-down" style="color: #9CA3AF; font-size: 12px; margin-left: 8px;"></i>
                    </button>
                    <div class="admin-profile-menu">
                        <div class="admin-profile-menu-header">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile" class="admin-profile-avatar-img">
                            @else
                                <div class="admin-profile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            @endif
                            <div class="admin-profile-name">{{ Auth::user()->name }}</div>
                            <div class="admin-profile-email">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="admin-profile-menu-items">
                            <a href="#" class="admin-profile-menu-item" onclick="navigateToAdminProfile(event)">
                                <i class="fas fa-user-circle"></i>
                                My Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Dashboard Overview Page -->
            <div id="dashboard-overview" class="page-content active">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-value">{{ $activeInterns ?? 0 }}</div>
                    <div class="stat-label">Active Interns</div>
                    <div class="stat-change positive">
                        <i class="fas fa-check-circle"></i> Currently Active
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-value">{{ $startupDocuments->count() ?? 0 }}</div>
                    <div class="stat-label">Research Projects</div>
                    <div class="stat-change positive">
                        <i class="fas fa-flask"></i> Document Submissions
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div class="stat-value">{{ $totalDocuments ?? 0 }}</div>
                    <div class="stat-label">Digital Records</div>
                    <div class="stat-change positive">
                        <i class="fas fa-file-alt"></i> Total Documents
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div class="stat-value">{{ $activeIncubatees ?? 0 }}</div>
                    <div class="stat-label">Incubatees</div>
                    <div class="stat-change positive">
                        <i class="fas fa-briefcase"></i> Active Companies
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="charts-grid">
                <div class="chart-card">
                    <h3 class="chart-title">Intern Progress Overview (Monthly)</h3>
                    <canvas id="barChart"></canvas>
                </div>

                <div class="chart-card">
                    <h3 class="chart-title">System Usage Distribution</h3>
                    <canvas id="pieChart"></canvas>
                </div>
            </div>

            <!-- Recent Activity Table -->
            <div class="table-card">
                <div class="table-header">
                    <h3 class="table-title">Recent Activity</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>System</th>
                            <th>Action</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="recentActivityBody">
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                                No recent activity
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
            </div>

            <!-- Intern List Page -->
            <div id="intern-list" class="page-content">
                <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Intern List Management</h2>
                        <p style="color: #6B7280; font-size: 14px;">View all registered interns with their progress and hours</p>
                    </div>
                    <button onclick="openSchoolManagementModal()" style="background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; border: none; padding: 12px 20px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(123, 29, 58, 0.3); transition: all 0.3s ease;">
                        <i class="fas fa-university"></i>
                        Manage Schools
                    </button>
                </div>

                <!-- Stats Cards -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
                    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div style="width: 50px; height: 50px; background: #DBEAFE; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-users" style="color: #2563EB; font-size: 22px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #1F2937;">{{ $totalInterns ?? 0 }}</div>
                                <div style="color: #6B7280; font-size: 14px;">Total Interns</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div style="width: 50px; height: 50px; background: #D1FAE5; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-check" style="color: #059669; font-size: 22px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #1F2937;">{{ $activeInterns ?? 0 }}</div>
                                <div style="color: #6B7280; font-size: 14px;">Active Interns</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div style="width: 50px; height: 50px; background: #FEF3C7; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock" style="color: #D97706; font-size: 22px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #1F2937;">{{ $todayAttendances->count() ?? 0 }}</div>
                                <div style="color: #6B7280; font-size: 14px;">Present Today</div>
                            </div>
                        </div>
                    </div>
                    <div onclick="document.getElementById('pendingInternsSection').scrollIntoView({behavior: 'smooth'})" style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div style="width: 50px; height: 50px; background: #FEE2E2; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-clock" style="color: #DC2626; font-size: 22px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #1F2937;">{{ $pendingInternApprovals ?? 0 }}</div>
                                <div style="color: #6B7280; font-size: 14px;">Pending Approval</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Interns Section - Grouped by School -->
                @php
                    $pendingBySchool = ($pendingInterns ?? collect())->groupBy(function($intern) {
                        return $intern->schoolRelation->name ?? $intern->school ?? 'Unknown School';
                    });
                @endphp

                @if(($pendingInterns ?? collect())->count() > 0)
                <div id="pendingInternsSection" style="margin-bottom: 24px;">
                    <div style="background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%); color: white; padding: 16px 20px; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0; font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-user-clock"></i>
                            Pending Intern Approvals
                        </h4>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;">{{ ($pendingInterns ?? collect())->count() }} Pending from {{ $pendingBySchool->count() }} {{ $pendingBySchool->count() == 1 ? 'School' : 'Schools' }}</span>
                        </div>
                    </div>
                    <!-- Bulk Actions Bar -->
                    <div id="bulkActionsBar" style="display: none; background: linear-gradient(135deg, #1E40AF 0%, #1E3A8A 100%); color: white; padding: 12px 20px; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="checkbox" id="selectAllPending" onchange="toggleSelectAllPending()" style="width: 18px; height: 18px; cursor: pointer; accent-color: #FFBF00;">
                            <span id="selectedCountText" style="font-size: 14px; font-weight: 600;">0 selected</span>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button onclick="bulkApproveSelected()" style="background: linear-gradient(135deg, #10B981, #059669); color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                                <i class="fas fa-check-double"></i> Approve Selected
                            </button>
                            <button onclick="bulkRejectSelected()" style="background: linear-gradient(135deg, #EF4444, #DC2626); color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                                <i class="fas fa-times"></i> Reject Selected
                            </button>
                            <button onclick="clearBulkSelection()" style="background: rgba(255,255,255,0.2); color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-times"></i> Clear
                            </button>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 0 0 12px 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        @foreach($pendingBySchool as $schoolName => $schoolPending)
                        @php
                            $schoolId = $schoolPending->first()->school_id ?? 0;
                        @endphp
                        <div class="pending-school-group" id="pending-school-{{ $schoolId }}" style="border-bottom: 1px solid #E5E7EB;">
                            <div onclick="togglePendingSchoolGroup('pending-school-content-{{ $schoolId }}')" style="padding: 16px 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #F9FAFB; transition: all 0.3s ease;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #FEF3C7, #FDE68A); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-university" style="color: #92400E; font-size: 16px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #1F2937;">{{ $schoolName }}</div>
                                        <div style="font-size: 12px; color: #6B7280;">{{ $schoolPending->count() }} pending {{ $schoolPending->count() == 1 ? 'intern' : 'interns' }}</div>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <button onclick="event.stopPropagation(); approveAllBySchool({{ $schoolId }}, '{{ addslashes($schoolName) }}')" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-check-double"></i> Approve All ({{ $schoolPending->count() }})
                                    </button>
                                    <i class="fas fa-chevron-down pending-toggle-icon" id="icon-pending-{{ $schoolId }}" style="transition: transform 0.3s ease; color: #6B7280;"></i>
                                </div>
                            </div>
                            <div id="pending-school-content-{{ $schoolId }}" style="display: block; padding: 0 20px 16px;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background: #F3F4F6;">
                                            <th style="padding: 10px 12px; text-align: center; width: 40px;"><input type="checkbox" class="school-select-all" data-school="{{ $schoolId }}" onchange="toggleSchoolSelection({{ $schoolId }})" style="width: 16px; height: 16px; cursor: pointer; accent-color: #7B1D3A;"></th>
                                            <th style="padding: 10px 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6B7280;">Name</th>
                                            <th style="padding: 10px 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6B7280;">Email</th>
                                            <th style="padding: 10px 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6B7280;">Course</th>
                                            <th style="padding: 10px 12px; text-align: left; font-size: 12px; font-weight: 600; color: #6B7280;">Applied On</th>
                                            <th style="padding: 10px 12px; text-align: center; font-size: 12px; font-weight: 600; color: #6B7280;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($schoolPending as $pending)
                                        <tr id="pending-intern-{{ $pending->id }}" style="border-bottom: 1px solid #E5E7EB;" data-school-id="{{ $schoolId }}">
                                            <td style="padding: 12px; text-align: center;">
                                                <input type="checkbox" class="pending-intern-checkbox" data-intern-id="{{ $pending->id }}" data-intern-name="{{ $pending->name }}" data-school-id="{{ $schoolId }}" onchange="updateBulkSelection()" style="width: 16px; height: 16px; cursor: pointer; accent-color: #7B1D3A;">
                                            </td>
                                            <td style="padding: 12px;">
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #FEF3C7, #FDE68A); display: flex; align-items: center; justify-content: center; color: #92400E; font-weight: 700; font-size: 13px; overflow: hidden;">
                                                        @if($pending->profile_picture)
                                                            <img src="{{ asset('storage/' . $pending->profile_picture) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            {{ strtoupper(substr($pending->name, 0, 1)) }}
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div style="font-weight: 600; color: #1F2937; font-size: 13px;">{{ $pending->name }}</div>
                                                        <div style="font-size: 11px; color: #6B7280;">{{ $pending->phone }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="padding: 12px; font-size: 13px; color: #374151;">{{ $pending->email }}</td>
                                            <td style="padding: 12px; font-size: 13px; color: #374151;">{{ $pending->course }}</td>
                                            <td style="padding: 12px; font-size: 13px; color: #6B7280;">{{ $pending->created_at->format('M d, Y') }}</td>
                                            <td style="padding: 12px; text-align: center;">
                                                <div style="display: flex; justify-content: center; gap: 6px;">
                                                    <button onclick="approveIntern({{ $pending->id }})" style="background: #D1FAE5; color: #065F46; border: none; width: 28px; height: 28px; border-radius: 6px; cursor: pointer;" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button onclick="openRejectInternModal({{ $pending->id }}, '{{ addslashes($pending->name) }}')" style="background: #FEE2E2; color: #991B1B; border: none; width: 28px; height: 28px; border-radius: 6px; cursor: pointer;" title="Reject">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- All Schools List (Show all schools even without interns) -->
                @if(($schools ?? collect())->count() > 0)
                    @foreach($schools as $school)
                    @php
                        $schoolInterns = ($interns ?? collect())->where('school_id', $school->id);
                        $teamLeader = \App\Models\User::where('role', \App\Models\User::ROLE_TEAM_LEADER)
                            ->where('school_id', $school->id)
                            ->first();
                    @endphp
                    <div class="school-group" style="margin-bottom: 16px;">
                        <div class="school-header" style="background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; padding: 16px 20px; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease;">
                            <div onclick="toggleSchoolGroup('school-{{ $school->id }}')" style="cursor: pointer; flex: 1; display: flex; align-items: center; gap: 12px;">
                                <h4 style="margin: 0; font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 12px;">
                                    <i class="fas fa-university"></i>
                                    {{ $school->name }}
                                    @if($school->status !== 'Active')
                                    <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px; font-size: 10px;">Inactive</span>
                                    @endif
                                </h4>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <!-- Team Leader Status -->
                                @if($teamLeader)
                                    <div style="display: flex; align-items: center; gap: 8px; background: rgba(16, 185, 129, 0.2); padding: 6px 12px; border-radius: 8px;">
                                        <i class="fas fa-user-tie" style="color: #10B981;"></i>
                                        <div style="display: flex; flex-direction: column;">
                                            <span style="font-size: 11px; color: rgba(255,255,255,0.8);">Team Leader</span>
                                            <span style="font-size: 12px; font-weight: 600;">{{ $teamLeader->name }}</span>
                                        </div>
                                        @if($teamLeader->is_active)
                                            <span style="width: 8px; height: 8px; background: #10B981; border-radius: 50%; margin-left: 4px;" title="Active"></span>
                                        @else
                                            <span style="width: 8px; height: 8px; background: #EF4444; border-radius: 50%; margin-left: 4px;" title="Inactive"></span>
                                        @endif
                                        <button onclick="event.stopPropagation(); editTeamLeaderFromSchool({{ $teamLeader->id }})" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 24px; height: 24px; border-radius: 4px; cursor: pointer; margin-left: 4px;" title="Edit Team Leader">
                                            <i class="fas fa-edit" style="font-size: 11px;"></i>
                                        </button>
                                    </div>
                                @else
                                    <button onclick="event.stopPropagation(); openTeamLeaderModalForSchool({{ $school->id }}, '{{ addslashes($school->name) }}')" style="display: flex; align-items: center; gap: 6px; background: rgba(251, 191, 36, 0.9); color: #7B1D3A; padding: 6px 12px; border-radius: 8px; border: none; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='rgba(251, 191, 36, 1)'" onmouseout="this.style.background='rgba(251, 191, 36, 0.9)'">
                                        <i class="fas fa-user-plus"></i>
                                        Assign Team Leader
                                    </button>
                                @endif
                                <span style="background: rgba(255,255,255,0.2); color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">{{ $school->required_hours }} hrs required</span>
                                <span onclick="event.stopPropagation(); toggleSchoolGroup('school-{{ $school->id }}')" style="background: rgba(255,191,0,0.9); color: #7B1D3A; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; cursor: pointer;">{{ $schoolInterns->count() }} {{ $schoolInterns->count() == 1 ? 'Intern' : 'Interns' }}</span>
                                @if(($school->pending_interns ?? 0) > 0)
                                <span style="background: #FEE2E2; color: #991B1B; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">+{{ $school->pending_interns }} pending</span>
                                @endif
                                <i onclick="event.stopPropagation(); toggleSchoolGroup('school-{{ $school->id }}')" class="fas fa-chevron-down school-toggle-icon" id="icon-school-{{ $school->id }}" style="transition: transform 0.3s ease; cursor: pointer;"></i>
                            </div>
                        </div>
                        <div class="table-card" id="school-{{ $school->id }}" style="border-radius: 0 0 12px 12px; display: block; margin-top: 0;">
                            @if($schoolInterns->count() > 0)
                            <table>
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Progress</th>
                                        <th>Hours</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schoolInterns as $intern)
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700; overflow: hidden;">
                                                    @if($intern->profile_picture)
                                                        <img src="{{ asset('storage/' . $intern->profile_picture) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        {{ strtoupper(substr($intern->name, 0, 1)) }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <span style="font-weight: 600;">{{ $intern->name }}</span>
                                                    <div style="font-size: 12px; color: #6B7280;">{{ $intern->reference_code }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $intern->course }}</td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <div class="progress-bar-container" style="flex: 1; height: 8px;">
                                                    <div class="progress-bar" style="width: {{ $intern->progress_percentage }}%;"></div>
                                                </div>
                                                <span style="font-weight: 600; color: #7B1D3A;">{{ $intern->progress_percentage }}%</span>
                                            </div>
                                        </td>
                                        <td><strong>{{ $intern->completed_hours }}/{{ $intern->required_hours }}</strong></td>
                                        <td>
                                            <span class="status-badge" style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
                                                @if($intern->status === 'Active') background: #D1FAE5; color: #065F46;
                                                @elseif($intern->status === 'Completed') background: #DBEAFE; color: #1E40AF;
                                                @else background: #FEE2E2; color: #991B1B; @endif">
                                                {{ $intern->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-action btn-view" onclick="viewInternDetails({{ $intern->id }})" title="View"><i class="fas fa-eye"></i></button>
                                                <button class="btn-action btn-edit" onclick="editIntern({{ $intern->id }})" title="Edit"><i class="fas fa-edit"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div style="text-align: center; padding: 40px; color: #9CA3AF;">
                                <i class="fas fa-user-graduate" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                                <p style="margin: 0; color: #6B7280;">No approved interns from this school yet.</p>
                                @if(($school->pending_interns ?? 0) > 0)
                                <p style="margin: 8px 0 0; font-size: 13px; color: #D97706;">
                                    <i class="fas fa-clock"></i> {{ $school->pending_interns }} intern(s) pending approval
                                </p>
                                @endif
                                @if($school->contact_person)
                                <p style="margin: 12px 0 0; font-size: 12px; color: #6B7280;">
                                    <i class="fas fa-user"></i> Contact: {{ $school->contact_person }}
                                    @if($school->contact_phone) | {{ $school->contact_phone }} @endif
                                </p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="table-card" style="text-align: center; padding: 60px 40px; color: #9CA3AF;">
                        <i class="fas fa-university" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
                        <h3 style="color: #6B7280; margin-bottom: 8px;">No Schools Registered Yet</h3>
                        <p>Click "Manage Schools" to add schools first, then interns can register.</p>
                    </div>
                @endif
            </div>

            <!-- Time & Attendance Page -->
            <div id="time-attendance" class="page-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Time & Attendance Management</h2>
                    <p style="color: #6B7280; font-size: 14px;">Track daily rendered hours, monitor overtime, approve requests, and manage intern attendance records</p>
                </div>

                <!-- Time Tabs -->
                <div class="time-tabs">
                    <button class="time-tab active" onclick="switchTimeTab(event, 'daily-hours')">
                        <i class="fas fa-calendar-day"></i> Today's Attendance
                    </button>
                    <button class="time-tab" onclick="switchTimeTab(event, 'attendance-history')">
                        <i class="fas fa-history"></i> Attendance History
                    </button>
                    <button class="time-tab" onclick="switchTimeTab(event, 'hours-summary')">
                        <i class="fas fa-file-invoice"></i> Hours Summary
                    </button>
                </div>

                <!-- Daily Hours Table -->
                <div id="daily-hours" class="time-tab-content">
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <div class="filter-group">
                            <span class="filter-label">Status:</span>
                            <select class="filter-select" id="attendanceStatusFilter" onchange="filterDailyAttendance()">
                                <option value="all">All Status</option>
                                <option value="Present">Present</option>
                                <option value="Late">Late</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <span class="filter-label">School:</span>
                            <select class="filter-select" id="schoolFilter" onchange="filterDailyAttendance()">
                                <option value="all">All Schools</option>
                                @foreach(($interns ?? collect())->pluck('school')->unique() as $school)
                                <option value="{{ $school }}">{{ $school }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-search">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search by intern name..." id="dailySearchInput" onkeyup="filterDailyAttendance()">
                        </div>
                    </div>

                    @php
                        // Get IDs of interns who have attendance today
                        $attendingInternIds = $todayAttendances->pluck('intern_id')->toArray();
                        // Get active interns who don't have attendance today (absent)
                        $absentInterns = $interns->where('status', 'Active')->filter(function($intern) use ($attendingInternIds) {
                            return !in_array($intern->id, $attendingInternIds);
                        });
                        $absentCount = $absentInterns->count();
                        $presentCount = $todayAttendances->count();
                    @endphp
                    <div class="table-card">
                        <div class="table-header">
                            <h3 class="table-title">Today's Attendance - {{ now()->timezone('Asia/Manila')->format('F d, Y') }}</h3>
                            <div style="display: flex; gap: 8px;">
                                <span style="background: #D1FAE5; color: #065F46; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-user-check"></i> {{ $presentCount }} Present
                                </span>
                                @if($absentCount > 0)
                                <span style="background: #FEE2E2; color: #991B1B; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-user-times"></i> {{ $absentCount }} Absent
                                </span>
                                @endif
                            </div>
                        </div>
                        <div style="overflow-x: auto;">
                        <table style="min-width: 1000px;">
                            <thead>
                                <tr>
                                    <th style="min-width: 180px; white-space: nowrap;">Intern Name</th>
                                    <th style="min-width: 150px; white-space: nowrap;">School</th>
                                    <th style="min-width: 100px; white-space: nowrap;">Time In</th>
                                    <th style="min-width: 100px; white-space: nowrap;">Time Out</th>
                                    <th style="min-width: 100px; white-space: nowrap;">Hours Today</th>
                                    <th style="min-width: 140px; white-space: nowrap;">Over/Under</th>
                                    <th style="min-width: 80px; white-space: nowrap;">Status</th>
                                    <th style="min-width: 120px; white-space: nowrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="dailyHoursTableBody">
                                @forelse($todayAttendances ?? [] as $attendance)
                                @php /** @var \App\Models\Attendance $attendance */ @endphp
                                <tr data-attendance-id="{{ $attendance->id }}" data-time-in="{{ $attendance->raw_time_in }}" data-timed-out="{{ $attendance->time_out ? 'true' : 'false' }}" data-status="{{ $attendance->status }}" data-school="{{ $attendance->intern->school ?? 'N/A' }}" data-name="{{ strtolower($attendance->intern->name ?? '') }}">
                                    <td style="white-space: nowrap;">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 36px; height: 36px; min-width: 36px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700; overflow: hidden;">
                                                @if($attendance->intern->profile_picture ?? null)
                                                    <img src="{{ asset('storage/' . $attendance->intern->profile_picture) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    {{ strtoupper(substr($attendance->intern->name ?? 'U', 0, 1)) }}
                                                @endif
                                            </div>
                                            <div class="intern-info" style="white-space: nowrap;">
                                                <span class="intern-name" style="font-weight: 600;">{{ $attendance->intern->name ?? 'Unknown' }}</span>
                                                <span class="intern-school" style="font-size: 12px; color: #6B7280; display: block;">{{ $attendance->intern->reference_code ?? '' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="white-space: nowrap;">{{ $attendance->intern->school ?? 'N/A' }}</td>
                                    <td style="white-space: nowrap;">
                                        <span class="time-in-out-badge time-in" style="background: #D1FAE5; color: #065F46; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 600; white-space: nowrap;">
                                            <i class="fas fa-sign-in-alt"></i> {{ $attendance->formatted_time_in }}
                                        </span>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        @if($attendance->time_out)
                                        <span class="time-in-out-badge time-out" style="background: #FEE2E2; color: #991B1B; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 600; white-space: nowrap;">
                                            <i class="fas fa-sign-out-alt"></i> {{ $attendance->formatted_time_out }}
                                        </span>
                                        @else
                                        <span style="color: #9CA3AF; font-style: italic; white-space: nowrap;">Still working...</span>
                                        @endif
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <strong style="font-size: 16px;" id="hours-{{ $attendance->id }}">
                                            {{ number_format($attendance->current_hours_worked, 2) }} hrs
                                        </strong>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        @if($attendance->time_out)
                                            @if($attendance->hasUndertime())
                                                <span style="background: #FEE2E2; color: #991B1B; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                    <i class="fas fa-arrow-down"></i> -{{ number_format((float)($attendance->undertime_hours ?? 0), 2) }} hrs
                                                </span>
                                            @elseif($attendance->hasOvertime())
                                                @if($attendance->overtime_approved)
                                                    <span style="background: #D1FAE5; color: #065F46; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                        <i class="fas fa-check-circle"></i> +{{ number_format((float)($attendance->overtime_hours ?? 0), 2) }} hrs (Approved)
                                                    </span>
                                                @else
                                                    <span style="background: #FEF3C7; color: #92400E; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                        <i class="fas fa-clock"></i> +{{ number_format((float)($attendance->overtime_hours ?? 0), 2) }} hrs (Pending)
                                                    </span>
                                                @endif
                                            @else
                                                <span style="background: #DBEAFE; color: #1E40AF; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                    <i class="fas fa-check"></i> On Target
                                                </span>
                                            @endif
                                        @else
                                            <span style="color: #9CA3AF; font-size: 12px;">--</span>
                                        @endif
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <span class="hours-badge" style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap;
                                            @if($attendance->status === 'Present') background: #D1FAE5; color: #065F46;
                                            @elseif($attendance->status === 'Late') background: #FEF3C7; color: #92400E;
                                            @else background: #FEE2E2; color: #991B1B; @endif">
                                            {{ $attendance->status }}
                                        </span>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        @if($attendance->isOvertimePending())
                                            <button onclick="approveOvertime({{ $attendance->id }})" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; white-space: nowrap;">
                                                <i class="fas fa-check"></i> Approve OT
                                            </button>
                                        @else
                                            <span style="color: #9CA3AF;">--</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                @endforelse
                                {{-- Show absent interns --}}
                                @foreach($absentInterns as $absentIntern)
                                <tr class="absent-row" data-attendance-id="absent-{{ $absentIntern->id }}" data-status="Absent" data-school="{{ $absentIntern->school ?? 'N/A' }}" data-name="{{ strtolower($absentIntern->name ?? '') }}">
                                    <td style="white-space: nowrap;">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 36px; height: 36px; min-width: 36px; border-radius: 50%; background: linear-gradient(135deg, #9CA3AF, #6B7280); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; overflow: hidden;">
                                                @if($absentIntern->profile_picture ?? null)
                                                    <img src="{{ asset('storage/' . $absentIntern->profile_picture) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.7;">
                                                @else
                                                    {{ strtoupper(substr($absentIntern->name ?? 'U', 0, 1)) }}
                                                @endif
                                            </div>
                                            <div class="intern-info" style="white-space: nowrap;">
                                                <span class="intern-name" style="font-weight: 600; color: #6B7280;">{{ $absentIntern->name ?? 'Unknown' }}</span>
                                                <span class="intern-school" style="font-size: 12px; color: #9CA3AF; display: block;">{{ $absentIntern->reference_code ?? '' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="white-space: nowrap; color: #6B7280;">{{ $absentIntern->school ?? 'N/A' }}</td>
                                    <td style="white-space: nowrap;">
                                        <span style="color: #9CA3AF; font-style: italic;">--</span>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <span style="color: #9CA3AF; font-style: italic;">--</span>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <strong style="font-size: 16px; color: #9CA3AF;">0.00 hrs</strong>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <span style="color: #9CA3AF; font-size: 12px;">--</span>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <span class="hours-badge" style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; background: #FEE2E2; color: #991B1B;">
                                            Absent
                                        </span>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <span style="color: #9CA3AF;">--</span>
                                    </td>
                                </tr>
                                @endforeach
                                @if($todayAttendances->isEmpty() && $absentInterns->isEmpty())
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                        <i class="fas fa-clock" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                                        No active interns found.
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        </div>
                        <!-- Pagination for Today's Attendance -->
                        <div id="dailyHoursPagination" class="table-pagination" style="display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-top: 1px solid #E5E7EB;">
                            <div style="font-size: 14px; color: #6B7280;">
                                Showing <span id="dailyShowingStart">1</span>-<span id="dailyShowingEnd">8</span> of <span id="dailyTotalRecords">0</span> records
                            </div>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <button onclick="changeDailyPage('prev')" id="dailyPrevBtn" style="background: #F3F4F6; border: 1px solid #E5E7EB; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 13px; display: flex; align-items: center; gap: 4px;" disabled>
                                    <i class="fas fa-chevron-left"></i> Previous
                                </button>
                                <div id="dailyPageNumbers" style="display: flex; gap: 4px;"></div>
                                <button onclick="changeDailyPage('next')" id="dailyNextBtn" style="background: #F3F4F6; border: 1px solid #E5E7EB; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 13px; display: flex; align-items: center; gap: 4px;">
                                    Next <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Attendance History -->
                <div id="attendance-history" class="time-tab-content" style="display: none;">
                    <!-- Filter Bar for History -->
                    <div class="filter-bar" style="margin-bottom: 16px;">
                        <div class="filter-group">
                            <span class="filter-label">Status:</span>
                            <select class="filter-select" id="historyStatusFilter" onchange="filterHistoryAttendance()">
                                <option value="all">All Status</option>
                                <option value="Present">Present</option>
                                <option value="Late">Late</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <span class="filter-label">School:</span>
                            <select class="filter-select" id="historySchoolFilter" onchange="filterHistoryAttendance()">
                                <option value="all">All Schools</option>
                                @foreach(($interns ?? collect())->pluck('school')->unique() as $school)
                                <option value="{{ $school }}">{{ $school }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-search">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search by intern name..." id="historySearchInput" onkeyup="filterHistoryAttendance()">
                        </div>
                    </div>
                    <div class="table-card">
                        <div class="table-header">
                            <h3 class="table-title">Attendance History - All Records</h3>
                            <span style="background: #DBEAFE; color: #1E40AF; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                {{ ($attendanceHistory ?? collect())->count() }} Records
                            </span>
                        </div>
                        <div style="overflow-x: auto;">
                        <table style="min-width: 1100px;">
                            <thead>
                                <tr>
                                    <th style="min-width: 120px; white-space: nowrap;">Date</th>
                                    <th style="min-width: 180px; white-space: nowrap;">Intern Name</th>
                                    <th style="min-width: 150px; white-space: nowrap;">School</th>
                                    <th style="min-width: 100px; white-space: nowrap;">Time In</th>
                                    <th style="min-width: 100px; white-space: nowrap;">Time Out</th>
                                    <th style="min-width: 110px; white-space: nowrap;">Hours Worked</th>
                                    <th style="min-width: 140px; white-space: nowrap;">Over/Under</th>
                                    <th style="min-width: 80px; white-space: nowrap;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody">
                                @forelse($attendanceHistory ?? [] as $attendance)
                                @php /** @var \App\Models\Attendance $attendance */ @endphp
                                <tr data-status="{{ $attendance->status }}" data-school="{{ $attendance->intern->school ?? 'N/A' }}" data-name="{{ strtolower($attendance->intern->name ?? '') }}">
                                    <td style="white-space: nowrap;">
                                        <div style="font-weight: 600;">{{ $attendance->date ? \Carbon\Carbon::parse($attendance->date)->format('M d, Y') : 'N/A' }}</div>
                                        <div style="font-size: 12px; color: #6B7280;">{{ $attendance->date ? \Carbon\Carbon::parse($attendance->date)->format('l') : '' }}</div>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 36px; height: 36px; min-width: 36px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700; overflow: hidden;">
                                                @if($attendance->intern->profile_picture ?? null)
                                                    <img src="{{ asset('storage/' . $attendance->intern->profile_picture) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    {{ strtoupper(substr($attendance->intern->name ?? 'U', 0, 1)) }}
                                                @endif
                                            </div>
                                            <div style="white-space: nowrap;">
                                                <span style="font-weight: 600;">{{ $attendance->intern->name ?? 'Unknown' }}</span>
                                                <div style="font-size: 12px; color: #6B7280;">{{ $attendance->intern->reference_code ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="white-space: nowrap;">{{ $attendance->intern->school ?? 'N/A' }}</td>
                                    <td style="white-space: nowrap;">
                                        <span style="background: #D1FAE5; color: #065F46; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 500; white-space: nowrap;">
                                            <i class="fas fa-sign-in-alt" style="margin-right: 4px;"></i>{{ $attendance->formatted_time_in }}
                                        </span>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        @if($attendance->time_out)
                                        <span style="background: #FEE2E2; color: #991B1B; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 500;">
                                            <i class="fas fa-sign-out-alt" style="margin-right: 4px;"></i>{{ $attendance->formatted_time_out }}
                                        </span>
                                        @else
                                        <span style="color: #9CA3AF; font-style: italic; white-space: nowrap;">Not yet</span>
                                        @endif
                                    </td>
                                    <td style="white-space: nowrap;"><strong style="color: #7B1D3A;">{{ number_format((float)($attendance->hours_worked ?? 0), 2) }} hrs</strong></td>
                                    <td style="white-space: nowrap;">
                                        @if($attendance->time_out)
                                            @if($attendance->hasUndertime())
                                                <span style="background: #FEE2E2; color: #991B1B; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap;">
                                                    <i class="fas fa-arrow-down"></i> -{{ number_format((float)($attendance->undertime_hours ?? 0), 2) }} hrs
                                                </span>
                                            @elseif($attendance->hasOvertime())
                                                @if($attendance->overtime_approved)
                                                    <span style="background: #D1FAE5; color: #065F46; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap;">
                                                        <i class="fas fa-check-circle"></i> +{{ number_format((float)($attendance->overtime_hours ?? 0), 2) }} hrs
                                                    </span>
                                                @else
                                                    <span style="background: #FEF3C7; color: #92400E; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap;">
                                                        <i class="fas fa-clock"></i> +{{ number_format((float)($attendance->overtime_hours ?? 0), 2) }} hrs (Pending)
                                                    </span>
                                                @endif
                                            @else
                                                <span style="background: #DBEAFE; color: #1E40AF; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap;">
                                                    <i class="fas fa-check"></i> On Target
                                                </span>
                                            @endif
                                        @else
                                            <span style="color: #9CA3AF; font-size: 12px;">--</span>
                                        @endif
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap;
                                            @if($attendance->status === 'Present') background: #D1FAE5; color: #065F46;
                                            @elseif($attendance->status === 'Late') background: #FEF3C7; color: #92400E;
                                            @else background: #FEE2E2; color: #991B1B; @endif">
                                            {{ $attendance->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                        <i class="fas fa-history" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                                        No attendance history yet. Records will appear here once interns start timing in.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                        <!-- Pagination for Attendance History -->
                        <div id="historyPagination" class="table-pagination" style="display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-top: 1px solid #E5E7EB;">
                            <div style="font-size: 14px; color: #6B7280;">
                                Showing <span id="historyShowingStart">1</span>-<span id="historyShowingEnd">8</span> of <span id="historyTotalRecords">0</span> records
                            </div>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <button onclick="changeHistoryPage('prev')" id="historyPrevBtn" style="background: #F3F4F6; border: 1px solid #E5E7EB; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 13px; display: flex; align-items: center; gap: 4px;" disabled>
                                    <i class="fas fa-chevron-left"></i> Previous
                                </button>
                                <div id="historyPageNumbers" style="display: flex; gap: 4px;"></div>
                                <button onclick="changeHistoryPage('next')" id="historyNextBtn" style="background: #F3F4F6; border: 1px solid #E5E7EB; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 13px; display: flex; align-items: center; gap: 4px;">
                                    Next <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hours Summary -->
                <div id="hours-summary" class="time-tab-content" style="display: none;">
                    <div class="table-card">
                        <div class="table-header">
                            <h3 class="table-title">Comprehensive Hours Summary - All Interns</h3>
                        </div>
                        <div style="overflow-x: auto;">
                        <table style="min-width: 950px;">
                            <thead>
                                <tr>
                                    <th style="min-width: 180px; white-space: nowrap;">Intern Name</th>
                                    <th style="min-width: 150px; white-space: nowrap;">School</th>
                                    <th style="min-width: 110px; white-space: nowrap;">Required Hours</th>
                                    <th style="min-width: 120px; white-space: nowrap;">Completed Hours</th>
                                    <th style="min-width: 120px; white-space: nowrap;">Remaining Hours</th>
                                    <th style="min-width: 140px; white-space: nowrap;">Completion %</th>
                                    <th style="min-width: 100px; white-space: nowrap;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="summaryTableBody">
                                @forelse($interns ?? [] as $intern)
                                @php /** @var \App\Models\Intern $intern */ @endphp
                                <tr>
                                    <td style="white-space: nowrap;">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 36px; height: 36px; min-width: 36px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700; overflow: hidden;">
                                                @if($intern->profile_picture ?? null)
                                                    <img src="{{ asset('storage/' . $intern->profile_picture) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    {{ strtoupper(substr($intern->name, 0, 1)) }}
                                                @endif
                                            </div>
                                            <span style="font-weight: 600; white-space: nowrap;">{{ $intern->name }}</span>
                                        </div>
                                    </td>
                                    <td style="white-space: nowrap;">{{ $intern->school }}</td>
                                    <td style="white-space: nowrap;">{{ $intern->required_hours }} hrs</td>
                                    <td style="white-space: nowrap;"><strong style="color: #10B981;">{{ $intern->completed_hours }} hrs</strong></td>
                                    <td style="white-space: nowrap; @if($intern->remaining_hours > 200) color: #EF4444; font-weight: 600; @endif">
                                        {{ $intern->remaining_hours }} hrs
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div class="progress-bar-container" style="flex: 1; max-width: 80px; min-width: 60px;">
                                                <div class="progress-bar" style="width: {{ $intern->progress_percentage }}%; @if($intern->progress_percentage < 50) background: #EF4444; @endif"></div>
                                            </div>
                                            <span style="font-weight: 600;">{{ $intern->progress_percentage }}%</span>
                                        </div>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        @if($intern->progress_percentage >= 75)
                                        <span class="status-badge status-active" style="white-space: nowrap;">On Track</span>
                                        @elseif($intern->progress_percentage >= 50)
                                        <span class="status-badge status-pending" style="white-space: nowrap;">Moderate</span>
                                        @else
                                        <span class="status-badge" style="background: #FEE2E2; color: #991B1B; white-space: nowrap;">At Risk</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                        <i class="fas fa-chart-bar" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                                        No intern hours data available.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                        <!-- Pagination for Hours Summary -->
                        <div id="summaryPagination" class="table-pagination" style="display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-top: 1px solid #E5E7EB;">
                            <div style="font-size: 14px; color: #6B7280;">
                                Showing <span id="summaryShowingStart">1</span>-<span id="summaryShowingEnd">8</span> of <span id="summaryTotalRecords">0</span> records
                            </div>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <button onclick="changeSummaryPage('prev')" id="summaryPrevBtn" style="background: #F3F4F6; border: 1px solid #E5E7EB; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 13px; display: flex; align-items: center; gap: 4px;" disabled>
                                    <i class="fas fa-chevron-left"></i> Previous
                                </button>
                                <div id="summaryPageNumbers" style="display: flex; gap: 4px;"></div>
                                <button onclick="changeSummaryPage('next')" id="summaryNextBtn" style="background: #F3F4F6; border: 1px solid #E5E7EB; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 13px; display: flex; align-items: center; gap: 4px;">
                                    Next <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Assignment Page -->
            <div id="task-assignment" class="page-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Task Assignment Management</h2>
                    <p style="color: #6B7280; font-size: 14px;">Assign tasks to interns, set deadlines and priorities, monitor task status and progress</p>
                </div>

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="filter-group">
                        <span class="filter-label">Status:</span>
                        <select class="filter-select" onchange="filterTasks()" id="taskStatusFilter">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">Assignment Type:</span>
                        <select class="filter-select" onchange="filterTasks()" id="assignmentTypeFilter">
                            <option value="all">All Types</option>
                            <option value="group">Group Assigned</option>
                            <option value="individual">Individual Assigned</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">School:</span>
                        <select class="filter-select" onchange="filterTasks()" id="taskSchoolFilter">
                            <option value="all">All Schools</option>
                            <option value="upcebu">UP Cebu</option>
                            <option value="ctu">CTU</option>
                            <option value="scu">San Carlos University</option>
                        </select>
                    </div>
                    <div class="filter-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search by task or intern name..." onkeyup="searchTasks()" id="taskSearchInput">
                    </div>
                    <button class="filter-btn" onclick="openNewTaskModal()">
                        <i class="fas fa-plus"></i> New Task
                    </button>
                    <button class="filter-btn secondary" onclick="resetTaskFilters()">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>

                <!-- Bulk Task Actions Bar -->
                <div id="bulkTaskActionsBar" style="display: none; background: linear-gradient(135deg, #1E40AF 0%, #1E3A8A 100%); color: white; padding: 12px 20px; border-radius: 10px; margin-bottom: 16px; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span id="selectedTaskCountText" style="font-size: 14px; font-weight: 600;">0 tasks selected</span>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button onclick="bulkUpdateTaskStatus('In Progress')" style="background: linear-gradient(135deg, #F59E0B, #D97706); color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-play"></i> Mark In Progress
                        </button>
                        <button onclick="bulkUpdateTaskStatus('Completed')" style="background: linear-gradient(135deg, #10B981, #059669); color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-check-double"></i> Mark Completed
                        </button>
                        <button onclick="bulkDeleteTasks()" style="background: linear-gradient(135deg, #EF4444, #DC2626); color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-trash"></i> Delete Selected
                        </button>
                        <button onclick="clearTaskSelection()" style="background: rgba(255,255,255,0.2); color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-header">
                        <h3 class="table-title">All Task Assignments</h3>
                        <button style="padding: 8px 16px; background: #7B1D3A; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-download"></i> Export Tasks
                        </button>
                    </div>
                    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table style="min-width: 1400px;">
                        <thead>
                            <tr>
                                <th style="width: 40px; min-width: 40px;"><input type="checkbox" id="selectAllTasks" onchange="toggleSelectAllTasks()" style="width: 16px; height: 16px; cursor: pointer; accent-color: #7B1D3A;"></th>
                                <th style="min-width: 200px;">Task Title</th>
                                <th style="min-width: 180px;">Assigned To</th>
                                <th style="min-width: 140px;">Assignment Type</th>
                                <th style="min-width: 120px;">School</th>
                                <th style="min-width: 120px;">Due Date</th>
                                <th style="min-width: 100px;">Priority</th>
                                <th style="min-width: 120px;">Progress</th>
                                <th style="min-width: 120px;">Status</th>
                                <th style="min-width: 150px; position: sticky; right: 0; background: #F9FAFB; z-index: 10;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="taskTableBody">
                            @php
                                /** @var \Illuminate\Support\Collection<int, \App\Models\Task> $tasks */
                                $groupedTasks = ($tasks ?? collect())->groupBy('group_id')->map(function($group) {
                                    return $group->first()->group_id ? $group : $group->each(fn($t) => $t->group_id = 'single_'.$t->id);
                                });
                                $displayedTasks = collect();
                                /** @var \App\Models\Task $task */
                                foreach($tasks ?? [] as $task) {
                                    if (!$task->group_id || !$displayedTasks->contains('group_id', $task->group_id)) {
                                        $displayedTasks->push($task);
                                    }
                                }
                            @endphp
                            @forelse($displayedTasks as $task)
                            @php
                                $groupMembers = ($tasks ?? collect())->where('group_id', $task->group_id)->where('group_id', '!=', null);
                                $isGroup = $groupMembers->count() > 1;
                                $schoolsInGroup = $groupMembers->pluck('intern.school')->filter()->unique();
                                $isMixedSchool = $schoolsInGroup->count() > 1;
                            @endphp
                            <tr data-task-id="{{ $task->id }}">
                                <td style="text-align: center;">
                                    <input type="checkbox" class="task-checkbox" data-task-id="{{ $task->id }}" data-task-title="{{ $task->title }}" onchange="updateTaskSelection()" style="width: 16px; height: 16px; cursor: pointer; accent-color: #7B1D3A;">
                                </td>
                                <td>
                                    <div style="font-weight: 600; margin-bottom: 4px;">{{ $task->title }}</div>
                                    <div style="font-size: 12px; color: #6B7280;">{{ Str::limit($task->description ?? 'No description', 80) }}</div>
                                </td>
                                <td>
                                    @if($isGroup)
                                        <div style="display: flex; flex-direction: column; gap: 4px;">
                                            @foreach($groupMembers->take(2) as $member)
                                            <div class="intern-info">
                                                <span class="intern-name">{{ $member->intern->name ?? 'Unknown' }}</span>
                                                <span class="intern-school">{{ $member->intern->school ?? 'N/A' }}</span>
                                            </div>
                                            @endforeach
                                            @if($groupMembers->count() > 2)
                                            <span style="font-size: 11px; color: #6B7280; font-style: italic;">+{{ $groupMembers->count() - 2 }} more</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="intern-info">
                                            <span class="intern-name">{{ $task->intern->name ?? 'Unknown' }}</span>
                                            <span class="intern-school">{{ $task->intern->school ?? 'N/A' }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($isGroup)
                                        <span class="status-badge" style="background: #DBEAFE; color: #1E40AF;">Group ({{ $groupMembers->count() }})</span>
                                    @else
                                        <span class="status-badge" style="background: #D1FAE5; color: #065F46;">Individual</span>
                                    @endif
                                </td>
                                <td>
                                    @if($isMixedSchool)
                                        <span style="font-size: 12px; color: #7B1D3A; font-weight: 600;">Mixed Schools</span>
                                    @else
                                        {{ $task->intern->school ?? 'N/A' }}
                                    @endif
                                </td>
                                <td style="font-weight: 600;">{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</td>
                                <td>
                                    @php
                                        $priorityStyle = $task->priority === 'High'
                                            ? 'background: #DC2626; color: white;'
                                            : ($task->priority === 'Medium'
                                                ? 'background: #F59E0B; color: white;'
                                                : 'background: #228B22; color: white;');
                                    @endphp
                                    <span class="status-badge" style="{{ $priorityStyle }}">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="progress-bar-container" style="flex: 1; max-width: 80px;">
                                            <div class="progress-bar" style="width: {{ $task->progress ?? 0 }}%;"></div>
                                        </div>
                                        <span style="font-weight: 600; font-size: 12px;">{{ $task->progress ?? 0 }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $isPendingAdminApproval = $task->status === 'Completed' && empty($task->completed_date);
                                        $statusStyle = $task->status === 'Completed'
                                            ? 'background: #D1FAE5; color: #065F46;'
                                            : ($task->status === 'In Progress'
                                                ? 'background: #FEF3C7; color: #92400E;'
                                                : 'background: #E5E7EB; color: #6B7280;');

                                        if ($isPendingAdminApproval) {
                                            $statusStyle = 'background: #DBEAFE; color: #1E40AF;';
                                        }
                                    @endphp
                                    <span class="status-badge" style="{{ $statusStyle }}">
                                        {{ $isPendingAdminApproval ? 'Pending Admin Approval' : $task->status }}
                                    </span>
                                </td>
                                <td style="position: sticky; right: 0; background: #F9FAFB; z-index: 9;">
                                    <div class="action-buttons" style="display: flex; gap: 6px; justify-content: center; flex-wrap: nowrap;">
                                        <button class="btn-action btn-view" title="View Details" onclick="viewTaskDetails({{ $task->id }})" style="padding: 8px 10px; background: #3B82F6; color: white; border: none; border-radius: 4px; cursor: pointer; flex-shrink: 0;"><i class="fas fa-eye"></i></button>
                                        <button class="btn-action btn-edit" title="Edit Task" onclick="editTask({{ $task->id }})" style="padding: 8px 10px; background: #F59E0B; color: white; border: none; border-radius: 4px; cursor: pointer; flex-shrink: 0;"><i class="fas fa-edit"></i></button>
                                        @if($task->status === 'Completed' && !empty($task->completed_date))
                                        <button class="btn-action" title="Already Completed" disabled style="padding: 8px 10px; border: none; border-radius: 4px; flex-shrink: 0;"><i class="fas fa-check"></i></button>
                                        @else
                                        <button class="btn-action btn-check" title="Mark Complete" onclick="markTaskComplete({{ $task->id }})" style="padding: 8px 10px; background: #10B981; color: white; border: none; border-radius: 4px; cursor: pointer; flex-shrink: 0;"><i class="fas fa-check"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                    <i class="fas fa-tasks" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                                    No tasks assigned yet. Click <strong>New Task</strong> to create one.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>

                    <!-- Pagination -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-top: 1px solid #E5E7EB;">
                        <div style="color: #6B7280; font-size: 14px;">
                            Showing <strong id="taskShowingStart">1</strong> to <strong id="taskShowingEnd">9</strong> of <strong id="taskTotalEntries">9</strong> entries
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <button id="taskPrevPage" onclick="changeTaskPage('prev')" style="padding: 8px 12px; border: 1px solid #E5E7EB; background: white; border-radius: 6px; cursor: pointer; font-weight: 600; color: #6B7280;" disabled>
                                <i class="fas fa-chevron-left"></i> Previous
                            </button>
                            <button style="padding: 8px 12px; border: 1px solid #7B1D3A; background: #7B1D3A; color: white; border-radius: 6px; font-weight: 600;">1</button>
                            <button id="taskNextPage" onclick="changeTaskPage('next')" style="padding: 8px 12px; border: 1px solid #E5E7EB; background: white; border-radius: 6px; cursor: pointer; font-weight: 600; color: #6B7280;" disabled>
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Research Tracking Page -->
            <div id="research-tracking" class="page-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Research & Startup Document Tracking</h2>
                    <p style="color: #6B7280; font-size: 14px;">Track and manage document submissions from startups in the incubation program</p>
                </div>

                <!-- Stats Overview -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #DBEAFE, #93C5FD); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-file-alt" style="color: #2563EB; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #1F2937;">{{ $startupDocuments->count() }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Total Documents</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #FEF3C7, #FCD34D); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock" style="color: #D97706; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #D97706;">{{ $startupDocuments->where('status', 'pending')->count() }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Pending Review</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #E0E7FF, #A5B4FC); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-search" style="color: #4F46E5; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #4F46E5;">{{ $startupDocuments->where('status', 'under_review')->count() }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Under Review</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #D1FAE5, #6EE7B7); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check-circle" style="color: #059669; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #059669;">{{ $startupDocuments->whereIn('status', ['approved', 'completed'])->count() }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Approved</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="filter-group">
                        <span class="filter-label">Status:</span>
                        <select class="filter-select" onchange="filterDocuments()" id="documentStatusFilter">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="under_review">Under Review</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">Document Type:</span>
                        <select class="filter-select" onchange="filterDocuments()" id="documentTypeFilter">
                            <option value="all">All Types</option>
                            <option value="Business Plan">Business Plan</option>
                            <option value="Financial Report">Financial Report</option>
                            <option value="Progress Report">Progress Report</option>
                            <option value="Legal Document">Legal Document</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="filter-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search documents..." onkeyup="searchDocuments()" id="documentSearchInput">
                    </div>
                </div>

                <!-- Kanban Board View -->
                <div id="kanban-view" class="kanban-container">
                    <!-- Pending Column -->
                    <div class="kanban-column" data-status="pending" ondrop="dropCard(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                        <div class="kanban-header">
                            <h4><i class="fas fa-inbox"></i> Pending</h4>
                            <span class="kanban-count" id="pending-count">{{ $startupDocuments->where('status', 'pending')->count() }}</span>
                        </div>
                        <div class="kanban-cards" id="pending-cards">
                            @forelse($startupDocuments->where('status', 'pending') as $doc)
                            <div class="kanban-card pending" draggable="true" data-id="{{ $doc->id }}" ondragstart="dragStart(event)" ondragend="dragEnd(event)" onclick="viewDocumentDetails('{{ $doc->id }}')">
                                <div class="card-header">
                                    <h5>{{ $doc->company_name }}</h5>
                                    <span class="status-badge" style="background: #FEF3C7; color: #92400E; font-size: 9px; padding: 2px 6px;">Pending</span>
                                </div>
                                <p class="card-description">{{ $doc->document_type }}</p>
                                <div class="card-meta">
                                    <span><i class="fas fa-user"></i> {{ Str::limit($doc->contact_person, 15) }}</span>
                                    <span><i class="fas fa-calendar"></i> {{ $doc->created_at->format('M d') }}</span>
                                </div>
                                <div style="font-size: 10px; color: #6B7280;">
                                    <i class="fas fa-hashtag"></i> {{ $doc->tracking_code }}
                                </div>
                                <div style="margin-top: auto; padding-top: 8px; display: flex; gap: 4px;">
                                    <button onclick="event.stopPropagation(); openReviewDocumentModal('{{ $doc->id }}')" style="flex: 1; padding: 5px 8px; font-size: 10px; background: #10B981; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                        <i class="fas fa-check"></i> Review
                                    </button>
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" onclick="event.stopPropagation();" style="padding: 5px 8px; font-size: 10px; background: #3B82F6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none;">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            @empty
                            <div class="kanban-empty" style="padding: 20px; text-align: center; color: #9CA3AF; font-size: 12px;">
                                <i class="fas fa-inbox" style="font-size: 20px; margin-bottom: 6px; display: block;"></i>
                                No pending documents
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Under Review Column -->
                    <div class="kanban-column" data-status="under_review" ondrop="dropCard(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                        <div class="kanban-header">
                            <h4><i class="fas fa-search"></i> Under Review</h4>
                            <span class="kanban-count" id="under_review-count">{{ $startupDocuments->where('status', 'under_review')->count() }}</span>
                        </div>
                        <div class="kanban-cards" id="under_review-cards">
                            @forelse($startupDocuments->where('status', 'under_review') as $doc)
                            <div class="kanban-card reviewing" draggable="true" data-id="{{ $doc->id }}" ondragstart="dragStart(event)" ondragend="dragEnd(event)" onclick="viewDocumentDetails('{{ $doc->id }}')">
                                <div class="card-header">
                                    <h5>{{ $doc->company_name }}</h5>
                                    <span class="status-badge" style="background: #DBEAFE; color: #1E40AF; font-size: 9px; padding: 2px 6px;">Reviewing</span>
                                </div>
                                <p class="card-description">{{ $doc->document_type }}</p>
                                <div class="card-meta">
                                    <span><i class="fas fa-user"></i> {{ Str::limit($doc->contact_person, 15) }}</span>
                                    <span><i class="fas fa-calendar"></i> {{ $doc->created_at->format('M d') }}</span>
                                </div>
                                <div style="font-size: 10px; color: #6B7280;">
                                    <i class="fas fa-hashtag"></i> {{ $doc->tracking_code }}
                                </div>
                                <div style="margin-top: auto; padding-top: 8px; display: flex; gap: 4px;">
                                    <button onclick="event.stopPropagation(); openReviewDocumentModal('{{ $doc->id }}')" style="flex: 1; padding: 5px 8px; font-size: 10px; background: #10B981; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                        <i class="fas fa-check"></i> Approve/Reject
                                    </button>
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" onclick="event.stopPropagation();" style="padding: 5px 8px; font-size: 10px; background: #3B82F6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none;">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            @empty
                            <div class="kanban-empty" style="padding: 20px; text-align: center; color: #9CA3AF; font-size: 12px;">
                                <i class="fas fa-search" style="font-size: 20px; margin-bottom: 6px; display: block;"></i>
                                No documents under review
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Approved Column -->
                    <!-- Approved Column -->
                    <div class="kanban-column" data-status="approved" ondrop="dropCard(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                        <div class="kanban-header">
                            <h4><i class="fas fa-check-circle"></i> Approved</h4>
                            <span class="kanban-count" id="approved-count">{{ $startupDocuments->where('status', 'approved')->count() }}</span>
                        </div>
                        <div class="kanban-cards" id="approved-cards">
                            @forelse($startupDocuments->where('status', 'approved') as $doc)
                            <div class="kanban-card success" draggable="true" data-id="{{ $doc->id }}" ondragstart="dragStart(event)" ondragend="dragEnd(event)" onclick="viewDocumentDetails('{{ $doc->id }}')">
                                <div class="card-header">
                                    <h5>{{ $doc->company_name }}</h5>
                                    <span class="status-badge" style="background: #DCFCE7; color: #166534; font-size: 9px; padding: 2px 6px;">Approved</span>
                                </div>
                                <p class="card-description">{{ $doc->document_type }}</p>
                                <div class="card-meta">
                                    <span><i class="fas fa-user"></i> {{ Str::limit($doc->contact_person, 15) }}</span>
                                    <span><i class="fas fa-calendar"></i> {{ $doc->reviewed_at ? $doc->reviewed_at->format('M d') : 'N/A' }}</span>
                                </div>
                                <div style="font-size: 10px; color: #6B7280;">
                                    <i class="fas fa-hashtag"></i> {{ $doc->tracking_code }}
                                </div>
                                @if($doc->reviewer)
                                <div style="margin-top: auto; padding-top: 6px; font-size: 10px; color: #059669;">
                                    <i class="fas fa-user-check"></i> {{ Str::limit($doc->reviewer->name, 15) }}
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="kanban-empty" style="padding: 20px; text-align: center; color: #9CA3AF; font-size: 12px;">
                                <i class="fas fa-check-circle" style="font-size: 20px; margin-bottom: 6px; display: block;"></i>
                                No approved documents
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Rejected Column -->
                    <div class="kanban-column" data-status="rejected" ondrop="dropCard(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                        <div class="kanban-header">
                            <h4><i class="fas fa-times-circle"></i> Rejected</h4>
                            <span class="kanban-count" id="rejected-count">{{ $startupDocuments->where('status', 'rejected')->count() }}</span>
                        </div>
                        <div class="kanban-cards" id="rejected-cards">
                            @forelse($startupDocuments->where('status', 'rejected') as $doc)
                            <div class="kanban-card rejected" draggable="true" data-id="{{ $doc->id }}" ondragstart="dragStart(event)" ondragend="dragEnd(event)" onclick="viewDocumentDetails('{{ $doc->id }}')">
                                <div class="card-header">
                                    <h5>{{ $doc->company_name }}</h5>
                                    <span class="status-badge" style="background: #FEE2E2; color: #991B1B; font-size: 9px; padding: 2px 6px;">Rejected</span>
                                </div>
                                <p class="card-description">{{ $doc->document_type }}</p>
                                <div class="card-meta">
                                    <span><i class="fas fa-user"></i> {{ Str::limit($doc->contact_person, 15) }}</span>
                                    <span><i class="fas fa-calendar"></i> {{ $doc->reviewed_at ? $doc->reviewed_at->format('M d') : 'N/A' }}</span>
                                </div>
                                <div style="font-size: 10px; color: #6B7280;">
                                    <i class="fas fa-hashtag"></i> {{ $doc->tracking_code }}
                                </div>
                                @if($doc->admin_notes)
                                <div style="margin-top: auto; padding-top: 6px; font-size: 9px; color: #DC2626; padding: 4px; background: #FEF2F2; border-radius: 3px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ Str::limit($doc->admin_notes, 30) }}
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="kanban-empty" style="padding: 20px; text-align: center; color: #9CA3AF; font-size: 12px;">
                                <i class="fas fa-times-circle" style="font-size: 20px; margin-bottom: 6px; display: block;"></i>
                                No rejected documents
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- List View (Hidden by default) -->
                <div id="list-view" style="display: none;">
                    <div class="table-card">
                        <div class="table-header">
                            <h3 class="table-title">All Startup Documents</h3>
                            <button style="padding: 8px 16px; background: #7B1D3A; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-download"></i> Export Report
                            </button>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Tracking Code</th>
                                    <th>Company Name</th>
                                    <th>Document Type</th>
                                    <th>Contact Person</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($startupDocuments as $doc)
                                <tr class="document-row" data-status="{{ $doc->status }}" data-type="{{ $doc->document_type }}">
                                    <td><strong>{{ $doc->tracking_code }}</strong></td>
                                    <td>
                                        <div style="font-weight: 600; margin-bottom: 4px;">{{ $doc->company_name }}</div>
                                        <div style="font-size: 12px; color: #6B7280;">{{ $doc->email }}</div>
                                    </td>
                                    <td>{{ $doc->document_type }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div class="avatar">{{ strtoupper(substr($doc->contact_person, 0, 1)) }}</div>
                                            <span>{{ $doc->contact_person }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $doc->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($doc->status == 'pending')
                                            <span class="status-badge" style="background: #FEF3C7; color: #92400E;">Pending</span>
                                        @elseif($doc->status == 'under_review')
                                            <span class="status-badge" style="background: #DBEAFE; color: #1E40AF;">Under Review</span>
                                        @elseif($doc->status == 'approved')
                                            <span class="status-badge" style="background: #DCFCE7; color: #166534;">Approved</span>
                                        @elseif($doc->status == 'rejected')
                                            <span class="status-badge" style="background: #FEE2E2; color: #991B1B;">Rejected</span>
                                        @else
                                            <span class="status-badge" style="background: #E5E7EB; color: #374151;">{{ ucfirst($doc->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action btn-view" onclick="viewDocumentDetails('{{ $doc->id }}')" title="View Details"><i class="fas fa-eye"></i></button>
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn-action btn-edit" title="Download"><i class="fas fa-download"></i></a>
                                            @if(in_array($doc->status, ['pending', 'under_review']))
                                            <button class="btn-action" style="background: #10B981; color: white;" onclick="event.stopPropagation(); openReviewDocumentModal('{{ $doc->id }}')" title="Review"><i class="fas fa-clipboard-check"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                        <i class="fas fa-file-alt" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                                        No document submissions yet
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Incubatee Tracker Page -->
            <div id="incubatee-tracker" class="page-content">
                <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Incubatee Management & Tracking</h2>
                        <p style="color: #6B7280; font-size: 14px;">Monitor MOA requests, payment submissions, and incubatee activities from the startup portal</p>
                    </div>
                    <button onclick="openMoaTemplateModal()" style="background: linear-gradient(135deg, #7B1D3A, #5a1428); color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: transform 0.2s, box-shadow 0.2s;">
                        <i class="fas fa-file-contract"></i> Generate MOA Template
                    </button>
                </div>

                <!-- Stats Overview -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #D1FAE5, #6EE7B7); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check-circle" style="color: #059669; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #059669;">{{ $activeIncubatees }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Active Incubatees</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #DBEAFE, #93C5FD); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-file-contract" style="color: #2563EB; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #2563EB;">{{ $moaRequests->count() }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Total MOA Requests</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #FEF3C7, #FCD34D); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-exclamation-circle" style="color: #D97706; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #D97706;">{{ $pendingMoaCount }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Pending MOAs</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #E0E7FF, #A5B4FC); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-money-bill-wave" style="color: #4F46E5; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #4F46E5;">{{ $pendingPaymentCount }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Pending Payments</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs for MOA and Payments -->
                <div class="filter-tabs" style="margin-bottom: 20px;">
                    <button class="filter-tab active" onclick="switchIncubateeTab('moa')" id="moaTabBtn">
                        <i class="fas fa-file-contract"></i> MOA Requests
                    </button>
                    <button class="filter-tab" onclick="switchIncubateeTab('payments')" id="paymentsTabBtn">
                        <i class="fas fa-credit-card"></i> Payment Submissions
                    </button>
                </div>

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="filter-group">
                        <span class="filter-label">Status:</span>
                        <select class="filter-select" onchange="filterIncubatees()" id="incubateeStatusFilter">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="under_review">Under Review</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="filter-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search incubatees..." onkeyup="searchIncubatees()" id="incubateeSearchInput">
                    </div>
                </div>

                <!-- MOA Requests Table -->
                <div id="moa-table" class="table-card">
                    <div class="table-header">
                        <h3 class="table-title">MOA Requests from Startups</h3>
                        <button style="padding: 8px 16px; background: #7B1D3A; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-download"></i> Export Report
                        </button>
                    </div>
                    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table style="min-width: 950px;">
                        <thead>
                            <tr>
                                <th>Tracking Code</th>
                                <th>Company/Startup</th>
                                <th>Contact Person</th>
                                <th>MOA Purpose</th>
                                <th>Submitted</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($moaRequests as $moa)
                            <tr class="incubatee-row" data-status="{{ $moa->status }}">
                                <td><strong>{{ $moa->tracking_code }}</strong></td>
                                <td>
                                    <div style="font-weight: 600; margin-bottom: 4px;">{{ $moa->company_name }}</div>
                                    <div style="font-size: 12px; color: #6B7280;">{{ $moa->email }}</div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="avatar">{{ strtoupper(substr($moa->contact_person, 0, 1)) }}</div>
                                        <div>
                                            <span style="font-weight: 600;">{{ $moa->contact_person }}</span>
                                            @if($moa->phone)
                                            <div style="font-size: 11px; color: #6B7280;">{{ $moa->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 500;">{{ $moa->moa_purpose }}</div>
                                    @if($moa->moa_details)
                                    <div style="font-size: 11px; color: #6B7280; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ Str::limit($moa->moa_details, 50) }}
                                    </div>
                                    @endif
                                </td>
                                <td>{{ $moa->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($moa->status == 'pending')
                                        <span class="status-badge" style="background: #FEF3C7; color: #92400E;">Pending</span>
                                    @elseif($moa->status == 'under_review')
                                        <span class="status-badge" style="background: #DBEAFE; color: #1E40AF;">Under Review</span>
                                    @elseif($moa->status == 'approved')
                                        <span class="status-badge" style="background: #DCFCE7; color: #166534;">Approved</span>
                                        @if($moa->reviewed_at)
                                        <div style="font-size: 11px; color: #10B981; margin-top: 2px;">{{ $moa->reviewed_at->format('M d, Y') }}</div>
                                        @endif
                                    @elseif($moa->status == 'rejected')
                                        <span class="status-badge" style="background: #FEE2E2; color: #991B1B;">Rejected</span>
                                    @else
                                        <span class="status-badge" style="background: #E5E7EB; color: #374151;">{{ ucfirst($moa->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-view" onclick="viewMoaDetails('{{ $moa->id }}')"><i class="fas fa-eye"></i></button>
                                        <button class="btn-action btn-edit" onclick="reviewSubmission('{{ $moa->id }}', 'moa')"><i class="fas fa-clipboard-check"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                    <i class="fas fa-file-contract" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                                    No MOA requests yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>

                <!-- Payment Submissions Table -->
                <div id="payments-table" class="table-card" style="display: none;">
                    <div class="table-header">
                        <h3 class="table-title">Payment Submissions from Startups</h3>
                        <button style="padding: 8px 16px; background: #7B1D3A; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-download"></i> Export Report
                        </button>
                    </div>
                    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table style="min-width: 1000px;">
                        <thead>
                            <tr>
                                <th>Tracking Code</th>
                                <th>Company/Startup</th>
                                <th>Invoice #</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Payment Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentSubmissions as $payment)
                            @php
                                $methodLabels = [
                                    'bank_transfer' => '🏦 Bank Transfer',
                                    'bank_deposit' => '💵 Bank Deposit',
                                    'gcash' => '<img src="' . asset('images/gcash.jpg') . '" alt="GCash" style="height: 16px; width: auto; vertical-align: middle; margin-right: 4px;">GCash',
                                    'maya' => '📱 Maya',
                                    'check' => '📄 Check',
                                    'cash' => '💰 Cash'
                                ];
                            @endphp
                            <tr class="incubatee-row" data-status="{{ $payment->status }}">
                                <td><strong>{{ $payment->tracking_code }}</strong></td>
                                <td>
                                    <div style="font-weight: 600; margin-bottom: 4px;">{{ $payment->company_name }}</div>
                                    <div style="font-size: 12px; color: #6B7280;">{{ $payment->contact_person }}</div>
                                </td>
                                <td><strong>{{ $payment->invoice_number }}</strong></td>
                                <td style="font-weight: 700; color: #059669;">₱{{ number_format($payment->amount, 2) }}</td>
                                <td>
                                    <span style="font-size: 12px;">{!! $methodLabels[$payment->payment_method] ?? $payment->payment_method ?? 'N/A' !!}</span>
                                </td>
                                <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    @if($payment->status == 'pending')
                                        <span class="status-badge" style="background: #FEF3C7; color: #92400E;">Pending</span>
                                    @elseif($payment->status == 'under_review')
                                        <span class="status-badge" style="background: #DBEAFE; color: #1E40AF;">Under Review</span>
                                    @elseif($payment->status == 'approved')
                                        <span class="status-badge" style="background: #DCFCE7; color: #166534;">Verified</span>
                                    @elseif($payment->status == 'rejected')
                                        <span class="status-badge" style="background: #FEE2E2; color: #991B1B;">Rejected</span>
                                    @else
                                        <span class="status-badge" style="background: #E5E7EB; color: #374151;">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-view" onclick="viewPaymentDetails('{{ $payment->id }}')" title="View Details"><i class="fas fa-eye"></i></button>
                                        <button class="btn-action" style="background: #10B981; color: white;" onclick="reviewSubmission('{{ $payment->id }}', 'finance')" title="Review"><i class="fas fa-clipboard-check"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                    <i class="fas fa-credit-card" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                                    No payment submissions yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            <!-- Issues & Complaints Management Page -->
            <div id="issues-management" class="page-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Issues & Complaints Management</h2>
                    <p style="color: #6B7280; font-size: 14px;">Track and resolve facility issues, equipment problems, and incubatee complaints from the startup portal</p>
                </div>

                <!-- Stats Overview -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #FEE2E2, #FCA5A5); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-exclamation-circle" style="color: #DC2626; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #DC2626;">{{ $openIssues }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Open Issues</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #FEF3C7, #FCD34D); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock" style="color: #D97706; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #D97706;">{{ $inProgressIssues }}</div>
                                <div style="font-size: 13px; color: #6B7280;">In Progress</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #D1FAE5, #6EE7B7); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check-circle" style="color: #059669; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #059669;">{{ $resolvedThisMonth }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Resolved (This Month)</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #E0E7FF, #A5B4FC); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-building" style="color: #4F46E5; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #4F46E5;">{{ $roomIssues->count() }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Total Reports</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="filter-group">
                        <span class="filter-label">Status:</span>
                        <select class="filter-select" onchange="filterIssues()" id="issueStatusFilter">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <span class="filter-label">Type:</span>
                        <select class="filter-select" onchange="filterIssues()" id="issueTypeFilter">
                            <option value="all">All Types</option>
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
                    <div class="filter-group">
                        <span class="filter-label">Priority:</span>
                        <select class="filter-select" onchange="filterIssues()" id="issuePriorityFilter">
                            <option value="all">All Priorities</option>
                            <option value="urgent">Urgent</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                    <div class="filter-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search issues..." onkeyup="searchIssues()" id="issueSearchInput">
                    </div>
                </div>

                <!-- Issues Table -->
                <div class="table-card" style="overflow-x: auto;">
                    <div class="table-header" style="flex-wrap: wrap; gap: 12px;">
                        <h3 class="table-title" style="font-size: 15px;">All Room Issues & Complaints</h3>
                        <div style="display: flex; gap: 8px;">
                            <button style="padding: 6px 12px; background: white; color: #7B1D3A; border: 2px solid #7B1D3A; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                    <table style="min-width: 900px; font-size: 13px;">
                        <thead>
                            <tr>
                                <th style="padding: 10px 12px; font-size: 11px;">Code</th>
                                <th style="padding: 10px 12px; font-size: 11px;">Room/Description</th>
                                <th style="padding: 10px 12px; font-size: 11px;">Type</th>
                                <th style="padding: 10px 12px; font-size: 11px;">Reported By</th>
                                <th style="padding: 10px 12px; font-size: 11px;">Priority</th>
                                <th style="padding: 10px 12px; font-size: 11px;">Status</th>
                                <th style="padding: 10px 12px; font-size: 11px;">Date</th>
                                <th style="padding: 10px 12px; font-size: 11px; width: 90px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roomIssues as $issue)
                            <tr class="issue-row" data-status="{{ $issue->status }}" data-type="{{ $issue->issue_type }}" data-priority="{{ $issue->priority }}">
                                <td style="padding: 10px 12px;"><strong style="font-size: 12px;">{{ $issue->tracking_code }}</strong></td>
                                <td style="padding: 10px 12px;">
                                    <div style="font-weight: 600; margin-bottom: 2px; font-size: 12px;">Room {{ $issue->room_number }}</div>
                                    <div style="font-size: 11px; color: #6B7280; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($issue->description, 40) }}</div>
                                </td>
                                <td style="padding: 10px 12px;">
                                    @php
                                        $typeColors = [
                                            'electrical' => ['bg' => '#FEF3C7', 'text' => '#92400E'],
                                            'plumbing' => ['bg' => '#DBEAFE', 'text' => '#1E40AF'],
                                            'aircon' => ['bg' => '#E0F2FE', 'text' => '#0369A1'],
                                            'internet' => ['bg' => '#FEE2E2', 'text' => '#991B1B'],
                                            'furniture' => ['bg' => '#F3E8FF', 'text' => '#6B21A8'],
                                            'cleaning' => ['bg' => '#DCFCE7', 'text' => '#166534'],
                                            'security' => ['bg' => '#FCE7F3', 'text' => '#9D174D'],
                                            'other' => ['bg' => '#E5E7EB', 'text' => '#374151'],
                                        ];
                                        $color = $typeColors[$issue->issue_type] ?? $typeColors['other'];
                                    @endphp
                                    <span class="status-badge" style="background: {{ $color['bg'] }}; color: {{ $color['text'] }}; font-size: 10px; padding: 3px 8px;">{{ $issue->issue_type_label }}</span>
                                </td>
                                <td style="padding: 10px 12px;">
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <div class="avatar" style="width: 28px; height: 28px; font-size: 11px;">{{ strtoupper(substr($issue->contact_person, 0, 1)) }}</div>
                                        <div>
                                            <span style="font-weight: 600; font-size: 12px;">{{ Str::limit($issue->contact_person, 15) }}</span>
                                            <div style="font-size: 10px; color: #6B7280;">{{ Str::limit($issue->company_name, 15) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 10px 12px;">
                                    @php
                                        $priorityClasses = [
                                            'urgent' => 'priority-critical',
                                            'high' => 'priority-high',
                                            'medium' => 'priority-medium',
                                            'low' => 'priority-low',
                                        ];
                                        $priorityClass = $priorityClasses[$issue->priority] ?? 'priority-medium';
                                    @endphp
                                    <span class="priority-badge {{ $priorityClass }}" style="font-size: 10px; padding: 3px 8px;">{{ ucfirst($issue->priority) }}</span>
                                </td>
                                <td style="padding: 10px 12px;">
                                    @if($issue->status == 'pending')
                                        <span class="status-badge" style="background: #FEE2E2; color: #991B1B; font-size: 10px; padding: 3px 8px;">Pending</span>
                                    @elseif($issue->status == 'in_progress')
                                        <span class="status-badge" style="background: #FEF3C7; color: #92400E; font-size: 10px; padding: 3px 8px;">In Progress</span>
                                    @elseif($issue->status == 'resolved')
                                        <span class="status-badge" style="background: #DCFCE7; color: #166534; font-size: 10px; padding: 3px 8px;">Resolved</span>
                                        @if($issue->resolved_at)
                                        <div style="font-size: 10px; color: #10B981; margin-top: 2px;">{{ $issue->resolved_at->format('M d') }}</div>
                                        @endif
                                    @elseif($issue->status == 'closed')
                                        <span class="status-badge" style="background: #E5E7EB; color: #374151; font-size: 10px; padding: 3px 8px;">Closed</span>
                                    @else
                                        <span class="status-badge" style="background: #E5E7EB; color: #374151; font-size: 10px; padding: 3px 8px;">{{ ucfirst($issue->status) }}</span>
                                    @endif
                                </td>
                                <td style="padding: 10px 12px; font-size: 12px;">{{ $issue->created_at->format('M d, Y') }}</td>
                                <td style="padding: 10px 12px;">
                                    <div class="action-buttons" style="gap: 4px; justify-content: center;">
                                        <button class="btn-action btn-view" style="width: 26px; height: 26px; font-size: 11px; display: flex; align-items: center; justify-content: center;" onclick="viewRoomIssueDetails('{{ $issue->id }}')"><i class="fas fa-eye"></i></button>
                                        @if($issue->photo_path)
                                        <a href="{{ asset('storage/' . $issue->photo_path) }}" target="_blank" class="btn-action btn-edit" style="width: 26px; height: 26px; font-size: 11px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-image"></i></a>
                                        @endif
                                        <button class="btn-action" style="background: #10B981; color: white; width: 26px; height: 26px; font-size: 11px; display: flex; align-items: center; justify-content: center;" onclick="updateIssueStatus('{{ $issue->id }}')"><i class="fas fa-check"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                    <i class="fas fa-check-circle" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                                    No room issues reported yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Project Progress Page -->
            <div id="project-progress" class="page-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Project Progress Updates</h2>
                    <p style="color: #6B7280; font-size: 14px;">View and manage project progress updates submitted by startups</p>
                </div>

                <!-- Stats Overview -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #EDE9FE, #DDD6FE); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-chart-line" style="color: #7C3AED; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="totalProgressCount" style="font-size: 28px; font-weight: 700; color: #7C3AED;">{{ $progressUpdates->count() ?? 0 }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Total Updates</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #FEF3C7, #FCD34D); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock" style="color: #D97706; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #D97706;">{{ $progressUpdates->where('status', 'submitted')->count() ?? 0 }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Pending Review</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #DBEAFE, #93C5FD); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-eye" style="color: #2563EB; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #2563EB;">{{ $progressUpdates->where('status', 'reviewed')->count() ?? 0 }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Reviewed</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #D1FAE5, #6EE7B7); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check-circle" style="color: #059669; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #059669;">{{ $progressUpdates->where('status', 'acknowledged')->count() ?? 0 }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Acknowledged</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter & Actions -->
                <div class="filter-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <div class="filter-group">
                            <span class="filter-label">Filter by Status:</span>
                            <select class="filter-select" id="progressStatusFilter" onchange="filterProgress()">
                                <option value="all">All Status</option>
                                <option value="submitted">Pending Review</option>
                                <option value="reviewed">Reviewed</option>
                                <option value="acknowledged">Acknowledged</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <span class="filter-label">Milestone Type:</span>
                            <select class="filter-select" id="progressTypeFilter" onchange="filterProgress()">
                                <option value="all">All Types</option>
                                <option value="development">Product Development</option>
                                <option value="funding">Funding/Investment</option>
                                <option value="partnership">Partnership</option>
                                <option value="launch">Product Launch</option>
                                <option value="achievement">Achievement/Award</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Progress Updates Table -->
                <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden;">
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #F9FAFB; border-bottom: 1px solid #E5E7EB;">
                                    <th style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase;">Startup</th>
                                    <th style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase;">Title</th>
                                    <th style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase;">Type</th>
                                    <th style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase;">Status</th>
                                    <th style="text-align: left; padding: 14px 16px; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase;">Submitted</th>
                                    <th style="text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="progressTableBody">
                                @forelse($progressUpdates ?? [] as $progress)
                                @php /** @var \App\Models\StartupSubmission $progress */ @endphp
                                <tr class="progress-row" data-status="{{ $progress->status }}" data-type="{{ $progress->milestone_type }}" style="border-bottom: 1px solid #F3F4F6;">
                                    <td style="padding: 16px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #7B1D3A, #A62450); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 12px;">
                                                {{ strtoupper(substr($progress->startup->company_name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 600; color: #1F2937;">{{ $progress->startup->company_name }}</div>
                                                <div style="font-size: 12px; color: #6B7280;">{{ $progress->startup->startup_code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 16px;">
                                        <div style="font-weight: 500; color: #1F2937;">{{ Str::limit($progress->title, 40) }}</div>
                                        <div style="font-size: 12px; color: #6B7280; margin-top: 2px;">{{ Str::limit($progress->description, 60) }}</div>
                                    </td>
                                    <td style="padding: 16px;">
                                        @php
                                            $typeColors = [
                                                'development' => ['bg' => '#DBEAFE', 'color' => '#1E40AF'],
                                                'funding' => ['bg' => '#D1FAE5', 'color' => '#065F46'],
                                                'partnership' => ['bg' => '#FEF3C7', 'color' => '#92400E'],
                                                'launch' => ['bg' => '#FCE7F3', 'color' => '#9D174D'],
                                                'achievement' => ['bg' => '#EDE9FE', 'color' => '#5B21B6'],
                                                'other' => ['bg' => '#F3F4F6', 'color' => '#374151'],
                                            ];
                                            $typeStyle = $typeColors[$progress->milestone_type] ?? $typeColors['other'];
                                        @endphp
                                        <span style="display: inline-block; padding: 4px 10px; background: {{ $typeStyle['bg'] }}; color: {{ $typeStyle['color'] }}; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                            {{ $progress->milestone_type_label }}
                                        </span>
                                    </td>
                                    <td style="padding: 16px;">
                                        @if($progress->status === 'submitted')
                                            <span style="display: inline-block; padding: 4px 10px; background: #FEF3C7; color: #92400E; border-radius: 20px; font-size: 11px; font-weight: 600;">Pending Review</span>
                                        @elseif($progress->status === 'reviewed')
                                            <span style="display: inline-block; padding: 4px 10px; background: #DBEAFE; color: #1E40AF; border-radius: 20px; font-size: 11px; font-weight: 600;">Reviewed</span>
                                        @else
                                            <span style="display: inline-block; padding: 4px 10px; background: #D1FAE5; color: #065F46; border-radius: 20px; font-size: 11px; font-weight: 600;">Acknowledged</span>
                                        @endif
                                    </td>
                                    <td style="padding: 16px; font-size: 13px; color: #6B7280;">
                                        {{ $progress->created_at->format('M d, Y') }}
                                        <div style="font-size: 11px; color: #9CA3AF;">{{ $progress->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td style="padding: 16px; text-align: center;">
                                        <div style="display: flex; gap: 8px; justify-content: center;">
                                            <button onclick="viewProgressDetails({{ $progress->id }})" style="width: 32px; height: 32px; background: #F3F4F6; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" title="View Details">
                                                <i class="fas fa-eye" style="color: #6B7280;"></i>
                                            </button>
                                            <button onclick="respondToProgress({{ $progress->id }})" style="width: 32px; height: 32px; background: #EDE9FE; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" title="Respond">
                                                <i class="fas fa-comment-dots" style="color: #7C3AED;"></i>
                                            </button>
                                            @if($progress->file_path)
                                            <a href="{{ Storage::url($progress->file_path) }}" target="_blank" style="width: 32px; height: 32px; background: #DBEAFE; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; text-decoration: none;" title="Download Attachment">
                                                <i class="fas fa-paperclip" style="color: #2563EB;"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 60px; color: #9CA3AF;">
                                        <i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.3;"></i>
                                        <div style="font-size: 16px; font-weight: 500; margin-bottom: 4px;">No progress updates yet</div>
                                        <div style="font-size: 13px;">Progress updates from startups will appear here</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Progress Detail Modal -->
            <div id="progressDetailModal" class="modal" style="display: none;">
                <div class="modal-content" style="max-width: 600px;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #7B1D3A.0%, #A62450 100%); color: white; padding: 20px 24px;">
                        <h3 style="font-size: 18px; font-weight: 700; margin: 0;"><i class="fas fa-chart-line" style="margin-right: 10px;"></i>Progress Update Details</h3>
                        <button onclick="closeModal('progressDetailModal')" style="background: rgba(255,255,255,0.2); border: none; width: 32px; height: 32px; border-radius: 8px; color: white; cursor: pointer;"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-body" id="progressDetailContent" style="padding: 24px;">
                        <!-- Content will be loaded dynamically -->
                    </div>
                </div>
            </div>

            <!-- Progress Respond Modal -->
            <div id="progressRespondModal" class="modal" style="display: none;">
                <div class="modal-content" style="max-width: 500px;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #7C3AED 0%, #6D28D9 100%); color: white; padding: 20px 24px;">
                        <h3 style="font-size: 18px; font-weight: 700; margin: 0;"><i class="fas fa-comment-dots" style="margin-right: 10px;"></i>Respond to Progress</h3>
                        <button onclick="closeModal('progressRespondModal')" style="background: rgba(255,255,255,0.2); border: none; width: 32px; height: 32px; border-radius: 8px; color: white; cursor: pointer;"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-body" style="padding: 24px;">
                        <form id="progressRespondForm" onsubmit="submitProgressResponse(event)">
                            <input type="hidden" id="respondProgressId" name="progress_id">
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Update Status</label>
                                <select id="respondProgressStatus" name="status" style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 10px; font-size: 14px;">
                                    <option value="submitted">Pending Review</option>
                                    <option value="reviewed">Reviewed</option>
                                    <option value="acknowledged">Acknowledged</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Admin Comment</label>
                                <textarea id="respondProgressComment" name="admin_comment" rows="4" style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 10px; font-size: 14px; resize: vertical;" placeholder="Add your feedback or comment..."></textarea>
                            </div>
                            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                                <button type="button" onclick="closeModal('progressRespondModal')" style="padding: 12px 24px; background: #F3F4F6; color: #374151; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">Cancel</button>
                                <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #7C3AED 0%, #6D28D9 100%); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                                    <i class="fas fa-paper-plane" style="margin-right: 8px;"></i>Submit Response
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Manage Startups Page -->
            <div id="manage-startups" class="page-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Manage Startup Accounts</h2>
                    <p style="color: #6B7280; font-size: 14px;">Create and manage startup portal login accounts for incubatees</p>
                </div>

                <!-- Stats Overview -->
                <div id="startupStatsGrid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #F3E8FF, #DDD6FE); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-building" style="color: #7B1D3A; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="totalStartupsCount" style="font-size: 28px; font-weight: 700; color: #1F2937;">0</div>
                                <div style="font-size: 13px; color: #6B7280;">Total Startups</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #D1FAE5, #6EE7B7); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check-circle" style="color: #059669; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="activeStartupsCount" style="font-size: 28px; font-weight: 700; color: #059669;">0</div>
                                <div style="font-size: 13px; color: #6B7280;">Active Accounts</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #FEF3C7, #FCD34D); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-file-contract" style="color: #D97706; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="pendingMoaCount" style="font-size: 28px; font-weight: 700; color: #D97706;">0</div>
                                <div style="font-size: 13px; color: #6B7280;">Pending MOA</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #FEE2E2, #FCA5A5); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-ban" style="color: #DC2626; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="inactiveStartupsCount" style="font-size: 28px; font-weight: 700; color: #DC2626;">0</div>
                                <div style="font-size: 13px; color: #6B7280;">Inactive Accounts</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="filter-bar" style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <div class="filter-group">
                            <span class="filter-label">Status:</span>
                            <select class="filter-select" onchange="filterStartups()" id="startupStatusFilter">
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <span class="filter-label">MOA Status:</span>
                            <select class="filter-select" onchange="filterStartups()" id="startupMoaFilter">
                                <option value="all">All</option>
                                <option value="none">None</option>
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                        <div class="filter-search">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search startups..." onkeyup="searchStartups()" id="startupSearchInput">
                        </div>
                    </div>
                    <button onclick="openCreateStartupModal()" style="padding: 10px 20px; background: linear-gradient(135deg, #7B1D3A, #A62450); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-plus"></i> Create Startup Account
                    </button>
                </div>

                <!-- Startups Table -->
                <div class="table-card">
                    <div class="table-header">
                        <h3 class="table-title">Startup Accounts</h3>
                        <button onclick="exportStartups()" style="padding: 8px 16px; background: white; color: #7B1D3A; border: 2px solid #7B1D3A; border-radius: 6px; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                        <thead>
                            <tr>
                                <th style="width: 140px; min-width: 140px; text-align: left; padding: 14px 16px; white-space: nowrap;">Startup Code</th>
                                <th style="min-width: 220px; text-align: left; padding: 14px 16px;">Company Info</th>
                                <th style="min-width: 150px; text-align: left; padding: 14px 16px;">Contact Person</th>
                                <th style="width: 110px; min-width: 110px; text-align: center; padding: 14px 16px; white-space: nowrap;">MOA Status</th>
                                <th style="width: 100px; min-width: 100px; text-align: center; padding: 14px 16px; white-space: nowrap;">Status</th>
                                <th style="width: 180px; min-width: 180px; text-align: center; padding: 14px 16px; white-space: nowrap;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="startupsTableBody">
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: #6B7280;">
                                    <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 12px; display: block;"></i>
                                    Loading startups...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            <!-- Create/Edit Startup Modal -->
            <div id="startupModal" class="modal" style="display: none;">
                <div class="modal-content" style="max-width: 580px; width: 95%; border-radius: 16px; overflow: hidden;">
                    <!-- Modal Header with Gradient -->
                    <div style="background: linear-gradient(135deg, #7B1D3A 0%, #A62450 100%); padding: 20px 24px; position: relative;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-building" style="font-size: 20px; color: white;"></i>
                            </div>
                            <div>
                                <h3 id="startupModalTitle" style="font-size: 18px; font-weight: 700; color: white; margin: 0;">Create Startup Account</h3>
                                <p style="color: rgba(255,255,255,0.8); font-size: 12px; margin-top: 2px;">Fill in details to create a new startup account</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeStartupModal()" style="position: absolute; top: 16px; right: 16px; width: 32px; height: 32px; background: rgba(255,255,255,0.2); border: none; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
                            <i class="fas fa-times" style="color: white; font-size: 14px;"></i>
                        </button>
                    </div>

                    <form id="startupForm" onsubmit="saveStartup(event)">
                        <input type="hidden" id="startupId" name="startup_id">
                        <div style="padding: 20px 24px; max-height: 60vh; overflow-y: auto;">
                            <!-- Company Info Section -->
                            <div style="margin-bottom: 20px;">
                                <h4 style="font-size: 12px; font-weight: 600; color: #7B1D3A; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-briefcase"></i> Company Information
                                </h4>
                                <div style="display: flex; flex-direction: column; gap: 12px;">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 4px;">Company Name <span style="color: #EF4444;">*</span></label>
                                        <div style="position: relative;">
                                            <i class="fas fa-building" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9CA3AF; font-size: 13px;"></i>
                                            <input type="text" id="companyName" name="company_name" required placeholder="Enter company name" style="width: 100%; padding: 10px 10px 10px 36px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 13px; transition: all 0.3s; box-sizing: border-box;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Info Section -->
                            <div style="margin-bottom: 20px;">
                                <h4 style="font-size: 12px; font-weight: 600; color: #7B1D3A; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-user"></i> Contact Information
                                </h4>
                                <div style="display: flex; flex-direction: column; gap: 12px;">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 4px;">Contact Person <span style="color: #EF4444;">*</span></label>
                                        <div style="position: relative;">
                                            <i class="fas fa-user-tie" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9CA3AF; font-size: 13px;"></i>
                                            <input type="text" id="contactPerson" name="contact_person" required placeholder="Full name" style="width: 100%; padding: 10px 10px 10px 36px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 13px; transition: all 0.3s; box-sizing: border-box;">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 4px;">Email Address <span style="color: #EF4444;">*</span></label>
                                        <div style="position: relative;">
                                            <i class="fas fa-envelope" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9CA3AF; font-size: 13px;"></i>
                                            <input type="email" id="startupEmail" name="email" required placeholder="email@company.com" style="width: 100%; padding: 10px 10px 10px 36px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 13px; transition: all 0.3s; box-sizing: border-box;">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 4px;">Phone Number</label>
                                        <div style="position: relative;">
                                            <i class="fas fa-phone" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9CA3AF; font-size: 13px;"></i>
                                            <input type="text" id="startupPhone" name="phone" placeholder="e.g., +63 912 345 6789" style="width: 100%; padding: 10px 10px 10px 36px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 13px; transition: all 0.3s; box-sizing: border-box;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Credentials Section (shown after creation) -->
                            <div id="credentialsSection" style="display: none; background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%); padding: 16px; border-radius: 10px; border: 2px solid #86EFAC;">
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                    <div style="width: 36px; height: 36px; background: #166534; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-key" style="color: white; font-size: 14px;"></i>
                                    </div>
                                    <div>
                                        <h4 style="color: #166534; font-size: 14px; font-weight: 700; margin: 0;">Startup Code Generated!</h4>
                                        <p style="color: #15803D; font-size: 11px; margin: 0;">Share this code with the startup</p>
                                    </div>
                                </div>
                                <div style="background: white; padding: 14px; border-radius: 8px; border: 1px solid #BBF7D0; text-align: center;">
                                    <label style="font-size: 10px; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Startup Code</label>
                                    <div id="generatedCode" style="font-family: 'Courier New', monospace; font-size: 20px; font-weight: 700; color: #166534; margin-top: 4px;"></div>
                                </div>
                                <button type="button" onclick="copyStartupCode()" style="margin-top: 12px; width: 100%; padding: 10px; background: #166534; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.3s;">
                                    <i class="fas fa-copy"></i> Copy Code
                                </button>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div style="padding: 16px 24px; background: #F9FAFB; border-top: 1px solid #E5E7EB; display: flex; justify-content: flex-end; gap: 10px;">
                            <button type="button" onclick="closeStartupModal()" style="padding: 10px 18px; background: white; color: #6B7280; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                                Cancel
                            </button>
                            <button type="submit" id="startupSubmitBtn" style="padding: 10px 20px; background: linear-gradient(135deg, #7B1D3A 0%, #A62450 100%); color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.3s;">
                                <i class="fas fa-plus-circle"></i> Create Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- View Startup Details Modal -->
            <div id="viewStartupModal" class="modal" style="display: none;">
                <div class="modal-content" style="max-width: 700px; border-radius: 16px; overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #7B1D3A 0%, #A62450 100%); padding: 24px 28px; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-building" style="font-size: 20px; color: white;"></i>
                            </div>
                            <h3 style="font-size: 20px; font-weight: 700; color: white; margin: 0;">Startup Details</h3>
                        </div>
                        <button type="button" onclick="closeViewStartupModal()" style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); border: none; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-times" style="color: white; font-size: 16px;"></i>
                        </button>
                    </div>
                    <div class="modal-body" id="startupDetailsContent" style="padding: 28px;">
                        <!-- Content loaded dynamically -->
                    </div>
                </div>
            </div>

            <!-- Reset Startup Password Modal -->
            <div id="changeStartupPasswordModal" class="modal" style="display: none;">
                <div class="modal-content" style="max-width: 450px; border-radius: 16px; overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); padding: 20px 24px; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 42px; height: 42px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-key" style="font-size: 18px; color: white;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 18px; font-weight: 700; color: white; margin: 0;">Reset Password</h3>
                                <p id="changePasswordStartupName" style="font-size: 12px; color: rgba(255,255,255,0.8); margin: 0;"></p>
                            </div>
                        </div>
                        <button type="button" onclick="closeChangePasswordModal()" style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border: none; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                            <i class="fas fa-times" style="color: white; font-size: 14px;"></i>
                        </button>
                    </div>
                    <div class="modal-body" style="padding: 24px;">
                        <input type="hidden" id="changePasswordStartupId">
                        <input type="hidden" id="changePasswordStartupEmail">

                        <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px;">
                            <div style="display: flex; align-items: flex-start; gap: 10px;">
                                <i class="fas fa-info-circle" style="color: #2563EB; margin-top: 2px;"></i>
                                <div style="font-size: 13px; color: #1E40AF;">
                                    This will generate a temporary password. The startup will be required to set their own new password upon next login.
                                </div>
                            </div>
                        </div>

                        <!-- Error Message -->
                        <div id="passwordResetError" style="display: none; background: #FEE2E2; border: 1px solid #FECACA; border-radius: 10px; padding: 12px 14px; margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-times-circle" style="color: #DC2626;"></i>
                                <span style="font-size: 13px; color: #991B1B;">Error message here</span>
                            </div>
                        </div>

                        <!-- Success Message with Temp Password -->
                        <div id="passwordResetSuccess" style="display: none; background: #D1FAE5; border: 1px solid #A7F3D0; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                <i class="fas fa-check-circle" style="color: #059669; font-size: 18px;"></i>
                                <span style="font-size: 14px; font-weight: 600; color: #065F46;">Password Reset Successful!</span>
                            </div>
                            <div style="background: white; border: 2px dashed #A7F3D0; border-radius: 8px; padding: 12px; text-align: center;">
                                <div style="font-size: 11px; color: #6B7280; margin-bottom: 6px;">Temporary Password</div>
                                <div id="tempPasswordDisplay" style="font-size: 18px; font-weight: 700; font-family: monospace; color: #059669; letter-spacing: 1px;"></div>
                            </div>
                            <button type="button" onclick="copyTempPassword()" style="width: 100%; margin-top: 12px; padding: 10px; background: #059669; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                                <i class="fas fa-copy"></i> Copy to Clipboard
                            </button>
                            <div style="font-size: 11px; color: #6B7280; margin-top: 10px; text-align: center;">
                                Share this password securely with the startup. They will be prompted to change it on their next login.
                            </div>
                        </div>
                    </div>
                    <div id="resetPasswordFooter" class="modal-footer" style="padding: 16px 24px; background: #F9FAFB; border-top: 1px solid #E5E7EB; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" onclick="closeChangePasswordModal()" style="padding: 10px 20px; background: #F3F4F6; color: #374151; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#E5E7EB'" onmouseout="this.style.background='#F3F4F6'">Cancel</button>
                        <button type="button" id="confirmResetBtn" onclick="confirmPasswordReset()" style="padding: 10px 20px; background: linear-gradient(135deg, #4F46E5, #7C3AED); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                            <i class="fas fa-sync-alt"></i> Reset Password
                        </button>
                    </div>
                </div>
            </div>

            <!-- Digital Records Page -->
            <div id="digital-records" class="page-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Digital Records Management</h2>
                    <p style="color: #6B7280; font-size: 14px;">Organize and manage all your documents, files, and records in a secure digital repository</p>
                </div>

                <!-- Stats Overview -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #DBEAFE, #93C5FD); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-folder" style="color: #2563EB; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="dr-total-folders" style="font-size: 28px; font-weight: 700; color: #1F2937;">--</div>
                                <div style="font-size: 13px; color: #6B7280;">Total Folders</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #D1FAE5, #6EE7B7); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-file" style="color: #059669; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="dr-total-files" style="font-size: 28px; font-weight: 700; color: #059669;">--</div>
                                <div style="font-size: 13px; color: #6B7280;">Total Files</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #EDE9FE, #C4B5FD); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-hdd" style="color: #7C3AED; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="dr-storage-used" style="font-size: 28px; font-weight: 700; color: #7C3AED;">--</div>
                                <div style="font-size: 13px; color: #6B7280;">Storage Used</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #FEF3C7, #FCD34D); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock" style="color: #D97706; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="dr-recent-uploads" style="font-size: 28px; font-weight: 700; color: #D97706;">--</div>
                                <div style="font-size: 13px; color: #6B7280;">Recent Uploads (7d)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="filter-bar" style="margin-bottom: 24px;">
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <button class="filter-btn secondary" onclick="goBackFolder()" id="back-btn" style="display: none;">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <span id="current-path" style="font-size: 14px; color: #6B7280; font-weight: 500;">
                            <i class="fas fa-home"></i> Root
                        </span>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" placeholder="Search files..." class="filter-input" style="padding: 8px 12px; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 14px; width: 250px;" onkeyup="searchFiles(this.value)">
                        <button class="filter-btn secondary" onclick="toggleViewMode()">
                            <i class="fas fa-th" id="view-icon"></i>
                        </button>
                        <button class="filter-btn" onclick="openNewFolderModal()">
                            <i class="fas fa-folder-plus"></i> New Folder
                        </button>
                        <button class="filter-btn" onclick="openUploadFileModal()">
                            <i class="fas fa-upload"></i> Upload File
                        </button>
                    </div>
                </div>

                <!-- File Browser - Grid View -->
                <div id="grid-view" class="file-grid">
                    <!-- Content will be loaded dynamically -->
                    <div style="grid-column: span 7; text-align: center; padding: 50px; color: #9CA3AF;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 50px; margin-bottom: 16px;"></i>
                        <p style="font-size: 16px;">Loading documents...</p>
                    </div>
                </div>

                <!-- File Browser - List View -->
                <div id="list-view" style="display: none;">
                    <div class="table-card">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 40%;">Name</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Modified</th>
                                    <th style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="list-view-body">
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 50px; color: #9CA3AF;">
                                        <i class="fas fa-spinner fa-spin" style="font-size: 30px; margin-bottom: 16px;"></i>
                                        <p>Loading documents...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Activity Log Page -->
            <div id="activity-log" class="page-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Activity Log</h2>
                    <p style="color: #6B7280; font-size: 14px;">View all system activities, notifications, and recent events</p>
                </div>

                <!-- Filter Bar -->
                <div class="filter-bar" style="margin-bottom: 24px;">
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <div class="filter-group">
                            <span class="filter-label">Type:</span>
                            <select class="filter-select" id="activityTypeFilter" onchange="filterActivityLog()">
                                <option value="all">All Activities</option>
                                <option value="booking">Bookings</option>
                                <option value="startup">Startups</option>
                                <option value="issue">Issues</option>
                                <option value="intern">Interns</option>
                                <option value="document">Documents</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <span class="filter-label">Status:</span>
                            <select class="filter-select" id="activityStatusFilter" onchange="filterActivityLog()">
                                <option value="all">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="filter-search">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search activities..." id="activitySearchInput" onkeyup="filterActivityLog()">
                        </div>
                    </div>
                    <button class="filter-btn secondary" onclick="refreshActivityLog()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>

                <!-- Activity Stats -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #DBEAFE, #93C5FD); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-list" style="color: #2563EB; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="totalActivitiesCount" style="font-size: 28px; font-weight: 700; color: #1F2937;">0</div>
                                <div style="font-size: 13px; color: #6B7280;">Total Activities</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #FEF3C7, #FCD34D); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock" style="color: #D97706; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="pendingActivitiesCount" style="font-size: 28px; font-weight: 700; color: #D97706;">0</div>
                                <div style="font-size: 13px; color: #6B7280;">Pending Actions</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #D1FAE5, #6EE7B7); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check-circle" style="color: #059669; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="completedActivitiesCount" style="font-size: 28px; font-weight: 700; color: #059669;">0</div>
                                <div style="font-size: 13px; color: #6B7280;">Completed Today</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #FEE2E2, #FCA5A5); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-exclamation-triangle" style="color: #DC2626; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="urgentActivitiesCount" style="font-size: 28px; font-weight: 700; color: #DC2626;">0</div>
                                <div style="font-size: 13px; color: #6B7280;">Needs Attention</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Log Table -->
                <div class="table-card">
                    <div class="table-header">
                        <h3 class="table-title">Recent Activities <span style="font-weight: 400; font-size: 13px; color: #6B7280;">(Last 7 days)</span></h3>
                    </div>
                    <div style="overflow-x: auto;">
                        <table style="min-width: 900px;">
                            <thead>
                                <tr>
                                    <th style="width: 50px;"></th>
                                    <th>Activity</th>
                                    <th>Type</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                    <th>Date & Time</th>
                                    <th style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="activityLogBody">
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 60px; color: #9CA3AF;">
                                        <i class="fas fa-spinner fa-spin" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                                        Loading activities...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Scheduler Page -->
            <div id="scheduler" class="page-content">
                <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Scheduler & Events</h2>
                        <p style="color: #6B7280; font-size: 14px;">Manage agency bookings, events, and calendar</p>
                    </div>

                </div>

                <!-- Stats Overview -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #FEF3C7, #FCD34D); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock" style="color: #D97706; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="pendingBookingsCount" style="font-size: 28px; font-weight: 700; color: #D97706;">{{ $pendingBookings ?? 0 }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Pending Requests</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #DBEAFE, #93C5FD); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-check" style="color: #2563EB; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #2563EB;">{{ $todayBookings ?? 0 }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Today's Bookings</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #D1FAE5, #6EE7B7); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-alt" style="color: #059669; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #059669;">{{ isset($upcomingBookings) ? $upcomingBookings->count() : 0 }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Upcoming Events</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #EDE9FE, #C4B5FD); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-building" style="color: #7C3AED; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 28px; font-weight: 700; color: #7C3AED;">{{ isset($allBookings) ? $allBookings->where('status', 'approved')->count() : 0 }}</div>
                                <div style="font-size: 13px; color: #6B7280;">Total Approved</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Tabs -->
                <div class="filter-bar" style="margin-bottom: 24px;">
                    <div class="filter-tabs" style="margin: 0;">
                        <button class="filter-tab active" onclick="switchBookingTab('pending')">
                            <i class="fas fa-clock"></i> Pending Requests <span class="badge-count" id="pendingBadge">{{ $pendingBookings ?? 0 }}</span>
                        </button>
                        <button class="filter-tab" onclick="switchBookingTab('calendar')">
                            <i class="fas fa-calendar"></i> Calendar View
                        </button>
                        <button class="filter-tab" onclick="switchBookingTab('events')">
                            <i class="fas fa-calendar-plus"></i> Events
                        </button>
                        <button class="filter-tab" onclick="switchBookingTab('all')">
                            <i class="fas fa-list"></i> All Bookings
                        </button>
                    </div>
                </div>

                <!-- Pending Bookings Tab -->
                <div id="pendingBookingsTab" class="booking-tab-content">
                    <!-- Approved but not emailed Alert -->
                    @php
                        $notEmailedCount = $allBookings->where('status', 'approved')->where('admin_emailed', false)->count();
                    @endphp
                    @if($notEmailedCount > 0)
                    <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: white; padding: 16px 20px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 24px;"></i>
                        <div>
                            <div style="font-weight: 600; font-size: 14px;">Email Notification Pending</div>
                            <div style="font-size: 12px; opacity: 0.9;">{{ $notEmailedCount }} approved booking(s) awaiting email notification to booker</div>
                        </div>
                        <button onclick="switchBookingTab('all')" style="margin-left: auto; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 12px;">
                            View All <i class="fas fa-arrow-right" style="margin-left: 4px;"></i>
                        </button>
                    </div>
                    @endif

                    @php
                        $emailedCount = $allBookings->where('admin_emailed', true)->count();
                    @endphp
                    @if($emailedCount > 0)
                    <div id="emailNotificationBanner" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; padding: 16px 20px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; position: relative;">
                        <i class="fas fa-envelope-circle-check" style="font-size: 24px;"></i>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 14px;">Email Notifications Sent</div>
                            <div style="font-size: 12px; opacity: 0.9;">{{ $emailedCount }} booking(s) have been notified via email</div>
                        </div>
                        <button onclick="this.parentElement.remove()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <script>
                        // Auto-dismiss email notification banner after 5 seconds
                        setTimeout(function() {
                            const banner = document.getElementById('emailNotificationBanner');
                            if (banner) {
                                banner.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                                banner.style.opacity = '0';
                                banner.style.transform = 'translateY(-20px)';
                                setTimeout(() => banner.remove(), 500);
                            }
                        }, 5000);
                    </script>
                    @endif

                    <div class="table-card" style="overflow-x: auto;">
                        <table style="min-width: 1000px;">
                            <thead>
                                <tr>
                                    <th style="min-width: 130px; white-space: nowrap;">Date & Time</th>
                                    <th style="min-width: 120px; white-space: nowrap;">Agency</th>
                                    <th style="min-width: 120px; white-space: nowrap;">Purpose</th>
                                    <th style="min-width: 120px; white-space: nowrap;">Contact Person</th>
                                    <th style="min-width: 140px; white-space: nowrap;">Contact Info</th>
                                    <th style="min-width: 150px; white-space: nowrap;">Notes</th>
                                    <th style="min-width: 80px; white-space: nowrap;">Attachment</th>
                                    <th style="min-width: 120px; white-space: nowrap;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pendingBookingsBody">
                                @forelse($allBookings->where('status', 'pending') ?? [] as $booking)
                                <tr id="booking-row-{{ $booking->id }}">
                                    <td>
                                        <div style="font-weight: 600;">{{ $booking->booking_date->format('M d, Y') }}</div>
                                        <div style="font-size: 12px; color: #6B7280;">{{ $booking->formatted_time }}</div>
                                        @if($booking->admin_emailed)
                                        <span style="background: #D1FAE5; color: #059669; font-size: 10px; padding: 2px 6px; border-radius: 4px; margin-top: 4px; display: inline-block;">
                                            <i class="fas fa-envelope-circle-check"></i> Emailed
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; color: #7B1D3A;">{{ $booking->agency_name }}</div>
                                    </td>
                                    <td>{{ $booking->event_name }}</td>
                                    <td>{{ $booking->contact_person }}</td>
                                    <td>
                                        <div style="font-size: 12px;"><i class="fas fa-phone" style="color: #6B7280; margin-right: 4px;"></i> {{ $booking->phone }}</div>
                                        <div style="font-size: 12px;"><i class="fas fa-envelope" style="color: #6B7280; margin-right: 4px;"></i> {{ $booking->email }}</div>
                                    </td>
                                    <td style="max-width: 200px;">
                                        <div style="font-size: 12px; color: #6B7280; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $booking->purpose }}">
                                            {{ $booking->purpose ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($booking->attachment_path)
                                        <a href="{{ asset('storage/' . $booking->attachment_path) }}" target="_blank" class="btn-action btn-view" title="View Attachment" style="background: #DBEAFE; color: #2563EB;">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        @else
                                        <span style="color: #9CA3AF; font-size: 12px;">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 4px; align-items: center;">
                                            <button class="btn-action btn-view" onclick="openBookingActionModal({{ $booking->id }}, '{{ addslashes($booking->agency_name) }}', '{{ $booking->booking_date->format('M d, Y') }}', '{{ $booking->formatted_time }}', '{{ addslashes($booking->event_name) }}', '{{ addslashes($booking->contact_person) }}', '{{ $booking->email }}', '{{ $booking->phone }}', '{{ addslashes($booking->purpose ?? 'N/A') }}', '{{ $booking->attachment_path ? asset('storage/' . $booking->attachment_path) : '' }}', '{{ $booking->status }}', {{ $booking->admin_emailed ? 'true' : 'false' }})" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn-action btn-edit" onclick="openBookingActionModal({{ $booking->id }}, '{{ addslashes($booking->agency_name) }}', '{{ $booking->booking_date->format('M d, Y') }}', '{{ $booking->formatted_time }}', '{{ addslashes($booking->event_name) }}', '{{ addslashes($booking->contact_person) }}', '{{ $booking->email }}', '{{ $booking->phone }}', '{{ addslashes($booking->purpose ?? 'N/A') }}', '{{ $booking->attachment_path ? asset('storage/' . $booking->attachment_path) : '' }}', '{{ $booking->status }}', {{ $booking->admin_emailed ? 'true' : 'false' }})" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn-action btn-delete" onclick="openBookingActionModal({{ $booking->id }}, '{{ addslashes($booking->agency_name) }}', '{{ $booking->booking_date->format('M d, Y') }}', '{{ $booking->formatted_time }}', '{{ addslashes($booking->event_name) }}', '{{ addslashes($booking->contact_person) }}', '{{ $booking->email }}', '{{ $booking->phone }}', '{{ addslashes($booking->purpose ?? 'N/A') }}', '{{ $booking->attachment_path ? asset('storage/' . $booking->attachment_path) : '' }}', '{{ $booking->status }}', {{ $booking->admin_emailed ? 'true' : 'false' }})" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr id="noPendingRow">
                                    <td colspan="8" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                        <i class="fas fa-check-circle" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                                        No pending booking requests
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination for Pending Bookings -->
                    <div id="pendingBookingsPagination" class="pagination-controls" style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding: 12px 16px; background: #F9FAFB; border-radius: 8px;">
                        <div style="font-size: 14px; color: #6B7280;">
                            Showing <span id="pendingShowingStart">1</span> to <span id="pendingShowingEnd">8</span> of <span id="pendingTotalItems">0</span> entries
                        </div>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <button id="pendingPrevBtn" onclick="pendingPrevPage()" style="padding: 8px 12px; border: 1px solid #D1D5DB; border-radius: 6px; background: white; cursor: pointer; font-size: 14px;">
                                <i class="fas fa-chevron-left"></i> Previous
                            </button>
                            <span id="pendingPageInfo" style="padding: 8px 16px; background: #7B1D3A; color: white; border-radius: 6px; font-weight: 600;">1 / 1</span>
                            <button id="pendingNextBtn" onclick="pendingNextPage()" style="padding: 8px 12px; border: 1px solid #D1D5DB; border-radius: 6px; background: white; cursor: pointer; font-size: 14px;">
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Calendar View Tab -->
                <div id="calendarViewTab" class="booking-tab-content" style="display: none;">
                    <div class="calendar-layout">
                        <!-- Left Sidebar -->
                        <div class="calendar-sidebar">
                            <!-- Mini Calendar -->
                            <div class="mini-calendar-card">
                                <div class="mini-calendar-header">
                                    <button class="cal-nav-btn" onclick="schedulerPrevMonth()"><i class="fas fa-chevron-left"></i></button>
                                    <span class="mini-calendar-title" id="schedulerMonthTitle">January 2026</span>
                                    <button class="cal-nav-btn" onclick="schedulerNextMonth()"><i class="fas fa-chevron-right"></i></button>
                                </div>
                                <div class="mini-calendar">
                                    <div class="mini-calendar-days">
                                        <div class="day-label">Su</div>
                                        <div class="day-label">Mo</div>
                                        <div class="day-label">Tu</div>
                                        <div class="day-label">We</div>
                                        <div class="day-label">Th</div>
                                        <div class="day-label">Fr</div>
                                        <div class="day-label">Sa</div>
                                    </div>
                                    <div class="mini-calendar-grid" id="schedulerMiniCalendar">
                                        <!-- Calendar days will be generated by JavaScript -->
                                    </div>
                                </div>
                            </div>

                            <!-- Upcoming Bookings -->
                            <div class="upcoming-events-card">
                                <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 12px; color: #1F2937;">Upcoming Bookings</h4>
                                <div class="upcoming-events-list" id="upcomingBookingsList">
                                    @forelse($upcomingBookings ?? [] as $booking)
                                    <div class="upcoming-event-item" style="border-left-color: #3B82F6;">
                                        <div class="event-time">{{ $booking->booking_date->format('M d') }}</div>
                                        <div class="event-info">
                                            <div class="event-title">{{ $booking->agency_name }}</div>
                                            <div class="event-location"><i class="fas fa-clock"></i> {{ $booking->formatted_time }}</div>
                                        </div>
                                    </div>
                                    @empty
                                    <div style="text-align: center; padding: 20px; color: #9CA3AF;">
                                        <i class="fas fa-calendar-check" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                                        No upcoming bookings
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Main Calendar View -->
                        <div class="calendar-main">
                            <div class="calendar-header">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <button class="cal-nav-btn" onclick="schedulerPrevMonth()"><i class="fas fa-chevron-left"></i></button>
                                    <h3 style="font-size: 24px; font-weight: 700; color: #1F2937; margin: 0;" id="schedulerMainTitle">January 2026</h3>
                                    <button class="cal-nav-btn" onclick="schedulerNextMonth()"><i class="fas fa-chevron-right"></i></button>
                                </div>
                                <button onclick="showCreateEventModal()" style="background: linear-gradient(135deg, #7B1D3A, #5a1428); padding: 10px 20px; border-radius: 8px; border: none; color: white; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;">
                                    <i class="fas fa-plus"></i> Add Event
                                </button>
                            </div>

                            <!-- Calendar Legend -->
                            <div style="display: flex; gap: 20px; margin-bottom: 16px; padding: 12px; background: #F9FAFB; border-radius: 8px; font-size: 13px;">
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <div style="width: 12px; height: 12px; background: #3B82F6; border-radius: 2px;"></div>
                                    <span style="color: #6B7280;">Bookings</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <div style="width: 12px; height: 12px; background: #10B981; border-radius: 2px;"></div>
                                    <span style="color: #6B7280;">Events</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <div style="width: 12px; height: 12px; background: #EF4444; border-radius: 2px;"></div>
                                    <span style="color: #6B7280;">Blocked</span>
                                </div>
                            </div>

                            <!-- Month View Calendar -->
                            <div class="full-calendar">
                                <div class="calendar-weekdays">
                                    <div class="weekday">Sunday</div>
                                    <div class="weekday">Monday</div>
                                    <div class="weekday">Tuesday</div>
                                    <div class="weekday">Wednesday</div>
                                    <div class="weekday">Thursday</div>
                                    <div class="weekday">Friday</div>
                                    <div class="weekday">Saturday</div>
                                </div>
                                <div class="calendar-grid" id="schedulerCalendarGrid">
                                    <!-- Calendar will be generated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- All Bookings Tab -->
                <div id="allBookingsTab" class="booking-tab-content" style="display: none;">
                    <div class="filter-bar" style="margin-bottom: 16px; display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                        <div class="search-box" style="max-width: 300px;">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search bookings..." id="searchBookings" onkeyup="filterBookings()">
                        </div>
                        <select class="filter-select" id="filterBookingStatus" onchange="filterBookings()">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <select class="filter-select" id="filterEmailStatus" onchange="filterBookings()" style="min-width: 140px;">
                            <option value="">All Email Status</option>
                            <option value="not-sent">Not Sent</option>
                            <option value="sent">Sent</option>
                        </select>
                    </div>
                    <div class="table-card" style="overflow-x: auto;">
                        <table style="min-width: 1100px;">
                            <thead>
                                <tr>
                                    <th style="min-width: 120px; white-space: nowrap;">Date</th>
                                    <th style="min-width: 80px; white-space: nowrap;">Time</th>
                                    <th style="min-width: 120px; white-space: nowrap;">Agency</th>
                                    <th style="min-width: 120px; white-space: nowrap;">Purpose</th>
                                    <th style="min-width: 140px; white-space: nowrap;">Contact</th>
                                    <th style="min-width: 100px; white-space: nowrap;">Status</th>
                                    <th style="min-width: 100px; white-space: nowrap;">Email Status</th>
                                    <th style="min-width: 80px; white-space: nowrap;">Attachment</th>
                                    <th style="min-width: 150px; white-space: nowrap;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="allBookingsBody">
                                @forelse($allBookings ?? [] as $booking)
                                <tr class="booking-row" data-status="{{ $booking->status }}" data-search="{{ strtolower($booking->agency_name . ' ' . $booking->event_name . ' ' . $booking->contact_person) }}" data-emailed="{{ $booking->admin_emailed ? 'yes' : 'no' }}">
                                    <td style="white-space: nowrap;">
                                        {{ $booking->booking_date->format('M d, Y') }}
                                    </td>
                                    <td>{{ $booking->formatted_time }}</td>
                                    <td>
                                        <div style="font-weight: 600;">{{ $booking->agency_name }}</div>
                                    </td>
                                    <td>{{ $booking->event_name }}</td>
                                    <td>
                                        <div>{{ $booking->contact_person }}</div>
                                        <div style="font-size: 12px; color: #6B7280;">{{ $booking->email }}</div>
                                    </td>
                                    <td>
                                        @if($booking->status === 'pending')
                                        <span class="status-badge" style="background: #FEF3C7; color: #D97706;">Pending</span>
                                        @elseif($booking->status === 'approved')
                                        <span class="status-badge" style="background: #D1FAE5; color: #059669;">Approved</span>
                                        @else
                                        <span class="status-badge" style="background: #FEE2E2; color: #DC2626;">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->status === 'approved')
                                            @if($booking->admin_emailed)
                                            <span style="display: inline-flex; align-items: center; gap: 4px; background: #D1FAE5; color: #059669; font-size: 11px; padding: 4px 8px; border-radius: 6px; font-weight: 600;" title="Email notification sent to booker">
                                                <i class="fas fa-check-circle"></i> Sent
                                            </span>
                                            @else
                                            <span style="display: inline-flex; align-items: center; gap: 4px; background: #FEF3C7; color: #D97706; font-size: 11px; padding: 4px 8px; border-radius: 6px; font-weight: 600; cursor: pointer; animation: pulse-amber 2s infinite;" title="Click to send email notification" onclick="openBookingActionModal({{ $booking->id }}, '{{ addslashes($booking->agency_name) }}', '{{ $booking->booking_date->format('M d, Y') }}', '{{ $booking->formatted_time }}', '{{ addslashes($booking->event_name) }}', '{{ addslashes($booking->contact_person) }}', '{{ $booking->email }}', '{{ $booking->phone }}', '{{ addslashes($booking->purpose ?? 'N/A') }}', '{{ $booking->attachment_path ? asset('storage/' . $booking->attachment_path) : '' }}', '{{ $booking->status }}', {{ $booking->admin_emailed ? 'true' : 'false' }})">
                                                <i class="fas fa-envelope"></i> Not Sent
                                            </span>
                                            @endif
                                        @else
                                        <span style="color: #9CA3AF; font-size: 12px;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->attachment_path)
                                        <a href="{{ asset('storage/' . $booking->attachment_path) }}" target="_blank" class="btn-action btn-view" title="View PDF" style="background: #DBEAFE; color: #2563EB;">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        @else
                                        <span style="color: #9CA3AF; font-size: 12px;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 4px; align-items: center; flex-wrap: nowrap;">
                                            <button class="btn-action btn-view" onclick="openBookingActionModal({{ $booking->id }}, '{{ addslashes($booking->agency_name) }}', '{{ $booking->booking_date->format('M d, Y') }}', '{{ $booking->formatted_time }}', '{{ addslashes($booking->event_name) }}', '{{ addslashes($booking->contact_person) }}', '{{ $booking->email }}', '{{ $booking->phone }}', '{{ addslashes($booking->purpose ?? 'N/A') }}', '{{ $booking->attachment_path ? asset('storage/' . $booking->attachment_path) : '' }}', '{{ $booking->status }}', {{ $booking->admin_emailed ? 'true' : 'false' }})" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($booking->status === 'pending')
                                            <button class="btn-action btn-edit" onclick="openBookingActionModal({{ $booking->id }}, '{{ addslashes($booking->agency_name) }}', '{{ $booking->booking_date->format('M d, Y') }}', '{{ $booking->formatted_time }}', '{{ addslashes($booking->event_name) }}', '{{ addslashes($booking->contact_person) }}', '{{ $booking->email }}', '{{ $booking->phone }}', '{{ addslashes($booking->purpose ?? 'N/A') }}', '{{ $booking->attachment_path ? asset('storage/' . $booking->attachment_path) : '' }}', '{{ $booking->status }}', {{ $booking->admin_emailed ? 'true' : 'false' }})" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn-action btn-delete" onclick="openBookingActionModal({{ $booking->id }}, '{{ addslashes($booking->agency_name) }}', '{{ $booking->booking_date->format('M d, Y') }}', '{{ $booking->formatted_time }}', '{{ addslashes($booking->event_name) }}', '{{ addslashes($booking->contact_person) }}', '{{ $booking->email }}', '{{ $booking->phone }}', '{{ addslashes($booking->purpose ?? 'N/A') }}', '{{ $booking->attachment_path ? asset('storage/' . $booking->attachment_path) : '' }}', '{{ $booking->status }}', {{ $booking->admin_emailed ? 'true' : 'false' }})" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            @else
                                            <button class="btn-action" disabled title="Already {{ ucfirst($booking->status) }}" style="opacity: 0.5;">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn-action" disabled title="Already {{ ucfirst($booking->status) }}" style="opacity: 0.5;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            @endif
                                            <button class="btn-action btn-delete" onclick="deleteBooking({{ $booking->id }})" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                        <i class="fas fa-calendar-times" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                                        No bookings found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination for All Bookings -->
                    <div id="allBookingsPagination" class="pagination-controls" style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding: 12px 16px; background: #F9FAFB; border-radius: 8px;">
                        <div style="font-size: 14px; color: #6B7280;">
                            Showing <span id="allShowingStart">1</span> to <span id="allShowingEnd">8</span> of <span id="allTotalItems">0</span> entries
                        </div>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <button id="allPrevBtn" onclick="allBookingsPrevPage()" style="padding: 8px 12px; border: 1px solid #D1D5DB; border-radius: 6px; background: white; cursor: pointer; font-size: 14px;">
                                <i class="fas fa-chevron-left"></i> Previous
                            </button>
                            <span id="allPageInfo" style="padding: 8px 16px; background: #7B1D3A; color: white; border-radius: 6px; font-weight: 600;">1 / 1</span>
                            <button id="allNextBtn" onclick="allBookingsNextPage()" style="padding: 8px 12px; border: 1px solid #D1D5DB; border-radius: 6px; background: white; cursor: pointer; font-size: 14px;">
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Events Tab -->
                <div id="eventsTab" class="booking-tab-content" style="display: none;">
                    <div id="eventsListContainer" style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="text-align: center; padding: 60px 20px; color: #9CA3AF;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 48px; margin-bottom: 16px;"></i>
                            <p style="font-size: 16px;">Loading events...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- ========== TEAM LEADERS SECTION ========== -->
            <div id="team-leaders" class="page-content">
                <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <div>
                        <h1 style="font-size: 24px; font-weight: 700; color: #1F2937; margin: 0;">Team Leaders</h1>
                        <p style="color: #6B7280; margin-top: 4px;">Manage team leaders who supervise interns from their schools</p>
                    </div>
                    <button onclick="openTeamLeaderModal()" class="btn-primary" style="background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-plus"></i> Add Team Leader
                    </button>
                </div>

                <!-- Stats Cards -->
                <div id="teamLeaderStats" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #DBEAFE, #93C5FD); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-users" style="color: #2563EB; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="statTotalTeamLeaders" style="font-size: 28px; font-weight: 700; color: #1F2937;">0</div>
                                <div style="font-size: 13px; color: #6B7280;">Total Team Leaders</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #D1FAE5, #6EE7B7); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check-circle" style="color: #059669; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="statActiveTeamLeaders" style="font-size: 28px; font-weight: 700; color: #059669;">0</div>
                                <div style="font-size: 13px; color: #6B7280;">Active</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #FEE2E2, #FCA5A5); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-ban" style="color: #DC2626; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="statInactiveTeamLeaders" style="font-size: 28px; font-weight: 700; color: #DC2626;">0</div>
                                <div style="font-size: 13px; color: #6B7280;">Inactive</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #FEF3C7, #FCD34D); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-university" style="color: #D97706; font-size: 20px;"></i>
                            </div>
                            <div>
                                <div id="statSchoolsCovered" style="font-size: 28px; font-weight: 700; color: #D97706;">0</div>
                                <div style="font-size: 13px; color: #6B7280;">Schools Covered</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Leaders Table -->
                <div class="card" style="background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden;">
                    <div style="padding: 20px 24px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="font-size: 18px; font-weight: 700; color: #1F2937; margin: 0;">Team Leader Directory</h3>
                        <div style="display: flex; gap: 12px; align-items: center;">
                            <select id="filterTeamLeaderStatus" onchange="filterTeamLeaders()" style="padding: 8px 16px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;">
                                <option value="all">All Status</option>
                                <option value="active">Active Only</option>
                                <option value="inactive">Inactive Only</option>
                            </select>
                        </div>
                    </div>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                            <thead>
                                <tr style="background: #F9FAFB;">
                                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; min-width: 120px; white-space: nowrap;">Reference Code</th>
                                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; min-width: 180px;">Name</th>
                                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; min-width: 180px;">Email</th>
                                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; min-width: 150px; white-space: nowrap;">School</th>
                                    <th style="padding: 14px 16px; text-align: center; font-weight: 600; color: #374151; font-size: 13px; min-width: 70px; white-space: nowrap;">Interns</th>
                                    <th style="padding: 14px 16px; text-align: center; font-weight: 600; color: #374151; font-size: 13px; min-width: 70px; white-space: nowrap;">Reports</th>
                                    <th style="padding: 14px 16px; text-align: center; font-weight: 600; color: #374151; font-size: 13px; min-width: 80px; white-space: nowrap;">Status</th>
                                    <th style="padding: 14px 16px; text-align: center; font-weight: 600; color: #374151; font-size: 13px; min-width: 120px; white-space: nowrap;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="teamLeadersTableBody">
                                <tr>
                                    <td colspan="8" style="padding: 60px; text-align: center; color: #9CA3AF;">
                                        <i class="fas fa-spinner fa-spin" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                                        Loading team leaders...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ========== TEAM REPORTS SECTION ========== -->
            <div id="team-reports" class="page-content">
                <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <div>
                        <h1 style="font-size: 24px; font-weight: 700; color: #1F2937; margin: 0;">Team Leader Reports</h1>
                        <p style="color: #6B7280; margin-top: 4px;">Review and manage reports submitted by team leaders</p>
                    </div>
                    <div id="pendingReportsBadge" style="background: #FEF3C7; color: #92400E; padding: 8px 16px; border-radius: 20px; font-weight: 600; display: none;">
                        <i class="fas fa-clock"></i> <span id="pendingReportsCount">0</span> Pending Review
                    </div>
                </div>

                <!-- Reports Filters -->
                <div style="display: flex; gap: 12px; margin-bottom: 24px;">
                    <select id="filterReportStatus" onchange="filterReports()" style="padding: 10px 16px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;">
                        <option value="all">All Reports</option>
                        <option value="submitted">Pending Review</option>
                        <option value="reviewed">Reviewed</option>
                        <option value="acknowledged">Acknowledged</option>
                    </select>
                    <select id="filterReportType" onchange="filterReports()" style="padding: 10px 16px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;">
                        <option value="all">All Types</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="incident">Incident</option>
                        <option value="special">Special</option>
                    </select>
                </div>

                <!-- Reports List -->
                <div id="reportsListContainer" style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="text-align: center; padding: 60px 20px; color: #9CA3AF;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
                        <p style="font-size: 16px;">Loading reports...</p>
                    </div>
                </div>
            </div>

            <!-- ========== ADMIN PROFILE SECTION ========== -->
            <div id="admin-profile" class="page-content">
                <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); overflow: hidden;">
                    <!-- Profile Header -->
                    <div style="text-align: center; padding: 40px 24px; background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white;">
                        <div id="adminProfilePictureContainer" style="position: relative; width: 120px; height: 120px; margin: 0 auto 16px; cursor: pointer;" onclick="document.getElementById('adminProfilePictureInput').click()">
                            @if(Auth::user()->profile_picture)
                                <img id="adminProfilePictureImg" src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3); border: 4px solid rgba(255, 255, 255, 0.3);">
                            @else
                                <div id="adminProfilePictureInitial" style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3); border: 4px solid rgba(255, 255, 255, 0.3);">
                                    <span style="font-size: 48px; color: #7B1D3A; font-weight: 700;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div style="position: absolute; bottom: 0; right: 0; width: 36px; height: 36px; background: #FFBF00; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                <i class="fas fa-camera" style="color: #7B1D3A; font-size: 14px;"></i>
                            </div>
                        </div>
                        <input type="file" id="adminProfilePictureInput" accept="image/jpeg,image/png,image/jpg,image/gif" style="display: none;" onchange="uploadAdminProfilePicture(this)">
                        <p style="font-size: 12px; opacity: 0.8; margin-bottom: 12px;">Click to change profile picture</p>
                        <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 4px;">{{ Auth::user()->name }}</h2>
                        <p style="color: #FFBF00; font-weight: 600; font-size: 14px;">System Administrator</p>
                    </div>

                    <!-- Profile Content -->
                    <div style="padding: 32px;">
                        <!-- Account Information -->
                        <div style="margin-bottom: 32px;">
                            <h4 style="font-size: 16px; font-weight: 700; color: #1F2937; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-id-card" style="color: #7B1D3A;"></i> Account Information
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                                <div style="background: #F9FAFB; padding: 16px; border-radius: 10px;">
                                    <label style="font-size: 12px; color: #6B7280; display: block; margin-bottom: 6px; text-transform: uppercase; font-weight: 600;">Full Name</label>
                                    <p style="font-weight: 600; color: #1F2937; font-size: 15px;">{{ Auth::user()->name }}</p>
                                </div>
                                <div style="background: #F9FAFB; padding: 16px; border-radius: 10px;">
                                    <label style="font-size: 12px; color: #6B7280; display: block; margin-bottom: 6px; text-transform: uppercase; font-weight: 600;">Email Address</label>
                                    <p style="font-weight: 600; color: #1F2937; font-size: 15px; word-break: break-all;">{{ Auth::user()->email }}</p>
                                </div>
                                <div style="background: #F9FAFB; padding: 16px; border-radius: 10px;">
                                    <label style="font-size: 12px; color: #6B7280; display: block; margin-bottom: 6px; text-transform: uppercase; font-weight: 600;">Role</label>
                                    <span style="display: inline-block; background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">Administrator</span>
                                </div>
                                <div style="background: #F9FAFB; padding: 16px; border-radius: 10px;">
                                    <label style="font-size: 12px; color: #6B7280; display: block; margin-bottom: 6px; text-transform: uppercase; font-weight: 600;">Account Status</label>
                                    <span style="display: inline-block; background: #D1FAE5; color: #065F46; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;"><i class="fas fa-check-circle" style="margin-right: 4px;"></i>Active</span>
                                </div>
                            </div>
                        </div>

                        <!-- System Statistics -->
                        <div style="margin-bottom: 32px;">
                            <h4 style="font-size: 16px; font-weight: 700; color: #1F2937; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-chart-bar" style="color: #7B1D3A;"></i> System Overview
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
                                <div style="background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); padding: 20px; border-radius: 12px; text-align: center;">
                                    <div style="font-size: 28px; font-weight: 700; color: #4F46E5;">{{ $activeInterns ?? 0 }}</div>
                                    <div style="font-size: 12px; color: #6366F1; font-weight: 600; margin-top: 4px;">Active Interns</div>
                                </div>
                                <div style="background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); padding: 20px; border-radius: 12px; text-align: center;">
                                    <div style="font-size: 28px; font-weight: 700; color: #D97706;">{{ $totalDocuments ?? 0 }}</div>
                                    <div style="font-size: 12px; color: #B45309; font-weight: 600; margin-top: 4px;">Documents</div>
                                </div>
                                <div style="background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%); padding: 20px; border-radius: 12px; text-align: center;">
                                    <div style="font-size: 28px; font-weight: 700; color: #059669;">{{ isset($startups) ? $startups->count() : 0 }}</div>
                                    <div style="font-size: 12px; color: #047857; font-weight: 600; margin-top: 4px;">Startups</div>
                                </div>
                                <div style="background: linear-gradient(135deg, #FCE7F3 0%, #FBCFE8 100%); padding: 20px; border-radius: 12px; text-align: center;">
                                    <div style="font-size: 28px; font-weight: 700; color: #DB2777;">{{ isset($schools) ? $schools->count() : 0 }}</div>
                                    <div style="font-size: 12px; color: #BE185D; font-weight: 600; margin-top: 4px;">Schools</div>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Permissions -->
                        <div>
                            <h4 style="font-size: 16px; font-weight: 700; color: #1F2937; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-shield-alt" style="color: #7B1D3A;"></i> Administrator Permissions
                            </h4>
                            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                <span style="background: #D1FAE5; color: #065F46; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 500;">
                                    <i class="fas fa-check" style="margin-right: 6px;"></i>Full System Access
                                </span>
                                <span style="background: #DBEAFE; color: #1E40AF; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 500;">
                                    <i class="fas fa-users-cog" style="margin-right: 6px;"></i>User Management
                                </span>
                                <span style="background: #FEF3C7; color: #92400E; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 500;">
                                    <i class="fas fa-cog" style="margin-right: 6px;"></i>System Settings
                                </span>
                                <span style="background: #FCE7F3; color: #9D174D; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 500;">
                                    <i class="fas fa-file-alt" style="margin-right: 6px;"></i>Document Management
                                </span>
                                <span style="background: #E0E7FF; color: #3730A3; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 500;">
                                    <i class="fas fa-calendar-alt" style="margin-right: 6px;"></i>Scheduler Control
                                </span>
                                <span style="background: #CCFBF1; color: #0F766E; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 500;">
                                    <i class="fas fa-database" style="margin-right: 6px;"></i>Data Management
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== SETTINGS SECTION ========== -->
            <div id="admin-settings" class="page-content">
                <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <div>
                        <h1 style="font-size: 24px; font-weight: 700; color: #1F2937; margin: 0;">System Settings</h1>
                        <p style="color: #6B7280; margin-top: 4px;">Configure system preferences and manage application settings</p>
                    </div>
                </div>

                <!-- Settings Grid -->
                <div style="display: grid; grid-template-columns: 280px 1fr; gap: 24px;">
                    <!-- Settings Navigation -->
                    <div style="background: white; border-radius: 12px; padding: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); height: fit-content; position: sticky; top: 20px;">
                        <button class="settings-nav-item active" onclick="showSettingsTab('general')" id="settingsNavGeneral">
                            <i class="fas fa-cog"></i>
                            <span>General Settings</span>
                        </button>
                        <button class="settings-nav-item" onclick="showSettingsTab('internship')" id="settingsNavInternship">
                            <i class="fas fa-user-graduate"></i>
                            <span>Internship Settings</span>
                        </button>
                        <button class="settings-nav-item" onclick="showSettingsTab('notifications')" id="settingsNavNotifications">
                            <i class="fas fa-bell"></i>
                            <span>Notifications</span>
                        </button>
                        <button class="settings-nav-item" onclick="showSettingsTab('scheduler')" id="settingsNavScheduler">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Scheduler Settings</span>
                        </button>
                        <button class="settings-nav-item" onclick="showSettingsTab('appearance')" id="settingsNavAppearance">
                            <i class="fas fa-palette"></i>
                            <span>Appearance</span>
                        </button>
                        <button class="settings-nav-item" onclick="showSettingsTab('backup')" id="settingsNavBackup">
                            <i class="fas fa-database"></i>
                            <span>Backup & Data</span>
                        </button>
                        <button class="settings-nav-item" onclick="showSettingsTab('about')" id="settingsNavAbout">
                            <i class="fas fa-info-circle"></i>
                            <span>About System</span>
                        </button>
                    </div>

                    <!-- Settings Content -->
                    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

                        <!-- General Settings Tab -->
                        <div id="settingsTabGeneral" class="settings-tab active">
                            <h3 style="font-size: 18px; font-weight: 700; color: #1F2937; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-cog" style="color: #7B1D3A;"></i>
                                General Settings
                            </h3>

                            <div class="settings-group">
                                <label class="settings-label">System Name</label>
                                <input type="text" id="settingSystemName" class="settings-input" value="UP Management System" placeholder="Enter system name">
                                <p class="settings-hint">This name appears in the browser title and headers</p>
                            </div>

                            <div class="settings-group">
                                <label class="settings-label">Office Name</label>
                                <input type="text" id="settingOfficeName" class="settings-input" value="University of Pangasinan" placeholder="Enter office/organization name">
                            </div>

                            <div class="settings-group">
                                <label class="settings-label">Contact Email</label>
                                <input type="email" id="settingContactEmail" class="settings-input" value="" placeholder="admin@example.com">
                                <p class="settings-hint">Used for system notifications and user inquiries</p>
                            </div>

                            <div class="settings-group">
                                <label class="settings-label">Timezone</label>
                                <select id="settingTimezone" class="settings-input">
                                    <option value="Asia/Manila" selected>Asia/Manila (Philippine Standard Time)</option>
                                    <option value="UTC">UTC (Coordinated Universal Time)</option>
                                    <option value="Asia/Singapore">Asia/Singapore</option>
                                    <option value="Asia/Tokyo">Asia/Tokyo</option>
                                </select>
                            </div>

                            <div class="settings-group">
                                <label class="settings-label">Date Format</label>
                                <select id="settingDateFormat" class="settings-input">
                                    <option value="M d, Y">Jan 23, 2026</option>
                                    <option value="d/m/Y">23/01/2026</option>
                                    <option value="m/d/Y">01/23/2026</option>
                                    <option value="Y-m-d">2026-01-23</option>
                                </select>
                            </div>

                            <div class="settings-group">
                                <div class="settings-toggle-row">
                                    <div>
                                        <label class="settings-label" style="margin-bottom: 0;">Maintenance Mode</label>
                                        <p class="settings-hint" style="margin-top: 4px;">Temporarily disable access for non-admin users</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="settingMaintenanceMode">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Internship Settings Tab -->
                        <div id="settingsTabInternship" class="settings-tab">
                            <h3 style="font-size: 18px; font-weight: 700; color: #1F2937; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-user-graduate" style="color: #7B1D3A;"></i>
                                Internship Settings
                            </h3>

                            <div class="settings-group">
                                <label class="settings-label">Work Hours Start</label>
                                <input type="time" id="settingWorkStart" class="settings-input" value="08:00">
                                <p class="settings-hint">Time when work starts. Interns timing in after this are marked as Late.</p>
                            </div>

                            <div class="settings-group">
                                <label class="settings-label">Overtime Threshold (hours)</label>
                                <input type="number" id="settingOvertimeThreshold" class="settings-input" value="8" min="1" max="12" step="0.5">
                                <p class="settings-hint">Hours after which overtime starts counting</p>
                            </div>

                            <div class="settings-group">
                                <div class="settings-toggle-row">
                                    <div>
                                        <label class="settings-label" style="margin-bottom: 0;">Auto-Approve Intern Registration</label>
                                        <p class="settings-hint" style="margin-top: 4px;">Automatically approve new intern registrations</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="settingAutoApproveIntern">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="settings-group">
                                <div class="settings-toggle-row">
                                    <div>
                                        <label class="settings-label" style="margin-bottom: 0;">Require Overtime Approval</label>
                                        <p class="settings-hint" style="margin-top: 4px;">Overtime hours need admin approval before counting</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="settingRequireOvertimeApproval" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications Settings Tab -->
                        <div id="settingsTabNotifications" class="settings-tab">
                            <h3 style="font-size: 18px; font-weight: 700; color: #1F2937; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-bell" style="color: #7B1D3A;"></i>
                                Notification Settings
                            </h3>

                            <div class="settings-group">
                                <div class="settings-toggle-row">
                                    <div>
                                        <label class="settings-label" style="margin-bottom: 0;">New Booking Alerts</label>
                                        <p class="settings-hint" style="margin-top: 4px;">Get notified when new booking requests arrive</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="settingBookingAlerts" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="settings-group">
                                <div class="settings-toggle-row">
                                    <div>
                                        <label class="settings-label" style="margin-bottom: 0;">New Intern Registration Alerts</label>
                                        <p class="settings-hint" style="margin-top: 4px;">Get notified when new interns register</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="settingInternAlerts" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="settings-group">
                                <div class="settings-toggle-row">
                                    <div>
                                        <label class="settings-label" style="margin-bottom: 0;">Issue Report Alerts</label>
                                        <p class="settings-hint" style="margin-top: 4px;">Get notified when new issues are reported</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="settingIssueAlerts" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="settings-group">
                                <div class="settings-toggle-row">
                                    <div>
                                        <label class="settings-label" style="margin-bottom: 0;">Sound Notifications</label>
                                        <p class="settings-hint" style="margin-top: 4px;">Play sound when new notifications arrive</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="settingSoundNotifications" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="settings-group">
                                <label class="settings-label">Notification Refresh Interval (seconds)</label>
                                <select id="settingNotificationInterval" class="settings-input">
                                    <option value="5">5 seconds (Real-time)</option>
                                    <option value="10" selected>10 seconds</option>
                                    <option value="30">30 seconds</option>
                                    <option value="60">1 minute</option>
                                </select>
                            </div>
                        </div>

                        <!-- Scheduler Settings Tab -->
                        <div id="settingsTabScheduler" class="settings-tab">
                            <h3 style="font-size: 18px; font-weight: 700; color: #1F2937; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-calendar-alt" style="color: #7B1D3A;"></i>
                                Scheduler Settings
                            </h3>

                            <div class="settings-group">
                                <label class="settings-label">Default Booking Duration (hours)</label>
                                <input type="number" id="settingBookingDuration" class="settings-input" value="2" min="1" max="8">
                            </div>

                            <div class="settings-group">
                                <label class="settings-label">Minimum Advance Booking (days)</label>
                                <input type="number" id="settingMinAdvanceBooking" class="settings-input" value="1" min="0" max="30">
                                <p class="settings-hint">Minimum days before the event date for booking requests</p>
                            </div>

                            <div class="settings-group">
                                <label class="settings-label">Maximum Advance Booking (days)</label>
                                <input type="number" id="settingMaxAdvanceBooking" class="settings-input" value="90" min="7" max="365">
                                <p class="settings-hint">How far in advance bookings can be made</p>
                            </div>

                            <div class="settings-group">
                                <div class="settings-toggle-row">
                                    <div>
                                        <label class="settings-label" style="margin-bottom: 0;">Auto-Approve Bookings</label>
                                        <p class="settings-hint" style="margin-top: 4px;">Automatically approve booking requests</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="settingAutoApproveBooking">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="settings-group">
                                <div class="settings-toggle-row">
                                    <div>
                                        <label class="settings-label" style="margin-bottom: 0;">Allow Weekend Bookings</label>
                                        <p class="settings-hint" style="margin-top: 4px;">Allow booking requests on Saturdays and Sundays</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="settingWeekendBookings">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="settings-group">
                                <label class="settings-label">Booking Hours Start</label>
                                <input type="time" id="settingBookingStart" class="settings-input" value="08:00">
                            </div>

                            <div class="settings-group">
                                <label class="settings-label">Booking Hours End</label>
                                <input type="time" id="settingBookingEnd" class="settings-input" value="17:00">
                            </div>
                        </div>

                        <!-- Appearance Settings Tab -->
                        <div id="settingsTabAppearance" class="settings-tab">
                            <h3 style="font-size: 18px; font-weight: 700; color: #1F2937; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-palette" style="color: #7B1D3A;"></i>
                                Appearance Settings
                            </h3>

                            <div class="settings-group">
                                <label class="settings-label">Primary Color</label>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <input type="color" id="settingPrimaryColor" value="#7B1D3A" style="width: 60px; height: 40px; border: none; border-radius: 8px; cursor: pointer;">
                                    <input type="text" id="settingPrimaryColorHex" class="settings-input" value="#7B1D3A" style="width: 120px;" readonly>
                                </div>
                            </div>

                            <div class="settings-group">
                                <label class="settings-label">Accent Color</label>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <input type="color" id="settingAccentColor" value="#FFBF00" style="width: 60px; height: 40px; border: none; border-radius: 8px; cursor: pointer;">
                                    <input type="text" id="settingAccentColorHex" class="settings-input" value="#FFBF00" style="width: 120px;" readonly>
                                </div>
                            </div>

                            <div class="settings-group">
                                <label class="settings-label">Sidebar Style</label>
                                <select id="settingSidebarStyle" class="settings-input">
                                    <option value="gradient" selected>Gradient (Maroon)</option>
                                    <option value="solid">Solid Color</option>
                                    <option value="dark">Dark Mode</option>
                                </select>
                            </div>

                            <div class="settings-group">
                                <div class="settings-toggle-row">
                                    <div>
                                        <label class="settings-label" style="margin-bottom: 0;">Compact Mode</label>
                                        <p class="settings-hint" style="margin-top: 4px;">Reduce spacing and padding throughout the interface</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="settingCompactMode">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="settings-group">
                                <div class="settings-toggle-row">
                                    <div>
                                        <label class="settings-label" style="margin-bottom: 0;">Show Dashboard Animations</label>
                                        <p class="settings-hint" style="margin-top: 4px;">Enable smooth animations and transitions</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="settingAnimations" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Backup & Data Tab -->
                        <div id="settingsTabBackup" class="settings-tab">
                            <h3 style="font-size: 18px; font-weight: 700; color: #1F2937; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-database" style="color: #7B1D3A;"></i>
                                Backup & Data Management
                            </h3>

                            <div style="background: #F0FDF4; border: 1px solid #86EFAC; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                                <h4 style="color: #065F46; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-shield-alt"></i> Data Backup
                                </h4>
                                <p style="color: #047857; font-size: 14px; margin-bottom: 16px;">Export system data for backup purposes</p>
                                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                    <button onclick="exportData('interns')" class="settings-btn">
                                        <i class="fas fa-user-graduate"></i> Export Interns
                                    </button>
                                    <button onclick="exportData('attendance')" class="settings-btn">
                                        <i class="fas fa-clock"></i> Export Attendance
                                    </button>
                                    <button onclick="exportData('tasks')" class="settings-btn">
                                        <i class="fas fa-tasks"></i> Export Tasks
                                    </button>
                                    <button onclick="exportData('bookings')" class="settings-btn">
                                        <i class="fas fa-calendar"></i> Export Bookings
                                    </button>
                                </div>
                            </div>

                            <div style="background: #FEF3C7; border: 1px solid #FCD34D; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                                <h4 style="color: #92400E; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-broom"></i> Data Cleanup
                                </h4>
                                <p style="color: #A16207; font-size: 14px; margin-bottom: 16px;">Clear old or unnecessary data to improve performance</p>

                                <div class="settings-group" style="margin-bottom: 16px;">
                                    <label class="settings-label">Delete records older than (days)</label>
                                    <input type="number" id="cleanupDays" class="settings-input" value="90" min="30" max="365" style="width: 150px;">
                                </div>

                                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                    <button onclick="clearOldData('attendance')" class="settings-btn warning">
                                        <i class="fas fa-clock"></i> Clear Old Attendance
                                    </button>
                                    <button onclick="clearOldData('tasks')" class="settings-btn warning">
                                        <i class="fas fa-tasks"></i> Clear Completed Tasks
                                    </button>
                                    <button onclick="clearOldData('bookings')" class="settings-btn warning">
                                        <i class="fas fa-calendar-times"></i> Clear Old Bookings
                                    </button>
                                    <button onclick="clearOldData('notifications')" class="settings-btn warning">
                                        <i class="fas fa-bell-slash"></i> Clear Notifications
                                    </button>
                                    <button onclick="clearOldData('all')" class="settings-btn danger">
                                        <i class="fas fa-trash-alt"></i> Clear All Old Data
                                    </button>
                                </div>
                            </div>

                            <div style="background: #FEE2E2; border: 1px solid #FECACA; border-radius: 12px; padding: 20px;">
                                <h4 style="color: #991B1B; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-exclamation-triangle"></i> Danger Zone
                                </h4>
                                <p style="color: #DC2626; font-size: 14px; margin-bottom: 16px;">These actions are irreversible. Proceed with caution.</p>
                                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                    <button onclick="resetSettings()" class="settings-btn danger">
                                        <i class="fas fa-undo"></i> Reset All Settings
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- About Tab -->
                        <div id="settingsTabAbout" class="settings-tab">
                            <h3 style="font-size: 18px; font-weight: 700; color: #1F2937; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-info-circle" style="color: #7B1D3A;"></i>
                                About System
                            </h3>

                            <div style="text-align: center; padding: 40px 20px; background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); border-radius: 16px; color: white; margin-bottom: 24px;">
                                <div style="width: 80px; height: 80px; background: white; border-radius: 20px; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ asset('images/up logo.png') }}" alt="UP Logo" style="width: 60px; height: 60px; object-fit: contain;">
                                </div>
                                <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 8px;">UP Management System</h2>
                                <p style="opacity: 0.9; margin-bottom: 4px;">Version 1.0.0</p>
                                <p style="opacity: 0.7; font-size: 13px;">© 2026 University of Pangasinan</p>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 24px;">
                                <div style="background: #F9FAFB; padding: 16px; border-radius: 10px;">
                                    <div style="color: #6B7280; font-size: 12px; text-transform: uppercase; margin-bottom: 4px;">Framework</div>
                                    <div style="font-weight: 600; color: #1F2937;">Laravel {{ app()->version() }}</div>
                                </div>
                                <div style="background: #F9FAFB; padding: 16px; border-radius: 10px;">
                                    <div style="color: #6B7280; font-size: 12px; text-transform: uppercase; margin-bottom: 4px;">PHP Version</div>
                                    <div style="font-weight: 600; color: #1F2937;">{{ phpversion() }}</div>
                                </div>
                                <div style="background: #F9FAFB; padding: 16px; border-radius: 10px;">
                                    <div style="color: #6B7280; font-size: 12px; text-transform: uppercase; margin-bottom: 4px;">Server Time</div>
                                    <div style="font-weight: 600; color: #1F2937;" id="serverTime">{{ now()->setTimezone('Asia/Manila')->format('M d, Y g:i A') }}</div>
                                </div>
                                <div style="background: #F9FAFB; padding: 16px; border-radius: 10px;">
                                    <div style="color: #6B7280; font-size: 12px; text-transform: uppercase; margin-bottom: 4px;">Environment</div>
                                    <div style="font-weight: 600; color: #1F2937;">{{ ucfirst(config('app.env')) }}</div>
                                </div>
                            </div>

                            <div style="background: #F9FAFB; padding: 20px; border-radius: 12px;">
                                <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 12px;">System Modules</h4>
                                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                    <span style="background: #DBEAFE; color: #1E40AF; padding: 6px 12px; border-radius: 20px; font-size: 13px;">
                                        <i class="fas fa-user-graduate"></i> Intern Management
                                    </span>
                                    <span style="background: #D1FAE5; color: #065F46; padding: 6px 12px; border-radius: 20px; font-size: 13px;">
                                        <i class="fas fa-clock"></i> Time & Attendance
                                    </span>
                                    <span style="background: #FEF3C7; color: #92400E; padding: 6px 12px; border-radius: 20px; font-size: 13px;">
                                        <i class="fas fa-tasks"></i> Task Management
                                    </span>
                                    <span style="background: #FCE7F3; color: #9D174D; padding: 6px 12px; border-radius: 20px; font-size: 13px;">
                                        <i class="fas fa-calendar"></i> Scheduler
                                    </span>
                                    <span style="background: #E0E7FF; color: #3730A3; padding: 6px 12px; border-radius: 20px; font-size: 13px;">
                                        <i class="fas fa-rocket"></i> Incubatee Tracker
                                    </span>
                                    <span style="background: #FEE2E2; color: #991B1B; padding: 6px 12px; border-radius: 20px; font-size: 13px;">
                                        <i class="fas fa-exclamation-circle"></i> Issues Management
                                    </span>
                                    <span style="background: #F3E8FF; color: #6B21A8; padding: 6px 12px; border-radius: 20px; font-size: 13px;">
                                        <i class="fas fa-folder"></i> Digital Records
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #E5E7EB; display: flex; justify-content: flex-end; gap: 12px;">
                            <button onclick="resetSettingsForm()" style="padding: 12px 24px; background: #F3F4F6; color: #374151; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button onclick="saveSettings()" style="padding: 12px 24px; background: linear-gradient(135deg, #7B1D3A, #5a1428); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>

    </main>
    <div id="assignTeamLeaderModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 class="modal-title" id="assignTLModalTitle"><i class="fas fa-user-tie" style="margin-right: 8px;"></i>Assign Team Leader</h3>
            </div>
            <div class="modal-body">
                <input type="hidden" id="assignTLSchoolId">
                <input type="hidden" id="selectedInternId">

                <!-- School Info -->
                <div style="background: linear-gradient(135deg, #7B1D3A, #5a1428); color: white; padding: 16px; border-radius: 12px; margin-bottom: 20px;">
                    <div style="font-size: 12px; opacity: 0.8; text-transform: uppercase;">Assigning Team Leader for</div>
                    <div id="assignTLSchoolName" style="font-size: 18px; font-weight: 700;"></div>
                </div>

                <!-- Search Box -->
                <div class="form-group">
                    <label class="form-label">Search Intern</label>
                    <div style="position: relative;">
                        <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9CA3AF;"></i>
                        <input type="text" id="searchInternInput" class="form-input" placeholder="Type to search by name..." style="padding-left: 40px;" oninput="filterInternsList()">
                    </div>
                </div>

                <!-- Interns List -->
                <div class="form-group">
                    <label class="form-label">Select an Intern <span style="font-weight: normal; color: #6B7280;">(<span id="internsCount">0</span> available)</span></label>
                    <div id="internsListContainer" style="max-height: 250px; overflow-y: auto; border: 1px solid #E5E7EB; border-radius: 10px;">
                        <div style="text-align: center; padding: 40px; color: #9CA3AF;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                            Loading interns...
                        </div>
                    </div>
                </div>

                <!-- Selected Intern Display -->
                <div id="selectedInternDisplay" style="display: none; background: #F0FDF4; border: 1px solid #86EFAC; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div id="selectedInternAvatar" style="width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #10B981, #059669); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px;"></div>
                            <div>
                                <div id="selectedInternName" style="font-weight: 700; color: #065F46; font-size: 16px;"></div>
                                <div id="selectedInternInfo" style="font-size: 13px; color: #047857;"></div>
                            </div>
                        </div>
                        <button onclick="clearInternSelection()" style="background: #FEE2E2; color: #DC2626; border: none; width: 32px; height: 32px; border-radius: 6px; cursor: pointer;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Password Field -->
                <div id="passwordSection" style="display: none;">
                    <div class="form-group">
                        <label class="form-label required">Set Login Password</label>
                        <input type="password" id="assignTLPassword" class="form-input" placeholder="Create password for Team Leader login (min 8 characters)" minlength="8" required>
                        <small style="color: #6B7280; font-size: 12px; margin-top: 4px; display: block;">This password will be used by the intern to login as Team Leader</small>
                    </div>
                </div>

                <!-- Or Create New Link -->
                <div style="text-align: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid #E5E7EB;">
                    <span style="color: #6B7280; font-size: 13px;">Need to add an external coordinator? </span>
                    <a href="#" onclick="switchToManualTeamLeader()" style="color: #7B1D3A; font-weight: 600; font-size: 13px;">Create new account</a>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeAssignTeamLeaderModal()">Cancel</button>
                <button class="btn-modal primary" id="btnAssignTeamLeader" onclick="assignInternAsTeamLeader()" disabled style="opacity: 0.5;">
                    <i class="fas fa-user-shield"></i> Assign as Team Leader
                </button>
            </div>
        </div>
    </div>

    <!-- Team Leader Modal (Manual Entry - for external coordinators) -->
    <div id="teamLeaderModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 class="modal-title" id="teamLeaderModalTitle"><i class="fas fa-user-tie" style="margin-right: 8px;"></i>Add Team Leader</h3>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <form id="teamLeaderForm">
                    <input type="hidden" id="teamLeaderId">

                    <div class="form-group">
                        <label class="form-label required">Full Name</label>
                        <input type="text" id="teamLeaderName" class="form-input" placeholder="Enter full name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Email Address</label>
                        <input type="email" id="teamLeaderEmail" class="form-input" placeholder="Enter email address" required>
                    </div>

                    <div class="form-group" id="passwordGroup">
                        <label class="form-label" id="passwordLabel">Password</label>
                        <input type="password" id="teamLeaderPassword" class="form-input" placeholder="Enter password (min 8 characters)" minlength="8">
                        <small id="passwordHint" style="color: #6B7280; font-size: 12px; margin-top: 4px; display: block;">Leave blank when editing to keep current password</small>
                    </div>

                    <div class="form-group" id="schoolGroup">
                        <label class="form-label required">Assigned School</label>
                        <select id="teamLeaderSchool" class="form-input" required>
                            <option value="">-- Select School --</option>
                        </select>
                    </div>

                    <!-- Module Permissions Section -->
                    <div class="form-group" id="permissionsSection" style="margin-top: 20px; border-top: 1px solid #E5E7EB; padding-top: 20px;">
                        <label class="form-label" style="font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-key" style="color: #7B1D3A;"></i>
                            Module Access Permissions
                        </label>
                        <p style="font-size: 12px; color: #6B7280; margin-bottom: 16px;">Grant this Team Leader access to specific admin modules</p>

                        <div id="permissionsList" style="display: grid; gap: 12px;">
                            <!-- Scheduler -->
                            <div class="permission-item" style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-calendar-alt" style="color: #7B1D3A; width: 20px;"></i>
                                        <div>
                                            <div style="font-weight: 600; font-size: 13px; color: #1F2937;">Scheduler</div>
                                            <div style="font-size: 11px; color: #6B7280;">Manage room bookings and calendar events</div>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 12px;">
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="checkbox" id="perm_scheduler_view" name="permissions[scheduler][can_view]" style="accent-color: #7B1D3A;">
                                            <span style="font-size: 11px; color: #374151;">View</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="checkbox" id="perm_scheduler_edit" name="permissions[scheduler][can_edit]" style="accent-color: #7B1D3A;">
                                            <span style="font-size: 11px; color: #374151;">Edit</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Research Tracking -->
                            <div class="permission-item" style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-flask" style="color: #7B1D3A; width: 20px;"></i>
                                        <div>
                                            <div style="font-weight: 600; font-size: 13px; color: #1F2937;">Research Tracking</div>
                                            <div style="font-size: 11px; color: #6B7280;">View and manage research projects</div>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 12px;">
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="checkbox" id="perm_research_tracking_view" name="permissions[research_tracking][can_view]" style="accent-color: #7B1D3A;">
                                            <span style="font-size: 11px; color: #374151;">View</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="checkbox" id="perm_research_tracking_edit" name="permissions[research_tracking][can_edit]" style="accent-color: #7B1D3A;">
                                            <span style="font-size: 11px; color: #374151;">Edit</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Incubatee Tracker -->
                            <div class="permission-item" style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-rocket" style="color: #7B1D3A; width: 20px;"></i>
                                        <div>
                                            <div style="font-weight: 600; font-size: 13px; color: #1F2937;">Incubatee Tracker</div>
                                            <div style="font-size: 11px; color: #6B7280;">Track startup submissions and progress</div>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 12px;">
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="checkbox" id="perm_incubatee_tracker_view" name="permissions[incubatee_tracker][can_view]" style="accent-color: #7B1D3A;">
                                            <span style="font-size: 11px; color: #374151;">View</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="checkbox" id="perm_incubatee_tracker_edit" name="permissions[incubatee_tracker][can_edit]" style="accent-color: #7B1D3A;">
                                            <span style="font-size: 11px; color: #374151;">Edit</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Issues & Complaints -->
                            <div class="permission-item" style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-exclamation-triangle" style="color: #7B1D3A; width: 20px;"></i>
                                        <div>
                                            <div style="font-weight: 600; font-size: 13px; color: #1F2937;">Issues & Complaints</div>
                                            <div style="font-size: 11px; color: #6B7280;">Handle room issues and complaints</div>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 12px;">
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="checkbox" id="perm_issues_management_view" name="permissions[issues_management][can_view]" style="accent-color: #7B1D3A;">
                                            <span style="font-size: 11px; color: #374151;">View</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="checkbox" id="perm_issues_management_edit" name="permissions[issues_management][can_edit]" style="accent-color: #7B1D3A;">
                                            <span style="font-size: 11px; color: #374151;">Edit</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Digital Records -->
                            <div class="permission-item" style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-file-alt" style="color: #7B1D3A; width: 20px;"></i>
                                        <div>
                                            <div style="font-weight: 600; font-size: 13px; color: #1F2937;">Digital Records</div>
                                            <div style="font-size: 11px; color: #6B7280;">Access and manage digital documents</div>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 12px;">
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="checkbox" id="perm_digital_records_view" name="permissions[digital_records][can_view]" style="accent-color: #7B1D3A;">
                                            <span style="font-size: 11px; color: #374151;">View</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="checkbox" id="perm_digital_records_edit" name="permissions[digital_records][can_edit]" style="accent-color: #7B1D3A;">
                                            <span style="font-size: 11px; color: #374151;">Edit</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Full Intern Management -->
                            <div class="permission-item" style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-briefcase" style="color: #7B1D3A; width: 20px;"></i>
                                        <div>
                                            <div style="font-weight: 600; font-size: 13px; color: #1F2937;">Full Intern Management</div>
                                            <div style="font-size: 11px; color: #6B7280;">Full access to all intern data (not just own school)</div>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 12px;">
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="checkbox" id="perm_intern_management_view" name="permissions[intern_management][can_view]" style="accent-color: #7B1D3A;">
                                            <span style="font-size: 11px; color: #374151;">View</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                            <input type="checkbox" id="perm_intern_management_edit" name="permissions[intern_management][can_edit]" style="accent-color: #7B1D3A;">
                                            <span style="font-size: 11px; color: #374151;">Edit</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="referenceCodeDisplay" style="display: none; background: #F0FDF4; border: 1px solid #86EFAC; border-radius: 8px; padding: 16px; margin-top: 16px;">
                        <div style="font-size: 12px; color: #166534; text-transform: uppercase; font-weight: 600; margin-bottom: 4px;">Reference Code</div>
                        <div id="referenceCodeValue" style="font-size: 20px; font-weight: 700; color: #15803D; font-family: monospace;"></div>
                        <small style="color: #22C55E; font-size: 11px;">Use this code for login identification</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeTeamLeaderModal()">Cancel</button>
                <button class="btn-modal primary" onclick="saveTeamLeader()"><i class="fas fa-save"></i> Save Team Leader</button>
            </div>
        </div>
    </div>

    <!-- View Report Modal -->
    <div id="viewReportModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 750px; width: 95%; border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <!-- Modal Header with Gradient -->
            <div id="viewReportHeader" style="background: linear-gradient(135deg, #7B1D3A 0%, #A62450 100%); padding: 20px 24px; position: relative;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-file-alt" style="font-size: 20px; color: white;"></i>
                    </div>
                    <div style="flex: 1;">
                        <h3 id="viewReportTitle" style="font-size: 18px; font-weight: 700; color: white; margin: 0;">Report Details</h3>
                        <p id="viewReportSubtitle" style="color: rgba(255,255,255,0.8); font-size: 12px; margin-top: 2px;">Team Leader Report</p>
                    </div>
                </div>
                <button type="button" onclick="closeViewReportModal()" style="position: absolute; top: 16px; right: 16px; width: 36px; height: 36px; background: rgba(255,255,255,0.2); border: none; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i class="fas fa-times" style="color: white; font-size: 16px;"></i>
                </button>
            </div>
            <div class="modal-body" id="viewReportContent" style="max-height: 65vh; overflow-y: auto; padding: 24px;">
                <!-- Report content loaded here -->
            </div>
            <div class="modal-footer" id="viewReportFooter" style="padding: 16px 24px; background: #F9FAFB; border-top: 1px solid #E5E7EB;">
                <button class="btn-modal secondary" onclick="closeViewReportModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Block Date Modal -->
    <div id="blockDateModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 450px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-calendar-times" style="margin-right: 8px;"></i>Block Date</h3>
            </div>
            <div class="modal-body">
                <form id="blockDateForm">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="text" id="blockDateDisplay" class="form-input" readonly style="background: #f9fafb;">
                        <input type="hidden" id="blockDateValue">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Number of Days to Block</label>
                        <input type="number" id="blockDateDays" class="form-input" value="1" min="1" max="365" placeholder="e.g., 7" onchange="updateBlockDateRange()">
                        <small style="color: #6B7280; font-size: 12px; margin-top: 4px; display: block;">Enter how many consecutive days to block starting from the selected date</small>
                        <div id="blockDateRangeDisplay" style="margin-top: 8px; padding: 8px 12px; background: #F3F4F6; border-radius: 6px; font-size: 13px; color: #374151; display: none;">
                            <i class="fas fa-calendar-alt" style="color: #7B1D3A; margin-right: 6px;"></i>
                            <span id="blockDateRangeText"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Reason</label>
                        <select id="blockDateReason" class="form-select" required>
                            <option value="">-- Select Reason --</option>
                            <option value="unavailable">Not Available</option>
                            <option value="no_work">No Work</option>
                            <option value="holiday">Holiday</option>
                            <option value="sick">Sick Day</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description (Optional)</label>
                        <input type="text" id="blockDateDescription" class="form-input" placeholder="e.g., Staff meeting, Building maintenance...">
                    </div>
                </form>

                <!-- Existing bookings warning -->
                <div id="blockDateWarning" style="display: none; background: #FEF3C7; border: 1px solid #F59E0B; border-radius: 8px; padding: 12px; margin-top: 16px;">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fas fa-exclamation-triangle" style="color: #D97706; margin-top: 2px;"></i>
                        <div>
                            <div style="font-weight: 600; color: #92400E; margin-bottom: 4px;">Existing Bookings</div>
                            <div id="blockDateWarningText" style="font-size: 13px; color: #78350F;"></div>
                        </div>
                    </div>
                </div>

                <!-- Blocked date info (if already blocked) -->
                <div id="blockedDateInfo" style="display: none; background: #FEE2E2; border: 1px solid #EF4444; border-radius: 8px; padding: 12px; margin-top: 16px;">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fas fa-ban" style="color: #DC2626; margin-top: 2px;"></i>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #991B1B; margin-bottom: 4px;">Date Already Blocked</div>
                            <div id="blockedDateInfoText" style="font-size: 13px; color: #7F1D1D;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeBlockDateModal()">Cancel</button>
                <button class="btn-modal primary" id="blockDateSubmitBtn" onclick="submitBlockDate()" style="background: #EF4444;">
                    <i class="fas fa-ban"></i> Block Date
                </button>
                <button class="btn-modal primary" id="unblockDateBtn" onclick="unblockDate()" style="display: none; background: #10B981;">
                    <i class="fas fa-check"></i> Unblock Date
                </button>
            </div>
        </div>
    </div>

    <!-- Booking Action Modal -->
    <div id="bookingActionModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 550px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-calendar-check" style="margin-right: 8px;"></i>Booking Details</h3>
            </div>
            <div class="modal-body">
                <!-- Booking Info Card -->
                <div style="background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                        <div>
                            <div style="font-size: 12px; opacity: 0.8; margin-bottom: 4px;">Agency / Organization</div>
                            <div id="modalAgencyName" style="font-size: 18px; font-weight: 700;"></div>
                        </div>
                        <span id="modalStatusBadge" class="status-badge" style="background: #FEF3C7; color: #D97706;">Pending</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="font-size: 12px; opacity: 0.8; margin-bottom: 4px;"><i class="fas fa-calendar"></i> Date</div>
                            <div id="modalBookingDate" style="font-weight: 600;"></div>
                        </div>
                        <div>
                            <div style="font-size: 12px; opacity: 0.8; margin-bottom: 4px;"><i class="fas fa-clock"></i> Time</div>
                            <div id="modalBookingTime" style="font-weight: 600;"></div>
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div style="display: grid; gap: 16px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Purpose</div>
                            <div id="modalPurpose" style="font-weight: 600; color: #1F2937;"></div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Contact Person</div>
                            <div id="modalContactPerson" style="font-weight: 600; color: #1F2937;"></div>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;"><i class="fas fa-envelope"></i> Email</div>
                            <div id="modalEmail" style="color: #1F2937;"></div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;"><i class="fas fa-phone"></i> Phone</div>
                            <div id="modalPhone" style="color: #1F2937;"></div>
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Notes</div>
                        <div id="modalNotes" style="color: #1F2937; font-size: 14px; background: #F9FAFB; padding: 12px; border-radius: 8px;"></div>
                    </div>
                    <div id="modalAttachmentSection">
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Attachment</div>
                        <a id="modalAttachmentLink" href="#" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: #DBEAFE; color: #2563EB; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">
                            <i class="fas fa-file-pdf"></i> View PDF
                        </a>
                    </div>
                </div>

                <!-- Email Notification Section (for approved bookings not yet emailed) -->
                <div id="emailNotificationSection" style="display: none; margin-top: 20px; padding: 16px; background: #FEF3C7; border: 1px solid #F59E0B; border-radius: 12px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <i class="fas fa-envelope" style="font-size: 20px; color: #D97706;"></i>
                        <div>
                            <div style="font-weight: 600; color: #92400E;">Email Not Yet Sent</div>
                            <div style="font-size: 12px; color: #78350F;">The booker has not been notified about the approval.</div>
                        </div>
                    </div>

                    <!-- Email Preview -->
                    <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 16px; margin-bottom: 16px; max-height: 200px; overflow-y: auto;">
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">
                            <strong>To:</strong> <span id="emailPreviewTo"></span>
                        </div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 12px;">
                            <strong>Subject:</strong> <span id="emailPreviewSubject"></span>
                        </div>
                        <div id="emailPreviewBody" style="font-size: 13px; color: #374151; white-space: pre-line; line-height: 1.6;"></div>
                    </div>

                    <!-- Action Buttons -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <button class="btn-modal" onclick="copyEmailContent()" style="background: #3B82F6; color: white; width: 100%;">
                            <i class="fas fa-copy"></i> Copy to Clipboard
                        </button>
                        <button class="btn-modal" onclick="openMailClient()" style="background: #8B5CF6; color: white; width: 100%;">
                            <i class="fas fa-external-link-alt"></i> Open in Email App
                        </button>
                    </div>
                    <button class="btn-modal primary" onclick="markAsEmailed()" style="background: #10B981; width: 100%; margin-top: 12px;">
                        <i class="fas fa-check-circle"></i> Mark as Emailed
                    </button>
                </div>

                <!-- Already Emailed Badge -->
                <div id="emailSentSection" style="display: none; margin-top: 20px; padding: 16px; background: #D1FAE5; border: 1px solid #10B981; border-radius: 12px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-envelope-circle-check" style="font-size: 20px; color: #059669;"></i>
                        <div>
                            <div style="font-weight: 600; color: #065F46;">Email Notification Sent</div>
                            <div style="font-size: 12px; color: #047857;">The booker has been notified about the booking status.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="bookingActionFooter" style="display: flex; gap: 10px; justify-content: flex-end; align-items: center;">
                <button class="btn-modal secondary" onclick="closeBookingActionModal()" style="display: inline-flex; align-items: center; gap: 6px;">Close</button>
                <button class="btn-modal" id="btnRejectBooking" onclick="confirmRejectBooking()" style="background: #EF4444; color: white; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-times"></i> <span>Reject</span>
                </button>
                <button class="btn-modal primary" id="btnApproveBooking" onclick="confirmApproveBooking()" style="background: #10B981; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-check"></i> <span>Approve</span>
                </button>
            </div>
        </div>
    </div>

    <input type="hidden" id="currentBookingId" value="">
    <input type="hidden" id="currentBookingEmail" value="">
    <input type="hidden" id="currentBookingAgency" value="">
    <input type="hidden" id="currentBookingDate" value="">
    <input type="hidden" id="currentBookingTime" value="">
    <input type="hidden" id="currentBookingPurpose" value="">

    <!-- New Task Modal -->
    <div id="newTaskModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Create New Task</h3>
            </div>
            <div class="modal-body">
                <form id="newTaskForm">
                    <div class="form-group">
                        <label class="form-label required">Task Title</label>
                        <input type="text" class="form-input" id="taskTitle" placeholder="Enter task title" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Description</label>
                        <textarea class="form-input form-textarea" id="taskDescription" placeholder="Enter task description" required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Checklist Items (Optional)</label>
                        <textarea class="form-input form-textarea" id="taskChecklistText" placeholder="One item per line&#10;e.g. Planning&#10;Designing&#10;Implementation"></textarea>
                        <p style="font-size: 12px; color: #9CA3AF; margin-top: 6px;">If provided, intern progress will be calculated from checked items.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Assignment Type</label>
                        <div class="form-radio-group">
                            <label class="form-radio-label">
                                <input type="radio" name="assignmentType" value="individual" checked onchange="toggleAssignmentType()">
                                <span>Individual</span>
                            </label>
                            <label class="form-radio-label">
                                <input type="radio" name="assignmentType" value="group" onchange="toggleAssignmentType()">
                                <span>Group</span>
                            </label>
                        </div>
                    </div>

                    <!-- Individual Assignment -->
                    <div class="form-group" id="individualSection">
                        <label class="form-label required">Select Intern</label>
                        <select class="form-input" id="individualIntern">
                            <option value="">-- Select Intern --</option>
                            @foreach(($internsBySchool ?? collect()) as $school => $schoolInterns)
                            <optgroup label="{{ $school }}">
                                @foreach($schoolInterns as $intern)
                                <option value="{{ $intern->id }}" data-school="{{ $intern->school }}">{{ $intern->name }}</option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                        @if(($interns ?? collect())->isEmpty())
                        <p style="font-size: 12px; color: #9CA3AF; margin-top: 8px;">
                            <i class="fas fa-info-circle"></i> No interns registered yet. Interns will appear here once they register.
                        </p>
                        @endif
                    </div>

                    <!-- Group Assignment -->
                    <div class="form-group" id="groupSection" style="display: none;">
                        <label class="form-label required">Select School</label>
                        <select class="form-input" id="schoolSelect" onchange="loadInternsBySchool()">
                            <option value="">-- Select School --</option>
                            @foreach(($internsBySchool ?? collect())->keys() as $school)
                            <option value="{{ Str::slug($school) }}">{{ $school }}</option>
                            @endforeach
                            <option value="mixed">Mixed Schools (All)</option>
                        </select>
                    </div>

                    <div class="form-group" id="internsSection" style="display: none;">
                        <label class="form-label required">Select Group Members</label>
                        <div class="intern-select-grid" id="internsGrid">
                            <!-- Interns will be loaded here dynamically -->
                        </div>
                    </div>

                    <div class="form-group" id="leaderSection" style="display: none;">
                        <label class="form-label required">Assign Group Leader</label>
                        <select class="form-input" id="groupLeader">
                            <option value="">-- Select Leader --</option>
                            <!-- Leaders will be populated based on selected members -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Priority Level</label>
                        <select class="form-input" id="priorityLevel" required>
                            <option value="">-- Select Priority --</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Due Date</label>
                        <input type="date" class="form-input" id="dueDate" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeNewTaskModal()">Cancel</button>
                <button class="btn-modal primary" onclick="createTask()">Create Task</button>
            </div>
        </div>
    </div>

    <!-- View Task Modal -->
    <div id="viewTaskModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Task Details</h3>
            </div>
            <div class="modal-body" id="taskDetailsContent">
                <!-- Task details will be loaded here dynamically -->
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeViewTaskModal()">Close</button>
                <button class="btn-modal primary" onclick="editTaskFromView()"><i class="fas fa-edit"></i> Edit Task</button>
            </div>
        </div>
    </div>

    <!-- ========== RESEARCH TRACKING MODALS ========== -->

    <!-- View Document Details Modal -->
    <div id="documentDetailsModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-file-alt" style="margin-right: 8px;"></i>Document Details</h3>
            </div>
            <div class="modal-body" id="documentDetailsContent">
                <!-- Document details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeDocumentDetailsModal()">Close</button>
                <button class="btn-modal primary" onclick="openReviewDocumentModal()">
                    <i class="fas fa-clipboard-check"></i> Review Document
                </button>
                <a id="documentDownloadBtn" href="#" target="_blank" class="btn-modal" style="text-decoration: none; background: #10B981; color: white;">
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
        </div>
    </div>

    <!-- Review Document Modal -->
    <div id="reviewDocumentModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-clipboard-check" style="margin-right: 8px;"></i>Review Document</h3>
            </div>
            <div class="modal-body">
                <form id="reviewDocumentForm">
                    <input type="hidden" id="reviewDocId">

                    <div class="form-group">
                        <label class="form-label">Document Info</label>
                        <div id="reviewDocInfo" style="background: #F3F4F6; padding: 12px; border-radius: 8px; font-size: 14px;"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Review Action</label>
                        <select id="reviewDocAction" class="form-select" required onchange="toggleReviewNotes()">
                            <option value="">-- Select Action --</option>
                            <option value="under_review">Mark as Under Review</option>
                            <option value="approved">Approve Document</option>
                            <option value="rejected">Reject Document</option>
                        </select>
                    </div>

                    <div class="form-group" id="reviewNotesGroup">
                        <label class="form-label">Admin Notes</label>
                        <textarea id="reviewDocNotes" class="form-input" rows="3" placeholder="Add notes for this review..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeReviewDocumentModal()">Cancel</button>
                <button class="btn-modal primary" onclick="submitDocumentReview()">
                    <i class="fas fa-check"></i> Submit Review
                </button>
            </div>
        </div>
    </div>

    <!-- ========== INCUBATEE TRACKER MODALS ========== -->

    <!-- View MOA Details Modal -->
    <div id="moaDetailsModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 650px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-file-contract" style="margin-right: 8px;"></i>MOA Request Details</h3>
            </div>
            <div class="modal-body" id="moaDetailsContent">
                <!-- MOA details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeMoaDetailsModal()">Close</button>
                <button class="btn-modal" style="background: #10B981; color: white;" onclick="openReviewMoaModal()">
                    <i class="fas fa-clipboard-check"></i> Review MOA
                </button>
                <button class="btn-modal primary" onclick="generateMoaFromTemplate()">
                    <i class="fas fa-file-word"></i> Generate MOA
                </button>
            </div>
        </div>
    </div>

    <!-- Review MOA Modal -->
    <div id="reviewMoaModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-clipboard-check" style="margin-right: 8px;"></i>Review MOA Request</h3>
            </div>
            <div class="modal-body">
                <form id="reviewMoaForm">
                    <input type="hidden" id="reviewMoaId">

                    <div class="form-group">
                        <label class="form-label">MOA Info</label>
                        <div id="reviewMoaInfo" style="background: #F3F4F6; padding: 12px; border-radius: 8px; font-size: 14px;"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Review Action</label>
                        <select id="reviewMoaAction" class="form-select" required>
                            <option value="">-- Select Action --</option>
                            <option value="under_review">Mark as Under Review</option>
                            <option value="approved">Approve MOA Request</option>
                            <option value="rejected">Reject MOA Request</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Admin Notes</label>
                        <textarea id="reviewMoaNotes" class="form-input" rows="3" placeholder="Add notes for this review..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeReviewMoaModal()">Cancel</button>
                <button class="btn-modal primary" onclick="submitMoaReview()">
                    <i class="fas fa-check"></i> Submit Review
                </button>
            </div>
        </div>
    </div>

    <!-- MOA Template Modal -->
    <div id="moaTemplateModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 900px; max-height: 90vh;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-file-word" style="margin-right: 8px;"></i>MOA Template Generator</h3>
            </div>
            <div class="modal-body" style="max-height: 65vh; overflow-y: auto;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <!-- Form Section -->
                    <div>
                        <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 16px; color: #1F2937;">
                            <i class="fas fa-edit" style="color: #7B1D3A; margin-right: 8px;"></i>MOA Details
                        </h4>
                        <form id="moaTemplateForm">
                            <div class="form-group">
                                <label class="form-label required">Company/Startup Name</label>
                                <input type="text" id="moaCompanyName" class="form-input" placeholder="Enter company name" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Representative Name</label>
                                <input type="text" id="moaRepresentative" class="form-input" placeholder="Enter representative name" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Position/Title</label>
                                <input type="text" id="moaPosition" class="form-input" placeholder="e.g., CEO, Founder, Manager" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Business Address</label>
                                <textarea id="moaAddress" class="form-input" rows="2" placeholder="Enter complete business address" required></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">MOA Purpose</label>
                                <select id="moaPurpose" class="form-select" required>
                                    <option value="">-- Select Purpose --</option>
                                    <option value="incubation">Business Incubation Program</option>
                                    <option value="coworking">Co-working Space Usage</option>
                                    <option value="mentorship">Mentorship Program</option>
                                    <option value="partnership">Partnership Agreement</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Duration</label>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                    <div>
                                        <label style="font-size: 12px; color: #6B7280;">Start Date</label>
                                        <input type="date" id="moaStartDate" class="form-input" required>
                                    </div>
                                    <div>
                                        <label style="font-size: 12px; color: #6B7280;">End Date</label>
                                        <input type="date" id="moaEndDate" class="form-input" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Monthly Fee (if applicable)</label>
                                <input type="number" id="moaFee" class="form-input" placeholder="0.00" step="0.01" min="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Special Terms/Conditions</label>
                                <textarea id="moaTerms" class="form-input" rows="3" placeholder="Enter any special terms or conditions..."></textarea>
                            </div>
                            <button type="button" class="btn-modal primary" style="width: 100%;" onclick="generateMoaPreview()">
                                <i class="fas fa-eye"></i> Generate Preview
                            </button>
                        </form>
                    </div>

                    <!-- Preview Section -->
                    <div>
                        <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 16px; color: #1F2937;">
                            <i class="fas fa-file-alt" style="color: #7B1D3A; margin-right: 8px;"></i>MOA Preview
                        </h4>
                        <div id="moaPreviewContent" style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 24px; font-size: 12px; line-height: 1.6; max-height: 500px; overflow-y: auto;">
                            <div style="text-align: center; color: #9CA3AF; padding: 40px;">
                                <i class="fas fa-file-alt" style="font-size: 48px; margin-bottom: 16px;"></i>
                                <p>Fill in the form and click "Generate Preview" to see the MOA document</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeMoaTemplateModal()">Cancel</button>
                <button class="btn-modal" style="background: #3B82F6; color: white;" onclick="printMoa()">
                    <i class="fas fa-print"></i> Print MOA
                </button>
                <button class="btn-modal primary" onclick="downloadMoaAsPdf()">
                    <i class="fas fa-download"></i> Download PDF
                </button>
            </div>
        </div>
    </div>

    <!-- View Payment Details Modal -->
    <div id="paymentDetailsModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 900px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-credit-card" style="margin-right: 8px;"></i>Payment Submission Details</h3>
            </div>
            <div class="modal-body" id="paymentDetailsContent">
                <!-- Payment details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closePaymentDetailsModal()">Close</button>
                <button class="btn-modal primary" onclick="openReviewPaymentModal()">
                    <i class="fas fa-clipboard-check"></i> Review Payment
                </button>
            </div>
        </div>
    </div>

    <!-- Review Payment Modal -->
    <div id="reviewPaymentModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-clipboard-check" style="margin-right: 8px;"></i>Review Payment</h3>
            </div>
            <div class="modal-body">
                <form id="reviewPaymentForm">
                    <input type="hidden" id="reviewPaymentId">

                    <div class="form-group">
                        <label class="form-label">Payment Info</label>
                        <div id="reviewPaymentInfo" style="background: #F3F4F6; padding: 12px; border-radius: 8px; font-size: 14px;"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Verification Action</label>
                        <select id="reviewPaymentAction" class="form-select" required>
                            <option value="">-- Select Action --</option>
                            <option value="under_review">Mark as Under Review</option>
                            <option value="approved">Verify Payment</option>
                            <option value="rejected">Reject Payment</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Admin Notes</label>
                        <textarea id="reviewPaymentNotes" class="form-input" rows="3" placeholder="Add notes for this verification..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeReviewPaymentModal()">Cancel</button>
                <button class="btn-modal primary" onclick="submitPaymentReview()">
                    <i class="fas fa-check"></i> Submit Review
                </button>
            </div>
        </div>
    </div>

    <!-- ========== ISSUES & COMPLAINTS MODALS ========== -->

    <!-- View Room Issue Details Modal -->
    <div id="roomIssueDetailsModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 520px; width: 95%;">
            <div class="modal-header" style="padding: 16px 20px;">
                <h3 class="modal-title" style="font-size: 16px;"><i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>Room Issue Details</h3>
            </div>
            <div class="modal-body" id="roomIssueDetailsContent" style="padding: 16px 20px; max-height: 60vh; overflow-y: auto;">
                <!-- Issue details will be loaded here -->
            </div>
            <div class="modal-footer" style="padding: 12px 20px;">
                <button class="btn-modal secondary" style="padding: 8px 16px; font-size: 13px;" onclick="closeRoomIssueDetailsModal()">Close</button>
                <button class="btn-modal primary" style="padding: 8px 16px; font-size: 13px;" onclick="openUpdateIssueStatusModal()">
                    <i class="fas fa-edit"></i> Update Status
                </button>
            </div>
        </div>
    </div>

    <!-- Update Issue Status Modal -->
    <div id="updateIssueStatusModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 420px; width: 95%;">
            <div class="modal-header" style="padding: 16px 20px;">
                <h3 class="modal-title" style="font-size: 16px;"><i class="fas fa-edit" style="margin-right: 8px;"></i>Update Issue Status</h3>
            </div>
            <div class="modal-body" style="padding: 16px 20px; max-height: 60vh; overflow-y: auto;">
                <form id="updateIssueStatusForm">
                    <input type="hidden" id="updateIssueId">

                    <div class="form-group" style="margin-bottom: 14px;">
                        <label class="form-label" style="font-size: 12px; margin-bottom: 4px;">Issue Info</label>
                        <div id="updateIssueInfo" style="background: #F3F4F6; padding: 10px 12px; border-radius: 6px; font-size: 13px;"></div>
                    </div>

                    <div class="form-group" style="margin-bottom: 14px;">
                        <label class="form-label required" style="font-size: 12px; margin-bottom: 4px;">New Status</label>
                        <select id="updateIssueNewStatus" class="form-select" required style="padding: 10px 12px; font-size: 13px;">
                            <option value="">-- Select Status --</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 14px;">
                        <label class="form-label" style="font-size: 12px; margin-bottom: 4px;">Assigned To (Optional)</label>
                        <input type="text" id="updateIssueAssignee" class="form-input" placeholder="Enter assignee name" style="padding: 10px 12px; font-size: 13px;">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 12px; margin-bottom: 4px;">Resolution Notes</label>
                        <textarea id="updateIssueNotes" class="form-input" rows="3" placeholder="Add resolution notes..." style="padding: 10px 12px; font-size: 13px; resize: vertical;"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="padding: 12px 20px;">
                <button class="btn-modal secondary" style="padding: 8px 16px; font-size: 13px;" onclick="closeUpdateIssueStatusModal()">Cancel</button>
                <button class="btn-modal primary" style="padding: 8px 16px; font-size: 13px;" onclick="submitIssueStatusUpdate()">
                    <i class="fas fa-check"></i> Update Status
                </button>
            </div>
        </div>
    </div>

    <!-- ========== SCHOOL MANAGEMENT MODALS ========== -->

    <!-- School Management Modal -->
    <div id="schoolManagementModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 900px; max-height: 90vh;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-university" style="margin-right: 8px;"></i>School Management</h3>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <!-- Add New School Button -->
                <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <h4 style="margin: 0; color: #1F2937; font-size: 16px;">
                        <i class="fas fa-list" style="color: #7B1D3A; margin-right: 8px;"></i>Registered Schools
                    </h4>
                    <button onclick="openAddSchoolForm()" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; border: none; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-plus"></i> Add New School
                    </button>
                </div>

                <!-- Add/Edit School Form (Hidden by default) -->
                <div id="schoolFormContainer" style="display: none; background: #F9FAFB; border-radius: 12px; padding: 20px; margin-bottom: 20px; border: 2px solid #E5E7EB;">
                    <h5 style="margin: 0 0 16px 0; color: #1F2937;" id="schoolFormTitle">
                        <i class="fas fa-plus-circle" style="color: #10B981; margin-right: 8px;"></i>Add New School
                    </h5>
                    <form id="schoolForm">
                        <input type="hidden" id="schoolFormId">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="form-group" style="margin-bottom: 12px;">
                                <label class="form-label required">School Name</label>
                                <input type="text" id="schoolFormName" class="form-input" placeholder="e.g., University of the Philippines Cebu" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 12px;">
                                <label class="form-label required">Required Hours</label>
                                <input type="number" id="schoolFormHours" class="form-input" placeholder="e.g., 500" min="1" max="2000" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 12px;">
                                <label class="form-label">Max Interns</label>
                                <input type="number" id="schoolFormMaxInterns" class="form-input" placeholder="Leave empty for unlimited" min="1">
                                <small style="color: #6B7280; font-size: 11px;">Maximum number of interns allowed (leave empty for no limit)</small>
                            </div>
                            <div class="form-group" style="margin-bottom: 12px;">
                                <label class="form-label">Contact Person</label>
                                <input type="text" id="schoolFormContactPerson" class="form-input" placeholder="e.g., Dr. Juan Dela Cruz">
                            </div>
                            <div class="form-group" style="margin-bottom: 12px;">
                                <label class="form-label">Contact Email</label>
                                <input type="email" id="schoolFormContactEmail" class="form-input" placeholder="e.g., contact@school.edu.ph">
                            </div>
                            <div class="form-group" style="margin-bottom: 12px;">
                                <label class="form-label">Contact Phone</label>
                                <input type="text" id="schoolFormContactPhone" class="form-input" placeholder="e.g., 09XX XXX XXXX">
                            </div>
                            <div class="form-group" style="margin-bottom: 12px;">
                                <label class="form-label">Notes</label>
                                <input type="text" id="schoolFormNotes" class="form-input" placeholder="Any additional notes...">
                            </div>
                        </div>
                        <div style="display: flex; gap: 12px; margin-top: 16px;">
                            <button type="button" onclick="cancelSchoolForm()" style="background: #E5E7EB; color: #374151; border: none; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                Cancel
                            </button>
                            <button type="submit" style="background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-save" style="margin-right: 6px;"></i><span id="schoolFormSubmitText">Save School</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Schools Table -->
                <div id="schoolsTableContainer">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #F3F4F6;">
                                <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #6B7280;">School Name</th>
                                <th style="padding: 12px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #6B7280;">Req. Hours</th>
                                <th style="padding: 12px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #6B7280;">Interns</th>
                                <th style="padding: 12px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #6B7280;">Capacity</th>
                                <th style="padding: 12px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #6B7280;">Total Rendered</th>
                                <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #6B7280;">Contact Person</th>
                                <th style="padding: 12px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #6B7280;">Status</th>
                                <th style="padding: 12px 16px; text-align: center; font-size: 13px; font-weight: 600; color: #6B7280;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="schoolsTableBody">
                            @forelse($schools ?? [] as $school)
                            <tr id="school-row-{{ $school->id }}" style="border-bottom: 1px solid #E5E7EB;">
                                <td style="padding: 14px 16px;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #7B1D3A, #5a1428); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-university" style="color: #FFBF00; font-size: 16px;"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: #1F2937;">{{ $school->name }}</div>
                                            @if($school->contact_email)
                                            <div style="font-size: 12px; color: #6B7280;">{{ $school->contact_email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <span style="background: #DBEAFE; color: #1E40AF; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">{{ $school->required_hours }} hrs</span>
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 2px;">
                                        <span style="font-weight: 700; color: #1F2937; font-size: 18px;">{{ $school->total_interns ?? 0 }}</span>
                                        @if(($school->pending_interns ?? 0) > 0)
                                        <span style="background: #FEF3C7; color: #92400E; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 600;">+{{ $school->pending_interns }} pending</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    @if($school->max_interns)
                                        @php
                                            $currentInterns = $school->total_interns ?? 0;
                                            $maxInterns = $school->max_interns;
                                            $percentage = ($currentInterns / $maxInterns) * 100;
                                            $isFull = $currentInterns >= $maxInterns;
                                            $isNearFull = $percentage >= 80;
                                        @endphp
                                        <div style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                                            <span style="font-weight: 600; font-size: 13px; {{ $isFull ? 'color: #DC2626;' : ($isNearFull ? 'color: #D97706;' : 'color: #059669;') }}">
                                                {{ $currentInterns }}/{{ $maxInterns }}
                                            </span>
                                            <div style="width: 60px; height: 6px; background: #E5E7EB; border-radius: 3px; overflow: hidden;">
                                                <div style="width: {{ min($percentage, 100) }}%; height: 100%; background: {{ $isFull ? '#DC2626' : ($isNearFull ? '#D97706' : '#059669') }}; border-radius: 3px;"></div>
                                            </div>
                                            @if($isFull)
                                            <span style="background: #FEE2E2; color: #DC2626; padding: 2px 6px; border-radius: 8px; font-size: 9px; font-weight: 600;">FULL</span>
                                            @endif
                                        </div>
                                    @else
                                        <span style="color: #9CA3AF; font-style: italic; font-size: 12px;">Unlimited</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <span style="font-weight: 600; color: #059669;">{{ number_format($school->total_rendered_hours ?? 0) }} hrs</span>
                                </td>
                                <td style="padding: 14px 16px;">
                                    @if($school->contact_person)
                                    <div style="font-weight: 500; color: #1F2937;">{{ $school->contact_person }}</div>
                                    @if($school->contact_phone)
                                    <div style="font-size: 12px; color: #6B7280;">{{ $school->contact_phone }}</div>
                                    @endif
                                    @else
                                    <span style="color: #9CA3AF; font-style: italic;">Not set</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; {{ $school->status === 'Active' ? 'background: #D1FAE5; color: #065F46;' : 'background: #FEE2E2; color: #991B1B;' }}">
                                        {{ $school->status }}
                                    </span>
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <div style="display: flex; justify-content: center; gap: 6px;">
                                        <button onclick="editSchool({{ $school->id }}, '{{ addslashes($school->name) }}', {{ $school->required_hours }}, {{ $school->max_interns ?? 'null' }}, '{{ addslashes($school->contact_person ?? '') }}', '{{ addslashes($school->contact_email ?? '') }}', '{{ addslashes($school->contact_phone ?? '') }}', '{{ addslashes($school->notes ?? '') }}')" style="background: #DBEAFE; color: #1E40AF; border: none; width: 32px; height: 32px; border-radius: 6px; cursor: pointer;" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="toggleSchoolStatus({{ $school->id }})" style="background: {{ $school->status === 'Active' ? '#FEF3C7' : '#D1FAE5' }}; color: {{ $school->status === 'Active' ? '#92400E' : '#065F46' }}; border: none; width: 32px; height: 32px; border-radius: 6px; cursor: pointer;" title="{{ $school->status === 'Active' ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $school->status === 'Active' ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                        </button>
                                        @if(($school->total_interns ?? 0) == 0)
                                        <button onclick="deleteSchool({{ $school->id }}, '{{ addslashes($school->name) }}')" style="background: #FEE2E2; color: #991B1B; border: none; width: 32px; height: 32px; border-radius: 6px; cursor: pointer;" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="noSchoolsRow">
                                <td colspan="8" style="padding: 40px; text-align: center; color: #9CA3AF;">
                                    <i class="fas fa-university" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                                    No schools registered yet. Click "Add New School" to get started.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeSchoolManagementModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Reject Intern Modal -->
    <div id="rejectInternModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 450px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-user-times" style="margin-right: 8px; color: #DC2626;"></i>Reject Intern</h3>
            </div>
            <div class="modal-body">
                <input type="hidden" id="rejectInternId">
                <div style="background: #FEE2E2; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                    <p style="margin: 0; color: #991B1B; font-size: 14px;">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                        You are about to reject <strong id="rejectInternName"></strong>'s application.
                    </p>
                </div>
                <div class="form-group">
                    <label class="form-label required">Reason for Rejection</label>
                    <textarea id="rejectInternReason" class="form-input" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeRejectInternModal()">Cancel</button>
                <button class="btn-modal primary" onclick="submitRejectIntern()" style="background: linear-gradient(135deg, #DC2626, #B91C1C);">
                    <i class="fas fa-times"></i> Reject Intern
                </button>
            </div>
        </div>
    </div>

    <!-- View Intern Details Modal -->
    <div id="internDetailsModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-user-graduate" style="margin-right: 8px; color: #7B1D3A;"></i>Intern Details</h3>
            </div>
            <div class="modal-body">
                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 24px; padding: 20px; background: linear-gradient(135deg, #FEF3C7, #FDE68A); border-radius: 12px;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700; font-size: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; cursor: pointer; position: relative;" id="internDetailAvatar" onclick="zoomProfilePicture()" title="Click to view full size">
                        A
                        <div id="zoomHoverOverlay" style="display: none; position: absolute; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; color: white; font-size: 20px;">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </div>
                    <div style="flex: 1;">
                        <h4 style="margin: 0 0 4px 0; font-size: 22px; font-weight: 700; color: #1F2937;" id="internDetailName">Loading...</h4>
                        <p style="margin: 0; color: #6B7280; font-size: 14px;" id="internDetailRefCode">REF-000000</p>
                        <div style="margin-top: 8px;">
                            <span class="status-badge" id="internDetailStatus" style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 16px;">
                        <div style="color: #6B7280; font-size: 12px; margin-bottom: 4px;">Email</div>
                        <div style="color: #1F2937; font-weight: 600;" id="internDetailEmail">-</div>
                    </div>
                    <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 16px;">
                        <div style="color: #6B7280; font-size: 12px; margin-bottom: 4px;">Phone</div>
                        <div style="color: #1F2937; font-weight: 600;" id="internDetailPhone">-</div>
                    </div>
                    <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 16px;">
                        <div style="color: #6B7280; font-size: 12px; margin-bottom: 4px;">School</div>
                        <div style="color: #1F2937; font-weight: 600;" id="internDetailSchool">-</div>
                    </div>
                    <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 16px;">
                        <div style="color: #6B7280; font-size: 12px; margin-bottom: 4px;">Course</div>
                        <div style="color: #1F2937; font-weight: 600;" id="internDetailCourse">-</div>
                    </div>
                </div>

                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                    <h5 style="margin: 0 0 16px 0; font-size: 14px; font-weight: 700; color: #1F2937;"><i class="fas fa-clock" style="margin-right: 8px; color: #7B1D3A;"></i>Hours Progress</h5>
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                        <div class="progress-bar-container" style="flex: 1; height: 12px;">
                            <div class="progress-bar" id="internDetailProgress" style="width: 0%;"></div>
                        </div>
                        <span style="font-weight: 700; color: #7B1D3A; font-size: 16px;" id="internDetailProgressPercent">0%</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; color: #6B7280;">
                        <span><strong id="internDetailCompletedHours">0</strong> hours completed</span>
                        <span><strong id="internDetailRequiredHours">0</strong> hours required</span>
                    </div>
                </div>

                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                    <h5 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 700; color: #1F2937;"><i class="fas fa-calendar" style="margin-right: 8px; color: #7B1D3A;"></i>Timeline</h5>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                        <div>
                            <div style="color: #6B7280; font-size: 12px; margin-bottom: 4px;">Start Date</div>
                            <div style="color: #1F2937; font-weight: 600;" id="internDetailStartDate">-</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="color: #6B7280; font-size: 12px; margin-bottom: 4px;">End Date</div>
                            <div style="color: #1F2937; font-weight: 600;" id="internDetailEndDate">-</div>
                        </div>
                    </div>
                    <div>
                        <div style="color: #6B7280; font-size: 12px; margin-bottom: 4px;">Registered On</div>
                        <div style="color: #1F2937; font-weight: 600;" id="internDetailRegisteredOn">-</div>
                    </div>
                </div>

                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 20px;">
                    <h5 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 700; color: #1F2937;"><i class="fas fa-info-circle" style="margin-right: 8px; color: #7B1D3A;"></i>Additional Information</h5>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <div style="color: #6B7280; font-size: 12px; margin-bottom: 4px;">Year Level</div>
                            <div style="color: #1F2937; font-weight: 600;" id="internDetailYearLevel">-</div>
                        </div>
                        <div>
                            <div style="color: #6B7280; font-size: 12px; margin-bottom: 4px;">Address</div>
                            <div style="color: #1F2937; font-weight: 600;" id="internDetailAddress">-</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button class="btn-modal secondary" onclick="closeInternDetailsModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Edit Intern Modal -->
    <div id="editInternModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-user-edit" style="margin-right: 8px; color: #7B1D3A;"></i>Edit Intern</h3>
            </div>
            <div class="modal-body">
                <form id="editInternForm">
                    <input type="hidden" id="editInternId">

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label required">Full Name</label>
                            <input type="text" id="editInternName" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label required">Email</label>
                            <input type="email" id="editInternEmail" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" id="editInternPhone" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Course</label>
                            <input type="text" id="editInternCourse" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Year Level</label>
                            <input type="text" id="editInternYearLevel" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Required Hours</label>
                            <input type="number" id="editInternRequiredHours" class="form-input" min="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Start Date</label>
                            <input type="date" id="editInternStartDate" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">End Date</label>
                            <input type="date" id="editInternEndDate" class="form-input">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select id="editInternStatus" class="form-select">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeEditInternModal()">Cancel</button>
                <button class="btn-modal primary" onclick="saveInternChanges()">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Create/Edit Event Modal -->
    <div id="eventModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 10000; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 16px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div style="background: linear-gradient(135deg, #7B1D3A, #5a1428); padding: 24px; color: white; border-radius: 16px 16px 0 0;">
                <h2 id="eventModalTitle" style="margin: 0; font-size: 20px; font-weight: 700;">Create Event</h2>
            </div>
            <div style="padding: 24px;">
                <input type="hidden" id="eventId">

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Event Title *</label>
                    <input type="text" id="eventTitle" style="width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;" placeholder="Enter event title">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Description</label>
                    <textarea id="eventDescription" style="width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px; resize: vertical; min-height: 100px;" placeholder="Enter event description"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div>
                        <label id="startDateLabel" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Start Date & Time *</label>
                        <input type="datetime-local" id="eventStartDate" style="width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;">
                    </div>
                    <div>
                        <label id="endDateLabel" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">End Date & Time *</label>
                        <input type="datetime-local" id="eventEndDate" style="width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" id="eventAllDay" onchange="toggleAllDayEvent()" style="width: 18px; height: 18px; cursor: pointer;">
                        <span style="font-size: 14px; font-weight: 600; color: #374151;">All Day Event</span>
                    </label>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Location</label>
                    <input type="text" id="eventLocation" style="width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;" placeholder="Enter event location">
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Color</label>
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <input type="color" id="eventColor" value="#3B82F6" style="width: 60px; height: 40px; border: 1px solid #E5E7EB; border-radius: 6px; cursor: pointer;">
                        <div style="display: flex; gap: 8px;">
                            <button type="button" onclick="document.getElementById('eventColor').value='#3B82F6'" style="width: 32px; height: 32px; background: #3B82F6; border: 2px solid #E5E7EB; border-radius: 6px; cursor: pointer;"></button>
                            <button type="button" onclick="document.getElementById('eventColor').value='#10B981'" style="width: 32px; height: 32px; background: #10B981; border: 2px solid #E5E7EB; border-radius: 6px; cursor: pointer;"></button>
                            <button type="button" onclick="document.getElementById('eventColor').value='#F59E0B'" style="width: 32px; height: 32px; background: #F59E0B; border: 2px solid #E5E7EB; border-radius: 6px; cursor: pointer;"></button>
                            <button type="button" onclick="document.getElementById('eventColor').value='#EF4444'" style="width: 32px; height: 32px; background: #EF4444; border: 2px solid #E5E7EB; border-radius: 6px; cursor: pointer;"></button>
                            <button type="button" onclick="document.getElementById('eventColor').value='#8B5CF6'" style="width: 32px; height: 32px; background: #8B5CF6; border: 2px solid #E5E7EB; border-radius: 6px; cursor: pointer;"></button>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; justify-content: space-between; align-items: center;">
                    <button id="eventDeleteBtn" onclick="deleteEventFromModal()" style="padding: 12px 24px; background: #FEE2E2; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; color: #DC2626; display: none; align-items: center; gap: 8px;">
                        <i class="fas fa-trash"></i> Delete Event
                    </button>
                    <div style="display: flex; gap: 12px;">
                        <button onclick="closeEventModal()" style="padding: 12px 24px; background: #F3F4F6; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; color: #374151;">Cancel</button>
                        <button onclick="saveEvent()" style="padding: 12px 24px; background: linear-gradient(135deg, #7B1D3A, #5a1428); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-save"></i> Save Event
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Authentication is handled server-side by Laravel middleware
        // User data is passed from the controller
        document.addEventListener('DOMContentLoaded', function() {
            // User is already authenticated via Laravel middleware
            // Update user info in header with Laravel data
            const userName = @json($user->name ?? 'Admin User');
            const userEmail = @json($user->email ?? 'admin@upcebu.edu.ph');

            const userNameEl = document.getElementById('userName');
            const userAvatarEl = document.getElementById('userAvatar');
            const userRoleEl = document.getElementById('userRole');

            if (userNameEl) {
                userNameEl.textContent = userName;
            }
            if (userAvatarEl) {
                userAvatarEl.textContent = userName.charAt(0).toUpperCase();
            }
            if (userRoleEl) {
                userRoleEl.textContent = 'Administrator';
            }

            // Restore current page after refresh
            const savedPage = localStorage.getItem('adminCurrentPage');
            if (savedPage) {
                // Hide all pages first
                document.querySelectorAll('.page-content').forEach(page => {
                    page.classList.remove('active');
                });

                // Show the saved page
                const pageElement = document.getElementById(savedPage);
                if (pageElement) {
                    pageElement.classList.add('active');

                    // Update breadcrumb
                    const breadcrumb = document.querySelector('.breadcrumb');
                    if (breadcrumb) {
                        if (savedPage === 'intern-list') {
                            breadcrumb.innerHTML = 'Dashboard > Intern Management > <span>Intern List</span>';
                        } else if (savedPage === 'time-attendance') {
                            breadcrumb.innerHTML = 'Dashboard > Intern Management > <span>Time & Attendance</span>';
                        } else if (savedPage === 'task-assignment') {
                            breadcrumb.innerHTML = 'Dashboard > Intern Management > <span>Task Assignment</span>';
                        } else if (savedPage === 'research-tracking') {
                            breadcrumb.innerHTML = 'Dashboard > <span>Research Tracking</span>';
                        } else if (savedPage === 'incubatee-tracker') {
                            breadcrumb.innerHTML = 'Dashboard > <span>Incubatee Tracker</span>';
                        } else if (savedPage === 'issues-management') {
                            breadcrumb.innerHTML = 'Dashboard > <span>Issues & Complaints</span>';
                        } else if (savedPage === 'digital-records') {
                            breadcrumb.innerHTML = 'Dashboard > <span>Digital Records</span>';
                        } else if (savedPage === 'scheduler') {
                            breadcrumb.innerHTML = 'Dashboard > <span>Scheduler & Events</span>';
                        }
                    }

                    // Update active menu item
                    document.querySelectorAll('.menu-item, .submenu-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    const activeMenuItem = document.querySelector(`[onclick*="'${savedPage}'"]`);
                    if (activeMenuItem) {
                        activeMenuItem.classList.add('active');
                    }
                }
            }

            // Restore dashboard state after page refresh
            const savedTab = localStorage.getItem('activeDashboardTab');

            if (savedTab) {
                const tabElement = document.querySelector(`[data-tab="${savedTab}"]`);
                const contentElement = document.getElementById(savedTab);

                // Hide all tabs
                document.querySelectorAll('.tab-content').forEach(tab => {
                    tab.classList.remove('active');
                });

                // Remove active class from all tab buttons
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active');
                });

                // Show the saved tab
                if (contentElement) {
                    contentElement.classList.add('active');
                }
                if (tabElement) {
                    tabElement.classList.add('active');
                }
            }

            // Save tab state when clicking tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.getAttribute('data-tab');
                    if (tabName) {
                        localStorage.setItem('activeDashboardTab', tabName);
                    }
                });
            });

            // Load notifications on page load
            loadNotifications();
        });

        // Notification System
        let notifications = [];

        // Load previous counts from localStorage (persisted across page reloads)
        function loadStoredCounts() {
            try {
                const stored = JSON.parse(localStorage.getItem('adminNotifCounts') || '{}');
                return stored;
            } catch (e) {
                return {};
            }
        }

        function saveStoredCounts(counts) {
            localStorage.setItem('adminNotifCounts', JSON.stringify(counts));
        }

        let storedCounts = loadStoredCounts();
        let previousBookingCount = storedCounts.booking || 0;
        let previousStartupCount = storedCounts.startup || 0;
        let previousIssueCount = storedCounts.issue || 0;
        let previousInternCount = storedCounts.intern || 0;
        let previousReportCount = storedCounts.report || 0;
        let previousMoaCount = storedCounts.moa || 0;
        let previousPaymentCount = storedCounts.payment || 0;
        let previousDocumentCount = storedCounts.document || 0;
        let previousAbsentCount = storedCounts.absent || 0;
        let previousTaskSubmissionCount = storedCounts.taskSubmission || 0;
        let previousDocUploadCount = storedCounts.docUpload || 0;
        let previousProgressCount = storedCounts.progress || 0;
        let previousTlActivityCount = storedCounts.tlActivity || 0;

        // Track if this is the very first poll (to initialize counts without toasts)
        let isVeryFirstPoll = Object.keys(storedCounts).length === 0;

        // Check if first load summary was already shown today
        function hasShownFirstLoadToday() {
            const today = new Date().toISOString().split('T')[0];
            const lastShown = localStorage.getItem('adminNotifFirstLoadDate');
            return lastShown === today;
        }

        function markFirstLoadShown() {
            const today = new Date().toISOString().split('T')[0];
            localStorage.setItem('adminNotifFirstLoadDate', today);
        }

        let isFirstLoad = !hasShownFirstLoadToday();

        // Get read notification IDs from localStorage
        function getReadNotifications() {
            try {
                return JSON.parse(localStorage.getItem('readNotifications') || '[]');
            } catch (e) {
                return [];
            }
        }

        // Save read notification IDs to localStorage
        function saveReadNotifications(ids) {
            localStorage.setItem('readNotifications', JSON.stringify(ids));
        }

        // Generate unique ID for a notification
        function getNotificationId(type, item) {
            if (type === 'booking') return `booking_${item.id}`;
            if (type === 'startup') return `startup_${item.id}`;
            if (type === 'issue') return `issue_${item.id}`;
            return `${type}_${item.id}`;
        }

        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('active');
        }

        // Admin Profile dropdown toggle
        const adminProfileBtn = document.getElementById('adminProfileBtn');
        const adminProfileDropdown = document.getElementById('adminProfileDropdown');

        if (adminProfileBtn && adminProfileDropdown) {
            adminProfileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                adminProfileDropdown.classList.toggle('active');
            });
        }

        // Navigate to admin profile page
        function navigateToAdminProfile(event) {
            event.preventDefault();
            adminProfileDropdown.classList.remove('active');
            loadPage(event, 'admin-profile');
        }

        // Admin profile picture upload function
        async function uploadAdminProfilePicture(input) {
            if (!input.files || !input.files[0]) return;

            const file = input.files[0];

            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, JPG, or GIF)');
                return;
            }

            const formData = new FormData();
            formData.append('profile_picture', file);

            try {
                const response = await fetch('{{ route("admin.profile.upload-picture") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update the profile picture on the page
                    const container = document.getElementById('adminProfilePictureContainer');
                    const existingImg = document.getElementById('adminProfilePictureImg');
                    const existingInitial = document.getElementById('adminProfilePictureInitial');

                    if (existingImg) {
                        existingImg.src = data.image_url;
                    } else if (existingInitial) {
                        // Replace initial with image
                        const img = document.createElement('img');
                        img.id = 'adminProfilePictureImg';
                        img.src = data.image_url;
                        img.alt = 'Profile';
                        img.style.cssText = 'width: 120px; height: 120px; border-radius: 50%; object-fit: cover; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3); border: 4px solid rgba(255, 255, 255, 0.3);';
                        existingInitial.replaceWith(img);
                    }

                    // Also update header avatar if needed
                    const headerAvatar = document.getElementById('userAvatar');
                    if (headerAvatar) {
                        headerAvatar.innerHTML = `<img src="${data.image_url}" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">`;
                    }

                    alert('Profile picture updated successfully!');
                } else {
                    alert(data.message || 'Failed to upload profile picture');
                }
            } catch (error) {
                console.error('Error uploading profile picture:', error);
                alert('An error occurred while uploading the profile picture');
            }

            // Clear the input
            input.value = '';
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const wrapper = document.querySelector('.notification-wrapper');
            const dropdown = document.getElementById('notificationDropdown');
            if (wrapper && dropdown && !wrapper.contains(event.target)) {
                dropdown.classList.remove('active');
            }

            // Close admin profile dropdown when clicking outside
            if (adminProfileDropdown && !adminProfileDropdown.contains(event.target)) {
                adminProfileDropdown.classList.remove('active');
            }
        });

        async function loadNotifications() {
            try {
                let bookings = [];
                let startups = [];
                let issues = [];
                let pendingInterns = [];
                let teamReports = [];
                let notificationData = {};

                // Fetch pending bookings
                try {
                    const bookingsResponse = await fetch('/admin/bookings');
                    if (bookingsResponse.ok) {
                        const bookingsData = await bookingsResponse.json();
                        bookings = bookingsData.bookings || bookingsData || [];
                    }
                } catch (e) {
                    console.warn('Error fetching bookings:', e);
                }
                const pendingBookings = Array.isArray(bookings) ? bookings.filter(b => b.status === 'pending') : [];

                // Fetch startup submissions
                try {
                    const startupsResponse = await fetch('/admin/startup-submissions');
                    if (startupsResponse.ok) {
                        startups = await startupsResponse.json();
                    }
                } catch (e) {
                    console.warn('Error fetching startups:', e);
                }
                // Categorize startup submissions by type
                const allPendingSubmissions = Array.isArray(startups) ? startups.filter(s => s.status === 'pending') : [];
                const pendingMoa = allPendingSubmissions.filter(s => s.industry === 'moa' || s.type === 'moa');
                const pendingPayments = allPendingSubmissions.filter(s => s.industry === 'finance' || s.type === 'finance' || s.industry === 'payment' || s.type === 'payment');
                const pendingDocuments = allPendingSubmissions.filter(s => s.industry === 'document' || s.type === 'document');
                const pendingStartups = allPendingSubmissions.filter(s => !['moa', 'finance', 'payment', 'document'].includes(s.industry) && !['moa', 'finance', 'payment', 'document'].includes(s.type));

                // Fetch room issues
                try {
                    const issuesResponse = await fetch('/admin/room-issues');
                    if (issuesResponse.ok) {
                        issues = await issuesResponse.json();
                    }
                } catch (e) {
                    console.warn('Error fetching issues:', e);
                }
                const pendingIssues = Array.isArray(issues) ? issues.filter(i => i.status === 'pending' || i.status === 'in_progress') : [];

                // Fetch pending intern registrations
                try {
                    const internsResponse = await fetch('/admin/interns/pending');
                    if (internsResponse.ok) {
                        const internsData = await internsResponse.json();
                        pendingInterns = internsData.interns || internsData || [];
                    }
                } catch (e) {
                    console.warn('Error fetching pending interns:', e);
                }
                pendingInterns = Array.isArray(pendingInterns) ? pendingInterns : [];

                // Fetch team leader reports
                try {
                    const reportsResponse = await fetch('/admin/api/team-reports');
                    if (reportsResponse.ok) {
                        const reportsData = await reportsResponse.json();
                        teamReports = reportsData.reports || reportsData || [];
                    }
                } catch (e) {
                    console.warn('Error fetching team reports:', e);
                }
                const pendingReports = Array.isArray(teamReports) ? teamReports.filter(r => r.status === 'submitted') : [];

                // Fetch additional notification data (absent interns, task submissions, document uploads, progress, TL activities)
                try {
                    const notifResponse = await fetch('/admin/api/notifications');
                    if (notifResponse.ok) {
                        notificationData = await notifResponse.json();
                    }
                } catch (e) {
                    console.warn('Error fetching notification data:', e);
                }

                const absentInterns = notificationData.absent_interns || [];
                const taskSubmissions = notificationData.task_submissions || [];
                const documentUploads = notificationData.document_uploads || [];
                const progressUpdates = notificationData.progress_updates || [];
                const tlActivities = notificationData.team_leader_activities || [];

                // Check for new items and show toast notifications (only if not the very first poll)
                if (!isFirstLoad && !isVeryFirstPoll) {
                    // New bookings
                    if (pendingBookings.length > previousBookingCount) {
                        const newCount = pendingBookings.length - previousBookingCount;
                        const latestBooking = pendingBookings[0];
                        showToast('info', `🗓️ New Booking Request${newCount > 1 ? 's' : ''}!`,
                            newCount > 1
                                ? `${newCount} new booking requests need your attention.`
                                : `${latestBooking.agency_name || 'Someone'} wants to book for ${latestBooking.event_name || 'an event'}.`,
                            6000);
                        playNotificationSound();
                    }

                    // New intern registrations
                    if (pendingInterns.length > previousInternCount) {
                        const newCount = pendingInterns.length - previousInternCount;
                        const latestIntern = pendingInterns[0];
                        showToast('warning', `👤 New Intern Registration${newCount > 1 ? 's' : ''}!`,
                            newCount > 1
                                ? `${newCount} new intern registrations need approval.`
                                : `${latestIntern.name || 'An intern'} has registered and awaits approval.`,
                            6000);
                        playNotificationSound();
                    }

                    // Absent interns (only notify once per day on first detection)
                    if (absentInterns.length > 0 && absentInterns.length !== previousAbsentCount && previousAbsentCount === 0) {
                        showToast('warning', `⏰ Absent Interns Today`,
                            `${absentInterns.length} intern${absentInterns.length > 1 ? 's are' : ' is'} absent today.`,
                            6000);
                    }

                    // New task submissions
                    if (taskSubmissions.length > previousTaskSubmissionCount) {
                        const newCount = taskSubmissions.length - previousTaskSubmissionCount;
                        const latestTask = taskSubmissions[0];
                        showToast('success', `✅ Task Completed${newCount > 1 ? ' (Multiple)' : ''}!`,
                            newCount > 1
                                ? `${newCount} tasks have been completed.`
                                : `${latestTask.intern_name || 'An intern'} completed "${latestTask.title || 'a task'}".`,
                            6000);
                        playNotificationSound();
                    }

                    // New document uploads
                    if (documentUploads.length > previousDocUploadCount) {
                        const newCount = documentUploads.length - previousDocUploadCount;
                        const latestDoc = documentUploads[0];
                        showToast('info', `📤 New File Upload${newCount > 1 ? 's' : ''}!`,
                            newCount > 1
                                ? `${newCount} new files have been uploaded.`
                                : `"${latestDoc.name || 'A file'}" was uploaded by ${latestDoc.uploader || 'someone'}.`,
                            6000);
                        playNotificationSound();
                    }

                    // New team leader reports
                    if (pendingReports.length > previousReportCount) {
                        const newCount = pendingReports.length - previousReportCount;
                        const latestReport = pendingReports[0];
                        showToast('info', `📋 New Team Leader Report${newCount > 1 ? 's' : ''}!`,
                            newCount > 1
                                ? `${newCount} new reports need your review.`
                                : `${latestReport.team_leader_name || 'A team leader'} submitted a ${latestReport.report_type || 'report'}.`,
                            6000);
                        playNotificationSound();
                    }

                    // Team leader activities
                    if (tlActivities.length > previousTlActivityCount) {
                        const newCount = tlActivities.length - previousTlActivityCount;
                        const latestActivity = tlActivities[0];
                        showToast('info', `👥 Team Leader Action${newCount > 1 ? 's' : ''}!`,
                            newCount > 1
                                ? `Team leaders performed ${newCount} new actions.`
                                : `${latestActivity.team_leader_name || 'A team leader'}: ${latestActivity.action || 'performed an action'}.`,
                            6000);
                        playNotificationSound();
                    }

                    // New project progress updates
                    if (progressUpdates.length > previousProgressCount) {
                        const newCount = progressUpdates.length - previousProgressCount;
                        const latestProgress = progressUpdates[0];
                        showToast('success', `📈 Project Progress Update${newCount > 1 ? 's' : ''}!`,
                            newCount > 1
                                ? `${newCount} startups submitted progress updates.`
                                : `${latestProgress.startup_name || 'A startup'} submitted a ${latestProgress.milestone_type || 'progress'} update.`,
                            6000);
                        playNotificationSound();
                    }

                    // New MOA submissions
                    if (pendingMoa.length > previousMoaCount) {
                        const newCount = pendingMoa.length - previousMoaCount;
                        const latestMoa = pendingMoa[0];
                        showToast('success', `📄 New MOA Submission${newCount > 1 ? 's' : ''}!`,
                            newCount > 1
                                ? `${newCount} new MOA requests need review.`
                                : `${latestMoa.startup_name || 'A startup'} submitted an MOA request.`,
                            6000);
                        playNotificationSound();
                    }

                    // New payment submissions
                    if (pendingPayments.length > previousPaymentCount) {
                        const newCount = pendingPayments.length - previousPaymentCount;
                        const latestPayment = pendingPayments[0];
                        showToast('success', `💰 New Payment Submission${newCount > 1 ? 's' : ''}!`,
                            newCount > 1
                                ? `${newCount} new payment submissions need verification.`
                                : `${latestPayment.startup_name || 'A startup'} submitted a payment.`,
                            6000);
                        playNotificationSound();
                    }

                    // New document submissions (startup)
                    if (pendingDocuments.length > previousDocumentCount) {
                        const newCount = pendingDocuments.length - previousDocumentCount;
                        const latestDoc = pendingDocuments[0];
                        showToast('info', `📁 New Document Submission${newCount > 1 ? 's' : ''}!`,
                            newCount > 1
                                ? `${newCount} new documents need review.`
                                : `${latestDoc.startup_name || 'A startup'} submitted a document.`,
                            6000);
                        playNotificationSound();
                    }

                    // New startups (general applications)
                    if (pendingStartups.length > previousStartupCount) {
                        const newCount = pendingStartups.length - previousStartupCount;
                        const latestStartup = pendingStartups[0];
                        showToast('success', `🚀 New Startup Application${newCount > 1 ? 's' : ''}!`,
                            newCount > 1
                                ? `${newCount} new startup applications need review.`
                                : `${latestStartup.startup_name || 'A startup'} has applied for incubation.`,
                            6000);
                        playNotificationSound();
                    }

                    // New issues
                    if (pendingIssues.length > previousIssueCount) {
                        const newCount = pendingIssues.length - previousIssueCount;
                        const latestIssue = pendingIssues[0];
                        showToast('warning', `⚠️ New Issue Reported${newCount > 1 ? 's' : ''}!`,
                            newCount > 1
                                ? `${newCount} new issues require attention.`
                                : `Issue at ${latestIssue.room_location || 'a room'}: ${latestIssue.category || 'Reported'}`,
                            6000);
                        playNotificationSound();
                    }
                } else if (isFirstLoad && !isVeryFirstPoll) {
                    // First load of the day (but not very first poll ever) - show summary if there are pending items
                    const totalPending = pendingBookings.length + pendingInterns.length + pendingReports.length +
                                         pendingMoa.length + pendingPayments.length + pendingDocuments.length +
                                         pendingStartups.length + pendingIssues.length + progressUpdates.length;
                    if (totalPending > 0) {
                        let summaryParts = [];
                        if (pendingBookings.length > 0) summaryParts.push(`${pendingBookings.length} booking${pendingBookings.length > 1 ? 's' : ''}`);
                        if (pendingInterns.length > 0) summaryParts.push(`${pendingInterns.length} intern${pendingInterns.length > 1 ? 's' : ''}`);
                        if (pendingReports.length > 0) summaryParts.push(`${pendingReports.length} report${pendingReports.length > 1 ? 's' : ''}`);
                        if (pendingMoa.length > 0) summaryParts.push(`${pendingMoa.length} MOA${pendingMoa.length > 1 ? 's' : ''}`);
                        if (pendingPayments.length > 0) summaryParts.push(`${pendingPayments.length} payment${pendingPayments.length > 1 ? 's' : ''}`);
                        if (progressUpdates.length > 0) summaryParts.push(`${progressUpdates.length} progress update${progressUpdates.length > 1 ? 's' : ''}`);

                        showToast('info', '📬 Pending Items',
                            `You have ${summaryParts.slice(0, 3).join(', ')}${summaryParts.length > 3 ? ' and more' : ''} awaiting review.`,
                            5000);
                    }

                    // Show absent interns on first load
                    if (absentInterns.length > 0) {
                        showToast('warning', `⏰ Today's Attendance`,
                            `${absentInterns.length} intern${absentInterns.length > 1 ? 's are' : ' is'} currently absent.`,
                            5000);
                    }

                    // Mark first load as shown for today
                    markFirstLoadShown();
                }

                // Update previous counts
                previousBookingCount = pendingBookings.length;
                previousStartupCount = pendingStartups.length;
                previousIssueCount = pendingIssues.length;
                previousInternCount = pendingInterns.length;
                previousReportCount = pendingReports.length;
                previousMoaCount = pendingMoa.length;
                previousPaymentCount = pendingPayments.length;
                previousDocumentCount = pendingDocuments.length;
                previousAbsentCount = absentInterns.length;
                previousTaskSubmissionCount = taskSubmissions.length;
                previousDocUploadCount = documentUploads.length;
                previousProgressCount = progressUpdates.length;
                previousTlActivityCount = tlActivities.length;
                isFirstLoad = false;
                isVeryFirstPoll = false;

                // Persist counts to localStorage so they survive page refresh
                saveStoredCounts({
                    booking: previousBookingCount,
                    startup: previousStartupCount,
                    issue: previousIssueCount,
                    intern: previousInternCount,
                    report: previousReportCount,
                    moa: previousMoaCount,
                    payment: previousPaymentCount,
                    document: previousDocumentCount,
                    absent: previousAbsentCount,
                    taskSubmission: previousTaskSubmissionCount,
                    docUpload: previousDocUploadCount,
                    progress: previousProgressCount,
                    tlActivity: previousTlActivityCount
                });

                // Get read notification IDs
                const readIds = getReadNotifications();

                // Build notifications array (excluding read ones)
                notifications = [];

                // Absent interns (high priority)
                if (absentInterns.length > 0) {
                    const notifId = `absent_today_${new Date().toISOString().split('T')[0]}`;
                    if (!readIds.includes(notifId)) {
                        notifications.push({
                            id: notifId,
                            type: 'absent',
                            icon: 'fa-user-clock',
                            title: 'Absent Interns Today',
                            text: `${absentInterns.length} intern${absentInterns.length > 1 ? 's' : ''} absent - ${absentInterns.slice(0, 2).map(i => i.name).join(', ')}${absentInterns.length > 2 ? '...' : ''}`,
                            time: 'Today',
                            page: 'time-attendance'
                        });
                    }
                }

                // Pending intern registrations
                pendingInterns.forEach(intern => {
                    const notifId = `intern_${intern.id}`;
                    if (!readIds.includes(notifId)) {
                        notifications.push({
                            id: notifId,
                            type: 'intern',
                            icon: 'fa-user-plus',
                            title: 'New Intern Registration',
                            text: `${intern.name || 'Unknown'} - ${intern.school || intern.school_name || 'Unknown School'}`,
                            time: formatTimeAgo(intern.created_at),
                            page: 'intern-list'
                        });
                    }
                });

                // Task submissions
                taskSubmissions.forEach(task => {
                    const notifId = `task_complete_${task.id}`;
                    if (!readIds.includes(notifId)) {
                        notifications.push({
                            id: notifId,
                            type: 'task',
                            icon: 'fa-check-circle',
                            title: 'Task Completed',
                            text: `${task.intern_name || 'Intern'} completed "${task.title || 'task'}"`,
                            time: formatTimeAgo(task.completed_at),
                            page: 'task-assignment'
                        });
                    }
                });

                // Document uploads (intern files)
                documentUploads.forEach(doc => {
                    const notifId = `upload_${doc.id}`;
                    if (!readIds.includes(notifId)) {
                        notifications.push({
                            id: notifId,
                            type: 'upload',
                            icon: 'fa-cloud-upload-alt',
                            title: 'File Uploaded',
                            text: `${doc.uploader || 'Someone'} uploaded "${doc.name || 'file'}"`,
                            time: formatTimeAgo(doc.created_at),
                            page: 'digital-records'
                        });
                    }
                });

                // Pending team leader reports
                pendingReports.forEach(report => {
                    const notifId = `report_${report.id}`;
                    if (!readIds.includes(notifId)) {
                        notifications.push({
                            id: notifId,
                            type: 'report',
                            icon: 'fa-file-alt',
                            title: 'Team Leader Report',
                            text: `${report.team_leader_name || 'Team Leader'} - ${report.report_type || report.title || 'Report'}`,
                            time: formatTimeAgo(report.created_at),
                            page: 'team-leaders'
                        });
                    }
                });

                // Team leader activities
                tlActivities.forEach(activity => {
                    const notifId = activity.id;
                    if (!readIds.includes(notifId)) {
                        notifications.push({
                            id: notifId,
                            type: 'tlactivity',
                            icon: 'fa-user-shield',
                            title: activity.action || 'Team Leader Action',
                            text: `${activity.team_leader_name}: ${activity.description || 'performed an action'}`,
                            time: formatTimeAgo(activity.created_at),
                            page: 'team-leaders'
                        });
                    }
                });

                // Project progress updates
                progressUpdates.forEach(progress => {
                    const notifId = `progress_${progress.id}`;
                    if (!readIds.includes(notifId)) {
                        notifications.push({
                            id: notifId,
                            type: 'progress',
                            icon: 'fa-chart-line',
                            title: 'Project Progress',
                            text: `${progress.startup_name || 'Startup'} - ${progress.milestone_type || progress.title || 'Update'}`,
                            time: formatTimeAgo(progress.created_at),
                            page: 'incubatee-tracker'
                        });
                    }
                });

                // Pending bookings
                pendingBookings.forEach(booking => {
                    const notifId = getNotificationId('booking', booking);
                    if (!readIds.includes(notifId)) {
                        notifications.push({
                            id: notifId,
                            type: 'booking',
                            icon: 'fa-calendar-check',
                            title: 'New Booking Request',
                            text: `${booking.agency_name || 'Unknown'} - ${booking.event_name || 'Event'}`,
                            time: formatTimeAgo(booking.created_at),
                            page: 'scheduler'
                        });
                    }
                });

                // Pending MOA submissions
                pendingMoa.forEach(moa => {
                    const notifId = `moa_${moa.id}`;
                    if (!readIds.includes(notifId)) {
                        notifications.push({
                            id: notifId,
                            type: 'moa',
                            icon: 'fa-file-contract',
                            title: 'MOA Submission',
                            text: `${moa.startup_name || moa.company_name || 'Startup'} - MOA Request`,
                            time: formatTimeAgo(moa.created_at),
                            page: 'incubatee-tracker'
                        });
                    }
                });

                // Pending payment submissions
                pendingPayments.forEach(payment => {
                    const notifId = `payment_${payment.id}`;
                    if (!readIds.includes(notifId)) {
                        notifications.push({
                            id: notifId,
                            type: 'payment',
                            icon: 'fa-money-bill-wave',
                            title: 'Payment Submission',
                            text: `${payment.startup_name || payment.company_name || 'Startup'} - Payment Verification`,
                            time: formatTimeAgo(payment.created_at),
                            page: 'incubatee-tracker'
                        });
                    }
                });

                // Pending document submissions (startup)
                pendingDocuments.forEach(doc => {
                    const notifId = `document_${doc.id}`;
                    if (!readIds.includes(notifId)) {
                        notifications.push({
                            id: notifId,
                            type: 'document',
                            icon: 'fa-folder-open',
                            title: 'Document Submission',
                            text: `${doc.startup_name || doc.company_name || 'Startup'} - Document Review`,
                            time: formatTimeAgo(doc.created_at),
                            page: 'incubatee-tracker'
                        });
                    }
                });

                // Other startup applications
                pendingStartups.forEach(startup => {
                    const notifId = getNotificationId('startup', startup);
                    if (!readIds.includes(notifId)) {
                        notifications.push({
                            id: notifId,
                            type: 'startup',
                            icon: 'fa-rocket',
                            title: 'Startup Application',
                            text: `${startup.startup_name || startup.company_name || 'Startup'} - ${startup.industry || 'General'}`,
                            time: formatTimeAgo(startup.created_at),
                            page: 'incubatee-tracker'
                        });
                    }
                });

                // Pending issues
                pendingIssues.forEach(issue => {
                    const notifId = getNotificationId('issue', issue);
                    if (!readIds.includes(notifId)) {
                        notifications.push({
                            id: notifId,
                            type: 'issue',
                            icon: 'fa-exclamation-circle',
                            title: issue.status === 'pending' ? 'New Issue Reported' : 'Issue In Progress',
                            text: `${issue.room_location || 'Room'} - ${issue.category || 'Issue'}`,
                            time: formatTimeAgo(issue.created_at),
                            page: 'issues-management'
                        });
                    }
                });

                renderNotifications();
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }

        // Simple notification sound using Web Audio API
        function playNotificationSound() {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.value = 880; // A5 note
                oscillator.type = 'sine';

                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.3);
            } catch (e) {
                // Audio not supported or blocked
                console.log('Notification sound not available');
            }
        }

        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);

            if (diffMins < 1) return 'Just now';
            if (diffMins < 60) return `${diffMins}m ago`;
            if (diffHours < 24) return `${diffHours}h ago`;
            if (diffDays < 7) return `${diffDays}d ago`;
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }

        function renderNotifications() {
            const list = document.getElementById('notificationList');
            const badge = document.getElementById('notificationBadge');
            const count = notifications.length;

            // Update badge
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.toggle('hidden', count === 0);

            // Render list
            if (count === 0) {
                list.innerHTML = `
                    <div class="notification-empty">
                        <i class="fas fa-bell-slash"></i>
                        <p>No new notifications</p>
                    </div>
                `;
            } else {
                list.innerHTML = notifications.map(notif => `
                    <div class="notification-item" onclick="handleNotificationClick('${notif.page}', '${notif.id}')">
                        <div class="notification-icon ${notif.type}">
                            <i class="fas ${notif.icon}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">${notif.title}</div>
                            <div class="notification-text">${notif.text}</div>
                            <div class="notification-time">${notif.time}</div>
                        </div>
                    </div>
                `).join('');
            }
        }

        function handleNotificationClick(page, notifId) {
            // Mark this notification as read
            if (notifId) {
                const readIds = getReadNotifications();
                if (!readIds.includes(notifId)) {
                    readIds.push(notifId);
                    saveReadNotifications(readIds);
                }
                // Remove from current notifications
                notifications = notifications.filter(n => n.id !== notifId);
                renderNotifications();
            }

            document.getElementById('notificationDropdown').classList.remove('active');
            // Navigate to the relevant page
            const fakeEvent = { preventDefault: () => {} };
            loadPage(fakeEvent, page);
        }

        function markAllAsRead() {
            // Save all current notification IDs as read
            const readIds = getReadNotifications();
            notifications.forEach(notif => {
                if (notif.id && !readIds.includes(notif.id)) {
                    readIds.push(notif.id);
                }
            });
            saveReadNotifications(readIds);

            notifications = [];
            renderNotifications();
            showToast('success', 'Notifications Cleared', 'All notifications have been marked as read.');
        }

        // Refresh notifications every 10 seconds for real-time updates
        setInterval(loadNotifications, 10000);

        // ========== ACTIVITY LOG FUNCTIONS ==========
        let allActivities = [];

        async function loadActivityLog() {
            const tbody = document.getElementById('activityLogBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" style="text-align: center; padding: 60px; color: #9CA3AF;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                        Loading activities...
                    </td>
                </tr>
            `;

            try {
                allActivities = [];
                let stats = { total: 0, pending: 0, completed: 0, urgent: 0 };

                // Only show activities from the last 7 days
                const sevenDaysAgo = new Date();
                sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
                sevenDaysAgo.setHours(0, 0, 0, 0);

                // Fetch all bookings
                try {
                    const response = await fetch('/admin/bookings');
                    if (response.ok) {
                        const data = await response.json();
                        const bookings = data.bookings || data || [];
                        bookings.forEach(b => {
                            const createdDate = new Date(b.created_at);
                            if (createdDate >= sevenDaysAgo) {
                                allActivities.push({
                                    type: 'booking',
                                    icon: 'fa-calendar-check',
                                    iconColor: '#2563EB',
                                    iconBg: '#DBEAFE',
                                    title: 'Booking Request',
                                    description: `${b.agency_name} - ${b.event_name}`,
                                    details: `${b.number_of_attendees || 0} attendees`,
                                    status: b.status,
                                    date: b.created_at,
                                    rawData: b,
                                    page: 'scheduler'
                                });
                                stats.total++;
                                if (b.status === 'pending') stats.pending++;
                                if (b.status === 'approved') stats.completed++;
                            }
                        });
                    }
                } catch (e) { console.warn('Error loading bookings:', e); }

                // Fetch startup submissions
                try {
                    const response = await fetch('/admin/startup-submissions');
                    if (response.ok) {
                        const startups = await response.json();
                        startups.forEach(s => {
                            const createdDate = new Date(s.created_at);
                            if (createdDate >= sevenDaysAgo) {
                                allActivities.push({
                                    type: 'startup',
                                    icon: 'fa-rocket',
                                    iconColor: '#7C3AED',
                                    iconBg: '#EDE9FE',
                                    title: 'Startup Application',
                                    description: s.startup_name,
                                    details: s.industry || 'N/A',
                                    status: s.status,
                                    date: s.created_at,
                                    rawData: s,
                                    page: 'incubatee-tracker'
                                });
                                stats.total++;
                                if (s.status === 'pending') stats.pending++;
                                if (s.status === 'approved') stats.completed++;
                            }
                        });
                    }
                } catch (e) { console.warn('Error loading startups:', e); }

                // Fetch room issues
                try {
                    const response = await fetch('/admin/room-issues');
                    if (response.ok) {
                        const issues = await response.json();
                        issues.forEach(i => {
                            const createdDate = new Date(i.created_at);
                            if (createdDate >= sevenDaysAgo) {
                                const isUrgent = i.priority === 'high' || i.priority === 'urgent';
                                allActivities.push({
                                    type: 'issue',
                                    icon: 'fa-exclamation-triangle',
                                    iconColor: isUrgent ? '#DC2626' : '#D97706',
                                    iconBg: isUrgent ? '#FEE2E2' : '#FEF3C7',
                                    title: 'Issue Report',
                                    description: `${i.room_location} - ${i.category}`,
                                    details: i.description ? i.description.substring(0, 50) + (i.description.length > 50 ? '...' : '') : 'No details',
                                    status: i.status,
                                    date: i.created_at,
                                    rawData: i,
                                    page: 'issues-management'
                                });
                                stats.total++;
                                if (i.status === 'pending' || i.status === 'in_progress') stats.pending++;
                                if (i.status === 'resolved') stats.completed++;
                                if (isUrgent && i.status !== 'resolved') stats.urgent++;
                            }
                        });
                    }
                } catch (e) { console.warn('Error loading issues:', e); }

                // Fetch interns
                try {
                    const response = await fetch('/admin/interns');
                    if (response.ok) {
                        const data = await response.json();
                        const interns = data.interns || data || [];
                        interns.forEach(i => {
                            const createdDate = new Date(i.created_at);
                            if (createdDate >= sevenDaysAgo) {
                                allActivities.push({
                                    type: 'intern',
                                    icon: 'fa-user-graduate',
                                    iconColor: '#059669',
                                    iconBg: '#D1FAE5',
                                    title: 'New Intern Added',
                                    description: `${i.first_name} ${i.last_name}`,
                                    details: i.school_name || 'N/A',
                                    status: i.status || 'active',
                                    date: i.created_at,
                                    rawData: i,
                                    page: 'interns'
                                });
                                stats.total++;
                                if (i.status === 'active') stats.completed++;
                            }
                        });
                    }
                } catch (e) { console.warn('Error loading interns:', e); }

                // Sort by date (newest first)
                allActivities.sort((a, b) => new Date(b.date) - new Date(a.date));

                // Update stats
                document.getElementById('totalActivitiesCount').textContent = stats.total;
                document.getElementById('pendingActivitiesCount').textContent = stats.pending;
                document.getElementById('completedActivitiesCount').textContent = stats.completed;
                document.getElementById('urgentActivitiesCount').textContent = stats.urgent;

                // Render activities
                renderActivityLog(allActivities);

            } catch (error) {
                console.error('Error loading activity log:', error);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 60px; color: #EF4444;">
                            <i class="fas fa-exclamation-circle" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                            Error loading activities
                        </td>
                    </tr>
                `;
            }
        }

        function renderActivityLog(activities) {
            const tbody = document.getElementById('activityLogBody');

            if (!activities || activities.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 60px; color: #9CA3AF;">
                            <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                            No activities found
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = activities.map(activity => {
                const statusColors = {
                    'pending': { bg: '#FEF3C7', text: '#D97706' },
                    'approved': { bg: '#D1FAE5', text: '#059669' },
                    'rejected': { bg: '#FEE2E2', text: '#DC2626' },
                    'in_progress': { bg: '#DBEAFE', text: '#2563EB' },
                    'resolved': { bg: '#D1FAE5', text: '#059669' },
                    'completed': { bg: '#D1FAE5', text: '#059669' },
                    'active': { bg: '#D1FAE5', text: '#059669' },
                    'cancelled': { bg: '#F3F4F6', text: '#6B7280' }
                };
                const statusStyle = statusColors[activity.status] || statusColors['pending'];
                const formattedDate = new Date(activity.date).toLocaleString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                return `
                    <tr data-type="${activity.type}" data-status="${activity.status}">
                        <td style="text-align: center;">
                            <div style="width: 36px; height: 36px; background: ${activity.iconBg}; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas ${activity.icon}" style="color: ${activity.iconColor}; font-size: 14px;"></i>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #1F2937;">${activity.title}</div>
                            <div style="font-size: 12px; color: #6B7280;">${activity.description}</div>
                        </td>
                        <td>
                            <span style="padding: 4px 10px; background: ${activity.iconBg}; color: ${activity.iconColor}; border-radius: 20px; font-size: 12px; font-weight: 500; text-transform: capitalize;">
                                ${activity.type}
                            </span>
                        </td>
                        <td style="color: #6B7280; font-size: 13px;">${activity.details}</td>
                        <td>
                            <span style="padding: 4px 10px; background: ${statusStyle.bg}; color: ${statusStyle.text}; border-radius: 20px; font-size: 12px; font-weight: 500; text-transform: capitalize;">
                                ${activity.status.replace('_', ' ')}
                            </span>
                        </td>
                        <td style="color: #6B7280; font-size: 13px;">${formattedDate}</td>
                        <td>
                            <button onclick="viewActivityDetails('${activity.page}')" style="padding: 6px 12px; background: #2563EB; color: white; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function filterActivityLog() {
            const typeFilter = document.getElementById('activityTypeFilter').value;
            const statusFilter = document.getElementById('activityStatusFilter').value;
            const searchQuery = document.getElementById('activitySearchInput').value.toLowerCase();

            let filtered = allActivities;

            if (typeFilter !== 'all') {
                filtered = filtered.filter(a => a.type === typeFilter);
            }

            if (statusFilter !== 'all') {
                filtered = filtered.filter(a => a.status === statusFilter);
            }

            if (searchQuery) {
                filtered = filtered.filter(a =>
                    a.title.toLowerCase().includes(searchQuery) ||
                    a.description.toLowerCase().includes(searchQuery) ||
                    a.details.toLowerCase().includes(searchQuery)
                );
            }

            renderActivityLog(filtered);
        }

        function refreshActivityLog() {
            loadActivityLog();
            showToast('success', 'Refreshed', 'Activity log has been updated.');
        }

        function viewActivityDetails(page) {
            const fakeEvent = { preventDefault: () => {} };
            loadPage(fakeEvent, page);
        }

        // ========== END ACTIVITY LOG FUNCTIONS ==========

        // Mobile Sidebar Toggle Functions
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuBtn = document.getElementById('mobileMenuBtn');
            const menuIcon = document.getElementById('mobileMenuIcon');

            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
            menuBtn.classList.toggle('active');

            // Toggle icon between bars and times
            if (sidebar.classList.contains('open')) {
                menuIcon.classList.remove('fa-bars');
                menuIcon.classList.add('fa-times');
                document.body.style.overflow = 'hidden'; // Prevent background scroll
            } else {
                menuIcon.classList.remove('fa-times');
                menuIcon.classList.add('fa-bars');
                document.body.style.overflow = ''; // Restore scroll
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuBtn = document.getElementById('mobileMenuBtn');
            const menuIcon = document.getElementById('mobileMenuIcon');

            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            menuBtn.classList.remove('active');
            menuIcon.classList.remove('fa-times');
            menuIcon.classList.add('fa-bars');
            document.body.style.overflow = '';
        }

        // Close sidebar when clicking a menu item on mobile
        document.querySelectorAll('.sidebar .menu-item, .sidebar .submenu-item').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 968) {
                    // Small delay to allow the page to load first
                    setTimeout(closeMobileSidebar, 150);
                }
            });
        });

        // Close sidebar on window resize if it becomes desktop size
        window.addEventListener('resize', function() {
            if (window.innerWidth > 968) {
                closeMobileSidebar();
            }
        });

        // Logout function - uses Laravel logout
        function handleLogout(event) {
            event.preventDefault();
            document.getElementById('logoutForm').submit();
        }

        // Toggle submenu function
        function toggleSubmenu(event, submenuId) {
            event.preventDefault();
            event.stopPropagation();

            const submenu = document.getElementById(submenuId);
            const icon = document.getElementById(submenuId + 'Icon');

            // Toggle the submenu
            submenu.classList.toggle('open');
            icon.classList.toggle('open');
        }

        // Toggle sub-submenu function
        function toggleSubSubmenu(event, subSubmenuId) {
            event.preventDefault();
            event.stopPropagation();

            const subSubmenu = document.getElementById(subSubmenuId);
            const icon = document.getElementById(subSubmenuId + 'Icon');

            // Toggle the sub-submenu
            subSubmenu.classList.toggle('open');
            if (icon) {
                icon.classList.toggle('open');
            }
        }

        // Load page function
        function loadPage(event, pageId) {
            event.preventDefault();

            // Hide all pages
            document.querySelectorAll('.page-content').forEach(page => {
                page.classList.remove('active');
            });

            // Show selected page
            const selectedPage = document.getElementById(pageId);
            if (selectedPage) {
                selectedPage.classList.add('active');
            }

            // Save current page to localStorage
            localStorage.setItem('adminCurrentPage', pageId);

            // Update active menu item
            document.querySelectorAll('.menu-item, .submenu-item').forEach(item => {
                item.classList.remove('active');
            });

            // Find and activate the clicked menu item
            const clickedItem = event.target.closest('.menu-item, .submenu-item');
            if (clickedItem) {
                clickedItem.classList.add('active');
            }

            // Update breadcrumb
            const breadcrumb = document.querySelector('.breadcrumb');
            if (pageId === 'intern-list') {
                breadcrumb.innerHTML = 'Dashboard > Intern Management > <span>Intern List</span>';
            } else if (pageId === 'time-attendance') {
                breadcrumb.innerHTML = 'Dashboard > Intern Management > <span>Time & Attendance</span>';
            } else if (pageId === 'task-assignment') {
                breadcrumb.innerHTML = 'Dashboard > Intern Management > <span>Task Assignment</span>';
            } else if (pageId === 'team-leaders') {
                breadcrumb.innerHTML = 'Dashboard > Intern Management > <span>Team Leaders</span>';
                loadTeamLeadersData();
            } else if (pageId === 'team-reports') {
                breadcrumb.innerHTML = 'Dashboard > Intern Management > <span>Team Reports</span>';
                loadTeamReportsData();
            } else if (pageId === 'research-tracking') {
                breadcrumb.innerHTML = 'Dashboard > Startup Management > <span>Research Tracking</span>';
            } else if (pageId === 'incubatee-tracker') {
                breadcrumb.innerHTML = 'Dashboard > Startup Management > <span>Incubatee Tracker</span>';
            } else if (pageId === 'issues-management') {
                breadcrumb.innerHTML = 'Dashboard > Startup Management > <span>Issues & Complaints</span>';
            } else if (pageId === 'project-progress') {
                breadcrumb.innerHTML = 'Dashboard > Startup Management > <span>Project Progress</span>';
            } else if (pageId === 'manage-startups') {
                breadcrumb.innerHTML = 'Dashboard > Startup Management > <span>Manage Startups</span>';
                loadStartupsData();
            } else if (pageId === 'digital-records') {
                breadcrumb.innerHTML = 'Dashboard > <span>Digital Records</span>';
            } else if (pageId === 'scheduler') {
                breadcrumb.innerHTML = 'Dashboard > <span>Scheduler & Events</span>';
            } else if (pageId === 'activity-log') {
                breadcrumb.innerHTML = 'Dashboard > <span>Activity Log</span>';
                loadActivityLog();
            } else if (pageId === 'admin-profile') {
                breadcrumb.innerHTML = 'Dashboard > <span>My Profile</span>';
            } else if (pageId === 'admin-settings') {
                breadcrumb.innerHTML = 'Dashboard > <span>Settings</span>';
                loadSettingsFromStorage();
            } else if (pageId === 'dashboard-overview') {
                breadcrumb.innerHTML = 'Dashboard > <span>Overview</span>';
            }
        }

        // ===== SETTINGS PAGE FUNCTIONS =====

        function showSettingsTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.settings-tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all nav items
            document.querySelectorAll('.settings-nav-item').forEach(item => {
                item.classList.remove('active');
            });

            // Show selected tab
            const selectedTab = document.getElementById('settingsTab' + tabName.charAt(0).toUpperCase() + tabName.slice(1));
            if (selectedTab) {
                selectedTab.classList.add('active');
            }

            // Add active class to nav item
            const navItem = document.getElementById('settingsNav' + tabName.charAt(0).toUpperCase() + tabName.slice(1));
            if (navItem) {
                navItem.classList.add('active');
            }
        }

        function getDefaultSettings() {
            return {
                // General
                system_name: 'UP Management System',
                office_name: 'University of the Philippines',
                contact_email: '',
                timezone: 'Asia/Manila',
                date_format: 'M d, Y',
                maintenance_mode: false,

                // Internship
                work_start: '08:00',
                overtime_threshold: 8,
                auto_approve_intern: false,
                require_overtime_approval: true,

                // Notifications
                booking_alerts: true,
                intern_alerts: true,
                issue_alerts: true,
                sound_notifications: true,
                notification_interval: 30,

                // Scheduler
                booking_duration: 2,
                min_advance_booking: 1,
                max_advance_booking: 90,
                auto_approve_booking: false,
                weekend_bookings: false,
                booking_start: '08:00',
                booking_end: '17:00',

                // Appearance
                primary_color: '#7B1D3A',
                accent_color: '#FFBF00',
                sidebar_style: 'gradient',
                compact_mode: false,
                animations: true
            };
        }

        async function loadSettingsFromStorage() {
            try {
                const response = await fetch('/admin/settings');
                const data = await response.json();

                if (data.success) {
                    populateSettingsForm(data.settings);
                } else {
                    populateSettingsForm(getDefaultSettings());
                }
            } catch (error) {
                console.error('Error loading settings:', error);
                populateSettingsForm(getDefaultSettings());
            }
        }

        function populateSettingsForm(settings) {
            // General Settings
            document.getElementById('settingSystemName').value = settings.system_name || '';
            document.getElementById('settingOfficeName').value = settings.office_name || '';
            document.getElementById('settingContactEmail').value = settings.contact_email || '';
            document.getElementById('settingTimezone').value = settings.timezone || 'Asia/Manila';
            document.getElementById('settingDateFormat').value = settings.date_format || 'M d, Y';
            document.getElementById('settingMaintenanceMode').checked = settings.maintenance_mode || false;

            // Internship Settings
            document.getElementById('settingWorkStart').value = settings.work_start || '08:00';
            document.getElementById('settingOvertimeThreshold').value = settings.overtime_threshold || 8;
            document.getElementById('settingAutoApproveIntern').checked = settings.auto_approve_intern || false;
            document.getElementById('settingRequireOvertimeApproval').checked = settings.require_overtime_approval !== false;

            // Notification Settings
            document.getElementById('settingBookingAlerts').checked = settings.booking_alerts !== false;
            document.getElementById('settingInternAlerts').checked = settings.intern_alerts !== false;
            document.getElementById('settingIssueAlerts').checked = settings.issue_alerts !== false;
            document.getElementById('settingSoundNotifications').checked = settings.sound_notifications !== false;
            document.getElementById('settingNotificationInterval').value = settings.notification_interval || 30;

            // Scheduler Settings
            document.getElementById('settingBookingDuration').value = settings.booking_duration || 2;
            document.getElementById('settingMinAdvanceBooking').value = settings.min_advance_booking || 1;
            document.getElementById('settingMaxAdvanceBooking').value = settings.max_advance_booking || 90;
            document.getElementById('settingAutoApproveBooking').checked = settings.auto_approve_booking || false;
            document.getElementById('settingWeekendBookings').checked = settings.weekend_bookings || false;
            document.getElementById('settingBookingStart').value = settings.booking_start || '08:00';
            document.getElementById('settingBookingEnd').value = settings.booking_end || '17:00';

            // Appearance Settings
            document.getElementById('settingPrimaryColor').value = settings.primary_color || '#7B1D3A';
            document.getElementById('settingPrimaryColorHex').value = settings.primary_color || '#7B1D3A';
            document.getElementById('settingAccentColor').value = settings.accent_color || '#FFBF00';
            document.getElementById('settingAccentColorHex').value = settings.accent_color || '#FFBF00';
            document.getElementById('settingSidebarStyle').value = settings.sidebar_style || 'gradient';
            document.getElementById('settingCompactMode').checked = settings.compact_mode || false;
            document.getElementById('settingAnimations').checked = settings.animations !== false;

            // Apply settings
            applySettings(settings);
        }

        async function saveSettings() {
            const settings = {
                // General
                system_name: document.getElementById('settingSystemName').value,
                office_name: document.getElementById('settingOfficeName').value,
                contact_email: document.getElementById('settingContactEmail').value,
                timezone: document.getElementById('settingTimezone').value,
                date_format: document.getElementById('settingDateFormat').value,
                maintenance_mode: document.getElementById('settingMaintenanceMode').checked,

                // Internship
                work_start: document.getElementById('settingWorkStart').value,
                overtime_threshold: parseFloat(document.getElementById('settingOvertimeThreshold').value),
                auto_approve_intern: document.getElementById('settingAutoApproveIntern').checked,
                require_overtime_approval: document.getElementById('settingRequireOvertimeApproval').checked,

                // Notifications
                booking_alerts: document.getElementById('settingBookingAlerts').checked,
                intern_alerts: document.getElementById('settingInternAlerts').checked,
                issue_alerts: document.getElementById('settingIssueAlerts').checked,
                sound_notifications: document.getElementById('settingSoundNotifications').checked,
                notification_interval: parseInt(document.getElementById('settingNotificationInterval').value),

                // Scheduler
                booking_duration: parseInt(document.getElementById('settingBookingDuration').value),
                min_advance_booking: parseInt(document.getElementById('settingMinAdvanceBooking').value),
                max_advance_booking: parseInt(document.getElementById('settingMaxAdvanceBooking').value),
                auto_approve_booking: document.getElementById('settingAutoApproveBooking').checked,
                weekend_bookings: document.getElementById('settingWeekendBookings').checked,
                booking_start: document.getElementById('settingBookingStart').value,
                booking_end: document.getElementById('settingBookingEnd').value,

                // Appearance
                primary_color: document.getElementById('settingPrimaryColor').value,
                accent_color: document.getElementById('settingAccentColor').value,
                sidebar_style: document.getElementById('settingSidebarStyle').value,
                compact_mode: document.getElementById('settingCompactMode').checked,
                animations: document.getElementById('settingAnimations').checked
            };

            try {
                const response = await fetch('/admin/settings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ settings: settings })
                });

                // Check if response is OK first
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Server error:', response.status, errorText);
                    showToast('error', 'Error', `Server error: ${response.status}. Check console for details.`);
                    return;
                }

                const data = await response.json();

                if (data.success) {
                    applySettings(settings);
                    showToast('success', 'Settings Saved', data.message);
                } else {
                    showToast('error', 'Error', data.message || 'Failed to save settings');
                }
            } catch (error) {
                console.error('Error saving settings:', error);
                showToast('error', 'Error', 'Failed to save settings: ' + error.message);
            }
        }

        function applySettings(settings) {
            // Update notification polling interval
            if (window.notificationInterval) {
                clearInterval(window.notificationInterval);
            }
            const interval = parseInt(settings.notification_interval || 30) * 1000;
            if (typeof loadNotifications === 'function') {
                window.notificationInterval = setInterval(loadNotifications, interval);
            }

            // Apply compact mode
            if (settings.compact_mode) {
                document.body.classList.add('compact-mode');
            } else {
                document.body.classList.remove('compact-mode');
            }

            // Apply animations setting
            if (!settings.animations) {
                document.body.classList.add('no-animations');
            } else {
                document.body.classList.remove('no-animations');
            }

            // Apply primary color
            const primaryColor = settings.primary_color || '#7B1D3A';
            document.documentElement.style.setProperty('--primary-color', primaryColor);

            // Apply accent color
            const accentColor = settings.accent_color || '#FFBF00';
            document.documentElement.style.setProperty('--accent-color', accentColor);

            // Apply sidebar style
            const sidebar = document.querySelector('.sidebar');
            if (sidebar && settings.sidebar_style) {
                sidebar.classList.remove('sidebar-gradient', 'sidebar-solid', 'sidebar-dark');
                if (settings.sidebar_style === 'gradient') {
                    sidebar.classList.add('sidebar-gradient');
                } else if (settings.sidebar_style === 'solid') {
                    sidebar.classList.add('sidebar-solid');
                } else if (settings.sidebar_style === 'dark') {
                    sidebar.classList.add('sidebar-dark');
                }
            }

            // Store settings in session storage for quick access
            sessionStorage.setItem('adminSettings', JSON.stringify(settings));
        }

        // Load and apply settings on page load
        async function loadAndApplySettings() {
            try {
                const response = await fetch('/admin/settings');
                const data = await response.json();

                if (data.success) {
                    applySettings(data.settings);
                } else {
                    applySettings(getDefaultSettings());
                }
            } catch (error) {
                console.error('Error loading settings:', error);
                applySettings(getDefaultSettings());
            }
        }

        // Initialize settings on page load
        loadAndApplySettings();

        async function resetSettingsForm() {
            const defaults = getDefaultSettings();
            populateSettingsForm(defaults);
            showToast('info', 'Form Reset', 'Settings form has been reset to defaults. Click Save to apply.');
        }

        async function resetSettings() {
            if (confirm('Are you sure you want to reset all settings to defaults? This action cannot be undone.')) {
                try {
                    const response = await fetch('/admin/settings/reset', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        await loadSettingsFromStorage();
                        showToast('success', 'Settings Reset', data.message);
                    } else {
                        showToast('error', 'Error', 'Failed to reset settings');
                    }
                } catch (error) {
                    console.error('Error resetting settings:', error);
                    showToast('error', 'Error', 'Failed to reset settings');
                }
            }
        }

        function exportData(type) {
            showToast('info', 'Exporting...', `Preparing ${type} data export...`);

            // Create a simple CSV export simulation
            let url = '';
            switch(type) {
                case 'interns':
                    url = '/admin/export/interns';
                    break;
                case 'attendance':
                    url = '/admin/export/attendance';
                    break;
                case 'tasks':
                    url = '/admin/export/tasks';
                    break;
                case 'bookings':
                    url = '/admin/export/bookings';
                    break;
            }

            // Trigger download
            if (url) {
                const link = document.createElement('a');
                link.href = url;
                link.download = `${type}_export_${new Date().toISOString().split('T')[0]}.xls`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                setTimeout(() => {
                    showToast('success', 'Export Ready', `Your ${type} data has been exported to Excel.`);
                }, 1000);
            }
        }

        async function clearOldData(type) {
            const days = document.getElementById('cleanupDays')?.value || 90;

            const typeLabels = {
                'attendance': 'attendance records',
                'tasks': 'completed tasks',
                'bookings': 'old bookings',
                'notifications': 'notifications',
                'all': 'all old data'
            };

            if (confirm(`Are you sure you want to clear ${typeLabels[type]} older than ${days} days? This action cannot be undone.`)) {
                if (type === 'notifications') {
                    localStorage.removeItem('readNotifications');
                    showToast('success', 'Cleared', 'Notifications have been cleared.');
                    return;
                }

                showToast('info', 'Clearing...', `Clearing old ${typeLabels[type]}...`);

                try {
                    const response = await fetch('/admin/settings/clear-data', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            data_type: type,
                            older_than_days: parseInt(days)
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        showToast('success', 'Cleared', data.message);
                    } else {
                        showToast('error', 'Error', 'Failed to clear data');
                    }
                } catch (error) {
                    console.error('Error clearing data:', error);
                    showToast('error', 'Error', 'Failed to clear data');
                }
            }
        }

        // Color picker sync
        document.addEventListener('DOMContentLoaded', function() {
            const primaryColor = document.getElementById('settingPrimaryColor');
            const primaryColorHex = document.getElementById('settingPrimaryColorHex');
            const accentColor = document.getElementById('settingAccentColor');
            const accentColorHex = document.getElementById('settingAccentColorHex');

            if (primaryColor && primaryColorHex) {
                primaryColor.addEventListener('input', function() {
                    primaryColorHex.value = this.value.toUpperCase();
                });
            }

            if (accentColor && accentColorHex) {
                accentColor.addEventListener('input', function() {
                    accentColorHex.value = this.value.toUpperCase();
                });
            }

            // Load settings on page load if settings page was last page
            const savedPage = localStorage.getItem('adminCurrentPage');
            if (savedPage === 'admin-settings') {
                loadSettingsFromStorage();
            }
        });

        // ===== TEAM LEADER MANAGEMENT FUNCTIONS =====

        let teamLeadersData = [];
        let schoolsData = [];
        let reportsData = [];

        async function loadTeamLeadersData() {
            try {
                const response = await fetch('/admin/api/team-leaders');
                const data = await response.json();
                teamLeadersData = data.teamLeaders;
                schoolsData = data.schools;

                renderTeamLeadersTable();
                updateTeamLeaderStats();
                populateSchoolsDropdown();
            } catch (error) {
                console.error('Error loading team leaders:', error);
                showToast('error', 'Error', 'Failed to load team leaders data');
            }
        }

        function updateTeamLeaderStats() {
            const total = teamLeadersData.length;
            const active = teamLeadersData.filter(tl => tl.is_active).length;
            const inactive = total - active;
            const schoolsCovered = new Set(teamLeadersData.map(tl => tl.school_id)).size;

            document.getElementById('statTotalTeamLeaders').textContent = total;
            document.getElementById('statActiveTeamLeaders').textContent = active;
            document.getElementById('statInactiveTeamLeaders').textContent = inactive;
            document.getElementById('statSchoolsCovered').textContent = schoolsCovered;
        }

        function renderTeamLeadersTable() {
            const tbody = document.getElementById('teamLeadersTableBody');
            const filterStatus = document.getElementById('filterTeamLeaderStatus').value;

            let filtered = teamLeadersData;
            if (filterStatus === 'active') {
                filtered = teamLeadersData.filter(tl => tl.is_active);
            } else if (filterStatus === 'inactive') {
                filtered = teamLeadersData.filter(tl => !tl.is_active);
            }

            if (filtered.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" style="padding: 60px; text-align: center; color: #9CA3AF;">
                            <i class="fas fa-user-tie" style="font-size: 48px; margin-bottom: 12px; display: block;"></i>
                            <p>No team leaders found</p>
                            <button onclick="openTeamLeaderModal()" style="margin-top: 12px; background: linear-gradient(135deg, #7B1D3A, #5a1428); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                                <i class="fas fa-plus"></i> Add First Team Leader
                            </button>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = filtered.map(tl => `
                <tr style="border-bottom: 1px solid #E5E7EB;">
                    <td style="padding: 14px 16px; white-space: nowrap;">
                        <span style="background: #F3F4F6; padding: 4px 10px; border-radius: 6px; font-family: monospace; font-weight: 600; color: #374151;">${tl.reference_code || 'N/A'}</span>
                    </td>
                    <td style="padding: 14px 16px; min-width: 180px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            ${tl.profile_picture_url
                                ? `<img src="${tl.profile_picture_url}" alt="${tl.name}" style="width: 40px; height: 40px; min-width: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #E5E7EB;">`
                                : `<div style="width: 40px; height: 40px; min-width: 40px; background: linear-gradient(135deg, #7B1D3A, #5a1428); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                    ${tl.name.charAt(0).toUpperCase()}
                                </div>`
                            }
                            <div style="white-space: nowrap;">
                                <div style="font-weight: 600; color: #1F2937;">${tl.name}</div>
                                <div style="font-size: 12px; color: #6B7280;">Added ${tl.created_at}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 14px 16px; color: #4B5563; min-width: 180px; word-break: break-word;">${tl.email}</td>
                    <td style="padding: 14px 16px; white-space: nowrap;">
                        <span style="background: #DBEAFE; color: #1E40AF; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 500; white-space: nowrap; display: inline-block;">${tl.school_name}</span>
                    </td>
                    <td style="padding: 14px 16px; text-align: center; white-space: nowrap;">
                        <span style="font-weight: 600; color: #1F2937;">${tl.interns_count}</span>
                    </td>
                    <td style="padding: 14px 16px; text-align: center; white-space: nowrap;">
                        <span style="font-weight: 600; color: #1F2937;">${tl.reports_count}</span>
                    </td>
                    <td style="padding: 14px 16px; text-align: center; white-space: nowrap;">
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; ${tl.is_active ? 'background: #D1FAE5; color: #065F46;' : 'background: #FEE2E2; color: #991B1B;'}">
                            ${tl.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td style="padding: 14px 16px; text-align: center; white-space: nowrap;">
                        <div style="display: flex; justify-content: center; gap: 6px;">
                            <button onclick="editTeamLeader(${tl.id})" style="background: #DBEAFE; color: #1E40AF; border: none; width: 32px; height: 32px; min-width: 32px; border-radius: 6px; cursor: pointer;" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="toggleTeamLeaderStatus(${tl.id})" style="background: ${tl.is_active ? '#FEF3C7' : '#D1FAE5'}; color: ${tl.is_active ? '#92400E' : '#065F46'}; border: none; width: 32px; height: 32px; min-width: 32px; border-radius: 6px; cursor: pointer;" title="${tl.is_active ? 'Deactivate' : 'Activate'}">
                                <i class="fas ${tl.is_active ? 'fa-toggle-off' : 'fa-toggle-on'}"></i>
                            </button>
                            <button onclick="deleteTeamLeader(${tl.id}, '${tl.name.replace(/'/g, "\\'")}')" style="background: #FEE2E2; color: #991B1B; border: none; width: 32px; height: 32px; min-width: 32px; border-radius: 6px; cursor: pointer;" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function filterTeamLeaders() {
            renderTeamLeadersTable();
        }

        function populateSchoolsDropdown() {
            const select = document.getElementById('teamLeaderSchool');
            select.innerHTML = '<option value="">-- Select School --</option>';

            schoolsData.forEach(school => {
                const option = document.createElement('option');
                option.value = school.id;
                option.textContent = school.name;
                select.appendChild(option);
            });
        }

        function openTeamLeaderModal(id = null) {
            const modal = document.getElementById('teamLeaderModal');
            const title = document.getElementById('teamLeaderModalTitle');
            const form = document.getElementById('teamLeaderForm');
            const passwordGroup = document.getElementById('passwordGroup');
            const schoolGroup = document.getElementById('schoolGroup');
            const refCodeDisplay = document.getElementById('referenceCodeDisplay');
            const passwordLabel = document.getElementById('passwordLabel');
            const passwordInput = document.getElementById('teamLeaderPassword');
            const passwordHint = document.getElementById('passwordHint');

            form.reset();
            document.getElementById('teamLeaderId').value = '';
            refCodeDisplay.style.display = 'none';

            // Reset all permission checkboxes
            resetPermissionCheckboxes();

            if (id) {
                title.innerHTML = '<i class="fas fa-user-tie" style="margin-right: 8px;"></i>Edit Team Leader';
                const tl = teamLeadersData.find(t => t.id === id);
                if (tl) {
                    document.getElementById('teamLeaderId').value = tl.id;
                    document.getElementById('teamLeaderName').value = tl.name;
                    document.getElementById('teamLeaderEmail').value = tl.email;

                    // Hide school field during edit - school cannot be changed
                    schoolGroup.style.display = 'none';

                    // Make password optional for editing
                    passwordInput.removeAttribute('required');
                    passwordLabel.classList.remove('required');
                    passwordInput.placeholder = 'Leave blank to keep current password';
                    passwordHint.style.display = 'block';

                    if (tl.reference_code) {
                        document.getElementById('referenceCodeValue').textContent = tl.reference_code;
                        refCodeDisplay.style.display = 'block';
                    }

                    // Load permissions for this team leader
                    loadTeamLeaderPermissions(id);
                }
            } else {
                title.innerHTML = '<i class="fas fa-user-tie" style="margin-right: 8px;"></i>Add Team Leader';

                // Show school field for new team leaders
                schoolGroup.style.display = 'block';

                // Make password required for new team leaders
                passwordInput.setAttribute('required', 'required');
                passwordLabel.classList.add('required');
                passwordInput.placeholder = 'Enter password (min 8 characters)';
                passwordHint.style.display = 'none';
            }

            modal.style.display = 'flex';
        }

        function resetPermissionCheckboxes() {
            const modules = ['scheduler', 'research_tracking', 'incubatee_tracker', 'issues_management', 'digital_records', 'intern_management'];
            modules.forEach(module => {
                const viewCheckbox = document.getElementById(`perm_${module}_view`);
                const editCheckbox = document.getElementById(`perm_${module}_edit`);
                if (viewCheckbox) viewCheckbox.checked = false;
                if (editCheckbox) editCheckbox.checked = false;
            });
        }

        async function loadTeamLeaderPermissions(userId) {
            try {
                const response = await fetch(`/admin/api/team-leaders/${userId}/permissions`);
                const data = await response.json();

                if (data.permissions) {
                    Object.keys(data.permissions).forEach(module => {
                        const perm = data.permissions[module];
                        const viewCheckbox = document.getElementById(`perm_${module}_view`);
                        const editCheckbox = document.getElementById(`perm_${module}_edit`);

                        if (viewCheckbox) viewCheckbox.checked = perm.can_view;
                        if (editCheckbox) editCheckbox.checked = perm.can_edit;
                    });
                }
            } catch (error) {
                console.error('Error loading permissions:', error);
            }
        }

        function getPermissionsFromForm() {
            const modules = ['scheduler', 'research_tracking', 'incubatee_tracker', 'issues_management', 'digital_records', 'intern_management'];
            const permissions = {};

            modules.forEach(module => {
                const viewCheckbox = document.getElementById(`perm_${module}_view`);
                const editCheckbox = document.getElementById(`perm_${module}_edit`);

                permissions[module] = {
                    can_view: viewCheckbox ? viewCheckbox.checked : false,
                    can_edit: editCheckbox ? editCheckbox.checked : false
                };
            });

            return permissions;
        }

        async function editTeamLeader(id) {
            // Ensure schools data is loaded before opening modal
            if (schoolsData.length === 0 || teamLeadersData.length === 0) {
                try {
                    const response = await fetch('/admin/api/team-leaders');
                    const data = await response.json();
                    schoolsData = data.schools;
                    teamLeadersData = data.teamLeaders;
                } catch (error) {
                    console.error('Error loading data:', error);
                    showToast('error', 'Error', 'Failed to load team leader data');
                    return;
                }
            }

            // Populate schools dropdown first
            populateSchoolsDropdown();

            // Small delay to ensure DOM is ready
            setTimeout(() => {
                openTeamLeaderModal(id);
            }, 50);
        }

        // ===== ASSIGN TEAM LEADER FROM INTERNS =====

        let schoolInternsData = [];
        let currentAssignSchoolId = null;
        let currentAssignSchoolName = '';

        // Open intern selection modal for a school (from Intern List)
        async function openTeamLeaderModalForSchool(schoolId, schoolName) {
            currentAssignSchoolId = schoolId;
            currentAssignSchoolName = schoolName;

            const modal = document.getElementById('assignTeamLeaderModal');
            document.getElementById('assignTLSchoolId').value = schoolId;
            document.getElementById('assignTLSchoolName').textContent = schoolName;
            document.getElementById('assignTLModalTitle').innerHTML = `<i class="fas fa-user-tie" style="margin-right: 8px;"></i>Assign Team Leader`;

            // Reset form
            document.getElementById('searchInternInput').value = '';
            document.getElementById('selectedInternId').value = '';
            document.getElementById('assignTLPassword').value = '';
            document.getElementById('selectedInternDisplay').style.display = 'none';
            document.getElementById('passwordSection').style.display = 'none';
            document.getElementById('btnAssignTeamLeader').disabled = true;
            document.getElementById('btnAssignTeamLeader').style.opacity = '0.5';

            modal.style.display = 'flex';

            // Load interns for this school
            await loadSchoolInterns(schoolId);
        }

        async function loadSchoolInterns(schoolId) {
            const container = document.getElementById('internsListContainer');
            container.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #9CA3AF;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                    Loading interns...
                </div>
            `;

            try {
                const response = await fetch(`/admin/api/schools/${schoolId}/interns`);
                const data = await response.json();
                schoolInternsData = data.interns;

                document.getElementById('internsCount').textContent = schoolInternsData.length;
                renderInternsList();
            } catch (error) {
                console.error('Error loading interns:', error);
                container.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #DC2626;">
                        <i class="fas fa-exclamation-circle" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                        Failed to load interns
                    </div>
                `;
            }
        }

        function renderInternsList() {
            const container = document.getElementById('internsListContainer');
            const searchTerm = document.getElementById('searchInternInput').value.toLowerCase();

            let filtered = schoolInternsData;
            if (searchTerm) {
                filtered = schoolInternsData.filter(intern =>
                    intern.name.toLowerCase().includes(searchTerm) ||
                    intern.email.toLowerCase().includes(searchTerm) ||
                    intern.course.toLowerCase().includes(searchTerm)
                );
            }

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #9CA3AF;">
                        <i class="fas fa-user-graduate" style="font-size: 32px; margin-bottom: 8px; display: block;"></i>
                        <p style="margin: 0;">${searchTerm ? 'No interns match your search' : 'No active interns in this school'}</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = filtered.map(intern => `
                <div onclick="selectIntern(${intern.id})" class="intern-select-item" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; cursor: pointer; border-bottom: 1px solid #F3F4F6; transition: all 0.2s;"
                     onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700;">
                            ${intern.name.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #1F2937;">${intern.name}</div>
                            <div style="font-size: 12px; color: #6B7280;">${intern.course} • ${intern.email}</div>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 600; color: #7B1D3A;">${intern.progress}%</div>
                        <div style="font-size: 11px; color: #6B7280;">${intern.completed_hours}/${intern.required_hours} hrs</div>
                    </div>
                </div>
            `).join('');
        }

        function filterInternsList() {
            renderInternsList();
        }

        function selectIntern(internId) {
            const intern = schoolInternsData.find(i => i.id === internId);
            if (!intern) return;

            document.getElementById('selectedInternId').value = intern.id;
            document.getElementById('selectedInternAvatar').textContent = intern.name.charAt(0).toUpperCase();
            document.getElementById('selectedInternName').textContent = intern.name;
            document.getElementById('selectedInternInfo').textContent = `${intern.course} • ${intern.completed_hours}/${intern.required_hours} hrs completed`;

            document.getElementById('selectedInternDisplay').style.display = 'block';
            document.getElementById('passwordSection').style.display = 'block';
            document.getElementById('btnAssignTeamLeader').disabled = false;
            document.getElementById('btnAssignTeamLeader').style.opacity = '1';

            // Hide the list
            document.getElementById('internsListContainer').style.display = 'none';
        }

        function clearInternSelection() {
            document.getElementById('selectedInternId').value = '';
            document.getElementById('selectedInternDisplay').style.display = 'none';
            document.getElementById('passwordSection').style.display = 'none';
            document.getElementById('assignTLPassword').value = '';
            document.getElementById('btnAssignTeamLeader').disabled = true;
            document.getElementById('btnAssignTeamLeader').style.opacity = '0.5';

            // Show the list again
            document.getElementById('internsListContainer').style.display = 'block';
        }

        function closeAssignTeamLeaderModal() {
            document.getElementById('assignTeamLeaderModal').style.display = 'none';
        }

        async function assignInternAsTeamLeader() {
            const internId = document.getElementById('selectedInternId').value;
            const password = document.getElementById('assignTLPassword').value;

            if (!internId) {
                showToast('error', 'Error', 'Please select an intern');
                return;
            }

            if (!password || password.length < 8) {
                showToast('error', 'Error', 'Password must be at least 8 characters');
                return;
            }

            const btn = document.getElementById('btnAssignTeamLeader');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Assigning...';

            try {
                const response = await fetch('/admin/api/team-leaders/promote-intern', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        intern_id: internId,
                        password: password
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    closeAssignTeamLeaderModal();

                    // Show success with reference code
                    showSuccessModal(result.team_leader.name, result.reference_code);

                    // Refresh after showing
                    setTimeout(() => {
                        location.reload();
                    }, 4000);
                } else {
                    showToast('error', 'Error', result.error || 'Failed to assign team leader');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-user-shield"></i> Assign as Team Leader';
                }
            } catch (error) {
                console.error('Error assigning team leader:', error);
                showToast('error', 'Error', 'Failed to assign team leader');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-user-shield"></i> Assign as Team Leader';
            }
        }

        function showSuccessModal(name, refCode) {
            // Create a temporary success overlay
            const overlay = document.createElement('div');
            overlay.id = 'successOverlay';
            overlay.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 10001; display: flex; align-items: center; justify-content: center;';
            overlay.innerHTML = `
                <div style="background: white; border-radius: 20px; padding: 40px; text-align: center; max-width: 400px; animation: scaleIn 0.3s ease;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                        <i class="fas fa-check" style="font-size: 36px; color: white;"></i>
                    </div>
                    <h2 style="color: #1F2937; margin: 0 0 8px; font-size: 24px;">Team Leader Assigned!</h2>
                    <p style="color: #6B7280; margin: 0 0 24px;">${name} is now a Team Leader</p>
                    <div style="background: #F0FDF4; border: 2px solid #86EFAC; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                        <div style="font-size: 12px; color: #166534; text-transform: uppercase; font-weight: 600; margin-bottom: 8px;">Reference Code</div>
                        <div style="font-size: 28px; font-weight: 700; color: #15803D; font-family: monospace; letter-spacing: 2px;">${refCode}</div>
                    </div>
                    <p style="color: #9CA3AF; font-size: 13px; margin: 0;">Page will refresh in a moment...</p>
                </div>
            `;
            document.body.appendChild(overlay);
        }

        function switchToManualTeamLeader() {
            closeAssignTeamLeaderModal();

            // Load schools data if needed and open manual modal
            if (schoolsData.length === 0) {
                fetch('/admin/api/team-leaders')
                    .then(response => response.json())
                    .then(data => {
                        schoolsData = data.schools;
                        teamLeadersData = data.teamLeaders;
                        populateSchoolsDropdown();
                        openManualTeamLeaderModal();
                    });
            } else {
                populateSchoolsDropdown();
                openManualTeamLeaderModal();
            }
        }

        function openManualTeamLeaderModal() {
            const modal = document.getElementById('teamLeaderModal');
            const form = document.getElementById('teamLeaderForm');
            const refCodeDisplay = document.getElementById('referenceCodeDisplay');

            form.reset();
            document.getElementById('teamLeaderId').value = '';
            refCodeDisplay.style.display = 'none';
            document.getElementById('teamLeaderModalTitle').innerHTML = '<i class="fas fa-user-tie" style="margin-right: 8px;"></i>Create Team Leader Account';
            document.getElementById('teamLeaderPassword').setAttribute('required', 'required');

            // Pre-select the school if we have one
            if (currentAssignSchoolId) {
                setTimeout(() => {
                    document.getElementById('teamLeaderSchool').value = currentAssignSchoolId;
                }, 100);
            }

            modal.style.display = 'flex';
        }

        // Edit team leader from school header
        async function editTeamLeaderFromSchool(teamLeaderId) {
            // First ensure we have the data loaded
            if (teamLeadersData.length === 0 || schoolsData.length === 0) {
                try {
                    const response = await fetch('/admin/api/team-leaders');
                    const data = await response.json();
                    schoolsData = data.schools;
                    teamLeadersData = data.teamLeaders;
                } catch (error) {
                    console.error('Error loading team leaders:', error);
                    showToast('error', 'Error', 'Failed to load team leaders data');
                    return;
                }
            }

            // Populate schools dropdown first
            populateSchoolsDropdown();

            // Small delay to ensure DOM is ready
            setTimeout(() => {
                openTeamLeaderModal(teamLeaderId);
            }, 50);
        }

        function closeTeamLeaderModal() {
            document.getElementById('teamLeaderModal').style.display = 'none';
        }

        async function saveTeamLeader() {
            const id = document.getElementById('teamLeaderId').value;
            const name = document.getElementById('teamLeaderName').value;
            const email = document.getElementById('teamLeaderEmail').value;
            const password = document.getElementById('teamLeaderPassword').value;
            const school_id = document.getElementById('teamLeaderSchool').value;
            const permissions = getPermissionsFromForm();

            // Validate name and email (always required)
            if (!name || !email) {
                showToast('error', 'Validation Error', 'Please fill in all required fields');
                return;
            }

            // For new team leaders, school_id and password are required
            if (!id) {
                if (!school_id) {
                    showToast('error', 'Validation Error', 'Please select a school');
                    return;
                }
                if (!password) {
                    showToast('error', 'Validation Error', 'Password is required for new team leaders');
                    return;
                }
            }

            // Build data object - only include school_id for new team leaders
            const data = { name, email, permissions };
            if (!id && school_id) {
                data.school_id = school_id;
            }
            if (password) data.password = password;

            try {
                const url = id ? `/admin/api/team-leaders/${id}` : '/admin/api/team-leaders';
                const method = id ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    closeTeamLeaderModal();
                    loadTeamLeadersData();

                    if (result.reference_code) {
                        showToast('success', 'Success', `Team Leader created! Reference Code: ${result.reference_code}`);
                        // Refresh page to update school headers in Intern List
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        showToast('success', 'Success', result.message);
                    }
                } else {
                    showToast('error', 'Error', result.error || 'Failed to save team leader');
                }
            } catch (error) {
                console.error('Error saving team leader:', error);
                showToast('error', 'Error', 'Failed to save team leader');
            }
        }

        async function toggleTeamLeaderStatus(id) {
            const tl = teamLeadersData.find(t => t.id === id);
            const action = tl.is_active ? 'deactivate' : 'activate';

            if (!confirm(`Are you sure you want to ${action} ${tl.name}?`)) {
                return;
            }

            try {
                const response = await fetch(`/admin/api/team-leaders/${id}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    loadTeamLeadersData();
                    showToast('success', 'Success', result.message);
                } else {
                    showToast('error', 'Error', result.error || 'Failed to update status');
                }
            } catch (error) {
                console.error('Error toggling status:', error);
                showToast('error', 'Error', 'Failed to update status');
            }
        }

        async function deleteTeamLeader(id, name) {
            if (!confirm(`Are you sure you want to delete team leader "${name}"? This action cannot be undone.`)) {
                return;
            }

            try {
                const response = await fetch(`/admin/api/team-leaders/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    loadTeamLeadersData();
                    showToast('success', 'Success', result.message);
                } else {
                    showToast('error', 'Error', result.error || 'Failed to delete team leader');
                }
            } catch (error) {
                console.error('Error deleting team leader:', error);
                showToast('error', 'Error', 'Failed to delete team leader');
            }
        }

        // ===== TEAM REPORTS FUNCTIONS =====

        async function loadTeamReportsData() {
            try {
                const response = await fetch('/admin/api/team-reports');
                const data = await response.json();
                reportsData = data.reports;

                const pendingCount = data.pendingCount;
                const badge = document.getElementById('pendingReportsBadge');
                const countSpan = document.getElementById('pendingReportsCount');

                if (pendingCount > 0) {
                    badge.style.display = 'inline-flex';
                    countSpan.textContent = pendingCount;
                } else {
                    badge.style.display = 'none';
                }

                renderReportsList();
            } catch (error) {
                console.error('Error loading reports:', error);
                showToast('error', 'Error', 'Failed to load reports data');
            }
        }

        function renderReportsList() {
            const container = document.getElementById('reportsListContainer');
            const filterStatus = document.getElementById('filterReportStatus').value;
            const filterType = document.getElementById('filterReportType').value;

            let filtered = reportsData;
            if (filterStatus !== 'all') {
                filtered = filtered.filter(r => r.status === filterStatus);
            }
            if (filterType !== 'all') {
                filtered = filtered.filter(r => r.report_type === filterType);
            }

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 60px 20px; color: #9CA3AF; background: white; border-radius: 16px;">
                        <i class="fas fa-file-alt" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
                        <p style="font-size: 16px;">No reports found</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = filtered.map(report => `
                <div style="background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; border-left: 4px solid ${getStatusColor(report.status)};">
                    <div style="padding: 20px 24px; display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                <h3 style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">${report.title}</h3>
                                <span style="padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; background: ${getTypeBackground(report.report_type)}; color: ${getTypeColor(report.report_type)};">
                                    ${report.report_type.charAt(0).toUpperCase() + report.report_type.slice(1)}
                                </span>
                                <span style="padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; background: ${getStatusBackground(report.status)}; color: ${getStatusTextColor(report.status)};">
                                    ${getStatusLabel(report.status)}
                                </span>
                            </div>
                            <div style="display: flex; gap: 24px; color: #6B7280; font-size: 13px;">
                                <span><i class="fas fa-user-tie" style="margin-right: 6px;"></i>${report.team_leader_name}</span>
                                <span><i class="fas fa-id-badge" style="margin-right: 6px;"></i>${report.team_leader_code}</span>
                                <span><i class="fas fa-university" style="margin-right: 6px;"></i>${report.school_name}</span>
                                <span><i class="fas fa-calendar" style="margin-right: 6px;"></i>${report.created_at}</span>
                            </div>
                            ${report.period_start && report.period_end ? `
                                <div style="margin-top: 8px; font-size: 12px; color: #9CA3AF;">
                                    <i class="fas fa-clock"></i> Period: ${report.period_start} - ${report.period_end}
                                </div>
                            ` : ''}
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <button onclick="viewReport(${report.id})" style="background: #DBEAFE; color: #1E40AF; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            ${report.status === 'submitted' ? `
                                <button onclick="reviewReport(${report.id})" style="background: linear-gradient(135deg, #7B1D3A, #5a1428); color: white; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-check-circle"></i> Review
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function filterReports() {
            renderReportsList();
        }

        function getStatusColor(status) {
            switch(status) {
                case 'submitted': return '#F59E0B';
                case 'reviewed': return '#10B981';
                case 'acknowledged': return '#059669';
                default: return '#9CA3AF';
            }
        }

        function getStatusBackground(status) {
            switch(status) {
                case 'submitted': return '#FEF3C7';
                case 'reviewed': return '#D1FAE5';
                case 'acknowledged': return '#ECFDF5';
                default: return '#F3F4F6';
            }
        }

        function getStatusTextColor(status) {
            switch(status) {
                case 'submitted': return '#92400E';
                case 'reviewed': return '#065F46';
                case 'acknowledged': return '#047857';
                default: return '#6B7280';
            }
        }

        function getStatusLabel(status) {
            switch(status) {
                case 'submitted': return 'Pending Review';
                case 'reviewed': return 'Reviewed';
                case 'acknowledged': return 'Acknowledged';
                default: return status;
            }
        }

        function getTypeBackground(type) {
            switch(type) {
                case 'weekly': return '#DBEAFE';
                case 'monthly': return '#E0E7FF';
                case 'incident': return '#FEE2E2';
                case 'special': return '#F3E8FF';
                default: return '#F3F4F6';
            }
        }

        function getTypeColor(type) {
            switch(type) {
                case 'weekly': return '#1E40AF';
                case 'monthly': return '#4338CA';
                case 'incident': return '#991B1B';
                case 'special': return '#7C3AED';
                default: return '#6B7280';
            }
        }

        function viewReport(id) {
            const report = reportsData.find(r => r.id === id);
            if (!report) return;

            const modal = document.getElementById('viewReportModal');
            const title = document.getElementById('viewReportTitle');
            const subtitle = document.getElementById('viewReportSubtitle');
            const content = document.getElementById('viewReportContent');
            const footer = document.getElementById('viewReportFooter');

            // Update header
            title.textContent = report.title;
            subtitle.innerHTML = `<span style="display: inline-flex; align-items: center; gap: 6px;"><i class="fas fa-user"></i> ${report.team_leader_name}</span> <span style="margin: 0 8px;">•</span> <span>${report.report_type.charAt(0).toUpperCase() + report.report_type.slice(1)} Report</span>`;

            // Get status badge
            const getStatusBadge = (status) => {
                const badges = {
                    'submitted': '<span style="display: inline-flex; align-items: center; gap: 4px; background: #FEF3C7; color: #92400E; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;"><i class="fas fa-clock"></i> Pending Review</span>',
                    'reviewed': '<span style="display: inline-flex; align-items: center; gap: 4px; background: #DBEAFE; color: #1E40AF; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;"><i class="fas fa-check"></i> Reviewed</span>',
                    'acknowledged': '<span style="display: inline-flex; align-items: center; gap: 4px; background: #D1FAE5; color: #065F46; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;"><i class="fas fa-check-double"></i> Acknowledged</span>'
                };
                return badges[status] || badges['submitted'];
            };

            content.innerHTML = `
                <!-- Status and Info Bar -->
                <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #E5E7EB;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        ${getStatusBadge(report.status)}
                        <span style="font-size: 12px; color: #6B7280;"><i class="fas fa-calendar-alt" style="margin-right: 4px;"></i> ${report.created_at}</span>
                    </div>
                </div>

                <!-- Info Cards -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 24px;">
                    <div style="background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%); padding: 16px; border-radius: 12px; border: 1px solid #E5E7EB;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <div style="width: 32px; height: 32px; background: #7B1D3A; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-tie" style="color: white; font-size: 12px;"></i>
                            </div>
                            <span style="font-size: 11px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Team Leader</span>
                        </div>
                        <div style="font-weight: 600; color: #1F2937; font-size: 14px;">${report.team_leader_name}</div>
                        <div style="font-size: 12px; color: #6B7280; font-family: monospace;">${report.team_leader_code}</div>
                    </div>
                    <div style="background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%); padding: 16px; border-radius: 12px; border: 1px solid #E5E7EB;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <div style="width: 32px; height: 32px; background: #2563EB; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-school" style="color: white; font-size: 12px;"></i>
                            </div>
                            <span style="font-size: 11px; color: #6B7280; text-transform: uppercase; font-weight: 600;">School</span>
                        </div>
                        <div style="font-weight: 600; color: #1F2937; font-size: 14px;">${report.school_name}</div>
                    </div>
                    <div style="background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%); padding: 16px; border-radius: 12px; border: 1px solid #E5E7EB;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <div style="width: 32px; height: 32px; background: #059669; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-tag" style="color: white; font-size: 12px;"></i>
                            </div>
                            <span style="font-size: 11px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Report Type</span>
                        </div>
                        <div style="font-weight: 600; color: #1F2937; font-size: 14px;">${report.report_type.charAt(0).toUpperCase() + report.report_type.slice(1)}</div>
                    </div>
                </div>

                ${report.task_statistics ? `
                    <div style="margin-bottom: 24px;">
                        <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-chart-pie" style="color: #7B1D3A;"></i>Task Statistics
                        </h4>
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
                            <div style="text-align: center; padding: 14px 8px; background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%); border-radius: 10px; border: 1px solid #E5E7EB;">
                                <div style="font-size: 22px; font-weight: 700; color: #1F2937;">${report.task_statistics.total || 0}</div>
                                <div style="font-size: 10px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Total</div>
                            </div>
                            <div style="text-align: center; padding: 14px 8px; background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); border-radius: 10px; border: 1px solid #A7F3D0;">
                                <div style="font-size: 22px; font-weight: 700; color: #059669;">${report.task_statistics.completed || 0}</div>
                                <div style="font-size: 10px; color: #065F46; text-transform: uppercase; font-weight: 600;">Completed</div>
                            </div>
                            <div style="text-align: center; padding: 14px 8px; background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%); border-radius: 10px; border: 1px solid #BFDBFE;">
                                <div style="font-size: 22px; font-weight: 700; color: #2563EB;">${report.task_statistics.in_progress || 0}</div>
                                <div style="font-size: 10px; color: #1E40AF; text-transform: uppercase; font-weight: 600;">In Progress</div>
                            </div>
                            <div style="text-align: center; padding: 14px 8px; background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%); border-radius: 10px; border: 1px solid #FDE68A;">
                                <div style="font-size: 22px; font-weight: 700; color: #D97706;">${report.task_statistics.pending || 0}</div>
                                <div style="font-size: 10px; color: #92400E; text-transform: uppercase; font-weight: 600;">Pending</div>
                            </div>
                        </div>
                    </div>
                ` : ''}

                <div style="margin-bottom: 20px;">
                    <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-align-left" style="color: #7B1D3A;"></i>Summary
                    </h4>
                    <div style="background: #F9FAFB; padding: 16px; border-radius: 10px; white-space: pre-wrap; line-height: 1.6; color: #374151; border: 1px solid #E5E7EB;">${report.summary || 'No summary provided.'}</div>
                </div>

                ${report.accomplishments ? `
                    <div style="margin-bottom: 20px;">
                        <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-trophy" style="color: #10B981;"></i>Accomplishments
                        </h4>
                        <div style="background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); padding: 16px; border-radius: 10px; white-space: pre-wrap; line-height: 1.6; color: #065F46; border: 1px solid #A7F3D0;">${report.accomplishments}</div>
                    </div>
                ` : ''}

                ${report.challenges ? `
                    <div style="margin-bottom: 20px;">
                        <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-exclamation-triangle" style="color: #F59E0B;"></i>Challenges
                        </h4>
                        <div style="background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%); padding: 16px; border-radius: 10px; white-space: pre-wrap; line-height: 1.6; color: #92400E; border: 1px solid #FDE68A;">${report.challenges}</div>
                    </div>
                ` : ''}

                ${report.recommendations ? `
                    <div style="margin-bottom: 20px;">
                        <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-lightbulb" style="color: #8B5CF6;"></i>Recommendations
                        </h4>
                        <div style="background: linear-gradient(135deg, #F5F3FF 0%, #EDE9FE 100%); padding: 16px; border-radius: 10px; white-space: pre-wrap; line-height: 1.6; color: #5B21B6; border: 1px solid #DDD6FE;">${report.recommendations}</div>
                    </div>
                ` : ''}

                ${report.admin_feedback ? `
                    <div style="margin-bottom: 20px;">
                        <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-comment-dots" style="color: #7B1D3A;"></i>Admin Feedback
                        </h4>
                        <div style="background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%); border-left: 4px solid #7B1D3A; padding: 16px; border-radius: 0 10px 10px 0; white-space: pre-wrap; line-height: 1.6; color: #7B1D3A;">
                            ${report.admin_feedback}
                            ${report.reviewed_at ? `<div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(123, 29, 58, 0.2); font-size: 12px; color: #9B2C2C; display: flex; align-items: center; gap: 6px;"><i class="fas fa-clock"></i> Reviewed on ${report.reviewed_at}</div>` : ''}
                        </div>
                    </div>
                ` : ''}
            `;

            footer.innerHTML = `
                <button class="btn-modal secondary" onclick="closeViewReportModal()" style="display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-times"></i> Close
                </button>
                ${report.status === 'submitted' ? `
                    <button class="btn-modal primary" onclick="closeViewReportModal(); reviewReport(${report.id});" style="display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #7B1D3A 0%, #A62450 100%);">
                        <i class="fas fa-clipboard-check"></i> Review This Report
                    </button>
                ` : ''}
            `;

            modal.style.display = 'flex';
        }

        function closeViewReportModal() {
            document.getElementById('viewReportModal').style.display = 'none';
        }

        function reviewReport(id) {
            const report = reportsData.find(r => r.id === id);
            if (!report) return;

            const modal = document.getElementById('viewReportModal');
            const title = document.getElementById('viewReportTitle');
            const subtitle = document.getElementById('viewReportSubtitle');
            const content = document.getElementById('viewReportContent');
            const footer = document.getElementById('viewReportFooter');

            // Update header for review mode
            title.textContent = 'Review Report';
            subtitle.innerHTML = `<span style="display: inline-flex; align-items: center; gap: 6px;"><i class="fas fa-file-alt"></i> ${report.title}</span>`;

            content.innerHTML = `
                <!-- Review Alert Banner -->
                <div style="background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); border: 1px solid #F59E0B; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; background: #D97706; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-hourglass-half" style="color: white; font-size: 16px;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #92400E; font-size: 14px;">Pending Review</div>
                            <div style="font-size: 13px; color: #78350F;">This report from <strong>${report.team_leader_name}</strong> is awaiting your review.</div>
                        </div>
                    </div>
                </div>

                <!-- Report Info Cards -->
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 24px;">
                    <div style="background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%); padding: 14px 16px; border-radius: 10px; border: 1px solid #E5E7EB;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <i class="fas fa-tag" style="color: #7B1D3A; font-size: 12px;"></i>
                            <span style="font-size: 11px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Report Type</span>
                        </div>
                        <div style="font-weight: 600; color: #1F2937; font-size: 14px;">${report.report_type.charAt(0).toUpperCase() + report.report_type.slice(1)} Report</div>
                    </div>
                    <div style="background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%); padding: 14px 16px; border-radius: 10px; border: 1px solid #E5E7EB;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <i class="fas fa-school" style="color: #2563EB; font-size: 12px;"></i>
                            <span style="font-size: 11px; color: #6B7280; text-transform: uppercase; font-weight: 600;">School</span>
                        </div>
                        <div style="font-weight: 600; color: #1F2937; font-size: 14px;">${report.school_name}</div>
                    </div>
                </div>

                <!-- Summary Section -->
                <div style="margin-bottom: 20px;">
                    <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-align-left" style="color: #7B1D3A;"></i>Report Summary
                    </h4>
                    <div style="background: #F9FAFB; padding: 16px; border-radius: 10px; white-space: pre-wrap; max-height: 150px; overflow-y: auto; line-height: 1.6; color: #374151; border: 1px solid #E5E7EB;">${report.summary || 'No summary provided.'}</div>
                </div>

                <!-- Feedback Section -->
                <div style="margin-bottom: 10px;">
                    <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-comment-dots" style="color: #7B1D3A;"></i>Your Feedback
                        <span style="font-size: 11px; color: #9CA3AF; font-weight: 400;">(Optional)</span>
                    </h4>
                    <textarea id="reviewFeedback" style="width: 100%; padding: 14px 16px; border: 2px solid #E5E7EB; border-radius: 10px; font-size: 14px; min-height: 100px; resize: vertical; transition: all 0.3s; box-sizing: border-box;" placeholder="Provide feedback or comments to the team leader..." onfocus="this.style.borderColor='#7B1D3A'; this.style.boxShadow='0 0 0 3px rgba(123, 29, 58, 0.1)';" onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';"></textarea>
                </div>
            `;

            footer.innerHTML = `
                <button class="btn-modal secondary" onclick="closeViewReportModal()" style="display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-arrow-left"></i> Cancel
                </button>
                <button class="btn-modal" onclick="submitReview(${report.id}, 'reviewed')" style="background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); color: white; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-check"></i> Mark as Reviewed
                </button>
                <button class="btn-modal primary" onclick="submitReview(${report.id}, 'acknowledged')" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-check-double"></i> Acknowledge
                </button>
            `;

            modal.style.display = 'flex';
        }

        async function submitReview(id, status) {
            const feedback = document.getElementById('reviewFeedback').value;

            try {
                const response = await fetch(`/admin/api/team-reports/${id}/review`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status, admin_feedback: feedback })
                });

                const result = await response.json();

                if (response.ok) {
                    closeViewReportModal();
                    loadTeamReportsData();
                    showToast('success', 'Success', result.message);
                } else {
                    showToast('error', 'Error', result.error || 'Failed to submit review');
                }
            } catch (error) {
                console.error('Error submitting review:', error);
                showToast('error', 'Error', 'Failed to submit review');
            }
        }

        // ===== RESEARCH TRACKING FUNCTIONS =====

        function switchResearchView(viewType) {
            const kanbanView = document.getElementById('kanban-view');
            const listView = document.getElementById('list-view');

            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            event.target.closest('.filter-tab').classList.add('active');

            if (viewType === 'kanban') {
                kanbanView.style.display = 'grid';
                listView.style.display = 'none';
            } else if (viewType === 'list') {
                kanbanView.style.display = 'none';
                listView.style.display = 'block';
            } else if (viewType === 'timeline') {
                alert('Timeline view coming soon!');
            }
        }

        function filterResearchProjects() {
            console.log('Filtering research projects...');
            // In a real app, this would filter the projects based on selected filters
        }

        function searchResearchProjects() {
            console.log('Searching research projects...');
            // In a real app, this would search projects
        }

        function openNewProjectModal() {
            alert('New Project Modal - Feature coming soon!\n\nThis will allow you to:\n- Enter project name and description\n- Add team members\n- Set project category\n- Define initial milestones');
        }

        function viewProjectDetails(projectId) {
            alert(`Viewing Project #${projectId} Details\n\nThis will show:\n- Complete project information\n- Stage-by-stage progress\n- Training completion status\n- Development phase details\n- Team members and roles\n- Deliverables and milestones\n- Timeline and history`);
        }

        function promoteToIncubatee(projectId) {
            if (confirm('Promote this project to Incubatee status?\n\nThis will:\n- Move project to Incubatee Tracker\n- Create new MOA record\n- Notify team members\n- Archive research tracking history')) {
                alert(`Project #${projectId} promoted successfully!\nRedirecting to Incubatee Tracker...`);
            }
        }

        // ===== INCUBATEE TRACKER FUNCTIONS =====

        function switchIncubateeTab(tabType) {
            const moaTable = document.getElementById('moa-table');
            const paymentsTable = document.getElementById('payments-table');
            const moaBtn = document.getElementById('moaTabBtn');
            const paymentsBtn = document.getElementById('paymentsTabBtn');

            if (tabType === 'moa') {
                moaTable.style.display = 'block';
                paymentsTable.style.display = 'none';
                moaBtn.classList.add('active');
                paymentsBtn.classList.remove('active');
            } else if (tabType === 'payments') {
                moaTable.style.display = 'none';
                paymentsTable.style.display = 'block';
                moaBtn.classList.remove('active');
                paymentsBtn.classList.add('active');
            }
        }

        function filterIncubatees() {
            const statusFilter = document.getElementById('incubateeStatusFilter').value;
            const rows = document.querySelectorAll('.incubatee-row');

            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const matchStatus = statusFilter === 'all' || status === statusFilter;
                row.style.display = matchStatus ? '' : 'none';
            });
        }

        function searchIncubatees() {
            const searchTerm = document.getElementById('incubateeSearchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.incubatee-row');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }

        // ========== STARTUP DATA FOR MODALS ==========
        @php
            $startupDocumentsData = isset($startupDocuments) ? $startupDocuments->map(function($d) {
                return [
                    'id' => $d->id,
                    'tracking_code' => $d->tracking_code,
                    'company_name' => $d->company_name,
                    'contact_person' => $d->contact_person,
                    'email' => $d->email,
                    'phone' => $d->phone,
                    'document_type' => $d->document_type,
                    'original_filename' => $d->original_filename,
                    'file_path' => $d->file_path,
                    'notes' => $d->notes,
                    'status' => $d->status,
                    'admin_notes' => $d->admin_notes,
                    'created_at' => $d->created_at->format('M d, Y h:i A'),
                    'reviewed_at' => $d->reviewed_at ? $d->reviewed_at->format('M d, Y h:i A') : null,
                ];
            })->keyBy('id')->toArray() : [];

            $moaRequestsData = isset($moaRequests) ? $moaRequests->map(function($m) {
                return [
                    'id' => $m->id,
                    'tracking_code' => $m->tracking_code,
                    'company_name' => $m->company_name,
                    'contact_person' => $m->contact_person,
                    'email' => $m->email,
                    'phone' => $m->phone,
                    'moa_purpose' => $m->moa_purpose,
                    'moa_details' => $m->moa_details,
                    'notes' => $m->notes,
                    'status' => $m->status,
                    'admin_notes' => $m->admin_notes,
                    'created_at' => $m->created_at->format('M d, Y h:i A'),
                    'reviewed_at' => $m->reviewed_at ? $m->reviewed_at->format('M d, Y h:i A') : null,
                ];
            })->keyBy('id')->toArray() : [];

            $paymentSubmissionsData = isset($paymentSubmissions) ? $paymentSubmissions->map(function($p) {
                return [
                    'id' => $p->id,
                    'tracking_code' => $p->tracking_code,
                    'company_name' => $p->company_name,
                    'contact_person' => $p->contact_person,
                    'email' => $p->email,
                    'phone' => $p->phone,
                    'invoice_number' => $p->invoice_number,
                    'amount' => $p->amount,
                    'payment_method' => $p->payment_method,
                    'payment_date' => $p->payment_date ? \Carbon\Carbon::parse($p->payment_date)->format('M d, Y') : null,
                    'payment_proof_path' => $p->payment_proof_path,
                    'notes' => $p->notes,
                    'status' => $p->status,
                    'admin_notes' => $p->admin_notes,
                    'created_at' => $p->created_at->format('M d, Y h:i A'),
                    'reviewed_at' => $p->reviewed_at ? $p->reviewed_at->format('M d, Y h:i A') : null,
                ];
            })->keyBy('id')->toArray() : [];

            $roomIssuesData = isset($roomIssues) ? $roomIssues->map(function($r) {
                return [
                    'id' => $r->id,
                    'tracking_code' => $r->tracking_code,
                    'company_name' => $r->company_name,
                    'contact_person' => $r->contact_person,
                    'email' => $r->email,
                    'phone' => $r->phone,
                    'room_number' => $r->room_number,
                    'issue_type' => $r->issue_type,
                    'issue_type_label' => $r->issue_type_label,
                    'description' => $r->description,
                    'photo_path' => $r->photo_path,
                    'priority' => $r->priority,
                    'status' => $r->status,
                    'status_label' => $r->status_label,
                    'admin_notes' => $r->admin_notes,
                    'created_at' => $r->created_at->format('M d, Y h:i A'),
                    'resolved_at' => $r->resolved_at ? $r->resolved_at->format('M d, Y h:i A') : null,
                ];
            })->keyBy('id')->toArray() : [];
        @endphp

        const startupDocumentsData = @json($startupDocumentsData);
        const moaRequestsData = @json($moaRequestsData);
        const paymentSubmissionsData = @json($paymentSubmissionsData);
        const roomIssuesData = @json($roomIssuesData);

        let currentDocId = null;
        let currentMoaId = null;
        let currentPaymentId = null;
        let currentIssueId = null;

        // ========== KANBAN DRAG AND DROP FUNCTIONS ==========

        let draggedCard = null;

        function dragStart(event) {
            draggedCard = event.target;
            event.target.classList.add('dragging');
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', event.target.dataset.id);
        }

        function dragEnd(event) {
            event.target.classList.remove('dragging');
            // Remove drag-over class from all columns
            document.querySelectorAll('.kanban-column').forEach(col => {
                col.classList.remove('drag-over');
            });
            draggedCard = null;
        }

        function allowDrop(event) {
            event.preventDefault();
            event.currentTarget.classList.add('drag-over');
        }

        function dragLeave(event) {
            event.currentTarget.classList.remove('drag-over');
        }

        function dropCard(event) {
            event.preventDefault();
            const column = event.currentTarget;
            column.classList.remove('drag-over');

            const docId = event.dataTransfer.getData('text/plain');
            const newStatus = column.dataset.status;

            if (draggedCard && docId) {
                // Move card to new column
                const cardsContainer = column.querySelector('.kanban-cards');

                // Remove empty message if exists
                const emptyMsg = cardsContainer.querySelector('.kanban-empty');
                if (emptyMsg) {
                    emptyMsg.remove();
                }

                // Update card class based on new status
                draggedCard.classList.remove('pending', 'reviewing', 'success', 'rejected');

                // Update the status badge label and style
                const statusBadge = draggedCard.querySelector('.status-badge');

                // Get the button container
                const buttonContainer = draggedCard.querySelector('div[style*="margin-top: auto"]');
                const filePath = draggedCard.querySelector('a[href*="storage"]')?.getAttribute('href') || '#';

                switch(newStatus) {
                    case 'pending':
                        draggedCard.classList.add('pending');
                        if (statusBadge) {
                            statusBadge.textContent = 'Pending';
                            statusBadge.style.background = '#FEF3C7';
                            statusBadge.style.color = '#92400E';
                        }
                        if (buttonContainer) {
                            buttonContainer.innerHTML = `
                                <button onclick="event.stopPropagation(); openReviewDocumentModal('${docId}')" style="flex: 1; padding: 5px 8px; font-size: 10px; background: #10B981; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-check"></i> Review
                                </button>
                                <a href="${filePath}" target="_blank" onclick="event.stopPropagation();" style="padding: 5px 8px; font-size: 10px; background: #3B82F6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none;">
                                    <i class="fas fa-download"></i>
                                </a>
                            `;
                        }
                        break;
                    case 'under_review':
                        draggedCard.classList.add('reviewing');
                        if (statusBadge) {
                            statusBadge.textContent = 'Reviewing';
                            statusBadge.style.background = '#DBEAFE';
                            statusBadge.style.color = '#1E40AF';
                        }
                        if (buttonContainer) {
                            buttonContainer.innerHTML = `
                                <button onclick="event.stopPropagation(); openReviewDocumentModal('${docId}')" style="flex: 1; padding: 5px 8px; font-size: 10px; background: #10B981; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-check"></i> Approve/Reject
                                </button>
                                <a href="${filePath}" target="_blank" onclick="event.stopPropagation();" style="padding: 5px 8px; font-size: 10px; background: #3B82F6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none;">
                                    <i class="fas fa-download"></i>
                                </a>
                            `;
                        }
                        break;
                    case 'approved':
                        draggedCard.classList.add('success');
                        if (statusBadge) {
                            statusBadge.textContent = 'Approved';
                            statusBadge.style.background = '#DCFCE7';
                            statusBadge.style.color = '#166534';
                        }
                        if (buttonContainer) {
                            buttonContainer.innerHTML = `
                                <a href="${filePath}" target="_blank" onclick="event.stopPropagation();" style="flex: 1; padding: 5px 8px; font-size: 10px; background: #3B82F6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center;">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            `;
                        }
                        break;
                    case 'rejected':
                        draggedCard.classList.add('rejected');
                        if (statusBadge) {
                            statusBadge.textContent = 'Rejected';
                            statusBadge.style.background = '#FEE2E2';
                            statusBadge.style.color = '#991B1B';
                        }
                        if (buttonContainer) {
                            buttonContainer.innerHTML = `
                                <button onclick="event.stopPropagation(); openReviewDocumentModal('${docId}')" style="flex: 1; padding: 5px 8px; font-size: 10px; background: #6B7280; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-redo"></i> Re-review
                                </button>
                                <a href="${filePath}" target="_blank" onclick="event.stopPropagation();" style="padding: 5px 8px; font-size: 10px; background: #3B82F6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none;">
                                    <i class="fas fa-download"></i>
                                </a>
                            `;
                        }
                        break;
                }

                cardsContainer.appendChild(draggedCard);

                // Update status via AJAX
                updateDocumentStatus(docId, newStatus);

                // Update counts
                updateKanbanCounts();
            }
        }

        function updateDocumentStatus(docId, newStatus) {
            fetch(`/admin/startup-documents/${docId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Document status updated successfully', 'success');
                } else {
                    showNotification('Failed to update status', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error updating status', 'error');
            });
        }

        function updateKanbanCounts() {
            const statuses = ['pending', 'under_review', 'approved', 'rejected'];
            statuses.forEach(status => {
                const column = document.querySelector(`.kanban-column[data-status="${status}"]`);
                if (column) {
                    const count = column.querySelectorAll('.kanban-card').length;
                    const countBadge = document.getElementById(`${status}-count`);
                    if (countBadge) {
                        countBadge.textContent = count;
                    }
                }
            });
        }

        // ========== DOCUMENT DETAILS MODAL FUNCTIONS ==========

        function viewDocumentDetails(docId) {
            const doc = startupDocumentsData[docId];
            if (!doc) {
                alert('Document not found');
                return;
            }

            currentDocId = docId;

            const statusColors = {
                'pending': { bg: '#FEF3C7', text: '#92400E' },
                'under_review': { bg: '#DBEAFE', text: '#1E40AF' },
                'approved': { bg: '#DCFCE7', text: '#166534' },
                'rejected': { bg: '#FEE2E2', text: '#991B1B' }
            };
            const color = statusColors[doc.status] || { bg: '#E5E7EB', text: '#374151' };

            const content = `
                <div style="display: grid; gap: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #E5E7EB;">
                        <div>
                            <div style="font-size: 12px; color: #6B7280;">Tracking Code</div>
                            <div style="font-size: 18px; font-weight: 700; color: #7B1D3A;">${doc.tracking_code}</div>
                        </div>
                        <span style="background: ${color.bg}; color: ${color.text}; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            ${doc.status.replace('_', ' ').toUpperCase()}
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Company Name</div>
                            <div style="font-weight: 600;">${doc.company_name}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Contact Person</div>
                            <div style="font-weight: 600;">${doc.contact_person}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Email</div>
                            <div>${doc.email}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Phone</div>
                            <div>${doc.phone || 'N/A'}</div>
                        </div>
                    </div>

                    <div style="background: #F9FAFB; padding: 16px; border-radius: 8px;">
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">Document Information</div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div>
                                <span style="font-weight: 600;">Type:</span> ${doc.document_type}
                            </div>
                            <div>
                                <span style="font-weight: 600;">File:</span> ${doc.original_filename}
                            </div>
                        </div>
                    </div>

                    ${doc.notes ? `
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Submitter Notes</div>
                        <div style="background: #F3F4F6; padding: 12px; border-radius: 8px;">${doc.notes}</div>
                    </div>
                    ` : ''}

                    ${doc.admin_notes ? `
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Admin Notes</div>
                        <div style="background: #FEF3C7; padding: 12px; border-radius: 8px;">${doc.admin_notes}</div>
                    </div>
                    ` : ''}

                    <div style="display: flex; gap: 16px; font-size: 12px; color: #6B7280;">
                        <div><i class="fas fa-calendar"></i> Submitted: ${doc.created_at}</div>
                        ${doc.reviewed_at ? `<div><i class="fas fa-check-circle"></i> Reviewed: ${doc.reviewed_at}</div>` : ''}
                    </div>
                </div>
            `;

            document.getElementById('documentDetailsContent').innerHTML = content;
            document.getElementById('documentDownloadBtn').href = '/storage/' + doc.file_path;
            document.getElementById('documentDetailsModal').style.display = 'flex';
        }

        function closeDocumentDetailsModal() {
            document.getElementById('documentDetailsModal').style.display = 'none';
            currentDocId = null;
        }

        function openReviewDocumentModal(docId = null) {
            const id = docId || currentDocId;
            const doc = startupDocumentsData[id];
            if (!doc) return;

            document.getElementById('reviewDocId').value = id;
            document.getElementById('reviewDocInfo').innerHTML = `
                <strong>${doc.tracking_code}</strong><br>
                ${doc.company_name} - ${doc.document_type}
            `;
            document.getElementById('reviewDocAction').value = '';
            document.getElementById('reviewDocNotes').value = '';

            closeDocumentDetailsModal();
            document.getElementById('reviewDocumentModal').style.display = 'flex';
        }

        function closeReviewDocumentModal() {
            document.getElementById('reviewDocumentModal').style.display = 'none';
        }

        function toggleReviewNotes() {
            const action = document.getElementById('reviewDocAction').value;
            document.getElementById('reviewNotesGroup').style.display = action === 'rejected' ? 'block' : 'block';
        }

        function submitDocumentReview() {
            const docId = document.getElementById('reviewDocId').value;
            const action = document.getElementById('reviewDocAction').value;
            const notes = document.getElementById('reviewDocNotes').value;

            if (!action) {
                alert('Please select a review action');
                return;
            }

            // Disable button and show loading
            const submitBtn = document.querySelector('#reviewDocumentModal .btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

            fetch(`/admin/submissions/${docId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: action,
                    admin_notes: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Document ${data.submission.tracking_code} has been ${action === 'approved' ? 'approved' : action === 'rejected' ? 'rejected' : 'updated'}!`);
                    closeReviewDocumentModal();
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update submission');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the submission');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }

        // ========== MOA DETAILS MODAL FUNCTIONS ==========

        function viewMoaDetails(moaId) {
            const moa = moaRequestsData[moaId];
            if (!moa) {
                alert('MOA request not found');
                return;
            }

            currentMoaId = moaId;

            const statusColors = {
                'pending': { bg: '#FEF3C7', text: '#92400E' },
                'under_review': { bg: '#DBEAFE', text: '#1E40AF' },
                'approved': { bg: '#DCFCE7', text: '#166534' },
                'rejected': { bg: '#FEE2E2', text: '#991B1B' }
            };
            const color = statusColors[moa.status] || { bg: '#E5E7EB', text: '#374151' };

            const content = `
                <div style="display: grid; gap: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #E5E7EB;">
                        <div>
                            <div style="font-size: 12px; color: #6B7280;">Tracking Code</div>
                            <div style="font-size: 18px; font-weight: 700; color: #7B1D3A;">${moa.tracking_code}</div>
                        </div>
                        <span style="background: ${color.bg}; color: ${color.text}; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            ${moa.status.replace('_', ' ').toUpperCase()}
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Company/Startup Name</div>
                            <div style="font-weight: 600;">${moa.company_name}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Contact Person</div>
                            <div style="font-weight: 600;">${moa.contact_person}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Email</div>
                            <div>${moa.email}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Phone</div>
                            <div>${moa.phone || 'N/A'}</div>
                        </div>
                    </div>

                    <div style="background: #F9FAFB; padding: 16px; border-radius: 8px;">
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">MOA Purpose</div>
                        <div style="font-weight: 600; font-size: 16px; color: #7B1D3A;">${moa.moa_purpose}</div>
                    </div>

                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">MOA Details</div>
                        <div style="background: #F3F4F6; padding: 12px; border-radius: 8px; white-space: pre-wrap;">${moa.moa_details}</div>
                    </div>

                    ${moa.notes ? `
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Additional Notes</div>
                        <div style="background: #F3F4F6; padding: 12px; border-radius: 8px;">${moa.notes}</div>
                    </div>
                    ` : ''}

                    ${moa.admin_notes ? `
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Admin Notes</div>
                        <div style="background: #FEF3C7; padding: 12px; border-radius: 8px;">${moa.admin_notes}</div>
                    </div>
                    ` : ''}

                    <div style="display: flex; gap: 16px; font-size: 12px; color: #6B7280;">
                        <div><i class="fas fa-calendar"></i> Submitted: ${moa.created_at}</div>
                        ${moa.reviewed_at ? `<div><i class="fas fa-check-circle"></i> Reviewed: ${moa.reviewed_at}</div>` : ''}
                    </div>
                </div>
            `;

            document.getElementById('moaDetailsContent').innerHTML = content;
            document.getElementById('moaDetailsModal').style.display = 'flex';
        }

        function closeMoaDetailsModal() {
            document.getElementById('moaDetailsModal').style.display = 'none';
            currentMoaId = null;
        }

        function openReviewMoaModal() {
            const moa = moaRequestsData[currentMoaId];
            if (!moa) return;

            document.getElementById('reviewMoaId').value = currentMoaId;
            document.getElementById('reviewMoaInfo').innerHTML = `
                <strong>${moa.tracking_code}</strong><br>
                ${moa.company_name} - ${moa.moa_purpose}
            `;
            document.getElementById('reviewMoaAction').value = '';
            document.getElementById('reviewMoaNotes').value = '';

            closeMoaDetailsModal();
            document.getElementById('reviewMoaModal').style.display = 'flex';
        }

        function closeReviewMoaModal() {
            document.getElementById('reviewMoaModal').style.display = 'none';
        }

        function submitMoaReview() {
            const moaId = document.getElementById('reviewMoaId').value;
            const action = document.getElementById('reviewMoaAction').value;
            const notes = document.getElementById('reviewMoaNotes').value;

            if (!action) {
                alert('Please select a review action');
                return;
            }

            // Disable button and show loading
            const submitBtn = document.querySelector('#reviewMoaModal .btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

            fetch(`/admin/submissions/${moaId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: action,
                    admin_notes: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`MOA Request ${data.submission.tracking_code} has been ${action === 'approved' ? 'approved' : action === 'rejected' ? 'rejected' : 'updated'}!`);
                    closeReviewMoaModal();
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update MOA request');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the MOA request');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }

        function generateMoaFromTemplate() {
            const moa = moaRequestsData[currentMoaId];
            closeMoaDetailsModal();

            // Pre-fill the template form with MOA data
            if (moa) {
                document.getElementById('moaCompanyName').value = moa.company_name;
                document.getElementById('moaRepresentative').value = moa.contact_person;
                // Set purpose based on moa_purpose
                const purposeMap = {
                    'Incubation Program': 'incubation',
                    'Co-working Space': 'coworking',
                    'Mentorship': 'mentorship',
                    'Partnership': 'partnership'
                };
                document.getElementById('moaPurpose').value = purposeMap[moa.moa_purpose] || 'other';
            }

            document.getElementById('moaTemplateModal').style.display = 'flex';
        }

        // ========== MOA TEMPLATE MODAL FUNCTIONS ==========

        function closeMoaTemplateModal() {
            document.getElementById('moaTemplateModal').style.display = 'none';
        }

        function openMoaTemplateModal() {
            // Clear the form for a fresh MOA
            document.getElementById('moaCompanyName').value = '';
            document.getElementById('moaRepresentative').value = '';
            document.getElementById('moaPosition').value = '';
            document.getElementById('moaAddress').value = '';
            document.getElementById('moaPurpose').value = '';
            document.getElementById('moaStartDate').value = '';
            document.getElementById('moaEndDate').value = '';
            document.getElementById('moaFee').value = '';
            document.getElementById('moaTerms').value = '';
            document.getElementById('moaPreviewContent').innerHTML = '<p style="color: #6B7280; font-style: italic;">Fill in the form and click "Generate Preview" to see the MOA document.</p>';

            document.getElementById('moaTemplateModal').style.display = 'flex';
        }

        function generateMoaPreview() {
            const companyName = document.getElementById('moaCompanyName').value;
            const representative = document.getElementById('moaRepresentative').value;
            const position = document.getElementById('moaPosition').value;
            const address = document.getElementById('moaAddress').value;
            const purpose = document.getElementById('moaPurpose').value;
            const startDate = document.getElementById('moaStartDate').value;
            const endDate = document.getElementById('moaEndDate').value;
            const fee = document.getElementById('moaFee').value;
            const terms = document.getElementById('moaTerms').value;

            if (!companyName || !representative || !position || !address || !purpose || !startDate || !endDate) {
                alert('Please fill in all required fields');
                return;
            }

            const purposeLabels = {
                'incubation': 'Business Incubation Program',
                'coworking': 'Co-working Space Usage',
                'mentorship': 'Mentorship Program',
                'partnership': 'Partnership Agreement',
                'other': 'Other Services'
            };

            const formatDate = (dateStr) => {
                const d = new Date(dateStr);
                return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            };

            const preview = `
                <div style="text-align: center; margin-bottom: 24px;">
                    <img src="/images/up-cebu-logo.png" alt="UP Cebu Logo" style="height: 60px; margin-bottom: 12px;" onerror="this.style.display='none'">
                    <h2 style="font-size: 18px; font-weight: 700; color: #7B1D3A; margin: 0;">UNIVERSITY OF THE PHILIPPINES CEBU</h2>
                    <p style="font-size: 12px; color: #6B7280; margin: 4px 0;">Gorordo Avenue, Lahug, Cebu City 6000</p>
                    <h3 style="font-size: 16px; font-weight: 700; margin: 16px 0 0 0; text-decoration: underline;">MEMORANDUM OF AGREEMENT</h3>
                </div>

                <div style="text-align: justify; font-size: 12px; line-height: 1.8;">
                    <p><strong>KNOW ALL MEN BY THESE PRESENTS:</strong></p>

                    <p>This Memorandum of Agreement (MOA) is entered into by and between:</p>

                    <p style="margin-left: 20px;">
                        <strong>UNIVERSITY OF THE PHILIPPINES CEBU</strong>, a constituent unit of the University of the Philippines System,
                        represented herein by its Chancellor, with office address at Gorordo Avenue, Lahug, Cebu City 6000,
                        hereinafter referred to as "<strong>UP CEBU</strong>";
                    </p>

                    <p style="text-align: center;">- and -</p>

                    <p style="margin-left: 20px;">
                        <strong>${companyName.toUpperCase()}</strong>, represented herein by <strong>${representative}</strong>,
                        ${position}, with business address at ${address},
                        hereinafter referred to as the "<strong>PARTNER</strong>";
                    </p>

                    <p><strong>WITNESSETH:</strong></p>

                    <p><strong>WHEREAS</strong>, UP CEBU, through its Technology Business Incubator, aims to support and nurture startup
                    enterprises and innovative business ventures;</p>

                    <p><strong>WHEREAS</strong>, the PARTNER desires to avail of the ${purposeLabels[purpose]} offered by UP CEBU;</p>

                    <p><strong>NOW, THEREFORE</strong>, for and in consideration of the foregoing premises and the mutual covenants
                    herein contained, the parties agree as follows:</p>

                    <p><strong>ARTICLE I - PURPOSE</strong></p>
                    <p>This MOA is entered into for the purpose of: <strong>${purposeLabels[purpose]}</strong></p>

                    <p><strong>ARTICLE II - TERM</strong></p>
                    <p>This Agreement shall be effective from <strong>${formatDate(startDate)}</strong> to <strong>${formatDate(endDate)}</strong>,
                    unless sooner terminated by either party upon thirty (30) days prior written notice.</p>

                    ${fee ? `
                    <p><strong>ARTICLE III - FEES</strong></p>
                    <p>The PARTNER agrees to pay a monthly fee of <strong>₱${parseFloat(fee).toLocaleString('en-US', {minimumFractionDigits: 2})}</strong>
                    for the duration of this agreement, payable on or before the 5th day of each month.</p>
                    ` : ''}

                    <p><strong>ARTICLE IV - OBLIGATIONS</strong></p>
                    <p>Both parties shall comply with all applicable laws, rules, and regulations, and shall perform their
                    respective obligations under this Agreement in good faith.</p>

                    ${terms ? `
                    <p><strong>ARTICLE V - SPECIAL TERMS</strong></p>
                    <p>${terms}</p>
                    ` : ''}

                    <p><strong>IN WITNESS WHEREOF</strong>, the parties have hereunto set their hands this _____ day of ____________, 20____.</p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 40px;">
                        <div style="text-align: center;">
                            <p style="margin-bottom: 40px;"><strong>FOR UP CEBU:</strong></p>
                            <div style="border-top: 1px solid #000; padding-top: 8px;">
                                <strong>DR. LIZA L. CORRO</strong><br>
                                <span style="font-size: 11px;">Chancellor</span>
                            </div>
                        </div>
                        <div style="text-align: center;">
                            <p style="margin-bottom: 40px;"><strong>FOR THE PARTNER:</strong></p>
                            <div style="border-top: 1px solid #000; padding-top: 8px;">
                                <strong>${representative.toUpperCase()}</strong><br>
                                <span style="font-size: 11px;">${position}</span>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 40px;">
                        <p><strong>SIGNED IN THE PRESENCE OF:</strong></p>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 20px;">
                            <div style="border-top: 1px solid #000; padding-top: 8px; text-align: center;">Witness 1</div>
                            <div style="border-top: 1px solid #000; padding-top: 8px; text-align: center;">Witness 2</div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('moaPreviewContent').innerHTML = preview;
        }

        function printMoa() {
            const content = document.getElementById('moaPreviewContent').innerHTML;
            if (content.includes('Fill in the form')) {
                alert('Please generate a preview first');
                return;
            }

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>MOA - ${document.getElementById('moaCompanyName').value}</title>
                    <style>
                        body { font-family: 'Times New Roman', serif; padding: 40px; max-width: 800px; margin: 0 auto; }
                        @media print { body { padding: 20px; } }
                    </style>
                </head>
                <body>${content}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        function downloadMoaAsPdf() {
            alert('PDF download feature requires a PDF library like jsPDF or server-side generation.\n\nFor now, please use the Print function and select "Save as PDF" as the destination.');
            printMoa();
        }

        // ========== PAYMENT DETAILS MODAL FUNCTIONS ==========

        function viewPaymentDetails(paymentId) {
            const payment = paymentSubmissionsData[paymentId];
            if (!payment) {
                alert('Payment submission not found');
                return;
            }

            currentPaymentId = paymentId;

            const statusColors = {
                'pending': { bg: '#FEF3C7', text: '#92400E' },
                'under_review': { bg: '#DBEAFE', text: '#1E40AF' },
                'approved': { bg: '#DCFCE7', text: '#166534' },
                'rejected': { bg: '#FEE2E2', text: '#991B1B' }
            };
            const color = statusColors[payment.status] || { bg: '#E5E7EB', text: '#374151' };

            const paymentMethodLabels = {
                'bank_transfer': '🏦 Bank Transfer',
                'bank_deposit': '💵 Bank Deposit',
                'gcash': '<img src="/images/gcash.jpg" alt="GCash" style="height: 16px; width: auto; vertical-align: middle; margin-right: 4px;">GCash',
                'maya': '📱 Maya',
                'check': '📄 Check Payment',
                'cash': '💰 Cash'
            };
            const methodLabel = paymentMethodLabels[payment.payment_method] || payment.payment_method || 'N/A';

            const content = `
                <div style="display: flex; gap: 24px;">
                    <!-- Left side: Payment Details -->
                    <div style="flex: 1; display: grid; gap: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #E5E7EB;">
                            <div>
                                <div style="font-size: 12px; color: #6B7280;">Tracking Code</div>
                                <div style="font-size: 18px; font-weight: 700; color: #7B1D3A;">${payment.tracking_code}</div>
                            </div>
                            <span style="background: ${color.bg}; color: ${color.text}; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                ${payment.status === 'approved' ? 'VERIFIED' : payment.status.replace('_', ' ').toUpperCase()}
                            </span>
                        </div>

                        <div style="background: linear-gradient(135deg, #10B981, #059669); color: white; padding: 16px; border-radius: 12px; text-align: center;">
                            <div style="font-size: 12px; opacity: 0.9;">Payment Amount</div>
                            <div style="font-size: 28px; font-weight: 700;">₱${parseFloat(payment.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                            <div style="font-size: 13px; margin-top: 4px;">Invoice #${payment.invoice_number}</div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div>
                                <div style="font-size: 11px; color: #6B7280; margin-bottom: 2px;">Company Name</div>
                                <div style="font-weight: 600; font-size: 13px;">${payment.company_name}</div>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: #6B7280; margin-bottom: 2px;">Contact Person</div>
                                <div style="font-weight: 600; font-size: 13px;">${payment.contact_person}</div>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: #6B7280; margin-bottom: 2px;">Email</div>
                                <div style="font-size: 13px;">${payment.email}</div>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: #6B7280; margin-bottom: 2px;">Phone</div>
                                <div style="font-size: 13px;">${payment.phone || 'N/A'}</div>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: #6B7280; margin-bottom: 2px;">Payment Method</div>
                                <div style="font-weight: 600; font-size: 13px;">${methodLabel}</div>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: #6B7280; margin-bottom: 2px;">Payment Date</div>
                                <div style="font-weight: 600; font-size: 13px;">${payment.payment_date || 'N/A'}</div>
                            </div>
                        </div>

                        ${payment.notes ? `
                        <div>
                            <div style="font-size: 11px; color: #6B7280; margin-bottom: 4px;">Submitter Notes</div>
                            <div style="background: #F3F4F6; padding: 10px; border-radius: 8px; font-size: 13px;">${payment.notes}</div>
                        </div>
                        ` : ''}

                        ${payment.admin_notes ? `
                        <div>
                            <div style="font-size: 11px; color: #6B7280; margin-bottom: 4px;">Admin Notes</div>
                            <div style="background: #FEF3C7; padding: 10px; border-radius: 8px; font-size: 13px;">${payment.admin_notes}</div>
                        </div>
                        ` : ''}

                        <div style="display: flex; gap: 16px; font-size: 11px; color: #6B7280;">
                            <div><i class="fas fa-calendar"></i> Submitted: ${payment.created_at}</div>
                            ${payment.reviewed_at ? `<div><i class="fas fa-check-circle"></i> Verified: ${payment.reviewed_at}</div>` : ''}
                        </div>
                    </div>

                    <!-- Right side: Payment Proof -->
                    ${payment.payment_proof_path ? `
                    <div style="width: 280px; flex-shrink: 0;">
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px; font-weight: 600;">Payment Proof</div>
                        <a href="/storage/${payment.payment_proof_path}" target="_blank" style="display: block; border: 2px solid #E5E7EB; border-radius: 12px; overflow: hidden; transition: all 0.3s ease; height: calc(100% - 28px);" onmouseover="this.style.borderColor='#6366F1'" onmouseout="this.style.borderColor='#E5E7EB'">
                            <img src="/storage/${payment.payment_proof_path}" alt="Payment Proof" style="width: 100%; height: 100%; object-fit: contain; background: #F9FAFB;" onerror="this.parentElement.innerHTML='<div style=\'padding: 40px 20px; text-align: center; color: #6366F1; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;\'><i class=\'fas fa-file-pdf\' style=\'font-size: 48px; margin-bottom: 8px;\'></i><span>Click to view document</span></div>'">
                        </a>
                    </div>
                    ` : ''}
                </div>
            `;

            document.getElementById('paymentDetailsContent').innerHTML = content;
            document.getElementById('paymentDetailsModal').style.display = 'flex';
        }

        function closePaymentDetailsModal() {
            document.getElementById('paymentDetailsModal').style.display = 'none';
            currentPaymentId = null;
        }

        function openReviewPaymentModal() {
            const payment = paymentSubmissionsData[currentPaymentId];
            if (!payment) return;

            document.getElementById('reviewPaymentId').value = currentPaymentId;
            document.getElementById('reviewPaymentInfo').innerHTML = `
                <strong>${payment.tracking_code}</strong><br>
                ${payment.company_name} - ₱${parseFloat(payment.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}
            `;
            document.getElementById('reviewPaymentAction').value = '';
            document.getElementById('reviewPaymentNotes').value = '';

            closePaymentDetailsModal();
            document.getElementById('reviewPaymentModal').style.display = 'flex';
        }

        function closeReviewPaymentModal() {
            document.getElementById('reviewPaymentModal').style.display = 'none';
        }

        function submitPaymentReview() {
            const paymentId = document.getElementById('reviewPaymentId').value;
            const action = document.getElementById('reviewPaymentAction').value;
            const notes = document.getElementById('reviewPaymentNotes').value;

            if (!action) {
                alert('Please select a verification action');
                return;
            }

            // Disable button and show loading
            const submitBtn = document.querySelector('#reviewPaymentModal .btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

            fetch(`/admin/submissions/${paymentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: action,
                    admin_notes: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Payment ${data.submission.tracking_code} has been ${action === 'approved' ? 'verified' : action === 'rejected' ? 'rejected' : 'updated'}!`);
                    closeReviewPaymentModal();
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update payment submission');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the payment submission');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }

        // ========== ROOM ISSUE DETAILS MODAL FUNCTIONS ==========

        function viewRoomIssueDetails(issueId) {
            // Try both string and number keys
            const issue = roomIssuesData[issueId] || roomIssuesData[String(issueId)] || roomIssuesData[Number(issueId)];
            if (!issue) {
                alert('Issue not found');
                return;
            }

            currentIssueId = issueId;

            const statusColors = {
                'pending': { bg: '#FEE2E2', text: '#991B1B' },
                'in_progress': { bg: '#FEF3C7', text: '#92400E' },
                'resolved': { bg: '#DCFCE7', text: '#166534' },
                'closed': { bg: '#E5E7EB', text: '#374151' }
            };
            const color = statusColors[issue.status] || { bg: '#E5E7EB', text: '#374151' };

            const priorityColors = {
                'urgent': '#991B1B',
                'high': '#DC2626',
                'medium': '#F59E0B',
                'low': '#228B22'
            };

            const content = `
                <div style="display: grid; gap: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #E5E7EB;">
                        <div>
                            <div style="font-size: 12px; color: #6B7280;">Tracking Code</div>
                            <div style="font-size: 18px; font-weight: 700; color: #7B1D3A;">${issue.tracking_code}</div>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <span style="background: ${priorityColors[issue.priority] || '#6B7280'}; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                ${issue.priority.toUpperCase()}
                            </span>
                            <span style="background: ${color.bg}; color: ${color.text}; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                ${issue.status_label}
                            </span>
                        </div>
                    </div>

                    <div style="background: linear-gradient(135deg, #7B1D3A, #5a1428); color: white; padding: 16px; border-radius: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="font-size: 12px; opacity: 0.9;">Room Number</div>
                                <div style="font-size: 24px; font-weight: 700;">${issue.room_number}</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 12px; opacity: 0.9;">Issue Type</div>
                                <div style="font-size: 16px; font-weight: 600;">${issue.issue_type_label}</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Issue Description</div>
                        <div style="background: #F3F4F6; padding: 12px; border-radius: 8px; white-space: pre-wrap;">${issue.description}</div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Company Name</div>
                            <div style="font-weight: 600;">${issue.company_name}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Reported By</div>
                            <div style="font-weight: 600;">${issue.contact_person}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Email</div>
                            <div>${issue.email}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Phone</div>
                            <div>${issue.phone || 'N/A'}</div>
                        </div>
                    </div>

                    ${issue.photo_path ? `
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">Photo Evidence</div>
                        <a href="/storage/${issue.photo_path}" target="_blank">
                            <img src="/storage/${issue.photo_path}" alt="Issue Photo" style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 1px solid #E5E7EB;">
                        </a>
                    </div>
                    ` : ''}

                    ${issue.admin_notes ? `
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Resolution Notes</div>
                        <div style="background: #FEF3C7; padding: 12px; border-radius: 8px;">${issue.admin_notes}</div>
                    </div>
                    ` : ''}

                    <div style="display: flex; gap: 16px; font-size: 12px; color: #6B7280;">
                        <div><i class="fas fa-calendar"></i> Reported: ${issue.created_at}</div>
                        ${issue.resolved_at ? `<div><i class="fas fa-check-circle"></i> Resolved: ${issue.resolved_at}</div>` : ''}
                    </div>
                </div>
            `;

            document.getElementById('roomIssueDetailsContent').innerHTML = content;
            document.getElementById('roomIssueDetailsModal').style.display = 'flex';
        }

        function closeRoomIssueDetailsModal() {
            document.getElementById('roomIssueDetailsModal').style.display = 'none';
            currentIssueId = null;
        }

        function openUpdateIssueStatusModal() {
            // Try both string and number keys
            const issue = roomIssuesData[currentIssueId] || roomIssuesData[String(currentIssueId)] || roomIssuesData[Number(currentIssueId)];
            if (!issue) return;

            document.getElementById('updateIssueId').value = currentIssueId;
            document.getElementById('updateIssueInfo').innerHTML = `
                <strong>${issue.tracking_code}</strong><br>
                Room ${issue.room_number} - ${issue.issue_type_label}
            `;
            document.getElementById('updateIssueNewStatus').value = issue.status;
            document.getElementById('updateIssueAssignee').value = '';
            document.getElementById('updateIssueNotes').value = issue.admin_notes || '';

            closeRoomIssueDetailsModal();
            document.getElementById('updateIssueStatusModal').style.display = 'flex';
        }

        function closeUpdateIssueStatusModal() {
            document.getElementById('updateIssueStatusModal').style.display = 'none';
        }

        function updateIssueStatus(issueId) {
            currentIssueId = issueId;
            // Try both string and number keys since JSON might have integer keys
            const issue = roomIssuesData[issueId] || roomIssuesData[String(issueId)] || roomIssuesData[Number(issueId)];

            console.log('Updating issue:', issueId, typeof issueId);
            console.log('Issue data:', issue);
            console.log('All issues data keys:', Object.keys(roomIssuesData));

            if (!issue) {
                alert('Issue data not found. Please refresh the page and try again.');
                return;
            }

            document.getElementById('updateIssueId').value = issueId;
            document.getElementById('updateIssueInfo').innerHTML = `
                <strong>${issue.tracking_code}</strong><br>
                Room ${issue.room_number} - ${issue.issue_type_label}
            `;
            document.getElementById('updateIssueNewStatus').value = issue.status;
            document.getElementById('updateIssueAssignee').value = '';
            document.getElementById('updateIssueNotes').value = issue.admin_notes || '';

            document.getElementById('updateIssueStatusModal').style.display = 'flex';
        }

        function submitIssueStatusUpdate() {
            const issueId = document.getElementById('updateIssueId').value;
            const newStatus = document.getElementById('updateIssueNewStatus').value;
            const assignee = document.getElementById('updateIssueAssignee').value;
            const notes = document.getElementById('updateIssueNotes').value;

            if (!issueId) {
                alert('No issue selected. Please try again.');
                return;
            }

            if (!newStatus) {
                alert('Please select a new status');
                return;
            }

            // Disable button and show loading
            const submitBtn = document.querySelector('#updateIssueStatusModal .btn-modal.primary');
            if (!submitBtn) {
                console.error('Submit button not found');
                return;
            }
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

            fetch(`/admin/room-issues/${issueId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: newStatus,
                    admin_notes: notes,
                    assignee: assignee
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`Server error: ${response.status} - ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(`Issue ${data.issue.tracking_code} has been updated to: ${data.issue.status_label}!`);
                    closeUpdateIssueStatusModal();
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update issue');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the issue: ' + error.message);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }

        // ===== ISSUES MANAGEMENT FUNCTIONS =====

        function filterIssues() {
            const statusFilter = document.getElementById('issueStatusFilter').value;
            const typeFilter = document.getElementById('issueTypeFilter').value;
            const priorityFilter = document.getElementById('issuePriorityFilter').value;
            const rows = document.querySelectorAll('.issue-row');

            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const type = row.getAttribute('data-type');
                const priority = row.getAttribute('data-priority');

                const matchStatus = statusFilter === 'all' || status === statusFilter;
                const matchType = typeFilter === 'all' || type === typeFilter;
                const matchPriority = priorityFilter === 'all' || priority === priorityFilter;

                row.style.display = (matchStatus && matchType && matchPriority) ? '' : 'none';
            });
        }

        function searchIssues() {
            const searchTerm = document.getElementById('issueSearchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.issue-row');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }

        // ===== PROJECT PROGRESS FUNCTIONS =====

        function filterProgress() {
            const statusFilter = document.getElementById('progressStatusFilter').value;
            const typeFilter = document.getElementById('progressTypeFilter').value;
            const rows = document.querySelectorAll('.progress-row');

            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const type = row.getAttribute('data-type');

                const matchStatus = statusFilter === 'all' || status === statusFilter;
                const matchType = typeFilter === 'all' || type === typeFilter;

                row.style.display = (matchStatus && matchType) ? '' : 'none';
            });
        }

        function viewProgressDetails(progressId) {
            fetch(`/admin/progress/${progressId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const progress = data.progress;
                    const typeLabels = {
                        'development': 'Product Development',
                        'funding': 'Funding/Investment',
                        'partnership': 'Partnership',
                        'launch': 'Product Launch',
                        'achievement': 'Achievement/Award',
                        'other': 'Other Update'
                    };
                    const statusLabels = {
                        'submitted': 'Pending Review',
                        'reviewed': 'Reviewed',
                        'acknowledged': 'Acknowledged'
                    };

                    const content = `
                        <div style="display: grid; gap: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #E5E7EB;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #7B1D3A, #A62450); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px;">
                                        ${progress.startup.company_name.substring(0, 2).toUpperCase()}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #1F2937;">${progress.startup.company_name}</div>
                                        <div style="font-size: 12px; color: #6B7280;">${progress.startup.startup_code}</div>
                                    </div>
                                </div>
                                <span style="padding: 6px 14px; background: #EDE9FE; color: #5B21B6; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    ${typeLabels[progress.milestone_type] || 'Update'}
                                </span>
                            </div>

                            <div>
                                <div style="font-size: 20px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">${progress.title}</div>
                                <div style="background: #F9FAFB; padding: 16px; border-radius: 10px; line-height: 1.6; color: #374151;">${progress.description}</div>
                            </div>

                            ${progress.file_path ? `
                            <div>
                                <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">Attached File</div>
                                <a href="${progress.file_url}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: #DBEAFE; color: #1E40AF; border-radius: 8px; text-decoration: none; font-weight: 500;">
                                    <i class="fas fa-paperclip"></i>
                                    ${progress.original_filename}
                                </a>
                            </div>
                            ` : ''}

                            ${progress.admin_comment ? `
                            <div>
                                <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">Admin Response</div>
                                <div style="background: #F0F9FF; border-left: 3px solid #0284C7; padding: 12px 16px; border-radius: 0 8px 8px 0; color: #0C4A6E;">
                                    ${progress.admin_comment}
                                </div>
                            </div>
                            ` : ''}

                            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid #E5E7EB; font-size: 12px; color: #6B7280;">
                                <span><i class="fas fa-clock"></i> Submitted: ${progress.created_at}</span>
                                <span style="padding: 4px 10px; background: ${progress.status === 'acknowledged' ? '#D1FAE5' : (progress.status === 'reviewed' ? '#DBEAFE' : '#FEF3C7')}; color: ${progress.status === 'acknowledged' ? '#065F46' : (progress.status === 'reviewed' ? '#1E40AF' : '#92400E')}; border-radius: 20px; font-weight: 500;">
                                    ${statusLabels[progress.status] || progress.status}
                                </span>
                            </div>
                        </div>
                    `;

                    document.getElementById('progressDetailContent').innerHTML = content;
                    document.getElementById('progressDetailModal').style.display = 'flex';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to load progress details');
            });
        }

        function respondToProgress(progressId) {
            document.getElementById('respondProgressId').value = progressId;
            document.getElementById('respondProgressStatus').value = 'reviewed';
            document.getElementById('respondProgressComment').value = '';
            document.getElementById('progressRespondModal').style.display = 'flex';
        }

        function submitProgressResponse(event) {
            event.preventDefault();

            const progressId = document.getElementById('respondProgressId').value;
            const status = document.getElementById('respondProgressStatus').value;
            const comment = document.getElementById('respondProgressComment').value;

            fetch(`/admin/progress/${progressId}/respond`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status, admin_comment: comment })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Response submitted successfully!');
                    closeModal('progressRespondModal');
                    location.reload();
                } else {
                    alert(data.message || 'Failed to submit response');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting the response');
            });
        }

        // ===== STARTUP MANAGEMENT FUNCTIONS =====

        let startupsData = [];
        let currentEditStartupId = null;

        function loadStartupsData() {
            fetch('/admin/startup-accounts', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Loaded startups:', data);
                    startupsData = Array.isArray(data) ? data : [];
                    renderStartupsTable();
                    updateStartupStats();
                })
                .catch(error => {
                    console.error('Error loading startups:', error);
                    document.getElementById('startupsTableBody').innerHTML = `
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: #EF4444;">
                                <i class="fas fa-exclamation-circle" style="font-size: 24px; margin-bottom: 12px; display: block;"></i>
                                Failed to load startups. Please try again.<br>
                                <small style="color: #9CA3AF;">${error.message}</small>
                            </td>
                        </tr>
                    `;
                });
        }

        function updateStartupStats() {
            const total = startupsData.length;
            const active = startupsData.filter(s => s.status === 'active').length;
            const inactive = startupsData.filter(s => s.status === 'inactive').length;
            const pendingMoa = startupsData.filter(s => s.moa_status === 'pending').length;

            document.getElementById('totalStartupsCount').textContent = total;
            document.getElementById('activeStartupsCount').textContent = active;
            document.getElementById('inactiveStartupsCount').textContent = inactive;
            document.getElementById('pendingMoaCount').textContent = pendingMoa;
        }

        function renderStartupsTable() {
            const tbody = document.getElementById('startupsTableBody');

            if (startupsData.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 60px 40px;">
                            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #F3E8FF, #DDD6FE); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <i class="fas fa-building" style="font-size: 28px; color: #7B1D3A;"></i>
                            </div>
                            <div style="font-size: 16px; font-weight: 600; color: #374151; margin-bottom: 4px;">No Startup Accounts Yet</div>
                            <div style="font-size: 13px; color: #9CA3AF;">Click "Create Startup Account" to add your first startup.</div>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = startupsData.map(startup => `
                <tr class="startup-row" data-status="${startup.status}" data-moa="${startup.moa_status}" style="border-bottom: 1px solid #F3F4F6;">
                    <td style="padding: 16px; white-space: nowrap;">
                        <span style="font-family: 'Courier New', monospace; background: linear-gradient(135deg, #F9FAFB, #F3F4F6); padding: 6px 10px; border-radius: 6px; font-weight: 600; font-size: 12px; color: #374151; border: 1px solid #E5E7EB; white-space: nowrap; display: inline-block;">${startup.startup_code}</span>
                    </td>
                    <td style="padding: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #7B1D3A, #A62450); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span style="color: white; font-weight: 700; font-size: 14px;">${startup.company_name.charAt(0).toUpperCase()}</span>
                            </div>
                            <div style="min-width: 0;">
                                <div style="font-weight: 600; color: #1F2937; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${startup.company_name}</div>
                                ${startup.email ? `<div style="font-size: 12px; color: #6B7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><i class="fas fa-envelope" style="margin-right: 4px; font-size: 10px;"></i>${startup.email}</div>` : ''}
                            </div>
                        </div>
                    </td>
                    <td style="padding: 16px;">
                        <div style="font-size: 13px; color: #374151;">${startup.contact_person || '<span style="color: #9CA3AF;">—</span>'}</div>
                        ${startup.phone ? `<div style="font-size: 11px; color: #9CA3AF; margin-top: 2px;"><i class="fas fa-phone" style="margin-right: 4px;"></i>${startup.phone}</div>` : ''}
                    </td>
                    <td style="padding: 16px; text-align: center;">
                        <span style="padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block;
                            ${startup.moa_status === 'active' ? 'background: #D1FAE5; color: #065F46;' : ''}
                            ${startup.moa_status === 'pending' ? 'background: #FEF3C7; color: #92400E;' : ''}
                            ${startup.moa_status === 'expired' ? 'background: #FEE2E2; color: #991B1B;' : ''}
                            ${startup.moa_status === 'none' ? 'background: #F3F4F6; color: #6B7280;' : ''}
                        ">${startup.moa_status.charAt(0).toUpperCase() + startup.moa_status.slice(1)}</span>
                    </td>
                    <td style="padding: 16px; text-align: center;">
                        <span style="padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;
                            ${startup.status === 'active' ? 'background: #D1FAE5; color: #065F46;' : 'background: #FEE2E2; color: #991B1B;'}
                        "><span style="width: 6px; height: 6px; border-radius: 50%; background: ${startup.status === 'active' ? '#059669' : '#DC2626'};"></span>${startup.status.charAt(0).toUpperCase() + startup.status.slice(1)}</span>
                    </td>
                    <td style="padding: 16px; text-align: center;">
                        <div style="display: inline-flex; align-items: center; gap: 6px;">
                            <button onclick="viewStartupDetails(${startup.id})" style="width: 32px; height: 32px; background: #EFF6FF; color: #2563EB; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;" title="View Details" onmouseover="this.style.background='#DBEAFE'" onmouseout="this.style.background='#EFF6FF'">
                                <i class="fas fa-eye" style="font-size: 13px;"></i>
                            </button>
                            <button onclick="editStartup(${startup.id})" style="width: 32px; height: 32px; background: #FEF3C7; color: #D97706; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;" title="Edit" onmouseover="this.style.background='#FDE68A'" onmouseout="this.style.background='#FEF3C7'">
                                <i class="fas fa-edit" style="font-size: 13px;"></i>
                            </button>
                            <button onclick="resetStartupPassword(${startup.id})" style="width: 32px; height: 32px; background: #E0E7FF; color: #4F46E5; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;" title="Reset Password" onmouseover="this.style.background='#C7D2FE'" onmouseout="this.style.background='#E0E7FF'">
                                <i class="fas fa-key" style="font-size: 13px;"></i>
                            </button>
                            <button onclick="toggleStartupStatus(${startup.id}, '${startup.status}')" style="width: 32px; height: 32px; background: ${startup.status === 'active' ? '#FEE2E2' : '#D1FAE5'}; color: ${startup.status === 'active' ? '#991B1B' : '#065F46'}; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;" title="${startup.status === 'active' ? 'Deactivate' : 'Activate'}">
                                <i class="fas fa-${startup.status === 'active' ? 'ban' : 'check'}" style="font-size: 13px;"></i>
                            </button>
                            <button onclick="deleteStartup(${startup.id}, '${startup.company_name.replace(/'/g, "\\'")}')" style="width: 32px; height: 32px; background: #FEE2E2; color: #991B1B; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;" title="Delete" onmouseover="this.style.background='#FECACA'" onmouseout="this.style.background='#FEE2E2'">
                                <i class="fas fa-trash" style="font-size: 13px;"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function filterStartups() {
            const statusFilter = document.getElementById('startupStatusFilter').value;
            const moaFilter = document.getElementById('startupMoaFilter').value;
            const rows = document.querySelectorAll('.startup-row');

            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const moa = row.getAttribute('data-moa');

                const matchStatus = statusFilter === 'all' || status === statusFilter;
                const matchMoa = moaFilter === 'all' || moa === moaFilter;

                row.style.display = (matchStatus && matchMoa) ? '' : 'none';
            });
        }

        function searchStartups() {
            const searchTerm = document.getElementById('startupSearchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.startup-row');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }

        function openCreateStartupModal() {
            currentEditStartupId = null;
            document.getElementById('startupModalTitle').textContent = 'Create Startup Account';
            document.getElementById('startupSubmitBtn').innerHTML = '<i class="fas fa-plus-circle"></i> Create Account';
            document.getElementById('startupForm').reset();
            document.getElementById('startupId').value = '';
            document.getElementById('credentialsSection').style.display = 'none';
            document.getElementById('startupModal').style.display = 'flex';
        }

        function editStartup(id) {
            const startup = startupsData.find(s => s.id === id);
            if (!startup) return;

            currentEditStartupId = id;
            document.getElementById('startupModalTitle').textContent = 'Edit Startup Account';
            document.getElementById('startupSubmitBtn').innerHTML = '<i class="fas fa-save"></i> Update Account';
            document.getElementById('startupId').value = id;
            document.getElementById('companyName').value = startup.company_name;
            document.getElementById('contactPerson').value = startup.contact_person || '';
            document.getElementById('startupEmail').value = startup.email || '';
            document.getElementById('startupPhone').value = startup.phone || '';
            document.getElementById('credentialsSection').style.display = 'none';

            document.getElementById('startupModal').style.display = 'flex';
        }

        function closeStartupModal() {
            document.getElementById('startupModal').style.display = 'none';
            currentEditStartupId = null;
        }

        function saveStartup(event) {
            event.preventDefault();

            const formData = {
                company_name: document.getElementById('companyName').value,
                contact_person: document.getElementById('contactPerson').value,
                email: document.getElementById('startupEmail').value,
                phone: document.getElementById('startupPhone').value
            };

            const submitBtn = document.getElementById('startupSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            const url = currentEditStartupId ? `/admin/startup-accounts/${currentEditStartupId}` : '/admin/startup-accounts';
            const method = currentEditStartupId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }

                if (!currentEditStartupId && data.startup_code) {
                    // Show startup code for new account (no password - startup creates their own)
                    document.getElementById('generatedCode').textContent = data.startup_code;
                    document.getElementById('credentialsSection').style.display = 'block';
                    submitBtn.style.display = 'none';

                    // Refresh the table
                    loadStartupsData();
                } else {
                    closeStartupModal();
                    loadStartupsData();
                }

                submitBtn.disabled = false;
                submitBtn.innerHTML = currentEditStartupId ? '<i class="fas fa-save"></i> Update Account' : '<i class="fas fa-plus-circle"></i> Create Account';
            })
            .catch(error => {
                alert('Error: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = currentEditStartupId ? '<i class="fas fa-save"></i> Update Account' : '<i class="fas fa-plus-circle"></i> Create Account';
            });
        }

        function copyStartupCode() {
            const code = document.getElementById('generatedCode').textContent;

            navigator.clipboard.writeText(code).then(() => {
                const btn = event.target.closest('button');
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-copy"></i> Copy Code to Clipboard';
                }, 2000);
            });
        }

        function viewStartupDetails(id) {
            const startup = startupsData.find(s => s.id === id);
            if (!startup) return;

            const content = `
                <div style="display: grid; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 16px; padding-bottom: 16px; border-bottom: 1px solid #E5E7EB;">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #7B1D3A, #A62450); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building" style="font-size: 24px; color: white;"></i>
                        </div>
                        <div>
                            <h3 style="font-size: 20px; font-weight: 700; color: #1F2937; margin-bottom: 4px;">${startup.company_name}</h3>
                            <span style="font-family: monospace; background: #F3F4F6; padding: 4px 8px; border-radius: 4px; font-size: 14px;">${startup.startup_code}</span>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div style="background: #F9FAFB; padding: 16px; border-radius: 8px;">
                            <label style="font-size: 12px; color: #6B7280; text-transform: uppercase;">Contact Person</label>
                            <div style="font-weight: 600; color: #1F2937; margin-top: 4px;">${startup.contact_person || '-'}</div>
                        </div>
                        <div style="background: #F9FAFB; padding: 16px; border-radius: 8px;">
                            <label style="font-size: 12px; color: #6B7280; text-transform: uppercase;">Email</label>
                            <div style="font-weight: 600; color: #1F2937; margin-top: 4px;">${startup.email || '-'}</div>
                        </div>
                        <div style="background: #F9FAFB; padding: 16px; border-radius: 8px;">
                            <label style="font-size: 12px; color: #6B7280; text-transform: uppercase;">Phone</label>
                            <div style="font-weight: 600; color: #1F2937; margin-top: 4px;">${startup.phone || '-'}</div>
                        </div>
                        <div style="background: #F9FAFB; padding: 16px; border-radius: 8px;">
                            <label style="font-size: 12px; color: #6B7280; text-transform: uppercase;">MOA Status</label>
                            <div style="margin-top: 4px;">
                                <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
                                    ${startup.moa_status === 'active' ? 'background: #D1FAE5; color: #065F46;' : ''}
                                    ${startup.moa_status === 'pending' ? 'background: #FEF3C7; color: #92400E;' : ''}
                                    ${startup.moa_status === 'expired' ? 'background: #FEE2E2; color: #991B1B;' : ''}
                                    ${startup.moa_status === 'none' ? 'background: #F3F4F6; color: #6B7280;' : ''}
                                ">${startup.moa_status.charAt(0).toUpperCase() + startup.moa_status.slice(1)}</span>
                            </div>
                        </div>
                        <div style="background: #F9FAFB; padding: 16px; border-radius: 8px;">
                            <label style="font-size: 12px; color: #6B7280; text-transform: uppercase;">Account Status</label>
                            <div style="margin-top: 4px;">
                                <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
                                    ${startup.status === 'active' ? 'background: #D1FAE5; color: #065F46;' : 'background: #FEE2E2; color: #991B1B;'}
                                ">${startup.status.charAt(0).toUpperCase() + startup.status.slice(1)}</span>
                            </div>
                        </div>
                    </div>

                    ${startup.moa_expiry ? `
                    <div style="background: #FEF3C7; padding: 12px 16px; border-radius: 8px; border: 1px solid #FCD34D;">
                        <i class="fas fa-calendar-alt" style="color: #D97706;"></i>
                        <span style="color: #92400E; font-weight: 500;"> MOA Expires: ${new Date(startup.moa_expiry).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
                    </div>
                    ` : ''}

                    <div style="background: #F3F4F6; padding: 12px 16px; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; color: #6B7280; font-size: 13px;">
                            <span>Created: ${new Date(startup.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</span>
                            <span>Last Updated: ${new Date(startup.updated_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</span>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('startupDetailsContent').innerHTML = content;
            document.getElementById('viewStartupModal').style.display = 'flex';
        }

        function closeViewStartupModal() {
            document.getElementById('viewStartupModal').style.display = 'none';
        }

        function resetStartupPassword(id) {
            const startup = startupsData.find(s => s.id === id);
            if (!startup) return;

            document.getElementById('changePasswordStartupId').value = id;
            document.getElementById('changePasswordStartupEmail').value = startup.email || '';
            document.getElementById('changePasswordStartupName').textContent = startup.company_name;
            document.getElementById('passwordResetError').style.display = 'none';
            document.getElementById('passwordResetSuccess').style.display = 'none';
            document.getElementById('confirmResetBtn').style.display = 'inline-flex';
            document.getElementById('resetPasswordFooter').querySelector('button:first-child').textContent = 'Cancel';
            document.getElementById('changeStartupPasswordModal').style.display = 'flex';
        }

        function closeChangePasswordModal() {
            document.getElementById('changeStartupPasswordModal').style.display = 'none';
        }

        function copyTempPassword() {
            const password = document.getElementById('tempPasswordDisplay').textContent;
            navigator.clipboard.writeText(password).then(() => {
                showToast('success', 'Copied!', 'Temporary password copied to clipboard.');
            });
        }

        function confirmPasswordReset() {
            const id = document.getElementById('changePasswordStartupId').value;
            const errorDiv = document.getElementById('passwordResetError');
            const successDiv = document.getElementById('passwordResetSuccess');
            const confirmBtn = document.getElementById('confirmResetBtn');

            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Resetting...';
            confirmBtn.disabled = true;

            fetch(`/admin/startup-accounts/${id}/reset-password`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }

                // Show success with temporary password
                document.getElementById('tempPasswordDisplay').textContent = data.temp_password;
                successDiv.style.display = 'block';
                errorDiv.style.display = 'none';
                confirmBtn.style.display = 'none';
                document.getElementById('resetPasswordFooter').querySelector('button:first-child').textContent = 'Close';
            })
            .catch(error => {
                errorDiv.querySelector('span').textContent = error.message;
                errorDiv.style.display = 'block';
                confirmBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Reset Password';
                confirmBtn.disabled = false;
            });
        }

        function toggleStartupStatus(id, currentStatus) {
            const startup = startupsData.find(s => s.id === id);
            if (!startup) return;

            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            const action = newStatus === 'active' ? 'activate' : 'deactivate';

            if (!confirm(`Are you sure you want to ${action} "${startup.company_name}"?`)) {
                return;
            }

            fetch(`/admin/startup-accounts/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                loadStartupsData();
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        }

        function deleteStartup(id, name) {
            if (!confirm(`Are you sure you want to delete "${name}"?\n\nThis action cannot be undone.`)) {
                return;
            }

            fetch(`/admin/startup-accounts/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                loadStartupsData();
            })
            .catch(error => {
                alert('Error deleting startup: ' + error.message);
            });
        }

        function exportStartups() {
            // Simple CSV export
            const headers = ['Startup Code', 'Company Name', 'Contact Person', 'Email', 'Phone', 'MOA Status', 'Status'];
            const rows = startupsData.map(s => [
                s.startup_code,
                s.company_name,
                s.contact_person || '',
                s.email || '',
                s.phone || '',
                s.moa_status,
                s.status
            ]);

            let csvContent = headers.join(',') + '\n';
            rows.forEach(row => {
                csvContent += row.map(cell => `"${cell}"`).join(',') + '\n';
            });

            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'startups_export.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // Show/hide MOA expiry field based on status
        document.getElementById('moaStatus')?.addEventListener('change', function() {
            const expiryGroup = document.getElementById('moaExpiryGroup');
            expiryGroup.style.display = this.value === 'active' ? 'block' : 'none';
        });

        // ===== DOCUMENT TRACKING FUNCTIONS =====

        function filterDocuments() {
            const statusFilter = document.getElementById('documentStatusFilter').value;
            const typeFilter = document.getElementById('documentTypeFilter').value;
            const rows = document.querySelectorAll('.document-row');

            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const type = row.getAttribute('data-type');

                const matchStatus = statusFilter === 'all' || status === statusFilter;
                const matchType = typeFilter === 'all' || type === typeFilter;

                row.style.display = (matchStatus && matchType) ? '' : 'none';
            });
        }

        function searchDocuments() {
            const searchTerm = document.getElementById('documentSearchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.document-row');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }

        // Review submission - routes to appropriate modal based on type
        function reviewSubmission(submissionId, type) {
            if (type === 'moa') {
                currentMoaId = submissionId;
                openReviewMoaModal();
            } else if (type === 'finance') {
                currentPaymentId = submissionId;
                openReviewPaymentModal();
            } else if (type === 'document') {
                currentDocId = submissionId;
                openReviewDocumentModal(submissionId);
            }
        }

        // ============================================
        // Booking & Scheduler Functions
        // ============================================

        // Booking data from server
        @php
            $approvedBookingsData = isset($allBookings) ? $allBookings->where('status', 'approved')->map(function($b) {
                return [
                    'id' => $b->id,
                    'date' => $b->booking_date->format('Y-m-d'),
                    'formatted_date' => $b->booking_date->format('M d, Y'),
                    'agency' => $b->agency_name,
                    'event' => $b->event_name,
                    'time' => $b->formatted_time,
                    'contact' => $b->contact_person,
                    'email' => $b->email,
                    'phone' => $b->phone,
                    'purpose' => $b->purpose ?? 'N/A',
                    'attachment' => $b->attachment_path ? asset('storage/' . $b->attachment_path) : '',
                    'status' => $b->status,
                    'admin_emailed' => $b->admin_emailed ? true : false
                ];
            })->values()->toArray() : [];

            $blockedDatesData = isset($blockedDates) ? $blockedDates->map(function($b) {
                return [
                    'id' => $b->id,
                    'date' => $b->blocked_date->format('Y-m-d'),
                    'reason' => $b->reason,
                    'reason_label' => $b->reason_label,
                    'reason_color' => $b->reason_color,
                    'description' => $b->description
                ];
            })->values()->toArray() : [];
        @endphp
        let schedulerBookings = @json($approvedBookingsData);
        let blockedDates = @json($blockedDatesData);
        let schedulerEvents = [];

        let schedulerCurrentMonth = new Date().getMonth();
        let schedulerCurrentYear = new Date().getFullYear();
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        // ========== TOAST NOTIFICATION SYSTEM ==========
        function showToast(type, title, message, duration = 5000) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;

            const icons = {
                success: 'fa-check-circle',
                error: 'fa-times-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };

            toast.innerHTML = `
                <i class="fas ${icons[type]} toast-icon"></i>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
                <div class="toast-progress"></div>
            `;

            container.appendChild(toast);

            // Auto remove after duration
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, duration);
        }

        // ========== CONFIRMATION MODAL SYSTEM ==========
        let confirmCallback = null;

        function showConfirmModal(options) {
            const { type = 'warning', title, message, confirmText = 'Confirm', onConfirm } = options;

            const modal = document.getElementById('confirmModal');
            const iconEl = document.getElementById('confirmModalIcon');
            const titleEl = document.getElementById('confirmModalTitle');
            const messageEl = document.getElementById('confirmModalMessage');
            const confirmBtn = document.getElementById('confirmModalBtn');

            // Set icon based on type
            const icons = {
                warning: { icon: 'fa-exclamation-triangle', class: 'warning' },
                danger: { icon: 'fa-trash-alt', class: 'danger' },
                info: { icon: 'fa-question-circle', class: 'info' }
            };

            const iconConfig = icons[type] || icons.warning;
            iconEl.className = `confirm-modal-icon ${iconConfig.class}`;
            iconEl.innerHTML = `<i class="fas ${iconConfig.icon}"></i>`;

            titleEl.textContent = title;
            messageEl.textContent = message;
            confirmBtn.innerHTML = `<i class="fas fa-check"></i> ${confirmText}`;

            // Set button style based on type
            confirmBtn.className = 'confirm-modal-btn confirm';
            if (type === 'info') {
                confirmBtn.classList.add('success');
            }

            confirmCallback = onConfirm;
            modal.classList.add('active');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.remove('active');
            confirmCallback = null;
        }

        function executeConfirmAction() {
            if (confirmCallback) {
                confirmCallback();
            }
            closeConfirmModal();
        }

        // Switch between booking tabs
        function switchBookingTab(tabName) {
            // Remove active from all tabs
            document.querySelectorAll('#scheduler .filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Find and activate the correct tab
            const tabs = document.querySelectorAll('#scheduler .filter-tab');
            tabs.forEach(tab => {
                const tabText = tab.textContent.toLowerCase();
                if ((tabName === 'pending' && tabText.includes('pending')) ||
                    (tabName === 'calendar' && tabText.includes('calendar')) ||
                    (tabName === 'events' && tabText.includes('event')) ||
                    (tabName === 'all' && tabText.includes('all'))) {
                    tab.classList.add('active');
                }
            });

            // Hide all tab contents
            document.querySelectorAll('.booking-tab-content').forEach(content => {
                content.style.display = 'none';
            });

            // Show selected tab
            if (tabName === 'pending') {
                document.getElementById('pendingBookingsTab').style.display = 'block';
            } else if (tabName === 'calendar') {
                document.getElementById('calendarViewTab').style.display = 'block';
                loadSchedulerEvents();
            } else if (tabName === 'events') {
                document.getElementById('eventsTab').style.display = 'block';
                loadAdminEvents();
            } else if (tabName === 'all') {
                document.getElementById('allBookingsTab').style.display = 'block';
                // Initialize pagination for all bookings
                if (typeof initAllBookingsPagination === 'function') {
                    initAllBookingsPagination();
                }
            }
        }

        // Booking Action Modal Functions
        function openBookingActionModal(id, agencyName, date, time, purpose, contactPerson, email, phone, notes, attachmentUrl, status, adminEmailed) {
            document.getElementById('currentBookingId').value = id;
            document.getElementById('modalAgencyName').textContent = agencyName;
            document.getElementById('modalBookingDate').textContent = date;
            document.getElementById('modalBookingTime').textContent = time;
            document.getElementById('modalPurpose').textContent = purpose;
            document.getElementById('modalContactPerson').textContent = contactPerson;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalPhone').textContent = phone;
            document.getElementById('modalNotes').textContent = notes || 'No notes provided';

            // Handle attachment
            const attachmentSection = document.getElementById('modalAttachmentSection');
            const attachmentLink = document.getElementById('modalAttachmentLink');
            if (attachmentUrl) {
                attachmentSection.style.display = 'block';
                attachmentLink.href = attachmentUrl;
            } else {
                attachmentSection.style.display = 'none';
            }

            // Handle status badge and buttons
            const statusBadge = document.getElementById('modalStatusBadge');
            const approveBtn = document.getElementById('btnApproveBooking');
            const rejectBtn = document.getElementById('btnRejectBooking');
            const emailNotificationSection = document.getElementById('emailNotificationSection');
            const emailSentSection = document.getElementById('emailSentSection');

            // Reset sections
            emailNotificationSection.style.display = 'none';
            emailSentSection.style.display = 'none';

            if (status === 'pending') {
                statusBadge.style.background = '#FEF3C7';
                statusBadge.style.color = '#D97706';
                statusBadge.textContent = 'Pending';
                approveBtn.style.display = 'inline-flex';
                rejectBtn.style.display = 'inline-flex';
            } else if (status === 'approved') {
                statusBadge.style.background = '#D1FAE5';
                statusBadge.style.color = '#059669';
                statusBadge.textContent = 'Approved';
                approveBtn.style.display = 'none';
                rejectBtn.style.display = 'none';

                // Show email notification section based on admin_emailed status
                if (adminEmailed) {
                    emailSentSection.style.display = 'block';
                } else {
                    emailNotificationSection.style.display = 'block';
                    // Generate email preview
                    generateEmailPreview(agencyName, date, time, purpose, email);
                }
            } else {
                statusBadge.style.background = '#FEE2E2';
                statusBadge.style.color = '#DC2626';
                statusBadge.textContent = 'Rejected';
                approveBtn.style.display = 'none';
                rejectBtn.style.display = 'none';
            }

            document.getElementById('bookingActionModal').classList.add('active');
        }

        function generateEmailPreview(agencyName, date, time, purpose, email) {
            const subject = `Booking Approved - ${purpose} on ${date}`;
            const body = `Dear ${agencyName},

We are pleased to inform you that your booking request has been APPROVED.

📅 BOOKING DETAILS:
━━━━━━━━━━━━━━━━━━━━
Date: ${date}
Time: ${time}
Purpose: ${purpose}

Please arrive at least 15 minutes before your scheduled time. If you need to reschedule or cancel, please contact us as soon as possible.

We look forward to seeing you!

Best regards,
UP Cebu Innovation & Technology Hub
University of the Philippines Cebu
📧 info@upcebu.edu.ph
📞 +63 32 123 4567`;

            document.getElementById('emailPreviewTo').textContent = email;
            document.getElementById('emailPreviewSubject').textContent = subject;
            document.getElementById('emailPreviewBody').textContent = body;

            // Store for copy/mail functions
            document.getElementById('currentBookingEmail').value = email;
            document.getElementById('currentBookingAgency').value = agencyName;
            document.getElementById('currentBookingDate').value = date;
            document.getElementById('currentBookingTime').value = time;
            document.getElementById('currentBookingPurpose').value = purpose;
        }

        function copyEmailContent() {
            const email = document.getElementById('currentBookingEmail').value;
            const subject = document.getElementById('emailPreviewSubject').textContent;
            const body = document.getElementById('emailPreviewBody').textContent;

            const fullContent = `To: ${email}\nSubject: ${subject}\n\n${body}`;

            navigator.clipboard.writeText(fullContent).then(() => {
                showToast('success', 'Copied!', 'Email content copied to clipboard. Paste it into your email application.');
            }).catch(err => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = fullContent;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showToast('success', 'Copied!', 'Email content copied to clipboard.');
            });
        }

        function openMailClient() {
            const email = document.getElementById('currentBookingEmail').value;
            const subject = encodeURIComponent(document.getElementById('emailPreviewSubject').textContent);
            const body = encodeURIComponent(document.getElementById('emailPreviewBody').textContent);

            window.open(`mailto:${email}?subject=${subject}&body=${body}`, '_blank');
            showToast('info', 'Email App Opened', 'Your default email application should now open with the pre-filled content.');
        }

        function markAsEmailed() {
            const bookingId = document.getElementById('currentBookingId').value;

            fetch(`/admin/bookings/${bookingId}/send-email`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Marked as Emailed', 'Email notification status has been updated.');
                    // Update UI
                    document.getElementById('emailNotificationSection').style.display = 'none';
                    document.getElementById('emailSentSection').style.display = 'block';
                } else {
                    showToast('error', 'Error', data.message || 'Failed to update status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Error', 'An error occurred while updating the status.');
            });
        }

        function closeBookingActionModal() {
            document.getElementById('bookingActionModal').classList.remove('active');
        }

        function confirmApproveBooking() {
            const bookingId = document.getElementById('currentBookingId').value;
            approveBooking(bookingId);
        }

        function confirmRejectBooking() {
            const bookingId = document.getElementById('currentBookingId').value;
            showConfirmModal({
                type: 'danger',
                title: 'Reject Booking?',
                message: 'This booking request will be rejected. The booker will not be able to use this time slot. This action cannot be undone.',
                confirmText: 'Reject Booking',
                onConfirm: () => rejectBooking(bookingId)
            });
        }

        // Approve booking
        function approveBooking(bookingId) {
            fetch(`/admin/bookings/${bookingId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeBookingActionModal();
                    showToast('success', 'Booking Approved!', 'You can now send an email notification to the booker.', 3000);
                    // Remove from pending table
                    const row = document.getElementById(`booking-row-${bookingId}`);
                    if (row) row.remove();
                    // Update pending count
                    updatePendingCount(-1);
                    // Reload to update all views
                    setTimeout(() => window.location.reload(), 3000);
                } else {
                    showToast('error', 'Error', data.message || 'Failed to approve booking.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Error', 'An error occurred while approving the booking.');
            });
        }

        // Reject booking
        function rejectBooking(bookingId) {
            fetch(`/admin/bookings/${bookingId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeBookingActionModal();
                    showToast('warning', 'Booking Rejected', 'The booking request has been rejected.');
                    const row = document.getElementById(`booking-row-${bookingId}`);
                    if (row) row.remove();
                    updatePendingCount(-1);
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('error', 'Error', data.message || 'Failed to reject booking.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Error', 'An error occurred while rejecting the booking.');
            });
        }

        // Delete booking
        function deleteBooking(bookingId) {
            showConfirmModal({
                type: 'danger',
                title: 'Delete Booking?',
                message: 'This will permanently delete the booking record. This action cannot be undone.',
                confirmText: 'Delete',
                onConfirm: () => executeDeleteBooking(bookingId)
            });
        }

        function executeDeleteBooking(bookingId) {
            fetch(`/admin/bookings/${bookingId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Deleted', 'Booking has been permanently deleted.');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('error', 'Error', data.message || 'Failed to delete booking.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Error', 'An error occurred while deleting the booking.');
            });
        }

        // View booking details
        function viewBookingDetails(bookingId) {
            // Find booking in schedulerBookings data
            const booking = schedulerBookings.find(b => b.id === bookingId);

            if (booking) {
                openBookingActionModal(
                    booking.id,
                    booking.agency,
                    booking.formatted_date,
                    booking.time,
                    booking.event,
                    booking.contact,
                    booking.email,
                    booking.phone,
                    booking.purpose,
                    booking.attachment,
                    booking.status,
                    booking.admin_emailed
                );
            } else {
                showToast('error', 'Error', 'Booking not found');
            }
        }

        // Update pending count badge
        function updatePendingCount(change) {
            const countEl = document.getElementById('pendingBookingsCount');
            const badgeEl = document.getElementById('pendingBadge');
            if (countEl) {
                const newCount = Math.max(0, parseInt(countEl.textContent) + change);
                countEl.textContent = newCount;
                if (badgeEl) badgeEl.textContent = newCount;
            }
            // Check if pending table is empty
            const tbody = document.getElementById('pendingBookingsBody');
            if (tbody && tbody.querySelectorAll('tr:not(#noPendingRow)').length === 0) {
                tbody.innerHTML = `<tr id="noPendingRow">
                    <td colspan="7" style="text-align: center; padding: 40px; color: #9CA3AF;">
                        <i class="fas fa-check-circle" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                        No pending booking requests
                    </td>
                </tr>`;
            }
        }

        // Filter bookings in All Bookings tab
        function filterBookings() {
            const searchValue = document.getElementById('searchBookings').value.toLowerCase();
            const statusFilter = document.getElementById('filterBookingStatus').value;

            document.querySelectorAll('#allBookingsBody .booking-row').forEach(row => {
                const searchText = row.getAttribute('data-search');
                const status = row.getAttribute('data-status');

                const matchesSearch = !searchValue || searchText.includes(searchValue);
                const matchesStatus = !statusFilter || status === statusFilter;

                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }

        // Scheduler calendar navigation
        function schedulerPrevMonth() {
            schedulerCurrentMonth--;
            if (schedulerCurrentMonth < 0) {
                schedulerCurrentMonth = 11;
                schedulerCurrentYear--;
            }
            renderSchedulerCalendar();
        }

        function schedulerNextMonth() {
            schedulerCurrentMonth++;
            if (schedulerCurrentMonth > 11) {
                schedulerCurrentMonth = 0;
                schedulerCurrentYear++;
            }
            renderSchedulerCalendar();
        }

        // Load events for scheduler calendar
        async function loadSchedulerEvents() {
            try {
                const response = await fetch('/intern/events', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();
                schedulerEvents = data.events || [];
                renderSchedulerCalendar();
            } catch (error) {
                console.error('Error loading events:', error);
                renderSchedulerCalendar();
            }
        }

        // Render scheduler calendar
        function renderSchedulerCalendar() {
            const titleEl = document.getElementById('schedulerMonthTitle');
            const mainTitleEl = document.getElementById('schedulerMainTitle');
            const miniCalEl = document.getElementById('schedulerMiniCalendar');
            const mainCalEl = document.getElementById('schedulerCalendarGrid');

            const monthYear = `${monthNames[schedulerCurrentMonth]} ${schedulerCurrentYear}`;
            if (titleEl) titleEl.textContent = monthYear;
            if (mainTitleEl) mainTitleEl.textContent = monthYear;

            const firstDay = new Date(schedulerCurrentYear, schedulerCurrentMonth, 1).getDay();
            const daysInMonth = new Date(schedulerCurrentYear, schedulerCurrentMonth + 1, 0).getDate();
            const daysInPrevMonth = new Date(schedulerCurrentYear, schedulerCurrentMonth, 0).getDate();
            const today = new Date();
            const todayString = today.toISOString().split('T')[0];

            // Generate mini calendar
            let miniHtml = '';
            for (let i = firstDay - 1; i >= 0; i--) {
                miniHtml += `<div class="mini-day other-month">${daysInPrevMonth - i}</div>`;
            }
            for (let day = 1; day <= daysInMonth; day++) {
                const dateString = `${schedulerCurrentYear}-${String(schedulerCurrentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isToday = dateString === todayString;
                const hasBooking = schedulerBookings.some(b => b.date === dateString);
                const hasEvent = schedulerEvents.some(e => {
                    const eventStart = new Date(e.start_date).toISOString().split('T')[0];
                    const eventEnd = new Date(e.end_date).toISOString().split('T')[0];
                    return dateString >= eventStart && dateString <= eventEnd;
                });
                const blockedInfo = blockedDates.find(b => b.date === dateString);
                let classes = 'mini-day';
                if (isToday) classes += ' today';
                if (hasBooking || hasEvent) classes += ' has-events';
                if (blockedInfo) classes += ' blocked';

                let style = blockedInfo ? `background: ${blockedInfo.reason_color}20; color: ${blockedInfo.reason_color}; font-weight: 600;` : '';
                miniHtml += `<div class="${classes}" style="${style}" onclick="openBlockDateModal('${dateString}')">${day}</div>`;
            }
            const remainingMini = 42 - (firstDay + daysInMonth);
            for (let i = 1; i <= remainingMini; i++) {
                miniHtml += `<div class="mini-day other-month">${i}</div>`;
            }
            if (miniCalEl) miniCalEl.innerHTML = miniHtml;

            // Generate main calendar
            let mainHtml = '';
            for (let i = firstDay - 1; i >= 0; i--) {
                mainHtml += `<div class="calendar-day other-month"><div class="day-number">${daysInPrevMonth - i}</div></div>`;
            }
            for (let day = 1; day <= daysInMonth; day++) {
                const dateString = `${schedulerCurrentYear}-${String(schedulerCurrentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isToday = dateString === todayString;
                const dayBookings = schedulerBookings.filter(b => b.date === dateString);
                const blockedInfo = blockedDates.find(b => b.date === dateString);

                let classes = 'calendar-day clickable-day';
                if (isToday) classes += ' current-day';
                if (blockedInfo) classes += ' blocked-day';

                let eventsHtml = '';

                // Show blocked status
                if (blockedInfo) {
                    eventsHtml += `
                        <div class="calendar-event blocked" style="background: ${blockedInfo.reason_color}20; border-left: 3px solid ${blockedInfo.reason_color}; color: ${blockedInfo.reason_color};">
                            <div class="event-name"><i class="fas fa-ban" style="margin-right: 4px;"></i>${blockedInfo.reason_label}</div>
                            ${blockedInfo.description ? `<div class="event-time">${blockedInfo.description}</div>` : ''}
                        </div>
                    `;
                }

                // Show bookings
                dayBookings.forEach(booking => {
                    eventsHtml += `
                        <div class="calendar-event meeting" onclick="event.stopPropagation(); viewBookingDetails(${booking.id})" style="cursor: pointer; background: #DBEAFE; border-left-color: #3B82F6;">
                            <div class="event-time">${booking.time.split(' - ')[0]}</div>
                            <div class="event-name">${booking.agency}</div>
                        </div>
                    `;
                });

                // Show events
                const dayEvents = schedulerEvents.filter(e => {
                    const eventStart = new Date(e.start_date).toISOString().split('T')[0];
                    const eventEnd = new Date(e.end_date).toISOString().split('T')[0];
                    return dateString >= eventStart && dateString <= eventEnd;
                });

                dayEvents.forEach(event => {
                    const eventStart = new Date(event.start_date);
                    const eventStartDate = eventStart.toISOString().split('T')[0];
                    const isStartDay = dateString === eventStartDate;
                    const timeStr = (event.all_day || !isStartDay) ? '' : eventStart.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
                    const eventLabel = isStartDay ? escapeHtml(event.title) : `↔ ${escapeHtml(event.title)}`;
                    eventsHtml += `
                        <div class="calendar-event" onclick="event.stopPropagation(); editEvent(${event.id})" style="cursor: pointer; background: ${event.color}20; border-left-color: ${event.color};">
                            ${timeStr ? `<div class="event-time">${timeStr}</div>` : ''}
                            <div class="event-name">${eventLabel}</div>
                        </div>
                    `;
                });

                let dayStyle = blockedInfo ? `background: ${blockedInfo.reason_color}08;` : '';
                mainHtml += `<div class="${classes}" style="${dayStyle}" onclick="openBlockDateModal('${dateString}')"><div class="day-number">${day}</div>${eventsHtml}</div>`;
            }
            const remainingMain = 42 - (firstDay + daysInMonth);
            for (let i = 1; i <= remainingMain; i++) {
                mainHtml += `<div class="calendar-day other-month"><div class="day-number">${i}</div></div>`;
            }
            if (mainCalEl) mainCalEl.innerHTML = mainHtml;
        }

        // ===== BLOCKED DATE MODAL FUNCTIONS =====
        let currentBlockDateId = null;

        function openBlockDateModal(dateString) {
            const date = new Date(dateString + 'T00:00:00');
            const formattedDate = date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

            document.getElementById('blockDateDisplay').value = formattedDate;
            document.getElementById('blockDateValue').value = dateString;
            document.getElementById('blockDateDays').value = '1';
            document.getElementById('blockDateReason').value = '';
            document.getElementById('blockDateDescription').value = '';
            document.getElementById('blockDateWarning').style.display = 'none';
            document.getElementById('blockedDateInfo').style.display = 'none';
            document.getElementById('blockDateRangeDisplay').style.display = 'none';
            document.getElementById('blockDateSubmitBtn').style.display = 'inline-flex';
            document.getElementById('unblockDateBtn').style.display = 'none';
            currentBlockDateId = null;
            updateBlockDateRange();

            // Check if date is already blocked
            const blockedInfo = blockedDates.find(b => b.date === dateString);
            if (blockedInfo) {
                currentBlockDateId = blockedInfo.id;
                document.getElementById('blockedDateInfo').style.display = 'block';
                document.getElementById('blockedDateInfoText').innerHTML = `
                    <strong>${blockedInfo.reason_label}</strong><br>
                    ${blockedInfo.description || 'No description provided'}
                `;
                document.getElementById('blockDateSubmitBtn').style.display = 'none';
                document.getElementById('unblockDateBtn').style.display = 'inline-flex';
            }

            // Check for existing bookings on this date
            const dateBookings = schedulerBookings.filter(b => b.date === dateString);
            if (dateBookings.length > 0 && !blockedInfo) {
                document.getElementById('blockDateWarning').style.display = 'block';
                let bookingsList = dateBookings.map(b => `• ${b.agency} (${b.time})`).join('<br>');
                document.getElementById('blockDateWarningText').innerHTML = `
                    There are ${dateBookings.length} approved booking(s) on this date:<br>${bookingsList}<br>
                    <small>Blocking this date will not cancel existing bookings.</small>
                `;
            }

            document.getElementById('blockDateModal').classList.add('active');
        }

        function closeBlockDateModal() {
            document.getElementById('blockDateModal').classList.remove('active');
            currentBlockDateId = null;
        }

        function updateBlockDateRange() {
            const startDate = document.getElementById('blockDateValue').value;
            const days = parseInt(document.getElementById('blockDateDays').value) || 1;

            if (!startDate || days < 1) {
                document.getElementById('blockDateRangeDisplay').style.display = 'none';
                return;
            }

            const start = new Date(startDate + 'T00:00:00');
            const end = new Date(start);
            end.setDate(end.getDate() + days - 1);

            const startFormatted = start.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            const endFormatted = end.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

            const rangeDisplay = document.getElementById('blockDateRangeDisplay');
            const rangeText = document.getElementById('blockDateRangeText');

            if (days === 1) {
                rangeText.textContent = `Blocking: ${startFormatted}`;
            } else {
                rangeText.textContent = `Blocking ${days} days: ${startFormatted} - ${endFormatted}`;
            }

            rangeDisplay.style.display = 'block';
        }

        function submitBlockDate() {
            const dateValue = document.getElementById('blockDateValue').value;
            const days = parseInt(document.getElementById('blockDateDays').value) || 1;
            const reason = document.getElementById('blockDateReason').value;
            const description = document.getElementById('blockDateDescription').value;

            if (!reason) {
                alert('Please select a reason.');
                return;
            }

            if (days < 1 || days > 365) {
                alert('Number of days must be between 1 and 365.');
                return;
            }

            // Generate all dates to block
            const datesToBlock = [];
            const startDate = new Date(dateValue + 'T00:00:00');

            for (let i = 0; i < days; i++) {
                const currentDate = new Date(startDate);
                currentDate.setDate(currentDate.getDate() + i);
                const dateString = currentDate.toISOString().split('T')[0];
                datesToBlock.push(dateString);
            }

            // Block all dates
            const promises = datesToBlock.map(date =>
                fetch('/admin/blocked-dates', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        blocked_date: date,
                        reason: reason,
                        description: description
                    })
                }).then(response => response.json())
            );

            Promise.all(promises)
                .then(results => {
                    const successCount = results.filter(data => data.success).length;
                    const failCount = results.length - successCount;

                    if (successCount > 0) {
                        // Add all successfully blocked dates to local array
                        results.forEach(data => {
                            if (data.success && data.blockedDate) {
                                blockedDates.push(data.blockedDate);
                            }
                        });

                        if (failCount === 0) {
                            alert(`Successfully blocked ${successCount} day${successCount > 1 ? 's' : ''}!`);
                        } else {
                            alert(`Blocked ${successCount} day${successCount > 1 ? 's' : ''}, but ${failCount} day${failCount > 1 ? 's were' : ' was'} already blocked or failed.`);
                        }

                        closeBlockDateModal();
                        renderSchedulerCalendar();
                    } else {
                        alert('Failed to block dates. Some or all dates may already be blocked.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while blocking the dates.');
                });
        }

        function unblockDate() {
            if (!currentBlockDateId) return;

            if (!confirm('Are you sure you want to unblock this date?')) return;

            fetch(`/admin/blocked-dates/${currentBlockDateId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Date unblocked successfully!');
                    // Remove from local array
                    blockedDates = blockedDates.filter(b => b.id !== currentBlockDateId);
                    closeBlockDateModal();
                    renderSchedulerCalendar();
                } else {
                    alert(data.message || 'Failed to unblock date.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while unblocking the date.');
            });
        }

        // Initialize scheduler calendar when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Pre-render calendar if scheduler tab gets shown
            if (document.getElementById('scheduler').classList.contains('active')) {
                renderSchedulerCalendar();
            }

            // Load Digital Records when the page loads
            loadDigitalRecords();
            loadDigitalRecordsStats();
        });

        // Digital Records Functions
        let currentFolder = 'root';
        let currentPath = '';
        let viewMode = 'grid'; // 'grid' or 'list'
        let folderHistory = [];

        function loadDigitalRecordsStats() {
            const totalFoldersEl = document.getElementById('dr-total-folders');
            const totalFilesEl = document.getElementById('dr-total-files');
            const storageUsedEl = document.getElementById('dr-storage-used');
            const recentUploadsEl = document.getElementById('dr-recent-uploads');

            if (!totalFoldersEl || !totalFilesEl || !storageUsedEl || !recentUploadsEl) {
                return;
            }

            const formatBytes = (bytes) => {
                if (bytes === 0) return '0 B';
                const k = 1024;
                const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                const value = (bytes / Math.pow(k, i)).toFixed(2);
                return `${value} ${sizes[i]}`;
            };

            fetch('/admin/documents/stats', {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.ok ? response.json() : Promise.reject('Failed to load stats'))
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Unable to load stats');
                }

                totalFoldersEl.textContent = data.folders ?? '--';
                totalFilesEl.textContent = data.files ?? '--';
                storageUsedEl.textContent = data.storage_human || formatBytes(data.storage_bytes || 0);
                recentUploadsEl.textContent = data.recent_uploads ?? '--';
            })
            .catch(error => {
                console.error('Error loading digital records stats:', error);
                totalFoldersEl.textContent = '--';
                totalFilesEl.textContent = '--';
                storageUsedEl.textContent = '--';
                recentUploadsEl.textContent = '--';
            });
        }

        function loadDigitalRecords() {
            if (currentPath === '') {
                loadRootFolders();
            } else {
                loadFolderContents(currentPath);
            }
        }

        function loadRootFolders() {
            fetch('/admin/documents/all-folders', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Combine shared folders and intern folders
                    const sharedFolders = data.shared_folders.map(f => ({
                        id: f.id,
                        name: f.name,
                        path: f.storage_path || f.path || `Shared/${f.name}`,
                        item_count: f.item_count || 0,
                        is_folder: true,
                        folder_type: 'shared',
                        color: f.color,
                        allowed_users: f.allowed_users
                    }));

                    const allFolders = [...sharedFolders, ...data.intern_folders];
                    displayItems(allFolders, []);
                } else {
                    showToast('error', 'Error', data.message || 'Failed to load folders');
                }
            })
            .catch(error => {
                console.error('Error loading folders:', error);
                showToast('error', 'Error', 'An error occurred while loading folders');
            });
        }

        function loadFolderContents(path) {
            console.log('Loading folder contents for path:', path);
            fetch(`/admin/documents/contents?path=${encodeURIComponent(path)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Folder contents response:', data);
                if (data.success) {
                    console.log('Items received:', data.items);
                    console.log('Folders with IDs:', data.items.filter(i => i.is_folder).map(f => ({ name: f.name, id: f.id })));
                    console.log('Files:', data.items.filter(i => !i.is_folder));
                    displayItems(data.items.filter(i => i.is_folder), data.items.filter(i => !i.is_folder));
                } else {
                    showToast('error', 'Error', data.message || 'Failed to load folder contents');
                }
            })
            .catch(error => {
                console.error('Error loading folder contents:', error);
                showToast('error', 'Error', 'An error occurred while loading folder contents');
            });
        }

        function displayItems(folders, files) {
            const gridView = document.getElementById('grid-view');
            const listViewBody = document.getElementById('list-view-body');

            if (folders.length === 0 && files.length === 0) {
                gridView.innerHTML = `
                    <div style="grid-column: span 7; text-align: center; padding: 50px; color: #9CA3AF;">
                        <i class="fas fa-folder-open" style="font-size: 50px; margin-bottom: 16px;"></i>
                        <p style="font-size: 16px;">This folder is empty</p>
                    </div>
                `;
                listViewBody.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 50px; color: #9CA3AF;">
                            <i class="fas fa-folder-open" style="font-size: 30px; margin-bottom: 16px;"></i>
                            <p>This folder is empty</p>
                        </td>
                    </tr>
                `;
                return;
            }

            // Grid View
            let gridHtml = '';
            folders.forEach(folder => {
                const displayName = formatFolderName(folder.name);
                const folderColor = folder.color || '#FFBF00';
                const deleteButton = folder.id ? `
                    <button class="delete-btn" onclick="event.stopPropagation(); deleteFolder(${folder.id}, '${escapeHtml(folder.name)}')"
                            style="position: absolute; top: 8px; right: 8px; background: #EF4444; color: white; border: none;
                                   border-radius: 50%; width: 28px; height: 28px; cursor: pointer; display: flex; align-items: center;
                                   justify-content: center; opacity: 0.9; transition: opacity 0.2s; z-index: 10;"
                            onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                        <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                    </button>
                ` : '';

                gridHtml += `
                    <div class="file-item folder-item" style="position: relative;">
                        ${deleteButton}
                        <div onclick="openFolder('${escapeHtml(folder.path)}', '${escapeHtml(folder.name)}')">
                            <div class="file-icon folder-icon" style="color: ${folderColor};">
                                <i class="fas fa-folder"></i>
                            </div>
                            <div class="file-name" title="${escapeHtml(folder.name)}">${escapeHtml(displayName)}</div>
                            <div class="file-meta">${folder.item_count} item(s)</div>
                        </div>
                    </div>
                `;
            });

            files.forEach(file => {
                const fileIcon = getFileIcon(file.name);
                gridHtml += `
                    <div class="file-item file-doc" style="position: relative;">
                        <button class="delete-btn" onclick="event.stopPropagation(); deleteFile('${escapeHtml(file.path)}', '${escapeHtml(file.name)}')"
                                style="position: absolute; top: 8px; right: 8px; background: #EF4444; color: white; border: none;
                                       border-radius: 50%; width: 28px; height: 28px; cursor: pointer; display: flex; align-items: center;
                                       justify-content: center; opacity: 0.9; transition: opacity 0.2s;"
                                onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                            <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                        </button>
                        <div onclick="viewFileDetails('${escapeHtml(file.path)}', '${escapeHtml(file.name)}')">
                            <div class="file-icon" style="${fileIcon.style}">
                                <i class="${fileIcon.icon}"></i>
                            </div>
                            <div class="file-name">${escapeHtml(file.name)}</div>
                            <div class="file-meta">${file.size} • ${file.modified}</div>
                        </div>
                    </div>
                `;
            });
            gridView.innerHTML = gridHtml;

            // List View
            let listHtml = '';
            folders.forEach(folder => {
                const displayName = formatFolderName(folder.name);
                const folderColor = folder.color || '#FFBF00';
                const deleteButtonList = folder.id ? `
                    <button class="action-btn" onclick="event.stopPropagation(); deleteFolder(${folder.id}, '${escapeHtml(folder.name)}')"
                            style="color: #EF4444;">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                ` : '';

                listHtml += `
                    <tr onclick="openFolder('${escapeHtml(folder.path)}', '${escapeHtml(folder.name)}')" style="cursor: pointer;">
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="fas fa-folder" style="font-size: 24px; color: ${folderColor};"></i>
                                <div>
                                    <span style="font-weight: 600;">${escapeHtml(displayName)}</span>
                                    <div style="font-size: 11px; color: #6B7280;">${escapeHtml(folder.name)}</div>
                                </div>
                            </div>
                        </td>
                        <td>Folder</td>
                        <td>${folder.item_count} items</td>
                        <td>-</td>
                        <td>
                            <button class="action-btn" onclick="event.stopPropagation(); openFolder('${escapeHtml(folder.path)}', '${escapeHtml(folder.name)}')">
                                <i class="fas fa-folder-open"></i>
                            </button>
                            ${deleteButtonList}
                        </td>
                    </tr>
                `;
            });

            files.forEach(file => {
                const fileIcon = getFileIcon(file.name);
                listHtml += `
                    <tr style="cursor: pointer;">
                        <td onclick="viewFileDetails('${escapeHtml(file.path)}', '${escapeHtml(file.name)}')">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="${fileIcon.icon}" style="font-size: 20px; ${fileIcon.style}"></i>
                                <span>${escapeHtml(file.name)}</span>
                            </div>
                        </td>
                        <td>${getFileType(file.name)}</td>
                        <td>${file.size}</td>
                        <td>${file.modified}</td>
                        <td>
                            <button class="action-btn" onclick="downloadFile('${escapeHtml(file.path)}', '${escapeHtml(file.name)}')">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="action-btn" onclick="deleteFile('${escapeHtml(file.path)}', '${escapeHtml(file.name)}')"
                                    style="color: #EF4444;">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            listViewBody.innerHTML = listHtml;
        }

        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            const icons = {
                'pdf': { icon: 'fas fa-file-pdf', style: 'color: #EF4444;' },
                'doc': { icon: 'fas fa-file-word', style: 'color: #2563EB;' },
                'docx': { icon: 'fas fa-file-word', style: 'color: #2563EB;' },
                'xls': { icon: 'fas fa-file-excel', style: 'color: #10B981;' },
                'xlsx': { icon: 'fas fa-file-excel', style: 'color: #10B981;' },
                'ppt': { icon: 'fas fa-file-powerpoint', style: 'color: #F59E0B;' },
                'pptx': { icon: 'fas fa-file-powerpoint', style: 'color: #F59E0B;' },
                'zip': { icon: 'fas fa-file-archive', style: 'color: #6B7280;' },
                'rar': { icon: 'fas fa-file-archive', style: 'color: #6B7280;' },
                'jpg': { icon: 'fas fa-file-image', style: 'color: #8B5CF6;' },
                'jpeg': { icon: 'fas fa-file-image', style: 'color: #8B5CF6;' },
                'png': { icon: 'fas fa-file-image', style: 'color: #8B5CF6;' },
                'gif': { icon: 'fas fa-file-image', style: 'color: #8B5CF6;' },
                'txt': { icon: 'fas fa-file-alt', style: 'color: #6B7280;' },
                'csv': { icon: 'fas fa-file-csv', style: 'color: #10B981;' },
            };
            return icons[ext] || { icon: 'fas fa-file', style: 'color: #9CA3AF;' };
        }

        function getFileType(filename) {
            const ext = filename.split('.').pop().toUpperCase();
            return ext + ' File';
        }

        function formatFolderName(folderName) {
            // Format intern folder names like "John_Doe_15" to "John Doe (ID: 15)"
            const match = folderName.match(/^(.+)_(\d+)$/);
            if (match) {
                const name = match[1].replace(/_/g, ' ');
                const id = match[2];
                return `${name} (ID: ${id})`;
            }
            // Otherwise just replace underscores with spaces
            return folderName.replace(/_/g, ' ');
        }

        function openFolder(path, name) {
            folderHistory.push({ path: currentPath, name: currentFolder });
            currentPath = path;
            currentFolder = name;

            const pathParts = path.split('/');
            let breadcrumb = '<i class="fas fa-home"></i> Root';
            pathParts.forEach((part, index) => {
                if (part) {
                    breadcrumb += ` > ${part.replace(/_/g, ' ')}`;
                }
            });

            document.getElementById('current-path').innerHTML = breadcrumb;
            document.getElementById('back-btn').style.display = 'flex';

            loadFolderContents(path);
        }

        function goBackFolder() {
            if (folderHistory.length > 0) {
                const previous = folderHistory.pop();
                currentPath = previous.path;
                currentFolder = previous.name;

                if (currentPath === '') {
                    document.getElementById('current-path').innerHTML = '<i class="fas fa-home"></i> Root';
                    document.getElementById('back-btn').style.display = 'none';
                    loadRootFolders();
                } else {
                    const pathParts = currentPath.split('/');
                    let breadcrumb = '<i class="fas fa-home"></i> Root';
                    pathParts.forEach(part => {
                        if (part) breadcrumb += ` > ${part.replace(/_/g, ' ')}`;
                    });
                    document.getElementById('current-path').innerHTML = breadcrumb;
                    loadFolderContents(currentPath);
                }
            }
        }

        function toggleViewMode() {
            const gridView = document.getElementById('grid-view');
            const listView = document.getElementById('list-view');
            const viewIcon = document.getElementById('view-icon');

            if (viewMode === 'grid') {
                gridView.style.display = 'none';
                listView.style.display = 'block';
                viewIcon.className = 'fas fa-th-large';
                viewMode = 'list';
            } else {
                gridView.style.display = 'grid';
                listView.style.display = 'none';
                viewIcon.className = 'fas fa-th';
                viewMode = 'grid';
            }
        }

        function searchFiles(query) {
            if (query.length > 0) {
                console.log('Searching for:', query);
                // In production, this would filter the displayed files
            }
        }

        function openNewFolderModal() {
            document.getElementById('createFolderModal').style.display = 'flex';
            document.getElementById('folderName').value = '';
            document.getElementById('folderDescription').value = '';
            document.getElementById('folderSizeLimit').value = '100';
            document.querySelectorAll('input[name="allowed_users"]').forEach(cb => cb.checked = false);
        }

        function closeCreateFolderModal() {
            document.getElementById('createFolderModal').style.display = 'none';
        }

        function selectColor(btn) {
            document.querySelectorAll('.color-option').forEach(b => b.style.borderColor = 'transparent');
            btn.style.borderColor = btn.style.backgroundColor;
            document.getElementById('selectedColor').value = btn.getAttribute('data-color');
        }

        function submitCreateFolder(event) {
            event.preventDefault();

            const folderName = document.getElementById('folderName').value;
            const color = document.getElementById('selectedColor').value;
            const description = document.getElementById('folderDescription').value;
            const allowedUsers = Array.from(document.querySelectorAll('input[name="allowed_users"]:checked')).map(cb => cb.value);
            const sizeLimit = document.getElementById('folderSizeLimit').value || 10;

            if (allowedUsers.length === 0) {
                showToast('error', 'Error', 'Please select at least one user type who can upload');
                return;
            }

            fetch('/admin/documents/create-folder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: folderName,
                    color: color,
                    description: description,
                    allowed_users: allowedUsers,
                    size_limit_mb: parseInt(sizeLimit)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Success', 'Folder created successfully');
                    closeCreateFolderModal();
                    loadDigitalRecords();
                } else {
                    showToast('error', 'Error', data.message || 'Failed to create folder');
                }
            })
            .catch(error => {
                console.error('Error creating folder:', error);
                showToast('error', 'Error', 'An error occurred while creating folder');
            });
        }

        function openUploadFileModal() {
            // Check if we're in a folder
            if (!currentPath || currentPath === '') {
                showToast('warning', 'Select a Folder', 'Please navigate to a folder before uploading files.');
                return;
            }
            document.getElementById('uploadFileModal').style.display = 'flex';
            document.getElementById('adminFileUpload').value = '';
            document.getElementById('uploadFileList').innerHTML = '<p style="color: #9CA3AF; text-align: center;">No files selected</p>';
        }

        function closeUploadFileModal() {
            document.getElementById('uploadFileModal').style.display = 'none';
        }

        function handleAdminFileSelect(input) {
            const files = input.files;
            const listContainer = document.getElementById('uploadFileList');

            if (files.length === 0) {
                listContainer.innerHTML = '<p style="color: #9CA3AF; text-align: center;">No files selected</p>';
                return;
            }

            const maxSizeMB = 50;
            const maxSizeBytes = maxSizeMB * 1024 * 1024;
            const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'rar', 'ppt', 'pptx', 'csv'];

            let html = '';
            for (let file of files) {
                const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
                const extension = file.name.split('.').pop().toLowerCase();
                let status = 'valid';
                let statusIcon = '<i class="fas fa-check-circle" style="color: #10B981;"></i>';
                let statusText = '';

                if (file.size > maxSizeBytes) {
                    status = 'error';
                    statusIcon = '<i class="fas fa-exclamation-circle" style="color: #EF4444;"></i>';
                    statusText = `<span style="color: #EF4444; font-size: 12px;">Too large (max ${maxSizeMB} MB)</span>`;
                } else if (!allowedExtensions.includes(extension)) {
                    status = 'error';
                    statusIcon = '<i class="fas fa-exclamation-circle" style="color: #EF4444;"></i>';
                    statusText = '<span style="color: #EF4444; font-size: 12px;">Unsupported format</span>';
                }

                html += `
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; border: 1px solid ${status === 'error' ? '#FCA5A5' : '#E5E7EB'}; border-radius: 8px; margin-bottom: 8px; background: ${status === 'error' ? '#FEF2F2' : '#F9FAFB'};">
                        <div style="display: flex; align-items: center; gap: 10px; flex: 1; min-width: 0;">
                            ${statusIcon}
                            <div style="min-width: 0; flex: 1;">
                                <div style="font-weight: 500; color: #1F2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${escapeHtml(file.name)}</div>
                                <div style="font-size: 12px; color: #6B7280;">${fileSizeMB} MB</div>
                                ${statusText}
                            </div>
                        </div>
                    </div>
                `;
            }
            listContainer.innerHTML = html;
        }

        function submitUploadFiles() {
            const input = document.getElementById('adminFileUpload');
            const files = input.files;

            if (files.length === 0) {
                showToast('warning', 'No Files', 'Please select files to upload.');
                return;
            }

            const maxSizeMB = 50;
            const maxSizeBytes = maxSizeMB * 1024 * 1024;
            const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'rar', 'ppt', 'pptx', 'csv'];

            let validFiles = [];
            let errorMessages = [];

            for (let file of files) {
                const extension = file.name.split('.').pop().toLowerCase();

                if (file.size > maxSizeBytes) {
                    const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
                    errorMessages.push(`"${file.name}" is too large (${fileSizeMB} MB). Maximum: ${maxSizeMB} MB.`);
                    continue;
                }

                if (!allowedExtensions.includes(extension)) {
                    errorMessages.push(`"${file.name}" has unsupported format.`);
                    continue;
                }

                validFiles.push(file);
            }

            if (errorMessages.length > 0) {
                errorMessages.forEach(msg => showToast('error', 'Upload Error', msg));
            }

            if (validFiles.length === 0) {
                return;
            }

            // Upload valid files
            let uploadCount = 0;
            let successCount = 0;

            validFiles.forEach(file => {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('path', currentPath);

                fetch('/admin/documents/upload', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => {
                    return response.text().then(text => {
                        let data;
                        try {
                            data = JSON.parse(text);
                        } catch (e) {
                            if (response.status === 413 || text.includes('upload_max_filesize') || text.includes('post_max_size')) {
                                throw new Error(`File "${file.name}" is too large. Maximum allowed size is 50 MB.`);
                            }
                            throw new Error(`Upload failed for "${file.name}". Please try again.`);
                        }

                        if (!response.ok) {
                            if (data.errors) {
                                const errorMessages = Object.values(data.errors).flat();
                                throw new Error(errorMessages.map(msg => msg.includes('may not be greater than') ? `File "${file.name}" is too large. Maximum: 50 MB.` : msg).join(', '));
                            }
                            throw new Error(data.message || `Upload failed for "${file.name}"`);
                        }
                        return data;
                    });
                })
                .then(data => {
                    if (data.success) {
                        successCount++;
                    } else {
                        showToast('error', 'Upload Failed', data.message || `Failed to upload "${file.name}"`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Upload Error', error.message);
                })
                .finally(() => {
                    uploadCount++;
                    if (uploadCount === validFiles.length) {
                        if (successCount > 0) {
                            showToast('success', 'Upload Complete', `${successCount} file(s) uploaded successfully.`);
                            closeUploadFileModal();
                            loadFolderContents(currentPath);
                        }
                    }
                });
            });
        }

        function viewFileDetails(filePath, fileName) {
            currentPreviewFile = { path: filePath, name: fileName };
            const ext = fileName.split('.').pop().toLowerCase();
            const fileIcon = getFileIcon(fileName);
            const downloadUrl = `/admin/documents/download?path=${encodeURIComponent(filePath)}`;

            // Update modal header
            document.getElementById('filePreviewName').textContent = fileName;
            document.getElementById('filePreviewIcon').className = fileIcon.icon;
            document.getElementById('filePreviewIcon').style.cssText = fileIcon.style;

            // Determine preview type
            const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            const pdfExts = ['pdf'];
            const textExts = ['txt', 'csv', 'log', 'json', 'xml', 'html', 'css', 'js'];
            const officeExts = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

            let previewContent = '';

            if (imageExts.includes(ext)) {
                previewContent = `
                    <div style="padding: 20px; text-align: center; width: 100%;">
                        <img src="${downloadUrl}" alt="${escapeHtml(fileName)}" style="max-width: 100%; max-height: 60vh; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);" onerror="this.parentElement.innerHTML='<div style=\\'text-align: center; color: #6B7280; padding: 40px;\\'><i class=\\'fas fa-exclamation-triangle\\' style=\\'font-size: 48px; margin-bottom: 12px; color: #F59E0B;\\'></i><p>Failed to load image</p></div>'">
                    </div>
                `;
                document.getElementById('filePreviewInfo').textContent = 'Image Preview';
            } else if (pdfExts.includes(ext)) {
                previewContent = `
                    <iframe src="${downloadUrl}#toolbar=1&navpanes=0" style="width: 100%; height: 100%; min-height: 500px; border: none;" title="PDF Preview"></iframe>
                `;
                document.getElementById('filePreviewInfo').textContent = 'PDF Document';
            } else if (textExts.includes(ext)) {
                previewContent = `
                    <div style="padding: 20px; width: 100%;">
                        <div id="textPreviewContent" style="background: white; border-radius: 8px; padding: 20px; font-family: 'Consolas', 'Monaco', monospace; font-size: 13px; line-height: 1.6; white-space: pre-wrap; word-break: break-word; max-height: 60vh; overflow: auto; border: 1px solid #E5E7EB;">
                            <div style="text-align: center; color: #6B7280;"><i class="fas fa-spinner fa-spin"></i> Loading content...</div>
                        </div>
                    </div>
                `;
                document.getElementById('filePreviewInfo').textContent = `${ext.toUpperCase()} File`;

                // Fetch text content
                fetch(downloadUrl)
                    .then(response => response.text())
                    .then(text => {
                        const textEl = document.getElementById('textPreviewContent');
                        if (textEl) {
                            const maxChars = 100000;
                            if (text.length > maxChars) {
                                text = text.substring(0, maxChars) + '\n\n... (File truncated. Download to see full content)';
                            }
                            textEl.textContent = text || '(Empty file)';
                        }
                    })
                    .catch(error => {
                        const textEl = document.getElementById('textPreviewContent');
                        if (textEl) {
                            textEl.innerHTML = '<div style="text-align: center; color: #EF4444;"><i class="fas fa-exclamation-circle"></i> Failed to load file content</div>';
                        }
                    });
            } else if (officeExts.includes(ext)) {
                previewContent = `
                    <div style="text-align: center; padding: 60px 40px;">
                        <div style="width: 100px; height: 100px; margin: 0 auto 24px; background: linear-gradient(135deg, #DBEAFE, #93C5FD); border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                            <i class="${fileIcon.icon}" style="font-size: 48px; ${fileIcon.style}"></i>
                        </div>
                        <h3 style="color: #1F2937; margin-bottom: 8px; font-size: 20px;">${escapeHtml(fileName)}</h3>
                        <p style="color: #6B7280; margin-bottom: 24px; font-size: 14px;">Office documents cannot be previewed directly in the browser.</p>
                        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                            <a href="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(window.location.origin + downloadUrl)}" target="_blank" style="padding: 12px 24px; background: linear-gradient(135deg, #2563EB, #1D4ED8); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fas fa-external-link-alt"></i> Open in Office Online
                            </a>
                            <button onclick="downloadCurrentFile()" style="padding: 12px 24px; background: linear-gradient(135deg, #10B981, #059669); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fas fa-download"></i> Download
                            </button>
                        </div>
                    </div>
                `;
                document.getElementById('filePreviewInfo').textContent = 'Office Document';
            } else {
                previewContent = `
                    <div style="text-align: center; padding: 60px 40px;">
                        <div style="width: 100px; height: 100px; margin: 0 auto 24px; background: linear-gradient(135deg, #F3F4F6, #E5E7EB); border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                            <i class="${fileIcon.icon}" style="font-size: 48px; ${fileIcon.style}"></i>
                        </div>
                        <h3 style="color: #1F2937; margin-bottom: 8px; font-size: 20px;">${escapeHtml(fileName)}</h3>
                        <p style="color: #6B7280; margin-bottom: 24px; font-size: 14px;">Preview not available for this file type.</p>
                        <button onclick="downloadCurrentFile()" style="padding: 12px 24px; background: linear-gradient(135deg, #10B981, #059669); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-download"></i> Download File
                        </button>
                    </div>
                `;
                document.getElementById('filePreviewInfo').textContent = ext.toUpperCase() + ' File';
            }

            document.getElementById('filePreviewContent').innerHTML = previewContent;
            document.getElementById('filePreviewMeta').innerHTML = `<i class="fas fa-folder" style="margin-right: 6px;"></i> ${escapeHtml(filePath)}`;
            document.getElementById('filePreviewModal').style.display = 'flex';
        }

        let currentPreviewFile = null;

        function closeFilePreview() {
            document.getElementById('filePreviewModal').style.display = 'none';
            currentPreviewFile = null;
        }

        function downloadCurrentFile() {
            if (currentPreviewFile) {
                downloadFile(currentPreviewFile.path, currentPreviewFile.name);
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (document.getElementById('filePreviewModal').style.display === 'flex') {
                    closeFilePreview();
                }
                if (document.getElementById('bulkRejectModal').style.display === 'flex') {
                    closeBulkRejectModal();
                }
            }
        });

        function downloadFile(filePath, fileName) {
            window.location.href = `/admin/documents/download?path=${encodeURIComponent(filePath)}`;
        }

        function deleteFile(filePath, fileName) {
            if (!confirm(`Are you sure you want to delete "${fileName}"? This action cannot be undone.`)) {
                return;
            }

            fetch('/admin/documents/file', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ path: filePath })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Success', 'File deleted successfully');
                    // Reload current folder
                    if (currentPath === '') {
                        loadRootFolders();
                    } else {
                        loadFolderContents(currentPath);
                    }
                } else {
                    showToast('error', 'Error', data.message || 'Failed to delete file');
                }
            })
            .catch(error => {
                console.error('Error deleting file:', error);
                showToast('error', 'Error', 'An error occurred while deleting file');
            });
        }

        function deleteFolder(folderId, folderName) {
            console.log('Delete folder called with:', { folderId, folderName });

            if (!folderId) {
                showToast('error', 'Error', 'Folder ID is missing. Please refresh the page and try again.');
                return;
            }

            if (!confirm(`Are you sure you want to delete folder "${folderName}"? This will delete all contents inside. This action cannot be undone.`)) {
                return;
            }

            fetch('/admin/documents/folder', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ folder_id: folderId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Success', 'Folder deleted successfully');
                    // Reload current view
                    if (currentPath === '') {
                        loadRootFolders();
                    } else {
                        // Go back one level if we're inside the deleted folder
                        goBackFolder();
                    }
                } else {
                    showToast('error', 'Error', data.message || 'Failed to delete folder');
                }
            })
            .catch(error => {
                console.error('Error deleting folder:', error);
                showToast('error', 'Error', 'An error occurred while deleting folder');
            });
        }

        // Toggle school group table
        function toggleSchoolGroup(schoolId) {
            const table = document.getElementById(schoolId);
            const icon = document.getElementById('icon-' + schoolId);

            if (!table) return;

            if (table.style.display === 'none') {
                table.style.display = 'block';
                if (icon) icon.style.transform = 'rotate(0deg)';
            } else {
                table.style.display = 'none';
                if (icon) icon.style.transform = 'rotate(-90deg)';
            }
        }

        // Filter interns function
        function filterInterns(filterType) {
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.closest('.filter-tab').classList.add('active');

            // In a real application, this would filter the data
            console.log('Filtering by:', filterType);
        }

        // ===== TIME & ATTENDANCE PAGINATION =====
        const ITEMS_PER_PAGE = 8;

        // Daily Hours Pagination
        let dailyCurrentPage = 1;
        let dailyTotalPages = 1;

        function initDailyPagination() {
            const tbody = document.getElementById('dailyHoursTableBody');
            if (!tbody) return;

            const rows = tbody.querySelectorAll('tr:not(:only-child)');
            const totalRows = rows.length;

            // Check if only empty state row exists
            if (totalRows === 0 || (totalRows === 1 && rows[0].querySelector('td[colspan]'))) {
                document.getElementById('dailyHoursPagination').style.display = 'none';
                return;
            }

            dailyTotalPages = Math.ceil(totalRows / ITEMS_PER_PAGE);
            document.getElementById('dailyTotalRecords').textContent = totalRows;
            updateDailyPagination();
        }

        function updateDailyPagination() {
            const tbody = document.getElementById('dailyHoursTableBody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(:only-child)'));
            const totalRows = rows.filter(r => !r.querySelector('td[colspan]')).length;

            if (totalRows === 0) {
                document.getElementById('dailyHoursPagination').style.display = 'none';
                return;
            }

            document.getElementById('dailyHoursPagination').style.display = 'flex';

            const start = (dailyCurrentPage - 1) * ITEMS_PER_PAGE;
            const end = Math.min(start + ITEMS_PER_PAGE, totalRows);

            // Hide all rows, show only current page
            rows.forEach((row, index) => {
                if (row.querySelector('td[colspan]')) return;
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });

            // Update showing text
            document.getElementById('dailyShowingStart').textContent = totalRows > 0 ? start + 1 : 0;
            document.getElementById('dailyShowingEnd').textContent = end;
            document.getElementById('dailyTotalRecords').textContent = totalRows;

            // Update buttons
            document.getElementById('dailyPrevBtn').disabled = dailyCurrentPage === 1;
            document.getElementById('dailyNextBtn').disabled = dailyCurrentPage >= dailyTotalPages;

            // Update page numbers
            updatePageNumbers('daily', dailyCurrentPage, dailyTotalPages);
        }

        function changeDailyPage(direction) {
            if (direction === 'prev' && dailyCurrentPage > 1) {
                dailyCurrentPage--;
            } else if (direction === 'next' && dailyCurrentPage < dailyTotalPages) {
                dailyCurrentPage++;
            } else if (typeof direction === 'number') {
                dailyCurrentPage = direction;
            }
            updateDailyPagination();
        }

        // Filter Daily Attendance by status, school and name
        function filterDailyAttendance() {
            const statusFilter = document.getElementById('attendanceStatusFilter')?.value || 'all';
            const schoolFilter = document.getElementById('schoolFilter')?.value || 'all';
            const searchTerm = document.getElementById('dailySearchInput')?.value?.toLowerCase() || '';

            const tbody = document.getElementById('dailyHoursTableBody');
            if (!tbody) return;

            const rows = Array.from(tbody.querySelectorAll('tr[data-attendance-id]'));

            rows.forEach(row => {
                const rowStatus = row.dataset.status || '';
                const rowSchool = row.dataset.school || '';
                const rowName = row.dataset.name || '';

                const matchesStatus = statusFilter === 'all' || rowStatus === statusFilter;
                const matchesSchool = schoolFilter === 'all' || rowSchool === schoolFilter;
                const matchesSearch = !searchTerm || rowName.includes(searchTerm);

                row.style.display = (matchesStatus && matchesSchool && matchesSearch) ? '' : 'none';
            });

            // Update pagination counts
            const visibleRows = rows.filter(row => row.style.display !== 'none');
            const totalVisible = visibleRows.length;

            dailyCurrentPage = 1;
            dailyTotalPages = Math.ceil(totalVisible / ITEMS_PER_PAGE) || 1;

            // Apply pagination to visible rows only
            visibleRows.forEach((row, index) => {
                row.style.display = (index < ITEMS_PER_PAGE) ? '' : 'none';
            });

            document.getElementById('dailyShowingStart').textContent = totalVisible > 0 ? 1 : 0;
            document.getElementById('dailyShowingEnd').textContent = Math.min(ITEMS_PER_PAGE, totalVisible);
            document.getElementById('dailyTotalRecords').textContent = totalVisible;
            document.getElementById('dailyPrevBtn').disabled = true;
            document.getElementById('dailyNextBtn').disabled = dailyCurrentPage >= dailyTotalPages;

            // Show/hide pagination based on results
            document.getElementById('dailyHoursPagination').style.display = totalVisible > 0 ? 'flex' : 'none';
        }

        // History Pagination
        let historyCurrentPage = 1;
        let historyTotalPages = 1;

        function initHistoryPagination() {
            const tbody = document.getElementById('historyTableBody');
            if (!tbody) return;

            const rows = tbody.querySelectorAll('tr:not(:only-child)');
            const totalRows = rows.length;

            if (totalRows === 0 || (totalRows === 1 && rows[0].querySelector('td[colspan]'))) {
                document.getElementById('historyPagination').style.display = 'none';
                return;
            }

            historyTotalPages = Math.ceil(totalRows / ITEMS_PER_PAGE);
            document.getElementById('historyTotalRecords').textContent = totalRows;
            updateHistoryPagination();
        }

        function updateHistoryPagination() {
            const tbody = document.getElementById('historyTableBody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(:only-child)'));
            const totalRows = rows.filter(r => !r.querySelector('td[colspan]')).length;

            if (totalRows === 0) {
                document.getElementById('historyPagination').style.display = 'none';
                return;
            }

            document.getElementById('historyPagination').style.display = 'flex';

            const start = (historyCurrentPage - 1) * ITEMS_PER_PAGE;
            const end = Math.min(start + ITEMS_PER_PAGE, totalRows);

            rows.forEach((row, index) => {
                if (row.querySelector('td[colspan]')) return;
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });

            document.getElementById('historyShowingStart').textContent = totalRows > 0 ? start + 1 : 0;
            document.getElementById('historyShowingEnd').textContent = end;
            document.getElementById('historyTotalRecords').textContent = totalRows;

            document.getElementById('historyPrevBtn').disabled = historyCurrentPage === 1;
            document.getElementById('historyNextBtn').disabled = historyCurrentPage >= historyTotalPages;

            updatePageNumbers('history', historyCurrentPage, historyTotalPages);
        }

        function changeHistoryPage(direction) {
            if (direction === 'prev' && historyCurrentPage > 1) {
                historyCurrentPage--;
            } else if (direction === 'next' && historyCurrentPage < historyTotalPages) {
                historyCurrentPage++;
            } else if (typeof direction === 'number') {
                historyCurrentPage = direction;
            }
            updateHistoryPagination();
        }

        // Filter History Attendance by status, school and name
        function filterHistoryAttendance() {
            const statusFilter = document.getElementById('historyStatusFilter')?.value || 'all';
            const schoolFilter = document.getElementById('historySchoolFilter')?.value || 'all';
            const searchTerm = document.getElementById('historySearchInput')?.value?.toLowerCase() || '';

            const tbody = document.getElementById('historyTableBody');
            if (!tbody) return;

            const rows = Array.from(tbody.querySelectorAll('tr[data-status]'));

            rows.forEach(row => {
                const rowStatus = row.dataset.status || '';
                const rowSchool = row.dataset.school || '';
                const rowName = row.dataset.name || '';

                const matchesStatus = statusFilter === 'all' || rowStatus === statusFilter;
                const matchesSchool = schoolFilter === 'all' || rowSchool === schoolFilter;
                const matchesSearch = !searchTerm || rowName.includes(searchTerm);

                row.style.display = (matchesStatus && matchesSchool && matchesSearch) ? '' : 'none';
            });

            // Update pagination counts
            const visibleRows = rows.filter(row => row.style.display !== 'none');
            const totalVisible = visibleRows.length;

            historyCurrentPage = 1;
            historyTotalPages = Math.ceil(totalVisible / ITEMS_PER_PAGE) || 1;

            // Apply pagination to visible rows only
            visibleRows.forEach((row, index) => {
                row.style.display = (index < ITEMS_PER_PAGE) ? '' : 'none';
            });

            document.getElementById('historyShowingStart').textContent = totalVisible > 0 ? 1 : 0;
            document.getElementById('historyShowingEnd').textContent = Math.min(ITEMS_PER_PAGE, totalVisible);
            document.getElementById('historyTotalRecords').textContent = totalVisible;
            document.getElementById('historyPrevBtn').disabled = true;
            document.getElementById('historyNextBtn').disabled = historyCurrentPage >= historyTotalPages;

            // Show/hide pagination based on results
            document.getElementById('historyPagination').style.display = totalVisible > 0 ? 'flex' : 'none';
        }

        // Summary Pagination
        let summaryCurrentPage = 1;
        let summaryTotalPages = 1;

        function initSummaryPagination() {
            const tbody = document.getElementById('summaryTableBody');
            if (!tbody) return;

            const rows = tbody.querySelectorAll('tr:not(:only-child)');
            const totalRows = rows.length;

            if (totalRows === 0 || (totalRows === 1 && rows[0].querySelector('td[colspan]'))) {
                document.getElementById('summaryPagination').style.display = 'none';
                return;
            }

            summaryTotalPages = Math.ceil(totalRows / ITEMS_PER_PAGE);
            document.getElementById('summaryTotalRecords').textContent = totalRows;
            updateSummaryPagination();
        }

        function updateSummaryPagination() {
            const tbody = document.getElementById('summaryTableBody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(:only-child)'));
            const totalRows = rows.filter(r => !r.querySelector('td[colspan]')).length;

            if (totalRows === 0) {
                document.getElementById('summaryPagination').style.display = 'none';
                return;
            }

            document.getElementById('summaryPagination').style.display = 'flex';

            const start = (summaryCurrentPage - 1) * ITEMS_PER_PAGE;
            const end = Math.min(start + ITEMS_PER_PAGE, totalRows);

            rows.forEach((row, index) => {
                if (row.querySelector('td[colspan]')) return;
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });

            document.getElementById('summaryShowingStart').textContent = totalRows > 0 ? start + 1 : 0;
            document.getElementById('summaryShowingEnd').textContent = end;
            document.getElementById('summaryTotalRecords').textContent = totalRows;

            document.getElementById('summaryPrevBtn').disabled = summaryCurrentPage === 1;
            document.getElementById('summaryNextBtn').disabled = summaryCurrentPage >= summaryTotalPages;

            updatePageNumbers('summary', summaryCurrentPage, summaryTotalPages);
        }

        function changeSummaryPage(direction) {
            if (direction === 'prev' && summaryCurrentPage > 1) {
                summaryCurrentPage--;
            } else if (direction === 'next' && summaryCurrentPage < summaryTotalPages) {
                summaryCurrentPage++;
            } else if (typeof direction === 'number') {
                summaryCurrentPage = direction;
            }
            updateSummaryPagination();
        }

        // Shared page numbers renderer
        function updatePageNumbers(prefix, currentPage, totalPages) {
            const container = document.getElementById(`${prefix}PageNumbers`);
            if (!container) return;

            container.innerHTML = '';

            const maxButtons = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
            let endPage = Math.min(totalPages, startPage + maxButtons - 1);

            if (endPage - startPage < maxButtons - 1) {
                startPage = Math.max(1, endPage - maxButtons + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.style.cssText = `
                    min-width: 32px; height: 32px; border-radius: 6px; border: 1px solid #E5E7EB;
                    cursor: pointer; font-size: 13px; font-weight: 500;
                    ${i === currentPage ? 'background: #7B1D3A; color: white; border-color: #7B1D3A;' : 'background: #F3F4F6; color: #374151;'}
                `;
                btn.onclick = () => {
                    if (prefix === 'daily') changeDailyPage(i);
                    else if (prefix === 'history') changeHistoryPage(i);
                    else if (prefix === 'summary') changeSummaryPage(i);
                };
                container.appendChild(btn);
            }
        }

        // Initialize pagination on page load
        document.addEventListener('DOMContentLoaded', function() {
            initDailyPagination();
            initHistoryPagination();
            initSummaryPagination();
            initPendingBookingsPagination();
            initAllBookingsPagination();
        });

        // ===== SCHEDULER PAGINATION =====

        // Pending Bookings Pagination
        let pendingCurrentPage = 1;
        let pendingTotalPages = 1;

        function initPendingBookingsPagination() {
            const tbody = document.getElementById('pendingBookingsBody');
            if (!tbody) return;

            const rows = tbody.querySelectorAll('tr:not(#noPendingRow)');
            const totalRows = rows.length;

            if (totalRows === 0) {
                document.getElementById('pendingBookingsPagination').style.display = 'none';
                return;
            }

            pendingTotalPages = Math.ceil(totalRows / ITEMS_PER_PAGE);
            updatePendingBookingsPagination();
        }

        function updatePendingBookingsPagination() {
            const tbody = document.getElementById('pendingBookingsBody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(#noPendingRow)'));
            const totalRows = rows.length;

            if (totalRows === 0) {
                document.getElementById('pendingBookingsPagination').style.display = 'none';
                return;
            }

            document.getElementById('pendingBookingsPagination').style.display = 'flex';

            const start = (pendingCurrentPage - 1) * ITEMS_PER_PAGE;
            const end = Math.min(start + ITEMS_PER_PAGE, totalRows);

            rows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });

            document.getElementById('pendingShowingStart').textContent = totalRows > 0 ? start + 1 : 0;
            document.getElementById('pendingShowingEnd').textContent = end;
            document.getElementById('pendingTotalItems').textContent = totalRows;

            document.getElementById('pendingPrevBtn').disabled = pendingCurrentPage === 1;
            document.getElementById('pendingPrevBtn').style.opacity = pendingCurrentPage === 1 ? '0.5' : '1';
            document.getElementById('pendingNextBtn').disabled = pendingCurrentPage >= pendingTotalPages;
            document.getElementById('pendingNextBtn').style.opacity = pendingCurrentPage >= pendingTotalPages ? '0.5' : '1';

            document.getElementById('pendingPageInfo').textContent = `${pendingCurrentPage} / ${pendingTotalPages}`;
        }

        function pendingPrevPage() {
            if (pendingCurrentPage > 1) {
                pendingCurrentPage--;
                updatePendingBookingsPagination();
            }
        }

        function pendingNextPage() {
            if (pendingCurrentPage < pendingTotalPages) {
                pendingCurrentPage++;
                updatePendingBookingsPagination();
            }
        }

        // All Bookings Pagination
        let allCurrentPage = 1;
        let allTotalPages = 1;

        function initAllBookingsPagination() {
            const tbody = document.getElementById('allBookingsBody');
            if (!tbody) return;

            const rows = tbody.querySelectorAll('tr.booking-row');
            const totalRows = rows.length;

            if (totalRows === 0) {
                document.getElementById('allBookingsPagination').style.display = 'none';
                return;
            }

            allTotalPages = Math.ceil(totalRows / ITEMS_PER_PAGE);
            updateAllBookingsPagination();
        }

        function updateAllBookingsPagination() {
            const tbody = document.getElementById('allBookingsBody');
            const rows = Array.from(tbody.querySelectorAll('tr.booking-row'));
            // Apply filters first
            const searchTerm = document.getElementById('searchBookings')?.value?.toLowerCase() || '';
            const statusFilter = document.getElementById('filterBookingStatus')?.value || '';
            const emailFilter = document.getElementById('filterEmailStatus')?.value || '';

            const visibleRows = rows.filter(row => {
                const matchesSearch = !searchTerm || row.dataset.search.includes(searchTerm);
                const matchesStatus = !statusFilter || row.dataset.status === statusFilter;
                let matchesEmail = true;
                if (emailFilter === 'sent') {
                    matchesEmail = row.dataset.emailed === 'yes';
                } else if (emailFilter === 'not-sent') {
                    matchesEmail = row.dataset.emailed === 'no' && row.dataset.status === 'approved';
                }
                return matchesSearch && matchesStatus && matchesEmail;
            });

            const totalRows = visibleRows.length;

            if (totalRows === 0) {
                document.getElementById('allBookingsPagination').style.display = 'none';
                rows.forEach(row => row.style.display = 'none');
                return;
            }

            // Reset to first page if current page is out of range
            allTotalPages = Math.ceil(totalRows / ITEMS_PER_PAGE);
            if (allCurrentPage > allTotalPages) {
                allCurrentPage = 1;
            }

            document.getElementById('allBookingsPagination').style.display = 'flex';

            const start = (allCurrentPage - 1) * ITEMS_PER_PAGE;
            const end = Math.min(start + ITEMS_PER_PAGE, totalRows);

            // Hide all rows first
            rows.forEach(row => row.style.display = 'none');

            // Show only filtered rows for current page
            visibleRows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });

            document.getElementById('allShowingStart').textContent = totalRows > 0 ? start + 1 : 0;
            document.getElementById('allShowingEnd').textContent = end;
            document.getElementById('allTotalItems').textContent = totalRows;

            document.getElementById('allPrevBtn').disabled = allCurrentPage === 1;
            document.getElementById('allPrevBtn').style.opacity = allCurrentPage === 1 ? '0.5' : '1';
            document.getElementById('allNextBtn').disabled = allCurrentPage >= allTotalPages;
            document.getElementById('allNextBtn').style.opacity = allCurrentPage >= allTotalPages ? '0.5' : '1';

            document.getElementById('allPageInfo').textContent = `${allCurrentPage} / ${allTotalPages}`;
        }

        function allBookingsPrevPage() {
            if (allCurrentPage > 1) {
                allCurrentPage--;
                updateAllBookingsPagination();
            }
        }

        function allBookingsNextPage() {
            if (allCurrentPage < allTotalPages) {
                allCurrentPage++;
                updateAllBookingsPagination();
            }
        }

        // Override filterBookings to work with pagination
        const originalFilterBookings = window.filterBookings || function() {};
        window.filterBookings = function() {
            allCurrentPage = 1; // Reset to first page on filter change
            updateAllBookingsPagination();
        };

        // Switch time tab function
        function switchTimeTab(event, tabId) {
            // Update active tab
            document.querySelectorAll('.time-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.closest('.time-tab').classList.add('active');

            // Hide all tab contents
            document.querySelectorAll('.time-tab-content').forEach(content => {
                content.style.display = 'none';
            });

            // Show selected tab content
            const selectedContent = document.getElementById(tabId);
            if (selectedContent) {
                selectedContent.style.display = 'block';
            }
        }

        // Approve overtime function
        function approveOvertime(attendanceId) {
            if (!confirm('Are you sure you want to approve this overtime?')) {
                return;
            }

            fetch(`/admin/attendance/${attendanceId}/approve-overtime`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Overtime approved successfully!');
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to approve overtime.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while approving overtime.');
            });
        }

        // Update hours live for attendances still working
        function updateLiveHours() {
            const hoursElements = document.querySelectorAll('[id^="hours-"]');
            hoursElements.forEach(element => {
                const attendanceId = element.id.replace('hours-', '');
                const row = document.querySelector(`[data-attendance-id="${attendanceId}"]`);
                if (row) {
                    const timeInStr = row.getAttribute('data-time-in');
                    if (timeInStr && !row.getAttribute('data-timed-out')) {
                        const timeIn = new Date(timeInStr);
                        const now = new Date();
                        const diffMs = now - timeIn;
                        const hours = (diffMs / (1000 * 60 * 60)).toFixed(2);
                        element.textContent = hours + ' hrs';
                    }
                }
            });
        }

        // Start live hour updates every second
        setInterval(updateLiveHours, 1000);
        updateLiveHours();

        // Handle daily overtime approval/rejection (legacy)
        function handleDailyOvertimeAction(action, internName, overtimeHours) {
            const actionText = action === 'approve' ? 'approved' : 'rejected';
            alert(`${overtimeHours} hour(s) overtime for ${internName} has been ${actionText}.`);

            // In a real application, this would:
            // 1. Send request to backend
            // 2. Update database
            // 3. Send notification to intern
            // 4. Refresh the daily hours list
            console.log(`${action} ${overtimeHours} hrs overtime for ${internName}`);
        }

        // Pagination variables
        let currentPage = 1;
        const itemsPerPage = 15;
        let currentTimePage = 1;
        let currentSummaryPage = 1;
        let currentTaskPage = 1;

        // Task Assignment Filter and Search
        function filterTasks() {
            console.log('Filtering tasks...');
            // In a real application, this would filter the table based on status, type, and school
        }

        function searchTasks() {
            const searchInput = document.getElementById('taskSearchInput').value.toLowerCase();
            const tableBody = document.getElementById('taskTableBody');
            const rows = tableBody.getElementsByTagName('tr');

            for (let row of rows) {
                const taskCell = row.cells[0]?.textContent.toLowerCase();
                const assignedCell = row.cells[1]?.textContent.toLowerCase();
                if ((taskCell && taskCell.includes(searchInput)) || (assignedCell && assignedCell.includes(searchInput))) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        function resetTaskFilters() {
            document.getElementById('taskStatusFilter').value = 'all';
            document.getElementById('assignmentTypeFilter').value = 'all';
            document.getElementById('taskSchoolFilter').value = 'all';
            document.getElementById('taskSearchInput').value = '';
            filterTasks();
            searchTasks();
        }

        // ===== TASK MODAL FUNCTIONS =====

        // Open New Task Modal
        function openNewTaskModal() {
            document.getElementById('newTaskModal').classList.add('active');
            document.getElementById('newTaskForm').reset();
            toggleAssignmentType(); // Reset to individual view
        }

        // Close New Task Modal
        function closeNewTaskModal() {
            document.getElementById('newTaskModal').classList.remove('active');
            document.getElementById('newTaskForm').reset();
        }

        // Toggle between individual and group assignment
        function toggleAssignmentType() {
            const assignmentType = document.querySelector('input[name="assignmentType"]:checked').value;
            const individualSection = document.getElementById('individualSection');
            const groupSection = document.getElementById('groupSection');
            const internsSection = document.getElementById('internsSection');
            const leaderSection = document.getElementById('leaderSection');

            if (assignmentType === 'individual') {
                individualSection.style.display = 'block';
                groupSection.style.display = 'none';
                internsSection.style.display = 'none';
                leaderSection.style.display = 'none';
            } else {
                individualSection.style.display = 'none';
                groupSection.style.display = 'block';
            }
        }

        // Intern data by school - populated from database
        @php
            $jsInternsBySchool = ($internsBySchool ?? collect())->map(function($interns) {
                return $interns->map(function($intern) {
                    return [
                        'id' => $intern->id,
                        'name' => $intern->name,
                        'school' => $intern->school,
                        'reference_code' => $intern->reference_code,
                    ];
                })->values();
            })->toArray();

            $jsAllInterns = ($interns ?? collect())->map(function($intern) {
                return [
                    'id' => $intern->id,
                    'name' => $intern->name,
                    'school' => $intern->school,
                    'reference_code' => $intern->reference_code,
                ];
            })->values()->toArray();
        @endphp
        const internsBySchool = @json($jsInternsBySchool);

        // Create a mixed schools array with all interns
        const allInterns = @json($jsAllInterns);

        // Load interns based on selected school
        function loadInternsBySchool() {
            const schoolSelect = document.getElementById('schoolSelect').value;
            const internsGrid = document.getElementById('internsGrid');
            const internsSection = document.getElementById('internsSection');
            const leaderSection = document.getElementById('leaderSection');
            const groupLeaderSelect = document.getElementById('groupLeader');

            if (!schoolSelect) {
                internsSection.style.display = 'none';
                leaderSection.style.display = 'none';
                return;
            }

            // Get interns based on selection - use allInterns for 'mixed', otherwise lookup by school key
            let interns = [];
            if (schoolSelect === 'mixed') {
                interns = allInterns || [];
            } else {
                // Try to find matching school key (case insensitive, slug match)
                const schoolKeys = Object.keys(internsBySchool);
                for (const key of schoolKeys) {
                    if (key.toLowerCase().replace(/\s+/g, '-') === schoolSelect ||
                        key.toLowerCase().includes(schoolSelect.replace(/-/g, ' '))) {
                        interns = internsBySchool[key] || [];
                        break;
                    }
                }
            }

            internsGrid.innerHTML = '';
            groupLeaderSelect.innerHTML = '<option value="">-- Select Leader --</option>';

            if (interns.length === 0) {
                internsGrid.innerHTML = '<p style="color: #9CA3AF; font-size: 14px;">No interns found for this school.</p>';
            }

            interns.forEach(intern => {
                const label = document.createElement('label');
                label.className = 'intern-checkbox-label';
                label.innerHTML = `
                    <input type="checkbox" name="groupMembers" value="${intern.id}"
                           data-name="${intern.name}" data-school="${intern.school}"
                           onchange="updateLeaderDropdown()">
                    <span>${intern.name} <small style="color: #6B7280;">(${intern.school})</small></span>
                `;
                internsGrid.appendChild(label);
            });

            internsSection.style.display = 'block';
            leaderSection.style.display = 'block';
        }

        // Update leader dropdown based on selected members
        function updateLeaderDropdown() {
            const checkedBoxes = document.querySelectorAll('input[name="groupMembers"]:checked');
            const groupLeaderSelect = document.getElementById('groupLeader');

            groupLeaderSelect.innerHTML = '<option value="">-- Select Leader --</option>';

            checkedBoxes.forEach(checkbox => {
                const option = document.createElement('option');
                option.value = checkbox.value;
                option.textContent = checkbox.dataset.name;
                groupLeaderSelect.appendChild(option);
            });
        }

        // Create new task
        async function createTask() {
            const form = document.getElementById('newTaskForm');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const assignmentType = document.querySelector('input[name="assignmentType"]:checked').value;
            const title = document.getElementById('taskTitle').value;
            const description = document.getElementById('taskDescription').value;
            const checklistText = document.getElementById('taskChecklistText')?.value || '';
            const priority = document.getElementById('priorityLevel').value;
            const dueDate = document.getElementById('dueDate').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            let internIds = [];

            if (assignmentType === 'individual') {
                const internSelect = document.getElementById('individualIntern');
                if (!internSelect.value) {
                    alert('Please select an intern');
                    return;
                }
                internIds = [internSelect.value];
            } else {
                const checkedBoxes = document.querySelectorAll('input[name="groupMembers"]:checked');
                if (checkedBoxes.length === 0) {
                    alert('Please select at least one group member');
                    return;
                }
                internIds = Array.from(checkedBoxes).map(cb => cb.value);
            }

            // Generate a unique group ID for group tasks
            const groupId = assignmentType === 'group' ? 'group_' + Date.now() : null;

            try {
                // Create tasks for all selected interns
                const promises = internIds.map(internId => {
                    const taskData = {
                        intern_id: internId,
                        title: title,
                        description: description,
                        checklist_text: checklistText,
                        priority: priority === 'high' ? 'High' : (priority === 'medium' ? 'Medium' : 'Low'),
                        due_date: dueDate,
                        group_id: groupId
                    };

                    return fetch('/admin/tasks', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(taskData)
                    }).then(response => response.json());
                });

                const results = await Promise.all(promises);
                const allSuccessful = results.every(data => data.success);

                if (allSuccessful) {
                    const taskCount = internIds.length;
                    showToast('success', 'Success', `${taskCount} task${taskCount > 1 ? 's' : ''} created successfully!`);
                    closeNewTaskModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('error', 'Error', 'Some tasks failed to create');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Error', 'Error creating tasks: ' + error.message);
            }
        }

        // View task details
        function viewTaskDetails(taskId) {
            // Fetch task data from backend
            fetch(`/admin/tasks/${taskId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const task = data.task;

                // Intern Provided Progress Section
                const progressHtml = `
                    <div class="detail-section">
                        <div class="detail-label" style="color: #7B1D3A; font-weight: 700;">Intern Provided Progress</div>
                        <div class="detail-value">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                <div style="flex: 1; background: #f3f4f6; border-radius: 4px; height: 12px; overflow: hidden;">
                                    <div style="width: ${task.progress || 0}%; background: linear-gradient(90deg, #7B1D3A, #5a1428); height: 100%;"></div>
                                </div>
                                <span style="font-weight: 600; min-width: 50px;">${task.progress || 0}%</span>
                            </div>
                        </div>
                    </div>
                `;

                // Intern Notes Section
                const notesHtml = task.notes ? `
                    <div class="detail-section">
                        <div class="detail-label" style="color: #7B1D3A; font-weight: 700;">Intern Notes / Comments</div>
                        <div class="detail-value" style="background: #F3F4F6; padding: 12px; border-radius: 6px; border-left: 4px solid #7B1D3A; line-height: 1.5;">${task.notes}</div>
                    </div>
                ` : '';

                // Uploaded Documents Section
                const documentsHtml = task.documents && task.documents.length > 0 ? `
                    <div class="detail-section">
                        <div class="detail-label" style="color: #7B1D3A; font-weight: 700;">Uploaded Documents</div>
                        <div class="detail-value">
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                ${task.documents.map(doc => {
                                    const filename = doc.split('/').pop();
                                    return `
                                        <a href="/documents/download/${filename}" download style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; background: #E5E7EB; border-radius: 4px; text-decoration: none; color: #1F2937; font-size: 14px; transition: background 0.2s;">
                                            <i class="fas fa-file-download" style="color: #7B1D3A;"></i>
                                            ${filename}
                                        </a>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    </div>
                ` : '';

                const detailsHtml = `
                    <div class="detail-section">
                        <div class="detail-label">Task Title</div>
                        <div class="detail-value">${task.title}</div>
                    </div>
                    <div class="detail-section">
                        <div class="detail-label">Description</div>
                        <div class="detail-value">${task.description || 'No description provided'}</div>
                    </div>
                    <div class="detail-section">
                        <div class="detail-label">Assigned To</div>
                        <div class="detail-value">
                            ${task.group_members && task.group_members.length > 1 ?
                                task.group_members.map(member => `
                                    <div style="padding: 8px; background: #F3F4F6; border-radius: 6px; margin-bottom: 6px;">
                                        <div style="font-weight: 600;">${member.name}</div>
                                        <div style="font-size: 12px; color: #6B7280;">${member.school || 'N/A'}</div>
                                    </div>
                                `).join('')
                            : `${task.intern?.name || 'Unknown'}`}
                        </div>
                    </div>
                    <div class="detail-section">
                        <div class="detail-label">School</div>
                        <div class="detail-value">
                            ${task.group_members && task.group_members.length > 1 ?
                                (new Set(task.group_members.map(m => m.school)).size > 1 ?
                                    '<span style="color: #7B1D3A; font-weight: 600;">Mixed Schools</span>' :
                                    task.group_members[0].school || 'N/A'
                                )
                            : task.intern?.school || 'N/A'}
                        </div>
                    </div>
                    <div class="detail-section">
                        <div class="detail-label">Due Date</div>
                        <div class="detail-value">${new Date(task.due_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</div>
                    </div>
                    <div class="detail-section">
                        <div class="detail-label">Priority</div>
                        <div class="detail-value">
                            <span class="status-badge" style="background:
                                ${task.priority === 'High' ? '#DC2626; color: white' :
                                  task.priority === 'Medium' ? '#F59E0B; color: white' :
                                  '#228B22; color: white'};\">${task.priority}</span>
                        </div>
                    </div>
                    <div class="detail-section">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            ${(() => {
                                const isPending = task.status === 'Completed' && !task.completed_date;
                                const label = isPending ? 'Pending Admin Approval' : task.status;
                                const style = isPending ? '#DBEAFE; color: #1E40AF' :
                                    (task.status === 'Completed' ? '#D1FAE5; color: #065F46' :
                                     task.status === 'In Progress' ? '#FEF3C7; color: #92400E' :
                                     '#E5E7EB; color: #6B7280');

                                return '<span class="status-badge" style="background: ' + style + ';">' + label + '</span>';
                            })()}
                        </div>
                    </div>
                    ${progressHtml}
                    ${notesHtml}
                    ${documentsHtml}
                `;

                document.getElementById('taskDetailsContent').innerHTML = detailsHtml;
                document.getElementById('viewTaskModal').classList.add('active');
            })
            .catch(error => {
                console.error('Error fetching task:', error);
                alert('Error loading task details');
            });
        }

        // Close view task modal
        function closeViewTaskModal() {
            document.getElementById('viewTaskModal').classList.remove('active');
        }

        // Edit task from view modal
        function editTaskFromView() {
            closeViewTaskModal();
            // Here you would populate the new task modal with the task data for editing
            alert('Edit functionality will open the task form with pre-filled data');
        }

        // Mark task as complete
        function markTaskComplete(taskId) {
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
                    alert('Task marked as completed!');
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

        // Edit task
        function editTask(taskId) {
            alert('Edit task functionality - Task ID: ' + taskId);
            // TODO: Implement full edit functionality
        }

        // ===== DAILY HOURS FILTER AND SEARCH =====
        function filterDailyHours() {
            console.log('Filtering daily hours...');
            // In a real application, this would filter the table based on status and school
        }

        function searchDailyHours() {
            const searchInput = document.getElementById('dailySearchInput').value.toLowerCase();
            const tableBody = document.getElementById('dailyHoursTableBody');
            const rows = tableBody.getElementsByTagName('tr');

            for (let row of rows) {
                const nameCell = row.cells[0]?.textContent.toLowerCase();
                if (nameCell && nameCell.includes(searchInput)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        function resetDailyFilters() {
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('schoolFilter').value = 'all';
            document.getElementById('dailySearchInput').value = '';
            filterDailyHours();
            searchDailyHours();
        }

        // Time Records Filter and Search
        function filterTimeRecords() {
            console.log('Filtering time records...');
            // In a real application, this would filter the table based on status and date
        }

        function searchTimeRecords() {
            const searchInput = document.getElementById('timeSearchInput').value.toLowerCase();
            const tableBody = document.querySelector('#time-records tbody');
            const rows = tableBody.getElementsByTagName('tr');

            for (let row of rows) {
                const nameCell = row.cells[0]?.textContent.toLowerCase();
                if (nameCell && nameCell.includes(searchInput)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        function resetTimeFilters() {
            document.getElementById('timeStatusFilter').value = 'all';
            document.getElementById('dateFilter').value = '';
            document.getElementById('timeSearchInput').value = '';
            filterTimeRecords();
            searchTimeRecords();
        }

        // Hours Summary Filter and Search
        function filterHoursSummary() {
            console.log('Filtering hours summary...');
            // In a real application, this would filter the table based on status and completion
        }

        function searchHoursSummary() {
            const searchInput = document.getElementById('summarySearchInput').value.toLowerCase();
            const tableBody = document.querySelector('#hours-summary tbody');
            const rows = tableBody.getElementsByTagName('tr');

            for (let row of rows) {
                const nameCell = row.cells[0]?.textContent.toLowerCase();
                if (nameCell && nameCell.includes(searchInput)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        function resetSummaryFilters() {
            document.getElementById('summaryStatusFilter').value = 'all';
            document.getElementById('completionFilter').value = 'all';
            document.getElementById('summarySearchInput').value = '';
            filterHoursSummary();
            searchHoursSummary();
        }

        // Change page function
        function changePage(direction) {
            const totalEntries = parseInt(document.getElementById('totalEntries').textContent);
            const totalPages = Math.ceil(totalEntries / itemsPerPage);

            if (direction === 'next' && currentPage < totalPages) {
                currentPage++;
            } else if (direction === 'prev' && currentPage > 1) {
                currentPage--;
            }

            // Update pagination display
            const start = (currentPage - 1) * itemsPerPage + 1;
            const end = Math.min(currentPage * itemsPerPage, totalEntries);

            document.getElementById('showingStart').textContent = start;
            document.getElementById('showingEnd').textContent = end;

            // Enable/disable buttons
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === totalPages;

            // In a real application, this would load the data for the current page
            console.log(`Loading page ${currentPage}`);
        }

        // Time Records Pagination
        function changeTimePage(direction) {
            const totalEntries = parseInt(document.getElementById('timeTotalEntries').textContent);
            const totalPages = Math.ceil(totalEntries / itemsPerPage);

            if (direction === 'next' && currentTimePage < totalPages) {
                currentTimePage++;
            } else if (direction === 'prev' && currentTimePage > 1) {
                currentTimePage--;
            }

            const start = (currentTimePage - 1) * itemsPerPage + 1;
            const end = Math.min(currentTimePage * itemsPerPage, totalEntries);

            document.getElementById('timeShowingStart').textContent = start;
            document.getElementById('timeShowingEnd').textContent = end;

            document.getElementById('timePrevPage').disabled = currentTimePage === 1;
            document.getElementById('timeNextPage').disabled = currentTimePage === totalPages;

            console.log(`Loading time records page ${currentTimePage}`);
        }

        // Hours Summary Pagination
        function changeSummaryPage(direction) {
            const totalEntries = parseInt(document.getElementById('summaryTotalEntries').textContent);
            const totalPages = Math.ceil(totalEntries / itemsPerPage);

            if (direction === 'next' && currentSummaryPage < totalPages) {
                currentSummaryPage++;
            } else if (direction === 'prev' && currentSummaryPage > 1) {
                currentSummaryPage--;
            }

            const start = (currentSummaryPage - 1) * itemsPerPage + 1;
            const end = Math.min(currentSummaryPage * itemsPerPage, totalEntries);

            document.getElementById('summaryShowingStart').textContent = start;
            document.getElementById('summaryShowingEnd').textContent = end;

            document.getElementById('summaryPrevPage').disabled = currentSummaryPage === 1;
            document.getElementById('summaryNextPage').disabled = currentSummaryPage === totalPages;

            console.log(`Loading hours summary page ${currentSummaryPage}`);
        }

        // Task Assignment Pagination
        function changeTaskPage(direction) {
            const totalEntries = parseInt(document.getElementById('taskTotalEntries').textContent);
            const totalPages = Math.ceil(totalEntries / itemsPerPage);

            if (direction === 'next' && currentTaskPage < totalPages) {
                currentTaskPage++;
            } else if (direction === 'prev' && currentTaskPage > 1) {
                currentTaskPage--;
            }

            const start = (currentTaskPage - 1) * itemsPerPage + 1;
            const end = Math.min(currentTaskPage * itemsPerPage, totalEntries);

            document.getElementById('taskShowingStart').textContent = start;
            document.getElementById('taskShowingEnd').textContent = end;

            document.getElementById('taskPrevPage').disabled = currentTaskPage === 1;
            document.getElementById('taskNextPage').disabled = currentTaskPage === totalPages;

            console.log(`Loading task page ${currentTaskPage}`);
        }

        // ============================================
        // Chart Initialization (wrapped in IIFE for safety)
        // ============================================
        let barChart = null;
        let pieChart = null;

        (function initCharts() {
            // Bar Chart - Intern Progress (Smooth Modern Style)
            const barChartEl = document.getElementById('barChart');
            if (barChartEl) {
                const barCtx = barChartEl.getContext('2d');

                // Create gradients for bars
                const maroonGradient = barCtx.createLinearGradient(0, 0, 0, 400);
                maroonGradient.addColorStop(0, 'rgba(123, 29, 58, 0.9)');
                maroonGradient.addColorStop(1, 'rgba(123, 29, 58, 0.4)');

                const goldGradient = barCtx.createLinearGradient(0, 0, 0, 400);
                goldGradient.addColorStop(0, 'rgba(255, 191, 0, 0.9)');
                goldGradient.addColorStop(1, 'rgba(255, 191, 0, 0.4)');

                // Initialize charts with empty data first
                barChart = new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Active Interns',
                            data: [],
                            backgroundColor: maroonGradient,
                            borderColor: '#7B1D3A',
                            borderWidth: 0,
                            borderRadius: 8,
                            borderSkipped: false,
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
                        }, {
                            label: 'Completed Tasks',
                            data: [],
                            backgroundColor: goldGradient,
                            borderColor: '#FFBF00',
                            borderWidth: 0,
                            borderRadius: 8,
                            borderSkipped: false,
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        animation: {
                            duration: 1000,
                            easing: 'easeOutQuart'
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(31, 41, 55, 0.95)',
                                titleFont: { size: 13, weight: '600' },
                                bodyFont: { size: 12 },
                                padding: 12,
                                cornerRadius: 10,
                                displayColors: true,
                                boxPadding: 6
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: false
                                },
                                ticks: {
                                    font: { size: 11, weight: '500' },
                                    color: '#6B7280',
                                    padding: 8
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    font: { size: 11, weight: '500' },
                                    color: '#6B7280',
                                    padding: 8
                                }
                            }
                        }
                    }
                });
            }

            // Pie Chart - System Usage (Smooth Doughnut Style)
            const pieChartEl = document.getElementById('pieChart');
            if (pieChartEl) {
                const pieCtx = pieChartEl.getContext('2d');
                pieChart = new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Intern Management', 'Task Management', 'Digital Records', 'Incubatee Tracker', 'Scheduler'],
                        datasets: [{
                            data: [20, 20, 20, 20, 20],
                            backgroundColor: [
                                '#7B1D3A',
                                '#FFBF00',
                                '#F97316',
                                '#10B981',
                                '#3B82F6'
                            ],
                            hoverBackgroundColor: [
                                '#5a1428',
                                '#E5A800',
                                '#EA580C',
                                '#059669',
                                '#2563EB'
                            ],
                            borderWidth: 0,
                            spacing: 4,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        cutout: '65%',
                        animation: {
                            animateRotate: true,
                            animateScale: true,
                            duration: 1000,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    padding: 16,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: {
                                        size: 11,
                                        weight: '500'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(31, 41, 55, 0.95)',
                                titleFont: { size: 13, weight: '600' },
                                bodyFont: { size: 12 },
                                padding: 12,
                                cornerRadius: 10,
                                displayColors: true,
                                boxPadding: 6,
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.label + ': ' + context.parsed + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        })();

        // ============================================
        // Real-time Chart Data Functions
        // ============================================
        async function loadChartData() {
            try {
                const response = await fetch('/admin/chart-data');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();

                // Update Bar Chart with monthly data
                if (barChart && data.monthlyData && data.monthlyData.length > 0) {
                    barChart.data.labels = data.monthlyData.map(item => item.month);
                    barChart.data.datasets[0].data = data.monthlyData.map(item => item.activeInterns);
                    barChart.data.datasets[1].data = data.monthlyData.map(item => item.completedTasks);
                    barChart.update('none');
                }

                // Update Pie Chart with system usage
                if (pieChart && data.systemUsage) {
                    pieChart.data.datasets[0].data = [
                        data.systemUsage.internManagement,
                        data.systemUsage.taskManagement,
                        data.systemUsage.digitalRecords,
                        data.systemUsage.incubateeTracker,
                        data.systemUsage.scheduler
                    ];
                    pieChart.update('none');
                }

                console.log('Charts updated:', data.lastUpdated);
            } catch (error) {
                console.error('Error loading chart data:', error);
            }
        }

        // Load chart data on page load
        loadChartData();

        // Refresh chart data every 30 seconds
        setInterval(loadChartData, 30000);

        // ===== SCHOOL MANAGEMENT FUNCTIONS =====

        function openSchoolManagementModal() {
            document.getElementById('schoolManagementModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSchoolManagementModal() {
            document.getElementById('schoolManagementModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            cancelSchoolForm();
        }

        function openAddSchoolForm() {
            document.getElementById('schoolFormContainer').style.display = 'block';
            document.getElementById('schoolFormTitle').innerHTML = '<i class="fas fa-plus-circle" style="color: #10B981; margin-right: 8px;"></i>Add New School';
            document.getElementById('schoolFormSubmitText').textContent = 'Save School';
            document.getElementById('schoolFormId').value = '';
            document.getElementById('schoolForm').reset();
        }

        function cancelSchoolForm() {
            document.getElementById('schoolFormContainer').style.display = 'none';
            document.getElementById('schoolForm').reset();
            document.getElementById('schoolFormId').value = '';
        }

        function editSchool(id, name, hours, maxInterns, contactPerson, contactEmail, contactPhone, notes) {
            document.getElementById('schoolFormContainer').style.display = 'block';
            document.getElementById('schoolFormTitle').innerHTML = '<i class="fas fa-edit" style="color: #7B1D3A; margin-right: 8px;"></i>Edit School';
            document.getElementById('schoolFormSubmitText').textContent = 'Update School';
            document.getElementById('schoolFormId').value = id;
            document.getElementById('schoolFormName').value = name;
            document.getElementById('schoolFormHours').value = hours;
            document.getElementById('schoolFormMaxInterns').value = maxInterns || '';
            document.getElementById('schoolFormContactPerson').value = contactPerson;
            document.getElementById('schoolFormContactEmail').value = contactEmail;
            document.getElementById('schoolFormContactPhone').value = contactPhone;
            document.getElementById('schoolFormNotes').value = notes;
        }

        document.getElementById('schoolForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formId = document.getElementById('schoolFormId').value;
            const isEdit = formId !== '';
            const url = isEdit ? `/admin/schools/${formId}` : '/admin/schools';
            const method = isEdit ? 'PUT' : 'POST';

            const data = {
                name: document.getElementById('schoolFormName').value,
                required_hours: parseInt(document.getElementById('schoolFormHours').value),
                max_interns: document.getElementById('schoolFormMaxInterns').value ? parseInt(document.getElementById('schoolFormMaxInterns').value) : null,
                contact_person: document.getElementById('schoolFormContactPerson').value || null,
                contact_email: document.getElementById('schoolFormContactEmail').value || null,
                contact_phone: document.getElementById('schoolFormContactPhone').value || null,
                notes: document.getElementById('schoolFormNotes').value || null,
            };

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showToast('success', 'Success', result.message);
                    cancelSchoolForm();
                    // Reload page to refresh schools list
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('error', 'Error', result.message || 'An error occurred');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Error', 'An error occurred while saving the school');
            }
        });

        async function toggleSchoolStatus(schoolId) {
            try {
                const response = await fetch(`/admin/schools/${schoolId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showToast('success', 'Success', result.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('error', 'Error', result.message || 'An error occurred');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Error', 'An error occurred');
            }
        }

        async function deleteSchool(schoolId, schoolName) {
            if (!confirm(`Are you sure you want to delete "${schoolName}"? This action cannot be undone.`)) {
                return;
            }

            try {
                const response = await fetch(`/admin/schools/${schoolId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showToast('success', 'Success', result.message);
                    document.getElementById(`school-row-${schoolId}`).remove();
                } else {
                    showToast('error', 'Error', result.message || 'An error occurred');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Error', 'An error occurred while deleting the school');
            }
        }

        // ===== INTERN APPROVAL FUNCTIONS =====

        function togglePendingSchoolGroup(contentId) {
            const content = document.getElementById(contentId);
            const schoolId = contentId.replace('pending-school-content-', '');
            const icon = document.getElementById(`icon-pending-${schoolId}`);

            if (content.style.display === 'none') {
                content.style.display = 'block';
                if (icon) icon.style.transform = 'rotate(0deg)';
            } else {
                content.style.display = 'none';
                if (icon) icon.style.transform = 'rotate(-90deg)';
            }
        }

        async function approveAllBySchool(schoolId, schoolName) {
            if (!confirm(`Approve all pending interns from "${schoolName}"?`)) {
                return;
            }

            try {
                const response = await fetch(`/admin/interns/school/${schoolId}/approve-all`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showToast(result.message, 'success');
                    // Remove the entire school pending group
                    const schoolGroup = document.getElementById(`pending-school-${schoolId}`);
                    if (schoolGroup) {
                        schoolGroup.remove();
                    }
                    // Reload to update all counts
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(result.message || 'An error occurred', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred while approving interns', 'error');
            }
        }

        async function approveIntern(internId) {
            try {
                const response = await fetch(`/admin/interns/${internId}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showToast(result.message, 'success');
                    document.getElementById(`pending-intern-${internId}`).remove();
                    // Update pending count
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(result.message || 'An error occurred', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred while approving the intern', 'error');
            }
        }

        function openRejectInternModal(internId, internName) {
            document.getElementById('rejectInternId').value = internId;
            document.getElementById('rejectInternName').textContent = internName;
            document.getElementById('rejectInternReason').value = '';
            document.getElementById('rejectInternModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeRejectInternModal() {
            document.getElementById('rejectInternModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        async function submitRejectIntern() {
            const internId = document.getElementById('rejectInternId').value;
            const reason = document.getElementById('rejectInternReason').value;

            if (!reason.trim()) {
                showToast('Please provide a reason for rejection', 'error');
                return;
            }

            try {
                const response = await fetch(`/admin/interns/${internId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ rejection_reason: reason })
                });

                const result = await response.json();

                if (result.success) {
                    showToast(result.message, 'success');
                    closeRejectInternModal();
                    document.getElementById(`pending-intern-${internId}`).remove();
                    // Update pending count
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(result.message || 'An error occurred', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred while rejecting the intern', 'error');
            }
        }

        // ===== BULK OPERATIONS FUNCTIONS =====
        let selectedInterns = new Set();

        function updateBulkSelection() {
            const checkboxes = document.querySelectorAll('.pending-intern-checkbox');
            selectedInterns.clear();

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    selectedInterns.add({
                        id: cb.dataset.internId,
                        name: cb.dataset.internName,
                        schoolId: cb.dataset.schoolId
                    });
                }
            });

            updateBulkUI();
        }

        function updateBulkUI() {
            const bulkBar = document.getElementById('bulkActionsBar');
            const countText = document.getElementById('selectedCountText');
            const selectAllCheckbox = document.getElementById('selectAllPending');

            if (selectedInterns.size > 0) {
                bulkBar.style.display = 'flex';
                countText.textContent = `${selectedInterns.size} selected`;

                const totalCheckboxes = document.querySelectorAll('.pending-intern-checkbox').length;
                selectAllCheckbox.checked = selectedInterns.size === totalCheckboxes;
                selectAllCheckbox.indeterminate = selectedInterns.size > 0 && selectedInterns.size < totalCheckboxes;
            } else {
                bulkBar.style.display = 'none';
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }

            // Update school-level checkboxes
            document.querySelectorAll('.school-select-all').forEach(schoolCb => {
                const schoolId = schoolCb.dataset.school;
                const schoolCheckboxes = document.querySelectorAll(`.pending-intern-checkbox[data-school-id="${schoolId}"]`);
                const checkedCount = Array.from(schoolCheckboxes).filter(cb => cb.checked).length;

                schoolCb.checked = checkedCount === schoolCheckboxes.length && checkedCount > 0;
                schoolCb.indeterminate = checkedCount > 0 && checkedCount < schoolCheckboxes.length;
            });
        }

        function toggleSelectAllPending() {
            const selectAll = document.getElementById('selectAllPending');
            const checkboxes = document.querySelectorAll('.pending-intern-checkbox');

            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });

            updateBulkSelection();
        }

        function toggleSchoolSelection(schoolId) {
            const schoolCb = document.querySelector(`.school-select-all[data-school="${schoolId}"]`);
            const schoolCheckboxes = document.querySelectorAll(`.pending-intern-checkbox[data-school-id="${schoolId}"]`);

            schoolCheckboxes.forEach(cb => {
                cb.checked = schoolCb.checked;
            });

            updateBulkSelection();
        }

        function clearBulkSelection() {
            document.querySelectorAll('.pending-intern-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.school-select-all').forEach(cb => cb.checked = false);
            document.getElementById('selectAllPending').checked = false;
            selectedInterns.clear();
            updateBulkUI();
        }

        async function bulkApproveSelected() {
            if (selectedInterns.size === 0) {
                showToast('No interns selected', 'warning');
                return;
            }

            const internIds = Array.from(selectedInterns).map(i => i.id);
            const count = internIds.length;

            if (!confirm(`Are you sure you want to approve ${count} intern(s)?`)) {
                return;
            }

            showToast(`Approving ${count} intern(s)...`, 'info');

            let successCount = 0;
            let failCount = 0;

            for (const internId of internIds) {
                try {
                    const response = await fetch(`/admin/interns/${internId}/approve`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        successCount++;
                        const row = document.getElementById(`pending-intern-${internId}`);
                        if (row) row.remove();
                    } else {
                        failCount++;
                    }
                } catch (error) {
                    console.error('Error approving intern:', internId, error);
                    failCount++;
                }
            }

            clearBulkSelection();

            if (successCount > 0) {
                showToast(`Successfully approved ${successCount} intern(s)${failCount > 0 ? `, ${failCount} failed` : ''}`, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('Failed to approve interns', 'error');
            }
        }

        function bulkRejectSelected() {
            if (selectedInterns.size === 0) {
                showToast('No interns selected', 'warning');
                return;
            }

            const internList = Array.from(selectedInterns).map(i => `<div style="padding: 4px 0;"><i class="fas fa-user" style="color: #DC2626; margin-right: 8px;"></i>${i.name}</div>`).join('');

            document.getElementById('bulkRejectCount').textContent = selectedInterns.size;
            document.getElementById('bulkRejectList').innerHTML = internList;
            document.getElementById('bulkRejectReason').value = '';
            document.getElementById('bulkRejectModal').style.display = 'flex';
        }

        function closeBulkRejectModal() {
            document.getElementById('bulkRejectModal').style.display = 'none';
        }

        async function submitBulkReject() {
            const reason = document.getElementById('bulkRejectReason').value.trim();

            if (!reason) {
                showToast('Please provide a rejection reason', 'error');
                return;
            }

            const internIds = Array.from(selectedInterns).map(i => i.id);
            const count = internIds.length;

            showToast(`Rejecting ${count} intern(s)...`, 'info');

            let successCount = 0;
            let failCount = 0;

            for (const internId of internIds) {
                try {
                    const response = await fetch(`/admin/interns/${internId}/reject`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ rejection_reason: reason })
                    });

                    const result = await response.json();

                    if (result.success) {
                        successCount++;
                        const row = document.getElementById(`pending-intern-${internId}`);
                        if (row) row.remove();
                    } else {
                        failCount++;
                    }
                } catch (error) {
                    console.error('Error rejecting intern:', internId, error);
                    failCount++;
                }
            }

            closeBulkRejectModal();
            clearBulkSelection();

            if (successCount > 0) {
                showToast(`Successfully rejected ${successCount} intern(s)${failCount > 0 ? `, ${failCount} failed` : ''}`, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('Failed to reject interns', 'error');
            }
        }

        // ===== BULK TASK OPERATIONS =====
        let selectedTasks = new Set();

        function updateTaskSelection() {
            const checkboxes = document.querySelectorAll('.task-checkbox');
            selectedTasks.clear();

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    selectedTasks.add({
                        id: cb.dataset.taskId,
                        title: cb.dataset.taskTitle
                    });
                }
            });

            updateTaskBulkUI();
        }

        function updateTaskBulkUI() {
            const bulkBar = document.getElementById('bulkTaskActionsBar');
            const countText = document.getElementById('selectedTaskCountText');
            const selectAllCheckbox = document.getElementById('selectAllTasks');

            if (selectedTasks.size > 0) {
                bulkBar.style.display = 'flex';
                countText.textContent = `${selectedTasks.size} task(s) selected`;

                const totalCheckboxes = document.querySelectorAll('.task-checkbox').length;
                selectAllCheckbox.checked = selectedTasks.size === totalCheckboxes;
                selectAllCheckbox.indeterminate = selectedTasks.size > 0 && selectedTasks.size < totalCheckboxes;
            } else {
                bulkBar.style.display = 'none';
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }

        function toggleSelectAllTasks() {
            const selectAll = document.getElementById('selectAllTasks');
            const checkboxes = document.querySelectorAll('.task-checkbox');

            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });

            updateTaskSelection();
        }

        function clearTaskSelection() {
            document.querySelectorAll('.task-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('selectAllTasks').checked = false;
            selectedTasks.clear();
            updateTaskBulkUI();
        }

        async function bulkUpdateTaskStatus(newStatus) {
            if (selectedTasks.size === 0) {
                showToast('No tasks selected', 'warning');
                return;
            }

            const taskIds = Array.from(selectedTasks).map(t => t.id);
            const count = taskIds.length;

            if (!confirm(`Are you sure you want to mark ${count} task(s) as "${newStatus}"?`)) {
                return;
            }

            showToast(`Updating ${count} task(s)...`, 'info');

            let successCount = 0;
            let failCount = 0;

            for (const taskId of taskIds) {
                try {
                    const response = await fetch(`/admin/tasks/${taskId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: newStatus })
                    });

                    const result = await response.json();

                    if (result.success || response.ok) {
                        successCount++;
                    } else {
                        failCount++;
                    }
                } catch (error) {
                    console.error('Error updating task:', taskId, error);
                    failCount++;
                }
            }

            clearTaskSelection();

            if (successCount > 0) {
                showToast(`Successfully updated ${successCount} task(s)${failCount > 0 ? `, ${failCount} failed` : ''}`, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('Failed to update tasks', 'error');
            }
        }

        async function bulkDeleteTasks() {
            if (selectedTasks.size === 0) {
                showToast('No tasks selected', 'warning');
                return;
            }

            const taskIds = Array.from(selectedTasks).map(t => t.id);
            const count = taskIds.length;
            const taskNames = Array.from(selectedTasks).map(t => t.title).slice(0, 5).join(', ');

            if (!confirm(`Are you sure you want to delete ${count} task(s)?\n\nTasks: ${taskNames}${count > 5 ? '...' : ''}\n\nThis action cannot be undone.`)) {
                return;
            }

            showToast(`Deleting ${count} task(s)...`, 'info');

            let successCount = 0;
            let failCount = 0;

            for (const taskId of taskIds) {
                try {
                    const response = await fetch(`/admin/tasks/${taskId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (result.success || response.ok) {
                        successCount++;
                        const row = document.querySelector(`tr[data-task-id="${taskId}"]`);
                        if (row) row.remove();
                    } else {
                        failCount++;
                    }
                } catch (error) {
                    console.error('Error deleting task:', taskId, error);
                    failCount++;
                }
            }

            clearTaskSelection();

            if (successCount > 0) {
                showToast(`Successfully deleted ${successCount} task(s)${failCount > 0 ? `, ${failCount} failed` : ''}`, 'success');
                if (failCount === 0) {
                    // If all deleted successfully, no need to reload
                } else {
                    setTimeout(() => location.reload(), 1500);
                }
            } else {
                showToast('Failed to delete tasks', 'error');
            }
        }

        // View Intern Details
        async function viewInternDetails(internId) {
            try {
                const response = await fetch(`/admin/interns/${internId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch intern details');
                }

                const data = await response.json();
                const intern = data.intern;

                // Populate modal with intern data
                const avatarEl = document.getElementById('internDetailAvatar');
                const zoomOverlay = document.getElementById('zoomHoverOverlay');
                if (intern.profile_picture) {
                    const imageUrl = `/storage/${intern.profile_picture}`;
                    avatarEl.innerHTML = `<img src="${imageUrl}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">`;
                    avatarEl.appendChild(zoomOverlay);
                    avatarEl.setAttribute('data-image-url', imageUrl);
                    avatarEl.style.cursor = 'pointer';
                    // Show zoom icon on hover
                    avatarEl.onmouseenter = () => zoomOverlay.style.display = 'flex';
                    avatarEl.onmouseleave = () => zoomOverlay.style.display = 'none';
                } else {
                    avatarEl.textContent = intern.name.charAt(0).toUpperCase();
                    avatarEl.removeAttribute('data-image-url');
                    avatarEl.style.cursor = 'default';
                    avatarEl.onmouseenter = null;
                    avatarEl.onmouseleave = null;
                }
                document.getElementById('internDetailName').textContent = intern.name;
                document.getElementById('internDetailRefCode').textContent = intern.reference_code || 'N/A';
                document.getElementById('internDetailEmail').textContent = intern.email || '-';
                document.getElementById('internDetailPhone').textContent = intern.phone || '-';
                document.getElementById('internDetailSchool').textContent = intern.school || '-';
                document.getElementById('internDetailCourse').textContent = intern.course || '-';
                document.getElementById('internDetailYearLevel').textContent = intern.year_level || '-';
                document.getElementById('internDetailAddress').textContent = intern.address || '-';

                // Hours progress
                const completedHours = intern.completed_hours || 0;
                const requiredHours = intern.required_hours || 0;
                const progressPercent = requiredHours > 0 ? Math.round((completedHours / requiredHours) * 100) : 0;

                document.getElementById('internDetailCompletedHours').textContent = completedHours;
                document.getElementById('internDetailRequiredHours').textContent = requiredHours;
                document.getElementById('internDetailProgressPercent').textContent = progressPercent + '%';
                document.getElementById('internDetailProgress').style.width = progressPercent + '%';

                // Timeline
                document.getElementById('internDetailStartDate').textContent = intern.start_date
                    ? new Date(intern.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                    : '-';
                document.getElementById('internDetailEndDate').textContent = intern.end_date
                    ? new Date(intern.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                    : '-';
                document.getElementById('internDetailRegisteredOn').textContent = intern.created_at
                    ? new Date(intern.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                    : '-';

                // Status badge
                const statusBadge = document.getElementById('internDetailStatus');
                statusBadge.textContent = intern.status || 'Unknown';

                if (intern.status === 'Active') {
                    statusBadge.style.background = '#D1FAE5';
                    statusBadge.style.color = '#065F46';
                } else if (intern.status === 'Completed') {
                    statusBadge.style.background = '#DBEAFE';
                    statusBadge.style.color = '#1E40AF';
                } else {
                    statusBadge.style.background = '#FEE2E2';
                    statusBadge.style.color = '#991B1B';
                }

                // Show modal
                document.getElementById('internDetailsModal').classList.add('active');
                document.body.style.overflow = 'hidden';

            } catch (error) {
                console.error('Error fetching intern details:', error);
                showToast('error', 'Error', 'Failed to load intern details');
            }
        }

        function closeInternDetailsModal() {
            document.getElementById('internDetailsModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Edit Intern Functions
        async function editIntern(internId) {
            try {
                const response = await fetch(`/admin/interns/${internId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch intern details');
                }

                const data = await response.json();
                const intern = data.intern;

                // Populate form
                document.getElementById('editInternId').value = intern.id;
                document.getElementById('editInternName').value = intern.name || '';
                document.getElementById('editInternEmail').value = intern.email || '';
                document.getElementById('editInternPhone').value = intern.phone || '';
                document.getElementById('editInternCourse').value = intern.course || '';
                document.getElementById('editInternYearLevel').value = intern.year_level || '';
                document.getElementById('editInternRequiredHours').value = intern.required_hours || '';
                document.getElementById('editInternStartDate').value = intern.start_date ? intern.start_date.split('T')[0] : '';
                document.getElementById('editInternEndDate').value = intern.end_date ? intern.end_date.split('T')[0] : '';
                document.getElementById('editInternStatus').value = intern.status || 'Active';

                // Show modal
                document.getElementById('editInternModal').classList.add('active');
                document.body.style.overflow = 'hidden';

            } catch (error) {
                console.error('Error fetching intern details:', error);
                showToast('error', 'Error', 'Failed to load intern details');
            }
        }

        function closeEditInternModal() {
            document.getElementById('editInternModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        async function saveInternChanges() {
            const internId = document.getElementById('editInternId').value;
            const submitBtn = document.querySelector('#editInternModal .btn-modal.primary');
            const originalText = submitBtn.innerHTML;

            try {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                const response = await fetch(`/admin/interns/${internId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: document.getElementById('editInternName').value,
                        email: document.getElementById('editInternEmail').value,
                        phone: document.getElementById('editInternPhone').value,
                        course: document.getElementById('editInternCourse').value,
                        year_level: document.getElementById('editInternYearLevel').value,
                        required_hours: document.getElementById('editInternRequiredHours').value || null,
                        start_date: document.getElementById('editInternStartDate').value || null,
                        end_date: document.getElementById('editInternEndDate').value || null,
                        status: document.getElementById('editInternStatus').value
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showToast('success', 'Success', 'Intern details updated successfully');
                    closeEditInternModal();
                    location.reload();
                } else {
                    throw new Error(data.message || 'Failed to update intern');
                }
            } catch (error) {
                console.error('Error updating intern:', error);
                showToast('error', 'Error', error.message || 'Failed to update intern');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }

        // Events Management Functions
        function loadAdminEvents() {
            fetch('/intern/events', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                displayAdminEvents(data.events || []);
            })
            .catch(error => {
                console.error('Error loading events:', error);
                document.getElementById('eventsListContainer').innerHTML = `
                    <div style="text-align: center; padding: 60px 20px; color: #9CA3AF;">
                        <i class="fas fa-calendar" style="font-size: 48px; margin-bottom: 16px;"></i>
                        <p style="font-size: 16px;">No events found</p>
                    </div>
                `;
            });
        }

        function displayAdminEvents(events) {
            const container = document.getElementById('eventsListContainer');

            if (!events || events.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 60px 20px; color: #9CA3AF;">
                        <i class="fas fa-calendar" style="font-size: 48px; margin-bottom: 16px;"></i>
                        <p style="font-size: 16px;">No events created yet</p>
                        <p style="font-size: 14px; margin-top: 8px;">Click "Create Event" to add your first event</p>
                    </div>
                `;
                return;
            }

            let html = '';
            events.forEach(event => {
                const startDate = new Date(event.start_date);
                const endDate = new Date(event.end_date);

                // Format date range
                const startDateOnly = startDate.toISOString().split('T')[0];
                const endDateOnly = endDate.toISOString().split('T')[0];
                const isSameDay = startDateOnly === endDateOnly;

                let dateStr;
                if (isSameDay) {
                    dateStr = startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                } else {
                    const startFormatted = startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    const endFormatted = endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    dateStr = `${startFormatted} - ${endFormatted}`;
                }

                const timeStr = event.all_day ? 'All Day' : `${startDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })} - ${endDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })}`;

                html += `
                    <div style="background: white; border-radius: 12px; padding: 20px; border: 1px solid #E5E7EB; border-left: 4px solid ${event.color};">
                        <div style="display: flex; justify-content: space-between; align-items: start; gap: 16px;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                    <div style="width: 12px; height: 12px; background: ${event.color}; border-radius: 50%;"></div>
                                    <h3 style="font-size: 18px; font-weight: 600; color: #1F2937; margin: 0;">${escapeHtml(event.title)}</h3>
                                </div>
                                ${event.description ? `<p style="color: #6B7280; font-size: 14px; margin-bottom: 12px;">${escapeHtml(event.description)}</p>` : ''}
                                <div style="display: flex; flex-wrap: wrap; gap: 16px; font-size: 14px; color: #6B7280;">
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-calendar"></i>
                                        <span>${dateStr}</span>
                                    </div>
                                    ${!event.all_day ? `<div style="display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-clock"></i>
                                        <span>${timeStr}</span>
                                    </div>` : ''}
                                    ${event.location ? `<div style="display: flex; align-items: center; gap: 6px;"><i class="fas fa-map-marker-alt"></i><span>${escapeHtml(event.location)}</span></div>` : ''}
                                </div>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <button onclick="editEvent(${event.id})" style="padding: 8px 12px; background: #F3F4F6; border: none; border-radius: 6px; cursor: pointer; color: #374151; font-size: 14px; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button onclick="deleteEvent(${event.id})" style="padding: 8px 12px; background: #FEE2E2; border: none; border-radius: 6px; cursor: pointer; color: #DC2626; font-size: 14px; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function toggleAllDayEvent() {
            const isAllDay = document.getElementById('eventAllDay').checked;
            const startInput = document.getElementById('eventStartDate');
            const endInput = document.getElementById('eventEndDate');
            const startLabel = document.getElementById('startDateLabel');
            const endLabel = document.getElementById('endDateLabel');

            if (isAllDay) {
                // Switch to date-only inputs
                const startValue = startInput.value ? startInput.value.split('T')[0] : '';
                const endValue = endInput.value ? endInput.value.split('T')[0] : '';

                startInput.type = 'date';
                endInput.type = 'date';
                startInput.value = startValue;
                endInput.value = endValue;

                startLabel.textContent = 'Start Date *';
                endLabel.textContent = 'End Date *';
            } else {
                // Switch to datetime inputs
                const startValue = startInput.value ? startInput.value + 'T09:00' : '';
                const endValue = endInput.value ? endInput.value + 'T17:00' : '';

                startInput.type = 'datetime-local';
                endInput.type = 'datetime-local';
                startInput.value = startValue;
                endInput.value = endValue;

                startLabel.textContent = 'Start Date & Time *';
                endLabel.textContent = 'End Date & Time *';
            }
        }

        function showCreateEventModal() {
            document.getElementById('eventId').value = '';
            document.getElementById('eventModalTitle').textContent = 'Create Event';
            document.getElementById('eventTitle').value = '';
            document.getElementById('eventDeleteBtn').style.display = 'none';
            document.getElementById('eventDescription').value = '';
            document.getElementById('eventStartDate').value = '';
            document.getElementById('eventEndDate').value = '';
            document.getElementById('eventLocation').value = '';
            document.getElementById('eventColor').value = '#3B82F6';
            document.getElementById('eventAllDay').checked = false;

            // Reset to datetime-local inputs
            document.getElementById('eventStartDate').type = 'datetime-local';
            document.getElementById('eventEndDate').type = 'datetime-local';
            document.getElementById('startDateLabel').textContent = 'Start Date & Time *';
            document.getElementById('endDateLabel').textContent = 'End Date & Time *';

            document.getElementById('eventModal').style.display = 'flex';
        }

        function closeEventModal() {
            document.getElementById('eventModal').style.display = 'none';
        }

        async function editEvent(eventId) {
            try {
                const response = await fetch(`/intern/events`);
                const data = await response.json();
                const event = data.events.find(e => e.id === eventId);

                if (event) {
                    document.getElementById('eventId').value = event.id;
                    document.getElementById('eventModalTitle').textContent = 'Edit Event';
                    document.getElementById('eventTitle').value = event.title;
                    document.getElementById('eventDeleteBtn').style.display = 'flex';
                    document.getElementById('eventDescription').value = event.description || '';
                    document.getElementById('eventLocation').value = event.location || '';
                    document.getElementById('eventColor').value = event.color;
                    document.getElementById('eventAllDay').checked = event.all_day;

                    // Set input type and values based on all_day
                    if (event.all_day) {
                        document.getElementById('eventStartDate').type = 'date';
                        document.getElementById('eventEndDate').type = 'date';
                        document.getElementById('eventStartDate').value = event.start_date.split(' ')[0];
                        document.getElementById('eventEndDate').value = event.end_date.split(' ')[0];
                        document.getElementById('startDateLabel').textContent = 'Start Date *';
                        document.getElementById('endDateLabel').textContent = 'End Date *';
                    } else {
                        document.getElementById('eventStartDate').type = 'datetime-local';
                        document.getElementById('eventEndDate').type = 'datetime-local';
                        document.getElementById('eventStartDate').value = new Date(event.start_date).toISOString().slice(0, 16);
                        document.getElementById('eventEndDate').value = new Date(event.end_date).toISOString().slice(0, 16);
                        document.getElementById('startDateLabel').textContent = 'Start Date & Time *';
                        document.getElementById('endDateLabel').textContent = 'End Date & Time *';
                    }

                    document.getElementById('eventModal').style.display = 'flex';
                }
            } catch (error) {
                console.error('Error loading event:', error);
                showToast('error', 'Error', 'Failed to load event details');
            }
        }

        async function saveEvent() {
            const eventId = document.getElementById('eventId').value;
            const title = document.getElementById('eventTitle').value.trim();
            const description = document.getElementById('eventDescription').value.trim();
            const startDate = document.getElementById('eventStartDate').value;
            const endDate = document.getElementById('eventEndDate').value;
            const location = document.getElementById('eventLocation').value.trim();
            const color = document.getElementById('eventColor').value;
            const allDay = document.getElementById('eventAllDay').checked;

            if (!title || !startDate || !endDate) {
                showToast('error', 'Validation Error', 'Please fill in all required fields');
                return;
            }

            const data = {
                title,
                description,
                start_date: startDate,
                end_date: endDate,
                location,
                color,
                all_day: allDay
            };

            try {
                const url = eventId ? `/admin/events/${eventId}` : '/admin/events';
                const method = eventId ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    showToast('success', 'Success', result.message);
                    closeEventModal();
                    loadAdminEvents();
                    loadSchedulerEvents();
                } else {
                    const errors = result.errors;
                    if (errors) {
                        const firstError = Object.values(errors)[0][0];
                        showToast('error', 'Validation Error', firstError);
                    } else {
                        showToast('error', 'Error', 'Error saving event');
                    }
                }
            } catch (error) {
                console.error('Error saving event:', error);
                showToast('error', 'Error', 'An error occurred while saving the event');
            }
        }

        async function deleteEvent(eventId) {
            if (!confirm('Are you sure you want to delete this event?')) {
                return;
            }

            try {
                const response = await fetch(`/admin/events/${eventId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    showToast('success', 'Success', result.message);
                    loadAdminEvents();
                    loadSchedulerEvents();
                } else {
                    showToast('error', 'Error', 'Error deleting event');
                }
            } catch (error) {
                console.error('Error deleting event:', error);
                showToast('error', 'Error', 'An error occurred while deleting the event');
            }
        }

        async function deleteEventFromModal() {
            const eventId = document.getElementById('eventId').value;
            if (!eventId) return;

            if (!confirm('Are you sure you want to delete this event?')) {
                return;
            }

            try {
                const response = await fetch(`/admin/events/${eventId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    showToast('success', 'Success', result.message);
                    closeEventModal();
                    loadAdminEvents();
                    loadSchedulerEvents();
                } else {
                    showToast('error', 'Error', 'Error deleting event');
                }
            } catch (error) {
                console.error('Error deleting event:', error);
                showToast('error', 'Error', 'An error occurred while deleting the event');
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Profile Picture Zoom Functions
        function zoomProfilePicture() {
            const avatarEl = document.getElementById('internDetailAvatar');
            const imageUrl = avatarEl.getAttribute('data-image-url');

            if (imageUrl) {
                document.getElementById('zoomedProfileImage').src = imageUrl;
                const modal = document.getElementById('profilePictureZoomModal');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeProfileZoom() {
            const modal = document.getElementById('profilePictureZoomModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close zoom modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const zoomModal = document.getElementById('profilePictureZoomModal');
                if (zoomModal.style.display === 'flex') {
                    closeProfileZoom();
                }
            }
        });
    </script>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Create Folder Modal -->
    <!-- Upload File Modal -->
    <div id="uploadFileModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; overflow-y: auto; padding: 20px;">
        <div style="background: white; border-radius: 16px; width: 90%; max-width: 500px; padding: 30px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); margin: auto; max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 22px; font-weight: 700; color: #1F2937; margin: 0;">Upload Files</h3>
                <button onclick="closeUploadFileModal()" style="background: none; border: none; font-size: 24px; color: #6B7280; cursor: pointer; padding: 0; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Select Files</label>
                <div style="border: 2px dashed #E5E7EB; border-radius: 12px; padding: 30px; text-align: center; cursor: pointer; transition: all 0.3s;" onclick="document.getElementById('adminFileUpload').click()" onmouseover="this.style.borderColor='#7B1D3A'; this.style.background='#FDF2F4'" onmouseout="this.style.borderColor='#E5E7EB'; this.style.background='transparent'">
                    <i class="fas fa-cloud-upload-alt" style="font-size: 40px; color: #7B1D3A; margin-bottom: 12px;"></i>
                    <p style="font-weight: 600; color: #1F2937; margin-bottom: 4px;">Click to select files</p>
                    <p style="font-size: 12px; color: #6B7280;">or drag and drop files here</p>
                    <p style="font-size: 11px; color: #9CA3AF; margin-top: 8px;">Max 50 MB per file • PDF, DOC, XLS, PPT, Images, ZIP</p>
                </div>
                <input type="file" id="adminFileUpload" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.ppt,.pptx,.csv" style="display: none;" onchange="handleAdminFileSelect(this)">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Selected Files</label>
                <div id="uploadFileList" style="max-height: 200px; overflow-y: auto; padding: 4px;">
                    <p style="color: #9CA3AF; text-align: center;">No files selected</p>
                </div>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="closeUploadFileModal()" style="padding: 12px 24px; border: 2px solid #E5E7EB; background: white; color: #6B7280; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                    Cancel
                </button>
                <button type="button" onclick="submitUploadFiles()" style="padding: 12px 24px; background: linear-gradient(135deg, #7B1D3A, #5a1428); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 12px rgba(123, 29, 58, 0.3);">
                    <i class="fas fa-upload"></i> Upload Files
                </button>
            </div>
        </div>
    </div>

    <div id="createFolderModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; overflow-y: auto; padding: 20px;">
        <div style="background: white; border-radius: 16px; width: 90%; max-width: 500px; padding: 30px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); margin: auto; max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 22px; font-weight: 700; color: #1F2937; margin: 0;">Create Shared Folder</h3>
                <button onclick="closeCreateFolderModal()" style="background: none; border: none; font-size: 24px; color: #6B7280; cursor: pointer; padding: 0; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="createFolderForm" onsubmit="submitCreateFolder(event)">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Folder Name <span style="color: #EF4444;">*</span></label>
                    <input type="text" id="folderName" required style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; transition: border 0.3s;" placeholder="e.g., Internship Reports" onfocus="this.style.borderColor='#7B1D3A'" onblur="this.style.borderColor='#E5E7EB'">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Folder Color</label>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <button type="button" class="color-option" data-color="#3B82F6" style="width: 40px; height: 40px; border-radius: 8px; background: #3B82F6; border: 3px solid #3B82F6; cursor: pointer;" onclick="selectColor(this)"></button>
                        <button type="button" class="color-option" data-color="#FFBF00" style="width: 40px; height: 40px; border-radius: 8px; background: #FFBF00; border: 3px solid transparent; cursor: pointer;" onclick="selectColor(this)"></button>
                        <button type="button" class="color-option" data-color="#10B981" style="width: 40px; height: 40px; border-radius: 8px; background: #10B981; border: 3px solid transparent; cursor: pointer;" onclick="selectColor(this)"></button>
                        <button type="button" class="color-option" data-color="#EF4444" style="width: 40px; height: 40px; border-radius: 8px; background: #EF4444; border: 3px solid transparent; cursor: pointer;" onclick="selectColor(this)"></button>
                        <button type="button" class="color-option" data-color="#8B5CF6" style="width: 40px; height: 40px; border-radius: 8px; background: #8B5CF6; border: 3px solid transparent; cursor: pointer;" onclick="selectColor(this)"></button>
                        <button type="button" class="color-option" data-color="#F59E0B" style="width: 40px; height: 40px; border-radius: 8px; background: #F59E0B; border: 3px solid transparent; cursor: pointer;" onclick="selectColor(this)"></button>
                    </div>
                    <input type="hidden" id="selectedColor" value="#3B82F6">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Who can upload? <span style="color: #EF4444;">*</span></label>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px; border: 2px solid #E5E7EB; border-radius: 8px; transition: all 0.3s;" onmouseover="this.style.borderColor='#7B1D3A'" onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='#E5E7EB'">
                            <input type="checkbox" name="allowed_users" value="intern" style="width: 18px; height: 18px; cursor: pointer;">
                            <div>
                                <div style="font-weight: 600; color: #1F2937;">Interns</div>
                                <div style="font-size: 12px; color: #6B7280;">Allow interns to upload files</div>
                            </div>
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px; border: 2px solid #E5E7EB; border-radius: 8px; transition: all 0.3s;" onmouseover="this.style.borderColor='#7B1D3A'" onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='#E5E7EB'">
                            <input type="checkbox" name="allowed_users" value="team_leader" style="width: 18px; height: 18px; cursor: pointer;">
                            <div>
                                <div style="font-weight: 600; color: #1F2937;">Team Leaders</div>
                                <div style="font-size: 12px; color: #6B7280;">Allow team leaders to upload files</div>
                            </div>
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px; border: 2px solid #E5E7EB; border-radius: 8px; transition: all 0.3s;" onmouseover="this.style.borderColor='#7B1D3A'" onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='#E5E7EB'">
                            <input type="checkbox" name="allowed_users" value="startup" style="width: 18px; height: 18px; cursor: pointer;">
                            <div>
                                <div style="font-weight: 600; color: #1F2937;">Startups</div>
                                <div style="font-size: 12px; color: #6B7280;">Allow startups to upload files</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Folder Storage Limit (MB)</label>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <input type="number" id="folderSizeLimit" min="1" max="5000" value="100" style="width: 120px; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; transition: border 0.3s;" onfocus="this.style.borderColor='#7B1D3A'" onblur="this.style.borderColor='#E5E7EB'">
                        <span style="font-size: 14px; color: #6B7280;">MB total</span>
                    </div>
                    <div style="margin-top: 8px; display: flex; gap: 8px; flex-wrap: wrap;">
                        <button type="button" onclick="document.getElementById('folderSizeLimit').value=50" style="padding: 6px 12px; background: #F3F4F6; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 12px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#E5E7EB'" onmouseout="this.style.background='#F3F4F6'">50 MB</button>
                        <button type="button" onclick="document.getElementById('folderSizeLimit').value=100" style="padding: 6px 12px; background: #F3F4F6; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 12px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#E5E7EB'" onmouseout="this.style.background='#F3F4F6'">100 MB</button>
                        <button type="button" onclick="document.getElementById('folderSizeLimit').value=250" style="padding: 6px 12px; background: #F3F4F6; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 12px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#E5E7EB'" onmouseout="this.style.background='#F3F4F6'">250 MB</button>
                        <button type="button" onclick="document.getElementById('folderSizeLimit').value=500" style="padding: 6px 12px; background: #F3F4F6; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 12px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#E5E7EB'" onmouseout="this.style.background='#F3F4F6'">500 MB</button>
                        <button type="button" onclick="document.getElementById('folderSizeLimit').value=1000" style="padding: 6px 12px; background: #F3F4F6; border: 1px solid #E5E7EB; border-radius: 6px; font-size: 12px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#E5E7EB'" onmouseout="this.style.background='#F3F4F6'">1 GB</button>
                    </div>
                    <p style="font-size: 12px; color: #9CA3AF; margin-top: 6px;"><i class="fas fa-info-circle"></i> Total storage limit for this folder. Uploads will be blocked when limit is reached.</p>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Description (Optional)</label>
                    <textarea id="folderDescription" rows="3" style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; resize: vertical; transition: border 0.3s;" placeholder="Brief description of this folder's purpose..." onfocus="this.style.borderColor='#7B1D3A'" onblur="this.style.borderColor='#E5E7EB'"></textarea>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeCreateFolderModal()" style="padding: 12px 24px; border: 2px solid #E5E7EB; background: white; color: #6B7280; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                        Cancel
                    </button>
                    <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #7B1D3A, #5a1428); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 12px rgba(123, 29, 58, 0.3);">
                        <i class="fas fa-folder-plus"></i> Create Folder
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="confirm-modal-overlay">
        <div class="confirm-modal">
            <div class="confirm-modal-header">
                <div id="confirmModalIcon" class="confirm-modal-icon warning">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 id="confirmModalTitle" class="confirm-modal-title">Are you sure?</h3>
            </div>
            <div class="confirm-modal-body">
                <p id="confirmModalMessage" class="confirm-modal-message">This action cannot be undone.</p>
            </div>
            <div class="confirm-modal-footer">
                <button class="confirm-modal-btn cancel" onclick="closeConfirmModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button id="confirmModalBtn" class="confirm-modal-btn confirm" onclick="executeConfirmAction()">
                    <i class="fas fa-check"></i> Confirm
                </button>
            </div>
        </div>
    </div>

    <!-- Profile Picture Zoom Modal -->
    <div id="profilePictureZoomModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 10000; align-items: center; justify-content: center;" onclick="closeProfileZoom()">
        <div style="position: relative; max-width: 90%; max-height: 90vh; display: flex; align-items: center; justify-content: center;">
            <button onclick="closeProfileZoom()" style="position: absolute; top: -40px; right: 0; background: white; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                <i class="fas fa-times"></i>
            </button>
            <img id="zoomedProfileImage" src="" alt="Profile Picture" style="max-width: 100%; max-height: 90vh; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.5);" onclick="event.stopPropagation()">
        </div>
    </div>

    <!-- File Preview Modal -->
    <div id="filePreviewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 10001; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: white; border-radius: 16px; width: 100%; max-width: 1000px; max-height: 90vh; overflow: hidden; box-shadow: 0 25px 80px rgba(0,0,0,0.5); display: flex; flex-direction: column;">
            <!-- Header -->
            <div style="background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i id="filePreviewIcon" class="fas fa-file" style="font-size: 24px;"></i>
                    <div>
                        <h3 id="filePreviewName" style="margin: 0; font-size: 16px; font-weight: 600;">File Preview</h3>
                        <p id="filePreviewInfo" style="margin: 0; font-size: 12px; opacity: 0.8;"></p>
                    </div>
                </div>
                <button onclick="closeFilePreview()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i class="fas fa-times" style="font-size: 16px;"></i>
                </button>
            </div>
            <!-- Preview Content -->
            <div id="filePreviewContent" style="flex: 1; overflow: auto; background: #F3F4F6; display: flex; align-items: center; justify-content: center; min-height: 400px;">
                <div style="text-align: center; color: #6B7280;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 40px; margin-bottom: 12px;"></i>
                    <p>Loading preview...</p>
                </div>
            </div>
            <!-- Footer -->
            <div style="padding: 16px 24px; background: white; border-top: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
                <div id="filePreviewMeta" style="font-size: 13px; color: #6B7280;">
                    <!-- File metadata will be shown here -->
                </div>
                <div style="display: flex; gap: 10px;">
                    <button id="filePreviewDownloadBtn" onclick="downloadCurrentFile()" style="padding: 10px 20px; background: linear-gradient(135deg, #10B981, #059669); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-download"></i> Download
                    </button>
                    <button onclick="closeFilePreview()" style="padding: 10px 20px; background: #F3F4F6; color: #4B5563; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Reject Modal -->
    <div id="bulkRejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10001; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 25px 50px rgba(0,0,0,0.25);">
            <div style="background: linear-gradient(135deg, #EF4444, #DC2626); color: white; padding: 20px 24px; border-radius: 16px 16px 0 0;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-times-circle"></i> Bulk Reject Interns
                </h3>
            </div>
            <div style="padding: 24px;">
                <p style="color: #374151; margin-bottom: 16px;">You are about to reject <strong id="bulkRejectCount">0</strong> intern(s). Please provide a reason for rejection:</p>
                <div id="bulkRejectList" style="max-height: 150px; overflow-y: auto; margin-bottom: 16px; padding: 12px; background: #F9FAFB; border-radius: 8px; font-size: 13px;"></div>
                <textarea id="bulkRejectReason" rows="3" placeholder="Enter rejection reason..." style="width: 100%; padding: 12px; border: 2px solid #E5E7EB; border-radius: 8px; font-size: 14px; resize: vertical;" onfocus="this.style.borderColor='#DC2626'" onblur="this.style.borderColor='#E5E7EB'"></textarea>
            </div>
            <div style="padding: 16px 24px; background: #F9FAFB; border-top: 1px solid #E5E7EB; display: flex; justify-content: flex-end; gap: 10px; border-radius: 0 0 16px 16px;">
                <button onclick="closeBulkRejectModal()" style="padding: 10px 20px; background: white; color: #6B7280; border: 1px solid #E5E7EB; border-radius: 8px; font-weight: 600; cursor: pointer;">Cancel</button>
                <button onclick="submitBulkReject()" style="padding: 10px 20px; background: linear-gradient(135deg, #EF4444, #DC2626); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-times"></i> Reject All
                </button>
            </div>
        </div>
    </div>
</body>
</html>
