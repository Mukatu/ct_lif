<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const isHR = computed(() => authStore.user?.role === 'hr')

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}

const navigateToHR = () => {
  router.push('/hr')
}

const navigateToFeed = () => {
  router.push('/feed')
}
</script>

<template>
  <nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex justify-between h-16">
        <div class="flex items-center space-x-4">
          <img 
            src="https://picsum.photos/240/48" 
            alt="Speed Logo" 
            class="h-8 w-auto"
          />
          <h1 class="text-xl font-bold text-gray-800">Portail RH</h1>
        </div>
        <div class="flex-1 flex justify-center">
          <div class="hidden sm:flex sm:space-x-8">
            <button 
              v-if="isHR"
              @click="navigateToHR"
              class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2"
              :class="$route.path.startsWith('/hr') ? 'border-[#F2682C]' : 'border-transparent hover:border-[#F2682C]'"
            >
              Dashboard RH
            </button>
            <button 
              @click="navigateToFeed"
              class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2"
              :class="$route.path === '/feed' ? 'border-[#F2682C]' : 'border-transparent hover:border-[#F2682C]'"
            >
              Feed
            </button>
            <router-link 
              to="/gallery"
              class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-[#F2682C]"
            >
              Galerie
            </router-link>
            <router-link 
              to="/documentation"
              class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-[#F2682C]"
            >
              Documentation
            </router-link>
          </div>
        </div>
        <div class="flex items-center">
          <span class="text-gray-700 mr-4">{{ authStore.user?.name }}</span>
          <button 
            @click="handleLogout"
            class="bg-[#F2682C] text-white px-4 py-2 rounded-lg hover:bg-[#e55a20] transition-colors"
          >
            Déconnexion
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>