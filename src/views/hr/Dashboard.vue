<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const stats = ref({
  pendingLeaves: 12,
  activeEmployees: 156,
  documentsUploaded: 45,
  activeTrainings: 8,
  pendingEvaluations: 23,
  averagePerformance: 87
})

const userName = computed(() => authStore.user?.name || 'Utilisateur')

const menuItems = [
  {
    title: 'Gestion des congés',
    description: 'Gérer les demandes de congés',
    route: 'leave-management',
    count: stats.value.pendingLeaves
  },
  {
    title: 'Gestion documentaire',
    description: 'Gérer les documents RH',
    route: 'document-management',
    count: stats.value.documentsUploaded
  },
  {
    title: 'Gestion des formations',
    description: 'Gérer les formations',
    route: 'training-management',
    count: stats.value.activeTrainings
  },
  {
    title: 'Gestion du personnel',
    description: 'Gérer les employés',
    route: 'employee-management',
    count: stats.value.activeEmployees
  },
  {
    title: 'Gestion des performances',
    description: 'Gérer les évaluations',
    route: 'performance-management',
    count: stats.value.pendingEvaluations
  }
]

const navigateTo = (route: string) => {
  router.push({ name: route })
}
</script>

<template>
  <div>
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-900">Tableau de bord RH</h1>
      <p class="mt-2 text-gray-600">Bienvenue, {{ userName }}</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Congés</h3>
        <div class="flex justify-between items-center">
          <span class="text-3xl font-bold text-blue-600">{{ stats.pendingLeaves }}</span>
          <span class="text-gray-600">Demandes en attente</span>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personnel</h3>
        <div class="flex justify-between items-center">
          <span class="text-3xl font-bold text-blue-600">{{ stats.activeEmployees }}</span>
          <span class="text-gray-600">Employés actifs</span>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance</h3>
        <div class="flex justify-between items-center">
          <span class="text-3xl font-bold text-blue-600">{{ stats.averagePerformance }}%</span>
          <span class="text-gray-600">Performance moyenne</span>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <button
        v-for="item in menuItems"
        :key="item.route"
        @click="navigateTo(item.route)"
        class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-all transform hover:-translate-y-1 text-left"
      >
        <div class="flex flex-col h-full">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ item.title }}</h3>
          <p class="text-gray-600 text-sm mb-4">{{ item.description }}</p>
          <div class="mt-auto">
            <span v-if="item.count !== null" class="text-sm text-blue-600 font-medium">
              {{ item.count }} {{ item.count === 1 ? 'élément' : 'éléments' }}
            </span>
          </div>
        </div>
      </button>
    </div>
  </div>
</template>