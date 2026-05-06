<template>
  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <div class="mb-6">
            <Link href="/notes" class="text-blue-600 hover:text-blue-900 text-sm">← Back to Notes</Link>
          </div>

          <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ note.title }}</h1>
            <p class="text-sm text-gray-500">{{ formatDate(note.created_at) }}</p>
          </div>

          <!-- Tags -->
          <div v-if="note.tags && note.tags.length" class="mb-6 flex flex-wrap gap-2">
            <span v-for="tag in note.tags" :key="tag.id" class="text-sm bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
              {{ tag.name }}
            </span>
          </div>

          <!-- Markdown Content -->
          <div class="prose prose-sm max-w-none mb-8 whitespace-pre-wrap text-gray-700 font-mono">{{ note.content }}</div>

          <!-- Actions -->
          <div class="flex gap-4 border-t pt-6">
            <Link :href="`/notes/${note.id}/edit`" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition ease-in-out duration-150">
              Edit
            </Link>
            <button @click="deleteNote" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 transition ease-in-out duration-150">
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'

defineProps({
  note: Object,
})

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

const deleteNote = () => {
  if (confirm('Are you sure?')) {
    useForm({}).delete(`/notes/${note.value.id}`)
  }
}
</script>
