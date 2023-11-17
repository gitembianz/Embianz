<div>
    <div class="product">

        <!-- Product Slider -->
        <div class="slider2">
            <div class="slider-wrapper2">
                @foreach ($product->media as $media)
                    @if ($media->location->location == 'main' || $media->location->location == 'details')
                        <div class="slide2">
                            @if ($media->external)
                                <img src="{{ $media->path }}" alt="{{ $media->path }}">
                            @else
                                <img src="/{{ $media->path }}{{ $media->name }}" alt="{{ $media->path }}">
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="pagination2"></div>
        </div>

        <!-- Product Info -->
        <div class="product__details">

            <div class="product__text" style="position: relative">
                <h1 class="product__title">{{ $product->name }}</h1>
                @if ($product->product_categories->first())
                    <span class="product__subtitle">{{ $product->product_categories->first()->category->name }}</span>
                @endif

                <button class="card-favorites @if ($product->wishlists->where('session_id', $session_id)->isNotEmpty()) active @endif"
                    wire:click="toggleWishlist()">
                    <svg viewBox="0 0 512 512" width="20" title="heart">
                        <path
                            d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z" />
                    </svg>
                </button>
                <div class="price">
                    @if ($product->product_prices->first() !== null)
                        {{ $product->product_prices->first()->value }}
                        {{ $product->product_prices->first()->pricelist->currency->name }}
                    @else
                        pret indisponibil
                    @endif
                </div>
            </div>
            @if ($product->product_prices->first() !== null)
                <div class="product__count">
                    <button id="countDecrease" wire:click="decrementCounter">
                        <svg>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                    <input type="number" name="count" id="count" wire:model="quantity">
                    <button id="countIncrease" wire:click="incrementCounter">
                        <svg>
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>

                </div>
            @endif
            @if ($maxlimit)
                <label for="count">Cantitatea maxima a produsului este {{ $limit }}</label>
            @endif
            @php
                $price = $product->product_prices->first();
            @endphp
            @if ($price && $product->quantity != 0)
                <button wire:click="addToCart({{ $product->id }})" class="product__btn">Add to cart</button>
            @endif
            <div class="product__description">
              
                <div class="tab">
                    <div class="tab__header">
                        <button class="tab__header--btn @if ($activeTab === 0) active @endif"
                            wire:click="switchTab(0)">Description</button>
                        <button class="tab__header--btn @if ($activeTab === 1) active @endif"
                            wire:click="switchTab(1)">Details</button>
                    </div>
                    <div class="tab__content">
                        <div class="tab__pane @if ($activeTab === 0) active @endif">
                            <p>{{ $product->long_description }}</p>
                        </div>
                        <div class="tab__pane @if ($activeTab === 1) active @endif">
                            <div class="table__wrapper">
                                <table class="table__info">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
