<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UP Cebu Innovation & Technology Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F9FAFB; }

        /* Navigation */
        .navbar { background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); padding: 16px 0; position: fixed; width: 100%; top: 0; z-index: 1000; box-shadow: 0 2px 20px rgba(0, 0, 0, 0.2); }
        .nav-container { max-width: 1280px; margin: 0 auto; padding: 0 24px; display: flex; justify-content: space-between; align-items: center; }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo img { height: 45px; }
        .nav-logo-text { color: white; font-weight: 700; font-size: 18px; }
        .nav-logo-text span { color: #FFBF00; }
        .nav-links { display: flex; align-items: center; gap: 32px; }
        .nav-links a { color: white; text-decoration: none; font-weight: 500; font-size: 14px; transition: color 0.3s; }
        .nav-links a:hover { color: #FFBF00; }
        .admin-btn { background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%); color: #7B1D3A !important; padding: 10px 20px; border-radius: 8px; font-weight: 600; transition: transform 0.3s, box-shadow 0.3s; }
        .admin-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(255, 191, 0, 0.4); }
        .mobile-menu-btn { display: none; background: none; border: none; color: white; font-size: 24px; cursor: pointer; }

        /* Hero Section */
        .hero { background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 50%, #3d0d1a 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 120px 24px 80px; position: relative; overflow: hidden; }
        .hero::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); opacity: 0.5; }
        .hero-content { max-width: 800px; text-align: center; position: relative; z-index: 1; }
        .hero-badge { display: inline-block; background: rgba(255, 191, 0, 0.2); color: #FFBF00; padding: 8px 20px; border-radius: 30px; font-size: 14px; font-weight: 600; margin-bottom: 24px; border: 1px solid rgba(255, 191, 0, 0.3); }
        .hero h1 { color: white; font-size: 48px; font-weight: 800; margin-bottom: 20px; line-height: 1.2; }
        .hero h1 span { color: #FFBF00; }
        .hero p { color: rgba(255, 255, 255, 0.8); font-size: 18px; margin-bottom: 40px; line-height: 1.7; }
        .hero-buttons { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
        .btn-primary { background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%); color: #7B1D3A; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 16px; text-decoration: none; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(255, 191, 0, 0.4); }
        .btn-secondary { background: transparent; color: white; padding: 14px 32px; border-radius: 10px; font-weight: 600; font-size: 16px; text-decoration: none; border: 2px solid rgba(255, 255, 255, 0.3); transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.5); }

        /* Portal Cards Section */
        .portals-section { padding: 80px 24px; background: #F9FAFB; }
        .section-container { max-width: 1200px; margin: 0 auto; }
        .section-header { text-align: center; margin-bottom: 50px; }
        .section-header h2 { font-size: 36px; font-weight: 800; color: #1F2937; margin-bottom: 16px; }
        .section-header p { font-size: 18px; color: #6B7280; max-width: 600px; margin: 0 auto; }
        .portal-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
        .portal-card { background: white; border-radius: 20px; padding: 40px 30px; text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); transition: all 0.3s; border: 1px solid #E5E7EB; text-decoration: none; }
        .portal-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15); }
        .portal-icon { width: 80px; height: 80px; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; font-size: 36px; }
        .portal-card.intern .portal-icon { background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; }
        .portal-card.startup .portal-icon { background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; }
        .portal-card.agency .portal-icon { background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%); color: white; }
        .portal-card h3 { font-size: 24px; font-weight: 700; color: #1F2937; margin-bottom: 12px; }
        .portal-card p { color: #6B7280; font-size: 15px; line-height: 1.6; margin-bottom: 24px; }
        .portal-btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 14px; transition: all 0.3s; }
        .portal-card.intern .portal-btn { background: #7B1D3A; color: white; }
        .portal-card.startup .portal-btn { background: #10B981; color: white; }
        .portal-card.agency .portal-btn { background: #3B82F6; color: white; }
        .portal-card:hover .portal-btn { transform: translateX(5px); }

        /* Calendar Section */
        .calendar-section { padding: 80px 24px; background: white; }
        .calendar-container { display: grid; grid-template-columns: 1fr 400px; gap: 40px; background: #F9FAFB; border-radius: 24px; padding: 40px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); }
        .calendar-widget { background: white; border-radius: 16px; padding: 30px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        .calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .calendar-header h3 { font-size: 20px; font-weight: 700; color: #1F2937; }
        .calendar-nav { display: flex; gap: 8px; }
        .calendar-nav button { width: 36px; height: 36px; border-radius: 8px; border: 1px solid #E5E7EB; background: white; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; }
        .calendar-nav button:hover { background: #7B1D3A; color: white; border-color: #7B1D3A; }
        .calendar-weekdays { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; margin-bottom: 8px; }
        .calendar-weekdays span { text-align: center; font-weight: 600; font-size: 12px; color: #6B7280; padding: 8px; }
        .calendar-days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
        .calendar-day { aspect-ratio: 1; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.3s; position: relative; }
        .calendar-day:hover:not(.other-month):not(.disabled) { background: #7B1D3A; color: white; }
        .calendar-day.today { background: #FFBF00; color: #7B1D3A; font-weight: 700; }
        .calendar-day.has-booking { background: #E0F2FE; color: #0369A1; }
        .calendar-day.has-booking::after { content: ''; position: absolute; bottom: 4px; width: 6px; height: 6px; border-radius: 50%; background: #3B82F6; }
        .calendar-day.other-month { color: #D1D5DB; }
        .calendar-day.disabled { color: #D1D5DB; cursor: not-allowed; }
        .calendar-legend { display: flex; gap: 20px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #E5E7EB; }
        .legend-item { display: flex; align-items: center; gap: 8px; font-size: 12px; color: #6B7280; }
        .legend-dot { width: 12px; height: 12px; border-radius: 50%; }
        .legend-dot.today { background: #FFBF00; }
        .legend-dot.booked { background: #3B82F6; }

        /* Events Sidebar */
        .events-sidebar { background: white; border-radius: 16px; padding: 30px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        .events-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .events-header h3 { font-size: 18px; font-weight: 700; color: #1F2937; }
        .events-list { max-height: 400px; overflow-y: auto; }
        .event-item { padding: 16px; background: #F9FAFB; border-radius: 12px; margin-bottom: 12px; border-left: 4px solid #7B1D3A; }
        .event-date { font-size: 12px; color: #7B1D3A; font-weight: 600; margin-bottom: 4px; }
        .event-title { font-size: 14px; font-weight: 600; color: #1F2937; margin-bottom: 4px; }
        .event-time { font-size: 12px; color: #6B7280; display: flex; align-items: center; gap: 4px; }
        .no-events { text-align: center; padding: 40px 20px; color: #9CA3AF; }
        .no-events i { font-size: 40px; margin-bottom: 12px; }

        /* Footer */
        .footer { background: #1F2937; padding: 60px 24px 30px; color: white; }
        .footer-container { max-width: 1200px; margin: 0 auto; }
        .footer-content { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .footer-brand h3 { font-size: 20px; font-weight: 700; margin-bottom: 16px; }
        .footer-brand h3 span { color: #FFBF00; }
        .footer-brand p { color: #9CA3AF; font-size: 14px; line-height: 1.7; }
        .footer-links h4 { font-size: 14px; font-weight: 600; margin-bottom: 16px; color: #FFBF00; }
        .footer-links a { display: block; color: #9CA3AF; text-decoration: none; font-size: 14px; margin-bottom: 10px; transition: color 0.3s; }
        .footer-links a:hover { color: #FFBF00; }
        .footer-bottom { text-align: center; padding-top: 30px; border-top: 1px solid #374151; color: #6B7280; font-size: 14px; }

        /* Booking Modal */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.6); display: none; justify-content: center; align-items: center; z-index: 2000; padding: 20px; }
        .modal-overlay.active { display: flex; }
        .modal-content { background: white; border-radius: 20px; width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto; animation: modalSlide 0.3s ease; }
        @keyframes modalSlide { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header { padding: 24px 30px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center; }
        .modal-header h3 { font-size: 20px; font-weight: 700; color: #1F2937; }
        .modal-close { width: 36px; height: 36px; border-radius: 8px; border: none; background: #F3F4F6; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s; }
        .modal-close:hover { background: #FEE2E2; color: #991B1B; }
        .modal-body { padding: 30px; }
        .selected-date { background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; }
        .selected-date i { font-size: 24px; color: #FFBF00; }
        .selected-date span { font-weight: 600; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px; }
        .form-label .required { color: #DC2626; }
        .form-input { width: 100%; padding: 12px 16px; border: 1px solid #D1D5DB; border-radius: 10px; font-size: 14px; transition: all 0.3s; }
        .form-input:focus { outline: none; border-color: #7B1D3A; box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-textarea { min-height: 100px; resize: vertical; }
        .modal-footer { padding: 20px 30px; border-top: 1px solid #E5E7EB; display: flex; gap: 12px; justify-content: flex-end; }
        .btn-cancel { padding: 12px 24px; border-radius: 10px; border: 1px solid #D1D5DB; background: white; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s; }
        .btn-cancel:hover { background: #F3F4F6; }
        .btn-submit { padding: 12px 24px; border-radius: 10px; border: none; background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(123, 29, 58, 0.3); }

        /* Responsive */
        @media (max-width: 1024px) { .calendar-container { grid-template-columns: 1fr; } .portal-cards { grid-template-columns: 1fr; } .footer-content { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 768px) { .nav-links { display: none; } .mobile-menu-btn { display: block; } .hero h1 { font-size: 32px; } .hero p { font-size: 16px; } .section-header h2 { font-size: 28px; } .footer-content { grid-template-columns: 1fr; } .form-row { grid-template-columns: 1fr; } }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 100px;
            right: 24px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 400px;
        }
        .toast {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 20px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(0, 0, 0, 0.05);
            animation: toastSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            transform-origin: right center;
        }
        .toast.hiding {
            animation: toastSlideOut 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes toastSlideIn {
            from { opacity: 0; transform: translateX(100%) scale(0.8); }
            to { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes toastSlideOut {
            from { opacity: 1; transform: translateX(0) scale(1); }
            to { opacity: 0; transform: translateX(100%) scale(0.8); }
        }
        .toast-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 12px;
        }
        .toast.success .toast-icon { background: #DEF7EC; color: #03543F; }
        .toast.error .toast-icon { background: #FDE8E8; color: #9B1C1C; }
        .toast.warning .toast-icon { background: #FDF6B2; color: #8E4B10; }
        .toast.info .toast-icon { background: #E1EFFE; color: #1E429F; }
        .toast-content { flex: 1; min-width: 0; }
        .toast-title {
            font-weight: 600;
            font-size: 14px;
            color: #1F2937;
            margin-bottom: 2px;
        }
        .toast-message {
            font-size: 13px;
            color: #6B7280;
            line-height: 1.4;
        }
        .toast-close {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            border: none;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9CA3AF;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .toast-close:hover {
            background: #F3F4F6;
            color: #4B5563;
        }
        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 0 12px 12px;
            animation: toastProgress linear forwards;
        }
        .toast.success .toast-progress { background: #10B981; }
        .toast.error .toast-progress { background: #EF4444; }
        .toast.warning .toast-progress { background: #F59E0B; }
        .toast.info .toast-progress { background: #3B82F6; }
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
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10001;
            backdrop-filter: blur(4px);
        }
        .confirm-modal-overlay.active {
            display: flex;
        }
        .confirm-modal {
            background: white;
            border-radius: 16px;
            padding: 24px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            animation: confirmModalIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        }
        @keyframes confirmModalIn {
            from { opacity: 0; transform: scale(0.9) translateY(-20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
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
        .confirm-modal-icon.danger { background: #FEE2E2; color: #DC2626; }
        .confirm-modal-icon.warning { background: #FEF3C7; color: #D97706; }
        .confirm-modal-icon.info { background: #DBEAFE; color: #2563EB; }
        .confirm-modal-title {
            font-size: 18px;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 8px;
        }
        .confirm-modal-message {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 24px;
            line-height: 1.5;
        }
        .confirm-modal-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        .confirm-modal-btn {
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }
        .confirm-modal-btn.cancel {
            background: #F3F4F6;
            color: #4B5563;
        }
        .confirm-modal-btn.cancel:hover {
            background: #E5E7EB;
        }
        .confirm-modal-btn.confirm {
            background: #DC2626;
            color: white;
        }
        .confirm-modal-btn.confirm:hover {
            background: #B91C1C;
        }
        .confirm-modal-btn.confirm.primary {
            background: #7B1D3A;
        }
        .confirm-modal-btn.confirm.primary:hover {
            background: #5a1428;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="nav-logo">
                <img src="{{ asset('images/UP logo.png') }}" alt="UP Cebu Logo">
                <div class="nav-logo-text">UP Cebu <span>Innovation Hub</span></div>
            </a>
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#portals">Portals</a>
                <a href="#calendar">Calendar</a>
                <a href="#contact">Contact</a>
            </div>
            <button class="mobile-menu-btn"><i class="fas fa-bars"></i></button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <div class="hero-badge"><i class="fas fa-rocket"></i> Welcome to UP Cebu</div>
            <h1>Innovation & <span>Technology</span> Hub</h1>
            <p>Empowering research, nurturing startups, and shaping future innovators. Your gateway to academic excellence and technological advancement in the Visayas region.</p>
            <div class="hero-buttons">
                <a href="#portals" class="btn-primary"><i class="fas fa-th-large"></i> Explore Portals</a>
                <a href="#calendar" class="btn-secondary"><i class="fas fa-calendar-alt"></i> Book Appointment</a>
            </div>
        </div>
    </section>

    <!-- Portal Cards Section -->
    <section class="portals-section" id="portals">
        <div class="section-container">
            <div class="section-header">
                <h2>Access Our Portals</h2>
                <p>Choose your portal to access specialized services and resources tailored for your needs</p>
            </div>
            <div class="portal-cards">
                <a href="{{ route('intern.portal') }}" class="portal-card intern">
                    <div class="portal-icon"><i class="fas fa-user-graduate"></i></div>
                    <h3>Intern Portal</h3>
                    <p>Track attendance, view assigned tasks, submit reports, and manage your internship journey with ease.</p>
                    <span class="portal-btn">Access Portal <i class="fas fa-arrow-right"></i></span>
                </a>
                <a href="{{ route('startup.portal') }}" class="portal-card startup">
                    <div class="portal-icon"><i class="fas fa-rocket"></i></div>
                    <h3>Startup Portal</h3>
                    <p>Join our incubation and acceleration programs. Access resources, mentorship, and funding opportunities.</p>
                    <span class="portal-btn">Access Portal <i class="fas fa-arrow-right"></i></span>
                </a>
                <a href="#calendar" class="portal-card agency" onclick="scrollToCalendar(event)">
                    <div class="portal-icon"><i class="fas fa-building"></i></div>
                    <h3>Agency Booking</h3>
                    <p>Book appointments, schedule facility visits, and coordinate events with our administration.</p>
                    <span class="portal-btn">Book Now <i class="fas fa-arrow-down"></i></span>
                </a>
            </div>
        </div>
    </section>

    <!-- Calendar Section -->
    <section class="calendar-section" id="calendar">
        <div class="section-container">
            <div class="section-header">
                <h2>Book an Appointment</h2>
                <p>Click on any available date to schedule your visit or event</p>
            </div>
            <div class="calendar-container">
                <div class="calendar-widget">
                    <div class="calendar-header">
                        <h3 id="currentMonth">January 2026</h3>
                        <div class="calendar-nav">
                            <button onclick="previousMonth()"><i class="fas fa-chevron-left"></i></button>
                            <button onclick="nextMonth()"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="calendar-weekdays">
                        <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                    </div>
                    <div class="calendar-days" id="calendarDays"></div>
                    <div class="calendar-legend">
                        <div class="legend-item"><div class="legend-dot today"></div><span>Today</span></div>
                        <div class="legend-item"><div class="legend-dot booked"></div><span>Has Events/Bookings</span></div>
                    </div>
                </div>
                <div class="events-sidebar">
                    <div class="events-header"><h3>Upcoming Events</h3></div>
                    <div class="events-list" id="eventsList">
                        <div class="no-events"><i class="fas fa-calendar-check"></i><p>No upcoming events</p></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h3>UP Cebu <span>Innovation Hub</span></h3>
                    <p>University of the Philippines Cebu is committed to fostering innovation, research excellence, and entrepreneurship in the Visayas region.</p>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <a href="#home">Home</a>
                    <a href="#portals">Portals</a>
                    <a href="#calendar">Calendar</a>
                </div>
                <div class="footer-links">
                    <h4>Portals</h4>
                    <a href="{{ route('intern.portal') }}">Intern Portal</a>
                    <a href="{{ route('startup.portal') }}">Startup Portal</a>
                    <a href="#calendar">Agency Booking</a>
                </div>
                <div class="footer-links">
                    <h4>Contact</h4>
                    <a href="mailto:info@upcebu.edu.ph"><i class="fas fa-envelope"></i> info@upcebu.edu.ph</a>
                    <a href="tel:+63321234567"><i class="fas fa-phone"></i> +63 32 123 4567</a>
                    <a href="#"><i class="fas fa-map-marker-alt"></i> Gorordo Ave, Cebu City</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 University of the Philippines Cebu. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Booking Modal -->
    <div class="modal-overlay" id="bookingModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-calendar-plus"></i> Book Appointment</h3>
                <button class="modal-close" onclick="closeBookingModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="selected-date">
                    <i class="fas fa-calendar-day"></i>
                    <span id="selectedDateDisplay">Select a date</span>
                </div>
                <form id="bookingForm">
                    <input type="hidden" id="bookingDate" name="booking_date">
                    <div class="form-group">
                        <label class="form-label">Agency / Organization Name <span class="required">*</span></label>
                        <input type="text" class="form-input" id="agencyName" name="agency_name" placeholder="e.g. Department of Science & Technology" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Purpose <span class="required">*</span></label>
                        <input type="text" class="form-input" id="eventName" name="event_name" placeholder="e.g. Facility Tour / Partnership Meeting / Tech Workshop" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contact Person <span class="required">*</span></label>
                        <input type="text" class="form-input" id="contactPerson" name="contact_person" placeholder="e.g. Juan Dela Cruz" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number <span class="required">*</span></label>
                            <input type="tel" class="form-input" id="phone" name="phone" placeholder="09XX XXX XXXX" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address <span class="required">*</span></label>
                            <input type="email" class="form-input" id="email" name="email" placeholder="email@domain.com" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Start Time <span class="required">*</span></label>
                            <select class="form-input" id="timeStart" name="time_start" required>
                                <option value="">Select time</option>
                                <option value="08:00">8:00 AM</option>
                                <option value="09:00">9:00 AM</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="12:00">12:00 PM</option>
                                <option value="13:00">1:00 PM</option>
                                <option value="14:00">2:00 PM</option>
                                <option value="15:00">3:00 PM</option>
                                <option value="16:00">4:00 PM</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">End Time <span class="required">*</span></label>
                            <select class="form-input" id="timeEnd" name="time_end" required>
                                <option value="">Select time</option>
                                <option value="09:00">9:00 AM</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="12:00">12:00 PM</option>
                                <option value="13:00">1:00 PM</option>
                                <option value="14:00">2:00 PM</option>
                                <option value="15:00">3:00 PM</option>
                                <option value="16:00">4:00 PM</option>
                                <option value="17:00">5:00 PM</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-input form-textarea" id="purpose" name="purpose" placeholder="Additional notes or special requirements..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Attach PDF (Optional)</label>
                        <input type="file" class="form-input" id="attachment" name="attachment" accept=".pdf" style="padding: 10px;">
                        <small style="color: #6B7280; font-size: 12px; margin-top: 4px; display: block;">Accepted format: PDF only (Max 5MB)</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeBookingModal()">Cancel</button>
                <button class="btn-submit" onclick="submitBooking()"><i class="fas fa-paper-plane"></i> Submit Booking</button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Confirmation Modal -->
    <div class="confirm-modal-overlay" id="confirmModalOverlay">
        <div class="confirm-modal">
            <div class="confirm-modal-icon danger" id="confirmModalIcon">
                <i class="fas fa-exclamation-triangle" id="confirmModalIconInner"></i>
            </div>
            <h3 class="confirm-modal-title" id="confirmModalTitle">Are you sure?</h3>
            <p class="confirm-modal-message" id="confirmModalMessage">This action cannot be undone.</p>
            <div class="confirm-modal-buttons">
                <button class="confirm-modal-btn cancel" onclick="closeConfirmModal(false)">Cancel</button>
                <button class="confirm-modal-btn confirm" id="confirmModalBtn" onclick="closeConfirmModal(true)">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        // Toast Notification System
        function showToast(type, title, message, duration = 4000) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;

            const icons = {
                success: 'fa-check',
                error: 'fa-times',
                warning: 'fa-exclamation',
                info: 'fa-info'
            };

            toast.innerHTML = `
                <div class="toast-icon"><i class="fas ${icons[type]}"></i></div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="dismissToast(this.parentElement)"><i class="fas fa-times"></i></button>
                <div class="toast-progress" style="animation-duration: ${duration}ms"></div>
            `;

            container.appendChild(toast);

            setTimeout(() => dismissToast(toast), duration);
        }

        function dismissToast(toast) {
            if (toast && !toast.classList.contains('hiding')) {
                toast.classList.add('hiding');
                setTimeout(() => toast.remove(), 300);
            }
        }

        // Confirmation Modal System
        let confirmResolve = null;

        function showConfirmModal(options) {
            return new Promise((resolve) => {
                confirmResolve = resolve;

                const overlay = document.getElementById('confirmModalOverlay');
                const icon = document.getElementById('confirmModalIcon');
                const iconInner = document.getElementById('confirmModalIconInner');
                const title = document.getElementById('confirmModalTitle');
                const message = document.getElementById('confirmModalMessage');
                const confirmBtn = document.getElementById('confirmModalBtn');

                // Set content
                title.textContent = options.title || 'Are you sure?';
                message.textContent = options.message || 'This action cannot be undone.';
                confirmBtn.textContent = options.confirmText || 'Confirm';

                // Set icon type
                icon.className = 'confirm-modal-icon ' + (options.type || 'danger');
                const iconMap = { danger: 'fa-exclamation-triangle', warning: 'fa-exclamation-circle', info: 'fa-question-circle' };
                iconInner.className = 'fas ' + (iconMap[options.type] || iconMap.danger);

                // Set button style
                confirmBtn.className = 'confirm-modal-btn confirm' + (options.type === 'info' ? ' primary' : '');

                overlay.classList.add('active');
            });
        }

        function closeConfirmModal(result) {
            document.getElementById('confirmModalOverlay').classList.remove('active');
            if (confirmResolve) {
                confirmResolve(result);
                confirmResolve = null;
            }
        }

        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        let selectedDate = null;
        let bookings = [];
        let events = [];
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        document.addEventListener('DOMContentLoaded', function() {
            loadBookings();
            renderCalendar();
        });

        async function loadBookings() {
            try {
                // Load bookings
                const bookingsResponse = await fetch('/bookings');
                bookings = await bookingsResponse.json();

                // Load events
                const eventsResponse = await fetch('/intern/events');
                if (eventsResponse.ok) {
                    const eventsData = await eventsResponse.json();
                    events = eventsData.events || [];
                } else {
                    events = [];
                }

                // Load blocked dates (public route)
                const blockedResponse = await fetch('/blocked-dates');
                if (blockedResponse.ok) {
                    blockedDates = await blockedResponse.json();
                } else {
                    blockedDates = [];
                }

                renderCalendar();
                renderUpcomingEvents();
            } catch (error) {
                console.error('Error loading bookings:', error);
            }
        }

        // Blocked dates array
        let blockedDates = [];

        function renderCalendar() {
            const calendarDays = document.getElementById('calendarDays');
            const monthDisplay = document.getElementById('currentMonth');
            monthDisplay.textContent = `${monthNames[currentMonth]} ${currentYear}`;

            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const daysInPrevMonth = new Date(currentYear, currentMonth, 0).getDate();
            const today = new Date();
            const todayString = today.toISOString().split('T')[0];

            let html = '';
            for (let i = firstDay - 1; i >= 0; i--) { html += `<div class="calendar-day other-month">${daysInPrevMonth - i}</div>`; }
            for (let day = 1; day <= daysInMonth; day++) {
                const dateString = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isToday = dateString === todayString;
                const isPast = new Date(dateString) < new Date(todayString);
                const hasBooking = bookings.some(b => b.date === dateString);
                const hasEvent = events.some(e => {
                    const eventStart = new Date(e.start_date).toISOString().split('T')[0];
                    const eventEnd = new Date(e.end_date).toISOString().split('T')[0];
                    return dateString >= eventStart && dateString <= eventEnd;
                });
                const blockedInfo = blockedDates.find(b => b.date === dateString);
                const isBlocked = !!blockedInfo;

                let classes = 'calendar-day';
                if (isToday) classes += ' today';
                if (hasBooking || hasEvent) classes += ' has-booking';
                if (isPast && !isToday) classes += ' disabled';
                if (isBlocked) classes += ' blocked';

                // Don't allow clicking on blocked or past dates
                const isClickable = !isPast && !isBlocked;
                const onclick = isClickable ? `onclick="selectDate('${dateString}')"` : isBlocked ? `onclick="showBlockedMessage('${blockedInfo.reason_label}')"` : '';

                // Style for blocked dates
                let style = isBlocked ? `style="background: ${blockedInfo.reason_color}15; color: ${blockedInfo.reason_color}; border: 1px solid ${blockedInfo.reason_color}40;"` : '';

                html += `<div class="${classes}" ${style} ${onclick}>${day}${isBlocked ? '<i class="fas fa-ban" style="font-size: 8px; margin-left: 2px; opacity: 0.7;"></i>' : ''}</div>`;
            }
            const remainingDays = 42 - (firstDay + daysInMonth);
            for (let i = 1; i <= remainingDays; i++) { html += `<div class="calendar-day other-month">${i}</div>`; }
            calendarDays.innerHTML = html;
        }

        function showBlockedMessage(reason) {
            showToast('warning', 'Date Unavailable', `This date is not available for booking. Reason: ${reason}`);
        }

        function renderUpcomingEvents() {
            const eventsList = document.getElementById('eventsList');
            const today = new Date().toISOString().split('T')[0];
            const upcomingBookings = bookings.filter(b => b.date >= today).slice(0, 5);

            // Get upcoming events
            const upcomingEvents = events.filter(e => {
                const eventStart = new Date(e.start_date).toISOString().split('T')[0];
                return eventStart >= today;
            }).slice(0, 5);

            // Combine and sort by date
            const combined = [
                ...upcomingBookings.map(b => ({ type: 'booking', date: b.date, title: `${b.agency} - ${b.event}`, time: b.time })),
                ...upcomingEvents.map(e => ({
                    type: 'event',
                    date: new Date(e.start_date).toISOString().split('T')[0],
                    title: e.title,
                    time: e.all_day ? 'All Day' : new Date(e.start_date).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' }),
                    color: e.color
                }))
            ].sort((a, b) => new Date(a.date) - new Date(b.date)).slice(0, 5);

            if (combined.length === 0) {
                eventsList.innerHTML = `<div class="no-events"><i class="fas fa-calendar-check"></i><p>No upcoming events</p></div>`;
                return;
            }

            eventsList.innerHTML = combined.map(item => `
                <div class="event-item" ${item.type === 'event' ? `style="border-left-color: ${item.color};"` : ''}>
                    <div class="event-date">${formatDate(item.date)}</div>
                    <div class="event-title">${item.title}</div>
                    <div class="event-time"><i class="fas fa-clock"></i> ${item.time}</div>
                </div>
            `).join('');
        }

        function formatDate(dateString) {
            const date = new Date(dateString + 'T00:00:00');
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        }

        function previousMonth() { currentMonth--; if (currentMonth < 0) { currentMonth = 11; currentYear--; } renderCalendar(); }
        function nextMonth() { currentMonth++; if (currentMonth > 11) { currentMonth = 0; currentYear++; } renderCalendar(); }

        function selectDate(dateString) {
            selectedDate = dateString;
            document.getElementById('bookingDate').value = dateString;
            document.getElementById('selectedDateDisplay').textContent = formatDate(dateString);
            document.getElementById('bookingModal').classList.add('active');
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').classList.remove('active');
            document.getElementById('bookingForm').reset();
        }

        async function submitBooking() {
            const form = document.getElementById('bookingForm');
            if (!form.checkValidity()) { form.reportValidity(); return; }

            // Check file size if attachment is provided
            const attachmentInput = document.getElementById('attachment');
            if (attachmentInput.files.length > 0) {
                const file = attachmentInput.files[0];
                if (file.size > 5 * 1024 * 1024) {
                    showToast('error', 'File Too Large', 'File size must not exceed 5MB.');
                    return;
                }
                if (file.type !== 'application/pdf') {
                    showToast('error', 'Invalid File Type', 'Only PDF files are allowed.');
                    return;
                }
            }

            // Use FormData for file upload
            const formData = new FormData();
            formData.append('agency_name', document.getElementById('agencyName').value);
            formData.append('event_name', document.getElementById('eventName').value);
            formData.append('contact_person', document.getElementById('contactPerson').value);
            formData.append('phone', document.getElementById('phone').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('booking_date', document.getElementById('bookingDate').value);
            formData.append('time_start', document.getElementById('timeStart').value);
            formData.append('time_end', document.getElementById('timeEnd').value);
            formData.append('purpose', document.getElementById('purpose').value);

            if (attachmentInput.files.length > 0) {
                formData.append('attachment', attachmentInput.files[0]);
            }

            // Check if end time is after start time
            if (document.getElementById('timeStart').value >= document.getElementById('timeEnd').value) {
                showToast('error', 'Invalid Time', 'End time must be after start time.');
                return;
            }

            try {
                const response = await fetch('/bookings', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    showToast('success', 'Booking Submitted!', data.message || 'Your booking request has been submitted successfully.');
                    closeBookingModal();
                    loadBookings();
                } else {
                    // Show specific error messages for conflicts or blocked dates
                    showToast('error', 'Booking Failed', data.message || 'Failed to submit booking. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Error', 'Error submitting booking. Please try again.');
            }
        }

        function scrollToCalendar(event) { event.preventDefault(); document.getElementById('calendar').scrollIntoView({ behavior: 'smooth' }); }
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) { e.preventDefault(); const target = document.querySelector(this.getAttribute('href')); if (target) { target.scrollIntoView({ behavior: 'smooth' }); } });
        });
    </script>
</body>
</html>
