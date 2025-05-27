# Configuração do Banco de Dados MySQL

## Pré-requisitos

Certifique-se de que o MySQL ou MariaDB está instalado e rodando:

```bash
# Verificar se está instalado
mysql --version

# Verificar se está rodando
sudo systemctl status mysql
# ou
sudo systemctl status mariadb
```

## Configuração Automática

Execute o script de configuração para criar o banco de dados e usuário:

```bash
./setup-database.sh
```

Este script irá:

- Criar o banco de dados `book_manager`
- Criar o usuário `laravel` com senha `laravel123`
- Conceder todas as permissões necessárias

## Configuração Manual

Se preferir configurar manualmente, execute os comandos SQL:

```sql
sudo mysql -u root

CREATE DATABASE IF NOT EXISTS book_manager;
CREATE USER IF NOT EXISTS 'laravel'@'localhost' IDENTIFIED BY 'laravel123';
GRANT ALL PRIVILEGES ON book_manager.* TO 'laravel'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## Variáveis de Ambiente

O arquivo `.env` está configurado com:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=book_manager
DB_USERNAME=laravel
DB_PASSWORD=laravel123
```

## Executar Migrações

Após configurar o banco, execute as migrações:

```bash
php artisan migrate
php artisan db:seed
```

## Verificar Conexão

Para testar a conexão:

```bash
php artisan migrate:status
```
