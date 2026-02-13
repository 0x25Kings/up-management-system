@extends('startup.layout')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
    <!-- Page Header -->
    <div class="page-header-card">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <h1>Notifications</h1>
                <p>Stay updated with your latest alerts and announcements</p>
            </div>
        </div>
    </div>

    <!-- Actions Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div style="font-size: 14px; color: #6B7280;">
            <i class="fas fa-bell" style="margin-right: 4px;"></i>
            {{ $notifications->total() }} notification{{ $notifications->total() !== 1 ? 's' : '' }}
            @php $unreadCount = $startup->notifications()->unread()->count(); @endphp
            @if($unreadCount > 0)
                <span style="background: #EF4444; color: white; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-left: 8px;">
                    {{ $unreadCount }} unread
                </span>
            @endif
        </div>
        @if($unreadCount > 0)
            <form action="{{ route('startup.notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" style="background: linear-gradient(135deg, #7B1D3A, #A62450); color: white; border: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-check-double"></i> Mark All as Read
                </button>
            </form>
        @endif
    </div>

    <!-- Notifications List -->
    <div style="display: flex; flex-direction: column; gap: 12px;">
        @forelse($notifications as $notification)
            <div style="background: {{ $notification->is_read ? 'white' : 'linear-gradient(135deg, #FFF7ED, #FFFBEB)' }}; border: 1px solid {{ $notification->is_read ? '#E5E7EB' : '#FCD34D' }}; border-radius: 16px; padding: 20px 24px; display: flex; align-items: flex-start; gap: 16px; transition: all 0.3s; position: relative; overflow: hidden; {{ !$notification->is_read ? 'box-shadow: 0 2px 12px rgba(252, 211, 77, 0.2);' : '' }}">
                @if(!$notification->is_read)
                    <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: {{ $notification->color ?? '#7B1D3A' }};"></div>
                @endif
                
                <!-- Icon -->
                <div style="width: 48px; height: 48px; min-width: 48px; background: {{ $notification->color ?? '#7B1D3A' }}15; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: {{ $notification->color ?? '#7B1D3A' }};">
                    <i class="fas {{ $notification->icon ?? 'fa-bell' }}"></i>
                </div>

                <!-- Content -->
                <div style="flex: 1; min-width: 0;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                        <h4 style="font-size: 15px; font-weight: 700; color: #1F2937; margin: 0;">{{ $notification->title }}</h4>
                        <span style="font-size: 11px; padding: 2px 10px; border-radius: 20px; font-weight: 600; background: {{ $notification->type === 'system' ? '#EFF6FF' : ($notification->type === 'submission' ? '#F0FDF4' : '#FEF3C7') }}; color: {{ $notification->type === 'system' ? '#2563EB' : ($notification->type === 'submission' ? '#16A34A' : '#D97706') }};">
                            {{ ucfirst($notification->type) }}
                        </span>
                    </div>
                    <p style="font-size: 14px; color: #4B5563; margin: 0 0 8px 0; line-height: 1.5;">{{ $notification->message }}</p>
                    <div style="display: flex; align-items: center; gap: 12px; font-size: 12px; color: #9CA3AF;">
                        <span><i class="far fa-clock" style="margin-right: 4px;"></i> {{ $notification->created_at->diffForHumans() }}</span>
                        @if($notification->is_read)
                            <span><i class="fas fa-check" style="margin-right: 4px; color: #10B981;"></i> Read {{ $notification->read_at?->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div style="display: flex; align-items: center; gap: 8px;">
                    @if(!$notification->is_read)
                        <form action="{{ route('startup.notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" title="Mark as read" style="width: 36px; height: 36px; border: 1px solid #E5E7EB; border-radius: 8px; background: white; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #6B7280; transition: all 0.3s;">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                    @endif
                    @if($notification->link)
                        <a href="{{ route('startup.notifications.read', $notification->id) }}" onclick="event.preventDefault(); document.getElementById('notif-link-{{ $notification->id }}').submit();" title="View details" style="width: 36px; height: 36px; border: 1px solid #E5E7EB; border-radius: 8px; background: white; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #7B1D3A; text-decoration: none; transition: all 0.3s;">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <form id="notif-link-{{ $notification->id }}" action="{{ route('startup.notifications.read', $notification->id) }}" method="POST" style="display:none;">@csrf</form>
                    @endif
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 20px; border: 1px solid #E5E7EB;">
                <div style="width: 80px; height: 80px; background: #F3F4F6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px; color: #9CA3AF;">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 700; color: #374151; margin-bottom: 8px;">No Notifications Yet</h3>
                <p style="font-size: 14px; color: #6B7280;">You'll see alerts and announcements here when they arrive.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $notifications->links() }}
        </div>
    @endif
@endsection
