import { ref } from 'vue'

interface FileUploadOptions {
  accept?: string
  maxSize?: number
  multiple?: boolean
}

export function useFileUpload(options: FileUploadOptions = {}) {
  const files = ref<File[]>([])
  const previews = ref<string[]>([])
  const error = ref('')
  const loading = ref(false)

  const validateFile = (file: File): boolean => {
    // Check file type
    if (options.accept) {
      const acceptedTypes = options.accept.split(',')
      const fileType = file.type
      if (!acceptedTypes.some(type => 
        fileType.match(new RegExp(type.replace('*', '.*')))
      )) {
        error.value = 'Type de fichier non supporté'
        return false
      }
    }

    // Check file size
    if (options.maxSize && file.size > options.maxSize * 1024 * 1024) {
      error.value = `La taille du fichier ne doit pas dépasser ${options.maxSize}MB`
      return false
    }

    return true
  }

  const handleFiles = (fileList: FileList) => {
    error.value = ''
    const newFiles: File[] = []
    const newPreviews: string[] = []

    Array.from(fileList).forEach(file => {
      if (validateFile(file)) {
        newFiles.push(file)
        
        // Create preview for images
        if (file.type.startsWith('image/')) {
          const reader = new FileReader()
          reader.onload = (e) => {
            newPreviews.push(e.target?.result as string)
          }
          reader.readAsDataURL(file)
        }
      }
    })

    if (!options.multiple) {
      files.value = newFiles.slice(0, 1)
      previews.value = newPreviews.slice(0, 1)
    } else {
      files.value = [...files.value, ...newFiles]
      previews.value = [...previews.value, ...newPreviews]
    }
  }

  const removeFile = (index: number) => {
    files.value.splice(index, 1)
    previews.value.splice(index, 1)
  }

  const clearFiles = () => {
    files.value = []
    previews.value = []
    error.value = ''
  }

  return {
    files,
    previews,
    error,
    loading,
    handleFiles,
    removeFile,
    clearFiles
  }
}