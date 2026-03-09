<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/upinit.jpg') }}">
    <title>Reset Password - UP Cebu Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
            min-height: 100vh;
        }
        .reset-card {
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
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="reset-card w-full max-w-md p-8">
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/UP logo.png') }}" alt="UP Logo" class="w-20 h-20 mx-auto mb-4 object-contain">
            <h1 class="text-2xl font-bold text-gray-800">Set New Password</h1>
            <p class="text-gray-500 mt-2">Team Leader Account</p>
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
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-6 flex items-start">
            <i class="fas fa-info-circle mr-2 mt-0.5"></i>
            <div>
                <p class="font-semibold">Create your new password</p>
                <p class="text-sm mt-1">Your password must be at least 8 characters long.</p>
            </div>
        </div>

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('team-leader.reset-password.submit') }}" id="resetForm">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <!-- Email Display -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-gray-400"></i>Email Address
                </label>
                <div class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-gray-600">
                    {{ $email }}
                </div>
            </div>

            <!-- New Password Field -->
            <div class="mb-5">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-gray-400"></i>New Password
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none text-gray-700 pr-12"
                        placeholder="Enter new password"
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
                        placeholder="Confirm new password"
                        required
                        minlength="8"
                    >
                    <button
                        type="button"
                        onclick="togglePassword('password_confirmation')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    >
                        <i class="fas fa-eye" id="password_confirmationIcon"></i>
                    </button>
                </div>
                <div class="password-requirements mt-2">
                    <div class="requirement" id="matchReq">
                        <i class="fas fa-circle text-xs"></i>
                        <span>Passwords match</span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="btn-primary w-full py-3 text-white font-semibold rounded-lg flex items-center justify-center"
            >
                <i class="fas fa-key mr-2"></i>
                Set New Password
            </button>
        </form>

        <!-- Back to Login -->
        <div class="text-center mt-6">
            <a href="{{ route('admin.login') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                <i class="fas fa-arrow-left mr-2"></i>Back to Login
            </a>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + 'Icon');

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

        // Real-time validation
        const passwordField = document.getElementById('password');
        const confirmField = document.getElementById('password_confirmation');
        const lengthReq = document.getElementById('lengthReq');
        const matchReq = document.getElementById('matchReq');

        function validateLength() {
            if (passwordField.value.length >= 8) {
                lengthReq.classList.add('valid');
                lengthReq.classList.remove('invalid');
                lengthReq.querySelector('i').classList.remove('fa-circle');
                lengthReq.querySelector('i').classList.add('fa-check-circle');
            } else {
                lengthReq.classList.remove('valid');
                lengthReq.classList.add('invalid');
                lengthReq.querySelector('i').classList.add('fa-circle');
                lengthReq.querySelector('i').classList.remove('fa-check-circle');
            }
        }

        function validateMatch() {
            if (confirmField.value && passwordField.value === confirmField.value) {
                matchReq.classList.add('valid');
                matchReq.classList.remove('invalid');
                matchReq.querySelector('i').classList.remove('fa-circle');
                matchReq.querySelector('i').classList.add('fa-check-circle');
            } else if (confirmField.value) {
                matchReq.classList.remove('valid');
                matchReq.classList.add('invalid');
                matchReq.querySelector('i').classList.add('fa-circle');
                matchReq.querySelector('i').classList.remove('fa-check-circle');
            }
        }

        passwordField.addEventListener('input', () => {
            validateLength();
            validateMatch();
        });

        confirmField.addEventListener('input', validateMatch);
    </script>
</body>
</html>
