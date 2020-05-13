@if ($paginator->hasPages())
<div class="row">
    <div class="col-md-12">
        <div class="pagination-container margin-top-10 margin-bottom-20">
            <nav class="pagination">
                <ul>
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="disabled pagination-arrow" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <a><i aria-hidden="true" class="icon-material-outline-keyboard-arrow-left"></i></a>
                        </li>
                    @else
                        <li class="ripple-effect">
                            <a class="ripple-effect" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                                <i class="icon-material-outline-keyboard-arrow-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="disabled" aria-disabled="true">
                                <a class="ripple-effect"><span>{{ $element }}</span></a>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li aria-current="page">
                                        <a href="#" class="current-page ripple-effect"><span>{{ $page }}</span></a>
                                    </li>
                                @else
                                    <li>
                                        <a class="ripple-effect" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="pagination-arrow">
                            <a class="ripple-effect" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                                <i class="icon-material-outline-keyboard-arrow-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="pagination-arrow disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <a><i aria-hidden="true" class="icon-material-outline-keyboard-arrow-right"></i></a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>
@endif
