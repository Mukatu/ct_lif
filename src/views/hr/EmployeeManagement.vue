<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { PlusIcon, PencilIcon, TrashIcon, ArrowUpTrayIcon } from '@heroicons/vue/24/outline'
import { employeeService } from '../../services/api'
import EmployeeModal from '../../components/hr/EmployeeModal.vue'
import ImportModal from '../../components/hr/ImportModal.vue'
import ConfirmDialog from '../../components/ui/ConfirmDialog.vue'
import ErrorAlert from '../../components/ui/ErrorAlert.vue'
import DataTable from '../../components/ui/DataTable.vue'
import { useConfirmDialog } from '../../composables/useConfirmDialog'
import { useErrorHandler } from '../../composables/useErrorHandler'
import { differenceInYears } from 'date-fns'

const employees = ref([])
const showModal = ref(false)
const showImportModal = ref(false)
const selectedEmployee = ref(null)
const modalMode = ref('add')
const loading = ref(false)

const departments = ref([
  'CAB-DG',
  'DARH',
  'DJR',
  'DFC',
  'DCM',
  'DEW',
  'DT',
  'DIP'
])
const selectedDepartment = ref('Tous')
const searchQuery = ref('')

const { showDialog, dialogConfig, confirm } = useConfirmDialog()
const { error, showError, handleError, clearError } = useErrorHandler()

const columns = [
  { key: 'name', label: 'Nom' },
  { key: 'department', label: 'Direction' },
  { key: 'position', label: 'Poste' },
  { key: 'sex', label: 'Sexe' },
  { key: 'category', label: 'Catégorie' },
  { key: 'echelon', label: 'Échelon' },
  { key: 'age', label: 'Age', formatter: (value) => `${value} ans` },
  { key: 'hireDate', label: 'Date recrutement', formatter: (value) => new Date(value).toLocaleDateString() },
  { key: 'status', label: 'Statut' }
]

const filteredEmployees = computed(() => {
  return employees.value.filter(employee => {
    const matchesDepartment = selectedDepartment.value === 'Tous' || 
                            employee.department === selectedDepartment.value
    const matchesSearch = employee.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    return matchesDepartment && matchesSearch
  })
})

const fetchEmployees = async () => {
  try {
    loading.value = true
    const response = await employeeService.getAll()
    employees.value = response.data.data
  } catch (error) {
    handleError('Erreur lors du chargement des employés')
  } finally {
    loading.value = false
  }
}

const handleSave = async (data) => {
  try {
    if (modalMode.value === 'edit' && selectedEmployee.value) {
      await employeeService.update(selectedEmployee.value.id, data)
    } else {
      await employeeService.create(data)
    }
    await fetchEmployees()
    showModal.value = false
  } catch (error) {
    handleError('Erreur lors de l\'enregistrement de l\'employé')
  }
}

const handleImport = async (data) => {
  try {
    await employeeService.import(data)
    await fetchEmployees()
    showImportModal.value = false
  } catch (error) {
    handleError('Erreur lors de l\'import des employés')
  }
}

const deleteEmployee = async (id: number) => {
  confirm({
    title: 'Supprimer l\'employé',
    message: 'Êtes-vous sûr de vouloir supprimer cet employé ?',
    onConfirm: async () => {
      try {
        await employeeService.delete(id)
        await fetchEmployees()
      } catch (error) {
        handleError('Erreur lors de la suppression de l\'employé')
      }
    }
  })
}

const openModal = (mode: string, employee = null) => {
  modalMode.value = mode
  selectedEmployee.value = employee
  showModal.value = true
}

onMounted(fetchEmployees)
</script>

<template>
  <div class="bg-white rounded-lg shadow p-6">
    <ErrorAlert
      v-if="showError"
      :message="error"
      @close="clearError"
    />

    <div class="flex justify-between items-center mb-6">
      <h2 class="text-xl font-bold text-gray-900">Gestion du personnel</h2>
      <div class="flex space-x-3">
        <button
          @click="showImportModal = true"
          class="flex items-center px-4 py-2 border border-[#F2682C] text-[#F2682C] rounded-lg hover:bg-[#F2682C] hover:text-white transition-colors"
        >
          <ArrowUpTrayIcon class="w-5 h-5 mr-2" />
          Importer
        </button>
        <button
          @click="openModal('add')"
          class="flex items-center px-4 py-2 bg-[#F2682C] text-white rounded-lg hover:bg-[#e55a20]"
        >
          <PlusIcon class="w-5 h-5 mr-2" />
          Ajouter un employé
        </button>
      </div>
    </div>

    <div class="mb-6 flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Rechercher un employé..."
        class="flex-1 rounded-lg border-gray-300 focus:border-[#F2682C] focus:ring-[#F2682C]"
      />
      <select
        v-model="selectedDepartment"
        class="w-full md:w-64 rounded-lg border-gray-300 focus:border-[#F2682C] focus:ring-[#F2682C]"
      >
        <option value="Tous">Toutes les directions</option>
        <option v-for="dept in departments" :key="dept" :value="dept">
          {{ dept }}
        </option>
      </select>
    </div>

    <DataTable
      :columns="columns"
      :data="filteredEmployees"
      :loading="loading"
    >
      <template #row-actions="{ item }">
        <div class="flex space-x-2">
          <button 
            @click="openModal('edit', item)"
            class="text-[#F2682C] hover:text-[#e55a20]"
          >
            <PencilIcon class="w-5 h-5" />
          </button>
          <button 
            @click="deleteEmployee(item.id)"
            class="text-red-600 hover:text-red-800"
          >
            <TrashIcon class="w-5 h-5" />
          </button>
        </div>
      </template>
    </DataTable>

    <EmployeeModal
      v-if="showModal"
      :employee="selectedEmployee"
      :mode="modalMode"
      @close="showModal = false"
      @save="handleSave"
    />

    <ImportModal
      v-if="showImportModal"
      @close="showImportModal = false"
      @import="handleImport"
    />

    <ConfirmDialog
      v-if="showDialog"
      v-bind="dialogConfig"
    />
  </div>
</template>