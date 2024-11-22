<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { PlusIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'
import { trainingService } from '../../services/api'
import TrainingModal from '../../components/hr/TrainingModal.vue'

const trainings = ref([])
const showModal = ref(false)
const selectedTraining = ref(null)
const modalMode = ref('add')
const loading = ref(false)

const categories = ref(['RH', 'Management', 'Communication', 'Technique'])
const selectedCategory = ref('Tous')

const fetchTrainings = async () => {
  try {
    loading.value = true
    const response = await trainingService.getAll()
    trainings.value = response.data.data
  } catch (error) {
    console.error('Error fetching trainings:', error)
  } finally {
    loading.value = false
  }
}

const handleSave = async (formData: FormData) => {
  try {
    if (modalMode.value === 'edit' && selectedTraining.value) {
      await trainingService.update(selectedTraining.value.id, formData)
    } else {
      await trainingService.create(formData)
    }
    await fetchTrainings()
    showModal.value = false
  } catch (error) {
    console.error('Error saving training:', error)
  }
}

const deleteTraining = async (id: number) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette formation ?')) {
    try {
      await trainingService.delete(id)
      await fetchTrainings()
    } catch (error) {
      console.error('Error deleting training:', error)
    }
  }
}

const openModal = (mode: string, training = null) => {
  modalMode.value = mode
  selectedTraining.value = training
  showModal.value = true
}

onMounted(fetchTrainings)
</script>

<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-xl font-bold text-gray-900">Gestion des formations</h2>
      <button
        @click="openModal('add')"
        class="flex items-center px-4 py-2 bg-[#F2682C] text-white rounded-lg hover:bg-[#e55a20]"
      >
        <PlusIcon class="w-5 h-5 mr-2" />
        Nouvelle formation
      </button>
    </div>

    <div class="mb-6">
      <select
        v-model="selectedCategory"
        class="w-full md:w-64 rounded-lg border-gray-300 focus:border-[#F2682C] focus:ring-[#F2682C]"
      >
        <option value="Tous">Toutes les catégories</option>
        <option v-for="category in categories" :key="category" :value="category">
          {{ category }}
        </option>
      </select>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead>
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Formation</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participants</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="loading">
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
              Chargement...
            </td>
          </tr>
          <tr v-else-if="trainings.length === 0">
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
              Aucune formation trouvée
            </td>
          </tr>
          <tr v-else v-for="training in trainings" :key="training.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="text-sm font-medium text-gray-900">{{ training.title }}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ training.category }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ training.duration }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ training.level }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ training.participants }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <div class="flex space-x-2">
                <button 
                  @click="openModal('edit', training)"
                  class="text-[#F2682C] hover:text-[#e55a20]"
                >
                  <PencilIcon class="w-5 h-5" />
                </button>
                <button 
                  @click="deleteTraining(training.id)"
                  class="text-red-600 hover:text-red-800"
                >
                  <TrashIcon class="w-5 h-5" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <TrainingModal
      v-if="showModal"
      :training="selectedTraining"
      :mode="modalMode"
      @close="showModal = false"
      @save="handleSave"
    />
  </div>
</template>