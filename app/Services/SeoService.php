<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Collection;

class SeoService
{
    public const DEFAULT_IMAGE = 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1200&q=80';

    public static function defaultImage(): string
    {
        return media_url(setting('og_image'), self::DEFAULT_IMAGE);
    }

    public static function page(
        ?string $title,
        ?string $description,
        ?string $keywords,
        ?string $canonical = null,
        ?string $image = null,
        string $type = 'website'
    ): array {
        return [
            'title' => $title ?: setting('default_meta_title', 'Đàn Hương Chay | Ẩm thực chay thanh lành'),
            'description' => $description ?: setting('default_meta_description', 'Đàn Hương Chay phục vụ món chay Việt hiện đại, nguyên liệu sạch và không gian thanh tịnh.'),
            'keywords' => $keywords ?: setting('default_meta_keywords', 'Đàn Hương Chay, quán chay, nhà hàng chay, món chay ngon'),
            'canonical' => $canonical ?: url()->current(),
            'image' => media_url($image, self::defaultImage()),
            'type' => $type,
        ];
    }

    public static function restaurantSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => setting('schema_restaurant_name', setting('restaurant_name', 'Đàn Hương Chay')),
            'image' => self::defaultImage(),
            'url' => url('/'),
            'telephone' => setting('schema_phone', setting('phone', '+84 912 345 678')),
            'email' => setting('email', 'hello@danhuongchay.vn'),
            'priceRange' => setting('schema_price_range', '₫₫'),
            'servesCuisine' => ['Vietnamese', 'Vegetarian', 'Vegan'],
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => setting('schema_address', setting('address', '123 Đường An Nhiên, Quận 3, TP. Hồ Chí Minh')),
                'addressLocality' => 'TP. Hồ Chí Minh',
                'addressRegion' => 'TP. Hồ Chí Minh',
                'addressCountry' => 'VN',
            ],
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    'opens' => '09:00',
                    'closes' => '21:30',
                ],
            ],
            'sameAs' => array_values(array_filter([
                setting('facebook_url'),
                setting('zalo_url'),
                setting('instagram_url'),
                setting('youtube_url'),
                setting('tiktok_url'),
            ])),
        ];
    }

    public static function menuSchema(Collection $categories): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Menu',
            'name' => 'Thực đơn Đàn Hương Chay',
            'url' => route('menu.index'),
            'hasMenuSection' => $categories->map(function (Category $category): array {
                return [
                    '@type' => 'MenuSection',
                    'name' => $category->name,
                    'description' => $category->description,
                    'hasMenuItem' => $category->dishes->map(fn ($dish): array => [
                        '@type' => 'MenuItem',
                        'name' => $dish->name,
                        'description' => $dish->description,
                        'image' => $dish->image,
                        'url' => route('menu.show', $dish),
                        'offers' => [
                            '@type' => 'Offer',
                            'price' => (int) $dish->price,
                            'priceCurrency' => 'VND',
                        ],
                    ])->values()->all(),
                ];
            })->values()->all(),
        ];
    }

    public static function articleSchema(Post $post): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->meta_description ?: $post->excerpt,
            'image' => $post->thumbnail ?: self::DEFAULT_IMAGE,
            'datePublished' => optional($post->published_at)->toAtomString(),
            'dateModified' => $post->updated_at->toAtomString(),
            'author' => [
                '@type' => 'Organization',
                'name' => 'Đàn Hương Chay',
                'url' => url('/'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Đàn Hương Chay',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => self::DEFAULT_IMAGE,
                ],
            ],
            'mainEntityOfPage' => route('blog.show', $post),
        ];
    }

    public static function breadcrumbSchema(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)
                ->values()
                ->map(fn (array $item, int $index): array => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['label'],
                    'item' => $item['url'] ?? url()->current(),
                ])
                ->all(),
        ];
    }
}
