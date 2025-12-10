import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import workingHoursApi from '@/services/api/workingHoursApi'
import specialDatesApi from '@/services/api/specialDatesApi'
import Swal from 'sweetalert2'

export const useWorkingHoursStore = defineStore('workingHours', () => {
  const workingHours = ref({})
  const specialDates = ref([])
  const loading = ref(false)
  const error = ref(null)
  const syncing = ref(false)
  const loadingDates = ref(false)
    const specialDatesPagination = ref({
    current_page: 1,
    per_page: 15,
    total: 0,
    last_page: 1,
  })
  const searchQuery = ref('')
  const sortBy = ref('created_at')
  const sortOrder = ref('desc')

  const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']

  // Initialize store with API data
  const initializeStore = async () => {
    try {
      loading.value = true
      error.value = null
      const data = await workingHoursApi.getWeeklySchedule()
      workingHours.value = data;
    } catch (err) {
      error.value = err.message
      console.error('Failed to initialize store:', err)
      Swal.fire('Error', 'Failed to load working hours', 'error')
    } finally {
      loading.value = false
    }
  }

  const saveSingleDay = async (dayIndex) => {
    try {
      syncing.value = true
      const dayData = workingHours.value[dayIndex]

      console.log(dayData);
      
      const payload = {
        day_of_week: dayIndex,
        is_active: !dayData.is_day_off,
        start_time: dayData.start_time || null,
        end_time: dayData.end_time || null,
        breaks: (dayData.breaks || []).map(b => ({
          start_time: b.start_time,
          end_time: b.end_time,
          title: b.title || null,
          comment: b.comment || null,
        })),
      }

      await workingHoursApi.configureSingleDay(dayIndex, payload)
      
      Swal.fire('Success', `${dayNames[dayIndex]} updated successfully`, 'success')
      return true
    } catch (err) {
      error.value = err.message
      Swal.fire('Error', err.message || 'Failed to save day', 'error')
      return false
    } finally {
      syncing.value = false
    }
  }

  // Sync entire week with API
  const saveWeekly = async () => {
    try {
      syncing.value = true
      error.value = null

      const weekPayload = Object.keys(workingHours.value).map(day => {
        const dayData = workingHours.value[day]
        return {
          day_of_week: parseInt(day),
          is_active: dayData.is_day_off,
          start_time: dayData.start_time || null,
          end_time: dayData.end_time || null,
          breaks: (dayData.breaks || []).map(b => ({
            start_time: b.start_time,
            end_time: b.end_time,
            title: b.title || null,
            comment: b.comment || null,
          })),
        }
      })

      const result = await workingHoursApi.configureWeek(weekPayload)

      if (result.errors && result.errors.length > 0) {
        Swal.fire(
          'Partial Success',
          `${result.errors.length} days failed to save`,
          'warning'
        )
      } else {
        Swal.fire('Success', 'Weekly schedule saved successfully', 'success')
      }

      return true
    } catch (err) {
      error.value = err.message
      Swal.fire('Error', err.message || 'Failed to save week', 'error')
      return false
    } finally {
      syncing.value = false
    }
  }

  // Reset to default
  const resetDefault = async () => {
    try {
      loading.value = true
      const defaults = {
        0: { day_of_week: 0, day_name: 'Sunday', is_day_off: false, start_time: null, end_time: null, breaks: [] },
        1: { day_of_week: 1, day_name: 'Monday', is_day_off: true, start_time: '10:00', end_time: '18:00', breaks: [] },
        2: { day_of_week: 2, day_name: 'Tuesday', is_day_off: true, start_time: '09:00', end_time: '18:00', breaks: [] },
        3: { day_of_week: 3, day_name: 'Wednesday', is_day_off: true, start_time: '09:00', end_time: '18:00', breaks: [] },
        4: { day_of_week: 4, day_name: 'Thursday', is_day_off: true, start_time: '09:00', end_time: '18:00', breaks: [] },
        5: { day_of_week: 5, day_name: 'Friday', is_day_off: true, start_time: '09:00', end_time: '18:00', breaks: [] },
        6: { day_of_week: 6, day_name: 'Saturday', is_day_off: true, start_time: '10:00', end_time: '17:00', breaks: [] }
      }


      workingHours.value = defaults
      await saveWeekly()
    } catch (err) {
      error.value = err.message
      Swal.fire('Error', 'Failed to reset', 'error')
    } finally {
      loading.value = false
    }
  }

  // Copy Monday to all days
  const copyMonday = async () => {
    const monday = JSON.parse(JSON.stringify(workingHours.value[1]))
    for (let i = 0; i < 7; i++) {
      if (i !== 1) {
        workingHours.value[i] = JSON.parse(JSON.stringify(monday))
        workingHours.value[i].day_of_week = i
        workingHours.value[i].day_name = dayNames[i]
      }
    }
    await saveWeekly()
  }

  // Break operations
  const addBreak = (dayIndex, breakItem) => {
    if (!workingHours.value[dayIndex]) return
    if (!workingHours.value[dayIndex].breaks) {
      workingHours.value[dayIndex].breaks = []
    }
    workingHours.value[dayIndex].breaks.push({
      ...breakItem,
      id: Date.now(),
    })
  }

  const removeBreak = (dayIndex, breakId) => {
    if (workingHours.value[dayIndex]) {
      workingHours.value[dayIndex].breaks = workingHours.value[dayIndex].breaks.filter(
        b => b.id !== breakId
      )
    }
  }

  const getDay = (index) => {
    return workingHours.value[index] || {}
  }

const initializeSpecialDates = async (page = 1) => {
    try {
      loadingDates.value = true
      error.value = null
      const result = await specialDatesApi.getSpecialDates(
        page,
        specialDatesPagination.value.per_page,
        searchQuery.value,
        sortBy.value,
        sortOrder.value
      )
      specialDates.value = result.data
      specialDatesPagination.value = result.pagination || result.meta
    } catch (err) {
      error.value = err.message
      console.error('Failed to initialize special dates:', err)
      Swal.fire('Error', 'Failed to load special dates', 'error')
    } finally {
      loadingDates.value = false
    }
  }

  // Search special dates
  const searchSpecialDates = async (query = '') => {
    searchQuery.value = query
    await initializeSpecialDates(1)
  }

  // Change sort
  const changeSortOrder = async (field) => {
    if (sortBy.value === field) {
      sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
    } else {
      sortBy.value = field
      sortOrder.value = 'desc'
    }
    await initializeSpecialDates(1)
  }

  // Add special date
  const addSpecialDate = async (dateObj) => {
    try {
      syncing.value = true
      const result = await specialDatesApi.createSpecialDate(dateObj)
      Swal.fire('Success', 'Special date added successfully', 'success')
      // Reload the first page
      await initializeSpecialDates(1)
      return true
    } catch (err) {
      error.value = err.message
      Swal.fire('Error', err.message || 'Failed to add special date', 'error')
      return false
    } finally {
      syncing.value = false
    }
  }

  // Remove special date
  const removeSpecialDate = async (id) => {
    try {
      syncing.value = true
      await specialDatesApi.deleteSpecialDate(id)
      Swal.fire('Success', 'Special date deleted successfully', 'success')
      // Reload current page or first page if last item deleted
      const currentPage = specialDatesPagination.value.current_page
      const nextPage = specialDates.value.length === 1 && currentPage > 1 ? currentPage - 1 : currentPage
      await initializeSpecialDates(nextPage)
      return true
    } catch (err) {
      error.value = err.message
      Swal.fire('Error', err.message || 'Failed to delete special date', 'error')
      return false
    } finally {
      syncing.value = false
    }
  }

  return {
    workingHours,
    specialDates,
    loading,
    error,
    syncing,
    dayNames,
    loadingDates,
    specialDatesPagination,
    searchQuery,
    sortBy,
    sortOrder,
    
    initializeStore,
    saveSingleDay,
    saveWeekly,
    resetDefault,
    copyMonday,
    addBreak,
    removeBreak,
    initializeSpecialDates,
    searchSpecialDates,
    changeSortOrder,
    addSpecialDate,
    removeSpecialDate,
    getDay
  }
})
