# Đàn Hương Chay

Website Laravel cho quán chay **Đàn Hương Chay** với giao diện Blade + Tailwind CSS, dữ liệu mẫu, SEO meta, schema JSON-LD, sitemap, robots, form liên hệ và form đặt bàn lưu database.

Project đã có Admin Panel tại `/admin` để quản trị settings, banner, danh mục, món ăn, bài viết, trang tĩnh, menu, đặt bàn, liên hệ, SEO và tài khoản admin.

## Công nghệ

- Laravel 13
- PHP 8.3+
- SQLite mặc định để chạy nhanh local
- Blade template theo MVC
- Tailwind CSS 4 qua Vite
- PHPUnit feature tests

## Cài đặt

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

Nếu dùng SQLite, Laravel đã cấu hình:

```env
DB_CONNECTION=sqlite
```

Nếu file database chưa tồn tại:

```bash
type nul > database/database.sqlite
php artisan migrate --seed
```

## Build production

```bash
npm run build
php artisan optimize
```

## Chạy test

```bash
php artisan test
```

## Route chính

- `/` - Trang chủ
- `/gioi-thieu` - Giới thiệu
- `/thuc-don` - Thực đơn, tìm kiếm và lọc danh mục
- `/mon-an/{slug}` - Chi tiết món ăn
- `/blog` - Danh sách bài viết
- `/blog/{slug}` - Chi tiết bài viết
- `/dat-ban` - Form đặt bàn
- `/lien-he` - Form liên hệ
- `/sitemap.xml` - Sitemap tự động
- `/robots.txt` - Robots.txt động
- `/login` - Đăng nhập admin
- `/admin/dashboard` - Dashboard quản trị
- `/admin/settings` - Cài đặt website
- `/admin/identity` - Logo và nhận diện
- `/admin/branches` - Quản lý cơ sở, giờ mở cửa, giờ nhận đặt bàn và map
- `/admin/banners` - Banner trang chủ
- `/admin/testimonials` - Review khách hàng
- `/admin/promotions` - Khuyến mãi, quảng cáo và popup
- `/admin/categories` - Danh mục
- `/admin/dishes` - Món ăn / sản phẩm
- `/admin/posts` - Bài viết
- `/admin/pages` - Trang tĩnh
- `/admin/menus` - Menu điều hướng
- `/admin/reservations` - Đặt bàn
- `/admin/contacts` - Liên hệ
- `/admin/chats` - Chat online
- `/admin/seo` - SEO tổng thể
- `/admin/users` - Tài khoản admin

## Tài khoản admin mẫu

```text
Email: admin@danhuongchay.vn
Password: password
Role: admin
```

Chỉ user có `role = admin` mới truy cập được `/admin`.

## Database

Các bảng chính:

- `categories`: danh mục menu/blog
- `dishes`: món ăn, giá, ảnh, SEO riêng
- `posts`: bài blog, nội dung, SEO riêng
- `reservations`: yêu cầu đặt bàn
- `contacts`: tin nhắn liên hệ
- `site_settings`: cài đặt website, nhận diện, SEO
- `branches`: cơ sở Hải Phòng/Buôn Ma Thuột, hotline, địa chỉ, khung giờ nhận đặt bàn, map và SEO local
- `banners`: banner trang chủ
- `testimonials`: review khách hàng hiển thị dạng slider ở trang chủ
- `promotions`: chương trình nổi bật ở trang chủ và popup thông báo/quảng cáo
- `pages`: trang tĩnh
- `navigation_menus`: menu header/footer
- `users`: tài khoản đăng nhập, có `role` và `avatar`

Seeder tạo sẵn:

- 8+ món chay mẫu
- 5 bài blog mẫu
- 10 review khách hàng mẫu
- 3 khuyến mãi/quảng cáo mẫu, gồm popup bật/tắt được trong admin
- Danh mục menu và blog

## Cấu trúc đáng chú ý

```text
app/Http/Controllers/
app/Http/Requests/
app/Models/
app/Services/SeoService.php
app/Support/GeneratesSlugs.php
database/migrations/
database/seeders/
resources/views/layouts/app.blade.php
resources/views/partials/
resources/views/admin/
resources/views/menu/
resources/views/blog/
resources/css/app.css
routes/web.php
```

## SEO đã triển khai

- Title, meta description, meta keywords riêng từng trang
- Canonical URL
- Open Graph cho Facebook/Zalo
- Twitter Card
- Schema JSON-LD `Restaurant`
- Schema JSON-LD `Menu`
- Schema JSON-LD `Article`
- Breadcrumb và Breadcrumb schema cho trang chi tiết
- Sitemap tự động từ route, món ăn và bài viết
- Robots.txt
- Slug tự sinh trong model
- Alt text cho ảnh
- Lazy loading ảnh ngoài hero
- Robots.txt có thể chỉnh trong admin SEO
- Sitemap động lấy trang tĩnh, danh mục active, món active và bài active

## Admin Panel

Các nhóm quản trị:

- Cài đặt website: tên website, slogan, mô tả, hotline, email, địa chỉ, giờ mở cửa, mạng xã hội, map iframe, footer.
- Logo & nhận diện: logo header, logo footer, favicon, OG image, ảnh nền mặc định.
- Banner: CRUD, bật/tắt, thứ tự, upload ảnh.
- Review khách hàng: CRUD, rating, avatar, bật/tắt, sắp xếp; frontend chạy slider tự động.
- Khuyến mãi / Popup: CRUD chương trình sale/quảng cáo, chọn vị trí trang chủ hoặc popup, chọn mẫu `split`, `center`, `minimal`, bảng màu nhấn, link nhanh, thời gian bắt đầu/kết thúc, bật/tắt và chế độ popup chỉ hiện một lần. Thứ tự không được trùng trong cùng vị trí. Trang chủ ưu tiên hiển thị tối đa 4 khuyến mãi active theo thứ tự.
- Danh mục: CRUD, loại `dish` hoặc `post`, slug, ảnh, SEO, trạng thái.
- Món ăn: CRUD, tìm kiếm, lọc danh mục/trạng thái, ảnh đại diện, gallery nhiều ảnh, nổi bật, active, SEO.
- Bài viết: CRUD, thumbnail, nội dung HTML qua textarea, nổi bật, active, hẹn ngày đăng, SEO.
- Trang tĩnh: CRUD cho giới thiệu, chính sách, điều khoản.
- Menu điều hướng: header/footer, menu cha/con đơn giản, thứ tự, mở tab mới.
- Đặt bàn: xem chi tiết, đổi trạng thái `pending`, `confirmed`, `cancelled`, `completed`, ghi chú nội bộ.
- Liên hệ: xem chi tiết, trạng thái `new`, `read`, `processed`, ghi chú nội bộ.
- Chat online: xem hội thoại khách gửi từ widget, phản hồi tư vấn viên, trạng thái `open`, `pending`, `closed`.
- Chuông thông báo admin: polling `/admin/notifications`, hiển thị tin chat chưa đọc, đặt bàn pending và liên hệ mới.
- Sidebar badge: hiện số pending/new/unread ở Đặt bàn, Liên hệ và Chat online.
- Dashboard: lọc theo khoảng thời gian, thẻ thống kê, biểu đồ tổng quan dễ đọc, xu hướng 14 ngày và danh sách có ảnh.
- Tài khoản admin: thêm/sửa/xóa user, đổi mật khẩu, role `admin` hoặc `user`, không cho xóa chính mình.

## Chat online

Frontend có widget chat nổi ở góc dưới bên phải. Khách gửi tên, số điện thoại và nội dung cần tư vấn. Tin nhắn được lưu vào database:

- `chat_sessions`
- `chat_messages`

Admin vào `/admin/chats` để xem hội thoại và phản hồi. Widget dùng AJAX polling nhẹ, không cần WebSocket nên chạy được ngay với `php artisan serve`.

Chống spam chat:

- Rate limit bắt đầu chat: tối đa 3 lần/phút theo IP.
- Rate limit gửi tin: tối đa 10 tin/phút theo IP + session chat.
- Honeypot field ẩn để chặn bot form đơn giản.

Upload ảnh lưu ở `storage/app/public`, hiển thị qua `public/storage`. Chạy:

```bash
php artisan storage:link
```

### Cau hinh upload anh lon

Gioi han upload mac dinh cua app la 10MB/anh. Anh JPG, PNG, WEBP se duoc tu resize toi da 1600x1600 va luu thanh WEBP de toi uu toc do tai trang; SVG duoc giu nguyen.

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

Moi anh upload se sinh them cac ban WebP responsive nhu `thumb`, `card`, `large`, `hero` tuy profile upload. Frontend dung `srcset/sizes` de mobile tai anh nho hon, desktop tai anh lon hon.

De tao lai thumbnail cho anh cu:

```bash
php artisan media:regenerate-responsive-images
```

Muon ghi de thumbnail da ton tai:

```bash
php artisan media:regenerate-responsive-images --force
```

Neu chay bang PHP CLI / `php artisan serve`, can dam bao PHP cho phep nhan file lon hon 2MB. Chay local co the dung:

```bash
php -d upload_max_filesize=12M -d post_max_size=16M -d memory_limit=256M artisan serve
```

## Multi-branch setup

Website dung mot thuong hieu, mot menu va mot bang gia chung cho ca hai co so. Nhung phan can tach theo co so duoc quan ly trong `/admin/branches`:

- Dia chi, hotline, so dien thoai, email va Google Map iframe rieng.
- Gio mo cua hien thi rieng.
- Khung gio nhan dat ban rieng, vi du `09:00-14:00,16:00-21:00`.
- Gio cuoi nhan dat ban, vi du `20:30`.
- Anh khong gian co the gan voi tung co so trong `/admin/gallery`.

Form dat ban bat buoc chon co so va se validate theo khung gio cua co so da chon. Trang `/khong-gian` hien thi anh theo tung co so tu tren xuong duoi, giup khach xem khong gian ma khong bi bat chon co so qua som.

## Form

Form liên hệ lưu vào bảng `contacts`.

Form đặt bàn lưu vào bảng `reservations` với `status = pending`.

Cả hai form đều dùng Form Request để validate và hiển thị thông báo thành công sau khi submit.

## SEO local va conversion tracking moi

Route SEO local moi:

- `/quan-chay-hai-phong`: landing page cho tu khoa quan chay Hai Phong.
- `/quan-chay-buon-ma-thuot`: landing page cho tu khoa quan chay Buon Ma Thuot.
- `/dat-tiec-chay-hai-phong`: landing page dat tiec chay / mam cung chay.
- `/mam-cung-chay-hai-phong`: redirect ve `/dat-tiec-chay-hai-phong`.

Schema moi:

- `FAQPage` cho cac landing page SEO local.
- `MenuItem` cho trang chi tiet mon an.

Conversion tracking:

Admin co the dan Google Analytics code va Facebook Pixel code trong `/admin/seo`. Frontend se tu gui event neu `gtag` hoac `fbq` da ton tai tren trang.

Mot so event dang co:

- `click_call`: click goi dien.
- `click_zalo`: click Zalo.
- `click_messenger`: click Messenger/Facebook.
- `open_chat`: mo widget chat.
- `start_chat`: bat dau chat.
- `click_dish_card`: click vao card mon.
- `view_dish`: xem trang chi tiet mon.
- `click_dish_reservation`: click dat ban tu trang mon.
- `click_dish_catering`: click dat tiec/mam chay tu trang mon.
- `submit_reservation`: bam gui form dat ban.
- `reservation_success`: dat ban thanh cong sau redirect.
- `submit_contact`: bam gui form lien he.
- `contact_success`: lien he thanh cong sau redirect.
- `click_catering_reservation`: click CTA dat tiec chay.

Co che tracking nam trong `resources/js/app.js` va dung cac attribute `data-track-*`, nen co the gan them vao nut moi ma khong can viet lai JS.

## Song ngu Viet / Anh

Website da co nen tang song ngu:

- Tieng Viet giu route cu, vi du `/`, `/thuc-don`, `/mon-an/{slug}`.
- Tieng Anh dung prefix `/en`, vi du `/en`, `/en/menu`, `/en/dishes/{english-slug}`, `/en/blog/{english-slug}`.
- Middleware `locale` tu set ngon ngu theo route.
- Text giao dien nam trong `resources/lang/vi/site.php` va `resources/lang/en/site.php`.
- Noi dung dong co bang dich:
  - `category_translations`
  - `dish_translations`
  - `post_translations`
  - `page_translations`
  - `banner_translations`
  - `promotion_translations`
  - `testimonial_translations`
  - `navigation_menu_translations`
- Seeder ban dich mau: `EnglishTranslationSeeder`.
- Admin co tab `Tieng Viet` / `English` trong form danh muc, mon an, bai viet, trang tinh, banner, uu dai/popup, review, menu dieu huong, cai dat website va SEO tong the.
- Nut `Copy tu tieng Viet` chi copy noi dung goc sang tab English de admin sua nhanh, khong goi API dich tu dong.
- Admin co trang `/admin/translations/settings` de chon provider dich tu dong: DeepL hoac Microsoft Translator.
- Khi cau hinh provider, tab English co nut `Dich tu dong`; ket qua chi do vao form de review, chua tu luu database.
- Gallery khong can dich tu dong trong admin vi chu yeu la anh.
- He thong kiem tra quota khi provider ho tro API usage. Microsoft Translator xem quota trong Azure Portal.

Sau khi pull code moi tren host, chay:

```bash
php artisan migrate
php artisan db:seed --class=EnglishTranslationSeeder
php artisan db:seed --class=MarketingEnglishTranslationSeeder
npm run build
php artisan optimize:clear
```

Ghi chu: quota DeepL co the thay doi theo loai tai khoan, nen khong hard-code gia dinh theo thang trong web. Admin se doc usage tu DeepL API neu provider ho tro. Nen review lai cac ban dich seed mau va ban dich API truoc khi chay production chinh thuc.

### Lay DeepL API key

1. Vao https://www.deepl.com/pro-api va chon goi `DeepL API Free`.
2. Tao tai khoan DeepL va hoan tat buoc kich hoat theo yeu cau cua DeepL.
3. Vao khu vuc account/API Keys de copy Authentication Key. Key API Free thuong co duoi `:fx`.
4. Vao admin `/admin/translations/settings`, chon provider `DeepL API`, dan key vao `DeepL API key`, bat `Dich tu dong`, roi bam `Luu cai dat`.
5. Bam `Kiem tra ket noi` hoac `Kiem tra quota` truoc khi dung nut `Dich tu dong` trong form noi dung.

### Lay Microsoft Translator key

1. Vao Azure Portal va tao resource `Translator` trong Azure AI services.
2. Chon pricing tier phu hop, vi du `F0` neu tai khoan/resource cua ban du dieu kien.
3. Vao muc `Keys and Endpoint`, copy `Key`, `Region` va `Endpoint`.
4. Vao admin `/admin/translations/settings`, chon provider `Microsoft Translator API`.
5. Dien:
   - `Microsoft Translator key`: key trong Azure.
   - `Azure region`: vi du `southeastasia`, `eastasia`, tuy resource.
   - `Endpoint`: thuong la `https://api.cognitive.microsofttranslator.com`.
6. Bam `Luu cai dat`, sau do `Kiem tra ket noi`.

Neu gap loi local `cURL error 60: SSL certificate problem`, PHP/cURL dang thieu CA bundle. Cach dung:

1. Tai file https://curl.se/ca/cacert.pem
2. Luu vao may, vi du `C:\php\extras\ssl\cacert.pem` hoac thu muc PHP dang dung.
3. Mo file `php.ini` cua PHP dang chay Laravel va them/sua:

```ini
curl.cainfo="C:\php\extras\ssl\cacert.pem"
openssl.cafile="C:\php\extras\ssl\cacert.pem"
```

4. Restart PHP/server roi chay `php artisan optimize:clear`.

Tam thoi khi dev local co the them `DEEPL_VERIFY_SSL=false` vao `.env`, nhung khong nen dung tren production.

## Multi-branch updates (May 2026)

- Added unique sorting guard for branches:
  - Validation: `App\Http\Requests\Admin\BranchRequest` now enforces unique `sort_order`.
  - Database: migration `2026_05_20_235900_add_unique_sort_order_to_branches_table.php` normalizes existing values and adds unique index.
- Added catering hub route:
  - `GET /dat-tiec-chay` (`local.vegetarian-catering`)
  - `GET /dat-tiec-chay-hai-phong`
  - `GET /dat-tiec-chay-buon-ma-thuot`
- Added EN equivalents:
  - `/en/vegetarian-catering`
  - `/en/vegetarian-catering-hai-phong`
  - `/en/vegetarian-catering-buon-ma-thuot`
- About page now renders all active branches with branch-level map/address/contact blocks.
- Reservation page redesigned with editorial/premium layout while keeping dynamic branch time-slot validation intact.
