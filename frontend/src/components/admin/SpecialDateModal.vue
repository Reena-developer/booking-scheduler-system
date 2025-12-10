<template>
  <div class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Special Date</h5>
          <button type="button" class="btn-close" @click="$emit('close')"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label>Date * <span class="text-muted small">(Must be future date)</span></label>
            <input 
              type="date" 
              v-model="form.date" 
              :min="today"
              class="form-control"
              @change="validateDate">
            <small v-if="errors.date" class="text-danger">{{ errors.date }}</small>
          </div>

          <div class="mb-2">
            <label>Type *</label>
            <select v-model="form.type" class="form-select">
              <option value="full_off">Full Day Off</option>
              <option value="extra_hours">Extra Hours</option>
            </select>
          </div>
          
          <div v-if="form.type === 'extra_hours'" class="row mb-2">
            <div class="col">
              <label>Start Time</label>
              <input 
                type="time" 
                v-model="form.start_time" 
                class="form-control"
                @change="validateTimes">
            </div>
            <div class="col">
              <label>End Time</label>
              <input 
                type="time" 
                v-model="form.end_time" 
                class="form-control"
                @change="validateTimes">
            </div>
          </div>
          <small v-if="errors.time" class="text-danger">{{ errors.time }}</small>

          <div class="mb-2">
            <label>Title *</label>
            <input 
              type="text" 
              v-model="form.title" 
              class="form-control" 
              placeholder="e.g., Christmas, New Year"
              maxlength="100">
            <small v-if="errors.title" class="text-danger">{{ errors.title }}</small>
          </div>

          <div class="mb-2">
            <label>Comment (optional)</label>
            <textarea 
              v-model="form.comment" 
              class="form-control" 
              rows="2" 
              placeholder="Add notes..."
              maxlength="255"></textarea>
          </div>

          <div v-if="error" class="alert alert-danger small">{{ error }}</div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" @click="$emit('close')">Cancel</button>
          <button 
            class="btn btn-warning" 
            @click="handleAddSpecialDate"
            :disabled="store.syncing || !isFormValid()">
            {{ store.syncing ? 'Adding...' : 'Add Date' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useWorkingHoursStore } from '@/stores/workingHours'
import Swal from 'sweetalert2'

const emit = defineEmits(['close'])
const store = useWorkingHoursStore()

// Get today's date in YYYY-MM-DD format
const today = computed(() => {
  const date = new Date()
  return date.toISOString().split('T')[0]
})

const form = ref({
  date: '',
  type: 'full_off',
  half_day_type: 'am',
  start_time: '',
  end_time: '',
  title: '',
  comment: '',
})

const errors = ref({
  date: '',
  title: '',
  time: '',
})

const error = ref('')

const validateDate = () => {
  errors.value.date = ''
  if (!form.value.date) {
    errors.value.date = 'Date is required'
    return false
  }

  const selectedDate = new Date(form.value.date)
  const todayDate = new Date(today.value)
  
  if (selectedDate < todayDate) {
    errors.value.date = 'Cannot select past dates'
    return false
  }

  return true
}

const validateTimes = () => {
  errors.value.time = ''
  if (!form.value.start_time || !form.value.end_time) {
    return true
  }

  if (form.value.start_time >= form.value.end_time) {
    errors.value.time = 'Start time must be before end time'
    return false
  }

  return true
}

const isFormValid = () => {
  if (!form.value.date || !form.value.title) return false
  if (form.value.type === 'extra_hours' && !form.value.start_time && !form.value.end_time) return false
  return !errors.value.date && !errors.value.time
}

const handleAddSpecialDate = async () => {
  error.value = ''
  
  if (!validateDate()) return
  if (!form.value.title.trim()) {
    errors.value.title = 'Title is required'
    return
  }

  if (form.value.type === 'extra_hours') {
    if (!validateTimes()) return
  }

  const payload = {
    date: form.value.date,
    type: form.value.type,
    title: form.value.title.trim(),
    comment: form.value.comment.trim() || null,
  }

  if (form.value.type === 'extra_hours') {
    payload.start_time = form.value.start_time
    payload.end_time = form.value.end_time
  }

  const success = await store.addSpecialDate(payload)
  if (success) {
    emit('close')
  }
}
</script>