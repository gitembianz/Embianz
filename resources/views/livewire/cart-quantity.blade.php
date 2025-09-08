    <div id="cartCount" class="header__count"
        style="opacity: {{ $quantity != 0 ? '1' : '0' }};">
        <span>
            @if ($quantity)
                {{ $quantity }}
            @endif
        </span>
    </div>
