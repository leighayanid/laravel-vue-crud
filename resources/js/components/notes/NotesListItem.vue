<template>
    <button type="button"
        class="w-full rounded-md border border-l-4 p-3 text-left transition" :class="[
            colorStyles[note.color].note,
            isSelected
                ? 'bg-accent text-accent-foreground shadow-sm ring-2 ring-ring/30'
                : 'bg-background hover:bg-accent',
        ]" :aria-current="isSelected ? 'true' : undefined" @click="select(note)">
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
            {{ note.updated_at }}
        </p>
    </button>
</template>

<script setup lang="ts">
import { Pin } from 'lucide-vue-next';
import { computed } from 'vue';
import { colorStyles } from '@/features/notes/noteColor';
import type { Note } from '@/types';

const props = defineProps<{
    note: Note;
    selectedNoteId: number | null;
}>();

const emit = defineEmits<{
    select: [note: Note];
}>();

const isSelected = computed(() => props.selectedNoteId === props.note.id);

const select = (note: Note) => {
    emit('select', note);
};
</script>
