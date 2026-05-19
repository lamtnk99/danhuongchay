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

        Post::query()->whereIn('title', [
            'Lợi ích của ăn chay với sức khỏe mỗi ngày',
            'Cách xây dựng một bữa chay cân bằng dinh dưỡng',
            'Sống xanh bắt đầu từ căn bếp nhỏ',
            'Ăn chay thanh đạm nhưng không nhạt nhòa',
            'Gợi ý chọn đồ uống lành mạnh sau bữa chay',
        ])->delete();

        $posts = [
            [
                'category_id' => $categories['Ăn chay'],
                'title' => 'Quán chay Hải Phòng nên chọn như thế nào để ăn ngon, sạch và đáng quay lại?',
                'excerpt' => 'Một quán chay tốt không chỉ cần món ăn ngon mà còn phải có nguyên liệu rõ nguồn gốc, không gian dễ chịu, phục vụ ổn định và thực đơn đủ linh hoạt cho nhiều nhu cầu.',
                'content' => '<p>Khi tìm <strong>quán chay Hải Phòng</strong>, nhiều người thường bắt đầu từ cảm nhận đầu tiên: nhìn thực đơn có hấp dẫn không, không gian có dễ chịu không, và món ăn có thật sự “ăn xong muốn quay lại” không. Nhưng để chọn được một nơi phù hợp cho bữa ăn gia đình, bạn bè hay tiếp khách, có vài tiêu chí thực tế nên xem kỹ hơn.</p><h2>1. Thực đơn cần rõ ràng và có chiều sâu</h2><p>Một quán chay đáng tin thường không chỉ có vài món rau luộc hay đậu phụ xào nấm. Thực đơn nên có món khai vị, món chính, cơm, mì, bún, lẩu, đồ uống và món theo mùa. Với những người ăn chay thường xuyên, sự đa dạng giúp bữa ăn bớt lặp lại, đồng thời cho thấy quán có tư duy bếp rõ ràng chứ không chỉ làm cho có.</p><p>Ở các thành phố lớn như Hải Phòng, khách hàng ngày càng quan tâm đến <strong>món chay ngon</strong> nhưng vẫn giữ được cảm giác đủ đầy. Vì vậy, các món cần có điểm nhấn về kết cấu, hương thơm, cách nêm nếm và phần trình bày.</p><h2>2. Nguyên liệu là yếu tố quyết định trải nghiệm</h2><p>Người dùng tìm <strong>nhà hàng chay Hải Phòng</strong> thường muốn ăn thanh nhưng không nhạt. Điều đó chỉ làm được khi nguyên liệu được chọn kỹ: rau củ theo mùa, nấm tươi, đậu hũ mới, các loại hạt rang, nước dùng ninh từ rau củ và gia vị vừa phải. Một quán tốt sẽ sẵn sàng nói rõ món nào có vị nhẹ, món nào đậm, món nào phù hợp cho người mới ăn chay.</p><h2>3. Không gian và nhịp phục vụ phải dễ chịu</h2><p>Đa phần khách đến quán chay không chỉ để no bụng mà còn để tìm một bữa ăn nhẹ nhàng hơn. Không gian sáng, sạch, có mảng xanh và cách bày bàn gọn gàng sẽ làm trải nghiệm tốt hơn hẳn. Nhịp phục vụ cũng nên ổn định: gọi món rõ ràng, lên món đúng thời gian, chủ động nhắc khách về các món bán chạy hoặc món theo mùa.</p><h2>4. Nên có khả năng đặt bàn và đặt món linh hoạt</h2><p>Với những người tìm <strong>đặt bàn quán chay</strong> hoặc cần đặt cho nhóm gia đình, tiệc nhỏ, mâm cúng chay, quán nên có form đặt bàn rõ ràng, xác nhận nhanh và giờ hoạt động minh bạch. Điều này quan trọng hơn nhiều so với quảng cáo đẹp vì nó ảnh hưởng trực tiếp đến trải nghiệm thật.</p><h3>Kết luận</h3><p>Một <strong>quán chay Hải Phòng</strong> đáng chọn là nơi cân bằng được 4 thứ: món ăn ngon, nguyên liệu sạch, không gian dễ chịu và khả năng phục vụ ổn định. Khi những điều đó đi cùng nhau, khách không chỉ ăn một bữa mà còn muốn quay lại cho những dịp khác.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80',
                'meta_title' => 'Quán chay Hải Phòng nên chọn thế nào? | Đàn Hương Chay',
                'meta_description' => 'Gợi ý cách chọn quán chay Hải Phòng ngon, sạch, có thực đơn phong phú, không gian đẹp và phù hợp cho gia đình, tiếp khách hoặc đặt bàn.',
                'meta_keywords' => 'quán chay Hải Phòng, nhà hàng chay Hải Phòng, món chay ngon, đặt bàn quán chay, tiệc chay, mâm cúng chay',
                'published_at' => now()->subDays(2),
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Ăn chay'],
                'title' => 'Nhà hàng chay Hải Phòng cho gia đình, tiếp khách và đặt tiệc: nên xem gì trước khi gọi bàn?',
                'excerpt' => 'Khi đi ăn cùng gia đình hoặc cần đặt tiệc chay, bạn nên xem cách quán lên món, xử lý đặt bàn, phân chia khẩu vị và bố cục không gian trước khi quyết định.',
                'content' => '<p>Không phải mọi <strong>nhà hàng chay Hải Phòng</strong> đều phù hợp cho cùng một mục đích. Có nơi hợp bữa trưa nhanh, có nơi hợp ăn tối thư thả, có nơi lại mạnh về <strong>đặt tiệc chay</strong> hoặc mâm cúng. Nếu bạn đi theo nhóm gia đình hay mời khách, hãy ưu tiên những tiêu chí rất thực tế dưới đây.</p><h2>1. Món ăn phải phù hợp nhiều độ tuổi</h2><p>Đi cùng gia đình nghĩa là bữa ăn có thể có người lớn tuổi, trẻ nhỏ và người ăn chay lâu năm lẫn người mới thử. Thực đơn nên có món nhẹ vị, món đậm hơn một chút, món dễ ăn và món “đã miệng” để ai cũng tìm được thứ mình thích. Đây là lý do các quán có menu phân nhóm rõ ràng thường được đánh giá tốt hơn.</p><h2>2. Dịch vụ đặt bàn cần rõ ràng và phản hồi nhanh</h2><p>Khi tìm từ khóa <strong>đặt bàn quán chay</strong>, người dùng thường quan tâm đến việc có xác nhận ngay hay không, có thể chọn khung giờ nào, và có ghi chú riêng cho bàn tiệc, sinh nhật hay mâm cúng không. Một hệ thống đặt bàn tốt sẽ giúp quán giảm sai sót, còn khách thì yên tâm hơn.</p><h2>3. Không gian nên có chỗ ngồi linh hoạt</h2><p>Gia đình thường cần bàn ghép vừa đủ hoặc góc ngồi ít ồn hơn. Nếu quán có khu vực riêng cho nhóm nhỏ, ánh sáng dịu và cách âm tốt hơn, trải nghiệm sẽ khác hẳn. Đây là điểm nhiều khách quay lại không phải vì món “lạ”, mà vì cảm giác ngồi rất dễ chịu.</p><h2>4. Món theo mùa và món đặt trước là lợi thế</h2><p>Nếu quán có các món lẩu, món cuốn, set mâm chay hoặc món theo mùa, khả năng giữ khách sẽ cao hơn. Những món này không chỉ tạo cảm giác mới mẻ mà còn tốt cho SEO vì khách thường tìm các cụm như <strong>món chay ngon</strong>, <strong>thực đơn chay</strong>, <strong>tiệc chay</strong> hoặc <strong>mâm cúng chay</strong>.</p><h3>Kết luận</h3><p>Chọn đúng <strong>nhà hàng chay Hải Phòng</strong> là chọn nơi biết phục vụ nhiều nhu cầu cùng lúc: ăn ngon, đặt bàn dễ, không gian ổn và thực đơn đủ linh hoạt. Đó là nền tảng để quán chay không chỉ đông mà còn có khách quay lại thường xuyên.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=1200&q=80',
                'meta_title' => 'Nhà hàng chay Hải Phòng cho gia đình và đặt tiệc | Đàn Hương Chay',
                'meta_description' => 'Kinh nghiệm chọn nhà hàng chay Hải Phòng phù hợp cho gia đình, tiếp khách, đặt tiệc và mâm cúng chay với thực đơn linh hoạt, dễ gọi món.',
                'meta_keywords' => 'nhà hàng chay Hải Phòng, đặt tiệc chay, đặt bàn quán chay, mâm cúng chay, quán chay gia đình, món chay ngon',
                'published_at' => now()->subDays(5),
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Ăn chay'],
                'title' => 'Ẩm thực chay fusion là gì? Vì sao món chay hiện đại ngày càng được yêu thích?',
                'excerpt' => 'Fusion không phải là làm món chay cầu kỳ quá mức, mà là phối hợp hợp lý giữa kỹ thuật nấu, kết cấu món và hương vị để tạo ra trải nghiệm mới.',
                'content' => '<p><strong>Ẩm thực chay fusion</strong> là cách tiếp cận hiện đại trong đó món chay giữ tinh thần thanh nhẹ nhưng được xử lý bằng nhiều kỹ thuật và cảm hứng khác nhau. Có thể là cách nướng, áp chảo, hầm, cuộn, trộn hoặc kết hợp gia vị Á - Âu sao cho món ăn vẫn hài hòa và dễ thưởng thức.</p><h2>Fusion không đồng nghĩa với phức tạp</h2><p>Nhiều người nghe “fusion” tưởng là cầu kỳ, nhưng với món chay, điều quan trọng nhất vẫn là giữ được độ tươi, độ cân bằng và sự dễ ăn. Một món ngon có thể chỉ cần nấm tươi, rau củ theo mùa, nước sốt vừa miệng và trình bày tinh tế. Điểm khác biệt nằm ở cách quán tạo lớp hương vị và trải nghiệm thị giác.</p><h2>Vì sao khách ngày càng thích món chay hiện đại?</h2><p>Thứ nhất, người dùng muốn một bữa chay đủ chất nhưng không nhàm chán. Thứ hai, nhiều khách tìm <strong>món chay ngon</strong> không chỉ vì ăn chay mà còn vì muốn đổi vị, giữ dáng, ăn nhẹ bụng hoặc dùng cho bữa gặp gỡ. Thứ ba, những món chay hiện đại thường dễ chụp ảnh, dễ chia sẻ và dễ tạo cảm giác “quán có cá tính”.</p><h2>Một món chay fusion tốt cần gì?</h2><p>Nó cần ba lớp: nguyên liệu sạch, kết cấu rõ ràng và vị cân bằng. Ví dụ, một món nấm nướng sốt tiêu đen sẽ ngon hơn nhiều nếu có vị umami tự nhiên, chút giòn ở rau củ ăn kèm và phần nước sốt không quá nặng. Tương tự, một món cuốn sẽ hấp dẫn hơn khi có thành phần đủ màu và phần chấm đủ chiều sâu.</p><h3>Ứng dụng trong quán chay thực tế</h3><p>Với một <strong>quán chay Hải Phòng</strong>, fusion là cách để quán tạo dấu ấn riêng mà vẫn không rời xa nhu cầu của khách địa phương. Đó là lý do nhiều thực khách đánh giá cao các quán biết kết hợp giữa món truyền thống và cách làm hiện đại: vừa thân quen, vừa có nét mới.</p><h3>Kết luận</h3><p>Fusion đúng nghĩa là làm món chay trở nên hấp dẫn hơn, không đánh mất tinh thần thanh lành. Khi quán làm tốt điều này, khách sẽ nhớ lâu hơn vì món ăn có bản sắc rõ ràng.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1200&q=80',
                'meta_title' => 'Ẩm thực chay fusion là gì? | Đàn Hương Chay Hải Phòng',
                'meta_description' => 'Giải thích ẩm thực chay fusion là gì, vì sao món chay hiện đại được yêu thích và cách quán chay tạo dấu ấn riêng qua thực đơn sáng tạo.',
                'meta_keywords' => 'ẩm thực chay fusion, món chay hiện đại, món chay ngon, quán chay Hải Phòng, nhà hàng chay Hải Phòng',
                'published_at' => now()->subDays(8),
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Sức khỏe'],
                'title' => 'Ăn chay có lợi gì? Cách xây thực đơn chay no lâu, đủ chất và dễ áp dụng mỗi ngày',
                'excerpt' => 'Ăn chay bền vững không cần cầu kỳ, chỉ cần thực đơn đủ đạm thực vật, chất xơ, tinh bột tốt và chất béo lành mạnh.',
                'content' => '<p>Khi tìm hiểu về <strong>ăn chay</strong>, đa số người dùng muốn biết một điều rất thật: ăn chay có đủ chất không, có no lâu không, và làm sao duy trì được trong đời sống bận rộn. Câu trả lời là có, nếu bạn xây thực đơn hợp lý và không chỉ nhìn vào một món riêng lẻ.</p><h2>1. Một bữa chay nên có 4 phần</h2><p>Một bữa chay cân bằng thường nên có tinh bột tốt, đạm thực vật, rau xanh và chất béo tốt. Ví dụ: cơm gạo lứt, mì, bún hoặc khoai; đậu hũ, nấm, đậu lăng, đậu gà; rau theo mùa; hạt hoặc dầu thực vật dùng vừa phải. Khi bốn nhóm này đi cùng nhau, bữa ăn sẽ no lâu hơn và ít gây cảm giác thiếu hụt.</p><h2>2. Ăn chay không có nghĩa là ăn nhạt</h2><p>Rất nhiều người bỏ cuộc chỉ vì các bữa chay đầu tiên quá đơn điệu. Thực tế, món chay ngon phải có mùi thơm rõ, vị ngọt tự nhiên từ rau củ, chút béo nhẹ và độ giòn hoặc dai vừa đủ. Đây cũng là lý do các quán có menu phong phú thường giữ khách tốt hơn.</p><h2>3. Cách áp dụng cho người mới bắt đầu</h2><p>Nếu bạn chưa quen ăn chay, hãy bắt đầu bằng 2 đến 3 bữa mỗi tuần. Ưu tiên món quen thuộc như cơm, bún, mì, canh, lẩu nhẹ vị và đồ uống thanh mát. Sau vài tuần, cơ thể sẽ quen hơn và việc chọn <strong>món chay ngon</strong> cũng trở nên tự nhiên hơn.</p><h2>4. Ăn chay trong nhịp sống hiện đại</h2><p>Người bận rộn thường cần những bữa ăn nhanh nhưng vẫn lành mạnh. Vì vậy, một <strong>nhà hàng chay Hải Phòng</strong> tốt nên có thực đơn rõ ràng, lên món nhanh, có thể đặt trước và có những món theo set để tiết kiệm thời gian. Sự tiện lợi này là lý do nhiều khách chọn quán chay cho bữa trưa văn phòng hoặc bữa tối nhẹ nhàng.</p><h3>Kết luận</h3><p>Ăn chay có thể là thói quen bền vững nếu thực đơn được xây đúng. Khi bữa chay đủ chất, dễ ăn và hợp khẩu vị, nó không còn là “thay thế” mà trở thành một lựa chọn sống lành mạnh thật sự.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1543339308-43e59d6b73a6?auto=format&fit=crop&w=1200&q=80',
                'meta_title' => 'Ăn chay có lợi gì? | Thực đơn chay no lâu, đủ chất',
                'meta_description' => 'Hướng dẫn xây thực đơn ăn chay đủ chất, no lâu và dễ áp dụng hằng ngày với đạm thực vật, rau xanh, tinh bột tốt và chất béo lành mạnh.',
                'meta_keywords' => 'ăn chay, thực đơn chay, ăn chay đủ chất, quán chay Hải Phòng, món chay ngon, ăn chay healthy',
                'published_at' => now()->subDays(11),
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Sức khỏe'],
                'title' => 'Món chay ngon ở Hải Phòng nên thử gì cho bữa trưa, bữa tối và cuối tuần?',
                'excerpt' => 'Khi tìm món chay ở Hải Phòng, nhiều khách quan tâm đến bữa ăn no vừa phải, dễ gọi món, có nước dùng ngon và đủ lựa chọn cho cả nhóm.',
                'content' => '<p>Từ khóa <strong>món chay ngon</strong> thường được tìm rất nhiều khi khách muốn đổi vị hoặc tìm một bữa ăn thanh đạm nhưng vẫn đủ hấp dẫn. Tùy thời điểm trong ngày, nhu cầu sẽ khác nhau: bữa trưa cần nhanh gọn, bữa tối cần ấm bụng, còn cuối tuần thường muốn ngồi lâu hơn và gọi nhiều món để chia sẻ.</p><h2>1. Bữa trưa nên ưu tiên món gọn và dễ ăn</h2><p>Vào buổi trưa, những món như cơm chay, bún chay, mì xào nấm hay các món có sốt nhẹ thường rất phù hợp. Chúng giúp no nhanh nhưng không gây nặng bụng, đặc biệt phù hợp với dân văn phòng hoặc khách đi theo nhóm nhỏ. Đây cũng là lúc một <strong>quán chay Hải Phòng</strong> có nhịp phục vụ nhanh sẽ ghi điểm.</p><h2>2. Bữa tối nên chọn món ấm và cân bằng</h2><p>Buổi tối, nhiều người thích lẩu chay, canh nấm, món cuốn hoặc set nhiều món nhỏ để vừa ăn vừa trò chuyện. Những món này tạo cảm giác thư thả và phù hợp cho gia đình. Với khách đang tìm <strong>nhà hàng chay Hải Phòng</strong>, đây thường là thời điểm họ muốn trải nghiệm không gian nhiều hơn là chỉ ăn cho xong bữa.</p><h2>3. Cuối tuần nên có món theo nhóm</h2><p>Cuối tuần là lúc các set chia sẻ, lẩu chay, mâm chay hoặc món đặc biệt theo mùa dễ được gọi hơn. Một thực đơn có sẵn những món này sẽ giúp khách dễ quyết định, đồng thời tăng khả năng đặt bàn trước vì nhóm đông thường thích chủ động sắp xếp chỗ ngồi.</p><h2>4. Món chay ngon là món có “nhịp”</h2><p>Một món ngon không chỉ nằm ở vị mà còn ở nhịp ăn: có độ giòn, có chút béo, có độ ngọt tự nhiên và không làm khách ngấy. Khi những yếu tố này được cân bằng, khách sẽ nhớ món không chỉ vì tên mà vì cảm giác ăn xong thấy nhẹ bụng, dễ chịu.</p><h3>Kết luận</h3><p>Nếu bạn đang tìm <strong>món chay ngon ở Hải Phòng</strong>, hãy ưu tiên những quán có menu đa dạng theo bữa, có món theo nhóm và có cách trình bày rõ ràng. Đó là dấu hiệu của một nơi hiểu người ăn chay thật sự.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?auto=format&fit=crop&w=1200&q=80',
                'meta_title' => 'Món chay ngon ở Hải Phòng nên thử gì? | Đàn Hương Chay',
                'meta_description' => 'Gợi ý món chay ngon ở Hải Phòng cho bữa trưa, bữa tối và cuối tuần với lựa chọn dễ ăn, no lâu, phù hợp gia đình và nhóm bạn.',
                'meta_keywords' => 'món chay ngon, món chay ngon Hải Phòng, quán chay Hải Phòng, nhà hàng chay Hải Phòng, thực đơn chay',
                'published_at' => now()->subDays(14),
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Sống xanh'],
                'title' => 'Mâm cúng chay và tiệc chay tại nhà: chuẩn bị gì để gọn, đẹp và trang trọng?',
                'excerpt' => 'Nhiều khách tìm quán chay không chỉ để ăn mà còn để đặt mâm cúng chay, tiệc chay gia đình hoặc các buổi lễ cần sự chỉn chu.',
                'content' => '<p>Nhu cầu <strong>mâm cúng chay</strong> và <strong>tiệc chay</strong> ngày càng phổ biến vì nhiều gia đình muốn giữ sự thanh tịnh, gọn gàng và trang trọng trong các dịp quan trọng. Điều quan trọng nhất khi chuẩn bị không phải là làm thật nhiều món, mà là lên bố cục hợp lý, đủ ý nghĩa và dễ dùng.</p><h2>1. Mâm cúng chay cần cân đối</h2><p>Một mâm cúng chay thường nên có món tượng trưng cho sự đủ đầy: món chính, món canh, món cuốn, món xào, xôi hoặc cơm, thêm trái cây hoặc đồ ngọt nhẹ. Cách trình bày nên sạch sẽ, màu sắc hài hòa, tránh quá nặng mùi dầu hoặc quá nhiều gia vị.</p><h2>2. Tiệc chay tại nhà nên ưu tiên tính tiện lợi</h2><p>Nếu cần đặt cho tiệc sinh nhật, kỷ niệm hoặc sum họp gia đình, bộ món nên dễ chia phần, dễ ăn và giữ vị ổn định khi di chuyển. Đây là lý do các quán có dịch vụ <strong>đặt tiệc chay</strong> và <strong>đặt bàn quán chay</strong> thường được tìm nhiều hơn vào cuối tuần và ngày rằm.</p><h2>3. Chọn quán có khả năng tư vấn menu</h2><p>Không phải khách nào cũng biết nên chọn bao nhiêu món. Một quán tốt sẽ tư vấn số lượng vừa phải theo số người, thời gian ăn và mục đích sử dụng. Điều này vừa tiết kiệm, vừa tránh lãng phí và làm cho bữa tiệc gọn gàng hơn.</p><h2>4. Tại sao ngày càng nhiều người tìm quán chay để đặt lễ?</h2><p>Bởi quán chay giúp giảm bớt công chuẩn bị, đồng thời đảm bảo món ăn có hình thức đẹp và vị ổn định. Với những người tìm <strong>quán chay Hải Phòng</strong> cho mục đích này, sự chỉn chu và phản hồi nhanh là hai yếu tố rất quan trọng.</p><h3>Kết luận</h3><p>Dù là mâm cúng hay tiệc chay, điều đáng giá nhất vẫn là sự gọn gàng, trang trọng và phù hợp nhu cầu. Một quán hiểu rõ điều này sẽ luôn có chỗ đứng riêng trong lòng khách hàng.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?auto=format&fit=crop&w=1200&q=80',
                'meta_title' => 'Mâm cúng chay và tiệc chay tại nhà | Đàn Hương Chay',
                'meta_description' => 'Chuẩn bị mâm cúng chay và tiệc chay tại nhà sao cho gọn, đẹp và trang trọng với gợi ý chọn món, số lượng và cách trình bày.',
                'meta_keywords' => 'mâm cúng chay, tiệc chay, đặt tiệc chay, quán chay Hải Phòng, đặt bàn quán chay, món chay ngon',
                'published_at' => now()->subDays(17),
                'is_featured' => false,
                'is_active' => true,
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
