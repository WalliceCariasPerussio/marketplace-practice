
# Marketplace Connector

Sistema desenvolvido em Laravel 11 que tem o objetivo de integrar marketplaces a um hub centralizado.

## Requisitos

- **Docker** instalado ([Como instalar o Docker](https://docs.docker.com/get-docker/)).
- **Docker Compose** ([Como instalar o Docker Compose](https://docs.docker.com/compose/install/)).
- **Git** para clonar o projeto.

## Clonando o Repositório

Primeiro, clone o projeto do GitHub e vá para o diretório do projeto:

```bash
git clone https://github.com/WalliceCariasPerussio/marketplace-practice.git
cd marketplace-practice
```

## Simulando o Marketplace com Mockoon

Está sendo utilizado o **Mockoon** para simular a API de um marketplace. Foi configurado em **docker-compose.yml** para executar o Mockoon automaticamente junto com a aplicação.

## Configuração Inicial (Ambiente Local)

### Para Ubuntu:
```bash
cp .env.example .env
```

### Para Windows (PowerShell):
```powershell
Copy-Item .env.example .env
```

## Inicializando o Docker Compose

```bash
docker compose up -d
```

Subindo todos os serviços do projeto, como Laravel, MySQL, Redis, Mockoon, etc.

## Instalando Dependências

Instalando as dependências do PHP via Composer:

```bash
docker exec -it laravel-app composer install
```

## Gerando a Chave da Aplicação

```bash
docker exec -it laravel-app php artisan key:generate
```

## Migrando o Banco de Dados

```bash
docker exec -it laravel-app php artisan migrate --seed
```

## Ajustando Permissões

```bash
docker exec -it laravel-app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
```

## Autenticação JWT

Embora o desafio não tenha solicitado a implementação de autenticação, foi adicionado ao projeto uma camada simples de autenticação utilizando **JWT (JSON Web Token)**.

### Configuração do JWT

1. **Instalação do JWT:**
   
   Gerando a chave secreta que será usada para assinar os tokens.

   ```bash
   docker exec -it laravel-app php artisan jwt:secret
   ```

2. **Geração de Token JWT:**
   Gerando um token JWT para o usuário com o seguinte comando:

   ```bash
   docker exec -it laravel-app php artisan app:generate-jwt-token
   ```

   Exemplo de resposta ao gerar um token:

   ```json
   {
       "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0IiwiaWF0IjoxNzI4NzU3MzYxLCJleHAiOjE3Mjg3NjA5NjEsIm5iZiI6MTcyODc1NzM2MSwianRpIjoiZ0gyMVV2bWhrak8wOFdvOCIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.17JFXLidNt5IfWemj_geU7dgdAUKoceSZefUtsby3mk",
       "token_type": "bearer",
       "expires_in": 3600
   }
   ```

3. **Proteção de Rotas:**
   A rota de importação de ofertas foi protegida utilizando o middleware de autenticação **JWT**

   Exemplo de requisição com o token:

   ```bash
   curl -H "Authorization: Bearer <seu_access_token>" http://localhost/api/import-offers
   ```

## Testando a Importação de Ofertas

Endpoint para importação de ofertas:

```
GET localhost/api/import-offers
```

### Exemplo de retorno da API:

```json
{
    "message": "Importação de ofertas agendada com sucesso!",
    "offer_import": {
        "id": 29,
        "account_id": "1",
        "status": "pending",
        "total_offers": 0,
        "total_imported": 0,
        "created_at": "2024-10-14T11:32:07.000000Z",
        "updated_at": "2024-10-14T11:32:07.000000Z"
    }
}
```

## Executando Jobs com Laravel Queues

Para fins de **testes**, Foi utilizado o comando `queue:work` diretamente no arquivo `docker-compose.yml` para rodar os jobs em background. No entanto, em **produção**, é recomendado usar o **Supervisor** para gerenciar os workers de forma mais eficiente.

## Dicas Úteis

1. **Execução Paralela de Workers**: Foram configurados dois workers no arquivo `docker-compose.yml` para simular a execução paralela de jobs, assim, os processos de importação e exportação são executados simultaneamente.

2. **Execução do Worker**: O serviço **worker** no `docker-compose.yml` está configurado para rodar o comando `queue:work`. Durante a instalação, ele pode ser reiniciado algumas vezes até que todas as dependências sejam instaladas.

3. **Visualizando Jobs com Telescope**: O **Laravel Telescope** foi adicionado ao projeto para monitorar jobs e requisições HTTP. Para acessar o Telescope, use:

```
http://localhost/telescope
```

Certifique-se de que o **Telescope** está ativado no ambiente local (`APP_ENV=local` no arquivo `.env`).
4. **Acesso ao Banco de Dados**:
   - Dados de acesso padrão ao banco de dados:
     - **Host**: `localhost`
     - **Usuário**: `user`
     - **Senha**: `pass`
     - **Porta**: Definida pela variável `FORWARD_DB_PORT` no arquivo `.env`, por padrão **3306**.

---
