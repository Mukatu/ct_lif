<script setup lang="ts">
import { ref } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  post: {
    type: Object,
    default: null
  },
  mode: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['close', 'save'])

const formData = ref({
  title: props.post?.title || '',
  description: props.post?.description || '',
  content: props.post?.content || '',
  category: props.post?.category || 'Actualités',
  image: null as File | null
})

const imagePreview = ref(props.post?.image || '')

const handleImageChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    formData.value.image = target.files[0]
    imagePreview.value = URL.createObjectURL(target.files[0])
  }
}

const handleSubmit = () => {
  emit('save', {
    ...formData.value,
    image: imagePreview.value // Dans un vrai cas, on enverrait le fichier au serveur
  })
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">
          {{ mode === 'edit' ? 'Modifier l\'article' : 'Nouvel article' }}
        </h3>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
          <XMarkIcon class="w-6 h-6" />
        </button>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
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
          <label class="block text-sm font-medium text-gray-700">Description</label>
          <input
            v-model="formData.description"
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
            <option value="Événements">Événements</option>
            <option value="Formation">Formation</option>
            <option value="Actualités">Actualités</option>
            <option value="Projets">Projets</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Contenu</label>
          <textarea
            v-model="formData.content"
            rows="6"
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
          ></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Image</label>
          <div class="mt-1 flex items-center space-x-4">
            <input
              type="file"
              accept="image/*"
              @change="handleImageChange"
              class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#F2682C] file:text-white hover:file:bg-[#e55a20]"
            />
            <img
              v-if="imagePreview"
              :src="imagePreview"
              alt="Preview"
              class="h-20 w-20 object-cover rounded-lg"
            />
          </div>
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
            {{ mode === 'edit' ? 'Mettre à jour' : 'Publier' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>