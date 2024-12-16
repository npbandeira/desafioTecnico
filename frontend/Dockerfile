FROM node:lts-alpine

# Instalar o servidor HTTP simples para servir conteúdo estático
RUN npm install -g http-server

# Tornar a pasta 'app' o diretório de trabalho atual
WORKDIR /app

# Copiar tanto 'package.json' quanto 'package-lock.json' (se disponível)
COPY package*.json ./

# Instalar as dependências do projeto
RUN npm install

# Copiar os arquivos e pastas do projeto para o diretório de trabalho atual (ou seja, a pasta 'app')
COPY . .

# Construir o app para produção com minificação
RUN npm run build

# Expor a porta 8080
EXPOSE 8080

# Montar o volume no diretorio do projeto para build
VOLUME ["/app/dist"]

# Comando para iniciar o servidor HTTP, servindo a pasta 'dist'
CMD [ "http-server", "dist" ]
