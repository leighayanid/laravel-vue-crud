<template>
    <aside class="flex min-h-[360px] flex-col gap-3 rounded-lg border bg-card p-3">
        <div class="relative">
            <Search class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground" />
            <!-- Search input -->
            <Input v-model="searchQuery" class="pl-9" placeholder="Search notes" type="search" />
        </div>

        <div class="flex-1 space-y-2 overflow-y-auto pr-1">
            <!-- Note list items will go here -->
            <NotesListItem v-for="note in props.notes" :key="note.id" :note="note"
                :selected-note-id="props.selectedNoteId" @select="select" />

            <div v-if="props.notes.length === 0"
                class="rounded-md border border-dashed p-6 text-center text-sm text-muted-foreground">
                No notes match your search.
            </div>
        </div>
    </aside>
</template>
<script setup lang="ts">
import { Search } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Input } from '@/components/ui/input';
import type { Note } from '@/types';
import NotesListItem from './NotesListItem.vue';

const props = defineProps<{
    notes: Note[];
    selectedNoteId: number | null;
}>();

const emit = defineEmits<{
    select: [note: Note];
    search: [query: string];
}>();

const searchQuery = ref('');

watch(searchQuery, value => {
    emit('search', value.toString());
});

const select = (note: Note) => {
    emit('select', note);
};
</script>
