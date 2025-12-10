import api from '@/utils/axiosInstance'

class WorkingHoursApi {
  constructor() {
    this.endpoint = '/working-hours'
  }

  async getWeeklySchedule() {
    try {
      const response = await api.get(this.endpoint)
      return response.data.data || []
    } catch (error) {
      throw new Error(error.response?.data?.message || 'Failed to fetch weekly schedule')
    }
  }

  async configureSingleDay(day, payload) {
    try {
      const response = await api.post(`${this.endpoint}/configure-day`, payload)
      return response.data.data
    } catch (error) {
      throw new Error(error.response?.data?.message || 'Failed to save day')
    }
  }

  async configureWeek(payload) {
    try {
      const response = await api.post(`${this.endpoint}/configure-week`, {
        working_hours: payload,
      })

      return {
        successful: response.data.data || [],
        errors: response.data.errors || [],
      }
    } catch (error) {
      throw new Error(error.response?.data?.message || 'Failed to save week')
    }
  }
}

export default new WorkingHoursApi()