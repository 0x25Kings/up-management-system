@extends('team-leader.layouts.app')

@section('title', $intern->name)
@section('subtitle', $intern->course . ' - ' . $intern->schoolRelation->name ?? 'N/A')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('team-leader.interns') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Interns
    </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Intern Info Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Intern Information</h3>
        </div>
        <div class="card-body">
            <div style="text-align: center; margin-bottom: 24px;">
                @if($intern->profile_picture && file_exists(public_path('storage/' . $intern->profile_picture)))
                    <img src="{{ asset('storage/' . $intern->profile_picture) }}" alt="{{ $intern->name }}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin: 0 auto 16px; display: block; border: 3px solid #E5E7EB;">
                @else
                    <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: 600; margin: 0 auto 16px;">
                        {{ strtoupper(substr($intern->name, 0, 1)) }}
                    </div>
                @endif
                <h4 style="font-size: 20px; font-weight: 700; color: #1F2937;">{{ $intern->name }}</h4>
                <span class="badge badge-{{ $intern->status === 'Active' ? 'success' : ($intern->status === 'Completed' ? 'info' : 'warning') }}">
                    {{ $intern->status }}
                </span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div>
                    <div style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Email</div>
                    <div style="color: #1F2937;">{{ $intern->email }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Phone</div>
                    <div style="color: #1F2937;">{{ $intern->phone ?? 'N/A' }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Course</div>
                    <div style="color: #1F2937;">{{ $intern->course }} - Year {{ $intern->year_level }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Internship Period</div>
                    <div style="color: #1F2937;">{{ $intern->start_date?->format('M d, Y') }} - {{ $intern->end_date?->format('M d, Y') }}</div>
                </div>
            </div>

            <hr style="margin: 24px 0; border: none; border-top: 1px solid #E5E7EB;">

            @php
                $progress = $intern->required_hours > 0
                    ? round(($intern->completed_hours / $intern->required_hours) * 100, 1)
                    : 0;
                $progressClass = $progress >= 75 ? 'green' : ($progress >= 50 ? 'yellow' : 'red');
            @endphp

            <div>
                <div style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600; margin-bottom: 8px;">Hours Progress</div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-weight: 600;">{{ number_format($intern->completed_hours, 1) }} hrs</span>
                    <span style="color: #6B7280;">{{ $intern->required_hours }} hrs required</span>
                </div>
                <div class="progress-container">
                    <div class="progress-bar {{ $progressClass }}" style="width: {{ $progress }}%"></div>
                </div>
                <div style="text-align: center; margin-top: 8px; font-weight: 600; color: #4B5563;">{{ $progress }}% Complete</div>
            </div>
        </div>
    </div>

    <!-- Tasks & Attendance -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Tasks -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Assigned Tasks ({{ $tasks->count() }})</h3>
                <a href="{{ route('team-leader.tasks.create') }}?intern_id={{ $intern->id }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Assign Task
                </a>
            </div>
            <div class="card-body" style="padding: 0; max-height: 400px; overflow-y: auto;">
                @forelse($tasks as $task)
                    @php $isPendingAdminApproval = $task->status === 'Completed' && empty($task->completed_date); @endphp
                    <div style="padding: 16px; border-bottom: 1px solid #E5E7EB; display: flex; align-items: center; gap: 16px;">
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #1F2937;">{{ $task->title }}</div>
                            <div style="font-size: 12px; color: #6B7280;">
                                Due: {{ $task->due_date?->format('M d, Y') ?? 'No due date' }}
                                @if($task->isOverdue())
                                    <span style="color: #DC2626; font-weight: 600;"> (Overdue)</span>
                                @endif
                            </div>
                        </div>
                        <span class="badge badge-{{ $task->priority === 'Low' ? 'success' : ($task->priority === 'Medium' ? 'warning' : 'danger') }}">
                            {{ $task->priority }}
                        </span>
                        <span class="badge badge-{{ $isPendingAdminApproval ? 'info' : ($task->status === 'Completed' ? 'success' : ($task->status === 'In Progress' ? 'info' : 'warning')) }}">
                            {{ $isPendingAdminApproval ? 'Pending Admin Approval' : $task->status }}
                        </span>
                        <a href="{{ route('team-leader.tasks.edit', $task) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                @empty
                    <div style="text-align: center; padding: 48px; color: #6B7280;">
                        <i class="fas fa-tasks" style="font-size: 32px; margin-bottom: 12px; display: block; color: #D1D5DB;"></i>
                        <p>No tasks assigned yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Attendance -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Attendance</h3>
            </div>
            <div class="card-body" style="padding: 0; max-height: 300px; overflow-y: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Hours</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            @php
                                $hoursWorked = 0;
                                if ($attendance->time_in && $attendance->time_out) {
                                    $timeIn = \Carbon\Carbon::parse($attendance->time_in);
                                    $timeOut = \Carbon\Carbon::parse($attendance->time_out);
                                    $hoursWorked = $timeOut->diffInMinutes($timeIn) / 60;
                                }
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                                <td>{{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') : '-' }}</td>
                                <td>{{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') : '-' }}</td>
                                <td>{{ number_format($hoursWorked, 1) }} hrs</td>
                                <td>
                                    <span class="badge badge-{{ $attendance->status === 'Present' ? 'success' : 'danger' }}">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 32px; color: #6B7280;">
                                    No attendance records found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
