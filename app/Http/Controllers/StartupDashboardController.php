<?php

namespace App\Http\Controllers;

use App\Models\RoomIssue;
use App\Models\Startup;
use App\Models\StartupProgress;
use App\Models\StartupSubmission;
use App\Models\StartupNotification;
use App\Models\StartupActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StartupDashboardController extends Controller
{
    /**
     * Get the current startup from session
     */
    protected function getStartup()
    {
        return Startup::findOrFail(session('startup_id'));
    }

    /**
     * Show the startup dashboard
     */
    public function index()
    {
        $startup = $this->getStartup();

        // Get counts and recent submissions
        $documentCount = $startup->submissions()->where('type', 'document')->count();
        $moaCount = $startup->submissions()->where('type', 'moa')->count();
        $paymentCount = $startup->submissions()->where('type', 'finance')->count();
        $roomIssueCount = $startup->roomIssues()->count();

        $recentSubmissions = $startup->submissions()
            ->latest()
            ->take(5)
            ->get();

        $recentRoomIssues = $startup->roomIssues()
            ->latest()
            ->take(5)
            ->get();

        $recentProgress = $startup->progressUpdates()
            ->latest()
            ->take(5)
            ->get();

        $pendingCount = $startup->submissions()->where('status', 'pending')->count()
            + $startup->roomIssues()->where('status', 'pending')->count();

        return view('startup.dashboard', compact(
            'startup',
            'documentCount',
            'moaCount',
            'paymentCount',
            'roomIssueCount',
            'recentSubmissions',
            'recentRoomIssues',
            'recentProgress',
            'pendingCount'
        ));
    }

    /**
     * Show all submissions
     */
    public function submissions(Request $request)
    {
        $startup = $this->getStartup();
        $type = $request->get('type');

        $query = $startup->submissions();

        if ($type && in_array($type, ['document', 'moa', 'finance'])) {
            $query->where('type', $type);
        }

        $submissions = $query->latest()->paginate(6);

        return view('startup.submissions', compact('startup', 'submissions', 'type'));
    }

    /**
     * Show room issues
     */
    public function roomIssues()
    {
        $startup = $this->getStartup();
        $roomIssues = $startup->roomIssues()->latest()->paginate(6);

        return view('startup.room-issues', compact('startup', 'roomIssues'));
    }

    /**
     * Show document upload form
     */
    public function showDocumentForm()
    {
        $startup = $this->getStartup();
        return view('startup.upload-document', compact('startup'));
    }

    /**
     * Submit a document
     */
    public function submitDocument(Request $request)
    {
        $startup = $this->getStartup();

        $validated = $request->validate([
            'document_type' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
            'notes' => 'nullable|string|max:1000',
        ]);

        $file = $request->file('document');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('startup-documents', $filename, 'public');

        $submission = StartupSubmission::create([
            'startup_id' => $startup->id,
            'tracking_code' => StartupSubmission::generateTrackingCode('document'),
            'company_name' => $startup->company_name,
            'contact_person' => $startup->contact_person,
            'email' => $startup->email,
            'phone' => $startup->phone,
            'type' => 'document',
            'document_type' => $validated['document_type'],
            'title' => $validated['title'],
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        StartupActivityLog::log($startup->id, 'document_submit', 'Submitted document: ' . $validated['title'] . ' (Type: ' . $validated['document_type'] . ')', ['tracking_code' => $submission->tracking_code]);

        return redirect()->route('startup.dashboard')
            ->with('success', 'Document submitted successfully! Tracking code: ' . $submission->tracking_code);
    }

    /**
     * Show room issue form
     */
    public function showRoomIssueForm()
    {
        $startup = $this->getStartup();
        return view('startup.report-issue', compact('startup'));
    }

    /**
     * Submit a room issue
     */
    public function submitRoomIssue(Request $request)
    {
        $startup = $this->getStartup();

        $validated = $request->validate([
            'room_number' => 'required|string|max:50',
            'issue_type' => 'required|in:electrical,plumbing,aircon,internet,furniture,cleaning,security,other',
            'description' => 'required|string|max:2000',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'priority' => 'nullable|in:low,medium,high,critical',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('room-issues', $filename, 'public');
        }

        $issue = RoomIssue::create([
            'startup_id' => $startup->id,
            'tracking_code' => RoomIssue::generateTrackingCode(),
            'company_name' => $startup->company_name,
            'contact_person' => $startup->contact_person,
            'email' => $startup->email,
            'phone' => $startup->phone,
            'room_number' => $validated['room_number'] ?: $startup->room_number,
            'issue_type' => $validated['issue_type'],
            'description' => $validated['description'],
            'photo_path' => $photoPath,
            'priority' => $validated['priority'] ?? 'medium',
            'status' => 'pending',
        ]);

        StartupActivityLog::log($startup->id, 'issue_submit', 'Reported room issue: ' . $validated['issue_type'] . ' in Room ' . $validated['room_number'], ['tracking_code' => $issue->tracking_code]);

        return redirect()->route('startup.dashboard')
            ->with('success', 'Room issue reported successfully! Tracking code: ' . $issue->tracking_code);
    }

    /**
     * Show MOA request form
     */
    public function showMoaForm()
    {
        $startup = $this->getStartup();
        return view('startup.request-moa', compact('startup'));
    }

    /**
     * Submit MOA request
     */
    public function submitMoa(Request $request)
    {
        $startup = $this->getStartup();

        $validated = $request->validate([
            'moa_purpose' => 'required|string|max:255',
            'moa_duration' => 'required|string|max:50',
            'moa_details' => 'required|string|max:2000',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $path = null;
        $originalFilename = null;

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('startup-moa', $filename, 'public');
            $originalFilename = $file->getClientOriginalName();
        }

        $submission = StartupSubmission::create([
            'startup_id' => $startup->id,
            'tracking_code' => StartupSubmission::generateTrackingCode('moa'),
            'company_name' => $startup->company_name,
            'contact_person' => $startup->contact_person,
            'email' => $startup->email,
            'phone' => $startup->phone,
            'type' => 'moa',
            'moa_purpose' => $validated['moa_purpose'],
            'moa_duration' => $validated['moa_duration'],
            'moa_details' => $validated['moa_details'],
            'file_path' => $path,
            'original_filename' => $originalFilename,
            'status' => 'pending',
        ]);

        // Update startup MOA status
        $startup->update(['moa_status' => 'pending']);

        StartupActivityLog::log($startup->id, 'moa_submit', 'Submitted MOA request: ' . $validated['moa_purpose'], ['tracking_code' => $submission->tracking_code]);

        return redirect()->route('startup.dashboard')
            ->with('success', 'MOA request submitted successfully! Tracking code: ' . $submission->tracking_code);
    }

    /**
     * Show payment submission form
     */
    public function showPaymentForm()
    {
        $startup = $this->getStartup();
        return view('startup.submit-payment', compact('startup'));
    }

    /**
     * Submit payment proof
     */
    public function submitPayment(Request $request)
    {
        $startup = $this->getStartup();

        $validated = $request->validate([
            'invoice_number' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:50',
            'payment_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'proof_verified' => 'required|accepted',
            'notes' => 'nullable|string|max:1000',
        ], [
            'invoice_number.required' => 'Reference Number is required. Please enter the transaction reference number from your receipt.',
            'invoice_number.max' => 'Reference Number must not exceed 100 characters.',
            'amount.required' => 'Amount Paid is required. Please enter the payment amount.',
            'amount.numeric' => 'Amount Paid must be a valid number.',
            'amount.min' => 'Amount Paid must be greater than zero.',
            'payment_method.required' => 'Payment Method is required. Please select how you made the payment.',
            'payment_proof.required' => 'Payment Proof is required. Please upload your receipt or transaction screenshot.',
            'payment_proof.file' => 'Payment Proof must be a valid file.',
            'payment_proof.mimes' => 'Payment Proof must be a PDF, JPG, JPEG, or PNG file.',
            'payment_proof.max' => 'Payment Proof must not exceed 5MB.',
            'proof_verified.required' => 'You must confirm that your payment proof matches the details entered.',
            'proof_verified.accepted' => 'You must confirm that your payment proof matches the details entered.',
        ]);

        $file = $request->file('payment_proof');
        
        // Check image dimensions for quality
        if (in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
            $imageInfo = getimagesize($file->getPathname());
            if ($imageInfo && ($imageInfo[0] < 300 || $imageInfo[1] < 200)) {
                return back()
                    ->withInput()
                    ->withErrors(['payment_proof' => 'The uploaded image is too small or low quality. Please upload a clearer image (minimum 300x200 pixels).']);
            }
        }
        
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('startup-payments', $filename, 'public');

        $submission = StartupSubmission::create([
            'startup_id' => $startup->id,
            'tracking_code' => StartupSubmission::generateTrackingCode('finance'),
            'company_name' => $startup->company_name,
            'contact_person' => $startup->contact_person,
            'email' => $startup->email,
            'phone' => $startup->phone,
            'type' => 'finance',
            'invoice_number' => $validated['invoice_number'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_date' => now(), // Automatically set to submission date/time
            'payment_proof_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        StartupActivityLog::log($startup->id, 'payment_submit', 'Submitted payment proof: ₱' . number_format($validated['amount'], 2) . ' via ' . $validated['payment_method'], ['tracking_code' => $submission->tracking_code, 'amount' => $validated['amount']]);

        return redirect()->route('startup.dashboard')
            ->with('success', 'Payment proof submitted successfully! Tracking code: ' . $submission->tracking_code);
    }

    /**
     * Show startup profile
     */
    public function profile()
    {
        $startup = $this->getStartup();
        return view('startup.profile', compact('startup'));
    }

    /**
     * Update startup profile
     */
    public function updateProfile(Request $request)
    {
        $startup = $this->getStartup();

        $validated = $request->validate([
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
        ]);

        $startup->update($validated);

        StartupActivityLog::log($startup->id, 'profile_update', 'Updated company profile information.');

        return redirect()->route('startup.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Upload startup profile photo
     */
    public function uploadProfilePhoto(Request $request)
    {
        $startup = $this->getStartup();

        $request->validate([
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Delete old photo if exists
        if ($startup->profile_photo && \Storage::disk('public')->exists($startup->profile_photo)) {
            \Storage::disk('public')->delete($startup->profile_photo);
        }

        $file = $request->file('profile_photo');
        $filename = 'startup_' . $startup->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('startup-photos', $filename, 'public');

        $startup->update(['profile_photo' => $path]);

        StartupActivityLog::log($startup->id, 'photo_upload', 'Updated profile photo.');

        return redirect()->route('startup.profile')
            ->with('success', 'Profile photo updated successfully!');
    }

    /**
     * Download MOA template
     */
    public function downloadMoaTemplate()
    {
        $templatePath = public_path('templates/moa-template.docx');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'MOA template not available. Please contact the administrator.');
        }

        return response()->download($templatePath, 'MOA-Template.docx');
    }

    /**
     * Show project progress page
     */
    public function progress()
    {
        $startup = $this->getStartup();
        $progressUpdates = $startup->progressUpdates()->latest()->paginate(10);

        return view('startup.progress', compact('startup', 'progressUpdates'));
    }

    /**
     * Submit project progress update
     */
    public function submitProgress(Request $request)
    {
        $startup = $this->getStartup();

        $validated = $request->validate([
            'milestone_type' => 'required|in:development,funding,partnership,launch,achievement,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
        ]);

        $path = null;
        $originalFilename = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('startup-progress', $filename, 'public');
            $originalFilename = $file->getClientOriginalName();
        }

        StartupProgress::create([
            'startup_id' => $startup->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'milestone_type' => $validated['milestone_type'],
            'file_path' => $path,
            'original_filename' => $originalFilename,
            'status' => 'submitted',
        ]);

        return redirect()->route('startup.progress')
            ->with('success', 'Project progress update submitted successfully!');
    }

    /**
     * Show track submissions page with timeline
     */
    public function trackSubmissions(Request $request)
    {
        $startup = $this->getStartup();
        
        // Get filter parameters
        $type = $request->get('type', 'all');
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        // Get submissions
        $submissionsQuery = $startup->submissions()->latest();
        if ($type !== 'all') {
            $submissionsQuery->where('type', $type);
        }
        if ($status !== 'all') {
            $submissionsQuery->where('status', $status);
        }
        if ($search) {
            $submissionsQuery->where(function ($q) use ($search) {
                $q->where('tracking_code', 'like', "%{$search}%")
                  ->orWhere('document_type', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhere('moa_purpose', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%");
            });
        }
        $submissions = $submissionsQuery->get();

        // Get room issues
        $roomIssuesQuery = $startup->roomIssues()->latest();
        if ($status !== 'all') {
            $roomIssuesQuery->where('status', $status);
        }
        if ($search) {
            $roomIssuesQuery->where(function ($q) use ($search) {
                $q->where('tracking_code', 'like', "%{$search}%")
                  ->orWhere('issue_type', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('room_number', 'like', "%{$search}%");
            });
        }
        $roomIssues = ($type === 'all' || $type === 'room_issue') ? $roomIssuesQuery->get() : collect();

        // Combine and sort by date
        $allItems = collect();
        
        foreach ($submissions as $submission) {
            $allItems->push([
                'type' => 'submission',
                'category' => $submission->type,
                'tracking_code' => $submission->tracking_code,
                'title' => $submission->type === 'document' ? $submission->document_type : 
                           ($submission->type === 'moa' ? 'MOA Request' : 'Payment Submission'),
                'description' => $submission->notes ?? $submission->moa_purpose ?? "Amount: ₱" . number_format($submission->amount ?? 0, 2),
                'status' => $submission->status,
                'status_label' => $submission->status_label,
                'status_color' => $submission->status_color,
                'admin_notes' => $submission->admin_notes,
                'created_at' => $submission->created_at,
                'reviewed_at' => $submission->reviewed_at,
            ]);
        }

        foreach ($roomIssues as $issue) {
            $allItems->push([
                'type' => 'room_issue',
                'category' => 'room_issue',
                'tracking_code' => $issue->tracking_code,
                'title' => $issue->issue_type_label . ' - Room ' . $issue->room_number,
                'description' => $issue->description,
                'status' => $issue->status,
                'status_label' => $issue->status_label,
                'status_color' => $issue->status_color,
                'admin_notes' => $issue->admin_notes,
                'created_at' => $issue->created_at,
                'resolved_at' => $issue->resolved_at,
            ]);
        }

        // Sort by created_at descending
        $allItems = $allItems->sortByDesc('created_at')->values();

        // Stats (calculated before pagination)
        $stats = [
            'total' => $allItems->count(),
            'pending' => $allItems->where('status', 'pending')->count(),
            'in_progress' => $allItems->whereIn('status', ['in_progress', 'under_review'])->count(),
            'approved' => $allItems->whereIn('status', ['approved', 'resolved'])->count(),
            'rejected' => $allItems->whereIn('status', ['rejected', 'cancelled'])->count(),
        ];

        // Manual pagination for combined collection
        $page = $request->get('page', 1);
        $perPage = 50;
        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $allItems->forPage($page, $perPage),
            $allItems->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('startup.track', compact('startup', 'paginatedItems', 'stats', 'type', 'status', 'search'));
    }

    /**
     * Show submission details with timeline
     */
    public function trackSubmissionDetails($trackingCode)
    {
        $startup = $this->getStartup();
        $trackingCode = strtoupper(trim($trackingCode));

        // Check if it's a room issue
        if (str_starts_with($trackingCode, 'ROOM-')) {
            $item = $startup->roomIssues()->where('tracking_code', $trackingCode)->first();
            $itemType = 'room_issue';
        } else {
            $item = $startup->submissions()->where('tracking_code', $trackingCode)->first();
            $itemType = 'submission';
        }

        if (!$item) {
            return redirect()->route('startup.track')->with('error', 'Submission not found.');
        }

        return view('startup.track-details', compact('startup', 'item', 'itemType'));
    }

    // ==========================================
    // NOTIFICATIONS
    // ==========================================

    /**
     * Show notifications page
     */
    public function notifications()
    {
        $startup = $this->getStartup();
        $notifications = $startup->notifications()->latest()->paginate(20);

        return view('startup.notifications', compact('startup', 'notifications'));
    }

    /**
     * Mark a notification as read
     */
    public function markNotificationRead($id)
    {
        $startup = $this->getStartup();
        $notification = $startup->notifications()->findOrFail($id);
        $notification->markAsRead();

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsRead()
    {
        $startup = $this->getStartup();
        $startup->notifications()->unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Get unread notification count (for AJAX)
     */
    public function unreadNotificationCount()
    {
        $startup = $this->getStartup();
        return response()->json([
            'count' => $startup->notifications()->unread()->count()
        ]);
    }

    // ==========================================
    // CHANGE PASSWORD
    // ==========================================

    /**
     * Show change password form
     */
    public function showChangePassword()
    {
        $startup = $this->getStartup();
        return view('startup.change-password', compact('startup'));
    }

    /**
     * Process password change
     */
    public function changePassword(Request $request)
    {
        $startup = $this->getStartup();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!$startup->checkPassword($request->current_password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $startup->update(['password' => $request->password]);

        StartupActivityLog::log($startup->id, 'password_change', 'Password changed successfully.');
        StartupNotification::notify(
            $startup->id, 'system', 'Password Changed',
            'Your password was changed successfully. If you did not make this change, please contact the administrator immediately.',
            null, 'fa-key', '#F59E0B'
        );

        return redirect()->route('startup.profile')->with('success', 'Password changed successfully!');
    }

    // ==========================================
    // MOA VIEWER
    // ==========================================

    /**
     * Show MOA documents viewer
     */
    public function moaViewer()
    {
        $startup = $this->getStartup();
        $moaSubmissions = $startup->submissions()
            ->where('type', 'moa')
            ->latest()
            ->get();

        return view('startup.moa-viewer', compact('startup', 'moaSubmissions'));
    }

    // ==========================================
    // BILLING / PAYMENT HISTORY
    // ==========================================

    /**
     * Show billing and payment history
     */
    public function billingHistory(Request $request)
    {
        $startup = $this->getStartup();

        $query = $startup->submissions()->where('type', 'finance')->latest();

        $statusFilter = $request->get('status', 'all');
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $payments = $query->paginate(20);

        return view('startup.billing', compact('startup', 'payments', 'statusFilter'));
    }

    // ==========================================
    // ACTIVITY LOG
    // ==========================================

    /**
     * Show activity log
     */
    public function activityLog()
    {
        $startup = $this->getStartup();
        $logs = $startup->activityLogs()->latest()->paginate(30);

        return view('startup.activity-log', compact('startup', 'logs'));
    }
}
