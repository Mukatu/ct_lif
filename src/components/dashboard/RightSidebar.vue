<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import VideoModal from '../VideoModal.vue'
import ModerationSidebar from '../moderation/ModerationSidebar.vue'

const router = useRouter()
const authStore = useAuthStore()

const isInternalComm = computed(() => authStore.user?.role === 'internal_comm')
const isEmployee = computed(() => authStore.user?.role === 'employee')

// Formations pour les employés
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
    title: 'Communication efficace',
    duration: '25 min',
    level: 'Intermédiaire',
    url: 'https://example.com/video3',
    thumbnail: 'https://picsum.photos/70/40'
  }
])

const showModal = ref(false)
const selectedVideo = ref(null)

const openVideo = (training) => {
  selectedVideo.value = training
  showModal.value = true
}

const closeVideo = () => {
  showModal.value = false
  selectedVideo.value = null
}
</script>

<template>
  <!-- Sidebar pour les employés -->
  <div v-if="isEmployee" class="bg-white rounded-lg shadow p-4">
    <h2 class="text-base font-semibold text-gray-900 mb-4">Formations recommandées</h2>
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

    <VideoModal
      v-if="showModal"
      :video="selectedVideo"
      @close="closeVideo"
    />
  </div>

  <!-- Sidebar pour les modérateurs -->
  <ModerationSidebar v-if="isInternalComm" />
</template>