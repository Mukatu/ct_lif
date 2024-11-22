<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { blogService } from '../services/api'
import { format } from 'date-fns'
import fr from 'date-fns/locale/fr'
import PostModal from '../components/blog/PostModal.vue'
import PhotoModal from '../components/blog/PhotoModal.vue'

const route = useRoute()
const authStore = useAuthStore()

const isInternalComm = computed(() => authStore.user?.role === 'internal_comm')
const loading = ref(false)
const posts = ref([])
const photos = ref([])
const selectedCategory = ref('all')
const showPostModal = ref(false)
const showPhotoModal = ref(false)
const selectedPost = ref(null)
const selectedPhoto = ref(null)

const categories = [
  { id: 'all', name: 'Tous' },
  { id: 'events', name: 'Événements' },
  { id: 'training', name: 'Formation' },
  { id: 'news', name: 'Actualités' },
  { id: 'projects', name: 'Projets' },
  { id: 'photos', name: 'Photos' }
]

const filteredContent = computed(() => {
  if (selectedCategory.value === 'photos') return photos.value
  if (selectedCategory.value === 'all') return posts.value
  return posts.value.filter(post => post.category === selectedCategory.value)
})

const fetchContent = async () => {
  try {
    loading.value = true
    const [postsResponse, photosResponse] = await Promise.all([
      blogService.getPosts(),
      blogService.getPhotos()
    ])
    posts.value = postsResponse.data.data
    photos.value = photosResponse.data.data

    // Si un postId est fourni dans l'URL, scrollez jusqu'à ce post
    const postId = route.query.postId
    if (postId) {
      nextTick(() => {
        const element = document.getElementById(`post-${postId}`)
        if (element) {
          element.scrollIntoView({ behavior: 'smooth' })
        }
      })
    }
  } catch (error) {
    console.error('Error fetching content:', error)
  } finally {
    loading.value = false
  }
}

const handlePostAction = async (action: 'edit' | 'delete', post = null) => {
  if (action === 'edit') {
    selectedPost.value = post
    showPostModal.value = true
  } else if (action === 'delete' && post) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
      try {
        await blogService.deletePost(post.id)
        await fetchContent()
      } catch (error) {
        console.error('Error deleting post:', error)
      }
    }
  }
}

const handlePhotoClick = (photo) => {
  selectedPhoto.value = photo
  showPhotoModal.value = true
}

onMounted(fetchContent)
</script>

<template>
  <div class="min-h-[calc(100vh-64px)] bg-gray-50 py-8">
    <div class="container mx-auto px-4">
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Galerie</h1>
        <button
          v-if="isInternalComm"
          @click="handlePostAction('edit')"
          class="px-4 py-2 bg-[#F2682C] text-white rounded-lg hover:bg-[#e55a20]"
        >
          Nouvel article
        </button>
      </div>

      <!-- Categories -->
      <div class="flex space-x-4 mb-8 overflow-x-auto pb-2">
        <button
          v-for="category in categories"
          :key="category.id"
          @click="selectedCategory = category.id"
          class="px-4 py-2 rounded-full whitespace-nowrap transition-colors"
          :class="selectedCategory === category.id
            ? 'bg-[#F2682C] text-white'
            : 'bg-white text-gray-700 hover:bg-gray-100'"
        >
          {{ category.name }}
        </button>
      </div>

      <!-- Content Grid -->
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <template v-if="selectedCategory === 'photos'">
          <!-- Photos Grid -->
          <div
            v-for="photo in photos"
            :key="photo.id"
            class="aspect-square cursor-pointer overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow"
            @click="handlePhotoClick(photo)"
          >
            <img 
              :src="photo.url" 
              :alt="photo.title"
              class="w-full h-full object-cover"
            />
          </div>
        </template>
        <template v-else>
          <!-- Posts Grid -->
          <article
            v-for="post in filteredContent"
            :key="post.id"
            :id="'post-' + post.id"
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
          >
            <img 
              :src="post.image" 
              :alt="post.title"
              class="w-full h-40 object-cover"
            />
            
            <div class="p-4">
              <div class="flex items-center space-x-2 mb-2">
                <img 
                  :src="post.author.avatar" 
                  :alt="post.author.name"
                  class="w-6 h-6 rounded-full"
                />
                <span class="text-sm text-gray-600">{{ post.author.name }}</span>
              </div>
              
              <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ post.title }}</h3>
              <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ post.description }}</p>
              
              <div class="flex items-center justify-between text-sm">
                <span class="text-[#F2682C]">{{ post.category }}</span>
                <span class="text-gray-500">
                  {{ format(new Date(post.date), 'dd MMM yyyy', { locale: fr }) }}
                </span>
              </div>

              <div v-if="isInternalComm" class="mt-3 flex justify-end space-x-2">
                <button
                  @click="handlePostAction('edit', post)"
                  class="text-[#F2682C] hover:text-[#e55a20]"
                >
                  <PencilIcon class="w-5 h-5" />
                </button>
                <button
                  @click="handlePostAction('delete', post)"
                  class="text-red-600 hover:text-red-800"
                >
                  <TrashIcon class="w-5 h-5" />
                </button>
              </div>
            </div>
          </article>
        </template>
      </div>
    </div>

    <!-- Modals -->
    <PostModal
      v-if="showPostModal"
      :post="selectedPost"
      :mode="selectedPost ? 'edit' : 'add'"
      @close="showPostModal = false"
      @save="handlePostSave"
    />

    <PhotoModal
      v-if="showPhotoModal"
      :photo="selectedPhoto"
      @close="showPhotoModal = false"
    />
  </div>
</template>