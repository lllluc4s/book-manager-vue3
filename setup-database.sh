#!/bin/bash

# Script para configurar o banco de dados MySQL/MariaDB para o projeto Laravel

echo "Configurando banco de dados MySQL/MariaDB..."

# Criar banco de dados e usuário
sudo mysql -u root << EOF
CREATE DATABASE IF NOT EXISTS book_manager;
CREATE USER IF NOT EXISTS 'laravel'@'localhost' IDENTIFIED BY 'laravel123';
GRANT ALL PRIVILEGES ON book_manager.* TO 'laravel'@'localhost';
FLUSH PRIVILEGES;
SHOW DATABASES;
EOF

echo "Banco de dados configurado com sucesso!"
echo "Database: book_manager"
echo "Username: laravel"
echo "Password: laravel123"
