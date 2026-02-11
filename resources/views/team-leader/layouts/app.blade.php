<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/upinit.jpg') }}">
    <title>Team Leader - Base Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F9FAFB; }

        .sidebar {
            background: linear-gradient(180deg, #1e3a5f 0%, #152a45 50%, #0f1f33 100%);
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }

        .sidebar-logo {
            padding: 28px 24px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar-logo img { height: 52px; margin: 0 auto 14px; }
        .sidebar-logo h3 { color: white; font-size: 15px; font-weight: 600; margin-bottom: 6px; }
        .sidebar-logo p { color: #3B82F6; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }

        .sidebar-menu { padding: 16px 0; }
        .menu-section { padding: 8px 20px; color: rgba(255, 255, 255, 0.4); font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-top: 8px; }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 13px 24px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin: 2px 12px;
            border-radius: 10px;
            transition: all 0.25s ease;
        }

        .menu-item i:first-child { width: 22px; margin-right: 14px; font-size: 17px; }
        .menu-item:hover { background: rgba(255, 255, 255, 0.08); color: white; }
        .menu-item.active {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .menu-badge { background: #EF4444; color: white; font-size: 10px; padding: 2px 8px; border-radius: 10px; margin-left: auto; font-weight: 600; }

        .logout-btn {
            margin: 16px 12px;
            padding: 12px 24px;
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            width: calc(100% - 24px);
        }

        .main-content { margin-left: 260px; min-height: 100vh; background: #F9FAFB; }

        .top-header {
            background: white;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #E5E7EB;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-title { font-size: 24px; font-weight: 700; color: #1F2937; }
        .header-subtitle { font-size: 14px; color: #6B7280; margin-top: 4px; }

        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .page-content { padding: 32px; }

        .card { background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); overflow: hidden; }
        .card-header { padding: 20px 24px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center; }
        .card-title { font-size: 18px; font-weight: 700; color: #1F2937; }
        .card-body { padding: 24px; }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { text-align: left; padding: 12px 16px; background: #F9FAFB; color: #6B7280; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #E5E7EB; }
        .data-table td { padding: 16px; border-bottom: 1px solid #E5E7EB; color: #1F2937; font-size: 14px; }
        .data-table tr:hover { background: #F9FAFB; }

        .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #ECFDF5; color: #059669; }
        .badge-warning { background: #FFFBEB; color: #D97706; }
        .badge-danger { background: #FEF2F2; color: #DC2626; }
        .badge-info { background: #EFF6FF; color: #2563EB; }

        .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s ease; border: none; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-primary { background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); color: white; }
        .btn-success { background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; }
        .btn-danger { background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%); color: white; }
        .btn-secondary { background: #F3F4F6; color: #4B5563; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }

        .filter-section { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; align-items: center; }
        .filter-section input, .filter-section select {
            padding: 10px 16px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            background: white;
        }

        .alert { padding: 16px 20px; border-radius: 12px; display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
        .alert-success { background: #ECFDF5; border-left: 4px solid #10B981; color: #065F46; }
        .alert-danger { background: #FEF2F2; border-left: 4px solid #EF4444; color: #991B1B; }

        .progress-container { width: 100%; height: 8px; background: #E5E7EB; border-radius: 4px; overflow: hidden; }
        .progress-bar { height: 100%; border-radius: 4px; transition: width 0.3s ease; }
        .progress-bar.green { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
        .progress-bar.yellow { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); }
        .progress-bar.red { background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%); }

        .pagination { display: flex; justify-content: center; gap: 8px; margin-top: 24px; }
        .pagination a, .pagination span {
            padding: 8px 16px;
            border-radius: 8px;
            background: white;
            border: 1px solid #E5E7EB;
            color: #6B7280;
            font-size: 14px;
            text-decoration: none;
        }
        .pagination a:hover { border-color: #3B82F6; color: #3B82F6; }
        .pagination .active span { background: #3B82F6; color: white; border-color: #3B82F6; }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/up-logo.png') }}" alt="UP Cebu Logo">
            <h3>UP Cebu Incubator</h3>
            <p>Team Leader Portal</p>
        </div>

        <div class="sidebar-menu">
            <div class="menu-section">Main</div>
            <a class="menu-item @if(request()->routeIs('team-leader.dashboard*')) active @endif" href="{{ route('team-leader.dashboard') }}">
                <i class="fas fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>

            <div class="menu-section">Management</div>
            <a class="menu-item @if(request()->routeIs('team-leader.interns*')) active @endif" href="{{ route('team-leader.interns') }}">
                <i class="fas fa-users"></i>
                <span>My Interns</span>
            </a>
            <a class="menu-item @if(request()->routeIs('team-leader.tasks*')) active @endif" href="{{ route('team-leader.tasks') }}">
                <i class="fas fa-tasks"></i>
                <span>Task Management</span>
            </a>
            <a class="menu-item @if(request()->routeIs('team-leader.attendance*')) active @endif" href="{{ route('team-leader.attendance') }}">
                <i class="fas fa-calendar-check"></i>
                <span>Attendance</span>
            </a>

            <div class="menu-section">Reports</div>
            <a class="menu-item @if(request()->routeIs('team-leader.reports*')) active @endif" href="{{ route('team-leader.reports') }}">
                <i class="fas fa-file-alt"></i>
                <span>My Reports</span>
            </a>

            <form action="{{ route('admin.logout') }}" method="POST" style="margin-top: auto;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-header">
            <div>
                <h1 class="header-title">@yield('title', 'Dashboard')</h1>
                <p class="header-subtitle">@yield('subtitle', Auth::user()->school->name ?? 'Team Leader Portal')</p>
            </div>
            <div class="user-info">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div>
                    <div style="font-weight: 600; color: #1F2937;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 12px; color: #6B7280;">Team Leader</div>
                </div>
            </div>
        </div>

        <div class="page-content">
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

            @yield('content')
        </div>
    </div>

    @yield('scripts')
</body>
</html>
