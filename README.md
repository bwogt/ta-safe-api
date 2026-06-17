# TA-SAFE-API
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)](https://mysql.com)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=flat&logo=docker&logoColor=white)](https://docker.com)
[![Redis](https://img.shields.io/badge/Redis-7.2-DC382D?style=flat&logo=redis&logoColor=white)](https://redis.io)
[![Grafana](https://img.shields.io/badge/Grafana-Stack-F46800?style=flat&logo=grafana&logoColor=white)](https://grafana.com)
[![PHPUnit](https://img.shields.io/badge/Tests-PHPUnit-6C2AF2?style=flat&logo=php&logoColor=white)](https://phpunit.de)

## 🧠 Contexto e Motivação

No Brasil, **mais de 1 milhão de celulares são roubados ou furtados por ano**. Esse mercado paralelo movimenta cifras significativas e alimenta um ciclo em que aparelhos de procedência duvidosa são repassados a consumidores sem qualquer comprovação de origem.

Diante desse cenário, o comprador comum dispõe de poucos recursos para verificar a legalidade e a procedência de um aparelho usado. O Tá Safe surge como um protótipo acadêmico que permite o registro do dispositivo a partir da Nota Fiscal Eletrônica (NF-e) e o registro voluntário das transferências de propriedade entre usuários. Esses dados constroem um histórico público, verificável e rastreável de propriedade e transferências, auxiliando o usuário na avaliação da procedência do aparelho e promovendo maior transparência sobre sua origem.

Esta API é responsável por orquestrar toda a lógica de negócio do sistema, gerenciando autenticação, persistência de dados, integrações externas e o processamento assíncrono de tarefas por meio de filas.

## 📌 Monografia e links úteis

- 📄 [Monografia completa (TCC)](https://github.com](https://tcc.tsi.pro.br/uploads/academic_activity/pdf/268/GP_COINT_2024_1_BRUNO_JOSE_DOS_SANTOS_WOGT_MONOGRAFIA.pdf))
- 🗂️ [Gestão de atividades (Kanban)](https://github.com/users/bwogt/projects/4)
- 📱 [Frontend mobile (versão legada)](https://github.com/bwogt/ta-safe-mobile-old)
- 🚀 [Frontend mobile (nova versão)](https://github.com/bwogt/ta-safe-mobile)  — em breve


## 🚀 Principais funcionalidades

+ **Autenticação**: Cadastro, login e redefinição de senha;
+ **Registro de celulares**: Validação e extração de dados a partir da Nota Fiscal Eletrônica (NF-e);
+ **Histórico de propriedade**: Consulta ao histórico de propriedade e de transferências do aparelho;
+ **Transferência de titularidade**: Registro da transferência de propriedade entre usuários.

## 🛠️ Stack de Tecnologias

| Camada          | Tecnologia                         |
|-----------------|-----------------------------------|
| **Linguagem**   | PHP 8.3                           |
| **Framework**   | Laravel 12                        |
| **Banco de Dados** | MySQL 8.0                      |
| **Cache / Filas** | Redis 7.2                       |
| **Containerização** | Docker & Docker Compose (custom) |
| **Testes**      | PHPUnit (com ambiente isolado)    |
| **Observabilidade** | Grafana + Loki + Alloy          |
| **Documentação** | Swagger/OpenAPI (UI integrada)   |


## 🐳 Ambiente de Desenvolvimento (Docker)

### Pré-Requisitos
- Git
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

5. Suba o ambiente de desenvolvimento (`com perfil web`)
~~~bash
docker compose --profile web up -d --build
~~~

6. Gere uma APP_KEY
~~~bash
docker compose exec app php artisan key:generate
~~~

## 🧪 Ambiente de Testes
O ambiente de testes é executado de forma isolada, utilizando:
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
O projeto possui uma stack de observabilidade voltada para o pilar de logs, composta por:
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
Após iniciar o ambiente de desenvolvimento, a documentação interativa da API estará disponível em:
~~~bash
http://localhost/docs/api#/
~~~
