@if ($paginator->hasPages())
    <nav>
        <ul class="uk-pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="uk-disabled" aria-disabled="true">
                    <span aria-hidden="true" uk-icon="chevron-double-left"></span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" uk-icon="chevron-double-left"></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="uk-disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="uk-active" aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" uk-icon="chevron-double-right"></a>
                </li>
            @else
                <li class="uk-disabled" aria-disabled="true">
                    <span aria-hidden="true" uk-icon="chevron-double-right"></span>
                </li>
            @endif
        </ul>
    </nav>
@endif
