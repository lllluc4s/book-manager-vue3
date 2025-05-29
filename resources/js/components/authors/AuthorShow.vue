<template>
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card" v-if="author">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
              <i class="bi bi-person"></i> {{ author.nome }}
              <span v-if="author.estado" class="badge bg-success ms-2">Ativo</span>
              <span v-else class="badge bg-secondary ms-2">Inativo</span>
            </h4>
            <div class="btn-action-group" v-if="canManage">
              <router-link 
                :to="{ name: 'authors.edit', params: { id: author.id } }" 
                class="btn btn-action-vertical btn-edit" 
                title="Editar autor"
              >
                <i class="bi bi-pencil"></i>
              </router-link>
              <button 
                @click="deleteAuthor" 
                class="btn btn-action-vertical btn-delete" 
                title="Excluir autor"
              >
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <h5>Livros do Autor ({{ author.books ? author.books.length : 0 }})</h5>
          
          <div v-if="author.books && author.books.length > 0" class="row row-uniform-height">
            <div 
              v-for="book in author.books" 
              :key="book.id" 
              class="col-md-6 col-lg-4 mb-3"
            >
              <div class="card card-uniform-height">
                <div class="card-body">
                  <h6 class="card-title">{{ book.titulo }}</h6>
                  <p class="card-text text-muted small">
                    <i class="bi bi-calendar"></i> {{ formatDate(book.data_publicacao) }}
                  </p>
                  <p class="card-text">{{ truncateText(book.descricao, 80) }}</p>
                  <div class="btn-action-group">
                    <router-link 
                      :to="{ name: 'books.show', params: { id: book.id } }" 
                      class="btn btn-action-vertical btn-view" 
                      title="Ver livro"
                    >
                      <i class="bi bi-eye"></i>
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-else class="text-center py-4">
            <i class="bi bi-book text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-2">Este autor ainda não possui livros cadastrados.</p>
            <router-link 
              v-if="canManage"
              :to="{ name: 'books.create' }" 
              class="btn btn-primary"
            >
              <i class="bi bi-plus-circle"></i> Adicionar Livro
            </router-link>
          </div>

          <div class="mt-4">
            <router-link 
              :to="{ name: 'authors.index' }" 
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
        Autor não encontrado.
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

export default {
  name: 'AuthorShow',
  setup() {
    const route = useRoute()
    const router = useRouter()
    const author = ref(null)
    const loading = ref(true)

    const canManage = computed(() => {
      const user = JSON.parse(localStorage.getItem('user') || '{}')
      return user.role === 'admin'
    })

    const loadAuthor = async () => {
      try {
        loading.value = true
        const response = await axios.get(`/authors/${route.params.id}`)
        if (response.data.success) {
          author.value = response.data.data
        }
      } catch (error) {
        console.error('Erro ao carregar autor:', error)
        if (error.response?.status === 404) {
          router.push({ name: 'authors.index' })
        }
      } finally {
        loading.value = false
      }
    }

    const deleteAuthor = async () => {
      const message = 'Tem certeza que deseja excluir este autor?'
      
      if (!confirm(message)) {
        return
      }

      try {
        const response = await axios.delete(`/authors/${author.value.id}`)
        if (response.data.success) {
          window.dispatchEvent(new CustomEvent('show-message', {
            detail: { type: 'success', message: 'Autor excluído com sucesso!' }
          }))
          router.push({ name: 'authors.index' })
        }
      } catch (error) {
        console.error('Erro ao excluir autor:', error)
        let message = 'Erro ao excluir autor.'
        
        if (error.response?.status === 400) {
          message = error.response.data.message || 'Não é possível excluir este autor pois ele possui livros associados.'
        }
        
        window.dispatchEvent(new CustomEvent('show-message', {
          detail: { type: 'error', message }
        }))
      }
    }

    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString('pt-BR')
    }

    const truncateText = (text, maxLength) => {
      if (text.length <= maxLength) return text
      return text.substring(0, maxLength) + '...'
    }

    onMounted(() => {
      loadAuthor()
    })

    return {
      author,
      loading,
      canManage,
      deleteAuthor,
      formatDate,
      truncateText
    }
  }
}
</script>
