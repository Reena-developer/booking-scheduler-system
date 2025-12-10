import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/utils/axiosInstance'

export const useServicesStore = defineStore('services', () => {
  const services = ref([])
  const pagination = ref({})
  const loading = ref(false)
  const error = ref(null)

  const fetchServices = async () => {
    loading.value = true
    try {
      const { data } = await api.get('/services')
      services.value = data.data.items
      pagination.value = data.data.meta
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch services'
    } finally {
      loading.value = false
    }
  }

  const createService = async (payload) => {
    loading.value = true
    try {
      const { data } = await api.post('/services', payload)
      services.value.unshift(data.data)
      return data.data
    } finally {
      loading.value = false
    }
  }

  const updateService = async (id, payload) => {
    loading.value = true
    try {
      const { data } = await api.put(`/services/${id}`, payload)
      const index = services.value.findIndex(s => s.id === id)
      if (index !== -1) services.value[index] = data.data
      return data.data
    } finally {
      loading.value = false
    }
  }

  const deleteService = async (id) => {
    loading.value = true
    try {
      await api.delete(`/services/${id}`)
      services.value = services.value.filter(s => s.id !== id)
    } finally {
      loading.value = false
    }
  }

  return { services, pagination, loading, error, fetchServices, createService, updateService, deleteService }
})
