<div>
 <!------------------------Breadcrumbs----------------------->
 <ol class="breadcrumbs container">
  <li>
   <a class="breadcrumbs__link" href="{{ url('/') }}">
    @if (app()->has('label_breadcrumbs_home_page'))
     {!! app('label_breadcrumbs_home_page') !!}
    @endif
   </a>
  </li>
  <li>
   <a class="breadcrumbs__link" href="{{ url('/search') }}">
    @if (app()->has('label_breadcrumbs_search'))
     {!! app('label_breadcrumbs_search') !!}
    @endif
   </a>
  </li>
 </ol>
 <!---------------------------------------------------------->
 <section class="controls container controls--search">
  <input class="controls__search" type="text" maxlength="100" autocomplete="off" name="search" id="search"
   wire:model.live.debounce.500ms="search"
   placeholder="@if (app()->has('label_placeholder_search')) {!! app('label_placeholder_search') !!} @endif">
  <h2 class="section__title">
   @if (app()->has('label_search_title'))
    {!! app('label_search_title') !!}
   @endif
  </h2>
  <div>
   <button class="tab__button tab__button--long @if ($showproducts) active @endif"
    wire:click="toggle('products')">
    @if (app()->has('label_search_product_element'))
     {!! app('label_search_product_element') !!}
     @endif @if ($products->isNotEmpty())
      ({{ $products->total() }})
     @else
      (0)
     @endif
   </button>
   <button class="tab__button tab__button--long @if ($showcategories) active @endif"
    wire:click="toggle('categories')">
    @if (app()->has('label_search_category_element'))
     {!! app('label_search_category_element') !!}
     @endif @if ($categories->isNotEmpty())
      ({{ $categories->total() }})
     @else
      (0)
     @endif
   </button>
  </div>
 </section>
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
 @if ($showproducts)
  <section class="catalogue container">
   @if ($products->isEmpty())
    <p>
     @if (app()->has('label_message_no_elements'))
      {!! app('label_message_no_elements') !!}
     @endif
    </p>
   @else
    @foreach ($products as $index => $product)
     <div class="product">
      <div @if ($loop->last) id="last_record" @endif class="card">
       <a
        href="{{ route('product', ['product' => $product->seo_id !== null && $product->seo_id !== '' ? $product->seo_id : $product->id]) }}">
        @if ($product->media->where('type', 'main')->first() != null)
         <img title="{{ $product->name }}, {{ $product->short_description }}" loading="eager" class="card-image"
          src="/{{ $product->media->where('type', 'main')->first()->path }}{{ $product->media->where('type', 'main')->first()->name }}"
          alt="{{ $product->name }}">
        @else
         <img title="Default image" loading="eager" class="card-image" src="/images/store/default/default300.webp"
          alt="something wrong">
        @endif
       </a>
       <?php if ($product->product_prices->count() != 0) {
           $price = number_format($product->product_prices->first()->value, 2, $decimal, $mill);
           $discount = $product->product_prices->first()->discount != 0 ? true : false;
       } else {
           $price = null;
           $discount = false;
       }
       ?>

       @if ($price)
        {{-- Out- negru // save - rosu --}}
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
        @elseif($product->quantity == 0 && (app()->has('global_preorder') && app('global_preorder') != 'true'))
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
       @livewire(
           'product-wishlist-button',
           [
               'productId' => $product->id,
               'class' => 'card__action',
               'is_in_wishlist' => $product->wishlists->isNotEmpty(),
           ],
           key($product->id)
       )

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
         <div style="display: none">
          <span class="dlv_name">{{ $product->name }}</span>
          <span class="dlv_price">{{ $price }}</span>
          <span class="dlv_currency">
           @if (app()->has('global_currency_primary_name'))
            {!! app('global_currency_primary_name') !!}
           @endif
          </span>
         </div>
        </div>
        @if ($price)
         @livewire('add-to-cart-button', ['product' => $product], key($product->id . $index))
        @else
         <button class="card-button-disabled" aria-label="Disabled Add to cart button">
          @if (app()->has('label_add_to_cart_button_indisponibil'))
           {!! app('label_add_to_cart_button_indisponibil') !!}
          @endif
         </button>
        @endif
       </div>
      </div>
     </div>
    @endforeach
    <x-lazy />
   @endif
  </section>

 @endif

 @if ($showcategories)
  <section class="catalogue container catalogue--categories">
   @if ($categories->isEmpty())
    <p>
     @if (app()->has('label_message_no_elements'))
      {!! app('label_message_no_elements') !!}
     @endif
    </p>
   @else
    @foreach ($categories as $index => $category)
     <div @if ($loop->last) id="last_record" @endif class="product">
      <a class="card card--category" role="listitem"
       href="{{ route('products', ['categorySlug' => $category->seo_id !== null && $category->seo_id !== '' ? $category->seo_id : $category->id]) }}">
       <div>
        @if ($category->media->first() != null)
         <img title="{{ strip_tags($category->name) }}" loading="eager" class="card-image"
          src="/{{ $category->media->first()->path }}{{ $category->media->first()->name }}"
          alt="{{ strip_tags($category->name) }}">
        @endif
       </div>
       <div class="card-info">
        <div class="card-text">
         <h3 class="card-title">{!! $category->name !!}</h2>
        </div>
        {!! $category->long_description !!}
       </div>
      </a>
     </div>
    @endforeach
    <x-lazy />
   @endif
  </section>
 @endif
 @if ($products->count() > $loadAmount || $categories->count() > $loadAmount)
  <section class="container">
   <button class="filter__apply" wire:click="loadMore" wire:loading.remove>Vezi mai mult!</button>
  </section>
 @endif

 <!---------------------------------------------------------->
 <script src="/script/store/catalog.js" defer></script>
</div>
