<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WeeklyScheduleRequest;
use App\Http\Resources\WeeklyScheduleResource;
use App\Models\WeeklySchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeeklyScheduleController extends Controller
{
    public function index()
    {
        $schedules = Auth::user()->weeklySchedules()->latest()->get();
        return WeeklyScheduleResource::collection($schedules);
    }

    public function store(WeeklyScheduleRequest $request)
    {
        $schedule = Auth::user()->weeklySchedules()->create($request->validated());

        return new WeeklyScheduleResource($schedule);
    }

    public function show(WeeklySchedule $weeklySchedule)
    {
        $this->authorizeAccess($weeklySchedule);
        return new WeeklyScheduleResource($weeklySchedule);
    }

    public function update(WeeklyScheduleRequest $request, WeeklySchedule $weeklySchedule)
    {
        $this->authorizeAccess($weeklySchedule);
        $weeklySchedule->update($request->validated());

        return new WeeklyScheduleResource($weeklySchedule);
    }

    public function destroy(WeeklySchedule $weeklySchedule)
    {
        $this->authorizeAccess($weeklySchedule);
        $weeklySchedule->delete();

        return response()->json([
            'message' => 'Schedule deleted successfully'
        ]);
    }

    private function authorizeAccess(WeeklySchedule $schedule)
    {
        if ($schedule->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
