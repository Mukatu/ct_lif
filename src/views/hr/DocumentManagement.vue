<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { PlusIcon, TrashIcon, DocumentIcon } from '@heroicons/vue/24/outline'
import { documentService } from '../../services/api'
import DocumentModal from '../../components/hr/DocumentModal.vue'
import ConfirmDialog from '../../components/ui/ConfirmDialog.vue'
import ErrorAlert from '../../components/ui/ErrorAlert.vue'
import DataTable from '../../components/ui/DataTable.vue'
import { useConfirmDialog } from '../../composables/useConfirmDialog'
import { useErrorHandler } from '../../composables/useErrorHandler'

const documents = ref([])
const showModal = ref(false)
const selectedDocument = ref(null)
const modalMode = ref('add')
const categories = ref(['Procédures', 'Règlements', 'Formulaires', 'Guides'])
const selectedCategory = ref('Tous')
const loading = ref(false)

const { showDialog, dialogConfig, confirm } = useConfirmDialog()
const { error, showError, handleError, clearError } = useErrorHandler()

const columns = [
  { key: 'title', label: 'Document' },
  { key: 'category', label: 'Catégorie' },
  { key: 'created_at', label: 'Date d\'ajout', formatter: (value) => new Date(value).toLocaleDateString() },
  { key: 'size', label: 'Taille' }
]

const fetchDocuments = async () => {
  try {
    loading.value = true
    const response = await documentService.getAll()
    documents.value = response.data.data
  } catch (error) {
    handleError('Erreur lors du chargement des documents')
  } finally {
    loading.value = false
  }
}

const handleSave = async (formData: FormData) => {
  try {
    if (modalMode.value === 'edit' && selectedDocument.value) {
      await documentService.update(selectedDocument.value.id, formData)
    } else {
      await documentService.create(formData)
    }
    await fetchDocuments()
    showModal.value = false
  } catch (error) {
    handleError('Erreur lors de l\'enregistrement du document')
  }
}

const deleteDocument = async (id: number) => {
  confirm({
    title: 'Supprimer le document',
    message: 'Êtes-vous sûr de vouloir supprimer ce document ?',
    onConfirm: async () => {
      try {
        await documentService.delete(id)
        await fetchDocuments()
      } catch (error) {
        handleError('Erreur lors de la suppression du document')
      }
    }
  })
}

const downloadDocument = async (id: number) => {
  try {
    const response = await documentService.download(id)
    const url = window.URL.createObjectURL(new Blob([response.data]))
    window.open(url, '_blank')
  } catch (error) {
    handleError('Erreur lors du téléchargement du document')
  }
}

const openModal = (mode: string, document = null) => {
  modalMode.value = mode
  selectedDocument.value = document
  showModal.value = true
}

onMounted(fetchDocuments)
</script>

<template>
  <div class="bg-white rounded-lg shadow p-6">
    <ErrorAlert
      v-if="showError"
      :message="error"
      @close="clearError"
    />

    <div class="flex justify-between items-center mb-6">
      <h2 class="text-xl font-bold text-gray-900">Gestion documentaire</h2>
      <button
        @click="openModal('add')"
        class="flex items-center px-4 py-2 bg-[#F2682C] text-white rounded-lg hover:bg-[#e55a20]"
      >
        <PlusIcon class="w-5 h-5 mr-2" />
        Ajouter un document
      </button>
    </div>

    <div class="mb-6">
      <select
        v-model="selectedCategory"
        class="w-full md:w-64 rounded-lg border-gray-300 focus:border-[#F2682C] focus:ring-[#F2682C]"
      >
        <option value="Tous">Toutes les catégories</option>
        <option v-for="category in categories" :key="category" :value="category">
          {{ category }}
        </option>
      </select>
    </div>

    <DataTable
      :columns="columns"
      :data="documents"
      :loading="loading"
    >
      <template #row-actions="{ item }">
        <div class="flex space-x-2">
          <button 
            @click="downloadDocument(item.id)"
            class="text-[#F2682C] hover:text-[#e55a20]"
          >
            Télécharger
          </button>
          <button 
            @click="deleteDocument(item.id)"
            class="text-red-600 hover:text-red-800"
          >
            <TrashIcon class="w-5 h-5" />
          </button>
        </div>
      </template>
    </DataTable>

    <DocumentModal
      v-if="showModal"
      :document="selectedDocument"
      :mode="modalMode"
      @close="showModal = false"
      @save="handleSave"
    />

    <ConfirmDialog
      v-if="showDialog"
      v-bind="dialogConfig"
    />
  </div>
</template>