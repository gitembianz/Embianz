<div @if ($count != 0) class="header__count"
@else
    style ="dispaly:none !important" @endif id="wishlistCount">
	@if ($count != 0)
		{{ $count }}
	@endif
</div>
