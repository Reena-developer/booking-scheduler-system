import { defineStore } from 'pinia'
import api from '@/utils/axiosInstance'

export const useBookingsStore = defineStore('bookings', () => {
  const getAvailableSlots = async (serviceId, date) => {
    const { data } = await api.post('/available-slots', {
      service_id: serviceId,
      date
    })
    return data.data
  }

  const createBooking = async (payload) => {
    const { data } = await api.post('/appointments/book', payload)
    return data.data
  }

  return {
    getAvailableSlots,
    createBooking
  }
})
