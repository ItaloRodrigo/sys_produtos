# Usa a imagem Node.js oficial
FROM node:16

# Define o diretório de trabalho no contêiner
WORKDIR /app

# Copia os arquivos package.json e package-lock.json
COPY package*.json ./

# Instala as dependências
RUN npm install

# Copia o restante dos arquivos do projeto
COPY . .

# Expõe a porta padrão que o Vue usa
EXPOSE 4000

# Comando para iniciar a aplicação no modo de desenvolvimento
CMD ["npm", "run", "dev"]