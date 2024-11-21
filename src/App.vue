<script setup lang="ts">
import { RouterView } from 'vue-router'
import Navbar from './components/layout/Navbar.vue'
import { useRoute } from 'vue-router'
import { computed } from 'vue'

const route = useRoute()
const showNavbar = computed(() => route.name !== 'home')
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <Navbar v-if="showNavbar" class="sticky top-0 z-50" />
    <RouterView />
  </div>
</template>

<style>
html, body {
  height: 100%;
  margin: 0;
}
</style>