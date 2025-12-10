<template>
  <div>
    <div v-if="loading" class="text-center my-3">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="text-muted mt-2">Loading available slots...</p>
    </div>

    <div v-else>
      <div v-if="slots.length > 0" class="mb-3">
        <label class="form-label fw-semibold">Available Slots *</label>
        <div class="row g-2">
          <div
            v-for="slot in slots"
            :key="slot.start_time"
            class="col-6 col-md-4 col-lg-3"
          >
            <button
              type="button"
              class="btn w-100"
              :class="{
                'btn-primary': selectedSlot?.start_time === slot.start_time,
                'btn-outline-primary': selectedSlot?.start_time !== slot.start_time
              }"
              @click="selectSlot(slot)"
            >
              {{ slot.display }}
            </button>
          </div>
        </div>
      </div>

      <div v-else class="alert alert-warning mb-0">
        No available slots on this date.
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useBookingsStore } from '@/stores/bookings'

const props = defineProps({
  serviceId: {
    type: [String, Number],
    required: true
  },
  date: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['slot-selected'])

const bookingStore = useBookingsStore()
const slots = ref([])
const selectedSlot = ref(null)
const loading = ref(false)

const fetchSlots = async () => {
  if (!props.serviceId || !props.date) return

  loading.value = true
  slots.value = []
  selectedSlot.value = null

  try {
    const response = await bookingStore.getAvailableSlots(
      props.serviceId,
      props.date
    )
    slots.value = response.slots || []

  } catch (err) {
    console.error('Failed to fetch slots:', err)
  } finally {
    loading.value = false
  }
}

const selectSlot = (slot) => {
  selectedSlot.value = slot
  emit('slot-selected', slot) 
}


watch(
  [() => props.date, () => props.serviceId],
  fetchSlots,
  { immediate: true }
)
</script>

<style scoped>
.btn {
  white-space: nowrap;
}
</style>
