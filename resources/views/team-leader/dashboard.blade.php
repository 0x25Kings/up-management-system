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
                            <div class="list-item" style="cursor: pointer;" onclick="editTask({{ $task->id }})">
                                <div class="list-item-avatar"><i class="fas fa-clipboard-list"></i></div>
                                <div class="list-item-content">
                                    <div class="list-item-title">{{ $task->title }}</div>
                                    <div class="list-item-subtitle">
                                        {{ $task->intern->name ?? 'N/A' }} • Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No date' }}
                                    </div>
                                </div>
                                <span class="badge badge-{{ $task->status === 'Completed' ? 'success' : ($task->status === 'In Progress' ? 'info' : 'warning') }}">
                                    {{ $task->status }}
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
                                    <span class="badge badge-{{ $task->status === 'Completed' ? 'success' : ($task->status === 'In Progress' ? 'info' : ($task->status === 'On Hold' ? 'danger' : 'warning')) }}">
                                        {{ $task->status }}
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
                    <div class="stat-value">{{ isset($schedulerData['bookings']) ? $schedulerData['bookings']->count() : 0 }}</div>
                    <div class="stat-label">Recent Bookings</div>
                </div>
                <div class="stat-card gold">
                    <div class="stat-icon gold"><i class="fas fa-clock"></i></div>
                    <div class="stat-value">{{ $schedulerData['pendingBookings'] ?? 0 }}</div>
                    <div class="stat-label">Pending Bookings</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon green"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-value">{{ isset($schedulerData['events']) ? $schedulerData['events']->count() : 0 }}</div>
                    <div class="stat-label">Recent Events</div>
                </div>
                <div class="stat-card maroon">
                    <div class="stat-icon maroon"><i class="fas fa-ban"></i></div>
                    <div class="stat-value">{{ isset($schedulerData['blockedDates']) ? $schedulerData['blockedDates']->count() : 0 }}</div>
                    <div class="stat-label">Blocked Dates</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Recent Bookings</h3>
                    @if(in_array('scheduler', $editableModules))
                    <span class="badge badge-success"><i class="fas fa-edit"></i> Edit Access</span>
                    @else
                    <span class="badge badge-info"><i class="fas fa-eye"></i> View Only</span>
                    @endif
                </div>
                <div class="card-body" style="padding: 0; overflow-x: auto;">
                    @if(isset($schedulerData['bookings']) && $schedulerData['bookings']->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Organization</th>
                                <th>Contact</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Room</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedulerData['bookings'] as $booking)
                            <tr>
                                <td><strong>{{ $booking->organization_name }}</strong></td>
                                <td>{{ $booking->contact_person }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</td>
                                <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                                <td>{{ $booking->room_type }}</td>
                                <td>
                                    <span class="badge badge-{{ $booking->status === 'approved' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-calendar-alt"></i>
                        <h4>No bookings found</h4>
                        <p>There are no room bookings to display</p>
                    </div>
                    @endif
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
                    <div class="empty-state">
                        <i class="fas fa-file-alt"></i>
                        <h4>Digital Records Module</h4>
                        <p>You have been granted access to the Digital Records module. This feature will be fully integrated soon.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- ==================== MODALS ==================== -->

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
                                <span class="badge badge-${task.status === 'Completed' ? 'success' : (task.status === 'In Progress' ? 'info' : 'warning')}">${task.status}</span>
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
    </script>
</body>
</html>
