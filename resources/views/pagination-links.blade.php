@if ($paginator->hasPages())
<ul class="">
    <!-- prev -->
    @if ($paginator->onFirstPage())
    <li class="">Prev</li>
    @else
    <li class="" wire:click="previousPage">Prev</li>
    @endif
    <!-- prev end -->

    <!-- numbers -->
    @foreach ($elements as $element)
    <div class="flex">
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li class="" wire:click="gotoPage({{$page}})">{{$page}}</li>
        @else
        <li class="" wire:click="gotoPage({{$page}})">{{$page}}</li>
        @endif
        @endforeach
        @endif
    </div>
    @endforeach
    <!-- end numbers -->


    <!-- next  -->
    @if ($paginator->hasMorePages())
    <li class="" wire:click="nextPage">Next</li>
    @else
    <li class="">Next</li>
    @endif
    <!-- next end -->
</ul>
@endif
