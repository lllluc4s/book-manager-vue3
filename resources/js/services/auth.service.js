// Serviço de autenticação para gerenciar login, logout e verificação de token
import axios from 'axios';

class AuthService {
    // Método para fazer login
    async login(credentials) {
        try {
            const response = await axios.post('/auth/login', credentials);

            if (response.data.success && response.data.data) {
                // Armazenar token e informações do usuário
                this.setToken(response.data.data.token);
                this.setUser(response.data.data.user);
                return {
                    success: true,
                    user: response.data.data.user
                };
            }

            return {
                success: false,
                message: response.data.message || 'Erro ao fazer login'
            };
        } catch (error) {
            console.error('Erro durante login:', error);
            return {
                success: false,
                message: this.getErrorMessage(error)
            };
        }
    }

    // Método para fazer logout
    async logout() {
        try {
            // Tentar fazer logout na API apenas se estiver autenticado
            if (this.isAuthenticated()) {
                await axios.post('/auth/logout');
            }
        } catch (error) {
            console.error('Erro durante logout:', error);
        } finally {
            // Sempre limpar dados locais
            this.clearAuthData();
        }
    }

    // Verificar autenticação
    isAuthenticated() {
        return !!this.getToken();
    }

    // Obter token
    getToken() {
        return localStorage.getItem('auth_token');
    }

    // Definir token
    setToken(token) {
        localStorage.setItem('auth_token', token);
    }

    // Obter usuário
    getUser() {
        const userData = localStorage.getItem('user');
        return userData ? JSON.parse(userData) : null;
    }

    // Definir usuário
    setUser(user) {
        localStorage.setItem('user', JSON.stringify(user));
    }

    // Limpar dados de autenticação
    clearAuthData() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
    }

    // Verificar se o usuário tem uma determinada função/role
    hasRole(role) {
        const user = this.getUser();
        return user && user.role === role;
    }

    // Extrair mensagem de erro legível
    getErrorMessage(error) {
        if (error.response) {
            if (error.response.status === 422 && error.response.data.errors) {
                // Erros de validação
                const firstError = Object.values(error.response.data.errors)[0];
                return Array.isArray(firstError) ? firstError[0] : firstError;
            } else if (error.response.status === 401) {
                return 'Credenciais inválidas';
            } else if (error.response.data.message) {
                return error.response.data.message;
            }
            return `Erro ${error.response.status}`;
        }
        return error.message || 'Erro desconhecido';
    }
}

export default new AuthService();
