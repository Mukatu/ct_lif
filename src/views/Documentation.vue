<script setup lang="ts">
import { ref } from 'vue'

const documents = ref([
  {
    id: 1,
    title: 'Guide des procédures RH',
    description: 'Documentation complète des processus RH',
    category: 'Procédures',
    downloadUrl: '#'
  },
  {
    id: 2,
    title: 'Règlement intérieur',
    description: 'Règles et politiques de l\'entreprise',
    category: 'Règlements',
    downloadUrl: '#'
  },
  {
    id: 3,
    title: 'Guide des avantages sociaux',
    description: 'Détails sur les avantages offerts aux employés',
    category: 'Avantages',
    downloadUrl: '#'
  }
])

const categories = ref([
  'Tous',
  'Procédures',
  'Règlements',
  'Avantages'
])

const selectedCategory = ref('Tous')
</script>

<template>
  <div class="min-h-[calc(100vh-64px)] bg-gray-50 pt-8">
    <div class="container mx-auto px-4">
      <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Documentation</h1>

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Filtrer par catégorie</label>
          <select
            v-model="selectedCategory"
            class="w-full md:w-64 rounded-md border-gray-300 shadow-sm focus:border-[#F2682C] focus:ring-[#F2682C]"
          >
            <option v-for="category in categories" :key="category" :value="category">
              {{ category }}
            </option>
          </select>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <div
            v-for="doc in documents"
            :key="doc.id"
            class="border rounded-lg p-4 hover:shadow-md transition-shadow"
          >
            <h3 class="text-lg font-semibold text-gray-900">{{ doc.title }}</h3>
            <p class="text-sm text-gray-600 mt-2">{{ doc.description }}</p>
            <div class="mt-4 flex items-center justify-between">
              <span class="text-sm text-[#F2682C]">{{ doc.category }}</span>
              <a
                :href="doc.downloadUrl"
                class="text-sm text-[#F2682C] hover:text-[#e55a20]"
                download
              >
                Télécharger
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>