import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/utils/axiosInstance'

export const useAuthStore = defineStore('auth', () => {
  const adminToken = ref(localStorage.getItem('adminToken') || null)
  const admin = ref(JSON.parse(localStorage.getItem('admin')) || null)

  const loading = ref(false)
  const error = ref(null)

  // Computed
  const isAdminLoggedIn = computed(() => !!adminToken.value)

  // Login
  const adminLogin = async (email, password) => {
    loading.value = true
    error.value = null

    try {
      const { data } = await api.post('/login', { email, password })

      // Save token & admin data
      adminToken.value = data.data.token
      admin.value = data.data.user

      localStorage.setItem('adminToken', adminToken.value)
      localStorage.setItem('admin', JSON.stringify(admin.value))

      // Add token to axios header globally
      api.defaults.headers.common['Authorization'] = `Bearer ${adminToken.value}`

      return data
    } catch (err) {
      error.value = err.response?.data?.message || 'Login failed'
      throw err
    } finally {
      loading.value = false
    }
  }

  // Logout
  const adminLogout = () => {
    adminToken.value = null
    admin.value = null

    localStorage.removeItem('adminToken')
    localStorage.removeItem('admin')

    // Remove header
    delete api.defaults.headers.common['Authorization']
  }

  return {
    adminToken,
    admin,
    loading,
    error,

    isAdminLoggedIn,
    adminLogin,
    adminLogout,
  }
})
