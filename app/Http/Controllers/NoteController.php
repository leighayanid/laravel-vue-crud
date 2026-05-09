<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $notes = Note::query()->ownedBy($request->user())->latest('updated_at')->get()
        ->map(fn (Note $note) => [
            'id' => $note->id,
            'title' => $note->title,
            'content' => $note->content,
            'color' => $note->color,
            'is_pinned' => (bool) $note->is_pinned,
            'updated_at' => $note->updated_at->diffForHumans(),
        ]);

        return Inertia::render('notes/Index', ['notes' => $notes]);
    }

    public function store(Request $request)
    {
       $data = $this->validateNote($request);
       $request->user()->notes()->create($data);
       return redirect()->route('notes.index')->with('success', 'Note created successfully.');
    }
    

    // public function show(Note $note)
    // {
    //     $this->authorize('view', $note);
    //     return Inertia::render('notes/Show', ['note' => $note->load('tags')]);
    // }

    // public function edit(Note $note)
    // {
    //     $this->authorize('update', $note);
    //     $tags = Tag::all();
    //     return Inertia::render('notes/Edit', [
    //         'note' => $note->load('tags'),
    //         'tags' => $tags,
    //     ]);
    // }

    public function update(Request $request, Note $note): RedirectResponse
    {
        $this->authorizeNote($request, $note);
        
        // $validated = $request->validate([
        //     'title' => 'required|string|max:255',
        //     'content' => 'required|string',
        //     'tags' => 'array|exists:tags,id',
        // ]);

        $note->update($this->validateNote($request));
        return to_route('notes.index')->with('success', 'Note updated successfully.');
    }

    public function destroy(Request $request, Note $note)
    {
        $this->authorizeNote($request, $note);
        $note->delete();
        return to_route('notes.index')->with('success', 'Note deleted successfully.');
    }

     /**
     * @return array{title: string, content: string, color: string, is_pinned?: bool}
     */
    private function validateNote(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'content' => ['required', 'string', 'max:20000'],
            'color' => ['required', 'string', 'in:slate,sky,emerald,amber,rose'],
            'is_pinned' => ['boolean'],
        ]);
    }

    private function authorizeNote(Request $request, Note $note): void
    {
        abort_unless($note->user_id === $request->user()->id, 403);
    }
}
