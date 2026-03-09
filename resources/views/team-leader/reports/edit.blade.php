@extends('team-leader.layouts.app')

@section('title', 'Edit Report')
@section('subtitle', $report->title)

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
    .required { color: #DC2626; }
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('team-leader.reports') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Report</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('team-leader.reports.update', $report) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Report Title <span class="required">*</span></label>
                    <input type="text" name="title" class="form-input" value="{{ old('title', $report->title) }}" required>
                    @error('title')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Report Type <span class="required">*</span></label>
                    <select name="report_type" class="form-input" required>
                        @foreach($reportTypes as $value => $label)
                            <option value="{{ $value }}" @if(old('report_type', $report->report_type) === $value) selected @endif>{{ $label }}</option>
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
                    <input type="date" name="period_start" class="form-input" value="{{ old('period_start', $report->period_start?->format('Y-m-d')) }}">
                    @error('period_start')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Period End</label>
                    <input type="date" name="period_end" class="form-input" value="{{ old('period_end', $report->period_end?->format('Y-m-d')) }}">
                    @error('period_end')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Summary <span class="required">*</span></label>
                <textarea name="summary" class="form-input form-textarea" required>{{ old('summary', $report->summary) }}</textarea>
                @error('summary')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Accomplishments</label>
                <textarea name="accomplishments" class="form-input form-textarea">{{ old('accomplishments', $report->accomplishments) }}</textarea>
                @error('accomplishments')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Challenges / Issues</label>
                <textarea name="challenges" class="form-input form-textarea">{{ old('challenges', $report->challenges) }}</textarea>
                @error('challenges')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Recommendations</label>
                <textarea name="recommendations" class="form-input form-textarea">{{ old('recommendations', $report->recommendations) }}</textarea>
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
