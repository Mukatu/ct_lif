import axios from 'axios'

const isDev = import.meta.env.DEV

// Create axios instance
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Add auth token to requests
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Handle response errors
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token')
      window.location.href = '/login'
    }
    return Promise.reject(error)
  }
)

// Export services
export const employeeService = isDev ? {
  getAll: () => Promise.resolve({
    data: {
      data: Array.from({ length: 10 }, (_, i) => ({
        id: i + 1,
        name: `Employé ${i + 1}`,
        department: ['CAB-DG', 'DARH', 'DJR', 'DFC', 'DCM', 'DEW', 'DT', 'DIP'][i % 8],
        position: `Poste ${i + 1}`,
        sex: i % 2 === 0 ? 'M' : 'F',
        category: String(Math.floor(Math.random() * 13) + 1),
        echelon: String(Math.floor(Math.random() * 10) + 1),
        birthDate: '1990-01-01',
        hireDate: '2020-01-01',
        address: `Adresse ${i + 1}`,
        contact: `+242 06 123 45 6${i}`,
        rib: `RIB00000${i}`,
        status: 'Actif'
      }))
    }
  }),
  import: (data: FormData) => Promise.resolve({ data: { success: true } })
} : {
  getAll: () => api.get('/employees'),
  import: (data: FormData) => api.post('/employees/import', data)
}

export const leaveService = isDev ? {
  getAll: () => Promise.resolve({
    data: {
      data: Array.from({ length: 5 }, (_, i) => ({
        id: i + 1,
        employee: `Employé ${i + 1}`,
        type: ['paid', 'unpaid', 'sick'][i % 3],
        startDate: '2024-03-01',
        endDate: '2024-03-05',
        reason: 'Congé annuel',
        status: ['pending', 'approved', 'rejected'][i % 3]
      }))
    }
  }),
  create: (data: any) => Promise.resolve({ data: { success: true } }),
  update: (id: number, data: any) => Promise.resolve({ data: { success: true } }),
  delete: (id: number) => Promise.resolve({ data: { success: true } })
} : {
  getAll: () => api.get('/leaves'),
  create: (data: any) => api.post('/leaves', data),
  update: (id: number, data: any) => api.put(`/leaves/${id}`, data),
  delete: (id: number) => api.delete(`/leaves/${id}`)
}

export const documentService = isDev ? {
  getAll: () => Promise.resolve({
    data: {
      data: Array.from({ length: 5 }, (_, i) => ({
        id: i + 1,
        title: `Document ${i + 1}`,
        category: ['Procédures', 'Règlements', 'Formulaires'][i % 3],
        created_at: new Date().toISOString(),
        size: '1.2 MB'
      }))
    }
  }),
  create: (data: FormData) => Promise.resolve({ data: { success: true } }),
  delete: (id: number) => Promise.resolve({ data: { success: true } }),
  download: (id: number) => Promise.resolve({ data: new Blob(['test']) })
} : {
  getAll: () => api.get('/documents'),
  create: (data: FormData) => api.post('/documents', data),
  delete: (id: number) => api.delete(`/documents/${id}`),
  download: (id: number) => api.get(`/documents/${id}/download`, { responseType: 'blob' })
}

export const trainingService = isDev ? {
  getAll: () => Promise.resolve({
    data: {
      data: Array.from({ length: 3 }, (_, i) => ({
        id: i + 1,
        title: `Formation ${i + 1}`,
        description: `Description de la formation ${i + 1}`,
        duration: '2h',
        level: ['Débutant', 'Intermédiaire', 'Avancé'][i % 3],
        category: ['RH', 'Management', 'Communication'][i % 3],
        thumbnail: `https://picsum.photos/400/225?random=${i}`,
        url: `https://example.com/video${i + 1}`,
        participants: Math.floor(Math.random() * 20)
      }))
    }
  }),
  create: (data: FormData) => Promise.resolve({ data: { success: true } }),
  update: (id: number, data: FormData) => Promise.resolve({ data: { success: true } }),
  delete: (id: number) => Promise.resolve({ data: { success: true } })
} : {
  getAll: () => api.get('/trainings'),
  create: (data: FormData) => api.post('/trainings', data),
  update: (id: number, data: FormData) => api.put(`/trainings/${id}`, data),
  delete: (id: number) => api.delete(`/trainings/${id}`)
}

export const performanceService = isDev ? {
  getAll: () => Promise.resolve({
    data: {
      data: Array.from({ length: 5 }, (_, i) => ({
        id: i + 1,
        employee: `Employé ${i + 1}`,
        department: ['CAB-DG', 'DARH', 'DJR'][i % 3],
        period: '2024 T1',
        score: Math.floor(Math.random() * 40) + 60,
        status: ['En cours', 'Complété'][i % 2]
      }))
    }
  })
} : {
  getAll: () => api.get('/evaluations')
}

export const blogService = isDev ? {
  getPosts: () => Promise.resolve({
    data: {
      data: Array.from({ length: 10 }, (_, i) => ({
        id: i + 1,
        title: `Article ${i + 1}`,
        description: `Description de l'article ${i + 1}`,
        content: `Contenu de l'article ${i + 1}`,
        image: `https://picsum.photos/800/400?random=${i}`,
        category: ['Événements', 'Formation', 'Actualités', 'Projets'][i % 4],
        author: {
          name: 'Service Communication',
          avatar: `https://ui-avatars.com/api/?name=Service+Communication&background=random`
        },
        date: new Date().toISOString(),
        likes: Math.floor(Math.random() * 50),
        comments: Math.floor(Math.random() * 20)
      }))
    }
  }),
  getPhotos: () => Promise.resolve({
    data: {
      data: Array.from({ length: 12 }, (_, i) => ({
        id: i + 1,
        title: `Photo ${i + 1}`,
        description: `Description de la photo ${i + 1}`,
        url: `https://picsum.photos/800/600?random=${i}`,
        category: ['Événements', 'Formation', 'Team Building', 'Célébrations'][i % 4],
        date: new Date().toISOString()
      }))
    }
  })
} : {
  getPosts: () => api.get('/posts'),
  getPhotos: () => api.get('/photos')
}

export default api