<div>
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
  <!-------------------If Category is appear------------------>
  @if ($category != null && $category->id != app('global_default_category'))
   @foreach ($category->getCategoryBreadcrumbs() as $breadcrumb)
    @if ($breadcrumb['name'] === $category->name)
     <li>
      <a class="breadcrumbs__link"
       href="{{ route('products', ['categorySlug' => $category->seo_id !== null && $category->seo_id !== '' ? $category->seo_id : $category->id]) }}">
       {!! $category->name !!}
      </a>
     </li>
    @else
     <li>
      <a class="breadcrumbs__link" href="{{ route('products', ['categorySlug' => $breadcrumb['slug']]) }}">
       {{ $breadcrumb['name'] }}
      </a>
     </li>
    @endif
   @endforeach
  @endif
 </ol>
 <!----------------------Categorie + detalii--------------------->
 @if ($category)
  <section class="section__header container">
   <h1 class="section__title">
    @if (!empty($category->short_description))
     {{ $category->short_description }}
    @else
     {!! $category->name !!}
    @endif
   </h1>
   <p class="section__text">
    {!! $category->long_description !!}
   </p>
  </section>
 @endif

 <!---------------------------Filter------------------------->
 <section class="controls container" id="productlist">
  <button class="controls__button" id="filterOpen" aria-label="Open filter button">
   <svg>
    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
   </svg>
  </button>
  <input class="controls__search" maxlength="100" type="text" name="search" id="search"
   wire:model.live.debounce.500ms="search" autocomplete="off"
   placeholder="@if (app()->has('label_placeholder_search')) {!! app('label_placeholder_search') !!} @endif">
  <button class="controls__button" id="sortOpen" aria-label="Open sort button">
   <svg>
    <line x1="21" y1="10" x2="7" y2="10"></line>
    <line x1="21" y1="6" x2="3" y2="6"></line>
    <line x1="21" y1="14" x2="3" y2="14"></line>
    <line x1="21" y1="18" x2="7" y2="18"></line>
   </svg>
  </button>
 </section>
 <!---------------------------- Display filters-------------------------->
 @if (!empty($selectedfilters))
  <section class="tag container">
   @foreach ($selectedfilters as $key => $specname)
    <button class="tag__button" wire:click="removeSpec('{{ $key }}', '{{ $specname }}')">
     {{ $specname }}: {{ $key }}
     <svg>
      <line x1="18" y1="6" x2="6" y2="18"></line>
      <line x1="6" y1="6" x2="18" y2="18"></line>
     </svg>
    </button>
   @endforeach
   <button class="tag__button" wire:click="clearall()" class="filter__applied--clear">
    @if (app()->has('label_remove_all_filters'))
     {!! app('label_remove_all_filters') !!}
    @endif
    <svg>
     <line x1="18" y1="6" x2="6" y2="18"></line>
     <line x1="6" y1="6" x2="18" y2="18"></line>
    </svg>
   </button>
  </section>
 @endif
 <!-------------------------Catalogue------------------------>
 <section class="catalogue container">
  @if ($products->isEmpty())
   <p>
    @if (app()->has('label_message_no_elements'))
     {!! app('label_message_no_elements') !!}
    @endif
   </p>
  @else
   @foreach ($products as $index => $product)

    @php
     if ($product->type == 'parent') {
         if ($product->variants->count() == 0) {
             continue;
         } else {
             if ($product->variants->where('default_variant', true)->first()) {
                 $element = $product->variants->where('default_variant', true)->first()->product;
             } else {
                 $element = $product->variants->first()->product;
             }
         }
     } else {
         $element = $product;
     }
    @endphp
    <div wire:key="product-{{ $element->id }}" class="product">
     <div @if ($loop->last) id="last_record" @endif class="card">
      <a
       href="{{ route('product', ['product' => $element->seo_id !== null && $element->seo_id !== '' ? $element->seo_id : $element->id]) }}">
       @if ($element->media->first() != null)
        <img title="{{ $product->name }}, {{ $product->short_description }}" loading="lazy" class="card-image"
         src="/{{ $element->media->first()->path }}{{ $element->media->first()->name }}" alt="{{ $element->name }}">
       @else
        <img title="Default image" loading="lazy" class="card-image" src="/images/store/default/default300.webp"
         alt="something wrong">
       @endif
      </a>


      @php
       if ($element->product_prices->count() != 0) {
           $price = number_format($element->product_prices->first()->value, 2, $decimal, $mill);
           $discount = $element->product_prices->first()->discount != 0 ? true : false;
       } else {
           $price = null;
           $discount = false;
       }
      @endphp
      @if ($price)
       {{-- Out- negru // save - rosu --}}
       @if ($element->quantity < app('global_low_stock') && $element->quantity > 0)
        <p class="card-status out">
         @if (app()->has('label_product_status_stock'))
          {!! app('label_product_status_stock') !!}
         @endif
        </p>
        @if ($discount)
         <p class="card-status save-secondary">
          -{{ $element->product_prices->first()->discount }}%
         </p>
        @endif
       @elseif($element->quantity <= 0 && (app()->has('global_preorder') && app('global_preorder') != 'true'))
        <p class="card-status out">
         @if (app()->has('label_product_status_indisponible'))
          {!! app('label_product_status_indisponible') !!}
         @endif
        </p>
       @else
        @if ($discount)
         <p class="card-status save">
          -{{ $element->product_prices->first()->discount }}%
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
      @livewire(
          'product-wishlist-button',
          [
              'productId' => $element->id,
              'class' => 'card__action',
              'is_in_wishlist' => $this->isInWishlist($element->id),
          ],
          key('w' . $element->id)
      )

      <div class="card-info">
       <div class="card-text">
        <h2 class="card-title"><a style="text-decoration: none; font-weight:500"
          href="{{ route('product', ['product' => $element->seo_id !== null && $element->seo_id !== '' ? $element->seo_id : $element->id]) }}">
          @if ($element->type === 'variant' && $category->accepted_items === 'parents')
           {{ $element->parent->name }}
          @else
           {{ $element->name }}
          @endif
         </a>
        </h2>
        @php
         if ($element->type === 'variant' && $category->accepted_items === 'parents') {
             $corectproduct = $element->parent;
         } else {
             $corectproduct = $element;
         }
         if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
             $primaryCategory = $corectproduct->product_categories->where('primary_category', true)->first();
         } else {
             $primaryCategory = $corectproduct->product_categories->first();
         }
        @endphp

        @if ($primaryCategory && $primaryCategory->category)
         <a class="categorylink"
          href="{{ route('products', ['categorySlug' => $primaryCategory->category->seo_id !== null && $primaryCategory->category->seo_id !== '' ? $primaryCategory->category->seo_id : $primaryCategory->category->id]) }}">
          {{ $primaryCategory->category->short_description }}
         </a>
        @endif
        <p class="card-price">
         {{-- label price from --}}
         @if (
             $category->display_variant_price == true &&
                 ($element->type == 'parent' || ($element->type === 'variant' && $category->accepted_items === 'parents')) &&
                 app()->has('global_variant_price_from') &&
                 app()->has('global_variant_add_to_cart') &&
                 app('global_variant_add_to_cart') === 'true' &&
                 app()->has('label_product_price_from') &&
                 app('global_variant_price_from') === 'true')
          {!! app('label_product_price_from') !!}
         @endif
         {{-- price --}}
         @if ($element->type == 'parent' || ($element->type === 'variant' && $category->accepted_items === 'parents'))
          @if (
              $category->display_variant_price == true &&
                  app()->has('global_variant_add_to_cart') &&
                  app('global_variant_add_to_cart') === 'true')
           @if ($discount)
            <span class="card-price discount">
             @if ($element->product_prices->first())
              {{ $price }}
              @if (app()->has('global_currency_primary_symbol'))
               {!! app('global_currency_primary_symbol') !!}
              @endif
             @endif
            </span>
            <span class="card-price oldprice">
             {{ number_format($element->product_prices->first()->value_no_discount, 2, $decimal, $mill) }}
             @if (app()->has('global_currency_primary_symbol'))
              {!! app('global_currency_primary_symbol') !!}
             @endif
            </span>
           @else
            <span>
             @if ($element->product_prices->first())
              {{ $price }}
              @if (app()->has('global_currency_primary_symbol'))
               {!! app('global_currency_primary_symbol') !!}
              @endif
             @endif
            </span>
           @endif
          @endif
         @else
          @if ($discount)
           <span class="card-price discount">
            @if ($element->product_prices->first())
             {{ $price }}
             @if (app()->has('global_currency_primary_symbol'))
              {!! app('global_currency_primary_symbol') !!}
             @endif
            @endif
           </span>
           <span class="card-price oldprice">
            {{ number_format($element->product_prices->first()->value_no_discount, 2, $decimal, $mill) }}
            @if (app()->has('global_currency_primary_symbol'))
             {!! app('global_currency_primary_symbol') !!}
            @endif
           </span>
          @else
           <span>
            @if ($element->product_prices->first())
             {{ $price }}
             @if (app()->has('global_currency_primary_symbol'))
              {!! app('global_currency_primary_symbol') !!}
             @endif
            @endif
           </span>
          @endif
         @endif
        </p>
        <div style="display: none">
         <span class="dlv_name">{{ $element->name }}</span>
         <span class="dlv_price">{{ $price }}</span>
         <span class="dlv_currency">
          @if (app()->has('global_currency_primary_name'))
           {!! app('global_currency_primary_name') !!}
          @endif
         </span>
        </div>
       </div>
       @if (
           ($element->type == 'parent' || ($element->type === 'variant' && $category->accepted_items === 'parents')) &&
               app()->has('global_variant_add_to_cart') &&
               app('global_variant_add_to_cart') === 'true' &&
               $price &&
               $category->display_variant_price == true)
        @livewire('add-to-cart-button', ['product' => $element], key('pro' . $element->id))
       @elseif(($price && $element->type == 'parent') || ($element->type === 'variant' && $category->accepted_items === 'parents'))
        <div class="card__button--wrapper">
         <button class="card__button">

          <a style="text-decoration: none"
           href="{{ route('product', ['product' => $element->seo_id !== null && $element->seo_id !== '' ? $element->seo_id : $element->id]) }}"
           class="card__button--text">
           @if (app()->has('label_show_parent'))
            {!! app('label_show_parent') !!}
           @endif
          </a>
         </button>
        </div>
       @elseif ($price)
        @livewire('add-to-cart-button', ['product' => $element], key('pro' . $element->id))
       @else
        <button class="card-button-disabled" aria-label="Disabled Add to cart button">
         @if (app()->has('label_add_to_cart_button_indisponibil'))
          {!! app('label_add_to_cart_button_indisponibil') !!}
         @endif
        </button>
       @endif
      </div>
     </div>
     <div style="display: none" class="json-ld-data" data-product-json='@json($element)'></div>
    </div>
   @endforeach
   @unless (app()->has('global_pagination') && app('global_pagination') === 'links')
    <x-lazy />
   @endunless
  @endif
 </section>
 <!-----------------------Load more---------------------->
 @if (app()->has('global_pagination') && app('global_pagination') === 'links')
  <section class="container">
   {{ $products->links() }}
  </section>
 @else
  @if ($products->total() >= $loadAmount)
   <section class="container">
    <button class="filter__apply" wire:click="loadMore" wire:loading.remove>Vezi mai mult!</button>
   </section>
  @endif
 @endif

 <!---------------------------Filters------------------------->
 <div class="filter" id="filterList" wire:ignore>
  <div class="filter__content" id="filterContent">
   <div class="filter__top">
    <button class="filter__apply" id="resetFilter" wire:click.prevent="clearall">
     @if (app()->has('label_remove_all_filters'))
      {!! app('label_remove_all_filters') !!}
     @endif
     <svg>
      <polyline points="23 4 23 10 17 10"></polyline>
      <polyline points="1 20 1 14 7 14"></polyline>
      <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
     </svg>
    </button>
    <button class="filter__reset" id="filterClose">
     <svg>
      <line x1="18" y1="6" x2="6" y2="18"></line>
      <line x1="6" y1="6" x2="18" y2="18"></line>
     </svg>
    </button>
   </div>
   <button class="filter__top filter__top--button" id="closeFilter">
    @if (app()->has('label_display_filters_results'))
     {!! app('label_display_filters_results') !!}
    @endif
    <span>{{ $products->total() }}</span>
   </button>
   <div class="filter__list">
    @foreach ($filtervalues as $values)
     <div class="dropfilter">
      <div class="dropfilter__button">
       <button class="dropfilter__open">
        <h4>{{ $values['spec'] }}</h4>
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </div>
      <div class="dropfilter__list">
       @foreach ($values['values'] as $value => $productData)
        @php
         $productsString = collect($productData['product_data'])
             ->map(function ($product) {
                 if (isset($product['parent_id'])) {
                     return implode('|', [$product['product_id'], $product['parent_id']]);
                 } else {
                     return implode([$product['product_id']]);
                 }
             })
             ->implode(',');
         $sanitizedValue = str_replace('.', ',', $value);
        @endphp
        <label class="dropfilter__link" for="{{ $sanitizedValue }}">
         <input type="checkbox"
          wire:model="queryfilters.{{ $values['spec'] }}.{{ $sanitizedValue }}.{{ $productsString }}"
          wire:change="applyFilter" id="{{ $sanitizedValue }}">
         <h4>{{ $value }}</h4>
        </label>
       @endforeach
      </div>
     </div>
    @endforeach


   </div>
  </div>
  <button id="filterClose" class="filter__close-modal"></button>
  <script>
   document.addEventListener('livewire:load', function() {
    Livewire.on('filtersApplied', function(selectedCount, totalCount) {
     const filterButton = document.querySelector('.filter__top--button span');
     filterButton.textContent = selectedCount > 0 ? selectedCount : totalCount;
    });
   });
  </script>
 </div>
 <!-------------------------Sorting----------------------->
 <div class="filter" id="sortList">
  <div class="filter__content" id="sortContent">
   <div class="filter__top">
    <div class="filter__text--long">
     @if (app()->has('label_sort_title'))
      {!! app('label_sort_title') !!}
     @endif
    </div>
    <button class="filter__reset" id="sortClose">
     <svg>
      <line x1="18" y1="6" x2="6" y2="18"></line>
      <line x1="6" y1="6" x2="18" y2="18"></line>
     </svg>
    </button>
   </div>
   {{-- orderby --}}
   <div class="filter__list">

    <input class="filter__input" wire:model="orderBy" type="radio" name="sort" value="best_selling"
     id="sort">
    <label class="filter__link sort__item" for="sort">
     <h4>
      @if (app()->has('label_sort_popularity'))
       {!! app('label_sort_popularity') !!}
      @endif
     </h4>
    </label>

    <input class="filter__input" wire:model="orderBy" type="radio" name="sort1" value="price_as"
     id="sort1">
    <label class="filter__link sort__item" for="sort1">
     <h4>
      @if (app()->has('label_sort_price_as'))
       {!! app('label_sort_price_as') !!}
      @endif
     </h4>
    </label>

    <input class="filter__input" wire:model="orderBy" type="radio" name="sort2" value="price_ds"
     id="sort2">
    <label class="filter__link sort__item" for="sort2">
     <h4>
      @if (app()->has('label_sort_price_ds'))
       {!! app('label_sort_price_ds') !!}
      @endif
     </h4>
    </label>

    <input class="filter__input" wire:model="orderBy" type="radio" name="sort3" value="quantity"
     id="sort3">
    <label class="filter__link sort__item" for="sort3">
     <h4>
      @if (app()->has('label_sort_quantity_ds'))
       {!! app('label_sort_quantity_ds') !!}
      @endif
     </h4>
    </label>

    <input class="filter__input" wire:model="orderBy" type="radio" name="sort8" value="quantity_as"
     id="sort8">
    <label class="filter__link sort__item" for="sort8">
     <h4>
      @if (app()->has('label_sort_quantity_as'))
       {!! app('label_sort_quantity_as') !!}
      @endif
     </h4>
    </label>

    <input class="filter__input" wire:model="orderBy" type="radio" name="sort4" value="name_az"
     id="sort4">
    <label class="filter__link sort__item" for="sort4">
     <h4>
      @if (app()->has('label_sort_name_az'))
       {!! app('label_sort_name_az') !!}
      @endif
     </h4>
    </label>

    <input class="filter__input" wire:model="orderBy" type="radio" name="sort5" value="name_za"
     id="sort5">
    <label class="filter__link sort__item" for="sort5">
     <h4>
      @if (app()->has('label_sort_name_za'))
       {!! app('label_sort_name_za') !!}
      @endif
     </h4>
    </label>

    <input class="filter__input" wire:model="orderBy" type="radio" name="sort6" value="date_old_new"
     id="sort6">
    <label class="filter__link sort__item" for="sort6">
     <h4>
      @if (app()->has('label_sort_date_ds'))
       {!! app('label_sort_date_ds') !!}
      @endif
     </h4>
    </label>

    <input class="filter__input" wire:model="orderBy" type="radio" name="sort7" value="date_new_old"
     id="sort7">
    <label class="filter__link sort__item" for="sort7">
     <h4>
      @if (app()->has('label_sort_date_as'))
       {!! app('label_sort_date_as') !!}
      @endif
     </h4>
    </label>
   </div>
  </div>
 </div>
 <!---------------------- Support Center -------------------->
 <x-support />
 <script>
  document.addEventListener("livewire:load", function() {
   injectJsonLd();
   Livewire.hook('message.processed', (message, component) => {
    injectJsonLd();
   });

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
 <script src="/script/store/catalog.js" defer></script>
</div>
