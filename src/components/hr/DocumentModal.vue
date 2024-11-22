<script setup lang="ts">
import { ref } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  document: {
    type: Object,
    default: null
  },
  mode: {
    type: String,
    default: 'view'
  }
})

const emit = defineEmits(['close', 'save'])

const formData = ref({
  title: props.document?.title || '',
  category: props.document?.category || '',
  file: null as File | null
})

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files) {
    formData.value.file = target.files[0]
  }
}

const handleSubmit = async () => {
  try {
    const data = new FormData()
    data.append('title', formData.value.title)
    data.append('category', formData.value.category)
    if (formData.value.file) {
      data.append('file', formData.value.file)
    }

    emit('save', data)
  } catch (error) {
    console.error('Error submitting document:', error)
  }
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-lg w-full p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">
          {{ mode === 'edit' ? 'Modifier le document' : 'Ajouter un document' }}
        </h3>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
          <XMarkIcon class="w-6 h-6" />
        </button>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Titre</label>
          <input
            v-model="formData.title"
            type="text"
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Catégorie</label>
          <select
            v-model="formData.category"
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
          >
            <option value="">Sélectionner une catégorie</option>
            <option value="Procédures">Procédures</option>
            <option value="Règlements">Règlements</option>
            <option value="Formulaires">Formulaires</option>
            <option value="Guides">Guides</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Fichier</label>
          <input
            type="file"
            @change="handleFileChange"
            :required="mode !== 'edit'"
            accept=".pdf,.doc,.docx"
            class="mt-1 block w-full"
          />
        </div>

        <div class="flex justify-end space-x-3">
          <button
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50"
          >
            Annuler
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-[#F2682C] text-white rounded-md hover:bg-[#e55a20]"
          >
            {{ mode === 'edit' ? 'Mettre à jour' : 'Ajouter' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>