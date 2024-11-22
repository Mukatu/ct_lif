<script setup lang="ts">
import { XCircleIcon } from '@heroicons/vue/24/solid'

defineProps({
  message: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['close'])
</script>

<template>
  <div class="rounded-md bg-red-50 p-4 mb-4">
    <div class="flex">
      <div class="flex-shrink-0">
        <XCircleIcon class="h-5 w-5 text-red-400" />
      </div>
      <div class="ml-3">
        <p class="text-sm font-medium text-red-800">{{ message }}</p>
      </div>
      <div class="ml-auto pl-3">
        <button
          @click="$emit('close')"
          class="inline-flex rounded-md bg-red-50 p-1.5 text-red-500 hover:bg-red-100"
        >
          <span class="sr-only">Fermer</span>
          <XCircleIcon class="h-5 w-5" />
        </button>
      </div>
    </div>
  </div>
</template>