<div>
 <main>
  <!---------------------- Slider Images --------------------->
  @if (!$slideritems->isEmpty())
   <div class="main-slider">
    <div class="main-slider__wrapper">
     @foreach ($slideritems as $item)
      {{-- -- Modelul de schimb de imagini pe slider la rezolutie -- --}}
      <a class="main-slider__slide"
       href="{{ route('products', ['categorySlug' => $item->seo_id !== null && $item->seo_id !== '' ? $item->seo_id : $item->id]) }}"
       draggable="false">
       <picture>
        @if ($item->media != null)
         {{-- Default (Desktop) --}}
         @if ($item->media->where('sequence', 2)->first() != null)
          <source media="(min-width: 992px)" sizes="(min-width: 992px) 50vw"
           srcset="/{{ $item->media->where('sequence', 2)->first()->path }}{{ $item->media->where('sequence', 2)->first()->name }}"
           loading="eager" fetchpriority="high" height="{{ $item->media->where('sequence', 2)->first()->height }}"
           width="{{ $item->media->where('sequence', 2)->first()->width }}">
         @else
          <source media="(min-width: 992px)" sizes="(min-width: 992px) 50vw" srcset="/images/store/default/default.webp"
           loading="eager" fetchpriority="high">
         @endif
         {{-- Tablet Picture --}}
         @if ($item->media->where('sequence', 3)->first() != null)
          <source title="{{ $item->media->where('sequence', 3)->first()->name }}" media="(min-width: 576px)"
           sizes="(min-width: 576px) 80vw"
           srcset="/{{ $item->media->where('sequence', 3)->first()->path }}{{ $item->media->where('sequence', 3)->first()->name }}"
           loading="eager" fetchpriority="high" height="{{ $item->media->where('sequence', 3)->first()->height }}"
           width="{{ $item->media->where('sequence', 3)->first()->width }}">
         @elseif ($item->media->where('sequence', 2)->first() != null)
          <source title="{{ $item->media->where('sequence', 2)->first()->name }}" media="(min-width: 576px)"
           sizes="(min-width: 576px) 80vw"
           srcset="/{{ $item->media->where('sequence', 2)->first()->path }}{{ $item->media->where('sequence', 2)->first()->name }}"
           loading="eager" fetchpriority="high" height="{{ $item->media->where('sequence', 2)->first()->height }}"
           width="{{ $item->media->where('sequence', 2)->first()->width }}">
         @else
          <source title="Default image" media="(min-width: 576px)" sizes="(min-width: 576px) 80vw"
           srcset="/images/store/default/default640.webp" loading="eager" fetchpriority="high">
         @endif
         {{-- Mobile Picture --}}
         @if ($item->media->where('sequence', 4)->first() != null)
          <img title="{{ $item->name }}" sizes="100vw" alt="{{ $item->name }}"
           src="/{{ $item->media->where('sequence', 4)->first()->path }}{{ $item->media->where('sequence', 4)->first()->name }}"
           loading="eager" fetchpriority="high" height="{{ $item->media->where('sequence', 4)->first()->height }}"
           width="{{ $item->media->where('sequence', 4)->first()->width }}">
         @elseif ($item->media->where('sequence', 3)->first() != null)
          <img title="{{ $item->name }}" sizes="100vw" alt="{{ $item->name }}"
           src="/{{ $item->media->where('sequence', 3)->first()->path }}{{ $item->media->where('sequence', 3)->first()->name }}"
           loading="eager" fetchpriority="high" height="{{ $item->media->where('sequence', 3)->first()->height }}"
           width="{{ $item->media->where('sequence', 3)->first()->width }}">
         @elseif ($item->media->where('sequence', 2)->first() != null)
          <img title="{{ $item->name }}" sizes="100vw" alt="{{ $item->name }}"
           src="/{{ $item->media->where('sequence', 2)->first()->path }}{{ $item->media->where('sequence', 2)->first()->name }}"
           loading="eager" fetchpriority="high" height="{{ $item->media->where('sequence', 2)->first()->height }}"
           width="{{ $item->media->where('sequence', 2)->first()->width }}">
         @else
          <img title="Default image" src="/images/store/default/default300.webp" alt="something wrong">
         @endif
        @else
         <img title="Default image" src="/images/store/default/default300.webp" alt="something wrong">
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
       <div class="card-slider__slide new-slider__slide card" data-product-jsonld="products-{{ $loop->index }}">
        <a draggable="false"
         href="{{ route('product', ['product' => $product->seo_id !== null && $product->seo_id !== '' ? $product->seo_id : $product->id]) }}">
         @php
          $mainMedia = $product->media->firstWhere('type', 'main');
         @endphp
         @if ($mainMedia)
          <img title="{{ $product->name }}" loading="eager" class="card-image"
           src="/{{ $mainMedia->path }}{{ $mainMedia->name }}" alt="{{ $product->name }}">
         @else
          <img title="Default image" loading="eager" class="card-image" src="/images/store/default/default300.webp"
           alt="something wrong">
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
         @if ($product->quantity < app('global_low_stock') && $product->quantity > 0)
          <p class="card-status save">
           @if (app()->has('label_product_status_stock'))
            {!! app('label_product_status_stock') !!}
           @endif
          </p>
          @if ($discount)
           <p class="card-status save-secondary">
            -{{ $product->product_prices->first()->discount }}%
           </p>
          @endif
         @elseif($product->quantity <= 0 && (app()->has('global_preorder') && app('global_preorder') != 'true'))
          <p class="card-status out">
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
           if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
               $primaryCategory = $product->product_categories->where('primary_category', true)->first();
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
        <div style="display: none" class="json-ld-data" data-product-json='@json($product)'></div>
       </div>
      @endforeach
     </div>
     <button class="card-slider__button new-slider__button prev" aria-label="Previous card slider button">
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
   <section>
    <div class="card-slider container popular-slider">
     <div class="card-slider__wrapper popular-slider__wrapper">
      @foreach ($newproducts as $product)
       <div class="card-slider__slide popular-slider__slide card"
        data-product-jsonld="newproducts-{{ $loop->index }}">
        <a draggable="false"
         href="{{ route('product', ['product' => $product->seo_id !== null && $product->seo_id !== '' ? $product->seo_id : $product->id]) }}">
         @php
          $mainMedia = $product->media->firstWhere('type', 'main');
         @endphp
         @if ($mainMedia)
          <img title="{{ $product->name }}, {{ $product->short_description }}" loading="eager" class="card-image"
           src="/{{ $mainMedia->path }}{{ $mainMedia->name }}" alt="{{ $product->name }}">
         @else
          <img title="Default image" loading="eager" class="card-image" src="/images/store/default/default300.webp"
           alt="something wrong">
         @endif
        </a>
        @livewire(
            'product-wishlist-button',
            [
                'productId' => $product->id,
                'class' => 'card__action',
                'is_in_wishlist' => $this->isInWishlist($product->id),
            ],
            key('neww' . $product->id)
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
         @if ($product->quantity < app('global_low_stock') && $product->quantity > 0)
          <p class="card-status out">
           @if (app()->has('label_product_status_stock'))
            {!! app('label_product_status_stock') !!}
           @endif
          </p>
          @if ($discount)
           <p class="card-status save-secondary">
            -{{ $product->product_prices->first()->discount }}%
           </p>
          @endif
         @elseif($product->quantity <= 0 && (app()->has('global_preorder') && app('global_preorder') != 'true'))
          <p class="card-status out">
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
           if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
               $primaryCategory = $product->product_categories->where('primary_category', true)->first();
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
          @livewire('add-to-cart-button', ['product' => $product], key('new' . $product->id))
         @else
          <button class="card-button-disabled" aria-label="Disabled Add to cart button">
           @if (app()->has('label_add_to_cart_button_indisponibil'))
            {!! app('label_add_to_cart_button_indisponibil') !!}
           @endif
          </button>
         @endif
         <div style="display: none" class="dlv">
          <span class="dlv_name">{{ $product->name }}</span>
          <span class="dlv_price">{{ $price }}</span>
          <span class="dlv_currency">
           @if (app()->has('global_currency_primary_name'))
            {!! app('global_currency_primary_name') !!}
           @endif
          </span>
         </div>

        </div>
        <div style="display: none" class="json-ld-data" data-product-json='@json($product)'></div>
       </div>
      @endforeach
     </div>
     <button class="popular-slider__button card-slider__button prev" aria-label="Previous card slider button">
      <svg>
       <polyline points="15 18 9 12 15 6"></polyline>
      </svg>
     </button>
     <button class="popular-slider__button card-slider__button next" aria-label="Next card slider button">
      <svg>
       <polyline points="9 18 15 12 9 6"></polyline>
      </svg>
     </button>
    </div>
   </section>
  @endif

  <!---------------------- Support Center -------------------->
  <x-support />
 </main>
 <script>
  document.addEventListener("livewire:load", function() {
   injectJsonLd();

   function injectJsonLd() {
    let existingScripts = document.querySelectorAll('script[type="application/ld+json"]');
    existingScripts.forEach(script => script.remove());

    let jsonLdElements = document.querySelectorAll('.json-ld-data');
    jsonLdElements.forEach(element => {
     let productData = element.dataset.productJson;
     let product = JSON.parse(productData);

     let currencyElement = document.querySelector('.dlv_currency');
     let price = (product.product_prices && product.product_prices.length > 0) ?
      `${product.product_prices[0].value}` : `0`;
     let ratingValue = (product.reviews && product.reviews.length > 0) ?
      `${product.reviews[0].value}` : `0`;
     let reviewCount = (product.reviews && product.reviews.length > 0) ?
      `${product.reviews[0].count}` : `0`;
     let currency = currencyElement.textContent.trim();
     let media = (product.media && product.media.length > 0) ?
      `${window.location.origin}/${product.media[0].path}${product.media[0].name}` :
      `${window.location.origin}/images/store/default/default300.webp`;
     if (price != '0' || (ratingValue != '0') && (reviewCount != 0)) {
      let jsonLd = {
       "@context": "https://schema.org/",
       "@type": "Product",
       "name": product.name,
       "image": media,
       "description": product.long_description.replace(/(<([^>]+)>)/gi, ""),
       "brand": {
        "@type": "Brand",
        "name": product.brand
       },
       "sku": product.sku,
       "offers": {
        "@type": "Offer",
        "url": `${window.location.origin}/product/${product.seo_id || product.id}`,
        "priceCurrency": currency,
        "price": price,
        "availability": `https://schema.org/InStock`,
        "priceValidUntil": product.end_date,
        "hasMerchantReturnPolicy": {
         "value": true
        },
        "shippingDetails": {
         "type": "FreeShipping",
         "price": "0"
        },
        "aggregateRating": {
         "@type": "AggregateRating",
         "ratingValue": ratingValue,
         "reviewCount": reviewCount
        },
        "review": {
         "@type": "Review",
         "reviewRating": {
          "@type": "Rating",
          "ratingValue": ratingValue,
          "bestRating": 5
         },
         "author": {
          "@type": "Person",
          "name": "anonim"
         }
        },
       }
      };

      let script = document.createElement('script');
      script.type = 'application/ld+json';
      script.textContent = JSON.stringify(jsonLd);
      document.head.appendChild(script);
     }
    });
   }
  });
 </script>
 <script src="/script/store/main.js" defer></script>
</div>
