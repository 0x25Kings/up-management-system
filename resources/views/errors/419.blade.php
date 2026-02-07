<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/upLogo.png') }}">
    <title>Session Expired - UP Cebu Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 text-center">
        <!-- Icon -->
        <div class="w-20 h-20 mx-auto mb-6 bg-amber-100 rounded-full flex items-center justify-center">
            <i class="fas fa-clock text-4xl text-amber-500"></i>
        </div>
        
        <!-- Title -->
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Session Expired</h1>
        
        <!-- Message -->
        <p class="text-gray-600 mb-6">
            Your session has expired due to inactivity. Please refresh the page and try again.
        </p>
        
        <!-- Action Buttons -->
        <div class="space-y-3">
            @php
                $previousUrl = url()->previous();
                $currentUrl = request()->url();
                
                // Determine appropriate redirect based on URL
                if (str_contains($previousUrl, '/startup') || str_contains($currentUrl, '/startup')) {
                    $redirectUrl = route('startup.login');
                    $buttonText = 'Back to Startup Login';
                } elseif (str_contains($previousUrl, '/admin') || str_contains($currentUrl, '/admin')) {
                    $redirectUrl = route('admin.login');
                    $buttonText = 'Back to Admin Login';
                } elseif (str_contains($previousUrl, '/intern') || str_contains($currentUrl, '/intern')) {
                    $redirectUrl = route('intern.portal');
                    $buttonText = 'Back to Intern Portal';
                } elseif (str_contains($previousUrl, '/team-leader') || str_contains($currentUrl, '/team-leader')) {
                    $redirectUrl = route('admin.login');
                    $buttonText = 'Back to Team Leader Login';
                } else {
                    $redirectUrl = route('home');
                    $buttonText = 'Back to Home';
                }
            @endphp
            
            <a href="{{ $redirectUrl }}" 
               class="block w-full py-3 px-4 bg-gradient-to-r from-[#7B1D3A] to-[#5a1428] text-white font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
                <i class="fas fa-arrow-left mr-2"></i>{{ $buttonText }}
            </a>
            
            <button onclick="window.location.reload()" 
                    class="block w-full py-3 px-4 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-all duration-300">
                <i class="fas fa-refresh mr-2"></i>Refresh Page
            </button>
        </div>
        
        <!-- Info Text -->
        <p class="text-sm text-gray-400 mt-6">
            <i class="fas fa-info-circle mr-1"></i>
            Sessions expire after {{ config('session.lifetime') }} minutes of inactivity.
        </p>
    </div>
    
    <script>
        // Auto-redirect after 10 seconds
        setTimeout(function() {
            window.location.href = '{{ $redirectUrl }}';
        }, 10000);
    </script>
</body>
</html>
