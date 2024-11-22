import { ref } from 'vue'

interface Notification {
  id: number
  type: 'success' | 'error' | 'warning' | 'info'
  message: string
  duration?: number
}

export function useNotification() {
  const notifications = ref<Notification[]>([])

  const show = (notification: Omit<Notification, 'id'>) => {
    const id = Date.now()
    notifications.value.push({
      id,
      ...notification
    })

    if (notification.duration !== 0) {
      setTimeout(() => {
        remove(id)
      }, notification.duration || 5000)
    }

    return id
  }

  const remove = (id: number) => {
    notifications.value = notifications.value.filter(n => n.id !== id)
  }

  const clear = () => {
    notifications.value = []
  }

  return {
    notifications,
    show,
    remove,
    clear
  }
}