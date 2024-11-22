<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import { 
  DocumentTextIcon,
  CalendarIcon,
  UserIcon,
  FlagIcon,
  ChatBubbleLeftIcon,
  ChartBarIcon
} from '@heroicons/vue/24/outline'

const router = useRouter()
const authStore = useAuthStore()

const isEmployee = computed(() => authStore.user?.role === 'employee')
const isInternalComm = computed(() => authStore.user?.role === 'internal_comm')

const employeeMenuItems = [
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
]

const commMenuItems = [
  {
    name: 'Contenu signalé',
    icon: FlagIcon,
    path: '/moderation/reports'
  },
  {
    name: 'Commentaires',
    icon: ChatBubbleLeftIcon,
    path: '/moderation/comments'
  },
  {
    name: 'Statistiques',
    icon: ChartBarIcon,
    path: '/moderation/stats'
  }
]

const menuItems = computed(() => 
  isEmployee.value ? employeeMenuItems : 
  isInternalComm.value ? commMenuItems : []
)
</script>

<template>
  <div v-if="menuItems.length > 0" class="w-72 bg-white shadow-lg fixed left-0 top-16 h-[calc(100vh-64px)] p-6">
    <nav class="space-y-3">
      <router-link
        v-for="item in menuItems"
        :key="item.path"
        :to="item.path"
        class="flex items-center px-4 py-3 rounded-lg transition-colors"
        :class="{
          'bg-[#F2682C] text-white': $route.path === item.path,
          'text-gray-700 hover:bg-gray-100': $route.path !== item.path
        }"
      >
        <component
          :is="item.icon"
          :class="[
            'w-5 h-5 mr-3',
            $route.path === item.path ? 'text-white' : 'text-blue-600'
          ]"
        />
        <span class="font-medium">{{ item.name }}</span>
      </router-link>
    </nav>
  </div>
</template>