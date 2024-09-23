<div id="store-show-product">
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
 <ol class="breadcrumbs container">
  <li>
   <a class="breadcrumbs__link" href="{{ url('/') }}">
    @if (app()->has('label_breadcrumbs_home_page'))
     {!! app('label_breadcrumbs_home_page') !!}
    @endif
   </a>
  </li>
  @if (app()->has('global_show_on_breadcrumbs') && app('global_show_on_breadcrumbs') == 'true')
   <li>
    <a class="breadcrumbs__link" href="{{ url('/storeproducts') }}">
     @if (app()->has('label_breadcrumbs_allproducts'))
      {!! app('label_breadcrumbs_allproducts') !!}
     @endif
    </a>
   </li>
  @endif
  @if ($product->product_categories->isNotEmpty())
   @foreach ($product->getCategoryHierarchy() as $breadcrumb)
    <li>
     <a class="breadcrumbs__link" href="{{ route('products', ['categorySlug' => $breadcrumb['slug']]) }}">
      {{ $breadcrumb['name'] }}
     </a>
    </li>
   @endforeach
  @endif
  <li>
   <a
    href="{{ route('product', ['product' => $product->seo_id !== null && $product->seo_id !== '' ? $product->seo_id : $product->id]) }}"
    class="breadcrumbs__link">{{ $product->name }}</a>
  </li>
 </ol>
 <section class="product container">
  <!-------------------- Slider Product ------------------>
  <div class="product-slider">
   <div class="product-slider__center">

    <div class="product-slider__wrapper">
     @if ($product->media->count() != 0)
      @foreach ($product->media->where('type', 'full') as $media)
       <div class="product-slider__slide">
        <img width="550" height="550" loading="eager" src="/{{ $media->path }}{{ $media->name }}"
         data-name-alt="{{ $media->name }}{{ $product->name }}" alt="{{ $media->name }}{{ $product->name }}"
         data-img-src="/{{ $product->media->where('type', 'original')->where('sequence', $media->sequence)->first()->path }}{{ $product->media->where('type', 'original')->where('sequence', $media->sequence)->first()->name }}">
       </div>
      @endforeach
     @else
      <div class="product-slider__slide">
       <img loading="eager" src="/images/store/default/default.webp" data-img-src="/images/store/default/default.webp"
        alt="something wrong" data-name-alt="something wrong">
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
   <div class="product-slider__pagination--navigation">
    <button class="product-slider__pagination--button product-slider__pagination--prev disabled" aria-label="Previous">
     <svg>
      <polyline points="15 18 9 12 15 6"></polyline>
     </svg>
    </button>
    <div class="product-slider__pagination">
    </div>
    <button class="product-slider__pagination--button product-slider__pagination--next disabled" aria-label="Next">
     <svg>
      <polyline points="9 18 15 12 9 6"></polyline>
     </svg>
    </button>
   </div>
  </div>
  <!------------------ End Slider Product ---------------->
  <!------------------------------------------------------>
  <!-------------------- Modal Product ------------------>
  <div class="product-modal">
   <div class="product-modal__content"></div>
   <button class="product-modal__close">
    <svg>
     <polyline points="4 14 10 14 10 20"></polyline>
     <polyline points="20 10 14 10 14 4"></polyline>
     <line x1="14" y1="10" x2="21" y2="3"></line>
     <line x1="3" y1="21" x2="10" y2="14"></line>
    </svg>
   </button>
   <button class="product-modal__prev">
    <svg>
     <polyline points="15 18 9 12 15 6"></polyline>
    </svg>
   </button>
   <button class="product-modal__next">
    <svg>
     <polyline points="9 18 15 12 9 6"></polyline>
    </svg>
   </button>
   <span class="product-modal__count"></span>
  </div>
  <!------------------ End Modal Product ---------------->
  <!------------------------------------------------------>
  <!----------------------- Product details ---------------------->
  @livewire('product-details', ['product' => $product, 'wishlistItems' => $wishlistItems])
  <!--------------------- End Product details -------------------->
  <!------------------------------------------------------>
 </section>

 <section class="tab container">
  <div class="tab__top">
   <button class="tab__button active" onclick="switchTab(0)">
    @if (app()->has('label_pdp_description_tag'))
     {!! app('label_pdp_description_tag') !!}
    @endif
   </button>
   <button class="tab__button" onclick="switchTab(1)">
    @if (app()->has('label_pdp_details_tag'))
     {!! app('label_pdp_details_tag') !!}
    @endif
   </button>
  </div>
  <div class="tab__content active">
   <p class="tab__info">{!! $product->long_description !!}</p>
  </div>
  <div class="tab__content">
   <table class="tab__table">
    <thead>
     <tr>
      <th>
       @if (app()->has('label_pdp_specs_tag'))
        {!! app('label_pdp_specs_tag') !!}
       @endif
      </th>
      <th>
       @if (app()->has('label_pdp_description_tag'))
        {!! app('label_pdp_description_tag') !!}
       @endif
      </th>
     </tr>
    </thead>
    <tbody>
     @if ($product->product_specs->first() !== null)
      @foreach ($product->product_specs->sortBy('sequence') as $spec)
       <tr>
        <td>{{ $spec->spec->name }}</td>
        <td>{{ $spec->value }}</td>
       </tr>
      @endforeach
     @else
      <tr>
       <td colspan="2">
        @if (app()->has('label_pdp_specs_error'))
         {!! app('label_pdp_specs_error') !!}
        @endif
       </td>
      </tr>
     @endif
    </tbody>
   </table>
  </div>
 </section>

 <script>
  function switchTab(tabIndex) {
   const tabs = document.querySelectorAll('.tab__content');
   const buttons = document.querySelectorAll('.tab__button');

   tabs.forEach((tab, index) => {
    if (index === tabIndex) {
     tab.classList.add('active');
     buttons[index].classList.add('active');
    } else {
     tab.classList.remove('active');
     buttons[index].classList.remove('active');
    }
   });
  }
 </script>
 <h2></h2>
 <!---------------------------------------------------------->
 <!------------------- Section Description ------------------>
 @if ($product->related_product->filter(fn($item) => !is_null($item['product']))->isNotEmpty())
  <section>
   <div class="section__header container">
    <h2 class="section__title">Descoperă și alte opțiuni similare</h2>
    <p class="section__text">
     În căutarea perfectă? Explorează și alte propuneri care te-ar putea interesa.
     Descoperă produse similare, perfecte pentru gusturile tale și nevoile tale. În continuare, vei găsi
     opțiuni care completează gama noastră și care îți pot satisface preferințele. Alege cu încredere
     dintre
     aceste alternative și găsește exact ceea ce cauți.
    </p>
   </div>
  </section>
  <!----------------- End Section Description ---------------->
  <!---------------------------------------------------------->
  <!---------------------- Slider Cards ---------------------->
  <section id="relatedSlider" class="related__slider container">
   {{-- <div class="related__navigation"> --}}
   <button class="related__btn prev" aria-label="Previous related slider">
    <svg>
     <polyline points="15 18 9 12 15 6"></polyline>
    </svg>
   </button>
   <button class="related__btn next" aria-label="Next related slider">
    <svg>
     <polyline points="9 18 15 12 9 6"></polyline>
    </svg>
   </button>
   {{--
    </div> --}}
   <div class="related__wrapper">
    @foreach ($product->related_product->sortByDesc('product.popularity') as $product)
     @if (
         $product->product &&
             $product->product->active == true &&
             $product->product->end_date >= now()->format('Y-m-d') &&
             $product->product->start_date <= now()->format('Y-m-d'))
      <div class="card product" style="width: 100%;">
       <a style="width: 100%"
        href="{{ route('product', ['product' => $product->product->seo_id !== null && $product->product->seo_id !== '' ? $product->product->seo_id : $product->product->id]) }}">

        @if ($product->product->media->first() != null)
         <img loading="eager" width="300" height="300" class="card-image"
          src="/{{ $product->product->media->first()->path }}{{ $product->product->media->first()->name }}"
          alt="{{ $product->product->media->first()->name }} {{ $product->product->name }}">
        @else
         <img loading="eager" width="300" height="300" class="card-image"
          src="/images/store/default/default300.webp" alt="something wrong">
        @endif
       </a>
       @livewire('product-wishlist-button', ['productId' => $product->product->id, 'class' => 'card__action', 'is_in_wishlist' => $this->isInWishlist($product->product->id)], key($product->product->id))
       <?php if ($product->product->product_prices->count() != 0) {
           $price = number_format($product->product->product_prices->first()->value, 2, $decimal, $mill);
           $discount = $product->product->product_prices->first()->discount != 0 ? true : false;
       } else {
           $price = null;
           $discount = false;
       }
       ?>
       @if ($price)
        @if ($product->product->quantity < $quantity && $product->product->quantity > 0)
         <p class="card-status save">
          @if (app()->has('label_product_status_stock'))
           {!! app('label_product_status_stock') !!}
          @endif
         </p>
         @if ($discount)
          <p class="card-status save-secondary">
           -{{ $product->product->product_prices->first()->discount }}%
          </p>
         @endif
        @elseif($product->product->quantity == 0)
         <p class="card-status out">
          @if (app()->has('label_product_status_indisponible'))
           {!! app('label_product_status_indisponible') !!}
          @endif
         </p>
        @else
         @if ($discount)
          <p class="card-status save">
           -{{ $product->product->product_prices->first()->discount }}%
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
           href="{{ route('product', ['product' => $product->product->seo_id !== null && $product->product->seo_id !== '' ? $product->product->seo_id : $product->product->id]) }}">{{ $product->product->name }}</a>
         </h2>
         @php
          if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
              $primaryCategory = $product->product->product_categories->where('primary_category', true)->first();
          } else {
              $primaryCategory = $product->product->product_categories->first();
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
            @if ($product->product->product_prices->first())
             {{ $price }}
             @if (app()->has('global_currency_primary_symbol'))
              {!! app('global_currency_primary_symbol') !!}
             @endif
            @endif
           </span>
           <span class="card-price oldprice">
            {{ number_format($product->product->product_prices->first()->value_no_discount, 2, $decimal, $mill) }}
            @if (app()->has('global_currency_primary_name'))
             {!! app('global_currency_primary_name') !!}
            @endif
           </span>
          @else
           <span>
            @if ($product->product->product_prices->first())
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
         @livewire('add-to-cart-button', ['product' => $product->product], key($product->product->id))
        @else
         <a class="card-button-disabled" onclick="handleClick()" aria-label="Indisponibil">Indisponibil</a>
        @endif
       </div>
       <div style="display: none" class="dlv">
        <span class="dlv_name">{{ $product->product->name }}</span>
        <span class="dlv_price">{{ $price }}</span>
        <span class="dlv_currency">
         @if (app()->has('global_currency_primary_name'))
          {!! app('global_currency_primary_name') !!}
         @endif
        </span>
       </div>
       <div style="display: none" class="json-ld-data" data-product-json='@json($product->product)'></div>
      </div>
     @endif
    @endforeach
   </div>
  </section>
  <!---------------------- Support Center -------------------->
  <x-support />
 @endif
 <script>
  document.addEventListener("livewire:load", function() {
   injectJsonLd();
   Livewire.hook('message.processed', (message, component) => {
    injectJsonLd();
   });

   function injectJsonLd() {
    let existingScripts = document.querySelectorAll('script[type="application/ld+json"]');

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
     if (price != '0') {
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
 <script src="/script/store/product.js" defer></script>
</div>
