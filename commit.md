---
description: Tạo git commit theo Conventional Commits convention của dự án
---

Format chuẩn message (**tối đa 72 ký tự toàn bộ**, bao gồm type, scope, description và AI tag):
`type(scope): description [AI]`
— imperative mood, chữ thường, không dấu chấm

- sử dụng chính xác tag "[AI]"
- **72 ký tự là giới hạn tổng**, tính cả `type(scope): `, description và ` [AI]`

Types: `feat` ✨ tính năng mới (MINOR) · `fix` 🐛 sửa lỗi (PATCH) · `hotfix` 🔥 lỗi production (PATCH) · `refactor` ♻️ cải thiện code · `test` 🧪 test cases · `docs` 📝 tài liệu · `chore` ⚙️ config/CI/CD · `style` 💅 format/lint

Nếu "$ARGUMENTS" không rỗng:

- Validate message "$ARGUMENTS" theo format trên
- Kiểm tra tổng độ dài không vượt 72 ký tự
- Nếu đúng: chạy `git commit -m "$ARGUMENTS"`
- Nếu sai: chỉ ra lỗi, đề xuất sửa trên text, không đọc file, không commit

Nếu "$ARGUMENTS" rỗng:

- Chạy `git status` và `git diff --staged` (nếu chưa staged thì `git diff`)
- **Luôn luôn** đề xuất 1 commit message tổng (≤ 72 ký tự) cho toàn bộ thay đổi
- Đếm số lines diff:

  **Nếu ≤ 400 lines:**
  - Hiển thị message tổng đề xuất
  - Hỏi xác nhận (y) rồi commit tất cả

  **Nếu > 400 lines:**
  - ⚠️ Cảnh báo vượt quá 400 lines (giới hạn mỗi lần merge)
  - Hiển thị message tổng đề xuất (option 0 hoặc cuối danh sách)
  - Gợi ý tách thành các nhóm file/feature liên quan, mỗi nhóm là 1 commit với message đề xuất và danh sách file cụ thể, ví dụ:
    ```
    1. feat(order): add order create step contract [AI] — gồm: StepContract.tsx, StepContract.styles.ts, useStepContract.ts
    2. feat(order): add order info step ui [AI] — gồm: StepOrderInfo.tsx, StepOrderInfo.styles.ts
    3. feat(order): add order service and api hooks [AI] — gồm: orderService.ts, orderEndPoint.ts, useApiOrder.ts
    0. feat(order): add order create screen with contract and info steps [AI] — commit tổng tất cả file
    ```
  - Hỏi user nhập số (1, 2, 3... hoặc 0 cho commit tổng) để chọn nhóm muốn commit trước
  - Sau khi user chọn số, stage đúng các file thuộc nhóm đó và commit
