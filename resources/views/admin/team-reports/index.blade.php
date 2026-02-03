<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Team Leader Reports - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F9FAFB; }

        .card { background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); overflow: hidden; }
        .card-header { padding: 16px 20px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center; }
        .card-title { font-size: 17px; font-weight: 700; color: #1F2937; }
        .card-body { padding: 20px; }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { text-align: left; padding: 10px 14px; background: #F9FAFB; color: #6B7280; font-weight: 600; font-size: 12px; text-transform: uppercase; }
        .data-table td { padding: 14px; border-bottom: 1px solid #E5E7EB; }
        .data-table tr:hover { background: #F9FAFB; }

        .badge { padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #ECFDF5; color: #059669; }
        .badge-warning { background: #FFFBEB; color: #D97706; }
        .badge-info { background: #EFF6FF; color: #2563EB; }

        .btn { padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s; }
        .btn-primary { background: linear-gradient(135deg, #7B1D3A 0%, #5a1428 100%); color: white; }
        .btn-secondary { background: #F3F4F6; color: #4B5563; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }

        .alert { padding: 14px 18px; border-radius: 12px; display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
        .alert-success { background: #ECFDF5; border-left: 4px solid #10B981; color: #065F46; }
        .alert-warning { background: #FFFBEB; border-left: 4px solid #F59E0B; color: #92400E; }

        .stat-card { background: white; padding: 16px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); border-left: 4px solid; }
        .stat-card.warning { border-left-color: #F59E0B; }

        .pagination { display: flex; justify-content: center; gap: 6px; margin-top: 20px; }
        .pagination a, .pagination span { padding: 6px 14px; border-radius: 8px; background: white; border: 1px solid #E5E7EB; }
    </style>
</head>
<body>
    <div style="padding: 24px; max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h1 style="font-size: 28px; font-weight: 700; color: #1F2937;">Team Leader Reports</h1>
                <p style="color: #6B7280;">Review and respond to team leader reports</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($pendingCount > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                You have <strong>{{ $pendingCount }}</strong> report(s) waiting for your review.
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Reports ({{ $reports->total() }})</h3>
                <span class="badge badge-warning">{{ $pendingCount }} Pending</span>
            </div>
            <div class="card-body" style="padding: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Report</th>
                            <th>Team Leader</th>
                            <th>School</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>
                                    <div style="font-weight: 600;">{{ $report->title }}</div>
                                    <div style="font-size: 12px; color: #6B7280;">
                                        @if($report->period_start && $report->period_end)
                                            {{ $report->period_start->format('M d') }} - {{ $report->period_end->format('M d, Y') }}
                                        @else
                                            No period specified
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #1e3a5f 0%, #152a45 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 600;">
                                            {{ strtoupper(substr($report->teamLeader->name ?? 'N', 0, 1)) }}
                                        </div>
                                        <span>{{ $report->teamLeader->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>{{ $report->school->name ?? 'N/A' }}</td>
                                <td><span class="badge badge-info">{{ ucfirst($report->report_type) }}</span></td>
                                <td>
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
                                </td>
                                <td>{{ $report->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.team-reports.show', $report) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 32px; color: #6B7280;">
                                    <i class="fas fa-file-alt" style="font-size: 48px; margin-bottom: 16px; display: block; color: #D1D5DB;"></i>
                                    <h4 style="font-size: 18px; color: #4B5563; margin-bottom: 8px;">No Reports Yet</h4>
                                    <p>Team leaders haven't submitted any reports yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pagination">
            {{ $reports->links('pagination::simple-bootstrap-4') }}
        </div>
    </div>
</body>
</html>
