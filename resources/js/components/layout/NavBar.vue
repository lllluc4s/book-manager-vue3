<template>
  <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
      <router-link class="navbar-brand" :to="isAuthenticated ? '/books' : '/'">
        <i class="bi bi-book"></i> Gestão de Livros
      </router-link>
      
      <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
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
            <a class="nav-link user-dropdown-toggle" href="#" @click.prevent="toggleDropdown">
              <i class="bi bi-person-circle"></i> {{ user?.name || 'Usuário' }} <i class="bi bi-caret-down-fill"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" :class="{ 'show': showDropdown }">
              <li>
                <a class="dropdown-item" href="#" @click.prevent="logout">
                  <i class="bi bi-box-arrow-right"></i> Sair
                </a>
              </li>
            </ul>
          </li>
          <li v-else-if="$route.name !== 'login'" class="nav-item">
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
      user: null,
      showDropdown: false,
      authState: !!authService.getToken() // Propriedade reativa para controle do estado de autenticação
    };
  },
  computed: {
    isAuthenticated() {
      return this.authState;
    }
  },
  async mounted() {
    if (this.isAuthenticated) {
      await this.fetchUser();
    }
    
    // Adicionar evento de clique global para fechar o dropdown
    document.addEventListener('click', this.closeDropdownOnClickOutside);
    
    // Ouvir eventos de alteração de autenticação
    window.addEventListener('auth-state-changed', this.handleAuthStateChanged);
  },
  
  beforeUnmount() {
    // Remover eventos globais ao desmontar o componente
    document.removeEventListener('click', this.closeDropdownOnClickOutside);
    window.removeEventListener('auth-state-changed', this.handleAuthStateChanged);
  },
  methods: {
    toggleDropdown(event) {
      event.stopPropagation();
      this.showDropdown = !this.showDropdown;
    },
    
    closeDropdownOnClickOutside(event) {
      const dropdown = document.querySelector('.user-dropdown-toggle');
      const dropdownMenu = document.querySelector('.dropdown-menu');
      
      // Se o clique não foi no dropdown ou dentro do menu do dropdown, feche-o
      if (dropdown && dropdownMenu && 
          !dropdown.contains(event.target) && 
          !dropdownMenu.contains(event.target)) {
        this.showDropdown = false;
      }
    },
    
    async fetchUser() {
      try {
        // Verificar primeiro se está autenticado
        if (!authService.isAuthenticated()) {
          console.log('Não há token de autenticação');
          this.authState = false;
          return;
        }

        // Atualizar estado de autenticação
        this.authState = true;

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
      try {
        // Fechar o dropdown
        this.showDropdown = false;
        
        await authService.logout();
        this.user = null;
        
        // Adicionar uma mensagem de sucesso antes de redirecionar
        window.dispatchEvent(new CustomEvent('show-message', {
          detail: { type: 'success', message: 'Logout realizado com sucesso!' }
        }));
        
        // Redirecionar para a página inicial
        this.$router.push('/');
      } catch (error) {
        console.error('Erro ao fazer logout:', error);
        window.dispatchEvent(new CustomEvent('show-message', {
          detail: { type: 'error', message: 'Erro ao fazer logout. Por favor, tente novamente.' }
        }));
      }
    },
    
    // Método para lidar com alterações no estado de autenticação
    async handleAuthStateChanged(event) {
      const { authenticated, user } = event.detail;
      
      // Atualizar o estado de autenticação
      this.authState = authenticated;
      
      if (authenticated) {
        // Se autenticado, definir o usuário ou buscar da API
        if (user) {
          this.user = user;
        } else {
          await this.fetchUser();
        }
      } else {
        // Se não autenticado, limpar usuário
        this.user = null;
      }
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

<style scoped>
.user-dropdown-toggle {
  cursor: pointer;
}

.user-dropdown-toggle .bi-caret-down-fill {
  font-size: 0.75em;
  margin-inline-start: 5px;
}

.dropdown-menu {
  position: absolute;
  inset: 0px auto auto 0px;
  transform: translate(-16px, 40px);
  display: none;
  min-inline-size: 10rem;
  padding: 0.5rem 0;
  margin: 0;
  font-size: 1rem;
  color: #212529;
  text-align: start;
  list-style: none;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 0.25rem;
}

.dropdown-menu.show {
  display: block;
  z-index: 1000;
}

.dropdown-item {
  display: block;
  inline-size: 100%;
  padding: 0.25rem 1rem;
  clear: both;
  font-weight: 400;
  color: #212529;
  text-align: inherit;
  text-decoration: none;
  white-space: nowrap;
  background-color: transparent;
  border: 0;
}

.dropdown-item:hover, .dropdown-item:focus {
  color: #1e2125;
  background-color: #e9ecef;
}

/* Estilo para o ícone do hambúrguer (garantir que seja branco) */
.custom-toggler .navbar-toggler-icon {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
}

/* Centralizar ícones no menu mobile */
@media (max-width: 991.98px) {
  .navbar-nav .nav-link i {
    display: inline-block;
    margin-right: 5px;
  }
  
  .dropdown-item i {
    margin-right: 8px;
    text-align: center;
  }
  
  .dropdown-menu {
    text-align: center;
    transform: none;
    position: static;
    float: none;
    width: auto;
    margin-top: 0;
  }
  
  .navbar-nav {
    text-align: center;
  }
  
  .dropdown-item {
    text-align: center;
  }
}
</style>
