<template>
  <div>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
      <div class="container-lg">
        <router-link to="/admin" class="navbar-brand fw-bold">
          <i class="bi bi-speedometer2"></i> Admin Dashboard
        </router-link>

        <div class="ms-auto d-flex align-items-center gap-3">
          <div class="dropdown">
            <button 
              class="btn btn-secondary btn-sm dropdown-toggle" 
              type="button" 
              data-bs-toggle="dropdown"
            >
              <i class="bi bi-person-circle"></i> {{ admin?.name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Settings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger cursor-pointer" @click="logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="min-vh-100 bg-light py-5">
      <div class="container-lg">
        
        <!-- Dashboard Header (only show on main dashboard page) -->
        <div v-if="!showChildRoute" class="mb-5">
          <h1 class="fw-bold mb-2">Dashboard</h1>
          <p class="text-muted">Welcome back, {{ admin?.name }}!</p>
        </div>

        <!-- Cards Grid (only show on main dashboard page) -->
        <div v-if="!showChildRoute" class="row g-4 mb-5">
          <!-- Manage Services -->
          <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm h-100 dashboard-card" @click="goTo('services')">
              <div class="card-body text-center py-5">
                <div class="mb-3">
                  <i class="bi bi-collection" style="font-size: 3rem; color: #0d6efd;"></i>
                </div>
                <h5 class="card-title fw-bold">Manage Services</h5>
                <p class="card-text text-muted small">Add, edit or delete available services and set pricing.</p>
                <span class="badge bg-primary mt-3">Manage</span>
              </div>
            </div>
          </div>

          <!-- Manage Schedule -->
          <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm h-100 dashboard-card" @click="goTo('schedule')">
              <div class="card-body text-center py-5">
                <div class="mb-3">
                  <i class="bi bi-calendar-week" style="font-size: 3rem; color: #198754;"></i>
                </div>
                <h5 class="card-title fw-bold">Working Hours</h5>
                <p class="card-text text-muted small">Configure working hours, breaks, and special holidays.</p>
                <span class="badge bg-success mt-3">Configure</span>
              </div>
            </div>
          </div>

          <!-- Manage Bookings -->
          <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm h-100 dashboard-card" @click="goTo('bookings')">
              <div class="card-body text-center py-5">
                <div class="mb-3">
                  <i class="bi bi-calendar-check" style="font-size: 3rem; color: #dc3545;"></i>
                </div>
                <h5 class="card-title fw-bold">Bookings</h5>
                <p class="card-text text-muted small">View and manage all customer appointments and details.</p>
                <span class="badge bg-danger mt-3">{{ totalBookings || 0 }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Child Route View -->
        <router-view 
          v-slot="{ Component }"
          @update-bookings="fetchBookingsCount"
        >
          <keep-alive>
            <component :is="Component" />
          </keep-alive>
        </router-view>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { storeToRefs } from 'pinia'

// ===========================
// STORES & ROUTER
// ===========================
const authStore = useAuthStore()
const { admin } = storeToRefs(authStore)
const router = useRouter()
const route = useRoute()

// ===========================
// STATE
// ===========================
const totalBookings = ref(0)

// Check if child route is active
const showChildRoute = computed(() => {
  return route.path !== '/admin'
})

// ===========================
// METHODS
// ===========================
const goTo = (path) => {
  router.push(`/admin/${path}`)
}

const logout = () => {
  if (confirm('Are you sure you want to logout?')) {
    authStore.adminLogout()
    router.push('/admin/login')
  }
}

const fetchBookingsCount = async () => {
  try {
    const { data } = await authStore.adminApi.get('/bookings')
    totalBookings.value = data.data?.length || 0
  } catch (err) {
    console.error('Failed to fetch bookings count:', err)
  }
}

// ===========================
// LIFECYCLE
// ===========================
onMounted(() => {
  // Fetch bookings count on mount
  fetchBookingsCount()
})
</script>

<style scoped>
/* Navigation */
.navbar {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.navbar-brand {
  font-size: 1.25rem;
  letter-spacing: 0.5px;
}

/* Dashboard Cards */
.dashboard-card {
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  border-top: 3px solid transparent;
}

.dashboard-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
  border-top-color: #0d6efd;
}

.dashboard-card:nth-child(2):hover {
  border-top-color: #198754;
}

.dashboard-card:nth-child(3):hover {
  border-top-color: #dc3545;
}

.dashboard-card .card-body {
  cursor: pointer;
}

.dashboard-card .bi {
  transition: transform 0.3s ease;
}

.dashboard-card:hover .bi {
  transform: scale(1.1) rotate(5deg);
}

/* Background */
.min-vh-100 {
  min-height: 100vh;
}

.bg-light {
  background-color: #f8f9fa;
}

/* Dropdown */
.dropdown-menu-end {
  right: 0 !important;
}

.dropdown-item {
  transition: all 0.2s ease;
}

.dropdown-item:hover {
  background-color: #f8f9fa;
  padding-left: 1.5rem;
}

.dropdown-item.text-danger:hover {
  background-color: #fff5f5;
  color: #c92a2a !important;
}

/* Typography */
h1 {
  color: #212529;
  letter-spacing: 0.3px;
}

.text-muted {
  color: #6c757d;
}

/* Badge */
.badge {
  font-weight: 600;
  padding: 0.5rem 1rem;
  font-size: 0.85rem;
}

/* Responsive */
@media (max-width: 768px) {
  .navbar-brand {
    font-size: 1.1rem;
  }

  .dashboard-card .bi {
    font-size: 2rem !important;
  }

  .dashboard-card:hover {
    transform: translateY(-4px);
  }
}

/* Utility */
.cursor-pointer {
  cursor: pointer;
}

.sticky-top {
  top: 0;
  z-index: 999;
}
</style>