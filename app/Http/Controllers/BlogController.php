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
            ->with('category')
            ->published()
            ->latest('published_at')
            ->paginate(6);

        $seo = SeoService::page(
            'Blog ăn chay và sống xanh | Đàn Hương Chay',
            'Bài viết về ăn chay, sức khỏe, dinh dưỡng thực vật và phong cách sống xanh từ Đàn Hương Chay.',
            'blog ăn chay, sống xanh, sức khỏe, dinh dưỡng chay',
            route('blog.index')
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('blog.index', compact('posts', 'seo', 'schemas'));
    }

    public function show(Post $post): View
    {
        abort_if(! $post->is_active || blank($post->published_at) || $post->published_at->isFuture(), 404);

        $post->load('category');

        $relatedPosts = Post::query()
            ->with('category')
            ->published()
            ->where('category_id', $post->category_id)
            ->whereKeyNot($post->getKey())
            ->latest('published_at')
            ->limit(3)
            ->get();

        $breadcrumbs = [
            ['label' => 'Trang chủ', 'url' => route('home')],
            ['label' => 'Blog', 'url' => route('blog.index')],
            ['label' => $post->title],
        ];

        $seo = SeoService::page(
            $post->meta_title ?: "{$post->title} | Đàn Hương Chay",
            $post->meta_description ?: $post->excerpt,
            $post->meta_keywords ?: "{$post->title}, ăn chay, sống xanh, Đàn Hương Chay",
            route('blog.show', $post),
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
