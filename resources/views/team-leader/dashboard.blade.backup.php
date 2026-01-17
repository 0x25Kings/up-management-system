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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            -moz-osx-font-smoothing: grayscale;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            width: 280px;
            min-width: 280px;
            max-width: 280px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            box-shadow: 4px 0 30px rgba(123, 17, 19, 0.2);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--gold);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--gold-light);
        }

        .sidebar-logo {
            padding: 32px 24px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 215, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.1) 0%, transparent 100%);
        }

        .sidebar-logo img {
            height: 70px;
            width: auto;
            margin: 0 auto 16px auto;
            display: block;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
            transition: transform 0.3s ease;
        }

        .sidebar-logo img:hover {
            transform: scale(1.05);
        }

        .sidebar-logo h3 {
            color: var(--white);
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 8px;
            text-align: center;
            line-height: 1.4;
            letter-spacing: 0.3px;
        }

        .sidebar-logo p {
            color: var(--gold);
            font-size: 11px;
            text-align: center;
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
            margin-top: 8px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            position: relative;
            margin: 3px 14px;
            border-radius: 12px;
            cursor: pointer;
        }

        .menu-item i:first-child {
            width: 24px;
            margin-right: 14px;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            transform: translateX(4px);
        }

        .menu-item:hover i:first-child {
            color: var(--gold);
        }

        .menu-item.active {
            background: linear-gradient(135deg, var(--forest-green) 0%, var(--forest-green-dark) 100%);
            color: var(--white);
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(34, 139, 34, 0.4);
        }

        .menu-item.active i:first-child {
            color: var(--gold);
        }

        .menu-badge {
            background: var(--gold);
            color: var(--maroon);
            font-size: 10px;
            padding: 3px 10px;
            border-radius: 12px;
            margin-left: auto;
            font-weight: 700;
        }

        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            background: var(--light-gray);
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        .header-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--maroon);
            letter-spacing: -0.5px;
        }

        .header-subtitle {
            font-size: 14px;
            color: #6B7280;
            margin-top: 4px;
            font-weight: 500;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 8px 16px;
            background: var(--off-white);
            border-radius: 16px;
            border: 1px solid rgba(0, 0, 0, 0.05);
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
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(123, 17, 19, 0.3);
        }

        .user-name {
            font-weight: 700;
            color: #1F2937;
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            color: var(--forest-green);
            font-weight: 600;
        }

        .page-content {
            display: none;
            padding: 36px;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .page-content.active {
            display: block;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            margin-bottom: 36px;
        }

        .stat-card {
            background: var(--white);
            padding: 28px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border-left: 5px solid;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.05;
            transform: translate(30%, -30%);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        }

        .stat-card.maroon { border-left-color: var(--maroon); }
        .stat-card.maroon::before { background: var(--maroon); }
        .stat-card.green { border-left-color: var(--forest-green); }
        .stat-card.green::before { background: var(--forest-green); }
        .stat-card.gold { border-left-color: var(--gold-dark); }
        .stat-card.gold::before { background: var(--gold); }
        .stat-card.red { border-left-color: #DC2626; }
        .stat-card.red::before { background: #DC2626; }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 18px;
        }

        .stat-icon.maroon { background: rgba(123, 17, 19, 0.1); color: var(--maroon); }
        .stat-icon.green { background: rgba(34, 139, 34, 0.1); color: var(--forest-green); }
        .stat-icon.gold { background: rgba(255, 215, 0, 0.2); color: var(--gold-dark); }
        .stat-icon.red { background: rgba(220, 38, 38, 0.1); color: #DC2626; }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: #1F2937;
            margin-bottom: 6px;
            letter-spacing: -1px;
        }

        .stat-label {
            font-size: 14px;
            color: #6B7280;
            font-weight: 600;
        }

        /* Cards */
        .card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            padding: 24px 28px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(180deg, var(--off-white) 0%, var(--white) 100%);
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--maroon);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--forest-green);
        }

        .card-body {
            padding: 28px;
        }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            text-align: left;
            padding: 14px 18px;
            background: var(--off-white);
            color: var(--maroon);
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 2px solid rgba(123, 17, 19, 0.1);
        }

        .data-table td {
            padding: 18px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            color: #1F2937;
            font-size: 14px;
        }

        .data-table tr {
            transition: background 0.2s ease;
        }

        .data-table tr:hover {
            background: rgba(34, 139, 34, 0.03);
        }

        /* Badges */
        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success { background: rgba(34, 139, 34, 0.1); color: var(--forest-green); }
        .badge-warning { background: rgba(255, 215, 0, 0.3); color: #92400E; }
        .badge-danger { background: rgba(220, 38, 38, 0.1); color: #DC2626; }
        .badge-info { background: rgba(123, 17, 19, 0.1); color: var(--maroon); }

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(123, 17, 19, 0.3);
        }

        .btn-primary:hover {
            box-shadow: 0 8px 25px rgba(123, 17, 19, 0.4);
            transform: translateY(-3px);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--forest-green) 0%, var(--forest-green-dark) 100%);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(34, 139, 34, 0.3);
        }

        .btn-success:hover {
            box-shadow: 0 8px 25px rgba(34, 139, 34, 0.4);
            transform: translateY(-3px);
        }

        .btn-danger {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: var(--white);
        }

        .btn-secondary {
            background: var(--off-white);
            color: var(--maroon);
            border: 2px solid rgba(123, 17, 19, 0.1);
        }

        .btn-secondary:hover {
            border-color: var(--maroon);
            background: rgba(123, 17, 19, 0.05);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
            border-radius: 10px;
        }

        /* Progress bar */
        .progress-container {
            width: 100%;
            height: 10px;
            background: rgba(0, 0, 0, 0.06);
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 5px;
            transition: width 0.5s ease;
        }

        .progress-bar.maroon { background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-light) 100%); }
        .progress-bar.green { background: linear-gradient(135deg, var(--forest-green) 0%, var(--forest-green-light) 100%); }
        .progress-bar.gold { background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold) 100%); }
        .progress-bar.red { background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%); }

        /* Alert boxes */
        .alert {
            padding: 18px 24px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
            font-weight: 500;
        }

        .alert i {
            font-size: 20px;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.15) 0%, rgba(255, 215, 0, 0.05) 100%);
            border-left: 5px solid var(--gold-dark);
            color: #92400E;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(34, 139, 34, 0.1) 0%, rgba(34, 139, 34, 0.03) 100%);
            border-left: 5px solid var(--forest-green);
            color: #065F46;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(220, 38, 38, 0.03) 100%);
            border-left: 5px solid #DC2626;
            color: #991B1B;
        }

        /* Grid layouts */
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 28px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }

        @media (max-width: 1200px) {
            .grid-3 { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 900px) {
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            .sidebar { width: 240px; min-width: 240px; max-width: 240px; }
            .main-content { margin-left: 240px; }
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 16px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 56px 28px;
            color: #6B7280;
        }

        .empty-state i {
            font-size: 56px;
            margin-bottom: 20px;
            color: rgba(123, 17, 19, 0.2);
        }

        .empty-state h4 {
            font-size: 18px;
            font-weight: 700;
            color: var(--maroon);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #9CA3AF;
            font-size: 14px;
        }

        /* List items */
        .list-item {
            display: flex;
            align-items: center;
            padding: 18px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .list-item:hover {
            background: linear-gradient(135deg, rgba(34, 139, 34, 0.03) 0%, rgba(255, 215, 0, 0.03) 100%);
            transform: translateX(4px);
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item-avatar {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 700;
            margin-right: 18px;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(123, 17, 19, 0.2);
        }

        .list-item-content {
            flex: 1;
        }

        .list-item-title {
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 6px;
            font-size: 15px;
        }

        .list-item-subtitle {
            font-size: 13px;
            color: #6B7280;
            font-weight: 500;
        }

        /* Logout button */
        .logout-btn {
            margin: 20px 14px;
            padding: 14px 24px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
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
            width: calc(100% - 28px);
        }

        .logout-btn:hover {
            background: rgba(255, 215, 0, 0.15);
            border-color: var(--gold);
            transform: translateY(-2px);
        }

        /* Filter section */
        .filter-section {
            display: flex;
            gap: 14px;
            margin-bottom: 28px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-section input,
        .filter-section select {
            padding: 12px 18px;
            border: 2px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            font-size: 14px;
            background: var(--white);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .filter-section input:focus,
        .filter-section select:focus {
            outline: none;
            border-color: var(--maroon);
            box-shadow: 0 0 0 4px rgba(123, 17, 19, 0.1);
        }

        /* Task Overview Section */
        .task-overview-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 28px;
            text-align: center;
        }

        .task-overview-item {
            padding: 24px;
            background: var(--off-white);
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .task-overview-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .task-overview-value {
            font-size: 40px;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 8px;
        }

        .task-overview-label {
            color: #6B7280;
            font-weight: 600;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .task-overview-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
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

            <div style="flex-grow: 1;"></div>

            <form action="{{ route('admin.logout') }}" method="POST" id="logoutForm">
                @csrf
                <input type="hidden" name="redirect_to" value="intern">
            </form>
            <button type="button" class="logout-btn" onclick="handleLogout()">
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
            <div class="user-info">
                <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div>
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-role">Team Leader</div>
                </div>
            </div>
        </div>

        <!-- Dashboard Page -->
        <div id="dashboard" class="page-content active">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card maroon">
                    <div class="stat-icon maroon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value">{{ $totalInterns }}</div>
                    <div class="stat-label">Total Interns</div>
                </div>

                <div class="stat-card green">
                    <div class="stat-icon green">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-value">{{ $activeInterns }}</div>
                    <div class="stat-label">Active Interns</div>
                </div>

                <div class="stat-card gold">
                    <div class="stat-icon gold">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-value">{{ $totalTasks }}</div>
                    <div class="stat-label">Total Tasks</div>
                </div>

                <div class="stat-card green">
                    <div class="stat-icon green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value">{{ $completedTasks }}</div>
                    <div class="stat-label">Completed Tasks</div>
                </div>

                <div class="stat-card red">
                    <div class="stat-icon red">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-value">{{ $overdueTasks }}</div>
                    <div class="stat-label">Overdue Tasks</div>
                </div>

                <div class="stat-card maroon">
                    <div class="stat-icon maroon">
                        <i class="fas fa-clock"></i>
                    </div>
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

            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="{{ route('team-leader.tasks.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create New Task
                </a>
                <a href="{{ route('team-leader.reports.create') }}" class="btn btn-success">
                    <i class="fas fa-file-alt"></i>
                    Create Report
                </a>
            </div>

            <div class="grid-2">
                <!-- Recent Tasks -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Recent Tasks</h3>
                        <a href="{{ route('team-leader.tasks') }}" class="btn btn-sm btn-secondary">View All</a>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        @forelse($recentTasks as $task)
                            <div class="list-item">
                                <div class="list-item-avatar">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div class="list-item-content">
                                    <div class="list-item-title">{{ $task->title }}</div>
                                    <div class="list-item-subtitle">
                                        Assigned to: {{ $task->intern->name ?? 'N/A' }} • 
                                        Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}
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
                        <a href="{{ route('team-leader.interns') }}" class="btn btn-sm btn-secondary">View All</a>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        @forelse($internsNeedingAttention as $intern)
                            @php
                                $progress = $intern->required_hours > 0 
                                    ? round(($intern->completed_hours / $intern->required_hours) * 100, 1) 
                                    : 0;
                            @endphp
                            <div class="list-item">
                                <div class="list-item-avatar">
                                    {{ strtoupper(substr($intern->name, 0, 1)) }}
                                </div>
                                <div class="list-item-content">
                                    <div class="list-item-title">{{ $intern->name }}</div>
                                    <div class="list-item-subtitle">
                                        {{ $intern->course }} • {{ number_format($intern->completed_hours, 1) }} / {{ $intern->required_hours }} hrs
                                    </div>
                                    <div class="progress-container" style="margin-top: 10px;">
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

            <!-- Task Statistics Chart -->
            <div class="card" style="margin-top: 28px;">
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
            <div class="card" style="margin-top: 28px;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-file-alt"></i> My Recent Reports</h3>
                    <a href="{{ route('team-leader.reports') }}" class="btn btn-sm btn-secondary">View All</a>
                </div>
                <div class="card-body" style="padding: 0;">
                    @forelse($recentReports as $report)
                        <div class="list-item">
                            <div class="list-item-avatar" style="background: linear-gradient(135deg, var(--forest-green) 0%, var(--forest-green-dark) 100%);">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="list-item-content">
                                <div class="list-item-title">{{ $report->title }}</div>
                                <div class="list-item-subtitle">
                                    {{ ucfirst($report->report_type) }} Report • {{ $report->created_at->format('M d, Y') }}
                                </div>
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

        <!-- Interns Page -->
        <div id="interns" class="page-content">
            <!-- Intern Stats -->
            <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
                <div class="stat-card maroon">
                    <div class="stat-icon maroon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value">{{ $allInterns->count() }}</div>
                    <div class="stat-label">Total Interns</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon green">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-value">{{ $allInterns->where('status', 'Active')->count() }}</div>
                    <div class="stat-label">Active</div>
                </div>
                <div class="stat-card gold">
                    <div class="stat-icon gold">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-value">{{ $allInterns->where('status', 'Completed')->count() }}</div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users"></i> My Interns</h3>
                    <a href="{{ route('team-leader.interns') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-external-link-alt"></i> Full View
                    </a>
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
                            @foreach($allInterns->take(10) as $intern)
                            @php
                                $progress = $intern->required_hours > 0 
                                    ? round(($intern->completed_hours / $intern->required_hours) * 100, 1) 
                                    : 0;
                            @endphp
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div class="list-item-avatar" style="width: 38px; height: 38px; font-size: 12px; margin: 0;">
                                            {{ strtoupper(substr($intern->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: #1F2937;">{{ $intern->name }}</div>
                                            <div style="font-size: 12px; color: #6B7280;">{{ $intern->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $intern->course ?? 'N/A' }}</td>
                                <td style="min-width: 150px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div class="progress-container" style="flex: 1;">
                                            <div class="progress-bar {{ $progress < 30 ? 'red' : ($progress < 70 ? 'gold' : 'green') }}" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span style="font-size: 12px; font-weight: 600; color: var(--maroon);">{{ $progress }}%</span>
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
                                    <a href="{{ route('team-leader.interns.show', $intern) }}" class="btn btn-sm btn-secondary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($allInterns->count() > 10)
                    <div style="padding: 16px; text-align: center; border-top: 1px solid rgba(0,0,0,0.05);">
                        <a href="{{ route('team-leader.interns') }}" class="btn btn-primary btn-sm">
                            View All {{ $allInterns->count() }} Interns <i class="fas fa-arrow-right" style="margin-left: 6px;"></i>
                        </a>
                    </div>
                    @endif
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

        <!-- Tasks Page -->
        <div id="tasks" class="page-content">
            <!-- Task Stats -->
            <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));">
                <div class="stat-card gold">
                    <div class="stat-icon gold">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value">{{ $pendingTasks }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card maroon">
                    <div class="stat-icon maroon">
                        <i class="fas fa-spinner"></i>
                    </div>
                    <div class="stat-value">{{ $inProgressTasks }}</div>
                    <div class="stat-label">In Progress</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value">{{ $completedTasks }}</div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-card red">
                    <div class="stat-icon red">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-value">{{ $overdueTasks }}</div>
                    <div class="stat-label">Overdue</div>
                </div>
            </div>

            <div class="quick-actions">
                <a href="{{ route('team-leader.tasks.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create New Task
                </a>
                <a href="{{ route('team-leader.tasks') }}" class="btn btn-secondary">
                    <i class="fas fa-external-link-alt"></i>
                    Full Task Management
                </a>
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
                            @foreach($allTasks->take(15) as $task)
                            @php
                                $isOverdue = $task->due_date && $task->due_date < now() && $task->status !== 'Completed';
                            @endphp
                            <tr style="{{ $isOverdue ? 'background: rgba(220, 38, 38, 0.05);' : '' }}">
                                <td>
                                    <div style="font-weight: 600; color: #1F2937;">{{ Str::limit($task->title, 30) }}</div>
                                    @if($isOverdue)
                                    <span style="font-size: 11px; color: #DC2626; font-weight: 600;">
                                        <i class="fas fa-exclamation-circle"></i> Overdue
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 28px; height: 28px; border-radius: 8px; background: var(--maroon); color: white; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;">
                                            {{ strtoupper(substr($task->intern->name ?? 'N', 0, 1)) }}
                                        </div>
                                        <span style="font-size: 13px;">{{ $task->intern->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="
                                        background: {{ $task->priority === 'Urgent' ? '#DC2626' : ($task->priority === 'High' ? '#F59E0B' : ($task->priority === 'Medium' ? 'var(--gold)' : 'var(--forest-green)')) }};
                                        color: {{ $task->priority === 'Medium' ? 'var(--maroon)' : 'white' }};
                                    ">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td>
                                    @if($task->due_date)
                                    <span style="font-size: 13px; {{ $isOverdue ? 'color: #DC2626; font-weight: 600;' : '' }}">
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
                                        <a href="{{ route('team-leader.tasks.edit', $task) }}" class="btn btn-sm btn-secondary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($allTasks->count() > 15)
                    <div style="padding: 16px; text-align: center; border-top: 1px solid rgba(0,0,0,0.05);">
                        <a href="{{ route('team-leader.tasks') }}" class="btn btn-primary btn-sm">
                            View All {{ $allTasks->count() }} Tasks <i class="fas fa-arrow-right" style="margin-left: 6px;"></i>
                        </a>
                    </div>
                    @endif
                    @else
                    <div class="empty-state">
                        <i class="fas fa-tasks"></i>
                        <h4>No tasks yet</h4>
                        <p>Create your first task to get started</p>
                        <a href="{{ route('team-leader.tasks.create') }}" class="btn btn-primary" style="margin-top: 16px;">
                            <i class="fas fa-plus"></i> Create Task
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Attendance Page -->
        <div id="attendance" class="page-content">
            <!-- Attendance Stats -->
            <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
                <div class="stat-card green">
                    <div class="stat-icon green">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-value">{{ $presentToday }}</div>
                    <div class="stat-label">Present Today</div>
                </div>
                <div class="stat-card red">
                    <div class="stat-icon red">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div class="stat-value">{{ $absentToday }}</div>
                    <div class="stat-label">Absent Today</div>
                </div>
                <div class="stat-card gold">
                    <div class="stat-icon gold">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value">{{ $lateToday }}</div>
                    <div class="stat-label">Late Today</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-check"></i> Today's Attendance - {{ $today->format('F d, Y') }}</h3>
                    <a href="{{ route('team-leader.attendance') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-calendar-alt"></i> View All Dates
                    </a>
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
                                        <div class="list-item-avatar" style="width: 38px; height: 38px; font-size: 12px; margin: 0;">
                                            {{ strtoupper(substr($attendance->intern->name ?? 'N', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: #1F2937;">{{ $attendance->intern->name ?? 'N/A' }}</div>
                                            <div style="font-size: 12px; color: #6B7280;">{{ $attendance->intern->course ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($timeIn)
                                    <span style="{{ $isLate ? 'color: #F59E0B; font-weight: 600;' : '' }}">
                                        {{ $timeIn->format('h:i A') }}
                                        @if($isLate)
                                        <i class="fas fa-exclamation-circle" style="margin-left: 4px;"></i>
                                        @endif
                                    </span>
                                    @else
                                    <span style="color: #9CA3AF;">--:--</span>
                                    @endif
                                </td>
                                <td>
                                    @if($timeOut)
                                    {{ $timeOut->format('h:i A') }}
                                    @else
                                    <span style="color: var(--forest-green); font-weight: 500;">Still working</span>
                                    @endif
                                </td>
                                <td>
                                    @if($hoursWorked > 0)
                                    <span style="font-weight: 600; color: var(--maroon);">{{ number_format($hoursWorked, 1) }} hrs</span>
                                    @else
                                    <span style="color: #9CA3AF;">--</span>
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

        <!-- Reports Page -->
        <div id="reports" class="page-content">
            <!-- Report Stats -->
            <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
                <div class="stat-card maroon">
                    <div class="stat-icon maroon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-value">{{ $allReports->count() }}</div>
                    <div class="stat-label">Total Reports</div>
                </div>
                <div class="stat-card gold">
                    <div class="stat-icon gold">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="stat-value">{{ $allReports->where('status', 'draft')->count() }}</div>
                    <div class="stat-label">Drafts</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon green">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div class="stat-value">{{ $allReports->where('status', 'submitted')->count() }}</div>
                    <div class="stat-label">Submitted</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon green">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div class="stat-value">{{ $allReports->where('status', 'reviewed')->count() }}</div>
                    <div class="stat-label">Reviewed</div>
                </div>
            </div>

            <div class="quick-actions">
                <a href="{{ route('team-leader.reports.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    Create New Report
                </a>
                <a href="{{ route('team-leader.reports') }}" class="btn btn-secondary">
                    <i class="fas fa-external-link-alt"></i>
                    Full Reports View
                </a>
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
                            @foreach($allReports->take(10) as $report)
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: #1F2937;">{{ Str::limit($report->title, 35) }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst($report->report_type) }}</span>
                                </td>
                                <td>
                                    @if($report->period_start && $report->period_end)
                                    <span style="font-size: 13px;">
                                        {{ \Carbon\Carbon::parse($report->period_start)->format('M d') }} - {{ \Carbon\Carbon::parse($report->period_end)->format('M d, Y') }}
                                    </span>
                                    @else
                                    <span style="color: #9CA3AF;">--</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-size: 13px;">{{ $report->created_at->format('M d, Y') }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $report->status === 'reviewed' ? 'success' : ($report->status === 'submitted' ? 'info' : 'warning') }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <a href="{{ route('team-leader.reports.show', $report) }}" class="btn btn-sm btn-secondary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($report->status === 'draft')
                                        <a href="{{ route('team-leader.reports.edit', $report) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($allReports->count() > 10)
                    <div style="padding: 16px; text-align: center; border-top: 1px solid rgba(0,0,0,0.05);">
                        <a href="{{ route('team-leader.reports') }}" class="btn btn-primary btn-sm">
                            View All {{ $allReports->count() }} Reports <i class="fas fa-arrow-right" style="margin-left: 6px;"></i>
                        </a>
                    </div>
                    @endif
                    @else
                    <div class="empty-state">
                        <i class="fas fa-file-alt"></i>
                        <h4>No reports yet</h4>
                        <p>Create your first report to submit to admin</p>
                        <a href="{{ route('team-leader.reports.create') }}" class="btn btn-success" style="margin-top: 16px;">
                            <i class="fas fa-plus"></i> Create Report
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Logout handler - redirects to intern portal after logout
        function handleLogout() {
            document.getElementById('logoutForm').submit();
        }

        // Page navigation
        const menuItems = document.querySelectorAll('.menu-item[data-page]');
        const pageContents = document.querySelectorAll('.page-content');
        const pageTitle = document.getElementById('pageTitle');

        const pageTitles = {
            'dashboard': 'Dashboard',
            'interns': 'My Interns',
            'tasks': 'Task Management',
            'attendance': 'Team Attendance',
            'reports': 'My Reports'
        };

        menuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.getAttribute('data-page');

                // Update active menu
                menuItems.forEach(mi => mi.classList.remove('active'));
                this.classList.add('active');

                // Show page
                pageContents.forEach(pc => pc.classList.remove('active'));
                document.getElementById(page).classList.add('active');

                // Update title
                pageTitle.textContent = pageTitles[page] || 'Dashboard';
            });
        });
    </script>
</body>
</html>
