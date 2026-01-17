@extends('team-leader.layouts.app')

@section('title', 'My Interns')
@section('subtitle', $school->name)

@section('content')
<div class="filter-section">
    <form action="{{ route('team-leader.interns') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; width: 100%;">
        <input type="text" name="search" placeholder="Search interns..." value="{{ request('search') }}" style="width: 250px;">
        <select name="status">
            <option value="all">All Status</option>
            <option value="Active" @if(request('status') === 'Active') selected @endif>Active</option>
            <option value="Completed" @if(request('status') === 'Completed') selected @endif>Completed</option>
            <option value="Inactive" @if(request('status') === 'Inactive') selected @endif>Inactive</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-search"></i> Search
        </button>
        @if(request('search') || request('status'))
            <a href="{{ route('team-leader.interns') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i> Clear
            </a>
        @endif
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Interns ({{ $interns->total() }})</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Hours</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($interns as $intern)
                    @php
                        $progress = $intern->required_hours > 0 
                            ? round(($intern->completed_hours / $intern->required_hours) * 100, 1) 
                            : 0;
                        $progressClass = $progress >= 75 ? 'green' : ($progress >= 50 ? 'yellow' : 'red');
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight: 600;">{{ $intern->name }}</div>
                            <div style="font-size: 12px; color: #6B7280;">{{ $intern->email }}</div>
                        </td>
                        <td>
                            <div>{{ $intern->course }}</div>
                            <div style="font-size: 12px; color: #6B7280;">Year {{ $intern->year_level }}</div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $intern->status === 'Active' ? 'success' : ($intern->status === 'Completed' ? 'info' : 'warning') }}">
                                {{ $intern->status }}
                            </span>
                        </td>
                        <td style="width: 150px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="progress-container" style="flex: 1;">
                                    <div class="progress-bar {{ $progressClass }}" style="width: {{ $progress }}%"></div>
                                </div>
                                <span style="font-size: 12px; font-weight: 600; color: #6B7280;">{{ $progress }}%</span>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ number_format($intern->completed_hours, 1) }}</div>
                            <div style="font-size: 12px; color: #6B7280;">/ {{ $intern->required_hours }} hrs</div>
                        </td>
                        <td>
                            <a href="{{ route('team-leader.interns.show', $intern) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px; color: #6B7280;">
                            <i class="fas fa-users" style="font-size: 48px; margin-bottom: 16px; display: block; color: #D1D5DB;"></i>
                            <h4 style="font-size: 18px; color: #4B5563; margin-bottom: 8px;">No interns found</h4>
                            <p>No interns match your search criteria.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">
    {{ $interns->appends(request()->query())->links('pagination::simple-bootstrap-4') }}
</div>
@endsection
