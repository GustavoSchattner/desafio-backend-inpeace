# ⛪ Desafio Backend InPeace

![Symfony](https://img.shields.io/badge/Symfony-000?style=for-the-badge&logo=symfony&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Doctrine](https://img.shields.io/badge/Doctrine-000?style=for-the-badge&logo=doctrine&logoColor=white)

Sistema robusto de gerenciamento de igrejas e membros desenvolvido com **Symfony 6+**, seguindo **PSR-12**, com suporte a **Soft Delete**, **CSRF Protection**, **Type Hints Completos** e **Alta Performance**. O projeto integra-se com a API do IBGE para carregamento dinâmico de Estados e Cidades.

---

## 📑 Índice

- [Funcionalidades](#-funcionalidades)
- [Pré-requisitos](#-pré-requisitos)
- [Instalação](#-instalação)
- [Configuração](#-configuração)
- [Uso](#-uso)
- [Tecnologias e Padrões](#-tecnologias-e-padrões)
- [Arquitetura](#-arquitetura)
- [Comandos Úteis](#-comandos-úteis)
- [Testes](#-testes)
- [Troubleshooting](#-troubleshooting)

---

## 🚀 Funcionalidades

- ✅ **CRUD Completo:** Gerenciamento de Igrejas e Membros
- ⛪ **Associação de Membros:** Vincular membros a igrejas específicas
- 🗑️ **Soft Delete:** Exclusão lógica com suporte a restauração
- 🌎 **Integração IBGE:** Consumo da API para Estados e Cidades
- ⚡ **Select Dinâmico:** Carregamento AJAX de cidades por estado
- 🛡️ **CSRF Protection:** Proteção contra ataques CSRF em todos os formulários
- 📊 **Paginação:** Listagem com paginação inteligente (KnpPaginator)
- 🎨 **UX/UI Moderna:** Interface Bootstrap com feedback visual
- ✔️ **Validação Completa:** Validação de CPF, Email e dados obrigatórios

---

## 📋 Pré-requisitos

### Documentação Oficial

- 📖 [Symfony Documentation](https://symfony.com/doc/current/index.html)
- 🐳 [Docker Documentation](https://docs.docker.com/)
- 🪟 [WSL2 Installation Guide](https://docs.microsoft.com/windows/wsl/install)
- 📚 [Doctrine ORM](https://www.doctrine-project.org/)
- 🛣️ [Symfony Routing](https://symfony.com/doc/current/routing.html)

Escolha seu sistema operacional abaixo:

<details>
<summary><strong>Windows (WSL2)</strong></summary>

### Requisitos
- Windows 10 versão 2004+ ou Windows 11
- WSL2 instalado e configurado
- Docker Desktop 4.0+

### Instalação do WSL2

```powershell
# Execute no PowerShell como Administrador
wsl --install
```

Após a instalação, reinicie o computador e configure seu usuário Linux.

### Instalação do Docker Desktop

1. Baixe o [Docker Desktop para Windows](https://www.docker.com/products/docker-desktop/)
2. Execute o instalador
3. Após instalação, abra o Docker Desktop
4. Vá em **Settings** → **Resources** → **WSL Integration**
5. Ative a integração com sua distribuição WSL2

### Verificação

```bash
# No terminal WSL2
docker --version
docker compose version
```

</details>

<details>
<summary><strong>Linux (Ubuntu/Debian)</strong></summary>

### Instalação do Docker

```bash
# Atualizar repositórios
sudo apt-get update

# Instalar dependências
sudo apt-get install ca-certificates curl gnupg

# Adicionar chave GPG oficial do Docker
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

# Adicionar repositório
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Instalar Docker
sudo apt-get update
sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# Adicionar seu usuário ao grupo docker (para não precisar de sudo)
sudo usermod -aG docker $USER
newgrp docker
```

### Verificação

```bash
docker --version
docker compose version
```

</details>

<details>
<summary><strong>Linux (Fedora/RHEL)</strong></summary>

### Instalação do Docker

```bash
# Remover versões antigas
sudo dnf remove docker docker-client docker-client-latest docker-common docker-latest docker-latest-logrotate docker-logrotate docker-selinux docker-engine-selinux docker-engine

# Instalar repositório
sudo dnf -y install dnf-plugins-core
sudo dnf config-manager --add-repo https://download.docker.com/linux/fedora/docker-ce.repo

# Instalar Docker
sudo dnf install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# Iniciar serviço
sudo systemctl start docker
sudo systemctl enable docker

# Adicionar usuário ao grupo
sudo usermod -aG docker $USER
newgrp docker
```

</details>

<details>
<summary><strong>Linux (Arch)</strong></summary>

### Instalação do Docker

```bash
# Instalar Docker
sudo pacman -S docker docker-compose

# Iniciar serviço
sudo systemctl start docker.service
sudo systemctl enable docker.service

# Adicionar usuário ao grupo
sudo usermod -aG docker $USER
newgrp docker
```

</details>

<details>
<summary><strong>macOS</strong></summary>

### Usando Homebrew (Recomendado)

```bash
# Instalar Docker Desktop
brew install --cask docker

# Ou instalar via download direto:
# https://www.docker.com/products/docker-desktop/
```

Após instalação, abra o Docker Desktop pela primeira vez para finalizar a configuração.

### Verificação

```bash
docker --version
docker compose version
```

</details>

---

## 🔧 Instalação

### Pré-requisitos do Sistema

Escolha seu sistema operacional para instalar Docker:

<details>
<summary><strong>Windows (WSL2)</strong></summary>

### Requisitos
- Windows 10 versão 2004+ ou Windows 11
- WSL2 instalado e configurado
- Docker Desktop 4.0+

### Instalação do WSL2

```powershell
# Execute no PowerShell como Administrador
wsl --install
```

Após a instalação, reinicie o computador e configure seu usuário Linux.

### Instalação do Docker Desktop

1. Baixe o [Docker Desktop para Windows](https://www.docker.com/products/docker-desktop/)
2. Execute o instalador
3. Após instalação, abra o Docker Desktop
4. Vá em **Settings** → **Resources** → **WSL Integration**
5. Ative a integração com sua distribuição WSL2

### Verificação

```bash
# No terminal WSL2
docker --version
docker compose version
php --version  # 8.1+
```

</details>

<details>
<summary><strong>Linux (Ubuntu/Debian)</strong></summary>

### Instalação do Docker

```bash
# Atualizar repositórios
sudo apt-get update

# Instalar dependências
sudo apt-get install ca-certificates curl gnupg

# Adicionar chave GPG oficial do Docker
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

# Adicionar repositório
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Instalar Docker
sudo apt-get update
sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# Adicionar seu usuário ao grupo docker (para não precisar de sudo)
sudo usermod -aG docker $USER
newgrp docker
```

### Instalar PHP e Composer

```bash
# Instalar PHP 8.1+ com extensões necessárias
sudo apt-get install php php-cli php-fpm php-mysql php-xml php-mbstring php-curl

# Instalar Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Verificação

```bash
docker --version
docker compose version
php --version
composer --version
```

</details>

<details>
<summary><strong>macOS</strong></summary>

### Usando Homebrew (Recomendado)

```bash
# Instalar Docker Desktop
brew install --cask docker

# Ou instalar via download direto:
# https://www.docker.com/products/docker-desktop/

# Instalar PHP e Composer
brew install php composer
```

Após instalação, abra o Docker Desktop pela primeira vez para finalizar a configuração.

### Verificação

```bash
docker --version
docker compose version
php --version
composer --version
```

</details>

### Instalação da Aplicação

```bash
# 1. Clonar o repositório
git clone https://github.com/seu-usuario/desafio-backend-inpeace.git
cd desafio-backend-inpeace

# 2. Instalar dependências PHP
composer install

# 3. Copiar arquivo de ambiente
cp .env.example .env

# 4. Iniciar containers Docker
docker compose up -d

# 5. Aguardar que os containers fiquem prontos (30-60 segundos)
docker compose ps

# 6. Rodar migrations do banco de dados
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

# 7. Limpar cache
docker compose exec php php bin/console cache:clear

# 8. Acessar aplicação
# http://localhost
```

---

## ⚙️ Configuração

### Arquivo .env

O arquivo `.env` contém as configurações principais. Certifique-se de configurar:

```env
# Aplicação
APP_ENV=dev
APP_SECRET=seu_secret_unico_aqui
APP_DEBUG=true

# Banco de Dados (MySQL via Docker)
DATABASE_URL="mysql://sail:password@mysql:3306/desafio_inpeace?serverVersion=8.0&charset=utf8mb4"
# ⚠️ IMPORTANTE: Use "mysql" como host, não "localhost"

# Mailer (opcional)
MAILER_DSN=null://null
```

**Por que `mysql:3306` em vez de `localhost`?**
- A aplicação roda dentro de um container Docker
- Precisa acessar o MySQL que está em **outro** container
- `mysql` é o nome do serviço no `docker-compose.yml`
- Usar `localhost` ou `127.0.0.1` fará a aplicação buscar o MySQL no próprio container (onde ele não existe)

### Estrutura de Pastas

```
src/
├── Controller/
│   ├── ChurchController.php       # CRUD de Igrejas
│   └── MemberController.php       # CRUD de Membros
├── Entity/
│   ├── Church.php                 # Entidade Igreja
│   └── Member.php                 # Entidade Membro
├── Form/
│   ├── ChurchType.php             # Formulário de Igreja
│   └── MemberType.php             # Formulário de Membro
├── Repository/
│   ├── ChurchRepository.php       # Queries customizadas para Igreja
│   └── MemberRepository.php       # Queries customizadas para Membro
├── Service/
│   ├── ChurchService.php          # Lógica de negócio de Igreja
│   ├── MemberService.php          # Lógica de negócio de Membro
│   └── FileUploader.php           # Gerenciamento de uploads
├── Validator/
│   ├── Cpf.php                    # Validador customizado de CPF
│   └── CpfValidator.php           # Implementação do validador
└── Kernel.php                     # Kernel do Symfony

templates/
├── base.html.twig                 # Template base (extends)
├── church/
│   ├── index.html.twig            # Listagem de igrejas
│   ├── show.html.twig             # Detalhes de uma igreja
│   ├── form.html.twig             # Formulário (new/edit)
│   └── delete.html.twig           # Página de delete com confirmação
└── member/
    ├── index.html.twig            # Listagem de membros
    ├── show.html.twig             # Detalhes de um membro
    ├── form.html.twig             # Formulário (new/edit)
    └── delete.html.twig           # Página de delete com confirmação

migrations/
└── Version20251221*.php           # Histórico de migrações

tests/
├── Controller/
│   ├── ChurchControllerTest.php   # Testes do ChurchController
│   └── MemberControllerTest.php   # Testes do MemberController
├── Entity/
├── Repository/
├── Service/
└── Validator/
```

---

## 💻 Uso

### Iniciando a Aplicação

```bash
# Iniciar containers
docker compose up -d

# Ver logs em tempo real
docker compose logs -f php

# Parar containers
docker compose down
```

### Acessando o Sistema

1. **Aplicação Web:** [http://localhost](http://localhost)
2. **PhpMyAdmin (opcional):** [http://localhost:8080](http://localhost:8080)
   - Usuário: `sail`
   - Senha: `password`

### Fluxos Principais

#### Gerenciar Igrejas
```
GET  /church              # Listagem de igrejas
GET  /church/new          # Formulário de nova igreja
POST /church              # Salvar nova igreja
GET  /church/{id}         # Detalhes de uma igreja
GET  /church/{id}/edit    # Formulário de edição
POST /church/{id}         # Atualizar igreja (PUT mascarado)
GET  /church/{id}/delete  # Página de confirmação
POST /church/{id}/delete  # Deletar igreja
```

#### Gerenciar Membros
```
GET  /member              # Listagem de membros
GET  /member/new          # Formulário de novo membro
POST /member              # Salvar novo membro
GET  /member/{id}         # Detalhes de um membro
GET  /member/{id}/edit    # Formulário de edição
POST /member/{id}         # Atualizar membro (PUT mascarado)
GET  /member/{id}/delete  # Página de confirmação
POST /member/{id}/delete  # Deletar membro
```

#### API IBGE (AJAX)
```
GET  /api/states          # Lista de estados
GET  /api/cities/{state}  # Cidades de um estado
```

---

## 🛠️ Tecnologias e Padrões

### Stack Tecnológica

| Tecnologia | Versão | Uso |
|------------|--------|-----|
| **Symfony** | 6.4+ | Framework PHP |
| **PHP** | 8.1+ | Linguagem backend |
| **Doctrine ORM** | 2.15+ | Mapeamento objeto-relacional |
| **MySQL** | 8.0 | Banco de dados |
| **Docker** | 24.0+ | Containerização |
| **Twig** | 3.0+ | Template engine |
| **Bootstrap** | 5.3+ | Framework CSS |
| **Vanilla JS** | ES6+ | Frontend interativo |

### Padrões de Código

- ✅ **PSR-12:** Padrão de código PHP seguido rigorosamente
- ✅ **Strict Types:** `declare(strict_types=1)` em todos os arquivos PHP
- ✅ **Type Hints:** Parâmetros e retornos totalmente tipados
- ✅ **Service Pattern:** Lógica de negócio isolada em Services
- ✅ **Repository Pattern:** Queries customizadas em Repositories
- ✅ **Dependency Injection:** Injeção via construtor
- ✅ **Form Type:** Formulários type-safe com Symfony Forms
- ✅ **Soft Delete:** Gedmo soft delete para exclusões lógicas
- ✅ **SOLID Principles:** Código orientado a princípios sólidos

---

## 🏗️ Arquitetura

### Padrão MVC + Service Layer

```
Request HTTP
     ↓
  Router (config/routes.yaml)
     ↓
Controller (ProcessRequest)
     ↓
  Service (LogicalBusiness)
     ↓
Repository (DataAccess)
     ↓
  Doctrine ORM
     ↓
  MySQL Database
     ↓
Response HTTP (View/Template)
```

### Service Layer: ChurchService

Exemplos de operações de negócio:

```php
// Criar nova igreja
$church = $service->createChurch($name, $city);

// Deletar com opções
$result = $service->deleteWithAction(
    church: $church,
    action: 'transfer',        // 'cascade', 'transfer', ou 'orphan'
    targetChurchId: $targetId  // Obrigatório se action='transfer'
);

// Transferir membros
$transferred = $service->transferMembers($fromChurch, $toChurch);
```

### Entity: Church (Igreja)

```php
class Church
{
    private ?int $id = null;
    private string $name;
    private string $city;
    private string $state;
    private \DateTime $createdAt;
    private ?\DateTime $deletedAt = null;  // Soft Delete
    
    /**
     * @var Collection<int, Member>
     */
    #[ORM\OneToMany(...)]
    private Collection $members;
}
```

### Entity: Member (Membro)

```php
class Member
{
    private ?int $id = null;
    private string $name;
    private string $cpf;
    private string $email;
    private string $city;
    private string $state;
    private ?\DateTime $deletedAt = null;  // Soft Delete
    
    #[ORM\ManyToOne(...)]
    private ?Church $church = null;
}
```

### Validadores Customizados

#### CPF Validator
```php
// Garante que o CPF seja válido e único
#[Assert\NotBlank]
#[Assert\Length(min: 11, max: 11)]
#[Cpf]  // Validador customizado
private string $cpf;
```

#### Email Validator
```php
// Garante email único no banco
#[Assert\Email]
#[Assert\Unique(entityClass: Member::class)]
private string $email;
```

---

## 🧪 Testes

### Executar Testes

```bash
# Rodar todos os testes
docker compose exec app php bin/phpunit

# Rodar apenas testes de Controller
docker compose exec app php bin/phpunit tests/Controller/

# Rodar com cobertura (gera relatório)
docker compose exec app php bin/phpunit --coverage-html var/coverage
```

### Estrutura de Testes

Todos os testes utilizam `WebTestCase` para testes de integração:

```php
class ChurchControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        // Limpar banco de dados
        // Criar fixtures
    }

    public function testIndexAction(): void
    {
        $client = static::createClient();
        $response = $client->request('GET', '/church');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table.table');
    }
}
```

---

## 📊 Comandos Úteis

### Console Symfony (bin/console)

```bash
# Listar todos os comandos
docker compose exec app php bin/console list

# Migrations
docker compose exec app php bin/console doctrine:migrations:migrate
docker compose exec app php bin/console doctrine:migrations:status
docker compose exec app php bin/console doctrine:migrations:rollback

# Database
docker compose exec app php bin/console doctrine:database:create
docker compose exec app php bin/console doctrine:database:drop
docker compose exec app php bin/console doctrine:schema:validate

# Cache
docker compose exec app php bin/console cache:clear
docker compose exec app php bin/console cache:warmup

# Assets
docker compose exec app php bin/console assets:install public

# Rotas
docker compose exec app php bin/console debug:router
docker compose exec app php bin/console debug:router app_church_index

# Services
docker compose exec app php bin/console debug:container
```

### Composer

```bash
# Instalar dependências
docker compose exec app composer install

# Atualizar dependências
docker compose exec app composer update

# Adicionar pacote
docker compose exec app composer require symfony/asset

# Remover pacote
docker compose exec app composer remove pacote/nome
```

### Code Quality

```bash
# Verificar estilo de código (PSR-12)
docker compose exec app ./vendor/bin/phpcs src/

# Corrigir estilo de código automaticamente
docker compose exec app ./vendor/bin/phpcbf src/

# PHPStan (análise estática)
docker compose exec app ./vendor/bin/phpstan analyse src/

# Rector (refactoring automático)
docker compose exec app ./vendor/bin/rector process src/ --dry-run
```

### Docker

```bash
# Ver containers rodando
docker compose ps

# Entrar no container da aplicação
docker compose exec app bash

# Entrar no MySQL
docker compose exec db mysql -u sail -ppassword

# Ver logs
docker compose logs

# Ver logs de um serviço específico
docker compose logs app
docker compose logs db

# Reiniciar containers
docker compose restart

# Parar e remover containers
docker compose down

# Parar, remover e deletar volumes (CUIDADO!)
docker compose down -v
```

---

## 🐛 Troubleshooting

### Problema: "SQLSTATE[HY000] [2002] Connection refused"

**Causa:** O Symfony está tentando conectar ao MySQL em `localhost` em vez do container.

**Solução:**
```bash
# Verifique o .env
# DATABASE_URL="mysql://sail:password@mysql:3306/..."
#                                    ↑
#                            Deve ser 'mysql', não 'localhost'

# Limpe o cache de configuração
docker compose exec app php bin/console cache:clear
```

### Problema: "Doctrine\ORM\ORMException: The EntityManager is closed"

**Causa:** EntityManager desconectou da base de dados.

**Solução:**
```bash
# Verifique se o MySQL está rodando
docker compose ps mysql

# Reinicie os containers
docker compose restart

# Verifique os logs
docker compose logs mysql
```

### Problema: Migrations não rodam

**Solução:**

```bash
# Verificar status das migrations
docker compose exec app php bin/console doctrine:migrations:status

# Se o banco não existe, criar
docker compose exec app php bin/console doctrine:database:create

# Rodar migrations
docker compose exec app php bin/console doctrine:migrations:migrate

# Se tiver conflitos, resetar (cuidado!)
docker compose exec app php bin/console doctrine:database:drop --force
docker compose exec app php bin/console doctrine:database:create
docker compose exec app php bin/console doctrine:migrations:migrate
```

### Problema: Soft Delete não funciona

**Verificação:**

```bash
# Entrar no MySQL
docker compose exec db mysql -u sail -ppassword desafio_inpeace

# Verificar se o campo deletedAt existe
SHOW COLUMNS FROM member WHERE Field = 'deleted_at';

# Se não existir, criar migração
docker compose exec app php bin/console make:migration AddDeletedAtToMember

# Depois rodar a migration
docker compose exec app php bin/console doctrine:migrations:migrate
```

### Problema: Formulário diz "The CSRF token is invalid"

**Causa:** Token CSRF expirou ou foi corrompido.

**Solução:**

1. Limpar cookies do navegador
2. Limpar cache Symfony:
```bash
docker compose exec app php bin/console cache:clear
```

3. Recarregar a página

### Problema: Upload de arquivo não funciona

**Verificação:**

```bash
# Verificar pasta de uploads
docker compose exec app ls -la public/uploads/

# Dar permissões se necessário
docker compose exec app chmod -R 755 public/uploads/

# Verificar configuração no .env
# UPLOAD_DIR=public/uploads/
```

### Problema: Assets (CSS/JS) não carregam

**Solução:**

```bash
# Instalar assets
docker compose exec app php bin/console assets:install public

# Se usar webpack/asset-mapper
docker compose exec app php bin/console asset-map:compile
```

---

## 📝 Padrão de Commits

```bash
# Feature
git commit -m "feat: adicionar validação de CPF"

# Bug fix
git commit -m "fix: corrigir erro na transferência de membros"

# Refactoring
git commit -m "refactor: extrair lógica de soft delete"

# Tests
git commit -m "test: adicionar testes para ChurchService"

# Docs
git commit -m "docs: atualizar README com instruções de setup"

# Chore
git commit -m "chore: atualizar dependências"
```

---

<p align="center">
Desenvolvido com 💚 por <strong>Gustavo</strong> | Desafio Backend InPeace
</p>
