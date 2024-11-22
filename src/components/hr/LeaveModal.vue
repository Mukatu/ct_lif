<script setup lang="ts">
import { ref } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { format } from 'date-fns'
import fr from 'date-fns/locale/fr'

const props = defineProps({
  request: {
    type: Object,
    default: null
  },
  mode: {
    type: String,
    default: 'add'
  }
})

const emit = defineEmits(['close', 'save'])

const formData = ref({
  employee: props.request?.employee || '',
  type: props.request?.type || 'paid',
  startDate: props.request?.startDate || format(new Date(), 'yyyy-MM-dd'),
  endDate: props.request?.endDate || format(new Date(), 'yyyy-MM-dd'),
  reason: props.request?.reason || ''
})

const handleSubmit = () => {
  emit('save', formData.value)
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-lg w-full p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">
          {{ mode === 'edit' ? 'Modifier la demande' : 'Nouvelle demande' }}
        </h3>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
          <XMarkIcon class="w-6 h-6" />
        </button>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Employé</label>
          <input
            v-model="formData.employee"
            type="text"
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Type de congé</label>
          <select
            v-model="formData.type"
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
          >
            <option value="paid">Congé payé</option>
            <option value="unpaid">Congé sans solde</option>
            <option value="sick">Congé maladie</option>
            <option value="other">Autre</option>
          </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Date de début</label>
            <input
              v-model="formData.startDate"
              type="date"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Date de fin</label>
            <input
              v-model="formData.endDate"
              type="date"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
            />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Motif</label>
          <textarea
            v-model="formData.reason"
            rows="3"
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
          ></textarea>
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