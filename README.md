# Đàn Hương Chay

Website Laravel cho thương hiệu quán chay **Đàn Hương Chay**, dùng chung một thực đơn và hệ thống quản trị cho nhiều cơ sở. Dự án có frontend SEO, admin panel, quản lý nội dung động, đặt bàn, liên hệ, chat online, song ngữ Việt/Anh, phân quyền admin và tối ưu ảnh.

## Công nghệ

- Laravel 13
- PHP 8.3+
- Blade template theo MVC
- Tailwind CSS 4 qua Vite
- SQLite mặc định cho local, có thể chuyển MySQL production
- PHPUnit feature tests

## Cài đặt local

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run dev
php artisan serve
```

Mặc định app chạy tại:

```text
http://127.0.0.1:8000
```

Nếu dùng SQLite và file database chưa tồn tại:

```bash
type nul > database/database.sqlite
php artisan migrate --seed
```

## Build production

```bash
npm run build
php artisan optimize
```

Nếu deploy shared host không chạy được command, build local rồi upload cả thư mục `public/build`.

## Tài khoản admin mẫu

```text
Email: admin@danhuongchay.vn
Password: password
Role: admin
RBAC role: Super Admin
```

Chỉ user có `role = admin` mới truy cập được `/admin`.

## Route chính

- `/` - Trang chủ
- `/gioi-thieu` - Giới thiệu
- `/khong-gian` - Không gian quán
- `/thuc-don` - Thực đơn, tìm kiếm và lọc danh mục
- `/mon-an/{slug}` - Chi tiết món ăn
- `/blog` - Danh sách bài viết
- `/blog/{slug}` - Chi tiết bài viết
- `/dat-ban` - Form đặt bàn
- `/lien-he` - Form liên hệ
- `/quan-chay-hai-phong` - Landing SEO local Hải Phòng
- `/quan-chay-buon-ma-thuot` - Landing SEO local Buôn Ma Thuột
- `/dat-tiec-chay` - Trang trung gian đặt tiệc/mâm chay
- `/dat-tiec-chay-hai-phong` - Landing đặt tiệc Hải Phòng
- `/dat-tiec-chay-buon-ma-thuot` - Landing đặt tiệc Buôn Ma Thuột
- `/sitemap.xml` - Sitemap động
- `/robots.txt` - Robots động
- `/login` - Đăng nhập admin

## Admin Panel

Admin nằm tại `/admin`, có sidebar, topbar, dashboard, bảng dữ liệu, form CRUD và thông báo session flash.

Các module chính:

- `/admin/dashboard` - Dashboard, thống kê, lọc theo khoảng thời gian
- `/admin/settings` - Cài đặt website
- `/admin/identity` - Logo, favicon, OG image, ảnh nền mặc định
- `/admin/branches` - Quản lý cơ sở, địa chỉ, hotline, giờ mở cửa, khung giờ đặt bàn, map
- `/admin/banners` - Banner trang chủ
- `/admin/categories` - Danh mục món ăn/bài viết
- `/admin/dishes` - Món ăn/sản phẩm
- `/admin/posts` - Bài viết
- `/admin/pages` - Trang tĩnh
- `/admin/menus` - Menu điều hướng
- `/admin/gallery` - Không gian quán theo cơ sở
- `/admin/promotions` - Ưu đãi, popup, quảng cáo
- `/admin/testimonials` - Review khách hàng
- `/admin/reservations` - Đặt bàn
- `/admin/contacts` - Liên hệ
- `/admin/chats` - Chat online
- `/admin/seo` - SEO tổng thể
- `/admin/translations/settings` - Cài đặt dịch tự động
- `/admin/users` - Tài khoản admin
- `/admin/roles` - Phân quyền admin

## Phân quyền admin RBAC

Dự án dùng RBAC tự code, không phụ thuộc package ngoài.

Bảng liên quan:

- `roles`: nhóm vai trò
- `permissions`: từng quyền thao tác
- `permission_role`: gán quyền cho vai trò
- `users.role_id`: gán tài khoản với vai trò

Role mẫu từ seeder:

- `Super Admin`: toàn quyền
- `Quản lý nội dung`: món ăn, danh mục, bài viết, trang tĩnh, banner, ưu đãi, gallery
- `Lễ tân`: đặt bàn, liên hệ, chat online
- `Marketing`: banner, ưu đãi, bài viết, SEO, dịch thuật
- `Viewer`: chỉ xem

Route admin được chặn theo từng action. Ví dụ:

- `dishes.view`
- `dishes.create`
- `dishes.update`
- `dishes.delete`

Sidebar và một số nút quản trị cũng ẩn theo quyền hiện tại. Middleware `permission` được đăng ký trong `bootstrap/app.php`.

Ngoài quyền chức năng, tài khoản admin có thể bị giới hạn theo cơ sở bằng `users.branch_id` trong `/admin/users`. Nếu để trống, admin thấy dữ liệu toàn hệ thống; nếu chọn một cơ sở, admin chỉ thấy đặt bàn, liên hệ, chat, dashboard và thông báo của cơ sở đó.

## Database chính

- `users`
- `roles`
- `permissions`
- `permission_role`
- `site_settings`
- `branches`
- `categories`
- `dishes`
- `posts`
- `pages`
- `navigation_menus`
- `banners`
- `promotions`
- `testimonials`
- `gallery_images`
- `reservations`
- `contacts`
- `chat_sessions`
- `chat_messages`
- Các bảng translation cho category, dish, post, page, banner, promotion, testimonial, navigation menu, gallery

## Multi-branch

Website dùng chung thương hiệu, menu và bảng giá cho cả hai cơ sở. Những phần cần tách theo cơ sở nằm trong `/admin/branches`:

- Tên cơ sở
- Thành phố
- Địa chỉ
- Hotline, số điện thoại, email
- Giờ mở cửa hiển thị
- Khung giờ nhận đặt bàn, ví dụ `09:00-14:00,16:00-21:00`
- Giờ cuối nhận đặt bàn, ví dụ `20:30`
- Google Map iframe
- Ảnh đại diện cơ sở
- SEO local
- Facebook URL và Zalo URL riêng cho các nút liên hệ nhanh theo cơ sở

Form đặt bàn bắt buộc chọn cơ sở và validate theo khung giờ của cơ sở đã chọn.

## Trang chủ editorial

Trang chủ đã được nâng theo hướng editorial/restaurant:

- Hero ảnh lớn, CTA rõ: `Đặt bàn`, `Xem thực đơn`, `Đặt tiệc / mâm chay`
- Card giữ chỗ hiển thị các cơ sở để khách chọn đặt bàn
- Section món yêu thích dạng “thực đơn đặc biệt”
- Ưu đãi trang chủ dạng khối nổi bật, tối đa 4 ưu đãi active
- Section “Vì sao chọn quán” với 4 card: nguyên liệu sạch, nêm nếm tinh tế, không gian yên, đặt bàn nhanh
- Gallery không gian quán
- Review khách hàng dạng slider
- Blog mới nhất

Các style chính nằm trong `resources/css/app.css`, view chính là `resources/views/home.blade.php`.

## UX mobile và liên hệ nhanh

Header mobile có nút đổi ngôn ngữ `VI/EN` hiển thị trực tiếp cạnh nút menu hamburger, không cần mở menu mới đổi ngôn ngữ.

Trang `Giới thiệu` và `Liên hệ` dùng partial `resources/views/partials/branch-social-actions.blade.php` để hiển thị nút nhanh theo từng cơ sở:

- Gọi điện theo hotline/số điện thoại cơ sở
- Zalo theo `zalo_url` của cơ sở, fallback về setting chung
- Facebook theo `facebook_url` của cơ sở, fallback về setting chung
- Đặt bàn đúng cơ sở

Footer vẫn giữ một bộ icon social chung cho toàn thương hiệu.

## Đặt bàn

Form đặt bàn lưu vào `reservations` với `status = pending`.

Luồng đặt bàn hỗ trợ:

- Chọn cơ sở
- Validate ngày/giờ đặt theo cơ sở
- Chặn giờ đã qua nếu đặt trong ngày hôm nay
- Chỉ nhận giờ nằm trong khung mở cửa/khung nhận đặt bàn
- Có thể cấu hình giờ mở cửa và giờ nhận đặt bàn trong admin cơ sở
- Form đã bỏ email để gọn hơn

## Chat online

Cập nhật multi-branch: widget chat có trường chọn cơ sở; `chat_sessions.branch_id` lưu cơ sở phụ trách hội thoại. Admin `/admin/chats` có cột cơ sở và filter theo cơ sở, chuông thông báo hiển thị thêm badge cơ sở cho chat, đặt bàn và liên hệ.

Frontend có widget chat nổi. Khách gửi tên, số điện thoại và nội dung cần tư vấn.

Dữ liệu lưu vào:

- `chat_sessions`
- `chat_messages`

Admin quản lý tại `/admin/chats`.

Cơ chế chống spam:

- Rate limit bắt đầu chat
- Rate limit gửi tin nhắn
- Honeypot field ẩn
- Admin có polling để cập nhật tin nhắn

Sidebar và chuông admin hiển thị số tin chat chưa đọc, đặt bàn pending và liên hệ mới.

## Upload ảnh và tối ưu ảnh

Upload xử lý qua `App\Services\UploadService`.

Hỗ trợ:

- Upload ảnh JPG, PNG, WEBP, SVG
- Resize ảnh raster
- Tạo WebP
- Tạo nhiều kích thước responsive: `thumb`, `card`, `large`, `hero`
- Fallback lưu ảnh gốc nếu host thiếu GD/WebP
- Xóa ảnh cũ khi cập nhật ảnh mới
- Helper hiển thị ảnh: `media_url`, `media_variant_url`, `media_srcset`

Cấu hình trong `.env`:

```env
UPLOAD_MAX_IMAGE_KB=10240
UPLOAD_RESIZE_WIDTH=1600
UPLOAD_RESIZE_HEIGHT=1600
UPLOAD_WEBP_QUALITY=82
UPLOAD_THUMB_WIDTH=360
UPLOAD_CARD_WIDTH=720
UPLOAD_LARGE_WIDTH=1200
UPLOAD_HERO_VARIANT_WIDTH=1920
```

Regenerate ảnh cũ:

```bash
php artisan media:regenerate-responsive-images
php artisan media:regenerate-responsive-images --force
```

## Shared hosting upload mode

Nếu shared host không chạy được `php artisan storage:link` hoặc không hỗ trợ symlink, bật upload trực tiếp vào `public/uploads`:

```env
UPLOAD_DISK=public_uploads
APP_URL=https://danhuongchay.com
```

Khi đó ảnh mới sẽ lưu vào:

```text
public/uploads
```

và hiển thị bằng URL:

```text
https://danhuongchay.com/uploads/...
```

Trên host cần tạo thư mục `public/uploads` nếu chưa có và cấp quyền ghi cho PHP, thường là `755` hoặc `775`.

Nếu có ảnh cũ trong `storage/app/public`, copy các thư mục con như `settings`, `users`, `dishes`, `banners`, `posts`, `pages`, `categories`, `gallery`, `promotions` sang `public/uploads`.

Nếu host cache config Laravel mà không chạy được command, xóa thủ công:

```text
bootstrap/cache/config.php
```

Kiểm tra storage local/production:

```bash
php artisan app:check-storage --write
```

## SEO

Đã triển khai:

- Title, meta description, meta keywords riêng
- Canonical URL
- Open Graph cho Facebook/Zalo
- Twitter Card
- Schema JSON-LD `Restaurant`
- Schema JSON-LD `Menu`
- Schema JSON-LD `Article`
- Schema JSON-LD `FAQPage` cho landing SEO local
- Schema `MenuItem` cho chi tiết món
- Breadcrumb và Breadcrumb schema
- Sitemap động
- Robots.txt động
- Slug tự sinh
- Alt text cho ảnh
- Lazy loading ảnh ngoài hero
- Landing SEO local cho Hải Phòng và Buôn Ma Thuột

## Conversion tracking

Admin có thể dán Google Analytics, Google Search Console verification và Facebook Pixel trong `/admin/seo`.

Frontend tự gửi event nếu `gtag` hoặc `fbq` tồn tại.

Một số event:

- `click_call`
- `click_zalo`
- `click_facebook`
- `open_chat`
- `start_chat`
- `click_dish_card`
- `view_dish`
- `click_dish_reservation`
- `click_dish_catering`
- `submit_reservation`
- `reservation_success`
- `submit_contact`
- `contact_success`
- `click_catering_reservation`

## Song ngữ Việt / Anh

Website có nền tảng song ngữ:

- Tiếng Việt dùng route cũ: `/`, `/thuc-don`, `/mon-an/{slug}`
- Tiếng Anh dùng prefix `/en`: `/en`, `/en/menu`, `/en/dishes/{slug}`
- Các landing local/đặt tiệc cũng có bản tiếng Anh: `/en/vegetarian-restaurant-hai-phong`, `/en/vegetarian-restaurant-buon-ma-thuot`, `/en/vegetarian-catering`, `/en/vegetarian-catering-hai-phong`, `/en/vegetarian-catering-buon-ma-thuot`
- Middleware `locale` set ngôn ngữ theo route
- Text giao diện nằm trong:
  - `resources/lang/vi/site.php`
  - `resources/lang/en/site.php`
  - `resources/lang/vi/validation.php`
  - `resources/lang/en/validation.php`
- Nội dung động có bảng dịch riêng cho danh mục, món ăn, bài viết, trang tĩnh, banner, ưu đãi, review, menu điều hướng, gallery

Admin có tab `Tiếng Việt` / `English` trong các form nội dung. Nút `Copy từ tiếng Việt` chỉ copy nội dung gốc sang tab English để admin sửa nhanh.

## Dịch tự động

Admin cấu hình tại:

```text
/admin/translations/settings
```

Provider hỗ trợ:

- DeepL API
- Microsoft Translator API

Kết quả dịch tự động chỉ đổ vào form để admin review, không tự lưu thẳng database.

### DeepL

1. Vào https://www.deepl.com/pro-api
2. Chọn gói DeepL API phù hợp
3. Copy API key
4. Dán vào `/admin/translations/settings`
5. Kiểm tra kết nối/quota trước khi dịch

### Microsoft Translator

1. Vào Azure Portal
2. Tạo resource `Translator`
3. Vào `Keys and Endpoint`
4. Copy `Key`, `Region`, `Endpoint`
5. Dán vào `/admin/translations/settings`

Nếu gặp lỗi local `cURL error 60`, cấu hình CA bundle trong `php.ini`:

```ini
curl.cainfo="C:\php\extras\ssl\cacert.pem"
openssl.cafile="C:\php\extras\ssl\cacert.pem"
```

## Validation tiếng Việt

Dự án đã thêm file dịch validation:

- `resources/lang/vi/validation.php`
- `resources/lang/en/validation.php`

Các lỗi như `validation.confirmed`, `validation.uploaded` sẽ hiển thị tiếng Việt rõ ràng, ví dụ:

```text
mật khẩu xác nhận không khớp.
avatar tải lên thất bại. Vui lòng kiểm tra dung lượng file hoặc thử lại.
```

## Production notes

Nếu sau khi deploy host mất CSS và browser báo đang tải Vite từ `http://[::1]:5173`, nguyên nhân là production vẫn đang dùng dev server Vite. Cần:

```bash
npm run build
```

Upload thư mục:

```text
public/build
```

Không chạy `npm run dev` trên production.

Nếu đổi `.env`, config, lang hoặc view mà host vẫn chưa nhận:

```bash
php artisan optimize:clear
```

Nếu shared host không chạy command, xóa thủ công các file cache trong:

```text
bootstrap/cache/
```

## Test

```bash
php artisan test
```

Trước khi deploy các thay đổi frontend:

```bash
npm run build
php artisan test
```

## Vận hành đặt bàn trong admin

Module `/admin/reservations` dùng như bảng trực ca cho lễ tân từng cơ sở:

- Mặc định mở các đơn hôm nay ở trạng thái `pending` và `confirmed`.
- Các nhóm ưu tiên gồm `Cần gọi ngay`, `Chờ gọi xác nhận`, `Sắp đến giờ`, `Đã giữ bàn`, `Đã qua giờ chưa chốt`, `Đã kết thúc`.
- Đơn pending chờ quá 30 phút hoặc gần giờ dùng bữa trong 90 phút được đẩy vào nhóm cần gọi ngay.
- Nhân viên có thể thao tác nhanh: gọi khách, đánh dấu đã gọi, giữ bàn, khách đã đến, hủy.
- Mỗi thao tác được lưu vào `reservation_activities` để xem lịch sử xử lý trong trang chi tiết đặt bàn.
- Dashboard admin có khối `Ca đặt bàn hôm nay`; chuông thông báo và badge sidebar đều lọc theo `users.branch_id`.
- Các trường workflow mới trong `reservations`: `last_contacted_at`, `confirmed_at`, `completed_at`, `cancelled_at`, `contact_attempts`.
