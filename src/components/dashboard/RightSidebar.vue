<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import VideoModal from '../VideoModal.vue'

const router = useRouter()
const showModal = ref(false)
const selectedVideo = ref(null)

const trainings = ref([
  {
    id: 1,
    title: 'Introduction aux procédures RH',
    duration: '15 min',
    level: 'Débutant',
    url: 'https://example.com/video1',
    thumbnail: 'https://picsum.photos/70/40'
  },
  {
    id: 2,
    title: 'Gestion du temps et productivité',
    duration: '20 min',
    level: 'Intermédiaire',
    url: 'https://example.com/video2',
    thumbnail: 'https://picsum.photos/70/40'
  },
  {
    id: 3,
    title: 'Leadership et management d\'équipe',
    duration: '30 min',
    level: 'Avancé',
    url: 'https://example.com/video3',
    thumbnail: 'https://picsum.photos/70/40'
  },
  {
    id: 4,
    title: 'Communication efficace',
    duration: '25 min',
    level: 'Intermédiaire',
    url: 'https://example.com/video4',
    thumbnail: 'https://picsum.photos/70/40'
  }
])

const openVideo = (training) => {
  selectedVideo.value = training
  showModal.value = true
}

const goToTrainings = () => {
  router.push('/trainings')
}
</script>

<template>
  <div class="bg-white rounded-lg shadow p-4">
    <h2 class="text-base font-semibold text-gray-900 mb-4">Formations disponibles</h2>
    <div class="space-y-3">
      <button 
        v-for="training in trainings" 
        :key="training.id" 
        @click="openVideo(training)"
        class="block w-full text-left hover:bg-gray-50 rounded-lg p-2 transition-colors"
      >
        <div class="flex space-x-2">
          <img 
            :src="training.thumbnail" 
            :alt="training.title"
            class="w-[70px] h-[40px] rounded object-cover"
          />
          <div class="flex-1">
            <h3 class="font-medium text-gray-900 text-sm mb-0.5 line-clamp-2">{{ training.title }}</h3>
            <div class="flex items-center text-xs text-gray-500">
              <span>{{ training.duration }}</span>
              <span class="mx-1">•</span>
              <span>{{ training.level }}</span>
            </div>
          </div>
        </div>
      </button>
    </div>
    <div class="mt-4 text-center">
      <button 
        @click="goToTrainings"
        class="text-[#F2682C] hover:text-[#e55a20] font-medium text-sm"
      >
        Voir toutes les formations
      </button>
    </div>
  </div>

  <VideoModal
    v-if="showModal"
    :video="selectedVideo"
    @close="showModal = false"
  />
</template>