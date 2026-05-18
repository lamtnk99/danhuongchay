@if (!empty($items))
    <nav class="mx-auto max-w-7xl px-4 pt-8 text-sm sm:px-6 lg:px-8" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-2 text-stone-500">
            @foreach ($items as $item)
                <li class="flex items-center gap-2">
                    @if (!$loop->first)
                        <span aria-hidden="true">/</span>
                    @endif

                    @if (!empty($item['url']) && !$loop->last)
                        <a href="{{ $item['url'] }}" class="hover:text-emerald-800">{{ $item['label'] }}</a>
                    @else
                        <span class="font-medium text-stone-700">{{ $item['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
