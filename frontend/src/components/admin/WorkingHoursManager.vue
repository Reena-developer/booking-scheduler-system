<template>
  <div class="container py-4">
    <!-- Header -->
    <div class="mb-4">
      <h1 class="h3">Working Hours Configuration</h1>
      <p class="text-muted">Manage your weekly schedule and special dates</p>
    </div>

    <!-- Loading State -->
    <div v-if="store.loading" class="alert alert-info">
      <div class="spinner-border spinner-border-sm me-2"></div>
      Loading your schedule... 
    </div>

    <!-- Error Alert -->
    <div v-if="store.error" class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ store.error }}
      <button type="button" class="btn-close" @click="store.error = null"></button>
    </div>

    <!-- Tabs -->
    <ul v-if="!store.loading" class="nav nav-tabs mb-4">
      <li class="nav-item">
        <button class="nav-link" :class="{ active: activeTab === 'weekly' }" @click="activeTab = 'weekly'">
          Weekly Schedule
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link" :class="{ active: activeTab === 'special' }" @click="activeTab = 'special'">
          Special Dates
        </button>
      </li>
    </ul>

    <!-- WEEKLY TAB -->
    <div v-if="activeTab === 'weekly' && !store.loading" class="row">
      <div class="col-lg-8 mb-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <!-- Title + Buttons -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="card-title mb-0">Weekly Schedule</h5>
              <div class="d-flex gap-2">
                <button 
                  @click="copyMondayToAll" 
                  :disabled="store.syncing"
                  class="btn btn-sm btn-primary">
                  <span v-if="store.syncing" class="spinner-border spinner-border-sm me-2"></span>
                  Copy Monday to All
                </button>
                <button 
                  @click="handleResetDefault" 
                  :disabled="store.syncing"
                  class="btn btn-sm btn-secondary">
                  Reset to Default
                </button>
              </div>
            </div>

            <!-- Week Items -->
            <div
              v-for="(day, dayIndex) in store.workingHours"
              :key="day.day_of_week"
              class="d-flex justify-content-between align-items-center p-2 border rounded mb-2"
            >
              <div>
                <h6 class="mb-0">{{ day.day_name }}</h6>

                <small class="text-muted">
                  <!-- If day is active -->
                  <span v-if="!day.is_day_off">
                    {{ day.start_time }} - {{ day.end_time }}
                  </span>

                  <!-- If day is off -->
                  <span v-else>Closed</span>
                </small>
              </div>

              <div class="d-flex align-items-center gap-2">
                <!-- Break count -->
                <span v-if="day.breaks && day.breaks.length" class="text-warning small">
                  {{ day.breaks.length }} break(s)
                </span>

                <!-- Edit button -->
                <button
                  @click="editDay(dayIndex)"
                  class="btn btn-sm btn-outline-primary"
                >
                  <i class="bi bi-pencil"></i> Edit
                </button>
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="col-lg-4"></div>
    </div>

    <!-- SPECIAL DATES TAB -->
    <div v-if="activeTab === 'special' && !store.loading" class="card shadow-sm mt-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Special Dates</h5>
          <button 
            @click="showSpecialDateModal = true" 
            class="btn btn-sm btn-warning text-white">
            + Add Date
          </button>
        </div>

        <!-- Search & Filter -->
        <div class="mb-3">
          <input 
            type="text" 
            v-model="searchInput"
            @keyup.enter="store.searchSpecialDates(searchInput)"
            placeholder="Search special dates..."
            class="form-control">
        </div>

        <!-- Loading state -->
        <div v-if="store.loadingDates" class="text-center py-3">
          <div class="spinner-border spinner-border-sm"></div>
          Loading...
        </div>

        <!-- Empty state -->
        <div v-else-if="store.specialDates.length === 0" class="text-center py-3 text-muted">
          No special dates configured
        </div>

        <!-- Special dates list -->
        <div v-else>
          <div
            v-for="d in store.specialDates"
            :key="d.id"
            class="d-flex justify-content-between align-items-center p-2 border rounded mb-2">

            <div class="flex-grow-1">
              <strong>{{ d.title }}</strong><br />
              <small class="text-muted">
                {{ formatDate(d.date) }} • 
                <span class="badge" :class="getBadgeClass(d.type)">{{ d.type }}</span>
                <span v-if="d.half_day_type" class="ms-2">({{ d.half_day_type.toUpperCase() }})</span>
                <span v-if="d.start_time" class="ms-2">{{ d.start_time }} - {{ d.end_time }}</span>
              </small>
              <div v-if="d.comment" class="small text-muted mt-1">{{ d.comment }}</div>
            </div>

            <button 
              @click="handleDeleteSpecialDate(d.id)" 
              class="btn btn-sm btn-outline-danger ms-2"
              :disabled="store.syncing">
              <i class="bi bi-trash"></i>
            </button>
          </div>

          <!-- Pagination -->
          <nav v-if="store.specialDatesPagination.last_page > 1" class="mt-3">
            <ul class="pagination justify-content-center mb-0">
              <li class="page-item" :class="{ disabled: store.specialDatesPagination.current_page === 1 }">
                <button 
                  class="page-link" 
                  @click="store.initializeSpecialDates(store.specialDatesPagination.current_page - 1)"
                  :disabled="store.specialDatesPagination.current_page === 1">
                  Previous
                </button>
              </li>

              <li v-for="page in store.specialDatesPagination.last_page" :key="page" class="page-item" 
                :class="{ active: store.specialDatesPagination.current_page === page }">
                <button 
                  class="page-link" 
                  @click="store.initializeSpecialDates(page)">
                  {{ page }}
                </button>
              </li>

              <li class="page-item" :class="{ disabled: store.specialDatesPagination.current_page === store.specialDatesPagination.last_page }">
                <button 
                  class="page-link" 
                  @click="store.initializeSpecialDates(store.specialDatesPagination.current_page + 1)"
                  :disabled="store.specialDatesPagination.current_page === store.specialDatesPagination.last_page">
                  Next
                </button>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>


    <!-- Modals -->
    <DayModal 
      v-if="showDayModal" 
      :dayIndex="selectedDay" 
      @close="closeDayModal"
      @save="handleDayModalSave" />
    <SpecialDateModal 
      v-if="showSpecialDateModal" 
      @close="showSpecialDateModal = false" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useWorkingHoursStore } from '@/stores/workingHours'
import { storeToRefs } from 'pinia'
import Swal from 'sweetalert2'
import DayModal from './DayModal.vue'
import SpecialDateModal from './SpecialDateModal.vue'
import specialDatesApi from '@/services/api/specialDatesApi'

const store = useWorkingHoursStore()
const activeTab = ref('weekly')
const showDayModal = ref(false)
const showSpecialDateModal = ref(false)
const selectedDay = ref(null)
const searchInput = ref('')

onMounted(() => {
  store.initializeStore()
  store.initializeSpecialDates() 
})


const editDay = (dayIndex) => {
  selectedDay.value = dayIndex
  showDayModal.value = true
}

const closeDayModal = () => {
  showDayModal.value = false
  selectedDay.value = null
}

const handleDayModalSave = async (dayIndex) => {
  await store.saveSingleDay(dayIndex)
  closeDayModal()
}

const copyMondayToAll = async () => {
  const confirmed = await Swal.fire({
    title: 'Copy Monday to All?',
    text: 'This will replace all days with Monday\'s schedule.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, copy',
  })

  if (confirmed.isConfirmed) {
    await store.copyMonday()
  }
}

const handleResetDefault = async () => {
  const confirmed = await Swal.fire({
    title: 'Reset all?',
    text: 'This will reset working hours to default.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, reset',
  })

  if (confirmed.isConfirmed) {
    await store.resetDefault()
  }
}

const formatDate = (dateStr) => {
  const date = new Date(dateStr + 'T00:00:00')
  return date.toLocaleDateString('en-US', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' })
}

const getBadgeClass = (type) => {
  const classes = {
    full_off: 'bg-danger',
    half_day: 'bg-warning text-dark',
    extra_hours: 'bg-info',
  }
  return classes[type] || 'bg-secondary'
}

const handleDeleteSpecialDate = (id) => {
  Swal.fire({
    title: 'Delete special date?',
    text: 'This cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete',
  }).then(async r => {
    if (r.isConfirmed) {
      await store.removeSpecialDate(id)
    }
  })
}
</script>