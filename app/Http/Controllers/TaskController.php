<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|integer|between:0,3',
            'status' => 'required|string|in:todo,in-progress,done',
            'board_id' => 'nullable|exists:boards,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $task = new Task();
        $task->user_id = Auth::id();
        $task->board_id = $validated['board_id'] ?? null;
        $task->title = $validated['title'];
        $task->priority = $validated['priority'];
        $task->status = $validated['status'];
        $task->start_date = $validated['start_date'] ?? null;
        $task->end_date = $validated['end_date'] ?? null;
        
        if ($task->status === 'done') {
            $task->completed_at = now();
        }
        
        $task->save();

        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }

    public function edit($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        // Remove category mapping logic
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|integer|between:0,3',
            'status' => 'required|string|in:todo,in-progress,done',
            'board_id' => 'nullable|exists:boards,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $task->title = $validated['title'];
        // Remove category assignment
        $task->priority = $validated['priority'];
        $task->status = $validated['status'];
        $task->board_id = $validated['board_id'] ?? $task->board_id;
        $task->start_date = $validated['start_date'] ?? $task->start_date;
        $task->end_date = $validated['end_date'] ?? $task->end_date;

        if ($task->status === 'done' && !$task->completed_at) {
            $task->completed_at = now();
        } elseif ($task->status !== 'done') {
            $task->completed_at = null;
        }

        $task->save();

        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:todo,in-progress,done',
        ]);

        $task->status = $request->status;
        
        if ($task->status === 'done' && !$task->completed_at) {
            $task->completed_at = now();
        } elseif ($task->status !== 'done') {
            $task->completed_at = null;
        }

        $task->save();

        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }

    public function destroy($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }
}
