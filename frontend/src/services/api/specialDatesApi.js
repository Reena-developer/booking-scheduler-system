// services/api/specialDatesApi.js

import api from '@/utils/axiosInstance'

class SpecialDatesApi {
  constructor() {
    this.endpoint = '/working-hours/special-days'
  }

  // Get special dates with pagination
  async getSpecialDates(page = 1, perPage = 15, search = '', sortBy = 'created_at', sortOrder = 'desc') {
    try {
      const params = {
        page,
        per_page: perPage,
        sort_by: sortBy,
        sort_order: sortOrder,
      }
      if (search) params.search = search

      const response = await api.get(this.endpoint, { params })
      return {
        data: response.data.data.items || [],
        pagination: response.data.pagination || {},
        meta: response.data.meta || {},
      }
    } catch (error) {
      console.error('API Error:', error.response?.status, error.response?.data)
      throw new Error(error.response?.data?.message || 'Failed to fetch special dates')
    }
  }

  // Create special date
  async createSpecialDate(payload) {
    try {
      const response = await api.post(this.endpoint, payload)
      return response.data.data
    } catch (error) {
      console.error('API Error:', error.response?.status, error.response?.data)
      throw new Error(error.response?.data?.message || 'Failed to create special date')
    }
  }

  // Delete special date
  async deleteSpecialDate(id) {
    try {
      const response = await api.delete(`${this.endpoint}/${id}`)
      return response.data.data
    } catch (error) {
      console.error('API Error:', error.response?.status, error.response?.data)
      throw new Error(error.response?.data?.message || 'Failed to delete special date')
    }
  }
}

export default new SpecialDatesApi()