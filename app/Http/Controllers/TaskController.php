<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return response()->json($tasks);
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'start_date' => 'nullable|date',
            'start_time' => 'nullable|string',
            'end_date' => 'nullable|date',
            'end_time' => 'nullable|string',
        ]);

        $task = new Task();
        $task->title = $validated['title'];
        $task->category = $validated['category'];
        $task->user_id = Auth::id();

        // Handle start datetime
        if (!empty($validated['start_date']) && !empty($validated['start_time'])) {
            $task->start_datetime = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
        }

        // Handle end datetime
        if (!empty($validated['end_date']) && !empty($validated['end_time'])) {
            $task->end_datetime = Carbon::parse($validated['end_date'] . ' ' . $validated['end_time']);
        }

        $task->save();

        return response()->json($task);
    }

    /**
     * Update task completion status.
     */
    public function toggleComplete($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->completed = !$task->completed;
        $task->save();

        return response()->json($task);
    }

    /**
     * Update task details.
     */
    public function update(Request $request, $id)
    {
        // Find the task that belongs to the authenticated user
        $task = Task::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'start_date' => 'nullable|date',
            'start_time' => 'nullable|string',
            'end_date' => 'nullable|date',
            'end_time' => 'nullable|string',
        ]);

        $task->title = $validated['title'];
        $task->category = $validated['category'];

        // Handle start datetime
        if (!empty($validated['start_date']) && !empty($validated['start_time'])) {
            $task->start_datetime = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
        } else {
            $task->start_datetime = null;
        }

        // Handle end datetime
        if (!empty($validated['end_date']) && !empty($validated['end_time'])) {
            $task->end_datetime = Carbon::parse($validated['end_date'] . ' ' . $validated['end_time']);
        } else {
            $task->end_datetime = null;
        }

        $task->save();

        return response()->json($task);
    }

    /**
     * Remove a task.
     */
    public function destroy($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }
    public function show($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);

        // Make sure to format dates for frontend
        return response()->json([
            'id' => $task->id,
            'title' => $task->title,
            'category' => $task->category,
            'completed' => (bool) $task->completed,
            'start_date' => $task->start_datetime ? $task->start_datetime->format('Y-m-d') : null,
            'start_time' => $task->start_datetime ? $task->start_datetime->format('H:i') : null,
            'end_date' => $task->end_datetime ? $task->end_datetime->format('Y-m-d') : null,
            'end_time' => $task->end_datetime ? $task->end_datetime->format('H:i') : null,
        ]);
    }

    public function completed()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->where('completed', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('main.completed')->with('tasks', $tasks);
        ;
    }

    public function personal()
    {
        $greeting = $this->getGreeting();
        $currentDateTime = Carbon::now()->format('l, j F Y');
        $personalTasks = Task::where('user_id', Auth::id())
            ->where('category', 'personal')
            ->orderBy('completed')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('personal', compact('greeting', 'currentDateTime', 'personalTasks'));
    }

}
