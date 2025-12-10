import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes = [
  {
    path: '/',
    component: () => import('@/views/BookAppointment.vue'),
    name: 'BookAppointment',
  },
  
  // Admin Routes
  {
    path: '/admin/login',
    component: () => import('@/views/AdminLogin.vue'),
    name: 'AdminLogin',
  },
  {
    path: '/admin',
    component: () => import('@/views/AdminDashboard.vue'),
    name: 'AdminDashboard',
    meta: { requiresAdmin: true },
    children: [
      {
        path: 'services',
        component: () => import('@/components/admin/ServiceManager.vue'),
        name: 'ServiceManager',
      },
      {
        path: 'schedule',
        component: () => import('@/components/admin/WorkingHoursManager.vue'),
        name: 'WorkingHours',
      },
      {
        path: 'bookings',
        component: () => import('@/components/admin/BookingsList.vue'),
        name: 'Bookings',
      },
    ],
  },

  // 404
  {
    path: '/:pathMatch(.*)*',
    component: () => import('@/views/NotFound.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Navigation Guard - Only for Admin Routes
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  if (to.meta.requiresAdmin && !authStore.adminToken) {
    return next('/admin/login')
  }

  next()
})

export default router