<script setup lang="ts">
import { ref, defineEmits } from 'vue'

const emit = defineEmits(['close', 'file-selected'])

const acceptedTypes = {
  image: 'image/*',
  video: 'video/*',
  document: '.pdf,.doc,.docx,.xls,.xlsx'
}

const selectedType = ref('image')
const dragOver = ref(false)
const selectedFile = ref(null)
const previewUrl = ref('')

const handleFileSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    selectedFile.value = file
    if (file.type.startsWith('image/')) {
      const reader = new FileReader()
      reader.onload = (e) => {
        previewUrl.value = e.target.result
      }
      reader.readAsDataURL(file)
    }
  }
}

const handleUpload = () => {
  if (selectedFile.value) {
    emit('file-selected', selectedFile.value)
    emit('close')
  }
}

const handleDrop = (event) => {
  event.preventDefault()
  dragOver.value = false
  
  const file = event.dataTransfer?.files[0]
  if (file) {
    selectedFile.value = file
    if (file.type.startsWith('image/')) {
      const reader = new FileReader()
      reader.onload = (e) => {
        previewUrl.value = e.target.result
      }
      reader.readAsDataURL(file)
    }
  }
}

const handleDragOver = (event) => {
  event.preventDefault()
  dragOver.value = true
}

const handleDragLeave = () => {
  dragOver.value = false
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-lg w-full p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">Ajouter un média</h3>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Type de fichier</label>
        <select
          v-model="selectedType"
          class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-[#F2682C] focus:border-[#F2682C]"
        >
          <option value="image">Image</option>
          <option value="video">Vidéo</option>
          <option value="document">Document</option>
        </select>
      </div>

      <div
        class="border-2 border-dashed rounded-lg p-8 text-center"
        :class="{
          'border-[#F2682C] bg-orange-50': dragOver,
          'border-gray-300 hover:border-[#F2682C]': !dragOver
        }"
        @dragover="handleDragOver"
        @dragleave="handleDragLeave"
        @drop="handleDrop"
      >
        <input
          type="file"
          :accept="acceptedTypes[selectedType]"
          class="hidden"
          @change="handleFileSelect"
          id="file-upload"
        />
        <label
          for="file-upload"
          class="cursor-pointer inline-flex flex-col items-center"
        >
          <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
          </svg>
          <span class="text-gray-600">
            Glissez-déposez un fichier ici ou cliquez pour sélectionner
          </span>
          <span class="text-sm text-gray-500 mt-2">
            {{ selectedType === 'image' ? 'PNG, JPG' : selectedType === 'video' ? 'MP4, WebM' : 'PDF, DOC, XLS' }}
          </span>
        </label>
      </div>

      <div v-if="previewUrl" class="mt-4">
        <img :src="previewUrl" alt="Preview" class="max-h-48 mx-auto rounded-lg" />
      </div>

      <div class="mt-6 flex justify-end">
        <button
          @click="handleUpload"
          :disabled="!selectedFile"
          class="px-4 py-2 bg-[#F2682C] text-white rounded-lg hover:bg-[#e55a20] transition-colors disabled:opacity-50"
        >
          Télécharger
        </button>
      </div>
    </div>
  </div>
</template>