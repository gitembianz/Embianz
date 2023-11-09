<div>
    {{-- <div class="home__container" id="homeContainer">
        <div id="home__slide" role="list">
            @if ($category)
                @foreach ($category->product_categories as $product)
                    <div class="home__item home__this"
                        style="background-image:
                  @if (count($product->product->media) > 0) @foreach ($product->product->media as $media)
                  @if ($media->location->location == "main")
                    @if ($media->external)
                      url({{ $media->path }}" alt="{{ $media->path }})
                    @else
                      url(/{{ $media->path }}{{ $media->name }}) @endif
                  @endif
                  <?php break; ?>
                @endforeach
@else
url(/images/store/default/default.svg)
              @endif "
                        role="listitem">
                        <div class="home__content">
                            <div class="container home__content--flex">
                                <h2>{{ $product->product->name }}</h2>
                                <p>
                                    {{ $product->product->short_description }}
                                </p>
                                <a href="{{ route('product', ['id' => $product->product->id]) }}" aria-label="See more">
                                    <button>See more</button>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="home__buttons">
            <button id="home__prev" aria-label="Previous slide">
                <svg>
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <button id="home__next" aria-label="Next slide">
                <svg>
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
    </div> --}}
    @if ($category)
        <div class="slider">
            <div class="slider__wrapper">
                @foreach ($category->product_categories as $product)
                    <div class="slide">
                        @if (count($product->product->media) > 0)
                            @foreach ($product->product->media as $media)
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
                        @else
                            <img src="/images/store/default/default.svg" draggable="false" alt="something wrong">
                        @endif
                    </div>
                @endforeach
            </div>
            {{-- <div class="progress__container">
                <div class="progress">
                    <div class="progress-loader"></div>
                </div>
            </div> --}}
            <div class="slider__pagination"></div>
            <div class="slider__buttons">
                <div class="slider__btn" id="prev">
                    <svg>
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </div>
                <div class="slider__btn" id="next">
                    <svg>
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </div>
            <div class="slider__touch-area" id="touch-prev"></div>
            <div class="slider__touch-area" id="touch-next"></div>
        </div>
    @endif
    <div class="container cards">
        <h2 class="section__title">Produsele noastre populare</h2>
        <div class="card-wrapper">
            <button id="cardLeft" class="card-nav" aria-label="Previous product">
                <svg>
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <ul class="card-carousel" role="list">
                @foreach ($popproducts as $product)
                    <div class="card" role="listitem">
                        <a href="/product/{{ $product->id }}">
                            @if (count($product->media) > 0)
                                @foreach ($product->media as $media)
                                    @if ($media->location->location == "main")
                                        @if ($media->external)
                                            <img class="card-image" src="{{ $media->path }}" draggable="false"
                                                alt="{{ $media->path }}">
                                        @else
                                            <img class="card-image" src="/{{ $media->path }}{{ $media->name }}"
                                                draggable="false" alt="{{ $media->path }}">
                                        @endif
                                        <?php break; ?>
                                    @endif
                                @endforeach
                            @else
                                <img src="/images/store/default/default.svg" draggable="false" alt="something wrong">
                            @endif
                        </a>
                        {{-- <img class="card-image" src="https://24bottles.com/cdn/shop/products/1496_01_590x.png?v=1644250387" --}}
                        {{-- alt="Card-Image"> --}}
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
                                {{-- <span>600ml</span> --}}
                            </div>
                            <div class="card-text">
                                <h3>{{ $product->name }}</h3>
                                <p>
                                    @if ($product->product_prices->first())
                                        {{ $product->product_prices->first()->pricelist->currency->name }}
                                        {{ $product->product_prices->first()->value }}
                                    @else
                                        {{ __("no price") }}
                                    @endif
                                </p>
                            </div>
                            {{-- <button class="card-button">View Product</button> --}}
                            <a class="card-button" wire:click="addToCart({{ $product->id }})">Adauga in coș</a>
                        </div>
                    </div>
                @endforeach
            </ul>
            <button id="cardRight" class="card-nav" aria-label="Next product">
                <svg>
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
    </div>
    <div class="home__discover" style="background-image: url(images/store/discover-background.webp)">
        <div class="container home__discover--flex">
            <div class="home__discover--text">
                <h1>
                    Explorează produsele noastre și găsește-l pe cel perfect pentru tine.</h1>
                <a href="/storeproducts">Descoperiți produsele noastre</a>
            </div>
            <img src="images/store/discover-items.webp" alt="discover items">
        </div>
    </div>
</div>
