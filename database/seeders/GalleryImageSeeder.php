<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class GalleryImageSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            [
                'title' => 'Góc bàn gỗ cạnh mảng xanh',
                'slug' => 'goc-ban-go-canh-mang-xanh',
                'description' => 'Góc ngồi ấm, đủ riêng tư cho bữa tối nhẹ hoặc một buổi gặp gỡ nhỏ.',
                'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=85',
                'alt_text' => 'Không gian bàn gỗ và cây xanh trong nhà hàng',
                'location' => 'space',
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Ánh sáng dịu buổi chiều',
                'slug' => 'anh-sang-diu-buoi-chieu',
                'description' => 'Khoảng sáng tự nhiên giúp không gian bữa chay mềm hơn và dễ chịu hơn.',
                'image' => 'https://images.unsplash.com/photo-1552566626-52f8b828add9?auto=format&fit=crop&w=1200&q=85',
                'alt_text' => 'Không gian nhà hàng với ánh sáng dịu',
                'location' => 'space',
                'sort_order' => 2,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Khu bàn nhóm gia đình',
                'slug' => 'khu-ban-nhom-gia-dinh',
                'description' => 'Bố trí thoáng cho nhóm bạn bè, gia đình hoặc các buổi đặt bàn trước.',
                'image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=85',
                'alt_text' => 'Khu bàn nhóm trong nhà hàng chay',
                'location' => 'space',
                'sort_order' => 3,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Quầy trà và góc chờ',
                'slug' => 'quay-tra-va-goc-cho',
                'description' => 'Một góc nhỏ để khách nghỉ chân, dùng trà thảo mộc trước bữa ăn.',
                'image' => 'https://images.unsplash.com/photo-1559329007-40df8a9345d8?auto=format&fit=crop&w=1200&q=85',
                'alt_text' => 'Quầy trà và góc chờ trong nhà hàng',
                'location' => 'space',
                'sort_order' => 4,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Chi tiết gỗ và cây xanh',
                'slug' => 'chi-tiet-go-va-cay-xanh',
                'description' => 'Những chi tiết vật liệu tự nhiên tạo cảm giác gần gũi và yên tĩnh.',
                'image' => 'https://images.unsplash.com/photo-1521017432531-fbd92d768814?auto=format&fit=crop&w=1200&q=85',
                'alt_text' => 'Chi tiết gỗ và cây xanh trong không gian quán',
                'location' => 'space',
                'sort_order' => 5,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Bếp chuẩn bị rau củ tươi',
                'slug' => 'bep-chuan-bi-rau-cu-tuoi',
                'description' => 'Khu chuẩn bị nguyên liệu theo ngày, ưu tiên rau củ tươi và cách xử lý gọn sạch.',
                'image' => 'https://images.unsplash.com/photo-1556911220-bff31c812dba?auto=format&fit=crop&w=1200&q=85',
                'alt_text' => 'Rau củ tươi được chuẩn bị trong bếp',
                'location' => 'kitchen',
                'sort_order' => 6,
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($images as $image) {
            GalleryImage::updateOrCreate(
                ['slug' => $image['slug']],
                $image
            );
        }
    }
}
