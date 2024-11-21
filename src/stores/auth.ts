import { defineStore } from 'pinia'
import { ref } from 'vue'

interface User {
  id: number
  name: string
  email: string
  role: string
  department: string
  position: string
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const isAuthenticated = ref(false)

  const login = async (email: string, password: string) => {
    try {
      // Simulate authentication with predefined users
      let userData: User | null = null;

      switch (email) {
        case 'comm@example.com':
          userData = {
            id: 1,
            name: 'Communication Interne',
            email: 'comm@example.com',
            role: 'internal_comm',
            department: 'Communication',
            position: 'Chargé de communication interne'
          }
          break;
        case 'rh@example.com':
          userData = {
            id: 2,
            name: 'Ressources Humaines',
            email: 'rh@example.com',
            role: 'hr',
            department: 'Ressources Humaines',
            position: 'Gestionnaire RH'
          }
          break;
        case 'employee@example.com':
          userData = {
            id: 3,
            name: 'Jean Dupont',
            email: 'employee@example.com',
            role: 'employee',
            department: 'Marketing',
            position: 'Agent Marketing'
          }
          break;
        default:
          throw new Error('Invalid credentials')
      }

      if (password !== 'password') {
        throw new Error('Invalid credentials')
      }

      user.value = userData
      isAuthenticated.value = true
      return true
    } catch (error) {
      throw new Error('Invalid credentials')
    }
  }

  const logout = () => {
    user.value = null
    isAuthenticated.value = false
  }

  const hasPermission = (permission: string): boolean => {
    if (!user.value) return false

    const rolePermissions: Record<string, string[]> = {
      internal_comm: ['publish_posts', 'manage_posts', 'comment_posts'],
      hr: ['manage_requests', 'manage_documents', 'manage_trainings'],
      employee: ['comment_posts', 'create_requests']
    }

    return rolePermissions[user.value.role]?.includes(permission) || false
  }

  return {
    user,
    isAuthenticated,
    login,
    logout,
    hasPermission
  }
})