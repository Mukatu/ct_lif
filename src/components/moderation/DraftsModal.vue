<script setup lang="ts">
import { ref } from 'vue'
import { XMarkIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  drafts: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['close', 'edit', 'delete'])

</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">Brouillons</h3>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
          <XMarkIcon class="w-6 h-6" />
        </button>
      </div>

      <div class="space-y-4">
        <div v-for="draft in drafts" :key="draft.id" class="p-4 bg-gray-50 rounded-lg">
          <div class="flex justify-between items-start">
            <div>
              <p class="font-medium">{{ draft.title }}</p>
              <p class="text-sm text-gray-500">Dernière modification: {{ draft.lastModified }}</p>
            </div>
            <div class="flex space-x-2">
              <button
                @click="$emit('edit', draft.id)"
                class="text-[#F2682C] hover:text-[#e55a20]"
              >
                <PencilIcon class="w-5 h-5" />
              </button>
              <button
                @click="$emit('delete', draft.id)"
                class="text-red-600 hover:text-red-800"
              >
                <TrashIcon class="w-5 h-5" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>