<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NoteController extends Controller
{
    public function index()
    {
        $notes = auth()->user()->notes()->with('tags')->get();
        return Inertia::render('Notes/Index', ['notes' => $notes]);
    }

    public function create()
    {
        $tags = Tag::all();
        return Inertia::render('Notes/Create', ['tags' => $tags]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'array|exists:tags,id',
        ]);

        $note = auth()->user()->notes()->create($validated);
        
        if ($request->has('tags')) {
            $note->tags()->sync($request->tags);
        }

        return redirect()->route('notes.show', $note);
    }

    public function show(Note $note)
    {
        $this->authorize('view', $note);
        return Inertia::render('Notes/Show', ['note' => $note->load('tags')]);
    }

    public function edit(Note $note)
    {
        $this->authorize('update', $note);
        $tags = Tag::all();
        return Inertia::render('Notes/Edit', [
            'note' => $note->load('tags'),
            'tags' => $tags,
        ]);
    }

    public function update(Request $request, Note $note)
    {
        $this->authorize('update', $note);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'array|exists:tags,id',
        ]);

        $note->update($validated);
        
        if ($request->has('tags')) {
            $note->tags()->sync($request->tags);
        }

        return redirect()->route('notes.show', $note);
    }

    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        $note->delete();
        return redirect()->route('notes.index');
    }
}
