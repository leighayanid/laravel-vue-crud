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
                <Button type="button" @click="createNote">
                    <Plus class="size-4" />
                    New note
                </Button>
            </div>
        </header>
        <div class="grid flex-1 gap-4 lg:grid-cols-[320px_minmax(0,1fr)]">
            <aside class="flex min-h-[360px] flex-col gap-3 rounded-lg border bg-card p-3">
                <div class="relative">
                    <Search
                        class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground" />
                    <!-- Search input -->
                    <Input class="pl-9" placeholder="Search notes" type="search" />
                </div>

                <div class="flex-1 space-y-2 overflow-y-auto pr-1">
                    <!-- Note list items will go here -->
                    <button v-for="note in filteredNotes" :key="note.id" type="button"
                        class="w-full rounded-md border border-l-4 bg-background p-3 text-left transition hover:bg-accent"
                        :class="[
                            colorStyles[note.color].note,
                            selectedNoteId === note.id
                                ? 'ring-2 ring-ring/30'
                                : '',
                        ]" @click="selectNote(note)">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="size-2 rounded-full" :class="colorStyles[note.color].dot"></span>
                                    <h2 class="truncate text-sm font-medium">
                                        {{ note.title }}
                                    </h2>
                                </div>
                                <p class="line-clamp-2 text-xs text-muted-foreground">
                                    {{ note.excerpt || 'No note body yet.' }}
                                </p>
                            </div>
                            <Pin v-if="note.is_pinned" class="mt-0.5 size-4 shrink-0 text-muted-foreground" />
                        </div>
                        <p class="mt-2 text-xs text-muted-foreground">
                            {{ note.updated_at_human }}
                        </p>
                    </button>

                    <div v-if="filteredNotes.length === 0"
                        class="rounded-md border border-dashed p-6 text-center text-sm text-muted-foreground">
                        No notes match your search.
                    </div>
                </div>
            </aside>

            <main class="min-h-[620px] rounded-lg border bg-card">
                <form class="flex h-full flex-col" @submit.prevent="createNote">
                    <div class="flex flex-col gap-4 border-b p-4 xl:flex-row xl:items-end xl:justify-between">
                        <div class="grid flex-1 gap-2">
                            <Label for="title">Title</Label>
                            <Input id="title" v-model="form.title" placeholder="Give this note a title" />
                            <InputError />
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <div class="flex items-center rounded-md border p-1">
                                <button type="button" class="inline-flex h-8 items-center gap-2 rounded px-3 text-sm" :class="
                                        editorMode === 'write'
                                            ? 'bg-accent text-accent-foreground'
                                            : 'text-muted-foreground'
                                    "
                                    @click="editorMode = 'write'">
                                    <Edit3 class="size-4" />
                                    Write
                                </button>
                                <button type="button" class="inline-flex h-8 items-center gap-2 rounded px-3 text-sm" :class="
                                        editorMode === 'preview'
                                            ? 'bg-accent text-accent-foreground'
                                            : 'text-muted-foreground'
                                    "
                                    @click="editorMode = 'preview'">
                                    <PanelRightOpen class="size-4" />
                                    Preview
                                </button>
                            </div>

                            <Button type="submit" :disabled="form.processing">
                                <Save class="size-4" />

                            </Button>
                            <Button type="button" variant="destructive" @click="deleteNote">
                                <Trash2 class="size-4" />
                            </Button>
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" class="size-4 rounded border-input" />
                                Pin this note
                            </label>
                        </div>
                    </div>

                    <div class="grid gap-4 border-b p-4 xl:grid-cols-[1fr_auto] xl:items-center">
                        <div class="flex items-center gap-2">
                            <Label class="text-sm font-medium">Color</Label>
                            <Select v-model="form.color">
                                <SelectTrigger class="w-auto">
                                    <SelectValue placeholder="Select color" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="option in colorOptions" :key="option.value"
                                        :value="option.value">
                                        {{ option.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>


                    </div>

                    <div class="grid flex-1">
                        <textarea id="content" v-if="editorMode === 'write'" v-model="form.content"
                            class="min-h-[420px] resize-none bg-transparent p-4 font-mono text-sm leading-6 outline-none placeholder:text-muted-foreground"
                            placeholder="# Start writing in Markdown..."></textarea>
                        <div v-else
                            class="min-h-[420px] resize-none bg-transparent p-4 font-mono text-sm leading-6 outline-none placeholder:text-muted-foreground">
                            <div v-if="form.content.trim()" class="note-markdown" v-html="previewHtml">

                            </div>
                            <div v-else class="text-center text-sm text-muted-foreground">
                                Nothing to preview.
                            </div>
                        </div>

                    </div>

                    <div
                        class="flex flex-wrap items-center justify-between gap-2 border-t px-4 py-3 text-xs text-muted-foreground">
                        <span>{{
                            selectedNoteId
                                ? 'Editing saved note'
                                : 'Writing a new note'
                        }}</span>
                        <span>{{ wordCount }} words</span>
                    </div>
                </form>
            </main>
        </div>
    </div>

</template>

<script setup lang="ts">
import { Link, useForm, Head } from '@inertiajs/vue3'
import {
    Edit3,
    FileText,
    PanelRightOpen,
    Plus,
    Save,
    Search,
    Trash2,
} from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { destroy, index, store, update } from '@/routes/notes';
import { Note, NoteColor } from '@/types';
import { computed, ref } from 'vue';
import { colorOptions, colorStyles } from '@/features/notes/noteColor';
import { markdownToHtml } from '@/features/notes/markdown';
import { useNoteForm } from '@/composables/useNoteForm';

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

const { selectedNoteId, form, createNote, deleteNote, selectNote } = useNoteForm(props.notes);

// Editor mode can be 'write' or 'preview'. For now, we'll just implement 'write' mode and add 'preview' later. **/
const editorMode = ref<'write' | 'preview'>('write');

const selectedColor = computed(() => {
    return form.color as NoteColor;
});

const filteredNotes = computed(() => {
    return props.notes.filter(note => {
        return note.title.toLowerCase().includes('') || note.content.toLowerCase().includes('');
    });
});

const wordCount = computed(() => {
    return form.content.trim() ? form.content.trim().split(/\s+/).length : 0;
});


const previewHtml = computed(() => markdownToHtml(form.content));



/**
 * Update note functionality is handled in the createNote function, 
 * which checks if a note is selected and either 
 * updates it or creates a new one accordingly. **/
const updateNote = (id: any) => {
    alert('Update note functionality coming soon!')
}
</script>
