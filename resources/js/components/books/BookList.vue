<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1><i class="bi bi-book"></i> Lista de Livros</h1>
      <router-link 
        v-if="canManage" 
        :to="{ name: 'books.create' }" 
        class="btn btn-custom-primary btn-lg"
      >
        <i class="bi bi-plus-circle"></i> Adicionar Livro
      </router-link>
    </div>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Carregando...</span>
      </div>
    </div>

    <div v-else-if="books.length > 0">
      <div class="row row-uniform-height">
        <div v-for="book in books" :key="book.id" class="col-md-4 mb-4">
          <div class="card h-100 book-card card-uniform-height">
            <div class="text-center pt-3">
              <img 
                :src="book.capa ? `/storage/${book.capa}` : '/images/default-book-cover.svg'"
                :alt="`Capa de ${book.titulo}`"
                class="rounded"
                style="width: 120px; height: 120px; object-fit: cover;"
              >
            </div>
            <div class="card-body d-flex flex-column">
              <div class="card-content flex-grow-1">
                <h5 class="card-title">{{ book.titulo }}</h5>
                <p class="card-text text-muted mb-2">
                  <i class="bi bi-person"></i> {{ book.author?.nome }}
                </p>
                <p class="card-text text-muted mb-2">
                  <i class="bi bi-calendar"></i> {{ formatDate(book.data_publicacao) }}
                </p>
                <p class="card-text">{{ truncateText(book.descricao, 100) }}</p>
              </div>
              <div class="book-actions-centered mt-3">
                <div class="btn-action-horizontal d-flex justify-content-center gap-2">
                  <router-link 
                    :to="{ name: 'books.show', params: { id: book.id } }"
                    class="btn btn-action btn-view" 
                    title="Ver detalhes"
                  >
                    <i class="bi bi-eye"></i>
                  </router-link>
                  <router-link 
                    v-if="canManage"
                    :to="{ name: 'books.edit', params: { id: book.id } }"
                    class="btn btn-action btn-edit" 
                    title="Editar livro"
                  >
                    <i class="bi bi-pencil"></i>
                  </router-link>
                  <button 
                    v-if="canManage"
                    @click="confirmDelete(book)"
                    class="btn btn-action btn-delete" 
                    title="Excluir livro"
                  >
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Paginação -->
      <div class="pagination-wrapper" v-if="pagination.total > pagination.per_page">
        <nav>
          <ul class="pagination">
            <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
              <button class="page-link" @click="changePage(pagination.current_page - 1)">Anterior</button>
            </li>
            
            <li 
              v-for="page in paginationPages" 
              :key="page"
              class="page-item" 
              :class="{ active: page === pagination.current_page }"
            >
              <button class="page-link" @click="changePage(page)">{{ page }}</button>
            </li>
            
            <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
              <button class="page-link" @click="changePage(pagination.current_page + 1)">Próximo</button>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <div v-else class="text-center py-5">
      <i class="bi bi-book display-1 text-muted"></i>
      <h3 class="text-muted mt-3">Nenhum livro encontrado</h3>
      <p class="text-muted">Comece adicionando seu primeiro livro!</p>
      <router-link 
        v-if="canManage" 
        :to="{ name: 'books.create' }" 
        class="btn btn-custom-primary btn-lg"
      >
        <i class="bi bi-plus-circle"></i> Adicionar Livro
      </router-link>
    </div>

    <!-- Modal de confirmação de exclusão -->
    <div v-if="bookToDelete" class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5)">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirmar Exclusão</h5>
            <button type="button" class="btn-close" @click="bookToDelete = null"></button>
          </div>
          <div class="modal-body">
            Tem certeza que deseja excluir o livro "<strong>{{ bookToDelete.titulo }}</strong>"?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="bookToDelete = null">Cancelar</button>
            <button type="button" class="btn btn-danger" @click="deleteBook">Sim, Excluir</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import authService from '../../services/auth.service';

export default {
  name: 'BookList',
  setup(props, { emit }) {
    const books = ref([])
    const pagination = ref({})
    const loading = ref(true)
    const bookToDelete = ref(null)
    
    const canManage = computed(() => {
      const user = authService.getUser()
      return user?.role === 'admin'
    })
    
    const paginationPages = computed(() => {
      if (!pagination.value.current_page) return []
      
      const current = pagination.value.current_page
      const total = pagination.value.last_page
      
      // Mostrar até 5 páginas
      let start = Math.max(1, current - 2)
      let end = Math.min(total, start + 4)
      
      if (end - start < 4) {
        start = Math.max(1, end - 4)
      }
      
      const pages = []
      for (let i = start; i <= end; i++) {
        pages.push(i)
      }
      
      return pages
    })
    
    const fetchBooks = async (page = 1) => {
      loading.value = true
      try {
        const response = await axios.get(`/books?page=${page}`)
        books.value = response.data.data
        pagination.value = {
          current_page: response.data.current_page,
          last_page: response.data.last_page,
          per_page: response.data.per_page,
          total: response.data.total
        }
      } catch (error) {
        console.error('Erro ao buscar livros:', error)
        window.dispatchEvent(new CustomEvent('show-message', {
          detail: { type: 'error', message: 'Erro ao carregar livros.' }
        }))
      } finally {
        loading.value = false
      }
    }
    
    const changePage = (page) => {
      if (page >= 1 && page <= pagination.value.last_page) {
        fetchBooks(page)
      }
    }
    
    const confirmDelete = (book) => {
      bookToDelete.value = book
    }
    
    const deleteBook = async () => {
      try {
        await axios.delete(`/books/${bookToDelete.value.id}`)
        window.dispatchEvent(new CustomEvent('show-message', {
          detail: { type: 'success', message: 'Livro excluído com sucesso!' }
        }))
        bookToDelete.value = null
        await fetchBooks(pagination.value.current_page)
      } catch (error) {
        console.error('Erro ao excluir livro:', error)
        window.dispatchEvent(new CustomEvent('show-message', {
          detail: { type: 'error', message: 'Erro ao excluir livro.' }
        }))
        bookToDelete.value = null
      }
    }
    
    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString('pt-BR')
    }
    
    const truncateText = (text, length) => {
      if (!text) return ''
      if (text.length <= length) return text
      return text.substring(0, length) + '...'
    }
    
    onMounted(() => {
      fetchBooks()
    })
    
    return {
      books,
      pagination,
      loading,
      bookToDelete,
      canManage,
      paginationPages,
      changePage,
      confirmDelete,
      deleteBook,
      formatDate,
      truncateText
    }
  }
};
</script>
