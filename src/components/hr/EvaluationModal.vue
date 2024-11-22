<script setup lang="ts">
import { ref, computed } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { format } from 'date-fns'
import fr from 'date-fns/locale/fr'

const props = defineProps({
  evaluation: {
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
  employee: props.evaluation?.employee || '',
  department: props.evaluation?.department || '',
  period: props.evaluation?.period || '',
  scores: {
    objectives: props.evaluation?.scores?.objectives || 0,
    skills: props.evaluation?.scores?.skills || 0,
    values: props.evaluation?.scores?.values || 0
  },
  comments: {
    strengths: props.evaluation?.comments?.strengths || '',
    improvements: props.evaluation?.comments?.improvements || '',
    goals: props.evaluation?.comments?.goals || ''
  }
})

const totalScore = computed(() => {
  const { objectives, skills, values } = formData.value.scores
  return Math.round((objectives * 0.4 + skills * 0.3 + values * 0.3) * 100) / 100
})

const scoreClass = computed(() => {
  if (totalScore.value >= 80) return 'text-green-600'
  if (totalScore.value >= 60) return 'text-yellow-600'
  return 'text-red-600'
})

const handleSubmit = () => {
  const data = {
    ...formData.value,
    totalScore: totalScore.value,
    evaluationDate: format(new Date(), 'yyyy-MM-dd')
  }
  emit('save', data)
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">
          {{ mode === 'edit' ? 'Modifier l\'évaluation' : 'Nouvelle évaluation' }}
        </h3>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
          <XMarkIcon class="w-6 h-6" />
        </button>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Employé</label>
            <input
              v-model="formData.employee"
              type="text"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Département</label>
            <select
              v-model="formData.department"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            >
              <option value="">Sélectionner un département</option>
              <option value="Marketing">Marketing</option>
              <option value="RH">RH</option>
              <option value="IT">IT</option>
              <option value="Finance">Finance</option>
              <option value="Commercial">Commercial</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Période</label>
            <select
              v-model="formData.period"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            >
              <option value="">Sélectionner une période</option>
              <option value="2024 T1">2024 T1</option>
              <option value="2023 T4">2023 T4</option>
              <option value="2023 T3">2023 T3</option>
              <option value="2023 T2">2023 T2</option>
            </select>
          </div>
        </div>

        <div class="border-t border-gray-200 pt-4">
          <h4 class="text-lg font-medium mb-4">Évaluation des compétences</h4>
          
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">
                Atteinte des objectifs (40%)
              </label>
              <input
                v-model="formData.scores.objectives"
                type="number"
                min="0"
                max="100"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">
                Compétences techniques (30%)
              </label>
              <input
                v-model="formData.scores.skills"
                type="number"
                min="0"
                max="100"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">
                Valeurs et comportement (30%)
              </label>
              <input
                v-model="formData.scores.values"
                type="number"
                min="0"
                max="100"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
              />
            </div>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
              <span class="font-medium">Score total:</span>
              <span :class="['text-lg font-bold', scoreClass]">
                {{ totalScore }}%
              </span>
            </div>
          </div>
        </div>

        <div class="border-t border-gray-200 pt-4 space-y-4">
          <h4 class="text-lg font-medium mb-4">Commentaires</h4>

          <div>
            <label class="block text-sm font-medium text-gray-700">Points forts</label>
            <textarea
              v-model="formData.comments.strengths"
              rows="3"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            ></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Axes d'amélioration</label>
            <textarea
              v-model="formData.comments.improvements"
              rows="3"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            ></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Objectifs pour la prochaine période</label>
            <textarea
              v-model="formData.comments.goals"
              rows="3"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            ></textarea>
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
            {{ mode === 'edit' ? 'Mettre à jour' : 'Ajouter' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>