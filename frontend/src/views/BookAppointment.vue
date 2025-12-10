<template>
  <div class="container py-5">

    <!-- ADMIN NAV -->
    <nav class="d-flex justify-content-end mb-4">
      <button
        v-if="isAdminLoggedIn"
        class="btn btn-outline-primary me-2"
        @click="goToAdminDashboard"
      >
        Admin Dashboard
      </button>

      <button
        v-else
        class="btn btn-outline-secondary"
        @click="goToAdminLogin"
      >
        Admin Login
      </button>
    </nav>

    <h2 class="mb-4 text-center fw-bold">Book an Appointment</h2>

    <!-- Step 1: DATE -->
    <div class="card shadow-sm mb-4">
      <div class="card-header fw-semibold">1. Select Date</div>
      <div class="card-body">
        <input
          type="date"
          class="form-control"
          v-model="selectedDate"
          :min="today"
          @change="resetAfterDate"
        />
        <p v-if="errors.booking_date" class="text-danger small">{{ errors.booking_date }}</p>
      </div>
    </div>

    <!-- Step 2: SERVICE -->
    <div v-if="selectedDate" class="card shadow-sm mb-4">
      <div class="card-header fw-semibold">2. Select Service</div>
      <div class="card-body">
        <div v-if="serviceStore.loading">Loading services...</div>

        <select
          v-model="selectedServiceId"
          class="form-select"
          @change="resetSlot"
        >
          <option value="" disabled>Select a service</option>
          <option
            v-for="service in serviceStore.services"
            :key="service.id"
            :value="service.id"
          >
            {{ service.name }} — ₹{{ service.price }} ({{ service.duration }} min)
          </option>
        </select>

        <p v-if="errors.service_id" class="text-danger small">{{ errors.service_id }}</p>
      </div>
    </div>

    <!-- Step 3: SLOT -->
    <div v-if="selectedServiceId" class="card shadow-sm mb-4">
      <div class="card-header fw-semibold">3. Select Time Slot</div>
      <div class="card-body">
        <SlotSelector
          :service-id="selectedServiceId"
          :date="selectedDate"
          @slot-selected="selectedSlot = $event"
        />
        <p v-if="errors.slot" class="text-danger small mt-2">{{ errors.slot }}</p>
      </div>
    </div>

    <!-- Step 4: DETAILS -->
    <div v-if="selectedSlot" class="card shadow-sm mb-4">
      <div class="card-header fw-semibold">4. Your Details</div>
      <div class="card-body">

        <div class="mb-3">
          <label class="form-label">Full Name *</label>
          <input type="text" class="form-control" v-model="name" />
          <p v-if="errors.client_name" class="text-danger small">{{ errors.client_name }}</p>
        </div>

        <div class="mb-3">
          <label class="form-label">Email *</label>
          <input type="email" class="form-control" v-model="email" />
          <p v-if="errors.client_email" class="text-danger small">{{ errors.client_email }}</p>
        </div>

        <div class="mb-3">
          <label class="form-label">Phone *</label>
          <input type="tel" class="form-control" v-model="phone" />
          <p v-if="errors.client_phone" class="text-danger small">{{ errors.client_phone }}</p>
        </div>

        <div class="mb-3">
          <label class="form-label">Notes (optional)</label>
          <textarea class="form-control" rows="2" v-model="notes"></textarea>
        </div>

        <button
          class="btn btn-success w-100"
          @click="submitBooking"
          :disabled="bookingStore.loading"
        >
          <span v-if="bookingStore.loading">Booking...</span>
          <span v-else>Confirm Booking</span>
        </button>
      </div>
    </div>

    <!-- Success and Error Messages -->
    <div v-if="bookingSuccess" class="alert alert-success mt-4">
      Appointment booked successfully!
    </div>
    <div v-if="bookingError" class="alert alert-danger mt-4">
      {{ bookingError }}
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useServicesStore } from '@/stores/services'
import { useBookingsStore } from '@/stores/bookings'
import { useAuthStore } from '@/stores/auth'
import SlotSelector from '@/components/customer/SlotSelector.vue'

const serviceStore = useServicesStore()
const bookingStore = useBookingsStore()
const authStore = useAuthStore()

const isAdminLoggedIn = authStore.isLoggedIn
const goToAdminDashboard = () => (window.location.href = '/admin/dashboard')
const goToAdminLogin = () => (window.location.href = '/admin/login')

// FORM STATE
const selectedDate = ref('')
const selectedServiceId = ref('')
const selectedSlot = ref(null)
const name = ref('')
const email = ref('')
const phone = ref('')
const notes = ref('')

const bookingSuccess = ref(false)
const bookingError = ref(null)

const errors = ref({
  booking_date: "",
  service_id: "",
  slot: "",
  client_name: "",
  client_email: "",
  client_phone: "",
})

const today = computed(() => new Date().toISOString().split('T')[0])

// FETCH SERVICES
onMounted(() => {
  serviceStore.fetchServices()
})

// RESET FUNCTIONS
const resetAfterDate = () => {
  selectedServiceId.value = ''
  resetSlot()
  bookingError.value = null
  bookingSuccess.value = false
}

const resetSlot = () => {
  selectedSlot.value = null
  bookingError.value = null
  bookingSuccess.value = false
}

const resetForm = () => {
  selectedDate.value = ''
  selectedServiceId.value = ''
  selectedSlot.value = null
  name.value = ''
  email.value = ''
  phone.value = ''
  notes.value = ''
  clearErrors()
}

// ERROR HANDLING
const clearErrors = () => {
  Object.keys(errors.value).forEach(key => errors.value[key] = "")
}

const runClientValidation = () => {
  clearErrors()
  if (!selectedDate.value) errors.value.booking_date = "Please select a date."
  if (!selectedServiceId.value) errors.value.service_id = "Please select a service."
  if (!selectedSlot.value) errors.value.slot = "Please select a slot."
  if (!name.value) errors.value.client_name = "Name is required."
  if (!email.value) errors.value.client_email = "Email is required."
  if (!phone.value) errors.value.client_phone = "Phone number is required."

  return Object.values(errors.value).some(e => e !== "")
}

// SUBMIT BOOKING
const submitBooking = async () => {
  bookingError.value = null
  bookingSuccess.value = false

  const hasClientErrors = runClientValidation()
  if (hasClientErrors) return

  try {
    await bookingStore.createBooking({
      service_id: selectedServiceId.value,
      client_name: name.value,
      client_email: email.value,
      client_phone: phone.value,
      booking_date: selectedDate.value,
      start_time: selectedSlot.value.start_time,
      end_time: selectedSlot.value.end_time,
      notes: notes.value,
    })

    bookingSuccess.value = true
    resetForm()

  } catch (err) {
    const errorData = err?.response?.data

    if (errorData?.errors && Object.keys(errorData.errors).length > 0) {
      Object.keys(errorData.errors).forEach(key => {
        errors.value[key] = errorData.errors[key][0]
      })
    }

    if (errorData?.status === false && errorData?.message) {
      bookingError.value = errorData.message
    } else {
      bookingError.value = "Something went wrong. Please try again."
    }
  }
}
</script>

<style scoped>
.card {
  border-radius: 10px;
}
</style>
