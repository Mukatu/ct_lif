import { describe, it, expect, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import NotificationToast from '@/components/ui/NotificationToast.vue'
import DataTable from '@/components/ui/DataTable.vue'
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'

describe('UI Components', () => {
  it('should render notification with correct type and message', () => {
    const wrapper = mount(NotificationToast, {
      props: {
        type: 'success',
        message: 'Test message'
      }
    })
    
    expect(wrapper.text()).toContain('Test message')
    expect(wrapper.classes()).toContain('bg-green-50')
  })

  it('should render data table with correct data', () => {
    const columns = [
      { key: 'name', label: 'Name' },
      { key: 'age', label: 'Age' }
    ]
    
    const data = [
      { name: 'John', age: 30 },
      { name: 'Jane', age: 25 }
    ]
    
    const wrapper = mount(DataTable, {
      props: { columns, data }
    })
    
    expect(wrapper.findAll('tr')).toHaveLength(3) // Header + 2 rows
    expect(wrapper.text()).toContain('John')
    expect(wrapper.text()).toContain('30')
  })

  it('should show loading spinner when loading', () => {
    const wrapper = mount(LoadingSpinner)
    expect(wrapper.classes()).toContain('animate-spin')
  })
})