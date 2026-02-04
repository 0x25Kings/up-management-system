<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/upLogo.png') }}">
    <title>Startup Portal - UP Cebu Management System</title>
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
            background: linear-gradient(135deg, #FFBF00 0%, #FFA500 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 36px;
            color: #7B1D3A;
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

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%);
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
            box-shadow: 0 8px 20px rgba(123, 29, 58, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .access-code-section {
            background: linear-gradient(135deg, rgba(123, 29, 58, 0.05) 0%, rgba(255, 191, 0, 0.1) 100%);
            border: 2px solid rgba(123, 29, 58, 0.15);
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .access-code-section h4 {
            font-size: 16px;
            font-weight: 600;
            color: #7B1D3A;
            margin-bottom: 8px;
        }

        .access-code-section h4 i {
            color: #FFBF00;
            margin-right: 8px;
        }

        .access-code-section p {
            font-size: 13px;
            color: #6B7280;
            margin-bottom: 20px;
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

        .alert-success {
            background: #D1FAE5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        .help-text {
            text-align: center;
            font-size: 13px;
            color: #6B7280;
            padding: 16px;
            background: #F9FAFB;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .help-text i {
            color: #FFBF00;
            margin-right: 6px;
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
                        <i class="fas fa-building"></i>
                    </div>
                    <h1 style="color: #7B1D3A; font-size: 24px; font-weight: 700;">Startup Portal</h1>
                    <p style="color: #6B7280; margin-top: 8px;">University of the Philippines Cebu</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

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

                <!-- Enter Startup Code -->
                <div class="access-code-section">
                    <form action="{{ route('startup.verify-code') }}" method="POST" id="codeForm">
                        @csrf
                        <h4>
                            <i class="fas fa-key"></i>
                            Enter Your Startup Code
                        </h4>
                        <p>Enter the startup code provided by the administrator to access your portal.</p>

                        <div class="form-group">
                            <label for="startup_code">
                                <i class="fas fa-building" style="color: #7B1D3A; margin-right: 6px;"></i>Startup Code
                            </label>
                            <input 
                                type="text" 
                                id="startup_code" 
                                name="startup_code" 
                                class="@error('startup_code') error @enderror" 
                                placeholder="e.g., STU-2026-ABC123"
                                value="{{ old('startup_code') }}"
                                required
                                autocomplete="off"
                            >
                            @error('startup_code')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-submit" id="verifyBtn">
                            <i class="fas fa-arrow-right"></i>
                            <span>Continue</span>
                        </button>
                    </form>
                </div>

                <div class="help-text">
                    <i class="fas fa-info-circle"></i>
                    Don't have a code? Contact the administrator to register your startup.
                </div>

                <div style="text-align: center;">
                    <a href="{{ route('home') }}" style="color: #6B7280; text-decoration: none; font-size: 14px;">
                        <i class="fas fa-arrow-left" style="margin-right: 6px;"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto uppercase startup code
        document.getElementById('startup_code').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });

        // Form submit loading state
        document.getElementById('codeForm').addEventListener('submit', function() {
            const btn = document.getElementById('verifyBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
        });
    </script>
</body>
</html>
