<script setup lang="ts">
import { ref } from 'vue'

const images = ref([
  {
    id: 1,
    title: 'Team Building 2024',
    url: 'https://picsum.photos/400/300',
    description: 'Notre dernier événement team building'
  },
  {
    id: 2,
    title: 'Séminaire RH',
    url: 'https://picsum.photos/400/301',
    description: 'Formation continue des équipes'
  },
  {
    id: 3,
    title: 'Fête de fin d\'année',
    url: 'https://picsum.photos/400/302',
    description: 'Célébration des succès de l\'année'
  }
])
</script>

<template>
  <div class="min-h-[calc(100vh-64px)] bg-gray-50 pt-8">
    <div class="container mx-auto px-4">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Galerie Photos</h1>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="image in images" :key="image.id" class="bg-white rounded-lg shadow-md overflow-hidden">
          <img :src="image.url" :alt="image.title" class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="text-xl font-semibold text-[#F2682C]">{{ image.title }}</h3>
            <p class="mt-2 text-gray-600">{{ image.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>