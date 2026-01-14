<?php

namespace App\Http\Controllers;

use App\Models\BlockedDate;
use Illuminate\Http\Request;

class BlockedDateController extends Controller
{
    /**
     * Get all blocked dates
     */
    public function index()
    {
        $blockedDates = BlockedDate::orderBy('blocked_date', 'asc')
            ->get()
            ->map(function ($blocked) {
                return [
                    'id' => $blocked->id,
                    'date' => $blocked->blocked_date->format('Y-m-d'),
                    'reason' => $blocked->reason,
                    'reason_label' => $blocked->reason_label,
                    'reason_color' => $blocked->reason_color,
                    'description' => $blocked->description,
                ];
            });

        return response()->json($blockedDates);
    }

    /**
     * Store a new blocked date
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'blocked_date' => 'required|date',
            'reason' => 'required|in:unavailable,no_work,holiday,sick,maintenance,other',
            'description' => 'nullable|string|max:255',
        ]);

        // Check if date is already blocked
        if (BlockedDate::isBlocked($validated['blocked_date'])) {
            return response()->json([
                'success' => false,
                'message' => 'This date is already blocked.'
            ], 422);
        }

        $blockedDate = BlockedDate::create([
            'blocked_date' => $validated['blocked_date'],
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Date blocked successfully.',
            'blockedDate' => [
                'id' => $blockedDate->id,
                'date' => $blockedDate->blocked_date->format('Y-m-d'),
                'reason' => $blockedDate->reason,
                'reason_label' => $blockedDate->reason_label,
                'reason_color' => $blockedDate->reason_color,
                'description' => $blockedDate->description,
            ]
        ]);
    }

    /**
     * Remove a blocked date
     */
    public function destroy(BlockedDate $blockedDate)
    {
        $blockedDate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blocked date removed successfully.'
        ]);
    }

    /**
     * Check if a specific date is blocked
     */
    public function check($date)
    {
        $blocked = BlockedDate::getBlockedInfo($date);

        if ($blocked) {
            return response()->json([
                'blocked' => true,
                'reason' => $blocked->reason,
                'reason_label' => $blocked->reason_label,
                'description' => $blocked->description,
            ]);
        }

        return response()->json(['blocked' => false]);
    }
}
