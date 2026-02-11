<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/upinit.jpg') }}">
    <title>System Maintenance - {{ $system_name ?? 'UP Management System' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #7B1D3A;
            --accent-color: #FFBF00;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            min-height: 100vh;
        }

        .maintenance-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .header-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, #5a1428 100%);
        }

        .pulse-icon {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .gear-spin {
            animation: spin 4s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .bounce-dots span {
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .bounce-dots span:nth-child(1) {
            animation-delay: -0.32s;
        }

        .bounce-dots span:nth-child(2) {
            animation-delay: -0.16s;
        }

        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="maintenance-card max-w-lg w-full">
        <!-- Header -->
        <div class="header-gradient px-8 py-10 text-center">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/upLogo.png') }}" alt="UP Logo" class="h-20 w-auto">
            </div>
            <h1 class="text-white text-2xl font-bold mb-2">{{ $system_name ?? 'UP Management System' }}</h1>
            <p class="text-yellow-300 text-sm font-medium uppercase tracking-wider">University of the Philippines Cebu</p>
        </div>

        <!-- Content -->
        <div class="px-8 py-10 text-center">
            <!-- Animated Icon -->
            <div class="mb-6 flex justify-center">
                <div class="relative">
                    <i class="fas fa-cog text-6xl text-gray-300 gear-spin"></i>
                    <i class="fas fa-wrench text-3xl text-[#7B1D3A] absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
                </div>
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                <i class="fas fa-tools text-[#7B1D3A] mr-2"></i>
                System Under Maintenance
            </h2>

            <!-- Message -->
            <p class="text-gray-600 mb-6 leading-relaxed">
                {{ $message ?? 'We are currently performing scheduled maintenance to improve your experience.' }}
            </p>

            <!-- Status Indicator -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-center space-x-2">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full pulse-icon"></div>
                    <span class="text-yellow-700 font-medium">Maintenance in Progress</span>
                    <div class="bounce-dots flex space-x-1 ml-2">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                        <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                        <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <p class="text-gray-600 text-sm">
                    <i class="fas fa-info-circle text-[#7B1D3A] mr-2"></i>
                    We apologize for any inconvenience. The system will be back online shortly.
                </p>
            </div>

            @if($contact_email)
            <!-- Contact Info -->
            <div class="border-t pt-6">
                <p class="text-gray-500 text-sm mb-2">Need urgent assistance?</p>
                <a href="mailto:{{ $contact_email }}" class="inline-flex items-center text-[#7B1D3A] hover:text-[#5a1428] font-medium transition">
                    <i class="fas fa-envelope mr-2"></i>
                    {{ $contact_email }}
                </a>
            </div>
            @endif

            <!-- Refresh Button -->
            <div class="mt-6">
                <button onclick="location.reload()" class="inline-flex items-center px-6 py-3 bg-[#7B1D3A] text-white rounded-lg hover:bg-[#5a1428] transition font-medium">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Check Again
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-8 py-4 text-center border-t">
            <p class="text-gray-400 text-xs">
                &copy; {{ date('Y') }} {{ $system_name ?? 'UP Management System' }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
