<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->tasks()->latest();
        
        if ($request->has('board_id')) {
            $query->where('board_id', $request->board_id);
        }
        
        return TaskResource::collection($query->get());
    }

    public function store(TaskRequest $request)
    {
        $task = Auth::user()->tasks()->create($request->validated());

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        $this->authorizeAccess($task);
        return new TaskResource($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorizeAccess($task);
        $task->update($request->validated());

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $this->authorizeAccess($task);
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }

    private function authorizeAccess(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
