<x-store-head :title='($data->seo_title ?? "") . " | "' />

<x-store-header />
<main>

    @livewire("store-show-product", ["productId" => $data->id])
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
</main>
<x-store-footer />
