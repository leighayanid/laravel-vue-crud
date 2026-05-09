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
                            <Select>
                                <SelectTrigger class="w-auto">
                                    <SelectValue placeholder="Select color" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="slate">Slate</SelectItem>
                                    <SelectItem value="sky">Sky</SelectItem>
                                    <SelectItem value="emerald">Emerald</SelectItem>
                                    <SelectItem value="amber">Amber</SelectItem>
                                    <SelectItem value="rose">Rose</SelectItem>
                                    <SelectItem value="violet">Violet</SelectItem>
                                    <SelectItem value="fuchsia">Fuchsia</SelectItem>
                                    <SelectItem value="pink">Pink</SelectItem>
                                    <SelectItem value="indigo">Indigo</SelectItem>
                                    <SelectItem value="lime">Lime</SelectItem>
                                    <SelectItem value="orange">Orange</SelectItem>
                                    <SelectItem value="yellow">Yellow</SelectItem>
                                    <SelectItem value="red">Red</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>


                    </div>

                    <div class="grid flex-1">
                        <textarea
                            id="content"
                            v-model="form.content"
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



const createNote = () => {
    if (selectedNoteId.value) {
        form.put(update.url(selectedNoteId.value), {
            preserveScroll: true,
             onSuccess: () => {
                alert('Note updated successfully!')
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
            alert('Note created successfully!')
            form.reset()
        },
        onError: () => {
            alert('Failed to create note.')
        }
    });
}

const deleteNote = (id: any) => {
    if (confirm('Are you sure?')) {
        alert('Delete note functionality coming soon!')
    }
}

const updateNote = (id: any) => {
    alert('Update note functionality coming soon!')
}
</script>
