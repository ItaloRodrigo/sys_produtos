# Sys Produtos

Este projeto é composto por duas principais stacks: uma aplicação frontend construída com Vue.js e uma API backend construída com Laravel. Abaixo estão as instruções detalhadas para configurar e executar o projeto localmente usando Docker.

## Estrutura do Projeto

- **Frontend**: Aplicação Vue.js com Vite.
- **Backend**: API Laravel com suporte para Swagger e outras ferramentas, autenticação JWT (Token Pessoal utilizando o pacote `laravel/sanctum`).

---

## Funcionalidades

### Usuários

- **Listagem de Usuários**: Exibe todos os usuários cadastrados em uma tabela com suporte a paginação.
- **Consulta de Usuários**: Permite buscar usuários por critérios específicos, como nome ou email.
- **Criação de Usuários**: Permite adicionar novos usuários ao sistema com dados básicos como nome, email e senha.
- **Edição de Usuários**: Permite atualizar informações dos usuários existentes.
- **Exclusão de Usuários**: Permite remover usuários do sistema.

### Produtos

- **Listagem de Produtos**: Exibe todos os produtos cadastrados em uma tabela com suporte a paginação.
- **Consulta de Produtos**: Permite buscar produtos por critérios específicos, como nome ou categoria.
- **Criação de Produtos**: Permite adicionar novos produtos com detalhes como nome, descrição, preço e imagem.
- **Edição de Produtos**: Permite atualizar informações dos produtos existentes.
- **Exclusão de Produtos**: Permite remover produtos do sistema.

### Categorias

- **Listagem de Categorias**: Exibe todas as categorias cadastradas em uma tabela com suporte a paginação.
- **Consulta de Categorias**: Permite buscar categorias por critérios específicos, como nome.
- **Criação de Categorias**: Permite adicionar novas categorias ao sistema.
- **Edição de Categorias**: Permite atualizar informações das categorias existentes.
- **Exclusão de Categorias**: Permite remover categorias do sistema.

## Processos de Listagem e Paginação

Todos os recursos de listagem de dados no sistema suportam paginação para gerenciar grandes volumes de informações de forma eficiente. Isso permite aos usuários navegar facilmente pelos dados e acessar informações específicas sem sobrecarregar a interface.

## Tecnologias Utilizadas

- **Frontend**: Vue.js, Vuetify, Vite, Chart.js
- **Backend**: Laravel, PHP, MySQL
- **Serviços**: Docker, Nginx, Redis

## Pré-requisitos

Antes de começar, certifique-se de ter o Docker e o Docker Compose instalados em seu sistema. Você pode baixar e instalar o Docker a partir do [site oficial do Docker](https://www.docker.com/get-started).

---

## Instalação e Execução

### Frontend

1. **Navegue para o diretório do frontend**:

    ```bash
    cd path/to/frontend
    ```

2. **Crie e inicie os containers Docker**:

    ```bash
    docker-compose up --build
    ```

    Isso criará um container para a aplicação Vue.js e o iniciará na porta `4000`.

3. **Acesse a aplicação**:

    Abra o navegador e vá para `http://localhost:4000` para visualizar a aplicação frontend, utilize as credenciais (usuário: admin@gmail.com, senha: admin).

### Backend

1. **Navegue para o diretório do backend**:

    ```bash
    cd path/to/backend
    ```

2. **Crie e inicie os containers Docker**:

    ```bash
    docker-compose up --build
    ```

    Isso criará os containers para a aplicação Laravel, o banco de dados MySQL, o PHPMyAdmin e o Redis. A aplicação Laravel será servida pelo Nginx na porta `8989`.

3. **Acesse os serviços**:

    - **Aplicação Laravel**: `http://localhost:8989`
    - **PHPMyAdmin**: `http://localhost:8080` (usuário: root, senha: root)
    - **Swagger**: `http://localhost:8989/api/documentation` (documentação da API Laravel)
    - **AppVue**: `http://localhost:4000` (usuário: admin@gmail.com, senha: admin)

---

## Dependências

### Frontend

- `@mdi/font`: 7.0.96
- `chart.js`: ^4.4.3
- `core-js`: ^3.34.0
- `jquery`: ^3.7.1
- `moment`: ^2.30.1
- `pinia-plugin-persistedstate`: ^3.2.1
- `roboto-fontface`: *
- `vue`: ^3.4.0
- `vue-axios`: ^3.5.2
- `vue-chartjs`: ^5.3.1
- `vue-the-mask`: ^0.11.1
- `vuetify`: ^3.5.0

### Backend

- `php`: ^8.0.2
- `darkaonline/l5-swagger`: ^8.6
- `guzzlehttp/guzzle`: ^7.2
- `laravel/framework`: ^9.19
- `laravel/sanctum`: ^3.3
- `laravel/tinker`: ^2.7

**Dependências de Desenvolvimento**:

- `fakerphp/faker`: ^1.9.1
- `laravel/pint`: ^1.0
- `laravel/sail`: ^1.0.1
- `mockery/mockery`: ^1.4.4
- `nunomaduro/collision`: ^6.1
- `phpunit/phpunit`: ^9.5.10
- `spatie/laravel-ignition`: ^1.0

---

## Configurações do `.env`

### Frontend

Não há configurações específicas do `.env` para o frontend neste projeto.

### Backend

Crie um arquivo `.env` na raiz do diretório do backend com o seguinte conteúdo:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:ZAtBhPpJmJEhuWSXUyl2PU1TgFVkdhQ8JoK/BIHS1Zk=
APP_DEBUG=true
APP_URL=http://localhost:8989

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=sys_produtos
DB_USERNAME=root
DB_PASSWORD=root


L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_CONST_HOST="${APP_URL}/api/v1"
```

---

## Notas Adicionais

- O Dockerfile está configurado para instalar todas as dependências, contudo, em caso de realmente não iniciar a aplicação faça o seguinte: 
-- entre no container da aplicação backend e rode o comando "composer install":

```docker
docker exec -it <nome-do-container> bash
composer install
```

- **Swagger**: A documentação da API está disponível através do Swagger UI na URL `http://localhost:8989/api/documentation`. Certifique-se de que o serviço de backend está rodando para acessar a documentação.

- Após rodar o comando para instanciar os containers, e obter o vendor, é necessário aguardar alguns minutos enquanto o swagger carrega a documentação, em caso de falha, execute:
 
 ```env
php artisan l5-swagger:generate
```
---

Com essas instruções, você deve ser capaz de configurar e executar o projeto em seu ambiente local usando Docker. Se você encontrar problemas ou tiver dúvidas, sinta-se à vontade para buscar ajuda ou revisar a documentação oficial do Docker e das ferramentas usadas.
