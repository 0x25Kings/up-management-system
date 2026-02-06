<?php

namespace App\Http\Controllers;

use App\Models\StartupSubmission;
use App\Models\RoomIssue;
use App\Models\StartupProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminStartupController extends Controller
{
    /**
     * Get all startup submissions for notifications
     */
    public function getSubmissions()
    {
        $submissions = StartupSubmission::orderBy('created_at', 'desc')
            ->get()
            ->map(function ($submission) {
                return [
                    'id' => $submission->id,
                    'tracking_code' => $submission->tracking_code,
                    'startup_name' => $submission->company_name,
                    'company_name' => $submission->company_name,
                    'type' => $submission->type,
                    'industry' => $submission->type ?? 'General',
                    'contact_person' => $submission->contact_person,
                    'email' => $submission->email,
                    'status' => $submission->status,
                    'created_at' => $submission->created_at->toISOString(),
                ];
            });

        return response()->json($submissions);
    }

    /**
     * Get all room issues for notifications
     */
    public function getRoomIssues()
    {
        $issues = RoomIssue::orderBy('created_at', 'desc')
            ->get()
            ->map(function ($issue) {
                return [
                    'id' => $issue->id,
                    'tracking_code' => $issue->tracking_code,
                    'room_location' => $issue->room_number ?? 'Unknown Room',
                    'category' => $issue->issue_type_label ?? $issue->issue_type,
                    'description' => $issue->description,
                    'company_name' => $issue->company_name,
                    'status' => $issue->status,
                    'priority' => $issue->priority,
                    'created_at' => $issue->created_at->toISOString(),
                ];
            });

        return response()->json($issues);
    }

    /**
     * Update a startup submission status (documents, MOA, payments)
     */
    public function updateSubmission(Request $request, StartupSubmission $submission)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $submission->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Submission updated successfully',
            'submission' => [
                'id' => $submission->id,
                'tracking_code' => $submission->tracking_code,
                'status' => $submission->status,
                'admin_notes' => $submission->admin_notes,
                'reviewed_at' => $submission->reviewed_at->format('M d, Y h:i A'),
            ]
        ]);
    }

    /**
     * Update document status via drag-and-drop (Kanban)
     */
    public function updateDocumentStatus(Request $request, StartupSubmission $submission)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,approved,rejected',
        ]);

        $submission->update([
            'status' => $request->status,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document status updated successfully',
            'submission' => [
                'id' => $submission->id,
                'status' => $submission->status,
            ]
        ]);
    }

    /**
     * Update a room issue status
     */
    public function updateRoomIssue(Request $request, RoomIssue $roomIssue)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'admin_notes' => 'nullable|string|max:1000',
            'assignee' => 'nullable|string|max:255',
        ]);

        $updateData = [
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ];

        // If resolving/closing, set resolved_at and resolved_by
        if (in_array($request->status, ['resolved', 'closed'])) {
            $updateData['resolved_at'] = now();
            $updateData['resolved_by'] = Auth::id();
        }

        $roomIssue->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Issue updated successfully',
            'issue' => [
                'id' => $roomIssue->id,
                'tracking_code' => $roomIssue->tracking_code,
                'status' => $roomIssue->status,
                'status_label' => $roomIssue->status_label,
                'admin_notes' => $roomIssue->admin_notes,
                'resolved_at' => $roomIssue->resolved_at ? $roomIssue->resolved_at->format('M d, Y h:i A') : null,
            ]
        ]);
    }

    /**
     * Get submission details
     */
    public function getSubmission(StartupSubmission $submission)
    {
        return response()->json([
            'success' => true,
            'submission' => [
                'id' => $submission->id,
                'tracking_code' => $submission->tracking_code,
                'company_name' => $submission->company_name,
                'contact_person' => $submission->contact_person,
                'email' => $submission->email,
                'phone' => $submission->phone,
                'type' => $submission->type,
                'document_type' => $submission->document_type,
                'file_path' => $submission->file_path,
                'original_filename' => $submission->original_filename,
                'moa_purpose' => $submission->moa_purpose,
                'moa_details' => $submission->moa_details,
                'invoice_number' => $submission->invoice_number,
                'amount' => $submission->amount,
                'payment_proof_path' => $submission->payment_proof_path,
                'notes' => $submission->notes,
                'status' => $submission->status,
                'admin_notes' => $submission->admin_notes,
                'created_at' => $submission->created_at->format('M d, Y h:i A'),
                'reviewed_at' => $submission->reviewed_at ? $submission->reviewed_at->format('M d, Y h:i A') : null,
            ]
        ]);
    }

    /**
     * Get room issue details
     */
    public function getRoomIssue(RoomIssue $roomIssue)
    {
        return response()->json([
            'success' => true,
            'issue' => [
                'id' => $roomIssue->id,
                'tracking_code' => $roomIssue->tracking_code,
                'company_name' => $roomIssue->company_name,
                'contact_person' => $roomIssue->contact_person,
                'email' => $roomIssue->email,
                'phone' => $roomIssue->phone,
                'room_number' => $roomIssue->room_number,
                'issue_type' => $roomIssue->issue_type,
                'issue_type_label' => $roomIssue->issue_type_label,
                'description' => $roomIssue->description,
                'photo_path' => $roomIssue->photo_path,
                'priority' => $roomIssue->priority,
                'status' => $roomIssue->status,
                'status_label' => $roomIssue->status_label,
                'admin_notes' => $roomIssue->admin_notes,
                'created_at' => $roomIssue->created_at->format('M d, Y h:i A'),
                'resolved_at' => $roomIssue->resolved_at ? $roomIssue->resolved_at->format('M d, Y h:i A') : null,
            ]
        ]);
    }

    /**
     * Delete a submission
     */
    public function deleteSubmission(StartupSubmission $submission)
    {
        $trackingCode = $submission->tracking_code;
        $submission->delete();

        return response()->json([
            'success' => true,
            'message' => "Submission {$trackingCode} deleted successfully"
        ]);
    }

    /**
     * Delete a room issue
     */
    public function deleteRoomIssue(RoomIssue $roomIssue)
    {
        $trackingCode = $roomIssue->tracking_code;
        $roomIssue->delete();

        return response()->json([
            'success' => true,
            'message' => "Issue {$trackingCode} deleted successfully"
        ]);
    }

    /**
     * Get a single progress update
     */
    public function getProgress(StartupProgress $progress)
    {
        $progress->load('startup');

        return response()->json([
            'success' => true,
            'progress' => [
                'id' => $progress->id,
                'title' => $progress->title,
                'description' => $progress->description,
                'milestone_type' => $progress->milestone_type,
                'milestone_type_label' => $progress->milestone_type_label,
                'status' => $progress->status,
                'file_path' => $progress->file_path,
                'file_url' => $progress->file_path ? Storage::url($progress->file_path) : null,
                'original_filename' => $progress->original_filename,
                'admin_comment' => $progress->admin_comment,
                'created_at' => $progress->created_at->format('M d, Y h:i A'),
                'reviewed_at' => $progress->reviewed_at ? $progress->reviewed_at->format('M d, Y h:i A') : null,
                'startup' => [
                    'id' => $progress->startup->id,
                    'company_name' => $progress->startup->company_name,
                    'startup_code' => $progress->startup->startup_code,
                ]
            ]
        ]);
    }

    /**
     * Respond to a project progress update
     */
    public function respondToProgress(Request $request, StartupProgress $progress)
    {
        $validated = $request->validate([
            'status' => 'required|in:submitted,reviewed,acknowledged',
            'admin_comment' => 'nullable|string|max:2000',
        ]);

        $progress->update([
            'status' => $validated['status'],
            'admin_comment' => $validated['admin_comment'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Response submitted successfully',
            'progress' => $progress
        ]);
    }
}
