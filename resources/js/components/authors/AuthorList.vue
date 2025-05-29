<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>
        <i class="bi bi-person"></i> Lista de Autores
      </h1>
      <router-link 
        v-if="canManage"
        :to="{ name: 'authors.create' }" 
        class="btn btn-custom-primary"
      >
        <i class="bi bi-plus-circle"></i> Novo Autor
      </router-link>
    </div>

    <div v-if="authors.length > 0" class="card card-custom">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Estado</th>
                <th>Livros</th>
                <th width="200">Ações</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="author in authors" :key="author.id">
                <td>{{ author.nome }}</td>
                <td>
                  <span v-if="author.estado" class="badge bg-success">Ativo</span>
                  <span v-else class="badge bg-secondary">Inativo</span>
                </td>
                <td>
                  <span class="badge bg-info">{{ author.books_count || 0 }}</span>
                </td>
                <td>
                  <div class="btn-action-group">
                    <router-link 
                      :to="{ name: 'authors.show', params: { id: author.id } }" 
                      class="btn btn-action-vertical btn-view" 
                      title="Ver detalhes"
                    >
                      <i class="bi bi-eye"></i>
                    </router-link>
                    <router-link 
                      v-if="canManage"
                      :to="{ name: 'authors.edit', params: { id: author.id } }" 
                      class="btn btn-action-vertical btn-edit" 
                      title="Editar autor"
                    >
                      <i class="bi bi-pencil"></i>
                    </router-link>
                    <button 
                      v-if="canManage"
                      @click="deleteAuthor(author)" 
                      class="btn btn-action-vertical btn-delete" 
                      title="Excluir autor"
                    >
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Estado vazio -->
    <div v-else-if="!loading" class="text-center py-5">
      <i class="bi bi-person display-1 text-muted"></i>
      <h3 class="text-muted mt-3">Nenhum autor encontrado</h3>
      <p class="text-muted">Comece adicionando seu primeiro autor!</p>
      <router-link 
        v-if="canManage"
        :to="{ name: 'authors.create' }" 
        class="btn btn-custom-primary"
      >
        <i class="bi bi-plus-circle"></i> Adicionar Autor
      </router-link>
    </div>

    <!-- Loading state -->
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Carregando...</span>
      </div>
    </div>

    <!-- Paginação -->
    <div v-if="pagination && pagination.last_page > 1" class="pagination-wrapper">
      <nav aria-label="Navegação de páginas">
        <ul class="pagination justify-content-center">
          <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
            <button 
              class="page-link" 
              @click="changePage(pagination.current_page - 1)"
              :disabled="pagination.current_page === 1"
            >
              Anterior
            </button>
          </li>
          
          <li 
            v-for="page in visiblePages" 
            :key="page" 
            class="page-item" 
            :class="{ active: page === pagination.current_page }"
          >
            <button 
              class="page-link" 
              @click="changePage(page)"
            >
              {{ page }}
            </button>
          </li>
          
          <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
            <button 
              class="page-link" 
              @click="changePage(pagination.current_page + 1)"
              :disabled="pagination.current_page === pagination.last_page"
            >
              Próxima
            </button>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { computed, onMounted, ref } from 'vue'

export default {
  name: 'AuthorList',
  setup() {
    const authors = ref([])
    const loading = ref(true)
    const pagination = ref(null)

    const canManage = computed(() => {
      const user = JSON.parse(localStorage.getItem('user') || '{}')
      return user.role === 'admin'
    })

    const visiblePages = computed(() => {
      if (!pagination.value) return []
      
      const current = pagination.value.current_page
      const last = pagination.value.last_page
      const pages = []
      
      // Mostrar 5 páginas ao redor da atual
      const start = Math.max(1, current - 2)
      const end = Math.min(last, current + 2)
      
      for (let i = start; i <= end; i++) {
        pages.push(i)
      }
      
      return pages
    })

    const loadAuthors = async (page = 1) => {
      try {
        loading.value = true
        const response = await axios.get(`/authors?page=${page}`)
        if (response.data.success) {
          const data = response.data.data
          if (data.data) {
            // Dados paginados
            authors.value = data.data
            pagination.value = {
              current_page: data.current_page,
              last_page: data.last_page,
              per_page: data.per_page,
              total: data.total
            }
          } else {
            // Dados não paginados
            authors.value = data
            pagination.value = null
          }
        }
      } catch (error) {
        console.error('Erro ao carregar autores:', error)
        window.dispatchEvent(new CustomEvent('show-message', {
          detail: { 
            type: 'error', 
            message: 'Erro ao carregar lista de autores.' 
          }
        }))
      } finally {
        loading.value = false
      }
    }

    const changePage = (page) => {
      if (page >= 1 && page <= pagination.value.last_page) {
        loadAuthors(page)
      }
    }

    const deleteAuthor = async (author) => {
      const message = `Tem certeza que deseja excluir este autor? Esta ação não pode ser desfeita. ATENÇÃO: Não será possível excluir se houver livros associados.`
      
      if (!confirm(message)) {
        return
      }

      try {
        const response = await axios.delete(`/authors/${author.id}`)
        if (response.data.success) {
          window.dispatchEvent(new CustomEvent('show-message', {
            detail: { type: 'success', message: 'Autor excluído com sucesso!' }
          }))
          loadAuthors(pagination.value?.current_page || 1)
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

    onMounted(() => {
      loadAuthors()
    })

    return {
      authors,
      loading,
      pagination,
      canManage,
      visiblePages,
      changePage,
      deleteAuthor
    }
  }
}
</script>
