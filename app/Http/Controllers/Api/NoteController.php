<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\NoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Auth::user()->notes()->latest()->get();
        return NoteResource::collection($notes);
    }

    public function store(NoteRequest $request)
    {
        $note = Auth::user()->notes()->create($request->validated());

        return new NoteResource($note);
    }

    public function show(Note $note)
    {
        $this->authorizeAccess($note);
        return new NoteResource($note);
    }

    public function update(NoteRequest $request, Note $note)
    {
        $this->authorizeAccess($note);
        $note->update($request->validated());

        return new NoteResource($note);
    }

    public function destroy(Note $note)
    {
        $this->authorizeAccess($note);
        $note->delete();

        return response()->json([
            'message' => 'Note deleted successfully'
        ]);
    }

    private function authorizeAccess(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
