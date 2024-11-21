<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const menuItems = ref([
  { name: 'Documentation', path: '/documentation', icon: 'document-text' },
  { name: 'Demande de congé', path: '/leave-request', icon: 'calendar' }
])
</script>

<template>
  <div class="bg-white rounded-lg shadow p-4">
    <nav class="space-y-2">
      <router-link
        v-for="item in menuItems"
        :key="item.name"
        :to="item.path"
        class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md transition-colors"
        active-class="bg-blue-50 text-blue-600"
      >
        <span>{{ item.name }}</span>
      </router-link>
    </nav>
  </div>
</template>