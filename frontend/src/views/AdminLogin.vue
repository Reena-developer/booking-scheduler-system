<template>
  <div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card shadow" style="max-width: 400px; width: 100%;">
      <div class="card-body p-5">
        <h1 class="text-center mb-4 fw-bold">Admin Login</h1>

        <form @submit.prevent="handleLogin">
          
          <!-- Email -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input
              v-model="form.email"
              type="email"
              class="form-control"
              required
            />
          </div>

          <!-- Password -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input
              v-model="form.password"
              type="password"
              class="form-control"
              required
            />
          </div>

          <!-- Error -->
          <div
            v-if="auth.error"
            class="alert alert-danger alert-dismissible fade show"
          >
            {{ auth.error }}
            <button type="button" class="btn-close" @click="auth.error = ''"></button>
          </div>

          <!-- Button -->
          <button
            type="submit"
            class="btn btn-primary w-100 btn-lg"
            :disabled="auth.loading"
          >
            {{ auth.loading ? 'Signing in...' : 'Sign In' }}
          </button>

        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const form = ref({ email: '', password: '' })
const auth = useAuthStore()
const router = useRouter()
const route = useRoute()

const handleLogin = async () => {
  try {
    await auth.adminLogin(form.value.email, form.value.password)
    
    const redirectTo = route.query.redirect || '/admin'
    router.push(redirectTo)

  } catch (err) {
    // Error already set in store, nothing else required
    console.error(err)
  }
}
</script>

<style scoped>
.min-vh-100 {
  min-height: 100vh;
}
</style>
