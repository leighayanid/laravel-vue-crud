<template>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">My Notes</h1>
            <Link href="/notes/create" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
              Create Note
            </Link>
          </div>

          <div v-if="notes.length === 0" class="text-center py-12">
            <p class="text-gray-500 text-lg">No notes yet. Create your first note!</p>
          </div>

          <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="note in notes" :key="note.id" class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
              <Link :href="`/notes/${note.id}`" class="block">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-600">{{ note.title }}</h3>
                <p class="text-gray-600 text-sm line-clamp-2">{{ note.content }}</p>
              </Link>
              
              <div v-if="note.tags && note.tags.length" class="mt-3 flex flex-wrap gap-1">
                <span v-for="tag in note.tags" :key="tag.id" class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                  {{ tag.name }}
                </span>
              </div>

              <div class="mt-4 flex gap-2 text-sm">
                <Link :href="`/notes/${note.id}/edit`" class="text-blue-600 hover:text-blue-900">Edit</Link>
                <button @click="deleteNote(note.id)" class="text-red-600 hover:text-red-900">Delete</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link, useForm, Head } from '@inertiajs/vue3'

defineProps({
  notes: Array,
})

const deleteNote = (id) => {
  if (confirm('Are you sure?')) {
    useForm({}).delete(`/notes/${id}`)
  }
}
</script>
