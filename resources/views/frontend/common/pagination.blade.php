@if ($paginator->hasPages())
    <!-- Pagination -->
    <div class="pagination__numbers">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a><i class="las la-arrow-left la-24"></i></a>
        @else
            @php
            $url = $paginator->previousPageUrl();
            if(is_array(request()->input('category'))) {
                foreach (request()->input('category') as  $data) {
                    $url .= "&category%5B%5D=".$data."&";
                    // category%5B%5D=1&category%5B%5D=2#
                }
            }   
            @endphp
            <a href="{{ $url }}"><i class="las la-arrow-left la-24"></i></a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @php
                        if(is_array(request()->input('category'))) {
                            foreach (request()->input('category') as  $data) {
                                $url .= "&category%5B%5D=".$data."&";
                                // category%5B%5D=1&category%5B%5D=2#
                            }
                        }
                    @endphp

                    @if ($page == $paginator->currentPage())
                        {{-- {{dd('here')}} --}}
                        <span>{{ $page }}</span>
                    @elseif ((($page == $paginator->currentPage() - 1 || $page == $paginator->currentPage() - 1) || $page == $paginator->lastPage()) && !isMobile())
                        <a href="{{ $url }}">{{ $page }}</a>
                    @elseif (($page == $paginator->currentPage() + 1 || $page == $paginator->currentPage() + 2) || $page == $paginator->lastPage())
                        <a href="{{ $url }}">{{ $page }}</a>
                    @elseif ($page == $paginator->lastPage() - 1)
                        <a>...</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
                    
            @php
            $url = $paginator->nextPageUrl();
            if(is_array(request()->input('category'))) {
                foreach (request()->input('category') as  $data) {
                    $url .= "&category%5B%5D=".$data."&";
                    // category%5B%5D=1&category%5B%5D=2#
                }
            }   
            @endphp
            <a href="{{ $url }}">
                <i class="las la-arrow-right la-24"></i>
            </a>
        @else
            <a>
                <i class="las la-arrow-right la-24"></i>
            </a>
        @endif
    </div>
    <!-- Pagination -->
@endif