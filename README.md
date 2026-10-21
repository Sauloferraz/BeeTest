# API de Entregas

API REST em Laravel para gerenciamento de entregas, com autenticação por token.

## Stack
- PHP 8.4 / Laravel
- MySQL
- Laravel Sanctum (autenticação)
- Cache com Redis (tags por usuário) na listagem de entregas
- Testes automatizados com `php artisan test`

## Requisitos
- PHP 8.4 e Composer
- MySQL rodando em `127.0.0.1:3306`, com um banco chamado `api_entregas`
- Redis rodando em `127.0.0.1:6379` (no Windows, por exemplo, o Memurai)

## Como rodar
O projeto Laravel está na pasta `api-entregas`.

```bash
cd api-entregas
composer install
cp .env.example .env
```

Edite o `.env` e preencha `DB_PASSWORD` com a senha do seu MySQL. Depois:

```bash
php artisan key:generate
php artisan migrate
php artisan serve
```

## Endpoints
| Método | Rota | Descrição | Autenticação |
|---|---|---|---|
| POST | /api/register | Cadastro de usuário | Não |
| POST | /api/login | Login (retorna token) | Não |
| POST | /api/logout | Logout | Sim |
| GET | /api/entregas | Lista entregas (filtro `?status=` e paginação `?page=`) | Sim |
| POST | /api/entregas | Cria entrega | Sim |
| GET | /api/entregas/{id} | Detalha entrega | Sim |
| PUT | /api/entregas/{id} | Atualiza entrega | Sim |
| DELETE | /api/entregas/{id} | Remove entrega | Sim |

As rotas protegidas exigem o header `Authorization: Bearer {token}`. Cada usuário acessa apenas as suas próprias entregas.

O arquivo `requests.http`, na raiz do repositório, tem exemplos de requisições para testar a API no PhpStorm.

## Testes
```bash
cd api-entregas
php artisan test
```