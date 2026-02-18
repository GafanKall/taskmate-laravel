<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BoardRequest;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function index()
    {
        $boards = Auth::user()->boards()->latest()->get();
        return BoardResource::collection($boards);
    }

    public function store(BoardRequest $request)
    {
        $board = Auth::user()->boards()->create($request->validated());

        return new BoardResource($board);
    }

    public function show(Board $board)
    {
        $this->authorizeAccess($board);
        return new BoardResource($board);
    }

    public function update(BoardRequest $request, Board $board)
    {
        $this->authorizeAccess($board);
        $board->update($request->validated());

        return new BoardResource($board);
    }

    public function destroy(Board $board)
    {
        $this->authorizeAccess($board);
        $board->delete();

        return response()->json([
            'message' => 'Board deleted successfully'
        ]);
    }

    private function authorizeAccess(Board $board)
    {
        if ($board->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
