<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guests_are_redirected_to_the_login_page()
    {
        $this->get(route('notes.index'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_only_view_their_own_notes()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Note::factory()->for($user)->create([
            'title' => 'My note',
            'content' => 'Only I should see this.',
        ]);

        Note::factory()->for($otherUser)->create([
            'title' => 'Someone else note',
            'content' => 'This belongs to another user.',
        ]);

        $this->actingAs($user)
            ->get(route('notes.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('notes/Index')
                ->has('notes', 1)
                ->where('notes.0.title', 'My note')
            );
    }

    public function test_authenticated_users_can_create_notes()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('notes.store'), [
                'title' => 'Launch checklist',
                'content' => 'Ship the app with confidence.',
                'color' => 'sky',
                'is_pinned' => true,
            ])
            ->assertRedirect(route('notes.index'));

        $this->assertDatabaseHas('notes', [
            'user_id' => $user->id,
            'title' => 'Launch checklist',
            'content' => 'Ship the app with confidence.',
            'color' => 'sky',
            'is_pinned' => true,
        ]);
    }

    public function test_note_creation_requires_valid_data()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('notes.store'), [
                'title' => '',
                'content' => '',
                'color' => 'not-a-note-color',
            ])
            ->assertSessionHasErrors(['title', 'content', 'color']);

        $this->assertDatabaseCount('notes', 0);
    }

    public function test_authenticated_users_can_update_their_own_notes()
    {
        $user = User::factory()->create();
        $note = Note::factory()->for($user)->create([
            'title' => 'Old title',
            'content' => 'Old content',
            'color' => 'slate',
            'is_pinned' => false,
        ]);

        $this->actingAs($user)
            ->put(route('notes.update', $note), [
                'title' => 'New title',
                'content' => 'New content',
                'color' => 'emerald',
                'is_pinned' => true,
            ])
            ->assertRedirect(route('notes.index'));

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'New title',
            'content' => 'New content',
            'color' => 'emerald',
            'is_pinned' => true,
        ]);
    }

    public function test_authenticated_users_cannot_update_notes_they_do_not_own()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $note = Note::factory()->for($otherUser)->create([
            'title' => 'Private note',
        ]);

        $this->actingAs($user)
            ->put(route('notes.update', $note), [
                'title' => 'Changed title',
                'content' => 'Changed content',
                'color' => 'rose',
                'is_pinned' => true,
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'Private note',
        ]);
    }

    public function test_authenticated_users_can_delete_their_own_notes()
    {
        $user = User::factory()->create();
        $note = Note::factory()->for($user)->create();

        $this->actingAs($user)
            ->delete(route('notes.destroy', $note))
            ->assertRedirect(route('notes.index'));

        $this->assertDatabaseMissing('notes', [
            'id' => $note->id,
        ]);
    }

    public function test_authenticated_users_cannot_delete_notes_they_do_not_own()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $note = Note::factory()->for($otherUser)->create();

        $this->actingAs($user)
            ->delete(route('notes.destroy', $note))
            ->assertForbidden();

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
        ]);
    }
}
