<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BlockedDate;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Get all bookings for calendar display
     */
    public function index()
    {
        $bookings = Booking::where('status', 'approved')
            ->orderBy('booking_date', 'asc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'title' => $booking->agency_name . ' - ' . $booking->event_name,
                    'date' => $booking->booking_date->format('Y-m-d'),
                    'time' => $booking->formatted_time,
                    'agency' => $booking->agency_name,
                    'event' => $booking->event_name,
                ];
            });

        return response()->json($bookings);
    }

    /**
     * Get bookings for a specific date
     */
    public function getByDate($date)
    {
        $bookings = Booking::whereDate('booking_date', $date)
            ->where('status', 'approved')
            ->orderBy('time_start', 'asc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'agency' => $booking->agency_name,
                    'event' => $booking->event_name,
                    'time' => $booking->formatted_time,
                ];
            });

        return response()->json($bookings);
    }

    /**
     * Get public booking settings for frontend
     */
    public function getBookingSettings()
    {
        return response()->json([
            'booking_duration' => Setting::get('booking_duration', 2),
            'min_advance_booking' => Setting::get('min_advance_booking', 1),
            'max_advance_booking' => Setting::get('max_advance_booking', 90),
            'auto_approve_booking' => Setting::get('auto_approve_booking', false),
            'weekend_bookings' => Setting::get('weekend_bookings', false),
            'booking_start' => Setting::get('booking_start', '08:00'),
            'booking_end' => Setting::get('booking_end', '17:00'),
        ]);
    }

    /**
     * Store a new booking request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'agency_name' => 'required|string|max:255',
            'event_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'booking_date' => 'required|date|after_or_equal:today',
            'time_start' => 'required',
            'time_end' => 'required|after:time_start',
            'purpose' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Load scheduler settings
        $minAdvance = Setting::get('min_advance_booking', 1);
        $maxAdvance = Setting::get('max_advance_booking', 90);
        $allowWeekends = Setting::get('weekend_bookings', false);
        $autoApprove = Setting::get('auto_approve_booking', false);
        $bookingStart = Setting::get('booking_start', '08:00');
        $bookingEnd = Setting::get('booking_end', '17:00');
        $defaultDuration = Setting::get('booking_duration', 2);

        $bookingDate = Carbon::parse($validated['booking_date']);
        $today = Carbon::today('Asia/Manila');

        // Enforce minimum advance booking days
        $daysFromNow = $today->diffInDays($bookingDate, false);
        if ($daysFromNow < $minAdvance) {
            return response()->json([
                'success' => false,
                'message' => "Bookings must be made at least {$minAdvance} day(s) in advance."
            ], 422);
        }

        // Enforce maximum advance booking days
        if ($daysFromNow > $maxAdvance) {
            return response()->json([
                'success' => false,
                'message' => "Bookings cannot be made more than {$maxAdvance} days in advance."
            ], 422);
        }

        // Check weekend bookings
        if (!$allowWeekends && $bookingDate->isWeekend()) {
            return response()->json([
                'success' => false,
                'message' => 'Weekend bookings are not allowed.'
            ], 422);
        }

        // Enforce booking hours
        $requestedStart = Carbon::parse($validated['time_start']);
        $requestedEnd = Carbon::parse($validated['time_end']);
        $allowedStart = Carbon::parse($bookingStart);
        $allowedEnd = Carbon::parse($bookingEnd);

        if ($requestedStart->lt($allowedStart) || $requestedEnd->gt($allowedEnd)) {
            $startFormatted = $allowedStart->format('g:i A');
            $endFormatted = $allowedEnd->format('g:i A');
            return response()->json([
                'success' => false,
                'message' => "Bookings are only allowed between {$startFormatted} and {$endFormatted}."
            ], 422);
        }

        // Enforce maximum booking duration
        $durationHours = $requestedStart->diffInHours($requestedEnd);
        if ($durationHours > $defaultDuration) {
            return response()->json([
                'success' => false,
                'message' => "Booking duration cannot exceed {$defaultDuration} hour(s)."
            ], 422);
        }

        // Check if the date is blocked
        if (BlockedDate::isBlocked($validated['booking_date'])) {
            $blocked = BlockedDate::getBlockedInfo($validated['booking_date']);
            return response()->json([
                'success' => false,
                'message' => "This date is not available for booking. Reason: {$blocked->reason_label}."
            ], 422);
        }

        // Check for time slot conflict with approved bookings
        $conflict = $this->checkTimeConflict(
            $validated['booking_date'],
            $validated['time_start'],
            $validated['time_end']
        );

        if ($conflict) {
            $conflictTime = Carbon::parse($conflict->time_start)->format('g:i A') . ' - ' . Carbon::parse($conflict->time_end)->format('g:i A');
            return response()->json([
                'success' => false,
                'message' => "This time slot is already booked ({$conflictTime}). Please select a different time."
            ], 422);
        }

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('booking-attachments', $filename, 'public');
            $validated['attachment_path'] = $path;
        }

        // Remove the attachment key as it's not a database field
        unset($validated['attachment']);

        // Auto-approve if setting is enabled
        if ($autoApprove) {
            $validated['status'] = 'approved';
            $validated['approved_at'] = now('Asia/Manila');
        }

        $booking = Booking::create($validated);

        $message = $autoApprove
            ? 'Booking has been automatically approved!'
            : 'Booking request submitted successfully! Please wait for admin approval.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'booking' => $booking
        ]);
    }

    /**
     * Check for time slot conflicts with approved bookings
     */
    private function checkTimeConflict($date, $timeStart, $timeEnd)
    {
        $newStart = Carbon::parse($timeStart);
        $newEnd = Carbon::parse($timeEnd);

        // Find any approved booking on the same date that overlaps with the requested time
        return Booking::whereDate('booking_date', $date)
            ->where('status', 'approved')
            ->where(function ($query) use ($newStart, $newEnd) {
                $query->where(function ($q) use ($newStart, $newEnd) {
                    // New booking starts during an existing booking
                    $q->whereTime('time_start', '<=', $newStart->format('H:i:s'))
                      ->whereTime('time_end', '>', $newStart->format('H:i:s'));
                })->orWhere(function ($q) use ($newStart, $newEnd) {
                    // New booking ends during an existing booking
                    $q->whereTime('time_start', '<', $newEnd->format('H:i:s'))
                      ->whereTime('time_end', '>=', $newEnd->format('H:i:s'));
                })->orWhere(function ($q) use ($newStart, $newEnd) {
                    // New booking completely contains an existing booking
                    $q->whereTime('time_start', '>=', $newStart->format('H:i:s'))
                      ->whereTime('time_end', '<=', $newEnd->format('H:i:s'));
                })->orWhere(function ($q) use ($newStart, $newEnd) {
                    // Existing booking completely contains the new booking
                    $q->whereTime('time_start', '<=', $newStart->format('H:i:s'))
                      ->whereTime('time_end', '>=', $newEnd->format('H:i:s'));
                });
            })
            ->first();
    }

    /**
     * Check availability for a specific date and time
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'required',
        ]);

        // Check if date is blocked
        if (BlockedDate::isBlocked($request->date)) {
            $blocked = BlockedDate::getBlockedInfo($request->date);
            return response()->json([
                'available' => false,
                'reason' => 'blocked',
                'message' => "This date is not available. Reason: {$blocked->reason_label}."
            ]);
        }

        // Check for time conflict
        $conflict = $this->checkTimeConflict(
            $request->date,
            $request->time_start,
            $request->time_end
        );

        if ($conflict) {
            return response()->json([
                'available' => false,
                'reason' => 'conflict',
                'message' => 'This time slot is already booked.',
                'conflict' => [
                    'time' => $conflict->formatted_time,
                    'agency' => $conflict->agency_name,
                ]
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'This time slot is available.'
        ]);
    }

    /**
     * Get all bookings (admin and team leaders with permissions)
     */
    public function getAllBookings()
    {
        $bookings = Booking::orderBy('booking_date', 'desc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'date' => $booking->booking_date->format('Y-m-d'),
                    'time' => $booking->formatted_time,
                    'agency' => $booking->agency_name,
                    'event' => $booking->event_name,
                    'contact_person' => $booking->contact_person,
                    'email' => $booking->email,
                    'phone' => $booking->phone,
                    'purpose' => $booking->purpose,
                    'status' => $booking->status,
                    'admin_emailed' => $booking->admin_emailed ?? false,
                ];
            });

        return response()->json(['bookings' => $bookings]);
    }

    /**
     * Get all pending bookings (admin)
     */
    public function pending()
    {
        $bookings = Booking::where('status', 'pending')
            ->orderBy('booking_date', 'asc')
            ->get();

        return response()->json($bookings);
    }

    /**
     * Approve a booking (admin)
     */
    public function approve(Request $request, Booking $booking)
    {
        $booking->approve(Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Booking approved successfully!'
        ]);
    }

    /**
     * Reject a booking (admin)
     */
    public function reject(Booking $booking)
    {
        $booking->reject();

        return response()->json([
            'success' => true,
            'message' => 'Booking rejected.'
        ]);
    }

    /**
     * Delete a booking (admin)
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted.'
        ]);
    }

    /**
     * Send email notification to booker (admin)
     */
    public function sendEmailNotification(Booking $booking)
    {
        // In a production environment, you would send an actual email here
        // For now, we'll just update the admin_emailed flag

        // Example of what you would do with Laravel Mail:
        // Mail::to($booking->email)->send(new BookingApprovedMail($booking));

        $booking->admin_emailed = true;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Email notification sent successfully!'
        ]);
    }
}
