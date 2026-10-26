# Hotel Villa di Rimini

Sistema web do **Hotel Villa di Rimini**: site institucional, pré-reserva de
acomodações, newsletter, avaliação de hóspedes e painel administrativo.

O projeto é todo em **português do Brasil** — textos, mensagens, domínio e
testes. A documentação detalhada (decisões, histórico e backlog) fica em
[`docs/HISTORICO.md`](docs/HISTORICO.md).

## Funcionalidades

- **Site público**: home, acomodações, páginas institucionais, blog/notícias,
  atendimento, política de privacidade, feed RSS e sitemap XML.
- **Pré-reserva**: valida o formulário, grava cliente e reserva e dispara
  e-mails para o hotel e para o hóspede.
- **Newsletter**: cadastro, reativação e validação com honeypot.
- **Avaliação da estadia**: questionário respondido pelo hóspede.
- **Painel administrativo** (AdminLTE): acomodações, reservas, posts e
  categorias, slides, parceiros, newsletter, avaliações, usuários e
  configurações gerais do site.
- **Google Analytics**: métricas no dashboard do painel.
- **Uploads no Cloudflare R2** em produção, com disco local em desenvolvimento.

## Stack

| Item | Versão / tecnologia |
| --- | --- |
| PHP | 8.3 |
| Laravel | 10.50 |
| Laravel Sail | 1.69 |
| PHPUnit | 10.5 |
| Banco local | MariaDB 10 (container) |
| Banco de testes | SQLite `:memory:` |
| Painel | AdminLTE 3 (`jeroennoten/laravel-adminlte`) |
| Front-end | Blade + Bootstrap 4 + jQuery + Laravel Mix |
| Storage de produção | Cloudflare R2 (compatível com S3) |
| Deploy | Coolify + railpack (FrankenPHP) |

## Requisitos

- **Docker** e **Docker Compose** (recomendado; o ambiente é o Laravel Sail).
- **Composer 2** com PHP 8.3 e as extensões `exif` e `gd` — necessárias para
  instalar as dependências antes de subir os containers.
- **Node.js 18+** e **npm** apenas para recompilar os assets do Mix.

## Como rodar

### 1. Dependências e `.env`

```bash
git clone git@github.com:informaticalivreoficial/villa-rimini.git
cd villa-rimini
cp .env.example .env
composer install
```

Edite o `.env` conforme a seção [Configuração](#configuração). Para o painel,
preencha `ADMIN_NOME`, `ADMIN_EMAIL` e `ADMIN_PASS` **antes** de rodar os
seeders — o usuário administrador inicial é criado a partir dessas variáveis.

### 2. Containers, banco e storage

```bash
vendor/bin/sail up -d
vendor/bin/sail artisan key:generate
vendor/bin/sail artisan migrate --seed
vendor/bin/sail artisan storage:link
```

### 3. Front-end

Os assets do tema público ficam em `public/frontend` (versionados). Para
alterar os arquivos de `resources/js` ou `resources/sass` (Mix):

```bash
vendor/bin/sail npm install
vendor/bin/sail npm run dev
```

### 4. Acessos locais

| Serviço | URL |
| --- | --- |
| Site | <http://laravel.test> |
| Painel | <http://laravel.test/admin> |
| phpMyAdmin | <http://localhost:8080> |
| Mailpit (e-mails de teste) | <http://localhost:8025> |

## Configuração

O `.env` **não é versionado** e contém credenciais reais. Use o
[`.env.example`](.env.example) como base e nunca copie valores para issues,
logs ou documentação.

| Grupo | Variáveis | Observações |
| --- | --- | --- |
| Aplicação | `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_DEBUG`, `APP_URL` | Em produção, `APP_URL` deve ser a URL pública com HTTPS. |
| Banco | `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | No Sail, use `DB_HOST=mariadb`. |
| E-mail | `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS` | Localmente o Mailpit captura as mensagens. |
| Storage | `FILESYSTEM_DISK`, `R2_*` | `FILESYSTEM_DISK=local` ou `public` no desenvolvimento; `r2` em produção. |
| Analytics | `ANALYTICS_PROPERTY_ID`, `ANALYTICS_CREDENTIALS_BASE64` | JSON da service account em base64; `ANALYTICS_CREDENTIALS_JSON` e arquivo local são fallbacks. |
| Admin inicial | `ADMIN_NOME`, `ADMIN_EMAIL`, `ADMIN_PASS` | Usados pelo `UsersTableSeeder`. |
| Imagens | `LOGOMARCA_*`, `FAVEICON_*`, `MARCADAGUA_*`, `IMGHEADER_*`, `METAIMG_*` | Dimensões usadas no processamento de uploads. |

### Cloudflare R2

Em produção, `FILESYSTEM_DISK=r2`. O banco guarda apenas o caminho relativo ao
prefixo do projeto (`slides/foto.jpg`), e o Laravel monta a chave no bucket com
`R2_ROOT` (`villadirimini/slides/foto.jpg`). O bucket é **compartilhado com
outros projetos** — nunca deixe `R2_ROOT` vazio.

- O sitemap é gravado no disco padrão e servido em `/sitemap`.
- `App\Support\Media::exists()` cacheia as checagens de existência por 6 h para
  evitar `HeadObject` no R2 a cada imagem renderizada.
- Credenciais de service account nunca devem ficar em `storage/app/public` nem
  no bucket.

Detalhes completos em [`docs/HISTORICO.md`](docs/HISTORICO.md#cloudflare-r2).

## Testes

```bash
vendor/bin/sail test
# ou
vendor/bin/sail artisan test
```

A suíte roda com **SQLite em memória** (`phpunit.xml`); os testes de feature
usam `RefreshDatabase`, `FILESYSTEM_DISK=local` e `MAIL_MAILER=array`, portanto
**não tocam no banco nem no R2 de produção**. Use `Storage::fake()` e
`Mail::fake()` nos testes que envolvem arquivos e e-mails.

Cobertura atual: helpers globais, modelos/scopes, home, acomodações,
pré-reserva, páginas institucionais, RSS, `fetchCity`, newsletter, criação de
reserva com e-mails e controle de acesso ao painel.

## Estrutura

```text
app/
├── Http/Controllers/{Admin,Auth,Web}/   # painel, login e site público
├── Http/Requests/                       # validações
├── Models/                              # Eloquent
├── Repositories/                        # acesso a dados
├── Services/                            # Config, Cidade, Estado, User
├── Support/                             # Seo, Cropper, Media
├── Helpers/                             # funções globais
└── Mail/                                # e-mails transacionais

database/
├── migrations/                          # schema
├── seeders/                             # estados, cidades, config, admin, posts
└── factories/

resources/views/
├── web/default/                         # tema público
├── admin/                               # telas do painel
└── emails/                              # templates de e-mail

routes/web.php                           # rotas do site, painel e auth
tests/{Unit,Feature}/                    # PHPUnit
docs/HISTORICO.md                        # histórico, decisões e backlog
```

## Rotas principais

| Rota | Descrição |
| --- | --- |
| `/` | Home |
| `/acomodacoes`, `/acomodacao/{slug}` | Lista e detalhe das acomodações |
| `/reservar` | Formulário de pré-reserva |
| `/blog`, `/blog/artigo/{slug}`, `/blog/categoria/{slug}`, `/blog/pesquisar` | Blog |
| `/noticias`, `/noticia/{slug}` | Notícias |
| `/atendimento`, `/politica-de-privacidade`, `/pesquisa`, `/avaliacao` | Páginas institucionais |
| `/feed`, `/sitemap` | RSS e sitemap XML |
| `/fetchCity` | Cidades por estado (AJAX) |
| `/login`, `/admin` | Autenticação e painel (requer `auth`) |

## Deploy

Produção no **Coolify** com build pack **railpack** (FrankenPHP PHP 8.3.36),
branch `main`, porta `80` e `post_deployment_command`:

```bash
php artisan storage:link --force
```

No boot, o railpack executa `php artisan migrate --force`,
`storage:link`, `optimize:clear` e `optimize`. O build roda `config:cache`
antes de o MySQL interno estar acessível — por isso o `AppServiceProvider`
tolera falha de conexão nesse momento. O `nixpacks.toml` é legado e não é
usado pelo railpack.

Procedimento operacional, variáveis de produção e pendências de infraestrutura
estão em [`docs/HISTORICO.md`](docs/HISTORICO.md#deploy-em-produção-coolify).

## Convenções

- Código, comentários, mensagens e commits em **português do Brasil**.
- Commits no padrão **Conventional Commits** (`feat:`, `fix:`, `docs:`,
  `perf:`, `chore:`).
- Rode a suíte de testes antes de commitar.
- Atualize `docs/HISTORICO.md` (diário de bordo e backlog) ao encerrar uma
  tarefa relevante.
- Não versione `.env`, credenciais ou arquivos de service account.

---

Projeto privado do Hotel Villa di Rimini.
