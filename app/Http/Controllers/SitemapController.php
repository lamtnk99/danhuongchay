<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Page;
use App\Models\Post;
use App\Services\SeoService;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect()
            ->merge($this->staticUrls())
            ->merge($this->pageUrls())
            ->merge($this->dishUrls())
            ->merge($this->postUrls())
            ->unique('loc')
            ->values();

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }

    private function staticUrls(): Collection
    {
        return collect([
            ['name' => 'home', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['name' => 'local.vegetarian-restaurant-hai-phong', 'priority' => '0.95', 'changefreq' => 'weekly'],
            ['name' => 'local.vegetarian-restaurant-buon-ma-thuot', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['name' => 'local.vegetarian-catering', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['name' => 'local.vegetarian-catering-hai-phong', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['name' => 'local.vegetarian-catering-buon-ma-thuot', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['name' => 'about', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['name' => 'gallery.index', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['name' => 'menu.index', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['name' => 'blog.index', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['name' => 'reservations.create', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['name' => 'contact', 'priority' => '0.7', 'changefreq' => 'monthly'],
        ])->flatMap(fn (array $url): Collection => $this->localizedUrlEntries(
            SeoService::routeAlternates($url['name']),
            now(),
            $url['changefreq'],
            $url['priority']
        ));
    }

    private function pageUrls(): Collection
    {
        return Page::query()
            ->active()
            ->with('translations')
            ->where('slug', '!=', 'gioi-thieu')
            ->latest('updated_at')
            ->get()
            ->flatMap(fn (Page $page): Collection => $this->localizedUrlEntries(
                SeoService::routeAlternates('pages.show', ['page' => $page]),
                $page->updated_at,
                'monthly',
                '0.7'
            ));
    }

    private function dishUrls(): Collection
    {
        return Dish::query()
            ->active()
            ->with('translations')
            ->latest('updated_at')
            ->get()
            ->flatMap(fn (Dish $dish): Collection => $this->localizedUrlEntries(
                SeoService::routeAlternates('menu.show', ['dish' => $dish]),
                $dish->updated_at,
                'monthly',
                '0.8'
            ));
    }

    private function postUrls(): Collection
    {
        return Post::query()
            ->published()
            ->with('translations')
            ->latest('updated_at')
            ->get()
            ->flatMap(fn (Post $post): Collection => $this->localizedUrlEntries(
                SeoService::routeAlternates('blog.show', ['post' => $post]),
                $post->updated_at,
                'monthly',
                '0.7'
            ));
    }

    private function localizedUrlEntries(array $alternates, Carbon $lastmod, string $changefreq, string $priority): Collection
    {
        return collect($alternates)
            ->map(fn (string $loc): array => [
                'loc' => $loc,
                'lastmod' => $lastmod,
                'changefreq' => $changefreq,
                'priority' => $priority,
                'alternates' => $alternates,
            ])
            ->values();
    }
}
