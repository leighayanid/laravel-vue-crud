import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import { destroy, store, update } from '@/routes/notes';
import type { Note, NoteColor } from '@/types';

export function useNoteForm(notes: Note[]) {

    const selectedNoteId = ref<number | null>(
        notes.length > 0 ? notes[0].id : null,
    );

    const form = useForm({
        title: notes.length > 0 ? notes[0].title : '',
        content: notes.length > 0 ? notes[0].content : '',
        color: notes.length > 0 ? notes[0].color : ('slate' as NoteColor),
        is_pinned: notes.length > 0 ? notes[0].is_pinned : false,
    });

    const newNote = () => {
        selectedNoteId.value = null;
        form.clearErrors();
        form.reset();
        form.title = '';
        form.content = '';
        form.color = 'slate' as NoteColor;
        form.is_pinned = false;
    };

    const selectNote = (note: Note) => {
        selectedNoteId.value = note.id;
        form.title = note.title;
        form.content = note.content;
        form.color = note.color as NoteColor;
        form.is_pinned = note.is_pinned;
        form.clearErrors();
    };

    const createNote = () => {
        if (selectedNoteId.value) {
            form.put(update.url(selectedNoteId.value), {
                preserveScroll: true,
                onSuccess: () => {
                    newNote();
                },
                onError: () => {
                    toast.error('Failed to update note.');
                },
            });

            return;
        }

        form.post(store.url(), {
            preserveScroll: true,
            onSuccess: () => {
                newNote();
            },
            onError: () => {
                toast.error('Failed to create note.');
            },
        });
    };

    const deleteNote = () => {
        if (!selectedNoteId.value) {
            toast.info('No note selected to delete.');
            return;
        }

        if (!confirm('Are you sure you want to delete this note? This action cannot be undone.')) {
            return;
        }

        form.delete(destroy.url(selectedNoteId.value), {
            preserveScroll: true,
            onSuccess: () => {
                newNote();
            },
            onError: () => {
                toast.error('Failed to delete note.');
            },
        });
    };

    return {
        selectedNoteId,
        form,
        newNote,
        selectNote,
        createNote,
        deleteNote,
    };
}
