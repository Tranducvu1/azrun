# SportShop - Đồ Án TMĐT Thể Thao

Website thương mại điện tử thể thao (tương tự imsports.vn) xây dựng bằng **Laravel 13 + Tailwind CSS + Alpine.js**.

## Tính năng

### Frontend (khách hàng)
- Trang chủ: banner slider, danh mục, flash sale, sản phẩm mới/hot, tin tức
- Danh mục sản phẩm: lọc theo category, brand, giá, sort
- Chi tiết sản phẩm: gallery ảnh, chọn size, thêm giỏ
- Giỏ hàng + Thanh toán COD
- Đăng ký / Đăng nhập / Tài khoản
- Blog tin tức
- Trang thương hiệu

### Admin Panel (`/admin`)
- Dashboard thống kê
- CRUD Sản phẩm, Danh mục, Thương hiệu
- Quản lý Đơn hàng (cập nhật trạng thái)
- CRUD Banner, Bài viết, Mã giảm giá

## Cài đặt

```bash
cd azrun
composer install
cp .env.example .env   # nếu chưa có
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Truy cập: http://127.0.0.1:8000

## Tài khoản admin

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@sportshop.vn | password |

> Database khởi tạo **trống** — không có sản phẩm/danh mục mẫu. Vào `/admin` để thêm dữ liệu thật.

## Cấu trúc chính

```
app/Http/Controllers/     # Controllers frontend + admin
app/Models/               # Eloquent models
database/seeders/         # AdminSeeder (chỉ tạo tài khoản admin + brand mặc định)
resources/views/          # Blade templates
routes/web.php            # Routes
```

## Database

SQLite mặc định (`database/database.sqlite`). Đổi sang MySQL trong `.env` nếu cần.

## Nhập dữ liệu sản phẩm qua Admin

1. **Danh mục** (`/admin/categories`) — thêm hạng mục cha/con
2. **Sản phẩm** (`/admin/products`) — tên, danh mục, giá bán, size, mô tả, ảnh
3. Size nhập dạng: `39, 40, 41, 42` hoặc `S, M, L, XL`

Ảnh: upload file hoặc dán URL trực tiếp trong form admin.
