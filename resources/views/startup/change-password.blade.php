@extends('startup.layout')

@section('title', 'Change Password')
@section('page-title', 'Change Password')

@section('content')
    <!-- Page Header -->
    <div class="page-header-card">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-key"></i>
            </div>
            <div>
                <h1>Change Password</h1>
                <p>Update your account password to keep it secure</p>
            </div>
        </div>
    </div>

    <!-- Password Change Form -->
    <div class="form-card" style="max-width: 600px;">
        <div class="form-card-header">
            <h2><i class="fas fa-lock"></i> Update Password</h2>
            <p>Enter your current password and choose a new one</p>
        </div>
        <div class="form-card-body">
            <form action="{{ route('startup.change-password.submit') }}" method="POST" id="changePasswordForm">
                @csrf

                <!-- Current Password -->
                <div class="form-group">
                    <label>Current Password <span>*</span></label>
                    <div style="position: relative;">
                        <input type="password" name="current_password" id="currentPassword" class="form-input {{ $errors->has('current_password') ? 'error' : '' }}" placeholder="Enter your current password" required style="padding-right: 48px;">
                        <button type="button" onclick="togglePassword('currentPassword', this)" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9CA3AF; padding: 4px;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="form-group">
                    <label>New Password <span>*</span></label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="newPassword" class="form-input {{ $errors->has('password') ? 'error' : '' }}" placeholder="Enter your new password" required minlength="8" style="padding-right: 48px;" oninput="checkPasswordStrength(this.value)">
                        <button type="button" onclick="togglePassword('newPassword', this)" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9CA3AF; padding: 4px;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <!-- Password Strength Indicator -->
                    <div id="passwordStrength" style="margin-top: 10px; display: none;">
                        <div style="display: flex; gap: 4px; margin-bottom: 6px;">
                            <div id="str1" style="flex: 1; height: 4px; border-radius: 2px; background: #E5E7EB; transition: background 0.3s;"></div>
                            <div id="str2" style="flex: 1; height: 4px; border-radius: 2px; background: #E5E7EB; transition: background 0.3s;"></div>
                            <div id="str3" style="flex: 1; height: 4px; border-radius: 2px; background: #E5E7EB; transition: background 0.3s;"></div>
                            <div id="str4" style="flex: 1; height: 4px; border-radius: 2px; background: #E5E7EB; transition: background 0.3s;"></div>
                        </div>
                        <span id="strengthText" style="font-size: 12px; font-weight: 600;"></span>
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="form-hint"><i class="fas fa-info-circle"></i> Must be at least 8 characters</div>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label>Confirm New Password <span>*</span></label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" id="confirmPassword" class="form-input" placeholder="Re-enter your new password" required style="padding-right: 48px;" oninput="checkPasswordMatch()">
                        <button type="button" onclick="togglePassword('confirmPassword', this)" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9CA3AF; padding: 4px;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div id="matchMessage" style="font-size: 12px; margin-top: 6px; display: none;"></div>
                </div>

                <!-- Security Tips -->
                <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
                    <h4 style="font-size: 13px; font-weight: 700; color: #1E40AF; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-shield-alt"></i> Password Tips
                    </h4>
                    <ul style="font-size: 12px; color: #3B82F6; list-style: none; padding: 0; display: flex; flex-direction: column; gap: 6px;">
                        <li><i class="fas fa-check-circle" style="margin-right: 6px; color: #60A5FA;"></i> Use at least 8 characters</li>
                        <li><i class="fas fa-check-circle" style="margin-right: 6px; color: #60A5FA;"></i> Include uppercase and lowercase letters</li>
                        <li><i class="fas fa-check-circle" style="margin-right: 6px; color: #60A5FA;"></i> Add numbers and special characters</li>
                        <li><i class="fas fa-check-circle" style="margin-right: 6px; color: #60A5FA;"></i> Avoid common words or personal info</li>
                    </ul>
                </div>

                <!-- Submit -->
                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-key"></i> Update Password
                    </button>
                    <a href="{{ route('startup.profile') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

function checkPasswordStrength(password) {
    const container = document.getElementById('passwordStrength');
    const bars = [document.getElementById('str1'), document.getElementById('str2'), document.getElementById('str3'), document.getElementById('str4')];
    const text = document.getElementById('strengthText');
    
    if (!password) { container.style.display = 'none'; return; }
    container.style.display = 'block';
    
    let score = 0;
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;
    if (/\d/.test(password)) score++;
    if (/[^a-zA-Z0-9]/.test(password)) score++;

    const colors = ['#EF4444', '#F59E0B', '#3B82F6', '#10B981'];
    const labels = ['Weak', 'Fair', 'Good', 'Strong'];
    
    bars.forEach((bar, i) => {
        bar.style.background = i < score ? colors[score - 1] : '#E5E7EB';
    });
    text.textContent = labels[score - 1] || '';
    text.style.color = colors[score - 1] || '#9CA3AF';
}

function checkPasswordMatch() {
    const newPass = document.getElementById('newPassword').value;
    const confirm = document.getElementById('confirmPassword').value;
    const msg = document.getElementById('matchMessage');
    
    if (!confirm) { msg.style.display = 'none'; return; }
    msg.style.display = 'block';
    
    if (newPass === confirm) {
        msg.innerHTML = '<i class="fas fa-check-circle" style="margin-right: 4px;"></i> Passwords match';
        msg.style.color = '#10B981';
    } else {
        msg.innerHTML = '<i class="fas fa-times-circle" style="margin-right: 4px;"></i> Passwords do not match';
        msg.style.color = '#EF4444';
    }
}
</script>
@endpush
