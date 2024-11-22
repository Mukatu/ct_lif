import { ref, computed } from 'vue'

interface SortConfig {
  field: string
  direction: 'asc' | 'desc'
}

interface FilterConfig {
  field: string
  value: string | number | boolean
}

export function useDataTable<T>(initialData: T[] = []) {
  const data = ref<T[]>(initialData)
  const loading = ref(false)
  const error = ref('')
  const sortConfig = ref<SortConfig | null>(null)
  const filters = ref<FilterConfig[]>([])
  const searchQuery = ref('')
  const itemsPerPage = ref(10)
  const currentPage = ref(1)

  const filteredData = computed(() => {
    let result = [...data.value]

    // Apply filters
    filters.value.forEach(filter => {
      result = result.filter(item => 
        (item as any)[filter.field] === filter.value
      )
    })

    // Apply search
    if (searchQuery.value) {
      const query = searchQuery.value.toLowerCase()
      result = result.filter(item =>
        Object.values(item).some(value =>
          String(value).toLowerCase().includes(query)
        )
      )
    }

    // Apply sorting
    if (sortConfig.value) {
      const { field, direction } = sortConfig.value
      result.sort((a, b) => {
        const aValue = (a as any)[field]
        const bValue = (b as any)[field]
        if (direction === 'asc') {
          return aValue > bValue ? 1 : -1
        }
        return aValue < bValue ? 1 : -1
      })
    }

    return result
  })

  const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value
    const end = start + itemsPerPage.value
    return filteredData.value.slice(start, end)
  })

  const totalPages = computed(() =>
    Math.ceil(filteredData.value.length / itemsPerPage.value)
  )

  const setSort = (field: string) => {
    if (sortConfig.value?.field === field) {
      if (sortConfig.value.direction === 'asc') {
        sortConfig.value.direction = 'desc'
      } else {
        sortConfig.value = null
      }
    } else {
      sortConfig.value = { field, direction: 'asc' }
    }
  }

  const addFilter = (field: string, value: string | number | boolean) => {
    filters.value.push({ field, value })
  }

  const removeFilter = (field: string) => {
    filters.value = filters.value.filter(f => f.field !== field)
  }

  const clearFilters = () => {
    filters.value = []
  }

  const setPage = (page: number) => {
    currentPage.value = page
  }

  return {
    data,
    loading,
    error,
    sortConfig,
    filters,
    searchQuery,
    itemsPerPage,
    currentPage,
    filteredData,
    paginatedData,
    totalPages,
    setSort,
    addFilter,
    removeFilter,
    clearFilters,
    setPage
  }
}