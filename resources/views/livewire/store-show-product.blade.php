<div>
    <section class="product container">
        <!-------------------- Slider Product ------------------>
        <div class="product-slider">
            <div class="product-slider__wrapper">
                @foreach ($product->media as $media)
                    @if ($media->location->location == "main" || $media->location->location == "details")
                        <div class="product-slider__slide">
                            @if ($media->external)
                                <img src="{{ $media->path }}" alt="{{ $media->path }}">
                            @else
                                <img src="/{{ $media->path }}{{ $media->name }}" alt="{{ $media->path }}">
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="product-slider__button prev">
                <svg>
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </div>
            <div class="product-slider__button next">
                <svg>
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </div>
        <!------------------ End Slider Product ---------------->
        <!------------------------------------------------------>
        <!----------------------- Product ---------------------->
        <div class="product__container">
            <!------------------------------------------------------>
            <!------------------ Product (Details) ----------------->
            <div class="product__text">
                <div>
                    <span class="product__subtitle">{{ $product->product_categories->first()->category->name }}</span>
                    <h1 class="product__title">{{ $product->name }}</h1>
                </div>
                <button class="product__wishlist @if ($product->wishlists->where("session_id", $session_id)->isNotEmpty()) active @endif"
                    wire:click="toggleWishlist({{ $product->id }})">
                    <svg viewBox="0 0 512 512" width="20" title="heart">
                        <path
                            d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z" />
                    </svg>
                </button>
            </div>
            <div class="product__price">
                <span>Price</span>
                <span>
                    @if ($product->product_prices->first() !== null)
                        {{ $product->product_prices->first()->value }}
                        {{ $product->product_prices->first()->pricelist->currency->name }}
                    @else
                        Pret Indisponibil
                    @endif
                </span>
            </div>
            @if ($product->product_prices->first() !== null)
                <div class="quantity">
                    <span>Quantity</span>
                    <input class="quantity__input" type="number" name="count" id="count" wire:model="quantity">
                    <div class="quantity__buttons">
                        <button class="quantity__arrow" wire:click="incrementCounter">
                            <svg>
                                <polyline points="18 15 12 9 6 15"></polyline>
                            </svg>
                        </button>
                        <button class="quantity__arrow" wire:click="decrementCounter">
                            <svg>
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            @if ($maxlimit)
                <span>Cantitatea maxima a produsului este {{ $limit }}</span>
            @endif
            @php
                $price = $product->product_prices->first();
            @endphp
            @if ($price && $product->quantity != 0)
                <button wire:click="addToCart({{ $product->id }})" class="product__button">Add to cart</button>
            @endif
            <!---------------- End Product (Details) --------------->
            <!------------------------------------------------------>
            <!-------------------- Tab (Details) ------------------->
            <div class="tab">
                <div class="tab__top">
                    <button class="tab__button @if ($activeTab === 0) active @endif"
                        wire:click="switchTab(0)">Description</button>
                    <button class="tab__button @if ($activeTab === 1) active @endif"
                        wire:click="switchTab(1)">Details</button>
                </div>
                <div class="tab__content @if ($activeTab === 0) active @endif">
                    <p class="tab__info">{{ $product->long_description }}</p>
                </div>
                <div class="tab__content @if ($activeTab === 1) active @endif">
                    <table class="tab__table">
                        <thead>
                            <tr>
                                <th>Specification </th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($product->product_specs->first() !== null)
                                @foreach ($product->product_specs as $spec)
                                    <tr>
                                        <td>{{ $spec->spec->name }}</td>
                                        <td>{{ $spec->value }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2">No Specs for this product</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!------------------ End Tab (Details) ----------------->
            <!------------------------------------------------------>
        </div>
        <!--------------------- End Product -------------------->
        <!------------------------------------------------------>
    </section>
</div>
