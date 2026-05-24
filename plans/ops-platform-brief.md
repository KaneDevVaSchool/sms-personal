# OPS Platform — Project Brief

> Hệ thống quản lý tổng hợp nội bộ: tài nguyên, dự án, kiến thức, đội ngũ, hợp đồng, AI accounts và mục tiêu.

---

## 1. Tổng quan

**Tên dự án:** OPS Platform  
**Loại:** Internal Management System  
**Stack chính:** Laravel 13 (API) + Vue 3 (SPA)  
**Đối tượng sử dụng:** Team nội bộ (Admin / Manager / Member)  
**Mục tiêu:** Thay thế các công cụ rời rạc (spreadsheet, Google Drive, chat) bằng một nền tảng thống nhất, cho phép quản lý toàn bộ vận hành trong một dashboard.

---

## 2. Các module chức năng

### 2.1 Quản lý Tài nguyên (`/resources`)
- Quản lý danh sách servers, domains, hosting, license, tài khoản dịch vụ
- Theo dõi trạng thái (active / expired / warning), ngày hết hạn, chi phí/tháng
- Alert tự động khi tài nguyên sắp hết hạn (7 ngày, 30 ngày)
- Phân loại theo type, team sở hữu

**Entities:** `Resource`, `ResourceType`, `ResourceAlert`

---

### 2.2 Quản lý Website (`/websites`)
- Danh sách website với trạng thái uptime, URL, tech stack
- Mapping domain → hosting → server
- Gắn CMS, môi trường (production / staging / dev)
- Theo dõi SSL certificate expiry

**Entities:** `Website`, `WebsiteEnvironment`

---

### 2.3 Quản lý Dự án (`/projects`)
- Kanban board (To Do / In Progress / Review / Done)
- Timeline / Gantt view theo milestone
- Giao task cho thành viên, set deadline, priority
- Theo dõi tiến độ % hoàn thành theo sprint/milestone
- Comment thread trên từng task

**Entities:** `Project`, `Task`, `Milestone`, `TaskComment`, `TaskAssignee`

---

### 2.4 CMS Kiến thức (`/knowledge`)
- Blog nội bộ: bài viết về setup công nghệ mới, AI sharing, how-to guides
- Editor WYSIWYG (TipTap hoặc Quill)
- Phân loại: Category + Tag, tìm kiếm full-text
- Trạng thái: Draft / Published / Archived
- Theo dõi lượt xem, bookmark

**Entities:** `Post`, `Category`, `Tag`, `PostView`, `PostBookmark`

---

### 2.5 Quản lý Đội ngũ (`/team`)
- Hồ sơ thành viên: thông tin cá nhân, role, department, ngày join
- Phân quyền RBAC: Admin / Manager / Member
- Cấu trúc team, người phụ trách
- Onboarding checklist cho thành viên mới

**Entities:** `User`, `Role`, `Permission`, `Department`, `Team`

---

### 2.6 Quản lý Hợp đồng (`/contracts`)
- Upload & lưu trữ hợp đồng (PDF/DOCX) lên S3/R2
- Metadata: đối tác, giá trị, ngày ký, ngày hết hạn, loại hợp đồng
- Trạng thái: Active / Expired / Pending / Terminated
- Nhắc nhở gia hạn tự động
- Version history khi có addendum

**Entities:** `Contract`, `ContractFile`, `ContractAlert`

---

### 2.7 Tài khoản AI (`/ai-accounts`)
- Quản lý API keys cho các dịch vụ AI (OpenAI, Claude, Gemini, Midjourney…)
- Theo dõi usage quota, credit còn lại, billing cycle
- Assign key cho team/dự án cụ thể
- Cảnh báo khi gần hết quota

**Entities:** `AiAccount`, `AiUsageLog`, `AiAlert`

---

### 2.8 Quản lý Mục tiêu — OKR (`/okrs`)
- Định nghĩa Objective + Key Results theo quý
- Gắn OKR với team hoặc cá nhân
- Cập nhật % hoàn thành, check-in định kỳ
- Dashboard theo dõi tiến độ OKR toàn công ty

**Entities:** `Objective`, `KeyResult`, `OkrCheckin`

---

## 3. Tech Stack

### Backend — laravel 13

| Thành phần | Công nghệ |
|---|---|
| Framework | laravel 13 |
| PHP | >= 8.3 |
| Database | PostgreSQL 16 |
| ORM | Eloquent + Prisma-style migrations |
| Auth | Laravel Sanctum (SPA token) |
| Authorization | Spatie Laravel Permission (RBAC) |
| File Storage | AWS S3 / Cloudflare R2 |
| Queue | Laravel Queues + Redis |
| Mail / Alert | Laravel Mail + Resend hoặc SMTP |
| Cache | Redis |
| API Docs | Scribe hoặc L5-Swagger |
| Testing | Pest PHP |

### Frontend — Vue 3

| Thành phần | Công nghệ |
|---|---|
| Framework | Vue 3 (Composition API) |
| Build tool | Vite |
| State management | Pinia |
| Routing | Vue Router 4 |
| UI Library | shadcn-vue hoặc PrimeVue |
| HTTP Client | Axios |
| Tables | TanStack Table (Vue) |
| Editor | Tiptap |
| Charts | Chart.js + vue-chartjs |
| Date | Day.js |
| Drag & Drop | VueDraggable (Kanban) |
| Type safety | TypeScript |

### Infrastructure

| Thành phần | Công nghệ |
|---|---|
| Deployment | Docker + Nginx |
| CI/CD | GitHub Actions |
| Hosting | VPS (DigitalOcean / Hetzner) hoặc Laravel Forge |
| Storage | Cloudflare R2 (S3-compatible) |
| Monitoring | Laravel Telescope (dev), Sentry (prod) |

---

## 4. Kiến trúc hệ thống

```
┌─────────────────────────────────────────┐
│            Vue 3 SPA (Vite)             │
│  Pinia Store → Vue Router → Components  │
└──────────────┬──────────────────────────┘
               │ HTTP (Axios + Sanctum token)
               ▼
┌─────────────────────────────────────────┐
│          laravel 13 REST API            │
│  Routes → Middleware → Controllers      │
│  FormRequests → Actions → Services      │
│  API Resources → JSON Response          │
└──────────────┬──────────────────────────┘
               │
    ┌──────────┼──────────┐
    ▼          ▼          ▼
PostgreSQL    Redis     S3/R2
(data)      (cache/    (files)
            queues)
```

### Phân lớp Backend

```
app/
├── Actions/          # Single-purpose use cases (CreateProject, UploadContract...)
├── Http/
│   ├── Controllers/  # Thin controllers, delegate to Actions
│   ├── Requests/     # Form validation + toDto()
│   └── Resources/    # API response transformers
├── Models/           # Eloquent models với casts, scopes, relationships
├── Policies/         # RBAC authorization per model
├── Services/         # Domain services (NotificationService, StorageService...)
├── Jobs/             # Queued jobs (SendAlertEmail, ProcessContractUpload...)
└── Events/           # Domain events
```

### Response Format chuẩn

Tất cả API response theo format nhất quán:

```json
{
  "success": true,
  "data": { ... },
  "error": null,
  "meta": {
    "page": 1,
    "per_page": 25,
    "total": 100
  }
}
```

---

## 5. Database Schema (tóm tắt)

### Core tables

```sql
-- Users & Auth
users               (id, name, email, avatar, department_id, created_at)
roles               (id, name, guard_name)
model_has_roles     (role_id, model_type, model_id)
departments         (id, name, manager_id)
teams               (id, name, department_id)
team_user           (team_id, user_id, role)

-- Resources
resources           (id, name, type, url, owner_team_id, status, expires_at, cost_monthly)
resource_types      (id, name, icon)

-- Websites
websites            (id, name, url, status, ssl_expires_at, resource_id)

-- Projects
projects            (id, name, description, status, owner_id, due_date)
milestones          (id, project_id, name, due_date, completed_at)
tasks               (id, project_id, milestone_id, title, status, priority, due_date)
task_assignees      (task_id, user_id)
task_comments       (id, task_id, user_id, body, created_at)

-- Knowledge CMS
posts               (id, title, slug, body, author_id, category_id, status, published_at)
categories          (id, name, slug, parent_id)
tags                (id, name, slug)
post_tag            (post_id, tag_id)

-- Contracts
contracts           (id, name, partner, type, value, signed_at, expires_at, status)
contract_files      (id, contract_id, path, version, uploaded_by)

-- AI Accounts
ai_accounts         (id, service, label, api_key_hint, quota_limit, quota_used, billing_date)

-- OKRs
objectives          (id, title, team_id, owner_id, quarter, year)
key_results         (id, objective_id, title, target, current, unit)
okr_checkins        (id, key_result_id, value, note, checked_at, user_id)
```

---

## 6. Authentication & Authorization

- **Auth:** Laravel Sanctum — SPA cookie-based authentication
- **RBAC:** Spatie Permission package
  - `admin` — full access
  - `manager` — manage own team's resources, projects, contracts
  - `member` — read + limited write trong scope của mình
- **Policy per module:** Mỗi module có Policy riêng kiểm tra quyền trước action

```php
// Ví dụ: ProjectPolicy
public function update(User $user, Project $project): bool
{
    return $user->hasRole('admin')
        || $user->id === $project->owner_id
        || $user->hasPermissionTo('projects.update');
}
```

---

## 7. Notification & Alert System

Các loại alert được xử lý qua **Queue + Scheduled Jobs:**

| Trigger | Channel | Timing |
|---|---|---|
| Tài nguyên sắp hết hạn | Email + in-app | 30 ngày, 7 ngày trước |
| SSL certificate expiry | Email | 30 ngày, 7 ngày trước |
| Contract sắp hết hạn | Email + in-app | 60 ngày, 30 ngày trước |
| AI quota > 80% | In-app | Real-time |
| Task deadline | In-app | 1 ngày trước |
| OKR check-in nhắc nhở | Email | Hàng tuần |

---

## 8. Lộ trình phát triển

### Phase 1 — Foundation (Sprint 1–2)
- [ ] Setup laravel 13 project (Sanctum, Spatie Permission, Pest)
- [ ] Setup Vue 3 + Vite + Pinia + Vue Router + TypeScript
- [ ] Auth flow: Login, logout, refresh token, profile
- [ ] Layout chính: Sidebar navigation, breadcrumb, notification bell
- [ ] Module **Đội ngũ** (User CRUD, Role assignment, Department, Team)
- [ ] Database migrations cho tất cả modules

### Phase 2 — Core Operations (Sprint 3–5)
- [ ] Module **Tài nguyên** — CRUD, filter, alert scheduler
- [ ] Module **Website** — CRUD, SSL tracking
- [ ] Module **Dự án** — Kanban board, task management, assignees
- [ ] File upload infrastructure (S3/R2 + presigned URLs)

### Phase 3 — Knowledge & AI (Sprint 6–7)
- [ ] Module **CMS Kiến thức** — Editor, publish flow, search
- [ ] Module **Tài khoản AI** — API key vault, usage dashboard
- [ ] Full-text search (Laravel Scout + MeiliSearch hoặc PostgreSQL FTS)

### Phase 4 — Contracts & OKR (Sprint 8–9)
- [ ] Module **Hợp đồng** — Upload PDF, metadata, versioning, alerts
- [ ] Module **OKR** — Objective + KR tracking, check-in flow
- [ ] Notification center (in-app + email digest)

### Phase 5 — Polish & Scale (Sprint 10)
- [ ] Home dashboard: widget tổng hợp từ tất cả modules
- [ ] Dark mode
- [ ] Mobile responsive
- [ ] Performance: query optimization, caching, lazy loading
- [ ] Docs nội bộ (Scribe API docs)
- [ ] CI/CD pipeline (GitHub Actions → Docker → Forge/VPS)

---

## 9. Cấu trúc thư mục Vue 3

```
resources/
└── js/
    ├── composables/        # useAuth, useNotification, usePagination...
    ├── layouts/            # AppLayout, AuthLayout
    ├── modules/
    │   ├── resources/
    │   │   ├── components/
    │   │   ├── pages/      # ResourceIndex.vue, ResourceShow.vue...
    │   │   ├── store.ts    # Pinia store
    │   │   └── api.ts      # Axios calls
    │   ├── projects/
    │   ├── knowledge/
    │   ├── team/
    │   ├── contracts/
    │   ├── ai-accounts/
    │   └── okrs/
    ├── router/
    │   └── index.ts
    ├── stores/
    │   └── auth.ts
    ├── types/              # TypeScript interfaces
    └── utils/
```

---

## 10. Cursor AI — Hướng dẫn sử dụng brief này

Dán prompt sau vào Cursor để bắt đầu:

```
I'm building an internal OPS Platform using laravel 13 (REST API) + Vue 3 (SPA with Vite, Pinia, TypeScript).

Tech stack:
- Backend: laravel 13, PHP 8.3, PostgreSQL, Sanctum auth, Spatie Permission (RBAC), Redis queue, S3/R2 storage
- Frontend: Vue 3 Composition API, TypeScript, Vite, Pinia, Vue Router 4, Axios, shadcn-vue

The system has 8 modules:
1. Resource Management (servers, domains, licenses)
2. Website Management (uptime, SSL, hosting mapping)
3. Project Management (Kanban, tasks, milestones)
4. Knowledge CMS (internal blog, AI sharing, guides)
5. Team Management (users, roles, departments — RBAC)
6. Contract Management (PDF storage, renewal alerts)
7. AI Account Management (API keys, quota tracking)
8. OKR Tracking (objectives, key results, check-ins)

Architecture pattern:
- Thin controllers → Actions → Services → Models
- FormRequest validation with toDto()
- API Resources for consistent JSON responses: { success, data, error, meta }
- RBAC via Policies per module

Please start by:
1. Generating the complete Prisma-style Laravel migrations for all 8 modules
2. Setting up the base Laravel API structure (routes, base controller, response helper)
3. Setting up the Vue 3 project structure with auth flow
```

---

*Brief version 1.0 — laravel 13 + Vue 3 stack*
