<div @if ($cart && $cart->quantity_amount != 0) class="header__count"
@else
    style ="dispaly:none !important" @endif
    id="cartCount">
    @if ($cart->quantity_amount != 0)
        {{ $cart->quantity_amount }}
    @endif
</div>
