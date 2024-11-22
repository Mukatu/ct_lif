<script setup lang="ts">
import { ref } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import * as XLSX from 'xlsx'

const emit = defineEmits(['close', 'import'])

const file = ref<File | null>(null)
const preview = ref<any[]>([])
const error = ref('')

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files) {
    file.value = target.files[0]
    parseExcel(file.value)
  }
}

const parseExcel = async (file: File) => {
  try {
    const data = await file.arrayBuffer()
    const workbook = XLSX.read(data)
    const worksheet = workbook.Sheets[workbook.SheetNames[0]]
    const jsonData = XLSX.utils.sheet_to_json(worksheet)
    
    // Validate required fields
    const requiredFields = ['name', 'sex', 'category', 'echelon', 'department', 'position', 'birthDate', 'hireDate']
    const hasAllFields = requiredFields.every(field => 
      jsonData.length > 0 && Object.keys(jsonData[0]).includes(field)
    )

    if (!hasAllFields) {
      error.value = 'Le fichier doit contenir toutes les colonnes requises: ' + requiredFields.join(', ')
      preview.value = []
      return
    }

    preview.value = jsonData.slice(0, 5) // Show first 5 rows as preview
    error.value = ''
  } catch (err) {
    error.value = 'Erreur lors de la lecture du fichier'
    preview.value = []
  }
}

const handleImport = () => {
  if (!file.value) return
  
  const reader = new FileReader()
  reader.onload = async (e) => {
    try {
      const data = e.target?.result
      const workbook = XLSX.read(data, { type: 'binary' })
      const worksheet = workbook.Sheets[workbook.SheetNames[0]]
      const jsonData = XLSX.utils.sheet_to_json(worksheet)
      
      emit('import', jsonData)
    } catch (err) {
      error.value = 'Erreur lors de l\'import'
    }
  }
  reader.readAsBinaryString(file.value)
}

const downloadTemplate = () => {
  const template = [
    {
      name: 'Nom complet',
      sex: 'M/F',
      category: '1-13',
      echelon: '1-10',
      department: 'Direction',
      position: 'Poste',
      birthDate: 'YYYY-MM-DD',
      hireDate: 'YYYY-MM-DD',
      address: 'Adresse',
      contact: 'Contact',
      rib: 'RIB'
    }
  ]
  
  const ws = XLSX.utils.json_to_sheet(template)
  const wb = XLSX.utils.book_new()
  XLSX.utils.book_append_sheet(wb, ws, 'Template')
  XLSX.writeFile(wb, 'template_import_employes.xlsx')
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">Import des employés</h3>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
          <XMarkIcon class="w-6 h-6" />
        </button>
      </div>

      <div class="space-y-4">
        <div>
          <button
            @click="downloadTemplate"
            class="text-blue-600 hover:text-blue-800 font-medium"
          >
            Télécharger le modèle Excel
          </button>
        </div>

        <div class="border-2 border-dashed rounded-lg p-8">
          <input
            type="file"
            accept=".xlsx,.xls"
            class="hidden"
            @change="handleFileChange"
            id="file-upload"
          />
          <label
            for="file-upload"
            class="cursor-pointer flex flex-col items-center"
          >
            <span class="text-gray-600">
              Cliquez pour sélectionner ou déposez votre fichier Excel ici
            </span>
            <span class="text-sm text-gray-500 mt-2">
              Format accepté: .xlsx, .xls
            </span>
          </label>
        </div>

        <div v-if="error" class="text-red-600 text-sm">
          {{ error }}
        </div>

        <div v-if="preview.length > 0" class="mt-4">
          <h4 class="font-medium mb-2">Aperçu des données (5 premières lignes)</h4>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
                <tr>
                  <th
                    v-for="header in Object.keys(preview[0])"
                    :key="header"
                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    {{ header }}
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(row, index) in preview" :key="index">
                  <td
                    v-for="(value, key) in row"
                    :key="key"
                    class="px-4 py-2 whitespace-nowrap text-sm text-gray-500"
                  >
                    {{ value }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
          <button
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50"
          >
            Annuler
          </button>
          <button
            type="button"
            @click="handleImport"
            class="px-4 py-2 bg-[#F2682C] text-white rounded-md hover:bg-[#e55a20]"
            :disabled="!file || preview.length === 0"
          >
            Importer
          </button>
        </div>
      </div>
    </div>
  </div>
</template>