<div>
    <x-store-alert />
    <section>
        <!------------------------------------------------------>
        <!------------------- Basket Section ------------------->
        <div class="section__header container">
            <h1 class="section__title">Cosul de cumparaturi!</h1>
            <p class="section__text">
                Vezi produsele mai jos
            </p>
        </div>
        <!----------------- End Basket Section ----------------->
        <!------------------------------------------------------>
    </section>
    <!------------------------------------------------------>
    <!------------------------------------------------------>
    <section>
        <div class="basket__container container">
            <!------------------------------------------------------>
            <!------------------- Basket Products ------------------>
            <div class="basket">
                @if ($cartItems->isEmpty())
                    <span class="basket__empty">Cosul de cumparaturi este gol</span>
                @else
                    @foreach ($cartItems as $cartItem)
                        <div class="basket__product">
                            <div class="basket__top">
                                @if ($cartItem->product->media->first())
                                    <img class="cart__list--img"
                                        src="/{{ $cartItem->product->media->first()->path }}{{ $cartItem->product->media->first()->name }}"
                                        alt="{{ $cartItem->product->media->first()->name }} {{ $cartItem->product->name }}">
                                @else
                                    <img class="cart__list--img" src="/images/store/default/default70.webp"
                                        alt="something wrong">
                                @endif
                                <div>
                                    <a href="{{ route('product', ['product' => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== '' ? $cartItem->product->seo_id : $cartItem->product->id]) }}"
                                        class="basket__title">{{ $cartItem->product->name }}</a>
                                    <span class="basket__price">
                                        <?php $currency = $cartItem->product->product_prices->first()->pricelist->currency->name; ?>
                                        @if ($currency !== null)
                                            {{ number_format($cartItem->price, 2, ',', '.') }}
                                            {{ $currency }}
                                        @else
                                            pret indisponibil
                                        @endif
                                    </span>
                                </div>
                                <button class="basket__delete" aria-label="Remove from cart button"
                                    wire:click="removeFromCart({{ $cartItem->product->id }})">
                                    <svg>
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                        </path>
                                    </svg>
                                </button>

                                @livewire('product-wishlist-button', ['productId' => $cartItem->product->id, 'class' => 'basket__action', 'is_in_wishlist' => $cartItem->product->wishlists->isNotEmpty()], key($cartItem->product->id))

                            </div>
                            <button class="basket__delete--hidden basket__delete--long" aria-label="Remove from cart"
                                wire:click="removeFromCart({{ $cartItem->product->id }})">
                                Șterge din coș
                                <svg>
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg>
                            </button>

                            <div class="quantity">
                                <span>Cantitatea</span>

                                <div class="quantity__buttons">
                                    <button class="quantity__arrow" aria-label="Decrease quantity"
                                        wire:click="decrement({{ $cartItem->product->id }})">
                                        <svg>
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="8" y1="12" x2="16" y2="12"></line>
                                        </svg>
                                    </button>
                                    <span class="quantity__input">
                                        {{ $cartItem->quantity }}
                                    </span>
                                    <button class="quantity__arrow" aria-label="Increase quantity"
                                        wire:click="increment({{ $cartItem->product->id }})">
                                        <svg>
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="16"></line>
                                            <line x1="8" y1="12" x2="16" y2="12"></line>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="basket__subtotal">
                                <span>Subtotal:</span>
                                <span>
                                    {{ number_format($cartItem->quantity * $cartItem->price, 2, ',', '.') }}
                                    {{ $currency }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <!----------------- End Basket Products ---------------->
            <!------------------------------------------------------>
            <!------------------- Basket Continue ------------------>
            @if (!$cartItems->isEmpty())
                <div class="details">
                    <div class="details__content">
                        <h2 class="details__title">Detalii comanda</h2>
                        <div class="details__text">
                            <h3>Produse:</h3>
                            <span> {{ number_format($cart->sum_amount, 2, ',', '.') }}
                                {{ $currency }}</span>
                        </div>
                        <div class="details__text">
                            <h3>Livrare:</h3>
                            <span>
                                @if (app('global_delivery_price') == 0)
                                    Gratuit
                                @else
                                    {{ number_format(app('global_delivery_price'), 2, ',', '.') }} {{ $currency }}
                                @endif
                            </span>
                        </div>
                        <div class="details__text">
                            <h3>Voucher:</h3>
                            <span class="voucher__choice">
                                -20%
                                <button wire:click="removevoucher" class="details__delete" aria-label="Remove voucher">
                                    <svg>
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                        </path>
                                    </svg>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="details__content">
                        <div class="details__text">
                            <h3>Total:</h3>
                            <span>
                                <?php
                                $total = $cart->sum_amount + app('global_delivery_price');
                                ?>
                                @if ($new_price)
                                    <span
                                        style="text-decoration: line-through; color:red; margin-right:1rem">{{ number_format($total, 2, ',', '.') }}{{ $currency }}</span>{{ number_format($cart->final_amount, 2, ',', '.') }}{{ $currency }}
                                @else
                                    {{ number_format($total, 2, ',', '.') }} {{ $currency }}
                                @endif
                            </span>
                        </div>
                        @if ($new_price)
                            <div>
                            </div>
                            <form wire:submit.prevent="checkvoucher" class="voucher">
                                <input type="text" value="{{ $voucher }}" readonly>
                            </form>
                            @if ($message)
                                <p class="voucher__error">{{ $message }}</p>
                            @endif
                        @else
                            <form class="voucher" wire:submit.prevent="checkvoucher">
                                <input type="text" wire:model="voucher" name="voucher"
                                    placeholder="Ai un voucher sau card cadou?">
                                <button type="submit">
                                    Aplica
                                </button>
                            </form>
                            @if ($message)
                                <p class="voucher__error">{{ $message }}</p>
                            @endif
                        @endif
                        <button class="details__button" wire:click="continue()"
                            aria-label="Continue form">Continua</button>
                    </div>

                </div>
            @endif
            <!----------------- End Basket Continue ---------------->
            <!------------------------------------------------------>
        </div>
    </section>
    <!---------------------------------------------------------->
    <!--------------------- support button --------------------->
    <x-help-button />
    <!------------------- End support button ------------------->
    <!---------------------------------------------------------->
    <script src="/script/store/checkout.js" async defer></script>
</div>
