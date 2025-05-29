<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1><i class="bi bi-book"></i> Lista de Livros</h1>
      <router-link v-if="canManage" to="/books/create" class="btn btn-custom-primary">
        <i class="bi bi-plus-circle"></i> Novo Livro
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
                    :to="`/books/${book.id}`"
                    class="btn btn-action btn-view" 
                    title="Ver detalhes"
                  >
                    <i class="bi bi-eye"></i>
                  </router-link>
                  <router-link 
                    v-if="canManage"
                    :to="`/books/${book.id}/edit`"
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
      <router-link v-if="canManage" to="/books/create" class="btn btn-primary">
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
export default {
  name: 'BookList',
  data() {
    return {
      books: [],
      pagination: {},
      loading: true,
      bookToDelete: null,
      user: null
    };
  },
  computed: {
    canManage() {
      return this.user?.role === 'admin';
    },
    paginationPages() {
      const pages = [];
      const current = this.pagination.current_page;
      const total = this.pagination.last_page;
      
      // Mostrar até 5 páginas
      let start = Math.max(1, current - 2);
      let end = Math.min(total, start + 4);
      
      if (end - start < 4) {
        start = Math.max(1, end - 4);
      }
      
      for (let i = start; i <= end; i++) {
        pages.push(i);
      }
      
      return pages;
    }
  },
  async mounted() {
    await this.fetchUser();
    await this.fetchBooks();
  },
  methods: {
    async fetchUser() {
      try {
        const response = await this.$axios.get('/auth/user');
        this.user = response.data;
      } catch (error) {
        console.error('Erro ao buscar usuário:', error);
      }
    },
    async fetchBooks(page = 1) {
      this.loading = true;
      try {
        const response = await this.$axios.get(`/books?page=${page}`);
        this.books = response.data.data;
        this.pagination = {
          current_page: response.data.current_page,
          last_page: response.data.last_page,
          per_page: response.data.per_page,
          total: response.data.total
        };
      } catch (error) {
        console.error('Erro ao buscar livros:', error);
        this.$emit('error', 'Erro ao carregar livros.');
      } finally {
        this.loading = false;
      }
    },
    changePage(page) {
      if (page >= 1 && page <= this.pagination.last_page) {
        this.fetchBooks(page);
      }
    },
    confirmDelete(book) {
      this.bookToDelete = book;
    },
    async deleteBook() {
      try {
        await this.$axios.delete(`/books/${this.bookToDelete.id}`);
        this.$emit('success', 'Livro excluído com sucesso!');
        this.bookToDelete = null;
        await this.fetchBooks(this.pagination.current_page);
      } catch (error) {
        console.error('Erro ao excluir livro:', error);
        this.$emit('error', 'Erro ao excluir livro.');
        this.bookToDelete = null;
      }
    },
    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString('pt-BR');
    },
    truncateText(text, length) {
      if (text.length <= length) return text;
      return text.substring(0, length) + '...';
    }
  }
};
</script>
