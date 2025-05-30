<template>
  <div id="app">
    <NavBar v-if="$route.name !== 'welcome'" />
    <div :class="$route.name !== 'welcome' ? 'container mb-5 pt-4 content-wrapper' : ''">
      <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ successMessage }}
        <button type="button" class="btn-close" @click="clearSuccessMessage"></button>
      </div>
      
      <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px; box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2); border-left: 4px solid #dc3545;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ errorMessage }}
        <button type="button" class="btn-close" @click="clearErrorMessage"></button>
      </div>
      
      <router-view @success="showSuccessMessage" @error="showErrorMessage" />
    </div>
  </div>
</template>

<script>
import NavBar from './layout/NavBar.vue';

export default {
  name: 'App',
  components: {
    NavBar
  },
  data() {
    return {
      successMessage: '',
      errorMessage: '',
      authListenerSetup: false
    };
  },
  created() {
    // Configurar ouvinte para eventos de autenticação
    window.addEventListener('auth-state-changed', this.handleAuthStateChanged);
    
    // Configurar ouvinte para eventos de mensagens
    window.addEventListener('show-message', this.handleShowMessage);
  },
  
  beforeUnmount() {
    // Remover o ouvinte quando o componente for desmontado
    window.removeEventListener('auth-state-changed', this.handleAuthStateChanged);
    window.removeEventListener('show-message', this.handleShowMessage);
  },
  
  methods: {
    handleAuthStateChanged(event) {
      // Forçar a atualização do componente NavBar quando o estado de autenticação mudar
      this.$forceUpdate();
    },
    
    showSuccessMessage(message) {
      this.successMessage = message;
      setTimeout(() => {
        this.clearSuccessMessage();
      }, 5000);
    },
    showErrorMessage(message) {
      this.errorMessage = message;
      setTimeout(() => {
        this.clearErrorMessage();
      }, 5000);
    },
    clearSuccessMessage() {
      this.successMessage = '';
    },
    clearErrorMessage() {
      this.errorMessage = '';
    },
    
    handleShowMessage(event) {
      const { type, message } = event.detail;
      if (type === 'success') {
        this.showSuccessMessage(message);
      } else if (type === 'error') {
        this.showErrorMessage(message);
      }
    }
  }
};
</script>

<style>
/* Estilo personalizado para o alerta de erro */
.alert-danger {
  border-radius: 8px !important;
  box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2) !important;
  border-left: 4px solid #dc3545 !important;
  background-color: #f8d7da !important;
  color: #721c24 !important;
  font-weight: 500 !important;
}
</style>
