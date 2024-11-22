<script setup lang="ts">
import { ref, computed } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useNotification } from '../composables/useNotification'
import {
  UserIcon,
  PencilIcon,
  KeyIcon,
  CogIcon
} from '@heroicons/vue/24/outline'

const authStore = useAuthStore()
const notification = useNotification()
const isEmployee = computed(() => authStore.user?.role === 'employee')

const activeTab = ref('overview')
const profileImage = ref(null)
const loading = ref(false)

const formData = ref({
  name: authStore.user?.name || '',
  email: authStore.user?.email || '',
  department: authStore.user?.department || '',
  position: authStore.user?.position || '',
  currentPassword: '',
  newPassword: '',
  confirmPassword: ''
})

const tabs = [
  { key: 'overview', label: 'Vue d\'ensemble', icon: UserIcon },
  { key: 'edit', label: 'Modifier le profil', icon: PencilIcon },
  { key: 'password', label: 'Changer le mot de passe', icon: KeyIcon }
]

const handleImageUpload = async (event) => {
  const file = event.target.files[0]
  if (file) {
    try {
      loading.value = true
      const formData = new FormData()
      formData.append('avatar', file)
      // Simuler l'upload
      await new Promise(resolve => setTimeout(resolve, 1000))
      const reader = new FileReader()
      reader.onload = (e) => {
        profileImage.value = e.target.result
      }
      reader.readAsDataURL(file)
      notification.show({
        type: 'success',
        message: 'Photo de profil mise à jour'
      })
    } catch (error) {
      notification.show({
        type: 'error',
        message: 'Erreur lors de la mise à jour de la photo'
      })
    } finally {
      loading.value = false
    }
  }
}

const updateProfile = async () => {
  try {
    loading.value = true
    // Simuler la mise à jour
    await new Promise(resolve => setTimeout(resolve, 1000))
    notification.show({
      type: 'success',
      message: 'Profil mis à jour avec succès'
    })
  } catch (error) {
    notification.show({
      type: 'error',
      message: 'Erreur lors de la mise à jour du profil'
    })
  } finally {
    loading.value = false
  }
}

const updatePassword = async () => {
  if (formData.value.newPassword !== formData.value.confirmPassword) {
    notification.show({
      type: 'error',
      message: 'Les mots de passe ne correspondent pas'
    })
    return
  }

  try {
    loading.value = true
    // Simuler la mise à jour
    await new Promise(resolve => setTimeout(resolve, 1000))
    formData.value.currentPassword = ''
    formData.value.newPassword = ''
    formData.value.confirmPassword = ''
    notification.show({
      type: 'success',
      message: 'Mot de passe mis à jour avec succès'
    })
  } catch (error) {
    notification.show({
      type: 'error',
      message: 'Erreur lors de la mise à jour du mot de passe'
    })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div v-if="isEmployee" class="max-w-7xl mx-auto py-8 px-4">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-900">Mon Profil</h1>
      <p class="mt-2 text-gray-600">Gérez vos informations personnelles et vos préférences</p>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
      <!-- Profile Header -->
      <div class="relative h-32 bg-gradient-to-r from-[#F2682C] to-[#e55a20]">
        <div class="absolute -bottom-16 left-8">
          <div class="relative">
            <img
              :src="profileImage || `https://ui-avatars.com/api/?name=${formData.name}&background=random`"
              :alt="formData.name"
              class="w-32 h-32 rounded-full border-4 border-white object-cover"
            />
            <label class="absolute bottom-0 right-0 bg-white rounded-full p-2 shadow-lg cursor-pointer hover:bg-gray-50">
              <input 
                type="file" 
                class="hidden" 
                accept="image/*" 
                @change="handleImageUpload"
                :disabled="loading"
              >
              <CogIcon class="w-5 h-5 text-gray-600" />
            </label>
          </div>
        </div>
      </div>

      <!-- Profile Navigation -->
      <div class="pt-20 px-8 border-b border-gray-200">
        <nav class="flex space-x-8">
          <button
            v-for="tab in tabs"
            :key="tab.key"
            class="pb-4 px-2 flex items-center space-x-2 border-b-2 font-medium text-sm transition-colors"
            :class="[
              activeTab === tab.key
                ? 'border-[#F2682C] text-[#F2682C]'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
            @click="activeTab = tab.key"
          >
            <component :is="tab.icon" class="w-5 h-5" />
            <span>{{ tab.label }}</span>
          </button>
        </nav>
      </div>

      <!-- Content -->
      <div class="p-8">
        <!-- Overview Tab -->
        <div v-if="activeTab === 'overview'" class="space-y-6">
          <div>
            <h3 class="text-lg font-medium text-gray-900">Informations personnelles</h3>
            <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div class="bg-gray-50 px-4 py-3 rounded-lg">
                <dt class="text-sm font-medium text-gray-500">Nom complet</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ formData.name }}</dd>
              </div>
              <div class="bg-gray-50 px-4 py-3 rounded-lg">
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ formData.email }}</dd>
              </div>
              <div class="bg-gray-50 px-4 py-3 rounded-lg">
                <dt class="text-sm font-medium text-gray-500">Direction</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ formData.department }}</dd>
              </div>
              <div class="bg-gray-50 px-4 py-3 rounded-lg">
                <dt class="text-sm font-medium text-gray-500">Poste</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ formData.position }}</dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Edit Profile Tab -->
        <div v-else-if="activeTab === 'edit'" class="max-w-xl">
          <form @submit.prevent="updateProfile" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Nom complet</label>
              <input
                v-model="formData.name"
                type="text"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input
                v-model="formData.email"
                type="email"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Direction</label>
              <input
                v-model="formData.department"
                type="text"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Poste</label>
              <input
                v-model="formData.position"
                type="text"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
              />
            </div>

            <div class="flex justify-end">
              <button
                type="submit"
                class="px-4 py-2 bg-[#F2682C] text-white rounded-lg hover:bg-[#e55a20] disabled:opacity-50"
                :disabled="loading"
              >
                {{ loading ? 'Mise à jour...' : 'Enregistrer les modifications' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Change Password Tab -->
        <div v-else-if="activeTab === 'password'" class="max-w-xl">
          <form @submit.prevent="updatePassword" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
              <input
                v-model="formData.currentPassword"
                type="password"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
              <input
                v-model="formData.newPassword"
                type="password"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Confirmer le nouveau mot de passe</label>
              <input
                v-model="formData.confirmPassword"
                type="password"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
              />
            </div>

            <div class="flex justify-end">
              <button
                type="submit"
                class="px-4 py-2 bg-[#F2682C] text-white rounded-lg hover:bg-[#e55a20] disabled:opacity-50"
                :disabled="loading"
              >
                {{ loading ? 'Mise à jour...' : 'Changer le mot de passe' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>