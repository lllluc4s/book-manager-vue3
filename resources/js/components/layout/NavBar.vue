<template>
  <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
      <router-link class="navbar-brand" :to="isAuthenticated ? '/books' : '/'">
        <i class="bi bi-book"></i> Gestão de Livros
      </router-link>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li v-if="isAuthenticated" class="nav-item">
            <router-link class="nav-link" :class="$route.name?.startsWith('books') ? 'active' : ''" to="/books">
              <i class="bi bi-book"></i> Livros
            </router-link>
          </li>
          <li v-if="isAuthenticated" class="nav-item">
            <router-link class="nav-link" :class="$route.name?.startsWith('authors') ? 'active' : ''" to="/authors">
              <i class="bi bi-person"></i> Autores
            </router-link>
          </li>
        </ul>
        
        <!-- Menu do usuário -->
        <ul class="navbar-nav ms-auto">
          <li v-if="isAuthenticated" class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle"></i> {{ user?.name }}
            </a>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="#" @click="logout">
                  <i class="bi bi-box-arrow-right"></i> Sair
                </a>
              </li>
            </ul>
          </li>
          <li v-else class="nav-item">
            <router-link class="nav-link" to="/login">
              <i class="bi bi-box-arrow-in-right"></i> Login
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import authService from '../../services/auth.service';

export default {
  name: 'NavBar',
  data() {
    return {
      user: null
    };
  },
  computed: {
    isAuthenticated() {
      return authService.isAuthenticated();
    }
  },
  async mounted() {
    if (this.isAuthenticated) {
      await this.fetchUser();
    }
  },
  methods: {
    async fetchUser() {
      try {
        // Verificar primeiro se está autenticado
        if (!this.isAuthenticated) {
          console.log('Não há token de autenticação');
          return;
        }

        // Obter usuário do localStorage para exibição imediata
        const storedUser = authService.getUser();
        if (storedUser) {
          this.user = storedUser;
        }
        
        // Validar com a API
        const response = await this.$axios.get('/auth/user');
        if (response.data.success) {
          this.user = response.data.data;
          // Atualizar no serviço
          authService.setUser(this.user);
        } else {
          throw new Error('Resposta inválida da API');
        }
      } catch (error) {
        console.error('Erro ao buscar usuário:', error);
        // Se houver erro de autenticação, fazer logout
        if (error.response && (error.response.status === 401 || error.response.status === 403)) {
          await this.logout();
        }
      }
    },
    async logout() {
      await authService.logout();
      this.user = null;
      this.$router.push('/');
    }
  },
  watch: {
    '$route'() {
      // Recarregar usuário quando mudar de rota
      if (this.isAuthenticated && !this.user) {
        this.fetchUser();
      }
    }
  }
};
</script>
