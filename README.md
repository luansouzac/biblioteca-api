# Biblioteca API

API REST desenvolvida em Laravel para gerenciar livros, autores, categorias e usuários de uma biblioteca.

## Tecnologias

- PHP 8.5
- Laravel 13
- MySQL 8.4
- Laravel Sanctum (autenticação por token)
- Docker (Laravel Sail)
- PHPUnit

## Requisitos

- Docker
- Make

## Como rodar o projeto

Clone o repositório:

```bash
git clone https://github.com/luansouzac/biblioteca-api.git
cd biblioteca-api
```

Instale e suba o projeto:

```bash
make install
```

Esse comando cria o `.env`, instala as dependências, sobe os containers, gera a chave da aplicação, cria as tabelas e popula o banco com dados de exemplo.

A API fica disponível em `http://localhost/api`.

Nas próximas vezes basta usar:

```bash
make up
make down
```

### Comandos disponíveis

| Comando | Descrição |
|---|---|
| `make install` | Instala e configura o projeto do zero |
| `make up` | Sobe os containers |
| `make down` | Derruba os containers |
| `make restart` | Reinicia os containers |
| `make ps` | Mostra o status dos containers |
| `make logs` | Mostra os logs |
| `make shell` | Abre um terminal dentro do container |
| `make migrate` | Roda as migrations |
| `make fresh` | Recria o banco e roda os seeders |
| `make seed` | Roda os seeders |
| `make routes` | Lista as rotas da API |
| `make test` | Roda os testes automatizados |
| `make artisan c="..."` | Roda qualquer comando do artisan |

## Usuário de teste

O seeder cria um usuário para testar as rotas protegidas:

- Email: `admin@biblioteca.com`
- Senha: `123456`

## Autenticação

As rotas de consulta (GET) são públicas. As rotas de cadastro, alteração e exclusão precisam de um token.

Para obter o token, faça login:

```bash
curl -X POST http://localhost/api/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@biblioteca.com","password":"123456"}'
```

Envie o token retornado no header das requisições:

```
Authorization: Bearer SEU_TOKEN
```

## Endpoints

### Autenticação

| Método | Rota | Descrição | Token |
|---|---|---|---|
| POST | `/api/register` | Cadastra um usuário | Não |
| POST | `/api/login` | Faz login e retorna o token | Não |
| POST | `/api/logout` | Invalida o token atual | Sim |
| GET | `/api/user` | Retorna o usuário logado | Sim |

### Autores

| Método | Rota | Descrição | Token |
|---|---|---|---|
| GET | `/api/autores` | Lista os autores | Não |
| GET | `/api/autores/{id}` | Retorna um autor e seus livros | Não |
| POST | `/api/autores` | Cadastra um autor | Sim |
| PUT | `/api/autores/{id}` | Atualiza um autor | Sim |
| DELETE | `/api/autores/{id}` | Remove um autor | Sim |

### Categorias

| Método | Rota | Descrição | Token |
|---|---|---|---|
| GET | `/api/categorias` | Lista as categorias | Não |
| GET | `/api/categorias/{id}` | Retorna uma categoria e seus livros | Não |
| POST | `/api/categorias` | Cadastra uma categoria | Sim |
| PUT | `/api/categorias/{id}` | Atualiza uma categoria | Sim |
| DELETE | `/api/categorias/{id}` | Remove uma categoria | Sim |

### Livros

| Método | Rota | Descrição | Token |
|---|---|---|---|
| GET | `/api/livros` | Lista os livros com autor e categoria | Não |
| GET | `/api/livros/{id}` | Retorna um livro com autor e categoria | Não |
| POST | `/api/livros` | Cadastra um livro | Sim |
| PUT | `/api/livros/{id}` | Atualiza um livro | Sim |
| DELETE | `/api/livros/{id}` | Remove um livro | Sim |

## Exemplos de requisição

### Cadastrar autor

```json
POST /api/autores
{
  "nome": "Machado de Assis",
  "nacionalidade": "Brasileira",
  "data_nascimento": "1839-06-21"
}
```

### Cadastrar categoria

```json
POST /api/categorias
{
  "nome": "Romance",
  "descricao": "Narrativas centradas em personagens e suas relações"
}
```

### Cadastrar livro

```json
POST /api/livros
{
  "titulo": "Dom Casmurro",
  "isbn": "9788535910663",
  "ano_publicacao": 1899,
  "paginas": 256,
  "autor_id": 1,
  "categoria_id": 1
}
```

### Resposta de `GET /api/livros/1`

```json
{
  "id": 1,
  "titulo": "Dom Casmurro",
  "isbn": "9788535910663",
  "ano_publicacao": 1899,
  "paginas": 256,
  "autor_id": 1,
  "categoria_id": 1,
  "autor": {
    "id": 1,
    "nome": "Machado de Assis",
    "nacionalidade": "Brasileira",
    "data_nascimento": "1839-06-21"
  },
  "categoria": {
    "id": 1,
    "nome": "Romance",
    "descricao": "Narrativas centradas em personagens e suas relações"
  }
}
```

## Códigos de resposta

| Código | Significado |
|---|---|
| 200 | Sucesso |
| 201 | Registro criado |
| 204 | Registro removido |
| 401 | Não autenticado |
| 404 | Registro não encontrado |
| 422 | Erro de validação |

## Banco de dados

As tabelas são criadas pelas migrations do Laravel:

- `users`
- `autores`
- `categorias`
- `livros` (com chaves estrangeiras para `autores` e `categorias`)

Ao remover um autor ou uma categoria, os livros relacionados também são removidos.

## Testes automatizados

Os testes ficam em `tests/Feature` e rodam em um banco separado (`testing`), sem afetar os dados da aplicação.

Para rodar:

```bash
make test
```

| Arquivo | O que testa |
|---|---|
| `AuthTest` | Registro, login, logout, usuário logado e bloqueio sem token |
| `AutorTest` | Listagem, consulta, cadastro, validação, atualização e exclusão de autores |
| `CategoriaTest` | Listagem, consulta, cadastro, nome duplicado, atualização e exclusão de categorias |
| `LivroTest` | Listagem com autor e categoria, cadastro, validação, atualização, exclusão e remoção em cascata |
