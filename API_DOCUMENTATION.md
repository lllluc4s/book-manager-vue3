# 📡 API REST - Sistema de Gestão de Livros

API para gestão de livros e autores com autenticação Laravel Sanctum. Esta API foi projetada para funcionar com o frontend Vue 3.

## 🔐 Autenticação

A API utiliza **Laravel Sanctum** para autenticação baseada em tokens Bearer. Todas as rotas protegidas requerem um token válido no header `Authorization`.

## 🚀 Endpoints de Autenticação

### Registro de Usuário

```http
POST /api/auth/register
Content-Type: application/json

{
    "name": "Nome do Usuário",
    "email": "email@exemplo.com",
    "password": "senha123",
    "password_confirmation": "senha123"
}
```

### Login

```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "admin@test.com",
    "password": "password"
}
```

**Resposta de sucesso:**

```json
{
  "success": true,
  "message": "Login realizado com sucesso",
  "data": {
    "user": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@test.com"
    },
    "token": "1|AbCdEf123456...",
    "token_type": "Bearer"
  }
}
```

### Logout

```http
POST /api/auth/logout
Authorization: Bearer {token}
```

### Informações do Usuário

```http
GET /api/auth/user
Authorization: Bearer {token}
```

## 👥 Endpoints de Autores

> **⚠️ Todas as rotas a seguir requerem autenticação**

### 1. Listar Autores

```http
GET /api/authors
Authorization: Bearer {token}

# Parâmetros opcionais:
# ?estado=1 (filtrar por estado ativo/inativo)
# ?per_page=15 (itens por página)
```

### 2. Criar Autor

```http
POST /api/authors
Authorization: Bearer {token}
Content-Type: application/json

{
    "nome": "Nome do Autor",
    "estado": true
}
```

### 3. Exibir Autor Específico

```http
GET /api/authors/{id}
Authorization: Bearer {token}
```

### 4. Atualizar Autor

```http
PUT /api/authors/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "nome": "Novo Nome",
    "estado": false
}
```

### 5. Excluir Autor

```http
DELETE /api/authors/{id}
Authorization: Bearer {token}
```

> **🛡️ Proteção:** Não é possível excluir autores que possuem livros associados (retorna erro 409).

### 6. Obter Livros de um Autor

```http
GET /api/authors/{id}/books
Authorization: Bearer {token}
```

**Resposta:**

```json
{
    "success": true,
    "data": {
        "author": {
            "id": 1,
            "nome": "Machado de Assis",
            "estado": true
        },
        "books": {
            "data": [
                {
                    "id": 1,
                    "titulo": "Dom Casmurro",
                    "descricao": "Romance clássico...",
                    "data_publicacao": "1899-01-01",
                    "capa": "capas/exemplo.jpg"
                }
            ],
            "pagination": {...}
        }
    }
}
```

## 📚 Endpoints de Livros

> **⚠️ Todas as rotas de livros requerem autenticação**

### 1. Listar Livros

```http
GET /api/books
Authorization: Bearer {token}

# Parâmetros opcionais:
# ?author_id=1 (filtrar por autor)
# ?per_page=15 (itens por página)
```

### 2. Criar Livro

```http
POST /api/books
Authorization: Bearer {token}
Content-Type: application/json

{
    "titulo": "Título do Livro",
    "descricao": "Descrição detalhada do livro",
    "data_publicacao": "2025-05-15",
    "author_id": 1,
    "capa": "base64_encoded_image_string"  // Opcional
}
```

### 3. Exibir Livro Específico

```http
GET /api/books/{id}
Authorization: Bearer {token}
```

### 4. Atualizar Livro

```http
PUT /api/books/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "titulo": "Novo Título",
    "descricao": "Nova descrição",
    "data_publicacao": "2025-06-01",
    "author_id": 2,
    "capa": "base64_encoded_image_string"  // Opcional
}
```

### 5. Excluir Livro

```http
DELETE /api/books/{id}
Authorization: Bearer {token}
```

### 6. Remover Capa do Livro

```http
DELETE /api/books/{id}/capa
Authorization: Bearer {token}
```

## 📊 Códigos de Status HTTP

| Código | Descrição                                      |
| ------ | ---------------------------------------------- |
| `200`  | Sucesso                                        |
| `201`  | Criado com sucesso                             |
| `401`  | Não autenticado                                |
| `403`  | Sem permissão                                  |
| `404`  | Recurso não encontrado                         |
| `409`  | Conflito (ex: tentar excluir autor com livros) |
| `422`  | Erro de validação                              |

## 👤 Usuários de Teste

- **Administrador:** admin@test.com / password
- **Usuário:** user@test.com / password

---

**Desenvolvido por Lucas Rodrigues**
