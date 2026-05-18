<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Đàn Hương Chay', 'group' => 'general'],
            ['key' => 'restaurant_name', 'value' => 'Đàn Hương Chay', 'group' => 'general'],
            ['key' => 'slogan', 'value' => 'Ẩm thực chay thanh lành - Nuôi dưỡng thân tâm', 'group' => 'general'],
            ['key' => 'short_description', 'value' => 'Món chay Việt hiện đại, nguyên liệu tươi sạch, hương vị nhẹ nhàng và không gian yên tĩnh cho những bữa ăn an nhiên.', 'group' => 'general'],
            ['key' => 'phone', 'value' => '0912 345 678', 'group' => 'general'],
            ['key' => 'hotline', 'value' => '0912 345 678', 'group' => 'general'],
            ['key' => 'email', 'value' => 'hello@danhuongchay.vn', 'group' => 'general'],
            ['key' => 'address', 'value' => '123 Đường An Nhiên, Quận 3, TP. Hồ Chí Minh', 'group' => 'general'],
            ['key' => 'opening_hours', 'value' => '09:00 - 21:30 hằng ngày', 'group' => 'general'],
            ['key' => 'facebook_url', 'value' => 'https://www.facebook.com/danhuongchay', 'group' => 'general'],
            ['key' => 'zalo_url', 'value' => 'https://zalo.me/0912345678', 'group' => 'general'],
            ['key' => 'instagram_url', 'value' => 'https://www.instagram.com/danhuongchay', 'group' => 'general'],
            ['key' => 'tiktok_url', 'value' => 'https://www.tiktok.com/@danhuongchay', 'group' => 'general'],
            ['key' => 'youtube_url', 'value' => 'https://www.youtube.com/@danhuongchay', 'group' => 'general'],
            ['key' => 'google_map_iframe', 'value' => '<iframe title="Bản đồ Đàn Hương Chay" src="https://www.google.com/maps?q=Ho%20Chi%20Minh%20City&output=embed" class="h-80 w-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>', 'group' => 'general'],
            ['key' => 'copyright', 'value' => '© '.date('Y').' Đàn Hương Chay. All rights reserved.', 'group' => 'general'],
            ['key' => 'footer_description', 'value' => 'Ẩm thực chay thanh lành, nuôi dưỡng thân tâm bằng nguyên liệu sạch và cách nấu tinh tế.', 'group' => 'general'],
            ['key' => 'default_meta_title', 'value' => 'Đàn Hương Chay | Ẩm thực chay thanh lành', 'group' => 'seo'],
            ['key' => 'default_meta_description', 'value' => 'Đàn Hương Chay phục vụ món chay Việt hiện đại, nguyên liệu sạch, không gian thanh tịnh và dịch vụ đặt bàn tiện lợi.', 'group' => 'seo'],
            ['key' => 'default_meta_keywords', 'value' => 'Đàn Hương Chay, quán chay, nhà hàng chay, món chay ngon, ẩm thực chay', 'group' => 'seo'],
            ['key' => 'robots_txt_content', 'value' => "User-agent: *\nAllow: /\nSitemap: ".url('/sitemap.xml')."\n", 'group' => 'seo'],
            ['key' => 'schema_restaurant_name', 'value' => 'Đàn Hương Chay', 'group' => 'seo'],
            ['key' => 'schema_address', 'value' => '123 Đường An Nhiên, Quận 3, TP. Hồ Chí Minh', 'group' => 'seo'],
            ['key' => 'schema_phone', 'value' => '+84 912 345 678', 'group' => 'seo'],
            ['key' => 'schema_price_range', 'value' => '₫₫', 'group' => 'seo'],
            ['key' => 'schema_opening_hours', 'value' => '09:00 - 21:30', 'group' => 'seo'],
            ['key' => 'og_image', 'value' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1200&q=80', 'type' => 'image', 'group' => 'seo'],
            ['key' => 'default_background', 'value' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1600&q=85', 'type' => 'image', 'group' => 'identity'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::set($setting['key'], $setting['value'], $setting['type'] ?? 'text', $setting['group']);
        }
    }
}
