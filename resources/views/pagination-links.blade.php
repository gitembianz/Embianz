@if ($paginator->hasPages())
    <div class="pagination">
        <!-- prev -->
        @if ($paginator->onFirstPage())
            <button class="pagination__item">&#60;</button>
        @else
            <button class="pagination__item" wire:click="previousPage">&#60;</button>
        @endif
        <!-- prev end -->

        <!-- numbers -->
        @foreach ($elements as $element)
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button class="pagination__item active"
                            wire:click="gotoPage({{ $page }})">{{ $page }}</button>
                    @else
                        <button class="pagination__item"
                            wire:click="gotoPage({{ $page }})">{{ $page }}</button>
                    @endif
                @endforeach
            @endif
        @endforeach
        <!-- end numbers -->

        <!-- next  -->
        @if ($paginator->hasMorePages())
            <button class="pagination__item" wire:click="nextPage">&#62;</button>
        @else
            <button class="pagination__item">&#62;</button>
        @endif
        <!-- next end -->
    </div>
@endif
