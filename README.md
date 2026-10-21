# API de Entregas

API REST em Laravel para gerenciamento de entregas, com autenticação por token.

## Stack
- PHP 8.4 / Laravel
- SQLite (configurável para MySQL via `.env`)
- Laravel Sanctum (autenticação)
- Cache com Redis (tags por usuário) na listagem de entregas
- Testes automatizados com `php artisan test`

## Requisitos
- PHP 8.4 e Composer
- Redis rodando em `127.0.0.1:6379` (no Windows, por exemplo, o Memurai)

## Como rodar
O projeto Laravel está na pasta `api-entregas`.

```bash
cd api-entregas
composer install
cp .env.example .env
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
| GET | /api/entregas | Lista entregas | Sim |
| POST | /api/entregas | Cria entrega | Sim |
| GET | /api/entregas/{id} | Detalha entrega | Sim |
| PUT | /api/entregas/{id} | Atualiza entrega | Sim |
| DELETE | /api/entregas/{id} | Remove entrega | Sim |

As rotas protegidas exigem o header `Authorization: Bearer {token}`.

O arquivo `requests.http`, na raiz do repositório, tem exemplos de requisições para testar a API no PhpStorm.

## Testes
```bash
cd api-entregas
php artisan test
```
