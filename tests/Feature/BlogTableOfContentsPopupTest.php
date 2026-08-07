<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTableOfContentsPopupTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_blog_article_uses_floating_table_of_contents_without_sidebar_column(): void
    {
        $post = Post::published()->firstOrFail();

        $this->get(route('blog.show', $post))
            ->assertOk()
            ->assertSee('data-toc-toggle', false)
            ->assertSee('data-toc-popup', false)
            ->assertDontSee('blog-toc-sticky', false)
            ->assertDontSee('has-toc', false);
    }
}
