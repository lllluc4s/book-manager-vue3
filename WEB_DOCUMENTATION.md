# 🌐 Sistema Web - Gestão de Livros

Sistema Laravel com interface web responsiva para gestão de livros e autores.

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

## 🌍 URLs e Funcionalidades

### Sistema de Autenticação

| URL       | Método | Descrição           |
| --------- | ------ | ------------------- |
| `/login`  | GET    | Formulário de login |
| `/login`  | POST   | Processar login     |
| `/logout` | POST   | Fazer logout        |

### Área Administrativa (Requer Login + Permissão Admin)

#### Gestão de Livros

| URL                | Método | Descrição             |
| ------------------ | ------ | --------------------- |
| `/books`           | GET    | Lista todos os livros |
| `/books/create`    | GET    | Formulário de criação |
| `/books`           | POST   | Salvar novo livro     |
| `/books/{id}`      | GET    | Visualizar livro      |
| `/books/{id}/edit` | GET    | Formulário de edição  |
| `/books/{id}`      | PUT    | Atualizar livro       |
| `/books/{id}`      | DELETE | Excluir livro         |
| `/books/{id}/capa` | DELETE | Remover capa do livro |

#### Gestão de Autores

| URL                  | Método | Descrição                           |
| -------------------- | ------ | ----------------------------------- |
| `/authors`           | GET    | Lista todos os autores              |
| `/authors/create`    | GET    | Formulário de criação               |
| `/authors`           | POST   | Salvar novo autor                   |
| `/authors/{id}`      | GET    | Visualizar autor e seus livros      |
| `/authors/{id}/edit` | GET    | Formulário de edição                |
| `/authors/{id}`      | PUT    | Atualizar autor                     |
| `/authors/{id}`      | DELETE | Excluir autor (se não tiver livros) |

## 🔒 Funcionalidades de Segurança

### Middleware de Administrador

- **Verificação de permissões** em todas as rotas administrativas
- **Erro 403** para usuários não autorizados
- **Redirecionamento automático** para login quando não autenticado

### Validação e Proteção

- **Proteção CSRF** nos formulários
- **Validação de uploads** (JPG/PNG, máx 2MB)
- **Proteção contra exclusão** de autores com livros associados
- **Sanitização** de dados de entrada

## 📷 Sistema de Upload de Imagens

### Características

- **Formatos aceitos:** JPG, PNG
- **Tamanho máximo:** 2MB
- **Redimensionamento automático:** 200x200 pixels
- **Armazenamento:** storage/app/public/capas/
- **Visualização:** Preview na edição e detalhes

### Validação

```php
'capa' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
```

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
2. Navegue pela página inicial sem login

### 2. Fazer Login Administrativo

1. Clique em "Login" na navbar
2. Use as credenciais: `admin@test.com` / `password`
3. Será redirecionado para área administrativa

### 3. Gerenciar Livros

1. Acesse "Livros" no menu principal
2. Use botões para **Criar**, **Editar** ou **Excluir**
3. **Upload de capas:** Drag & drop ou seleção de arquivo
4. **Visualização:** Capa aparece redimensionada automaticamente

### 4. Gerenciar Autores

1. Acesse "Autores" no menu principal
2. Crie novos autores com nome e estado
3. Visualize livros associados a cada autor
4. **Restrição:** Autores com livros não podem ser excluídos

---

**Desenvolvido por Lucas Rodrigues**
