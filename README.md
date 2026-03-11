# 🌐 TA-SAFE-API
Este repositório contém a API responsável pelo backend do sistema Tá Safe, uma plataforma desenvolvida para rastreabilidade de celulares usados.
O backend é responsável por gerenciar toda a lógica de negócio, persistência de dados e integrações externas, oferecendo segurança e confiabilidade no processo de compra e venda de aparelhos.

## 📌 Gestão de atividades (Kanban)
https://github.com/users/bwogt/projects/4

## 📄 Monografia
https://repositorio.utfpr.edu.br/jspui/handle/1/34098

## 📱 Frontend
https://github.com/bwogt/ta-safe-mobile

## 🚀 Principais funcionalidades

+ Cadastro e autenticação de usuários;
+ Registro de celulares a partir da NF-e vinculada ao CPF do primeiro proprietário;
+ Consulta de histórico de propriedade do aparelho;
+ Transferência de propriedade entre usuários;
+ Automatização para validação e extração de dados da NF-e;
+ Processamento assíncrono de tarefas via filas.

## 🛠️ Tecnologias

+ PHP 
+ Laravel 
+ MySQL 
+ Docker & Docker Compose
+ PHPUnit

## 🐳 Ambiente de Desenvolvimento (Docker)

O projeto utiliza um ambiente Docker customizado, substituindo o Laravel Sail, para permitir maior controle da infraestrutura e integração com ferramentas de observabilidade.

**Requisitos**
 - Docker Engine
 - Docker Compose

### Passos para executar

1. Clone o Repositório
~~~git
git clone git@github.com:bruw/ta-safe-api.git
~~~

2. Acesse a Pasta do Projeto
~~~bash
cd ta-safe-api
~~~

3. Copie o arquivo de ambiente
~~~bash
cp .env.example .env
~~~

4. Ajuste as variáveis necessárias no .env
~~~env
DB_DATABASE=ta_safe
DB_USERNAME=ta_safe
DB_PASSWORD=secret
~~~

5. Suba o ambient web
~~~bash
docker compose --profile web up -d --build
~~~

6. Gere uma APP_KEY
~~~bash
docker compose exec app php artisan key:generate
~~~

## 🧪 Ambiente de Testes
O ambiente de testes é totalmente isolado, utilizando:
 - containers próprios
 - banco de dados efêmero
 - variáveis de ambiente específicas

1 . Suba o ambiente de testes
~~~bash
docker compose \
  --profile test \
  --env-file=.env.testing \
  up -d --build
~~~

2 . Executando os testes
~~~bash
docker compose exec app_test php artisan test
~~~

As migrations são executadas automaticamente ao subir o container de testes.

## 📊 Observabilidade (Logs)
O projeto conta com uma stack de observabilidade focada no pilar de logs, utilizando:
 - Grafana Loki para armazenamento de logs
 - Grafana Alloy para coleta automática dos logs dos containers Docker
 - Grafana para visualização e análise

1. Subindo a stack de observabilidade
~~~bash
docker compose --profile obs up -d
~~~

2. Acessos
- Grafana: http://localhost:3000
     - usuário: admin
     - senha: admin

## 📘 Documentação
Para visualizar a documentação da API acesse
~~~bash
http://localhost/docs/api#/
~~~
