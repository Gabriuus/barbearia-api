# Sistema de Barbearia - API RESTful (Laravel 12)

Este repositório contém o código-fonte da API da barbearia, criado como Teste Técnico para Desenvolvedor Back-End Júnior.

A aplicação utiliza o framework **Laravel 12**, implementando o padrão REST, com segregação de responsabilidades entre Controladores e **Service Classes**, validação utilizando **FormRequests** e notificação assíncrona baseada em filas e jobs.

## Requisitos
* PHP >= 8.2
* Composer
* Extensões PHP obrigatórias: `pdo_mysql` (Para o Banco de Dados)
* MySQL ou MariaDB rodando localmente (porta 3306)

## 📌 1. Instalação e Execução

### Passo a passo:
1. Abra o terminal na raiz do projeto (`barbearia-api`).
2. Garanta que suas credenciais estão corretas no arquivo `.env`. O padrão pré-configurado é:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=barbearia_api
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Instale as dependências com o Composer (se não estiverem instaladas):
   ```bash
   composer install
   ```
4. Crie o banco de dados `barbearia_api` via Painel de Controle (Ex: PhpMyAdmin, DBeaver) ou via linha de comando se ainda não existir.
5. Execute as migrações e o seeder (isso cirará os perfis e o admin inicial):
   ```bash
   php artisan migrate:fresh --seed
   ```
6. Inicie a fila de processamento (para recebimento de e-mails em log/assíncronos):
   ```bash
   php artisan queue:work
   ```
7. Em outra aba do terminal, inicie o servidor:
   ```bash
   php artisan serve
   ```

*(O e-mail padrão do Admin Inicial é `admin@barbearia.com` e a senha é `admin123`)*

---

## 📖 2. Documentação da API (Apidog / Swagger)

A especificação OpenAPI 3.0 está no arquivo raiz chamado `apidog.yaml`.
Para visualizar de forma interativa via **Apidog**:
1. Baixe o App ou Acesse [Apidog Web](https://apidog.com/).
2. Crie um novo projeto.
3. Clique em "Importar", selecione `apidog.yaml` deste diretório local.
4. Todos os endpoints e schemas estarão visíveis e prontos para teste.

---

## ⚡ 3. Teste via Postman / Insomnia

Para testar no Insomnia/Postman:
1. Crie uma requisição do tipo **POST** para `http://localhost:8000/api/login`.
   Corpo:
   ```json
   {
       "email": "admin@barbearia.com",
       "password": "admin123"
   }
   ```
2. Após disparar, copie o token retornado em `data.token`.
3. Para rotas privadas (Ex: `POST /api/admin/admins`), vá na aba `Auth` (ou `Authorization`), selecione **Bearer Token** e cole o token.
4. Para simular Cliente, use a rota aberta `POST /api/register` preenchendo o corpo JSON e faça o login com este mesmo usuário novo gerado.

---

## 🚀 4. Funcionalidades Implementadas (Bônus Incluídos)
- [x] Autenticação segura via **Sanctum**.
- [x] Implementação de **UUID** como chave principal no banco de dados.
- [x] Rotas privadas por tipo de perfil e criação *apenas de admins por admins*.
- [x] Agendamentos por clientes testando choque/colisão de horários.
- [x] Serviço de background Queue processando *Jobs/Mailables* de notificação de Agendamento.
- [x] Arquitetura distribuída com **Services** e regras em **FormRequests**.
- [x] Filtros e Paginação de listagens na agenda.
