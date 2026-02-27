<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/upinit.jpg') }}">
    <title>Set Up Password - UP Cebu Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
            min-height: 100vh;
        }
        .setup-card {
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
        .password-requirements {
            font-size: 12px;
            color: #6B7280;
        }
        .requirement {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
        }
        .requirement.valid {
            color: #059669;
        }
        .requirement.invalid {
            color: #DC2626;
        }
        .reference-code {
            font-family: 'Monaco', 'Consolas', monospace;
            letter-spacing: 2px;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="setup-card w-full max-w-md p-8">
        <!-- Logo Section -->
        <div class="text-center mb-6">
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                <i class="fas fa-user-shield text-white text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Welcome, Team Leader!</h1>
            <p class="text-gray-500 mt-2">Set up your password to continue</p>
        </div>

        <!-- Team Leader Info -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-maroon-500 to-maroon-700 rounded-full flex items-center justify-center text-white font-bold text-lg" style="background: linear-gradient(135deg, #7B1D3A, #5a1428);">
                    {{ substr($teamLeader->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-semibold text-gray-800">{{ $teamLeader->name }}</div>
                    <div class="text-sm text-gray-500">{{ $teamLeader->email }}</div>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-200">
                <div class="text-xs text-gray-500 uppercase font-semibold mb-1">Reference Code</div>
                <div class="reference-code text-lg font-bold text-green-600">{{ $teamLeader->reference_code }}</div>
            </div>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Info Message -->
        @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-6 flex items-start">
                <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <!-- Setup Password Form -->
        <form method="POST" action="{{ route('team-leader.setup-password.submit') }}" id="setupForm">
            @csrf

            <!-- New Password Field -->
            <div class="mb-5">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-gray-400"></i>Create Password
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none text-gray-700 pr-12"
                        placeholder="Enter your password"
                        required
                        minlength="8"
                    >
                    <button
                        type="button"
                        onclick="togglePassword('password')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    >
                        <i class="fas fa-eye" id="passwordIcon"></i>
                    </button>
                </div>
                <div class="password-requirements mt-2">
                    <div class="requirement" id="lengthReq">
                        <i class="fas fa-circle text-xs"></i>
                        <span>At least 8 characters</span>
                    </div>
                </div>
            </div>

            <!-- Confirm Password Field -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-gray-400"></i>Confirm Password
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none text-gray-700 pr-12"
                        placeholder="Confirm your password"
                        required
                        minlength="8"
                    >
                    <button
                        type="button"
                        onclick="togglePassword('password_confirmation')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    >
                        <i class="fas fa-eye" id="confirmIcon"></i>
                    </button>
                </div>
                <div class="password-requirements mt-2">
                    <div class="requirement" id="matchReq">
                        <i class="fas fa-circle text-xs"></i>
                        <span>Passwords must match</span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                id="submitBtn"
                class="btn-primary w-full py-3 px-4 text-white font-semibold rounded-lg flex items-center justify-center gap-2"
            >
                <i class="fas fa-check-circle"></i>
                Set Password & Continue
            </button>
        </form>

        <!-- Back Link -->
        <div class="text-center mt-6">
            <a href="{{ route('intern.portal') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back to Intern Portal
            </a>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId === 'password' ? 'passwordIcon' : 'confirmIcon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const lengthReq = document.getElementById('lengthReq');
        const matchReq = document.getElementById('matchReq');

        function validatePassword() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            // Check length
            if (password.length >= 8) {
                lengthReq.classList.add('valid');
                lengthReq.classList.remove('invalid');
                lengthReq.querySelector('i').classList.remove('fa-circle');
                lengthReq.querySelector('i').classList.add('fa-check-circle');
            } else {
                lengthReq.classList.remove('valid');
                lengthReq.classList.add('invalid');
                lengthReq.querySelector('i').classList.remove('fa-check-circle');
                lengthReq.querySelector('i').classList.add('fa-circle');
            }

            // Check match
            if (confirm.length > 0 && password === confirm) {
                matchReq.classList.add('valid');
                matchReq.classList.remove('invalid');
                matchReq.querySelector('i').classList.remove('fa-circle');
                matchReq.querySelector('i').classList.add('fa-check-circle');
            } else if (confirm.length > 0) {
                matchReq.classList.remove('valid');
                matchReq.classList.add('invalid');
                matchReq.querySelector('i').classList.remove('fa-check-circle');
                matchReq.querySelector('i').classList.add('fa-times-circle');
            } else {
                matchReq.classList.remove('valid', 'invalid');
                matchReq.querySelector('i').classList.remove('fa-check-circle', 'fa-times-circle');
                matchReq.querySelector('i').classList.add('fa-circle');
            }
        }

        passwordInput.addEventListener('input', validatePassword);
        confirmInput.addEventListener('input', validatePassword);
    </script>
</body>
</html>
