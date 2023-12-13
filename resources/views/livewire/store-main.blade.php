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
        @if ($category)
            @if (!$subcategories->isEmpty())
                <div class="main-slider">
                    <div class="main-slider__wrapper">
                        @foreach ($subcategories as $item)
                            <div class="main-slider__slide">
                                @if ($item->category->media)
                                    @if ($item->category->media->first()->external)
                                        <img src="{{ $item->category->media->first()->path }}" draggable="false"
                                            alt="{{ $item->category->media->first()->path }}">
                                    @else
                                        <img src="/{{ $item->category->media->first()->path }}{{ $item->category->media->first()->name }}"
                                            draggable="false" alt="{{ $item->category->media->first()->path }}">
                                    @endif
                                @endif
                                <div class="main-slider__text container">
                                    <h3>{{ $item->category->name }}</h3>
                                    <p>{{ $item->category->short_description }}</p>
                                    <a class="main-slider__link"
                                        href="/storeproducts/{{ $item->id }}">Acceseaza!</a>
                                </div>
                            </div>
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
                    <br><a href="{{ url("/storeproducts") }}">Vezi produsele!</a>
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
                                        @if ($product->media->count() != 0)
                                            @foreach ($product->media as $media)
                                                @if ($media->location->location == "main")
                                                    @if ($media->external)
                                                        <img class="card-image" src="{{ $media->path }}"
                                                            draggable="false" alt="{{ $media->path }}">
                                                    @else
                                                        <img class="card-image"
                                                            src="/{{ $media->path }}{{ $media->name }}"
                                                            draggable="false" alt="{{ $media->path }}">
                                                    @endif
                                                    <?php break; ?>
                                                @endif
                                            @endforeach
                                        @else
                                            <img class="card-image" src="/images/store/default/default.svg"
                                                draggable="false" alt="something wrong">
                                        @endif
                                    </a>
                                    @livewire("product-wishlist-button", ["product" => $product], key($product->id))
                                    <div class="card-info">
                                        <div class="card-text">
                                            <span>{{ $product->short_description }}</span>
                                        </div>
                                        <div class="card-text">
                                            <h3>{{ $product->name }}</h3>
                                            <p>
                                                @if ($product->product_prices->first())
                                                    {{ number_format($product->product_prices->first()->value, 2, ",", ".") }}
                                                    {{ $product->product_prices->first()->pricelist->currency->name }}
                                                @else
                                                    {{ __("Indisponibil") }}
                                                @endif
                                            </p>
                                        </div>
                                        @if ($product->product_prices->first() && $product->quantity != 0)
                                            <a class="card-button" wire:click="addToCart({{ $product->id }})">Adauga
                                                in coș</a>
                                        @else
                                            <a class="card-button-disabled">Indisponibil</a>
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
