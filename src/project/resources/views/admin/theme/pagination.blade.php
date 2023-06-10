@if ($paginator->hasPages())
    <ul class="pagination justify-content-end">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item previous disabled"><a href="#" class="page-link min-w-25px h-25px"><i class="previous"></i></a></li>
        @else
            <li class="page-item previous"><a href="{{ $paginator->previousPageUrl() }}" class="page-link min-w-25px h-25px"><i class="previous"></i></a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link min-w-25px h-25px">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><a href="#" class="page-link min-w-25px h-25px">{{ $page }}</a></li>
                    @else
                        <li class="page-item"><a href="{{ $url }}" class="page-link min-w-25px h-25px">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item next"><a href="{{ $paginator->nextPageUrl() }}"  class="page-link min-w-25px h-25px"><i class="next"></i></a></li>
        @else
            <li class="page-item next disabled"><a href="#"  class="page-link min-w-25px h-25px"><i class="next"></i></a></li>
        @endif
    </ul>    
@endif
