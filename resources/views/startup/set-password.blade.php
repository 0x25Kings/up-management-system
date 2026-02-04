<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/upLogo.png') }}">
    <title>Create Password - Startup Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #F3F4F6;
            min-height: 100vh;
        }

        .login-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            max-width: 500px;
            width: 100%;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .form-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .form-header .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 36px;
            color: white;
        }

        .company-badge {
            background: linear-gradient(135deg, rgba(123, 29, 58, 0.1) 0%, rgba(255, 191, 0, 0.1) 100%);
            border: 2px solid rgba(123, 29, 58, 0.2);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            text-align: center;
        }

        .company-badge .code {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: 700;
            color: #7B1D3A;
            margin-bottom: 4px;
        }

        .company-badge .name {
            font-size: 14px;
            color: #6B7280;
        }

        .welcome-banner {
            background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
            border: 2px solid #34D399;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .welcome-banner i {
            font-size: 24px;
            color: #059669;
        }

        .welcome-banner .content h4 {
            font-size: 15px;
            font-weight: 600;
            color: #065F46;
            margin-bottom: 2px;
        }

        .welcome-banner .content p {
            font-size: 13px;
            color: #047857;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #E5E7EB;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #7B1D3A;
            box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1);
        }

        .form-group input.error {
            border-color: #EF4444;
        }

        .input-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            cursor: pointer;
            padding: 4px;
        }

        .password-toggle:hover {
            color: #6B7280;
        }

        .password-requirements {
            background: #F9FAFB;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 20px;
        }

        .password-requirements h5 {
            font-size: 12px;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .password-requirements li {
            font-size: 13px;
            color: #6B7280;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .password-requirements li i {
            font-size: 12px;
            color: #D1D5DB;
        }

        .password-requirements li.valid i {
            color: #10B981;
        }

        .password-requirements li.valid {
            color: #059669;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .error-message {
            color: #EF4444;
            font-size: 13px;
            margin-top: 6px;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #6B7280;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .back-link a:hover {
            color: #7B1D3A;
        }

        @media (max-width: 768px) {
            .login-card {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-container">
            <div class="login-card">
                <div class="form-header">
                    <div class="logo">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h1 style="color: #7B1D3A; font-size: 24px; font-weight: 700;">Create Your Password</h1>
                    <p style="color: #6B7280; margin-top: 8px;">Set up a secure password for your account</p>
                </div>

                <!-- Company Info -->
                <div class="company-badge">
                    <div class="code">{{ $startup->startup_code }}</div>
                    <div class="name">{{ $startup->company_name }}</div>
                </div>

                <!-- Welcome Banner -->
                <div class="welcome-banner">
                    <i class="fas fa-sparkles"></i>
                    <div class="content">
                        <h4>Welcome to the Startup Portal!</h4>
                        <p>This is your first time logging in. Please create a password to secure your account.</p>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('startup.set-password.submit') }}" method="POST" id="setPasswordForm">
                    @csrf

                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock" style="color: #7B1D3A; margin-right: 6px;"></i>New Password
                        </label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="@error('password') error @enderror" 
                                placeholder="Create a strong password"
                                required
                                autofocus
                            >
                            <span class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                                <i class="fas fa-eye" id="toggleIcon1"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="password-requirements">
                        <h5>Password Requirements</h5>
                        <ul>
                            <li id="req-length"><i class="fas fa-circle"></i> At least 8 characters</li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">
                            <i class="fas fa-lock" style="color: #7B1D3A; margin-right: 6px;"></i>Confirm Password
                        </label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="Confirm your password"
                                required
                            >
                            <span class="password-toggle" onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                                <i class="fas fa-eye" id="toggleIcon2"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="fas fa-check-circle"></i>
                        <span>Create Password & Continue</span>
                    </button>
                </form>

                <div class="back-link">
                    <a href="{{ route('startup.login') }}">
                        <i class="fas fa-arrow-left" style="margin-right: 6px;"></i>
                        Use a different code
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Password validation
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const reqLength = document.getElementById('req-length');
            
            if (password.length >= 8) {
                reqLength.classList.add('valid');
                reqLength.querySelector('i').classList.remove('fa-circle');
                reqLength.querySelector('i').classList.add('fa-check-circle');
            } else {
                reqLength.classList.remove('valid');
                reqLength.querySelector('i').classList.remove('fa-check-circle');
                reqLength.querySelector('i').classList.add('fa-circle');
            }
        });

        // Form submit loading state
        document.getElementById('setPasswordForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating password...';
        });
    </script>
</body>
</html>
