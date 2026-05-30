# AGENTS.md

## 1. Project overview

This repository is a small PHP application for the daleGig follow-up workflow. It is focused on operational communication with GIGs/venues after proposals are sent.

The main entry point is `followup.php`, which:
- authorizes access through a fixed `token` in the query string
- reads pending follow-up cards from the `dalegig` database
- shows operator instructions for contact attempts
- records outcomes back into the databases
- may redirect the operator to helper pages such as `inserir_gerente.php`

This is not a modern framework app. It is a procedural PHP codebase with server-rendered HTML, direct SQL strings, and static assets.

## 2. Tech stack detected from the repository

- PHP application using `.php` pages in the web root
- MySQL access via `mysqli`
- HTML rendered directly in PHP files
- CSS generated from SCSS sources, but the build pipeline is not included in this repo
- Vendor frontend assets committed to the repo
- Apache/cPanel deployment clues via [`htaccess`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/htaccess)

Detected frontend/vendor clues:
- Bootstrap-based admin template assets under `vendors/`
- Material Design Icons
- Chart.js and related plugins
- jQuery-based template scripts
- SCSS sources reference `bootstrap` and `compass-mixins` from `node_modules`, but no Node manifest is present in this repo

## 3. Repository structure

- [`followup.php`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/followup.php): main operator dashboard and most business logic
- [`premium.php`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/premium.php): alternate access flow for premium users
- [`atualizar_gig.php`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/atualizar_gig.php): update producer contact data
- [`desativar_gig.php`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/desativar_gig.php): disable a GIG / contractor
- [`followup_reenvia_proposta.php`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/followup_reenvia_proposta.php): resend proposal instructions/content
- [`followup_preview.php`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/followup_preview.php): preview proposal email body
- [`inserir_gerente.php`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/inserir_gerente.php): manual instructions for entering feedback in another daleGig system
- [`CLASSES/conexao.php`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/CLASSES/conexao.php): database connections
- [`partials/`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/partials): shared navbar/footer partials
- [`scss/`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/scss): SCSS source files
- [`css/`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/css): compiled CSS committed to repo
- [`js/`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/js): frontend scripts, mostly template/dashboard behavior
- [`vendors/`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/vendors): committed third-party assets

## 4. Install/setup commands

- Install dependencies: Not found in repo
- Environment bootstrap: Not found in repo
- Composer install: Not found in repo
- npm/yarn/pnpm install: Not found in repo

Manual facts discovered from code:
- the app expects three MySQL databases configured directly in `CLASSES/conexao.php`
- there is no `.env.example` or env-based configuration in this repo

## 5. Development commands

- Development server command: Not found in repo
- Hot reload/watch command: Not found in repo

## 6. Build/test/lint/format commands

- Build command: Not found in repo
- Test command: Not found in repo
- Lint command: Not found in repo
- Format command: Not found in repo

Commands actually run successfully during inspection:
- `php -v`
- `php -l followup.php`
- `php -l atualizar_gig.php`
- `php -l desativar_gig.php`
- `php -l followup_reenvia_proposta.php`
- `php -l followup_preview.php`
- `php -l inserir_gerente.php`
- `php -l premium.php`
- `php -l CLASSES/conexao.php`

## 7. Database or migration notes

No migration system was found in the repo.

The code directly references multiple existing tables across at least three databases:
- `hg2dl269_gig_dalegig`
- `hg2dl269_dalegig`
- `hg2dl269_intranet_2dlpro`

Examples of tables referenced by the app:
- `followup_cards`
- `followup_saldo`
- `log_followup`
- `tours`
- `tours_gigs`
- `send_email_box`
- `artistas`
- `chat_conversa_proposta`
- `cadastro_venue`
- `banco_b_contratantes`
- `saldo`

If schema changes are needed, inspect live database structure first. Nothing in this repo documents schema evolution.

## 8. Deployment notes

Deployment clues found in [`htaccess`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/htaccess):
- Apache rewrite rules are in use
- production host appears to be `followup.dalegig.com`
- HTTPS redirection is configured
- cPanel-generated PHP handler is configured for `ea-php74`

HostGator FTP notes discovered during deploy:
- HostGator document root for production: `/home1/hg2dl269/followup.dalegig.com`
- FTP username: `deploy@followup.dalegig.com`
- `ftp.2dlproductions.com` did not resolve from this environment; `followup.dalegig.com` / `ftp.dalegig.com` reached the same FTP account.
- Use explicit FTPS on port 21. The certificate may not match the FTP hostname, so `curl --ssl-reqd --insecure` was needed locally.
- Do not store the FTP password in this repository or in AGENTS.md.
- Important: the HostGator production tree currently does not match this local repository. It has files such as `index.php`, `gig_edit.php`, `gigs_search.php`, `CLASSES/helpers.php`, and `CLASSES/estimate_engine.php`, while this repo has `followup.php`, template `vendors/`, and `partials/`. Verify the remote production tree before uploading files.

Important compatibility note:
- local syntax checks were run with PHP 8.0.30
- repo deployment clue points to PHP 7.4 in production
- keep PHP 7.4 compatibility in mind unless production is known to have changed

## 9. Coding conventions observed in the repo

- Procedural PHP in top-level page files
- Business logic, SQL, HTML, and redirects are mixed in the same file
- Database access is done with raw SQL strings and `mysqli_query`
- Input is read directly from `$_GET` and `$_POST`
- Escaping is usually done with `addslashes`, not prepared statements
- Shared layout is included with `include("partials/...")`
- Inline HTML generation with large `echo` blocks is common
- UI styling reuses the committed template assets in `vendors/`, `css/`, and `js/`

When editing:
- preserve the current flow unless the task explicitly authorizes a deeper refactor
- prefer minimal, localized changes
- keep redirects, token checks, and operator messaging behavior intact unless intentionally changing workflow

## 10. Security and safety rules

- Treat `CLASSES/conexao.php` as sensitive. It contains real hardcoded database credentials in this checkout.
- Do not paste, expose, rotate, or rewrite credentials unless the user explicitly asks for credential handling work.
- Do not log secrets, tokens, raw connection strings, or copied database content into new files.
- Be careful with GET-based access gates. Several pages trust query parameters such as `token`, `id_gig`, `tour`, and `id_email_sendbox`.
- Assume production data is live and operational. Many forms write directly to business tables.
- Prefer syntax checks and read-only inspection before any write-path changes.

## 11. Do not do

- Do not invent a missing framework, package manager, or build pipeline.
- Do not claim there is Composer, npm, tests, migrations, CI, or Docker support unless you add it explicitly and document that it was newly introduced.
- Do not edit `vendors/` unless the task is specifically about third-party assets.
- Do not change `css/style.css` and `scss/` independently without noting which file is source of truth for the task.
- Do not change SQL table/column names based on guesswork. Verify against all call sites first.
- Do not assume this folder is a git repository. `git status` failed because `.git` is not present here.
- Do not remove or rename helper pages that operational staff may open directly.

## 12. Known unclear areas / verify before changing

- Real local development/start command: Not found in repo
- Real SCSS compilation command and toolchain: Not found in repo
- Whether `css/style.css` is the deployed source of truth or always regenerated elsewhere
- Full database schema and foreign key relationships
- Whether `premium.php` is actively used in production or is legacy
- Whether [`followup_bkp.php`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/followup_bkp.php) is archival only or still referenced manually
- Whether the production server still runs PHP 7.4 despite local checks using PHP 8.0
- Missing `images/` assets referenced by the UI are not present in this repo snapshot
