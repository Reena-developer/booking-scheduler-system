<template>
  <div>
    <h2 class="fw-bold mb-4">Our Services</h2>

    <div v-if="loading" class="row">
      <div v-for="i in 3" :key="i" class="col-lg-4 col-md-6 mb-4">
        <div class="card" style="height: 300px;">
          <div class="placeholder-glow">
            <div class="placeholder placeholder-lg" style="height: 100%;"></div>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="services.length === 0" class="alert alert-info">
      No services available at the moment.
    </div>

    <div v-else class="row g-4">
      <div v-for="service in services" :key="service.id" class="col-lg-4 col-md-6">
        <ServiceCard :service="service" @book="$emit('book', service)" />
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  services: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
})

defineEmits(['book'])
</script>