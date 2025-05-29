<template>
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card card-custom" v-if="author">
        <div class="card-header">
          <h4 class="mb-0">
            <i class="bi bi-pencil"></i> Editar Autor
          </h4>
        </div>
        <div class="card-body">
          <form @submit.prevent="submitForm">
            <div class="mb-3">
              <label for="nome" class="form-label">Nome *</label>
              <input 
                type="text" 
                class="form-control" 
                :class="{ 'is-invalid': errors.nome }"
                id="nome" 
                v-model="form.nome" 
                required
              >
              <div v-if="errors.nome" class="invalid-feedback">
                {{ errors.nome[0] }}
              </div>
            </div>

            <div class="mb-3">
              <div class="form-check">
                <input type="hidden" name="estado" value="0">
                <input 
                  class="form-check-input" 
                  type="checkbox" 
                  id="estado" 
                  v-model="form.estado"
                  :true-value="true"
                  :false-value="false"
                >
                <label class="form-check-label" for="estado">
                  Autor ativo
                </label>
              </div>
              <div v-if="errors.estado" class="text-danger small">
                {{ errors.estado[0] }}
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <router-link 
                :to="{ name: 'authors.show', params: { id: author.id } }" 
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
                {{ submitting ? 'Atualizando...' : 'Atualizar Autor' }}
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
        Autor não encontrado.
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

export default {
  name: 'AuthorEdit',
  setup() {
    const route = useRoute()
    const router = useRouter()
    const author = ref(null)
    const loading = ref(true)
    const submitting = ref(false)
    const errors = ref({})

    const form = reactive({
      nome: '',
      estado: true
    })

    const loadAuthor = async () => {
      try {
        loading.value = true
        const response = await axios.get(`/authors/${route.params.id}`)
        if (response.data.success) {
          author.value = response.data.data
          
          // Preencher o formulário
          form.nome = author.value.nome
          form.estado = author.value.estado
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

    const submitForm = async () => {
      try {
        submitting.value = true
        errors.value = {}

        const response = await axios.put(`/authors/${author.value.id}`, {
          nome: form.nome,
          estado: form.estado
        })

        if (response.data.success) {
          window.dispatchEvent(new CustomEvent('show-message', {
            detail: { type: 'success', message: 'Autor atualizado com sucesso!' }
          }))
          router.push({ name: 'authors.show', params: { id: author.value.id } })
        }
      } catch (error) {
        console.error('Erro ao atualizar autor:', error)
        
        if (error.response?.status === 422) {
          errors.value = error.response.data.errors || {}
        } else {
          window.dispatchEvent(new CustomEvent('show-message', {
            detail: { 
              type: 'error', 
              message: error.response?.data?.message || 'Erro ao atualizar autor.' 
            }
          }))
        }
      } finally {
        submitting.value = false
      }
    }

    onMounted(() => {
      loadAuthor()
    })

    return {
      author,
      form,
      loading,
      errors,
      submitting,
      submitForm
    }
  }
}
</script>
