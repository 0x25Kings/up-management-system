<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - UP Cebu Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
            min-height: 100vh;
        }
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .btn-primary {
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(123, 29, 58, 0.4);
        }
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: #7B1D3A;
            box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1);
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="login-card w-full max-w-md p-8">
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-linear-to-br from-amber-400 to-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-user-shield text-3xl text-white"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Admin Login</h1>
            <p class="text-gray-500 mt-2">UP Cebu Management System</p>
        </div>

        <!-- Error Messages -->
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <!-- Email Field -->
            <div class="mb-5">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-gray-400"></i>Email Address
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none text-gray-700"
                    placeholder="admin@example.com"
                    required
                    autofocus
                >
            </div>

            <!-- Password Field -->
            <div class="mb-5">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-gray-400"></i>Password
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none text-gray-700 pr-12"
                        placeholder="••••••••"
                        required
                    >
                    <button
                        type="button"
                        onclick="togglePassword()"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    >
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-amber-500 border-gray-300 rounded focus:ring-amber-500">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="btn-primary w-full py-3 text-white font-semibold rounded-lg flex items-center justify-center"
            >
                <i class="fas fa-sign-in-alt mr-2"></i>
                Sign In to Dashboard
            </button>
        </form>

        <!-- Back to Home -->
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">
                <i class="fas fa-arrow-left mr-1"></i>
                Back to Home
            </a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
