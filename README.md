# Backend Challenge 20230105

## Descrição
Projeto para gerenciamento de produtos com uma API RESTful, incluindo funcionalidades de CRUD e importação de dados.

## Tecnologias Usadas
- **Linguagem:** PHP
- **Framework:** Laravel
- **Banco de Dados:** MySQL

## Instalação e Uso

1. **Clone o Repositório:**
    ```bash
    git clone https://github.com/AsafeSantana/coodesh_challenge
    ```

2. **Instale as Dependências:**
    ```bash
    cd repo
    composer install
    ```

3. **Configure o Banco de Dados:**
    - Edite o arquivo `.env` com as credenciais do seu banco de dados MySQL.

4. **Execute as Migrations:**
    ```bash
    php artisan migrate
    ```

5. **Inicie o Servidor:**
    ```bash
    php artisan serve
    ```

## .gitignore
O projeto inclui um arquivo `.gitignore` para ignorar arquivos e diretórios indesejados.

## Referência
This is a challenge by Coodesh

## Métodos da API

- **index():** Lista todos os produtos paginados. Retorna JSON com a lista de produtos.
- **show($code):** Exibe os detalhes de um produto específico pelo código, retorna JSON com os detalhes do produto ou erro.
- **update(Request $request, $code):** Atualiza um produto existente. Retorna JSON com o produto atualizado ou criado.
- **destroy($code):** Move um produto para o status "trash". Retorna sucesso ou erro.

## ImportFoodFacts
Responsável por importar, baixar e processar dados vindos do Open Food. Atualiza ou cria esses produtos no banco de dados.

## Endpoints

- `GET /`: Detalhes da API, incluindo status da conexão com o banco de dados, horário da última execução do CRON, tempo online e uso de memória.
- `PUT /products/{code}`: Atualiza um produto existente.
- `DELETE /products/{code}`: Muda o status do produto para "trash".
- `GET /products/{code}`: Obtém informações de um produto específico.
- `GET /products`: Lista todos os produtos com paginação.
