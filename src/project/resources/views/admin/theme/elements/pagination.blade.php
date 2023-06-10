@if ($paginator->hasPages())
    <div class="row pt-6">
        <div class="col-md-6 pt-2">
            <span>نمایش {{ $paginator->firstItem() }} تا {{ $paginator->lastItem() }} از مجموع {{ number_format($paginator->total()) }} مورد</span>
        </div>
        <div class="col-md-6">
            <nav>
                <ul class="pagination justify-content-end">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item previous disabled" aria-disabled="true"
                            aria-label="@lang('pagination.previous')">
                            <span class="page-link min-w-25px h-25px" aria-hidden="true">
                                <i class="previous"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item previous">
                            <a class="page-link min-w-25px h-25px cursor-pointer"
                                    {{--href="{{ $paginator->previousPageUrl() }}"--}}
                                    onclick="redirect('page', '{{ $paginator->currentPage() - 1 }}')"
                                    rel="prev" aria-label="@lang('pagination.previous')">
                                <i class="previous"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="disabled" aria-disabled="true">
                                <span>{{ $element }}</span>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page" style="cursor: default">
                                        <span class="page-link min-w-25px h-25px">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a onclick="redirect('page', '{{ $page }}')"
                                           class="page-link min-w-25px h-25px cursor-pointer">{{ $page }}</a>
                                    </li>
                                @endif

                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item next">
                            <a type="button" class="page-link min-w-25px h-25px cursor-pointer"
                               {{--href="{{ $paginator->nextPageUrl() }}"--}}
                               onclick="redirect('page', '{{ $paginator->currentPage() + 1 }}')"
                               rel="next"
                               aria-label="@lang('pagination.next')">
                                <i class="next"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item next disabled" aria-disabled="true"
                            aria-label="@lang('pagination.next')">
                            <span class="page-link min-w-25px h-25px" aria-hidden="true">
                                <i class="next"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>

@endif
