@extends('team-leader.layouts.app')

@section('title', 'My Reports')
@section('subtitle', 'Reports submitted to admin')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('team-leader.reports.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Create New Report
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">My Reports ({{ $reports->total() }})</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td>
                            <div style="font-weight: 600;">{{ $report->title }}</div>
                            <div style="font-size: 12px; color: #6B7280; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ Str::limit($report->summary, 50) }}
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst($report->report_type) }}</span>
                        </td>
                        <td>
                            @if($report->period_start && $report->period_end)
                                {{ $report->period_start->format('M d') }} - {{ $report->period_end->format('M d, Y') }}
                            @else
                                <span style="color: #6B7280;">Not specified</span>
                            @endif
                        </td>
                        <td>
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
                        </td>
                        <td>{{ $report->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('team-leader.reports.show', $report) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($report->status === 'draft')
                                    <a href="{{ route('team-leader.reports.edit', $report) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('team-leader.reports.destroy', $report) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this report?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px; color: #6B7280;">
                            <i class="fas fa-file-alt" style="font-size: 48px; margin-bottom: 16px; display: block; color: #D1D5DB;"></i>
                            <h4 style="font-size: 18px; color: #4B5563; margin-bottom: 8px;">No reports yet</h4>
                            <p>Create your first report to submit to admin.</p>
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
@endsection
