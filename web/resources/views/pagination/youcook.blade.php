{{-- Paginación del mockup: un círculo por página y el botón «Siguiente». --}}
@if ($paginator->hasPages())
    <nav class="mt-10 flex items-center justify-center gap-2" aria-label="Paginación">
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-1 text-charcoal-soft">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @php $actual = $page === $paginator->currentPage(); @endphp
                    <a
                        href="{{ $url }}"
                        @if ($actual) aria-current="page" @endif
                        class="grid size-10 place-items-center rounded-full font-display font-bold {{ $actual ? 'bg-tomato-500 text-white' : 'bg-white text-charcoal shadow-soft hover:text-tomato-600' }}"
                    >{{ $page }}</a>
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="ml-1 flex h-10 items-center gap-1 rounded-full bg-white px-4 font-display font-bold shadow-soft hover:text-tomato-600">
                Siguiente <x-icon name="arrow-right" size="16" />
            </a>
        @endif
    </nav>
@endif
