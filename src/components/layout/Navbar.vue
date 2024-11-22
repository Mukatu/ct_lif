<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const isHR = computed(() => authStore.user?.role === 'hr')
const isInternalComm = computed(() => authStore.user?.role === 'internal_comm')
const isEmployee = computed(() => authStore.user?.role === 'employee')

const navigateToProfile = () => {
  if (isEmployee.value) {
    router.push('/profile')
  }
}

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}
</script>

<template>
  <nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex justify-between h-16">
        <div class="flex items-center space-x-4">
          <img 
            src="/images/logo ct_small.jpeg"
            alt="CT Logo" 
            class="h-8 w-auto cursor-pointer"
            @click="router.push('/')"
          />
          <h1 class="text-xl font-bold text-gray-800">Congo Telecom</h1>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 flex justify-center">
          <div class="hidden sm:flex sm:space-x-8">
            <router-link 
              to="/feed"
              class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2"
              :class="$route.path === '/feed' ? 'border-[#F2682C]' : 'border-transparent hover:border-[#F2682C]'"
            >
              Actualités
            </router-link>
            <router-link 
              to="/gallery"
              class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-[#F2682C]"
            >
              Galerie
            </router-link>
          </div>
        </div>

        <!-- User Menu -->
        <div class="flex items-center space-x-4">
          <div 
            v-if="authStore.user"
            class="flex items-center space-x-3 cursor-pointer"
            @click="navigateToProfile"
          >
            <img 
              :src="`https://ui-avatars.com/api/?name=${authStore.user.name}&background=random`"
              :alt="authStore.user.name"
              class="h-8 w-8 rounded-full"
            />
            <span class="text-gray-700">{{ authStore.user.name }}</span>
          </div>
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