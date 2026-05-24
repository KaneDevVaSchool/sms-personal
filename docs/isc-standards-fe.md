---
description: Review code Frontend Web (React/Vue/Next.js) theo ISC Technical Standards + lỗi bảo mật & performance thường gặp
---

Chạy `git diff --staged` để lấy toàn bộ code đang trong staged area, sau đó review theo các tiêu chuẩn bên dưới.

Nếu không có staged changes, chạy `git diff HEAD` thay thế và thông báo cho user.

---

## LỚP A — ISC Standards (Frontend Web)

### ISC 1.2 — Đặt tên

- [ ] Biến toàn cục: `camelCase` — `isLoading`, `currentUser`, `totalCount`
- [ ] Component / Class: `PascalCase` — `UserProfileCard`, `OrderListPage`
- [ ] Hooks: tiền tố `use` — `useAuth`, `useOrderList`, `useDebounce`
- [ ] Constants: `ALL_CAPS` — `MAX_FILE_SIZE`, `DEFAULT_PAGE_SIZE`
- [ ] Interface/Type: `PascalCase` — `UserProfile`, `ApiResponse<T>`
- [ ] File component: `PascalCase.tsx` — `UserCard.tsx`
- [ ] File hooks/utils: `camelCase.ts` — `useAuth.ts`, `formatDate.ts`

### ISC 1.3 — Format & Style

- [ ] Không hardcode magic value — timeout ms, retry count, page size, base URL → dùng config/env/const
- [ ] 1 hàm / 1 hook = 1 chức năng
- [ ] Không có code lặp lại — extract sang hook hoặc utility
- [ ] Không dùng magic string như `'admin'`, `'active'` → dùng enum/const

### ISC 1.4 — Comments

- [ ] JSDoc cho public utility function, custom hook
- [ ] Logic phức tạp (debounce timing, race condition handling) có comment WHY
- [ ] Không có TODO/FIXME cũ không resolve

### ISC 1.5 — Bảo mật

- [ ] Không log token, password, OTP ra console / external service
- [ ] Validate dữ liệu tại form boundary trước khi gửi API
- [ ] Không expose API key / secret trong bundle (biến NEXT_PUBLIC_ / VITE_ phải là non-sensitive)

### ISC 2.x–4.x — API Consumer (client side)

- [ ] Gọi API đúng HTTP method (GET đọc, POST tạo, PATCH/PUT update, DELETE xoá)
- [ ] Handle đủ 4 trường response: `success`, `data`, `error`, `meta`
- [ ] Error code lấy từ `error.code` — dùng const/enum, không so sánh magic string
- [ ] Hiển thị message lỗi từ `error.message` (nghiệp vụ) hoặc fallback message generic (lỗi kỹ thuật)

### ISC 5.x — Timeout & Cancellation

- [ ] HTTP client (axios/fetch) có timeout mặc định
- [ ] Request cancellation dùng `AbortController` khi component unmount hoặc deps thay đổi
- [ ] Handle timeout error: hiển thị UI retry thích hợp

---

## LỚP B — Lỗi thường gặp & nguy hiểm (FE Web)

---

### 🔴 XSS — Cross-Site Scripting
**Vi phạm:** ISC 1.5 · OWASP A03:2021
**Tại sao nguy hiểm:** Chạy script trong trình duyệt user → đánh cắp token, redirect.

❌ Sai:
```tsx
// React
<div dangerouslySetInnerHTML={{ __html: userComment }} />

// Vue
<div v-html="userComment"></div>

// Vanilla JS
element.innerHTML = userInput;
```

✅ Đúng:
```tsx
// Dùng text content thay HTML
<div>{userComment}</div>

// Nếu bắt buộc render HTML: sanitize trước
import DOMPurify from 'dompurify';
<div dangerouslySetInnerHTML={{ __html: DOMPurify.sanitize(userComment) }} />
```

---

### 🔴 Insecure Token Storage
**Vi phạm:** ISC 1.5 · OWASP A02:2021
**Tại sao nguy hiểm:** `localStorage` bị đọc bởi mọi JavaScript trên trang → XSS đánh cắp access token.

❌ Sai:
```ts
// Lưu token trong localStorage
localStorage.setItem('accessToken', token);
localStorage.setItem('refreshToken', refreshToken);

// Đọc lại
const token = localStorage.getItem('accessToken');
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
```

✅ Đúng:
```ts
// Option 1: In-memory (mất khi refresh page, nhưng an toàn nhất)
let inMemoryToken: string | null = null;
export const setToken = (t: string) => { inMemoryToken = t; };
export const getToken = () => inMemoryToken;

// Option 2: httpOnly cookie (server set, JS không đọc được)
// Server: Set-Cookie: accessToken=...; HttpOnly; Secure; SameSite=Strict

// Option 3: sessionStorage (chỉ sống trong tab, không persist)
// Chỉ dùng cho dữ liệu không nhạy cảm
```

---

### 🔴 CSRF (Cookie-based Auth)
**Vi phạm:** ISC 1.5 · OWASP A01:2021
**Tại sao nguy hiểm:** Attacker dụ user truy cập trang độc hại → browser tự gửi cookie.

❌ Sai:
```ts
// Form submit không có CSRF token
<form method="POST" action="/transfer">
  <input name="amount" value="1000" />
  <button type="submit">Transfer</button>
</form>
```

✅ Đúng:
```ts
// Include CSRF token từ server trong request header
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
axios.defaults.headers.common['X-CSRF-Token'] = csrfToken;

// Hoặc dùng SameSite=Strict/Lax cookie (không cần explicit CSRF token)
// Hoặc dùng Bearer token (Authorization header) thay cookie — không cần CSRF
```

---

### 🔴 Open Redirect
**Vi phạm:** ISC 1.5 · OWASP A01:2021
**Tại sao nguy hiểm:** Attacker tạo link `https://myapp.com/login?redirect=https://evil.com` → phishing.

❌ Sai:
```ts
const redirectUrl = new URLSearchParams(location.search).get('redirect');
router.push(redirectUrl); // redirect bất kỳ URL
window.location.href = redirectUrl;
```

✅ Đúng:
```ts
const redirectUrl = new URLSearchParams(location.search).get('redirect') || '/dashboard';

// Chỉ cho phép relative path (bắt đầu bằng /)
const safeRedirect = redirectUrl.startsWith('/') && !redirectUrl.startsWith('//') 
  ? redirectUrl 
  : '/dashboard';

router.push(safeRedirect);
```

---

### 🔴 API Key / Secret trong Bundle FE
**Vi phạm:** ISC 1.5 · OWASP A02:2021
**Tại sao nguy hiểm:** Bundle JavaScript có thể đọc bằng DevTools — mọi user đều thấy key.

❌ Sai:
```ts
// .env hoặc hardcode trong source
const STRIPE_SECRET_KEY = 'sk_live_xxxxxxxxxxxx'; // SECRET key trong FE
const API_KEY = 'super-secret-api-key-123';

// hoặc qua env variable public
// NEXT_PUBLIC_STRIPE_SECRET=sk_live_xxx  ← sai, public = visible in bundle
```

✅ Đúng:
```ts
// Chỉ expose PUBLISHABLE key ở FE
const STRIPE_PUBLISHABLE_KEY = process.env.NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY;

// Secret key → chỉ dùng ở server/backend, không bao giờ ở FE bundle
// Tạo proxy endpoint: FE → BE → Stripe (BE giữ secret key)
```

---

### 🔴 CSP Violation / Unsafe Eval
**Vi phạm:** ISC 1.5
**Tại sao nguy hiểm:** `eval()` và inline script bypass Content Security Policy.

❌ Sai:
```ts
eval(userInput);           // chạy code từ user input
new Function(codeString)(); // tương tự eval
setTimeout('fetchData()', 1000); // string trong setTimeout
```

✅ Đúng:
```ts
// Không dùng eval, luôn truyền function
setTimeout(() => fetchData(), 1000);

// CSP header (set ở server):
// Content-Security-Policy: default-src 'self'; script-src 'self'; object-src 'none'
```

---

### 🟡 State Management sai chỗ
**Vi phạm:** ISC 1.3 (separation of concerns)
**Tại sao nguy hiểm:** Re-fetch không cần thiết, stale data, race condition.

❌ Sai:
```tsx
// useState + useEffect để fetch — race condition, không cache
const [orders, setOrders] = useState([]);
const [loading, setLoading] = useState(false);
useEffect(() => {
  setLoading(true);
  fetch('/api/orders').then(r => r.json()).then(data => {
    setOrders(data);
    setLoading(false);
  });
}, []);

// Lưu server data vào Redux
dispatch(setOrders(apiResponse.data));
```

✅ Đúng:
```tsx
// React Query / SWR — cache, background refetch, loading/error tự động
import { useQuery } from '@tanstack/react-query';

const { data: orders, isLoading, error } = useQuery({
  queryKey: ['orders'],
  queryFn: () => orderService.getList(),
});

// Redux chỉ cho client state: theme, language, isOnline, auth flags
```

---

### 🟡 Re-render thừa — Inline Object/Array trong JSX
**Vi phạm:** ISC 1.3 (performance)
**Tại sao nguy hiểm:** Object/array mới tạo mỗi render → child memo hoá vô nghĩa → UI lag.

❌ Sai:
```tsx
// Object mới mỗi render → TableComponent re-render mỗi lần parent render
<TableComponent
  columns={[{ key: 'name', label: 'Name' }]}  // array literal
  style={{ padding: 16 }}                       // object literal
  onFilter={(v) => setFilter(v)}                // arrow function mới
/>
```

✅ Đúng:
```tsx
const COLUMNS = [{ key: 'name', label: 'Name' }]; // khai báo ngoài component

const MyComponent = () => {
  const style = useMemo(() => ({ padding: 16 }), []);
  const handleFilter = useCallback((v: string) => setFilter(v), []);

  return <TableComponent columns={COLUMNS} style={style} onFilter={handleFilter} />;
};
```

---

### 🟡 Race Condition / Missing Cleanup trong useEffect
**Vi phạm:** ISC 5.x
**Tại sao nguy hiểm:** Response sau ghi đè response trước; memory leak sau khi component unmount.

❌ Sai:
```tsx
useEffect(() => {
  fetch(`/api/users/${userId}`)
    .then(r => r.json())
    .then(data => setUser(data)); // nếu userId thay đổi nhanh → race condition
}, [userId]);
```

✅ Đúng:
```tsx
useEffect(() => {
  const controller = new AbortController();

  fetch(`/api/users/${userId}`, { signal: controller.signal })
    .then(r => r.json())
    .then(data => setUser(data))
    .catch(err => {
      if (err.name !== 'AbortError') console.error(err); // bỏ qua lỗi cancel
    });

  return () => controller.abort(); // cleanup: cancel request cũ khi deps thay đổi
}, [userId]);
```

---

### 🟡 Stale Closure — useEffect Deps thiếu
**Vi phạm:** ISC 1.6
**Tại sao nguy hiểm:** Effect dùng giá trị cũ → bug khó detect.

❌ Sai:
```tsx
const [count, setCount] = useState(0);

useEffect(() => {
  const timer = setInterval(() => {
    console.log(count); // luôn log 0 vì count bị capture lúc mount
  }, 1000);
  return () => clearInterval(timer);
}, []); // deps rỗng — stale closure
```

✅ Đúng:
```tsx
useEffect(() => {
  const timer = setInterval(() => {
    setCount(prev => prev + 1); // dùng functional update, không cần capture count
  }, 1000);
  return () => clearInterval(timer);
}, []); // OK vì không dùng count trực tiếp
```

---

### 🟡 Thiếu Error Boundary
**Vi phạm:** ISC 1.5 (error handling)
**Tại sao nguy hiểm:** Một component throw lỗi → white screen toàn app.

❌ Sai:
```tsx
// Không có ErrorBoundary → lỗi render crash toàn app
ReactDOM.render(<App />, document.getElementById('root'));
```

✅ Đúng:
```tsx
class ErrorBoundary extends React.Component {
  state = { hasError: false };
  static getDerivedStateFromError() { return { hasError: true }; }
  componentDidCatch(error: Error, info: React.ErrorInfo) {
    logger.error('React render error', { error, info });
  }
  render() {
    return this.state.hasError ? <ErrorFallback /> : this.props.children;
  }
}

ReactDOM.render(
  <ErrorBoundary><App /></ErrorBoundary>,
  document.getElementById('root')
);
```

---

### 🟡 Bundle Size — Tree-shaking không đúng
**Vi phạm:** ISC 1.3 (performance)
**Tại sao nguy hiểm:** Bundle >1MB → Time to Interactive chậm.

❌ Sai:
```ts
import _ from 'lodash';                        // import toàn bộ ~70KB
import moment from 'moment';                   // import toàn bộ ~230KB
import { Button } from '@mui/material';        // không tree-shake nếu cấu hình sai
```

✅ Đúng:
```ts
import debounce from 'lodash/debounce';        // chỉ lấy function cần
import { format } from 'date-fns';            // date-fns tree-shakeable
import Button from '@mui/material/Button';    // import trực tiếp
```

---

### 🟡 Accessibility Cơ bản
**Vi phạm:** ISC 1.3 (quality)

❌ Sai:
```tsx
<img src={avatar} />                    {/* thiếu alt */}
<div onClick={handleClick}>Click</div> {/* không phải button → không keyboard */}
<button onClick={close}>X</button>     {/* không có aria-label */}
```

✅ Đúng:
```tsx
<img src={avatar} alt="User avatar" />
<button onClick={handleClick}>Click me</button>  {/* dùng button thật */}
<button onClick={close} aria-label="Close dialog">×</button>
```

---

### 🟡 i18n — Hardcode String
**Vi phạm:** ISC 1.3 (maintainability)

❌ Sai:
```tsx
<Text>Xin chào, {name}</Text>
<Button>Đăng nhập</Button>
toast.error('Đã xảy ra lỗi, vui lòng thử lại');
```

✅ Đúng:
```tsx
const { t } = useTranslation();
<Text>{t('home.greeting', { name })}</Text>
<Button>{t('auth.login')}</Button>
toast.error(t('common.error.generic'));
```

---

### 🟡 Thiếu Loading / Error State
**Vi phạm:** ISC 3.x (error handling UX)

❌ Sai:
```tsx
const { data } = useQuery({ queryKey: ['orders'], queryFn: fetchOrders });
return <OrderList orders={data} />; // nếu data undefined → crash / màn trắng
```

✅ Đúng:
```tsx
const { data, isLoading, error } = useQuery({ queryKey: ['orders'], queryFn: fetchOrders });

if (isLoading) return <SkeletonList />;
if (error) return <ErrorState message={error.message} onRetry={refetch} />;
if (!data?.length) return <EmptyState />;
return <OrderList orders={data} />;
```

---

### 🔵 SEO — Meta Tags (Public Pages)
**Vi phạm:** best practice web

❌ Sai:
```tsx
// Không có title, description, OG tags
export default function ProductPage() {
  return <div>...</div>;
}
```

✅ Đúng:
```tsx
// Next.js
import Head from 'next/head';
<Head>
  <title>{product.name} | MyApp</title>
  <meta name="description" content={product.description} />
  <meta property="og:title" content={product.name} />
  <meta property="og:image" content={product.imageUrl} />
</Head>
```

---

### 🔵 Form Validation — Chỉ validate FE
**Vi phạm:** ISC 1.5 (validate at boundary)

❌ Sai:
```tsx
// Chỉ validate ở form, không xử lý lỗi từ server
const onSubmit = async (data) => {
  await api.createOrder(data); // nếu server trả validation error → không xử lý
};
```

✅ Đúng:
```tsx
const onSubmit = async (data) => {
  try {
    await api.createOrder(data);
  } catch (err) {
    if (err.response?.data?.error?.code === 'INVALID_QUANTITY') {
      form.setError('quantity', { message: err.response.data.error.message });
    } else {
      toast.error(t('common.error.generic'));
    }
  }
};
```

---

## Cách trình bày kết quả

### Tổng quan
- Số file thay đổi, loại thay đổi (feat/fix/refactor...)
- Đánh giá chung: **Pass** / **Cần cải thiện** / **Có vấn đề nghiêm trọng**

### Vấn đề phát hiện

| Mức | Ký hiệu | Ý nghĩa |
| --- | ------- | ------- |
| Critical | 🔴 | Vi phạm bảo mật, hardcode secret, lỗi gây crash |
| Warning | 🟡 | Không đúng convention, thiếu error handling, performance risk |
| Suggestion | 🔵 | Cải thiện code quality, readability, SEO |

Với **mỗi vấn đề**, trình bày:
1. File và dòng code vi phạm (trích từ diff)
2. Lý do vi phạm tiêu chuẩn nào (ISC x.x / OWASP)
3. Code gợi ý fix cụ thể

### Điểm tốt
Liệt kê những gì code đang làm đúng theo chuẩn ISC.

---

### Git & Commit Checklist

- [ ] Không có `console.log` / `debugger` còn sót
- [ ] Không có code commented-out không cần thiết
- [ ] Không có credential / API key hardcode trong source
- [ ] File thay đổi đúng với scope của branch hiện tại

$ARGUMENTS
