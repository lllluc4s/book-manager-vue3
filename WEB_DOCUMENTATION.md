# 🌐 Sistema Web - Gestão de Livros

Sistema Laravel com interface SPA (Single Page Application) em Vue 3 para gestão de livros e autores.

## 🗄️ Estrutura do Banco de Dados

### Tabela Authors

```sql
id (PK), nome, estado (boolean), created_at, updated_at
```

### Tabela Books

```sql
id (PK), titulo, descricao, data_publicacao, author_id (FK), capa, created_at, updated_at
```

### Tabela Users

```sql
id (PK), name, email (unique), password, role, created_at, updated_at
```

### Tabela Logs

```sql
id (PK), level, message, context (JSON), channel, created_at, updated_at
```

## 👤 Usuários de Teste

Criados via seeders:

- **Administrador:** admin@test.com / password
- **Usuário:** user@test.com / password

## 🌍 Rotas da SPA Vue 3

O sistema utiliza Vue Router para gerenciar as rotas do frontend como um aplicativo de página única (SPA).

### Sistema de Autenticação

| Rota      | Componente Vue      | Descrição           |
| --------- | ------------------- | ------------------- |
| `/login`  | `Login.vue`         | Formulário de login |
| `/logout` | Manipulado pela SPA | Fazer logout        |

### Área de Usuário (Requer Autenticação)

#### Gestão de Livros

| Rota               | Componente Vue   | Descrição             |
| ------------------ | ---------------- | --------------------- |
| `/books`           | `BookList.vue`   | Lista todos os livros |
| `/books/create`    | `BookCreate.vue` | Formulário de criação |
| `/books/{id}`      | `BookShow.vue`   | Visualizar livro      |
| `/books/{id}/edit` | `BookEdit.vue`   | Formulário de edição  |

#### Gestão de Autores

| Rota                 | Componente Vue     | Descrição                      |
| -------------------- | ------------------ | ------------------------------ |
| `/authors`           | `AuthorList.vue`   | Lista todos os autores         |
| `/authors/create`    | `AuthorCreate.vue` | Formulário de criação          |
| `/authors/{id}`      | `AuthorShow.vue`   | Visualizar autor e seus livros |
| `/authors/{id}/edit` | `AuthorEdit.vue`   | Formulário de edição           |

### Backend APIs Utilizadas

Todas as operações de frontend agora consomem a API REST do sistema descrita em `API_DOCUMENTATION.md`.

## 🔒 Funcionalidades de Segurança

### Autenticação SPA com Tokens

- **Guards de rotas Vue** para proteção das páginas que necessitam autenticação
- **Token Bearer** armazenado em localStorage para autenticação
- **Interceptor Axios** para enviar token automaticamente em cada requisição
- **Redirecionamento automático** para login quando não autenticado

### Validação e Proteção

- **Proteção CSRF** via token em meta tag e interceptor Axios
- **Validação de uploads** (JPG/PNG, máx 2MB)
- **Proteção contra exclusão** de autores com livros associados
- **Validação de formulários** no frontend e backend

## 📷 Sistema de Upload de Imagens

### Características

- **Formatos aceitos:** JPG, PNG
- **Tamanho máximo:** 2MB
- **Redimensionamento automático:** 200x200 pixels
- **Armazenamento:** storage/app/public/capas/
- **Visualização:** Preview dinâmico via componentes Vue

## 🔧 Estrutura da Aplicação Vue 3

### Organização de Arquivos

- **resources/js/app.js** - Ponto de entrada da aplicação Vue
- **resources/js/components/** - Componentes Vue organizados por funcionalidade
- **resources/js/composables/** - Composables Vue para lógica reutilizável
- **resources/views/app.blade.php** - Template base para montar a SPA

### Principais Tecnologias Frontend

- **Vue 3** - Framework JavaScript progressivo
- **Vue Router 4** - Sistema de roteamento para SPA
- **Axios** - Cliente HTTP para comunicação com API
- **Bootstrap 5** - Framework CSS para UI responsiva
- **Vite** - Build tool para desenvolvimento rápido

## ⚙️ Sistema de Scheduler

### Comando de Limpeza de Logs

```bash
php artisan logs:clean-old [--days=30]
```

### Agendamento Automático

- **Frequência:** Diariamente às 00:00
- **Logs de execução:** storage/logs/scheduler.log
- **Configuração:** bootstrap/app.php

## 🎯 Como Usar o Sistema

### 1. Acessar como Visitante

1. Acesse: `http://localhost:8000`
2. Navegue pela página inicial da SPA Vue sem login

### 2. Fazer Login

1. Clique em "Login" no menu de navegação
2. Use as credenciais: `admin@test.com` / `password`

---

**Desenvolvido por Lucas Rodrigues**
