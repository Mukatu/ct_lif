<script setup lang="ts">
import { RouterView } from 'vue-router'
import Navbar from './components/layout/Navbar.vue'
import Sidebar from './components/layout/Sidebar.vue'
import { useRoute } from 'vue-router'
import { computed } from 'vue'

const route = useRoute()
const showNavbar = computed(() => !['home', 'login', 'register'].includes(route.name as string))
const showSidebar = computed(() => route.path.startsWith('/hr'))
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Navbar v-if="showNavbar" class="sticky top-0 z-50" />
    <div class="flex flex-1">
      <Sidebar v-if="showSidebar" />
      <main :class="{ 'ml-72 p-8': showSidebar }" class="flex-1">
        <RouterView />
      </main>
    </div>
  </div>
</template>