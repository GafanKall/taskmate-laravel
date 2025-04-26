<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return view('main.event');
    }

    public function getEvents()
{
    $events = Event::where('user_id', auth()->id())->get();

    $formattedEvents = $events->map(function($event) {
        return [
            'id' => $event->id,
            'title' => $event->title,
            'start' => $event->start_date,
            'end' => $event->end_date,
            'backgroundColor' => $event->color, // Make sure this property is set
            'borderColor' => $event->color,     // Also set border color
            'textColor' => '#ffffff',           // Optional: for text contrast
            'allDay' => $event->all_day,
            'description' => $event->description,
            'category' => $event->category
        ];
    });

    return response()->json($formattedEvents);
}

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category' => 'nullable|string',
            'color' => 'nullable|string',
            'all_day' => 'boolean',
        ]);

        $event = Event::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'color' => $request->color ?? '#3788d8',
            'all_day' => $request->all_day ?? false,
            'category' => $request->category,
        ]);

        return response()->json($event);
    }

    public function update(Request $request, Event $event)
    {
        // Check ownership
        if ($event->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category' => 'nullable|string',
            'color' => 'nullable|string',
            'all_day' => 'boolean',
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'color' => $request->color ?? $event->color,
            'all_day' => $request->all_day ?? $event->all_day,
            'category' => $request->category,
        ]);

        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        // Check ownership
        if ($event->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $event->delete();
        return response()->json(['message' => 'Event deleted successfully']);
    }

    public function show(Event $event)
{
    // Check ownership
    if ($event->user_id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    return response()->json($event);
}
}
