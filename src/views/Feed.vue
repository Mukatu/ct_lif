<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import LeftSidebar from '../components/dashboard/LeftSidebar.vue'
import FeedContent from '../components/dashboard/Feed.vue'
import RightSidebar from '../components/dashboard/RightSidebar.vue'

const router = useRouter()

const galleryTiles = ref([
  {
    id: 1,
    title: 'Team Building 2024',
    description: 'Découvrez nos moments forts',
    image: 'https://picsum.photos/400/300'
  },
  {
    id: 2,
    title: 'Séminaire RH',
    description: 'Formation continue',
    image: 'https://picsum.photos/400/301'
  },
  {
    id: 3,
    title: 'Fête de fin d\'année',
    description: 'Célébration annuelle',
    image: 'https://picsum.photos/400/302'
  }
])

const navigateToGallery = () => {
  router.push('/gallery')
}
</script>

<template>
  <div class="min-h-[calc(100vh-64px)] bg-gray-50">
    <div class="container mx-auto py-6">
      <div class="grid grid-cols-24 gap-4 h-[calc(100vh-96px)]">
        <!-- Left Menu -->
        <div class="col-span-5 sticky top-24">
          <LeftSidebar />
        </div>
        
        <!-- Feed Content -->
        <div class="col-span-12 overflow-y-auto px-4">
          <!-- Gallery Tiles -->
          <div class="grid grid-cols-3 gap-3 mb-6">
            <div
              v-for="tile in galleryTiles"
              :key="tile.id"
              @click="navigateToGallery"
              class="bg-white rounded-lg shadow-md overflow-hidden cursor-pointer transform transition-transform hover:scale-105"
            >
              <img
                :src="tile.image"
                :alt="tile.title"
                class="w-full h-24 object-cover"
              />
              <div class="p-3">
                <h3 class="text-[#F2682C] font-semibold text-sm mb-1">{{ tile.title }}</h3>
                <p class="text-xs text-gray-600">{{ tile.description }}</p>
              </div>
            </div>
          </div>

          <!-- Feed -->
          <FeedContent />
        </div>
        
        <!-- Right Sidebar -->
        <div class="col-span-7 sticky top-24">
          <RightSidebar />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: #F2682C #f1f1f1;
}

.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #F2682C;
  border-radius: 3px;
}
</style>