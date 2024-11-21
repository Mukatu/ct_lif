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

const menuItems = [
  {
    title: 'Gestion des congés',
    route: 'leave-management',
    count: stats.value.pendingLeaves,
    icon: 'calendar'
  },
  {
    title: 'Gestion documentaire',
    route: 'document-management',
    count: null,
    icon: 'document'
  },
  {
    title: 'Gestion des formations',
    route: 'training-management',
    count: stats.value.activeTrainings,
    icon: 'academic-cap'
  },
  {
    title: 'Gestion du personnel',
    route: 'employee-management',
    count: stats.value.activeEmployees,
    icon: 'users'
  },
  {
    title: 'Gestion des performances',
    route: 'performance-management',
    count: stats.value.pendingEvaluations,
    icon: 'chart-bar'
  }
]
</script>

<template>
  <div class="min-h-[calc(100vh-64px)] bg-gray-50">
    <div class="container mx-auto py-6 px-4">
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Tableau de bord RH</h1>
        <p class="mt-2 text-gray-600">Bienvenue, {{ authStore.user?.name }}</p>
      </div>

      <!-- Stats Overview -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Congés</h3>
          <div class="flex justify-between items-center">
            <span class="text-3xl font-bold text-[#F2682C]">{{ stats.pendingLeaves }}</span>
            <span class="text-gray-600">Demandes en attente</span>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Personnel</h3>
          <div class="flex justify-between items-center">
            <span class="text-3xl font-bold text-[#F2682C]">{{ stats.activeEmployees }}</span>
            <span class="text-gray-600">Employés actifs</span>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance</h3>
          <div class="flex justify-between items-center">
            <span class="text-3xl font-bold text-[#F2682C]">{{ stats.averagePerformance }}%</span>
            <span class="text-gray-600">Performance moyenne</span>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
        <router-link
          v-for="item in menuItems"
          :key="item.route"
          :to="{ name: item.route }"
          class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow"
        >
          <div class="flex flex-col items-center text-center">
            <div class="text-[#F2682C] mb-4">
              <!-- Icon component would go here -->
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ item.title }}</h3>
            <span v-if="item.count !== null" class="text-sm text-gray-600">
              {{ item.count }} {{ item.count === 1 ? 'élément' : 'éléments' }}
            </span>
          </div>
        </router-link>
      </div>

      <!-- Router View for Sub-components -->
      <router-view></router-view>
    </div>
  </div>
</template>