<script setup lang="ts">
import { ref } from 'vue'

const leaveRequests = ref([
  {
    id: 1,
    employee: 'Jean Dupont',
    type: 'paid',
    startDate: '2024-03-01',
    endDate: '2024-03-05',
    status: 'pending',
    reason: 'Vacances familiales'
  }
])

const updateStatus = (requestId: number, newStatus: string) => {
  const request = leaveRequests.value.find(r => r.id === requestId)
  if (request) {
    request.status = newStatus
  }
}
</script>

<template>
  <div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Gestion des congés</h2>
    
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead>
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employé</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="request in leaveRequests" :key="request.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ request.employee }}</td>
            <td class="px-6 py-4 whitespace-nowrap capitalize">{{ request.type }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              {{ request.startDate }} - {{ request.endDate }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="{
                'px-2 py-1 text-xs rounded-full': true,
                'bg-yellow-100 text-yellow-800': request.status === 'pending',
                'bg-green-100 text-green-800': request.status === 'approved',
                'bg-red-100 text-red-800': request.status === 'rejected'
              }">
                {{ request.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex space-x-2">
                <button
                  @click="updateStatus(request.id, 'approved')"
                  class="text-[#F2682C] hover:text-[#e55a20]"
                >
                  Approuver
                </button>
                <button
                  @click="updateStatus(request.id, 'rejected')"
                  class="text-red-600 hover:text-red-800"
                >
                  Rejeter
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>