<?php

namespace App\Http\Controllers;
use App\Models\Board;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of all boards
     */
    public function index()
    {
        $boards = Board::where('user_id', Auth::id())->get();
        return view('main.boards', compact('boards'));
    }

    /**
     * Display specific board view with tasks
     */
    public function show($boardId)
    {
        $greeting = $this->getGreeting();
        $currentDateTime = Carbon::now()->format('l, j F Y');

        // Get all boards for the current user
        $boards = Board::where('user_id', Auth::id())->get();

        // Get the current board
        $currentBoard = Board::where('user_id', Auth::id())->findOrFail($boardId);

        // Get tasks based on status for the current board
        $todoTasks = Task::where('user_id', Auth::id())
            ->where('board_id', $currentBoard->id)
            ->where('status', 'todo')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $inProgressTasks = Task::where('user_id', Auth::id())
            ->where('board_id', $currentBoard->id)
            ->where('status', 'in-progress')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $doneTasks = Task::where('user_id', Auth::id())
            ->where('board_id', $currentBoard->id)
            ->where('status', 'done')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Add category info to tasks
        $todoTasks = $this->addCategoryInfoToTasks($todoTasks);
        $inProgressTasks = $this->addCategoryInfoToTasks($inProgressTasks);
        $doneTasks = $this->addCategoryInfoToTasks($doneTasks);

        return view('main.board_detail', compact(
            'greeting',
            'currentDateTime',
            'boards',
            'currentBoard',
            'todoTasks',
            'inProgressTasks',
            'doneTasks'
        ));
    }

    /**
     * Store a new board
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $board = new Board();
        $board->name = $validated['name'];
        $board->description = $validated['description'] ?? null;
        $board->user_id = Auth::id();
        $board->save();

        return response()->json([
            'success' => true,
            'id' => $board->id,
            'message' => 'Board created successfully'
        ]);
    }

    /**
     * Update an existing board
     */
    public function update(Request $request, $id)
    {
        $board = Board::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $board->name = $validated['name'];
        $board->description = $validated['description'] ?? $board->description;
        $board->save();

        return response()->json([
            'success' => true,
            'id' => $board->id,
            'message' => 'Board updated successfully'
        ]);
    }

    /**
     * Delete a board
     */
    public function destroy(Request $request, $id)
    {
        $board = Board::where('user_id', Auth::id())->findOrFail($id);

        // Either delete associated tasks or make them un-assigned
        if ($request->has('delete_tasks') && $request->delete_tasks) {
            // Delete all tasks in this board
            Task::where('board_id', $board->id)->delete();
        } else {
            // Make tasks un-assigned
            Task::where('board_id', $board->id)->update(['board_id' => null]);
        }

        $board->delete();

        return response()->json(['success' => true, 'message' => 'Board deleted successfully']);
    }

    private function getGreeting()
    {
        $hour = date('H');
        if ($hour >= 5 && $hour < 12) {
            return 'Good Morning';
        } elseif ($hour >= 12 && $hour < 18) {
            return 'Good Afternoon';
        } else {
            return 'Good Evening';
        }
    }

    private function addCategoryInfoToTasks($tasks)
    {
        $categoryMap = [
            'work' => ['name' => 'Work', 'emoji' => '🛠️'],
            'personal' => ['name' => 'Personal', 'emoji' => '👤'],
            'education' => ['name' => 'Education', 'emoji' => '📚'],
            'health' => ['name' => 'Health', 'emoji' => '❤️'],
        ];

        foreach ($tasks as $task) {
            $categoryInfo = $categoryMap[$task->category] ?? ['name' => 'Other', 'emoji' => '📝'];
            $task->category_name = $categoryInfo['name'];
            $task->category_emoji = $categoryInfo['emoji'];
        }

        return $tasks;
    }
}
