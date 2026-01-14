<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - UP Cebu Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #F9FAFB;
        }

        .sidebar {
            background: linear-gradient(180deg, #7B1D3A 0%, #5a1428 100%);
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
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-logo {
            padding: 24px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .sidebar-logo img {
            height: 48px;
            width: auto;
            margin: 0 auto 12px auto;
            display: block;
        }

        .sidebar-logo h3 {
            color: white;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
            text-align: center;
            line-height: 1.3;
        }

        .sidebar-logo p {
            color: #FFBF00;
            font-size: 12px;
            text-align: center;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
        }

        .menu-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 18px;
        }

        .menu-item:hover {
            background: rgba(255, 191, 0, 0.1);
        }

        .menu-item.active {
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            color: #7B1D3A;
            font-weight: 600;
            border-left: 4px solid #7B1D3A;
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
            transition: max-height 0.3s ease;
            background: rgba(0, 0, 0, 0.2);
        }

        .submenu.open {
            max-height: 400px;
        }

        .submenu-item {
            display: flex;
            align-items: center;
            padding: 12px 24px 12px 56px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 13px;
            font-weight: 400;
        }

        .submenu-item:hover {
            background: rgba(255, 191, 0, 0.15);
            color: #FFBF00;
        }

        .submenu-item i {
            width: 16px;
            margin-right: 10px;
            font-size: 12px;
        }

        .submenu-item {
            position: relative;
        }

        .page-content {
            display: none;
        }

        .page-content.active {
            display: block;
        }

        .filter-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
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
            margin-bottom: 32px;
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
        }

        .filter-group {
            display: flex;
            gap: 12px;
            align-items: center;
            flex: 1;
            min-width: 200px;
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
            flex: 2;
            min-width: 250px;
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
        }

        .modal-header {
            padding: 24px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 24px;
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
            padding: 24px;
        }

        .form-group {
            margin-bottom: 20px;
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
            padding: 20px 24px;
            border-top: 1px solid #E5E7EB;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
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
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            padding: 20px 0;
            overflow-x: auto;
        }

        .kanban-column {
            background: #F9FAFB;
            border-radius: 12px;
            padding: 16px;
            min-height: 500px;
        }

        .kanban-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #E5E7EB;
        }

        .kanban-header h4 {
            font-size: 15px;
            font-weight: 700;
            color: #1F2937;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .kanban-count {
            background: #7B1D3A;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
        }

        .kanban-cards {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .kanban-card {
            background: white;
            border-radius: 10px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 4px solid #7B1D3A;
        }

        .kanban-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .kanban-card.success {
            border-left-color: #10B981;
            background: linear-gradient(135deg, #DCFCE7 0%, #ffffff 100%);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
            gap: 8px;
        }

        .card-header h5 {
            font-size: 14px;
            font-weight: 700;
            color: #1F2937;
            margin: 0;
            flex: 1;
        }

        .card-description {
            font-size: 12px;
            color: #6B7280;
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .card-meta {
            display: flex;
            gap: 12px;
            font-size: 11px;
            color: #6B7280;
            margin-bottom: 12px;
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
            background: #FEE2E2;
            color: #991B1B;
        }

        .priority-badge.priority-high {
            background: #FED7AA;
            color: #9A3412;
        }

        .priority-badge.priority-medium {
            background: #FEF3C7;
            color: #92400E;
        }

        .priority-badge.priority-low {
            background: #DBEAFE;
            color: #1E40AF;
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

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        .top-header {
            background: white;
            padding: 16px 32px;
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

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 16px 10px 40px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            width: 300px;
            background: #F9FAFB;
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
        }

        .notification-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #F9FAFB;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-btn:hover {
            background: #E5E7EB;
        }

        .notification-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 12px;
            height: 12px;
            background: #EF4444;
            border-radius: 50%;
            border: 2px solid white;
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

        .dashboard-content {
            padding: 32px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 24px;
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
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #E5E7EB;
        }

        .full-calendar {
            width: 100%;
        }

        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            margin-bottom: 1px;
            background: #E5E7EB;
            border-radius: 4px 4px 0 0;
        }

        .weekday {
            background: #F9FAFB;
            padding: 12px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            color: #6B7280;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #E5E7EB;
            border-radius: 0 0 4px 4px;
        }

        .calendar-day {
            background: white;
            min-height: 100px;
            padding: 8px;
            position: relative;
            cursor: pointer;
            transition: all 0.2s;
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
            border: 2px solid #FFBF00;
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
        }

        .calendar-event:hover {
            transform: translateX(2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
        }

        .calendar-event .event-name {
            color: #1F2937;
            font-weight: 500;
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

        @media (max-width: 968px) {
            .sidebar {
                transform: translateX(-100%);
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
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="images/UP logo.png" alt="UP Logo">
            <h3>University of the Philippines Cebu</h3>
            <p>InIT Admin Panel</p>
        </div>

        <nav class="sidebar-menu">
            <a href="#" class="menu-item active">
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
                    
                </div>
            </div>
            <a href="#" class="menu-item" onclick="loadPage(event, 'research-tracking')">
                <i class="fas fa-flask"></i>
                <span>Research Tracking</span>
            </a>
            <a href="#" class="menu-item" onclick="loadPage(event, 'incubatee-tracker')">
                <i class="fas fa-rocket"></i>
                <span>Incubatee Tracker</span>
            </a>
            <a href="#" class="menu-item" onclick="loadPage(event, 'issues-management')">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Issues & Complaints</span>
            </a>
            <a href="#" class="menu-item" onclick="loadPage(event, 'digital-records')">
                <i class="fas fa-file-alt"></i>
                <span>Digital Records</span>
            </a>
            
            <a href="#" class="menu-item" onclick="loadPage(event, 'scheduler')">
                <i class="fas fa-calendar-alt"></i>
                <span>Scheduler</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-user"></i>
                <span>Profile</span>
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
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search anything...">
                </div>
                <div class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge"></span>
                </div>
                <div class="user-profile">
                    <div class="user-avatar" id="userAvatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <div>
                        <div style="font-size: 14px; font-weight: 600; color: #1F2937;" id="userName">{{ Auth::user()->name }}</div>
                        <div style="font-size: 12px; color: #6B7280;" id="userRole">Administrator</div>
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
                    <div class="stat-value">245</div>
                    <div class="stat-label">Active Interns</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> +12.5% from last month
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-value">89</div>
                    <div class="stat-label">Research Projects</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> +5.3% from last month
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div class="stat-value">1,156</div>
                    <div class="stat-label">Digital Records</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> +8.1% from last month
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div class="stat-value">34</div>
                    <div class="stat-label">Incubatees</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> +3.2% from last month
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
                    <a href="#" class="view-all-btn">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>System</th>
                            <th>Action</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700;">K</div>
                                    <span style="font-weight: 600;">Kingsley Laran</span>
                                </div>
                            </td>
                            <td>Intern Management</td>
                            <td>Profile Updated</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td>Dec 15, 2:30 PM</td>
                        </tr>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700;">J</div>
                                    <span style="font-weight: 600;">Julliana Laurena</span>
                                </div>
                            </td>
                            <td>Research Tracking</td>
                            <td>Project Submitted</td>
                            <td><span class="status-badge status-completed">Completed</span></td>
                            <td>Dec 15, 1:15 PM</td>
                        </tr>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700;">R</div>
                                    <span style="font-weight: 600;">Ruther Marte</span>
                                </div>
                            </td>
                            <td>Digital Records</td>
                            <td>Document Viewed</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td>Dec 14, 5:45 PM</td>
                        </tr>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700;">M</div>
                                    <span style="font-weight: 600;">Mj Bersabal</span>
                                </div>
                            </td>
                            <td>Scheduler</td>
                            <td>Event Created</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td>Dec 14, 3:20 PM</td>
                        </tr>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700;">B</div>
                                    <span style="font-weight: 600;">Brejean Abarico</span>
                                </div>
                            </td>
                            <td>Incubatee Tracker</td>
                            <td>Milestone Updated</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td>Dec 14, 11:00 AM</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
            </div>

            <!-- Intern List Page -->
            <div id="intern-list" class="page-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Intern List Management</h2>
                    <p style="color: #6B7280; font-size: 14px;">View all registered interns with their progress and hours</p>
                </div>

                <!-- Stats Cards -->
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px;">
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
                </div>

                <!-- School-Grouped Intern List -->
                @php
                    // Use internsBySchool passed from controller, or create from interns if not available
                    $internsBySchool = $internsBySchool ?? ($interns ?? collect())->groupBy('school');
                @endphp

                @if($internsBySchool->count() > 0)
                    @foreach($internsBySchool as $school => $schoolInterns)
                    <div class="school-group" style="margin-bottom: 16px;">
                        <div class="school-header" onclick="toggleSchoolGroup('school-{{ Str::slug($school) }}')" style="background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; padding: 16px 20px; border-radius: 12px 12px 0 0; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease;">
                            <h4 style="margin: 0; font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 12px;">
                                <i class="fas fa-university"></i>
                                {{ $school }}
                            </h4>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span style="background: rgba(255,191,0,0.9); color: #7B1D3A; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;">{{ $schoolInterns->count() }} {{ $schoolInterns->count() == 1 ? 'Intern' : 'Interns' }}</span>
                                <i class="fas fa-chevron-down school-toggle-icon" id="icon-school-{{ Str::slug($school) }}" style="transition: transform 0.3s ease;"></i>
                            </div>
                        </div>
                        <div class="table-card" id="school-{{ Str::slug($school) }}" style="border-radius: 0 0 12px 12px; display: block; margin-top: 0;">
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
                                                <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700;">
                                                    {{ strtoupper(substr($intern->name, 0, 1)) }}
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
                                                <button class="btn-action btn-view" title="View"><i class="fas fa-eye"></i></button>
                                                <button class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="table-card" style="text-align: center; padding: 60px 40px; color: #9CA3AF;">
                        <i class="fas fa-users" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
                        <h3 style="color: #6B7280; margin-bottom: 8px;">No Interns Registered Yet</h3>
                        <p>Interns will appear here grouped by school once they register through the Intern Portal.</p>
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
                            <select class="filter-select" id="statusFilter">
                                <option value="all">All Status</option>
                                <option value="Present">Present</option>
                                <option value="Late">Late</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <span class="filter-label">School:</span>
                            <select class="filter-select" id="schoolFilter">
                                <option value="all">All Schools</option>
                                @foreach(($interns ?? collect())->pluck('school')->unique() as $school)
                                <option value="{{ $school }}">{{ $school }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-search">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search by intern name..." id="dailySearchInput">
                        </div>
                    </div>

                    <div class="table-card">
                        <div class="table-header">
                            <h3 class="table-title">Today's Attendance - {{ now()->timezone('Asia/Manila')->format('F d, Y') }}</h3>
                            <div style="display: flex; gap: 8px;">
                                <span style="background: #D1FAE5; color: #065F46; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-user-check"></i> {{ $todayAttendances->count() ?? 0 }} Present
                                </span>
                            </div>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Intern Name</th>
                                    <th>School</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Hours Today</th>
                                    <th>Over/Under</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="dailyHoursTableBody">
                                @forelse($todayAttendances ?? [] as $attendance)
                                <tr data-attendance-id="{{ $attendance->id }}" data-time-in="{{ $attendance->raw_time_in }}" data-timed-out="{{ $attendance->time_out ? 'true' : 'false' }}">
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700;">
                                                {{ strtoupper(substr($attendance->intern->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div class="intern-info">
                                                <span class="intern-name" style="font-weight: 600;">{{ $attendance->intern->name ?? 'Unknown' }}</span>
                                                <span class="intern-school" style="font-size: 12px; color: #6B7280; display: block;">{{ $attendance->intern->reference_code ?? '' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $attendance->intern->school ?? 'N/A' }}</td>
                                    <td>
                                        <span class="time-in-out-badge time-in" style="background: #D1FAE5; color: #065F46; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                            <i class="fas fa-sign-in-alt"></i> {{ $attendance->formatted_time_in }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($attendance->time_out)
                                        <span class="time-in-out-badge time-out" style="background: #FEE2E2; color: #991B1B; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                            <i class="fas fa-sign-out-alt"></i> {{ $attendance->formatted_time_out }}
                                        </span>
                                        @else
                                        <span style="color: #9CA3AF; font-style: italic;">Still working...</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong style="font-size: 16px;" id="hours-{{ $attendance->id }}">
                                            {{ number_format($attendance->current_hours_worked, 2) }} hrs
                                        </strong>
                                    </td>
                                    <td>
                                        @if($attendance->time_out)
                                            @if($attendance->hasUndertime())
                                                <span style="background: #FEE2E2; color: #991B1B; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                    <i class="fas fa-arrow-down"></i> -{{ number_format($attendance->undertime_hours, 2) }} hrs
                                                </span>
                                            @elseif($attendance->hasOvertime())
                                                @if($attendance->overtime_approved)
                                                    <span style="background: #D1FAE5; color: #065F46; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                        <i class="fas fa-check-circle"></i> +{{ number_format($attendance->overtime_hours, 2) }} hrs (Approved)
                                                    </span>
                                                @else
                                                    <span style="background: #FEF3C7; color: #92400E; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                        <i class="fas fa-clock"></i> +{{ number_format($attendance->overtime_hours, 2) }} hrs (Pending)
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
                                    <td>
                                        <span class="hours-badge" style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
                                            @if($attendance->status === 'Present') background: #D1FAE5; color: #065F46;
                                            @elseif($attendance->status === 'Late') background: #FEF3C7; color: #92400E;
                                            @else background: #FEE2E2; color: #991B1B; @endif">
                                            {{ $attendance->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($attendance->isOvertimePending())
                                            <button onclick="approveOvertime({{ $attendance->id }})" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                                <i class="fas fa-check"></i> Approve OT
                                            </button>
                                        @else
                                            <span style="color: #9CA3AF;">--</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                        <i class="fas fa-clock" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                                        No attendance records for today yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Time In/Out Records -->
                <!-- Attendance History -->
                <div id="attendance-history" class="time-tab-content" style="display: none;">
                    <div class="table-card">
                        <div class="table-header">
                            <h3 class="table-title">Attendance History - All Records</h3>
                            <span style="background: #DBEAFE; color: #1E40AF; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                {{ ($attendanceHistory ?? collect())->count() }} Records
                            </span>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Intern Name</th>
                                    <th>School</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Hours Worked</th>
                                    <th>Over/Under</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendanceHistory ?? [] as $attendance)
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;">{{ $attendance->date ? $attendance->date->format('M d, Y') : 'N/A' }}</div>
                                        <div style="font-size: 12px; color: #6B7280;">{{ $attendance->date ? $attendance->date->format('l') : '' }}</div>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700;">
                                                {{ strtoupper(substr($attendance->intern->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <span style="font-weight: 600;">{{ $attendance->intern->name ?? 'Unknown' }}</span>
                                                <div style="font-size: 12px; color: #6B7280;">{{ $attendance->intern->reference_code ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $attendance->intern->school ?? 'N/A' }}</td>
                                    <td>
                                        <span style="background: #D1FAE5; color: #065F46; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 500;">
                                            <i class="fas fa-sign-in-alt" style="margin-right: 4px;"></i>{{ $attendance->formatted_time_in }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($attendance->time_out)
                                        <span style="background: #FEE2E2; color: #991B1B; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 500;">
                                            <i class="fas fa-sign-out-alt" style="margin-right: 4px;"></i>{{ $attendance->formatted_time_out }}
                                        </span>
                                        @else
                                        <span style="color: #9CA3AF; font-style: italic;">Not yet</span>
                                        @endif
                                    </td>
                                    <td><strong style="color: #7B1D3A;">{{ number_format($attendance->hours_worked, 2) }} hrs</strong></td>
                                    <td>
                                        @if($attendance->time_out)
                                            @if($attendance->hasUndertime())
                                                <span style="background: #FEE2E2; color: #991B1B; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                    <i class="fas fa-arrow-down"></i> -{{ number_format($attendance->undertime_hours, 2) }} hrs
                                                </span>
                                            @elseif($attendance->hasOvertime())
                                                @if($attendance->overtime_approved)
                                                    <span style="background: #D1FAE5; color: #065F46; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                        <i class="fas fa-check-circle"></i> +{{ number_format($attendance->overtime_hours, 2) }} hrs
                                                    </span>
                                                @else
                                                    <span style="background: #FEF3C7; color: #92400E; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                        <i class="fas fa-clock"></i> +{{ number_format($attendance->overtime_hours, 2) }} hrs (Pending)
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
                                    <td>
                                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
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
                </div>

                <!-- Hours Summary -->
                <div id="hours-summary" class="time-tab-content" style="display: none;">
                    <div class="table-card">
                        <div class="table-header">
                            <h3 class="table-title">Comprehensive Hours Summary - All Interns</h3>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Intern Name</th>
                                    <th>School</th>
                                    <th>Required Hours</th>
                                    <th>Completed Hours</th>
                                    <th>Remaining Hours</th>
                                    <th>Completion %</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($interns ?? [] as $intern)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #FFBF00, #FFA500); display: flex; align-items: center; justify-content: center; color: #7B1D3A; font-weight: 700;">
                                                {{ strtoupper(substr($intern->name, 0, 1)) }}
                                            </div>
                                            <span style="font-weight: 600;">{{ $intern->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $intern->school }}</td>
                                    <td>{{ $intern->required_hours }} hrs</td>
                                    <td><strong style="color: #10B981;">{{ $intern->completed_hours }} hrs</strong></td>
                                    <td style="@if($intern->remaining_hours > 200) color: #EF4444; font-weight: 600; @endif">
                                        {{ $intern->remaining_hours }} hrs
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div class="progress-bar-container" style="flex: 1; max-width: 80px;">
                                                <div class="progress-bar" style="width: {{ $intern->progress_percentage }}%; @if($intern->progress_percentage < 50) background: #EF4444; @endif"></div>
                                            </div>
                                            <span style="font-weight: 600;">{{ $intern->progress_percentage }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($intern->progress_percentage >= 75)
                                        <span class="status-badge status-active">On Track</span>
                                        @elseif($intern->progress_percentage >= 50)
                                        <span class="status-badge status-pending">Moderate</span>
                                        @else
                                        <span class="status-badge" style="background: #FEE2E2; color: #991B1B;">At Risk</span>
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

                <div class="table-card">
                    <div class="table-header">
                        <h3 class="table-title">All Task Assignments</h3>
                        <button style="padding: 8px 16px; background: #7B1D3A; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-download"></i> Export Tasks
                        </button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Task Title</th>
                                <th>Assigned To</th>
                                <th>Assignment Type</th>
                                <th>School</th>
                                <th>Due Date</th>
                                <th>Priority</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="taskTableBody">
                            @forelse($tasks ?? [] as $task)
                            <tr>
                                <td>
                                    <div style="font-weight: 600; margin-bottom: 4px;">{{ $task->title }}</div>
                                    <div style="font-size: 12px; color: #6B7280;">{{ $task->description ?? 'No description' }}</div>
                                </td>
                                <td>
                                    <div class="intern-info">
                                        <span class="intern-name">{{ $task->intern->name ?? 'Unknown' }}</span>
                                        <span class="intern-school">{{ $task->intern->school ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td><span class="status-badge" style="background: #D1FAE5; color: #065F46;">Individual</span></td>
                                <td>{{ $task->intern->school ?? 'N/A' }}</td>
                                <td style="font-weight: 600;">{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="status-badge" style="background: 
                                        @if($task->priority === 'High') #FEE2E2; color: #991B1B;
                                        @elseif($task->priority === 'Medium') #FEF3C7; color: #92400E;
                                        @else #D1FAE5; color: #065F46;
                                        @endif">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="progress-bar-container" style="flex: 1; max-width: 80px;">
                                            <div class="progress-bar" style="width: {{ $task->status === 'Completed' ? 100 : ($task->status === 'In Progress' ? 50 : 10) }}%;"></div>
                                        </div>
                                        <span style="font-weight: 600; font-size: 12px;">{{ $task->status === 'Completed' ? 100 : ($task->status === 'In Progress' ? 50 : 10) }}%</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge" style="background: 
                                        @if($task->status === 'Completed') #D1FAE5; color: #065F46;
                                        @elseif($task->status === 'In Progress') #FEF3C7; color: #92400E;
                                        @else #E5E7EB; color: #6B7280;
                                        @endif">
                                        {{ $task->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-view" title="View Details" onclick="viewTaskDetails({{ $task->id }})"><i class="fas fa-eye"></i></button>
                                        <button class="btn-action btn-edit" title="Edit Task" onclick="editTask({{ $task->id }})"><i class="fas fa-edit"></i></button>
                                        @if($task->status !== 'Completed')
                                        <button class="btn-action btn-check" title="Mark Complete" onclick="markTaskComplete({{ $task->id }})"><i class="fas fa-check"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                    <i class="fas fa-tasks" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                                    No tasks assigned yet. Click <strong>New Task</strong> to create one.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
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
                <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 24px;">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #3B82F6, #2563EB);">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-value">{{ $startupDocuments->count() }}</div>
                        <div class="stat-label">Total Documents</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-value">{{ $startupDocuments->where('status', 'pending')->count() }}</div>
                        <div class="stat-label">Pending Review</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #6366F1, #4F46E5);">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="stat-value">{{ $startupDocuments->where('status', 'under_review')->count() }}</div>
                        <div class="stat-label">Under Review</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-value">{{ $startupDocuments->whereIn('status', ['approved', 'completed'])->count() }}</div>
                        <div class="stat-label">Approved</div>
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
                    <div class="kanban-column">
                        <div class="kanban-header">
                            <h4><i class="fas fa-inbox"></i> Pending</h4>
                            <span class="kanban-count">{{ $startupDocuments->where('status', 'pending')->count() }}</span>
                        </div>
                        <div class="kanban-cards">
                            @forelse($startupDocuments->where('status', 'pending') as $doc)
                            <div class="kanban-card" onclick="viewDocumentDetails('{{ $doc->id }}')">
                                <div class="card-header">
                                    <h5>{{ $doc->company_name }}</h5>
                                    <span class="status-badge" style="background: #FEF3C7; color: #92400E;">Pending</span>
                                </div>
                                <p class="card-description">{{ $doc->document_type }} - {{ $doc->original_filename }}</p>
                                <div class="card-meta">
                                    <span><i class="fas fa-user"></i> {{ $doc->contact_person }}</span>
                                    <span><i class="fas fa-calendar"></i> {{ $doc->created_at->format('M d') }}</span>
                                </div>
                                <div style="margin-top: 8px; font-size: 12px; color: #6B7280;">
                                    <i class="fas fa-hashtag"></i> {{ $doc->tracking_code }}
                                </div>
                                @if($doc->notes)
                                <div style="margin-top: 8px; font-size: 11px; color: #6B7280; padding: 6px; background: #F3F4F6; border-radius: 4px;">
                                    {{ Str::limit($doc->notes, 50) }}
                                </div>
                                @endif
                                <div style="margin-top: 10px; display: flex; gap: 6px;">
                                    <button onclick="event.stopPropagation(); openReviewDocumentModal('{{ $doc->id }}')" style="flex: 1; padding: 6px 10px; font-size: 11px; background: #10B981; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                        <i class="fas fa-check"></i> Review
                                    </button>
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" onclick="event.stopPropagation();" style="padding: 6px 10px; font-size: 11px; background: #3B82F6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none;">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            @empty
                            <div style="padding: 20px; text-align: center; color: #9CA3AF; font-size: 13px;">
                                <i class="fas fa-inbox" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                                No pending documents
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Under Review Column -->
                    <div class="kanban-column">
                        <div class="kanban-header">
                            <h4><i class="fas fa-search"></i> Under Review</h4>
                            <span class="kanban-count">{{ $startupDocuments->where('status', 'under_review')->count() }}</span>
                        </div>
                        <div class="kanban-cards">
                            @forelse($startupDocuments->where('status', 'under_review') as $doc)
                            <div class="kanban-card" onclick="viewDocumentDetails('{{ $doc->id }}')">
                                <div class="card-header">
                                    <h5>{{ $doc->company_name }}</h5>
                                    <span class="status-badge" style="background: #DBEAFE; color: #1E40AF;">Reviewing</span>
                                </div>
                                <p class="card-description">{{ $doc->document_type }} - {{ $doc->original_filename }}</p>
                                <div class="card-meta">
                                    <span><i class="fas fa-user"></i> {{ $doc->contact_person }}</span>
                                    <span><i class="fas fa-calendar"></i> {{ $doc->created_at->format('M d') }}</span>
                                </div>
                                <div style="margin-top: 8px; font-size: 12px; color: #6B7280;">
                                    <i class="fas fa-hashtag"></i> {{ $doc->tracking_code }}
                                </div>
                                <div style="margin-top: 10px; display: flex; gap: 6px;">
                                    <button onclick="event.stopPropagation(); openReviewDocumentModal('{{ $doc->id }}')" style="flex: 1; padding: 6px 10px; font-size: 11px; background: #10B981; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                        <i class="fas fa-check"></i> Approve/Reject
                                    </button>
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" onclick="event.stopPropagation();" style="padding: 6px 10px; font-size: 11px; background: #3B82F6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none;">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            @empty
                            <div style="padding: 20px; text-align: center; color: #9CA3AF; font-size: 13px;">
                                <i class="fas fa-search" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                                No documents under review
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Approved Column -->
                    <div class="kanban-column">
                        <div class="kanban-header">
                            <h4><i class="fas fa-check-circle"></i> Approved</h4>
                            <span class="kanban-count">{{ $startupDocuments->where('status', 'approved')->count() }}</span>
                        </div>
                        <div class="kanban-cards">
                            @forelse($startupDocuments->where('status', 'approved') as $doc)
                            <div class="kanban-card success" onclick="viewDocumentDetails('{{ $doc->id }}')">
                                <div class="card-header">
                                    <h5>{{ $doc->company_name }}</h5>
                                    <span class="status-badge" style="background: #DCFCE7; color: #166534;">Approved</span>
                                </div>
                                <p class="card-description">{{ $doc->document_type }} - {{ $doc->original_filename }}</p>
                                <div class="card-meta">
                                    <span><i class="fas fa-user"></i> {{ $doc->contact_person }}</span>
                                    <span><i class="fas fa-calendar"></i> {{ $doc->reviewed_at ? $doc->reviewed_at->format('M d') : 'N/A' }}</span>
                                </div>
                                <div style="margin-top: 8px; font-size: 12px; color: #6B7280;">
                                    <i class="fas fa-hashtag"></i> {{ $doc->tracking_code }}
                                </div>
                                @if($doc->reviewer)
                                <div style="margin-top: 8px; font-size: 11px; color: #059669;">
                                    <i class="fas fa-user-check"></i> Reviewed by {{ $doc->reviewer->name }}
                                </div>
                                @endif
                            </div>
                            @empty
                            <div style="padding: 20px; text-align: center; color: #9CA3AF; font-size: 13px;">
                                <i class="fas fa-check-circle" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                                No approved documents
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Rejected Column -->
                    <div class="kanban-column">
                        <div class="kanban-header">
                            <h4><i class="fas fa-times-circle"></i> Rejected</h4>
                            <span class="kanban-count">{{ $startupDocuments->where('status', 'rejected')->count() }}</span>
                        </div>
                        <div class="kanban-cards">
                            @forelse($startupDocuments->where('status', 'rejected') as $doc)
                            <div class="kanban-card" style="border-left: 3px solid #EF4444;" onclick="viewDocumentDetails('{{ $doc->id }}')">
                                <div class="card-header">
                                    <h5>{{ $doc->company_name }}</h5>
                                    <span class="status-badge" style="background: #FEE2E2; color: #991B1B;">Rejected</span>
                                </div>
                                <p class="card-description">{{ $doc->document_type }} - {{ $doc->original_filename }}</p>
                                <div class="card-meta">
                                    <span><i class="fas fa-user"></i> {{ $doc->contact_person }}</span>
                                    <span><i class="fas fa-calendar"></i> {{ $doc->reviewed_at ? $doc->reviewed_at->format('M d') : 'N/A' }}</span>
                                </div>
                                <div style="margin-top: 8px; font-size: 12px; color: #6B7280;">
                                    <i class="fas fa-hashtag"></i> {{ $doc->tracking_code }}
                                </div>
                                @if($doc->admin_notes)
                                <div style="margin-top: 8px; font-size: 11px; color: #DC2626; padding: 6px; background: #FEF2F2; border-radius: 4px;">
                                    <i class="fas fa-exclamation-circle"></i> {{ Str::limit($doc->admin_notes, 50) }}
                                </div>
                                @endif
                            </div>
                            @empty
                            <div style="padding: 20px; text-align: center; color: #9CA3AF; font-size: 13px;">
                                <i class="fas fa-times-circle" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
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
                <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 24px;">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-value">{{ $activeIncubatees }}</div>
                        <div class="stat-label">Active Incubatees</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #3B82F6, #2563EB);">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div class="stat-value">{{ $moaRequests->count() }}</div>
                        <div class="stat-label">Total MOA Requests</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="stat-value">{{ $pendingMoaCount }}</div>
                        <div class="stat-label">Pending MOAs</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #6366F1, #4F46E5);">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-value">{{ $pendingPaymentCount }}</div>
                        <div class="stat-label">Pending Payments</div>
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
                    <table>
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

                <!-- Payment Submissions Table -->
                <div id="payments-table" class="table-card" style="display: none;">
                    <div class="table-header">
                        <h3 class="table-title">Payment Submissions from Startups</h3>
                        <button style="padding: 8px 16px; background: #7B1D3A; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-download"></i> Export Report
                        </button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Tracking Code</th>
                                <th>Company/Startup</th>
                                <th>Contact Person</th>
                                <th>Invoice #</th>
                                <th>Amount</th>
                                <th>Submitted</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentSubmissions as $payment)
                            <tr class="incubatee-row" data-status="{{ $payment->status }}">
                                <td><strong>{{ $payment->tracking_code }}</strong></td>
                                <td>
                                    <div style="font-weight: 600; margin-bottom: 4px;">{{ $payment->company_name }}</div>
                                    <div style="font-size: 12px; color: #6B7280;">{{ $payment->email }}</div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="avatar">{{ strtoupper(substr($payment->contact_person, 0, 1)) }}</div>
                                        <span style="font-weight: 600;">{{ $payment->contact_person }}</span>
                                    </div>
                                </td>
                                <td><strong>{{ $payment->invoice_number }}</strong></td>
                                <td style="font-weight: 700; color: #059669;">₱{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($payment->status == 'pending')
                                        <span class="status-badge" style="background: #FEF3C7; color: #92400E;">Pending</span>
                                    @elseif($payment->status == 'under_review')
                                        <span class="status-badge" style="background: #DBEAFE; color: #1E40AF;">Under Review</span>
                                    @elseif($payment->status == 'approved')
                                        <span class="status-badge" style="background: #DCFCE7; color: #166534;">Verified</span>
                                        @if($payment->reviewed_at)
                                        <div style="font-size: 11px; color: #10B981; margin-top: 2px;">{{ $payment->reviewed_at->format('M d, Y') }}</div>
                                        @endif
                                    @elseif($payment->status == 'rejected')
                                        <span class="status-badge" style="background: #FEE2E2; color: #991B1B;">Rejected</span>
                                    @else
                                        <span class="status-badge" style="background: #E5E7EB; color: #374151;">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-view" onclick="viewPaymentDetails('{{ $payment->id }}')"><i class="fas fa-eye"></i></button>
                                        @if($payment->payment_proof_path)
                                        <a href="{{ asset('storage/' . $payment->payment_proof_path) }}" target="_blank" class="btn-action btn-edit"><i class="fas fa-receipt"></i></a>
                                        @endif
                                        <button class="btn-action" style="background: #10B981; color: white;" onclick="reviewSubmission('{{ $payment->id }}', 'finance')"><i class="fas fa-clipboard-check"></i></button>
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

            <!-- Issues & Complaints Management Page -->
            <div id="issues-management" class="page-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Issues & Complaints Management</h2>
                    <p style="color: #6B7280; font-size: 14px;">Track and resolve facility issues, equipment problems, and incubatee complaints from the startup portal</p>
                </div>

                <!-- Stats Overview -->
                <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 24px;">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #EF4444, #DC2626);">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="stat-value">{{ $openIssues }}</div>
                        <div class="stat-label">Open Issues</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-value">{{ $inProgressIssues }}</div>
                        <div class="stat-label">In Progress</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-value">{{ $resolvedThisMonth }}</div>
                        <div class="stat-label">Resolved (This Month)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #6366F1, #4F46E5);">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="stat-value">{{ $roomIssues->count() }}</div>
                        <div class="stat-label">Total Reports</div>
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
                <div class="table-card">
                    <div class="table-header">
                        <h3 class="table-title">All Room Issues & Complaints</h3>
                        <div style="display: flex; gap: 8px;">
                            <button style="padding: 8px 16px; background: white; color: #7B1D3A; border: 2px solid #7B1D3A; border-radius: 6px; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Tracking Code</th>
                                <th>Room/Description</th>
                                <th>Issue Type</th>
                                <th>Reported By</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Date Reported</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roomIssues as $issue)
                            <tr class="issue-row" data-status="{{ $issue->status }}" data-type="{{ $issue->issue_type }}" data-priority="{{ $issue->priority }}">
                                <td><strong>{{ $issue->tracking_code }}</strong></td>
                                <td>
                                    <div style="font-weight: 600; margin-bottom: 4px;">Room {{ $issue->room_number }}</div>
                                    <div style="font-size: 12px; color: #6B7280; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($issue->description, 60) }}</div>
                                </td>
                                <td>
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
                                    <span class="status-badge" style="background: {{ $color['bg'] }}; color: {{ $color['text'] }};">{{ $issue->issue_type_label }}</span>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="avatar">{{ strtoupper(substr($issue->contact_person, 0, 1)) }}</div>
                                        <div>
                                            <span style="font-weight: 600;">{{ $issue->contact_person }}</span>
                                            <div style="font-size: 11px; color: #6B7280;">{{ $issue->company_name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $priorityClasses = [
                                            'urgent' => 'priority-critical',
                                            'high' => 'priority-high',
                                            'medium' => 'priority-medium',
                                            'low' => 'priority-low',
                                        ];
                                        $priorityClass = $priorityClasses[$issue->priority] ?? 'priority-medium';
                                    @endphp
                                    <span class="priority-badge {{ $priorityClass }}">{{ ucfirst($issue->priority) }}</span>
                                </td>
                                <td>
                                    @if($issue->status == 'pending')
                                        <span class="status-badge" style="background: #FEE2E2; color: #991B1B;">Pending</span>
                                    @elseif($issue->status == 'in_progress')
                                        <span class="status-badge" style="background: #FEF3C7; color: #92400E;">In Progress</span>
                                    @elseif($issue->status == 'resolved')
                                        <span class="status-badge" style="background: #DCFCE7; color: #166534;">Resolved</span>
                                        @if($issue->resolved_at)
                                        <div style="font-size: 11px; color: #10B981; margin-top: 2px;">{{ $issue->resolved_at->format('M d') }}</div>
                                        @endif
                                    @elseif($issue->status == 'closed')
                                        <span class="status-badge" style="background: #E5E7EB; color: #374151;">Closed</span>
                                    @else
                                        <span class="status-badge" style="background: #E5E7EB; color: #374151;">{{ ucfirst($issue->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $issue->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-view" onclick="viewRoomIssueDetails('{{ $issue->id }}')"><i class="fas fa-eye"></i></button>
                                        @if($issue->photo_path)
                                        <a href="{{ asset('storage/' . $issue->photo_path) }}" target="_blank" class="btn-action btn-edit"><i class="fas fa-image"></i></a>
                                        @endif
                                        <button class="btn-action" style="background: #10B981; color: white;" onclick="updateIssueStatus('{{ $issue->id }}')"><i class="fas fa-check"></i></button>
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

            <!-- Digital Records Page -->
            <div id="digital-records" class="page-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Digital Records Management</h2>
                    <p style="color: #6B7280; font-size: 14px;">Organize and manage all your documents, files, and records in a secure digital repository</p>
                </div>

                <!-- Stats Overview -->
                <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 24px;">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #3B82F6, #2563EB);">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="stat-value">24</div>
                        <div class="stat-label">Total Folders</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                            <i class="fas fa-file"></i>
                        </div>
                        <div class="stat-value">387</div>
                        <div class="stat-label">Total Files</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #8B5CF6, #7C3AED);">
                            <i class="fas fa-hdd"></i>
                        </div>
                        <div class="stat-value">2.4 GB</div>
                        <div class="stat-label">Storage Used</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-value">12</div>
                        <div class="stat-label">Recent Uploads</div>
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
                    <!-- Folders -->
                    <div class="file-item folder-item" onclick="openFolder('intern-documents')">
                        <div class="file-icon folder-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="file-name">Intern Documents</div>
                        <div class="file-meta">45 items</div>
                    </div>

                    <div class="file-item folder-item" onclick="openFolder('research-files')">
                        <div class="file-icon folder-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="file-name">Research Files</div>
                        <div class="file-meta">28 items</div>
                    </div>

                    <div class="file-item folder-item" onclick="openFolder('incubatee-docs')">
                        <div class="file-icon folder-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="file-name">Incubatee Documents</div>
                        <div class="file-meta">62 items</div>
                    </div>

                    <div class="file-item folder-item" onclick="openFolder('moa-contracts')">
                        <div class="file-icon folder-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="file-name">MOA & Contracts</div>
                        <div class="file-meta">18 items</div>
                    </div>

                    <div class="file-item folder-item" onclick="openFolder('reports')">
                        <div class="file-icon folder-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="file-name">Reports</div>
                        <div class="file-meta">34 items</div>
                    </div>

                    <div class="file-item folder-item" onclick="openFolder('policies')">
                        <div class="file-icon folder-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="file-name">Policies & Guidelines</div>
                        <div class="file-meta">12 items</div>
                    </div>

                    <!-- Sample Files in Root -->
                    <div class="file-item file-doc" onclick="viewFileDetails('file1')">
                        <div class="file-icon" style="background: linear-gradient(135deg, #EF4444, #DC2626);">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="file-name">System Guidelines 2025.pdf</div>
                        <div class="file-meta">2.4 MB • 2 days ago</div>
                    </div>

                    <div class="file-item file-doc" onclick="viewFileDetails('file2')">
                        <div class="file-icon" style="background: linear-gradient(135deg, #3B82F6, #2563EB);">
                            <i class="fas fa-file-word"></i>
                        </div>
                        <div class="file-name">Annual Report Draft.docx</div>
                        <div class="file-meta">1.8 MB • 5 days ago</div>
                    </div>

                    <div class="file-item file-doc" onclick="viewFileDetails('file3')">
                        <div class="file-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <div class="file-name">Budget Tracker Q4.xlsx</div>
                        <div class="file-meta">845 KB • 1 week ago</div>
                    </div>

                    <div class="file-item file-doc" onclick="viewFileDetails('file4')">
                        <div class="file-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                            <i class="fas fa-file-powerpoint"></i>
                        </div>
                        <div class="file-name">Startup Orientation.pptx</div>
                        <div class="file-meta">5.2 MB • 2 weeks ago</div>
                    </div>

                    <div class="file-item file-doc" onclick="viewFileDetails('file5')">
                        <div class="file-icon" style="background: linear-gradient(135deg, #6B7280, #4B5563);">
                            <i class="fas fa-file-archive"></i>
                        </div>
                        <div class="file-name">Archive_2024.zip</div>
                        <div class="file-meta">124 MB • 1 month ago</div>
                    </div>

                    <div class="file-item file-doc" onclick="viewFileDetails('file6')">
                        <div class="file-icon" style="background: linear-gradient(135deg, #8B5CF6, #7C3AED);">
                            <i class="fas fa-file-image"></i>
                        </div>
                        <div class="file-name">Event Photos.jpg</div>
                        <div class="file-meta">3.6 MB • 3 days ago</div>
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
                                    <th>Owner</th>
                                    <th style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr onclick="openFolder('intern-documents')" style="cursor: pointer;">
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <i class="fas fa-folder" style="font-size: 24px; color: #FFBF00;"></i>
                                            <span style="font-weight: 600;">Intern Documents</span>
                                        </div>
                                    </td>
                                    <td>Folder</td>
                                    <td>45 items</td>
                                    <td>Dec 10, 2025</td>
                                    <td>Admin</td>
                                    <td>
                                        <button class="btn-action btn-view" onclick="event.stopPropagation(); openFolder('intern-documents')"><i class="fas fa-folder-open"></i></button>
                                    </td>
                                </tr>
                                <tr onclick="openFolder('research-files')" style="cursor: pointer;">
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <i class="fas fa-folder" style="font-size: 24px; color: #FFBF00;"></i>
                                            <span style="font-weight: 600;">Research Files</span>
                                        </div>
                                    </td>
                                    <td>Folder</td>
                                    <td>28 items</td>
                                    <td>Dec 12, 2025</td>
                                    <td>Admin</td>
                                    <td>
                                        <button class="btn-action btn-view" onclick="event.stopPropagation(); openFolder('research-files')"><i class="fas fa-folder-open"></i></button>
                                    </td>
                                </tr>
                                <tr onclick="viewFileDetails('file1')" style="cursor: pointer;">
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <i class="fas fa-file-pdf" style="font-size: 20px; color: #EF4444;"></i>
                                            <span>System Guidelines 2025.pdf</span>
                                        </div>
                                    </td>
                                    <td><span class="file-type-badge pdf">PDF</span></td>
                                    <td>2.4 MB</td>
                                    <td>Dec 13, 2025</td>
                                    <td>Kingsley Laran</td>
                                    <td>
                                        <button class="btn-action btn-view" onclick="event.stopPropagation(); viewFileDetails('file1')"><i class="fas fa-eye"></i></button>
                                        <button class="btn-action btn-download" onclick="event.stopPropagation(); downloadFile('file1')"><i class="fas fa-download"></i></button>
                                    </td>
                                </tr>
                                <tr onclick="viewFileDetails('file2')" style="cursor: pointer;">
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <i class="fas fa-file-word" style="font-size: 20px; color: #3B82F6;"></i>
                                            <span>Annual Report Draft.docx</span>
                                        </div>
                                    </td>
                                    <td><span class="file-type-badge doc">DOC</span></td>
                                    <td>1.8 MB</td>
                                    <td>Dec 10, 2025</td>
                                    <td>Julliana Laurena</td>
                                    <td>
                                        <button class="btn-action btn-view" onclick="event.stopPropagation(); viewFileDetails('file2')"><i class="fas fa-eye"></i></button>
                                        <button class="btn-action btn-download" onclick="event.stopPropagation(); downloadFile('file2')"><i class="fas fa-download"></i></button>
                                    </td>
                                </tr>
                                <tr onclick="viewFileDetails('file3')" style="cursor: pointer;">
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <i class="fas fa-file-excel" style="font-size: 20px; color: #10B981;"></i>
                                            <span>Budget Tracker Q4.xlsx</span>
                                        </div>
                                    </td>
                                    <td><span class="file-type-badge excel">XLS</span></td>
                                    <td>845 KB</td>
                                    <td>Dec 8, 2025</td>
                                    <td>Ruther Marte</td>
                                    <td>
                                        <button class="btn-action btn-view" onclick="event.stopPropagation(); viewFileDetails('file3')"><i class="fas fa-eye"></i></button>
                                        <button class="btn-action btn-download" onclick="event.stopPropagation(); downloadFile('file3')"><i class="fas fa-download"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Scheduler Page -->
            <div id="scheduler" class="page-content">
                <div style="margin-bottom: 24px;">
                    <h2 style="font-size: 28px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">Agency Bookings & Calendar</h2>
                    <p style="color: #6B7280; font-size: 14px;">Manage agency booking requests and view scheduled appointments</p>
                </div>

                <!-- Stats Overview -->
                <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 24px;">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-value" id="pendingBookingsCount">{{ $pendingBookings ?? 0 }}</div>
                        <div class="stat-label">Pending Requests</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #3B82F6, #2563EB);">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-value">{{ $todayBookings ?? 0 }}</div>
                        <div class="stat-label">Today's Bookings</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="stat-value">{{ isset($upcomingBookings) ? $upcomingBookings->count() : 0 }}</div>
                        <div class="stat-label">Upcoming Events</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #8B5CF6, #7C3AED);">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="stat-value">{{ isset($allBookings) ? $allBookings->where('status', 'approved')->count() : 0 }}</div>
                        <div class="stat-label">Total Approved</div>
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
                        <button class="filter-tab" onclick="switchBookingTab('all')">
                            <i class="fas fa-list"></i> All Bookings
                        </button>
                    </div>
                </div>

                <!-- Pending Bookings Tab -->
                <div id="pendingBookingsTab" class="booking-tab-content">
                    <div class="table-card">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Agency</th>
                                    <th>Event / School</th>
                                    <th>Contact Person</th>
                                    <th>Contact Info</th>
                                    <th>Purpose</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pendingBookingsBody">
                                @forelse($allBookings->where('status', 'pending') ?? [] as $booking)
                                <tr id="booking-row-{{ $booking->id }}">
                                    <td>
                                        <div style="font-weight: 600;">{{ $booking->booking_date->format('M d, Y') }}</div>
                                        <div style="font-size: 12px; color: #6B7280;">{{ $booking->formatted_time }}</div>
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
                                        <button class="btn-action btn-edit" onclick="approveBooking({{ $booking->id }})" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn-action btn-delete" onclick="rejectBooking({{ $booking->id }})" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr id="noPendingRow">
                                    <td colspan="7" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                        <i class="fas fa-check-circle" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                                        No pending booking requests
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                    <div class="filter-bar" style="margin-bottom: 16px;">
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
                    </div>
                    <div class="table-card">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Agency</th>
                                    <th>Event</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="allBookingsBody">
                                @forelse($allBookings ?? [] as $booking)
                                <tr class="booking-row" data-status="{{ $booking->status }}" data-search="{{ strtolower($booking->agency_name . ' ' . $booking->event_name . ' ' . $booking->contact_person) }}">
                                    <td>{{ $booking->booking_date->format('M d, Y') }}</td>
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
                                        <button class="btn-action btn-view" onclick="viewBookingDetails({{ $booking->id }})" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($booking->status === 'pending')
                                        <button class="btn-action btn-edit" onclick="approveBooking({{ $booking->id }})" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn-action btn-delete" onclick="rejectBooking({{ $booking->id }})" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                        <button class="btn-action btn-delete" onclick="deleteBooking({{ $booking->id }})" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: #9CA3AF;">
                                        <i class="fas fa-calendar-times" style="font-size: 40px; margin-bottom: 12px; display: block;"></i>
                                        No bookings found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Block Date Modal -->
    <div id="blockDateModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 450px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-calendar-times" style="margin-right: 8px;"></i>Block Date</h3>
                <button class="modal-close" onclick="closeBlockDateModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="blockDateForm">
                    <div class="form-group">
                        <label class="form-label">Selected Date</label>
                        <input type="text" id="blockDateDisplay" class="form-input" readonly style="background: #f9fafb;">
                        <input type="hidden" id="blockDateValue">
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

    <!-- New Task Modal -->
    <div id="newTaskModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Create New Task</h3>
                <button class="modal-close" onclick="closeNewTaskModal()">
                    <i class="fas fa-times"></i>
                </button>
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
                <button class="modal-close" onclick="closeViewTaskModal()">
                    <i class="fas fa-times"></i>
                </button>
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
                <button class="modal-close" onclick="closeDocumentDetailsModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="documentDetailsContent">
                <!-- Document details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeDocumentDetailsModal()">Close</button>
                <a id="documentDownloadBtn" href="#" target="_blank" class="btn-modal primary" style="text-decoration: none;">
                    <i class="fas fa-download"></i> Download Document
                </a>
            </div>
        </div>
    </div>

    <!-- Review Document Modal -->
    <div id="reviewDocumentModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-clipboard-check" style="margin-right: 8px;"></i>Review Document</h3>
                <button class="modal-close" onclick="closeReviewDocumentModal()">
                    <i class="fas fa-times"></i>
                </button>
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
                <button class="modal-close" onclick="closeMoaDetailsModal()">
                    <i class="fas fa-times"></i>
                </button>
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
                <button class="modal-close" onclick="closeReviewMoaModal()">
                    <i class="fas fa-times"></i>
                </button>
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
                <button class="modal-close" onclick="closeMoaTemplateModal()">
                    <i class="fas fa-times"></i>
                </button>
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
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-credit-card" style="margin-right: 8px;"></i>Payment Submission Details</h3>
                <button class="modal-close" onclick="closePaymentDetailsModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="paymentDetailsContent">
                <!-- Payment details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closePaymentDetailsModal()">Close</button>
                <a id="paymentProofBtn" href="#" target="_blank" class="btn-modal" style="background: #6366F1; color: white; text-decoration: none;">
                    <i class="fas fa-receipt"></i> View Proof
                </a>
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
                <button class="modal-close" onclick="closeReviewPaymentModal()">
                    <i class="fas fa-times"></i>
                </button>
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
        <div class="modal-content" style="max-width: 650px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>Room Issue Details</h3>
                <button class="modal-close" onclick="closeRoomIssueDetailsModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="roomIssueDetailsContent">
                <!-- Issue details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeRoomIssueDetailsModal()">Close</button>
                <button class="btn-modal primary" onclick="openUpdateIssueStatusModal()">
                    <i class="fas fa-edit"></i> Update Status
                </button>
            </div>
        </div>
    </div>

    <!-- Update Issue Status Modal -->
    <div id="updateIssueStatusModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-edit" style="margin-right: 8px;"></i>Update Issue Status</h3>
                <button class="modal-close" onclick="closeUpdateIssueStatusModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateIssueStatusForm">
                    <input type="hidden" id="updateIssueId">
                    
                    <div class="form-group">
                        <label class="form-label">Issue Info</label>
                        <div id="updateIssueInfo" style="background: #F3F4F6; padding: 12px; border-radius: 8px; font-size: 14px;"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">New Status</label>
                        <select id="updateIssueNewStatus" class="form-select" required>
                            <option value="">-- Select Status --</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Assigned To (Optional)</label>
                        <input type="text" id="updateIssueAssignee" class="form-input" placeholder="Enter assignee name">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Resolution Notes</label>
                        <textarea id="updateIssueNotes" class="form-input" rows="3" placeholder="Add resolution notes or updates..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-modal secondary" onclick="closeUpdateIssueStatusModal()">Cancel</button>
                <button class="btn-modal primary" onclick="submitIssueStatusUpdate()">
                    <i class="fas fa-check"></i> Update Status
                </button>
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
            
            // Update breadcrumb
            const breadcrumb = document.querySelector('.breadcrumb');
            if (pageId === 'intern-list') {
                breadcrumb.innerHTML = 'Dashboard > Intern Management > <span>Intern List</span>';
            } else if (pageId === 'time-attendance') {
                breadcrumb.innerHTML = 'Dashboard > Intern Management > <span>Time & Attendance</span>';
            } else if (pageId === 'task-assignment') {
                breadcrumb.innerHTML = 'Dashboard > Intern Management > <span>Task Assignment</span>';
            } else if (pageId === 'research-tracking') {
                breadcrumb.innerHTML = 'Dashboard > <span>Research Tracking</span>';
            } else if (pageId === 'incubatee-tracker') {
                breadcrumb.innerHTML = 'Dashboard > <span>Incubatee Tracker</span>';
            } else if (pageId === 'issues-management') {
                breadcrumb.innerHTML = 'Dashboard > <span>Issues & Complaints</span>';
            } else if (pageId === 'digital-records') {
                breadcrumb.innerHTML = 'Dashboard > <span>Digital Records</span>';
            } else if (pageId === 'scheduler') {
                breadcrumb.innerHTML = 'Dashboard > <span>Event Scheduler</span>';
            } else if (pageId === 'dashboard-overview') {
                breadcrumb.innerHTML = 'Dashboard > <span>Overview</span>';
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
            
            const content = `
                <div style="display: grid; gap: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid #E5E7EB;">
                        <div>
                            <div style="font-size: 12px; color: #6B7280;">Tracking Code</div>
                            <div style="font-size: 18px; font-weight: 700; color: #7B1D3A;">${payment.tracking_code}</div>
                        </div>
                        <span style="background: ${color.bg}; color: ${color.text}; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            ${payment.status === 'approved' ? 'VERIFIED' : payment.status.replace('_', ' ').toUpperCase()}
                        </span>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, #10B981, #059669); color: white; padding: 20px; border-radius: 12px; text-align: center;">
                        <div style="font-size: 12px; opacity: 0.9;">Payment Amount</div>
                        <div style="font-size: 32px; font-weight: 700;">₱${parseFloat(payment.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                        <div style="font-size: 14px; margin-top: 8px;">Invoice #${payment.invoice_number}</div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Company Name</div>
                            <div style="font-weight: 600;">${payment.company_name}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Contact Person</div>
                            <div style="font-weight: 600;">${payment.contact_person}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Email</div>
                            <div>${payment.email}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Phone</div>
                            <div>${payment.phone || 'N/A'}</div>
                        </div>
                    </div>
                    
                    ${payment.notes ? `
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Submitter Notes</div>
                        <div style="background: #F3F4F6; padding: 12px; border-radius: 8px;">${payment.notes}</div>
                    </div>
                    ` : ''}
                    
                    ${payment.admin_notes ? `
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Admin Notes</div>
                        <div style="background: #FEF3C7; padding: 12px; border-radius: 8px;">${payment.admin_notes}</div>
                    </div>
                    ` : ''}
                    
                    <div style="display: flex; gap: 16px; font-size: 12px; color: #6B7280;">
                        <div><i class="fas fa-calendar"></i> Submitted: ${payment.created_at}</div>
                        ${payment.reviewed_at ? `<div><i class="fas fa-check-circle"></i> Verified: ${payment.reviewed_at}</div>` : ''}
                    </div>
                </div>
            `;
            
            document.getElementById('paymentDetailsContent').innerHTML = content;
            document.getElementById('paymentProofBtn').href = payment.payment_proof_path ? '/storage/' + payment.payment_proof_path : '#';
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
            const issue = roomIssuesData[issueId];
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
                'urgent': '#DC2626',
                'high': '#F59E0B',
                'medium': '#3B82F6',
                'low': '#10B981'
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
            const issue = roomIssuesData[currentIssueId];
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
            openUpdateIssueStatusModal();
        }
        
        function submitIssueStatusUpdate() {
            const issueId = document.getElementById('updateIssueId').value;
            const newStatus = document.getElementById('updateIssueNewStatus').value;
            const assignee = document.getElementById('updateIssueAssignee').value;
            const notes = document.getElementById('updateIssueNotes').value;
            
            if (!newStatus) {
                alert('Please select a new status');
                return;
            }
            
            // Disable button and show loading
            const submitBtn = document.querySelector('#updateIssueStatusModal .btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            
            fetch(`/admin/room-issues/${issueId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: newStatus,
                    admin_notes: notes,
                    assignee: assignee
                })
            })
            .then(response => response.json())
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
                alert('An error occurred while updating the issue');
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
                    'agency' => $b->agency_name,
                    'event' => $b->event_name,
                    'time' => $b->formatted_time,
                    'contact' => $b->contact_person
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
        
        let schedulerCurrentMonth = new Date().getMonth();
        let schedulerCurrentYear = new Date().getFullYear();
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        // Switch between booking tabs
        function switchBookingTab(tabName) {
            // Remove active from all tabs
            document.querySelectorAll('#scheduler .filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            // Add active to clicked tab
            event.target.closest('.filter-tab').classList.add('active');
            
            // Hide all tab contents
            document.querySelectorAll('.booking-tab-content').forEach(content => {
                content.style.display = 'none';
            });
            
            // Show selected tab
            if (tabName === 'pending') {
                document.getElementById('pendingBookingsTab').style.display = 'block';
            } else if (tabName === 'calendar') {
                document.getElementById('calendarViewTab').style.display = 'block';
                renderSchedulerCalendar();
            } else if (tabName === 'all') {
                document.getElementById('allBookingsTab').style.display = 'block';
            }
        }

        // Approve booking
        function approveBooking(bookingId) {
            if (!confirm('Approve this booking request?')) return;
            
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
                    alert('Booking approved successfully!');
                    // Remove from pending table
                    const row = document.getElementById(`booking-row-${bookingId}`);
                    if (row) row.remove();
                    // Update pending count
                    updatePendingCount(-1);
                    // Reload to update all views
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    alert(data.message || 'Failed to approve booking.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while approving the booking.');
            });
        }

        // Reject booking
        function rejectBooking(bookingId) {
            if (!confirm('Reject this booking request? This action cannot be undone.')) return;
            
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
                    alert('Booking rejected.');
                    const row = document.getElementById(`booking-row-${bookingId}`);
                    if (row) row.remove();
                    updatePendingCount(-1);
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    alert(data.message || 'Failed to reject booking.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while rejecting the booking.');
            });
        }

        // Delete booking
        function deleteBooking(bookingId) {
            if (!confirm('Are you sure you want to permanently delete this booking?')) return;
            
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
                    alert('Booking deleted successfully.');
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to delete booking.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the booking.');
            });
        }

        // View booking details
        function viewBookingDetails(bookingId) {
            // Find booking in allBookings data
            alert(`Viewing Booking #${bookingId} Details\n\nIn a production environment, this would show a modal with complete booking information.`);
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
                const blockedInfo = blockedDates.find(b => b.date === dateString);
                let classes = 'mini-day';
                if (isToday) classes += ' today';
                if (hasBooking) classes += ' has-events';
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
                        <div class="calendar-event meeting" onclick="event.stopPropagation(); viewBookingDetails(${booking.id})" style="cursor: pointer;">
                            <div class="event-time">${booking.time.split(' - ')[0]}</div>
                            <div class="event-name">${booking.agency}</div>
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
            document.getElementById('blockDateReason').value = '';
            document.getElementById('blockDateDescription').value = '';
            document.getElementById('blockDateWarning').style.display = 'none';
            document.getElementById('blockedDateInfo').style.display = 'none';
            document.getElementById('blockDateSubmitBtn').style.display = 'inline-flex';
            document.getElementById('unblockDateBtn').style.display = 'none';
            currentBlockDateId = null;

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

        function submitBlockDate() {
            const dateValue = document.getElementById('blockDateValue').value;
            const reason = document.getElementById('blockDateReason').value;
            const description = document.getElementById('blockDateDescription').value;

            if (!reason) {
                alert('Please select a reason.');
                return;
            }

            fetch('/admin/blocked-dates', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    blocked_date: dateValue,
                    reason: reason,
                    description: description
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Date blocked successfully!');
                    // Add to local array
                    blockedDates.push(data.blockedDate);
                    closeBlockDateModal();
                    renderSchedulerCalendar();
                } else {
                    alert(data.message || 'Failed to block date.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while blocking the date.');
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
        });

        // Digital Records Functions
        let currentFolder = 'root';
        let viewMode = 'grid'; // 'grid' or 'list'

        function openFolder(folderId) {
            currentFolder = folderId;
            document.getElementById('current-path').innerHTML = `<i class="fas fa-home"></i> Root > ${folderId.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}`;
            document.getElementById('back-btn').style.display = 'flex';
            
            alert(`Opening folder: ${folderId}\n\nThis will display:\n- All subfolders within this folder\n- All files in this folder\n- Breadcrumb navigation updated\n- Back button enabled`);
        }

        function goBackFolder() {
            currentFolder = 'root';
            document.getElementById('current-path').innerHTML = '<i class="fas fa-home"></i> Root';
            document.getElementById('back-btn').style.display = 'none';
            alert('Navigating back to root folder');
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
            const folderName = prompt('Enter new folder name:');
            if (folderName) {
                alert(`Creating new folder: "${folderName}"\n\nThis will:\n- Create a new folder in current location\n- Update the file list\n- Set permissions\n- Log the action`);
            }
        }

        function openUploadFileModal() {
            alert('Upload File Modal\n\nThis will allow you to:\n- Select files from your computer (drag & drop supported)\n- Choose destination folder\n- Add file description/tags\n- Set access permissions\n- Upload multiple files at once\n- Show upload progress\n- Supported formats: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, Images, etc.');
        }

        function viewFileDetails(fileId) {
            alert(`Viewing File Details: ${fileId}\n\nThis will show:\n- File preview (if supported format)\n- File name and type\n- File size\n- Upload date and time\n- Uploaded by (user)\n- Version history\n- Download button\n- Share/Permission settings\n- Move/Rename options\n- Delete option`);
        }

        function downloadFile(fileId) {
            alert(`Downloading file: ${fileId}\n\nThis will:\n- Initiate file download\n- Log download activity\n- Track who downloaded the file`);
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
        function createTask() {
            const form = document.getElementById('newTaskForm');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const assignmentType = document.querySelector('input[name="assignmentType"]:checked').value;
            const title = document.getElementById('taskTitle').value;
            const description = document.getElementById('taskDescription').value;
            const priority = document.getElementById('priorityLevel').value;
            const dueDate = document.getElementById('dueDate').value;

            let internId = '';

            if (assignmentType === 'individual') {
                const internSelect = document.getElementById('individualIntern');
                if (!internSelect.value) {
                    alert('Please select an intern');
                    return;
                }
                internId = internSelect.value;
            } else {
                const checkedBoxes = document.querySelectorAll('input[name="groupMembers"]:checked');
                if (checkedBoxes.length === 0) {
                    alert('Please select at least one group member');
                    return;
                }
                // For now, use first member's ID. In a full implementation, you'd handle group assignments differently
                internId = checkedBoxes[0].value;
            }

            const taskData = {
                intern_id: internId,
                title: title,
                description: description,
                priority: priority === 'high' ? 'High' : (priority === 'medium' ? 'Medium' : 'Low'),
                due_date: dueDate,
                _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            };

            fetch('/admin/tasks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': taskData._token
                },
                body: JSON.stringify(taskData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Task created successfully!');
                    closeNewTaskModal();
                    // Reload the page to show the new task
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to create task'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error creating task: ' + error.message);
            });
        }

        // View task details
        function viewTaskDetails(taskId) {
            // Sample task data - in a real app, this would come from a server or data store
            const tasks = {
                1: {
                    title: 'Database Schema Design',
                    description: 'Design and implement the database schema for the new management system',
                    assignedTo: 'Development Team (3)',
                    type: 'Group',
                    members: [
                        { name: 'Kingsley Laran', isLeader: true, school: 'UP Cebu' },
                        { name: 'Julliana Laurena', isLeader: false, school: 'UP Cebu' },
                        { name: 'Ruther Marte', isLeader: false, school: 'UP Cebu' }
                    ],
                    school: 'UP Cebu',
                    dueDate: '2024-02-15',
                    priority: 'High',
                    progress: 75,
                    status: 'In Progress'
                },
                2: {
                    title: 'UI/UX Research',
                    description: 'Conduct user research and create wireframes for the dashboard',
                    assignedTo: 'Julliana Laurena',
                    type: 'Individual',
                    school: 'UP Cebu',
                    dueDate: '2024-02-10',
                    priority: 'Medium',
                    progress: 100,
                    status: 'Completed'
                }
            };

            const task = tasks[taskId] || tasks[1]; // Default to first task if not found
            
            let membersHtml = '';
            if (task.type === 'Group' && task.members) {
                membersHtml = `
                    <div class="detail-section">
                        <div class="detail-label">Group Members</div>
                        <div class="assigned-members">
                            ${task.members.map(member => `
                                <span class="member-badge ${member.isLeader ? 'leader' : ''}">
                                    ${member.name} ${member.isLeader ? '(Leader)' : ''}
                                </span>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            const detailsHtml = `
                <div class="detail-section">
                    <div class="detail-label">Task Title</div>
                    <div class="detail-value">${task.title}</div>
                </div>
                <div class="detail-section">
                    <div class="detail-label">Description</div>
                    <div class="detail-value">${task.description}</div>
                </div>
                <div class="detail-section">
                    <div class="detail-label">Assignment Type</div>
                    <div class="detail-value">${task.type}</div>
                </div>
                <div class="detail-section">
                    <div class="detail-label">Assigned To</div>
                    <div class="detail-value">${task.assignedTo}</div>
                </div>
                ${membersHtml}
                <div class="detail-section">
                    <div class="detail-label">School</div>
                    <div class="detail-value">${task.school}</div>
                </div>
                <div class="detail-section">
                    <div class="detail-label">Due Date</div>
                    <div class="detail-value">${new Date(task.dueDate).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</div>
                </div>
                <div class="detail-section">
                    <div class="detail-label">Priority</div>
                    <div class="detail-value">
                        <span class="priority-badge priority-${task.priority.toLowerCase()}">${task.priority}</span>
                    </div>
                </div>
                <div class="detail-section">
                    <div class="detail-label">Progress</div>
                    <div class="detail-value">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="flex: 1; background: #f3f4f6; border-radius: 4px; height: 8px; overflow: hidden;">
                                <div style="width: ${task.progress}%; background: #7B1D3A; height: 100%;"></div>
                            </div>
                            <span>${task.progress}%</span>
                        </div>
                    </div>
                </div>
                <div class="detail-section">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="status-badge status-${task.status.toLowerCase().replace(' ', '-')}">${task.status}</span>
                    </div>
                </div>
            `;

            document.getElementById('taskDetailsContent').innerHTML = detailsHtml;
            document.getElementById('viewTaskModal').classList.add('active');
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

        // Bar Chart - Intern Progress
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Active Interns',
                    data: [185, 195, 210, 225, 235, 245],
                    backgroundColor: 'rgba(123, 29, 58, 0.8)',
                    borderColor: '#7B1D3A',
                    borderWidth: 2,
                    borderRadius: 8
                }, {
                    label: 'Completed Tasks',
                    data: [165, 180, 190, 205, 215, 230],
                    backgroundColor: 'rgba(255, 191, 0, 0.8)',
                    borderColor: '#FFBF00',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Pie Chart - System Usage
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Intern Management', 'Research Tracking', 'Digital Records', 'Incubatee Tracker', 'Scheduler'],
                datasets: [{
                    data: [35, 25, 20, 12, 8],
                    backgroundColor: [
                        '#7B1D3A',
                        '#FFBF00',
                        '#FFA500',
                        '#10B981',
                        '#3B82F6'
                    ],
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
