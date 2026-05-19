<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\UploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(private readonly UploadService $uploads) {}

    public function edit(): View
    {
        return view('admin.settings.edit', [
            'title' => 'Cài đặt website',
            'action' => route('admin.settings.update'),
            'keys' => $this->generalKeys(),
            'imageKeys' => [],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rulesFor($this->generalKeys()));
        $this->saveSettings($data, $this->generalKeys(), 'general');

        return back()->with('success', 'Đã cập nhật cài đặt website.');
    }

    public function identity(): View
    {
        return view('admin.settings.identity', [
            'title' => 'Logo & nhận diện',
            'action' => route('admin.identity.update'),
            'imageKeys' => $this->identityKeys(),
        ]);
    }

    public function updateIdentity(Request $request): RedirectResponse
    {
        $data = $request->validate($this->imageRulesFor($this->identityKeys()));
        $this->saveImages($data, $this->identityKeys(), 'identity');

        return back()->with('success', 'Đã cập nhật logo và nhận diện.');
    }

    public function seo(): View
    {
        return view('admin.settings.seo', [
            'title' => 'SEO tổng thể',
            'action' => route('admin.seo.update'),
            'keys' => $this->seoKeys(),
            'imageKeys' => ['og_image' => 'OG image mặc định'],
        ]);
    }

    public function updateSeo(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rulesFor($this->seoKeys()) + $this->imageRulesFor(['og_image' => 'OG image mặc định']));

        $this->saveSettings($data, $this->seoKeys(), 'seo');
        $this->saveImages($data, ['og_image' => 'OG image mặc định'], 'seo');

        return back()->with('success', 'Đã cập nhật SEO tổng thể.');
    }

    private function generalKeys(): array
    {
        return [
            'site_name' => 'Tên website',
            'restaurant_name' => 'Tên quán',
            'slogan' => 'Slogan',
            'short_description' => 'Mô tả ngắn',
            'phone' => 'Số điện thoại',
            'hotline' => 'Hotline',
            'email' => 'Email',
            'address' => 'Địa chỉ',
            'opening_hours' => 'Giờ mở cửa',
            'reservation_time_slots' => 'Khung giờ nhận đặt bàn',
            'reservation_last_booking_time' => 'Giờ nhận đặt bàn muộn nhất',
            'reservation_last_order_buffer_minutes' => 'Số phút ngừng nhận trước giờ đóng bếp',
            'facebook_url' => 'Link Facebook',
            'zalo_url' => 'Link Zalo',
            'tiktok_url' => 'Link TikTok',
            'instagram_url' => 'Link Instagram',
            'youtube_url' => 'Link YouTube',
            'google_map_iframe' => 'Google Map iframe',
            'copyright' => 'Copyright footer',
            'footer_description' => 'Nội dung footer giới thiệu ngắn',
        ];
    }

    private function identityKeys(): array
    {
        return [
            'logo_header' => 'Logo header',
            'logo_footer' => 'Logo footer',
            'favicon' => 'Favicon',
            'default_background' => 'Ảnh nền mặc định',
        ];
    }

    private function seoKeys(): array
    {
        return [
            'default_meta_title' => 'Meta title mặc định',
            'default_meta_description' => 'Meta description mặc định',
            'default_meta_keywords' => 'Meta keywords mặc định',
            'google_analytics_code' => 'Google Analytics code',
            'google_search_console' => 'Google Search Console verification',
            'facebook_pixel_code' => 'Facebook Pixel code',
            'robots_txt_content' => 'Robots.txt content',
            'schema_restaurant_name' => 'Schema tên quán',
            'schema_address' => 'Schema địa chỉ',
            'schema_phone' => 'Schema số điện thoại',
            'schema_price_range' => 'Schema khoảng giá',
            'schema_opening_hours' => 'Schema giờ mở cửa',
            'schema_latitude' => 'Schema latitude',
            'schema_longitude' => 'Schema longitude',
        ];
    }

    private function rulesFor(array $keys): array
    {
        return collect($keys)
            ->mapWithKeys(function ($label, $key): array {
                return match ($key) {
                    'reservation_last_order_buffer_minutes' => [$key => ['nullable', 'integer', 'min:0', 'max:240']],
                    default => [$key => ['nullable', 'string']],
                };
            })
            ->all();
    }

    private function imageRulesFor(array $keys): array
    {
        return collect($keys)
            ->mapWithKeys(fn ($label, $key): array => [$key => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:'.config('uploads.max_image_kb')]])
            ->all();
    }

    private function saveSettings(array $data, array $keys, string $group): void
    {
        foreach (array_keys($keys) as $key) {
            SiteSetting::set($key, $data[$key] ?? null, 'textarea', $group);
        }
    }

    private function saveImages(array $data, array $keys, string $group): void
    {
        foreach (array_keys($keys) as $key) {
            if (! request()->hasFile($key)) {
                continue;
            }

            $oldPath = setting($key);
            $path = $this->uploads->uploadImage(request()->file($key), 'settings', $this->imageProfileFor($key));
            SiteSetting::set($key, $path, 'image', $group);
            $this->uploads->deleteImage($oldPath);
        }
    }

    private function imageProfileFor(string $key): string
    {
        return match ($key) {
            'logo_header', 'logo_footer', 'favicon' => 'brand',
            'default_background', 'og_image' => 'hero',
            default => 'content',
        };
    }
}
