<template>
  <form @submit.prevent="submitForm">
    <div class="modal-body">
      <div class="mb-3">
        <label class="form-label fw-semibold">Service Name *</label>
        <input v-model="form.name" type="text" class="form-control" required />
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Description</label>
        <textarea v-model="form.description" class="form-control" rows="3"></textarea>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Duration (minutes) *</label>
          <input v-model.number="form.duration" type="number" class="form-control" min="10" required />
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Price (₹) *</label>
          <input v-model.number="form.price" type="number" class="form-control" min="0" step="0.01" required />
        </div>
      </div>

      <div class="mb-3">
        <label class="form-check">
          <input v-model="form.is_active" type="checkbox" class="form-check-input" />
          <span class="form-check-label">Active</span>
        </label>
      </div>

      <div v-if="error" class="alert alert-danger alert-dismissible fade show">
        {{ error }}
        <button type="button" class="btn-close" @click="error = ''"></button>
      </div>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

      <button type="submit" class="btn btn-primary">
        {{ loading ? 'Saving...' : 'Save Service' }}
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  service: Object
})

const emit = defineEmits(['save'])

const loading = ref(false)
const error = ref('')

const form = ref({
  name: '',
  description: '',
  duration: 30,
  price: 0,
  is_active: true
})

// Prefill for edit mode
watch(
  () => props.service,
  (newVal) => {
    form.value = newVal
      ? { ...newVal }
      : { name: '', description: '', duration: 30, price: 0, is_active: true }
  },
  { immediate: true }
)

const submitForm = () => {
  loading.value = true
  error.value = ''

  try {
    emit('save', { ...form.value })
  } catch (err) {
    error.value = err?.message || 'Something went wrong'
  }

  loading.value = false
}
</script>
