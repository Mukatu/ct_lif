import { vi } from 'vitest'
import { config } from '@vue/test-utils'

// Mock router
config.global.mocks = {
  $router: {
    push: vi.fn(),
    replace: vi.fn()
  }
}

// Mock environment variables
vi.mock('@env', () => ({
  VITE_API_URL: 'http://localhost:8000/api'
}))

// Setup localStorage mock
const localStorageMock = {
  getItem: vi.fn(),
  setItem: vi.fn(),
  removeItem: vi.fn(),
  clear: vi.fn()
}

global.localStorage = localStorageMock

// Clean up after each test
afterEach(() => {
  vi.clearAllMocks()
})