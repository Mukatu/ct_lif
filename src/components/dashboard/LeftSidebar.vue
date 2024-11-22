<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import { 
  DocumentTextIcon,
  CalendarIcon,
  UserIcon
} from '@heroicons/vue/24/outline'

const router = useRouter()
const authStore = useAuthStore()

const isEmployee = computed(() => authStore.user?.role === 'employee')

const menuItems = computed(() => [
  {
    name: 'Documentation',
    icon: DocumentTextIcon,
    path: '/documentation'
  },
  {
    name: 'Demande de congés',
    icon: CalendarIcon,
    path: '/leave-request'
  },
  {
    name: 'Mon profil',
    icon: UserIcon,
    path: '/profile'
  }
])
</script>

<template>
  <div v-if="isEmployee" class="bg-white rounded-lg shadow p-4">
    <nav class="space-y-2">
      <router-link
        v-for="item in menuItems"
        :key="item.name"
        :to="item.path"
        class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md transition-colors"
        active-class="bg-[#F2682C] text-white"
      >
        <component 
          :is="item.icon" 
          class="w-5 h-5 mr-3 text-blue-600"
        />
        <span>{{ item.name }}</span>
      </router-link>
    </nav>
  </div>
</template>