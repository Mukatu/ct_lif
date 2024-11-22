<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { moderationService } from '../../services/moderationService'
import CommentsModal from './CommentsModal.vue'
import ReportsModal from './ReportsModal.vue'
import DraftsModal from './DraftsModal.vue'
import HistoryModal from './HistoryModal.vue'
import { useNotification } from '../../composables/useNotification'

const notification = useNotification()

// Modals visibility
const showCommentsModal = ref(false)
const showReportsModal = ref(false)
const showDraftsModal = ref(false)
const showHistoryModal = ref(false)

// Data
const pendingComments = ref([])
const reportedContent = ref([])
const drafts = ref([])
const history = ref([])
const stats = ref({
  pendingComments: 0,
  reportedContent: 0,
  totalPosts: 0,
  engagement: '0%'
})

// Load data
const loadData = async () => {
  try {
    const response = await moderationService.getModStats()
    stats.value = response.data.data
  } catch (error) {
    notification.show({
      type: 'error',
      message: 'Erreur lors du chargement des statistiques'
    })
  }
}

// Comments management
const handleComment = async (id: number, action: 'approve' | 'delete') => {
  try {
    if (action === 'approve') {
      await moderationService.approveComment(id)
    } else {
      await moderationService.deleteComment(id)
    }
    notification.show({
      type: 'success',
      message: action === 'approve' ? 'Commentaire approuvé' : 'Commentaire supprimé'
    })
    loadData()
  } catch (error) {
    notification.show({
      type: 'error',
      message: 'Erreur lors de la gestion du commentaire'
    })
  }
}

// Reports management
const handleReport = async (id: number) => {
  try {
    await moderationService.handleReport(id, 'resolve')
    notification.show({
      type: 'success',
      message: 'Signalement traité'
    })
    loadData()
  } catch (error) {
    notification.show({
      type: 'error',
      message: 'Erreur lors du traitement du signalement'
    })
  }
}

onMounted(loadData)
</script>

<template>
  <div class="space-y-6">
    <!-- Quick Stats -->
    <div class="grid grid-cols-2 gap-4">
      <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="text-sm font-medium text-gray-500">Commentaires en attente</h3>
        <p class="mt-2 text-3xl font-bold text-[#F2682C]">{{ stats.pendingComments }}</p>
      </div>
      <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="text-sm font-medium text-gray-500">Contenu signalé</h3>
        <p class="mt-2 text-3xl font-bold text-[#F2682C]">{{ stats.reportedContent }}</p>
      </div>
    </div>

    <!-- Actions -->
    <div class="bg-white rounded-lg shadow">
      <div class="p-4">
        <h2 class="text-lg font-medium text-gray-900">Actions rapides</h2>
        <div class="mt-4 space-y-4">
          <button
            @click="showCommentsModal = true"
            class="w-full flex items-center justify-between px-4 py-2 text-left text-gray-700 hover:bg-gray-50 rounded-lg"
          >
            <span>Modérer les commentaires</span>
            <span v-if="stats.pendingComments > 0" class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs">
              {{ stats.pendingComments }}
            </span>
          </button>

          <button
            @click="showReportsModal = true"
            class="w-full flex items-center justify-between px-4 py-2 text-left text-gray-700 hover:bg-gray-50 rounded-lg"
          >
            <span>Contenu signalé</span>
            <span v-if="stats.reportedContent > 0" class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs">
              {{ stats.reportedContent }}
            </span>
          </button>

          <button
            @click="showDraftsModal = true"
            class="w-full flex items-center px-4 py-2 text-left text-gray-700 hover:bg-gray-50 rounded-lg"
          >
            <span>Gérer les brouillons</span>
          </button>

          <button
            @click="showHistoryModal = true"
            class="w-full flex items-center px-4 py-2 text-left text-gray-700 hover:bg-gray-50 rounded-lg"
          >
            <span>Historique des activités</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <CommentsModal
      v-if="showCommentsModal"
      :comments="pendingComments"
      @close="showCommentsModal = false"
      @approve="id => handleComment(id, 'approve')"
      @delete="id => handleComment(id, 'delete')"
    />

    <ReportsModal
      v-if="showReportsModal"
      :reports="reportedContent"
      @close="showReportsModal = false"
      @handle="handleReport"
    />

    <DraftsModal
      v-if="showDraftsModal"
      :drafts="drafts"
      @close="showDraftsModal = false"
    />

    <HistoryModal
      v-if="showHistoryModal"
      :history="history"
      @close="showHistoryModal = false"
    />
  </div>
</template>