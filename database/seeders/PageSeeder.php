<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Câu chuyện Đàn Hương Chay',
                'slug' => 'gioi-thieu',
                'template' => 'about',
                'image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80',
                'content' => '<p>Đàn Hương Chay được tạo nên từ mong muốn có một nơi dùng bữa nhẹ nhàng, nơi món chay không chỉ là lựa chọn ăn uống mà còn là cách trở về với sự cân bằng.</p><h2>Triết lý ẩm thực chay</h2><p>Chúng tôi tin món chay ngon không cần cầu kỳ quá mức. Rau củ đúng mùa, nấm tươi, đậu hũ và hạt được xử lý cẩn thận có thể tạo nên một bữa ăn đầy đủ, thơm lành và sâu vị.</p><h2>Cam kết nguyên liệu</h2><p>Mỗi món được nêm vừa phải, hạn chế dầu nặng, ưu tiên vị ngọt tự nhiên và sự cân bằng giữa màu sắc, kết cấu, hương thơm.</p>',
                'meta_title' => 'Giới thiệu Đàn Hương Chay | Câu chuyện thương hiệu',
                'meta_description' => 'Tìm hiểu câu chuyện Đàn Hương Chay, triết lý ẩm thực chay thanh lành, không gian quán và cam kết nguyên liệu sạch.',
                'meta_keywords' => 'giới thiệu Đàn Hương Chay, triết lý ăn chay, nhà hàng chay sạch',
            ],
            [
                'title' => 'Chính sách đặt bàn',
                'slug' => 'chinh-sach-dat-ban',
                'template' => 'default',
                'content' => '<p>Khách hàng có thể đặt bàn qua form website hoặc hotline. Đội ngũ Đàn Hương Chay sẽ liên hệ xác nhận trước giờ dùng bữa.</p>',
                'meta_title' => 'Chính sách đặt bàn | Đàn Hương Chay',
                'meta_description' => 'Thông tin chính sách đặt bàn tại Đàn Hương Chay.',
                'meta_keywords' => 'chính sách đặt bàn, Đàn Hương Chay',
            ],
            [
                'title' => 'Chính sách bảo mật',
                'slug' => 'chinh-sach-bao-mat',
                'template' => 'default',
                'content' => '<p>Thông tin khách hàng gửi qua website chỉ được dùng cho mục đích tư vấn, xác nhận đặt bàn và chăm sóc dịch vụ.</p>',
                'meta_title' => 'Chính sách bảo mật | Đàn Hương Chay',
                'meta_description' => 'Chính sách bảo mật thông tin khách hàng của Đàn Hương Chay.',
                'meta_keywords' => 'chính sách bảo mật, Đàn Hương Chay',
            ],
            [
                'title' => 'Điều khoản sử dụng',
                'slug' => 'dieu-khoan-su-dung',
                'template' => 'default',
                'content' => '<p>Khi sử dụng website, khách hàng đồng ý cung cấp thông tin chính xác khi đặt bàn hoặc gửi liên hệ.</p>',
                'meta_title' => 'Điều khoản sử dụng | Đàn Hương Chay',
                'meta_description' => 'Điều khoản sử dụng website Đàn Hương Chay.',
                'meta_keywords' => 'điều khoản sử dụng, Đàn Hương Chay',
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page + ['is_active' => true]);
        }
    }
}
