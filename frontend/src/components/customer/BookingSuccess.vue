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
</script>