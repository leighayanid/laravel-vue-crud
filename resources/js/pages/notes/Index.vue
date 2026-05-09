<template>

    <Head title="My Notes" />
    <div class="flex h-full flex-col flex-1 p-5 gap-5 overflow-x-auto rounded-xl">
        <header class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="space-y-1">
                <h1 class="text-2xl font-semibold tracking-normal flex gap-2">
                    <FileText class="size-8" />Notes
                </h1>
                <p class="max-w-2xl text-sm text-muted-foreground">
                    Write, preview, pin, search, and color-code your notes from
                    one workspace.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <Button type="button" @click="newNote">
                    <Plus class="size-4" />
                    New note
                </Button>
            </div>
        </header>
        <div class="grid flex-1 gap-4 lg:grid-cols-[320px_minmax(0,1fr)]">
            <NotesSidebar :notes="filteredNotes" :selected-note-id="selectedNoteId" @select="selectNote" />
            <NotesEditor v-model:editor-mode="editorMode" :form="form" :selected-note-id="selectedNoteId"
                :preview-html="previewHtml" :word-count="wordCount" @save="createNote" @delete="deleteNote" />
        </div>
    </div>

</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import {
    FileText,
    Plus,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { index } from '@/routes/notes';
import type { Note } from '@/types';
import { computed, ref } from 'vue';
import { markdownToHtml } from '@/features/notes/markdown';
import { useNoteForm } from '@/composables/useNoteForm';
import NotesSidebar from '@/components/notes/NotesSidebar.vue';
import NotesEditor from '@/components/notes/NotesEditor.vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'My Notes',
                href: index(),
            },
        ],
    },
});

const props = defineProps<{
    notes: Note[];
}>();

const { selectedNoteId, form, createNote, deleteNote, selectNote, newNote } = useNoteForm(props.notes);

// Editor mode can be 'write' or 'preview'. For now, we'll just implement 'write' mode and add 'preview' later. **/
const editorMode = ref<'write' | 'preview'>('write');

const filteredNotes = computed(() => {
    return props.notes.filter(note => {
        return note.title.toLowerCase().includes('') || note.content.toLowerCase().includes('');
    });
});

const wordCount = computed(() => {
    return form.content.trim() ? form.content.trim().split(/\s+/).length : 0;
});


const previewHtml = computed(() => markdownToHtml(form.content));



</script>
