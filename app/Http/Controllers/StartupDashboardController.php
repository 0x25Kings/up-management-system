<?php

namespace App\Http\Controllers;

use App\Models\RoomIssue;
use App\Models\Startup;
use App\Models\StartupSubmission;
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

        $submissions = $query->latest()->paginate(10);

        return view('startup.submissions', compact('startup', 'submissions', 'type'));
    }

    /**
     * Show room issues
     */
    public function roomIssues()
    {
        $startup = $this->getStartup();
        $roomIssues = $startup->roomIssues()->latest()->paginate(10);

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
            'payment_date' => 'required|date',
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
            'payment_date.required' => 'Payment Date is required.',
            'payment_date.date' => 'Payment Date must be a valid date.',
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
            'payment_date' => $validated['payment_date'],
            'payment_proof_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

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

        return redirect()->route('startup.profile')
            ->with('success', 'Profile updated successfully!');
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
}
