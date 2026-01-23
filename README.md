# 📱 TA-SAFE-API
Este repositório contém a API responsável pelo backend do sistema Tá Safe, uma plataforma desenvolvida para rastreabilidade de celulares usados.
O backend é responsável por gerenciar toda a lógica de negócio, persistência de dados e integrações externas, oferecendo segurança e confiabilidade no processo de compra e venda de aparelhos.

## Monografia
https://repositorio.utfpr.edu.br/jspui/handle/1/34098

## Frontend
https://github.com/bwogt/ta-safe-mobile

## Principais funcionalidades

+ Cadastro e autenticação de usuários (com foco em segurança e privacidade).
+ Registro de celulares a partir da NF-e vinculada ao CPF do primeiro proprietário.
+ Consulta de histórico de propriedade, permitindo verificar a procedência do aparelho.
+ Transferência de propriedade entre usuários, com registro formal da transação.
+ Automatização para validação e extração de dados da NF-e.

## Tecnologias

+ PHP 
+ Laravel Framework 
+ MySQL 
+ Docker / Laravel Sail
+ PHPUnit

## Instalação Local

### Requisitos
- Docker Engine
- Docker Compose

### Passos

1. Clone o Repositório
~~~git
git clone git@github.com:bruw/ta-safe-api.git
~~~

2. Acesse a Pasta do Projeto
~~~bash
cd ta-safe-api
~~~

3. Instale as Dependências:

~~~bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
~~~

4. Copie o arquivo `example.env` e renomeie para `.env`

5. Modifique o `.env` com suas variáveis
~~~bash
DB_USERNAME=sail
DB_PASSWORD=password
~~~

6. Iniciar o Ambiente de Desenvolvimento
~~~bash
./vendor/bin/sail up -d
~~~

7. Gere uma APP_KEY
~~~bash
./vendor/bin/sail artisan key:generate
~~~

8. Executar Migrations e Seeders
~~~bash
./vendor/bin/sail artisan migrate:refresh --seed
~~~

## Testes
Para executar os testes utilize o seguinte comando
~~~bash
./vendor/bin/sail artisan test
~~~

## Documentação
Para visualizar a documentação da API acesse
~~~bash
http://localhost/docs/api#/
~~~

