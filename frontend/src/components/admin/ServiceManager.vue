<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold">Services Management</h2>

      <button class="btn btn-primary" @click="openAddForm">
        <i class="bi bi-plus-circle"></i> Add Service
      </button>
    </div>

    <!-- Loading -->
    <div v-if="servicesStore.loading">
      <Loading message="Loading services..." height="200px" />
    </div>

    <!-- Error -->
    <div v-else-if="servicesStore.error" class="alert alert-danger alert-dismissible fade show">
      {{ servicesStore.error }}
      <button type="button" class="btn-close" @click="servicesStore.error = ''"></button>
    </div>

    <!-- No Data -->
    <div v-else-if="servicesStore.services.length === 0" class="alert alert-info">
      No services added yet.
      <a href="#" @click.prevent="openAddForm">Add your first service</a>
    </div>

    <!-- Table -->
    <div v-else class="table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Duration</th>
            <th>Price</th>
            <th>Active</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="service in servicesStore.services" :key="service.id">
            <td>{{ service.name }}</td>
            <td>{{ service.description }}</td>
            <td>{{ service.duration }} min</td>
            <td>₹{{ service.price }}</td>
            <td>
              <span v-if="service.is_active" class="badge bg-success">Active</span>
              <span v-else class="badge bg-secondary">Inactive</span>
            </td>

            <td>
              <button class="btn btn-sm btn-warning me-2" @click="openEditForm(service)">
                <i class="bi bi-pencil"></i>
              </button>

              <button class="btn btn-sm btn-danger" @click="deleteService(service.id)">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- MODAL -->
    <div class="modal fade" ref="serviceModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">
              {{ editingId ? 'Edit Service' : 'Add Service' }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <ServiceForm :service="editingService" @save="onServiceSaved" />
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import { Modal } from 'bootstrap'
import { useServicesStore } from '@/stores/services'

import Swal from 'sweetalert2'
import ServiceForm from './ServiceForm.vue'
import Loading from '../shared/Loading.vue'

const servicesStore = useServicesStore()

// Modal
const serviceModal = ref(null)
const modal = ref(null)

// Editing
const editingService = ref(null)
const editingId = ref(null)

onMounted(async () => {
  modal.value = new Modal(serviceModal.value)
  await servicesStore.fetchServices()
})

const openAddForm = () => {
  editingId.value = null
  editingService.value = null
  modal.value.show()
}

const openEditForm = (service) => {
  editingId.value = service.id
  editingService.value = { ...service }
  modal.value.show()
}

const deleteService = async (id) => {
  const result = await Swal.fire({
    title: 'Delete Service?',
    text: 'Are you sure you want to delete this service?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, Delete',
    cancelButtonText: 'Cancel',
    reverseButtons: true,
  })

  if (!result.isConfirmed) {
    return
  }

  try {
    await servicesStore.deleteService(id)

    Swal.fire({
      title: 'Deleted!',
      text: 'Service has been deleted successfully.',
      icon: 'success',
      timer: 1500,
      showConfirmButton: false
    })
  } catch (err) {
    Swal.fire({
      title: 'Error',
      text: err.response?.data?.message || 'Failed to delete service',
      icon: 'error',
    })
  }
}

const onServiceSaved = async (payload) => {
  if (editingId.value) {
    await servicesStore.updateService(editingId.value, payload)
  } else {
    await servicesStore.createService(payload)
  }

  modal.value.hide()
}
</script>
