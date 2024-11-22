<script setup lang="ts">
import { TransitionGroup } from 'vue'
import NotificationToast from './NotificationToast.vue'
import { useNotification } from '../../composables/useNotification'

const { notifications, remove } = useNotification()
</script>

<template>
  <div class="fixed top-4 right-4 z-50 space-y-4 min-w-[320px]">
    <TransitionGroup
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-100"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <NotificationToast
        v-for="notification in notifications"
        :key="notification.id"
        :type="notification.type"
        :message="notification.message"
        @close="remove(notification.id)"
      />
    </TransitionGroup>
  </div>
</template>