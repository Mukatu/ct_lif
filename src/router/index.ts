import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import Home from '../views/Home.vue'
import Login from '../views/auth/Login.vue'
import Feed from '../views/Feed.vue'
import HRDashboard from '../views/hr/Dashboard.vue'
import LeaveManagement from '../views/hr/LeaveManagement.vue'
import DocumentManagement from '../views/hr/DocumentManagement.vue'
import TrainingManagement from '../views/hr/TrainingManagement.vue'
import EmployeeManagement from '../views/hr/EmployeeManagement.vue'
import PerformanceManagement from '../views/hr/PerformanceManagement.vue'
import Gallery from '../views/Gallery.vue'
import Documentation from '../views/Documentation.vue'
import Trainings from '../views/Trainings.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    },
    {
      path: '/feed',
      name: 'feed',
      component: Feed,
      meta: { requiresAuth: true }
    },
    {
      path: '/hr',
      component: HRDashboard,
      meta: { requiresAuth: true, requiresHR: true },
      children: [
        {
          path: '',
          name: 'hr-dashboard',
          component: LeaveManagement
        },
        {
          path: 'leaves',
          name: 'leave-management',
          component: LeaveManagement
        },
        {
          path: 'documents',
          name: 'document-management',
          component: DocumentManagement
        },
        {
          path: 'trainings',
          name: 'training-management',
          component: TrainingManagement
        },
        {
          path: 'employees',
          name: 'employee-management',
          component: EmployeeManagement
        },
        {
          path: 'performance',
          name: 'performance-management',
          component: PerformanceManagement
        }
      ]
    },
    {
      path: '/gallery',
      name: 'gallery',
      component: Gallery,
      meta: { requiresAuth: true }
    },
    {
      path: '/documentation',
      name: 'documentation',
      component: Documentation,
      meta: { requiresAuth: true }
    },
    {
      path: '/trainings',
      name: 'trainings',
      component: Trainings,
      meta: { requiresAuth: true }
    }
  ]
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
  } else if (to.meta.requiresHR && !authStore.user?.role === 'hr') {
    next('/feed')
  } else {
    next()
  }
})

export default router