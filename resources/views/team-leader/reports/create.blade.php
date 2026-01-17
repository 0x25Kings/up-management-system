@extends('team-leader.layouts.app')

@section('title', 'Create Report')
@section('subtitle', 'Submit a report to admin')

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

    .stats-summary {
        background: #F9FAFB;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 24px;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    .stat-item {
        text-align: center;
        padding: 16px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    .stat-item-value {
        font-size: 28px;
        font-weight: 700;
        color: #1F2937;
    }
    .stat-item-label {
        font-size: 12px;
        color: #6B7280;
        margin-top: 4px;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('team-leader.reports') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
</div>

<!-- Current Stats Summary -->
<div class="stats-summary">
    <h4 style="font-weight: 600; color: #1F2937; margin-bottom: 16px;">
        <i class="fas fa-chart-bar" style="color: #3B82F6;"></i> Current Team Statistics
    </h4>
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-item-value">{{ $interns->count() }}</div>
            <div class="stat-item-label">Total Interns</div>
        </div>
        <div class="stat-item">
            <div class="stat-item-value" style="color: #10B981;">{{ $taskStats['completed'] }}</div>
            <div class="stat-item-label">Completed Tasks</div>
        </div>
        <div class="stat-item">
            <div class="stat-item-value" style="color: #3B82F6;">{{ $taskStats['in_progress'] }}</div>
            <div class="stat-item-label">In Progress</div>
        </div>
        <div class="stat-item">
            <div class="stat-item-value" style="color: #F59E0B;">{{ $taskStats['pending'] }}</div>
            <div class="stat-item-label">Pending Tasks</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create New Report</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('team-leader.reports.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Report Title <span class="required">*</span></label>
                    <input type="text" name="title" class="form-input" value="{{ old('title') }}" placeholder="e.g., Weekly Progress Report - Week 3" required>
                    @error('title')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Report Type <span class="required">*</span></label>
                    <select name="report_type" class="form-input" required>
                        @foreach($reportTypes as $value => $label)
                            <option value="{{ $value }}" @if(old('report_type', 'weekly') === $value) selected @endif>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('report_type')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Period Start</label>
                    <input type="date" name="period_start" class="form-input" value="{{ old('period_start') }}">
                    @error('period_start')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Period End</label>
                    <input type="date" name="period_end" class="form-input" value="{{ old('period_end') }}">
                    @error('period_end')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Summary <span class="required">*</span></label>
                <textarea name="summary" class="form-input form-textarea" placeholder="Provide a brief summary of this reporting period" required>{{ old('summary') }}</textarea>
                @error('summary')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Accomplishments</label>
                <textarea name="accomplishments" class="form-input form-textarea" placeholder="List the accomplishments achieved during this period">{{ old('accomplishments') }}</textarea>
                <p class="form-hint">What tasks were completed? What milestones were reached?</p>
                @error('accomplishments')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Challenges / Issues</label>
                <textarea name="challenges" class="form-input form-textarea" placeholder="Describe any challenges or issues encountered">{{ old('challenges') }}</textarea>
                <p class="form-hint">Any blockers, delays, or problems that need attention?</p>
                @error('challenges')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Recommendations</label>
                <textarea name="recommendations" class="form-input form-textarea" placeholder="Provide any recommendations or suggestions">{{ old('recommendations') }}</textarea>
                @error('recommendations')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <hr style="margin: 32px 0; border: none; border-top: 1px solid #E5E7EB;">

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <a href="{{ route('team-leader.reports') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="status" value="draft" class="btn btn-secondary">
                    <i class="fas fa-save"></i> Save as Draft
                </button>
                <button type="submit" name="status" value="submitted" class="btn btn-success">
                    <i class="fas fa-paper-plane"></i> Submit to Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
