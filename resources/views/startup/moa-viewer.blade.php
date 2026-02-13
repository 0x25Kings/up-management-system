@extends('startup.layout')

@section('title', 'MOA Documents')
@section('page-title', 'MOA Documents')

@section('content')
    <!-- Page Header -->
    <div class="page-header-card">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-file-signature"></i>
            </div>
            <div>
                <h1>MOA Documents</h1>
                <p>View and manage your Memorandum of Agreement submissions</p>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px;">
        <div style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #E5E7EB; display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; background: #EFF6FF; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #3B82F6; font-size: 20px;">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <div style="font-size: 24px; font-weight: 800; color: #1F2937;">{{ $moaSubmissions->count() }}</div>
                <div style="font-size: 12px; color: #6B7280;">Total MOAs</div>
            </div>
        </div>
        <div style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #E5E7EB; display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; background: #F0FDF4; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #16A34A; font-size: 20px;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div style="font-size: 24px; font-weight: 800; color: #1F2937;">{{ $moaSubmissions->where('status', 'approved')->count() }}</div>
                <div style="font-size: 12px; color: #6B7280;">Approved</div>
            </div>
        </div>
        <div style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #E5E7EB; display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; background: #FEF3C7; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #D97706; font-size: 20px;">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div style="font-size: 24px; font-weight: 800; color: #1F2937;">{{ $moaSubmissions->where('status', 'pending')->count() }}</div>
                <div style="font-size: 12px; color: #6B7280;">Pending</div>
            </div>
        </div>
    </div>

    <!-- MOA Documents List -->
    <div class="form-card">
        <div class="form-card-header">
            <h2><i class="fas fa-folder-open"></i> Your MOA Submissions</h2>
            <p>All your Memorandum of Agreement documents in one place</p>
        </div>
        <div class="form-card-body" style="padding: 0;">
            @forelse($moaSubmissions as $moa)
                <div style="padding: 20px 32px; border-bottom: 1px solid #F3F4F6; display: flex; align-items: center; gap: 20px; transition: background 0.2s; cursor: default;"
                     onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='white'">
                    
                    <!-- Document Icon -->
                    <div style="width: 52px; height: 52px; min-width: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px;
                        @if($moa->status === 'approved')
                            background: #F0FDF4; color: #16A34A;
                        @elseif($moa->status === 'pending')
                            background: #FEF3C7; color: #D97706;
                        @elseif($moa->status === 'rejected')
                            background: #FEE2E2; color: #DC2626;
                        @else
                            background: #F3F4F6; color: #6B7280;
                        @endif
                    ">
                        <i class="fas fa-file-contract"></i>
                    </div>

                    <!-- Document Info -->
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px; flex-wrap: wrap;">
                            <h4 style="font-size: 15px; font-weight: 700; color: #1F2937; margin: 0;">{{ $moa->title ?? 'MOA Document' }}</h4>
                            <span style="font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 600;
                                @if($moa->status === 'approved')
                                    background: #DCFCE7; color: #166534;
                                @elseif($moa->status === 'pending')
                                    background: #FEF3C7; color: #92400E;
                                @elseif($moa->status === 'rejected')
                                    background: #FEE2E2; color: #991B1B;
                                @else
                                    background: #F3F4F6; color: #374151;
                                @endif
                            ">
                                {{ ucfirst($moa->status) }}
                            </span>
                        </div>
                        @if($moa->notes)
                            <p style="font-size: 13px; color: #6B7280; margin: 0 0 6px 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $moa->notes }}</p>
                        @endif
                        <div style="display: flex; align-items: center; gap: 16px; font-size: 12px; color: #9CA3AF; flex-wrap: wrap;">
                            <span><i class="far fa-calendar" style="margin-right: 4px;"></i> {{ $moa->created_at->format('M d, Y') }}</span>
                            @if($moa->tracking_code)
                                <span><i class="fas fa-hashtag" style="margin-right: 4px;"></i> {{ $moa->tracking_code }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div style="display: flex; gap: 8px;">
                        @if($moa->file_path)
                            <a href="{{ asset('storage/' . $moa->file_path) }}" target="_blank" title="View Document" style="width: 40px; height: 40px; border: 1px solid #E5E7EB; border-radius: 10px; background: white; display: flex; align-items: center; justify-content: center; color: #7B1D3A; text-decoration: none; transition: all 0.3s; font-size: 16px;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ asset('storage/' . $moa->file_path) }}" download title="Download" style="width: 40px; height: 40px; border: 1px solid #E5E7EB; border-radius: 10px; background: white; display: flex; align-items: center; justify-content: center; color: #3B82F6; text-decoration: none; transition: all 0.3s; font-size: 16px;">
                                <i class="fas fa-download"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 80px 20px;">
                    <div style="width: 80px; height: 80px; background: #F3F4F6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px; color: #9CA3AF;">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #374151; margin-bottom: 8px;">No MOA Documents</h3>
                    <p style="font-size: 14px; color: #6B7280; margin-bottom: 20px;">You haven't submitted any MOA requests yet.</p>
                    <a href="{{ route('startup.request-moa') }}" style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #7B1D3A, #A62450); color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s;">
                        <i class="fas fa-plus"></i> Request MOA
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
