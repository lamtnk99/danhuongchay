<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\GalleryImage;
use App\Models\Promotion;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MarketingEnglishTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $this->settings();

        $bannerMap = [
            'Ẩm thực chay thanh lành - Nuôi dưỡng thân tâm' => [
                'title' => 'Wholesome vegetarian cuisine for body and mind',
                'subtitle' => 'Dan Huong Chay',
                'description' => 'Modern Vietnamese vegetarian food with fresh ingredients, gentle flavors and a peaceful space for mindful meals.',
                'button_text' => 'View menu',
            ],
        ];

        Banner::query()->get()->each(function (Banner $banner) use ($bannerMap): void {
            $data = $bannerMap[$banner->title] ?? [
                'title' => Str::headline(Str::ascii($banner->title)),
                'subtitle' => $banner->subtitle ? Str::headline(Str::ascii($banner->subtitle)) : null,
                'description' => 'A featured message from Dan Huong Chay vegetarian restaurant in Hai Phong.',
                'button_text' => $banner->button_text ? 'View details' : null,
            ];

            $banner->translations()->updateOrCreate(['locale' => 'en'], $data);
        });

        $promotionMap = [
            'Lẩu Nấm Thập Tam Hương' => ['Thirteen-Herb Mushroom Hot Pot', 'New signature from the Hai Phong menu', 'A broth built from 13 Eastern herbs with premium mushrooms, ideal for groups of 2-4 guests.', 'Signature dish', 'View dish'],
            'Mâm Cuốn Đàn Hương' => ['Dan Huong Fresh Roll Platter', 'A light vegetarian suggestion for friends', 'Betel leaf mushroom rolls, soft banh hoi and fresh greens served with our signature grain-based dipping sauce.', 'Best seller', 'Book to try'],
            'Nấm Đút Lò Phô Mai' => ['Baked Mushrooms with Mozzarella', 'A rich European-style dish made to share', 'Mushrooms and pumpkin covered with stretchy Mozzarella, served with crispy toasted bread.', 'New highlight', 'View menu'],
            'Trà Nhài Cam Nhãn Tươi' => ['Jasmine Tea with Orange and Fresh Longan', 'Complimentary for advance bookings', 'Book online this week and tables from two guests receive a refreshing jasmine, orange and longan tea.', 'A small mindful gift', 'Book table'],
        ];

        Promotion::query()->get()->each(function (Promotion $promotion) use ($promotionMap): void {
            $mapped = $promotionMap[$promotion->title] ?? null;
            $promotion->translations()->updateOrCreate(['locale' => 'en'], [
                'title' => $mapped[0] ?? Str::headline(Str::ascii($promotion->title)),
                'subtitle' => $mapped[1] ?? ($promotion->subtitle ? 'Featured offer from Dan Huong Chay' : null),
                'description' => $mapped[2] ?? 'A seasonal vegetarian promotion from Dan Huong Chay in Hai Phong.',
                'badge' => $mapped[3] ?? ($promotion->badge ? 'Special offer' : null),
                'button_text' => $mapped[4] ?? ($promotion->button_text ? 'View details' : null),
            ]);
        });

        $testimonialMap = [
            'Minh Anh' => ['Weekend regular', 'Peaceful space, light food and very balanced flavors. The mushroom hot pot is the dish that will bring me back.'],
            'Hoàng Nam' => ['Office guest', 'The vegetarian menu is easy to enjoy and beautifully presented. The team explains dishes carefully for guests new to vegetarian food.'],
            'Thanh Trúc' => ['Family guest', 'I like how the restaurant uses fresh vegetables and gentle seasoning. The meal feels very wholesome afterward.'],
            'Gia Hân' => ['Long-term vegetarian guest', 'The tofu with mushroom sauce is soft, fragrant and not heavy. Portions are balanced and service is quick at lunch.'],
            'Quốc Bảo' => ['Group reservation guest', 'We booked a small birthday table. The restaurant prepared everything thoughtfully and the hot pot suited many tastes.'],
            'Lan Phương' => ['New vegetarian guest', 'Vegetarian food here never feels lacking in flavor. Fresh vegetables, clean broth and very kind staff.'],
            'Tuấn Khang' => ['Evening guest', 'The space has a soft wood scent and comfortable lighting. I choose this place when I want a slower dinner.'],
            'Mai Chi' => ['Takeaway guest', 'Takeaway packaging is clean and the food stays warm. Brown rice and braised mushrooms are my favorite combo.'],
            'An Nhiên' => ['Green lifestyle guest', 'I appreciate that the flavors are not overly strong. I feel light after eating but still full for a long time.'],
            'Đức Minh' => ['Friend gathering guest', 'Beautiful dishes, reasonable prices and many choices for a group of friends. The drinks are also very good.'],
        ];

        Testimonial::query()->get()->each(function (Testimonial $testimonial) use ($testimonialMap): void {
            $mapped = $testimonialMap[$testimonial->name] ?? null;
            $testimonial->translations()->updateOrCreate(['locale' => 'en'], [
                'role' => $mapped[0] ?? 'Guest',
                'content' => $mapped[1] ?? 'A thoughtful vegetarian dining experience with gentle flavors and a peaceful space.',
            ]);
        });

        $galleryMap = [
            'Góc bàn gỗ cạnh mảng xanh' => ['Wooden table beside greenery', 'A warm corner with enough privacy for a light dinner or a small gathering.', 'Wooden table and greenery in the restaurant'],
            'Ánh sáng dịu buổi chiều' => ['Soft afternoon light', 'Natural light makes the vegetarian dining space feel softer and more comfortable.', 'Restaurant space with soft afternoon light'],
            'Khu bàn nhóm gia đình' => ['Family group table area', 'An airy layout for friends, families and advance group bookings.', 'Group table area in a vegetarian restaurant'],
            'Quầy trà và góc chờ' => ['Tea counter and waiting corner', 'A small corner for guests to rest and enjoy herbal tea before the meal.', 'Tea counter and waiting corner in the restaurant'],
            'Chi tiết gỗ và cây xanh' => ['Wood and greenery details', 'Natural material details create a close, quiet and grounded feeling.', 'Wood and greenery details in the restaurant space'],
            'Bếp chuẩn bị rau củ tươi' => ['Kitchen preparing fresh vegetables', 'Daily ingredient preparation with fresh vegetables and a clean workflow.', 'Fresh vegetables being prepared in the kitchen'],
        ];

        GalleryImage::query()->get()->each(function (GalleryImage $image) use ($galleryMap): void {
            $mapped = $galleryMap[$image->title] ?? null;
            $title = $mapped[0] ?? Str::headline(Str::ascii($image->title));
            $image->translations()->updateOrCreate(['locale' => 'en'], [
                'title' => $title,
                'slug' => Str::slug(Str::ascii($title)),
                'description' => $mapped[1] ?? 'A peaceful corner at Dan Huong Chay vegetarian restaurant in Hai Phong.',
                'alt_text' => $mapped[2] ?? $title,
                'meta_title' => "{$title} | Dan Huong Chay Space",
                'meta_description' => "View {$title} at Dan Huong Chay vegetarian restaurant in Hai Phong.",
            ]);
        });
    }

    private function settings(): void
    {
        $settings = [
            'site_name_en' => 'Dan Huong Chay - Hai Phong',
            'restaurant_name_en' => 'Dan Huong Chay',
            'slogan_en' => 'Wholesome vegetarian cuisine - Nourishing body and mind',
            'short_description_en' => 'Vegetarian fusion cuisine with a creative menu, clean ingredients and a peaceful dining space in Hai Phong.',
            'address_en' => 'Villa 01-B4 Hoang Mau - Gia Vien, Hai Phong City',
            'opening_hours_en' => '09:00 - 14:00 | 16:00 - 21:00 daily',
            'footer_description_en' => 'Vegetarian fusion cuisine in Hai Phong, crafted with clean ingredients, gentle flavors and a peaceful dining space.',
            'copyright_en' => '© '.date('Y').' Dan Huong Chay. All rights reserved.',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value, 'text', 'translation');
        }
    }
}
