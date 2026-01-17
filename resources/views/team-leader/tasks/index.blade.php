@extends('team-leader.layouts.app')

@section('title', 'Task Management')
@section('subtitle', $school->name)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <a href="{{ route('team-leader.tasks.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Create New Task
    </a>
</div>

<div class="filter-section">
    <form action="{{ route('team-leader.tasks') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; width: 100%;">
        <input type="text" name="search" placeholder="Search tasks..." value="{{ request('search') }}" style="width: 200px;">
        <select name="status">
            <option value="all">All Status</option>
            <option value="Pending" @if(request('status') === 'Pending') selected @endif>Pending</option>
            <option value="In Progress" @if(request('status') === 'In Progress') selected @endif>In Progress</option>
            <option value="Completed" @if(request('status') === 'Completed') selected @endif>Completed</option>
            <option value="On Hold" @if(request('status') === 'On Hold') selected @endif>On Hold</option>
        </select>
        <select name="priority">
            <option value="all">All Priority</option>
            <option value="Low" @if(request('priority') === 'Low') selected @endif>Low</option>
            <option value="Medium" @if(request('priority') === 'Medium') selected @endif>Medium</option>
            <option value="High" @if(request('priority') === 'High') selected @endif>High</option>
            <option value="Urgent" @if(request('priority') === 'Urgent') selected @endif>Urgent</option>
        </select>
        <select name="intern_id">
            <option value="">All Interns</option>
            @foreach($interns as $intern)
                <option value="{{ $intern->id }}" @if(request('intern_id') == $intern->id) selected @endif>{{ $intern->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-search"></i> Filter
        </button>
        @if(request()->hasAny(['search', 'status', 'priority', 'intern_id']))
            <a href="{{ route('team-leader.tasks') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i> Clear
            </a>
        @endif
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tasks ({{ $tasks->total() }})</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Assigned To</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    @php
                        $isOverdue = $task->isOverdue();
                    @endphp
                    <tr style="{{ $isOverdue ? 'background: #FEF2F2;' : '' }}">
                        <td>
                            <div style="font-weight: 600;">{{ $task->title }}</div>
                            <div style="font-size: 12px; color: #6B7280; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $task->description ?? 'No description' }}
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 600;">
                                    {{ strtoupper(substr($task->intern->name ?? 'N', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 500;">{{ $task->intern->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $task->priority === 'Low' ? 'success' : ($task->priority === 'Medium' ? 'warning' : ($task->priority === 'High' ? 'danger' : 'danger')) }}" 
                                  style="{{ $task->priority === 'Urgent' ? 'background: #DC2626; color: white;' : '' }}">
                                {{ $task->priority }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $task->status === 'Completed' ? 'success' : ($task->status === 'In Progress' ? 'info' : ($task->status === 'On Hold' ? 'warning' : 'warning')) }}">
                                {{ $task->status }}
                            </span>
                        </td>
                        <td style="width: 100px;">
                            <div class="progress-container">
                                <div class="progress-bar {{ $task->progress >= 75 ? 'green' : ($task->progress >= 50 ? 'yellow' : 'red') }}" style="width: {{ $task->progress }}%"></div>
                            </div>
                            <div style="text-align: center; font-size: 11px; color: #6B7280; margin-top: 4px;">{{ $task->progress }}%</div>
                        </td>
                        <td>
                            @if($task->due_date)
                                <div style="{{ $isOverdue ? 'color: #DC2626; font-weight: 600;' : '' }}">
                                    {{ $task->due_date->format('M d, Y') }}
                                </div>
                                @if($isOverdue)
                                    <div style="font-size: 11px; color: #DC2626;">Overdue</div>
                                @else
                                    <div style="font-size: 11px; color: #6B7280;">{{ $task->days_remaining }} days left</div>
                                @endif
                            @else
                                <span style="color: #6B7280;">No due date</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('team-leader.tasks.edit', $task) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('team-leader.tasks.destroy', $task) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 48px; color: #6B7280;">
                            <i class="fas fa-tasks" style="font-size: 48px; margin-bottom: 16px; display: block; color: #D1D5DB;"></i>
                            <h4 style="font-size: 18px; color: #4B5563; margin-bottom: 8px;">No tasks found</h4>
                            <p>Create your first task to get started.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">
    {{ $tasks->appends(request()->query())->links('pagination::simple-bootstrap-4') }}
</div>
@endsection
