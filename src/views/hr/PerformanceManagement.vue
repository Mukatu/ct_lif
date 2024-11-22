<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { PlusIcon, PencilIcon, ChartBarIcon } from '@heroicons/vue/24/outline'
import { performanceService } from '../../services/api'
import EvaluationModal from '../../components/hr/EvaluationModal.vue'

const evaluations = ref([])
const showModal = ref(false)
const selectedEvaluation = ref(null)
const modalMode = ref('add')
const loading = ref(false)

const periods = ref(['2024 T1', '2023 T4', '2023 T3', '2023 T2'])
const selectedPeriod = ref('2024 T1')

const fetchEvaluations = async () => {
  try {
    loading.value = true
    const response = await performanceService.getAll()
    evaluations.value = response.data.data
  } catch (error) {
    console.error('Error fetching evaluations:', error)
  } finally {
    loading.value = false
  }
}

const handleSave = async (data) => {
  try {
    if (modalMode.value === 'edit' && selectedEvaluation.value) {
      await performanceService.update(selectedEvaluation.value.id, data)
    } else {
      await performanceService.create(data)
    }
    await fetchEvaluations()
    showModal.value = false
  } catch (error) {
    console.error('Error saving evaluation:', error)
  }
}

const viewStats = async (id: number) => {
  try {
    const response = await performanceService.getStats(id)
    console.log('Stats:', response.data)
  } catch (error) {
    console.error('Error fetching stats:', error)
  }
}

const openModal = (mode: string, evaluation = null) => {
  modalMode.value = mode
  selectedEvaluation.value = evaluation
  showModal.value = true
}

onMounted(fetchEvaluations)
</script>

<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-xl font-bold text-gray-900">Gestion des performances</h2>
      <button
        @click="openModal('add')"
        class="flex items-center px-4 py-2 bg-[#F2682C] text-white rounded-lg hover:bg-[#e55a20]"
      >
        <PlusIcon class="w-5 h-5 mr-2" />
        Nouvelle évaluation
      </button>
    </div>

    <div class="mb-6">
      <select
        v-model="selectedPeriod"
        class="w-full md:w-64 rounded-lg border-gray-300 focus:border-[#F2682C] focus:ring-[#F2682C]"
      >
        <option v-for="period in periods" :key="period" :value="period">
          {{ period }}
        </option>
      </select>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead>
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employé</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Département</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="loading">
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
              Chargement...
            </td>
          </tr>
          <tr v-else-if="evaluations.length === 0">
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
              Aucune évaluation trouvée
            </td>
          </tr>
          <tr v-else v-for="evaluation in evaluations" :key="evaluation.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="text-sm font-medium text-gray-900">{{ evaluation.employee }}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ evaluation.department }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ evaluation.period }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <span class="text-sm font-medium" 
                  :class="{
                    'text-green-600': evaluation.score >= 80,
                    'text-yellow-600': evaluation.score >= 60 && evaluation.score < 80,
                    'text-red-600': evaluation.score < 60
                  }"
                >
                  {{ evaluation.score }}%
                </span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 py-1 text-xs rounded-full"
                :class="{
                  'bg-green-100 text-green-800': evaluation.status === 'Complété',
                  'bg-yellow-100 text-yellow-800': evaluation.status === 'En cours'
                }"
              >
                {{ evaluation.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <div class="flex space-x-2">
                <button 
                  @click="openModal('edit', evaluation)"
                  class="text-[#F2682C] hover:text-[#e55a20]"
                >
                  <PencilIcon class="w-5 h-5" />
                </button>
                <button 
                  @click="viewStats(evaluation.id)"
                  class="text-blue-600 hover:text-blue-800"
                >
                  <ChartBarIcon class="w-5 h-5" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <EvaluationModal
      v-if="showModal"
      :evaluation="selectedEvaluation"
      :mode="modalMode"
      @close="showModal = false"
      @save="handleSave"
    />
  </div>
</template>