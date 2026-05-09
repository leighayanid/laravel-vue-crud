<template>
    <main class="min-h-[620px] rounded-lg border bg-card">
        <form class="flex h-full flex-col" @submit.prevent="emit('save')">
            <div class="flex flex-col gap-4 border-b p-4 xl:flex-row xl:items-end xl:justify-between">
                <div class="grid flex-1 gap-2">
                    <Label for="title">Title</Label>
                    <Input id="title" v-model="form.title" placeholder="Give this note a title" />
                    <InputError :message="form.errors.title" />
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center rounded-md border p-1">
                        <button
                            type="button"
                            class="inline-flex h-8 items-center gap-2 rounded px-3 text-sm"
                            :class="
                                editorMode === 'write'
                                    ? 'bg-accent text-accent-foreground'
                                    : 'text-muted-foreground'
                            "
                            @click="emit('update:editorMode', 'write')"
                        >
                            <Edit3 class="size-4" />
                            Write
                        </button>
                        <button
                            type="button"
                            class="inline-flex h-8 items-center gap-2 rounded px-3 text-sm"
                            :class="
                                editorMode === 'preview'
                                    ? 'bg-accent text-accent-foreground'
                                    : 'text-muted-foreground'
                            "
                            @click="emit('update:editorMode', 'preview')"
                        >
                            <PanelRightOpen class="size-4" />
                            Preview
                        </button>
                    </div>

                    <Button type="submit" :disabled="form.processing">
                        <Save class="size-4" />
                    </Button>
                    <Button type="button" variant="destructive" @click="emit('delete')">
                        <Trash2 class="size-4" />
                    </Button>
                    <label class="flex items-center gap-2 text-sm">
                        <input v-model="form.is_pinned" type="checkbox" class="size-4 rounded border-input" />
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
                            <SelectItem v-for="option in colorOptions" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <InputError :message="form.errors.color" />
            </div>

            <div class="grid flex-1">
                <textarea
                    v-if="editorMode === 'write'"
                    id="content"
                    v-model="form.content"
                    class="min-h-[420px] resize-none bg-transparent p-4 font-mono text-sm leading-6 outline-none placeholder:text-muted-foreground"
                    placeholder="# Start writing in Markdown..."
                ></textarea>
                <div
                    v-else
                    class="min-h-[420px] resize-none bg-transparent p-4 font-mono text-sm leading-6 outline-none placeholder:text-muted-foreground"
                >
                    <div v-if="form.content.trim()" class="note-markdown" v-html="previewHtml"></div>
                    <div v-else class="text-center text-sm text-muted-foreground">
                        Nothing to preview.
                    </div>
                </div>
                <InputError class="px-4 pb-4" :message="form.errors.content" />
            </div>

            <div
                class="flex flex-wrap items-center justify-between gap-2 border-t px-4 py-3 text-xs text-muted-foreground"
            >
                <span>{{ selectedNoteId ? 'Editing saved note' : 'Writing a new note' }}</span>
                <span>{{ wordCount }} words</span>
            </div>
        </form>
    </main>
</template>

<script setup lang="ts">
import { Edit3, PanelRightOpen, Save, Trash2 } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { colorOptions } from '@/features/notes/noteColor';
import type { NoteColor } from '@/types';

type EditorMode = 'write' | 'preview';

type NoteForm = {
    title: string;
    content: string;
    color: NoteColor;
    is_pinned: boolean;
    processing: boolean;
    errors: Partial<Record<'title' | 'content' | 'color' | 'is_pinned', string>>;
};

defineProps<{
    form: NoteForm;
    selectedNoteId: number | null;
    editorMode: EditorMode;
    previewHtml: string;
    wordCount: number;
}>();

const emit = defineEmits<{
    save: [];
    delete: [];
    'update:editorMode': [mode: EditorMode];
}>();
</script>
