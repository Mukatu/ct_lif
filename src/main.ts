import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import './style.css'
import App from './App.vue'

// Error handling
window.onerror = function(msg, url, line, col, error) {
  // Log to monitoring service in production
  if (import.meta.env.PROD) {
    console.error('Global error:', { msg, url, line, col, error })
  }
  return false
}

window.onunhandledrejection = function(event) {
  // Log to monitoring service in production
  if (import.meta.env.PROD) {
    console.error('Unhandled promise rejection:', event.reason)
  }
}

const app = createApp(App)

// Global error handler
app.config.errorHandler = (err, instance, info) => {
  // Log to monitoring service in production
  if (import.meta.env.PROD) {
    console.error('Vue error handler:', err, info)
  }
}

app.use(createPinia())
app.use(router)
app.mount('#app')