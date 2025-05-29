<template>
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card" v-if="book">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
              <i class="bi bi-book"></i> {{ book.titulo }}
            </h4>
            <div class="btn-action-group" v-if="canManage">
              <router-link 
                :to="{ name: 'books.edit', params: { id: book.id } }" 
                class="btn btn-action-vertical btn-edit" 
                title="Editar livro"
              >
                <i class="bi bi-pencil"></i>
              </router-link>
              <button 
                @click="deleteBook" 
                class="btn btn-action-vertical btn-delete" 
                title="Excluir livro"
              >
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="card-body pt-4">
          <div class="row">
            <div class="col-md-3 mb-3">
              <h6 class="text-muted">Capa</h6>
              <img 
                :src="getImageUrl(book.capa)" 
                :alt="`Capa de ${book.titulo}`" 
                class="img-fluid rounded shadow-sm"
                style="max-width: 200px; max-height: 200px; object-fit: cover;"
              >
            </div>
            <div class="col-md-9">
              <div class="row">
                <div class="col-md-6">
                  <h6 class="text-muted">Autor</h6>
                  <p class="mb-3">
                    <i class="bi bi-person"></i> 
                    <router-link 
                      :to="{ name: 'authors.show', params: { id: book.author.id } }" 
                      class="text-decoration-none"
                    >
                      {{ book.author.nome }}
                    </router-link>
                  </p>
                </div>
                <div class="col-md-6">
                  <h6 class="text-muted">Data de Publicação</h6>
                  <p class="mb-3">
                    <i class="bi bi-calendar"></i> {{ formatDate(book.data_publicacao) }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 mt-3">
            <h6 class="text-muted">Descrição</h6>
            <div class="border rounded p-3 bg-light">
              <div v-html="formatDescription(book.descricao)"></div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="mt-4">
            <router-link 
              :to="{ name: 'books.index' }" 
              class="btn btn-custom-outline"
            >
              <i class="bi bi-arrow-left"></i> Voltar à Lista
            </router-link>
          </div>
        </div>
      </div>

      <!-- Loading state -->
      <div v-else-if="loading" class="text-center py-5">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Carregando...</span>
        </div>
      </div>

      <!-- Error state -->
      <div v-else class="alert alert-danger">
        <i class="bi bi-exclamation-triangle"></i>
        Livro não encontrado.
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

export default {
  name: 'BookShow',
  setup() {
    const route = useRoute()
    const router = useRouter()
    const book = ref(null)
    const loading = ref(true)
    
    const getImageUrl = (path) => {
      if (!path) return '/images/default-book-cover.svg'
      return `/storage/${path}`
    }

    const canManage = computed(() => {
      const user = JSON.parse(localStorage.getItem('user') || '{}')
      return user.role === 'admin'
    })

    const loadBook = async () => {
      try {
        loading.value = true
        const response = await axios.get(`/books/${route.params.id}`)
        if (response.data.success) {
          book.value = response.data.data
        }
      } catch (error) {
        console.error('Erro ao carregar livro:', error)
        if (error.response?.status === 404) {
          router.push({ name: 'books.index' })
        }
      } finally {
        loading.value = false
      }
    }

    const deleteBook = async () => {
      if (!confirm('Tem certeza que deseja excluir este livro?')) {
        return
      }

      try {
        const response = await axios.delete(`/books/${book.value.id}`)
        if (response.data.success) {
          // Emitir evento global para mostrar mensagem de sucesso
          window.dispatchEvent(new CustomEvent('show-message', {
            detail: { type: 'success', message: 'Livro excluído com sucesso!' }
          }))
          router.push({ name: 'books.index' })
        }
      } catch (error) {
        console.error('Erro ao excluir livro:', error)
        window.dispatchEvent(new CustomEvent('show-message', {
          detail: { 
            type: 'error', 
            message: error.response?.data?.message || 'Erro ao excluir livro.' 
          }
        }))
      }
    }

    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString('pt-BR')
    }

    const formatDescription = (description) => {
      return description.replace(/\n/g, '<br>')
    }

    onMounted(() => {
      loadBook()
    })

    return {
      book,
      loading,
      canManage,
      deleteBook,
      formatDate,
      formatDescription,
      getImageUrl
    }
  }
}
</script>
