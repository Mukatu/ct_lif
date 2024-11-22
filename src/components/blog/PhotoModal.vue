<script setup lang="ts">
import { XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  photo: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['close'])
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="max-w-4xl w-full bg-white rounded-lg overflow-hidden">
      <!-- Header -->
      <div class="p-4 flex justify-between items-center border-b">
        <h3 class="text-lg font-semibold">{{ photo.title }}</h3>
        <button 
          @click="$emit('close')"
          class="text-gray-500 hover:text-gray-700"
        >
          <XMarkIcon class="w-6 h-6" />
        </button>
      </div>

      <!-- Photo -->
      <div class="relative">
        <img 
          :src="photo.url" 
          :alt="photo.title"
          class="w-full h-auto max-h-[80vh] object-contain"
        />
      </div>

      <!-- Footer -->
      <div class="p-4 bg-gray-50">
        <p class="text-sm text-gray-600">{{ photo.description }}</p>
        <div class="mt-2 flex items-center text-sm text-gray-500">
          <span>{{ photo.date }}</span>
          <span class="mx-2">•</span>
          <span>{{ photo.category }}</span>
        </div>
      </div>
    </div>
  </div>
</template>