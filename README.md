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
```bash
git clone [URL_DO_REPOSITORIO]
cd projeto/backend
composer install

# Configurar .env.local com suas credenciais de banco
cp .env .env.local

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony serve:start
