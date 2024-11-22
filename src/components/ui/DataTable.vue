<script setup lang="ts">
import { computed } from 'vue'
import LoadingSpinner from './LoadingSpinner.vue'

const props = defineProps({
  columns: {
    type: Array as () => Array<{
      key: string
      label: string
      formatter?: (value: any) => string
    }>,
    required: true
  },
  data: {
    type: Array,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  },
  emptyMessage: {
    type: String,
    default: 'Aucune donnée disponible'
  }
})

const formattedData = computed(() => {
  return props.data.map(item => {
    const formattedItem: Record<string, any> = {}
    props.columns.forEach(column => {
      formattedItem[column.key] = column.formatter 
        ? column.formatter(item[column.key])
        : item[column.key]
    })
    return formattedItem
  })
})
</script>

<template>
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead>
        <tr>
          <th
            v-for="column in columns"
            :key="column.key"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
          >
            {{ column.label }}
          </th>
          <slot name="header-actions"></slot>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr v-if="loading">
          <td :colspan="columns.length + (!!$slots['row-actions'] ? 1 : 0)" class="px-6 py-4">
            <LoadingSpinner />
          </td>
        </tr>
        <tr v-else-if="data.length === 0">
          <td :colspan="columns.length + (!!$slots['row-actions'] ? 1 : 0)" class="px-6 py-4 text-center text-gray-500">
            {{ emptyMessage }}
          </td>
        </tr>
        <tr v-else v-for="(item, index) in formattedData" :key="index">
          <td
            v-for="column in columns"
            :key="column.key"
            class="px-6 py-4 whitespace-nowrap"
          >
            {{ item[column.key] }}
          </td>
          <td v-if="$slots['row-actions']" class="px-6 py-4 whitespace-nowrap">
            <slot name="row-actions" :item="data[index]"></slot>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>