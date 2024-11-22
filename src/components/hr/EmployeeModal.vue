<script setup lang="ts">
import { ref, computed } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { addYears, differenceInYears } from 'date-fns'

const props = defineProps({
  employee: {
    type: Object,
    default: null
  },
  mode: {
    type: String,
    default: 'add'
  }
})

const emit = defineEmits(['close', 'save'])

const categories = [
  '1', '2', '3', '4', '5', '6', '7', '8',
  '9', '10', '11', '12', '13'
]

const echelons = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']

const formData = ref({
  name: props.employee?.name || '',
  sex: props.employee?.sex || '',
  category: props.employee?.category || '',
  echelon: props.employee?.echelon || '',
  department: props.employee?.department || '',
  position: props.employee?.position || '',
  birthDate: props.employee?.birthDate || '',
  hireDate: props.employee?.hireDate || '',
  endDate: props.employee?.endDate || '',
  address: props.employee?.address || '',
  contact: props.employee?.contact || '',
  rib: props.employee?.rib || '',
  status: props.employee?.status || 'Actif'
})

const retirementAge = computed(() => {
  const category = parseInt(formData.value.category)
  if (category >= 1 && category <= 8) return 60
  if (category >= 9 && category <= 11) return 65
  if (category >= 12 && category <= 13) return 70
  return null
})

const retirementDate = computed(() => {
  if (!formData.value.birthDate || !retirementAge.value) return null
  const birthDate = new Date(formData.value.birthDate)
  return addYears(birthDate, retirementAge.value)
})

const handleSubmit = () => {
  const data = {
    ...formData.value,
    retirementDate: retirementDate.value
  }
  emit('save', data)
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-900">
          {{ mode === 'edit' ? 'Modifier l\'employé' : 'Ajouter un employé' }}
        </h3>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
          <XMarkIcon class="w-6 h-6" />
        </button>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Nom complet</label>
            <input
              v-model="formData.name"
              type="text"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Sexe</label>
            <select
              v-model="formData.sex"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            >
              <option value="">Sélectionner</option>
              <option value="M">Masculin</option>
              <option value="F">Féminin</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Catégorie</label>
            <select
              v-model="formData.category"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            >
              <option value="">Sélectionner une catégorie</option>
              <option v-for="cat in categories" :key="cat" :value="cat">
                {{ cat }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Échelon</label>
            <select
              v-model="formData.echelon"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            >
              <option value="">Sélectionner un échelon</option>
              <option v-for="ech in echelons" :key="ech" :value="ech">
                {{ ech }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Direction</label>
            <select
              v-model="formData.department"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            >
              <option value="">Sélectionner une direction</option>
              <option value="CAB-DG">CAB-DG</option>
              <option value="DARH">DARH</option>
              <option value="DJR">DJR</option>
              <option value="DFC">DFC</option>
              <option value="DCM">DCM</option>
              <option value="DEW">DEW</option>
              <option value="DT">DT</option>
              <option value="DIP">DIP</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Poste</label>
            <input
              v-model="formData.position"
              type="text"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
            <input
              v-model="formData.birthDate"
              type="date"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Date de recrutement</label>
            <input
              v-model="formData.hireDate"
              type="date"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Contact</label>
            <input
              v-model="formData.contact"
              type="tel"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Statut</label>
            <select
              v-model="formData.status"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            >
              <option value="Actif">Actif</option>
              <option value="Inactif">Inactif</option>
            </select>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Adresse</label>
            <input
              v-model="formData.address"
              type="text"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">RIB</label>
            <input
              v-model="formData.rib"
              type="text"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>

          <div v-if="retirementDate" class="md:col-span-2 p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-700">
              <span class="font-medium">Âge de départ à la retraite:</span> {{ retirementAge }} ans
            </p>
            <p class="text-sm text-gray-700">
              <span class="font-medium">Date de départ à la retraite:</span> 
              {{ retirementDate.toLocaleDateString() }}
            </p>
          </div>
        </div>

        <div class="flex justify-end space-x-3">
          <button
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50"
          >
            Annuler
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-[#F2682C] text-white rounded-md hover:bg-[#e55a20]"
          >
            {{ mode === 'edit' ? 'Mettre à jour' : 'Ajouter' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>