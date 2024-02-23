<div @if ($cart && $cart->quantity_amount != 0) style="display: flex !important"
@else

    style="display: none !important" @endif id="cartCount" class="header__count">
	<span>
		@if ($cart->quantity_amount != 0)
			{{ $cart->quantity_amount }}
		@endif
	</span>
</div>
