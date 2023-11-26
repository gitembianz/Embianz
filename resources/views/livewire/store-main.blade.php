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
                                @if (count($item->media) > 0)
                                    @foreach ($item->media as $media)
                                        @if ($media->location->location == "details")
                                            @if ($media->external)
                                                <img src="{{ $media->path }}" draggable="false" alt="{{ $media->path }}">
                                            @else
                                                <img src="/{{ $media->path }}{{ $media->name }}" draggable="false"
                                                    alt="{{ $media->path }}">
                                            @endif
                                            <?php break; ?>
                                        @endif
                                    @endforeach
                                @endif
                                <div class="main-slider__text container">
                                    <h3>{{ $item->name }}</h3>
                                    <p>{{ $item->short_description }}</p>
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
                <p class="section__text">Explorează colecția noastră de produse apreciate de clienți și găsește
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
                                        @if (count($product->media) > 0)
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
                                    <button class="card-favorites @if ($product->wishlists->where("session_id", $session_id)->isNotEmpty()) active @endif"
                                        wire:click="toggleWishlist({{ $product->id }})">
                                        <svg viewBox="0 0 512 512" width="20" title="heart">
                                            <path
                                                d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z" />
                                        </svg>
                                    </button>
                                    <div class="card-info">
                                        <div class="card-text">
                                            <span>{{ $product->short_description }}</span>
                                        </div>
                                        <div class="card-text">
                                            <h3>{{ $product->name }}</h3>
                                            <p>
                                                @if ($product->product_prices->first())
                                                    {{ $product->product_prices->first()->value }}
                                                    {{ $product->product_prices->first()->pricelist->currency->name }}
                                                @else
                                                    {{ __("no price") }}
                                                @endif
                                            </p>
                                        </div>
                                        <a class="card-button" wire:click="addToCart({{ $product->id }})">Adauga in
                                            coș</a>
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
        <!---------------------- Loading Logo ---------------------->
        <div class="loading-logo">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                width="1280" height="100%" viewBox="0 0 1280 1024" xml:space="preserve">
                <defs>
                </defs>
                <g transform="matrix(1 0 0 1 640 512)" id="background-logo">
                    <rect
                        style="stroke: none; stroke-width: 0; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(255, 255, 255); fill-opacity: 0; fill-rule: nonzero; opacity: 1;"
                        paint-order="stroke" x="-640" y="-512" rx="0" ry="0" width="1280"
                        height="1024" class="svg-elem-1"></rect>
                </g>
                <g transform="matrix(4.545629534834756 0 0 4.545629534834756 640.6479799851742 512.3702742772424)"
                    id="logo-logo">
                    <g style="" paint-order="stroke">
                        <g transform="matrix(1.4162867447099505 0 0 1.4162867447099505 0 0)">
                            <path
                                style="stroke: none; stroke-width: 1; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(26, 105, 26); fill-rule: nonzero; opacity: 1;"
                                paint-order="stroke" transform=" translate(-297.19335939999996, -421.5514536186913)"
                                d="M 283.0551758 419.6142578 L 328.5664063 401.78955080000003 C 324.6835938 395.84033200000005 319.1972657 391.315918 312.93554689999996 388.578125 C 294.8208008 380.6601562 273.64501959999996 388.9389648 265.72558599999996 407.0576172 C 259.4848633 421.33398439999996 263.2983399 437.5014649 274.0610352 447.5922852 C 285.0200196 457.8686524 307.2226563 461.1831055 318.4492188 450.9272461 C 308.7382813 454.7524414 298.703125 454.1420899 291.1806641 450.4614258 C 311.1738282 411.1860352 370.4550782 418.6079102 392.3867188 447.1704102 C 348.515625 436.0073243 315.2832032 493.6225586 270.27783209999996 463.2817383 C 266.39843759999997 460.66748049999995 262.8798828999999 457.49560549999995 259.8720704 453.9077149 C 253.0771485 448.57373049999995 244.96484379999998 445.5024415 235.54882819999997 445.5209961 C 246.60693369999998 449.2475586 256.16748049999995 455.2202149 261.44287119999996 467.621582 C 231.215332 477.5004883 222.7910156 445.4379883 202 439.0063477 C 216.4086914 429.1879883 235.699707 427.3403321 249.5209961 433.5288086 C 246.94091799999998 423.1108398 247.6474609 411.793457 252.2797852 401.1962891 C 263.4550782 375.6298828 293.2250977 363.9541016 318.796875 375.1323243 C 330.6523438 380.3149415 340.5292969 390.0146485 345.6152344 403.0019532 L 347.9296875 409.9741212 L 274.9882812 438.54248060000003 C 272.5532227 431.4145508 276.4277344 422.2104492 283.0551758 419.6142578 L 283.0551758 419.6142578 z"
                                stroke-linecap="round" class="svg-elem-2"></path>
                        </g>
                    </g>
                </g>
            </svg>
        </div>
        <!-------------------- End Loading Logo -------------------->
        <!---------------------------------------------------------->

    </main>
</div>
