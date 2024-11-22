import api from './api'
import { mockServices } from './mockServices'

const isDev = import.meta.env.DEV

export const moderationService = isDev ? {
  // Gestion des commentaires
  getPendingComments: () => Promise.resolve({
    data: {
      data: Array.from({ length: 5 }, (_, i) => ({
        id: i + 1,
        author: `Utilisateur ${i + 1}`,
        content: `Commentaire en attente ${i + 1}`,
        post: `Article ${i + 1}`,
        date: new Date().toISOString(),
        status: 'pending'
      }))
    }
  }),

  approveComment: (id: number) => Promise.resolve({ data: { success: true } }),
  deleteComment: (id: number) => Promise.resolve({ data: { success: true } }),

  // Gestion des signalements
  getReportedContent: () => Promise.resolve({
    data: {
      data: Array.from({ length: 3 }, (_, i) => ({
        id: i + 1,
        type: i % 2 === 0 ? 'comment' : 'post',
        content: `Contenu signalé ${i + 1}`,
        reporter: `Utilisateur ${i + 1}`,
        reason: 'Contenu inapproprié',
        date: new Date().toISOString()
      }))
    }
  }),

  handleReport: (id: number, action: string) => Promise.resolve({ data: { success: true } }),

  // Statistiques
  getModStats: () => Promise.resolve({
    data: {
      data: {
        pendingComments: 5,
        reportedContent: 3,
        totalPosts: 45,
        engagement: '78%',
        recentActivity: Array.from({ length: 5 }, (_, i) => ({
          action: `Action ${i + 1}`,
          date: new Date().toISOString(),
          details: `Détails de l'action ${i + 1}`
        }))
      }
    }
  })
} : {
  getPendingComments: () => api.get('/moderation/comments/pending'),
  approveComment: (id: number) => api.post(`/moderation/comments/${id}/approve`),
  deleteComment: (id: number) => api.delete(`/moderation/comments/${id}`),
  getReportedContent: () => api.get('/moderation/reports'),
  handleReport: (id: number, action: string) => api.post(`/moderation/reports/${id}`, { action }),
  getModStats: () => api.get('/moderation/stats')
}