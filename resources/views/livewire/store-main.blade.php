<div>
    <main>
        <!-- This is the Main Page;
        the <main> tag encompasses the main content and sections of the webpage,
        including the sliders and product cards. The styles for the sliders, "slider-image.scss"
        and "slider-card.scss," can be found in the "slider" folder. To maintain clarity and
        organization, the styles for the product cards are stored in the "products" folder,
        specifically in the "card-style.scss" file.

        For enhanced user interaction and functionality, the JavaScript file is located in the
        "Sliders" folder, which contains both "slider-images" and "slider-card" files. This
        ensures a streamlined structure for managing the dynamic aspects of the sliders on the Main Page. -->
        <!---------------------------------------------------------->
        <!---------------------- Slider Images --------------------->
        @if (!$slideritems->isEmpty())
            <div class="main-slider">
                <div class="main-slider__wrapper">
                    @foreach ($slideritems as $item)
                        <a class="main-slider__slide" href="/storeproducts/{{ $item->id }}">
                            @if ($item->media->first() != null)
                                <img src="/{{ $item->media->first()->path }}{{ $item->media->first()->name }}"
                                    alt="{{ $item->media->first()->name }} {{ $item->name }}">
                            @else
                                <img src="/images/store/default/default.webp" alt="something wrong">
                            @endif
                        </a>
                    @endforeach
                    <a class="main-slider__slide" href="/storeproducts/{{ $item->id }}">
                        <img src="/images/store/default/default.webp" alt="something wrong">
                        <picture>
                            {{-- Default (Desktop) --}}
                            <img src="img_orange_flowers.jpg" alt="Flowers" style="width:auto;">
                            {{-- Tablet Picture --}}
                            <source media="(max-width:650px)" srcset="img_pink_flowers.jpg">
                            {{-- Mobile Picture --}}
                            <source media="(max-width:465px)" srcset="img_white_flower.jpg">
                        </picture>
                    </a>
                </div>
                <div class="main-slider__button prev">
                    <svg>
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </div>
                <div class="main-slider__button next">
                    <svg>
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </div>
        @endif

        <!-------------------- End Slider Images ------------------->
        <!---------------------------------------------------------->
        <!------------------- Section Description ------------------>
        <section>
            <div class="section__header container">
                <h2 class="section__title">Descoperă produsele noastre populare!</h2>
                <p class="section__text">Explorează colecția noastră de produse și găsește
                    accesoriile perfecte pentru a-ți completa stilul.
                    <br><a href="{{ url('/storeproducts') }}">Vezi produsele!</a>
                </p>
            </div>
        </section>
        <!----------------- End Section Description ---------------->
        <!---------------------------------------------------------->
        <!---------------------- Slider Cards ---------------------->
        @if (!$popproducts->isEmpty())
            <section>
                <div class="card-slider container">
                    <div class="card-slider__wrapper">
                        @foreach ($popproducts as $product)
                            <div class="card-slider__slide">
                                <div class="card" role="listitem">
                                    <a href="/product/{{ $product->id }}">
                                        @if ($product->media->first() != null)
                                            <img class="card-image"
                                                src="/{{ $product->media->first()->path }}{{ $product->media->first()->name }}"
                                                alt="{{ $product->media->first()->name }} {{ $product->name }}">
                                        @else
                                            <img class="card-image" src="/images/store/default/default300.webp"
                                                alt="something wrong">
                                        @endif
                                    </a>
                                    @livewire('product-wishlist-button', ['productId' => $product->id, 'is_in_wishlist' => $product->is_in_wishlist], key($product->id))
                                    <?php if ($product->product_prices->count() != 0) {
                                        $price = number_format($product->product_prices->first()->value, 2, ',', '.');
                                        $discount = $product->product_prices->first()->discount != 0 ? true : false;
                                    } else {
                                        $price = null;
                                        $discount = false;
                                    }
                                    ?>
                                    @if ($price)
                                        @if ($product->quantity < $quantity && $product->quantity > 0)
                                            <p class="card-status out">
                                                Stock limitat!!
                                            </p>
                                            @if ($discount)
                                                <p class="card-status save-secondary">
                                                    -{{ $product->product_prices->first()->discount }}%
                                                </p>
                                            @endif
                                        @elseif($product->quantity == 0)
                                            <p class="card-status save">
                                                Produs indisponibil!
                                            </p>
                                        @else
                                            @if ($discount)
                                                <p class="card-status save">
                                                    -{{ $product->product_prices->first()->discount }}%
                                                </p>
                                            @endif
                                        @endif
                                        {{-- tagul de discount --}}
                                    @else
                                        <p class="card-status save">
                                            În curând!
                                        </p>
                                    @endif
                                    <div class="card-info">
                                        <div class="card-text">
                                            <span>{{ $product->short_description }}</span>
                                        </div>
                                        <div class="card-text">
                                            <h3>{{ $product->name }}</h3>
                                            <p class="card-price">
                                                @if ($discount)
                                                    <span class="card-price discount">
                                                        @if ($product->product_prices->first())
                                                            {{ $price }}
                                                            {{ $product->product_prices->first()->pricelist->currency->name }}
                                                        @endif
                                                    </span>
                                                    <span class="card-price oldprice">
                                                        {{ $product->product_prices->first()->rrp_value }}
                                                        {{ $product->product_prices->first()->pricelist->currency->name }}
                                                    </span>
                                                @else
                                                    <span>
                                                        @if ($product->product_prices->first())
                                                            {{ $price }}
                                                            {{ $product->product_prices->first()->pricelist->currency->name }}
                                                        @endif
                                                    </span>
                                                @endif

                                            </p>
                                        </div>
                                        @if ($price)
                                            @livewire('add-to-cart-button', ['product' => $product], key($product->id))
                                        @else
                                            <a class="card-button-disabled" onclick="handleClick()">Indisponibil</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-slider__button prev">
                        <svg>
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </div>
                    <div class="card-slider__button next">
                        <svg>
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </div>
            </section>
        @endif
        <!-------------------- End Slider Cards -------------------->
        <!---------------------------------------------------------->
        <!---------------------- Support Center -------------------->
        <x-support />
        <!-------------------- End Support Center ------------------>
        <!---------------------------------------------------------->

        <!---------------------------------------------------------->
        <!--------------------- support button --------------------->
        <x-help-button />
        <!------------------- End support button ------------------->
        <!---------------------------------------------------------->
    </main>
    <script src="/script/store/main.js" async defer></script>
</div>
