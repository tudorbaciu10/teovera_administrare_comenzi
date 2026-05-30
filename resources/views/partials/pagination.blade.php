@if ($paginator->hasPages())
<nav class="pagination">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="disabled"><span>&#8592;</span></span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}">&#8592;</a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span><span>{{ $element }}</span></span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="active"><span>{{ $page }}</span></span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}">&#8594;</a>
    @else
        <span class="disabled"><span>&#8594;</span></span>
    @endif
</nav>
@endif
