import type { AxiosError } from 'axios'

interface ApiError {
  status?: number
  message: string
  errors?: Record<string, string[]>
}

export const handleApiError = (error: AxiosError | ApiError): string => {
  // If it's an Axios error with a response
  if ('isAxiosError' in error && error.response?.data) {
    const data = error.response.data as ApiError
    return data.message || 'Une erreur est survenue'
  }

  // If it's our custom API error
  if ('message' in error) {
    return error.message
  }

  return 'Une erreur est survenue'
}

export const isApiError = (error: unknown): error is ApiError => {
  return typeof error === 'object' && error !== null && 'message' in error
}