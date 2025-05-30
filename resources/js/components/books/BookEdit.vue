<template>
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card card-custom" v-if="book">
        <div class="card-header">
          <h4 class="mb-0">
            <i class="bi bi-pencil"></i> Editar Livro
          </h4>
        </div>
        <div class="card-body">
          <form @submit.prevent="submitForm" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="titulo" class="form-label">Título *</label>
              <input 
                type="text" 
                class="form-control" 
                :class="{ 'is-invalid': errors.titulo }"
                id="titulo" 
                v-model="form.titulo" 
                required
              >
              <div v-if="errors.titulo" class="invalid-feedback">
                {{ errors.titulo[0] }}
              </div>
            </div>

            <div class="mb-3">
              <label for="author_id" class="form-label">Autor *</label>
              <select 
                class="form-select" 
                :class="{ 'is-invalid': errors.author_id }"
                id="author_id" 
                v-model="form.author_id" 
                required
              >
                <option value="">Selecione um autor</option>
                <option 
                  v-for="author in authors" 
                  :key="author.id" 
                  :value="author.id"
                >
                  {{ author.nome }}
                </option>
              </select>
              <div v-if="errors.author_id" class="invalid-feedback">
                {{ errors.author_id[0] }}
              </div>
            </div>

            <div class="mb-3">
              <label for="data_publicacao" class="form-label">Data de Publicação *</label>
              <input 
                type="date" 
                class="form-control" 
                :class="{ 'is-invalid': errors.data_publicacao }"
                id="data_publicacao" 
                v-model="form.data_publicacao" 
                required
              >
              <div v-if="errors.data_publicacao" class="invalid-feedback">
                {{ errors.data_publicacao[0] }}
              </div>
            </div>

            <div class="mb-3">
              <label for="descricao" class="form-label">Descrição *</label>
              <textarea 
                class="form-control" 
                :class="{ 'is-invalid': errors.descricao }"
                id="descricao" 
                v-model="form.descricao" 
                rows="5" 
                required
              ></textarea>
              <div v-if="errors.descricao" class="invalid-feedback">
                {{ errors.descricao[0] }}
              </div>
            </div>

            <div class="mb-3">
              <label for="capa" class="form-label">Capa do Livro</label>
              <div class="mb-2" v-if="book.capa">
                <div class="d-flex align-items-start gap-3">
                  <img 
                    :src="getImageUrl(book.capa)" 
                    alt="Capa atual" 
                    class="img-thumbnail" 
                    style="width: 100px; height: 100px; object-fit: cover;"
                  >
                  <div>
                    <small class="text-muted d-block mb-2">
                      {{ book.capa ? 'Capa atual' : 'Capa padrão (nenhuma capa enviada)' }}
                    </small>
                    <button 
                      v-if="book.capa" 
                      type="button" 
                      class="btn btn-outline-danger btn-sm" 
                      @click="showRemoveCoverModal = true"
                    >
                      <i class="bi bi-trash"></i> Remover Capa
                    </button>
                  </div>
                </div>
              </div>
              <input 
                type="file" 
                class="form-control" 
                :class="{ 'is-invalid': errors.capa }"
                id="capa" 
                @change="handleFileUpload"
                accept="image/jpeg,image/jpg,image/png"
              >
              <div class="form-text">
                Formatos aceitos: JPG, PNG. Tamanho máximo: 2MB. A imagem será redimensionada para 200x200 pixels.
              </div>
              <div v-if="errors.capa" class="invalid-feedback">
                {{ errors.capa[0] }}
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <router-link 
                :to="{ name: 'books.show', params: { id: book.id } }" 
                class="btn btn-custom-primary"
              >
                <i class="bi bi-arrow-left"></i> Voltar
              </router-link>
              <button 
                type="submit" 
                class="btn btn-custom-primary" 
                :disabled="submitting"
              >
                <span v-if="submitting" class="spinner-border spinner-border-sm me-2" role="status"></span>
                <i v-else class="bi bi-check-circle"></i> 
                {{ submitting ? 'Atualizando...' : 'Atualizar Livro' }}
              </button>
            </div>
          </form>
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

  <!-- Modal de confirmação para remover capa -->
  <div 
    class="modal fade" 
    :class="{ show: showRemoveCoverModal }" 
    :style="{ display: showRemoveCoverModal ? 'block' : 'none' }"
    tabindex="-1"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmar Remoção</h5>
          <button 
            type="button" 
            class="btn-close" 
            @click="showRemoveCoverModal = false"
          ></button>
        </div>
        <div class="modal-body">
          Tem certeza que deseja remover a capa deste livro? Esta ação não pode ser desfeita.
        </div>
        <div class="modal-footer">
          <button 
            type="button" 
            class="btn btn-secondary" 
            @click="showRemoveCoverModal = false"
          >
            Cancelar
          </button>
          <button 
            type="button" 
            class="btn btn-danger" 
            @click="removeCover"
            :disabled="removingCover"
          >
            <span v-if="removingCover" class="spinner-border spinner-border-sm me-2" role="status"></span>
            {{ removingCover ? 'Removendo...' : 'Sim, Remover' }}
          </button>
        </div>
      </div>
    </div>
  </div>
  <div 
    v-if="showRemoveCoverModal" 
    class="modal-backdrop fade show"
    @click="showRemoveCoverModal = false"
  ></div>
</template>

<script>
import axios from 'axios'
import { onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

export default {
  name: 'BookEdit',
  setup() {
    const route = useRoute()
    const router = useRouter()
    const book = ref(null)
    const authors = ref([])
    const loading = ref(true)
    const submitting = ref(false)
    const errors = ref({})
    const showRemoveCoverModal = ref(false)
    const removingCover = ref(false)
    
    const getImageUrl = (path) => {
      if (!path) return '/images/default-book-cover.svg'
      return `/storage/${path}`
    }

    const form = reactive({
      titulo: '',
      author_id: '',
      data_publicacao: '',
      descricao: '',
      capa: null
    })

    const loadBook = async () => {
      try {
        loading.value = true
        const response = await axios.get(`/books/${route.params.id}`)
        if (response.data.success) {
          book.value = response.data.data
          
          // Preencher o formulário
          form.titulo = book.value.titulo
          form.author_id = book.value.author_id
          form.data_publicacao = book.value.data_publicacao
          form.descricao = book.value.descricao
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

    const loadAuthors = async () => {
      try {
        const response = await axios.get('/authors')
        if (response.data.success) {
          authors.value = response.data.data.data || response.data.data
        }
      } catch (error) {
        console.error('Erro ao carregar autores:', error)
      }
    }

    const handleFileUpload = (event) => {
      const file = event.target.files[0]
      form.capa = file
    }

    const submitForm = async () => {
      try {
        submitting.value = true
        errors.value = {}

        const formData = new FormData()
        formData.append('titulo', form.titulo)
        formData.append('author_id', form.author_id)
        formData.append('data_publicacao', form.data_publicacao)
        formData.append('descricao', form.descricao)
        formData.append('_method', 'PUT')
        
        if (form.capa) {
          formData.append('capa', form.capa)
        }

        const response = await axios.post(`/books/${book.value.id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        if (response.data.success) {
          window.dispatchEvent(new CustomEvent('show-message', {
            detail: { type: 'success', message: 'Livro atualizado com sucesso!' }
          }))
          router.push({ name: 'books.show', params: { id: book.value.id } })
        }
      } catch (error) {
        console.error('Erro ao atualizar livro:', error)
        
        if (error.response?.status === 422) {
          errors.value = error.response.data.errors || {}
        } else {
          window.dispatchEvent(new CustomEvent('show-message', {
            detail: { 
              type: 'error', 
              message: error.response?.data?.message || 'Erro ao atualizar livro.' 
            }
          }))
        }
      } finally {
        submitting.value = false
      }
    }

    const removeCover = async () => {
      try {
        removingCover.value = true
        
        const response = await axios.delete(`/books/${book.value.id}/capa`)
        
        if (response.data.success) {
          book.value.capa = null
          showRemoveCoverModal.value = false
          window.dispatchEvent(new CustomEvent('show-message', {
            detail: { type: 'success', message: 'Capa removida com sucesso!' }
          }))
        }
      } catch (error) {
        console.error('Erro ao remover capa:', error)
        window.dispatchEvent(new CustomEvent('show-message', {
          detail: { 
            type: 'error', 
            message: error.response?.data?.message || 'Erro ao remover capa.' 
          }
        }))
      } finally {
        removingCover.value = false
      }
    }

    onMounted(async () => {
      await Promise.all([loadBook(), loadAuthors()])
    })

    return {
      book,
      form,
      authors,
      loading,
      errors,
      submitting,
      showRemoveCoverModal,
      removingCover,
      handleFileUpload,
      submitForm,
      removeCover,
      getImageUrl
    }
  }
}
</script>
