<div class="leftbar @if ($showcart) active @endif @if ($cartmodified) problem @endif @if ($aplicabble_voucher) mod @endif"
    id="basketList">
    <button class="leftbar__hidden--close" wire:click="$set('showcart', false)" id="basketHidden"></button>
    <div class="leftbar__content" id="basketContent">
        <div wire:loading.delay wire:target="increment, decrement, remove" class="wire-loading-container">
            <div class="spinner"></div>
        </div>

        <div class="leftbar__top">
            <span id="price_change">
                @if (app()->has('label_cart_page_title'))
                    {!! app('label_cart_page_title') !!}
                @endif
            </span>
            <button class="leftbar__close" id="basketClose" wire:click="$set('showcart', false)">
                <svg>
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        @php
            if (app()->has('global_numberformat_element')) {
                if (app('global_numberformat_element') === '.') {
                    $mill = '.';
                    $decimal = ',';
                } else {
                    $mill = ',';
                    $decimal = '.';
                }
            } else {
                $mill = '.';
                $decimal = ',';
            }
        @endphp

        @if (!isset($cart) || $cart->quantity_amount == 0)
            <span class="leftbar__empty">
                @if (app()->has('label_cart_empty'))
                    {!! app('label_cart_empty') !!}
                @endif
            </span>
        @else
            <ul class="leftbar__list">
                <?php
                $isdisabled = false;
                $disables = [];
                $nonquantity = [];
                ?>
                @foreach ($cart->cartItems as $index => $cartItem)
                    <?php
                    $disabled[$index] = false;
                    $nonquantity[$index] = false;

                    if ($cartItem->product->active != true || $cartItem->product->start_date > now(config('app.timezone'))->format('Y-m-d') || ($cartItem->product->end_date < now(config('app.timezone'))->format('Y-m-d') || ($cartItem->product->quantity < 0 && !$cartItem->product->preorder))) {
                        $disabled[$index] = true;
                        $isdisabled = true;
                    }
                    if (!optional($cartItem->product->product_prices->first())->value) {
                        $disabled[$index] = true;
                        $isdisabled = true;
                    }

                    if ($cartItem->product->quantity >= 0 && $cartItem->product->quantity < $cartItem->quantity && !$cartItem->product->preorder) {
                        $nonquantity[$index] = true;
                        $isdisabled = true;
                    }

                    $discount = false;

                    if (optional($cartItem->product->product_prices->first())->value) {
                        $discount = $cartItem->product->product_prices->first()->discount != 0 ? true : false;
                    }
                    ?>


                    <li class="leftbar__item">
                        @if ($nonquantity[$index])
                            <div class="basket__split">
                                <div class="leftbar__link">
                                    @if ($cartItem->product->media->where('type', 'min')->first())
                                        <img loading="eager" class="cart__list--img"
                                            title="{{ $cartItem->product->name }}"
                                            src="/{{ $cartItem->product->media->where('type', 'min')->first()->path }}{{ $cartItem->product->media->where('type', 'min')->first()->name }}"
                                            alt="{{ $cartItem->product->media->where('type', 'min')->first()->name }}{{ $cartItem->product->name }}">
                                    @else
                                        <img title="Default image" loading="eager" class="cart__list--img"
                                            src="/images/store/default/default70.webp" alt="something wrong">
                                    @endif
                                    <div class="leftbar__link--text">
                                        <h4 class="leftbar__link--title">{{ $cartItem->product->name }}</h4>
                                        <span class="item__product--error">
                                            @if (app()->has('label_product_quantity_error'))
                                                {!! app('label_product_quantity_error') !!}
                                            @endif
                                            {{ $cartItem->product->quantity }}
                                        </span>
                                    </div>
                                    <button class="leftbar__delete" style="border: none" type="button"
                                        wire:click="removeFromCart({{ $cartItem->product->id }})">
                                        <svg>
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path
                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="basket__item" style="border-top:1px solid #333333">
                                    <div class="quantity" style="border-bottom: 0 !important">
                                        <span>
                                            @if (app()->has('label_product_quantity_tag'))
                                                {!! app('label_product_quantity_tag') !!}
                                            @endif
                                        </span>
                                        <div class="quantity__buttons">
                                            <button
                                                class="quantity__arrow @if ($cartItem->quantity == 1) disabled @endif"
                                                style="width: 48px; height: 48px" aria-label="Decrease quantity"
                                                wire:click="decrement({{ $cartItem->id }})">
                                                <svg>
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="8" y1="12" x2="16" y2="12">
                                                    </line>
                                                </svg>
                                            </button>
                                            <span class="quantity__input product__quantity">
                                                {{ $cartItem->quantity }}
                                            </span>
                                            <button
                                                class="quantity__arrow @if ($cartItem->quantity >= $cartItem->product->quantity && !$cartItem->product->preorder) disabled @endif"
                                                style="width: 48px; height: 48px" aria-label="Increase quantity"
                                                wire:click="increment({{ $cartItem->id }})">
                                                <svg>
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="16">
                                                    </line>
                                                    <line x1="8" y1="12" x2="16" y2="12">
                                                    </line>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="basket__subtotal">
                                        <span>
                                            @if (app()->has('label_cart_page_subtotal_tag'))
                                                {!! app('label_cart_page_subtotal_tag') !!}
                                            @endif
                                        </span>
                                        <span>
                                            {{ number_format($cartItem->quantity * $cartItem->price, 2, $decimal, $mill) }}
                                            @if (app()->has('global_currency_primary_symbol'))
                                                {!! app('global_currency_primary_symbol') !!}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="basket__split">
                                <div class="basket__item__mini">
                                    <a class="leftbar__link"
                                        href="{{ route('product', ['product' => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== '' ? $cartItem->product->seo_id : $cartItem->product->id]) }}">
                                        @if ($cartItem->product->media->where('type', 'min')->first())
                                            <img loading="eager" class="cart__list--img"
                                                title="{{ $cartItem->product->name }}"
                                                src="/{{ $cartItem->product->media->where('type', 'min')->first()->path }}{{ $cartItem->product->media->where('type', 'min')->first()->name }}"
                                                alt="{{ $cartItem->product->media->where('type', 'min')->first()->name }}{{ $cartItem->product->name }}">
                                        @else
                                            <img title="Default image" loading="eager" class="cart__list--img"
                                                src="/images/store/default/default70.webp" alt="something wrong">
                                        @endif
                                        <div class="leftbar__link--text">
                                            <h4 class="leftbar__link--title">{{ $cartItem->product->name }}</h4>
                                            <span class="leftbar__link--price"
                                                @if ($discount) style="display: flex; gap:5px" @endif>
                                                @php
                                                    if (optional($cartItem->product->product_prices->first())->value) {
                                                        $price = number_format(
                                                            $cartItem->product->product_prices->first()->value,
                                                            2,
                                                            $decimal,
                                                            $mill,
                                                        );
                                                    } else {
                                                        $price = null;
                                                    }
                                                @endphp
                                                @if ($price && $price != null)
                                                    @if ($discount)
                                                        <span class="card-price discount">
                                                            {{ $price }}
                                                            @if (app()->has('global_currency_primary_symbol'))
                                                                {!! app('global_currency_primary_symbol') !!}
                                                            @endif
                                                        </span>
                                                        <span class="card-price oldprice">
                                                            {{ number_format($cartItem->product->product_prices->first()->value_no_discount, 2, $decimal, $mill) }}
                                                            @if (app()->has('global_currency_primary_symbol'))
                                                                {!! app('global_currency_primary_symbol') !!}
                                                            @endif
                                                        </span>
                                                    @else
                                                        <span>
                                                            {{ $price }}
                                                            @if (app()->has('global_currency_primary_symbol'))
                                                                {!! app('global_currency_primary_symbol') !!}
                                                            @endif
                                                        </span>
                                                    @endif
                                                @else
                                                    @if (app()->has('label_product_status_indisponible'))
                                                        {!! app('label_product_status_indisponible') !!}
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                    </a>
                                    <button class="leftbar__delete" style="border: none" type="button"
                                        wire:click="removeFromCart({{ $cartItem->product->id }})">
                                        <svg>
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path
                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="basket__item__mini" style="border-top:1px solid #333333">
                                    <div class="quantity">
                                        <span>
                                            @if (app()->has('label_product_quantity_tag'))
                                                {!! app('label_product_quantity_tag') !!}
                                            @endif
                                        </span>
                                        <div class="quantity__buttons">
                                            <button
                                                class="quantity__arrow @if ($cartItem->quantity == 1) disabled @endif"
                                                style="width: 48px; height: 48px" aria-label="Decrease quantity"
                                                wire:click="decrement({{ $cartItem->id }})">
                                                <svg>
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="8" y1="12" x2="16"
                                                        y2="12">
                                                    </line>
                                                </svg>
                                            </button>
                                            <span class="quantity__input product__quantity">
                                                {{ $cartItem->quantity }}
                                            </span>
                                            <button
                                                class="quantity__arrow @if ($cartItem->quantity >= $cartItem->product->quantity && !$cartItem->product->preorder) disabled @endif"
                                                style="width: 48px; height: 48px" aria-label="Increase quantity"
                                                wire:click="increment({{ $cartItem->id }})">
                                                <svg>
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12"
                                                        y2="16">
                                                    </line>
                                                    <line x1="8" y1="12" x2="16"
                                                        y2="12">
                                                    </line>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="basket__subtotal">
                                        <span>
                                            @if (app()->has('label_cart_page_subtotal_tag'))
                                                {!! app('label_cart_page_subtotal_tag') !!}
                                            @endif
                                        </span>
                                        <span>
                                            {{ number_format($cartItem->quantity * $cartItem->price, 2, $decimal, $mill) }}
                                            @if (app()->has('global_currency_primary_symbol'))
                                                {!! app('global_currency_primary_symbol') !!}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($disabled[$index])
                            <div class="item__product--disabled">
                                <span>
                                    @if (app()->has('label_product_status_indisponible'))
                                        {!! app('label_product_status_indisponible') !!}
                                    @endif
                                </span>
                                <button class="leftbar__delete" type="button"
                                    wire:click="removeFromCart({{ $cartItem->product->id }})">
                                    <svg>
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>

            <!-- GTM: Push view_minicart event when cart opens -->
             <script>
                // GLOBAL flag - nu se resetează
                window.viewMinicartSent = false;
                
                document.addEventListener('livewire:update', function() {
                    const basketList = document.getElementById('basketList');
                    
                    // Verify if mini-cart is open
                    if (basketList && basketList.classList.contains('active')) {
                        // Only send once per opening
                        if (window.viewMinicartSent) return;
                        window.viewMinicartSent = true;
                        
                        setTimeout(() => {
                            const cartItems = [];
                            let totalValue = 0;
                            
                            // Get all active products (exclude disabled ones)
                            const products = document.querySelectorAll('#basketList .leftbar__item:not(.item__product--disabled)');
                            
                            products.forEach((product, index) => {
                                const nameEl = product.querySelector('.leftbar__link--title');
                                const priceEl = product.querySelector('.leftbar__link--price');
                                const qtyEl = product.querySelector('.product__quantity');
                                const deleteBtn = product.querySelector('.leftbar__delete[wire\\:click]');
                                
                                if (nameEl && priceEl && qtyEl) {
                                    const productName = nameEl.innerText.trim();
                                    
                                    // Extract price (remove "RON" and other characters)
                                    let priceText = priceEl.innerText.replace(/[^\d.,]/g, '');
                                    const productPrice = parseFloat(priceText.replace(',', '.')) || 0;
                                    const quantity = parseInt(qtyEl.innerText) || 1;
                                    
                                    // Extract product ID from wire:click attribute
                                    let productId = `product_${index + 1}`; // Fallback
                                    if (deleteBtn) {
                                        const wireClick = deleteBtn.getAttribute('wire:click');
                                        const match = wireClick.match(/\((\d+)\)/);
                                        if (match) {
                                            productId = match[1];
                                        }
                                    }
                                    
                                    if (productName && productPrice > 0) {
                                        cartItems.push({
                                            item_id: productId,
                                            item_name: productName,
                                            price: productPrice,
                                            quantity: quantity
                                        });
                                        totalValue += productPrice * quantity;
                                    }
                                }
                            });
                            
                            // Push GTM event only if cart has items
                            if (cartItems.length > 0) {
                                window.dataLayer = window.dataLayer || [];
                                window.dataLayer.push({
                                    ecommerce: null
                                });
                                window.dataLayer.push({
                                    event: "view_cart",
                                    ecommerce: {
                                        currency: "RON",
                                        value: parseFloat(totalValue.toFixed(2)),
                                        items: cartItems
                                    }
                                });
                            }
                        }, 50);
                    } else {
                        // Reset flag when cart closes
                        window.viewMinicartSent = false;
                    }
                });
            </script>
            <div class="leftbar__total">
                <h5 class="leftbar__total--text">
                    @if (app()->has('label_cart_products_tag'))
                        {!! app('label_cart_products_tag') !!}
                    @endif
                    <span @if (app()->has('global_display_delivery_price_on_cart') && app('global_display_delivery_price_on_cart') != 'true') id="leftbarTotalPrice" @endif>
                        {{ number_format($cart->sum_amount, 2, $decimal, $mill) }}
                        @if (app()->has('global_currency_primary_symbol'))
                            {!! app('global_currency_primary_symbol') !!}
                        @endif
                    </span>
                </h5>
                @if ($cart->promotion_value > 0)
                    <h5 class="leftbar__total--text">
                        @if (app()->has('label_cart_promotion_tag'))
                            {!! app('label_cart_promotion_tag') !!}
                        @endif
                        <span style="color: red">
                            -{{ number_format($cart->promotion_value, 2, $decimal, $mill) }}@if (app()->has('global_currency_primary_symbol'))
                                {!! app('global_currency_primary_symbol') !!}
                            @endif
                        </span>
                    </h5>
                @endif

                @if (app()->has('global_display_delivery_price_on_cart') && app('global_display_delivery_price_on_cart') === 'true')
                    <h5 class="leftbar__total--text">
                        @if (app()->has('label_cart_delivery_tag'))
                            {!! app('label_cart_delivery_tag') !!}
                        @endif
                        <span>
                            @if ($cart->delivery_price == 0)
                                @if (app()->has('label_cart_delivery_free'))
                                    {!! app('label_cart_delivery_free') !!}
                                @endif
                            @else
                                {{ number_format($cart->delivery_price, 2, $decimal, $mill) }}@if (app()->has('global_currency_primary_symbol'))
                                    {!! app('global_currency_primary_symbol') !!}
                                @endif
                            @endif
                        </span>
                    </h5>
                @endif
                @if ($cart->voucher_id != null)
                    <h5 class="leftbar__total--text">
                        @if (app()->has('label_cart_voucher_tag'))
                            {!! app('label_cart_voucher_tag') !!}
                        @endif
                        <span class="voucher__choice">
                            -{{ number_format($cart->voucher_value, 2, $decimal, $mill) }} @if (app()->has('global_currency_primary_symbol'))
                                {!! app('global_currency_primary_symbol') !!}
                            @endif
                            <button wire:click="removevoucher" class="details__delete" aria-label="Remove voucher">
                                <svg>
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg>
                            </button>
                        </span>
                    </h5>
                @endif
                @if (app()->has('global_display_delivery_price_on_cart') && app('global_display_delivery_price_on_cart') === 'true')

                    <h5 class="leftbar__total--text">
                        @if (app()->has('label_cart_total_tag'))
                            {!! app('label_cart_total_tag') !!}
                        @endif
                        <span id="leftbarTotalPrice">
                            {{ number_format($cart->final_amount, 2, $decimal, $mill) }}
                            @if (app()->has('global_currency_primary_symbol'))
                                {!! app('global_currency_primary_symbol') !!}
                            @endif
                        </span>
                    </h5>
                @endif
                @if ($message)
                    <p class="voucher__error">{{ $message }}</p>
                @endif

                @if (app()->has('global_voucher_system_on') && app('global_voucher_system_on') === 'true')
                    @if ($cart->voucher_id == null)
                        <div class="voucher">
                            <input type="text" wire:model="voucher" maxlength="100" name="voucher"
                                placeholder="@if (app()->has('label_cart_voucher_placeholder')) {!! app('label_cart_voucher_placeholder') !!} @endif">
                            <button type="submit" wire:click="checkvoucher">
                                @if (app()->has('label_cart_voucher_apply'))
                                    {!! app('label_cart_voucher_apply') !!}
                                @endif
                            </button>
                        </div>
                    @endif
                @endif
                @if ($timer > 0)
                    <h5 class="leftbar__total--text" style="color:red">
                        @if (app()->has('label_cart_promotion_timer'))
                            {!! app('label_cart_promotion_timer') !!}
                        @endif
                        <span wire:ignore id="countdown_cart" style="color:red"></span>
                    </h5>
                @endif





                @if ($isdisabled)
                    <a class="leftbar__button leftbar__button--long item__button--disabled">
                        @if (app()->has('label_cart_order'))
                            {!! app('label_cart_order') !!}
                        @endif
                    </a>
                    <span class="item__text--disabled" id="headerContinue">
                        @if (app()->has('label_	cart_order_error'))
                            {!! app('label_	cart_order_error') !!}
                        @endif
                    </span>
                @else
                    @if ($cart->final_amount < 0 || $cart->sum_amount <= $cart->promotion_value || $cart->sum_amount <= $cart->voucher_value)
                        <a class="leftbar__button leftbar__button--long item__button--disabled">
                            @if (app()->has('label_cart_order'))
                                {!! app('label_cart_order') !!}
                            @endif
                        </a>
                        <span class="item__text--disabled" id="headerContinue">
                            @if (app()->has('label_cart_order_mesage'))
                                {!! app('label_cart_order_mesage') !!}
                            @endif
                        </span>
                    @else
                        <a class="leftbar__button leftbar__button--long" id="headerContinue"
                            wire:click.prevent="continue"
                                wire:loading.attr="disabled" wire:loading.class="item__button--disabled"
                                wire:target="continue">
                            @if (app()->has('label_cart_order'))
                                {!! app('label_cart_order') !!}
                            @endif
                        </a>
                    @endif
                @endif
            </div>
            <input type="hidden" value={{ $timer }} id="remaningtime">
            @if ($timer > 0)
                <script>
                    let ticker;
                    const storageKey = 'timer_end_time';

                    function clearExistingTimer() {
                        if (ticker) {
                            clearInterval(ticker);
                            ticker = null;
                        }
                    }

                    function setEndTime(serverTimer) {
                        const now = Math.floor(Date.now() / 1000);
                        localStorage.setItem(storageKey, now + serverTimer);
                    }

                    function startTimer() {
                        clearExistingTimer();

                        const endTime = parseInt(localStorage.getItem(storageKey));

                        ticker = setInterval(() => {
                            const now = Math.floor(Date.now() / 1000);
                            const timeLeft = Math.max(endTime - now, 0);

                            if (timeLeft > 0) {
                                displayTime(timeLeft);
                            } else {
                                clearExistingTimer();
                                localStorage.removeItem(storageKey);

                                const countdownElement = document.getElementById("countdown_cart");
                                if (countdownElement) {
                                    countdownElement.innerHTML = "0s";
                                }

                                @this.call('checkpromotions');
                                Livewire.emit('countdownExpired');
                            }
                        }, 1000);
                    }

                    function displayTime(timeLeft) {
                        const countdownElement = document.getElementById("countdown_cart");
                        if (!countdownElement) return;

                        const days = Math.floor(timeLeft / 86400);
                        const hours = Math.floor((timeLeft % 86400) / 3600);
                        const mins = Math.floor((timeLeft % 3600) / 60);
                        const secs = timeLeft % 60;

                        let pretty = "";
                        if (days > 0) pretty += days + "d ";
                        if (hours > 0 || days > 0) pretty += hours + "h ";
                        if (mins > 0 || hours > 0 || days > 0) pretty += mins + "m ";
                        pretty += secs + "s";

                        countdownElement.innerHTML = pretty;
                    }

                    function initTimer(serverTimer) {
                        setEndTime(serverTimer);
                        startTimer();
                    }

                    document.addEventListener('livewire:update', () => {
                        const remainingTimeElement = document.getElementById("remaningtime");

                        if (remainingTimeElement) {
                            const newTimer = parseInt(remainingTimeElement.value, 10);
                            setEndTime(newTimer);
                            startTimer();
                        }
                    });
                </script>
            @endif

            <script>
                document.getElementById('headerContinue').addEventListener('click', function() {
                    let productsList = [];
                    let products = document.querySelectorAll('.leftbar__item');
                    let total = parseFloat(document.getElementById('leftbarTotalPrice').innerText.replace('RON', '')
                        .trim());

                    products.forEach(function(product) {
                        let productName = product.querySelector('.leftbar__link--title')
                            .innerText; // Extrage numele produsului
                        let productPrice = parseFloat(product.querySelector('.leftbar__link--price').innerText
                            .replace('RON', '')
                            .trim());

                        let productQuantity = parseInt(product.querySelector('.product__quantity').innerText);


                        productsList.push(productName + ' --- ' + productQuantity + 'buc --- ' + productPrice);
                    });

                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({
                        'event': 'addToCart',
                        'products': productsList,
                        'total': total,
                        'event': 'continueToCheckout'
                    });
                });

                //<---------- GTM Remove from Cart Tracking ---------->

let removeFromCartListenerAttached = false;

function initRemoveFromCartTracking() {
  // Verifică daca listener-ul e deja atașat
  if (removeFromCartListenerAttached) return;
  removeFromCartListenerAttached = true;

  // Event delegation - funcționează pe elemente dinamice din Livewire
  document.addEventListener("click", function handleRemoveFromCart(e) {
    const deleteBtn = e.target.closest("button.leftbar__delete");
    
    if (!deleteBtn) return;

    // Check daca are wire:click="removeFromCart(..."
    const wireClick = deleteBtn.getAttribute("wire:click");
    if (!wireClick || !wireClick.includes("removeFromCart")) return;

    // Găsește container-ul produsului (`.leftbar__item`)
    const productContainer = deleteBtn.closest(".leftbar__item");
    if (!productContainer) return;

    // Extrage detalii din DOM
    const nameEl = productContainer.querySelector(".leftbar__link--title");
    const priceEl = productContainer.querySelector(".leftbar__link--price");
    const qtyEl = productContainer.querySelector(".product__quantity");

    if (!nameEl || !priceEl) return;

    const productName = nameEl.innerText.trim();
    const priceText = priceEl.innerText.replace(/RON|,/g, "").trim();
    const productPrice = parseFloat(priceText.replace(",", ".")) || 0;
    const quantity = qtyEl ? parseInt(qtyEl.innerText) || 1 : 1;

    // Extrage product ID din wire:click
    const match = wireClick.match(/removeFromCart\((\d+)\)/);
    const productId = match ? match[1] : "unknown";

    //console.log("✅ Remove from Cart:", productName, "Price:", productPrice, "Qty:", quantity);

    // Push GTM event - DOAR O SINGURĂ DATĂ
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
      event: "remove_from_cart",
      ecommerce: {
        items: [{
          item_id: String(productId),
          item_name: productName,
          price: productPrice,
          quantity: quantity,
          currency: "RON"
        }]
      }
    });
  }, false); // Capture phase false = bubble phase
}

document.getElementById('headerContinue').addEventListener('click', function() {
  const cartItems = [];
  const products = document.querySelectorAll('#basketList .leftbar__item:not(.item__product--disabled)');
  
  // Extrage items
  products.forEach(function(product) {
    const nameEl = product.querySelector('.leftbar__link--title');
    const priceEl = product.querySelector('.leftbar__link--price span');
    const qtyEl = product.querySelector('.product__quantity');
    const deleteBtn = product.querySelector('.leftbar__delete[wire\\:click]');
    
    if (nameEl && priceEl && qtyEl) {
      const productName = nameEl.innerText.trim();
      const priceText = priceEl.innerText.trim().replace(/lei|,/g, '');
      const productPrice = parseFloat(priceText.replace('.', '').replace(',', '.')) || 0;
      const quantity = parseInt(qtyEl.innerText) || 1;
      
      let productId = 'unknown';
      if (deleteBtn) {
        const wireClick = deleteBtn.getAttribute('wire:click');
        const match = wireClick.match(/removeFromCart\((\d+)\)/);
        if (match) productId = match[1];
      }
      
      cartItems.push({
        item_id: String(productId),
        item_name: productName,
        price: productPrice,
        quantity: quantity
      });
    }
  });


 const totalPriceEl = document.getElementById('leftbarTotalPrice');
let totalValue = 0;
if (totalPriceEl) {
  const totalText = totalPriceEl.innerText.trim();
  totalValue = totalText.replace(/lei/, '').trim(); 
}
  // Currency = "lei" din primul span cu lei
  let currency = 'RON';
  const currencyEl = document.querySelector('.leftbar__total--text span');
  if (currencyEl && currencyEl.innerText.includes('lei')) {
    currency = 'RON'; // lei = RON în context RO
  }

  // Coupon = din reducere roșie (Reducere: -184.89 lei)
  let coupon = '';
const discountH5 = document.querySelector('.leftbar__total--text:has(span[style*="red"])');
if (discountH5) {
  // Extrage valoarea din span roșu: "-184.89 lei" → "-184.89"
  const discountSpan = discountH5.querySelector('span[style*="red"]');
  if (discountSpan) {
    const discountValue = discountSpan.innerText.trim(); // "-184.89 lei"
    coupon = discountValue.replace(/lei/, '').trim(); // "-184.89"
  }
}

// Dacă nu e span roșu, încearcă din textul h5
if (!coupon) {
  const allH5 = document.querySelectorAll('.leftbar__total--text');
  allH5.forEach(function(h5) {
    if (h5.innerText.includes('Reducere') || h5.innerText.includes('-')) {
      const match = h5.innerText.match(/[-–]\s*[\d,.]+/);
      if (match) {
        coupon = match[0].trim();
      }
    }
  });
}

  // Trimite event complet
  if (cartItems.length > 0) {
    
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ ecommerce: null });
    window.dataLayer.push({
      event: 'begin_checkout',
      ecommerce: {
        currency: currency,
        value: totalValue,
        coupon: coupon || undefined,
        items: cartItems
      }
    });
  }
});


// Inițializează când DOM e ready
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initRemoveFromCartTracking);
} else {
  initRemoveFromCartTracking();
}

//<------- End GTM Remove from Cart Tracking ------>

            </script>
        @endif
    </div>
    <div class="leftbar__modal">
        <div class="leftbar__modal--text">
            @if (app()->has('label_cart_voucher_question'))
                {!! app('label_cart_voucher_question') !!}
                @endif "{{ $voucher }}"?
        </div>
        <div class="leftbar__modal--bundle">
            <button class="leftbar__modal--btn" wire:click="confirm_aplicabble">
                @if (app()->has('label_cart_voucher_question_confirm'))
                    {!! app('label_cart_voucher_question_confirm') !!}
                @endif
            </button>
            <button class="leftbar__modal--btn" wire:click="cancel_aplicabble">
                @if (app()->has('label_cart_voucher_question_cancel'))
                    {!! app('label_cart_voucher_question_cancel') !!}
                @endif
            </button>
        </div>
    </div>
    <div class="leftbar__problem">
        <div class="leftbar__modal--text">
            @if (app()->has('label_cart_changed_mesage'))
                {!! app('label_cart_changed_mesage') !!}
            @endif
        </div>
        <div class="leftbar__modal--bundle">
            <button class="leftbar__modal--btn" wire:click="seen">
                @if (app()->has('label_cart_changed_confirm'))
                    {!! app('label_cart_changed_confirm') !!}
                @endif
            </button>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:load', function() {
            let observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        @this.call('pricechanged');
                        @this.call('checkpromotions');

                    }
                });
            });

            observer.observe(document.getElementById('price_change'));
        });
    </script>
</div>
