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
                    {{-- -- Modelul de schimb de imagini pe slider la rezolutie -- --}}
                    <a class="main-slider__slide" href="#">
                        <picture>
                            {{-- Default (Desktop) --}}
                            <source media="(min-width: 992px)"
                                srcset="https://images.unsplash.com/photo-1529336953128-a85760f58cb5?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D">
                            {{-- Tablet Picture --}}
                            <source media="(min-width: 576px)"
                                srcset="https://images.unsplash.com/photo-1527698266440-12104e498b76?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D">
                            {{-- Mobile Picture --}}
                            <img alt="Flowers"
                                src="https://images.unsplash.com/photo-1585060544812-6b45742d762f?q=80&w=2681&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D">
                        </picture>
                    </a>
                    {{-- End Modelul de schimb de imagini pe slider la rezolutie --}}
                </div>
                <button class="main-slider__button prev" aria-label="Previous main slider">
                    <svg>
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <button class="main-slider__button next" aria-label="Next main slider">
                    <svg>
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
        @endif

        <!-------------------- End Slider Images ------------------->
        <!---------------------------------------------------------->
        <!------------------- Section Description ------------------>
        <section>
            <div class="section__header container">
                <h1 class="section__title">Descoperă produsele noastre populare!</h1>
                <p class="section__text">
                    Explorează colecția noastră de produse și găsește accesoriile perfecte pentru a-ți completa stilul.
                    <a href="{{ url('/storeproducts') }}">Vezi produsele!</a>
                </p>
            </div>
        </section>
        <!----------------- End Section Description ---------------->
        <!---------------------------------------------------------->
        <!---------------------- Slider Cards ---------------------->
        @if (!$popproducts->isEmpty())
            <section>
                <div class="card-slider container">
                    <div class="card-slider__wrapper" role="list">
                        @foreach ($popproducts as $product)
                            <div class="card-slider__slide card" role="listitem">
                                <a
                                    href="{{ route('product', ['product' => $product->seo_id !== null && $product->seo_id !== '' ? $product->seo_id : $product->id]) }}">
                                    @if ($product->media->first() != null)
                                        <img class="card-image"
                                            src="/{{ $product->media->first()->path }}{{ $product->media->first()->name }}"
                                            alt="{{ $product->media->first()->name }} {{ $product->name }}">
                                    @else
                                        <img class="card-image" src="/images/store/default/default300.webp"
                                            alt="something wrong">
                                    @endif
                                </a>
                                @livewire('product-wishlist-button', ['productId' => $product->id, 'class' => 'card__action', 'is_in_wishlist' => $product->wishlists->isNotEmpty()], key($product->id))
                                <?php
                                $price = null;
                                $discount = false;
                                
                                if ($product->product_prices->count() != 0) {
                                    $price = number_format($product->product_prices->first()->value, 2, ',', '.');
                                    $discount = $product->product_prices->first()->discount != 0 ? true : false;
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
                                        <h2>{{ $product->name }}</h2>
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
                                        <button class="card-button-disabled"
                                            aria-label="Disabled add to cart button">Indisponibil</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="card-slider__button prev" aria-label="Previous card slider button">
                        <svg>
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <button class="card-slider__button next" aria-label="Next card slider button">
                        <svg>
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
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
