<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Dish;
use App\Models\Page;
use App\Models\Post;
use App\Support\OpeningHours;
use Illuminate\Pagination\LengthAwarePaginator;
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
        $url = localized_route('blog.show', ['slug' => $post->localizedSlug()]);
        $organizationId = localized_route('home').'#organization';
        $content = trim(strip_tags($post->localized('content')));
        $wordCount = count(preg_split('/\s+/u', $content, -1, PREG_SPLIT_NO_EMPTY) ?: []);

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            '@id' => $url.'#article',
            'url' => $url,
            'headline' => $post->localized('title'),
            'description' => $post->localized('meta_description') ?: $post->localized('excerpt'),
            'image' => media_url($post->thumbnail, self::defaultImage()),
            'datePublished' => optional($post->published_at)->toAtomString(),
            'dateModified' => $post->updated_at->toAtomString(),
            'articleSection' => $post->category?->localized('name'),
            'keywords' => $post->localized('meta_keywords'),
            'inLanguage' => current_locale(),
            'wordCount' => $wordCount,
            'author' => [
                '@type' => 'Organization',
                'name' => __('site.email.customer_reservation.brand'),
                'url' => localized_route('home'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                '@id' => $organizationId,
                'name' => __('site.email.customer_reservation.brand'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => media_url(setting('logo_header'), self::defaultImage()),
                ],
            ],
            'isPartOf' => [
                '@type' => 'Blog',
                '@id' => localized_route('blog.index').'#blog',
                'url' => localized_route('blog.index'),
            ],
            'mainEntityOfPage' => $url,
        ];
    }

    public static function blogCollectionSchema(LengthAwarePaginator $posts): array
    {
        $blogUrl = localized_route('blog.index');
        $startPosition = (($posts->currentPage() - 1) * $posts->perPage()) + 1;

        return [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            '@id' => $blogUrl.'#blog',
            'url' => $posts->currentPage() === 1 ? $blogUrl : $posts->url($posts->currentPage()),
            'name' => __('site.blog.title'),
            'description' => __('site.blog.description'),
            'inLanguage' => current_locale(),
            'mainEntity' => [
                '@type' => 'ItemList',
                'numberOfItems' => $posts->count(),
                'itemListElement' => collect($posts->items())
                    ->values()
                    ->map(fn (Post $post, int $index): array => [
                        '@type' => 'ListItem',
                        'position' => $startPosition + $index,
                        'url' => localized_route('blog.show', ['slug' => $post->localizedSlug()]),
                        'name' => $post->localized('title'),
                    ])
                    ->all(),
            ],
        ];
    }

    /**
     * @param  array<int, array{question: string, answer: string}>  $faqs
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

        $model = collect($parameters)->first(fn ($value): bool => $value instanceof Dish || $value instanceof Page || $value instanceof Post);
        if ($model && ! self::hasUsableTranslation($model, $locale)) {
            return null;
        }

        return route($routeName, self::parametersForLocale($locale, $name, $parameters));
    }

    private static function hasUsableTranslation(Dish|Page|Post $model, string $locale): bool
    {
        if ($locale === config('locales.default', 'vi')) {
            return true;
        }

        $translation = $model->translation($locale);

        return match (true) {
            $model instanceof Post => $translation
                && filled($translation->slug)
                && filled($translation->title)
                && filled($translation->content),
            $model instanceof Page => $translation
                && filled($translation->slug)
                && filled($translation->title)
                && filled($translation->content),
            default => $translation
                && filled($translation->slug)
                && filled($translation->name),
        };
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
        $openingHours = OpeningHours::fromBranch($branch);
        $days = [
            'https://schema.org/Monday',
            'https://schema.org/Tuesday',
            'https://schema.org/Wednesday',
            'https://schema.org/Thursday',
            'https://schema.org/Friday',
            'https://schema.org/Saturday',
            'https://schema.org/Sunday',
        ];

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
            'menu' => localized_route('menu.index'),
            'acceptsReservations' => localized_route('reservations.create').'?branch='.$branch->id,
            'hasMap' => self::mapUrl($branch->google_map_iframe),
            'openingHoursSpecification' => collect($openingHours->slots)
                ->map(fn (array $slot): array => [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => $days,
                    'opens' => $slot['start'],
                    'closes' => $slot['end'],
                ])
                ->all(),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $branch->address ?: setting('schema_address', setting('address')),
                'addressLocality' => $branch->city,
                'addressCountry' => 'VN',
            ],
            'parentOrganization' => ['@id' => $organizationId],
            'sameAs' => array_values(array_unique(array_filter([
                $branch->facebook_url,
                $branch->zalo_url,
                ...self::sameAs(),
            ]))),
        ], fn ($value): bool => filled($value) || is_array($value));
    }

    private static function mapUrl(?string $map): ?string
    {
        if (blank($map)) {
            return null;
        }

        if (filter_var($map, FILTER_VALIDATE_URL)) {
            return $map;
        }

        if (preg_match('/\bsrc=["\']([^"\']+)["\']/i', $map, $matches)) {
            return html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5);
        }

        return null;
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
