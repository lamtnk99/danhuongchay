<article class="group overflow-hidden rounded-2xl border border-[#667132]/10 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
    <a href="{{ localized_route('blog.show', ['slug' => $post->localizedSlug()]) }}" class="block">
        <div class="aspect-[16/10] overflow-hidden bg-[#f5f5ec]">
            <img
                src="{{ media_variant_url($post->thumbnail, 'card') }}"
                @if (media_srcset($post->thumbnail, ['thumb', 'card', 'large'])) srcset="{{ media_srcset($post->thumbnail, ['thumb', 'card', 'large']) }}" @endif
                alt="{{ $post->localized('title') }}"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                loading="lazy"
                sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
            >
        </div>
    </a>
    <div class="p-5">
        <div class="flex flex-wrap items-center gap-3 text-xs font-medium text-stone-500">
            <span class="rounded-full bg-[#667132]/8 px-3 py-1 text-[#667132]">{{ $post->category->localized('name') }}</span>
            <time datetime="{{ optional($post->published_at)->toDateString() }}">
                {{ optional($post->published_at)->format('d/m/Y') }}
            </time>
        </div>
        <h3 class="mt-4 text-xl font-semibold leading-snug text-[#3d4226]">
            <a href="{{ localized_route('blog.show', ['slug' => $post->localizedSlug()]) }}" class="hover:text-[#525c27]">{{ $post->localized('title') }}</a>
        </h3>
        <p class="mt-3 line-clamp-3 text-sm leading-6 text-stone-600">{{ $post->localized('excerpt') }}</p>
    </div>
</article>
