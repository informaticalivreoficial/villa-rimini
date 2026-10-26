# Histórico do Projeto — Hotel Villa di Rimini

> Documento vivo para acompanhar a evolução do sistema. Atualize a seção
> **Diário de bordo** ao final de cada sessão de trabalho e revise o **Backlog**
> antes de começar uma nova tarefa.
>
> Última atualização: **2026-10-26**
> Branch principal: `main`
> Último commit analisado: `2315ea2` (`update footer`)

---

## 1. Visão geral

Sistema web do **Hotel Villa di Rimini**, com:

- site institucional público (home, acomodações, páginas, blog/notícias,
  atendimento, política de privacidade, feed RSS e sitemap);
- formulário de pré-reserva que cria cliente e reserva e dispara e-mails;
- captação de newsletter e avaliações de hóspedes;
- painel administrativo (AdminLTE) para acomodações, reservas, posts,
  categorias, slides, parceiros, newsletter, avaliações, usuários e configurações;
- geração de sitemap XML e integração com Google Analytics.

O idioma do projeto, dos nomes de domínio, das mensagens e dos testes é
**português do Brasil**.

---

## 2. Stack e ambiente

| Item | Versão atual |
| --- | --- |
| PHP | 8.3 (Sail 8.3 e Nixpacks 8.3.36) |
| Laravel | 10.50.3 |
| Laravel Sail | 1.69.0 |
| PHPUnit | 10.5.66 |
| Banco local | MariaDB 10 (container) |
| Banco de testes | SQLite `:memory:` |
| AdminLTE | `jeroennoten/laravel-adminlte` 3.16.0 |
| Front-end legado | Blade + Bootstrap 4 + jQuery + Laravel Mix |
| Storage de produção | Cloudflare R2 (`infolivre-public`, prefixo `villadirimini/`) |

### Subir o ambiente com Sail

```bash
vendor/bin/sail up -d
vendor/bin/sail artisan migrate
vendor/bin/sail npm install
vendor/bin/sail npm run dev
```

Acessos padrão:

- site: <http://laravel.test>
- painel: <http://laravel.test/admin>
- phpMyAdmin: <http://localhost:8080>
- Mailpit: <http://localhost:8025>

### Rodar os testes

```bash
vendor/bin/sail artisan test
# ou
vendor/bin/sail test
```

Os testes usam SQLite em memória (`phpunit.xml`), `RefreshDatabase` e
`FILESYSTEM_DISK=local`, portanto **não tocam no banco nem no R2 de produção**.
Use `Storage::fake()` nos testes que envolvem arquivos.

### Observações de ambiente

- O `vendor/` estava com dono `root`; em 2026-10-26 foi corrigido para o UID
  1000 para permitir que `vendor/bin/sail composer` grave arquivos.
- O `docker-compose.yml` usa o runtime Sail 8.3 (`sail-8.3/app`).
- O deploy via Nixpacks fixa PHP 8.3.36 e usa `composer install
  --ignore-platform-reqs`; o front-end compilado (`public/build`) é versionado.
- O arquivo `.env` **não é versionado** e contém credenciais reais. Nunca
  copie o conteúdo para issues, logs ou documentação.

### Cloudflare R2

O disco padrão de uploads é definido por `FILESYSTEM_DISK`:

- produção/homologação: `FILESYSTEM_DISK=r2`;
- desenvolvimento local: `FILESYSTEM_DISK=public` ou `local`.

Configuração do disco `r2` em `config/filesystems.php`:

| Variável | Uso |
| --- | --- |
| `R2_ACCOUNT_ID` | monta o endpoint S3 `https://<id>.r2.cloudflarestorage.com` |
| `R2_ACCESS_KEY_ID` / `R2_SECRET_ACCESS_KEY` | credenciais do token R2 |
| `R2_BUCKET` | bucket (atual: `infolivre-public`) |
| `R2_REGION` | `auto` |
| `R2_URL` | domínio público do bucket, sem barra final |
| `R2_ROOT` | prefixo do projeto dentro do bucket (atual: `villadirimini`) |
| `R2_THROW` | `true` para lançar exceção em falha de upload |

**Convenção de caminhos:** o banco guarda apenas o caminho relativo ao
`R2_ROOT` (`slides/foto.jpg`, `apartamentos/2/foto.jpg`,
`configuracoes/metaimg-....png`). O Laravel prefixa automaticamente com
`R2_ROOT`, gerando chaves como `villadirimini/slides/foto.jpg` e URLs como
`https://<R2_URL>/villadirimini/slides/foto.jpg`.

Pontos de atenção:

- O bucket é compartilhado com outros projetos (`imobiliaria-ubatuba`,
  `superpasseios`, `ubatubatimes` etc.); nunca use `R2_ROOT` vazio.
- O sitemap é gravado no disco padrão e servido por `/sitemap`; o caminho
  relativo fica em `configuracoes.sitemap`.
- Miniaturas locais (`App\Support\Cropper`) só são geradas quando o arquivo
  existe em `storage/app/public`; com R2 o caminho original é retornado.
- Verificações de existência de arquivo passam por `App\Support\Media::exists()`,
  que guarda o resultado em cache por 6 h. Sem isso, cada `cover()` fazia um
  `HeadObject` no R2 (~0,3 s) e a home levava ~4 s para responder.
- Credenciais do Google Analytics ficam em `ANALYTICS_CREDENTIALS_BASE64` no
  `.env` (JSON em base64). O arquivo
  `storage/app/analytics/service-account-credentials.json` continua aceito como
  fallback, mas nunca deve ficar em `storage/app/public` nem no R2.

---

## 3. Mapa do código

```text
app/
├── Http/Controllers/
│   ├── Admin/        # painel (posts, reservas, usuários, configurações etc.)
│   ├── Auth/         # login, registro, reset de senha (Laravel UI)
│   └── Web/          # site público, envio de e-mails e RSS
├── Http/Requests/    # validações do painel
├── Models/           # Eloquent (Apartamento, Reservas, Post, User, ...)
├── Repositories/     # acesso a dados (User, Config, Cidade, Estado)
├── Services/         # regras de negócio simples (Config, Cidade, Estado, User)
├── Support/          # Seo, Message, Cropper
├── Helpers/          # funções globais e utilitários (Renato, WhatsApp, Cidade)
└── Mail/             # e-mails transacionais (Web e Admin)

database/
├── migrations/       # schema (inclui a migration nova do campo posts.menu)
├── seeders/          # estados, cidades, config, usuário admin, posts
└── factories/        # UserFactory

resources/views/
├── web/default/      # tema público
├── admin/            # telas do painel
├── vendor/adminlte/  # customizações do AdminLTE
└── emails/           # templates de e-mail

routes/web.php        # todas as rotas web (site + admin + auth)
tests/                # testes Unit e Feature
```

### Entidades principais

- **User**: cliente, admin, editor e superadmin; campos de perfil, endereço,
  cônjuge e redes sociais; senha legada (`senha`) + hash (`password`).
- **Apartamento** + **ApartamentoGb**: acomodações e galeria de imagens.
- **Reservas**: cliente, apartamento, período, adultos, crianças, código,
  valor e status (1 = pendente, 0 = finalizada).
- **Post** + **CatPost** + **PostGb**: páginas, artigos e notícias, categorias
  e galeria.
- **Slide**, **Parceiro** + **ParceiroGb**: home e parceiros.
- **Newsletter** + **NewsletterCat**: listas e contatos.
- **Avaliacoes**: questionário pós-estadia.
- **Configuracoes**: registro único (`id = 1`) com dados do site, SEO, SMTP,
  contatos, redes e template.
- **Estados** e **Cidades**: estados/cidades brasileiros (chaves primárias
  customizadas: `estado_id` e `cidade_id`).

### Rotas públicas usadas pelo site

- `/` — home
- `/acomodacoes` e `/acomodacao/{slug}` — acomodações
- `/reservar` — formulário de pré-reserva
- `/atendimento`, `/politica-de-privacidade`, `/pesquisa`, `/avaliacao`
- `/feed` — RSS
- `/fetchCity` — cidades por estado (AJAX)
- `/sendEmail`, `/sendNewsletter`, `/acomodacaoSend`, `/avaliacaoSend` — envios

### Painel

O grupo `/admin` exige autenticação (`auth`). O dashboard fica em `/admin` e as
telas seguem o padrão AdminLTE (`adminlte::page`).

---

## 4. Estado atual (2026-10-26)

### O que foi feito nesta sessão

1. **Laravel atualizado dentro da série 10**
   - `laravel/framework`: `v10.48.24` → **`v10.50.3`** (última 10.x disponível).
   - `composer.json`: PHP mínimo ajustado de `^8.1` para **`^8.3`**, coerente
     com o Sail 8.3, o Nixpacks e os pacotes atualizados que exigem PHP 8.2+.
   - Dependências indiretas atualizadas (Symfony, Flysystem, Monolog, Carbon,
     AWS SDK, Google Analytics etc.) via `composer update --with-all-dependencies`.

2. **Pacotes diretos atualizados sem trocar de major**
   - `laravel/sail` 1.38.0 → **1.69.0**
   - `laravel/tinker` 2.10.0 → **2.11.1**
   - `laravel/ui` 4.5.2 → **4.6.3**
   - `jeroennoten/laravel-adminlte` 3.14.2 → **3.16.0**
   - `spatie/laravel-analytics` 5.4.0 → **5.6.0**
   - `spatie/laravel-ignition` 2.8.0 → **2.9.1**
   - `league/flysystem-aws-s3-v3` 3.29.0 → **3.35.3**
   - `guzzlehttp/guzzle` 7.9.2 → **7.16.1**
   - `phpunit/phpunit` 10.5.38 → **10.5.66**
   - `mockery/mockery` 1.6.12 → **1.6.15**
   - `nunomaduro/collision` 7.11.0 → **7.12.0**

   As majors novas disponíveis **não** foram aplicadas de propósito:
   Laravel 11/12/13, Sanctum 4, Tinker 3, Collision 8, PHPUnit 12,
   AdminLTE 4, Intervention Image 4, Sitemap 8, Guzzle 8 e localização pt-BR 3.
   Todas exigem migração planejada e branch própria.

3. **Correções necessárias para o projeto funcionar em banco limpo**
   - `AppServiceProvider` agora verifica `Schema::hasTable()` antes de consultar
     newsletter, páginas e configurações no boot. Isso permite rodar
     `migrate`/testes em um banco vazio sem erro de tabela inexistente.
   - Nova migration `2026_10_26_000000_add_menu_to_posts_table.php`: o código
     usava `posts.menu`, mas a migration original não criava a coluna. A
     migration é idempotente (`Schema::hasColumn`) e não altera bancos que já
     possuem o campo.
   - `Post`: `menu` adicionado ao `$fillable`.
   - `Cidades` e `Estados`: definidas as chaves primárias corretas
     (`cidade_id` e `estado_id`), corrigindo `create()` e relações.
   - `Cidades::estado()` corrigido para `belongsTo(Estados::class, 'estado_id', 'estado_id')`.
   - `Reservas`: criado o método `convertStringToDouble()` que o mutator de
     `valor_venda` já chamava, mas não existia.

4. **Testes**
   - Suíte reescrita para Laravel/PHPUnit 10 e SQLite em memória.
   - `phpunit.xml` migrado para o schema 10.5.
   - **46 testes / 129 asserções passando** em 2026-10-26.
   - Cobertura funcional: helpers globais, modelos, scopes, home, acomodações,
     detalhe da acomodação, pré-reserva, páginas institucionais, RSS,
     `fetchCity`, newsletter (novo e-mail, reativação, validação, honeypot),
     criação de reserva com e-mails fake e controle de acesso ao painel.

5. **Limpeza**
   - Testes de exemplo removidos.
   - `.phpunit.cache` adicionado ao `.gitignore`.

6. **Migração do storage para Cloudflare R2**
   - Disco `r2` configurado em `config/filesystems.php` com endpoint,
     domínio público, prefixo do projeto e `throw` habilitado.
   - `FILESYSTEM_DISK` passa a ser a variável oficial (fallback para o antigo
     `FILESYSTEM_DRIVER`).
   - Removidos os resquícios de `AWS_PASTA` dos uploads; os caminhos agora são
     relativos ao `R2_ROOT`.
   - 67 caminhos de imagem do banco foram normalizados para caminhos relativos
     (`villarimini/...` → `...`) e todos foram conferidos no R2.
   - Views e `ConfigService` deixaram de checar `public_path()`/`File::exists()`
     e passaram a usar `Storage`.
   - Sitemap passou a ser gravado no disco padrão e servido por `/sitemap` a
     partir do storage, sem `file_get_contents()` de URL externa.
   - `Cropper` virou seguro para discos remotos (retorna o original).
   - Credenciais do Google Analytics removidas da área pública e do bucket
     (estavam acessíveis publicamente) e movidas para
     `ANALYTICS_CREDENTIALS_BASE64` no `.env` (JSON em base64).

### Arquivos criados/alterados nesta sessão

- `composer.json`, `composer.lock`
- `phpunit.xml`
- `app/Providers/AppServiceProvider.php`
- `app/Models/Post.php`, `Reservas.php`, `Cidades.php`, `Estados.php`
- `database/migrations/2026_10_26_000000_add_menu_to_posts_table.php`
- `tests/Concerns/CreatesTestData.php`
- `tests/Unit/Models/UserTest.php`
- `tests/Unit/Support/HelpersTest.php`
- `tests/Feature/AdminAccessTest.php`, `ModelScopesTest.php`,
  `NewsletterTest.php`, `PublicPagesTest.php`, `ReservationTest.php`,
  `FilesystemTest.php`
- `config/filesystems.php`, `config/analytics.php`, `.env.example`
- `app/Services/ConfigService.php`, `app/Support/Cropper.php`
- `app/Http/Controllers/Admin/SitemapController.php`,
  `app/Http/Controllers/Web/{WebController,ClienteController}.php`
- `app/Http/Controllers/Admin/{Apartamento,Parceiro,Post,Slide,Template,User,Config}Controller.php`
- `resources/views/admin/configuracoes.blade.php`,
  `resources/views/admin/users/{create,show,edit}.blade.php`
- `.gitignore`
- `.opencode/agents/villa-rimini.md` (agente local, ignorado pelo Git)
- `docs/HISTORICO.md` (este arquivo)

---

## 5. Decisões tomadas

1. **Permanecer no Laravel 10 nesta etapa.** O pedido foi atualizar a última
   10.x, não trocar de série. A migração para 11/12/13 exige plano próprio,
   revisão de depreciações e atualização de pacotes com majors incompatíveis.
2. **Atualizar somente dentro das constraints atuais.** `composer update
   --with-all-dependencies` foi usado para trazer correções de segurança e bugs
   sem quebrar APIs.
3. **Testes isolados em SQLite em memória.** Mais rápidos, sem dependência do
   MariaDB e sem risco de apagar dados locais.
4. **Corrigir apenas o que impedia o ambiente limpo/testes.** As correções de
   schema e de modelos foram mínimas e compatíveis com o banco existente.
5. **Commits por assunto.** O trabalho foi separado em commits de dependências,
   correções, testes, documentação e deploy; a integração com o R2 tem commit
   próprio.
6. **Caminhos relativos ao prefixo do projeto no R2.** O banco guarda
   `slides/foto.jpg` e o disco prefixa com `R2_ROOT` (`villadirimini/`). Assim o
   mesmo banco funciona com `public` local e com R2 em produção.

---

## 6. Dívidas técnicas e riscos conhecidos

### Alta prioridade

- **Laravel 10 está fora do ciclo de suporte.** O `composer audit` aponta
  **11 advisories em 2 pacotes**. A maior parte vem de
  `spatie/browsershot 3.61.0` (via `spatie/crawler 7.1.3` →
  `spatie/laravel-sitemap 6.4.0`). Corrigir exige subir Sitemap/Crawler/Browsershot.
  Planejar upgrade para Laravel 11/12/13.
- **Rotas de blog/notícias sem métodos no controller.** As rotas
  `web.blog.artigos`, `web.blog.artigo`, `web.blog.categoria`,
  `web.blog.searchBlog`, `web.noticias` e `web.noticia` apontam para métodos
  que não existem em `Web\WebController` (o controller só tem home, página,
  acomodações, pesquisa, atendimento, política, avaliação, sitemap e
  `fetchCity`). Essas URLs retornam erro. Confirmar se são funcionalidades
  ativas e implementar ou remover as rotas.
- **CRUD de reservas no painel incompleto.** `ReservaController` só possui
  `pendentes`, `finalizadas`, `create` e `edit`; as rotas de `update`, `delete`,
  `deleteon` e o fluxo de criação não têm implementação correspondente.
- **Credenciais do Google Analytics estavam públicas.** O arquivo
  `service-account-credentials.json` estava em `storage/app/public/villarimini/`
  e no bucket R2 com acesso público. Foi removido em 2026-10-26, mas a chave da
  service account **deve ser rotacionada** no Google Cloud.
- **Segredos no `.env` local** (SMTP, admin, R2, GitHub). O arquivo é ignorado
  pelo Git, mas as credenciais devem ser rotacionadas se já circularam.
- **Bucket R2 compartilhado e público.** `R2_URL` expõe todo o bucket
  (`imobiliaria-ubatuba`, `superpasseios`, `ubatubatimes` etc.). Avaliar um
  domínio próprio por projeto ou um Worker com controle de acesso. Nunca deixe
  `R2_ROOT` vazio.
- **`werneckbh/laravel-qr-code` está abandonado** (sem substituto sugerido).
  Avaliar remoção ou troca.

### Média prioridade

- `AdminController@home` chama o Google Analytics diretamente. Sem credenciais
  válidas o dashboard pode falhar; envolver em cache/try-catch ou tornar a
  exibição opcional.
- `AppServiceProvider` ainda consulta o banco no boot para compartilhar dados
  com as views (agora com guarda). Considerar view composer com cache ou
  `View::share` somente quando necessário.
- `coffeecode/optimizer` está fixado em `2.1.1` (versão exata). Avaliar caret
  quando houver atualização compatível.
- Deploy: o workflow antigo rodava `route:cache` e depois `optimize:clear`
  (que limpa o cache de rotas). Revisar a ordem. O workflow está removido na
  working tree; confirmar a estratégia de deploy (Nixpacks/Coolify).

### Baixa prioridade

- Front-end legado (Bootstrap 4/jQuery/Mix) e assets compilados versionados.
- Código com muitos acessores/mutadores manuais; avaliar casts do Eloquent.
- Falta de padronização (Pint/PHPStan) e de CI executando a suíte.

---

## 7. Backlog priorizado

### P0 — próximo ciclo

- [x] Revisar e commitar as mudanças desta sessão.
- [ ] **Rotacionar a chave da service account do Google Analytics** (arquivo
      esteve público no R2 e em `storage/app/public`).
- [ ] Implementar ou remover as rotas de blog/notícias sem controller.
- [ ] Completar o CRUD de reservas no painel.
- [ ] Rotacionar credenciais expostas no `.env` local.
- [ ] Definir plano de upgrade para Laravel 11/12/13 (com testes e branch).

### P1 — curto prazo

- [ ] Adicionar CI (GitHub Actions) rodando `composer install` + `artisan test`.
- [ ] Tratar falhas do Google Analytics no dashboard.
- [ ] Substituir/remover `werneckbh/laravel-qr-code`.
- [ ] Atualizar Sitemap/Crawler/Browsershot para eliminar advisories.
- [ ] Adicionar testes para o painel (posts, usuários, configurações) incluindo
      upload/exclusão de imagens no disco `local`.
- [ ] Testar o ciclo completo de upload pelo painel contra o R2
      (configurações, slide, apartamento, post e usuário).
- [ ] Adicionar Pint e PHPStan em nível inicial.

### P2 — médio prazo

- [ ] Avaliar PHP 8.4 e Laravel 12/13.
- [ ] Modernizar front-end (Vite/Tailwind) sem quebrar o tema atual.
- [ ] Revisar SEO, acessibilidade e desempenho.
- [ ] Documentar procedimento de deploy e backup do banco.

---

## 8. Diário de bordo

### 2026-10-26 — Atualização Laravel 10 + suíte de testes

**Objetivo:** criar agente do projeto, histórico, atualizar Laravel 10 e pacotes
sem quebrar funcionalidades e criar testes.

**Executado:**

- Levantamento da estrutura, rotas, modelos, migrations, Sail e testes.
- `composer update --with-all-dependencies` dentro do Sail.
- Laravel 10.50.3, Sail 1.69.0 e demais pacotes atualizados dentro das majors.
- Ajuste do `composer.json` para PHP `^8.3`.
- Correção de `vendor/` root-owned para UID 1000.
- Correções de schema/modelos descritas na seção 4.
- `phpunit.xml` migrado para PHPUnit 10.5 e SQLite em memória.
- 40 testes criados/executados com sucesso.
- `migrate --force` executado no MariaDB local; migration do `menu` registrada.
- Smoke test HTTP: `/`, `/acomodacoes`, `/atendimento`,
  `/politica-de-privacidade`, `/reservar`, `/feed` e `/login` retornando 200.
- `route:cache` e `view:cache` validados (e limpos em seguida).

**Validação:**

```text
Tests: 40 passed (117 assertions)
Duration: ~2,3s
Laravel Framework 10.50.3
```

**Pendências imediatas:** revisar diff, commitar em branch, planejar upgrade de
série e corrigir as rotas de blog/notícias e o CRUD de reservas.

### 2026-10-26 — Migração do storage para Cloudflare R2

**Objetivo:** usar o R2 da Cloudflare para os uploads do site.

**Executado:**

- Conferência do bucket `infolivre-public`: os arquivos do hotel estavam no
  prefixo `villadirimini/` (70 objetos).
- `R2_ROOT` ajustado de `superpasseios` (prefixo de outro projeto) para
  `villadirimini`.
- 67 caminhos de imagem do banco normalizados para caminhos relativos.
- Verificação de todos os 67 caminhos no R2: **0 faltando**.
- Removidos os usos de `AWS_PASTA`; novos uploads gravam caminhos relativos.
- Views e `ConfigService` migrados de `public_path()`/`File::exists()` para
  `Storage`.
- Sitemap gerado no R2 (`villadirimini/hotel-villa-di-rimini_sitemap.xml`) e
  servido em `/sitemap` com HTTP 200.
- `FilesystemTest` adicionado (disco padrão, URL do R2, upload, metaimg e
  sitemap).
- Credenciais do Google Analytics removidas do acesso público e do bucket e
  passadas para `ANALYTICS_CREDENTIALS_BASE64` no `.env` (config aceita base64,
  JSON em linha ou arquivo de fallback).

**Validação:**

```text
Tests: 46 passed (129 assertions)
home: 200 (11 imagens servidas pelo R2)
sitemap: 200 application/xml
caminhos no R2: 67/67
```

**Pendências imediatas:** rotacionar a chave da service account do Google,
testar upload real pelo painel e definir domínio próprio/controle de acesso ao
bucket.

---

## 9. Checklist para a próxima sessão

- [ ] Ler este histórico e o backlog.
- [ ] `git status` para identificar trabalho não commitado.
- [ ] `vendor/bin/sail up -d`.
- [ ] `vendor/bin/sail artisan test` antes de qualquer alteração.
- [ ] Escolher um item do P0/P1 e criar branch.
- [ ] Ao final, adicionar uma entrada no diário de bordo e atualizar o estado.
