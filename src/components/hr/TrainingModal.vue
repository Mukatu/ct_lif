<script setup lang="ts">
import { ref } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { format } from 'date-fns'
import fr from 'date-fns/locale/fr'

const props = defineProps({
  training: {
    type: Object,
    default: null
  },
  mode: {
    type: String,
    default: 'add'
  }
})

const emit = defineEmits(['close', 'save'])

const formData = ref({
  title: props.training?.title || '',
  description: props.training?.description || '',
  category: props.training?.category || '',
  duration: props.training?.duration || '',
  level: props.training?.level || '',
  startDate: props.training?.startDate || format(new Date(), 'yyyy-MM-dd'),
  maxParticipants: props.training?.maxParticipants || 10,
  video: null as File | null,
  materials: [] as File[]
})

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files) {
    formData.value.video = target.files[0]
  }
}

const handleMaterialsChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files) {
    formData.value.materials = Array.from(target.files)
  }
}

const handleSubmit = () => {
  const data = new FormData()
  Object.entries(formData.value).forEach(([key, value]) => {
    if (value !== null) {
      if (Array.isArray(value)) {
        value.forEach((file, index) => {
          data.append(`${key}[${index}]`, file)
        })
      } else {
        data.append(key, value)
      }
    }
  })
  emit('save', data)
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">
          {{ mode === 'edit' ? 'Modifier la formation' : 'Nouvelle formation' }}
        </h3>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
          <XMarkIcon class="w-6 h-6" />
        </button>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
              <option value="RH">RH</option>
              <option value="Management">Management</option>
              <option value="Communication">Communication</option>
              <option value="Technique">Technique</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Durée</label>
            <input
              v-model="formData.duration"
              type="text"
              required
              placeholder="Ex: 2h"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Niveau</label>
            <select
              v-model="formData.level"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            >
              <option value="">Sélectionner un niveau</option>
              <option value="Débutant">Débutant</option>
              <option value="Intermédiaire">Intermédiaire</option>
              <option value="Avancé">Avancé</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Date de début</label>
            <input
              v-model="formData.startDate"
              type="date"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Nombre max. de participants</label>
            <input
              v-model="formData.maxParticipants"
              type="number"
              min="1"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Description</label>
          <textarea
            v-model="formData.description"
            rows="3"
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
          ></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Vidéo de formation</label>
          <input
            type="file"
            @change="handleFileChange"
            :required="mode !== 'edit'"
            accept="video/*"
            class="mt-1 block w-full"
          />
          <p class="mt-1 text-sm text-gray-500">Format MP4 ou WebM, max 100MB</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Supports de formation</label>
          <input
            type="file"
            @change="handleMaterialsChange"
            multiple
            accept=".pdf,.doc,.docx,.ppt,.pptx"
            class="mt-1 block w-full"
          />
          <p class="mt-1 text-sm text-gray-500">PDF, Word ou PowerPoint, max 20MB par fichier</p>
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