<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

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
        string $type = 'website',
        ?string $imageAlt = null,
        ?array $alternates = null,
        ?string $robots = null
    ): array {
        $title = $title ?: localized_setting('default_meta_title', __('site.seo_fallback.default_title'));

        return array_filter([
            'title' => $title,
            'description' => $description ?: localized_setting('default_meta_description', __('site.seo_fallback.default_description')),
            'keywords' => $keywords ?: localized_setting('default_meta_keywords', __('site.seo_fallback.default_keywords')),
            'canonical' => $canonical ?: url()->current(),
            'image' => media_url($image, self::defaultImage()),
            'image_alt' => $imageAlt ?: $title,
            'type' => $type,
            'alternates' => $alternates ?: self::currentRouteAlternates(),
            'robots' => $robots,
        ], fn ($value): bool => $value !== null && $value !== []);
    }

    public static function routeAlternates(string $name, array $parameters = []): array
    {
        return collect(array_keys(config('locales.supported', [])))
            ->mapWithKeys(fn (string $locale): array => [$locale => self::routeForLocale($locale, $name, $parameters)])
            ->filter()
            ->all();
    }

    public static function currentRouteAlternates(): array
    {
        $route = request()->route();
        $name = $route?->getName();

        if (! $name) {
            return [
                'vi' => route('home'),
                'en' => route('localized.home'),
            ];
        }

        $name = str_starts_with($name, 'localized.') ? substr($name, strlen('localized.')) : $name;

        if (! Route::has($name) && ! Route::has("localized.{$name}")) {
            return [];
        }

        return self::routeAlternates($name, $route?->parameters() ?? []);
    }

    public static function restaurantSchema(?Branch $contextBranch = null): array
    {
        $branches = Branch::query()
            ->active()
            ->when($contextBranch, fn ($query) => $query->orderByRaw('id = ? desc', [$contextBranch->id]))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $organizationId = localized_route('home').'#organization';
        $websiteId = localized_route('home').'#website';
        $branchNodes = $branches
            ->map(fn (Branch $branch): array => self::branchSchemaNode($branch, $organizationId))
            ->values()
            ->all();

        return [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter([
                [
                    '@type' => ['Organization', 'Restaurant'],
                    '@id' => $organizationId,
                    'name' => localized_setting('schema_restaurant_name', localized_setting('restaurant_name', __('site.email.customer_reservation.brand'))),
                    'url' => localized_route('home'),
                    'logo' => media_url(setting('logo_header'), self::defaultImage()),
                    'image' => self::defaultImage(),
                    'telephone' => setting('schema_phone', setting('hotline', setting('phone'))),
                    'email' => setting('email'),
                    'priceRange' => setting('schema_price_range', 'VND'),
                    'servesCuisine' => ['Vietnamese', 'Vegetarian', 'Vegan'],
                    'sameAs' => self::sameAs(),
                    'department' => collect($branchNodes)->map(fn (array $node): array => ['@id' => $node['@id']])->all(),
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => $websiteId,
                    'url' => localized_route('home'),
                    'name' => localized_setting('site_name', localized_setting('restaurant_name', __('site.email.customer_reservation.brand'))),
                    'publisher' => ['@id' => $organizationId],
                ],
                ...$branchNodes,
            ])),
        ];
    }

    public static function menuSchema(Collection $categories): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Menu',
            'name' => __('site.seo_fallback.menu_schema_name'),
            'url' => localized_route('menu.index'),
            'hasMenuSection' => $categories->map(function (Category $category): array {
                return [
                    '@type' => 'MenuSection',
                    'name' => $category->localized('name'),
                    'description' => $category->localized('description'),
                    'hasMenuItem' => $category->dishes->map(function ($dish): array {
                        $item = [
                            '@type' => 'MenuItem',
                            'name' => $dish->localized('name'),
                            'description' => $dish->localized('description'),
                            'image' => media_url($dish->image, self::defaultImage()),
                            'url' => localized_route('menu.show', ['slug' => $dish->localizedSlug()]),
                        ];

                        if (show_dish_prices()) {
                            $item['offers'] = [
                                '@type' => 'Offer',
                                'price' => (int) ($dish->sale_price ?: $dish->price),
                                'priceCurrency' => 'VND',
                            ];
                        }

                        return $item;
                    })->values()->all(),
                ];
            })->values()->all(),
        ];
    }

    public static function dishSchema($dish): array
    {
        $url = localized_route('menu.show', ['slug' => $dish->localizedSlug()]);
        $image = media_url($dish->image, self::defaultImage());
        $description = $dish->localized('meta_description') ?: $dish->localized('description');
        $offer = null;

        if (show_dish_prices()) {
            $offer = [
                '@type' => 'Offer',
                'price' => (int) ($dish->sale_price ?: $dish->price),
                'priceCurrency' => 'VND',
                'availability' => 'https://schema.org/InStock',
                'url' => $url,
            ];
        }

        $menuItem = [
            '@type' => 'MenuItem',
            '@id' => $url.'#menu-item',
            'name' => $dish->localized('name'),
            'description' => $description,
            'image' => $image,
            'url' => $url,
            'menuAddOn' => $dish->category?->localized('name'),
        ];

        $product = [
            '@type' => 'Product',
            '@id' => $url.'#product',
            'name' => $dish->localized('name'),
            'description' => $description,
            'image' => $image,
            'url' => $url,
            'brand' => [
                '@type' => 'Brand',
                'name' => localized_setting('restaurant_name', __('site.email.customer_reservation.brand')),
            ],
            'category' => $dish->category?->localized('name'),
        ];

        if ($offer) {
            $menuItem['offers'] = $offer;
            $product['offers'] = $offer;
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => [$menuItem, $product],
        ];
    }

    public static function articleSchema(Post $post): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->localized('title'),
            'description' => $post->localized('meta_description') ?: $post->localized('excerpt'),
            'image' => media_url($post->thumbnail, self::DEFAULT_IMAGE),
            'datePublished' => optional($post->published_at)->toAtomString(),
            'dateModified' => $post->updated_at->toAtomString(),
            'author' => [
                '@type' => 'Organization',
                'name' => __('site.email.customer_reservation.brand'),
                'url' => localized_route('home'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => __('site.email.customer_reservation.brand'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => self::defaultImage(),
                ],
            ],
            'mainEntityOfPage' => localized_route('blog.show', ['slug' => $post->localizedSlug()]),
        ];
    }

    /**
     * @param array<int, array{question: string, answer: string}> $faqs
     */
    public static function faqSchema(array $faqs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqs)
                ->map(fn (array $faq): array => [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['answer'],
                    ],
                ])
                ->values()
                ->all(),
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

    private static function routeForLocale(string $locale, string $name, array $parameters = []): ?string
    {
        $routeName = $locale === config('locales.default', 'vi') ? $name : "localized.{$name}";

        if (! Route::has($routeName)) {
            return null;
        }

        return route($routeName, self::parametersForLocale($locale, $name, $parameters));
    }

    private static function parametersForLocale(string $locale, string $name, array $parameters): array
    {
        $model = collect($parameters)->first(fn ($value): bool => is_object($value) && method_exists($value, 'localizedSlug'));

        if (! $model) {
            return $parameters;
        }

        $slug = $model->localizedSlug($locale);

        return match ($name) {
            'menu.show' => $locale === config('locales.default', 'vi') ? ['dish' => $slug] : ['slug' => $slug],
            'blog.show' => $locale === config('locales.default', 'vi') ? ['post' => $slug] : ['slug' => $slug],
            'pages.show' => ['page' => $slug],
            default => $parameters,
        };
    }

    private static function branchSchemaNode(Branch $branch, string $organizationId): array
    {
        return array_filter([
            '@type' => ['Restaurant', 'LocalBusiness'],
            '@id' => self::branchUrl($branch).'#local-business',
            'name' => $branch->name,
            'url' => self::branchUrl($branch),
            'image' => media_url($branch->image, self::defaultImage()),
            'description' => $branch->description,
            'telephone' => $branch->hotline ?: $branch->phone ?: setting('hotline', setting('phone')),
            'email' => $branch->email ?: setting('email'),
            'priceRange' => setting('schema_price_range', 'VND'),
            'servesCuisine' => ['Vietnamese', 'Vegetarian', 'Vegan'],
            'openingHours' => $branch->opening_hours ?: setting('schema_opening_hours', setting('opening_hours')),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $branch->address ?: setting('schema_address', setting('address')),
                'addressLocality' => $branch->city,
                'addressCountry' => 'VN',
            ],
            'parentOrganization' => ['@id' => $organizationId],
            'sameAs' => array_values(array_filter([$branch->facebook_url, $branch->zalo_url])),
        ], fn ($value): bool => filled($value) || is_array($value));
    }

    private static function branchUrl(Branch $branch): string
    {
        return match ($branch->slug) {
            'hai-phong' => localized_route('local.vegetarian-restaurant-hai-phong'),
            'buon-ma-thuot' => localized_route('local.vegetarian-restaurant-buon-ma-thuot'),
            default => localized_route('contact').'?branch='.$branch->slug,
        };
    }

    private static function sameAs(): array
    {
        return array_values(array_filter([
            setting('facebook_url'),
            setting('zalo_url'),
            setting('instagram_url'),
            setting('youtube_url'),
            setting('tiktok_url'),
        ]));
    }
}
