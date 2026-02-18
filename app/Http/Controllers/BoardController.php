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
    private $categoryMap = [
        'work' => ['name' => 'Work', 'emoji' => '🛠️'],
        'personal' => ['name' => 'Personal', 'emoji' => '👤'],
        'education' => ['name' => 'Education', 'emoji' => '📚'],
        'health' => ['name' => 'Health', 'emoji' => '❤️'],
    ];

    /**
     * Display specific board view with tasks
     */
    public function show($boardId)
    {
        $greeting = $this->getGreeting();
        $currentDateTime = Carbon::now()->format('l, j F Y');
        $userId = Auth::id();

        // Get all boards for the current user
        $boards = Board::where('user_id', $userId)->get();

        // Get the current board
        $currentBoard = Board::where('user_id', $userId)->findOrFail($boardId);

        // Get tasks based on status for the current board
        $todoTasks = $this->getBoardTasksByStatus($currentBoard->id, 'todo');
        $inProgressTasks = $this->getBoardTasksByStatus($currentBoard->id, 'in-progress');
        $doneTasks = $this->getBoardTasksByStatus($currentBoard->id, 'done');

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

    private function getBoardTasksByStatus($boardId, $status)
    {
        $tasks = Task::where('user_id', Auth::id())
            ->where('board_id', $boardId)
            ->where('status', $status)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->addCategoryInfoToTasks($tasks);
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

        $board = Board::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'user_id' => Auth::id(),
        ]);

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

        $board->update($validated);

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

        if ($request->boolean('delete_tasks')) {
            Task::where('board_id', $board->id)->delete();
        } else {
            Task::where('board_id', $board->id)->update(['board_id' => null]);
        }

        $board->delete();

        return response()->json(['success' => true, 'message' => 'Board deleted successfully']);
    }

    private function getGreeting()
    {
        $hour = date('H');
        if ($hour >= 5 && $hour < 12) return 'Good Morning';
        if ($hour >= 12 && $hour < 18) return 'Good Afternoon';
        return 'Good Evening';
    }

    private function addCategoryInfoToTasks($tasks)
    {
        foreach ($tasks as $task) {
            $categoryInfo = $this->categoryMap[$task->category] ?? ['name' => 'Other', 'emoji' => '📝'];
            $task->category_name = $categoryInfo['name'];
            $task->category_emoji = $categoryInfo['emoji'];
        }

        return $tasks;
    }
}
