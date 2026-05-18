<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Minh Anh',
                'role' => 'Khách quen cuối tuần',
                'content' => 'Không gian yên, món ăn nhẹ bụng nhưng vẫn rất tròn vị. Lẩu nấm là món mình sẽ quay lại vì nó.',
            ],
            [
                'name' => 'Hoàng Nam',
                'role' => 'Dân văn phòng',
                'content' => 'Thực đơn chay dễ ăn, trình bày tinh tế. Nhân viên tư vấn món rất kỹ cho người mới ăn chay.',
            ],
            [
                'name' => 'Thanh Trúc',
                'role' => 'Khách đi cùng gia đình',
                'content' => 'Mình thích cách quán dùng rau củ tươi và nêm nếm vừa phải. Cảm giác rất lành sau bữa ăn.',
            ],
            [
                'name' => 'Gia Hân',
                'role' => 'Ăn chay trường',
                'content' => 'Đậu hũ sốt nấm mềm, thơm và không bị ngấy. Phần ăn vừa vặn, lên món nhanh kể cả giờ trưa.',
            ],
            [
                'name' => 'Quốc Bảo',
                'role' => 'Khách đặt bàn nhóm',
                'content' => 'Nhóm mình đặt bàn sinh nhật nhỏ, quán chuẩn bị chu đáo và món lẩu rất hợp khẩu vị nhiều người.',
            ],
            [
                'name' => 'Lan Phương',
                'role' => 'Khách mới ăn chay',
                'content' => 'Món chay ở đây không tạo cảm giác thiếu vị. Rau củ tươi, nước dùng thanh và nhân viên rất dễ thương.',
            ],
            [
                'name' => 'Tuấn Khang',
                'role' => 'Khách ghé buổi tối',
                'content' => 'Không gian có mùi gỗ nhẹ, ánh sáng dễ chịu. Đây là nơi mình chọn khi muốn ăn tối chậm lại một chút.',
            ],
            [
                'name' => 'Mai Chi',
                'role' => 'Khách đặt mang về',
                'content' => 'Đóng gói món mang về sạch sẽ, vẫn giữ được độ nóng. Cơm gạo lứt và nấm kho là combo mình rất thích.',
            ],
            [
                'name' => 'An Nhiên',
                'role' => 'Người yêu lối sống xanh',
                'content' => 'Mình đánh giá cao cách quán hạn chế vị quá đậm. Ăn xong thấy nhẹ người mà vẫn no lâu.',
            ],
            [
                'name' => 'Đức Minh',
                'role' => 'Khách họp mặt bạn bè',
                'content' => 'Món lên đẹp, giá hợp lý và có nhiều lựa chọn cho nhóm bạn. Phần nước uống cũng rất ổn.',
            ],
        ];

        foreach ($testimonials as $index => $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name']],
                [
                    ...$testimonial,
                    'rating' => $index === 7 ? 4 : 5,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
