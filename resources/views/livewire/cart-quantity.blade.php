<div @if ($cart && $cart->quantity_amount != 0) class="header__count"
@else
    style="display:none !important" @endif
    id="cartCount">
    <span>
        @if ($cart->quantity_amount != 0)
            {{ $cart->quantity_amount }}
        @endif
    </span>
</div>
