<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-6 col-lg-5">
        <div class="card shadow border-0">
          <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="mb-0">
              <i class="bi bi-person-circle me-2"></i>
              Login
            </h4>
          </div>
          <div class="card-body p-4">
            <form @submit.prevent="handleLogin">
              <div class="mb-3">
                <label for="email" class="form-label">
                  <i class="bi bi-envelope me-1"></i>
                  E-mail
                </label>
                <input 
                  type="email" 
                  class="form-control"
                  :class="{ 'is-invalid': errors.email }"
                  id="email" 
                  v-model="form.email"
                  required 
                  autofocus
                  placeholder="Digite seu e-mail"
                >
                <div v-if="errors.email" class="invalid-feedback">{{ errors.email[0] }}</div>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">
                  <i class="bi bi-lock me-1"></i>
                  Senha
                </label>
                <input 
                  type="password" 
                  class="form-control"
                  :class="{ 'is-invalid': errors.password }"
                  id="password" 
                  v-model="form.password"
                  required
                  placeholder="Digite sua senha"
                >
                <div v-if="errors.password" class="invalid-feedback">{{ errors.password[0] }}</div>
              </div>

              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" v-model="form.remember">
                <label class="form-check-label" for="remember">
                  Lembrar-me
                </label>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary" :disabled="loading">
                  <i class="bi bi-box-arrow-in-right me-1"></i>
                  <span v-if="loading">Entrando...</span>
                  <span v-else>Entrar</span>
                </button>
              </div>
            </form>
            
            <hr class="my-4">
            
            <div class="text-center">
              <small class="text-muted">
                <strong>Usuários de teste:</strong><br>
                admin@test.com / password<br>
                user@test.com / password
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import authService from '../../services/auth.service';

export default {
  name: 'Login',
  data() {
    return {
      form: {
        email: '',
        password: '',
        remember: false
      },
      errors: {},
      loading: false
    };
  },
  methods: {
    async handleLogin() {
      this.loading = true;
      this.errors = {};
      
      try {
        // Usar o serviço de autenticação
        const result = await authService.login(this.form);
        
        if (result.success) {
          // Emitir mensagem de sucesso
          this.$emit('success', 'Login realizado com sucesso!');
          
          // Uma breve pausa para permitir que os eventos sejam processados
          await this.$nextTick();
          
          // Redirecionar para a lista de livros
          this.$router.push('/books');
        } else {
          // Mostrar mensagem de erro
          this.$emit('error', result.message);
        }
      } catch (error) {
        console.error('Erro no login:', error);
        if (error.response?.status === 422) {
          // Erros de validação
          this.errors = error.response.data.errors || {};
        } else {
          this.$emit('error', 'Erro ao tentar fazer login. Tente novamente.');
        }
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>
