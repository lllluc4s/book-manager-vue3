import axios from 'axios';
import 'bootstrap/dist/js/bootstrap.bundle.min.js'; // Importar Bootstrap JS
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';

// Importar componentes
import App from './components/App.vue';
import Login from './components/auth/Login.vue';
import AuthorCreate from './components/authors/AuthorCreate.vue';
import AuthorEdit from './components/authors/AuthorEdit.vue';
import AuthorList from './components/authors/AuthorList.vue';
import AuthorShow from './components/authors/AuthorShow.vue';
import BookCreate from './components/books/BookCreate.vue';
import BookEdit from './components/books/BookEdit.vue';
import BookList from './components/books/BookList.vue';
import BookShow from './components/books/BookShow.vue';
import Welcome from './components/Welcome.vue';

// Configurar axios
axios.defaults.baseURL = '/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Interceptador para incluir token CSRF
axios.interceptors.request.use(config => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        config.headers['X-CSRF-TOKEN'] = token;
    }

    // Incluir token de autenticação se existir
    const authToken = authService.getToken();
    if (authToken) {
        config.headers['Authorization'] = `Bearer ${authToken}`;
    }

    return config;
});

// Interceptador para lidar com erros de autenticação
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && (error.response.status === 401 || error.response.status === 403)) {
            // Token inválido ou expirado - fazer logout
            authService.clearAuthData();

            // Apenas redirecionar se não estiver já na página de login
            if (window.location.pathname !== '/login') {
                window.location.href = '/login';
            }
        }
        return Promise.reject(error);
    }
);

// Configurar rotas
const routes = [
    { path: '/', name: 'welcome', component: Welcome },
    { path: '/login', name: 'login', component: Login },
    { path: '/books', name: 'books.index', component: BookList, meta: { requiresAuth: true } },
    { path: '/books/create', name: 'books.create', component: BookCreate, meta: { requiresAuth: true } },
    { path: '/books/:id', name: 'books.show', component: BookShow, meta: { requiresAuth: true } },
    { path: '/books/:id/edit', name: 'books.edit', component: BookEdit, meta: { requiresAuth: true } },
    { path: '/authors', name: 'authors.index', component: AuthorList, meta: { requiresAuth: true } },
    { path: '/authors/create', name: 'authors.create', component: AuthorCreate, meta: { requiresAuth: true } },
    { path: '/authors/:id', name: 'authors.show', component: AuthorShow, meta: { requiresAuth: true } },
    { path: '/authors/:id/edit', name: 'authors.edit', component: AuthorEdit, meta: { requiresAuth: true } },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Importar serviço de autenticação
import authService from './services/auth.service';

// Guard de autenticação
router.beforeEach((to, from, next) => {
    const isAuthenticated = authService.isAuthenticated();

    if (to.meta.requiresAuth && !isAuthenticated) {
        // Redirecionar para login se tentar acessar rota protegida sem estar autenticado
        next('/login');
    } else if (to.name === 'login' && isAuthenticated) {
        // Redirecionar para a lista de livros se já estiver autenticado
        next('/books');
    } else {
        next();
    }
});

// Criar e montar a aplicação
const app = createApp(App);
app.use(router);
app.config.globalProperties.$axios = axios;
app.mount('#app');
