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
                                <button type="button" class="inline-flex h-8 items-center gap-2 rounded px-3 text-sm">
                                    <Edit3 class="size-4" />
                                    Write
                                </button>
                                <button type="button" class="inline-flex h-8 items-center gap-2 rounded px-3 text-sm">
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
                            <Select v-model="form.color" >
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
                        <textarea id="content" v-model="form.content"
                            class="min-h-[420px] resize-none bg-transparent p-4 font-mono text-sm leading-6 outline-none placeholder:text-muted-foreground"
                            placeholder="# Start writing in Markdown..."></textarea>
                        <!-- Markdown preview will go here -->
                    </div>

                    <div
                        class="flex flex-wrap items-center justify-between gap-2 border-t px-4 py-3 text-xs text-muted-foreground">
                        <span>{{
                            1
                                ? 'Editing saved note'
                                : 'Writing a new note'
                        }}</span>
                        <span>1000 words</span>
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

const colorOptions: Array<{ label: string; value: NoteColor }> = [
    { label: 'Slate', value: 'slate' },
    { label: 'Sky', value: 'sky' },
    { label: 'Emerald', value: 'emerald' },
    { label: 'Amber', value: 'amber' },
    { label: 'Rose', value: 'rose' },
    { label: 'Violet', value: 'violet' },
    { label: 'Fuchsia', value: 'fuchsia' },
    { label: 'Pink', value: 'pink' },
    { label: 'Indigo', value: 'indigo' },
    { label: 'Orange', value: 'orange' },
    { label: 'Yellow', value: 'yellow' },
    { label: 'Red', value: 'red' },
    { label: 'Gray', value: 'gray' },
    { label: 'Zinc', value: 'zinc' },
    { label: 'Neutral', value: 'neutral' },
    { label: 'Stone', value: 'stone' },
    { label: 'Green', value: 'green' },
    { label: 'Blue', value: 'blue' },
    { label: 'Purple', value: 'purple' }
];

const colorStyles: Record<
    NoteColor,
    { dot: string; note: string; swatch: string }
> = {
    slate: {
        dot: 'bg-slate-500',
        note: 'border-l-slate-500',
        swatch: 'bg-slate-500',
    },
    sky: {
        dot: 'bg-sky-500',
        note: 'border-l-sky-500',
        swatch: 'bg-sky-500',
    },
    emerald: {
        dot: 'bg-emerald-500',
        note: 'border-l-emerald-500',
        swatch: 'bg-emerald-500',
    },
    amber: {
        dot: 'bg-amber-500',
        note: 'border-l-amber-500',
        swatch: 'bg-amber-500',
    },
    rose: {
        dot: 'bg-rose-500',
        note: 'border-l-rose-500',
        swatch: 'bg-rose-500',
    },
    violet: {
        dot: 'bg-violet-500',
        note: 'border-l-violet-500',
        swatch: 'bg-violet-500',
    },
    fuchsia: {
        dot: 'bg-fuchsia-500',
        note: 'border-l-fuchsia-500',
        swatch: 'bg-fuchsia-500',
    },
    indigo: {
        dot: 'bg-indigo-500',
        note: 'border-l-indigo-500',
        swatch: 'bg-indigo-500',
    },
    orange: {
        dot: 'bg-orange-500',
        note: 'border-l-orange-500',
        swatch: 'bg-orange-500',
    },
    yellow: {
        dot: 'bg-yellow-500',
        note: 'border-l-yellow-500',
        swatch: 'bg-yellow-500',
    },
    red: {
        dot: 'bg-red-500',
        note: 'border-l-red-500',
        swatch: 'bg-red-500',
    },
    pink: {
        dot: 'bg-pink-500',
        note: 'border-l-pink-500',
        swatch: 'bg-pink-500',
    },
    gray: {
        dot: 'bg-gray-500',
        note: 'border-l-gray-500',
        swatch: 'bg-gray-500',
    },
    zinc: {
        dot: '  bg-zinc-500',
        note: 'border-l-zinc-500',
        swatch: 'bg-zinc-500',
    },
    neutral: {
        dot: 'bg-neutral-500',
        note: 'border-l-neutral-500',
        swatch: 'bg-neutral-500',
    },
    stone: {
        dot: 'bg-stone-500',
        note: 'border-l-stone-500',
        swatch: 'bg-stone-500'
    },
    green: {
        dot: 'bg-green-500',
        note: 'border-l-green-500',
        swatch: 'bg-green-500'
    },
    blue: {
        dot: 'bg-blue-500',
        note: 'border-l-blue-500',
        swatch: 'bg-blue-500'
    },
    purple: {
        dot: 'bg-purple-500',
        note: 'border-l-purple-500',
        swatch: 'bg-purple-500'
    }
};

const selectedNoteId = ref<number | null>(props.notes.length > 0 ? props.notes[0].id : null);


const form = useForm({
    title: props.notes.length > 0 ? props.notes[0].title : '',
    content: props.notes.length > 0 ? props.notes[0].content : '',
    color: props.notes.length > 0 ? props.notes[0].color : 'slate' as NoteColor,
    is_pinned: props.notes.length > 0 ? props.notes[0].is_pinned : false,
});

const selectedColor = computed(() => {
    return form.color as NoteColor;
});

const filteredNotes = computed(() => {
    return props.notes.filter(note => {
        return note.title.toLowerCase().includes('') || note.content.toLowerCase().includes('');
    });
});

const createNote = () => {
    if (selectedNoteId.value) {
        form.put(update.url(selectedNoteId.value), {
            preserveScroll: true,
            onSuccess: () => {
                newNote();
            },
            onError: () => {
                alert('Failed to update note.')
            }
        });
        return;
    }
    form.post(store.url(), {
        preserveScroll: true,
        onSuccess: () => {
            newNote();
        },
        onError: (e) => {
            console.error(e);
            alert('Failed to create note.');
        }
    });
}

const newNote = () => {
    selectedNoteId.value = null;
    form.clearErrors();
    form.reset();
    form.title = '';
    form.content = '';
    form.color = 'slate' as NoteColor;
    form.is_pinned = false;
}

const selectNote = (note: Note) => {
    selectedNoteId.value = note.id;
    form.title = note.title;
    form.content = note.content;
    form.color = note.color as NoteColor;
    form.is_pinned = note.is_pinned;
}

const deleteNote = (id: any) => {
    if (!selectedNoteId.value) {
        alert('No note selected to delete.');
        return;
    }
    if (confirm('Are you sure you want to delete this note? This action cannot be undone.')) {
        form.delete(destroy.url(selectedNoteId.value), {
            preserveScroll: true,
            onSuccess: () => {
                newNote();
            },
            onError: () => {
                alert('Failed to delete note.')
            }
        });
    }
}

const updateNote = (id: any) => {
    alert('Update note functionality coming soon!')
}
</script>
