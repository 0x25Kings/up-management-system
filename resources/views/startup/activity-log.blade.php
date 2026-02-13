@extends('startup.layout')

@section('title', 'Activity Log')
@section('page-title', 'Activity Log')

@section('content')
    <!-- Page Header -->
    <div class="page-header-card">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-history"></i>
            </div>
            <div>
                <h1>Activity Log</h1>
                <p>A complete timeline of your account activity</p>
            </div>
        </div>
    </div>

    <!-- Activity Stats -->
    <div style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
        <div style="background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 12px 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-list" style="color: #7B1D3A;"></i>
            <span style="font-size: 14px; color: #374151; font-weight: 600;">{{ $logs->total() }} total activities</span>
        </div>
        @if($logs->count() > 0)
            <div style="background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 12px 20px; display: flex; align-items: center; gap: 10px;">
                <i class="far fa-calendar" style="color: #6B7280;"></i>
                <span style="font-size: 14px; color: #374151;">Latest: {{ $logs->first()->created_at->diffForHumans() }}</span>
            </div>
        @endif
    </div>

    <!-- Activity Timeline -->
    <div class="form-card">
        <div class="form-card-header">
            <h2><i class="fas fa-stream"></i> Activity Timeline</h2>
            <p>Your recent actions and system events</p>
        </div>
        <div class="form-card-body" style="padding: 24px 32px;">
            @forelse($logs as $index => $log)
                <div style="display: flex; gap: 20px; position: relative; {{ !$loop->last ? 'padding-bottom: 28px;' : '' }}">
                    <!-- Timeline Line -->
                    @if(!$loop->last)
                        <div style="position: absolute; left: 23px; top: 48px; bottom: 0; width: 2px; background: #E5E7EB;"></div>
                    @endif
                    
                    <!-- Icon -->
                    <div style="width: 48px; height: 48px; min-width: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; color: white; background: {{ $log->color }}; box-shadow: 0 4px 12px {{ $log->color }}33; z-index: 1;">
                        <i class="fas {{ $log->icon }}"></i>
                    </div>

                    <!-- Content -->
                    <div style="flex: 1; padding-top: 2px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px; flex-wrap: wrap;">
                            <h4 style="font-size: 14px; font-weight: 700; color: #1F2937; margin: 0;">
                                {{ ucwords(str_replace('_', ' ', $log->action)) }}
                            </h4>
                            <span style="font-size: 11px; padding: 2px 10px; border-radius: 20px; font-weight: 600; background: {{ $log->color }}15; color: {{ $log->color }};">
                                {{ ucfirst(explode('_', $log->action)[0]) }}
                            </span>
                        </div>
                        <p style="font-size: 13px; color: #6B7280; margin: 0 0 8px 0;">{{ $log->description }}</p>
                        <div style="display: flex; align-items: center; gap: 16px; font-size: 12px; color: #9CA3AF; flex-wrap: wrap;">
                            <span><i class="far fa-clock" style="margin-right: 4px;"></i> {{ $log->created_at->format('M d, Y \a\t h:i A') }}</span>
                            <span><i class="fas fa-globe" style="margin-right: 4px;"></i> {{ $log->ip_address }}</span>
                            @if($log->created_at->isToday())
                                <span style="background: #DCFCE7; color: #166534; padding: 1px 8px; border-radius: 10px; font-weight: 600;">Today</span>
                            @elseif($log->created_at->isYesterday())
                                <span style="background: #EFF6FF; color: #2563EB; padding: 1px 8px; border-radius: 10px; font-weight: 600;">Yesterday</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Date separator --}}
                @if(!$loop->last && !$log->created_at->isSameDay($logs[$index + 1]->created_at ?? $log->created_at))
                    <div style="display: flex; align-items: center; gap: 12px; padding: 8px 0 8px 68px; margin-bottom: 4px;">
                        <div style="flex: 1; height: 1px; background: #E5E7EB;"></div>
                        <span style="font-size: 11px; color: #9CA3AF; font-weight: 600; white-space: nowrap;">
                            {{ $logs[$index + 1]->created_at->format('F d, Y') }}
                        </span>
                        <div style="flex: 1; height: 1px; background: #E5E7EB;"></div>
                    </div>
                @endif
            @empty
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="width: 80px; height: 80px; background: #F3F4F6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px; color: #9CA3AF;">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #374151; margin-bottom: 8px;">No Activity Yet</h3>
                    <p style="font-size: 14px; color: #6B7280;">Your account activity will appear here as you use the portal.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $logs->links() }}
        </div>
    @endif
@endsection
