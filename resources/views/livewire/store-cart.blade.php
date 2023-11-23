<div>
    <x-store-alert />
    <section>
        <!------------------------------------------------------>
        <!------------------- Basket Section ------------------->
        <div class="section__header container">
            <h2 class="section__title">Cosul de cumparaturi!</h2>
            <p class="section__text">
                Vezi produsele alese mai jos
            </p>
        </div>
        <!----------------- End Basket Section ----------------->
        <!------------------------------------------------------>
    </section>
    <section>
        <div class="basket__container container">
            <!------------------------------------------------------>
            <!------------------- Basket Products ------------------>
            <div class="basket">
                @if ($cartItems->isEmpty())
                    <span class="basket__empty">No products</span>
                @else
                    @foreach ($cartItems as $cartItem)
                        <div class="basket__product">
                            <div class="basket__top">
                                @if (count($cartItem->product->media) > 0)
                                    @foreach ($cartItem->product->media as $media)
                                        @if ($media->location->location == "main")
                                            @if ($media->external)
                                                <img src="{{ $media->path }}" alt="{{ $media->path }}">
                                            @else
                                                <img src="/{{ $media->path }}{{ $media->name }}"
                                                    alt="{{ $media->path }}">
                                            @endif
                                            <?php break; ?>
                                        @endif
                                    @endforeach
                                @else
                                    <img src="/images/store/default/default.svg" alt="something wrong">
                                @endif
                                <div>
                                    <span class="basket__price">
                                        <?php $currency = $cartItem->product->product_prices->first()->pricelist->currency->name; ?>
                                        @if ($currency !== null)
                                            {{ $cartItem->price }}
                                            {{ $currency }}
                                        @else
                                            pret indisponibil
                                        @endif
                                    </span>
                                    <h2 class="basket__title">{{ $cartItem->product->name }}</h2>
                                </div>
                                <button class="basket__delete"
                                    wire:click="removeFromCart({{ $cartItem->product->id }})">
                                    <svg>
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <button class="basket__delete--hidden"
                                wire:click="removeFromCart({{ $cartItem->product->id }})">
                                Delete from Cart
                            </button>
                            <div class="quantity">
                                <span>Quantity</span>
                                <input class="quantity__input" type="number" name="count" readonly
                                    value="{{ $cartItem->quantity }}">
                                <div class="quantity__buttons">
                                    <button class="quantity__arrow"
                                        wire:click="increment({{ $cartItem->product->id }})">
                                        <svg>
                                            <polyline points="18 15 12 9 6 15"></polyline>
                                        </svg>
                                    </button>
                                    <button class="quantity__arrow"
                                        wire:click="decrement({{ $cartItem->product->id }})">
                                        <svg>
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="basket__subtotal">
                                <span>Subtotal:</span>
                                <span>
                                    {{ $cartItem->quantity }} * {{ $cartItem->price }} =
                                    {{ $cartItem->quantity * $cartItem->price }}
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
                    <h2 class="details__title">Detalii comanda</h2>
                    <div class="details__text">
                        <h4>Produse:</h4>
                        <span> {{ $cart->sum_amount }}
                            {{ $currency }}</span>
                    </div>
                    <div class="details__text">
                        <h4>Livrare:</h4>
                        <span>
                            @if ($delivery == 0)
                                Gratuit
                            @else
                                {{ $delivery }} {{ $currency }}
                            @endif
                        </span>
                    </div>
                    <div class="details__text">
                        <h4>Total:</h4>
                        <span>
                            <?php
                            $total = $cart->sum_amount + $delivery;
                            ?>
                            @if ($new_price)
                                <span
                                    style="text-decoration: line-through; color:red; margin-right:1rem">{{ $total }}{{ $currency }}</span>{{ $cart->final_amount }}{{ $currency }}
                            @else
                                {{ $total }} {{ $currency }}
                            @endif
                        </span>
                    </div>
                    @if ($new_price)
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
                            @if ($message)
                                <p style="color: red; position :absolute; top:40px">{{ $message }}</p>
                            @endif
                        </form>
                    @endif
                    <a class="details__button" wire:click="continue()">Continua</a>
                </div>
            @endif
            <!----------------- End Basket Continue ---------------->
            <!------------------------------------------------------>
        </div>
    </section>
</div>
