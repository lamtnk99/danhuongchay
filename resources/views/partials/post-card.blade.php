<article class="group overflow-hidden rounded-2xl border border-emerald-900/10 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
    <a href="{{ route('blog.show', $post) }}" class="block">
        <div class="aspect-[16/10] overflow-hidden bg-emerald-50">
            <img
                src="{{ media_url($post->thumbnail) }}"
                alt="{{ $post->title }}"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                loading="lazy"
                sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
            >
        </div>
    </a>
    <div class="p-5">
        <div class="flex flex-wrap items-center gap-3 text-xs font-medium text-stone-500">
            <span class="rounded-full bg-amber-50 px-3 py-1 text-amber-800">{{ $post->category->name }}</span>
            <time datetime="{{ optional($post->published_at)->toDateString() }}">
                {{ optional($post->published_at)->format('d/m/Y') }}
            </time>
        </div>
        <h3 class="mt-4 text-xl font-semibold leading-snug text-emerald-950">
            <a href="{{ route('blog.show', $post) }}" class="hover:text-emerald-700">{{ $post->title }}</a>
        </h3>
        <p class="mt-3 line-clamp-3 text-sm leading-6 text-stone-600">{{ $post->excerpt }}</p>
    </div>
</article>
