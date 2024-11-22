import { describe, it, expect, vi } from 'vitest'
import api from '@/services/api'
import { blogService, documentService, employeeService } from '@/services/api'

// Mock axios
vi.mock('axios', () => ({
  default: {
    create: () => ({
      interceptors: {
        request: { use: vi.fn() },
        response: { use: vi.fn() }
      },
      get: vi.fn(),
      post: vi.fn(),
      put: vi.fn(),
      delete: vi.fn()
    })
  }
}))

describe('API Services', () => {
  it('should handle API errors correctly', async () => {
    const mockError = { 
      response: { 
        data: { message: 'Test error' },
        status: 500
      }
    }
    
    api.get = vi.fn().mockRejectedValue(mockError)
    
    await expect(documentService.getAll())
      .rejects
      .toThrow('Test error')
  })

  it('should handle successful API responses', async () => {
    const mockResponse = { 
      data: { 
        data: [{ id: 1, title: 'Test' }] 
      } 
    }
    
    api.get = vi.fn().mockResolvedValue(mockResponse)
    
    const result = await blogService.getPosts()
    expect(result.data.data).toHaveLength(1)
    expect(result.data.data[0].title).toBe('Test')
  })

  it('should handle file uploads correctly', async () => {
    const formData = new FormData()
    formData.append('file', new Blob(['test']))
    
    api.post = vi.fn().mockResolvedValue({ 
      data: { 
        data: { id: 1 } 
      } 
    })
    
    const result = await employeeService.import(formData)
    expect(result.data.data.id).toBe(1)
  })
})