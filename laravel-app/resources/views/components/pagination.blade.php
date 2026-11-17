@if ($paginator->hasPages())
<nav class="flex items-center justify-center gap-1 mt-10 mb-4">

    {{-- Előző --}}
    @if ($paginator->onFirstPage())
        <span class="px-3 py-2 rounded-lg text-gray-300 cursor-not-allowed text-sm select-none">&#8592;</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}"
           class="px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-100 text-sm transition">&#8592;</a>
    @endif

    {{-- Oldalszámok --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="px-2 py-2 text-gray-400 text-sm select-none">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-3.5 py-2 rounded-lg text-white text-sm font-bold select-none"
                          style="background-color:#037F8C;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}"
                       class="px-3.5 py-2 rounded-lg text-gray-600 hover:bg-gray-100 text-sm transition">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Következő --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
           class="px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-100 text-sm transition">&#8594;</a>
    @else
        <span class="px-3 py-2 rounded-lg text-gray-300 cursor-not-allowed text-sm select-none">&#8594;</span>
    @endif

</nav>
@endif
