# API de Gestão de Autores - Documentação

## Autenticação

A API utiliza Laravel Sanctum para autenticação baseada em tokens. Todas as rotas protegidas requerem um token Bearer válido.

### Endpoints de Autenticação

#### 1. Registro de Usuário

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

#### 2. Login

```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "admin@test.com",
    "password": "password123"
}
```

**Resposta de sucesso:**

```json
{
    "success": true,
    "message": "Login realizado com sucesso",
    "data": {
        "user": {...},
        "token": "1|token_aqui",
        "token_type": "Bearer"
    }
}
```

#### 3. Logout

```http
POST /api/auth/logout
Authorization: Bearer {token}
```

#### 4. Informações do Usuário

```http
GET /api/auth/user
Authorization: Bearer {token}
```

## Endpoints de Autores

Todas as rotas de autores requerem autenticação.

### 1. Listar Autores

```http
GET /api/authors
Authorization: Bearer {token}

# Parâmetros opcionais:
# ?estado=1 (filtrar por estado ativo/inativo)
# ?per_page=10 (número de itens por página)
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

**Nota:** Não é possível excluir autores que possuem livros associados.

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
        "author": {...},
        "books": {
            "data": [...],
            "pagination": {...}
        }
    }
}
```

## Códigos de Status HTTP

-   `200` - Sucesso
-   `201` - Criado com sucesso
-   `401` - Não autenticado
-   `404` - Recurso não encontrado
-   `409` - Conflito (ex: tentar excluir autor com livros)
-   `422` - Erro de validação

## Usuários de Teste

-   **Admin:** admin@test.com / password123
-   **Usuário:** user@test.com / password123

## Exemplos de Uso com cURL

### Login e obtenção de token:

```bash
curl -X POST http://127.0.0.1:8001/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@test.com", "password": "password123"}'
```

### Listar autores:

```bash
curl -X GET http://127.0.0.1:8001/api/authors \
  -H "Authorization: Bearer {seu_token}"
```

### Criar autor:

```bash
curl -X POST http://127.0.0.1:8001/api/authors \
  -H "Authorization: Bearer {seu_token}" \
  -H "Content-Type: application/json" \
  -d '{"nome": "Novo Autor", "estado": true}'
```
