<template>
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card card-custom">
        <div class="card-header">
          <h4 class="mb-0">
            <i class="bi bi-plus-circle"></i> Criar Novo Autor
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
                :to="{ name: 'authors.index' }" 
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
                {{ submitting ? 'Salvando...' : 'Salvar Autor' }}
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
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'AuthorCreate',
  setup() {
    const router = useRouter()
    const submitting = ref(false)
    const errors = ref({})

    const form = reactive({
      nome: '',
      estado: true
    })

    const submitForm = async () => {
      try {
        submitting.value = true
        errors.value = {}

        const response = await axios.post('/authors', {
          nome: form.nome,
          estado: form.estado
        })

        if (response.data.success) {
          window.dispatchEvent(new CustomEvent('show-message', {
            detail: { type: 'success', message: 'Autor criado com sucesso!' }
          }))
          router.push({ name: 'authors.index' })
        }
      } catch (error) {
        console.error('Erro ao criar autor:', error)
        
        if (error.response?.status === 422) {
          errors.value = error.response.data.errors || {}
        } else {
          window.dispatchEvent(new CustomEvent('show-message', {
            detail: { 
              type: 'error', 
              message: error.response?.data?.message || 'Erro ao criar autor.' 
            }
          }))
        }
      } finally {
        submitting.value = false
      }
    }

    return {
      form,
      errors,
      submitting,
      submitForm
    }
  }
}
</script>
