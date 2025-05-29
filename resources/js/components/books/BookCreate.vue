<template>
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card card-custom">
        <div class="card-header">
          <h4 class="mb-0">
            <i class="bi bi-plus-circle"></i> Criar Novo Livro
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
                :to="{ name: 'books.index' }" 
                class="btn btn-custom-outline"
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
                {{ submitting ? 'Salvando...' : 'Salvar Livro' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { onMounted, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'BookCreate',
  setup() {
    const router = useRouter()
    const authors = ref([])
    const submitting = ref(false)
    const errors = ref({})

    const form = reactive({
      titulo: '',
      author_id: '',
      data_publicacao: '',
      descricao: '',
      capa: null
    })

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
        
        if (form.capa) {
          formData.append('capa', form.capa)
        }

        const response = await axios.post('/books', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        if (response.data.success) {
          window.dispatchEvent(new CustomEvent('show-message', {
            detail: { type: 'success', message: 'Livro criado com sucesso!' }
          }))
          router.push({ name: 'books.index' })
        }
      } catch (error) {
        console.error('Erro ao criar livro:', error)
        
        if (error.response?.status === 422) {
          errors.value = error.response.data.errors || {}
        } else {
          window.dispatchEvent(new CustomEvent('show-message', {
            detail: { 
              type: 'error', 
              message: error.response?.data?.message || 'Erro ao criar livro.' 
            }
          }))
        }
      } finally {
        submitting.value = false
      }
    }

    onMounted(() => {
      loadAuthors()
    })

    return {
      form,
      authors,
      errors,
      submitting,
      handleFileUpload,
      submitForm
    }
  }
}
</script>
