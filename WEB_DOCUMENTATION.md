# Sistema de Gestão de Livros - Documentação Web

## Visão Geral

Sistema Laravel completo para gestão de livros com interface web responsiva usando Blade templates e Bootstrap 5. O sistema oferece funcionalidades para gerenciar autores e livros com autenticação de usuários.

## Características Principais

-   ✅ CRUD completo para Livros e Autores
-   ✅ Sistema de autenticação web integrado
-   ✅ Interface responsiva com Bootstrap 5
-   ✅ Visualização pública (catálogo) sem autenticação
-   ✅ Área administrativa protegida por login
-   ✅ Relacionamentos entre entidades (Author → Books)
-   ✅ Validação de dados e proteção contra exclusão
-   ✅ Banco de dados MySQL configurado
-   ✅ Seeders com dados de exemplo

## Estrutura do Sistema

### Banco de Dados

**Tabela Authors:**

-   id (PK)
-   nome (string, 255)
-   estado (string, 2)
-   created_at, updated_at

**Tabela Books:**

-   id (PK)
-   titulo (string, 255)
-   descricao (text)
-   data_publicacao (date)
-   author_id (FK → authors.id)
-   created_at, updated_at

**Tabela Users:**

-   id (PK)
-   name (string)
-   email (string, unique)
-   password (hash)
-   email_verified_at
-   created_at, updated_at

### Usuários de Teste

Criados via seeders:

-   **Admin:** admin@test.com / password
-   **User:** user@test.com / password

## URLs e Funcionalidades

### Área Pública (Sem Autenticação)

| URL               | Descrição                  |
| ----------------- | -------------------------- |
| `/`               | Página inicial             |
| `/login`          | Formulário de login        |
| `/books/public`   | Catálogo público de livros |
| `/authors/public` | Lista pública de autores   |

### Área Administrativa (Requer Login)

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

### Sistema de Autenticação

| URL       | Método | Descrição                  |
| --------- | ------ | -------------------------- |
| `/login`  | GET    | Exibir formulário de login |
| `/login`  | POST   | Processar login            |
| `/logout` | POST   | Fazer logout               |

## Interface do Usuário

### Layout Principal

-   **Navbar responsiva** com Bootstrap 5
-   **Logo/título** do sistema
-   **Menu de navegação** dinâmico baseado no status de autenticação
-   **Botões de ação** com ícones Bootstrap Icons
-   **Alertas** para feedback de ações
-   **Design mobile-first** totalmente responsivo

### Páginas Públicas

-   **Catálogo de Livros:** Cards responsivos com informações do livro
-   **Lista de Autores:** Cards com nome, estado e quantidade de livros
-   **Design atrativo** com cores e ícones

### Páginas Administrativas

-   **Tabelas responsivas** para listagem
-   **Formulários validados** para criação/edição
-   **Botões de ação** (visualizar, editar, excluir)
-   **Confirmações** para ações destrutivas
-   **Breadcrumbs** para navegação

## Funcionalidades de Segurança

-   **Autenticação obrigatória** para área administrativa
-   **Redirecionamento automático** para login quando não autenticado
-   **Proteção CSRF** em todos os formulários
-   **Validação de dados** no backend
-   **Proteção contra exclusão** de autores com livros associados

## Como Usar o Sistema

### 1. Acessar o Sistema

1. Acesse: `http://127.0.0.1:8000`
2. Navegue pelas páginas públicas ou faça login

### 2. Fazer Login

1. Clique em "Login" na navbar
2. Use as credenciais de teste:
    - Email: `admin@test.com`
    - Senha: `password`

### 3. Gerenciar Livros

1. Após login, acesse "Livros" no menu
2. Use os botões para criar, editar ou excluir livros
3. Todos os livros devem ter um autor associado

### 4. Gerenciar Autores

1. Acesse "Autores" no menu
2. Crie novos autores informando nome e estado (sigla)
3. Visualize os livros de cada autor
4. **Nota:** Autores com livros não podem ser excluídos

### 5. Navegar como Visitante

1. Acesse `/books/public` para ver o catálogo
2. Acesse `/authors/public` para ver autores
3. Nenhuma autenticação necessária

## Dados Pré-cadastrados

### Autores (10 autores brasileiros)

-   Machado de Assis (RJ)
-   Clarice Lispector (PE)
-   Guimarães Rosa (MG)
-   Jorge Amado (BA)
-   Cecília Meireles (RJ)
-   E mais 5 autores...

### Livros (6 clássicos brasileiros)

-   Dom Casmurro - Machado de Assis
-   A Hora da Estrela - Clarice Lispector
-   Grande Sertão: Veredas - Guimarães Rosa
-   Gabriela, Cravo e Canela - Jorge Amado
-   Viagem - Cecília Meireles
-   O Cortiço - Aluísio Azevedo

## Tecnologias Utilizadas

-   **Backend:** Laravel 10
-   **Frontend:** Blade Templates + Bootstrap 5
-   **Banco de Dados:** MySQL/MariaDB
-   **Autenticação:** Laravel Auth (web sessions)
-   **Icons:** Bootstrap Icons
-   **Styling:** Bootstrap 5 + CSS customizado

## Status do Projeto

✅ **CONCLUÍDO** - Sistema totalmente funcional com:

-   CRUD completo implementado
-   Autenticação web funcionando
-   Interface responsiva e moderna
-   Dados de teste carregados
-   Validações e proteções implementadas
-   Documentação completa

## Próximos Passos (Opcionais)

-   Implementar upload de imagens para livros
-   Adicionar busca e filtros avançados
-   Implementar paginação para listas grandes
-   Adicionar sistema de categorias/gêneros
-   Implementar dashboard com estatísticas
-   Adicionar funcionalidade de favoritos
