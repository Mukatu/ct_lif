<script setup lang="ts">
import { useRouter } from 'vue-router'

const router = useRouter()

const handleLogin = () => {
  router.push('/login')
}
</script>

<template>
  <div class="h-screen w-full flex overflow-hidden">
    <!-- Left Column -->
    <div class="w-2/5 bg-[#42C9E7] p-16 flex flex-col justify-between">
      <div class="space-y-4">
        <h1 class="text-3xl font-bold text-white leading-tight">
          Bienvenue sur le portail<br>communautaire de<br>Congo Telecom
        </h1>
        <p class="text-xl text-white">
          Profitez de toute l'actualité d'entreprise,<br>de la documentation et des formations.
        </p>
      </div>
      
      <!-- Promotion Box -->
      <div class="relative">
        <img 
          src='/images/ctimg3.png'
          alt="Rentrée numérique" 
          class="w-[500px] h-[300px] "
        />
        <!-- <div class="absolute top-6 left-6">
          <div class="bg-white text-blue-500 px-6 py-2 rounded-full font-bold text-lg">
            C'est la Rentrée NUMERIQUE !
          </div>
        </div>
        <div class="absolute bottom-6 left-6">
          <div class="bg-blue-600 text-white px-6 py-2 rounded-lg text-lg font-medium">
            BOX INTERNET GRATUITE !
          </div>
        </div> -->
      </div>
    </div>

    <!-- Right Column -->
    <div class="w-3/5 bg-white p-16 flex flex-col justify-between">
      <!-- Speed Logo -->
      <div>
        <img 
          src="/images/logo ct_small.jpeg"
          alt="Speed Logo" 
          class="w-[80px] h-[48px]"
        />
      </div>

      <!-- Family Image and Login Button -->
      <div class="flex flex-col items-center space-y-8">
        <img 
          src="/images/ctimg4.jpg"
          alt="Famille heureuse" 
          class="w-[4S0px] h-[400px]"
        />
        <button
          @click="handleLogin"
          class="bg-[#42C9E7] text-white px-16 py-3 rounded-full text-xl font-semibold hover:bg-[#3bb5d0] transition-colors"
        >
          Connexion
        </button>
      </div>
    </div>
  </div>
</template>