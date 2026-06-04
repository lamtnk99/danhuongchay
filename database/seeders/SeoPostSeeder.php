<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Services\UploadService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class SeoPostSeeder extends Seeder
{
    private const THUMBNAILS = [
        'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1543339308-43e59d6b73a6?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1544145945-f90425340c7e?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1505253716362-afaea1d3d1af?auto=format&fit=crop&w=1200&q=80',
    ];

    public function run(): void
    {
        if (Category::query()->where('type', 'post')->count() === 0) {
            $this->call(CategorySeeder::class);
        }

        $categories = Category::query()
            ->where('type', 'post')
            ->pluck('id', 'slug');

        $created = 0;
        $updated = 0;

        foreach ($this->posts() as $index => $data) {
            $slug = Str::slug($data['title']);
            $post = Post::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $categories[$data['category']] ?? $categories->first(),
                    'title' => $data['title'],
                    'excerpt' => $this->excerpt($data),
                    'content' => $this->vietnameseContent($data),
                    'thumbnail' => $this->importThumbnail(self::THUMBNAILS[$index % count(self::THUMBNAILS)], $slug),
                    'is_featured' => $index < 4,
                    'is_active' => true,
                    'meta_title' => Str::limit($data['title'].' | Đàn Hương Chay', 68, ''),
                    'meta_description' => $this->metaDescription($data),
                    'meta_keywords' => $this->metaKeywords($data),
                    'published_at' => now()->subHours($index + 1),
                ]
            );

            $post->wasRecentlyCreated ? $created++ : $updated++;

            PostTranslation::updateOrCreate(
                ['post_id' => $post->id, 'locale' => 'en'],
                [
                    'title' => $data['en_title'],
                    'slug' => Str::slug($data['en_title']),
                    'excerpt' => 'A practical guide for choosing vegetarian meals, booking smoothly and enjoying a calmer dining experience in Hai Phong.',
                    'content' => $this->englishContent($data['en_title']),
                    'meta_title' => Str::limit($data['en_title'].' | Dan Huong Chay', 68, ''),
                    'meta_description' => 'A practical vegetarian dining guide for Hai Phong, covering menu choices, booking tips and a calm restaurant experience for families and groups.',
                    'meta_keywords' => 'vegetarian restaurant Hai Phong, healthy vegetarian food, vegetarian catering, Dan Huong Chay',
                ]
            );
        }

        $this->command?->info("SEO posts seeded. Created: {$created}. Updated: {$updated}.");
    }

    /**
     * @return array<int, array{
     *     category: string,
     *     primary: string,
     *     secondary: string,
     *     title: string,
     *     en_title: string,
     *     intent: string,
     *     sections: array<int, string>
     * }>
     */
    private function posts(): array
    {
        return [
            ['category' => 'an-chay', 'primary' => 'cơm chay Hải Phòng', 'secondary' => 'quán chay Hải Phòng', 'title' => 'Cơm chay Hải Phòng: ăn ở đâu để no vừa, thanh nhẹ và đủ chất?', 'en_title' => 'Vegetarian rice in Hai Phong: how to choose a light but satisfying meal', 'intent' => 'bữa trưa nhanh, bữa tối nhẹ và lựa chọn đủ chất cho người muốn ăn chay thường xuyên', 'sections' => ['Cơm chay ngon bắt đầu từ nguyên liệu', 'Một phần cơm nên có gì để no lâu?', 'Khi nào nên ăn tại quán, khi nào nên đặt trước?', 'Gợi ý gọi món cho nhóm văn phòng', 'Vì sao cơm chay hợp với nhịp sống hiện đại']],
            ['category' => 'an-chay', 'primary' => 'đặt cơm chay', 'secondary' => 'cơm chay Hải Phòng', 'title' => 'Đặt cơm chay văn phòng ở Hải Phòng: chọn set thế nào cho ngon và tiện?', 'en_title' => 'Ordering vegetarian lunch for the office in Hai Phong', 'intent' => 'nhu cầu đặt cơm theo nhóm, cần đúng giờ, dễ chia phần và không quá nặng bụng', 'sections' => ['Set cơm chay văn phòng cần gọn và rõ món', 'Ưu tiên món giữ vị tốt khi giao', 'Cách đặt số lượng cho nhóm đông', 'Thông tin nên gửi trước cho nhà hàng', 'Đặt cơm chay đều đặn có lợi gì?']],
            ['category' => 'song-xanh', 'primary' => 'mâm cỗ chay', 'secondary' => 'mâm cỗ chay Hải Phòng', 'title' => 'Mâm cỗ chay Hải Phòng gồm những món gì để đủ trang trọng?', 'en_title' => 'What should a vegetarian feast tray in Hai Phong include?', 'intent' => 'khách cần đặt mâm cỗ chay cho ngày rằm, giỗ, lễ hoặc sum họp gia đình', 'sections' => ['Một mâm cỗ chay cần có bố cục', 'Nhóm món chính nên chọn ra sao?', 'Món canh, món cuốn và món xào tạo sự cân bằng', 'Trình bày quan trọng không kém hương vị', 'Khi nào nên đặt mâm cỗ chay trước?']],
            ['category' => 'song-xanh', 'primary' => 'đặt cỗ chay', 'secondary' => 'mâm cỗ chay', 'title' => 'Đặt cỗ chay tại Hải Phòng: kinh nghiệm chọn món cho ngày rằm, giỗ, lễ', 'en_title' => 'How to order vegetarian feast trays for family occasions', 'intent' => 'người tìm dịch vụ cỗ chay cần chọn món vừa đẹp, vừa dễ dùng, vừa đúng dịp', 'sections' => ['Xác định mục đích của mâm cỗ', 'Chọn món theo số người và thời điểm dùng', 'Vì sao không nên đặt quá sát giờ?', 'Những thông tin nên báo cho quán', 'Cỗ chay đẹp là cỗ vừa đủ và chỉn chu']],
            ['category' => 'suc-khoe', 'primary' => 'đồ ăn chay healthy', 'secondary' => 'ăn chay healthy', 'title' => 'Đồ ăn chay healthy: ăn thế nào để nhẹ bụng mà vẫn đủ năng lượng?', 'en_title' => 'Healthy vegetarian food: light meals that still feel satisfying', 'intent' => 'người muốn ăn lành mạnh nhưng sợ thiếu chất hoặc nhanh đói', 'sections' => ['Healthy không có nghĩa là ăn ít', 'Cân bằng đạm thực vật và chất xơ', 'Chọn tinh bột tốt cho bữa chay', 'Nước sốt và gia vị nên vừa phải', 'Ăn chay healthy bền vững hơn khi ngon miệng']],
            ['category' => 'suc-khoe', 'primary' => 'thực đơn chay healthy', 'secondary' => 'đồ ăn chay healthy', 'title' => 'Thực đơn chay healthy cho người bận rộn: gợi ý bữa trưa và bữa tối', 'en_title' => 'Healthy vegetarian menu ideas for busy people', 'intent' => 'người đi làm cần bữa chay nhanh, dễ chọn và duy trì được nhiều ngày', 'sections' => ['Bữa trưa cần đủ no nhưng không nặng', 'Bữa tối nên ấm và dễ tiêu', 'Chuẩn bị trước giúp ăn chay dễ hơn', 'Đổi món theo tuần để không chán', 'Khi nào nên đặt món ở nhà hàng chay?']],
            ['category' => 'an-chay', 'primary' => 'quán chay Hải Phòng', 'secondary' => 'nhà hàng chay Hải Phòng', 'title' => 'Quán chay Hải Phòng gần trung tâm: tiêu chí chọn nơi ăn yên tĩnh', 'en_title' => 'How to choose a calm vegetarian restaurant in central Hai Phong', 'intent' => 'khách muốn tìm nơi ăn chay có không gian an yên, dễ gặp gỡ và không quá ồn', 'sections' => ['Vị trí thuận tiện chưa đủ', 'Không gian ảnh hưởng trực tiếp đến bữa ăn', 'Menu cần hợp nhiều khẩu vị', 'Phục vụ nên nhẹ nhàng và rõ ràng', 'Một quán yên tĩnh giúp khách quay lại']],
            ['category' => 'an-chay', 'primary' => 'nhà hàng chay Hải Phòng', 'secondary' => 'đặt bàn quán chay', 'title' => 'Nhà hàng chay Hải Phòng cho nhóm đông: cách đặt bàn và gọi món', 'en_title' => 'Booking a vegetarian restaurant in Hai Phong for a group', 'intent' => 'nhóm gia đình, bạn bè hoặc công ty cần đặt bàn trước và gọi món hợp lý', 'sections' => ['Nhóm đông nên đặt bàn sớm', 'Gọi món theo kiểu chia sẻ dễ hơn', 'Cân bằng món khô, món nước và món rau', 'Ghi chú khẩu vị giúp quán chuẩn bị tốt', 'Đặt bàn đúng cách giúp bữa ăn trôi chảy']],
            ['category' => 'an-chay', 'primary' => 'ăn chay cuối tuần Hải Phòng', 'secondary' => 'quán chay Hải Phòng', 'title' => 'Ăn chay cuối tuần ở Hải Phòng: gợi ý lịch trình nhẹ nhàng', 'en_title' => 'A gentle weekend vegetarian meal plan in Hai Phong', 'intent' => 'khách muốn tìm một bữa ăn nhẹ nhàng sau tuần làm việc, kết hợp gặp gỡ và thư giãn', 'sections' => ['Bắt đầu bằng bữa trưa thanh nhẹ', 'Chọn quán có không gian để ngồi lâu', 'Món chia sẻ hợp với nhóm bạn', 'Đừng quên đặt bàn vào khung giờ cao điểm', 'Cuối tuần là lúc thử món mới']],
            ['category' => 'an-chay', 'primary' => 'lẩu chay Hải Phòng', 'secondary' => 'món chay ngon', 'title' => 'Lẩu chay Hải Phòng: món phù hợp cho gia đình và bạn bè', 'en_title' => 'Vegetarian hotpot in Hai Phong for family and friends', 'intent' => 'khách tìm món ăn nhóm, ấm bụng, dễ chia sẻ và hợp nhiều khẩu vị', 'sections' => ['Lẩu chay ngon nằm ở nước dùng', 'Rau nấm tươi quyết định trải nghiệm', 'Ăn lẩu chay nên gọi thêm món gì?', 'Vì sao lẩu hợp với nhóm đông?', 'Nên đặt bàn trước khi ăn lẩu cuối tuần']],
            ['category' => 'an-chay', 'primary' => 'món chay đãi khách', 'secondary' => 'nhà hàng chay Hải Phòng', 'title' => 'Món chay đãi khách: chọn món nào lịch thiệp và dễ ăn?', 'en_title' => 'Vegetarian dishes for hosting guests: elegant and easy choices', 'intent' => 'người cần tiếp khách bằng bữa chay lịch sự, dễ ăn và không quá kén khẩu vị', 'sections' => ['Món đãi khách cần dễ chia sẻ', 'Ưu tiên hương vị cân bằng', 'Trình bày tạo thiện cảm đầu tiên', 'Kết hợp món quen và món có điểm nhấn', 'Bữa chay lịch thiệp không cần cầu kỳ']],
            ['category' => 'song-xanh', 'primary' => 'tiệc chay sinh nhật', 'secondary' => 'đặt tiệc chay', 'title' => 'Tiệc chay sinh nhật: cách lên menu gọn, đẹp, hợp nhiều khẩu vị', 'en_title' => 'Vegetarian birthday party menu ideas for mixed tastes', 'intent' => 'gia đình muốn tổ chức sinh nhật nhẹ nhàng, đẹp mắt và có món phù hợp cả người ăn chay lẫn không ăn chay', 'sections' => ['Tiệc sinh nhật cần menu dễ dùng', 'Chọn món theo độ tuổi khách mời', 'Set chia sẻ giúp bàn tiệc vui hơn', 'Đồ uống và món tráng miệng nên thanh nhẹ', 'Đặt tiệc chay sớm giúp quán chuẩn bị tốt']],
            ['category' => 'song-xanh', 'primary' => 'cỗ chay ngày rằm', 'secondary' => 'mâm cỗ chay', 'title' => 'Cỗ chay ngày rằm: vì sao nên đặt sớm và chọn cơ sở uy tín?', 'en_title' => 'Vegetarian feast trays for full moon days: why early ordering matters', 'intent' => 'khách cần mâm cỗ ngày rằm nhưng muốn tránh thiếu món, trễ giờ hoặc trình bày chưa đẹp', 'sections' => ['Ngày rằm thường có nhu cầu cao', 'Đặt sớm giúp chọn món kỹ hơn', 'Cơ sở uy tín giữ chất lượng ổn định', 'Mâm cỗ nên vừa đủ, không phô trương', 'Sự đúng giờ rất quan trọng trong ngày lễ']],
            ['category' => 'suc-khoe', 'primary' => 'ăn chay healthy', 'secondary' => 'đồ ăn chay healthy', 'title' => 'Ăn chay healthy có khó không? 5 nguyên tắc đơn giản', 'en_title' => 'Is healthy vegetarian eating difficult? Five simple principles', 'intent' => 'người mới bắt đầu ăn lành mạnh cần nguyên tắc dễ nhớ, dễ áp dụng', 'sections' => ['Ăn đủ nhóm chất trước khi tính calo', 'Đạm thực vật nên có trong mỗi bữa', 'Rau củ theo mùa giúp bữa ăn ngon hơn', 'Đừng loại bỏ hoàn toàn chất béo tốt', 'Duy trì bằng món ngon thay vì ép mình']],
            ['category' => 'an-chay', 'primary' => 'bữa chay gia đình', 'secondary' => 'quán chay Hải Phòng', 'title' => 'Bữa chay gia đình: cách gọi món để người lớn tuổi và trẻ nhỏ đều dễ ăn', 'en_title' => 'How to order vegetarian dishes for the whole family', 'intent' => 'gia đình có nhiều độ tuổi cần gọi món hài hòa, dễ ăn và đủ dinh dưỡng', 'sections' => ['Bắt đầu bằng món quen thuộc', 'Thêm một món nước để cân bằng', 'Chọn món ít cay cho trẻ nhỏ', 'Người lớn tuổi thường hợp món mềm', 'Bữa gia đình ngon là bữa ai cũng có phần']],
            ['category' => 'an-chay', 'primary' => 'đặt bàn nhà hàng chay Hải Phòng', 'secondary' => 'đặt bàn quán chay', 'title' => 'Đặt bàn nhà hàng chay Hải Phòng: những thông tin nên chuẩn bị', 'en_title' => 'What to prepare before booking a vegetarian restaurant in Hai Phong', 'intent' => 'khách muốn đặt bàn nhanh, chính xác và được quán xác nhận thuận tiện', 'sections' => ['Số khách và khung giờ là thông tin quan trọng nhất', 'Nên báo trước nếu đi cùng trẻ nhỏ hoặc người lớn tuổi', 'Ghi chú dịp đặc biệt giúp quán chuẩn bị tốt', 'Đặt bàn sớm trong cuối tuần và ngày lễ', 'Thông tin rõ ràng giúp trải nghiệm tốt hơn']],
            ['category' => 'an-chay', 'primary' => 'không gian quán chay', 'secondary' => 'nhà hàng chay Hải Phòng', 'title' => 'Không gian quán chay: vì sao ảnh hưởng nhiều đến trải nghiệm bữa ăn?', 'en_title' => 'Why restaurant atmosphere matters in a vegetarian meal', 'intent' => 'khách quan tâm không chỉ món ăn mà cả cảm giác thư thái khi dùng bữa', 'sections' => ['Ánh sáng làm bữa ăn dễ chịu hơn', 'Khoảng cách bàn tạo sự riêng tư', 'Màu sắc và cây xanh giúp tinh thần nhẹ lại', 'Âm thanh quyết định thời gian khách muốn ở lại', 'Không gian tốt làm món ăn ngon hơn trong cảm nhận']],
            ['category' => 'an-chay', 'primary' => 'menu chay theo mùa', 'secondary' => 'món chay ngon', 'title' => 'Menu chay theo mùa: lợi ích của rau củ tươi và cách quán lên món', 'en_title' => 'Seasonal vegetarian menus and the value of fresh produce', 'intent' => 'người ăn chay muốn hiểu vì sao món theo mùa thường ngon, lành và dễ chịu hơn', 'sections' => ['Rau củ theo mùa thường ngọt tự nhiên', 'Menu theo mùa giúp quán đổi mới', 'Nguyên liệu tươi giảm nhu cầu nêm nặng', 'Món theo mùa hợp với lối ăn healthy', 'Khách quay lại vì luôn có điều mới để thử']],
        ];
    }

    private function excerpt(array $post): string
    {
        return 'Gợi ý thực tế về '.$post['primary'].' dành cho khách muốn ăn chay ngon, thanh nhẹ, dễ đặt bàn và phù hợp nhiều dịp tại Hải Phòng.';
    }

    private function metaDescription(array $post): string
    {
        return Str::limit('Tìm hiểu '.$post['primary'].' với gợi ý chọn món, đặt bàn và trải nghiệm nhà hàng chay Hải Phòng thanh lành, chỉn chu, phù hợp gia đình và nhóm bạn.', 158, '');
    }

    private function metaKeywords(array $post): string
    {
        return collect([
            $post['primary'],
            $post['secondary'],
            'quán chay Hải Phòng',
            'nhà hàng chay Hải Phòng',
            'đặt cơm chay',
            'mâm cỗ chay',
            'đồ ăn chay healthy',
        ])->unique()->implode(', ');
    }

    private function vietnameseContent(array $post): string
    {
        $html = '<p>Khi người dùng tìm <strong>'.e($post['primary']).'</strong>, họ thường không chỉ cần một địa chỉ để ăn cho xong bữa. Nhu cầu thật sự là '.e($post['intent']).'. Vì vậy, một bài viết chuẩn SEO tốt cần trả lời đúng câu hỏi của khách, đồng thời giúp họ hình dung rõ cách chọn món, chọn quán và đặt trước sao cho thuận tiện.</p>';

        foreach ($post['sections'] as $index => $heading) {
            $html .= '<h2>'.e($heading).'</h2>';
            $html .= '<p>'.$this->vietnameseParagraph($post, $index).'</p>';
        }

        return $html.'<h3>Kết luận</h3><p>Nếu bạn đang quan tâm đến <strong>'.e($post['primary']).'</strong>, hãy ưu tiên nơi có thực đơn rõ ràng, nguyên liệu tươi, không gian dễ chịu và khả năng hỗ trợ đặt bàn nhanh. Một bữa chay tốt là bữa ăn vừa ngon miệng, vừa nhẹ nhàng, vừa đủ chỉn chu cho đúng hoàn cảnh.</p>';
    }

    private function vietnameseParagraph(array $post, int $index): string
    {
        return match ($index) {
            0 => 'Điểm đầu tiên nên xem là sự rõ ràng trong thực đơn. Một lựa chọn tốt cần có món chính, món phụ và phần rau củ đi kèm hợp lý. Với <strong>'.e($post['primary']).'</strong>, khách sẽ dễ quyết định hơn khi quán mô tả món cụ thể, khẩu vị vừa phải và cho biết món nào phù hợp với nhóm đông, bữa gia đình hoặc bữa văn phòng.',
            1 => 'Một bữa chay ngon không chỉ nằm ở cảm giác thanh nhẹ mà còn ở độ no và sự cân bằng. Đạm thực vật, rau củ theo mùa, tinh bột tốt và nước sốt vừa miệng giúp món ăn dễ dùng hơn. Đây cũng là lý do các từ khóa như <strong>'.e($post['secondary']).'</strong> thường đi cùng nhu cầu tìm nơi có chất lượng ổn định.',
            2 => 'Nếu đi vào giờ cao điểm, khách nên đặt trước để quán chuẩn bị bàn, khung giờ và số lượng món phù hợp. Việc này đặc biệt hữu ích khi đi cùng gia đình, tiếp khách hoặc cần mâm cỗ, tiệc nhỏ. Thông tin càng rõ, trải nghiệm càng ít phát sinh sai sót.',
            3 => 'Với nhóm đông, cách gọi món thông minh là kết hợp món dễ chia sẻ, món nước, món rau và một món có điểm nhấn. Cách này giúp bàn ăn hài hòa, không bị quá nặng vị và vẫn tạo cảm giác phong phú. Một quán hiểu khách sẽ luôn biết gợi ý lượng món vừa đủ.',
            default => 'Về lâu dài, khách quay lại vì cảm giác tin cậy: món ăn ổn định, không gian dễ chịu, phục vụ rõ ràng và thông tin đặt bàn thuận tiện. Đó là nền tảng giúp một nhà hàng chay tăng tìm kiếm bền vững thay vì chỉ phụ thuộc vào quảng cáo ngắn hạn.',
        };
    }

    private function englishContent(string $title): string
    {
        $sections = [
            'Understanding the search intent',
            'How to choose a balanced vegetarian meal',
            'When booking ahead makes the experience better',
            'Menu tips for families, office groups and guests',
            'Why a calm restaurant experience matters',
        ];

        $html = '<p>This guide is written for visitors looking for a reliable vegetarian dining experience in Hai Phong. It explains how to choose dishes, plan the timing and book a table with less friction, especially for families, office groups and special occasions.</p>';

        foreach ($sections as $index => $heading) {
            $html .= '<h2>'.e($heading).'</h2>';
            $html .= '<p>'.$this->englishParagraph($index).'</p>';
        }

        return $html.'<h3>Summary</h3><p>Whether the topic is '.e($title).' or a broader vegetarian dining search, the best choice is a restaurant that combines thoughtful food, clear booking support and a peaceful atmosphere.</p>';
    }

    private function englishParagraph(int $index): string
    {
        return match ($index) {
            0 => 'A good SEO article should answer the real question behind the search. People are usually not only looking for a restaurant name; they want a clear menu, fresh ingredients, fair portions and a meal that fits the occasion.',
            1 => 'A satisfying vegetarian meal combines plant-based protein, seasonal vegetables, gentle seasoning and a clear main dish. This balance helps the meal feel light without becoming too small or plain.',
            2 => 'Booking ahead is useful during weekends, full moon days and family gatherings. It gives the restaurant time to prepare the right table, suggest suitable dishes and avoid unnecessary waiting.',
            3 => 'For groups, it is better to combine shared dishes, a warm soup or hotpot, vegetables and one signature item. This creates variety while keeping the table easy to enjoy for different tastes.',
            default => 'A calm space, stable service and consistent food quality are what make guests return. Good vegetarian dining is not only about ingredients; it is also about how comfortable the whole meal feels.',
        };
    }

    private function importThumbnail(string $url, string $slug): string
    {
        $disk = Storage::disk(config('uploads.disk', 'public'));
        $path = "posts/seo/{$slug}.jpg";

        if ($disk->exists($path)) {
            return $path;
        }

        try {
            $response = Http::timeout(20)->retry(2, 250)->get($url);

            if (! $response->successful() || blank($response->body())) {
                return $url;
            }

            $disk->put($path, $response->body());
            app(UploadService::class)->regenerateResponsiveVariants($path, 'content');

            return $path;
        } catch (Throwable) {
            return $url;
        }
    }
}
