<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Đàn Hương Chay - Hải Phòng', 'group' => 'general'],
            ['key' => 'restaurant_name', 'value' => 'Đàn Hương Chay', 'group' => 'general'],
            ['key' => 'slogan', 'value' => 'Chay ngon lành - Nghệ thuật - An yên', 'group' => 'general'],
            ['key' => 'short_description', 'value' => 'Ẩm thực chay fusion với menu sáng tạo, giao thoa hương vị, nguyên liệu sạch và không gian an yên tại Hải Phòng.', 'group' => 'general'],
            ['key' => 'phone', 'value' => '094 234 55 15', 'group' => 'general'],
            ['key' => 'hotline', 'value' => '0947 361 515', 'group' => 'general'],
            ['key' => 'email', 'value' => 'info@danhuongchay.com', 'group' => 'general'],
            ['key' => 'address', 'value' => 'Villa 01-B4 Hoàng Mậu - Gia Viên, TP. Hải Phòng', 'group' => 'general'],
            ['key' => 'opening_hours', 'value' => '09:00 - 14:00 | 16:00 - 21:00 hằng ngày', 'group' => 'general'],
            ['key' => 'reservation_time_slots', 'value' => '09:00-14:00,16:00-21:00', 'group' => 'general'],
            ['key' => 'reservation_last_booking_time', 'value' => '20:30', 'group' => 'general'],
            ['key' => 'reservation_last_order_buffer_minutes', 'value' => '30', 'group' => 'general'],
            ['key' => 'show_dish_prices', 'value' => '1', 'type' => 'boolean', 'group' => 'general'],
            ['key' => 'facebook_url', 'value' => 'https://www.facebook.com/danhuongchay', 'group' => 'general'],
            ['key' => 'zalo_url', 'value' => 'https://zalo.me/0947361515', 'group' => 'general'],
            ['key' => 'instagram_url', 'value' => 'https://www.instagram.com/danhuongchay', 'group' => 'general'],
            ['key' => 'tiktok_url', 'value' => 'https://www.tiktok.com/@danhuongchay', 'group' => 'general'],
            ['key' => 'youtube_url', 'value' => 'https://www.youtube.com/@danhuongchay', 'group' => 'general'],
            ['key' => 'google_map_iframe', 'value' => '<iframe title="Bản đồ Đàn Hương Chay Hải Phòng" src="https://www.google.com/maps?q=Villa%2001-B4%20Ho%C3%A0ng%20M%E1%BA%ADu%20Gia%20Vi%C3%AAn%20H%E1%BA%A3i%20Ph%C3%B2ng&output=embed" class="h-80 w-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>', 'group' => 'general'],
            ['key' => 'copyright', 'value' => '© '.date('Y').' Đàn Hương Chay. All rights reserved.', 'group' => 'general'],
            ['key' => 'footer_description', 'value' => 'Ẩm thực chay fusion, giao thoa hương vị, phục vụ bữa ăn an yên từ nguyên liệu sạch và cách nấu tinh tế.', 'group' => 'general'],
            ['key' => 'default_meta_title', 'value' => 'Đàn Hương Chay - Hải Phòng | Ẩm thực chay fusion', 'group' => 'seo'],
            ['key' => 'default_meta_description', 'value' => 'Đàn Hương Chay phục vụ ẩm thực chay fusion tại Hải Phòng, với menu sáng tạo, nguyên liệu sạch, không gian an yên và dịch vụ đặt bàn tiện lợi.', 'group' => 'seo'],
            ['key' => 'default_meta_keywords', 'value' => 'quán chay Hải Phòng, nhà hàng chay Hải Phòng, món chay ngon, thực đơn chay, đặt bàn quán chay, tiệc chay, mâm cúng chay, ăn chay healthy, ẩm thực chay fusion', 'group' => 'seo'],
            ['key' => 'robots_txt_content', 'value' => "User-agent: *\nAllow: /\nSitemap: ".url('/sitemap.xml')."\n", 'group' => 'seo'],
            ['key' => 'schema_restaurant_name', 'value' => 'Đàn Hương Chay', 'group' => 'seo'],
            ['key' => 'schema_address', 'value' => 'Villa 01-B4 Hoàng Mậu - Gia Viên, TP. Hải Phòng', 'group' => 'seo'],
            ['key' => 'schema_phone', 'value' => '+84 947 361 515', 'group' => 'seo'],
            ['key' => 'schema_price_range', 'value' => '₫₫', 'group' => 'seo'],
            ['key' => 'schema_opening_hours', 'value' => '09:00 - 14:00 | 16:00 - 21:00', 'group' => 'seo'],
            ['key' => 'og_image', 'value' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80', 'type' => 'image', 'group' => 'seo'],
            ['key' => 'default_background', 'value' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1600&q=85', 'type' => 'image', 'group' => 'identity'],
            ['key' => 'translation_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'translation'],
            ['key' => 'translation_provider', 'value' => 'deepl', 'group' => 'translation'],
            ['key' => 'deepl_api_plan', 'value' => 'free', 'group' => 'translation'],
            ['key' => 'deepl_source_lang', 'value' => 'VI', 'group' => 'translation'],
            ['key' => 'deepl_target_lang', 'value' => 'EN-US', 'group' => 'translation'],
            ['key' => 'microsoft_translator_endpoint', 'value' => 'https://api.cognitive.microsofttranslator.com', 'group' => 'translation'],
            ['key' => 'microsoft_translator_region', 'value' => null, 'group' => 'translation'],
            ['key' => 'microsoft_translator_target_lang', 'value' => 'en', 'group' => 'translation'],
            ['key' => 'translation_monthly_limit_warning', 'value' => '450000', 'type' => 'number', 'group' => 'translation'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::set($setting['key'], $setting['value'], $setting['type'] ?? 'text', $setting['group']);
        }
    }
}
