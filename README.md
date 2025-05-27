# 📚 Sistema de Gestão de Livros

Sistema Laravel completo para gestão de livros e autores com interface web moderna e API REST.

## 🚀 Funcionalidades

### 🌐 Sistema Web

- ✅ Interface responsiva com Bootstrap 5
- ✅ CRUD completo para Livros e Autores
- ✅ Sistema de autenticação integrado
- ✅ Área pública (catálogo) sem login
- ✅ Área administrativa protegida
- ✅ Relacionamentos entre entidades

### 🔌 API REST

- ✅ Autenticação com Laravel Sanctum
- ✅ Endpoints para gestão de autores
- ✅ Proteção por tokens de acesso
- ✅ Documentação da API incluída

## 🛠️ Tecnologias

- **Framework:** Laravel 10
- **Frontend:** Blade + Bootstrap 5
- **Banco:** MySQL/MariaDB
- **API:** Laravel Sanctum
- **Autenticação:** Laravel Auth

## 📦 Instalação e Configuração

### 1. Clonar e Instalar Dependências

```bash
git clone <repository>
cd book-manager
composer install
```

### 2. Configurar Ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configurar Banco de Dados

Edite o arquivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=book_manager
DB_USERNAME=laravel
DB_PASSWORD=laravel123
```

### 4. Executar Migrações e Seeders

```bash
php artisan migrate
php artisan db:seed
```

### 5. Iniciar Servidor

```bash
php artisan serve
```

Acesse: `http://127.0.0.1:8000`

## 👤 Usuários de Teste

- **Admin:** admin@test.com / password
- **User:** user@test.com / password

## 📖 Documentação

- **[Documentação Web](WEB_DOCUMENTATION.md)** - Guia completo do sistema web
- **[Documentação API](API_DOCUMENTATION.md)** - Referência da API REST

## 🌍 URLs Principais

### Sistema Web

| URL               | Descrição                 |
| ----------------- | ------------------------- |
| `/`               | Página inicial            |
| `/login`          | Login do sistema          |
| `/books/public`   | Catálogo público          |
| `/authors/public` | Autores (público)         |
| `/books`          | Gestão de livros (admin)  |
| `/authors`        | Gestão de autores (admin) |

### API REST

| Endpoint                      | Descrição           |
| ----------------------------- | ------------------- |
| `POST /api/auth/register`     | Registrar usuário   |
| `POST /api/auth/login`        | Login e obter token |
| `GET /api/authors`            | Listar autores      |
| `POST /api/authors`           | Criar autor         |
| `GET /api/authors/{id}/books` | Livros do autor     |

## 📊 Estrutura do Banco

```sql
-- Autores
authors: id, nome, estado, timestamps

-- Livros
books: id, titulo, descricao, data_publicacao, author_id, timestamps

-- Usuários
users: id, name, email, password, timestamps
```

## 🎯 Como Usar

### 1. Acesso Público

- Visite `/books/public` para ver o catálogo
- Navegue por `/authors/public` para conhecer os autores

### 2. Acesso Administrativo

- Faça login em `/login`
- Gerencie livros em `/books`
- Gerencie autores em `/authors`

### 3. API

- Obtenha token em `/api/auth/login`
- Use o token no header: `Authorization: Bearer {token}`
- Acesse endpoints protegidos
