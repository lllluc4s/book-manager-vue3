# 📚 Sistema de Gestão de Livros

Sistema Laravel para gestão de livros e autores. Inclui interface web, API REST com autenticação, upload de imagens, middleware de segurança e sistema de agendamento de tarefas.

## 🚀 Funcionalidades Principais

- ✅ **CRUD** de livros e autores com interface Blade responsiva
- ✅ **Upload de capas** com redimensionamento automático (200x200px)
- ✅ **API REST** com autenticação Laravel Sanctum
- ✅ **Sistema de autenticação** com controle de permissões (admin/usuário)
- ✅ **Middleware de segurança** para proteção de rotas administrativas
- ✅ **Scheduler** para limpeza automática de logs antigos
- ✅ **Relacionamentos** entre livros e autores
- ✅ **Validação** de formulários e uploads

## 🛠️ Tecnologias

- **PHP:** 8.3+
- **Framework:** Laravel 12.15
- **Frontend:** Blade Templates + Bootstrap 5
- **Banco de Dados:** MySQL 8.0+
- **Autenticação API:** Laravel Sanctum
- **Processamento de Imagens:** Intervention Image 3.11
- **Testes:** Pest 3.8

## 📦 Instalação e Configuração

### 1. Pré-requisitos

- PHP >= 8.3
- MySQL >= 8.0
- Composer
- Node.js (opcional, para assets)

### 2. Instalação

```bash
# Clonar o repositório
git clone <repository-url>
cd book-manager

# Instalar dependências
composer install

# Configurar ambiente
cp .env.example .env
php artisan key:generate
```

### 3. Configurar Banco de Dados

Edite o arquivo `.env` com suas credenciais:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=book_manager
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 4. Inicializar o Sistema

```bash
# Executar migrações e popular dados
php artisan migrate --seed

# Criar link simbólico para storage
php artisan storage:link

# Iniciar servidor de desenvolvimento
php artisan serve
```

Acesse: `http://localhost:8000`

## 👤 Usuários de Teste

- **Administrador:** admin@test.com / password
- **Usuário:** user@test.com / password

## 🌍 Principais Rotas

### Sistema Web

- `/` - Página inicial
- `/login` - Login do sistema
- `/books` - Gestão de livros (requer admin)
- `/authors` - Gestão de autores (requer admin)

### API REST

- `POST /api/auth/login` - Autenticação
- `GET /api/authors` - Listar autores
- `GET /api/authors/{id}/books` - Livros do autor
- **Headers:** `Authorization: Bearer {token}`

## 🗄️ Estrutura do Banco

```sql
-- Autores
authors: id, nome, estado, timestamps

-- Livros
books: id, titulo, descricao, data_publicacao, author_id, capa, timestamps

-- Usuários
users: id, name, email, password, role, timestamps

-- Logs (para scheduler)
logs: id, level, message, context, channel, timestamps
```

## ⚙️ Comandos Úteis

```bash
# Executar testes
./vendor/bin/pest

# Limpar logs manualmente
php artisan logs:clean-old

# Verificar scheduler
php artisan schedule:list

# Rodar migrações fresh (cuidado: apaga dados!)
php artisan migrate:fresh --seed
```

## 🧪 Testes de Verificação

O sistema inclui testes automatizados para validar funcionalidades:

```bash
# Executar todos os testes
./vendor/bin/pest

# Testes específicos
./vendor/bin/pest tests/Feature/SystemHealthTest.php
```

## 📄 Documentação Adicional

- **[API Documentation](API_DOCUMENTATION.md)** - Referência completa da API
- **[Web Documentation](WEB_DOCUMENTATION.md)** - Guia do sistema web

---

**Desenvolvido por Lucas Rodrigues**
