<?php

namespace App\Http\Controllers;

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
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'completed' => false,
            'user_id' => Auth::id(),
        ]);

        return response()->json($task, 201);
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
        $task = Task::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);

        $task->update($validated);

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
        return response()->json($task);
    }

    public function completed()
{
    $tasks = Task::where('user_id', Auth::id())
                ->where('completed', true)
                ->orderBy('created_at', 'desc')
                ->get();

    return view('main.completed')->with('tasks', $tasks);;
}
}
