# Sistema de Consulta e Armazenamento de CEPs

[![Symfony](https://img.shields.io/badge/Symfony-6.x-%23000000.svg?style=flat&logo=symfony)](https://symfony.com)
[![React](https://img.shields.io/badge/React-18.x-%2361DAFB.svg?style=flat&logo=react)](https://reactjs.org)

Um sistema integrado para consulta de endereços via CEP utilizando a API do ViaCEP, com armazenamento local e interface web moderna.

## 📋 Funcionalidades Principais
- Consulta de endereços por CEP via API ViaCEP
- Armazenamento local dos endereços consultados
- Cache de consultas por 1 hora
- Listagem paginada de endereços
- Ordenação por Localidade, Bairro e UF

## 🚀 Pré-requisitos
- PHP 8.1+
- Composer 2.5+
- Node.js 18.x+
- npm 9.x+
- Symfony CLI (opcional)
- MySQL 8.0+ ou outro banco compatível

## 🔧 Instalação

### Backend (Symfony)
1. Clone o repositório:
   ```bash
   git clone [URL_DO_REPOSITORIO]
   cd projeto/backend

2. Instale as dependências::
   ```bash
   composer install
   
3. Configure o ambiente:
    Crie ou edite o arquivo `.env.local` com:
   ```bash
   # .env.local
   DATABASE_URL="mysql://usuario:senha@127.0.0.1:3306/nome_banco?serverVersion=8.0&charset=utf8mb4"
   APP_ENV=dev

4. Crie o banco de dados e execute as migrations:
   ```bash
     php bin/console doctrine:database:create
     php bin/console doctrine:migrations:migrate

5. Inicie o servidor:
   ```bash
     symfony serve:start

### Instruções para Rodar o Frontend (React)

1. **Acesse o diretório do frontend:**
   ```bash
   cd projeto/frontend

2. Instale as dependências:
   ```bash
   npm install

3. Inicie a aplicação:
   ```bash
   npm run dev

# Uso

## Consulta de CEP

1. Acesse [http://localhost:3000](http://localhost:3000)
2. Insira um **CEP válido** (8 dígitos) no campo de busca.
3. Clique em **"Buscar"**.

## Listagem de Endereços

1. Acesse a **listagem de endereços**.
2. Utilize os **botões de ordenação** para organizar os resultados.
3. Navegue entre as páginas usando a **paginação**.

