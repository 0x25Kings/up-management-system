@extends('team-leader.layouts.app')

@section('title', $report->title)
@section('subtitle', ucfirst($report->report_type) . ' Report')

@section('content')
<div style="margin-bottom: 24px; display: flex; gap: 12px;">
    <a href="{{ route('team-leader.reports') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
    @if($report->status === 'draft')
        <a href="{{ route('team-leader.reports.edit', $report) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Report
        </a>
    @endif
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $report->title }}</h3>
        <div style="display: flex; gap: 12px; align-items: center;">
            @switch($report->status)
                @case('draft')
                    <span class="badge badge-warning">Draft</span>
                    @break
                @case('submitted')
                    <span class="badge badge-info">Submitted</span>
                    @break
                @case('reviewed')
                    <span class="badge badge-success">Reviewed</span>
                    @break
                @case('acknowledged')
                    <span class="badge" style="background: #ECFDF5; color: #059669;">Acknowledged</span>
                    @break
            @endswitch
            <span class="badge badge-info">{{ ucfirst($report->report_type) }}</span>
        </div>
    </div>
    <div class="card-body">
        <!-- Report Meta -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px; padding: 20px; background: #F9FAFB; border-radius: 12px;">
            <div>
                <div style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Created</div>
                <div style="font-weight: 600; color: #1F2937;">{{ $report->created_at->format('M d, Y h:i A') }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Report Period</div>
                <div style="font-weight: 600; color: #1F2937;">
                    @if($report->period_start && $report->period_end)
                        {{ $report->period_start->format('M d, Y') }} - {{ $report->period_end->format('M d, Y') }}
                    @else
                        Not specified
                    @endif
                </div>
            </div>
            <div>
                <div style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">School</div>
                <div style="font-weight: 600; color: #1F2937;">{{ $report->school->name ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Task Statistics -->
        @if($report->task_statistics)
            <div style="margin-bottom: 32px;">
                <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 16px;">
                    <i class="fas fa-chart-pie" style="color: #3B82F6;"></i> Task Statistics at Time of Report
                </h4>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
                    <div style="text-align: center; padding: 20px; background: #F9FAFB; border-radius: 12px;">
                        <div style="font-size: 28px; font-weight: 700; color: #1F2937;">{{ $report->task_statistics['total'] ?? 0 }}</div>
                        <div style="font-size: 12px; color: #6B7280;">Total Tasks</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: #ECFDF5; border-radius: 12px;">
                        <div style="font-size: 28px; font-weight: 700; color: #059669;">{{ $report->task_statistics['completed'] ?? 0 }}</div>
                        <div style="font-size: 12px; color: #065F46;">Completed</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: #EFF6FF; border-radius: 12px;">
                        <div style="font-size: 28px; font-weight: 700; color: #2563EB;">{{ $report->task_statistics['in_progress'] ?? 0 }}</div>
                        <div style="font-size: 12px; color: #1E40AF;">In Progress</div>
                    </div>
                    <div style="text-align: center; padding: 20px; background: #FFFBEB; border-radius: 12px;">
                        <div style="font-size: 28px; font-weight: 700; color: #D97706;">{{ $report->task_statistics['pending'] ?? 0 }}</div>
                        <div style="font-size: 12px; color: #92400E;">Pending</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Report Content -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div>
                <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 12px;">
                    <i class="fas fa-file-alt" style="color: #3B82F6;"></i> Summary
                </h4>
                <div style="background: #F9FAFB; padding: 20px; border-radius: 12px; white-space: pre-wrap;">{{ $report->summary }}</div>
            </div>

            @if($report->accomplishments)
                <div>
                    <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 12px;">
                        <i class="fas fa-trophy" style="color: #10B981;"></i> Accomplishments
                    </h4>
                    <div style="background: #ECFDF5; padding: 20px; border-radius: 12px; white-space: pre-wrap;">{{ $report->accomplishments }}</div>
                </div>
            @endif

            @if($report->challenges)
                <div>
                    <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 12px;">
                        <i class="fas fa-exclamation-triangle" style="color: #F59E0B;"></i> Challenges / Issues
                    </h4>
                    <div style="background: #FFFBEB; padding: 20px; border-radius: 12px; white-space: pre-wrap;">{{ $report->challenges }}</div>
                </div>
            @endif

            @if($report->recommendations)
                <div>
                    <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 12px;">
                        <i class="fas fa-lightbulb" style="color: #8B5CF6;"></i> Recommendations
                    </h4>
                    <div style="background: #F5F3FF; padding: 20px; border-radius: 12px; white-space: pre-wrap;">{{ $report->recommendations }}</div>
                </div>
            @endif
        </div>

        <!-- Admin Feedback -->
        @if($report->admin_feedback)
            <hr style="margin: 32px 0; border: none; border-top: 1px solid #E5E7EB;">
            <div>
                <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 12px;">
                    <i class="fas fa-comment" style="color: #3B82F6;"></i> Admin Feedback
                </h4>
                <div style="background: #EFF6FF; padding: 20px; border-radius: 12px; border-left: 4px solid #3B82F6;">
                    <div style="white-space: pre-wrap;">{{ $report->admin_feedback }}</div>
                    @if($report->reviewed_at)
                        <div style="margin-top: 12px; font-size: 12px; color: #6B7280;">
                            Reviewed by {{ $report->reviewer->name ?? 'Admin' }} on {{ $report->reviewed_at->format('M d, Y h:i A') }}
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Submit Button for Drafts -->
        @if($report->status === 'draft')
            <hr style="margin: 32px 0; border: none; border-top: 1px solid #E5E7EB;">
            <form action="{{ route('team-leader.reports.update', $report) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="title" value="{{ $report->title }}">
                <input type="hidden" name="report_type" value="{{ $report->report_type }}">
                <input type="hidden" name="summary" value="{{ $report->summary }}">
                <input type="hidden" name="accomplishments" value="{{ $report->accomplishments }}">
                <input type="hidden" name="challenges" value="{{ $report->challenges }}">
                <input type="hidden" name="recommendations" value="{{ $report->recommendations }}">
                <input type="hidden" name="period_start" value="{{ $report->period_start?->format('Y-m-d') }}">
                <input type="hidden" name="period_end" value="{{ $report->period_end?->format('Y-m-d') }}">
                <button type="submit" name="status" value="submitted" class="btn btn-success">
                    <i class="fas fa-paper-plane"></i> Submit to Admin
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
