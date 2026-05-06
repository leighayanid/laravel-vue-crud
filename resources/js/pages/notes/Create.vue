<template>
  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <h1 class="text-2xl font-bold text-gray-900 mb-6">Create New Note</h1>
          
          <form @submit.prevent="submit">
            <!-- Title -->
            <div class="mb-6">
              <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
              <input 
                id="title"
                v-model="form.title" 
                type="text" 
                placeholder="Enter note title"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
              />
              <span v-if="errors.title" class="text-red-600 text-sm">{{ errors.title }}</span>
            </div>

            <!-- Content (Markdown Editor) -->
            <div class="mb-6">
              <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content (Markdown)</label>
              <textarea 
                id="content"
                v-model="form.content" 
                placeholder="Enter your note in markdown..."
                rows="12"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 font-mono"
              ></textarea>
              <span v-if="errors.content" class="text-red-600 text-sm">{{ errors.content }}</span>
            </div>

            <!-- Tags -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
              <div class="space-y-2">
                <div v-for="tag in tags" :key="tag.id" class="flex items-center">
                  <input 
                    :id="`tag-${tag.id}`"
                    type="checkbox"
                    :value="tag.id"
                    v-model="form.tags"
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                  />
                  <label :for="`tag-${tag.id}`" class="ml-2 text-sm text-gray-700">{{ tag.name }}</label>
                </div>
              </div>
              <span v-if="errors.tags" class="text-red-600 text-sm">{{ errors.tags }}</span>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
              <button 
                type="submit"
                :disabled="processing"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
              >
                Create Note
              </button>
              <Link href="/notes" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                Cancel
              </Link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

defineProps({
  tags: Array,
})

const form = useForm({
  title: '',
  content: '',
  tags: [],
})

const errors = form.errors
const processing = form.processing

const submit = () => {
  form.post('/notes')
}
</script>
