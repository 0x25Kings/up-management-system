<?php

namespace App\Http\Controllers;

use App\Models\RoomIssue;
use App\Models\StartupSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StartupController extends Controller
{
    /**
     * Show the startup portal
     */
    public function index()
    {
        return view('portals.startup');
    }

    /**
     * Submit a document
     */
    public function submitDocument(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'document_type' => 'required|string|max:100',
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
            'notes' => 'nullable|string|max:1000',
        ]);

        $file = $request->file('document');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('startup-documents', $filename, 'public');

        $submission = StartupSubmission::create([
            'tracking_code' => StartupSubmission::generateTrackingCode('document'),
            'company_name' => $validated['company_name'],
            'contact_person' => $validated['contact_person'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'type' => 'document',
            'document_type' => $validated['document_type'],
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('startup.portal')
            ->with('success', 'Document submitted successfully!')
            ->with('tracking_code', $submission->tracking_code);
    }

    /**
     * Submit a room issue report
     */
    public function submitRoomIssue(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'room_number' => 'required|string|max:50',
            'issue_type' => 'required|in:electrical,plumbing,aircon,internet,furniture,cleaning,security,other',
            'description' => 'required|string|max:2000',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('room-issues', $filename, 'public');
        }

        $issue = RoomIssue::create([
            'tracking_code' => RoomIssue::generateTrackingCode(),
            'company_name' => $validated['company_name'],
            'contact_person' => $validated['contact_person'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'room_number' => $validated['room_number'],
            'issue_type' => $validated['issue_type'],
            'description' => $validated['description'],
            'photo_path' => $photoPath,
            'priority' => $validated['priority'] ?? 'medium',
            'status' => 'pending',
        ]);

        return redirect()->route('startup.portal')
            ->with('success', 'Room issue reported successfully!')
            ->with('tracking_code', $issue->tracking_code);
    }

    /**
     * Submit an MOA request
     */
    public function submitMoa(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'moa_purpose' => 'required|string|max:255',
            'moa_details' => 'required|string|max:2000',
            'notes' => 'nullable|string|max:1000',
        ]);

        $submission = StartupSubmission::create([
            'tracking_code' => StartupSubmission::generateTrackingCode('moa'),
            'company_name' => $validated['company_name'],
            'contact_person' => $validated['contact_person'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'type' => 'moa',
            'moa_purpose' => $validated['moa_purpose'],
            'moa_details' => $validated['moa_details'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('startup.portal')
            ->with('success', 'MOA request submitted successfully!')
            ->with('tracking_code', $submission->tracking_code);
    }

    /**
     * Submit payment proof
     */
    public function submitPayment(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'invoice_number' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'payment_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        $file = $request->file('payment_proof');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('payment-proofs', $filename, 'public');

        $submission = StartupSubmission::create([
            'tracking_code' => StartupSubmission::generateTrackingCode('finance'),
            'company_name' => $validated['company_name'],
            'contact_person' => $validated['contact_person'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'type' => 'finance',
            'invoice_number' => $validated['invoice_number'],
            'amount' => $validated['amount'],
            'payment_proof_path' => $path,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('startup.portal')
            ->with('success', 'Payment proof submitted successfully!')
            ->with('tracking_code', $submission->tracking_code);
    }

    /**
     * Track a submission
     */
    public function track(Request $request)
    {
        $request->validate([
            'tracking_code' => 'required|string',
        ]);

        $trackingCode = strtoupper(trim($request->tracking_code));

        // Check if it's a room issue
        if (str_starts_with($trackingCode, 'ROOM-')) {
            $result = RoomIssue::where('tracking_code', $trackingCode)->first();
            if ($result) {
                return response()->json([
                    'success' => true,
                    'type' => 'room_issue',
                    'data' => [
                        'tracking_code' => $result->tracking_code,
                        'company_name' => $result->company_name,
                        'room_number' => $result->room_number,
                        'issue_type' => $result->issue_type_label,
                        'description' => $result->description,
                        'status' => $result->status,
                        'status_label' => $result->status_label,
                        'status_color' => $result->status_color,
                        'priority' => $result->priority,
                        'admin_notes' => $result->admin_notes,
                        'submitted_at' => $result->created_at->format('M d, Y h:i A'),
                        'resolved_at' => $result->resolved_at?->format('M d, Y h:i A'),
                    ],
                ]);
            }
        }

        // Check startup submissions
        $result = StartupSubmission::where('tracking_code', $trackingCode)->first();
        if ($result) {
            return response()->json([
                'success' => true,
                'type' => 'submission',
                'data' => [
                    'tracking_code' => $result->tracking_code,
                    'company_name' => $result->company_name,
                    'type' => $result->type,
                    'type_label' => $result->type_label,
                    'status' => $result->status,
                    'status_label' => $result->status_label,
                    'status_color' => $result->status_color,
                    'document_type' => $result->document_type,
                    'moa_purpose' => $result->moa_purpose,
                    'invoice_number' => $result->invoice_number,
                    'amount' => $result->amount,
                    'admin_notes' => $result->admin_notes,
                    'submitted_at' => $result->created_at->format('M d, Y h:i A'),
                    'reviewed_at' => $result->reviewed_at?->format('M d, Y h:i A'),
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No submission found with this tracking code.',
        ]);
    }

    /**
     * Download MOA template
     */
    public function downloadMoaTemplate()
    {
        // You can create a real MOA template file and store it in storage/app/public/templates/
        $templatePath = storage_path('app/public/templates/moa-template.pdf');
        
        if (!file_exists($templatePath)) {
            return redirect()->route('startup.portal')
                ->with('error', 'MOA template is not available yet. Please contact the admin.');
        }

        return response()->download($templatePath, 'MOA-Template.pdf');
    }
}
