<template>
  <div class="card border-primary mb-4">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Booking Summary</h5>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-6">
          <p class="text-muted mb-1">Service</p>
          <p class="fw-semibold">{{ booking.service_name }}</p>
        </div>
        <div class="col-6">
          <p class="text-muted mb-1">Duration</p>
          <p class="fw-semibold">{{ booking.duration }} minutes</p>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-6">
          <p class="text-muted mb-1">Date & Time</p>
          <p class="fw-semibold">{{ booking.date }} at {{ booking.time }}</p>
        </div>
        <div class="col-6">
          <p class="text-muted mb-1">Price</p>
          <p class="fw-semibold text-success">₹{{ booking.price }}</p>
        </div>
      </div>

      <hr>

      <div class="row">
        <div class="col-6">
          <p class="text-muted mb-1">Name</p>
          <p class="fw-semibold">{{ booking.name }}</p>
        </div>
        <div class="col-6">
          <p class="text-muted mb-1">Email</p>
          <p class="fw-semibold">{{ booking.email }}</p>
        </div>
      </div>

      <div>
        <p class="text-muted mb-1">Phone</p>
        <p class="fw-semibold">{{ booking.phone }}</p>
      </div>

      <div v-if="booking.notes">
        <p class="text-muted mb-1">Notes</p>
        <p>{{ booking.notes }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  booking: {
    type: Object,
    required: true
  }
})
</script>

---

<!-- src/components/customer/BookingSuccess.vue -->
<template>
  <div class="text-center py-5">
    <div class="mb-4">
      <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
    </div>
    
    <h2 class="fw-bold mb-3">Booking Confirmed!</h2>
    <p class="text-muted mb-4">Your appointment has been successfully booked.</p>

    <div class="card mb-4 text-start">
      <div class="card-body">
        <p class="mb-2">
          <strong>Confirmation Details:</strong><br>
          <small>A confirmation email has been sent to <strong>{{ email }}</strong></small>
        </p>
        <p class="mb-0">
          <strong>What's Next:</strong><br>
          <small>Please arrive 10 minutes early. If you need to cancel, contact us as soon as possible.</small>
        </p>
      </div>
    </div>

    <router-link to="/" class="btn btn-primary">
      <i class="bi bi-house"></i> Back to Home
    </router-link>
  </div>
</template>

<script setup>
defineProps({
  email: {
    type: String,
    required: true
  }
})
</script><!-- ============================================
     SHARED COMPONENTS
     ============================================ -->

<!-- src/components/shared/Header.vue -->
<template>
  <header class="bg-primary text-white py-3">
    <div class="container-lg d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <i class="bi bi-calendar-check fs-4"></i>
        <h1 class="h4 mb-0">Booking Scheduler</h1>
      </div>
      <nav class="d-flex gap-3 align-items-center">
        <a href="/" class="text-white text-decoration-none">Home</a>
        <a href="#services" class="text-white text-decoration-none">Services</a>
        <a href="/admin/login" class="btn btn-light btn-sm">Admin</a>
      </nav>
    </div>
  </header>
</template>

<script setup>
</script>

<style scoped>
header {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
</style>