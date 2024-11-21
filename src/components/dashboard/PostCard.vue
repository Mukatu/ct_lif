<script setup lang="ts">
import { ref } from 'vue'
import { HeartIcon, ChatBubbleLeftIcon } from '@heroicons/vue/24/outline'
import { HeartIcon as HeartSolidIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
  post: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['like', 'comment'])

const newComment = ref('')
const showComments = ref(false)

const submitComment = () => {
  if (newComment.value.trim()) {
    emit('comment', props.post.id, newComment.value)
    newComment.value = ''
  }
}

const toggleComments = () => {
  showComments.value = !showComments.value
}
</script>

<template>
  <div class="bg-white rounded-lg shadow p-4">
    <!-- En-tête du post -->
    <div class="flex items-center mb-4">
      <img
        :src="post.author.avatar"
        :alt="post.author.name"
        class="w-10 h-10 rounded-full"
      />
      <div class="ml-3">
        <div class="font-semibold">{{ post.author.name }}</div>
        <div class="text-gray-500 text-sm">{{ post.date }}</div>
      </div>
    </div>

    <!-- Contenu du post -->
    <p class="text-gray-700 mb-4">{{ post.content }}</p>

    <!-- Média attaché -->
    <div v-if="post.media" class="mb-4">
      <img
        v-if="post.media.type === 'image'"
        :src="post.media.url"
        class="rounded-lg max-h-96 mx-auto"
        alt="Post media"
      />
      <video
        v-else-if="post.media.type === 'video'"
        :src="post.media.url"
        class="rounded-lg max-h-96 mx-auto"
        controls
      ></video>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-between border-t border-b py-2 mb-4">
      <button
        @click="$emit('like', post.id)"
        class="flex items-center space-x-1 text-gray-500 hover:text-red-500"
        :class="{ 'text-red-500': post.liked }"
      >
        <component :is="post.liked ? HeartSolidIcon : HeartIcon" class="w-5 h-5" />
        <span>{{ post.likes }}</span>
      </button>
      <button
        @click="toggleComments"
        class="flex items-center space-x-1 text-gray-500 hover:text-blue-500"
      >
        <ChatBubbleLeftIcon class="w-5 h-5" />
        <span>{{ post.comments.length }}</span>
      </button>
    </div>

    <!-- Section commentaires -->
    <div v-if="showComments" class="space-y-4">
      <!-- Liste des commentaires -->
      <div v-for="comment in post.comments" :key="comment.id" class="flex space-x-3">
        <img
          :src="comment.author.avatar"
          :alt="comment.author.name"
          class="w-8 h-8 rounded-full"
        />
        <div class="flex-1 bg-gray-50 rounded-lg p-3">
          <div class="flex items-center justify-between mb-1">
            <span class="font-medium">{{ comment.author.name }}</span>
            <span class="text-sm text-gray-500">{{ comment.date }}</span>
          </div>
          <p class="text-gray-700">{{ comment.content }}</p>
        </div>
      </div>

      <!-- Formulaire nouveau commentaire -->
      <div class="flex items-start space-x-3">
        <img
          src="https://picsum.photos/32/35"
          alt="Current user"
          class="w-8 h-8 rounded-full"
        />
        <div class="flex-1">
          <textarea
            v-model="newComment"
            rows="2"
            class="w-full p-2 border rounded-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Ajouter un commentaire..."
          ></textarea>
          <button
            @click="submitComment"
            class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            :disabled="!newComment.trim()"
          >
            Commenter
          </button>
        </div>
      </div>
    </div>
  </div>
</template>