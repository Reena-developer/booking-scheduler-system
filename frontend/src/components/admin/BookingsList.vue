<template>
  <div>
    <h2 class="fw-bold mb-4">Bookings Management</h2>

    <div v-if="loading" class="alert alert-info">
      <Loading />
    </div>

    <div v-else-if="error" class="alert alert-danger alert-dismissible fade show">
      {{ error }}
      <button type="button" class="btn-close" @click="error = ''"></button>
    </div>

    <div v-else-if="bookings.length === 0" class="alert alert-info">
      No bookings yet
    </div>

    <div v-else class="table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Service</th>
            <th>Date & Time</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="booking in bookings" :key="booking.id">
            <td class="fw-semibold">{{ booking.customer_name }}</td>
            <td>{{ booking.customer_email }}</td>
            <td>{{ booking.customer_phone }}</td>
            <td>{{ booking.service?.name }}</td>
            <td>{{ booking.booking_date }} {{ booking.booking_time }}</td>
            <td>
              <span class="badge" :class="getStatusClass(booking.status)">
                {{ booking.status }}
              </span>
            </td>
            <td>
              <button class="btn btn-sm btn-info me-2" @click="viewDetails(booking)">
                <i class="bi bi-eye"></i>
              </button>
              <button class="btn btn-sm btn-danger" @click="deleteBooking(booking.id)">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" ref="detailsModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Booking Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" v-if="selectedBooking">
            <p><strong>Customer:</strong> {{ selectedBooking.customer_name }}</p>
            <p><strong>Email:</strong> {{ selectedBooking.customer_email }}</p>
            <p><strong>Phone:</strong> {{ selectedBooking.customer_phone }}</p>
            <p><strong>Service:</strong> {{ selectedBooking.service?.name }}</p>
            <p><strong>Date & Time:</strong> {{ selectedBooking.booking_date }} at {{ selectedBooking.booking_time }}</p>
            <p><strong>Notes:</strong> {{ selectedBooking.notes || 'N/A' }}</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Modal } from 'bootstrap'
import { useAuthStore } from '@/stores/auth'
import Loading from '../shared/Loading.vue'

const authStore = useAuthStore()
const bookings = ref([])
const loading = ref(false)
const error = ref('')
const detailsModal = ref(null)
const modal = ref(null)
const selectedBooking = ref(null)

onMounted(async () => {
  modal.value = new Modal(detailsModal.value)
  await fetchBookings()
})

const fetchBookings = async () => {
  loading.value = true
  error.value = ''
  try {
    const { data } = await authStore.adminApi.get('/bookings')
    bookings.value = data.data
  } catch (err) {
    error.value = 'Failed to load bookings'
  } finally {
    loading.value = false
  }
}

const viewDetails = (booking) => {
  selectedBooking.value = booking
  modal.value.show()
}

const deleteBooking = async (id) => {
  if (!confirm('Delete this booking?')) return
  try {
    await authStore.adminApi.delete(`/bookings/${id}`)
    bookings.value = bookings.value.filter(b => b.id !== id)
  } catch (err) {
    error.value = 'Failed to delete booking'
  }
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-warning',
    confirmed: 'bg-success',
    cancelled: 'bg-danger',
    completed: 'bg-info'
  }
  return classes[status] || 'bg-secondary'
}
</script>