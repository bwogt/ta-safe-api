# TA-SAFE-API

[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![Laravel Sanctum](https://img.shields.io/badge/Sanctum-4.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com/docs/sanctum)
[![Laravel Telescope](https://img.shields.io/badge/Telescope-5.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com/docs/telescope)
[![Laravel Breeze](https://img.shields.io/badge/Breeze-2.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com/docs/starter-kits)
[![Laravel Pint](https://img.shields.io/badge/Pint-1.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com/docs/pint)
[![Scramble](https://img.shields.io/badge/Scramble-0.12-4B5563?style=flat&logo=openapiinitiative&logoColor=white)](https://scramble.dedoc.co/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)](https://mysql.com)
[![Redis](https://img.shields.io/badge/Redis-7.2-DC382D?style=flat&logo=redis&logoColor=white)](https://redis.io)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=flat&logo=docker&logoColor=white)](https://docker.com)
[![Grafana](https://img.shields.io/badge/Grafana-Stack-F46800?style=flat&logo=grafana&logoColor=white)](https://grafana.com)
[![PHPUnit](https://img.shields.io/badge/PHPUnit-11.x-6C2AF2?style=flat&logo=php&logoColor=white)](https://phpunit.de)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

[![CI](https://github.com/bwogt/ta-safe-api/actions/workflows/laravel-pint.yml/badge.svg)](https://github.com/bwogt/ta-safe-api/actions/workflows/laravel-pint.yml)
[![CI](https://github.com/bwogt/ta-safe-api/actions/workflows/laravel.yml/badge.svg)](https://github.com/bwogt/ta-safe-api/actions/workflows/laravel.yml)

### Descrição

No Brasil, estima-se que **mais de 1 milhão de aparelhos celulares** sejam roubados ou furtados anualmente. Esse mercado paralelo movimenta cifras expressivas e sustenta um ciclo no qual dispositivos de procedência incerta são repassados a consumidores sem qualquer comprovação de origem.

Nesse contexto, o comprador comum dispõe de recursos limitados para verificar a legalidade e a procedência de um aparelho usado.

O Tá Safe propõe-se a mitigar essa lacuna por meio de um protótipo acadêmico que possibilita o registro do dispositivo a partir da Nota Fiscal Eletrônica (NF-e) e o gerenciamento da transferência de propriedade entre usuários, mediante um fluxo de solicitação e aceite.

A cada transferência, o histórico do aparelho é atualizado, formando um registro público, verificável e rastreável de propriedade. Na prática: você passa a ter como avaliar a procedência do celular antes de comprar. E o mercado fica um pouco mais transparente.

Esta API é responsável por orquestrar toda a lógica de negócio do sistema, gerenciando autenticação, persistência de dados, integrações externas e o processamento assíncrono de tarefas por meio de filas e cache.

### 🔗 Links úteis

- 🎓 [Monografia (TCC)](https://tcc.tsi.pro.br/uploads/academic_activity/pdf/268/GP_COINT_2024_1_BRUNO_JOSE_DOS_SANTOS_WOGT_MONOGRAFIA.pdf)
- 🎯 [Gestão de atividades (Kanban)](https://github.com/users/bwogt/projects/4)
- 📱 [TA-SAFE-MOBILE (Nova Versão)](https://github.com/bwogt/ta-safe-mobile)  — em construção
- 📦 [TA-SAFE-MOBILE-LEGACY (Versão Legada)](https://github.com/bwogt/ta-safe-mobile-legacy)



### 🚀 Principais Funcionalidades

+ 🟢 **Autenticação**: Cadastro, login e redefinição de senha;
+ 🟢 **Registro de celulares**: Registro do aparelho com validação e extração automática de dados a partir da Nota Fiscal Eletrônica (NF-e);
+ 🟢 **Histórico de propriedade**: Consulta ao histórico de proprietários e às transferências registradas do aparelho;
+ 🟢 **Transferência de titularidade**: Gerenciamento de solicitações de transferência entre usuários, incluindo criação, cancelamento e aceite.

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


### 🐳 Ambiente de Desenvolvimento (Docker)

### Pré-Requisitos
- Git
- Docker Engine 24+
- Docker Compose v2

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
DB_USERNAME=developer
DB_PASSWORD=password
~~~

5. Inicie a aplicação com o comando
~~~bash
docker compose --profile web up -d --build
~~~

## 🧪 Ambiente de Testes
O ambiente de testes é executado de forma isolada, utilizando:
 - containers próprios
 - banco de dados efêmero
 - variáveis de ambiente específicas

1. Suba o ambiente de testes
~~~bash
docker compose --profile test --env-file=.env.testing up -d
~~~

2. Executando testes unitários e de integração
~~~bash
docker compose exec app_test php artisan test
~~~

As migrations são executadas automaticamente ao subir o container de testes.

### 📊 Observabilidade (Logs)
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

### 📘 Documentação
Após iniciar o ambiente de desenvolvimento, a documentação interativa da API estará disponível em:
http://localhost/docs/api#/

