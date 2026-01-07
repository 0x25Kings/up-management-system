<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UP Cebu Management System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Palatino Linotype', Palatino, 'Book Antiqua', serif; }
        h1, h2, h3, h4, h5, h6 { font-family: Optima, 'Segoe UI', Arial, sans-serif; }
        
        .navbar { background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); }
        .hero-section {
            background: linear-gradient(rgba(18, 56, 45, 0.7), rgba(18, 56, 45, 0.8)), 
                        url('/images/statue.webp') center 15%/cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-attachment: fixed;
        }
        .btn-primary {
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            color: #7B1D3A;
            font-weight: 600;
        }
        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(255, 191, 0, 0.3);
        }
        .system-card {
            transition: all 0.3s ease;
            border: 2px solid #E5E7EB;
        }
        .system-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: #FFBF00;
        }
        .system-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7B1D3A;
            font-size: 28px;
        }
        .nav-link { color: white; text-decoration: none; transition: color 0.3s ease; }
        .nav-link:hover { color: #FFBF00; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        .footer {
            background: linear-gradient(rgba(18, 56, 45, 0.95), rgba(18, 56, 45, 0.95)), 
                        url('/images/statue.webp') center 10%/cover;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen">
    <!-- Navigation Bar -->
    <nav class="navbar fixed top-0 left-0 right-0 h-20 flex items-center px-8 justify-between z-50 shadow-lg">
        <a href="/" class="flex items-center gap-3">
            <img src="/images/UP logo.png" alt="UP Logo" style="height: 56px; width: auto;">
            <div>
                <p class="text-white font-bold text-sm">University of the Philippines</p>
                <p class="text-yellow-300 text-xs">InIT</p>
            </div>
        </a>

        <div class="hidden md:flex items-center gap-8">
            <a href="/" class="nav-link">Home</a>
            <a href="/#about" class="nav-link">About</a>
            <a href="/#mission" class="nav-link">Mission & Vision</a>
            <a href="/#paintings" class="nav-link">Gallery</a>
            <a href="/#contact" class="nav-link">Contact</a>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('intern.portal') }}" class="px-4 py-2 rounded-lg transition text-white hover:text-yellow-300 text-sm">
                <i class="fas fa-user-graduate mr-1"></i> Intern
            </a>
            <a href="{{ route('startup.portal') }}" class="px-4 py-2 rounded-lg transition text-white hover:text-yellow-300 text-sm">
                <i class="fas fa-rocket mr-1"></i> Startup
            </a>
            <a href="{{ route('agency.portal') }}" class="px-4 py-2 rounded-lg transition text-white hover:text-yellow-300 text-sm">
                <i class="fas fa-building mr-1"></i> Agency
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer text-white py-16">
        <div class="max-w-6xl mx-auto px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12 pb-12 border-b border-white/10">
                <div>
                    <div class="flex items-center gap-4 mb-6">
                        <img src="/images/logo cm.png" alt="UP Logo" class="h-12">
                        <img src="/images/UP logo.png" alt="CM Logo" class="h-12">
                        <img src="/images/init no background.png" alt="InIT Logo" class="h-12">
                    </div>
                    <p class="text-sm opacity-90">
                        Streamlining internship management, research tracking, digital records, incubatee monitoring, and scheduling with modern technology.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm opacity-80">
                        <li><a href="/" class="hover:text-yellow-300">Home</a></li>
                        <li><a href="/#about" class="hover:text-yellow-300">About</a></li>
                        <li><a href="/#mission" class="hover:text-yellow-300">Mission & Vision</a></li>
                        <li><a href="/#contact" class="hover:text-yellow-300">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Portals</h4>
                    <ul class="space-y-2 text-sm opacity-80">
                        <li><a href="{{ route('intern.portal') }}" class="hover:text-yellow-300">Intern Portal</a></li>
                        <li><a href="{{ route('startup.portal') }}" class="hover:text-yellow-300">Startup Portal</a></li>
                        <li><a href="{{ route('agency.portal') }}" class="hover:text-yellow-300">Agency Portal</a></li>
                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-yellow-300">Admin Dashboard</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Contact Info</h4>
                    <ul class="space-y-2 text-sm opacity-80">
                        <li><i class="fas fa-envelope mr-2"></i> init@up.edu.ph</li>
                        <li><i class="fas fa-phone mr-2"></i> 09154601907</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Gorordo Avenue, Cebu City</li>
                    </ul>
                </div>
            </div>

            <div class="text-center text-sm opacity-80">
                <p>&copy; 2025 University of the Philippines Cebu - InIT Program. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
