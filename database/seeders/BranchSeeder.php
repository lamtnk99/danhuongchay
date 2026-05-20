<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Đàn Hương Chay Hải Phòng',
                'slug' => 'hai-phong',
                'city' => 'Hải Phòng',
                'address' => 'Villa 01-B4 Hoàng Mậu - Gia Viên (lô 26 Lê Hồng Phong cũ), Hải Phòng, Việt Nam, 180000',
                'phone' => '094 234 55 15',
                'hotline' => '0947 361 515',
                'email' => 'info@danhuongchay.com',
                'opening_hours' => '09:00 - 14:00 | 16:00 - 21:00 hằng ngày',
                'reservation_time_slots' => '09:00-14:00,16:00-21:00',
                'reservation_last_booking_time' => '20:30',
                'reservation_last_order_buffer_minutes' => 30,
                'google_map_iframe' => '<iframe title="Đàn Hương Chay Hải Phòng" src="https://www.google.com/maps?q=Villa%2001-B4%20Ho%C3%A0ng%20M%E1%BA%ADu%20Gia%20Vi%C3%AAn%20H%E1%BA%A3i%20Ph%C3%B2ng&output=embed" class="h-80 w-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'description' => 'Cơ sở Hải Phòng nằm trong khu villa yên tĩnh, phù hợp cho bữa chay gia đình, gặp gỡ bạn bè và đặt tiệc nhỏ.',
                'is_active' => true,
                'sort_order' => 1,
                'meta_title' => 'Đàn Hương Chay Hải Phòng',
                'meta_description' => 'Cơ sở Đàn Hương Chay Hải Phòng tại Villa 01-B4 Hoàng Mậu - Gia Viên, phục vụ ẩm thực chay fusion và đặt bàn.',
            ],
            [
                'name' => 'Đàn Hương Chay Buôn Ma Thuột',
                'slug' => 'buon-ma-thuot',
                'city' => 'Buôn Ma Thuột',
                'address' => 'Địa chỉ cơ sở Buôn Ma Thuột đang cập nhật',
                'phone' => '094 234 55 15',
                'hotline' => '0947 361 515',
                'email' => 'info@danhuongchay.com',
                'opening_hours' => '09:00 - 14:00 | 16:00 - 21:00 hằng ngày',
                'reservation_time_slots' => '09:00-14:00,16:00-21:00',
                'reservation_last_booking_time' => '20:30',
                'reservation_last_order_buffer_minutes' => 30,
                'description' => 'Cơ sở Buôn Ma Thuột dùng chung tinh thần ẩm thực chay fusion, vị thanh và không gian an yên của Đàn Hương Chay.',
                'is_active' => true,
                'sort_order' => 2,
                'meta_title' => 'Đàn Hương Chay Buôn Ma Thuột',
                'meta_description' => 'Cơ sở Đàn Hương Chay Buôn Ma Thuột phục vụ món chay ngon, thực đơn fusion và nhận đặt bàn theo khung giờ.',
            ],
        ];

        foreach ($branches as $data) {
            Branch::updateOrCreate(['slug' => $data['slug']], $data);
        }

        $haiPhong = Branch::where('slug', 'hai-phong')->first();
        if ($haiPhong) {
            GalleryImage::query()
                ->whereNull('branch_id')
                ->whereIn('slug', [
                    'goc-ban-go-canh-mang-xanh',
                    'anh-sang-diu-buoi-chieu',
                    'khu-ban-nhom-gia-dinh',
                    'quay-tra-va-goc-cho',
                ])
                ->update(['branch_id' => $haiPhong->id]);
        }

        $buonMaThuot = Branch::where('slug', 'buon-ma-thuot')->first();
        if ($buonMaThuot) {
            foreach ([
                [
                    'title' => 'Không gian xanh cơ sở Buôn Ma Thuột',
                    'slug' => 'khong-gian-xanh-buon-ma-thuot',
                    'description' => 'Gợi ý không gian xanh nhẹ nhàng cho cơ sở Buôn Ma Thuột, ảnh thực tế có thể cập nhật trong admin.',
                    'image' => 'https://images.unsplash.com/photo-1544148103-0773bf10d330?auto=format&fit=crop&w=1200&q=85',
                    'alt_text' => 'Không gian xanh nhà hàng chay Buôn Ma Thuột',
                    'location' => 'space',
                    'sort_order' => 1,
                    'is_featured' => true,
                    'is_active' => true,
                ],
                [
                    'title' => 'Góc bàn ấm áp cơ sở Buôn Ma Thuột',
                    'slug' => 'goc-ban-am-ap-buon-ma-thuot',
                    'description' => 'Bố cục bàn ăn ấm, phù hợp cho bữa chay gia đình hoặc nhóm bạn.',
                    'image' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1200&q=85',
                    'alt_text' => 'Góc bàn ăn nhà hàng chay Buôn Ma Thuột',
                    'location' => 'space',
                    'sort_order' => 2,
                    'is_featured' => true,
                    'is_active' => true,
                ],
            ] as $image) {
                GalleryImage::updateOrCreate(
                    ['slug' => $image['slug']],
                    $image + ['branch_id' => $buonMaThuot->id]
                );
            }
        }
    }
}
