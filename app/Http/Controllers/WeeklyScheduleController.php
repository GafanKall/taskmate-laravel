<?php

namespace App\Http\Controllers;

use App\Models\WeeklySchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeeklyScheduleController extends Controller
{
    /**
     * Display the weekly schedule view.
     */
    public function index()
    {
        $schedules = WeeklySchedule::where('user_id', Auth::id())
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $days = WeeklySchedule::getDaysOfWeek();

        // Group schedules by day
        $schedulesByDay = [];
        foreach ($days as $dayKey => $dayName) {
            $schedulesByDay[$dayKey] = $schedules->filter(function($schedule) use ($dayKey) {
                return $schedule->day_of_week === $dayKey;
            });
        }

        return view('main.weekly-schedule', [
            'schedulesByDay' => $schedulesByDay,
            'days' => $days
        ]);
    }

    /**
     * Store a new weekly schedule item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
        ]);

        $validated['user_id'] = Auth::id();

        $schedule = WeeklySchedule::create($validated);

        return response()->json([
            'success' => true,
            'schedule' => $schedule,
            'message' => 'Schedule created successfully'
        ]);
    }

    /**
     * Update a weekly schedule item.
     */
    public function update(Request $request, $id)
    {
        try {
            $schedule = WeeklySchedule::findOrFail($id);

            // Ensure the schedule belongs to the current user
            if ($schedule->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $validatedData = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'day_of_week' => 'sometimes|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
                'start_time' => 'sometimes|required|date_format:H:i',
                'end_time' => 'sometimes|nullable|date_format:H:i|after_or_equal:start_time',
            ]);

            $schedule->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Schedule updated successfully',
                'schedule' => $schedule
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a weekly schedule item.
     */
    public function destroy(WeeklySchedule $weeklySchedule)
    {
        // Check if the schedule belongs to the authenticated user
        if ($weeklySchedule->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $weeklySchedule->delete();

        return response()->json(['success' => true]);
    }
}
