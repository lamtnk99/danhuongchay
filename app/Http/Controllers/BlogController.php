<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\ArticlePresentationService;
use App\Services\PostInternalLinkService;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        private readonly PostInternalLinkService $internalLinks,
        private readonly ArticlePresentationService $articles,
    ) {}

    public function index(): View
    {
        $posts = Post::query()
            ->with(['category.translations', 'translations'])
            ->published()
            ->availableInLocale()
            ->latest('published_at')
            ->paginate(6);

        abort_if($posts->currentPage() > $posts->lastPage(), 404);

        $page = $posts->currentPage();
        $canonical = $page === 1 ? localized_route('blog.index') : $posts->url($page);
        $alternates = collect(SeoService::routeAlternates('blog.index'))
            ->map(fn (string $url): string => $page === 1 ? $url : $url.'?page='.$page)
            ->all();
        $title = __('site.auto.auto_48c9fe5895d3');

        if ($page > 1) {
            $title .= ' — '.__('site.blog.page_title', ['page' => $page]);
        }

        $seo = SeoService::page(
            $title,
            __('site.auto.auto_3a133bf698f5'),
            __('site.auto.auto_13855245ebf3'),
            $canonical,
            alternates: $alternates
        );

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::blogCollectionSchema($posts),
        ];

        return view('blog.index', compact('posts', 'seo', 'schemas'));
    }

    public function show(Post|string $post): RedirectResponse|View
    {
        $requestedSlug = is_string($post) ? $post : $post->slug;

        if (! $post instanceof Post) {
            $post = Post::query()
                ->where('slug', $post)
                ->orWhereHas('translations', fn ($query) => $query->where('locale', current_locale())->where('slug', $post))
                ->firstOrFail();
        }

        $isAdminPreview = request()->attributes->getBoolean('admin_blog_preview');

        abort_if(! $isAdminPreview && (! $post->is_active || blank($post->published_at) || $post->published_at->isFuture()), 404);

        $post->load(['category.translations', 'translations']);

        if (! $isAdminPreview && ($redirect = $this->localizedRedirect($post, $requestedSlug))) {
            return $redirect;
        }

        $relatedPosts = $this->internalLinks->relatedPosts($post);
        $neighbors = $this->internalLinks->chronologicalNeighbors($post);
        $previousPost = $neighbors['previous'];
        $nextPost = $neighbors['next'];
        $article = $this->articles->present($post->localized('content'), current_locale());

        $breadcrumbs = [
            ['label' => __('site.nav.home'), 'url' => localized_route('home')],
            ['label' => __('site.nav.blog'), 'url' => localized_route('blog.index')],
            ['label' => $post->localized('title')],
        ];

        $seo = SeoService::page(
            $post->localized('meta_title') ?: "{$post->localized('title')} | Đàn Hương Chay",
            $post->localized('meta_description') ?: $post->localized('excerpt'),
            $post->localized('meta_keywords') ?: "{$post->localized('title')}, vegetarian restaurant Hai Phong, Dan Huong Chay",
            localized_route('blog.show', ['slug' => $post->localizedSlug()]),
            $post->thumbnail,
            'article',
            $post->localized('title'),
            SeoService::routeAlternates('blog.show', ['post' => $post])
        );
        $seo['published_time'] = $post->published_at->toAtomString();
        $seo['modified_time'] = $post->updated_at->toAtomString();
        $seo['section'] = $post->category->localized('name');
        if ($isAdminPreview) {
            $seo['robots'] = 'noindex, nofollow';
            $seo['language_links'] = collect(array_keys(config('locales.supported', [])))
                ->mapWithKeys(fn (string $locale): array => [
                    $locale => route('admin.posts.preview', ['post' => $post, 'locale' => $locale]),
                ])
                ->all();
        }

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::articleSchema($post, $article),
            SeoService::breadcrumbSchema($breadcrumbs),
        ];

        return view('blog.show', compact(
            'post',
            'relatedPosts',
            'previousPost',
            'nextPost',
            'breadcrumbs',
            'seo',
            'schemas',
            'article'
        ));
    }

    public function preview(Post $post): RedirectResponse|View
    {
        $locale = (string) request()->query('locale', config('locales.default', 'vi'));
        if (! array_key_exists($locale, config('locales.supported', []))) {
            $locale = config('locales.default', 'vi');
        }

        if ($locale !== config('locales.default', 'vi')) {
            $translation = $post->translation($locale);
            if (! $translation || blank($translation->slug) || blank($translation->title) || blank($translation->content)) {
                $locale = config('locales.default', 'vi');
            }
        }

        App::setLocale($locale);
        request()->attributes->set('locale', $locale);
        request()->attributes->set('admin_blog_preview', true);

        return $this->show($post);
    }

    private function localizedRedirect(Post $post, string $requestedSlug): ?RedirectResponse
    {
        if (current_locale() === config('locales.default', 'vi')) {
            return null;
        }

        $translation = $post->translation(current_locale());
        if (! $translation || blank($translation->slug) || blank($translation->title) || blank($translation->content)) {
            return redirect()->route('blog.show', $post, 301);
        }

        if ($requestedSlug !== $translation->slug) {
            return redirect()->route('localized.blog.show', ['slug' => $translation->slug], 301);
        }

        return null;
    }
}
