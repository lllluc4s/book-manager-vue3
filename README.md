# 📚 Sistema de Gestão de Livros - Vue 3 + Laravel

Um sistema moderno de gestão de livros e autores desenvolvido com **Vue 3** + **Laravel API**, oferecendo uma experiência de usuário rica e responsiva.

## 🚀 Tecnologias Utilizadas

### Frontend
- **Vue 3** - Framework JavaScript reativo
- **Vue Router** - Roteamento client-side
- **Axios** - Cliente HTTP para comunicação com API
- **Bootstrap 5** - Framework CSS responsivo
- **Vite** - Build tool moderno e rápido

### Backend
- **Laravel 11** - Framework PHP robusto
- **Laravel Sanctum** - Autenticação de API
- **MySQL** - Banco de dados relacional
- **PHP 8.2+** - Linguagem de programação

## ✨ Funcionalidades

### 📖 Gestão de Livros
- ✅ Listagem com paginação
- ✅ Visualização detalhada
- ✅ Criação e edição
- ✅ Exclusão com confirmação
- ✅ Upload de capas
- ✅ Filtros e pesquisa

### 👥 Gestão de Autores
- ✅ CRUD completo
- ✅ Listagem paginada
- ✅ Visualização de livros por autor
- ✅ Validação de dados

### 🔐 Sistema de Autenticação
- ✅ Login/logout seguro
- ✅ Proteção de rotas
- ✅ Tokens JWT via Sanctum
- ✅ Middleware de autenticação

### 🎨 Interface de Usuário
- ✅ Design responsivo
- ✅ Navegação intuitiva
- ✅ Feedback visual
- ✅ Formulários validados
- ✅ Mensagens de sucesso/erro

## 🛠️ Instalação e Configuração

### Pré-requisitos
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+
- Git

### 1. Clone o repositório
```bash
git clone https://github.com/lllluc4s/book-manager-vue3.git
cd book-manager-vue3
```

### 2. Instale as dependências PHP
```bash
composer install
```

### 3. Instale as dependências Node.js
```bash
npm install
```

### 4. Configure o ambiente
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure o banco de dados
Edite o arquivo `.env` com suas credenciais do MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=book_manager_vue3
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 6. Execute as migrações e seeders
```bash
php artisan migrate --seed
```

### 7. Crie o link simbólico para storage
```bash
php artisan storage:link
```

## 🚀 Executando o Projeto

### Terminal 1 - Backend (Laravel)
```bash
php artisan serve
# Servidor disponível em: http://127.0.0.1:8000
```

### Terminal 2 - Frontend (Vite)
```bash
npm run dev
# Build assets em: http://localhost:5173
```

### Acesse a aplicação
Abra seu navegador em: **http://127.0.0.1:8000**

## 👤 Credenciais de Teste

```
Admin: admin@test.com / password
Usuário: user@test.com / password
```

## 📁 Estrutura do Projeto

```
📦 book-manager-vue3/
├── 🎨 resources/js/components/     # Componentes Vue
│   ├── layout/                    # Layout components
│   ├── auth/                      # Autenticação
│   ├── books/                     # Gestão de livros
│   └── authors/                   # Gestão de autores
├── 🔧 app/Http/Controllers/Api/   # Controllers da API
├── 🗃️ database/                   # Migrações e seeders
├── 🛣️ routes/                     # Rotas web e API
└── 📦 public/                     # Assets públicos
```

## 🌐 API Endpoints

### Autenticação
- `POST /api/auth/login` - Login
- `POST /api/auth/logout` - Logout
- `GET /api/auth/user` - Usuário atual

### Livros
- `GET /api/books` - Listar livros
- `POST /api/books` - Criar livro
- `GET /api/books/{id}` - Visualizar livro
- `PUT /api/books/{id}` - Atualizar livro
- `DELETE /api/books/{id}` - Excluir livro

### Autores
- `GET /api/authors` - Listar autores
- `POST /api/authors` - Criar autor
- `GET /api/authors/{id}` - Visualizar autor
- `PUT /api/authors/{id}` - Atualizar autor
- `DELETE /api/authors/{id}` - Excluir autor

## 🔧 Comandos Úteis

```bash
# Build para produção
npm run build

# Executar testes
php artisan test

# Limpar cache
php artisan cache:clear
php artisan config:clear

# Reset do banco de dados
php artisan migrate:fresh --seed
```

## 📸 Screenshots

*Em breve...*

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👨‍💻 Autor

**Lucas** - [GitHub](https://github.com/lllluc4s)

---

⭐ **Se este projeto foi útil para você, considere dar uma estrela!**
