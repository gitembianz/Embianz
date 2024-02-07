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
                    @if ($product->media->count() != 0)
                        @foreach ($product->media->where("type", "full") as $media)
                            <div class="product-slider__slide">
                                <img src="/{{ $media->path }}{{ $media->name }}" alt="{{ $media->path }}"
                                    data-img-src="/{{ $product->media->where("type", "original")->where("sequence", $media->sequence)->first()->path }}{{ $product->media->where("type", "original")->where("sequence", $media->sequence)->first()->name }}">
                            </div>
                        @endforeach
                    @else
                        <div class="product-slider__slide">
                            <img src="/images/store/default/default.webp" alt="something wrong">
                        </div>
                    @endif
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
        <!----------------------- Product details ---------------------->
        @livewire("product-details", ["product" => $product])
        <!--------------------- End Product details -------------------->
        <!------------------------------------------------------>
    </section>
    <!---------------------------------------------------------->
    <!------------------- Section Description ------------------>
    <section>
        <div class="section__header container">
            <h2 class="section__title">Descoperă și alte opțiuni similare</h2>
            <p class="section__text">
                În căutarea perfectă? Explorează și alte propuneri care te-ar putea interesa.
                Descoperă produse similare, perfecte pentru gusturile tale și nevoile tale. În continuare, vei găsi
                opțiuni care completează gama noastră și care îți pot satisface preferințele. Alege cu încredere dintre
                aceste alternative și găsește exact ceea ce cauți.
            </p>
        </div>
    </section>
    <!----------------- End Section Description ---------------->
    <!---------------------------------------------------------->
    <!---------------------- Slider Cards ---------------------->
    <section>
        <div class="card-slider container">
            <div class="card-slider__wrapper">
                <div class="card-slider__slide">
                    <div class="card" role="listitem">
                        <a href="#">
                            <img class="card-image" src="/images/store/default/default300.webp" alt="something wrong">
                        </a>
                        <div class="card-info">
                            <div class="card-text">
                                <span>description</span>
                            </div>
                            <div class="card-text">
                                <h3>Product Name</h3>
                                <p class="card-price">
                                    <span>
                                        15.00$
                                    </span>
                                </p>
                            </div>

                            <a class="card-button-disabled">Indisponibil</a>
                        </div>
                    </div>
                </div>
                <div class="card-slider__slide">
                    <div class="card" role="listitem">
                        <a href="#">
                            <img class="card-image" src="/images/store/default/default300.webp" alt="something wrong">
                        </a>
                        <div class="card-info">
                            <div class="card-text">
                                <span>description</span>
                            </div>
                            <div class="card-text">
                                <h3>Product Name</h3>
                                <p class="card-price">
                                    <span>
                                        15.00$
                                    </span>
                                </p>
                            </div>

                            <a class="card-button-disabled">Indisponibil</a>
                        </div>
                    </div>
                </div>
                <div class="card-slider__slide">
                    <div class="card" role="listitem">
                        <a href="#">
                            <img class="card-image" src="/images/store/default/default300.webp" alt="something wrong">
                        </a>
                        <div class="card-info">
                            <div class="card-text">
                                <span>description</span>
                            </div>
                            <div class="card-text">
                                <h3>Product Name</h3>
                                <p class="card-price">
                                    <span>
                                        15.00$
                                    </span>
                                </p>
                            </div>

                            <a class="card-button-disabled">Indisponibil</a>
                        </div>
                    </div>
                </div>
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
    <!-------------------- End Slider Cards -------------------->
    <!---------------------------------------------------------->
    <!--------------------- support button --------------------->
    <x-help-button />
    <!------------------- End support button ------------------->
    <!---------------------------------------------------------->
    <script src="/script/store/product.js" async defer></script>
</div>
