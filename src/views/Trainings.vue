<script setup lang="ts">
import { ref, computed } from 'vue'
import VideoModal from '../components/VideoModal.vue'

const searchQuery = ref('')
const showModal = ref(false)
const selectedVideo = ref(null)

const categories = [
  'Tous',
  'RH',
  'Management',
  'Communication',
  'Développement personnel'
]

const selectedCategory = ref('Tous')

const allTrainings = ref([
  {
    id: 1,
    title: 'Introduction aux procédures RH',
    description: 'Découvrez les bases des procédures RH et leur importance.',
    duration: '15 min',
    level: 'Débutant',
    category: 'RH',
    url: 'https://example.com/video1',
    thumbnail: 'https://picsum.photos/400/225'
  },
  {
    id: 2,
    title: 'Gestion du temps et productivité',
    description: 'Apprenez à mieux gérer votre temps et augmenter votre productivité.',
    duration: '20 min',
    level: 'Intermédiaire',
    category: 'Développement personnel',
    url: 'https://example.com/video2',
    thumbnail: 'https://picsum.photos/400/225'
  },
  // ... autres formations
])

const filteredTrainings = computed(() => {
  return allTrainings.value
    .filter(training => {
      const matchesSearch = training.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                          training.description.toLowerCase().includes(searchQuery.value.toLowerCase())
      const matchesCategory = selectedCategory.value === 'Tous' || training.category === selectedCategory.value
      return matchesSearch && matchesCategory
    })
})

const openVideo = (training) => {
  selectedVideo.value = training
  showModal.value = true
}
</script>

<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Catalogue des formations</h1>

    <div class="mb-8 space-y-4">
      <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0">
        <div class="flex-1">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Rechercher une formation..."
            class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
          />
        </div>
        <div class="w-full md:w-64">
          <select
            v-model="selectedCategory"
            class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
          >
            <option v-for="category in categories" :key="category" :value="category">
              {{ category }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
      <div
        v-for="training in filteredTrainings"
        :key="training.id"
        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
      >
        <img
          :src="training.thumbnail"
          :alt="training.title"
          class="w-full h-48 object-cover"
        />
        <div class="p-4">
          <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ training.title }}</h3>
          <p class="text-gray-600 mb-4">{{ training.description }}</p>
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
              <span>{{ training.duration }}</span>
              <span class="mx-2">•</span>
              <span>{{ training.level }}</span>
            </div>
            <button
              @click="openVideo(training)"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              Démarrer
            </button>
          </div>
        </div>
      </div>
    </div>

    <VideoModal
      v-if="showModal"
      :video="selectedVideo"
      @close="showModal = false"
    />
  </div>
</template>