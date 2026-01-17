<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $report->title }} - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F9FAFB; }
        
        .card { background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); overflow: hidden; }
        .card-header { padding: 20px 24px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center; }
        .card-title { font-size: 18px; font-weight: 700; color: #1F2937; }
        .card-body { padding: 24px; }

        .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #ECFDF5; color: #059669; }
        .badge-warning { background: #FFFBEB; color: #D97706; }
        .badge-info { background: #EFF6FF; color: #2563EB; }

        .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.2s; }
        .btn-primary { background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; }
        .btn-success { background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; }
        .btn-secondary { background: #F3F4F6; color: #4B5563; }

        .alert { padding: 16px 20px; border-radius: 12px; display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
        .alert-success { background: #ECFDF5; border-left: 4px solid #10B981; color: #065F46; }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 14px; }
        .form-input { width: 100%; padding: 12px 16px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 14px; }
        .form-input:focus { outline: none; border-color: #7B1D3A; box-shadow: 0 0 0 3px rgba(123, 29, 58, 0.1); }
        .form-textarea { min-height: 120px; resize: vertical; }

        .section-title { font-weight: 600; color: #1F2937; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .section-content { background: #F9FAFB; padding: 20px; border-radius: 12px; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div style="padding: 32px; max-width: 900px; margin: 0 auto;">
        <div style="display: flex; gap: 12px; margin-bottom: 24px;">
            <a href="{{ route('admin.team-reports.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">{{ $report->title }}</h3>
                    <p style="color: #6B7280; margin-top: 4px;">Submitted by {{ $report->teamLeader->name ?? 'N/A' }}</p>
                </div>
                <div style="display: flex; gap: 12px;">
                    @switch($report->status)
                        @case('submitted')
                            <span class="badge badge-warning">Pending Review</span>
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
                        <div style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">School</div>
                        <div style="font-weight: 600; color: #1F2937;">{{ $report->school->name ?? 'N/A' }}</div>
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
                        <div style="font-size: 12px; color: #6B7280; text-transform: uppercase; font-weight: 600;">Submitted</div>
                        <div style="font-weight: 600; color: #1F2937;">{{ $report->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>

                <!-- Task Statistics -->
                @if($report->task_statistics)
                    <div style="margin-bottom: 32px;">
                        <h4 class="section-title">
                            <i class="fas fa-chart-pie" style="color: #7B1D3A;"></i> Task Statistics at Time of Report
                        </h4>
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
                            <div style="text-align: center; padding: 20px; background: #F9FAFB; border-radius: 12px;">
                                <div style="font-size: 28px; font-weight: 700; color: #1F2937;">{{ $report->task_statistics['total'] ?? 0 }}</div>
                                <div style="font-size: 12px; color: #6B7280;">Total</div>
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
                        <h4 class="section-title">
                            <i class="fas fa-file-alt" style="color: #7B1D3A;"></i> Summary
                        </h4>
                        <div class="section-content">{{ $report->summary }}</div>
                    </div>

                    @if($report->accomplishments)
                        <div>
                            <h4 class="section-title">
                                <i class="fas fa-trophy" style="color: #10B981;"></i> Accomplishments
                            </h4>
                            <div class="section-content" style="background: #ECFDF5;">{{ $report->accomplishments }}</div>
                        </div>
                    @endif

                    @if($report->challenges)
                        <div>
                            <h4 class="section-title">
                                <i class="fas fa-exclamation-triangle" style="color: #F59E0B;"></i> Challenges / Issues
                            </h4>
                            <div class="section-content" style="background: #FFFBEB;">{{ $report->challenges }}</div>
                        </div>
                    @endif

                    @if($report->recommendations)
                        <div>
                            <h4 class="section-title">
                                <i class="fas fa-lightbulb" style="color: #8B5CF6;"></i> Recommendations
                            </h4>
                            <div class="section-content" style="background: #F5F3FF;">{{ $report->recommendations }}</div>
                        </div>
                    @endif
                </div>

                <!-- Previous Feedback -->
                @if($report->admin_feedback && $report->status !== 'submitted')
                    <hr style="margin: 32px 0; border: none; border-top: 1px solid #E5E7EB;">
                    <div>
                        <h4 class="section-title">
                            <i class="fas fa-comment" style="color: #7B1D3A;"></i> Your Feedback
                        </h4>
                        <div class="section-content" style="background: #FEF2F2; border-left: 4px solid #7B1D3A;">
                            {{ $report->admin_feedback }}
                            @if($report->reviewed_at)
                                <div style="margin-top: 12px; font-size: 12px; color: #6B7280;">
                                    Reviewed on {{ $report->reviewed_at->format('M d, Y h:i A') }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Review Form -->
                @if($report->status === 'submitted')
                    <hr style="margin: 32px 0; border: none; border-top: 1px solid #E5E7EB;">
                    <form action="{{ route('admin.team-reports.review', $report) }}" method="POST">
                        @csrf
                        <h4 class="section-title">
                            <i class="fas fa-pen" style="color: #7B1D3A;"></i> Review This Report
                        </h4>

                        <div class="form-group">
                            <label class="form-label">Your Feedback (Optional)</label>
                            <textarea name="admin_feedback" class="form-input form-textarea" placeholder="Provide feedback or comments to the team leader..."></textarea>
                        </div>

                        <div style="display: flex; gap: 12px;">
                            <button type="submit" name="status" value="reviewed" class="btn btn-primary">
                                <i class="fas fa-check"></i> Mark as Reviewed
                            </button>
                            <button type="submit" name="status" value="acknowledged" class="btn btn-success">
                                <i class="fas fa-check-double"></i> Acknowledge
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
