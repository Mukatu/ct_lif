<script setup lang="ts">
import { ref } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  history: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['close'])

</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">Historique des activités</h3>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
          <XMarkIcon class="w-6 h-6" />
        </button>
      </div>

      <div class="space-y-4">
        <div v-for="(item, index) in history" :key="index" class="p-4 bg-gray-50 rounded-lg">
          <div class="flex items-start">
            <div class="flex-1">
              <p class="font-medium">{{ item.action }}</p>
              <p class="text-sm text-gray-500">{{ item.date }}</p>
              <p class="mt-2 text-gray-600">{{ item.details }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>