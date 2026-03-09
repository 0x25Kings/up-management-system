@extends('team-leader.layouts.app')

@section('title', 'Create Task')
@section('subtitle', 'Assign a new task to an intern')

@section('styles')
<style>
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 14px; }
    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    .form-input:focus {
        outline: none;
        border-color: #3B82F6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .form-textarea {
        min-height: 120px;
        resize: vertical;
    }
    .form-error { color: #DC2626; font-size: 12px; margin-top: 4px; }
    .form-hint { color: #6B7280; font-size: 12px; margin-top: 4px; }
    .required { color: #DC2626; }
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('team-leader.tasks') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Tasks
    </a>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Create New Task</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('team-leader.tasks.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Assign To <span class="required">*</span></label>
                <select name="intern_id" class="form-input" required>
                    <option value="">Select an intern</option>
                    @foreach($interns as $intern)
                        <option value="{{ $intern->id }}" @if(old('intern_id', request('intern_id')) == $intern->id) selected @endif>
                            {{ $intern->name }} - {{ $intern->course }}
                        </option>
                    @endforeach
                </select>
                @error('intern_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Task Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-input" value="{{ old('title') }}" placeholder="Enter task title" required>
                @error('title')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input form-textarea" placeholder="Describe the task in detail">{{ old('description') }}</textarea>
                @error('description')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Requirements</label>
                <textarea name="requirements" class="form-input form-textarea" placeholder="List any specific requirements or deliverables">{{ old('requirements') }}</textarea>
                <p class="form-hint">Specify what needs to be delivered for this task.</p>
                @error('requirements')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Priority <span class="required">*</span></label>
                    <select name="priority" class="form-input" required>
                        <option value="Low" @if(old('priority') === 'Low') selected @endif>Low</option>
                        <option value="Medium" @if(old('priority', 'Medium') === 'Medium') selected @endif>Medium</option>
                        <option value="High" @if(old('priority') === 'High') selected @endif>High</option>
                        <option value="Urgent" @if(old('priority') === 'Urgent') selected @endif>Urgent</option>
                    </select>
                    @error('priority')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Due Date <span class="required">*</span></label>
                    <input type="date" name="due_date" class="form-input" value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}" required>
                    @error('due_date')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 32px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Task
                </button>
                <a href="{{ route('team-leader.tasks') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
