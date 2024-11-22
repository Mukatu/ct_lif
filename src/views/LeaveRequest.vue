<script setup lang="ts">
import { ref } from 'vue'
import { leaveService } from '../services/api'
import { useNotification } from '../composables/useNotification'

const notification = useNotification()

const loading = ref(false)
const leaveRequest = ref({
  startDate: '',
  endDate: '',
  type: 'paid',
  reason: ''
})

const submitLeaveRequest = async () => {
  try {
    loading.value = true
    await leaveService.create(leaveRequest.value)
    notification.show({
      type: 'success',
      message: 'Demande de congé soumise avec succès'
    })
    leaveRequest.value = {
      startDate: '',
      endDate: '',
      type: 'paid',
      reason: ''
    }
  } catch (error) {
    notification.show({
      type: 'error',
      message: 'Erreur lors de la soumission de la demande'
    })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
      <h1 class="text-2xl font-bold text-gray-900 mb-6">Demande de congé</h1>
      
      <form @submit.prevent="submitLeaveRequest" class="space-y-6">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Date de début</label>
            <input
              v-model="leaveRequest.startDate"
              type="date"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Date de fin</label>
            <input
              v-model="leaveRequest.endDate"
              type="date"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Type de congé</label>
          <select
            v-model="leaveRequest.type"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
          >
            <option value="paid">Congé payé</option>
            <option value="unpaid">Congé sans solde</option>
            <option value="sick">Congé maladie</option>
            <option value="other">Autre</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Motif</label>
          <textarea
            v-model="leaveRequest.reason"
            rows="4"
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            placeholder="Décrivez brièvement la raison de votre demande"
          ></textarea>
        </div>

        <div class="flex justify-end">
          <button
            type="submit"
            class="px-4 py-2 bg-[#F2682C] text-white rounded-lg hover:bg-[#e55a20] disabled:opacity-50"
            :disabled="loading"
          >
            {{ loading ? 'Envoi en cours...' : 'Soumettre la demande' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>