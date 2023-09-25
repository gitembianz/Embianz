<div class="cart__page container">
    <h2 class="section__title">Favorite Products</h2>

    <div class="cart-page__list">

        @if ($wishlistitems->isEmpty())
            <p>No products</p>
        @else
            @foreach ($wishlistitems as $product)
                <article class="cart-page__item">
                    @if (count($product->media) > 0)
                        @foreach ($product->media as $media)
                            @if ($media->location->location == 'main')
                                @if ($media->external)
                                    <img src="{{ $media->path }}" alt="{{ $media->path }}">
                                @else
                                    <img src="/{{ $media->path }}{{ $media->name }}" alt="{{ $media->path }}">
                                @endif
                                <?php break; ?>
                            @endif
                        @endforeach
                    @else
                        <img src="/images/store/default/default.svg"alt="something wrong">
                    @endif
                    <div class="cart-page--text">
                        <h3>{{ $product->name }}</h3>
                        <span>
                            @if ($product->product_prices->first() !== null)
                                {{ $product->product_prices->first()->value }}
                                {{ $product->product_prices->first()->pricelist->currency->first()->name }}
                            @else
                                price unavailable
                            @endif
                        </span>
                        {{-- <span>
                            <span>107.98 lei</span>
                            89,99 lei
                        </span> --}}
                        <div class="cart-page--bundle">
                            <button class="cart-page__delete" wire:click="removeFromWishlist({{ $product->id }})">
                                <svg>
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg>
                            </button>
                            <button class="cart-page__heart">
                                <svg>
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        @endif
    </div>
</div>
