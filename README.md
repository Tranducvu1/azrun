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

## Tài khoản demo

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@sportshop.vn | password |
| Khách | customer@test.com | password |

## Cấu trúc chính

```
app/Http/Controllers/     # Controllers frontend + admin
app/Models/               # Eloquent models
database/seeders/         # DemoDataSeeder (15 SP, 17 brands, 7 categories...)
resources/views/          # Blade templates
routes/web.php            # Routes
```

## Database

SQLite mặc định (`database/database.sqlite`). Đổi sang MySQL trong `.env` nếu cần.

## Ảnh sản phẩm

Demo dùng placeholder từ `placehold.co`. Admin có thể nhập URL ảnh khi thêm/sửa sản phẩm.
