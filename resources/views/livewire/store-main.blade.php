<div class="home__container" id="homeContainer">
    <div id="home__slide" role="list">
        @if ($category)

            @foreach ($category->product_categories as $product)
                <div class="home__item home__this"
                    style="background-image:
               @if (count($product->product->media) > 0) @foreach ($product->product->media as $media)
                                               @if ($media->location->location == 'main')
                                                   @if ($media->external)
                                                       url({{ $media->path }}" alt="{{ $media->path }})
                                                   @else
                                                       url(/{{ $media->path }}{{ $media->name }}) @endif
@break

@endif
                                       @endforeach
@else
url(/images/store/default/default.svg)
      @endif"
                    role="listitem">
                    <div class="home__content">
                        <div class="container home__content--flex">
                            <h2>{{ $product->product->name }}</h2>
                            <p>
                                {{ $product->product->short_description }}
                            </p>
                            <button aria-label="See more">See more</button>
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
</div>
<div class="container cards">
    <h2 class="section__title">Top products this month</h2>
    <div class="card-wrapper">
        <button id="cardLeft" class="card-button" aria-label="Previous product">
            <svg>
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <ul class="card-carousel" role="list">
            @foreach ($popproducts as $product)
                <li class="card" role="listitem">
                    @if (count($product->media) > 0)
                        @foreach ($product->media as $media)
                            @if ($media->location->location == 'main')
                                @if ($media->external)
                                    <img src="{{ $media->path }}" draggable="false" alt="{{ $media->path }}">
                                @else
                                    <img src="/{{ $media->path }}{{ $media->name }}" draggable="false"
                                        alt="{{ $media->path }}">
                                @endif
                            @break

                        @endif
                    @endforeach
                @else
                    <img src="/images/store/default/default.svg" draggable="false" alt="something wrong">
                @endif
                <h2>{{ $product->name }}</h2>
                <p>{{ $product->short_description }}</p>
                <span>Eco-friendly, BPA-free, Reusable</span>
                <p>
                    @if ($product->product_prices->first())
                        {{ $product->product_prices->first()->pricelist->currency->name }}
                        {{ $product->product_prices->first()->value }}
                    @else
                        {{ __('no price') }}
                    @endif
                </p>
                {{-- <p class="price"><span>$24.99</span>$19.99</p> --}}
                <div class="card__fire">
                    <img src="/images/store/fire.svg" alt="fire">
                </div>
            </li>
        @endforeach
    </ul>
    <button id="cardRight" class="card-button" aria-label="Next product">
        <svg>
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </button>
</div>
</div>
<div class="home__discover" style="background-image: url(images/store/discover-background.webp)">
<div class="container home__discover--flex">
    <div class="home__discover--text">
        <h1>Explore our products and find the perfect one for you.</h1>
        <a href="#">Discover our products</a>
    </div>
    <img src="images/store/discover-items.webp" alt="discover items">
</div>
</div>
{{--
<div class="home__video">
  <video autoplay loop muted playsinline defaultmuted preload="auto">
      <source src="images/store/myVideo.mp4" type="video/mp4"> Your browser does not support HTML5 video.
  </video>
  <div class="home__video--text container">
      <h1>Verdele este pasiunea noastră - Descoperă colecția noastră de sticle eco-friendly și fă o
          alegere sustenabilă!</h1>
      <span><q><i>20% din profitul nostru susține protecția mediului!</i></q></span>
      <img src="images/store/white-logo.svg" alt="logo">
  </div>
</div>
--}}
