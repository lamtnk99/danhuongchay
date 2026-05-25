<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\GalleryImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HaiPhongGalleryImageSeeder extends Seeder
{
    public function run(): void
    {
        $haiPhong = Branch::query()->where('slug', 'hai-phong')->first();
        $buonMaThuot = Branch::query()->where('slug', 'buon-ma-thuot')->first();

        if (! $haiPhong) {
            $this->command?->warn('Không tìm thấy cơ sở Hải Phòng, bỏ qua seed ảnh không gian.');

            return;
        }

        $this->moveLegacyImagesToBuonMaThuot($buonMaThuot);
        $this->seedHaiPhongImages($haiPhong);
    }

    private function seedHaiPhongImages(Branch $branch): void
    {
        $images = [
            ['Góc dùng bữa an yên', 'Peaceful dining corner'],
            ['Không gian xanh thanh tịnh', 'Calm green dining space'],
            ['Bàn ăn cho gia đình', 'Family dining table'],
            ['Ánh sáng dịu trong nhà hàng', 'Soft light inside the restaurant'],
            ['Góc ngồi gần mảng xanh', 'Seating corner beside greenery'],
            ['Không gian chay ấm áp', 'Warm vegetarian dining room'],
            ['Chi tiết bàn ghế gỗ', 'Wooden table and chair details'],
            ['Khu dùng bữa riêng tư', 'Private dining area'],
            ['Không gian tiếp khách nhẹ nhàng', 'Gentle welcoming space'],
            ['Góc thưởng thức món chay', 'Vegetarian tasting corner'],
            ['Sảnh nhà hàng Đàn Hương Chay', 'Dan Huong Chay restaurant hall'],
            ['Khu bàn nhóm Hải Phòng', 'Hai Phong group table area'],
            ['Không gian tĩnh tại', 'Still and mindful space'],
            ['Bàn ăn bên ánh sáng mềm', 'Dining table in soft light'],
            ['Góc nội thất mộc', 'Natural interior corner'],
            ['Không gian chay cho nhóm bạn', 'Vegetarian space for friends'],
            ['Một góc Đàn Hương Chay Hải Phòng', 'A corner of Dan Huong Chay Hai Phong'],
        ];

        foreach ($images as $index => [$title, $englishTitle]) {
            $number = $index + 1;
            $filename = sprintf('khong-gian-hai-phong-%02d.jpg', $number);
            $slug = sprintf('khong-gian-dan-huong-chay-hai-phong-%02d', $number);

            $image = GalleryImage::updateOrCreate(
                ['slug' => $slug],
                [
                    'branch_id' => $branch->id,
                    'title' => $title,
                    'description' => 'Không gian Đàn Hương Chay Hải Phòng với chất liệu mộc, ánh sáng dịu và cảm giác an yên cho bữa chay gia đình, bạn bè hoặc những buổi gặp gỡ nhẹ nhàng.',
                    'image' => '/images/khong-gian-hai-phong/'.$filename,
                    'alt_text' => $title.' tại Đàn Hương Chay Hải Phòng',
                    'location' => 'space',
                    'sort_order' => $number,
                    'is_featured' => true,
                    'is_active' => true,
                    'meta_title' => $title.' | Đàn Hương Chay Hải Phòng',
                    'meta_description' => 'Ảnh không gian thực tế tại Đàn Hương Chay Hải Phòng, phù hợp cho khách xem trước khi đặt bàn hoặc đặt tiệc chay.',
                ]
            );

            $this->syncEnglishTranslation(
                $image,
                $englishTitle,
                'A real view of Dan Huong Chay Hai Phong with natural materials, soft light and a peaceful mood for vegetarian meals.',
                $englishTitle.' at Dan Huong Chay Hai Phong',
                'Hai Phong'
            );
        }
    }

    private function moveLegacyImagesToBuonMaThuot(?Branch $branch): void
    {
        if (! $branch) {
            $this->command?->warn('Không tìm thấy cơ sở Buôn Ma Thuột, bỏ qua chuyển ảnh cũ.');

            return;
        }

        $legacyImages = [
            'goc-ban-go-canh-mang-xanh' => ['Wooden table beside greenery', 'A warm vegetarian dining corner for small gatherings.'],
            'anh-sang-diu-buoi-chieu' => ['Soft afternoon light', 'Natural afternoon light in a relaxed vegetarian dining space.'],
            'khu-ban-nhom-gia-dinh' => ['Family group table area', 'An airy table layout for families and friend groups.'],
            'quay-tra-va-goc-cho' => ['Tea counter and waiting corner', 'A quiet corner for guests to settle in before the meal.'],
            'chi-tiet-go-va-cay-xanh' => ['Wood and greenery details', 'Natural wood and greenery details for a calm dining mood.'],
            'bep-chuan-bi-rau-cu-tuoi' => ['Kitchen preparing fresh vegetables', 'Fresh vegetables prepared daily for vegetarian dishes.'],
        ];

        foreach (array_keys($legacyImages) as $index => $slug) {
            $image = GalleryImage::query()->where('slug', $slug)->first();

            if (! $image) {
                continue;
            }

            $image->update([
                'branch_id' => $branch->id,
                'sort_order' => $index + 3,
            ]);

            [$englishTitle, $englishDescription] = $legacyImages[$slug];
            $this->syncEnglishTranslation(
                $image,
                $englishTitle,
                $englishDescription,
                $englishTitle.' at Dan Huong Chay Buon Ma Thuot',
                'Buon Ma Thuot'
            );
        }
    }

    private function syncEnglishTranslation(
        GalleryImage $image,
        string $title,
        string $description,
        string $altText,
        string $branchName
    ): void {
        $image->translations()->updateOrCreate(['locale' => 'en'], [
            'title' => $title,
            'slug' => Str::slug($branchName.' '.$title),
            'description' => $description,
            'alt_text' => $altText,
            'meta_title' => $title.' | Dan Huong Chay '.$branchName,
            'meta_description' => 'View '.$title.' at Dan Huong Chay '.$branchName.'.',
        ]);
    }
}
