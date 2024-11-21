<script setup lang="ts">
import { ref } from 'vue'

const leaveRequest = ref({
  startDate: '',
  endDate: '',
  type: 'paid',
  reason: ''
})

const submitLeaveRequest = async () => {
  try {
    // TODO: Implement API call to submit leave request
    console.log('Leave request submitted:', leaveRequest.value)
  } catch (error) {
    console.error('Error submitting leave request:', error)
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
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Date de fin</label>
            <input
              v-model="leaveRequest.endDate"
              type="date"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Type de congé</label>
          <select
            v-model="leaveRequest.type"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
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
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="Décrivez brièvement la raison de votre demande"
          ></textarea>
        </div>

        <div class="flex justify-end">
          <button
            type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          >
            Soumettre la demande
          </button>
        </div>
      </form>
    </div>
  </div>
</template>