import { ref } from 'vue'

export function useConfirmDialog() {
  const showDialog = ref(false)
  const dialogConfig = ref({
    title: '',
    message: '',
    onConfirm: () => {},
    onCancel: () => {}
  })

  const confirm = (config: {
    title: string
    message: string
    onConfirm: () => void
    onCancel?: () => void
  }) => {
    dialogConfig.value = {
      ...config,
      onCancel: config.onCancel || (() => {})
    }
    showDialog.value = true
  }

  const handleConfirm = () => {
    dialogConfig.value.onConfirm()
    showDialog.value = false
  }

  const handleCancel = () => {
    dialogConfig.value.onCancel()
    showDialog.value = false
  }

  return {
    showDialog,
    dialogConfig,
    confirm,
    handleConfirm,
    handleCancel
  }
}