<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Team Leader Dashboard - {{ $school->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --maroon: #7B1113;
            --maroon-dark: #5a0d0f;
            --maroon-light: #9a1517;
            --forest-green: #228B22;
            --forest-green-dark: #1a6b1a;
            --forest-green-light: #2ea02e;
            --gold: #FFD700;
            --gold-dark: #d4af00;
            --gold-light: #ffe44d;
            --white: #FFFFFF;
            --off-white: #FAFAFA;
            --light-gray: #F5F5F5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            background: var(--light-gray);
            -webkit-font-smoothing: antialiased;
        }

        /* Spin animation for refresh icon */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .fa-spin {
            animation: spin 1s linear infinite;
        }

        /* Pulse animation for updated elements */
        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(34, 139, 34, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(34, 139, 34, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 139, 34, 0); }
        }
        .data-updated {
            animation: pulse-green 0.6s ease-out;
        }

        /* Live indicator */
        .live-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: rgba(34, 139, 34, 0.15);
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            color: var(--forest-green);
        }
        .live-indicator::before {
            content: '';
            width: 8px;
            height: 8px;
            background: var(--forest-green);
            border-radius: 50%;
            animation: pulse-dot 2s infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            width: 280px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 30px rgba(123, 17, 19, 0.2);
        }

        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
        .sidebar::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 3px; }

        .sidebar-logo {
            padding: 32px 24px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 215, 0, 0.2);
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.1) 0%, transparent 100%);
        }

        .sidebar-logo img {
            height: 70px;
            margin: 0 auto 16px;
            display: block;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        .sidebar-logo h3 {
            color: var(--white);
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .sidebar-logo p {
            color: var(--gold);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .sidebar-menu {
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            min-height: calc(100vh - 180px);
        }

        .menu-section {
            padding: 12px 24px 8px;
            color: rgba(255, 215, 0, 0.6);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            margin: 3px 14px;
            border-radius: 12px;
            cursor: pointer;
        }

        .menu-item i:first-child {
            width: 24px;
            margin-right: 14px;
            font-size: 18px;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, var(--forest-green) 0%, var(--forest-green-dark) 100%);
            box-shadow: 0 4px 20px rgba(34, 139, 34, 0.4);
        }

        .menu-item.active i:first-child { color: var(--gold); }

        .menu-badge {
            background: var(--gold);
            color: var(--maroon);
            font-size: 10px;
            padding: 3px 10px;
            border-radius: 12px;
            margin-left: auto;
            font-weight: 700;
        }

        .logout-btn {
            margin: 20px 14px;
            padding: 14px 24px;
            background: rgba(255, 255, 255, 0.1);
            color: var(--gold);
            border: 2px solid rgba(255, 215, 0, 0.3);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 215, 0, 0.15);
            border-color: var(--gold);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }

        .top-header {
            background: var(--white);
            padding: 20px 36px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--maroon);
        }

        .header-subtitle {
            font-size: 14px;
            color: #6B7280;
            margin-top: 4px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 8px 16px;
            background: var(--off-white);
            border-radius: 16px;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 700;
        }

        .user-name { font-weight: 700; color: #1F2937; font-size: 14px; }
        .user-role { font-size: 12px; color: var(--forest-green); font-weight: 600; }

        .page-content {
            display: none;
            padding: 36px;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .page-content.active { display: block; }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--white);
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border-left: 5px solid;
            transition: transform 0.3s ease;
        }

        .stat-card:hover { transform: translateY(-4px); }
        .stat-card.maroon { border-left-color: var(--maroon); }
        .stat-card.green { border-left-color: var(--forest-green); }
        .stat-card.gold { border-left-color: var(--gold-dark); }
        .stat-card.red { border-left-color: #DC2626; }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 16px;
        }

        .stat-icon.maroon { background: rgba(123, 17, 19, 0.1); color: var(--maroon); }
        .stat-icon.green { background: rgba(34, 139, 34, 0.1); color: var(--forest-green); }
        .stat-icon.gold { background: rgba(255, 215, 0, 0.2); color: var(--gold-dark); }
        .stat-icon.red { background: rgba(220, 38, 38, 0.1); color: #DC2626; }

        .stat-value { font-size: 32px; font-weight: 800; color: #1F2937; }
        .stat-label { font-size: 13px; color: #6B7280; font-weight: 600; margin-top: 4px; }

        /* Cards */
        .card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--off-white);
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--maroon);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i { color: var(--forest-green); }
        .card-body { padding: 24px; }

        /* Tables */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            text-align: left;
            padding: 12px 16px;
            background: var(--off-white);
            color: var(--maroon);
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table td {
            padding: 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-size: 14px;
        }
        .data-table tr:hover { background: rgba(34, 139, 34, 0.03); }

        /* Badges */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-success { background: rgba(34, 139, 34, 0.1); color: var(--forest-green); }
        .badge-warning { background: rgba(255, 215, 0, 0.3); color: #92400E; }
        .badge-danger { background: rgba(220, 38, 38, 0.1); color: #DC2626; }
        .badge-info { background: rgba(123, 17, 19, 0.1); color: var(--maroon); }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(123, 17, 19, 0.3);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(123, 17, 19, 0.4); }

        .btn-success {
            background: linear-gradient(135deg, var(--forest-green) 0%, var(--forest-green-dark) 100%);
            color: var(--white);
        }
        .btn-success:hover { transform: translateY(-2px); }

        .btn-secondary {
            background: var(--off-white);
            color: var(--maroon);
            border: 2px solid rgba(123, 17, 19, 0.1);
        }
        .btn-secondary:hover { border-color: var(--maroon); }

        .btn-danger {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: var(--white);
        }

        .btn-sm { padding: 7px 14px; font-size: 12px; }

        /* Progress */
        .progress-container {
            width: 100%;
            height: 8px;
            background: rgba(0, 0, 0, 0.06);
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-bar { height: 100%; border-radius: 4px; }
        .progress-bar.green { background: var(--forest-green); }
        .progress-bar.gold { background: var(--gold-dark); }
        .progress-bar.red { background: #DC2626; }

        /* Grid */
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
        @media (max-width: 900px) { .grid-2 { grid-template-columns: 1fr; } }

        /* Quick Actions */
        .quick-actions { display: flex; gap: 14px; margin-bottom: 24px; flex-wrap: wrap; }

        /* List Item */
        .list-item {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .list-item:last-child { border-bottom: none; }
        .list-item:hover { background: rgba(34, 139, 34, 0.02); }

        .list-item-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 700;
            margin-right: 16px;
            font-size: 13px;
        }

        .list-item-content { flex: 1; }
        .list-item-title { font-weight: 600; color: #1F2937; margin-bottom: 4px; }
        .list-item-subtitle { font-size: 12px; color: #6B7280; }

        /* Alert */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .alert-warning { background: rgba(255, 215, 0, 0.15); border-left: 4px solid var(--gold-dark); color: #92400E; }
        .alert-success { background: rgba(34, 139, 34, 0.1); border-left: 4px solid var(--forest-green); color: #065F46; }
        .alert-danger { background: rgba(220, 38, 38, 0.1); border-left: 4px solid #DC2626; color: #991B1B; }

        /* Empty State */
        .empty-state { text-align: center; padding: 48px 24px; color: #6B7280; }
        .empty-state i { font-size: 48px; margin-bottom: 16px; color: rgba(123, 17, 19, 0.2); }
        .empty-state h4 { font-size: 16px; font-weight: 700; color: var(--maroon); margin-bottom: 8px; }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            backdrop-filter: blur(4px);
        }

        .modal-overlay.active { display: flex; }

        .modal {
            background: var(--white);
            border-radius: 20px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlide 0.3s ease;
        }

        .modal.modal-lg { max-width: 800px; }

        @keyframes modalSlide {
            from { opacity: 0; transform: translateY(-20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-header {
            padding: 24px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            color: var(--white);
        }

        .modal-header h3 {
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-header h3 i { color: var(--gold); }

        .modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: var(--white);
            width: 36px;
            height: 36px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        .modal-close:hover { background: rgba(255, 255, 255, 0.3); }

        .modal-body {
            padding: 24px;
            overflow-y: auto;
            max-height: calc(90vh - 150px);
        }

        .modal-footer {
            padding: 20px 24px;
            border-top: 1px solid rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: var(--off-white);
        }

        /* Form Styles */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid rgba(0, 0, 0, 0.08);
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            font-family: inherit;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--maroon);
            box-shadow: 0 0 0 4px rgba(123, 17, 19, 0.1);
        }
        .form-textarea { resize: vertical; min-height: 100px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 500px) { .form-row { grid-template-columns: 1fr; } }

        /* Info Grid */
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 20px; }
        .info-item label { font-size: 12px; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-item p { font-size: 15px; font-weight: 600; color: #1F2937; margin-top: 4px; }

        /* Task Overview */
        .task-overview-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; text-align: center; }
        .task-overview-item { padding: 20px; background: var(--off-white); border-radius: 12px; }
        .task-overview-value { font-size: 36px; font-weight: 800; margin-bottom: 4px; }
        .task-overview-label { color: #6B7280; font-weight: 600; font-size: 13px; }
        @media (max-width: 768px) { .task-overview-grid { grid-template-columns: repeat(2, 1fr); } }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 16px 24px;
            background: var(--forest-green);
            color: white;
            border-radius: 12px;
            font-weight: 600;
            z-index: 3000;
            display: none;
            animation: toastSlide 0.3s ease;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }
        .toast.error { background: #DC2626; }
        .toast.active { display: flex; align-items: center; gap: 10px; }
        @keyframes toastSlide { from { transform: translateX(100%); } to { transform: translateX(0); } }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/UP logo.png') }}" alt="UP Cebu Logo">
            <h3>UP Cebu Incubator</h3>
            <p>Team Leader Portal</p>
        </div>

        <div class="sidebar-menu">
            <div class="menu-section">Main</div>
            <a class="menu-item active" data-page="dashboard">
                <i class="fas fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>

            <div class="menu-section">Management</div>
            <a class="menu-item" data-page="interns">
                <i class="fas fa-users"></i>
                <span>My Interns</span>
                <span class="menu-badge">{{ $totalInterns }}</span>
            </a>
            <a class="menu-item" data-page="tasks">
                <i class="fas fa-tasks"></i>
                <span>Task Management</span>
                @if($overdueTasks > 0)
                    <span class="menu-badge" style="background: #DC2626; color: white;">{{ $overdueTasks }}</span>
                @endif
            </a>
            <a class="menu-item" data-page="attendance">
                <i class="fas fa-calendar-check"></i>
                <span>Attendance</span>
            </a>

            <div class="menu-section">Reports</div>
            <a class="menu-item" data-page="reports">
                <i class="fas fa-file-alt"></i>
                <span>My Reports</span>
                @if($pendingReports > 0)
                    <span class="menu-badge" style="background: var(--gold-dark); color: var(--maroon);">{{ $pendingReports }}</span>
                @endif
            </a>

            @if(count($viewableModules) > 0)
            <div class="menu-section" style="margin-top: 20px; border-top: 1px solid rgba(255, 215, 0, 0.2); padding-top: 20px;">
                <i class="fas fa-key" style="margin-right: 4px;"></i> Granted Access
            </div>

            @if(in_array('scheduler', $viewableModules))
            <a class="menu-item" data-page="scheduler">
                <i class="fas fa-calendar-alt"></i>
                <span>Scheduler</span>
                @if(isset($schedulerData['pendingBookings']) && $schedulerData['pendingBookings'] > 0)
                    <span class="menu-badge" style="background: var(--gold-dark); color: var(--maroon);">{{ $schedulerData['pendingBookings'] }}</span>
                @endif
            </a>
            @endif

            @if(in_array('research_tracking', $viewableModules))
            <a class="menu-item" data-page="research-tracking">
                <i class="fas fa-flask"></i>
                <span>Research Tracking</span>
            </a>
            @endif

            @if(in_array('incubatee_tracker', $viewableModules))
            <a class="menu-item" data-page="incubatee-tracker">
                <i class="fas fa-rocket"></i>
                <span>Incubatee Tracker</span>
                @if(isset($incubateeData['pendingSubmissions']) && $incubateeData['pendingSubmissions'] > 0)
                    <span class="menu-badge" style="background: var(--gold-dark); color: var(--maroon);">{{ $incubateeData['pendingSubmissions'] }}</span>
                @endif
            </a>
            @endif

            @if(in_array('issues_management', $viewableModules))
            <a class="menu-item" data-page="issues-management">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Issues & Complaints</span>
                @if(isset($issuesData['pendingIssues']) && $issuesData['pendingIssues'] > 0)
                    <span class="menu-badge" style="background: #DC2626; color: white;">{{ $issuesData['pendingIssues'] }}</span>
                @endif
            </a>
            @endif

            @if(in_array('digital_records', $viewableModules))
            <a class="menu-item" data-page="digital-records">
                <i class="fas fa-file-alt"></i>
                <span>Digital Records</span>
            </a>
            @endif
            @endif

            <div style="flex-grow: 1;"></div>

            <form action="{{ route('admin.logout') }}" method="POST" id="logoutForm">
                @csrf
                <input type="hidden" name="redirect_to" value="intern">
            </form>
            <button type="button" class="logout-btn" onclick="document.getElementById('logoutForm').submit()">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-header">
            <div>
                <h1 class="header-title" id="pageTitle">Dashboard</h1>
                <p class="header-subtitle">{{ $school->name }}</p>
            </div>
            <div style="display: flex; align-items: center; gap: 20px;">
                <!-- Auto-refresh indicator -->
                <div id="refreshIndicator" style="display: flex; align-items: center; gap: 12px; padding: 8px 16px; background: rgba(34, 139, 34, 0.08); border-radius: 12px; font-size: 12px; border: 1px solid rgba(34, 139, 34, 0.15);">
                    <span class="live-indicator">LIVE</span>
                    <i class="fas fa-sync-alt" id="refreshIcon" style="color: var(--forest-green);"></i>
                    <span style="color: #6B7280;">Updated: <strong id="lastUpdatedTime" style="color: var(--forest-green);">Just now</strong></span>
                    <button onclick="manualRefresh()" style="background: var(--forest-green); color: white; border: none; padding: 5px 12px; border-radius: 8px; font-size: 11px; cursor: pointer; font-weight: 600; transition: all 0.2s;" title="Refresh now" onmouseover="this.style.background='var(--forest-green-dark)'" onmouseout="this.style.background='var(--forest-green)'">
                        <i class="fas fa-redo"></i> Refresh
                    </button>
                </div>
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div>
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-role">Team Leader</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== DASHBOARD PAGE ==================== -->
        <div id="dashboard" class="page-content active">
            @if(session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif

            <div class="stats-grid">
                <div class="stat-card maroon">
                    <div class="stat-icon maroon"><i class="fas fa-users"></i></div>
                    <div class="stat-value">{{ $totalInterns }}</div>
                    <div class="stat-label">Total Interns</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
                    <div class="stat-value">{{ $allInterns->where('status', 'Active')->count() }}</div>
                    <div class="stat-label">Active Interns</div>
                </div>
                <div class="stat-card gold">
                    <div class="stat-icon gold"><i class="fas fa-tasks"></i></div>
                    <div class="stat-value">{{ $totalTasks }}</div>
                    <div class="stat-label">Total Tasks</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-value">{{ $completedTasks }}</div>
                    <div class="stat-label">Completed Tasks</div>
                </div>
                <div class="stat-card red">
                    <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="stat-value">{{ $overdueTasks }}</div>
                    <div class="stat-label">Overdue Tasks</div>
                </div>
                <div class="stat-card maroon">
                    <div class="stat-icon maroon"><i class="fas fa-clock"></i></div>
                    <div class="stat-value">{{ $presentToday }}</div>
                    <div class="stat-label">Present Today</div>
                </div>
            </div>

            @if($overdueTasks > 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>You have <strong>{{ $overdueTasks }}</strong> overdue task(s) that need attention.</span>
                </div>
            @endif

            <div class="quick-actions">
                <button class="btn btn-primary" onclick="openCreateTaskModal()">
                    <i class="fas fa-plus"></i> Create New Task
                </button>
                <button class="btn btn-success" onclick="openCreateReportModal()">
                    <i class="fas fa-file-alt"></i> Create Report
                </button>
            </div>

            <div class="grid-2">
                <!-- Recent Tasks -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Recent Tasks</h3>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        @forelse($recentTasks as $task)
                            @php $isPendingAdminApproval = $task->status === 'Completed' && empty($task->completed_date); @endphp
                            <div class="list-item" style="cursor: pointer;" onclick="editTask({{ $task->id }})">
                                <div class="list-item-avatar"><i class="fas fa-clipboard-list"></i></div>
                                <div class="list-item-content">
                                    <div class="list-item-title">{{ $task->title }}</div>
                                    <div class="list-item-subtitle">
                                        {{ $task->intern->name ?? 'N/A' }} • Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No date' }}
                                    </div>
                                </div>
                                <span class="badge badge-{{ $isPendingAdminApproval ? 'info' : ($task->status === 'Completed' ? 'success' : ($task->status === 'In Progress' ? 'info' : 'warning')) }}">
                                    {{ $isPendingAdminApproval ? 'Pending Admin Approval' : $task->status }}
                                </span>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list"></i>
                                <h4>No tasks yet</h4>
                                <p>Create your first task to get started</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Interns Needing Attention -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user-clock"></i> Interns Needing Attention</h3>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        @forelse($internsNeedingAttention as $intern)
                            @php $progress = $intern->required_hours > 0 ? round(($intern->completed_hours / $intern->required_hours) * 100, 1) : 0; @endphp
                            <div class="list-item" style="cursor: pointer;" onclick="viewIntern({{ $intern->id }})">
                                <div class="list-item-avatar">{{ strtoupper(substr($intern->name, 0, 1)) }}</div>
                                <div class="list-item-content">
                                    <div class="list-item-title">{{ $intern->name }}</div>
                                    <div class="list-item-subtitle">{{ $intern->course }} • {{ number_format($intern->completed_hours, 1) }} / {{ $intern->required_hours }} hrs</div>
                                    <div class="progress-container" style="margin-top: 8px;">
                                        <div class="progress-bar {{ $progress < 30 ? 'red' : 'gold' }}" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                                <span class="badge badge-warning">{{ $progress }}%</span>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-user-check"></i>
                                <h4>All interns on track!</h4>
                                <p>Everyone is making good progress</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Task Overview -->
            <div class="card" style="margin-top: 24px;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Task Overview</h3>
                </div>
                <div class="card-body">
                    <div class="task-overview-grid">
                        <div class="task-overview-item">
                            <div class="task-overview-value" style="color: var(--gold-dark);">{{ $pendingTasks }}</div>
                            <div class="task-overview-label">Pending</div>
                        </div>
                        <div class="task-overview-item">
                            <div class="task-overview-value" style="color: var(--maroon);">{{ $inProgressTasks }}</div>
                            <div class="task-overview-label">In Progress</div>
                        </div>
                        <div class="task-overview-item">
                            <div class="task-overview-value" style="color: var(--forest-green);">{{ $completedTasks }}</div>
                            <div class="task-overview-label">Completed</div>
                        </div>
                        <div class="task-overview-item">
                            <div class="task-overview-value" style="color: #DC2626;">{{ $overdueTasks }}</div>
                            <div class="task-overview-label">Overdue</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="card" style="margin-top: 24px;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-file-alt"></i> Recent Reports</h3>
                </div>
                <div class="card-body" style="padding: 0;">
                    @forelse($recentReports as $report)
                        <div class="list-item" style="cursor: pointer;" onclick="viewReport({{ $report->id }})">
                            <div class="list-item-avatar" style="background: linear-gradient(135deg, var(--forest-green) 0%, var(--forest-green-dark) 100%);">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="list-item-content">
                                <div class="list-item-title">{{ $report->title }}</div>
                                <div class="list-item-subtitle">{{ ucfirst($report->report_type) }} • {{ $report->created_at->format('M d, Y') }}</div>
                            </div>
                            <span class="badge badge-{{ $report->status === 'submitted' ? 'info' : ($report->status === 'reviewed' ? 'success' : 'warning') }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-file-alt"></i>
                            <h4>No reports yet</h4>
                            <p>Create your first report to submit to admin</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ==================== INTERNS PAGE ==================== -->
        <div id="interns" class="page-content">
            <div class="stats-grid">
                <div class="stat-card maroon">
                    <div class="stat-icon maroon"><i class="fas fa-users"></i></div>
                    <div class="stat-value">{{ $allInterns->count() }}</div>
                    <div class="stat-label">Total Interns</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
                    <div class="stat-value">{{ $allInterns->where('status', 'Active')->count() }}</div>
                    <div class="stat-label">Active</div>
                </div>
                <div class="stat-card gold">
                    <div class="stat-icon gold"><i class="fas fa-user-graduate"></i></div>
                    <div class="stat-value">{{ $allInterns->where('status', 'Completed')->count() }}</div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users"></i> My Interns</h3>
                </div>
                <div class="card-body" style="padding: 0; overflow-x: auto;">
                    @if($allInterns->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Intern</th>
                                <th>Course</th>
                                <th>Hours Progress</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allInterns as $intern)
                            @php $progress = $intern->required_hours > 0 ? round(($intern->completed_hours / $intern->required_hours) * 100, 1) : 0; @endphp
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div class="list-item-avatar" style="width: 36px; height: 36px; font-size: 12px; margin: 0;">
                                            {{ strtoupper(substr($intern->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600;">{{ $intern->name }}</div>
                                            <div style="font-size: 12px; color: #6B7280;">{{ $intern->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $intern->course ?? 'N/A' }}</td>
                                <td style="min-width: 140px;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="progress-container" style="flex: 1;">
                                            <div class="progress-bar {{ $progress < 30 ? 'red' : ($progress < 70 ? 'gold' : 'green') }}" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span style="font-size: 12px; font-weight: 600;">{{ $progress }}%</span>
                                    </div>
                                    <div style="font-size: 11px; color: #6B7280; margin-top: 4px;">
                                        {{ number_format($intern->completed_hours, 1) }} / {{ $intern->required_hours }} hrs
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $intern->status === 'Active' ? 'success' : ($intern->status === 'Completed' ? 'info' : 'warning') }}">
                                        {{ $intern->status }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-secondary" onclick="viewIntern({{ $intern->id }})" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <h4>No interns yet</h4>
                        <p>Interns assigned to your school will appear here</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ==================== TASKS PAGE ==================== -->
        <div id="tasks" class="page-content">
            <div class="stats-grid">
                <div class="stat-card gold">
                    <div class="stat-icon gold"><i class="fas fa-clock"></i></div>
                    <div class="stat-value">{{ $pendingTasks }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card maroon">
                    <div class="stat-icon maroon"><i class="fas fa-spinner"></i></div>
                    <div class="stat-value">{{ $inProgressTasks }}</div>
                    <div class="stat-label">In Progress</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-value">{{ $completedTasks }}</div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-card red">
                    <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="stat-value">{{ $overdueTasks }}</div>
                    <div class="stat-label">Overdue</div>
                </div>
            </div>

            <div class="quick-actions">
                <button class="btn btn-primary" onclick="openCreateTaskModal()">
                    <i class="fas fa-plus"></i> Create New Task
                </button>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-tasks"></i> All Tasks</h3>
                </div>
                <div class="card-body" style="padding: 0; overflow-x: auto;">
                    @if($allTasks->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Assigned To</th>
                                <th>Priority</th>
                                <th>Due Date</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allTasks as $task)
                            @php $isOverdue = $task->due_date && $task->due_date < now() && $task->status !== 'Completed'; @endphp
                            <tr style="{{ $isOverdue ? 'background: rgba(220, 38, 38, 0.03);' : '' }}">
                                <td>
                                    <div style="font-weight: 600;">{{ Str::limit($task->title, 30) }}</div>
                                    @if($isOverdue)
                                    <span style="font-size: 11px; color: #DC2626; font-weight: 600;">
                                        <i class="fas fa-exclamation-circle"></i> Overdue
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 26px; height: 26px; border-radius: 8px; background: var(--maroon); color: white; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 600;">
                                            {{ strtoupper(substr($task->intern->name ?? 'N', 0, 1)) }}
                                        </div>
                                        <span style="font-size: 13px;">{{ $task->intern->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="background: {{ $task->priority === 'Urgent' ? '#DC2626' : ($task->priority === 'High' ? '#F59E0B' : ($task->priority === 'Medium' ? 'var(--gold)' : 'var(--forest-green)')) }}; color: {{ $task->priority === 'Medium' ? 'var(--maroon)' : 'white' }};">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td>
                                    @if($task->due_date)
                                    <span style="{{ $isOverdue ? 'color: #DC2626; font-weight: 600;' : '' }}">
                                        {{ $task->due_date->format('M d, Y') }}
                                    </span>
                                    @else
                                    <span style="color: #9CA3AF;">No due date</span>
                                    @endif
                                </td>
                                <td style="min-width: 100px;">
                                    <div class="progress-container" style="margin-bottom: 4px;">
                                        <div class="progress-bar green" style="width: {{ $task->progress ?? 0 }}%"></div>
                                    </div>
                                    <span style="font-size: 11px; color: #6B7280;">{{ $task->progress ?? 0 }}%</span>
                                </td>
                                <td>
                                    @php $isPendingAdminApproval = $task->status === 'Completed' && empty($task->completed_date); @endphp
                                    <span class="badge badge-{{ $isPendingAdminApproval ? 'info' : ($task->status === 'Completed' ? 'success' : ($task->status === 'In Progress' ? 'info' : ($task->status === 'On Hold' ? 'danger' : 'warning'))) }}">
                                        {{ $isPendingAdminApproval ? 'Pending Admin Approval' : $task->status }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <button class="btn btn-sm btn-secondary" onclick="editTask({{ $task->id }})" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteTask({{ $task->id }})" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-tasks"></i>
                        <h4>No tasks yet</h4>
                        <p>Create your first task to get started</p>
                        <button class="btn btn-primary" style="margin-top: 16px;" onclick="openCreateTaskModal()">
                            <i class="fas fa-plus"></i> Create Task
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ==================== ATTENDANCE PAGE ==================== -->
        <div id="attendance" class="page-content">
            <div class="stats-grid">
                <div class="stat-card green">
                    <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
                    <div class="stat-value">{{ $presentToday }}</div>
                    <div class="stat-label">Present Today</div>
                </div>
                <div class="stat-card red">
                    <div class="stat-icon red"><i class="fas fa-user-times"></i></div>
                    <div class="stat-value">{{ $absentToday }}</div>
                    <div class="stat-label">Absent Today</div>
                </div>
                <div class="stat-card gold">
                    <div class="stat-icon gold"><i class="fas fa-clock"></i></div>
                    <div class="stat-value">{{ $lateToday }}</div>
                    <div class="stat-label">Late Today</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-check"></i> Today's Attendance - {{ $today->format('F d, Y') }}</h3>
                </div>
                <div class="card-body" style="padding: 0; overflow-x: auto;">
                    @if($todayAttendances->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Intern</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Hours Worked</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todayAttendances as $attendance)
                            @php
                                $timeIn = $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in) : null;
                                $timeOut = $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out) : null;
                                $hoursWorked = $timeIn && $timeOut ? round($timeOut->diffInMinutes($timeIn) / 60, 2) : 0;
                                $isLate = $timeIn && $timeIn->format('H:i') > '08:00';
                            @endphp
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div class="list-item-avatar" style="width: 36px; height: 36px; font-size: 12px; margin: 0;">
                                            {{ strtoupper(substr($attendance->intern->name ?? 'N', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600;">{{ $attendance->intern->name ?? 'N/A' }}</div>
                                            <div style="font-size: 12px; color: #6B7280;">{{ $attendance->intern->course ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($timeIn)
                                    <span style="{{ $isLate ? 'color: #F59E0B; font-weight: 600;' : '' }}">
                                        {{ $timeIn->format('h:i A') }}
                                        @if($isLate) <i class="fas fa-exclamation-circle"></i> @endif
                                    </span>
                                    @else <span style="color: #9CA3AF;">--:--</span>
                                    @endif
                                </td>
                                <td>
                                    @if($timeOut) {{ $timeOut->format('h:i A') }}
                                    @else <span style="color: var(--forest-green); font-weight: 500;">Still working</span>
                                    @endif
                                </td>
                                <td>
                                    @if($hoursWorked > 0)
                                    <span style="font-weight: 600; color: var(--maroon);">{{ number_format($hoursWorked, 1) }} hrs</span>
                                    @else <span style="color: #9CA3AF;">--</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $attendance->status === 'Present' ? 'success' : 'danger' }}">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-calendar-check"></i>
                        <h4>No attendance records for today</h4>
                        <p>Interns will appear here when they time in</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ==================== REPORTS PAGE ==================== -->
        <div id="reports" class="page-content">
            <div class="stats-grid">
                <div class="stat-card maroon">
                    <div class="stat-icon maroon"><i class="fas fa-file-alt"></i></div>
                    <div class="stat-value">{{ $allReports->count() }}</div>
                    <div class="stat-label">Total Reports</div>
                </div>
                <div class="stat-card gold">
                    <div class="stat-icon gold"><i class="fas fa-edit"></i></div>
                    <div class="stat-value">{{ $allReports->where('status', 'draft')->count() }}</div>
                    <div class="stat-label">Drafts</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon green"><i class="fas fa-paper-plane"></i></div>
                    <div class="stat-value">{{ $allReports->where('status', 'submitted')->count() }}</div>
                    <div class="stat-label">Submitted</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon green"><i class="fas fa-check-double"></i></div>
                    <div class="stat-value">{{ $allReports->where('status', 'reviewed')->count() }}</div>
                    <div class="stat-label">Reviewed</div>
                </div>
            </div>

            <div class="quick-actions">
                <button class="btn btn-success" onclick="openCreateReportModal()">
                    <i class="fas fa-plus"></i> Create New Report
                </button>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-file-alt"></i> My Reports</h3>
                </div>
                <div class="card-body" style="padding: 0; overflow-x: auto;">
                    @if($allReports->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Period</th>
                                <th>Created</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allReports as $report)
                            <tr>
                                <td><div style="font-weight: 600;">{{ Str::limit($report->title, 35) }}</div></td>
                                <td><span class="badge badge-info">{{ ucfirst($report->report_type) }}</span></td>
                                <td>
                                    @if($report->period_start && $report->period_end)
                                    {{ \Carbon\Carbon::parse($report->period_start)->format('M d') }} - {{ \Carbon\Carbon::parse($report->period_end)->format('M d, Y') }}
                                    @else <span style="color: #9CA3AF;">--</span>
                                    @endif
                                </td>
                                <td>{{ $report->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $report->status === 'reviewed' ? 'success' : ($report->status === 'submitted' ? 'info' : 'warning') }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <button class="btn btn-sm btn-secondary" onclick="viewReport({{ $report->id }})" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($report->status === 'draft')
                                        <button class="btn btn-sm btn-primary" onclick="editReport({{ $report->id }})" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteReport({{ $report->id }})" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-file-alt"></i>
                        <h4>No reports yet</h4>
                        <p>Create your first report to submit to admin</p>
                        <button class="btn btn-success" style="margin-top: 16px;" onclick="openCreateReportModal()">
                            <i class="fas fa-plus"></i> Create Report
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ==================== GRANTED ACCESS PAGES ==================== --}}

        {{-- SCHEDULER PAGE --}}
        @if(in_array('scheduler', $viewableModules))
        <div id="scheduler" class="page-content">
            <div class="stats-grid">
                <div class="stat-card maroon">
                    <div class="stat-icon maroon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="stat-value" id="tlTotalBookings">{{ isset($schedulerData['bookings']) ? $schedulerData['bookings']->count() : 0 }}</div>
                    <div class="stat-label">Total Bookings</div>
                </div>
                <div class="stat-card gold">
                    <div class="stat-icon gold"><i class="fas fa-clock"></i></div>
                    <div class="stat-value" id="tlPendingBookings">{{ $schedulerData['pendingBookings'] ?? 0 }}</div>
                    <div class="stat-label">Pending Bookings</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon green"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-value" id="tlTotalEvents">{{ isset($schedulerData['events']) ? $schedulerData['events']->count() : 0 }}</div>
                    <div class="stat-label">Total Events</div>
                </div>
                <div class="stat-card red">
                    <div class="stat-icon red"><i class="fas fa-ban"></i></div>
                    <div class="stat-value" id="tlBlockedDates">{{ isset($schedulerData['blockedDates']) ? $schedulerData['blockedDates']->count() : 0 }}</div>
                    <div class="stat-label">Blocked Dates</div>
                </div>
            </div>

            <!-- Calendar View -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar"></i> Schedule Calendar</h3>
                    <div style="display: flex; gap: 12px; align-items: center;">
                        @if(in_array('scheduler', $editableModules))
                        <button onclick="tlShowCreateEventModal()" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Event
                        </button>
                        <button onclick="tlOpenBlockDateModal()" class="btn btn-danger btn-sm">
                            <i class="fas fa-ban"></i> Block Date
                        </button>
                        <span class="badge badge-success"><i class="fas fa-edit"></i> Edit Access</span>
                        @else
                        <span class="badge badge-info"><i class="fas fa-eye"></i> View Only</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Calendar Header -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <h3 id="tlSchedulerMonthTitle" style="font-size: 20px; font-weight: 700; color: var(--maroon);">January 2026</h3>
                        <div style="display: flex; gap: 8px;">
                            <button onclick="tlPreviousMonth()" style="background: white; border: 2px solid var(--maroon); color: var(--maroon); width: 40px; height: 40px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button onclick="tlNextMonth()" style="background: white; border: 2px solid var(--maroon); color: var(--maroon); width: 40px; height: 40px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Calendar Grid -->
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: #E5E7EB; border: 1px solid #E5E7EB; border-radius: 12px; overflow: hidden;">
                        <!-- Weekday Headers -->
                        <div style="background: var(--maroon); color: white; padding: 12px; text-align: center; font-weight: 700; font-size: 12px;">Sun</div>
                        <div style="background: var(--maroon); color: white; padding: 12px; text-align: center; font-weight: 700; font-size: 12px;">Mon</div>
                        <div style="background: var(--maroon); color: white; padding: 12px; text-align: center; font-weight: 700; font-size: 12px;">Tue</div>
                        <div style="background: var(--maroon); color: white; padding: 12px; text-align: center; font-weight: 700; font-size: 12px;">Wed</div>
                        <div style="background: var(--maroon); color: white; padding: 12px; text-align: center; font-weight: 700; font-size: 12px;">Thu</div>
                        <div style="background: var(--maroon); color: white; padding: 12px; text-align: center; font-weight: 700; font-size: 12px;">Fri</div>
                        <div style="background: var(--maroon); color: white; padding: 12px; text-align: center; font-weight: 700; font-size: 12px;">Sat</div>
                    </div>
                    <div id="tlSchedulerCalendarGrid" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: #E5E7EB; border: 1px solid #E5E7EB; border-radius: 0 0 12px 12px; overflow: hidden; margin-top: 1px;">
                        <!-- Calendar days will be rendered here by JavaScript -->
                    </div>

                    <!-- Legend -->
                    <div style="display: flex; gap: 24px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #E5E7EB; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 16px; height: 16px; background: #DBEAFE; border-left: 3px solid #3B82F6; border-radius: 4px;"></div>
                            <span style="font-size: 13px; color: #6B7280;">Bookings</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 16px; height: 16px; background: rgba(34, 139, 34, 0.2); border-left: 3px solid var(--forest-green); border-radius: 4px;"></div>
                            <span style="font-size: 13px; color: #6B7280;">Events</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 16px; height: 16px; background: #FEE2E2; border-left: 3px solid #DC2626; border-radius: 4px;"></div>
                            <span style="font-size: 13px; color: #6B7280;">Blocked</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events & Bookings List -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Upcoming Events -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-calendar-day"></i> Upcoming Events</h3>
                    </div>
                    <div class="card-body" id="tlUpcomingEventsList" style="max-height: 400px; overflow-y: auto;">
                        <div class="empty-state">
                            <i class="fas fa-calendar-check"></i>
                            <p>No upcoming events</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Recent Bookings</h3>
                    </div>
                    <div class="card-body" id="tlRecentBookingsList" style="max-height: 400px; overflow-y: auto;">
                        <div class="empty-state">
                            <i class="fas fa-calendar-alt"></i>
                            <p>No bookings found</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- RESEARCH TRACKING PAGE --}}
        @if(in_array('research_tracking', $viewableModules))
        <div id="research-tracking" class="page-content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-flask"></i> Research Tracking</h3>
                    @if(in_array('research_tracking', $editableModules))
                    <span class="badge badge-success"><i class="fas fa-edit"></i> Edit Access</span>
                    @else
                    <span class="badge badge-info"><i class="fas fa-eye"></i> View Only</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="empty-state">
                        <i class="fas fa-flask"></i>
                        <h4>Research Tracking Module</h4>
                        <p>You have been granted access to the Research Tracking module. This feature will be fully integrated soon.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- INCUBATEE TRACKER PAGE --}}
        @if(in_array('incubatee_tracker', $viewableModules))
        <div id="incubatee-tracker" class="page-content">
            <div class="stats-grid">
                <div class="stat-card maroon">
                    <div class="stat-icon maroon"><i class="fas fa-rocket"></i></div>
                    <div class="stat-value">{{ $incubateeData['totalSubmissions'] ?? 0 }}</div>
                    <div class="stat-label">Total Submissions</div>
                </div>
                <div class="stat-card gold">
                    <div class="stat-icon gold"><i class="fas fa-hourglass-half"></i></div>
                    <div class="stat-value">{{ $incubateeData['pendingSubmissions'] ?? 0 }}</div>
                    <div class="stat-label">Pending Review</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-rocket"></i> Startup Submissions</h3>
                    @if(in_array('incubatee_tracker', $editableModules))
                    <span class="badge badge-success"><i class="fas fa-edit"></i> Edit Access</span>
                    @else
                    <span class="badge badge-info"><i class="fas fa-eye"></i> View Only</span>
                    @endif
                </div>
                <div class="card-body" style="padding: 0; overflow-x: auto;">
                    @if(isset($incubateeData['submissions']) && $incubateeData['submissions']->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Startup Name</th>
                                <th>Type</th>
                                <th>Contact</th>
                                <th>Submitted</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($incubateeData['submissions'] as $submission)
                            <tr>
                                <td><strong>{{ $submission->startup_name }}</strong></td>
                                <td><span class="badge badge-info">{{ ucfirst($submission->submission_type) }}</span></td>
                                <td>{{ $submission->contact_person }}</td>
                                <td>{{ $submission->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $submission->status === 'approved' ? 'success' : ($submission->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-rocket"></i>
                        <h4>No submissions found</h4>
                        <p>There are no startup submissions to display</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- ISSUES MANAGEMENT PAGE --}}
        @if(in_array('issues_management', $viewableModules))
        <div id="issues-management" class="page-content">
            <div class="stats-grid">
                <div class="stat-card maroon">
                    <div class="stat-icon maroon"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="stat-value">{{ $issuesData['totalIssues'] ?? 0 }}</div>
                    <div class="stat-label">Total Issues</div>
                </div>
                <div class="stat-card gold">
                    <div class="stat-icon gold"><i class="fas fa-clock"></i></div>
                    <div class="stat-value">{{ $issuesData['pendingIssues'] ?? 0 }}</div>
                    <div class="stat-label">Pending Issues</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Room Issues & Complaints</h3>
                    @if(in_array('issues_management', $editableModules))
                    <span class="badge badge-success"><i class="fas fa-edit"></i> Edit Access</span>
                    @else
                    <span class="badge badge-info"><i class="fas fa-eye"></i> View Only</span>
                    @endif
                </div>
                <div class="card-body" style="padding: 0; overflow-x: auto;">
                    @if(isset($issuesData['issues']) && $issuesData['issues']->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Startup</th>
                                <th>Issue Type</th>
                                <th>Description</th>
                                <th>Reported</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($issuesData['issues'] as $issue)
                            <tr>
                                <td><strong>{{ $issue->startup_name }}</strong></td>
                                <td><span class="badge badge-info">{{ ucfirst($issue->issue_type) }}</span></td>
                                <td>{{ Str::limit($issue->description, 50) }}</td>
                                <td>{{ $issue->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $issue->status === 'resolved' ? 'success' : ($issue->status === 'pending' ? 'warning' : 'info') }}">
                                        {{ ucfirst($issue->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h4>No issues found</h4>
                        <p>There are no room issues or complaints to display</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- DIGITAL RECORDS PAGE --}}
        @if(in_array('digital_records', $viewableModules))
        <div id="digital-records" class="page-content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-file-alt"></i> Digital Records</h3>
                    @if(in_array('digital_records', $editableModules))
                    <span class="badge badge-success"><i class="fas fa-edit"></i> Edit Access</span>
                    @else
                    <span class="badge badge-info"><i class="fas fa-eye"></i> View Only</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); margin-bottom: 18px;">
                        <div class="stat-card maroon">
                            <div class="stat-icon maroon"><i class="fas fa-folder"></i></div>
                            <div class="stat-value" id="tl-dr-total-folders">--</div>
                            <div class="stat-label">Total Folders</div>
                        </div>
                        <div class="stat-card green">
                            <div class="stat-icon green"><i class="fas fa-file"></i></div>
                            <div class="stat-value" id="tl-dr-total-files">--</div>
                            <div class="stat-label">Total Files</div>
                        </div>
                        <div class="stat-card gold">
                            <div class="stat-icon gold"><i class="fas fa-hdd"></i></div>
                            <div class="stat-value" id="tl-dr-storage-used">--</div>
                            <div class="stat-label">Storage Used</div>
                        </div>
                        <div class="stat-card red">
                            <div class="stat-icon red"><i class="fas fa-clock"></i></div>
                            <div class="stat-value" id="tl-dr-recent-uploads">--</div>
                            <div class="stat-label">Recent Uploads (7d)</div>
                        </div>
                    </div>

                    @if(!in_array('digital_records', $editableModules))
                    <div class="alert alert-warning" style="margin-bottom: 14px;">
                        <i class="fas fa-eye"></i>
                        <div>
                            <div style="font-weight: 700;">View-only access</div>
                            <div style="font-size: 13px;">You can browse and download files. Editing/deleting is disabled.</div>
                        </div>
                    </div>
                    @endif

                    <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; margin-bottom: 12px;">
                        <div style="font-size: 13px; color: #6B7280; font-weight: 600;">
                            <i class="fas fa-home"></i> <span id="tl-dr-current-path">Root</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                            <button class="btn btn-secondary btn-sm" id="tl-dr-back-btn" style="display:none;" onclick="tlDrGoBack()"><i class="fas fa-arrow-left"></i> Back</button>
                            <input id="tl-dr-search" type="text" class="form-input" placeholder="Search..." style="min-width: 220px;" oninput="tlDrFilter(this.value)">
                            <button class="btn btn-secondary btn-sm" onclick="tlDrRefresh()"><i class="fas fa-sync"></i> Refresh</button>
                            @if(in_array('digital_records', $editableModules))
                            <button class="btn btn-primary btn-sm" onclick="tlOpenCreateFolderModal()"><i class="fas fa-folder-plus"></i> New Folder</button>
                            @endif
                        </div>
                    </div>

                    <div class="card" style="border-radius: 14px; overflow: hidden; border: 1px solid rgba(0,0,0,0.06);">
                        <div style="overflow-x:auto;">
                            <table class="data-table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 45%;">Name</th>
                                        <th style="width: 15%;">Type</th>
                                        <th style="width: 15%;">Size</th>
                                        <th style="width: 20%;">Modified</th>
                                        <th style="width: 5%; text-align:right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="tl-dr-table-body">
                                    <tr>
                                        <td colspan="5" style="text-align:center; padding: 32px; color:#9CA3AF;">
                                            <i class="fas fa-spinner fa-spin" style="margin-right:8px;"></i> Loading...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- ==================== MODALS ==================== -->

    @if(in_array('digital_records', $editableModules))
    <div id="tlCreateFolderModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-folder-plus"></i> Create Shared Folder</h3>
                <button class="modal-close" onclick="closeModal('tlCreateFolderModal')">&times;</button>
            </div>
            <form onsubmit="tlSubmitCreateFolder(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Folder Name *</label>
                        <input id="tlFolderName" type="text" class="form-input" placeholder="Enter folder name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <input id="tlFolderDescription" type="text" class="form-input" placeholder="Optional description">
                    </div>
                    <input type="hidden" id="tlFolderColor" value="#7B1113">

                    <div class="form-group">
                        <label class="form-label">Who can upload? *</label>
                        <div style="display:flex; gap:14px; flex-wrap:wrap;">
                            <label style="display:flex; gap:8px; align-items:center;">
                                <input type="checkbox" name="tlAllowedUsers" value="intern" checked>
                                <span style="font-weight: 700;">Intern</span>
                            </label>
                            <label style="display:flex; gap:8px; align-items:center;">
                                <input type="checkbox" name="tlAllowedUsers" value="team_leader" checked>
                                <span style="font-weight: 700;">Team Leader</span>
                            </label>
                            <label style="display:flex; gap:8px; align-items:center;">
                                <input type="checkbox" name="tlAllowedUsers" value="startup">
                                <span style="font-weight: 700;">Startup</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display:flex; justify-content:flex-end; gap:12px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('tlCreateFolderModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Create Task Modal -->
    <div id="createTaskModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-plus-circle"></i> Create New Task</h3>
                <button class="modal-close" onclick="closeModal('createTaskModal')">&times;</button>
            </div>
            <form id="createTaskForm" action="{{ route('team-leader.tasks.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Assign To *</label>
                        <select name="intern_id" class="form-select" required>
                            <option value="">Select an intern</option>
                            @foreach($allInterns->where('status', 'Active') as $intern)
                            <option value="{{ $intern->id }}">{{ $intern->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Task Title *</label>
                        <input type="text" name="title" class="form-input" required placeholder="Enter task title">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" placeholder="Describe the task..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Requirements</label>
                        <textarea name="requirements" class="form-textarea" placeholder="List any specific requirements..."></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Priority *</label>
                            <select name="priority" class="form-select" required>
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Due Date *</label>
                            <input type="date" name="due_date" class="form-input" required min="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createTaskModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Task</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="editTaskModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Task</h3>
                <button class="modal-close" onclick="closeModal('editTaskModal')">&times;</button>
            </div>
            <form id="editTaskForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Assign To *</label>
                        <select name="intern_id" id="editTaskIntern" class="form-select" required>
                            @foreach($allInterns->where('status', 'Active') as $intern)
                            <option value="{{ $intern->id }}">{{ $intern->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Task Title *</label>
                        <input type="text" name="title" id="editTaskTitle" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="editTaskDescription" class="form-textarea"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Requirements</label>
                        <textarea name="requirements" id="editTaskRequirements" class="form-textarea"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Priority *</label>
                            <select name="priority" id="editTaskPriority" class="form-select" required>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select name="status" id="editTaskStatus" class="form-select" required>
                                <option value="Not Started">Not Started</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                                <option value="On Hold">On Hold</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Due Date *</label>
                            <input type="date" name="due_date" id="editTaskDueDate" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Progress (%)</label>
                            <input type="number" name="progress" id="editTaskProgress" class="form-input" min="0" max="100" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="editTaskNotes" class="form-textarea" placeholder="Additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editTaskModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Task</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Intern Modal -->
    <div id="viewInternModal" class="modal-overlay">
        <div class="modal modal-lg">
            <div class="modal-header">
                <h3><i class="fas fa-user"></i> Intern Details</h3>
                <button class="modal-close" onclick="closeModal('viewInternModal')">&times;</button>
            </div>
            <div class="modal-body" id="viewInternContent">
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 32px; color: var(--maroon);"></i>
                    <p style="margin-top: 16px; color: #6B7280;">Loading intern details...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Report Modal -->
    <div id="createReportModal" class="modal-overlay">
        <div class="modal modal-lg">
            <div class="modal-header">
                <h3><i class="fas fa-file-alt"></i> Create New Report</h3>
                <button class="modal-close" onclick="closeModal('createReportModal')">&times;</button>
            </div>
            <form id="createReportForm" onsubmit="submitCreateReport(event)">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Report Title *</label>
                            <input type="text" name="title" class="form-input" required placeholder="e.g., Weekly Progress Report">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Report Type *</label>
                            <select name="report_type" class="form-select" required>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="performance">Performance</option>
                                <option value="attendance">Attendance</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Period Start</label>
                            <input type="date" name="period_start" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Period End</label>
                            <input type="date" name="period_end" class="form-input">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Summary *</label>
                        <textarea name="summary" class="form-textarea" required placeholder="Provide a summary of the report..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Accomplishments</label>
                        <textarea name="accomplishments" class="form-textarea" placeholder="List key accomplishments..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Challenges</label>
                        <textarea name="challenges" class="form-textarea" placeholder="Describe any challenges encountered..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Recommendations</label>
                        <textarea name="recommendations" class="form-textarea" placeholder="Provide recommendations..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Save As</label>
                        <select name="status" class="form-select">
                            <option value="draft">Save as Draft</option>
                            <option value="submitted">Submit for Review</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createReportModal')">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Report</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Report Modal -->
    <div id="viewReportModal" class="modal-overlay">
        <div class="modal modal-lg">
            <div class="modal-header">
                <h3><i class="fas fa-file-alt"></i> Report Details</h3>
                <button class="modal-close" onclick="closeModal('viewReportModal')">&times;</button>
            </div>
            <div class="modal-body" id="viewReportContent">
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 32px; color: var(--maroon);"></i>
                    <p style="margin-top: 16px; color: #6B7280;">Loading report details...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Report Modal -->
    <div id="editReportModal" class="modal-overlay">
        <div class="modal modal-lg">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Report</h3>
                <button class="modal-close" onclick="closeModal('editReportModal')">&times;</button>
            </div>
            <form id="editReportForm" onsubmit="submitEditReport(event)">
                <input type="hidden" id="editReportId" name="report_id" value="">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Report Title *</label>
                            <input type="text" name="title" id="editReportTitle" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Report Type *</label>
                            <select name="report_type" id="editReportType" class="form-select" required>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="performance">Performance</option>
                                <option value="attendance">Attendance</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Period Start</label>
                            <input type="date" name="period_start" id="editReportPeriodStart" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Period End</label>
                            <input type="date" name="period_end" id="editReportPeriodEnd" class="form-input">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Summary *</label>
                        <textarea name="summary" id="editReportSummary" class="form-textarea" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Accomplishments</label>
                        <textarea name="accomplishments" id="editReportAccomplishments" class="form-textarea"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Challenges</label>
                        <textarea name="challenges" id="editReportChallenges" class="form-textarea"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Recommendations</label>
                        <textarea name="recommendations" id="editReportRecommendations" class="form-textarea"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Save As</label>
                        <select name="status" id="editReportStatus" class="form-select">
                            <option value="draft">Save as Draft</option>
                            <option value="submitted">Submit for Review</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editReportModal')">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update Report</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="modal-overlay">
        <div class="modal" style="max-width: 400px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);">
                <h3><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h3>
                <button class="modal-close" onclick="closeModal('deleteConfirmModal')">&times;</button>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body" style="text-align: center; padding: 32px;">
                    <i class="fas fa-trash-alt" style="font-size: 48px; color: #DC2626; margin-bottom: 16px;"></i>
                    <h4 style="margin-bottom: 8px; color: #1F2937;">Are you sure?</h4>
                    <p id="deleteConfirmMessage" style="color: #6B7280;">This action cannot be undone.</p>
                </div>
                <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('deleteConfirmModal')">Cancel</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage">Success!</span>
    </div>

    {{-- Create/Edit Event Modal --}}
    <div id="tlEventModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 10000; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 16px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div style="background: linear-gradient(135deg, var(--maroon), var(--maroon-dark)); padding: 24px; color: white; border-radius: 16px 16px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h2 id="tlEventModalTitle" style="margin: 0; font-size: 20px; font-weight: 700;">Create Event</h2>
                <button onclick="tlCloseEventModal()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; font-size: 18px; transition: all 0.3s ease;">&times;</button>
            </div>
            <div style="padding: 24px;">
                <input type="hidden" id="tlEventId">

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Event Title *</label>
                    <input type="text" id="tlEventTitle" style="width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;" placeholder="Enter event title">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Description</label>
                    <textarea id="tlEventDescription" style="width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px; resize: vertical; min-height: 100px;" placeholder="Enter event description"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div>
                        <label id="tlStartDateLabel" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Start Date & Time *</label>
                        <input type="datetime-local" id="tlEventStartDate" style="width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;">
                    </div>
                    <div>
                        <label id="tlEndDateLabel" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">End Date & Time *</label>
                        <input type="datetime-local" id="tlEventEndDate" style="width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" id="tlEventAllDay" onchange="tlToggleAllDayEvent()" style="width: 18px; height: 18px; cursor: pointer;">
                        <span style="font-size: 14px; font-weight: 600; color: #374151;">All Day Event</span>
                    </label>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Location</label>
                    <input type="text" id="tlEventLocation" style="width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;" placeholder="Enter event location">
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Color</label>
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <input type="color" id="tlEventColor" value="#3B82F6" style="width: 60px; height: 40px; border: 1px solid #E5E7EB; border-radius: 6px; cursor: pointer;">
                        <div style="display: flex; gap: 8px;">
                            <button type="button" onclick="document.getElementById('tlEventColor').value='#3B82F6'" style="width: 32px; height: 32px; background: #3B82F6; border: 2px solid #E5E7EB; border-radius: 6px; cursor: pointer;"></button>
                            <button type="button" onclick="document.getElementById('tlEventColor').value='#10B981'" style="width: 32px; height: 32px; background: #10B981; border: 2px solid #E5E7EB; border-radius: 6px; cursor: pointer;"></button>
                            <button type="button" onclick="document.getElementById('tlEventColor').value='#F59E0B'" style="width: 32px; height: 32px; background: #F59E0B; border: 2px solid #E5E7EB; border-radius: 6px; cursor: pointer;"></button>
                            <button type="button" onclick="document.getElementById('tlEventColor').value='#EF4444'" style="width: 32px; height: 32px; background: #EF4444; border: 2px solid #E5E7EB; border-radius: 6px; cursor: pointer;"></button>
                            <button type="button" onclick="document.getElementById('tlEventColor').value='#8B5CF6'" style="width: 32px; height: 32px; background: #8B5CF6; border: 2px solid #E5E7EB; border-radius: 6px; cursor: pointer;"></button>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; justify-content: space-between; align-items: center;">
                    <button id="tlEventDeleteBtn" onclick="tlDeleteEventFromModal()" style="padding: 12px 24px; background: #FEE2E2; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; color: #DC2626; display: none; align-items: center; gap: 8px;">
                        <i class="fas fa-trash"></i> Delete Event
                    </button>
                    <div style="display: flex; gap: 12px;">
                        <button onclick="tlCloseEventModal()" style="padding: 12px 24px; background: #F3F4F6; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; color: #374151;">Cancel</button>
                        <button onclick="tlSaveEvent()" style="padding: 12px 24px; background: linear-gradient(135deg, var(--maroon), var(--maroon-dark)); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-save"></i> Save Event
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Block Date Modal --}}
    <div id="tlBlockDateModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 10000; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 16px; width: 90%; max-width: 450px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div style="background: linear-gradient(135deg, var(--maroon), var(--maroon-dark)); padding: 24px; color: white; border-radius: 16px 16px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0; font-size: 20px; font-weight: 700;"><i class="fas fa-calendar-times" style="margin-right: 8px;"></i>Block Date</h2>
                <button onclick="tlCloseBlockDateModal()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; font-size: 18px; transition: all 0.3s ease;">&times;</button>
            </div>
            <div style="padding: 24px;">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Start Date</label>
                    <input type="date" id="tlBlockDateValue" style="width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Number of Days to Block</label>
                    <input type="number" id="tlBlockDateDays" value="1" min="1" max="365" style="width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;" placeholder="e.g., 7">
                    <small style="color: #6B7280; font-size: 12px; margin-top: 4px; display: block;">Enter how many consecutive days to block starting from the selected date</small>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Reason *</label>
                    <select id="tlBlockDateReason" style="width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;" required>
                        <option value="">-- Select Reason --</option>
                        <option value="unavailable">Not Available</option>
                        <option value="no_work">No Work</option>
                        <option value="holiday">Holiday</option>
                        <option value="sick">Sick Day</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">Description (Optional)</label>
                    <input type="text" id="tlBlockDateDescription" style="width: 100%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px;" placeholder="e.g., Staff meeting, Building maintenance...">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button onclick="tlCloseBlockDateModal()" style="padding: 12px 24px; background: #F3F4F6; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; color: #374151;">Cancel</button>
                    <button onclick="tlSubmitBlockDate()" style="padding: 12px 24px; background: #EF4444; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-ban"></i> Block Date
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Store data for modals
        const tasksData = @json($allTasks);
        const reportsData = @json($allReports);
        const internsData = @json($allInterns);

        // Page navigation
        const menuItems = document.querySelectorAll('.menu-item[data-page]');
        const pageContents = document.querySelectorAll('.page-content');
        const pageTitle = document.getElementById('pageTitle');
        const pageTitles = {
            'dashboard': 'Dashboard',
            'interns': 'My Interns',
            'tasks': 'Task Management',
            'attendance': 'Team Attendance',
            'reports': 'My Reports',
            'scheduler': 'Scheduler',
            'research-tracking': 'Research Tracking',
            'incubatee-tracker': 'Incubatee Tracker',
            'issues-management': 'Issues & Complaints',
            'digital-records': 'Digital Records'
        };

        menuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.getAttribute('data-page');
                menuItems.forEach(mi => mi.classList.remove('active'));
                this.classList.add('active');
                pageContents.forEach(pc => pc.classList.remove('active'));
                document.getElementById(page).classList.add('active');
                pageTitle.textContent = pageTitles[page] || 'Dashboard';

                if (page === 'digital-records') {
                    tlDrInit();
                }
            });
        });

        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close modal on overlay click
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) closeModal(overlay.id);
            });
        });

        // Toast notification
        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            toastMessage.textContent = message;
            toast.classList.toggle('error', isError);
            toast.classList.add('active');
            setTimeout(() => toast.classList.remove('active'), 3000);
        }

        // ========== DIGITAL RECORDS (TEAM LEADER) ==========
        const tlDrHasEditAccess = {{ in_array('digital_records', $editableModules) ? 'true' : 'false' }};
        let tlDrCurrentPath = '';
        let tlDrHistory = [];
        let tlDrItems = [];
        let tlDrSearchQuery = '';
        let tlDrInitialized = false;

        function tlDrInit() {
            if (tlDrInitialized) return;
            if (!document.getElementById('digital-records')) return;
            tlDrInitialized = true;
            tlLoadDigitalRecordsStats();
            tlLoadDigitalRecordsRoot();
        }

        function tlDrRefresh() {
            tlLoadDigitalRecordsStats();
            if (tlDrCurrentPath) {
                tlLoadFolderContents(tlDrCurrentPath, false);
            } else {
                tlLoadDigitalRecordsRoot();
            }
        }

        function tlDrGoBack() {
            if (tlDrHistory.length === 0) return;
            const prev = tlDrHistory.pop();
            tlDrCurrentPath = prev || '';
            document.getElementById('tl-dr-current-path').textContent = prev || 'Root';
            document.getElementById('tl-dr-back-btn').style.display = tlDrHistory.length ? 'inline-flex' : 'none';
            if (prev) {
                tlLoadFolderContents(prev, false);
            } else {
                tlLoadDigitalRecordsRoot();
            }
        }

        function tlDrFilter(query) {
            tlDrSearchQuery = (query || '').toLowerCase();
            tlRenderDigitalRecordsTable(tlDrItems);
        }

        function tlLoadDigitalRecordsStats() {
            fetch('/admin/documents/stats', { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) throw new Error(data.message || 'Failed');
                    const foldersEl = document.getElementById('tl-dr-total-folders');
                    const filesEl = document.getElementById('tl-dr-total-files');
                    const storageEl = document.getElementById('tl-dr-storage-used');
                    const recentEl = document.getElementById('tl-dr-recent-uploads');
                    if (!foldersEl) return;
                    foldersEl.textContent = data.folders ?? '--';
                    filesEl.textContent = data.files ?? '--';
                    storageEl.textContent = data.storage_human || tlFormatBytes(data.storage_bytes || 0);
                    recentEl.textContent = data.recent_uploads ?? '--';
                })
                .catch(err => {
                    console.error('TL stats error:', err);
                });
        }

        function tlLoadDigitalRecordsRoot() {
            tlDrCurrentPath = '';
            tlDrHistory = [];
            const backBtn = document.getElementById('tl-dr-back-btn');
            if (backBtn) backBtn.style.display = 'none';
            const pathEl = document.getElementById('tl-dr-current-path');
            if (pathEl) pathEl.textContent = 'Root';

            fetch('/admin/documents/all-folders', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) throw new Error(data.message || 'Failed');

                    const shared = (data.shared_folders || []).map(f => ({
                        id: f.id,
                        name: f.name,
                        path: f.storage_path || f.path || `Shared/${f.name}`,
                        is_folder: true,
                        folder_type: 'shared',
                        item_count: f.item_count || 0,
                        modified: '--'
                    }));

                    const intern = (data.intern_folders || []).map(f => ({
                        id: f.id,
                        name: f.name,
                        path: f.path,
                        is_folder: true,
                        folder_type: 'intern',
                        item_count: f.item_count || 0,
                        modified: '--'
                    }));

                    tlDrItems = [...shared, ...intern];
                    tlRenderDigitalRecordsTable(tlDrItems);
                })
                .catch(err => {
                    console.error('TL root load error:', err);
                    showToast('Failed to load Digital Records', true);
                });
        }

        function tlOpenFolder(path) {
            if (!path) return;
            if (tlDrCurrentPath) {
                tlDrHistory.push(tlDrCurrentPath);
            }
            tlDrCurrentPath = path;
            document.getElementById('tl-dr-current-path').textContent = path;
            document.getElementById('tl-dr-back-btn').style.display = 'inline-flex';
            tlLoadFolderContents(path, false);
        }

        function tlLoadFolderContents(path, pushHistory = false) {
            if (pushHistory && tlDrCurrentPath) {
                tlDrHistory.push(tlDrCurrentPath);
            }

            fetch(`/admin/documents/contents?path=${encodeURIComponent(path)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) throw new Error(data.message || 'Failed');

                    const items = (data.items || []).map(i => ({
                        id: i.id,
                        name: i.name,
                        path: i.path,
                        is_folder: !!i.is_folder,
                        item_count: i.item_count || 0,
                        size: i.size,
                        modified: i.modified || '--'
                    }));

                    tlDrItems = items;
                    tlRenderDigitalRecordsTable(tlDrItems);
                })
                .catch(err => {
                    console.error('TL contents error:', err);
                    showToast('Failed to load folder contents', true);
                });
        }

        function tlDownloadFile(path) {
            if (!path) return;
            window.open(`/admin/documents/download?path=${encodeURIComponent(path)}`, '_blank');
        }

        function tlDeleteFile(path, name) {
            if (!tlDrHasEditAccess) return showToast('Edit access required', true);
            if (!confirm(`Delete file "${name}"?`)) return;
            fetch('/admin/documents/file', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ path })
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) throw new Error(data.message || 'Failed');
                    showToast('File deleted');
                    tlDrRefresh();
                })
                .catch(err => {
                    console.error('TL delete file error:', err);
                    showToast('Failed to delete file', true);
                });
        }

        function tlDeleteFolder(folderId, name) {
            if (!tlDrHasEditAccess) return showToast('Edit access required', true);
            if (!folderId) return showToast('Folder id missing', true);
            if (!confirm(`Delete folder "${name}" and all contents?`)) return;
            fetch('/admin/documents/folder', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ folder_id: folderId })
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) throw new Error(data.message || 'Failed');
                    showToast('Folder deleted');
                    tlDrRefresh();
                })
                .catch(err => {
                    console.error('TL delete folder error:', err);
                    showToast('Failed to delete folder', true);
                });
        }

        function tlOpenCreateFolderModal() {
            if (!tlDrHasEditAccess) return showToast('Edit access required', true);
            document.getElementById('tlFolderName').value = '';
            document.getElementById('tlFolderDescription').value = '';
            // defaults
            document.querySelectorAll('input[name="tlAllowedUsers"]').forEach(cb => {
                cb.checked = cb.value === 'intern' || cb.value === 'team_leader';
            });
            openModal('tlCreateFolderModal');
        }

        function tlSubmitCreateFolder(event) {
            event.preventDefault();
            if (!tlDrHasEditAccess) return showToast('Edit access required', true);
            const name = document.getElementById('tlFolderName').value;
            const description = document.getElementById('tlFolderDescription').value;
            const color = document.getElementById('tlFolderColor').value || '#7B1113';
            const allowedUsers = Array.from(document.querySelectorAll('input[name="tlAllowedUsers"]:checked')).map(cb => cb.value);
            if (!allowedUsers.length) return showToast('Select at least one uploader', true);

            fetch('/admin/documents/create-folder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ name, description, color, allowed_users: allowedUsers })
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) throw new Error(data.message || 'Failed');
                    showToast('Folder created');
                    closeModal('tlCreateFolderModal');
                    tlLoadDigitalRecordsRoot();
                })
                .catch(err => {
                    console.error('TL create folder error:', err);
                    showToast('Failed to create folder', true);
                });
        }

        function tlRenderDigitalRecordsTable(items) {
            const tbody = document.getElementById('tl-dr-table-body');
            if (!tbody) return;

            const filtered = (items || []).filter(i => {
                if (!tlDrSearchQuery) return true;
                return (i.name || '').toLowerCase().includes(tlDrSearchQuery);
            });

            if (!filtered.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 32px; color:#9CA3AF;">
                            <i class="fas fa-folder-open" style="margin-right:8px;"></i> No items found
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = filtered.map(item => {
                const isFolder = item.is_folder;
                const type = isFolder ? 'Folder' : 'File';
                const size = isFolder ? `${item.item_count || 0} item(s)` : (item.size || '--');
                const modified = item.modified || '--';

                const openClick = isFolder
                    ? `onclick=\"tlOpenFolder('${tlEscapeHtml(item.path)}')\"`
                    : `onclick=\"tlDownloadFile('${tlEscapeHtml(item.path)}')\"`;

                const downloadBtn = !isFolder
                    ? `<button class=\"btn btn-secondary btn-sm\" style=\"padding:6px 10px;\" onclick=\"event.stopPropagation(); tlDownloadFile('${tlEscapeHtml(item.path)}')\"><i class=\"fas fa-download\"></i></button>`
                    : '';

                const deleteBtn = tlDrHasEditAccess
                    ? (isFolder
                        ? (item.id
                            ? `<button class=\"btn btn-danger btn-sm\" style=\"padding:6px 10px;\" onclick=\"event.stopPropagation(); tlDeleteFolder(${item.id}, '${tlEscapeHtml(item.name)}')\"><i class=\"fas fa-trash\"></i></button>`
                            : '')
                        : `<button class=\"btn btn-danger btn-sm\" style=\"padding:6px 10px;\" onclick=\"event.stopPropagation(); tlDeleteFile('${tlEscapeHtml(item.path)}', '${tlEscapeHtml(item.name)}')\"><i class=\"fas fa-trash\"></i></button>`)
                    : '';

                return `
                    <tr style="cursor:pointer;" ${openClick}>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <i class="fas ${isFolder ? 'fa-folder' : 'fa-file'}" style="color:${isFolder ? '#F59E0B' : '#6B7280'};"></i>
                                <strong>${tlEscapeHtml(item.name)}</strong>
                            </div>
                        </td>
                        <td>${type}</td>
                        <td>${tlEscapeHtml(size)}</td>
                        <td>${tlEscapeHtml(modified)}</td>
                        <td style="text-align:right; white-space:nowrap;">
                            ${downloadBtn}
                            ${deleteBtn}
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function tlFormatBytes(bytes) {
            if (!bytes || bytes <= 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return `${(bytes / Math.pow(k, i)).toFixed(2)} ${sizes[i]}`;
        }

        function tlEscapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>'"]/g, c => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            }[c]));
        }

        // ========== TASK FUNCTIONS ==========
        function openCreateTaskModal() {
            document.getElementById('createTaskForm').reset();
            openModal('createTaskModal');
        }

        function editTask(taskId) {
            const task = tasksData.find(t => t.id === taskId);
            if (!task) return showToast('Task not found', true);

            document.getElementById('editTaskForm').action = `/team-leader/tasks/${taskId}`;
            document.getElementById('editTaskIntern').value = task.intern_id;
            document.getElementById('editTaskTitle').value = task.title;
            document.getElementById('editTaskDescription').value = task.description || '';
            document.getElementById('editTaskRequirements').value = task.requirements || '';
            document.getElementById('editTaskPriority').value = task.priority;
            document.getElementById('editTaskStatus').value = task.status;
            document.getElementById('editTaskDueDate').value = task.due_date ? task.due_date.split('T')[0] : '';
            document.getElementById('editTaskProgress').value = task.progress || 0;
            document.getElementById('editTaskNotes').value = task.notes || '';

            openModal('editTaskModal');
        }

        function deleteTask(taskId) {
            document.getElementById('deleteForm').action = `/team-leader/tasks/${taskId}`;
            document.getElementById('deleteConfirmMessage').textContent = 'This task will be permanently deleted.';
            openModal('deleteConfirmModal');
        }

        // ========== INTERN FUNCTIONS ==========
        function viewIntern(internId) {
            openModal('viewInternModal');

            const intern = internsData.find(i => i.id === internId);
            if (!intern) {
                document.getElementById('viewInternContent').innerHTML = '<p style="text-align: center; color: #DC2626;">Intern not found</p>';
                return;
            }

            const progress = intern.required_hours > 0
                ? Math.round((intern.completed_hours / intern.required_hours) * 100)
                : 0;

            const internTasks = tasksData.filter(t => t.intern_id === internId);

            document.getElementById('viewInternContent').innerHTML = `
                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid rgba(0,0,0,0.08);">
                    <div class="list-item-avatar" style="width: 80px; height: 80px; font-size: 28px; margin: 0;">
                        ${intern.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <h2 style="font-size: 22px; font-weight: 700; color: #1F2937; margin-bottom: 4px;">${intern.name}</h2>
                        <p style="color: #6B7280;">${intern.email}</p>
                        <span class="badge badge-${intern.status === 'Active' ? 'success' : (intern.status === 'Completed' ? 'info' : 'warning')}">${intern.status}</span>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <label>Course</label>
                        <p>${intern.course || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <label>Phone</label>
                        <p>${intern.phone || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <label>Start Date</label>
                        <p>${intern.start_date || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <label>End Date</label>
                        <p>${intern.end_date || 'N/A'}</p>
                    </div>
                </div>

                <div style="background: var(--off-white); padding: 20px; border-radius: 12px; margin-bottom: 24px;">
                    <h4 style="margin-bottom: 12px; color: var(--maroon);">Hours Progress</h4>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-weight: 600;">${Number(intern.completed_hours || 0).toFixed(1)} / ${intern.required_hours} hours</span>
                        <span style="font-weight: 700; color: var(--maroon);">${progress}%</span>
                    </div>
                    <div class="progress-container" style="height: 12px;">
                        <div class="progress-bar ${progress < 30 ? 'red' : (progress < 70 ? 'gold' : 'green')}" style="width: ${progress}%"></div>
                    </div>
                </div>

                <h4 style="margin-bottom: 12px; color: var(--maroon);">Assigned Tasks (${internTasks.length})</h4>
                ${internTasks.length > 0 ? `
                    <div style="max-height: 200px; overflow-y: auto;">
                        ${internTasks.map(task => `
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: var(--off-white); border-radius: 8px; margin-bottom: 8px;">
                                <div>
                                    <div style="font-weight: 600;">${task.title}</div>
                                    <div style="font-size: 12px; color: #6B7280;">Due: ${task.due_date ? new Date(task.due_date).toLocaleDateString() : 'No date'}</div>
                                </div>
                                ${(() => {
                                    const isPending = task.status === 'Completed' && !task.completed_date;
                                    const label = isPending ? 'Pending Admin Approval' : task.status;
                                    const badge = isPending ? 'info' : (task.status === 'Completed' ? 'success' : (task.status === 'In Progress' ? 'info' : 'warning'));
                                    return '<span class="badge badge-' + badge + '">' + label + '</span>';
                                })()}
                            </div>
                        `).join('')}
                    </div>
                ` : '<p style="color: #6B7280; text-align: center; padding: 20px;">No tasks assigned yet</p>'}
            `;
        }

        // ========== REPORT FUNCTIONS ==========
        function openCreateReportModal() {
            document.getElementById('createReportForm').reset();
            openModal('createReportModal');
        }

        function viewReport(reportId) {
            openModal('viewReportModal');
            const report = reportsData.find(r => r.id === reportId);

            if (!report) {
                document.getElementById('viewReportContent').innerHTML = '<p style="text-align: center; color: #DC2626;">Report not found</p>';
                return;
            }

            document.getElementById('viewReportContent').innerHTML = `
                <div style="margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid rgba(0,0,0,0.08);">
                    <h2 style="font-size: 22px; font-weight: 700; color: #1F2937; margin-bottom: 8px;">${report.title}</h2>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <span class="badge badge-info">${report.report_type.charAt(0).toUpperCase() + report.report_type.slice(1)}</span>
                        <span class="badge badge-${report.status === 'reviewed' ? 'success' : (report.status === 'submitted' ? 'info' : 'warning')}">${report.status.charAt(0).toUpperCase() + report.status.slice(1)}</span>
                    </div>
                </div>

                <div class="info-grid" style="margin-bottom: 24px;">
                    <div class="info-item">
                        <label>Period Start</label>
                        <p>${report.period_start ? new Date(report.period_start).toLocaleDateString() : 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <label>Period End</label>
                        <p>${report.period_end ? new Date(report.period_end).toLocaleDateString() : 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <label>Created</label>
                        <p>${new Date(report.created_at).toLocaleDateString()}</p>
                    </div>
                    <div class="info-item">
                        <label>Last Updated</label>
                        <p>${new Date(report.updated_at).toLocaleDateString()}</p>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <h4 style="color: var(--maroon); margin-bottom: 8px;">Summary</h4>
                    <p style="background: var(--off-white); padding: 16px; border-radius: 10px; white-space: pre-wrap;">${report.summary || 'No summary provided'}</p>
                </div>

                ${report.accomplishments ? `
                <div style="margin-bottom: 20px;">
                    <h4 style="color: var(--forest-green); margin-bottom: 8px;">Accomplishments</h4>
                    <p style="background: rgba(34, 139, 34, 0.05); padding: 16px; border-radius: 10px; white-space: pre-wrap;">${report.accomplishments}</p>
                </div>
                ` : ''}

                ${report.challenges ? `
                <div style="margin-bottom: 20px;">
                    <h4 style="color: #F59E0B; margin-bottom: 8px;">Challenges</h4>
                    <p style="background: rgba(245, 158, 11, 0.05); padding: 16px; border-radius: 10px; white-space: pre-wrap;">${report.challenges}</p>
                </div>
                ` : ''}

                ${report.recommendations ? `
                <div style="margin-bottom: 20px;">
                    <h4 style="color: var(--maroon); margin-bottom: 8px;">Recommendations</h4>
                    <p style="background: rgba(123, 17, 19, 0.05); padding: 16px; border-radius: 10px; white-space: pre-wrap;">${report.recommendations}</p>
                </div>
                ` : ''}

                ${report.admin_feedback ? `
                <div style="margin-bottom: 20px;">
                    <h4 style="color: var(--forest-green); margin-bottom: 8px;">Admin Feedback</h4>
                    <p style="background: rgba(34, 139, 34, 0.1); padding: 16px; border-radius: 10px; border-left: 4px solid var(--forest-green); white-space: pre-wrap;">${report.admin_feedback}</p>
                </div>
                ` : ''}
            `;
        }

        function editReport(reportId) {
            const report = reportsData.find(r => r.id === reportId);
            if (!report) return showToast('Report not found', true);

            document.getElementById('editReportForm').action = `/team-leader/reports/${reportId}`;
            document.getElementById('editReportTitle').value = report.title;
            document.getElementById('editReportType').value = report.report_type;
            document.getElementById('editReportPeriodStart').value = report.period_start ? report.period_start.split('T')[0] : '';
            document.getElementById('editReportPeriodEnd').value = report.period_end ? report.period_end.split('T')[0] : '';
            document.getElementById('editReportSummary').value = report.summary || '';
            document.getElementById('editReportAccomplishments').value = report.accomplishments || '';
            document.getElementById('editReportChallenges').value = report.challenges || '';
            document.getElementById('editReportRecommendations').value = report.recommendations || '';
            document.getElementById('editReportStatus').value = report.status;

            openModal('editReportModal');
        }

        function deleteReport(reportId) {
            document.getElementById('deleteForm').action = `/team-leader/reports/${reportId}`;
            document.getElementById('deleteConfirmMessage').textContent = 'This report will be permanently deleted.';
            openModal('deleteConfirmModal');
        }

        // ========== AJAX FORM SUBMISSIONS ==========
        function submitCreateReport(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("team-leader.reports.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.redirected) {
                    showToast('Report created successfully!');
                    closeModal('createReportModal');
                    setTimeout(() => location.reload(), 1000);
                } else if (response.ok) {
                    showToast('Report created successfully!');
                    closeModal('createReportModal');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    return response.text().then(text => {
                        throw new Error('Failed to create report');
                    });
                }
            })
            .catch(err => {
                showToast('Error creating report', true);
            });
        }

        function submitEditReport(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');
            const reportId = document.getElementById('editReportId').value;

            fetch(`/team-leader/reports/${reportId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.redirected || response.ok) {
                    showToast('Report updated successfully!');
                    closeModal('editReportModal');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    throw new Error('Failed to update report');
                }
            })
            .catch(err => {
                showToast('Error updating report', true);
            });
        }

        // ==================== AUTO-REFRESH FUNCTIONALITY ====================
        let autoRefreshInterval = null;
        const REFRESH_INTERVAL = 30000; // 30 seconds
        let isRefreshing = false;

        // Start auto-refresh when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startAutoRefresh();
        });

        function startAutoRefresh() {
            // Clear any existing interval
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }

            // Set up new interval
            autoRefreshInterval = setInterval(function() {
                refreshData();
            }, REFRESH_INTERVAL);

            console.log('Auto-refresh started (every 30 seconds)');
        }

        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
            }
        }

        function manualRefresh() {
            refreshData(true);
        }

        async function refreshData(isManual = false) {
            if (isRefreshing) return;
            isRefreshing = true;

            const refreshIcon = document.getElementById('refreshIcon');
            refreshIcon.classList.add('fa-spin');

            try {
                // Fetch updated tasks
                const tasksResponse = await fetch('/team-leader/api/tasks', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (tasksResponse.ok) {
                    const tasksData = await tasksResponse.json();
                    if (tasksData.success) {
                        updateTasksTable(tasksData.tasks);
                        document.getElementById('lastUpdatedTime').textContent = tasksData.updated_at;
                    }
                }

                // Fetch updated stats
                const statsResponse = await fetch('/team-leader/api/stats', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (statsResponse.ok) {
                    const statsData = await statsResponse.json();
                    if (statsData.success) {
                        updateDashboardStats(statsData.stats);
                    }
                }

                if (isManual) {
                    showToast('Data refreshed successfully!');
                }

            } catch (error) {
                console.error('Error refreshing data:', error);
                if (isManual) {
                    showToast('Failed to refresh data', true);
                }
            } finally {
                isRefreshing = false;
                refreshIcon.classList.remove('fa-spin');
            }
        }

        function updateTasksTable(tasks) {
            const tbody = document.querySelector('#tasks .data-table tbody');
            if (!tbody || tasks.length === 0) return;

            // Update existing rows or rebuild table
            let html = '';
            tasks.forEach(task => {
                const priorityBg = task.priority === 'High' ? '#F59E0B' :
                                   (task.priority === 'Medium' ? 'var(--gold)' : 'var(--forest-green)');
                const priorityColor = task.priority === 'Medium' ? 'var(--maroon)' : 'white';

                const statusClass = task.status === 'Completed' ? 'success' :
                                    (task.status === 'In Progress' ? 'info' :
                                    (task.status === 'On Hold' ? 'danger' : 'warning'));

                html += `
                    <tr data-task-id="${task.id}">
                        <td>
                            <div style="font-weight: 600; color: var(--maroon);">${escapeHtml(task.title)}</div>
                            ${task.is_overdue ? '<span style="font-size: 11px; color: #DC2626; font-weight: 600;"><i class="fas fa-exclamation-circle"></i> Overdue</span>' : ''}
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 26px; height: 26px; border-radius: 8px; background: var(--maroon); color: white; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 600;">
                                    ${task.intern_initial}
                                </div>
                                <span style="font-size: 13px;">${escapeHtml(task.intern_name)}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: ${priorityBg}; color: ${priorityColor};">
                                ${task.priority}
                            </span>
                        </td>
                        <td>
                            <span style="${task.is_overdue ? 'color: #DC2626; font-weight: 600;' : ''}">
                                ${task.due_date}
                            </span>
                        </td>
                        <td style="min-width: 100px;">
                            <div class="progress-container" style="margin-bottom: 4px;">
                                <div class="progress-bar green" style="width: ${task.progress}%"></div>
                            </div>
                            <span style="font-size: 11px; color: #6B7280;">${task.progress}%</span>
                        </td>
                        <td>
                            <span class="badge badge-${statusClass}">
                                ${task.status}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <button class="btn btn-sm btn-secondary" onclick="editTask(${task.id})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteTask(${task.id})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;

            // Update task stats cards
            const pendingCount = tasks.filter(t => t.status === 'Not Started').length;
            const inProgressCount = tasks.filter(t => t.status === 'In Progress').length;
            const completedCount = tasks.filter(t => t.status === 'Completed').length;
            const overdueCount = tasks.filter(t => t.is_overdue).length;

            // Update stat cards on tasks page
            const taskStatCards = document.querySelectorAll('#tasks .stat-value');
            if (taskStatCards.length >= 4) {
                taskStatCards[0].textContent = pendingCount;
                taskStatCards[1].textContent = inProgressCount;
                taskStatCards[2].textContent = completedCount;
                taskStatCards[3].textContent = overdueCount;
            }
        }

        function updateDashboardStats(stats) {
            // Update dashboard stat cards - these have specific IDs or we target by position
            // Dashboard page stats (total interns, active interns, etc.)
            const dashboardStats = document.querySelectorAll('#dashboard .stat-value');

            // Update task overview on dashboard if it exists
            const taskOverviewValues = document.querySelectorAll('.task-overview-value');
            if (taskOverviewValues.length >= 4) {
                taskOverviewValues[0].textContent = stats.in_progress_tasks;
                taskOverviewValues[1].textContent = stats.completed_tasks;
                taskOverviewValues[2].textContent = stats.pending_tasks;
                taskOverviewValues[3].textContent = stats.overdue_tasks;
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Pause auto-refresh when tab is not visible
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoRefresh();
            } else {
                startAutoRefresh();
                refreshData(); // Refresh immediately when tab becomes visible
            }
        });

        // ==================== TEAM LEADER SCHEDULER FUNCTIONS ====================
        let tlSchedulerCurrentYear = new Date().getFullYear();
        let tlSchedulerCurrentMonth = new Date().getMonth();
        let tlSchedulerEvents = [];
        let tlSchedulerBookings = [];
        let tlSchedulerBlockedDates = [];
        const tlHasEditAccess = {{ in_array('scheduler', $editableModules) ? 'true' : 'false' }};

        // Load scheduler data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Load scheduler data if on scheduler page
            const schedulerMenuItem = document.querySelector('[data-page="scheduler"]');
            if (schedulerMenuItem) {
                schedulerMenuItem.addEventListener('click', function() {
                    setTimeout(() => {
                        tlLoadSchedulerData();
                    }, 100);
                });
            }
        });

        async function tlLoadSchedulerData() {
            try {
                // Load events
                const eventsResponse = await fetch('/intern/events');
                if (eventsResponse.ok) {
                    const eventsData = await eventsResponse.json();
                    tlSchedulerEvents = eventsData.events || [];
                }

                // Load bookings (fetch from admin endpoint to get all bookings including pending)
                const bookingsResponse = await fetch('/admin/bookings');
                if (bookingsResponse.ok) {
                    const bookingsData = await bookingsResponse.json();
                    // Get the bookings array from response
                    const bookingsArray = bookingsData.bookings || bookingsData;
                    // Convert the data to match the expected format
                    tlSchedulerBookings = bookingsArray.map(booking => ({
                        id: booking.id,
                        date: booking.date,
                        time: booking.time,
                        agency: booking.agency,
                        event: booking.event,
                        contact_person: booking.contact_person,
                        email: booking.email,
                        phone: booking.phone,
                        purpose: booking.purpose,
                        status: booking.status,
                        admin_emailed: booking.admin_emailed
                    }));
                }

                // Load blocked dates
                const blockedResponse = await fetch('/blocked-dates');
                if (blockedResponse.ok) {
                    tlSchedulerBlockedDates = await blockedResponse.json();
                }

                // Render calendar and lists
                tlRenderSchedulerCalendar();
                tlRenderUpcomingEvents();
                tlRenderRecentBookings();

            } catch (error) {
                console.error('Error loading scheduler data:', error);
            }
        }

        function tlRenderSchedulerCalendar() {
            const titleEl = document.getElementById('tlSchedulerMonthTitle');
            const mainCalEl = document.getElementById('tlSchedulerCalendarGrid');
            if (!mainCalEl) return;

            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            const monthYear = `${monthNames[tlSchedulerCurrentMonth]} ${tlSchedulerCurrentYear}`;
            if (titleEl) titleEl.textContent = monthYear;

            const firstDay = new Date(tlSchedulerCurrentYear, tlSchedulerCurrentMonth, 1).getDay();
            const daysInMonth = new Date(tlSchedulerCurrentYear, tlSchedulerCurrentMonth + 1, 0).getDate();
            const daysInPrevMonth = new Date(tlSchedulerCurrentYear, tlSchedulerCurrentMonth, 0).getDate();
            const today = new Date();
            const todayString = today.toISOString().split('T')[0];

            let mainHtml = '';

            // Previous month days
            for (let i = firstDay - 1; i >= 0; i--) {
                mainHtml += `<div style="background: #F9FAFB; padding: 12px; min-height: 100px; color: #D1D5DB;"><div style="font-weight: 600; margin-bottom: 8px;">${daysInPrevMonth - i}</div></div>`;
            }

            // Current month days
            for (let day = 1; day <= daysInMonth; day++) {
                const dateString = `${tlSchedulerCurrentYear}-${String(tlSchedulerCurrentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isToday = dateString === todayString;

                // Check for bookings on this date
                const dayBookings = tlSchedulerBookings.filter(b => b.date === dateString);

                // Check for events spanning this date
                const dayEvents = tlSchedulerEvents.filter(e => {
                    const eventStart = new Date(e.start_date).toISOString().split('T')[0];
                    const eventEnd = new Date(e.end_date).toISOString().split('T')[0];
                    return dateString >= eventStart && dateString <= eventEnd;
                });

                // Check if blocked
                const blockedInfo = tlSchedulerBlockedDates.find(b => b.date === dateString);

                let dayStyle = 'background: white; padding: 12px; min-height: 100px; border: 2px solid transparent;';
                if (isToday) dayStyle = 'background: #FEF3C7; padding: 12px; min-height: 100px; border: 2px solid var(--gold);';
                if (blockedInfo) dayStyle += ` background: ${blockedInfo.reason_color}08;`;

                let eventsHtml = '';

                // Show blocked status
                if (blockedInfo) {
                    eventsHtml += `<div style="background: ${blockedInfo.reason_color}20; border-left: 3px solid ${blockedInfo.reason_color}; color: ${blockedInfo.reason_color}; padding: 4px 6px; border-radius: 4px; font-size: 10px; font-weight: 600; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><i class="fas fa-ban" style="margin-right: 4px;"></i>${blockedInfo.reason_label}</div>`;
                }

                // Show bookings
                dayBookings.slice(0, 2).forEach(booking => {
                    eventsHtml += `<div style="background: #DBEAFE; border-left: 3px solid #3B82F6; color: #0369A1; padding: 4px 6px; border-radius: 4px; font-size: 10px; font-weight: 600; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer;" title="${booking.agency}" onclick="tlViewBooking(${booking.id})">${booking.agency}</div>`;
                });

                // Show events
                dayEvents.slice(0, 2).forEach(event => {
                    const eventStart = new Date(event.start_date).toISOString().split('T')[0];
                    const isStartDay = dateString === eventStart;
                    const eventLabel = isStartDay ? event.title : `↔ ${event.title}`;
                    eventsHtml += `<div style="background: ${event.color}20; border-left: 3px solid ${event.color}; color: ${event.color}; padding: 4px 6px; border-radius: 4px; font-size: 10px; font-weight: 600; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer;" title="${event.title}" onclick="tlViewEvent(${event.id})">${eventLabel}</div>`;
                });

                // Show +more indicator if there are more items
                const totalItems = dayBookings.length + dayEvents.length + (blockedInfo ? 1 : 0);
                if (totalItems > 3) {
                    eventsHtml += `<div style="color: #6B7280; font-size: 10px; font-weight: 600; margin-top: 2px;">+${totalItems - 3} more</div>`;
                }

                mainHtml += `<div style="${dayStyle}"><div style="font-weight: 700; margin-bottom: 8px; color: ${isToday ? 'var(--maroon)' : '#1F2937'};">${day}</div>${eventsHtml}</div>`;
            }

            // Remaining days
            const remainingMain = 42 - (firstDay + daysInMonth);
            for (let i = 1; i <= remainingMain; i++) {
                mainHtml += `<div style="background: #F9FAFB; padding: 12px; min-height: 100px; color: #D1D5DB;"><div style="font-weight: 600; margin-bottom: 8px;">${i}</div></div>`;
            }

            mainCalEl.innerHTML = mainHtml;
        }

        function tlRenderUpcomingEvents() {
            const container = document.getElementById('tlUpcomingEventsList');
            if (!container) return;

            const today = new Date().toISOString().split('T')[0];
            const upcomingEvents = tlSchedulerEvents.filter(e => {
                const eventStart = new Date(e.start_date).toISOString().split('T')[0];
                return eventStart >= today;
            }).sort((a, b) => new Date(a.start_date) - new Date(b.start_date)).slice(0, 5);

            if (upcomingEvents.length === 0) {
                container.innerHTML = `<div class="empty-state"><i class="fas fa-calendar-check"></i><p>No upcoming events</p></div>`;
                return;
            }

            let html = '';
            upcomingEvents.forEach(event => {
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

                const timeStr = event.all_day ? 'All Day' : startDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });

                html += `
                    <div style="padding: 16px; border-radius: 12px; margin-bottom: 12px; background: ${event.color}10; border-left: 4px solid ${event.color}; cursor: pointer; transition: all 0.3s;" onclick="tlViewEvent(${event.id})">
                        <div style="font-weight: 700; color: #1F2937; margin-bottom: 4px;">${escapeHtml(event.title)}</div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;"><i class="fas fa-calendar"></i> ${dateStr}</div>
                        <div style="font-size: 12px; color: #6B7280;"><i class="fas fa-clock"></i> ${timeStr}</div>
                        ${event.location ? `<div style="font-size: 12px; color: #6B7280; margin-top: 4px;"><i class="fas fa-map-marker-alt"></i> ${escapeHtml(event.location)}</div>` : ''}
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function tlRenderRecentBookings() {
            const container = document.getElementById('tlRecentBookingsList');
            if (!container) return;

            const recentBookings = tlSchedulerBookings.slice(0, 5);

            if (recentBookings.length === 0) {
                container.innerHTML = `<div class="empty-state"><i class="fas fa-calendar-alt"></i><p>No bookings found</p></div>`;
                return;
            }

            let html = '';
            recentBookings.forEach(booking => {
                const statusColor = booking.status === 'approved' ? 'var(--forest-green)' : (booking.status === 'pending' ? 'var(--gold-dark)' : '#DC2626');
                const date = new Date(booking.date);
                const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

                html += `
                    <div style="padding: 16px; border-radius: 12px; margin-bottom: 12px; background: white; border: 1px solid #E5E7EB; cursor: pointer; transition: all 0.3s;" onclick="tlViewBooking(${booking.id})">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                            <div style="font-weight: 700; color: #1F2937;">${escapeHtml(booking.agency)}</div>
                            <span class="badge" style="background: ${statusColor}20; color: ${statusColor}; font-size: 10px;">${booking.status.toUpperCase()}</span>
                        </div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;"><i class="fas fa-calendar"></i> ${dateStr}</div>
                        <div style="font-size: 12px; color: #6B7280;"><i class="fas fa-clock"></i> ${booking.time}</div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        function tlPreviousMonth() {
            tlSchedulerCurrentMonth--;
            if (tlSchedulerCurrentMonth < 0) {
                tlSchedulerCurrentMonth = 11;
                tlSchedulerCurrentYear--;
            }
            tlRenderSchedulerCalendar();
        }

        function tlNextMonth() {
            tlSchedulerCurrentMonth++;
            if (tlSchedulerCurrentMonth > 11) {
                tlSchedulerCurrentMonth = 0;
                tlSchedulerCurrentYear++;
            }
            tlRenderSchedulerCalendar();
        }

        function tlViewEvent(eventId) {
            const event = tlSchedulerEvents.find(e => e.id === eventId);
            if (!event) return;

            const startDate = new Date(event.start_date);
            const endDate = new Date(event.end_date);
            const dateStr = startDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            const endDateStr = endDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            const timeStr = event.all_day ? 'All Day' : `${startDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })} - ${endDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })}`;

            let actionButtons = '';
            if (tlHasEditAccess) {
                actionButtons = `
                    <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #E5E7EB; display: flex; gap: 12px; justify-content: flex-end;">
                        <button onclick="this.closest('.custom-alert-overlay').remove(); tlEditEvent(${event.id})" style="padding: 10px 20px; background: var(--maroon); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button onclick="this.closest('.custom-alert-overlay').remove(); tlDeleteEvent(${event.id})" style="padding: 10px 20px; background: #FEE2E2; color: #DC2626; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                `;
            }

            const message = `
                <div style="text-align: left;">
                    <div style="margin-bottom: 16px; padding: 16px; background: ${event.color}10; border-left: 4px solid ${event.color}; border-radius: 8px;">
                        <h3 style="color: ${event.color}; margin-bottom: 8px; font-size: 18px; font-weight: 700;">${escapeHtml(event.title)}</h3>
                        ${event.description ? `<p style="color: #6B7280; font-size: 14px;">${escapeHtml(event.description)}</p>` : ''}
                    </div>
                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 12px; font-size: 14px;">
                        <div style="color: #6B7280;"><i class="fas fa-calendar"></i> Date:</div>
                        <div style="font-weight: 600;">${dateStr}${dateStr !== endDateStr ? ` - ${endDateStr}` : ''}</div>

                        <div style="color: #6B7280;"><i class="fas fa-clock"></i> Time:</div>
                        <div style="font-weight: 600;">${timeStr}</div>

                        ${event.location ? `
                            <div style="color: #6B7280;"><i class="fas fa-map-marker-alt"></i> Location:</div>
                            <div style="font-weight: 600;">${escapeHtml(event.location)}</div>
                        ` : ''}
                    </div>
                    ${actionButtons}
                </div>
            `;

            showCustomAlert('Event Details', message);
        }

        function tlViewBooking(bookingId) {
            const booking = tlSchedulerBookings.find(b => b.id === bookingId);
            if (!booking) return;

            const date = new Date(booking.date);
            const dateStr = date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            const statusColor = booking.status === 'approved' ? 'var(--forest-green)' : (booking.status === 'pending' ? 'var(--gold-dark)' : '#DC2626');

            let actionButtons = '';
            if (tlHasEditAccess && booking.status === 'pending') {
                actionButtons = `
                    <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #E5E7EB; display: flex; gap: 12px; justify-content: flex-end;">
                        <button onclick="this.closest('.custom-alert-overlay').remove(); tlApproveBooking(${booking.id})" style="padding: 10px 20px; background: #10B981; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-check"></i> Approve
                        </button>
                        <button onclick="this.closest('.custom-alert-overlay').remove(); tlRejectBooking(${booking.id})" style="padding: 10px 20px; background: #EF4444; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </div>
                `;
            }

            // Email notification section for approved bookings
            let emailSection = '';
            if (booking.status === 'approved' && tlHasEditAccess) {
                if (booking.admin_emailed) {
                    emailSection = `
                        <div style="margin-top: 20px; padding: 16px; background: #D1FAE5; border: 1px solid #10B981; border-radius: 12px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="fas fa-envelope-circle-check" style="font-size: 20px; color: #059669;"></i>
                                <div>
                                    <div style="font-weight: 600; color: #065F46;">Email Notification Sent</div>
                                    <div style="font-size: 12px; color: #047857;">The booker has been notified about the booking status.</div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    const subject = `Booking Approved - ${booking.event} on ${dateStr}`;
                    const emailBody = `Dear ${booking.agency},

We are pleased to inform you that your booking request has been APPROVED.

📅 BOOKING DETAILS:
━━━━━━━━━━━━━━━━━━━━
Date: ${dateStr}
Time: ${booking.time}
Purpose: ${booking.event}

Please arrive at least 15 minutes before your scheduled time. If you need to reschedule or cancel, please contact us as soon as possible.

We look forward to seeing you!

Best regards,
UP Cebu Innovation & Technology Hub
University of the Philippines Cebu
📧 info@upcebu.edu.ph
📞 +63 32 123 4567`;

                    emailSection = `
                        <div style="margin-top: 20px; padding: 16px; background: #FEF3C7; border: 1px solid #F59E0B; border-radius: 12px;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                                <i class="fas fa-envelope" style="font-size: 20px; color: #D97706;"></i>
                                <div>
                                    <div style="font-weight: 600; color: #92400E;">Email Not Yet Sent</div>
                                    <div style="font-size: 12px; color: #78350F;">The booker has not been notified about the approval.</div>
                                </div>
                            </div>
                            <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 16px; margin-bottom: 16px; max-height: 200px; overflow-y: auto;">
                                <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">
                                    <strong>To:</strong> ${booking.email}
                                </div>
                                <div style="font-size: 12px; color: #6B7280; margin-bottom: 12px;">
                                    <strong>Subject:</strong> ${subject}
                                </div>
                                <div style="font-size: 13px; color: #374151; white-space: pre-line; line-height: 1.6;">${emailBody}</div>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                                <button onclick="tlCopyEmailContent('${booking.email}', '${subject.replace(/'/g, "\\'")}', \`${emailBody.replace(/`/g, '\\`')}\`)" style="padding: 10px; background: #3B82F6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <i class="fas fa-copy"></i> Copy to Clipboard
                                </button>
                                <button onclick="tlOpenMailClient('${booking.email}', '${subject.replace(/'/g, "\\'")}', \`${emailBody.replace(/`/g, '\\`')}\`)" style="padding: 10px; background: #8B5CF6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <i class="fas fa-external-link-alt"></i> Open in Email App
                                </button>
                            </div>
                            <button onclick="tlMarkAsEmailed(${booking.id})" style="width: 100%; padding: 10px; background: #10B981; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <i class="fas fa-check-circle"></i> Mark as Emailed
                            </button>
                        </div>
                    `;
                }
            }

            const message = `
                <div style="text-align: left;">
                    <div style="margin-bottom: 16px; padding: 16px; background: #DBEAFE; border-left: 4px solid #3B82F6; border-radius: 8px;">
                        <h3 style="color: #1E40AF; margin-bottom: 8px; font-size: 18px; font-weight: 700;">${escapeHtml(booking.agency)}</h3>
                        <span class="badge" style="background: ${statusColor}; color: white;">${booking.status.toUpperCase()}</span>
                    </div>
                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 12px; font-size: 14px;">
                        <div style="color: #6B7280;"><i class="fas fa-calendar"></i> Date:</div>
                        <div style="font-weight: 600;">${dateStr}</div>

                        <div style="color: #6B7280;"><i class="fas fa-clock"></i> Time:</div>
                        <div style="font-weight: 600;">${booking.time}</div>

                        ${booking.event ? `
                            <div style="color: #6B7280;"><i class="fas fa-tag"></i> Purpose:</div>
                            <div style="font-weight: 600;">${escapeHtml(booking.event)}</div>
                        ` : ''}

                        ${booking.contact_person ? `
                            <div style="color: #6B7280;"><i class="fas fa-user"></i> Contact Person:</div>
                            <div style="font-weight: 600;">${escapeHtml(booking.contact_person)}</div>
                        ` : ''}

                        ${booking.email ? `
                            <div style="color: #6B7280;"><i class="fas fa-envelope"></i> Email:</div>
                            <div style="font-weight: 600;">${booking.email}</div>
                        ` : ''}

                        ${booking.phone ? `
                            <div style="color: #6B7280;"><i class="fas fa-phone"></i> Phone:</div>
                            <div style="font-weight: 600;">${booking.phone}</div>
                        ` : ''}

                        ${booking.purpose ? `
                            <div style="color: #6B7280; grid-column: 1;"><i class="fas fa-sticky-note"></i> Notes:</div>
                            <div style="font-weight: 600; grid-column: 2;">${escapeHtml(booking.purpose)}</div>
                        ` : ''}
                    </div>
                    ${emailSection}
                    ${actionButtons}
                </div>
            `;

            showCustomAlert('Booking Details', message);
        }

        function tlCopyEmailContent(email, subject, body) {
            const fullContent = `To: ${email}\nSubject: ${subject}\n\n${body}`;

            navigator.clipboard.writeText(fullContent).then(() => {
                showToast('Email content copied to clipboard!');
            }).catch(() => {
                const textArea = document.createElement('textarea');
                textArea.value = fullContent;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showToast('Email content copied to clipboard!');
            });
        }

        function tlOpenMailClient(email, subject, body) {
            const mailtoLink = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
            window.open(mailtoLink, '_blank');
            showToast('Email app should now open with pre-filled content.');
        }

        function tlMarkAsEmailed(bookingId) {
            fetch(`/admin/bookings/${bookingId}/send-email`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Marked as emailed successfully!');
                    // Update the booking in the array
                    const booking = tlSchedulerBookings.find(b => b.id === bookingId);
                    if (booking) {
                        booking.admin_emailed = true;
                    }
                    // Close and reopen the modal to refresh
                    document.querySelector('.custom-alert-overlay')?.remove();
                    setTimeout(() => tlViewBooking(bookingId), 100);
                } else {
                    showToast(data.message || 'Failed to update status.', true);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while updating the status.', true);
            });
        }

        function showCustomAlert(title, htmlContent) {
            const overlay = document.createElement('div');
            overlay.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 10000; display: flex; align-items: center; justify-content: center;';

            const modal = document.createElement('div');
            modal.style.cssText = 'background: white; border-radius: 20px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);';

            modal.innerHTML = `
                <div style="background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%); padding: 24px; color: white; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="font-size: 18px; font-weight: 700; margin: 0;">${title}</h2>
                    <button onclick="this.closest('.custom-alert-overlay').remove()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 18px;">&times;</button>
                </div>
                <div style="padding: 24px;">
                    ${htmlContent}
                </div>
                <div style="padding: 16px 24px; border-top: 1px solid #E5E7EB; text-align: right;">
                    <button onclick="this.closest('.custom-alert-overlay').remove()" class="btn btn-primary">Close</button>
                </div>
            `;

            overlay.className = 'custom-alert-overlay';
            overlay.appendChild(modal);
            document.body.appendChild(overlay);

            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) overlay.remove();
            });
        }

        function tlShowCreateEventModal() {
            if (!tlHasEditAccess) {
                showToast('You do not have permission to create events', true);
                return;
            }

            document.getElementById('tlEventId').value = '';
            document.getElementById('tlEventModalTitle').textContent = 'Create Event';
            document.getElementById('tlEventTitle').value = '';
            document.getElementById('tlEventDeleteBtn').style.display = 'none';
            document.getElementById('tlEventDescription').value = '';
            document.getElementById('tlEventStartDate').value = '';
            document.getElementById('tlEventEndDate').value = '';
            document.getElementById('tlEventLocation').value = '';
            document.getElementById('tlEventColor').value = '#3B82F6';
            document.getElementById('tlEventAllDay').checked = false;

            // Reset to datetime-local inputs
            document.getElementById('tlEventStartDate').type = 'datetime-local';
            document.getElementById('tlEventEndDate').type = 'datetime-local';
            document.getElementById('tlStartDateLabel').textContent = 'Start Date & Time *';
            document.getElementById('tlEndDateLabel').textContent = 'End Date & Time *';

            document.getElementById('tlEventModal').style.display = 'flex';
        }

        function tlCloseEventModal() {
            document.getElementById('tlEventModal').style.display = 'none';
        }

        async function tlEditEvent(eventId) {
            if (!tlHasEditAccess) {
                showToast('You do not have permission to edit events', true);
                return;
            }

            try {
                const response = await fetch(`/intern/events`);
                const data = await response.json();
                const event = data.events.find(e => e.id === eventId);

                if (event) {
                    document.getElementById('tlEventId').value = event.id;
                    document.getElementById('tlEventModalTitle').textContent = 'Edit Event';
                    document.getElementById('tlEventTitle').value = event.title;
                    document.getElementById('tlEventDeleteBtn').style.display = 'flex';
                    document.getElementById('tlEventDescription').value = event.description || '';
                    document.getElementById('tlEventLocation').value = event.location || '';
                    document.getElementById('tlEventColor').value = event.color;
                    document.getElementById('tlEventAllDay').checked = event.all_day;

                    // Set input type and values based on all_day
                    if (event.all_day) {
                        document.getElementById('tlEventStartDate').type = 'date';
                        document.getElementById('tlEventEndDate').type = 'date';
                        document.getElementById('tlEventStartDate').value = event.start_date.split(' ')[0];
                        document.getElementById('tlEventEndDate').value = event.end_date.split(' ')[0];
                        document.getElementById('tlStartDateLabel').textContent = 'Start Date *';
                        document.getElementById('tlEndDateLabel').textContent = 'End Date *';
                    } else {
                        document.getElementById('tlEventStartDate').type = 'datetime-local';
                        document.getElementById('tlEventEndDate').type = 'datetime-local';
                        document.getElementById('tlEventStartDate').value = new Date(event.start_date).toISOString().slice(0, 16);
                        document.getElementById('tlEventEndDate').value = new Date(event.end_date).toISOString().slice(0, 16);
                        document.getElementById('tlStartDateLabel').textContent = 'Start Date & Time *';
                        document.getElementById('tlEndDateLabel').textContent = 'End Date & Time *';
                    }

                    document.getElementById('tlEventModal').style.display = 'flex';
                }
            } catch (error) {
                console.error('Error loading event:', error);
                showToast('Failed to load event details', true);
            }
        }

        function tlToggleAllDayEvent() {
            const allDay = document.getElementById('tlEventAllDay').checked;
            const startInput = document.getElementById('tlEventStartDate');
            const endInput = document.getElementById('tlEventEndDate');
            const startLabel = document.getElementById('tlStartDateLabel');
            const endLabel = document.getElementById('tlEndDateLabel');

            if (allDay) {
                startInput.type = 'date';
                endInput.type = 'date';
                startLabel.textContent = 'Start Date *';
                endLabel.textContent = 'End Date *';
            } else {
                startInput.type = 'datetime-local';
                endInput.type = 'datetime-local';
                startLabel.textContent = 'Start Date & Time *';
                endLabel.textContent = 'End Date & Time *';
            }
        }

        async function tlSaveEvent() {
            if (!tlHasEditAccess) {
                showToast('You do not have permission to save events', true);
                return;
            }

            const eventId = document.getElementById('tlEventId').value;
            const title = document.getElementById('tlEventTitle').value.trim();
            const description = document.getElementById('tlEventDescription').value.trim();
            const startDate = document.getElementById('tlEventStartDate').value;
            const endDate = document.getElementById('tlEventEndDate').value;
            const location = document.getElementById('tlEventLocation').value.trim();
            const color = document.getElementById('tlEventColor').value;
            const allDay = document.getElementById('tlEventAllDay').checked;

            if (!title || !startDate || !endDate) {
                showToast('Please fill in all required fields', true);
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
                    showToast(result.message || 'Event saved successfully');
                    tlCloseEventModal();
                    tlLoadSchedulerData();
                } else {
                    const errors = result.errors;
                    if (errors) {
                        const firstError = Object.values(errors)[0][0];
                        showToast(firstError, true);
                    } else {
                        showToast('Error saving event', true);
                    }
                }
            } catch (error) {
                console.error('Error saving event:', error);
                showToast('An error occurred while saving the event', true);
            }
        }

        async function tlDeleteEvent(eventId) {
            if (!tlHasEditAccess) {
                showToast('You do not have permission to delete events', true);
                return;
            }

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
                    showToast(result.message || 'Event deleted successfully');
                    tlLoadSchedulerData();
                } else {
                    showToast('Error deleting event', true);
                }
            } catch (error) {
                console.error('Error deleting event:', error);
                showToast('An error occurred while deleting the event', true);
            }
        }

        async function tlDeleteEventFromModal() {
            const eventId = document.getElementById('tlEventId').value;
            if (!eventId) return;

            tlCloseEventModal();
            await tlDeleteEvent(eventId);
        }

        // ==================== BLOCK DATE FUNCTIONS ====================
        function tlOpenBlockDateModal() {
            if (!tlHasEditAccess) {
                showToast('You do not have permission to block dates', true);
                return;
            }
            document.getElementById('tlBlockDateValue').value = '';
            document.getElementById('tlBlockDateDays').value = '1';
            document.getElementById('tlBlockDateReason').value = '';
            document.getElementById('tlBlockDateDescription').value = '';
            document.getElementById('tlBlockDateModal').style.display = 'flex';
        }

        function tlCloseBlockDateModal() {
            document.getElementById('tlBlockDateModal').style.display = 'none';
        }

        async function tlSubmitBlockDate() {
            if (!tlHasEditAccess) {
                showToast('You do not have permission to block dates', true);
                return;
            }

            const date = document.getElementById('tlBlockDateValue').value;
            const days = parseInt(document.getElementById('tlBlockDateDays').value) || 1;
            const reason = document.getElementById('tlBlockDateReason').value;
            const description = document.getElementById('tlBlockDateDescription').value;

            if (!date || !reason) {
                showToast('Please fill in all required fields', true);
                return;
            }

            try {
                const requests = [];
                const startDate = new Date(date);

                for (let i = 0; i < days; i++) {
                    const currentDate = new Date(startDate);
                    currentDate.setDate(startDate.getDate() + i);
                    const dateStr = currentDate.toISOString().split('T')[0];

                    requests.push(
                        fetch('/admin/blocked-dates', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                date: dateStr,
                                reason: reason,
                                description: description
                            })
                        })
                    );
                }

                const responses = await Promise.all(requests);
                const allSuccessful = responses.every(r => r.ok);

                if (allSuccessful) {
                    showToast(days === 1 ? 'Date blocked successfully' : `${days} dates blocked successfully`);
                    tlCloseBlockDateModal();
                    tlLoadSchedulerData();
                } else {
                    showToast('Some dates could not be blocked', true);
                }
            } catch (error) {
                console.error('Error blocking dates:', error);
                showToast('An error occurred while blocking dates', true);
            }
        }

        // ==================== BOOKING APPROVAL FUNCTIONS ====================
        async function tlApproveBooking(bookingId) {
            if (!tlHasEditAccess) {
                showToast('You do not have permission to approve bookings', true);
                return;
            }

            if (!confirm('Are you sure you want to approve this booking?')) {
                return;
            }

            try {
                const response = await fetch(`/admin/bookings/${bookingId}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    showToast('Booking approved successfully');
                    tlLoadSchedulerData();
                } else {
                    showToast(result.message || 'Failed to approve booking', true);
                }
            } catch (error) {
                console.error('Error approving booking:', error);
                showToast('An error occurred while approving the booking', true);
            }
        }

        async function tlRejectBooking(bookingId) {
            if (!tlHasEditAccess) {
                showToast('You do not have permission to reject bookings', true);
                return;
            }

            const reason = prompt('Please provide a reason for rejection (optional):');
            if (reason === null) return; // User cancelled

            try {
                const response = await fetch(`/admin/bookings/${bookingId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ reason: reason || 'No reason provided' })
                });

                const result = await response.json();

                if (response.ok) {
                    showToast('Booking rejected successfully');
                    tlLoadSchedulerData();
                } else {
                    showToast(result.message || 'Failed to reject booking', true);
                }
            } catch (error) {
                console.error('Error rejecting booking:', error);
                showToast('An error occurred while rejecting the booking', true);
            }
        }
    </script>
</body>
</html>
