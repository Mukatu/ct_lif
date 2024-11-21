<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  video: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['close'])

const closeModal = () => {
  emit('close')
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full">
      <div class="p-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-semibold">{{ video.title }}</h3>
        <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="aspect-w-16 aspect-h-9">
        <iframe
          :src="video.url"
          class="w-full h-full"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
        ></iframe>
      </div>
      <div class="p-4 bg-gray-50">
        <div class="flex items-center text-sm text-gray-500">
          <span>{{ video.duration }}</span>
          <span class="mx-2">•</span>
          <span>{{ video.level }}</span>
        </div>
      </div>
    </div>
  </div>
</template>