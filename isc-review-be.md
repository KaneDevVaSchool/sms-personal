---
description: Review code Backend theo ISC Technical Standards + lỗi bảo mật & performance thường gặp
---

Chạy `git diff --staged` để lấy toàn bộ code đang trong staged area, sau đó review theo các tiêu chuẩn bên dưới.

Nếu không có staged changes, chạy `git diff HEAD` thay thế và thông báo cho user.

---

## LỚP A — ISC Standards (Backend)

### ISC 1.2 — Đặt tên

- [ ] Biến thông thường: `camelCase` — `isActive`, `totalAmount`
- [ ] Biến private: `_camelCase` — `_userRepository`, `_cacheService`
- [ ] Hàm & Class: `PascalCase` — `CalculateTotal()`, `UserService`
- [ ] Constants: `ALL_CAPS` — `MAX_RETRY_COUNT`, `DEFAULT_TIMEOUT_MS`
- [ ] Interface: tiền tố `I` + PascalCase — `IUserService`, `IOrderRepository`
- [ ] Enum values: `ALL_CAPS` hoặc `PascalCase` nhất quán

### ISC 1.3 — Format & Style

- [ ] Không hardcode giá trị cứng — timeout, retry count, page size, feature flag phải từ config/const/enum
- [ ] 1 hàm = 1 chức năng, tối đa ~50 dòng (tách helper nếu dài hơn)
- [ ] Không có code lặp lại — extract sang service/helper/utility
- [ ] Không dùng magic string — `'admin'`, `'active'` → dùng enum

### ISC 1.4 — Comments

- [ ] Public method/service/endpoint phải có XML-doc (C#) hoặc JSDoc (JS/TS)
- [ ] Logic phức tạp (tính toán nghiệp vụ, workaround) có comment giải thích WHY
- [ ] Không comment thừa như `// tăng i lên 1` → `i++`
- [ ] Không có TODO/FIXME cũ không resolve

### ISC 1.5 — Bảo mật

- [ ] Không log dữ liệu nhạy cảm: email, phone, CMND, token, password, OTP
- [ ] Validate input tại entry point (controller/handler) — không tin dữ liệu từ client
- [ ] Không throw exception thô ra ngoài — wrap và log nội bộ, trả message an toàn cho client
- [ ] Không lưu secret (API key, DB password, JWT secret) trong source code

### ISC 1.6 — Nguyên tắc thiết kế

- [ ] SOLID: Single Responsibility, Open/Closed, Liskov, Interface Segregation, Dependency Inversion
- [ ] DRY: không duplicate logic giữa các service/handler
- [ ] KISS: giải pháp đơn giản nhất đủ giải quyết bài toán
- [ ] Dependency Injection: không `new Service()` trực tiếp bên trong class
- [ ] Ưu tiên interface/abstraction hơn class cụ thể

### ISC 2.x — API Naming Convention

- [ ] Endpoint dùng danh từ số nhiều, lowercase: `/users`, `/orders`, `/payments`
- [ ] Không dùng động từ trong path: ❌ `/getUsers` → ✅ `GET /users`
- [ ] Không lồng quá 3 cấp: tối đa `/orders/{id}/items`
- [ ] Action nghiệp vụ dùng POST với path rõ ràng: `POST /users/{id}/reset-password`
- [ ] HTTP method đúng mục đích: GET=đọc, POST=tạo, PUT=update toàn bộ, PATCH=update một phần, DELETE=xóa

### ISC 3.x — API Response Structure

Response thành công:
```json
{ "success": true, "data": {...}, "error": null, "meta": { "request_id": "...", "trace_id": "...", "timestamp": "..." } }
```

Response lỗi nghiệp vụ (HTTP 200):
```json
{ "success": false, "data": null, "error": { "code": "USR_404", "message": "...", "retryable": false }, "meta": {...} }
```

- [ ] Luôn có đủ 4 trường: `success`, `data`, `error`, `meta`
- [ ] `meta` luôn có `trace_id` và `request_id`
- [ ] Lỗi nghiệp vụ: HTTP 200 + `success = false`
- [ ] Lỗi kỹ thuật (crash, DB down): HTTP 4xx/5xx qua middleware
- [ ] Không trả HTTP 200 cho lỗi kỹ thuật

### ISC 4.x — Error Code

- [ ] Dùng prefix theo module: `AUTH_401`, `USR_404`, `ORD_409`, `SYS_500`
- [ ] Lỗi nghiệp vụ: `INVALID_XXX`, `MISSING_XXX`, `USER_LOCKED`, `ORDER_EXPIRED`
- [ ] Không đặt mã lỗi tùy tiện ngoài chuẩn đã định nghĩa
- [ ] Không hardcode string mã lỗi trực tiếp — dùng const/enum

### ISC 5.x — Timeout

- [ ] Mọi HTTP call ra ngoài phải có timeout rõ ràng — không để vô hạn
- [ ] Mọi DB query / gRPC call phải có timeout
- [ ] Timeout lấy từ config/env, không hardcode
- [ ] Khi timeout: trả error code `REQUEST_TIMEOUT`, `retryable: true`
- [ ] .NET: dùng `CancellationToken` | Node.js: dùng `AbortController` / `signal`

---

## LỚP B — Lỗi thường gặp & nguy hiểm (BE)

---

### 🔴 SQL Injection
**Vi phạm:** ISC 1.5 · OWASP A03:2021
**Tại sao nguy hiểm:** Attacker đọc/xoá/sửa toàn bộ database qua input độc hại.

❌ Sai:
```ts
// Node.js — concatenate string SQL
const user = await db.query(`SELECT * FROM users WHERE email = '${email}'`);

// C#
var sql = "SELECT * FROM Users WHERE Email = '" + email + "'";
```

✅ Đúng:
```ts
// Node.js — parameterized query
const user = await db.query('SELECT * FROM users WHERE email = $1', [email]);

// ORM (TypeORM)
const user = await userRepo.findOne({ where: { email } });
```

---

### 🔴 Broken Authorization / IDOR
**Vi phạm:** ISC 1.5 · OWASP A01:2021
**Tại sao nguy hiểm:** User A đọc/sửa được data của User B bằng cách đổi ID trong request.

❌ Sai:
```ts
// Chỉ check đăng nhập, không check ownership
app.get('/orders/:id', authenticate, async (req, res) => {
  const order = await orderRepo.findById(req.params.id);
  res.json(order); // bất kỳ user đăng nhập cũng đọc được
});
```

✅ Đúng:
```ts
app.get('/orders/:id', authenticate, async (req, res) => {
  const order = await orderRepo.findOne({
    where: { id: req.params.id, userId: req.user.id }, // check ownership
  });
  if (!order) return res.status(404).json({ success: false, error: { code: 'ORD_404' } });
  res.json({ success: true, data: order });
});
```

---

### 🔴 Mass Assignment
**Vi phạm:** ISC 1.5 · OWASP A03:2021
**Tại sao nguy hiểm:** Client gửi `{ role: "admin", isVerified: true }` và được ghi thẳng vào DB.

❌ Sai:
```ts
// Map nguyên body vào entity
const user = userRepo.create(req.body);
await userRepo.save(user);
```

✅ Đúng:
```ts
// Whitelist field được phép update
const { name, email, phone } = req.body;
const user = userRepo.create({ name, email, phone });
await userRepo.save(user);

// Hoặc dùng DTO validation (class-validator)
@IsString() @IsEmail()
class UpdateUserDto { name: string; email: string; }
```

---

### 🔴 SSRF (Server-Side Request Forgery)
**Vi phạm:** ISC 1.5 · OWASP A10:2021
**Tại sao nguy hiểm:** Attacker cho server gọi vào internal network (metadata AWS, Redis, DB) qua URL do họ cung cấp.

❌ Sai:
```ts
// Lấy URL từ user input, gọi thẳng
const { url } = req.body;
const response = await axios.get(url); // gọi http://169.254.169.254/metadata được
```

✅ Đúng:
```ts
const ALLOWED_HOSTS = ['api.partner.com', 'webhook.service.io'];

function validateUrl(url: string): boolean {
  const parsed = new URL(url);
  return ALLOWED_HOSTS.includes(parsed.hostname) && parsed.protocol === 'https:';
}

if (!validateUrl(req.body.url)) throw new BadRequestError('INVALID_URL');
const response = await axios.get(req.body.url);
```

---

### 🔴 Secret / Credential Hardcode
**Vi phạm:** ISC 1.5 · OWASP A02:2021
**Tại sao nguy hiểm:** Secret lọt vào git history, bất kỳ ai có repo đều đọc được.

❌ Sai:
```ts
const jwt = require('jsonwebtoken');
const token = jwt.sign(payload, 'my-super-secret-key-123'); // hardcode
const db = new Pool({ password: 'admin123' }); // hardcode
```

✅ Đúng:
```ts
// Lấy từ environment variable
const token = jwt.sign(payload, process.env.JWT_SECRET!);
const db = new Pool({ password: process.env.DB_PASSWORD });
// Đảm bảo .env không commit vào git (.gitignore)
```

---

### 🔴 CSRF (Cookie-based Session)
**Vi phạm:** ISC 1.5 · OWASP A01:2021
**Tại sao nguy hiểm:** Attacker dụ user click link → browser tự gửi cookie đến endpoint state-changing.

❌ Sai:
```ts
// Không có CSRF protection
app.post('/users/change-password', authenticate, changePasswordHandler);
```

✅ Đúng:
```ts
import csrf from 'csurf';
app.use(csrf({ cookie: true }));
app.post('/users/change-password', authenticate, csrfProtection, changePasswordHandler);

// Hoặc kiểm tra Origin/Referer header
// Hoặc dùng SameSite=Strict cookie + token-based auth (không cần CSRF)
```

---

### 🟡 N+1 Query
**Vi phạm:** ISC 1.6 (DRY, performance)
**Tại sao nguy hiểm:** 100 orders → 101 DB queries → timeout, OOM.

❌ Sai:
```ts
const orders = await orderRepo.find();
for (const order of orders) {
  order.user = await userRepo.findById(order.userId); // N query thêm
}
```

✅ Đúng:
```ts
// TypeORM — eager relation / join
const orders = await orderRepo.find({ relations: ['user'] });

// Prisma
const orders = await prisma.order.findMany({ include: { user: true } });

// Raw SQL — JOIN
const orders = await db.query(`
  SELECT o.*, u.name AS userName FROM orders o
  JOIN users u ON u.id = o.user_id
`);
```

---

### 🟡 Missing Database Index
**Vi phạm:** ISC 1.6 (performance)
**Tại sao nguy hiểm:** Full table scan → query chậm dần khi data tăng.

❌ Sai:
```ts
// Thường xuyên query theo email nhưng không có index
await userRepo.findOne({ where: { email } }); // full scan nếu không có index
```

✅ Đúng:
```ts
// TypeORM entity
@Entity()
export class User {
  @Index() // thêm index
  @Column({ unique: true })
  email: string;
}

// SQL migration
CREATE INDEX idx_users_email ON users(email);
```

---

### 🟡 Transaction Boundary
**Vi phạm:** ISC 1.6
**Tại sao nguy hiểm:** Ghi một phần thành công, một phần lỗi → data inconsistent.

❌ Sai:
```ts
await orderRepo.save(order);        // thành công
await inventoryRepo.decrease(item); // lỗi → order tạo nhưng inventory không trừ
await notificationService.send();
```

✅ Đúng:
```ts
await dataSource.transaction(async (manager) => {
  await manager.save(Order, order);
  await manager.decrement(Inventory, { itemId }, 'quantity', 1);
  // nếu bất kỳ bước nào throw, toàn bộ rollback
});
```

---

### 🟡 Race Condition (Read-Modify-Write)
**Vi phạm:** ISC 1.6
**Tại sao nguy hiểm:** Hai request song song đọc cùng balance → cộng 2 lần → sai số dư.

❌ Sai:
```ts
const account = await accountRepo.findById(id);
account.balance -= amount; // race condition nếu 2 request đồng thời
await accountRepo.save(account);
```

✅ Đúng:
```ts
// Optimistic locking (TypeORM @Version)
@Entity()
class Account {
  @VersionColumn() version: number;
  balance: number;
}

// Hoặc SQL atomic update
await db.query(
  'UPDATE accounts SET balance = balance - $1 WHERE id = $2 AND balance >= $1',
  [amount, id]
);
```

---

### 🟡 Idempotency (POST tạo resource)
**Vi phạm:** ISC 1.6
**Tại sao nguy hiểm:** Client retry → tạo duplicate order/payment.

❌ Sai:
```ts
app.post('/orders', async (req, res) => {
  const order = await orderRepo.create(req.body); // mỗi call tạo 1 order mới
});
```

✅ Đúng:
```ts
app.post('/orders', async (req, res) => {
  const idempotencyKey = req.headers['idempotency-key'];
  if (!idempotencyKey) return res.status(400).json({ error: 'MISSING_IDEMPOTENCY_KEY' });

  const existing = await cacheService.get(`order:${idempotencyKey}`);
  if (existing) return res.json({ success: true, data: existing }); // trả lại kết quả cũ

  const order = await orderRepo.create(req.body);
  await cacheService.set(`order:${idempotencyKey}`, order, 86400);
  res.json({ success: true, data: order });
});
```

---

### 🟡 Rate Limiting (Auth / OTP endpoint)
**Vi phạm:** ISC 1.5
**Tại sao nguy hiểm:** Brute force password/OTP không bị chặn.

❌ Sai:
```ts
app.post('/auth/login', loginHandler);       // không rate limit
app.post('/auth/otp/verify', otpHandler);    // brute force OTP được
```

✅ Đúng:
```ts
import rateLimit from 'express-rate-limit';

const authLimiter = rateLimit({
  windowMs: 15 * 60 * 1000, // 15 phút
  max: 5,                   // tối đa 5 lần/IP
  message: { success: false, error: { code: 'AUTH_RATE_LIMIT', retryable: true } },
});

app.post('/auth/login', authLimiter, loginHandler);
app.post('/auth/otp/verify', authLimiter, otpHandler);
```

---

### 🟡 Log PII / Sensitive Data
**Vi phạm:** ISC 1.5 · GDPR/PDPA
**Tại sao nguy hiểm:** Token, email, CMND lọt vào log → lộ qua log management tool.

❌ Sai:
```ts
logger.info('User login', { email, password, token }); // log password và token
logger.error('OTP failed', { phone, otp });              // log OTP
```

✅ Đúng:
```ts
logger.info('User login attempt', { userId, traceId }); // chỉ log non-sensitive
logger.error('OTP verification failed', { userId, traceId, reason: 'EXPIRED' });
```

---

### 🟡 Circuit Breaker (External service call)
**Vi phạm:** ISC 5.x
**Tại sao nguy hiểm:** External service chết → request queue ngày càng lớn → OOM → server chết theo.

❌ Sai:
```ts
// Không có fallback, không có timeout
const result = await externalPaymentApi.charge(payload);
```

✅ Đúng:
```ts
import axios from 'axios';
import { createCircuitBreaker } from 'opossum'; // hoặc dùng library tương đương

const chargeWithBreaker = createCircuitBreaker(
  (payload) => axios.post(PAYMENT_URL, payload, { timeout: 5000, signal: controller.signal }),
  { timeout: 5000, errorThresholdPercentage: 50, resetTimeout: 30000 }
);

chargeWithBreaker.fallback(() => ({ success: false, error: { code: 'PAYMENT_UNAVAILABLE', retryable: true } }));
```

---

### 🔵 Missing Pagination
**Vi phạm:** ISC 2.x, performance

❌ Sai:
```ts
app.get('/orders', async (req, res) => {
  const orders = await orderRepo.find(); // trả về toàn bộ table
  res.json({ success: true, data: orders });
});
```

✅ Đúng:
```ts
app.get('/orders', async (req, res) => {
  const page = Number(req.query.page) || 1;
  const limit = Math.min(Number(req.query.limit) || 20, 100); // cap max
  const [data, total] = await orderRepo.findAndCount({ skip: (page - 1) * limit, take: limit });
  res.json({ success: true, data, meta: { page, limit, total, request_id: req.id, trace_id: req.traceId, timestamp: new Date().toISOString() } });
});
```

---

### 🔵 Error Leak — Stack Trace ra Client
**Vi phạm:** ISC 1.5 · ISC 3.x

❌ Sai:
```ts
app.use((err, req, res, next) => {
  res.status(500).json({ message: err.message, stack: err.stack }); // leak internals
});
```

✅ Đúng:
```ts
app.use((err: Error, req: Request, res: Response, next: NextFunction) => {
  logger.error('Unhandled error', { err, traceId: req.traceId });
  res.status(500).json({
    success: false, data: null,
    error: { code: 'SYS_500', message: 'Internal server error', retryable: false },
    meta: { trace_id: req.traceId, request_id: req.id, timestamp: new Date().toISOString() },
  });
});
```

---

## Cách trình bày kết quả

### Tổng quan
- Số file thay đổi, loại thay đổi (feat/fix/refactor...)
- Đánh giá chung: **Pass** / **Cần cải thiện** / **Có vấn đề nghiêm trọng**

### Vấn đề phát hiện

| Mức | Ký hiệu | Ý nghĩa |
| --- | ------- | ------- |
| Critical | 🔴 | Vi phạm bảo mật, lỗi data, hardcode secret |
| Warning | 🟡 | Không đúng convention, thiếu error handling, performance risk |
| Suggestion | 🔵 | Cải thiện code quality, readability |

Với **mỗi vấn đề**, trình bày:
1. File và dòng code vi phạm (trích từ diff)
2. Lý do vi phạm tiêu chuẩn nào (ISC x.x / OWASP)
3. Code gợi ý fix cụ thể

### Điểm tốt
Liệt kê những gì code đang làm đúng theo chuẩn ISC.

---

### Git & Commit Checklist

- [ ] Không có `console.log` / debug code còn sót
- [ ] Không có code commented-out không cần thiết
- [ ] Không có credential / secret / API key hardcode trong source
- [ ] File thay đổi đúng với scope của branch hiện tại

$ARGUMENTS
