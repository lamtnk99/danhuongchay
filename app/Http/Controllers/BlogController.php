<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\SeoService;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->with(['category.translations', 'translations'])
            ->published()
            ->latest('published_at')
            ->paginate(6);

        $seo = SeoService::page(
            is_english() ? 'Vegetarian blog | Dan Huong Chay Hai Phong' : 'Blog quán chay Hải Phòng | Ăn chay, sống xanh, dinh dưỡng',
            is_english() ? 'Practical articles about vegetarian food in Hai Phong, plant-based wellness, green lifestyle and mindful dining at Dan Huong Chay.' : 'Bài viết thực tế về quán chay Hải Phòng, nhà hàng chay, món chay ngon, ăn chay healthy, thực đơn chay và phong cách sống xanh.',
            is_english() ? 'vegetarian blog Hai Phong, vegetarian food, vegan lifestyle, healthy eating, green living' : 'blog quán chay Hải Phòng, ăn chay, sống xanh, dinh dưỡng chay, món chay ngon, nhà hàng chay Hải Phòng',
            localized_route('blog.index')
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('blog.index', compact('posts', 'seo', 'schemas'));
    }

    public function show(Post|string $post): View
    {
        if (! $post instanceof Post) {
            $post = Post::query()
                ->where('slug', $post)
                ->orWhereHas('translations', fn ($query) => $query->where('locale', current_locale())->where('slug', $post))
                ->firstOrFail();
        }

        abort_if(! $post->is_active || blank($post->published_at) || $post->published_at->isFuture(), 404);

        $post->load(['category.translations', 'translations']);

        $relatedPosts = Post::query()
            ->with(['category.translations', 'translations'])
            ->published()
            ->where('category_id', $post->category_id)
            ->whereKeyNot($post->getKey())
            ->latest('published_at')
            ->limit(3)
            ->get();

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
            'article'
        );

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::articleSchema($post),
            SeoService::breadcrumbSchema($breadcrumbs),
        ];

        return view('blog.show', compact('post', 'relatedPosts', 'breadcrumbs', 'seo', 'schemas'));
    }
}
