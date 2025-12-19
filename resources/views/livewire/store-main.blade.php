<div>
    <x-confettialert />

    <main>
        <!---------------------- Slider Images --------------------->

        @if (!$slideritems->isEmpty())
            <div class="main-slider">
                <div class="main-slider__wrapper">
                    @foreach ($slideritems as $item)
                        <a class="main-slider__slide" href="{{ route('products', ['categorySlug' => $item['slug']]) }}"
                            draggable="false">

                            <picture>
                                {{-- Desktop --}}
                                @if ($item['slider_media'][2])
                                    <source media="(min-width: 992px)" srcset="{{ $item['slider_media'][2]['src'] }}"
                                        width="{{ $item['slider_media'][2]['width'] }}"
                                        height="{{ $item['slider_media'][2]['height'] }}" fetchpriority="high">
                                @else
                                    <source media="(min-width: 992px)" srcset="/images/store/default/default.webp">
                                @endif

                                {{-- Tablet --}}
                                @if ($item['slider_media'][3])
                                    <source media="(min-width: 576px)" srcset="{{ $item['slider_media'][3]['src'] }}"
                                        width="{{ $item['slider_media'][3]['width'] }}"
                                        height="{{ $item['slider_media'][3]['height'] }}">
                                @endif

                                {{-- Mobile --}}
                                @if ($item['slider_media'][4])
                                    <img src="{{ $item['slider_media'][4]['src'] }}" alt="{{ $item['name'] }}"
                                        width="{{ $item['slider_media'][4]['width'] }}"
                                        height="{{ $item['slider_media'][4]['height'] }}" fetchpriority="high">
                                @else
                                    <img src="/images/store/default/default300.webp" alt="Default image">
                                @endif
                            </picture>

                        </a>
                    @endforeach
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

        @php
            if (app()->has('global_numberformat_element')) {
                if (app('global_numberformat_element') === '.') {
                    $mill = '.';
                    $decimal = ',';
                } else {
                    $mill = ',';
                    $decimal = '.';
                }
            } else {
                $mill = '.';
                $decimal = ',';
            }
        @endphp
        {{-- sliders --}}
        @if ($popproducts->isNotEmpty())

            <section>
                <div class="section__header container">
                    <h1 class="section__title">
                        @if (app()->has('label_mainpage_popproducts_slider_title'))
                            {!! app('label_mainpage_popproducts_slider_title') !!}
                        @endif
                    </h1>
                    <p class="section__text">
                        @if (app()->has('label_mainpage_popproducts_slider_description'))
                            {!! app('label_mainpage_popproducts_slider_description') !!}
                        @endif
                    </p>
                </div>
            </section>
            <!----------------- End Section Description ---------------->

            <section>
                <div class="card-slider container new-slider">
                    <div class="card-slider__wrapper new-slider__wrapper">
                        @foreach ($popproducts as $product)
                            <div class="card-slider__slide new-slider__slide card"
                                data-product-jsonld="products-{{ $loop->index }}">
                                <a draggable="false"
                                    href="{{ route('product', ['product' => $product->seo_id !== null && $product->seo_id !== '' ? $product->seo_id : $product->id]) }}">
                                    @php
                                        $mainMedia = $product->media->firstWhere('type', 'main');
                                    @endphp
                                    @if ($mainMedia)
                                        <img title="{{ $product->name }}" loading="eager" class="card-image"
                                            src="/{{ $mainMedia->path }}{{ $mainMedia->name }}"
                                            alt="{{ $product->name }}">
                                    @else
                                        <img title="Default image" loading="eager" class="card-image"
                                            src="/images/store/default/default300.webp" alt="something wrong">
                                    @endif
                                </a>
                                @livewire(
                                    'product-wishlist-button',
                                    [
                                        'productId' => $product->id,
                                        'class' => 'card__action',
                                        'is_in_wishlist' => $this->isInWishlist($product->id),
                                    ],
                                    key('popw' . $product->id)
                                )
                                <?php
                                $price = null;
                                $discount = false;

                                if ($product->product_prices->count() != 0) {
                                    $price = number_format($product->product_prices->first()->value, 2, $decimal, $mill);
                                    $discount = $product->product_prices->first()->discount != 0 ? true : false;
                                }
                                ?>

                                @if ($price)
                                    @if (($product->quantity < app('global_low_stock') && $product->quantity > 0) || $product->low_stock)
                                        <p
                                            class="card-status @if ($discount) save-secondary
          @else
             save @endif ">
                                            @if (app()->has('label_product_status_stock'))
                                                {!! app('label_product_status_stock') !!}
                                            @endif
                                        </p>
                                        @if ($discount)
                                            <p class="card-status save">
                                                -{{ $product->product_prices->first()->discount }}%
                                            </p>
                                        @endif
                                    @elseif($product->quantity <= 0 && !$product->preorder)
                                        <p class="card-status save">
                                            @if (app()->has('label_product_status_indisponible'))
                                                {!! app('label_product_status_indisponible') !!}
                                            @endif
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
                                        @if (app()->has('label_product_status_coming_soon'))
                                            {!! app('label_product_status_coming_soon') !!}
                                        @endif
                                    </p>
                                @endif
                                <div class="card-info">
                                    <div class="card-text">
                                        <h2 class="card-title"><a style="text-decoration: none; font-weight:500"
                                                href="{{ route('product', ['product' => $product->seo_id !== null && $product->seo_id !== '' ? $product->seo_id : $product->id]) }}">{{ $product->name }}</a>
                                        </h2>

                                        @php
                                            if (
                                                app()->has('global_cache_data') &&
                                                app('global_cache_data') === 'true'
                                            ) {
                                                $primaryCategory = $product->product_categories
                                                    ->where('primary_category', true)
                                                    ->first();
                                            } else {
                                                $primaryCategory = $product->product_categories->first();
                                            }
                                        @endphp

                                        @if ($primaryCategory && $primaryCategory->category)
                                            <a class="categorylink"
                                                href="{{ route('products', ['categorySlug' => $primaryCategory->category->seo_id !== null && $primaryCategory->category->seo_id !== '' ? $primaryCategory->category->seo_id : $primaryCategory->category->id]) }}">
                                                {{ $primaryCategory->category->short_description }}
                                            </a>
                                        @endif
                                        <p class="card-price">
                                            @if ($discount)
                                                <span class="card-price discount">
                                                    @if ($product->product_prices->first())
                                                        {{ $price }}
                                                        @if (app()->has('global_currency_primary_symbol'))
                                                            {!! app('global_currency_primary_symbol') !!}
                                                        @endif
                                                    @endif
                                                </span>
                                                <span class="card-price oldprice">
                                                    {{ number_format($product->product_prices->first()->value_no_discount, 2, $decimal, $mill) }}
                                                    @if (app()->has('global_currency_primary_symbol'))
                                                        {!! app('global_currency_primary_symbol') !!}
                                                    @endif
                                                </span>
                                            @else
                                                <span>
                                                    @if ($product->product_prices->first())
                                                        {{ $price }}
                                                        @if (app()->has('global_currency_primary_symbol'))
                                                            {!! app('global_currency_primary_symbol') !!}
                                                        @endif
                                                    @endif
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    @if ($price)
                                        @livewire('add-to-cart-button', ['product' => $product], key('pop' . $product->id))
                                    @else
                                        <button class="card-button-disabled" aria-label="Disabled Add to cart button">
                                            @if (app()->has('label_add_to_cart_button_indisponibil'))
                                                {!! app('label_add_to_cart_button_indisponibil') !!}
                                            @endif
                                        </button>
                                    @endif
                                    <div style="display: none" class="dlv">
                                        <span class="dlv_name"> {{ $product->name }}</span>
                                        <span class="dlv_price">{{ $price }}</span>
                                        <span class="dlv_currency">
                                            @if (app()->has('global_currency_primary_name'))
                                                {!! app('global_currency_primary_name') !!}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div style="display: none" class="json-ld-data"
                                    data-product-json='@json($product)'></div>
                            </div>
                        @endforeach
                    </div>
                    <button class="card-slider__button new-slider__button prev"
                        aria-label="Previous card slider button">
                        <svg>
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <button class="card-slider__button new-slider__button next" aria-label="Next card slider button">
                        <svg>
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>
            </section>
        @endif

        @if ($newproducts->isNotEmpty())
         <section>
                <div class="section__header container">
                    <h2 class="section__title">
                        @if (app()->has('label_mainpage_isnewproducts_slider_title'))
                            {!! app('label_mainpage_isnewproducts_slider_title') !!}
                        @endif
                        </h1>
                        <p class="section__text">
                            @if (app()->has('label_mainpage_isnewproducts_slider_description'))
                                {!! app('label_mainpage_isnewproducts_slider_description') !!}
                            @endif
                        </p>
                </div>
            </section>
            <section id="lastseenSlider" class="related__slider container">
            <div class="related__navigation">
                <button class="related__btnlast prev" aria-label="Previous related slider">
                    <svg>
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <button class="related__btnlast next" aria-label="Next related slider">
                    <svg>
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>

            </div>
            <div class="related__wrapperlast">
                @foreach ($newproducts as $product)
                    <div class="card product" style="width: 100%;">
                        <a style="width: 100%"
                            href="{{ route('product', ['product' => $product->seo_id !== null && $product->seo_id !== '' ? $product->seo_id : $product->id]) }}">

                            @if ($product->media->first() != null)
                                <img loading="eager" width="300" height="300" class="card-image"
                                    src="/{{ $product->media->first()->path }}{{ $product->media->first()->name }}"
                                    alt="{{ $product->media->first()->name }} {{ $product->name }}">
                            @else
                                <img loading="eager" width="300" height="300" class="card-image"
                                    src="/images/store/default/default300.webp" alt="something wrong">
                            @endif
                        </a>
                        @livewire('product-wishlist-button', ['productId' => $product->id, 'class' => 'card__action', 'is_in_wishlist' => $this->isInWishlist($product->id)], key('lastw' . $product->id))
                        <?php if ($product->product_prices->count() != 0) {
                            $price = number_format($product->product_prices->first()->value, 2, $decimal, $mill);
                            $discount = $product->product_prices->first()->discount != 0 ? true : false;
                        } else {
                            $price = null;
                            $discount = false;
                        }
                        ?>
                        @if ($price)
                            @if (($product->quantity < app('global_low_stock') && $product->quantity > 0) || $product->low_stock)
                                <p
                                    class="card-status @if ($discount) save-secondary
          @else
             save @endif ">
                                    @if (app()->has('label_product_status_stock'))
                                        {!! app('label_product_status_stock') !!}
                                    @endif
                                </p>
                                @if ($discount)
                                    <p class="card-status save">
                                        -{{ $product->product_prices->first()->discount }}%
                                    </p>
                                @endif
                            @elseif($product->quantity <= 0 && !$product->preorder)
                                <p class="card-status save">
                                    @if (app()->has('label_product_status_indisponible'))
                                        {!! app('label_product_status_indisponible') !!}
                                    @endif
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
                                @if (app()->has('label_product_status_coming_soon'))
                                    {!! app('label_product_status_coming_soon') !!}
                                @endif
                            </p>
                        @endif
                        <div class="card-info">
                            <div class="card-text">
                                <h2><a style="text-decoration: none; font-weight:500"
                                        href="{{ route('product', ['product' => $product->seo_id !== null && $product->seo_id !== '' ? $product->seo_id : $product->id]) }}">{{ $product->name }}</a>
                                </h2>
                                @php
                                    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
                                        $primaryCategory = $product->product_categories
                                            ->where('primary_category', true)
                                            ->first();
                                    } else {
                                        $primaryCategory = $product->product_categories->first();
                                    }
                                @endphp

                                @if ($primaryCategory && $primaryCategory->category)
                                    <a class="categorylink"
                                        href="{{ route('products', ['categorySlug' => $primaryCategory->category->seo_id !== null && $primaryCategory->category->seo_id !== '' ? $primaryCategory->category->seo_id : $primaryCategory->category->id]) }}">
                                        {{ $primaryCategory->category->short_description }}
                                    </a>
                                @endif

                                <p class="card-price">
                                    @if ($discount)
                                        <span class="card-price discount">
                                            @if ($product->product_prices->first())
                                                {{ $price }}
                                                @if (app()->has('global_currency_primary_symbol'))
                                                    {!! app('global_currency_primary_symbol') !!}
                                                @endif
                                            @endif
                                        </span>
                                        <span class="card-price oldprice">
                                            {{ number_format($product->product_prices->first()->value_no_discount, 2, $decimal, $mill) }}
                                            @if (app()->has('global_currency_primary_name'))
                                                {!! app('global_currency_primary_name') !!}
                                            @endif
                                        </span>
                                    @else
                                        <span>
                                            @if ($product->product_prices->first())
                                                {{ $price }}
                                                @if (app()->has('global_currency_primary_symbol'))
                                                    {!! app('global_currency_primary_symbol') !!}
                                                @endif
                                            @endif
                                        </span>
                                    @endif

                                </p>
                            </div>
                            @if ($price)
                                @livewire('add-to-cart-button', ['product' => $product], key('last' . $product->id))
                            @else
                                <button class="card-button-disabled" aria-label="Disabled Add to cart button">
                                    @if (app()->has('label_add_to_cart_button_indisponibil'))
                                        {!! app('label_add_to_cart_button_indisponibil') !!}
                                    @endif
                                </button>
                            @endif
                        </div>
                        <div style="display: none" class="dlv">
                            <span class="dlv_name">{{ $product->name }}</span>
                            <span class="dlv_price">{{ $price }}</span>
                            <span class="dlv_currency">
                                @if (app()->has('global_currency_primary_name'))
                                    {!! app('global_currency_primary_name') !!}
                                @endif
                            </span>
                        </div>
                        <div style="display: none" class="json-ld-data"
                            data-product-json='@json($product)'></div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        <!---------------------- Support Center -------------------->
        <x-support />
    </main>
    <script src="/script/store/main.js" defer></script>
    <script>
        document.addEventListener("livewire:load", function() {
            injectJsonLd();

            Livewire.hook("message.processed", (message, component) => {
                injectJsonLd();
            });

            function injectJsonLd() {
                document.querySelectorAll('.json-ld-data').forEach((element, index) => {
                    const productData = element.dataset.productJson;
                    let product;

                    try {
                        product = JSON.parse(productData);
                    } catch (e) {
                        console.warn("Invalid JSON in .json-ld-data", e);
                        return;
                    }

                    const currencyElement = document.querySelector('.dlv_currency');
                    const currency = currencyElement ? currencyElement.textContent.trim() : 'EUR';

                    const price = product.product_prices?.[0]?.value || '0';
                    const ratingValue = product.reviews?.[0]?.value || '0';
                    const reviewCount = product.reviews?.[0]?.count || '0';

                    const media = product.media?.[0] ?
                        `${window.location.origin}/${product.media[0].path}${product.media[0].name}` :
                        `${window.location.origin}/images/store/default/default300.webp`;

                    const description = product.long_description ?
                        product.long_description.replace(/(<([^>]+)>)/gi, "") :
                        "";

                    const seoUrl = `${window.location.origin}/product/${product.seo_id || product.id}`;

                    const existingScript = document.getElementById(`jsonld-product-${product.id}`);
                    if (existingScript) {
                        existingScript.remove();
                    }

                    if (price !== '0') {
                        const jsonLd = {
                            "@context": "https://schema.org/",
                            "@type": "Product",
                            "name": product.name,
                            "image": media,
                            "description": description,
                            "brand": {
                                "@type": "Brand",
                                "name": product.brand
                            },
                            "sku": product.sku,
                            "offers": {
                                "@type": "Offer",
                                "url": seoUrl,
                                "priceCurrency": currency,
                                "price": price,
                                "availability": "https://schema.org/InStock",
                                "priceValidUntil": product.end_date || "2030-12-31"
                            }
                        };

                        if (ratingValue !== '0' && reviewCount !== '0') {
                            jsonLd.aggregateRating = {
                                "@type": "AggregateRating",
                                "ratingValue": ratingValue,
                                "reviewCount": reviewCount
                            };
                            jsonLd.review = {
                                "@type": "Review",
                                "reviewRating": {
                                    "@type": "Rating",
                                    "ratingValue": ratingValue,
                                    "bestRating": "5"
                                },
                                "author": {
                                    "@type": "Person",
                                    "name": "anonim"
                                }
                            };
                        }

                        const script = document.createElement("script");
                        script.type = "application/ld+json";
                        script.id = `jsonld-product-${product.id}`;
                        script.textContent = JSON.stringify(jsonLd);
                        document.head.appendChild(script);
                    }
                });
            }
        });
    </script>


</div>
