<template>
    <aside class="flex min-h-[360px] flex-col gap-3 rounded-lg border bg-card p-3">
        <div class="relative">
            <Search class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground" />
            <!-- Search input -->
            <Input class="pl-9" placeholder="Search notes" type="search" />
        </div>

        <div class="flex-1 space-y-2 overflow-y-auto pr-1">
            <!-- Note list items will go here -->
            <button v-for="note in props.notes" :key="note.id" type="button"
                class="w-full rounded-md border border-l-4 bg-background p-3 text-left transition hover:bg-accent"
                :class="[
                    colorStyles[note.color].note,
                    selectedNoteId === note.id
                        ? 'ring-2 ring-ring/30'
                        : '',
                ]" @click="select(note)">
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

            <div v-if="props.notes.length === 0"
                class="rounded-md border border-dashed p-6 text-center text-sm text-muted-foreground">
                No notes match your search.
            </div>
        </div>
    </aside>
</template>
<script setup lang="ts">
import { Note } from '@/types';
import { colorStyles } from '@/features/notes/noteColor';
import { useNoteForm } from '@/composables/useNoteForm';
import { Pin, Search } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';

const props = defineProps<{
    notes: Note[];
}>();

const emit = defineEmits<{
    select: [note: Note];
}>();

const select = (note: Note) => {
    emit('select', note);
};


const { selectNote, selectedNoteId } = useNoteForm(props.notes);


</script>