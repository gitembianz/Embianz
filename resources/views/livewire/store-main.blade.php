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
                                <img src="/{{ $item->media->first()->path }}/{{ $item->media->first()->name }}"
                                    alt="{{ $item->media->first()->path }}">
                            @else
                                <img src="/images/store/default/default.webp" alt="something wrong">
                            @endif

                            {{-- <div class="main-slider__text container">
                                <h3>{{ $item->name }}</h3>
                                <p>{{ $item->short_description }}</p>
                                <a class="main-slider__link" href="/storeproducts/{{ $item->id }}">Acceseaza!</a>
                            </div> --}}
                        </a>
                    @endforeach
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
                                                src="/{{ $product->media->first()->path }}/{{ $product->media->first()->name }}"
                                                alt="{{ $product->media->first()->path }}">
                                        @else
                                            <img class="card-image" src="/images/store/default/default300.webp"
                                                alt="something wrong">
                                        @endif
                                    </a>
                                    @livewire('product-wishlist-button', ['productId' => $product->id, 'is_in_wishlist' => $product->is_in_wishlist], key($product->id))
                                    <?php if ($product->product_prices->count() != 0) {
                                        $price = number_format($product->product_prices->first()->value, 2, ',', '.');
                                    } else {
                                        $price = null;
                                    }
                                    ?>
                                    <div class="card-info">
                                        <div class="card-text">
                                            <span>{{ $product->short_description }}</span>
                                        </div>
                                        <div class="card-text">
                                            <h3>{{ $product->name }}</h3>
                                            <p>
                                                @if ($product->product_prices->first())
                                                    {{ number_format($product->product_prices->first()->value, 2, ',', '.') }}
                                                    {{ $product->product_prices->first()->pricelist->currency->name }}
                                                @else
                                                    {{ __('Indisponibil') }}
                                                @endif
                                            </p>
                                        </div>
                                        @if ($product->product_prices->first())
                                            @livewire('add-to-cart-button', ['product' => $product], key($product->id))
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
