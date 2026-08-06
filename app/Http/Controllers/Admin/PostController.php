<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Services\UploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(private readonly UploadService $uploads) {}

    public function index(Request $request): View
    {
        $categories = Category::post()->orderBy('name')->get();
        $posts = Post::query()
            ->with('category')
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->q.'%'))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->status === 'active'))
            ->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::post()->active()->orderBy('name')->get();
        $linkablePosts = Post::query()
            ->with('category:id,name')
            ->orderBy('title')
            ->get(['id', 'category_id', 'title']);

        return view('admin.posts.create', [
            'post' => new Post(['is_active' => true]),
            'categories' => $categories,
            'linkablePosts' => $linkablePosts,
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $data = $this->normalizedData($request);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->uploads->uploadImage($request->file('thumbnail'), 'posts');
        }

        $post = Post::create($data);
        $this->syncEnglishTranslation($request, $post);
        $this->syncInternalLinks($request, $post);

        return redirect()->route('admin.posts.index')->with('success', 'Đã thêm bài viết.');
    }

    public function edit(Post $post): View
    {
        $categories = Category::post()->orderBy('name')->get();
        $linkablePosts = Post::query()
            ->with('category:id,name')
            ->whereKeyNot($post->getKey())
            ->orderBy('title')
            ->get(['id', 'category_id', 'title']);
        $post->load('internalLinks');

        return view('admin.posts.edit', compact('post', 'categories', 'linkablePosts'));
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $data = $this->normalizedData($request);

        if ($request->hasFile('thumbnail')) {
            $oldThumbnail = $post->thumbnail;
            $data['thumbnail'] = $this->uploads->uploadImage($request->file('thumbnail'), 'posts');
            $this->uploads->deleteImage($oldThumbnail);
        }

        $post->update($data);
        $this->syncEnglishTranslation($request, $post);
        $this->syncInternalLinks($request, $post);

        return redirect()->route('admin.posts.index')->with('success', 'Đã cập nhật bài viết.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->uploads->deleteImage($post->thumbnail);
        $post->delete();

        return back()->with('success', 'Đã xóa bài viết.');
    }

    private function normalizedData(PostRequest $request): array
    {
        return collect($request->validated())
            ->except(['thumbnail', 'translations', 'related_post_ids'])
            ->merge([
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active'),
            ])
            ->all();
    }

    private function syncEnglishTranslation(PostRequest $request, Post $post): void
    {
        $translation = collect(data_get($request->validated(), 'translations.en', []))
            ->map(fn ($value) => $value === '' ? null : $value);

        if ($translation->filter(fn ($value) => $value !== null)->isEmpty()) {
            $post->translations()->where('locale', 'en')->delete();

            return;
        }

        $post->translations()->updateOrCreate(['locale' => 'en'], $translation->all());
    }

    private function syncInternalLinks(PostRequest $request, Post $post): void
    {
        $links = collect($request->validated('related_post_ids', []))
            ->reject(fn (int|string $id): bool => (int) $id === $post->getKey())
            ->values()
            ->mapWithKeys(fn (int|string $id, int $position): array => [
                (int) $id => ['position' => $position],
            ])
            ->all();

        $post->internalLinks()->sync($links);
    }
}
