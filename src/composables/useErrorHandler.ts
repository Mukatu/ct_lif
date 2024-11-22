import { ref } from 'vue'

export function useErrorHandler() {
  const error = ref('')
  const showError = ref(false)

  const handleError = (message: string) => {
    error.value = message
    showError.value = true
    setTimeout(() => {
      showError.value = false
    }, 5000)
  }

  const clearError = () => {
    error.value = ''
    showError.value = false
  }

  return {
    error,
    showError,
    handleError,
    clearError
  }
}