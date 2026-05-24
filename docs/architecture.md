# Architecture

## Current state

Single Laravel application with **Inertia.js** rendering **Vue 3** pages from `resources/js/`. Auth via Laravel Breeze + Sanctum patterns. No separate API repo; JSON API envelope (ISC 3.x) applies as REST endpoints are added.

## Target state (OPS Platform)

See [../plans/ops-platform-brief.md](../plans/ops-platform-brief.md).

| Layer | Technology |
|-------|------------|
| Backend | Laravel 13, PHP 8.3+, Eloquent |
| Frontend | Vue 3, Vite, Tailwind (Inertia today; Pinia/Router as SPA grows) |
| Auth | Sanctum (SPA) |
| DB (target) | PostgreSQL 16 |
| Cache/queue (target) | Redis |
| Files (target) | S3 / Cloudflare R2 |

## Module map

| Route prefix | Domain |
|--------------|--------|
| `/resources` | Servers, domains, licenses, expiry alerts |
| `/websites` | Uptime, environments, SSL |
| `/projects` | Kanban, tasks, milestones |
| `/knowledge` | Internal CMS / posts |
| `/team` | Users, RBAC, departments |
| `/contracts` | Contract files and renewals |
| `/ai-accounts` | AI API keys and quota |
| `/okrs` | Objectives and key results |

## Backend layering (target)

```
app/
├── Actions/           # Single-purpose use cases
├── Http/
│   ├── Controllers/   # Thin; delegate to Actions/Services
│   ├── Middleware/
│   └── Requests/      # Validation at boundary
├── Models/
├── Services/
└── Support/           # ApiResponse, error codes
```

## API contract

All JSON APIs use:

```json
{ "success": true|false, "data": ..., "error": ..., "meta": { "request_id", "trace_id", "timestamp" } }
```

Business errors: HTTP 200 + `success: false`. Technical errors: 4xx/5xx. Details: [isc-standards-be.md](./isc-standards-be.md) and `.cursor/rules/04-api-response.mdc`.

## CI

GitHub Actions run PHPUnit and `npm run build` on `main` and `develop`. See `.github/workflows/ci.yml`.
