<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::blog()->pluck('id', 'name');

        $posts = [
            [
                'category_id' => $categories['Ăn chay'],
                'title' => 'Lợi ích của ăn chay với sức khỏe mỗi ngày',
                'excerpt' => 'Ăn chay đúng cách giúp bữa ăn nhẹ nhàng hơn, tăng rau xanh và hỗ trợ thói quen sống cân bằng.',
                'content' => '<p>Ăn chay không chỉ là lựa chọn ẩm thực mà còn là cách chăm sóc cơ thể bằng sự chú tâm. Một bữa chay cân bằng thường giàu chất xơ, vitamin, khoáng chất và các nguồn đạm thực vật từ đậu, nấm, hạt.</p><h2>Ăn chay cần đủ chất</h2><p>Điểm quan trọng là không chỉ thay món mặn bằng món không có thịt. Thực đơn nên có rau xanh, ngũ cốc, đậu hũ, các loại hạt và chất béo lành mạnh để duy trì năng lượng.</p><h2>Bắt đầu nhẹ nhàng</h2><p>Nếu mới ăn chay, bạn có thể bắt đầu bằng một vài bữa mỗi tuần. Khi cơ thể quen dần, việc lựa chọn món ăn sẽ trở nên tự nhiên hơn.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=1000&q=80',
                'meta_title' => 'Lợi ích của ăn chay với sức khỏe | Đàn Hương Chay',
                'meta_description' => 'Tìm hiểu lợi ích của ăn chay đúng cách và cách xây dựng bữa chay cân bằng cho sức khỏe mỗi ngày.',
                'published_at' => now()->subDays(3),
            ],
            [
                'category_id' => $categories['Sức khỏe'],
                'title' => 'Cách xây dựng một bữa chay cân bằng dinh dưỡng',
                'excerpt' => 'Một bữa chay tốt cần đủ tinh bột, đạm thực vật, rau xanh, chất béo tốt và gia vị vừa phải.',
                'content' => '<p>Bữa chay cân bằng không cần phức tạp. Bạn có thể bắt đầu từ một phần cơm hoặc bún, thêm rau luộc hoặc rau xào, một món đạm từ đậu hũ hoặc nấm, và một ít hạt rang.</p><h2>Đạm thực vật</h2><p>Đậu nành, đậu lăng, đậu gà, nấm và hạt là những nguồn đạm quen thuộc. Kết hợp đa dạng giúp bữa ăn phong phú hơn.</p><h2>Gia vị nhẹ</h2><p>Ẩm thực chay ngon khi giữ được vị ngọt tự nhiên của rau củ. Hạn chế quá nhiều dầu và muối sẽ giúp món ăn nhẹ bụng.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=1000&q=80',
                'meta_title' => 'Bữa chay cân bằng dinh dưỡng | Đàn Hương Chay',
                'meta_description' => 'Gợi ý xây dựng một bữa chay đủ chất với đạm thực vật, rau xanh, tinh bột và chất béo lành mạnh.',
                'published_at' => now()->subDays(6),
            ],
            [
                'category_id' => $categories['Sống xanh'],
                'title' => 'Sống xanh bắt đầu từ căn bếp nhỏ',
                'excerpt' => 'Giảm lãng phí thực phẩm, ưu tiên rau củ theo mùa và tái sử dụng hợp lý là những bước sống xanh gần gũi.',
                'content' => '<p>Căn bếp là nơi dễ bắt đầu lối sống xanh nhất. Việc lên thực đơn trước, mua vừa đủ và tận dụng phần rau củ còn lại giúp giảm lãng phí đáng kể.</p><h2>Ưu tiên mùa nào thức nấy</h2><p>Rau củ đúng mùa thường tươi, ngon và có chi phí hợp lý. Đây cũng là cách giúp bữa ăn gần với nhịp tự nhiên hơn.</p><h2>Giữ mọi thứ đơn giản</h2><p>Sống xanh không cần cầu kỳ. Những thay đổi nhỏ nhưng lặp lại đều đặn sẽ tạo tác động bền vững.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1000&q=80',
                'meta_title' => 'Sống xanh từ căn bếp nhỏ | Đàn Hương Chay',
                'meta_description' => 'Những thói quen sống xanh đơn giản trong căn bếp: giảm lãng phí, chọn rau củ theo mùa và ăn uống có ý thức.',
                'published_at' => now()->subDays(9),
            ],
            [
                'category_id' => $categories['Ăn chay'],
                'title' => 'Ăn chay thanh đạm nhưng không nhạt nhòa',
                'excerpt' => 'Món chay ngon nhờ kết cấu, nước dùng rau củ, nấm, hạt và cách nêm nếm tinh tế.',
                'content' => '<p>Nhiều người nghĩ món chay dễ nhạt, nhưng sự phong phú của rau củ, nấm, đậu và hạt có thể tạo nên tầng vị rất sâu. Bí quyết nằm ở nước dùng, cách xử lý nguyên liệu và độ cân bằng gia vị.</p><h2>Nước dùng là nền vị</h2><p>Rau củ nướng nhẹ trước khi hầm sẽ cho nước dùng ngọt hơn. Nấm khô, củ cải, bắp và táo đỏ cũng tạo hậu vị tự nhiên.</p><h2>Kết cấu làm món ăn thú vị</h2><p>Kết hợp mềm, giòn, dai và béo nhẹ giúp món chay có trải nghiệm trọn vẹn hơn.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1543339308-43e59d6b73a6?auto=format&fit=crop&w=1000&q=80',
                'meta_title' => 'Ăn chay thanh đạm nhưng không nhạt | Đàn Hương Chay',
                'meta_description' => 'Khám phá cách tạo vị sâu cho món chay bằng nước dùng rau củ, nấm, hạt và kết cấu nguyên liệu.',
                'published_at' => now()->subDays(12),
            ],
            [
                'category_id' => $categories['Sức khỏe'],
                'title' => 'Gợi ý chọn đồ uống lành mạnh sau bữa chay',
                'excerpt' => 'Trà thảo mộc, sữa hạt và nước ép ít đường giúp bữa chay kết thúc nhẹ nhàng.',
                'content' => '<p>Đồ uống sau bữa ăn nên hỗ trợ cảm giác dễ chịu thay vì làm cơ thể nặng thêm. Trà thảo mộc ấm, sữa hạt không quá ngọt hoặc nước ép rau củ là những lựa chọn phù hợp.</p><h2>Ít đường, nhiều hương tự nhiên</h2><p>Khi giảm đường, bạn sẽ cảm nhận rõ hơn mùi thơm của sen, nhãn, gừng, sả hoặc các loại hạt rang.</p><h2>Uống theo nhu cầu</h2><p>Buổi trưa có thể chọn nước ép mát, buổi tối nên ưu tiên trà ấm hoặc sữa hạt nhẹ.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?auto=format&fit=crop&w=1000&q=80',
                'meta_title' => 'Đồ uống lành mạnh sau bữa chay | Đàn Hương Chay',
                'meta_description' => 'Gợi ý trà thảo mộc, sữa hạt và nước ép ít đường để kết thúc bữa chay nhẹ nhàng.',
                'published_at' => now()->subDays(15),
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(
                ['title' => $post['title']],
                $post
            );
        }
    }
}
