<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostMedia;
use App\Models\User;
use App\Services\PostMediaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostMediaUploadTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_admin_can_upload_an_optimized_image_for_article_content(): void
    {
        Storage::fake('public');
        config()->set('uploads.disk', 'public');
        $admin = User::factory()->create(['role' => 'admin']);
        $post = Post::query()->firstOrFail();

        $response = $this->actingAs($admin)->postJson(route('admin.posts.media.store'), [
            'image' => UploadedFile::fake()->image('mam-com-chay.jpg', 1200, 800),
            'post_id' => $post->getKey(),
            'alt_text' => 'Mâm cơm chay theo mùa',
            'caption' => 'Món chay dùng nguyên liệu tươi.',
            'layout' => 'wide',
        ]);

        $response->assertCreated()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('media.alt_text', 'Mâm cơm chay theo mùa')
            ->assertJsonPath('media.layout', 'wide');

        $media = PostMedia::query()->firstOrFail();
        $this->assertSame($post->getKey(), $media->post_id);
        $this->assertSame(1200, $media->width);
        $this->assertSame(800, $media->height);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_article_upload_rejects_svg_files(): void
    {
        Storage::fake('public');
        config()->set('uploads.disk', 'public');
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->postJson(route('admin.posts.media.store'), [
            'image' => UploadedFile::fake()->create('unsafe.svg', 10, 'image/svg+xml'),
        ])->assertUnprocessable()->assertJsonValidationErrors('image');

        $this->assertDatabaseCount('post_media', 0);
    }

    public function test_uploaded_media_is_attached_when_a_new_post_is_saved(): void
    {
        $post = Post::query()->firstOrFail();
        $media = PostMedia::create([
            'path' => 'posts/content/example.webp',
            'disk' => 'public',
            'alt_text' => 'Ảnh ban đầu',
            'layout' => 'normal',
        ]);
        $content = '<p>Mở đầu.</p><figure data-blog-image data-media-id="'.$media->getKey().'" data-layout="wide">'
            .'<img src="/storage/posts/content/example.webp" alt="Mâm cơm chay" width="1200" height="800">'
            .'<figcaption>Bữa ăn thanh nhẹ.</figcaption></figure>';

        app(PostMediaService::class)->syncForPost($post, $content);

        $media->refresh();
        $this->assertSame($post->getKey(), $media->post_id);
        $this->assertSame('Mâm cơm chay', $media->alt_text);
        $this->assertSame('Bữa ăn thanh nhẹ.', $media->caption);
        $this->assertSame('wide', $media->layout);
    }
}
