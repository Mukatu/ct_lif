import api from './api'
import { mockServices } from './mockServices'

const isDev = import.meta.env.DEV

export const blogService = isDev ? {
  // Articles
  getPosts: (category?: string) => Promise.resolve({
    data: {
      data: Array.from({ length: 10 }, (_, i) => ({
        id: i + 1,
        title: `Article ${i + 1}`,
        description: `Description de l'article ${i + 1}`,
        content: `Contenu de l'article ${i + 1}`,
        image: `https://picsum.photos/800/400?random=${i}`,
        category: ['events', 'training', 'news', 'projects'][i % 4],
        author: {
          name: 'Service Communication',
          avatar: `https://ui-avatars.com/api/?name=Service+Communication&background=random`
        },
        date: new Date(Date.now() - i * 86400000).toISOString(),
        likes: Math.floor(Math.random() * 50),
        comments: Math.floor(Math.random() * 20)
      }))
    }
  }),

  getPost: (id: number) => Promise.resolve({
    data: {
      data: {
        id,
        title: `Article ${id}`,
        description: `Description de l'article ${id}`,
        content: `Contenu de l'article ${id}`,
        image: `https://picsum.photos/800/400?random=${id}`,
        category: ['events', 'training', 'news', 'projects'][id % 4],
        author: {
          name: 'Service Communication',
          avatar: `https://ui-avatars.com/api/?name=Service+Communication&background=random`
        },
        date: new Date().toISOString(),
        likes: Math.floor(Math.random() * 50),
        comments: Math.floor(Math.random() * 20)
      }
    }
  }),

  createPost: (data: FormData) => Promise.resolve({ 
    data: { 
      data: { 
        id: Date.now(),
        ...Object.fromEntries(data.entries())
      } 
    } 
  }),

  updatePost: (id: number, data: FormData) => Promise.resolve({ 
    data: { 
      data: { 
        id,
        ...Object.fromEntries(data.entries())
      } 
    } 
  }),

  deletePost: (id: number) => Promise.resolve({ data: { success: true } }),

  // Commentaires
  getComments: (postId: number) => Promise.resolve({
    data: {
      data: Array.from({ length: 5 }, (_, i) => ({
        id: i + 1,
        content: `Commentaire ${i + 1}`,
        author: {
          name: `Utilisateur ${i + 1}`,
          avatar: `https://ui-avatars.com/api/?name=User+${i + 1}&background=random`
        },
        date: new Date().toISOString()
      }))
    }
  }),

  addComment: (postId: number, content: string) => Promise.resolve({
    data: {
      data: {
        id: Date.now(),
        content,
        author: {
          name: 'Utilisateur actuel',
          avatar: `https://ui-avatars.com/api/?name=Current+User&background=random`
        },
        date: new Date().toISOString()
      }
    }
  }),

  // Likes
  toggleLike: (postId: number) => Promise.resolve({
    data: {
      success: true,
      liked: true,
      likesCount: Math.floor(Math.random() * 50)
    }
  })
} : {
  getPosts: (category?: string) => api.get('/posts', { params: { category } }),
  getPost: (id: number) => api.get(`/posts/${id}`),
  createPost: (data: FormData) => api.post('/posts', data),
  updatePost: (id: number, data: FormData) => api.put(`/posts/${id}`, data),
  deletePost: (id: number) => api.delete(`/posts/${id}`),
  getComments: (postId: number) => api.get(`/posts/${postId}/comments`),
  addComment: (postId: number, content: string) => api.post(`/posts/${postId}/comments`, { content }),
  toggleLike: (postId: number) => api.post(`/posts/${postId}/like`)
}