# Sys Produtos

## Configurações da "api-sys_produtos"

- nginx
- mysql:5.7.22
- phpmyadmin
- redis

## Dependências utilizadas
- backend:
    - darkaonline/l5-swagger (documentação)
- frontend:
    - vite
    - vue-axios
    - vuetify
    - pinia

## Informes dos serviços
- backend (Laravel): http://localhost:8989/api
- frontend (Vue.js + Vuetify): http://localhost:3000
- docs (Swagger): http://localhost:8989/api/documentation

### Para instalar e rodar com docker

Arquivo: docker-compose.yml

~~~~php
docker-compose up -d
~~~~

## configuração do .env (Banco de dados MySQL)
~~~php
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=sys_produtos
DB_USERNAME=root
DB_PASSWORD=root
~~~