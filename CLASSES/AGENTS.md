# AGENTS.md

## Purpose

`CLASSES/` currently contains database connection bootstrap code used by the PHP pages.

## What is here

- [`conexao.php`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/CLASSES/conexao.php): opens three `mysqli` connections:
  - `$conn_gig_dalegig`
  - `$conn_dalegig`
  - `$conn`

## Local conventions

- Pages include this file directly with `include ('CLASSES/conexao.php');`
- Connection details are hardcoded, not env-driven
- The rest of the app expects these variable names exactly

## Safety rules

- Treat this folder as sensitive because it contains live-looking credentials
- Do not copy credentials into docs, logs, tests, or screenshots
- Do not rename connection variables without updating every consumer
- Do not assume connection error handling is reliable here; inspect call sites carefully

## Common mistakes to avoid

- Do not replace this with a new config system unless the user explicitly asks for that refactor
- Do not “clean up” duplicate connection setup casually; multiple pages depend on current names and includes
- Do not assume `$mysqli` checks are correct just because syntax passes. Review runtime behavior before changing error handling

## Useful verification

- Syntax check used during inspection: `php -l CLASSES/conexao.php`
- Database schema discovery command: Not found in repo
