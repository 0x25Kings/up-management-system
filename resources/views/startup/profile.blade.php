@extends('startup.layout')

@section('title', 'Company Profile')
@section('page-title', 'Company Profile')

@section('content')
    <!-- Page Header -->
    <div class="page-header-card">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <h1>Company Profile</h1>
                <p>View and manage your startup information</p>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="profile-container">
        <!-- Profile Header Card -->
        <div class="profile-header-card">
            <div class="profile-avatar-wrapper">
                <div class="profile-avatar" id="profileAvatar">
                    @if($startup->profile_photo)
                        <img src="{{ asset('storage/' . $startup->profile_photo) }}" alt="Profile Photo" class="avatar-img">
                    @else
                        @php
                            $words = explode(' ', $startup->company_name);
                            $initials = '';
                            foreach ($words as $word) {
                                if (!empty($word)) {
                                    $initials .= strtoupper(substr($word, 0, 1));
                                }
                                if (strlen($initials) >= 2) break;
                            }
                            echo $initials ?: strtoupper(substr($startup->company_name, 0, 2));
                        @endphp
                    @endif
                </div>
                <button type="button" class="avatar-upload-btn" onclick="document.getElementById('photoInput').click()" title="Change profile photo">
                    <i class="fas fa-camera"></i>
                </button>
                <form id="photoForm" action="{{ route('startup.profile.upload-photo') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                    @csrf
                    <input type="file" id="photoInput" name="profile_photo" accept="image/jpeg,image/png,image/webp" onchange="previewAndSubmitPhoto(this)">
                </form>
            </div>
            <div class="profile-header-info">
                <h2>{{ ucwords(strtolower($startup->company_name)) }}</h2>
                <p>{{ ucwords(strtolower($startup->contact_person)) }}</p>
                <div class="profile-badges">
                    <span class="badge badge-code">
                        <i class="fas fa-fingerprint"></i>
                        {{ $startup->startup_code }}
                    </span>
                    <span class="badge {{ $startup->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                        <i class="fas fa-circle"></i>
                        {{ ucfirst($startup->status) }}
                    </span>
                    @if($startup->room_number)
                    <span class="badge badge-room">
                        <i class="fas fa-door-open"></i>
                        Room {{ $startup->room_number }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="profile-stats">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">Member Since</span>
                    <span class="stat-value">{{ $startup->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                    <i class="fas fa-file-signature"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">MOA Status</span>
                    <span class="stat-value">{{ ucfirst($startup->moa_status ?: 'None') }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366F1, #4F46E5);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">MOA Expiry</span>
                    <span class="stat-value">{{ $startup->moa_expiry ? $startup->moa_expiry->format('M d, Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="profile-grid">
            <!-- Company Information Form -->
            <div class="form-card">
                <div class="form-card-header">
                    <h2><i class="fas fa-edit"></i> Edit Information</h2>
                    <p>Update your company details</p>
                </div>
                <div class="form-card-body">
                    <form action="{{ route('startup.profile.update') }}" method="POST" id="profileForm">
                        @csrf

                        <div class="form-group">
                            <label>Company Name</label>
                            <input type="text" class="form-input" value="{{ ucwords(strtolower($startup->company_name)) }}" disabled style="background: #F3F4F6; cursor: not-allowed;">
                            <div class="form-hint">
                                <i class="fas fa-lock"></i>
                                Contact administrator to change company name
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact_person">Contact Person <span>*</span></label>
                                <input type="text" id="contact_person" name="contact_person" class="form-input @error('contact_person') error @enderror" 
                                       value="{{ old('contact_person', ucwords(strtolower($startup->contact_person))) }}" required>
                                @error('contact_person')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" id="phone" name="phone" class="form-input @error('phone') error @enderror" 
                                       value="{{ old('phone', $startup->phone) }}" placeholder="+63 XXX XXX XXXX">
                                @error('phone')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address <span>*</span></label>
                            <input type="email" id="email" name="email" class="form-input @error('email') error @enderror" 
                                   value="{{ old('email', $startup->email) }}" required>
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address">Business Address</label>
                            <input type="text" id="address" name="address" class="form-input @error('address') error @enderror" 
                                   value="{{ old('address', $startup->address) }}" placeholder="Enter your business address">
                            @error('address')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Company Description</label>
                            <textarea id="description" name="description" class="form-textarea @error('description') error @enderror" 
                                      placeholder="Brief description of your startup, products, or services...">{{ old('description', $startup->description) }}</textarea>
                            @error('description')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions" style="border-top: none; padding-top: 0; margin-top: 24px;">
                            <button type="submit" class="btn btn-primary" id="saveBtn" style="width: 100%; justify-content: center;">
                                <i class="fas fa-save"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column -->
            <div class="profile-sidebar">
                <!-- MOA Status Card -->
                <div class="moa-status-card {{ $startup->moa_status === 'active' ? 'moa-active' : ($startup->moa_status === 'pending' ? 'moa-pending' : ($startup->moa_status === 'expired' ? 'moa-expired' : 'moa-none')) }}">
                    <div class="moa-icon">
                        @if($startup->moa_status === 'active')
                            <i class="fas fa-check-circle"></i>
                        @elseif($startup->moa_status === 'pending')
                            <i class="fas fa-clock"></i>
                        @elseif($startup->moa_status === 'expired')
                            <i class="fas fa-exclamation-circle"></i>
                        @else
                            <i class="fas fa-file-contract"></i>
                        @endif
                    </div>
                    <div class="moa-info">
                        <h3>
                            @if($startup->moa_status === 'active')
                                MOA Active
                            @elseif($startup->moa_status === 'pending')
                                MOA Pending
                            @elseif($startup->moa_status === 'expired')
                                MOA Expired
                            @else
                                No MOA on File
                            @endif
                        </h3>
                        <p>
                            @if($startup->moa_status === 'active' && $startup->moa_expiry)
                                Valid until {{ $startup->moa_expiry->format('M d, Y') }}
                            @elseif($startup->moa_status === 'pending')
                                Your request is being reviewed
                            @elseif($startup->moa_status === 'expired')
                                Please submit a renewal request
                            @else
                                Submit an MOA request to get started
                            @endif
                        </p>
                    </div>
                    @if($startup->moa_status !== 'active')
                        <a href="{{ route('startup.request-moa') }}" class="moa-action-btn">
                            <i class="fas fa-plus"></i>
                            {{ $startup->moa_status === 'expired' ? 'Renew MOA' : 'Request MOA' }}
                        </a>
                    @endif
                </div>

                <!-- Quick Actions Card -->
                <div class="quick-actions-card">
                    <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                    <div class="quick-action-links">
                        <a href="{{ route('startup.upload-document') }}" class="quick-action-link">
                            <div class="quick-action-icon" style="background: linear-gradient(135deg, #3B82F6, #2563EB);">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <span>Upload Document</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="{{ route('startup.report-issue') }}" class="quick-action-link">
                            <div class="quick-action-icon" style="background: linear-gradient(135deg, #EF4444, #DC2626);">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <span>Report Issue</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="{{ route('startup.submit-payment') }}" class="quick-action-link">
                            <div class="quick-action-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <span>Submit Payment</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="{{ route('startup.submissions') }}" class="quick-action-link">
                            <div class="quick-action-icon" style="background: linear-gradient(135deg, #8B5CF6, #7C3AED);">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <span>View Submissions</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Security Card -->
                <div class="security-card">
                    <div class="security-header">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Account Security</h3>
                    </div>
                    <p>To change your password or for account-related concerns, please contact the administrator.</p>
                    <div class="admin-contact">
                        <i class="fas fa-envelope"></i>
                        <span>admin@upcebu.edu.ph</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .profile-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    .profile-header-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        display: flex;
        align-items: center;
        gap: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        border: 1px solid #E5E7EB;
    }

    .profile-avatar-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .profile-avatar {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-light) 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 800;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 6px 20px rgba(123, 29, 58, 0.3);
        overflow: hidden;
    }

    .profile-avatar .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-upload-btn {
        position: absolute;
        bottom: -4px;
        right: -4px;
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        border: 3px solid white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #7B1D3A;
        font-size: 13px;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .avatar-upload-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(255, 191, 0, 0.4);
    }

    .profile-header-info h2 {
        font-size: 24px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .profile-header-info > p {
        color: #6B7280;
        font-size: 15px;
        margin-bottom: 12px;
    }

    .profile-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .badge i { font-size: 8px; }

    .badge-code {
        background: linear-gradient(135deg, #F3F4F6, #E5E7EB);
        color: #374151;
        font-family: 'Courier New', monospace;
    }

    .badge-code i { font-size: 12px; }

    .badge-success {
        background: linear-gradient(135deg, #D1FAE5, #A7F3D0);
        color: #059669;
    }

    .badge-danger {
        background: linear-gradient(135deg, #FEE2E2, #FECACA);
        color: #DC2626;
    }

    .badge-room {
        background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
        color: #2563EB;
    }

    .badge-room i { font-size: 12px; }

    .profile-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        border: 1px solid #E5E7EB;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .stat-info {
        display: flex;
        flex-direction: column;
    }

    .stat-label {
        font-size: 12px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 16px;
        font-weight: 700;
        color: #1F2937;
        margin-top: 2px;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 24px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .profile-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .moa-status-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        border: 2px solid #E5E7EB;
        text-align: center;
    }

    .moa-status-card.moa-active { border-color: #10B981; background: linear-gradient(135deg, #ECFDF5, #D1FAE5); }
    .moa-status-card.moa-pending { border-color: #F59E0B; background: linear-gradient(135deg, #FFFBEB, #FEF3C7); }
    .moa-status-card.moa-expired { border-color: #EF4444; background: linear-gradient(135deg, #FEF2F2, #FEE2E2); }
    .moa-status-card.moa-none { border-color: #9CA3AF; background: linear-gradient(135deg, #F9FAFB, #F3F4F6); }

    .moa-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 28px;
    }

    .moa-active .moa-icon { background: #10B981; color: white; }
    .moa-pending .moa-icon { background: #F59E0B; color: white; }
    .moa-expired .moa-icon { background: #EF4444; color: white; }
    .moa-none .moa-icon { background: #9CA3AF; color: white; }

    .moa-info h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 6px;
    }

    .moa-info p {
        font-size: 14px;
        color: #6B7280;
    }

    .moa-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 16px;
        padding: 12px 24px;
        background: linear-gradient(135deg, var(--maroon), var(--maroon-dark));
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
    }

    .moa-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(123, 29, 58, 0.3);
    }

    .quick-actions-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        border: 1px solid #E5E7EB;
    }

    .quick-actions-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .quick-actions-card h3 i { color: var(--gold); }

    .quick-action-links {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .quick-action-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: #F9FAFB;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .quick-action-link:hover {
        background: #F3F4F6;
        transform: translateX(4px);
    }

    .quick-action-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
    }

    .quick-action-link span {
        flex: 1;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .quick-action-link > i:last-child {
        color: #9CA3AF;
        font-size: 12px;
    }

    .security-card {
        background: linear-gradient(135deg, #1F2937, #374151);
        border-radius: 16px;
        padding: 24px;
        color: white;
    }

    .security-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .security-header i {
        color: var(--gold);
        font-size: 20px;
    }

    .security-header h3 {
        font-size: 16px;
        font-weight: 700;
    }

    .security-card > p {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 16px;
        line-height: 1.5;
    }

    .admin-contact {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        font-size: 14px;
    }

    .admin-contact i { color: var(--gold); }

    @media (max-width: 1024px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }

        .profile-sidebar {
            order: -1;
        }
    }

    @media (max-width: 768px) {
        .profile-header-card {
            flex-direction: column;
            text-align: center;
        }

        .profile-badges {
            justify-content: center;
        }

        .profile-stats {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('profileForm').addEventListener('submit', function() {
        const btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    });

    function previewAndSubmitPhoto(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                input.value = '';
                return;
            }

            // Validate file type
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                alert('Only JPG, PNG, and WebP images are allowed');
                input.value = '';
                return;
            }

            // Preview the image
            const reader = new FileReader();
            reader.onload = function(e) {
                const avatar = document.getElementById('profileAvatar');
                avatar.innerHTML = '<img src="' + e.target.result + '" alt="Profile Photo" class="avatar-img">';
            };
            reader.readAsDataURL(file);

            // Submit the form
            document.getElementById('photoForm').submit();
        }
    }
</script>
@endpush
