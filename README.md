# followup dalegig

Aplicacao PHP procedural usada no fluxo operacional de follow-up da daleGig.

## Publicacao segura no GitHub

Este repositório foi preparado para nao versionar segredos locais:

- `CLASSES/conexao.php` fica fora do Git
- `followup_bkp.php` fica fora do Git por conter token legado hardcoded
- o token de acesso principal agora deve vir da variavel de ambiente `FOLLOWUP_ACCESS_TOKEN`

## Configuracao local

1. Copie `CLASSES/conexao.example.php` para `CLASSES/conexao.php`
2. Ajuste as credenciais locais ou exporte estas variaveis de ambiente:

- `DALEGIG_DB_HOST`
- `DALEGIG_DB_USER`
- `DALEGIG_DB_PASSWORD`
- `DALEGIG_DB_GIG`
- `DALEGIG_DB_MAIN`
- `DALEGIG_DB_INTRANET`
- `FOLLOWUP_ACCESS_TOKEN`
- `FOLLOWUP_ALLOWED_EMAILS` (opcional, lista separada por virgulas)
- `FOLLOWUP_ALLOW_TOKEN_LOGIN=1` (opcional, apenas para manter acesso legado por token)

## Observacoes

- O projeto nao inclui Composer, npm, migrations ou suite de testes.
- O deploy aparente usa Apache/cPanel e PHP 7.4.
- O acesso principal usa login por codigo enviado por email para enderecos autorizados.
