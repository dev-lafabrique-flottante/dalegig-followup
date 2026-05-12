# AGENTS.md

## Purpose

`scss/` holds the stylesheet source files for the UI theme used by this app.

## What is here

- [`style.scss`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/scss/style.scss): top-level SCSS entrypoint
- [`common.scss`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/scss/common.scss): imports Bootstrap SCSS, Compass mixins, template partials, and component overrides
- multiple partials for components, mixins, landing screens, and horizontal layout

## Important repo reality

- `scss/common.scss` imports from `../node_modules/bootstrap/...` and `../node_modules/compass-mixins/...`
- no `package.json`, lockfile, or SCSS build command exists in this repo snapshot
- compiled CSS already exists in [`css/style.css`](/Users/raphaelevangelista/Desktop/Bureau%20-%20MacBook%20Air%20de%20Raphael%20(2)/Code/followup%20dalegig/css/style.css)

## Local conventions

- This styling appears to come from a committed admin template asset set
- `css/style.css` looks like generated output from the SCSS sources
- JS and vendor assets are tightly coupled to the existing theme structure

## Common mistakes to avoid

- Do not promise a working SCSS rebuild command unless you first discover or add the missing toolchain
- Do not edit SCSS and then claim CSS is updated unless you actually regenerate it
- Do not edit large vendor-like theme sections if the task only needs a small app-specific visual tweak
- Do not remove template imports from `common.scss` without auditing the existing CSS dependencies

## Recommended editing approach

- For a quick production-safe style fix when no build tool is available, prefer a minimal targeted edit in committed CSS only if the user wants the deployed asset changed directly
- For maintainable source changes, edit SCSS and separately verify how CSS should be regenerated before touching output files

## Commands

- SCSS build command: Not found in repo
- CSS minify command: Not found in repo
