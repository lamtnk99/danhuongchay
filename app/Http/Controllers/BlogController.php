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
            'Blog quán chay Hải Phòng | Ăn chay, sống xanh, dinh dưỡng',
            'Bài viết thực tế về quán chay Hải Phòng, nhà hàng chay, món chay ngon, ăn chay healthy, thực đơn chay và phong cách sống xanh.',
            'blog quán chay Hải Phòng, ăn chay, sống xanh, dinh dưỡng chay, món chay ngon, nhà hàng chay Hải Phòng',
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
            $post->meta_keywords ?: "{$post->title}, quán chay Hải Phòng, món chay ngon, ăn chay, sống xanh",
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
