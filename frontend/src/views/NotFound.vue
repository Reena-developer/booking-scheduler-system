<template>
  <div class="h-screen flex flex-col items-center justify-center text-center px-4">
    <h1 class="text-5xl font-bold mb-4">404</h1>
    <h2 class="text-xl font-semibold mb-2">Page Not Found</h2>
    <p class="text-gray-600 mb-6">
      The page you're looking for doesn't exist.
    </p>

    <router-link
      to="/"
      class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
    >
      Go Home
    </router-link>
  </div>
</template>

<script setup>
// No script needed for a simple 404 page
</script>

<style scoped>
/* Optional styling if not using Tailwind */
</style>
