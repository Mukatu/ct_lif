<script setup lang="ts">
import { ref, computed } from 'vue'
import { useAuthStore } from '../../stores/auth'
import { PaperClipIcon, PhotoIcon, VideoCameraIcon } from '@heroicons/vue/24/outline'
import MediaUploadModal from './MediaUploadModal.vue'
import PostCard from './PostCard.vue'

const authStore = useAuthStore()
const canPublish = computed(() => authStore.user?.role === 'internal_comm')

const showMediaModal = ref(false)
const selectedMediaType = ref('image')
const postMedia = ref(null)

const posts = ref([
  {
    id: 1,
    author: {
      name: 'Communication Interne',
      avatar: 'https://picsum.photos/40/40'
    },
    content: 'Bienvenue sur notre nouveau portail RH ! N\'hésitez pas à explorer toutes les fonctionnalités.',
    media: null,
    date: '2024-02-20',
    likes: 5,
    liked: false,
    comments: [
      {
        id: 1,
        author: {
          name: 'Jean Dupont',
          avatar: 'https://picsum.photos/41/41'
        },
        content: 'Super initiative !',
        date: '2024-02-20'
      }
    ]
  }
])

const newPost = ref('')

const handleMediaSelect = (file) => {
  postMedia.value = {
    file,
    type: selectedMediaType.value,
    preview: file.type.startsWith('image/') ? URL.createObjectURL(file) : null
  }
  showMediaModal.value = false
}

const createPost = () => {
  if (newPost.value.trim() || postMedia.value) {
    posts.value.unshift({
      id: Date.now(),
      author: {
        name: authStore.user?.name || 'Utilisateur',
        avatar: 'https://picsum.photos/40/40'
      },
      content: newPost.value,
      media: postMedia.value ? {
        type: postMedia.value.type,
        url: postMedia.value.preview
      } : null,
      date: new Date().toISOString().split('T')[0],
      likes: 0,
      liked: false,
      comments: []
    })
    newPost.value = ''
    postMedia.value = null
  }
}

const openMediaModal = (type) => {
  selectedMediaType.value = type
  showMediaModal.value = true
}

const toggleLike = (postId: number) => {
  const post = posts.value.find(p => p.id === postId)
  if (post) {
    post.liked = !post.liked
    post.likes += post.liked ? 1 : -1
  }
}

const addComment = (postId: number, content: string) => {
  const post = posts.value.find(p => p.id === postId)
  if (post) {
    post.comments.push({
      id: Date.now(),
      author: {
        name: authStore.user?.name || 'Utilisateur',
        avatar: 'https://picsum.photos/40/40'
      },
      content,
      date: new Date().toISOString().split('T')[0]
    })
  }
}
</script>

<template>
  <div class="space-y-4">
    <!-- Création de post -->
    <div v-if="canPublish" class="bg-white rounded-lg shadow p-4">
      <div class="mb-4">
        <textarea
          v-model="newPost"
          rows="3"
          class="w-full p-2 border rounded-lg resize-none focus:ring-2 focus:ring-[#F2682C] focus:border-[#F2682C]"
          placeholder="Quoi de neuf ?"
        ></textarea>
        
        <!-- Preview du média -->
        <div v-if="postMedia && postMedia.preview" class="mt-2">
          <img :src="postMedia.preview" alt="Media preview" class="max-h-48 rounded-lg" />
        </div>
      </div>
      
      <div class="flex items-center justify-between">
        <div class="flex space-x-2">
          <button
            @click="openMediaModal('image')"
            class="flex items-center space-x-1 text-gray-500 hover:text-[#F2682C]"
          >
            <PhotoIcon class="w-5 h-5" />
            <span>Image</span>
          </button>
          <button
            @click="openMediaModal('video')"
            class="flex items-center space-x-1 text-gray-500 hover:text-[#F2682C]"
          >
            <VideoCameraIcon class="w-5 h-5" />
            <span>Vidéo</span>
          </button>
          <button
            @click="openMediaModal('document')"
            class="flex items-center space-x-1 text-gray-500 hover:text-[#F2682C]"
          >
            <PaperClipIcon class="w-5 h-5" />
            <span>Document</span>
          </button>
        </div>
        <button
          @click="createPost"
          class="px-4 py-2 bg-[#F2682C] text-white rounded-lg hover:bg-[#e55a20] transition-colors"
          :disabled="!newPost.trim() && !postMedia"
        >
          Publier
        </button>
      </div>
    </div>

    <!-- Liste des posts -->
    <PostCard
      v-for="post in posts"
      :key="post.id"
      :post="post"
      @like="toggleLike"
      @comment="addComment"
      :can-edit="canPublish"
    />

    <!-- Modal d'upload de média -->
    <MediaUploadModal
      v-if="showMediaModal"
      @close="showMediaModal = false"
      @file-selected="handleMediaSelect"
    />
  </div>
</template>