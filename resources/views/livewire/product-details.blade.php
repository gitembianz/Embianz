<div class="product__container">
    <x-confettialert />

    {{-- wishlist new system --}}
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("wishlistButton", e => ({
                productId: e.productId,
                isInWishlist: e.initial,
                loading: !1,
                toggle() {
                    this.loading || (this.loading = !0, this.isInWishlist = !this.isInWishlist, Livewire
                        .emit(this.isInWishlist ? "wishlist:add" : "wishlist:remove", this
                            .productId), this.loading = !1)
                }
            }))
        });
    </script>
    @livewire('wishlist-actions')

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
    <?php if ($product->product_prices->count() != 0) {
        $price = number_format($product->product_prices->first()->value, 2, $decimal, $mill);
        $discount = $product->product_prices->first()->discount != 0 ? true : false;
    } else {
        $price = null;
        $discount = false;
    }
    ?>
    <div class="product__text">
        <div>
            <h1 class="product__title">{{ $product->name }}</h1>
            <h2 class="product__subtitle">{{ $product->short_description }}</h2>
            @if ($discount)
                <span class="product__discount">-{{ $product->product_prices->first()->discount }}%</span>
            @endif
            @if (app()->has('global_review_system') && app('global_review_system') === 'true' && $product->reviews->count() > 0)
                @php
                    $rating = $product->reviews_avg_score * 20;
                @endphp
                <div class="ratingscore" wire:ignore>
                    <div class="rating" style="--rating: {{ $rating }}%;"></div>
                    @if (app()->has('global_display_rating_value') && app('global_display_rating_value') === 'true')
                        ({{ number_format($product->reviews_avg_score, 2) }})
                    @endif
                </div>
            @endif
        </div>

        <div x-data="wishlistButton({
            productId: {{ $product->id }},
            initial: @js($this->is_in_wishlist)
        })" x-init="window.addEventListener('wishlist-updated', (e) => {
            if (e.detail.productId === productId) {
                isInWishlist = e.detail.inWishlist
            }
        })" class="product__action">
            <button class="favorite__btn" :class="{ 'active': isInWishlist }" @click.prevent="toggle"
                aria-label="Add to wishlist">
                <svg viewBox="0 0 512 512" width="20">
                    <path
                        d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z" />
                </svg>
            </button>
        </div>
    </div>
    @if ($product->type == 'variant')
        @foreach ($variants as $variantId => $variantGroup)
            @if (count($variantGroup) > 1)
                <span class="product__price--title"
                    style="margin-right: auto; font-size: 14px; font-weight:500">{{ $product->beeingvariants->where('variant_id', $variantId)->first()->reference->name }}</span>
                <div class="product__price" style="height: auto;">
                    <div class="variant__slider mini-slider">
                        <div class="variant__wrapper mini-wrapper">
                            <a class="variant__btn active"
                                href="{{ route('product', ['product' => $product->seo_id !== null && $product->seo_id !== '' ? $product->seo_id : $product->id]) }}">
                                @if ($product->beeingvariants->where('variant_id', $variantId)->first()->displayed == 'image')
                                    @if ($product->media->first() != null)
                                        <img src="/{{ $product->media->first()->path }}{{ $product->media->first()->name }}"
                                            alt="{{ $product->media->first()->name }}">
                                    @else
                                        <img src="/images/store/default/default70.webp" alt="something wrong">
                                    @endif
                                @elseif ($product->beeingvariants->where('variant_id', $variantId)->first()->displayed == 'text')
                                    <span>{{ $product->beeingvariants->where('variant_id', $variantId)->first()->value }}</span>
                                @else
                                    @if ($product->media->first() != null)
                                        <img src="/{{ $product->media->first()->path }}{{ $product->media->first()->name }}"
                                            alt="{{ $product->media->first()->name }}">
                                    @else
                                        <img src="/images/store/default/default70.webp" alt="something wrong">
                                    @endif
                                    <span>{{ $product->beeingvariants->where('variant_id', $variantId)->first()->value }}</span>
                                @endif
                            </a>
                            @foreach ($variantGroup as $variant)
                                @php
                                    if ($variant->id == $product->id) {
                                        continue;
                                    }
                                @endphp
                                <a class="variant__btn"
                                    href="{{ route('product', ['product' => $variant->seo_id !== null && $variant->seo_id !== '' ? $variant->seo_id : $variant->id]) }}">
                                    @if ($variant->beeingvariants->where('variant_id', $variantId)->first()->displayed == 'image')
                                        @if ($variant->media->first() != null)
                                            <img src="/{{ $variant->media->first()->path }}{{ $variant->media->first()->name }}"
                                                alt="{{ $variant->media->first()->name }}">
                                        @else
                                            <img src="/images/store/default/default70.webp" alt="something wrong">
                                        @endif
                                    @elseif ($variant->beeingvariants->where('variant_id', $variantId)->first()->displayed == 'text')
                                        <span>{{ $variant->beeingvariants->where('variant_id', $variantId)->first()->value }}</span>
                                    @else
                                        @if ($variant->media->first() != null)
                                            <img src="/{{ $variant->media->first()->path }}{{ $variant->media->first()->name }}"
                                                alt="{{ $variant->media->first()->name }}">
                                        @else
                                            <img src="/images/store/default/default70.webp" alt="something wrong">
                                            <span>{{ $variant->beeingvariants->where('variant_id', $variantId)->first()->value }}</span>
                                        @endif
                                        <span>{{ $variant->beeingvariants->where('variant_id', $variantId)->first()->value }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
    <div class="product__price">
        <span class="product__price--title">
            @if (app()->has('label_pdp_price_tag'))
                {!! app('label_pdp_price_tag') !!}
            @endif
        </span>
        @if ($discount && $price)
            @if ($price)
                <div class="product__price--discount">
                    <span
                        class="product__price--oldprice">{{ number_format($product->product_prices->first()->value_no_discount, 2, $decimal, $mill) }}
                        @if (app()->has('global_currency_primary_symbol'))
                            {!! app('global_currency_primary_symbol') !!}
                        @endif
                    </span>
                    <span class="product__price--newprice">{{ $price }}
                        @if (app()->has('global_currency_primary_symbol'))
                            {!! app('global_currency_primary_symbol') !!}
                        @endif
                    </span>
                </div>
            @endif
        @else
            @if ($price)
                {{ $price }}
                @if (app()->has('global_currency_primary_symbol'))
                    {!! app('global_currency_primary_symbol') !!}
                @endif
            @else
                @if (app()->has('label_product_status_indisponible'))
                    {!! app('label_product_status_indisponible') !!}
                @endif
            @endif
        @endif
    </div>
    @if ($price)
        <span class="product__tva">
            @if (app()->has('label_pdp_vat'))
                {!! app('label_pdp_vat') !!}
            @endif
            @if (app()->has('global_display_vat_value') && app('global_display_vat_value') === 'true')
                {{ number_format($product->product_prices->first()->vat, 2, $decimal, $mill) }}%
            @endif
        </span>
        @if ($product->quantity > 0 || $product->preorder)
            <div class="quantity">
                <span>
                    @if (app()->has('label_product_quantity_tag'))
                        {!! app('label_product_quantity_tag') !!}
                    @endif
                </span>
                <div class="quantity__buttons">
                    <button class="quantity__arrow" wire:click="decrementCounter" aria-label="Decrement quantity">
                        <svg>
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                    </button>
                    <span class="quantity__input" name="count" id="count">
                        {{ $quantity }}
                    </span>
                    <button class="quantity__arrow" wire:click="incrementCounter" aria-label="Increment quantity">
                        <svg>
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                    </button>
                </div>

            </div>
        @endif
    @endif
    @if ($maxlimit)
        <span>
            @if (app()->has('label_product_quantity_error'))
                {!! app('label_product_quantity_error') !!}
            @endif {{ $limit }}
        </span>
    @endif

    @if ($price)
        @if ($product->quantity > 0 || $product->preorder)
            <button class="card__button" style="width: 100%;height: 40px;" onclick="flyToCart(this)"
                aria-label="Add to cart button" wire:click="addToCart({{ $product->id }})" wire:ignore="$refresh">
                <div class="card__button--cart">
                    <svg>
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </div>
                <div class="card__button--gift">
                    <svg>
                        <polyline points="20 12 20 22 4 22 4 12"></polyline>
                        <rect x="2" y="7" width="20" height="5"></rect>
                        <line x1="12" y1="22" x2="12" y2="7"></line>
                        <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path>
                        <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path>
                    </svg>
                </div>
                <span class="card__button--text">
                    @if (app()->has('label_add_to_cart_button'))
                        {!! app('label_add_to_cart_button') !!}
                    @endif
                </span>
            </button>
        @else
            <button class="card-button-disabled" aria-label="Disabled Add to cart button">
                @if (app()->has('label_add_to_cart_button_indisponibil'))
                    {!! app('label_add_to_cart_button_indisponibil') !!}
                @endif
            </button>
        @endif
    @else
        <button class="card-button-disabled" aria-label="Disabled Add to cart button">
            @if (app()->has('label_add_to_cart_button_indisponibil'))
                {!! app('label_add_to_cart_button_indisponibil') !!}
            @endif
        </button>
    @endif
    @if (app()->has('global_display_phone_on_pdp') && app('global_display_phone_on_pdp') === 'true')

        <div class="product__phone">
            <div class="product__phone__info">
                <span>
                    <a href="tel:+40757527656"><svg>
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                            </path>
                        </svg>
                    </a>
                    <span>
                        @if (app()->has('label_pdp_phone_title'))
                            {!! app('label_pdp_phone_title') !!}
                        @endif
                    </span>
                </span>
                @if (app()->has('label_pdp_phone_link'))
                    {!! app('label_pdp_phone_link') !!}
                @endif
            </div>
            <span class="info">
                @if (app()->has('label_pdp_phone_program'))
                    {!! app('label_pdp_phone_program') !!}
                @endif
            </span>
        </div>

    @endif

    @if (app()->has('global_review_system') && app('global_review_system') === 'true')
    @endif


    <div style="display: none" class="dlv">
        <span class="dlv_name">{{ $product->name }}</span>
        <span class="dlv_price">{{ $price }}</span>
        <span class="dlv_currency">
            @if (app()->has('global_currency_primary_name'))
                {!! app('global_currency_primary_name') !!}
            @endif
        </span>
    </div>
    @include('partials.jsonld', ['product' => $product])

</div>
