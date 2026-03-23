# Barbearia API (Laravel 12)

Este repositório contém a API Backend do sistema de agendamentos para a Barbearia, desenvolvida como parte do Teste Técnico para Desenvolvedor Back-End Júnior.

Nota Importante: O aplicativo visual Frontend Bônus (projetado em React/Next.js) que consome toda essa API não está na `master`. Ele foi versionado isoladamente **na própria branch chamada `barbearia-frontend` deste mesmo repositório do Github**! Mude a aba de branch ali em cima pra ver o código fonte das telas.

## Como Executar a API Localmente (O Motor)

Siga as instruções abaixo para rodar o backend principal na sua máquina:

1. Ligue o servidor de banco de dados MySQL (ex: pelo XAMPP).
2. Abra o seu terminal e acesse a raiz do projeto clonado.
3. Instale as dependências do servidor:
   ```bash
   composer install
   ```
4. Crie uma cópia do arquivo de configurações renomeando `env.example` para `.env` na raiz do projeto.
5. Gere a chave única de segurança da aplicação:
   ```bash
   php artisan key:generate
   ```
6. O seu arquivo `.env` deve conter estas credenciais básicas apontando para o seu MySQL:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=barbearia_api
   DB_USERNAME=root
   DB_PASSWORD=
   ```
7. Crie o banco de dados e injete os dados iniciais do administrador de uma vez:
   ```bash
   php artisan migrate:fresh --seed
   ```
8. Inicie o servidor da API isoladamente:
   ```bash
   php artisan serve
   ```
A sua API estará pronta e blindada rodando em `http://127.0.0.1:8000`. (*Não feche este terminal ligado*).

## Como Executar o Aplicativo Visual (O Frontend React)

Se você preferir não testar a API cruamente com códigos e quiser usar a interface visual real que idealizamos:

1. Deixe o motor do Backend rodando intocável (Passo 8 acima).
2. Abra um NOVO terminal e puxe do repositório a branch que guarda nossa interface:
   ```bash
   git checkout barbearia-frontend
   ```
3. Com os nossos arquivos visuais preenchendo a pasta, instale os pacotes Node:
   ```bash
   npm install
   ```
4. Ligue o servidor das telas dinâmicas:
   ```bash
   npm run dev
   ```
5. Abra o seu navegador comum e acesse: `http://localhost:3000`
6. Teste e logue visualmente no "Painel de Administração" usando a conta mestre plantada no passo 7 da API:
   - E-mail: admin@barbearia.com
   - Senha: admin123


## Testes Independentes sem Tela (Postman/VS Code)

O maior poder de uma arquitetura RESTful é sua independência. Se quiser testar o motor de negócio enxuto (ignorando os passos do app em React acima):
1. Abra o arquivo `testes.http` fornecido no repositório.
2. Usando uma extensão REST Client de sua preferência, atire "Send Request" nele para simular as criações de clientes e marcações de horário pela injeção JSON direta.

## Monitorando Filas de Disparo (Notificação)

Todo Agendamento bem-sucedido envia um pacote log de e-mail ao gestor sorrateiramente em *Plano de Fundo* em frações de segundos. Para capturar processamentos em fila rodando e esvazia-la:
Abra um novo terminal e ative o descarregador:
```bash
php artisan queue:work
```
