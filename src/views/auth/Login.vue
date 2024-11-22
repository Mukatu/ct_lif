<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('')
const password = ref('')
const error = ref('')

const handleLogin = async () => {
  try {
    await authStore.login(email.value, password.value)
    // Redirect based on user role
    if (authStore.user?.role === 'hr') {
      router.push('/hr')
    } else if (authStore.user?.role === 'internal_comm') {
      router.push('/feed')
    } else {
      router.push('/feed')
    }
  } catch (err) {
    error.value = 'Email ou mot de passe incorrect'
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow">
      <div>
        <img 
          src="/images/logo ct_small.jpeg" 
          alt="CT Logo" 
          class="mx-auto h-12 w-auto"
        />
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          INTRANET
        </h2>
      </div>

      <!-- Demo credentials info -->
      <div class="mt-4 p-4 bg-gray-50 rounded-md">
        <p class="text-sm text-gray-600">
          <strong>Identifiants de démonstration:</strong>
        </p>
        <div class="mt-2 space-y-1">
          <p class="text-sm text-gray-600">
            <strong>Communication Interne:</strong><br>
            Email: comm@example.com<br>
            Mot de passe: password
          </p>
          <p class="text-sm text-gray-600">
            <strong>Ressources Humaines:</strong><br>
            Email: rh@example.com<br>
            Mot de passe: password
          </p>
          <p class="text-sm text-gray-600">
            <strong>Employé:</strong><br>
            Email: employee@example.com<br>
            Mot de passe: password
          </p>
        </div>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="email" class="sr-only">Email</label>
            <input
              v-model="email"
              id="email"
              name="email"
              type="email"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-[#F2682C] focus:border-[#F2682C] focus:z-10 sm:text-sm"
              placeholder="Adresse email"
            />
          </div>
          <div>
            <label for="password" class="sr-only">Mot de passe</label>
            <input
              v-model="password"
              id="password"
              name="password"
              type="password"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-[#F2682C] focus:border-[#F2682C] focus:z-10 sm:text-sm"
              placeholder="Mot de passe"
            />
          </div>
        </div>

        <div v-if="error" class="text-red-600 text-sm text-center">
          {{ error }}
        </div>

        <div>
          <button
            type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#F2682C] hover:bg-[#e55a20] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F2682C]"
          >
            Se connecter
          </button>
        </div>
      </form>
    </div>
  </div>
</template>