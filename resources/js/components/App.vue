<template>
  <div id="app">
    <NavBar v-if="$route.name !== 'welcome'" />
    <div :class="$route.name !== 'welcome' ? 'container mt-4 mb-5' : ''">
      <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ successMessage }}
        <button type="button" class="btn-close" @click="clearSuccessMessage"></button>
      </div>
      
      <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ errorMessage }}
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
      errorMessage: ''
    };
  },
  methods: {
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
    }
  }
};
</script>
