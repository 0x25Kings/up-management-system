@extends('team-leader.layouts.app')

@section('title', 'Team Attendance')
@section('subtitle', 'View attendance for ' . $date->format('F d, Y'))

@section('content')
<!-- Date Selector -->
<div class="filter-section">
    <form action="{{ route('team-leader.attendance') }}" method="GET" style="display: flex; gap: 12px; align-items: center;">
        <label style="font-weight: 600; color: #374151;">Select Date:</label>
        <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="form-input" style="width: auto;" onchange="this.form.submit()">
        <a href="{{ route('team-leader.attendance') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-calendar-day"></i> Today
        </a>
    </form>
</div>

<!-- Stats -->
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px;">
    <div class="card" style="border-left: 4px solid #10B981;">
        <div class="card-body" style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #ECFDF5; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-check" style="color: #10B981; font-size: 20px;"></i>
            </div>
            <div>
                <div style="font-size: 28px; font-weight: 700; color: #10B981;">{{ $present }}</div>
                <div style="color: #6B7280; font-size: 14px;">Present</div>
            </div>
        </div>
    </div>
    <div class="card" style="border-left: 4px solid #EF4444;">
        <div class="card-body" style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #FEF2F2; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-times" style="color: #EF4444; font-size: 20px;"></i>
            </div>
            <div>
                <div style="font-size: 28px; font-weight: 700; color: #EF4444;">{{ $absent }}</div>
                <div style="color: #6B7280; font-size: 14px;">Absent</div>
            </div>
        </div>
    </div>
    <div class="card" style="border-left: 4px solid #F59E0B;">
        <div class="card-body" style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #FFFBEB; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-clock" style="color: #F59E0B; font-size: 20px;"></i>
            </div>
            <div>
                <div style="font-size: 28px; font-weight: 700; color: #F59E0B;">{{ $late }}</div>
                <div style="color: #6B7280; font-size: 14px;">Late</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Attendance for {{ $date->format('l, F d, Y') }}</h3>
        <span class="badge badge-info">{{ $interns->count() }} Active Interns</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Intern</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Hours Worked</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($interns as $intern)
                    @php
                        $attendance = $attendances->firstWhere('intern_id', $intern->id);
                        $hoursWorked = 0;
                        $isLate = false;
                        
                        if ($attendance && $attendance->time_in && $attendance->time_out) {
                            $timeIn = \Carbon\Carbon::parse($attendance->time_in);
                            $timeOut = \Carbon\Carbon::parse($attendance->time_out);
                            $hoursWorked = $timeOut->diffInMinutes($timeIn) / 60;
                        }
                        
                        if ($attendance && $attendance->time_in) {
                            $timeIn = \Carbon\Carbon::parse($attendance->time_in);
                            $isLate = $timeIn->format('H:i') > '08:00';
                        }
                    @endphp
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                    {{ strtoupper(substr($intern->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600;">{{ $intern->name }}</div>
                                    <div style="font-size: 12px; color: #6B7280;">{{ $intern->course }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($attendance && $attendance->time_in)
                                <span style="{{ $isLate ? 'color: #F59E0B; font-weight: 600;' : '' }}">
                                    {{ \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') }}
                                </span>
                                @if($isLate)
                                    <span class="badge badge-warning" style="margin-left: 8px;">Late</span>
                                @endif
                            @else
                                <span style="color: #9CA3AF;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($attendance && $attendance->time_out)
                                {{ \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') }}
                            @else
                                <span style="color: #9CA3AF;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($hoursWorked > 0)
                                <span style="font-weight: 600;">{{ number_format($hoursWorked, 1) }} hrs</span>
                            @else
                                <span style="color: #9CA3AF;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($attendance && $attendance->status === 'Present')
                                <span class="badge badge-success">Present</span>
                            @else
                                <span class="badge badge-danger">Absent</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
