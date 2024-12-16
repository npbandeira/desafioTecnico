# Documentação do Projeto: CRUD de Usuários com Vue.js, Laravel e PostgreSQL

## Descrição do Projeto

Este projeto consiste em um sistema CRUD (Create, Read, Update, Delete) de usuários, utilizando Vue.js (com Vuetify) no front-end e Laravel no back-end. O sistema permite a realização de operações CRUD completas em uma base de dados PostgreSQL, com validações de campos no Laravel, como CPF, e-mail e senha.

## Tecnologias Utilizadas

- **Frontend**:
  - **Vue.js**: Framework JavaScript para a construção da interface do usuário.
  - **Vuetify**: Biblioteca de componentes UI baseada no Material Design para Vue.js.
  - **Axios**: Biblioteca para realizar requisições HTTP de forma simples e eficiente.

- **Backend**:
  - **Laravel**: Framework PHP para construção de APIs e gerenciamento de operações no back-end.
  - **PostgreSQL**: Banco de dados relacional utilizado para armazenar as informações dos usuários.

- **Docker**: Utilizado para containerizar os ambientes de front-end, back-end e banco de dados.

## Estrutura do Projeto

O projeto é organizado da seguinte maneira:

```
├── backend/
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── resources/
│   ├── routes/
│   ├── .env
│   └── Dockerfile
├── frontend/
│   ├── src/
│   ├── public/
│   ├── package.json
│   ├── Dockerfile
│   └── vuetify.config.js
├── docker-compose.yml
└── README.md
```

- **backend/**: Contém os arquivos relacionados ao back-end em Laravel.
  - **routes/**: Arquivos de definição de rotas, onde as rotas CRUD dos usuários estão configuradas.
  - **app/**: Contém as lógicas de aplicação como Models, Controllers e Migrations.

- **frontend/**: Contém os arquivos relacionados ao front-end em Vue.js.
  - **src/**: Contém os componentes Vue.js, arquivos Vuex para gerenciamento de estado, e os serviços Axios para se comunicar com o back-end.
  
- **docker-compose.yml**: Arquivo de configuração do Docker que define os containers e redes.
- **README.md**: Documento de instruções de configuração e uso do projeto.

## Como Rodar o Projeto

### Requisitos

- **Docker** e **Docker Compose** instalados.
- **Node.js** e **npm** para o front-end.
- **PHP** e **Composer** para o back-end (Laravel).

### Passo 1: Clonar o Repositório

Clone o repositório para o seu ambiente local:

```bash
git clone https://github.com/npbandeira/desafioTecnico.git
cd desafioTecnico
```

### Passo 2: Construir os Containers

Execute o comando para construir e iniciar os containers do Docker:

```bash
docker-compose up --build
```

Isso irá:

1. Construir os containers para o front-end (Vue.js), back-end (Laravel) e banco de dados (PostgreSQL).
2. Subir os containers e mapear as portas necessárias:
   - **Front-end (Vue.js)**: `http://localhost:8080`
   - **Back-end (Laravel)**: `http://localhost:8000`
   - **Banco de Dados (PostgreSQL)**: `localhost:5432`

### Passo 3: Configuração do Banco de Dados

A primeira vez que você rodar o projeto, é necessário rodar as migrações do Laravel para configurar o banco de dados:

```bash
docker-compose exec laravel-app php artisan migrate
```

Isso criará as tabelas necessárias para o gerenciamento dos usuários.

### Passo 4: Acessar a Aplicação

1. **Front-end**: Acesse o aplicativo Vue.js em `http://localhost:8080`. A interface inclui formulários para adicionar, editar, visualizar e excluir usuários.
2. **Back-end**: Acesse a API Laravel em `http://localhost:8000` para realizar as operações CRUD de usuários. As rotas e a lógica do controlador estão configuradas para retornar respostas JSON.

## Estrutura do `docker-compose.yml`

```yaml
volumes:
  postgres:
    driver: local

networks:
  frontend:
    driver: bridge
  backend:
    driver: bridge

services:
  vue-app:
    build:
      context: ./frontend
    container_name: vue-app
    ports:
      - "8080:8080"
    volumes:
      - ./frontend:/app
    environment:
      - NODE_ENV=development
    networks:
      - frontend

  laravel-app:
    build:
      context: ./backend
    container_name: laravel-app
    ports:
      - "8000:8000"
    volumes:
      - ./backend:/var/www/
    networks:
      - frontend
      - backend
    depends_on:
      - postgres

  postgres:
    image: postgres:15
    container_name: postgres
    restart: always
    volumes:
      - /database-volume/postgres:/var/lib/postgresql/data
    environment:
      POSTGRES_DB: laravel
      POSTGRES_USER: laravel
      POSTGRES_PASSWORD: password
    ports:
      - "5432:5432"
    networks:
      - backend
```

### Explicação dos Serviços:

- **vue-app**: Container do front-end. Mapeia a porta 8080 para o host e monta o diretório local `./frontend` no diretório `/app` do container.
- **laravel-app**: Container do back-end. Mapeia a porta 8000 para o host e monta o diretório local `./backend` no diretório `/var/www/` do container.
- **postgres**: Container do PostgreSQL. Configura a base de dados, usuário e senha. A base de dados é persistida em um volume.

## Instruções de Uso

### Front-end (Vue.js com Vuetify):

1. **Adicionar Usuário**: Preencha o formulário com os campos Nome, CPF, E-mail e Senha e clique em "Adicionar Usuário". Os dados serão enviados para o back-end Laravel.
2. **Visualizar Usuários**: A lista de usuários será exibida na tela principal, com a opção de editar ou excluir.
3. **Editar Usuário**: Clique no botão de edição ao lado de um usuário para atualizar suas informações.
4. **Excluir Usuário**: Clique no botão de exclusão para remover um usuário.

### Back-end (Laravel):

1. **Rotas CRUD**:
   - **POST** `/api/usuario`: Criar um novo usuário.
   - **GET** `/api/usuario`: Obter todos os usuários.
   - **GET** `/api/usuario/{id}`: Obter um usuário específico.
   - **PUT** `/api/usuario/{id}`: Atualizar um usuário específico.
   - **DELETE** `/api/usuario/{id}`: Excluir um usuário específico.

2. **Validações**:
   - **Nome**: Campo obrigatório.
   - **CPF**: Deve ser válido.
   - **Email**: Deve ser um e-mail válido.
   - **Senha**: Deve ter no mínimo 6 caracteres.
.