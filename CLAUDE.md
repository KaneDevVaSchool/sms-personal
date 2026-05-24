# CLAUDE.md — Agent instructions (SMS / OPS Platform)

This file guides Claude Code and other AI agents working in this repository.

## Project

- **Name:** SMS (OPS Platform) — internal management system (resources, projects, knowledge, team, contracts, AI accounts, OKRs).
- **Remote:** `git@github.com:KaneDevVaSchool/sms-personal.git`
- **Stack:** Laravel 13 (PHP 8.3+) + Inertia.js + Vue 3 + Vite + Tailwind. PHPUnit for tests.
- **Product brief:** [plans/ops-platform-brief.md](plans/ops-platform-brief.md)
- **Architecture notes:** [docs/architecture.md](docs/architecture.md)

## Repository layout

```
app/                 # Laravel application code
routes/              # web.php, auth.php
database/            # migrations, seeders, factories
resources/js/        # Vue pages, layouts, components (@ alias)
resources/views/     # Inertia root blade
tests/               # PHPUnit Feature/Unit
docs/                # ISC standards & architecture
plans/               # Product/planning documents
.cursor/rules/       # Cursor rules (committed); detailed ISC summaries
```

## Standards (ISC)

Follow **ISC Technical Standards** for all changes:

| Area | Cursor rule | Full doc |
|------|-------------|----------|
| Backend | `.cursor/rules/01-isc-backend.mdc` | [docs/isc-standards-be.md](docs/isc-standards-be.md) |
| Frontend | `.cursor/rules/02-isc-frontend.mdc` | [docs/isc-standards-fe.md](docs/isc-standards-fe.md) |
| API envelope | `.cursor/rules/04-api-response.mdc` | ISC 3.x / 4.x in BE doc |

**Non-negotiables:**

- No secrets or credentials in source; use `.env` (never commit `.env`).
- No logging of PII (email, phone, tokens, passwords, OTP).
- Parameterized queries / Eloquent only — no SQL injection via string concat.
- Authorization on every mutating/read-by-id endpoint (no IDOR).
- API JSON: `success`, `data`, `error`, `meta` with `trace_id` / `request_id`.

## Commits

Format: `type(scope): description [AI]` — **max 72 characters total**, imperative mood, lowercase description.

Types: `feat`, `fix`, `hotfix`, `refactor`, `test`, `docs`, `chore`, `style`.

See `.cursor/rules/03-commit-convention.mdc` and [.cursor/commands/commit.md](.cursor/commands/commit.md). CI validates messages via `commitlint.config.js`.

If diff > 400 lines, prefer splitting into focused commits.

## Commands

```bash
composer install && npm ci
composer run dev          # server + queue + vite (@php scripts/dev.php)
php artisan test          # PHPUnit
npm run build             # production frontend build
```

## CI/CD

- **CI:** `.github/workflows/ci.yml` — PHP tests + `npm run build` on push/PR to `main` and `develop`.
- **CD:** `.github/workflows/cd.yml` — placeholder; configure Forge/VPS deploy when ready.
- **Commit lint:** `.github/workflows/commitlint.yml` on pull requests.

## Before finishing a task

- Run relevant tests (`php artisan test`) when PHP changed.
- Run `npm run build` when Vue/JS changed.
- Ensure no debug logging or secrets in the diff.
- Use the commit format above when committing.
