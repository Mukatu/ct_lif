<script setup lang="ts">
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { 
  CheckCircleIcon, 
  ExclamationCircleIcon,
  ExclamationTriangleIcon,
  InformationCircleIcon 
} from '@heroicons/vue/24/solid'

const props = defineProps<{
  type: 'success' | 'error' | 'warning' | 'info'
  message: string
}>()

const emit = defineEmits(['close'])

const icons = {
  success: CheckCircleIcon,
  error: ExclamationCircleIcon,
  warning: ExclamationTriangleIcon,
  info: InformationCircleIcon
}

const colors = {
  success: 'bg-green-50 text-green-800',
  error: 'bg-red-50 text-red-800',
  warning: 'bg-yellow-50 text-yellow-800',
  info: 'bg-blue-50 text-blue-800'
}

const iconColors = {
  success: 'text-green-400',
  error: 'text-red-400',
  warning: 'text-yellow-400',
  info: 'text-blue-400'
}
</script>

<template>
  <div 
    class="rounded-lg p-4 shadow-lg"
    :class="colors[type]"
  >
    <div class="flex items-start">
      <div class="flex-shrink-0">
        <component 
          :is="icons[type]"
          class="h-5 w-5"
          :class="iconColors[type]"
        />
      </div>
      <div class="ml-3 flex-1">
        <p class="text-sm font-medium">{{ message }}</p>
      </div>
      <div class="ml-4 flex-shrink-0">
        <button
          @click="$emit('close')"
          class="inline-flex rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2"
          :class="colors[type]"
        >
          <span class="sr-only">Fermer</span>
          <XMarkIcon class="h-5 w-5" />
        </button>
      </div>
    </div>
  </div>
</template>