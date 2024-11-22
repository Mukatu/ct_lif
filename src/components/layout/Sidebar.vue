<script setup lang="ts">
import { computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import {
  HomeIcon,
  CalendarIcon,
  DocumentTextIcon,
  AcademicCapIcon,
  UsersIcon,
  ChartBarIcon
} from '@heroicons/vue/24/outline'

const router = useRouter()
const route = useRoute()

const menuItems = [
  {
    name: 'Dashboard RH',
    route: 'hr-dashboard',
    icon: HomeIcon
  },
  {
    name: 'Gestion des congés',
    route: 'leave-management',
    icon: CalendarIcon
  },
  {
    name: 'Gestion documentaire',
    route: 'document-management',
    icon: DocumentTextIcon
  },
  {
    name: 'Gestion des formations',
    route: 'training-management',
    icon: AcademicCapIcon
  },
  {
    name: 'Gestion du personnel',
    route: 'employee-management',
    icon: UsersIcon
  },
  {
    name: 'Gestion des performances',
    route: 'performance-management',
    icon: ChartBarIcon
  }
]

const isActive = (routeName: string) => {
  return route.name === routeName
}
</script>

<template>
  <aside class="w-72 bg-white shadow-lg h-[calc(100vh-64px)] fixed left-0 top-16 overflow-y-auto">
    <nav class="p-6 space-y-3">
      <router-link
        v-for="item in menuItems"
        :key="item.route"
        :to="{ name: item.route }"
        class="flex items-center px-6 py-4 rounded-lg transition-colors"
        :class="{
          'bg-[#F2682C] text-white': isActive(item.route),
          'text-gray-700 hover:bg-gray-100': !isActive(item.route)
        }"
      >
        <component
          :is="item.icon"
          :class="[
            'w-6 h-6 mr-4',
            isActive(item.route) ? 'text-white' : 'text-blue-600'
          ]"
        />
        <span class="font-medium text-base">{{ item.name }}</span>
      </router-link>
    </nav>
  </aside>
</template>