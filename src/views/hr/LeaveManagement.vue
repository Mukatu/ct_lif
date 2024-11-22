<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { PlusIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'
import { format } from 'date-fns'
import fr from 'date-fns/locale/fr'
import { leaveService } from '../../services/api'
import LeaveModal from '../../components/hr/LeaveModal.vue'
import ConfirmDialog from '../../components/ui/ConfirmDialog.vue'
import ErrorAlert from '../../components/ui/ErrorAlert.vue'
import DataTable from '../../components/ui/DataTable.vue'
import { useConfirmDialog } from '../../composables/useConfirmDialog'
import { useErrorHandler } from '../../composables/useErrorHandler'

const leaveRequests = ref([])
const showModal = ref(false)
const selectedRequest = ref(null)
const modalMode = ref('add')
const loading = ref(false)

const { showDialog, dialogConfig, confirm } = useConfirmDialog()
const { error, showError, handleError, clearError } = useErrorHandler()

const columns = [
  { key: 'employee', label: 'Employé' },
  { key: 'type', label: 'Type', formatter: (value) => value.charAt(0).toUpperCase() + value.slice(1) },
  { 
    key: 'startDate',
    label: 'Date de début',
    formatter: (value) => format(new Date(value), 'dd/MM/yyyy', { locale: fr })
  },
  {
    key: 'endDate',
    label: 'Date de fin',
    formatter: (value) => format(new Date(value), 'dd/MM/yyyy', { locale: fr })
  },
  { 
    key: 'status',
    label: 'Statut',
    formatter: (value) => ({
      pending: 'En attente',
      approved: 'Approuvé',
      rejected: 'Refusé'
    })[value] || value
  }
]

const fetchLeaveRequests = async () => {
  try {
    loading.value = true
    const response = await leaveService.getAll()
    leaveRequests.value = response.data.data
  } catch (error) {
    handleError('Erreur lors du chargement des demandes de congés')
  } finally {
    loading.value = false
  }
}

const handleSave = async (data) => {
  try {
    if (modalMode.value === 'edit' && selectedRequest.value) {
      await leaveService.update(selectedRequest.value.id, data)
    } else {
      await leaveService.create(data)
    }
    await fetchLeaveRequests()
    showModal.value = false
  } catch (error) {
    handleError('Erreur lors de l\'enregistrement de la demande')
  }
}

const updateStatus = async (id: number, status: string) => {
  try {
    await leaveService.updateStatus(id, { status })
    await fetchLeaveRequests()
  } catch (error) {
    handleError('Erreur lors de la mise à jour du statut')
  }
}

const deleteRequest = async (id: number) => {
  confirm({
    title: 'Supprimer la demande',
    message: 'Êtes-vous sûr de vouloir supprimer cette demande ?',
    onConfirm: async () => {
      try {
        await leaveService.delete(id)
        await fetchLeaveRequests()
      } catch (error) {
        handleError('Erreur lors de la suppression de la demande')
      }
    }
  })
}

const openModal = (mode: string, request = null) => {
  modalMode.value = mode
  selectedRequest.value = request
  showModal.value = true
}

onMounted(fetchLeaveRequests)
</script>

<template>
  <div class="bg-white rounded-lg shadow p-6">
    <ErrorAlert
      v-if="showError"
      :message="error"
      @close="clearError"
    />

    <div class="flex justify-between items-center mb-6">
      <h2 class="text-xl font-bold text-gray-900">Gestion des congés</h2>
      <button
        @click="openModal('add')"
        class="flex items-center px-4 py-2 bg-[#F2682C] text-white rounded-lg hover:bg-[#e55a20]"
      >
        <PlusIcon class="w-5 h-5 mr-2" />
        Nouvelle demande
      </button>
    </div>

    <DataTable
      :columns="columns"
      :data="leaveRequests"
      :loading="loading"
    >
      <template #row-actions="{ item }">
        <div class="flex space-x-2">
          <button
            v-if="item.status === 'pending'"
            @click="updateStatus(item.id, 'approved')"
            class="text-green-600 hover:text-green-800"
          >
            Approuver
          </button>
          <button
            v-if="item.status === 'pending'"
            @click="updateStatus(item.id, 'rejected')"
            class="text-red-600 hover:text-red-800"
          >
            Refuser
          </button>
          <button 
            @click="openModal('edit', item)"
            class="text-[#F2682C] hover:text-[#e55a20]"
          >
            <PencilIcon class="w-5 h-5" />
          </button>
          <button 
            @click="deleteRequest(item.id)"
            class="text-red-600 hover:text-red-800"
          >
            <TrashIcon class="w-5 h-5" />
          </button>
        </div>
      </template>
    </DataTable>

    <LeaveModal
      v-if="showModal"
      :request="selectedRequest"
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