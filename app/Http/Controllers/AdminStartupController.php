<?php

namespace App\Http\Controllers;

use App\Models\StartupSubmission;
use App\Models\StartupNotification;
use App\Models\StartupActivityLog;
use App\Models\Startup;
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
            'payment_start_date' => 'nullable|date',
            'payment_end_date' => 'nullable|date|after_or_equal:payment_start_date',
            'billing_amount' => 'nullable|numeric|min:0.01',
            'billing_duration' => 'nullable|in:monthly,quarterly,semi_annual,annual',
            'billing_start_date' => 'nullable|date',
            'moa_status' => 'nullable|in:none,pending,active,expired',
            'moa_expiry' => 'nullable|date',
        ]);

        $updateData = [
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ];

        // Add payment dates if provided (for MOA approvals)
        if ($request->has('payment_start_date')) {
            $updateData['payment_start_date'] = $request->payment_start_date;
        }
        if ($request->has('payment_end_date')) {
            $updateData['payment_end_date'] = $request->payment_end_date;
        }

        $submission->update($updateData);

        // Update startup MOA status if this is an MOA submission
        if ($submission->type === 'moa') {
            $startup = null;
            if ($submission->startup_id) {
                $startup = Startup::find($submission->startup_id);
            } else {
                // Try to match by company name or email for public portal submissions
                $startup = Startup::where('company_name', $submission->company_name)
                    ->orWhere('email', $submission->email)
                    ->first();
                if ($startup) {
                    $submission->update(['startup_id' => $startup->id]);
                }
            }

            if ($startup) {
                if ($request->status === 'approved') {
                    $moaStatus = $request->moa_status ?: 'active';
                    $startupUpdateData = ['moa_status' => $moaStatus];

                    // Handle billing schedule if provided
                    if ($request->billing_amount && $request->billing_duration && $request->billing_start_date) {
                        $billingStartDate = \Carbon\Carbon::parse($request->billing_start_date);
                        $dueDate = match($request->billing_duration) {
                            'monthly' => $billingStartDate->copy()->addMonth(),
                            'quarterly' => $billingStartDate->copy()->addMonths(3),
                            'semi_annual' => $billingStartDate->copy()->addMonths(6),
                            'annual' => $billingStartDate->copy()->addYear(),
                        };

                        $startupUpdateData['payment_amount'] = $request->billing_amount;
                        $startupUpdateData['payment_duration'] = $request->billing_duration;
                        $startupUpdateData['payment_start_date'] = $billingStartDate;
                        $startupUpdateData['payment_due_date'] = $dueDate;
                        $startupUpdateData['next_payment_due'] = $dueDate;
                        $startupUpdateData['payment_reminder_sent'] = false;

                        StartupActivityLog::log($startup->id, 'payment_schedule', 'Payment schedule set during MOA approval: ₱' . number_format($request->billing_amount, 2) . ' ' . $request->billing_duration);
                    }

                    // Handle MOA expiry if provided
                    if ($request->moa_expiry) {
                        $startupUpdateData['moa_expiry'] = $request->moa_expiry;
                        $startupUpdateData['moa_expiry_reminder_sent'] = false;

                        StartupActivityLog::log($startup->id, 'moa_expiry', 'MOA expiry date set during approval to ' . \Carbon\Carbon::parse($request->moa_expiry)->format('M d, Y') . ', status: ' . $moaStatus);
                    }

                    $startup->update($startupUpdateData);

                    $paymentInfo = '';
                    if ($request->billing_amount && $request->billing_duration) {
                        $paymentInfo = ' Billing: ₱' . number_format($request->billing_amount, 2) . ' (' . ucfirst(str_replace('_', '-', $request->billing_duration)) . ').';
                    } elseif ($request->payment_start_date) {
                        $paymentInfo = ' Payment period: ' . $request->payment_start_date . ' to ' . $request->payment_end_date;
                    }

                    $expiryInfo = '';
                    if ($request->moa_expiry) {
                        $expiryInfo = ' MOA expires: ' . \Carbon\Carbon::parse($request->moa_expiry)->format('M d, Y') . '.';
                    }

                    StartupNotification::notify(
                        $startup->id,
                        'moa_approved',
                        'MOA Approved',
                        'Your MOA request (' . $submission->tracking_code . ') has been approved.' . $paymentInfo . $expiryInfo,
                        route('startup.submissions', ['type' => 'moa']),
                        'fa-check-circle',
                        '#10B981'
                    );
                } elseif ($request->status === 'rejected') {
                    $startup->update(['moa_status' => 'none']);
                    StartupNotification::notify(
                        $startup->id,
                        'moa_rejected',
                        'MOA Request Rejected',
                        'Your MOA request (' . $submission->tracking_code . ') has been rejected. ' . ($request->admin_notes ?? ''),
                        route('startup.submissions', ['type' => 'moa']),
                        'fa-times-circle',
                        '#EF4444'
                    );
                }
            }
        }

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
                    'profile_photo_url' => $progress->startup->profile_photo ? asset('storage/' . $progress->startup->profile_photo) : null,
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

    /**
     * Get all MOA requests for admin management
     */
    public function getMoaRequests()
    {
        $moaRequests = StartupSubmission::where('type', 'moa')
            ->orderBy('created_at', 'desc')
            ->with(['moaUploader', 'startup'])
            ->get()
            ->map(function ($moa) {
                return [
                    'id' => $moa->id,
                    'startup_id' => $moa->startup_id,
                    'tracking_code' => $moa->tracking_code,
                    'company_name' => $moa->company_name,
                    'contact_person' => $moa->contact_person,
                    'email' => $moa->email,
                    'phone' => $moa->phone,
                    'moa_purpose' => $moa->moa_purpose,
                    'moa_duration' => $moa->moa_duration,
                    'moa_details' => $moa->moa_details,
                    'notes' => $moa->notes,
                    'status' => $moa->status,
                    'source' => $moa->startup_id ? 'startup' : 'portal',
                    'admin_notes' => $moa->admin_notes,
                    'admin_moa_document_path' => $moa->admin_moa_document_path,
                    'admin_moa_document_filename' => $moa->admin_moa_document_filename,
                    'admin_moa_uploaded_at' => $moa->admin_moa_uploaded_at ? $moa->admin_moa_uploaded_at->format('M d, Y h:i A') : null,
                    'admin_moa_uploaded_by' => $moa->moaUploader ? $moa->moaUploader->name : null,
                    'payment_start_date' => $moa->payment_start_date ? $moa->payment_start_date->format('Y-m-d') : null,
                    'payment_end_date' => $moa->payment_end_date ? $moa->payment_end_date->format('Y-m-d') : null,
                    'rejection_remarks' => $moa->rejection_remarks,
                    'created_at' => $moa->created_at->format('M d, Y h:i A'),
                    'created_at_iso' => $moa->created_at->toISOString(),
                    'reviewed_at' => $moa->reviewed_at ? $moa->reviewed_at->format('M d, Y h:i A') : null,
                    'profile_photo_url' => ($moa->startup && $moa->startup->profile_photo)
                        ? asset('storage/' . $moa->startup->profile_photo) : null,
                ];
            });

        return response()->json($moaRequests);
    }

    /**
     * Approve MOA request with document upload and payment dates
     */
    public function approveMoaRequest(Request $request, StartupSubmission $submission)
    {
        if ($submission->type !== 'moa') {
            return response()->json(['success' => false, 'message' => 'This submission is not an MOA request'], 400);
        }

        $request->validate([
            'moa_document' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'payment_start_date' => 'nullable|date',
            'payment_end_date' => 'nullable|date|after_or_equal:payment_start_date',
            'admin_notes' => 'nullable|string|max:1000',
            'billing_amount' => 'nullable|numeric|min:0.01',
            'billing_duration' => 'nullable|in:monthly,quarterly,semi_annual,annual',
            'billing_start_date' => 'nullable|date',
            'moa_status' => 'nullable|in:none,pending,active,expired',
            'moa_expiry' => 'nullable|date',
        ]);

        $updateData = [
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ];

        if ($request->payment_start_date) {
            $updateData['payment_start_date'] = $request->payment_start_date;
        }
        if ($request->payment_end_date) {
            $updateData['payment_end_date'] = $request->payment_end_date;
        }

        // Handle file upload
        if ($request->hasFile('moa_document')) {
            if ($submission->admin_moa_document_path) {
                Storage::disk(config('filesystems.upload_disk'))->delete($submission->admin_moa_document_path);
            }
            $file = $request->file('moa_document');
            $filename = $file->getClientOriginalName();
            $path = $file->store('moa-documents', config('filesystems.upload_disk'));

            $updateData['admin_moa_document_path'] = $path;
            $updateData['admin_moa_document_filename'] = $filename;
            $updateData['admin_moa_uploaded_at'] = now();
            $updateData['admin_moa_uploaded_by'] = Auth::id();
        }

        $submission->update($updateData);

        // Update startup MOA status
        $startup = null;
        if ($submission->startup_id) {
            $startup = Startup::find($submission->startup_id);
        } else {
            // Try to match by company name or email for public portal submissions
            $startup = Startup::where('company_name', $submission->company_name)
                ->orWhere('email', $submission->email)
                ->first();
            if ($startup) {
                // Link submission to the matched startup
                $submission->update(['startup_id' => $startup->id]);
            }
        }

        if ($startup) {
            $moaStatus = $request->moa_status ?: 'active';
            $startupUpdateData = ['moa_status' => $moaStatus];

            // Handle billing schedule if provided
            if ($request->billing_amount && $request->billing_duration && $request->billing_start_date) {
                $billingStartDate = \Carbon\Carbon::parse($request->billing_start_date);
                $dueDate = match($request->billing_duration) {
                    'monthly' => $billingStartDate->copy()->addMonth(),
                    'quarterly' => $billingStartDate->copy()->addMonths(3),
                    'semi_annual' => $billingStartDate->copy()->addMonths(6),
                    'annual' => $billingStartDate->copy()->addYear(),
                };

                $startupUpdateData['payment_amount'] = $request->billing_amount;
                $startupUpdateData['payment_duration'] = $request->billing_duration;
                $startupUpdateData['payment_start_date'] = $billingStartDate;
                $startupUpdateData['payment_due_date'] = $dueDate;
                $startupUpdateData['next_payment_due'] = $dueDate;
                $startupUpdateData['payment_reminder_sent'] = false;

                StartupActivityLog::log($startup->id, 'payment_schedule', 'Payment schedule set during MOA approval: ₱' . number_format($request->billing_amount, 2) . ' ' . $request->billing_duration);
            }

            // Handle MOA expiry if provided
            if ($request->moa_expiry) {
                $startupUpdateData['moa_expiry'] = $request->moa_expiry;
                $startupUpdateData['moa_expiry_reminder_sent'] = false;

                StartupActivityLog::log($startup->id, 'moa_expiry', 'MOA expiry date set during approval to ' . \Carbon\Carbon::parse($request->moa_expiry)->format('M d, Y') . ', status: ' . $moaStatus);
            }

            $startup->update($startupUpdateData);

            $paymentInfo = '';
            if ($request->billing_amount && $request->billing_duration) {
                $paymentInfo = ' Billing: ₱' . number_format($request->billing_amount, 2) . ' (' . ucfirst(str_replace('_', '-', $request->billing_duration)) . ').';
            } elseif ($request->payment_start_date && $request->payment_end_date) {
                $paymentInfo = ' Payment period: ' . $request->payment_start_date . ' to ' . $request->payment_end_date . '.';
            }

            $expiryInfo = '';
            if ($request->moa_expiry) {
                $expiryInfo = ' MOA expires: ' . \Carbon\Carbon::parse($request->moa_expiry)->format('M d, Y') . '.';
            }

            StartupNotification::notify(
                $startup->id,
                'moa_approved',
                'MOA Approved!',
                'Your MOA request (' . $submission->tracking_code . ') has been approved.' . $paymentInfo . $expiryInfo .
                ($request->hasFile('moa_document') ? ' The signed MOA document is now available for download.' : ''),
                route('startup.submissions', ['type' => 'moa']),
                'fa-check-circle',
                '#10B981'
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'MOA request approved successfully',
            'submission' => [
                'id' => $submission->id,
                'tracking_code' => $submission->tracking_code,
                'status' => 'approved',
            ]
        ]);
    }

    /**
     * Reject MOA request with remarks
     */
    public function rejectMoaRequest(Request $request, StartupSubmission $submission)
    {
        if ($submission->type !== 'moa') {
            return response()->json(['success' => false, 'message' => 'This submission is not an MOA request'], 400);
        }

        $request->validate([
            'rejection_remarks' => 'required|string|max:2000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $submission->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'rejection_remarks' => $request->rejection_remarks,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        // Update startup MOA status and notify
        $startup = null;
        if ($submission->startup_id) {
            $startup = Startup::find($submission->startup_id);
        } else {
            // Try to match by company name or email for public portal submissions
            $startup = Startup::where('company_name', $submission->company_name)
                ->orWhere('email', $submission->email)
                ->first();
            if ($startup) {
                $submission->update(['startup_id' => $startup->id]);
            }
        }

        if ($startup) {
            $startup->update(['moa_status' => 'none']);

            StartupNotification::notify(
                $startup->id,
                'moa_rejected',
                'MOA Request Rejected',
                'Your MOA request (' . $submission->tracking_code . ') has been rejected. Reason: ' . $request->rejection_remarks,
                route('startup.submissions', ['type' => 'moa']),
                'fa-times-circle',
                '#EF4444'
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'MOA request rejected',
            'submission' => [
                'id' => $submission->id,
                'tracking_code' => $submission->tracking_code,
                'status' => 'rejected',
            ]
        ]);
    }

    /**
     * Send MOA submission reminder to a startup
     */
    public function sendMoaReminder(Startup $startup)
    {
        StartupNotification::notify(
            $startup->id,
            'moa_reminder',
            'MOA Submission Reminder',
            'This is a reminder to submit your Memorandum of Agreement (MOA). Please submit your MOA as soon as possible to complete your incubation requirements.',
            route('startup.submit-moa'),
            'fa-file-signature',
            '#F59E0B'
        );

        return response()->json([
            'success' => true,
            'message' => 'MOA submission reminder sent to ' . $startup->company_name
        ]);
    }

    /**
     * Send payment reminder to a startup
     */
    public function sendPaymentReminder(Startup $startup)
    {
        StartupNotification::notify(
            $startup->id,
            'payment_reminder',
            'Payment Overdue Reminder',
            'Your payment is overdue. Please submit your payment proof as soon as possible to avoid any disruption to your incubation services.',
            route('startup.submit-payment'),
            'fa-exclamation-triangle',
            '#EF4444'
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment reminder sent to ' . $startup->company_name
        ]);
    }

    /**
     * Upload MOA document for a specific request
     */
    public function uploadMoaDocument(Request $request, StartupSubmission $submission)
    {
        // Validate that this is an MOA submission
        if ($submission->type !== 'moa') {
            return response()->json([
                'success' => false,
                'message' => 'This submission is not an MOA request'
            ], 400);
        }

        $request->validate([
            'moa_document' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
        ]);

        // Delete old document if exists
        if ($submission->admin_moa_document_path) {
            Storage::disk(config('filesystems.upload_disk'))->delete($submission->admin_moa_document_path);
        }

        // Store new document
        $file = $request->file('moa_document');
        $filename = $file->getClientOriginalName();
        $path = $file->store('moa-documents', config('filesystems.upload_disk'));

        // Update submission
        $submission->update([
            'admin_moa_document_path' => $path,
            'admin_moa_document_filename' => $filename,
            'admin_moa_uploaded_at' => now(),
            'admin_moa_uploaded_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'MOA document uploaded successfully',
            'submission' => [
                'id' => $submission->id,
                'tracking_code' => $submission->tracking_code,
                'admin_moa_document_filename' => $filename,
                'admin_moa_uploaded_at' => $submission->admin_moa_uploaded_at->format('M d, Y h:i A'),
                'admin_moa_uploaded_by' => Auth::user()->name,
            ]
        ]);
    }

    /**
     * Download MOA document for a specific request
     */
    public function downloadMoaDocument(StartupSubmission $submission)
    {
        // Validate that this is an MOA submission
        if ($submission->type !== 'moa') {
            return response()->json([
                'success' => false,
                'message' => 'This submission is not an MOA request'
            ], 400);
        }

        // Check if document exists
        if (!$submission->admin_moa_document_path) {
            return response()->json([
                'success' => false,
                'message' => 'No MOA document has been uploaded yet'
            ], 404);
        }

        // Check if file exists in storage
        if (!Storage::disk(config('filesystems.upload_disk'))->exists($submission->admin_moa_document_path)) {
            return response()->json([
                'success' => false,
                'message' => 'MOA document file not found'
            ], 404);
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk(config('filesystems.upload_disk'));
        return $disk->download(
            $submission->admin_moa_document_path,
            $submission->admin_moa_document_filename
        );
    }

    /**
     * Update payment schedule for a startup
     */
    public function updatePaymentSchedule(Request $request, Startup $startup)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_duration' => 'required|in:monthly,quarterly,semi_annual,annual',
            'payment_start_date' => 'required|date',
        ]);

        $startDate = \Carbon\Carbon::parse($request->payment_start_date);

        // Calculate due date based on duration
        $dueDate = match($request->payment_duration) {
            'monthly' => $startDate->copy()->addMonth(),
            'quarterly' => $startDate->copy()->addMonths(3),
            'semi_annual' => $startDate->copy()->addMonths(6),
            'annual' => $startDate->copy()->addYear(),
        };

        $startup->update([
            'payment_amount' => $request->payment_amount,
            'payment_duration' => $request->payment_duration,
            'payment_start_date' => $startDate,
            'payment_due_date' => $dueDate,
            'next_payment_due' => $dueDate,
            'payment_reminder_sent' => false,
        ]);

        // Notify startup
        StartupNotification::notify(
            $startup->id,
            'payment_schedule',
            'Payment Schedule Set',
            'Your payment schedule has been set. Amount: ₱' . number_format($request->payment_amount, 2) .
            ' (' . ucfirst(str_replace('_', '-', $request->payment_duration)) . '). Next payment due: ' . $dueDate->format('M d, Y') . '.',
            route('startup.submissions', ['type' => 'finance']),
            'fa-calendar-check',
            '#2563EB'
        );

        StartupActivityLog::log($startup->id, 'payment_schedule', 'Payment schedule set by admin: ₱' . number_format($request->payment_amount, 2) . ' ' . $request->payment_duration);

        return response()->json([
            'success' => true,
            'message' => 'Payment schedule updated for ' . $startup->company_name,
            'startup' => [
                'id' => $startup->id,
                'payment_amount' => $startup->payment_amount,
                'payment_duration' => $startup->payment_duration,
                'payment_start_date' => $startup->payment_start_date->format('Y-m-d'),
                'payment_due_date' => $startup->payment_due_date->format('Y-m-d'),
                'next_payment_due' => $startup->next_payment_due->format('Y-m-d'),
            ]
        ]);
    }

    /**
     * Update MOA expiry for a startup
     */
    public function updateMoaExpiry(Request $request, Startup $startup)
    {
        $request->validate([
            'moa_expiry' => 'required|date|after_or_equal:today',
            'moa_status' => 'sometimes|in:none,pending,active,expired',
        ]);

        $updateData = [
            'moa_expiry' => $request->moa_expiry,
            'moa_expiry_reminder_sent' => false,
        ];

        // Also update MOA status if provided
        if ($request->has('moa_status') && $request->moa_status) {
            $updateData['moa_status'] = $request->moa_status;
        }

        $startup->update($updateData);

        $expiryDate = \Carbon\Carbon::parse($request->moa_expiry);

        $statusMsg = '';
        if ($request->has('moa_status') && $request->moa_status) {
            $statusMsg = ' MOA status: ' . ucfirst($request->moa_status) . '.';
        }

        // Notify startup
        StartupNotification::notify(
            $startup->id,
            'moa_expiry_set',
            'MOA Settings Updated',
            'Your MOA expiry date has been set to ' . $expiryDate->format('F d, Y') . '.' . $statusMsg . ' Please ensure to renew before this date.',
            route('startup.submissions', ['type' => 'moa']),
            'fa-calendar-times',
            '#D97706'
        );

        StartupActivityLog::log($startup->id, 'moa_expiry', 'MOA expiry date set to ' . $expiryDate->format('M d, Y') . ($request->moa_status ? ', status: ' . $request->moa_status : ''));

        return response()->json([
            'success' => true,
            'message' => 'MOA settings updated for ' . $startup->company_name,
        ]);
    }

    /**
     * Send payment due reminder
     */
    public function sendPaymentDueReminder(Startup $startup)
    {
        $dueDate = $startup->next_payment_due ?? $startup->payment_due_date;
        $amount = $startup->payment_amount ? '₱' . number_format($startup->payment_amount, 2) : 'your scheduled payment';

        StartupNotification::notify(
            $startup->id,
            'payment_due_reminder',
            'Payment Due Reminder',
            'This is a reminder that your payment of ' . $amount . ' is due on ' .
            ($dueDate ? $dueDate->format('M d, Y') : 'soon') . '. Please submit your payment as soon as possible.',
            route('startup.submit-payment'),
            'fa-exclamation-circle',
            '#EF4444'
        );

        $startup->update(['payment_reminder_sent' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Payment due reminder sent to ' . $startup->company_name
        ]);
    }

    /**
     * Send MOA expiry reminder
     */
    public function sendMoaExpiryReminder(Startup $startup)
    {
        $expiryDate = $startup->moa_expiry;

        StartupNotification::notify(
            $startup->id,
            'moa_expiry_reminder',
            'MOA Expiring Soon',
            'Your Memorandum of Agreement is expiring on ' .
            ($expiryDate ? $expiryDate->format('F d, Y') : 'soon') . '. Please submit a renewal request to continue your incubation.',
            route('startup.request-moa'),
            'fa-clock',
            '#D97706'
        );

        $startup->update(['moa_expiry_reminder_sent' => true]);

        return response()->json([
            'success' => true,
            'message' => 'MOA expiry reminder sent to ' . $startup->company_name
        ]);
    }

    /**
     * Get incubatee schedule data for the management tab
     */
    public function getIncubateeSchedules()
    {
        $startups = Startup::where('status', 'active')
            ->orderBy('company_name')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'company_name' => $s->company_name,
                    'contact_person' => $s->contact_person,
                    'email' => $s->email,
                    'room_number' => $s->room_number,
                    'moa_status' => $s->moa_status,
                    'moa_expiry' => $s->moa_expiry ? $s->moa_expiry->format('Y-m-d') : null,
                    'moa_expiry_formatted' => $s->moa_expiry ? $s->moa_expiry->format('M d, Y') : null,
                    'moa_days_remaining' => $s->moa_expiry ? (int) now()->diffInDays($s->moa_expiry, false) : null,
                    'payment_amount' => $s->payment_amount,
                    'payment_duration' => $s->payment_duration,
                    'payment_start_date' => $s->payment_start_date ? $s->payment_start_date->format('Y-m-d') : null,
                    'payment_due_date' => $s->payment_due_date ? $s->payment_due_date->format('Y-m-d') : null,
                    'next_payment_due' => $s->next_payment_due ? $s->next_payment_due->format('Y-m-d') : null,
                    'next_payment_due_formatted' => $s->next_payment_due ? $s->next_payment_due->format('M d, Y') : null,
                    'payment_days_remaining' => $s->next_payment_due ? (int) now()->diffInDays($s->next_payment_due, false) : null,
                ];
            });

        return response()->json($startups);
    }
}
