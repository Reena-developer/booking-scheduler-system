<!-- src/components/shared/Loading.vue -->
<template>
  <div class="d-flex justify-content-center align-items-center" :style="{ height: height }">
    <div class="text-center">
      <div class="spinner-border text-primary mb-3" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="text-muted">{{ message }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  message: {
    type: String,
    default: 'Loading...'
  },
  height: {
    type: String,
    default: '200px'
  }
})
</script>