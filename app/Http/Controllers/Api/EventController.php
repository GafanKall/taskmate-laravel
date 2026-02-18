<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Auth::user()->events()->latest()->get();
        return EventResource::collection($events);
    }

    public function store(EventRequest $request)
    {
        $event = Auth::user()->events()->create($request->validated());

        return new EventResource($event);
    }

    public function show(Event $event)
    {
        $this->authorizeAccess($event);
        return new EventResource($event);
    }

    public function update(EventRequest $request, Event $event)
    {
        $this->authorizeAccess($event);
        $event->update($request->validated());

        return new EventResource($event);
    }

    public function destroy(Event $event)
    {
        $this->authorizeAccess($event);
        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully'
        ]);
    }

    private function authorizeAccess(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
