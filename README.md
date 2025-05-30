<<<<<<< HEAD
# 📚 Sistema de Gestão de Livros

Sistema Laravel com Vue 3 para gestão de livros e autores. Inclui SPA (Single Page Application), API REST com autenticação, upload de imagens, middleware de segurança e sistema de agendamento de tarefas.

## 🚀 Funcionalidades Principais

- ✅ **CRUD** de livros e autores com interface Vue 3 responsiva
- ✅ **Upload de capas** com redimensionamento automático (200x200px)
- ✅ **SPA** com Vue Router e gerenciamento de estados
- ✅ **API REST** com autenticação Laravel Sanctum
- ✅ **Sistema de autenticação** com controle de permissões (admin/usuário)
- ✅ **Middleware de segurança** para proteção de rotas administrativas
- ✅ **Scheduler** para limpeza automática de logs antigos
- ✅ **Relacionamentos** entre livros e autores
- ✅ **Validação** de formulários e uploads

## 🛠️ Tecnologias

- **PHP:** 8.3+
- **Framework Backend:** Laravel 12.15
- **Framework Frontend:** Vue 3.4+
- **Ferramentas Frontend:** Vue Router 4.2+, Axios 1.8+
- **UI:** Bootstrap 5.3
- **Banco de Dados:** MySQL 8.0+
- **Autenticação API:** Laravel Sanctum
- **Build Tool:** Vite 6.2+
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
cd book-manager-vue3

# Instalar dependências do PHP
composer install

# Instalar dependências do Node.js
npm install

# Configurar ambiente
=======
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
>>>>>>> main
cp .env.example .env
php artisan key:generate
```

<<<<<<< HEAD
### 3. Configurar Banco de Dados

Edite o arquivo `.env` com suas credenciais:

=======
### 5. Configure o banco de dados
Edite o arquivo `.env` com suas credenciais do MySQL:
>>>>>>> main
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
<<<<<<< HEAD
DB_DATABASE=book_manager
=======
DB_DATABASE=book_manager_vue3
>>>>>>> main
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

<<<<<<< HEAD
### 4. Inicializar o Sistema

```bash
# Executar migrações e popular dados
php artisan migrate --seed

# Criar link simbólico para storage
php artisan storage:link

# Compilar assets frontend
npm run build

# Para desenvolvimento, usar modo de desenvolvimento
npm run dev

# Em outro terminal, iniciar servidor de desenvolvimento Laravel
php artisan serve
```

Acesse: `http://localhost:8000`

## 👤 Usuários de Teste

- **Administrador:** admin@test.com / password
- **Usuário:** user@test.com / password

## 🌍 Principais Rotas

### SPA (Vue Router)

- `/` - Página inicial
- `/login` - Login do sistema
- `/books` - Listagem de livros (autenticado)
- `/books/create` - Criação de livro (autenticado, admin)
- `/books/:id` - Detalhes do livro (autenticado)
- `/books/:id/edit` - Edição de livro (autenticado, admin)
- `/authors` - Listagem de autores (autenticado)
- `/authors/create` - Criação de autor (autenticado, admin)
- `/authors/:id` - Detalhes do autor (autenticado)
- `/authors/:id/edit` - Edição de autor (autenticado, admin)

### API REST

- `POST /api/auth/register` - Registro de novo usuário
- `POST /api/auth/login` - Autenticação (retorna token)
- `POST /api/auth/logout` - Logout (invalidar token)
- `GET /api/auth/user` - Dados do usuário autenticado
- `GET /api/authors` - Listar autores
- `POST /api/authors` - Criar autor
- `GET /api/authors/{id}` - Detalhe do autor
- `PUT /api/authors/{id}` - Atualizar autor
- `DELETE /api/authors/{id}` - Excluir autor
- `GET /api/authors/{id}/books` - Livros do autor
- `GET /api/books` - Listar livros
- `POST /api/books` - Criar livro
- `GET /api/books/{id}` - Detalhe do livro
- `PUT /api/books/{id}` - Atualizar livro
- `DELETE /api/books/{id}` - Excluir livro
- `DELETE /api/books/{id}/capa` - Remover capa de livro
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

# Compilar assets para produção
npm run build

# Iniciar servidor de desenvolvimento Vue com hot reload
npm run dev
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
=======
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
>>>>>>> main
