# Etapa 1: Construção
FROM node:16 as build-stage

# Define o diretório de trabalho no contêiner
WORKDIR /app

# Copia os arquivos package.json e package-lock.json
COPY package*.json ./

# Instala as dependências
RUN npm install

# Copia o restante dos arquivos do projeto
COPY . .

# Executa o build da aplicação
RUN npm run build

# Etapa 2: Produção
FROM nginx:stable-alpine as production-stage

# Copia os arquivos buildados da etapa anterior
COPY --from=build-stage /app/dist /usr/share/nginx/html

# Copia o arquivo de configuração do Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Expor a porta 80 para acessar a aplicação
EXPOSE 80

# Comando para iniciar o Nginx
CMD ["nginx", "-g", "daemon off;"]
