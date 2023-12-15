<div id="store-show-product">
    <section>
        <div class="breadcrumbs container">
            <a class="breadcrumbs__link" href="{{ url("/") }}">
                Acasa
            </a>
            <a class="breadcrumbs__link" href="{{ url("/storeproducts") }}">
                Produse
            </a>
            <a class="breadcrumbs__link">{{ $product->name }}</a>
        </div>
    </section>
    <section class="product container">
        <!-------------------- Slider Product ------------------>
        <div class="product-slider">
            <div class="product-slider__center">

                <div class="product-slider__wrapper">
                    @foreach ($product->media as $media)
                        @if ($media->location->location == "main" || $media->location->location == "details")
                            <div class="product-slider__slide">
                                @if ($media->external)
                                    <img src="{{ $media->path }}" alt="{{ $media->path }}"
                                        data-img-src="exemplu de text">
                                @else
                                    <img src="/{{ $media->path }}{{ $media->name }}" alt="{{ $media->path }}"
                                        data-img-src="exemplu de text">
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="product-slider__navigation">
                    <button class="product-slider__prev" aria-label="Previous slide">
                        <svg>
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <button class="product-slider__next" aria-label="Next slide">
                        <svg>
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="product-slider__pagination"></div>

        </div>
        <!------------------ End Slider Product ---------------->
        <!------------------------------------------------------>
        <!-------------------- Modal Product ------------------>
        <div class="product-modal">
            <div class="product-modal__content"></div>
            <span class="product-modal__close">
                <svg>
                    <polyline points="4 14 10 14 10 20"></polyline>
                    <polyline points="20 10 14 10 14 4"></polyline>
                    <line x1="14" y1="10" x2="21" y2="3"></line>
                    <line x1="3" y1="21" x2="10" y2="14"></line>
                </svg>
            </span>
        </div>
        <!------------------ End Modal Product ---------------->
        <!------------------------------------------------------>
        <!----------------------- Product ---------------------->
        <div class="product__container">
            <!------------------------------------------------------>
            <!------------------ Product (Details) ----------------->
            <div class="product__text">
                <div>
                    <span class="product__subtitle">{{ $product->short_description }}</span>
                    <h1 class="product__title">{{ $product->name }}</h1>
                </div>
                @livewire("wishlist-button", ["product" => $product])
            </div>
            <div class="product__price">
                <span>Pret</span>
                <span>
                    @if ($product->product_prices->first() !== null)
                        {{ number_format($product->product_prices->first()->value, 2, ",", ".") }}
                        {{ $product->product_prices->first()->pricelist->currency->name }}
                    @else
                        Pret Indisponibil
                    @endif
                </span>
            </div>
            @if ($product->product_prices->first() !== null)
                <span class="product__tva">
                    Pretul include taxa TVA de
                    {{ number_format($product->product_prices->first()->tva_percent, 2, ",", ".") }}%
                </span>
                <div class="quantity">
                    <span>Cantitate</span>
                    <div class="quantity__buttons">
                        <button class="quantity__arrow" wire:click="decrementCounter">
                            <svg>
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                        </button>
                        <span class="quantity__input" name="count" id="count">
                            {{ $quantity }}
                        </span>
                        <button class="quantity__arrow" wire:click="incrementCounter">
                            <svg>
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
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
                <button wire:click="addToCart({{ $product }})" class="product__button">Adauga in coș</button>
            @else
                <a class="card-button-disabled" onclick="handleClick()">Indisponibil</a>
            @endif
            <!---------------- End Product (Details) --------------->
            <!------------------------------------------------------>
            <!-------------------- Tab (Details) ------------------->
            <div class="tab">
                <div class="tab__top">
                    <button class="tab__button @if ($activeTab === 0) active @endif"
                        wire:click="switchTab(0)">Descriere</button>
                    <button class="tab__button @if ($activeTab === 1) active @endif"
                        wire:click="switchTab(1)">Detalii</button>
                </div>
                <div class="tab__content @if ($activeTab === 0) active @endif">
                    <p class="tab__info">{!! $product->long_description !!}</p>
                </div>
                <div class="tab__content @if ($activeTab === 1) active @endif">
                    <table class="tab__table">
                        <thead>
                            <tr>
                                <th>Specificatii </th>
                                <th>Descriere</th>
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
                                    <td colspan="2">Nu exista specificatii pentru acest product</td>
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
    <!---------------------------------------------------------->
    <!--------------------- support button --------------------->
    <x-help-button />
    <!------------------- End support button ------------------->
    <!---------------------------------------------------------->
    <script src="/script/store/product.js" async defer></script>
</div>
