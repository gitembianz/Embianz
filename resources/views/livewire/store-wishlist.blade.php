<div>
    <section>
        <!------------------------------------------------------>
        <!------------------- Basket Section ------------------->
        <div class="section__header container">
            <h2 class="section__title">Produse favorite</h2>
            <p class="section__text">
                Vezi produsele alese mai jos
            </p>
        </div>
        <!----------------- End Basket Section ----------------->
        <!------------------------------------------------------>
    </section>
    <section>
        <div class="favorite container">
            <!------------------------------------------------------>
            <!------------------- Basket Products ------------------>
            @if ($wishlistitems->isEmpty())
                <span class="basket__empty">Lista este goala</span>
            @else
                @foreach ($wishlistitems as $product)
                    <div class="basket__product">
                        <div class="basket__top">
                            @if ($product->media->first() != null)
                                <img src="/{{ $product->media->first()->path }}{{ $product->media->first()->name }}"
                                    alt="{{ $product->media->first()->name }} {{ $product->name }}">
                            @else
                                <img src="/images/store/default/default70.webp" alt="something wrong">
                            @endif
                            <div>
                                <span class="basket__price">
                                    @if ($product->product_prices->first() !== null)
                                        {{ $product->product_prices->first()->value }}
                                        {{ $product->product_prices->first()->pricelist->currency->first()->name }}
                                    @else
                                        price unavailable
                                    @endif
                                </span>
                                <a href="{{ route('product', $product) }}"
                                    class="basket__title">{{ $product->name }}</a>
                            </div>
                            <button class="basket__delete" wire:click="removeFromWishlist({{ $product->id }})">
                                <svg>
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg>
                            </button>
                            {{-- <button class="basket__delete">
                                <svg>
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </button> --}}
                        </div>
                        <button class="basket__delete--hidden" wire:click="removeFromWishlist({{ $product->id }})">
                            Remove from Wishlist
                        </button>
                        {{-- <button class="basket__delete--hidden">
                            Add to Cart
                        </button> --}}
                    </div>
                @endforeach
            @endif
            <!----------------- End Basket Products ---------------->
            <!------------------------------------------------------>
        </div>
    </section>
    <!---------------------------------------------------------->
    <!--------------------- support button --------------------->
    <x-help-button />
    <!------------------- End support button ------------------->
    <!---------------------------------------------------------->
</div>
