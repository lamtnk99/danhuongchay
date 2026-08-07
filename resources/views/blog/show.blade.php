@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['items' => $breadcrumbs])

    <div class="reading-progress" data-reading-progress aria-hidden="true"><span></span></div>

    <article class="blog-reader mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <header class="mx-auto max-w-4xl text-center">
            <p class="eyebrow">{{ $post->category->localized('name') }}</p>
            <h1 class="mt-4 text-4xl font-semibold leading-tight text-[#3d4226] sm:text-5xl">{{ $post->localized('title') }}</h1>
            <div class="mt-5 flex flex-wrap items-center justify-center gap-x-3 gap-y-2 text-sm font-medium text-stone-500">
                <span>{{ __('site.blog.by') }} {{ localized_setting('restaurant_name', __('site.email.customer_reservation.brand')) }}</span>
                <span aria-hidden="true">·</span>
                <time datetime="{{ $post->published_at->toAtomString() }}">{{ $post->published_at->format('d/m/Y') }}</time>
                @if ($post->updated_at->isAfter($post->published_at) && ! $post->updated_at->isSameDay($post->published_at))
                    <span aria-hidden="true">·</span>
                    <span>{{ current_locale() === 'en' ? 'Updated' : 'Cập nhật' }} <time datetime="{{ $post->updated_at->toAtomString() }}">{{ $post->updated_at->format('d/m/Y') }}</time></span>
                @endif
                <span aria-hidden="true">·</span>
                <span>{{ $article['reading_minutes'] }} {{ current_locale() === 'en' ? 'min read' : 'phút đọc' }}</span>
            </div>
        </header>

        <img
            src="{{ media_variant_url($post->thumbnail, 'large') }}"
            @if (media_srcset($post->thumbnail, ['card', 'large', 'hero'])) srcset="{{ media_srcset($post->thumbnail, ['card', 'large', 'hero']) }}" @endif
            alt="{{ $post->localized('title') }}"
            class="mx-auto mt-10 aspect-[16/9] w-full max-w-5xl rounded-3xl object-cover shadow-xl"
            fetchpriority="high"
            sizes="(max-width: 1024px) 100vw, 1024px"
        >

        <div class="blog-reader-grid mt-10">
            <div class="min-w-0">
                <div class="article-content" data-article-body>
                    {!! $article['html'] !!}
                </div>

                <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-[#667132]/15 pt-6">
                    <span class="text-sm font-bold text-stone-600">{{ current_locale() === 'en' ? 'Share this article' : 'Chia sẻ bài viết' }}</span>
                    <button type="button" class="blog-share-button" data-share-article data-share-title="{{ $post->localized('title') }}" data-share-url="{{ $seo['canonical'] ?? url()->current() }}">
                        {{ current_locale() === 'en' ? 'Share / copy link' : 'Chia sẻ / sao chép link' }}
                    </button>
                    <span class="hidden text-sm font-semibold text-[#667132]" data-share-status aria-live="polite"></span>
                </div>

                @if ($previousPost || $nextPost)
                    <nav class="mt-10 grid gap-4 border-t border-[#667132]/15 pt-8 sm:grid-cols-2" aria-label="{{ __('site.blog.related_posts') }}">
                        @if ($previousPost)
                            <a href="{{ localized_route('blog.show', ['slug' => $previousPost->localizedSlug()]) }}" rel="prev" class="rounded-2xl border border-[#667132]/15 bg-white p-5 transition hover:border-[#667132]/40 hover:shadow-md">
                                <span class="text-xs font-bold uppercase tracking-[0.18em] text-stone-500">{{ __('site.blog.previous_post') }}</span>
                                <span class="mt-2 block font-semibold leading-snug text-[#3d4226]">{{ $previousPost->localized('title') }}</span>
                            </a>
                        @endif
                        @if ($nextPost)
                            <a href="{{ localized_route('blog.show', ['slug' => $nextPost->localizedSlug()]) }}" rel="next" @class([
                                'rounded-2xl border border-[#667132]/15 bg-white p-5 transition hover:border-[#667132]/40 hover:shadow-md',
                                'text-right sm:col-start-2' => ! $previousPost,
                                'text-right' => $previousPost,
                            ])>
                                <span class="text-xs font-bold uppercase tracking-[0.18em] text-stone-500">{{ __('site.blog.next_post') }}</span>
                                <span class="mt-2 block font-semibold leading-snug text-[#3d4226]">{{ $nextPost->localized('title') }}</span>
                            </a>
                        @endif
                    </nav>
                @endif
            </div>

        </div>

        @if ($article['toc'])
            <button
                type="button"
                class="blog-toc-trigger"
                data-toc-toggle
                aria-controls="blog-toc-popup"
                aria-expanded="false"
            >
                <span class="blog-toc-trigger-ring" aria-hidden="true">
                    <svg viewBox="0 0 44 44">
                        <circle class="blog-toc-trigger-track" cx="22" cy="22" r="19"></circle>
                        <circle class="blog-toc-trigger-progress" cx="22" cy="22" r="19" pathLength="100"></circle>
                    </svg>
                    <span data-reading-percent>0%</span>
                </span>
                <span class="blog-toc-trigger-copy">
                    <small>{{ current_locale() === 'en' ? 'Reading map' : 'Hành trình đọc' }}</small>
                    <strong data-toc-current>{{ current_locale() === 'en' ? 'Article contents' : 'Mục lục bài viết' }}</strong>
                </span>
                <svg class="blog-toc-trigger-arrow" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M7 4l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>

            <div class="blog-toc-backdrop" data-toc-overlay hidden></div>
            <aside
                id="blog-toc-popup"
                class="blog-toc-popup"
                data-toc-popup
                role="dialog"
                aria-labelledby="blog-toc-title"
                hidden
            >
                <div class="blog-toc-popup-head">
                    <div>
                        <span class="blog-toc-popup-kicker">{{ current_locale() === 'en' ? 'Reading map' : 'Bản đồ bài viết' }}</span>
                        <p id="blog-toc-title">{{ current_locale() === 'en' ? 'Your reading journey' : 'Hành trình khám phá' }}</p>
                        <span>{{ $article['reading_minutes'] }} {{ current_locale() === 'en' ? 'min read' : 'phút đọc' }} · {{ count($article['toc']) }} {{ current_locale() === 'en' ? 'sections' : 'đầu mục' }}</span>
                    </div>
                    <button type="button" data-toc-close aria-label="{{ current_locale() === 'en' ? 'Close table of contents' : 'Đóng mục lục' }}">×</button>
                </div>
                <nav aria-label="{{ current_locale() === 'en' ? 'Table of contents' : 'Mục lục bài viết' }}">
                    @foreach ($article['toc'] as $item)
                        <a href="#{{ $item['id'] }}" @class(['is-subheading' => $item['level'] === 3]) data-toc-link>
                            <span class="blog-toc-index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span>{{ $item['text'] }}</span>
                        </a>
                    @endforeach
                </nav>
                <div class="blog-toc-popup-foot">
                    <div><span data-toc-progress-bar></span></div>
                    <p><strong data-reading-percent>0%</strong> {{ current_locale() === 'en' ? 'completed' : 'đã đọc' }}</p>
                </div>
            </aside>
        @endif
    </article>

    @if ($relatedPosts->isNotEmpty())
        <section class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">{{ __('site.blog.related_posts') }}</p>
                <h2>{{ __('site.blog.read_more') }}</h2>
                <p>{{ __('site.blog.related_description') }}</p>
            </div>
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @foreach ($relatedPosts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>
        </section>
    @endif
@endsection
