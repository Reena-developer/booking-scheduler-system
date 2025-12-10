<template>
  <div class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Configure {{ store.dayNames[dayIndex] }}</h5>
          <button type="button" class="btn-close" @click="$emit('close')"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3 form-check">
            <input 
              type="checkbox" 
              class="form-check-input" 
              v-model="day.is_day_off" 
              id="activeCheck">
            <label class="form-check-label" for="activeCheck">Day Off</label>
          </div>

          <div v-if="!day.is_day_off" class="row mb-3">
            <div class="col">
              <label>Start Time</label>
              <input type="time" v-model="day.start_time" class="form-control">
            </div>
            <div class="col">
              <label>End Time</label>
              <input type="time" v-model="day.end_time" class="form-control">
            </div>
          </div>
          <div v-if="errors.hours" class="text-danger mb-2">{{ errors.hours }}</div>

          <!-- Breaks -->
          <div v-if="!day.is_day_off" class="mb-3">
            <h6>Breaks</h6>
            <div 
              v-for="b in day.breaks" 
              :key="b.id" 
              class="d-flex justify-content-between align-items-center p-2 border rounded mb-2 bg-light">
              <div>
                <strong>{{ b.title }}</strong><br>
                <small>{{ b.start_time }} - {{ b.end_time }}</small>
              </div>
              <button @click="deleteBreak(b.id)" class="btn btn-sm btn-outline-danger">Delete</button>
            </div>

            <div class="p-3 border rounded bg-light">
              <input 
                type="text" 
                v-model="newBreak.title" 
                placeholder="Title" 
                class="form-control mb-2">
              <div class="row mb-2">
                <div class="col">
                  <input type="time" v-model="newBreak.start_time" class="form-control">
                </div>
                <div class="col">
                  <input type="time" v-model="newBreak.end_time" class="form-control">
                </div>
              </div>
              <input 
                type="text" 
                v-model="newBreak.comment" 
                placeholder="Comment (optional)" 
                class="form-control mb-2">
              <button @click="addBreak" class="btn btn-warning w-100">Add Break</button>
              <div v-if="errors.break" class="text-danger mt-1">{{ errors.break }}</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" @click="$emit('close')">Cancel</button>
          <button 
            class="btn btn-primary" 
            @click="saveDay"
            :disabled="store.syncing">
            {{ store.syncing ? 'Saving...' : 'Save' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useWorkingHoursStore } from '@/stores/workingHours'
import Swal from 'sweetalert2'

const props = defineProps({ dayIndex: Number })
const emit = defineEmits(['close', 'save'])
const store = useWorkingHoursStore()

const day = ref({ ...store.getDay(props.dayIndex) })
const newBreak = ref({ title: '', start_time: '', end_time: '', comment: '' })
const errors = ref({ hours: '', break: '' })

const addBreak = () => {
  errors.value.break = ''
  if (!newBreak.value.title || !newBreak.value.start_time || !newBreak.value.end_time) {
    errors.value.break = 'All break fields are required'
    return
  }
  if (newBreak.value.start_time >= newBreak.value.end_time) {
    errors.value.break = 'Break start must be before end'
    return
  }
  const overlap = day.value.breaks.some(b => 
    !(newBreak.value.end_time <= b.start_time || newBreak.value.start_time >= b.end_time)
  )
  if (overlap) {
    errors.value.break = 'Break overlaps with existing break'
    return
  }
  day.value.breaks.push({ ...newBreak.value, id: Date.now() })
  newBreak.value = { title: '', start_time: '', end_time: '', comment: '' }
}

const deleteBreak = (id) => {
  Swal.fire({
    icon: 'warning',
    title: 'Delete Break?',
    showCancelButton: true,
    confirmButtonText: 'Yes',
  }).then(res => {
    if (res.isConfirmed) {
      day.value.breaks = day.value.breaks.filter(b => b.id !== id)
    }
  })
}

const saveDay = async () => {
  errors.value.hours = ''
  if (day.value.is_day_off) {
    // If day is off, just save
    store.workingHours[props.dayIndex] = { ...day.value }
    emit('save', props.dayIndex)
    emit('close')
    return
  }

  if (!day.value.start_time || !day.value.end_time) {
    errors.value.hours = 'Start and End times required'
    return
  }
  if (day.value.start_time >= day.value.end_time) {
    errors.value.hours = 'Start time must be before end'
    return
  }

  store.workingHours[props.dayIndex] = { ...day.value }
  emit('save', props.dayIndex)
  emit('close')
}
</script>