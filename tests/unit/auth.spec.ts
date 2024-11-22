import { describe, it, expect, beforeEach } from 'vitest'
import { useAuthStore } from '@/stores/auth'
import { createPinia, setActivePinia } from 'pinia'

describe('Auth Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  it('should login successfully with valid credentials', async () => {
    const store = useAuthStore()
    await store.login('employee@example.com', 'password')
    expect(store.isAuthenticated).toBe(true)
    expect(store.user).toBeTruthy()
    expect(store.user?.role).toBe('employee')
  })

  it('should fail login with invalid credentials', async () => {
    const store = useAuthStore()
    await expect(store.login('wrong@email.com', 'wrongpass'))
      .rejects.toThrow('Invalid credentials')
  })

  it('should logout successfully', () => {
    const store = useAuthStore()
    store.logout()
    expect(store.isAuthenticated).toBe(false)
    expect(store.user).toBeNull()
  })

  it('should check permissions correctly', async () => {
    const store = useAuthStore()
    await store.login('comm@example.com', 'password')
    expect(store.hasPermission('publish_posts')).toBe(true)
    expect(store.hasPermission('manage_requests')).toBe(false)
  })
})