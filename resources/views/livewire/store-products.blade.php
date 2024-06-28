<div>
 <!------------------------Breadcrumbs----------------------->
 <div class="breadcrumbs container">
  <a class="breadcrumbs__link" href="{{ url('/') }}">
   @if (app()->has('label_breadcrumbs_home_page'))
    {!! app('label_breadcrumbs_home_page') !!}
   @endif
  </a>
  @if (app()->has('global_show_on_breadcrumbs') && app('global_show_on_breadcrumbs') == 'true')
   <a class="breadcrumbs__link" href="{{ url('/storeproducts') }}">
    @if (app()->has('label_breadcrumbs_allproducts'))
     {!! app('label_breadcrumbs_allproducts') !!}
    @endif
   </a>
  @endif
  <!-------------------If Category is appear------------------>
  @if ($category != null && $category->id != app('global_default_category'))
   @foreach ($category->getCategoryBreadcrumbs() as $breadcrumb)
    @if ($breadcrumb['name'] === $category->name)
     <a class="breadcrumbs__link"
      href="{{ route('products', ['categorySlug' => $category->seo_id !== null && $category->seo_id !== '' ? $category->seo_id : $category->id]) }}">
      {{ $category->name }}
     </a>
    @else
     <a class="breadcrumbs__link" href="{{ route('products', ['categorySlug' => $breadcrumb['slug']]) }}">
      {{ $breadcrumb['name'] }}
     </a>
    @endif
   @endforeach
  @endif
 </div>
 <!----------------------Categorie + detalii--------------------->
 @if ($category)
  <section class="section__header container">
   <h1 class="section__title">{{ $category->name }}</h1>
   <p class="section__text">
    {!! $category->long_description !!}
   </p>
  </section>
 @endif

 <!---------------------------Filter------------------------->
 <section class="controls container">
  <button class="controls__button" id="filterOpen" wire:click="$set('showspecfilter', true)"
   aria-label="Open filter button">
   <svg>
    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
   </svg>
  </button>
  <input class="controls__search" maxlength="100" type="text" name="search" id="search" wire:model="search"
   autocomplete="off" placeholder="@if (app()->has('label_placeholder_search')) {!! app('label_placeholder_search') !!} @endif">
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
 @if (!empty($selectedSpecNames))
  <section class="tag container">
   @foreach ($selectedSpecNames as $key => $name)
    <button class="tag__button" wire:click="removeSpec('{{ $key }}')">
     {{ $name }}: {{ $key }}
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
 <h2></h2>
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
     if ($product->type == 'parrent') {
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
    <div class="product">
     <div @if ($loop->last) id="last_record" @endif class="card">
      <a
       href="{{ route('product', ['product' => $element->seo_id !== null && $element->seo_id !== '' ? $element->seo_id : $element->id]) }}">
       @if ($element->media->first() != null)
        <img loading="eager" class="card-image"
         src="/{{ $element->media->first()->path }}{{ $element->media->first()->name }}"
         alt="{{ $element->media->first()->name }} {{ $element->name }}">
       @else
        <img loading="eager" class="card-image" src="/images/store/default/default300.webp" alt="something wrong">
       @endif
      </a>


      @php
       if ($element->product_prices->count() != 0) {
           $price = number_format($element->product_prices->first()->value, 2, ',', '.');
           $discount = $element->product_prices->first()->discount != 0 ? true : false;
       } else {
           $price = null;
           $discount = false;
       }
      @endphp
      @if ($price)
       {{-- Out- negru // save - rosu --}}
       @if ($element->quantity < $quantity && $element->quantity > 0)
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
       @elseif($element->quantity == 0)
        <p class="card-status save">
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
              'is_in_wishlist' => $element->wishlists->isNotEmpty(),
          ],
          key($element->id)
      )

      <div class="card-info">
       <div class="card-text">
        <span>{{ $product->short_description }}</span>
       </div>
       <div class="card-text">
        <h3 class="card-title">{{ $product->name }}</h3>
        <p class="card-price">
         @if (
             $product->type == 'parrent' &&
                 app()->has('global_variant_price_from') &&
                 app()->has('label_product_price_from') &&
                 app('global_variant_price_from') === 'true')
          {!! app('label_product_price_from') !!}
         @endif
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
           {{ $element->product_prices->first()->value_no_discount }}
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
        <div style="display: none">
         <span class="dlv_name">{{ $element->name }}</span>
         <span class="dlv_price">{{ $price }}</span>
         <span class="dlv_currency">
          @if (app()->has('global_currency_primary_symbol'))
           {!! app('global_currency_primary_symbol') !!}
          @endif
         </span>
        </div>
        </p>
       </div>
       @if (
           $product->type == 'parrent' &&
               app()->has('global_variant_add_to_cart') &&
               app('global_variant_add_to_cart') === 'true')
        @livewire('add-to-cart-button', ['product' => $element], key($element->id . $index))
       @elseif($product->type == 'parrent')
        <div class="card__button--wrapper">
         <a style="text-decoration: none; color: white"
          href="{{ route('product', ['product' => $element->seo_id !== null && $element->seo_id !== '' ? $element->seo_id : $element->id]) }}"
          class="card__button">
          @if (app()->has('label_show_parrent'))
           {!! app('label_show_parrent') !!}
          @endif
         </a>
        </div>
       @else
        @livewire('add-to-cart-button', ['product' => $element], key($element->id . $index))
       @endif
      </div>
     </div>
    </div>
   @endforeach
   <x-lazy />
  @endif
 </section>
 <!-----------------------Load more---------------------->
 @if ($products->total() >= $loadAmount)
  <section class="container">
   <button class="filter__apply" wire:click="loadMore" wire:loading.remove>Vezi mai mult!</button>
  </section>
 @endif
 <!---------------------------Filters------------------------->
 <div class="filter @if ($showspecfilter) active @endif" id="filterList">
  <div class="filter__content" id="filterContent">
   <div class="filter__top">
    <button class="filter__apply" id="resetFilter" wire:click="resetFilter">
     @if (app()->has('label_remove_all_filters'))
      {!! app('label_remove_all_filters') !!}
     @endif
     <svg>
      <polyline points="23 4 23 10 17 10"></polyline>
      <polyline points="1 20 1 14 7 14"></polyline>
      <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
     </svg>
    </button>
    <button class="filter__reset" wire:click="$set('showspecfilter', false)" id="filterClose" href="#">
     <svg>
      <line x1="18" y1="6" x2="6" y2="18"></line>
      <line x1="6" y1="6" x2="18" y2="18"></line>
     </svg>
    </button>
   </div>
   <button class="filter__top filter__top--button" wire:click="$set('showspecfilter', false)">
    @if (app()->has('label_display_filters_results'))
     {!! app('label_display_filters_results') !!}
    @endif <span>{{ $products->total() }}</span>
   </button>
   <div wire:ignore class="filter__list">
    @foreach ($filtervalues->sortBy('spec.sequence')->groupBy('spec_id') as $values)
     <div class="dropfilter">
      <div class="dropfilter__button">
       <button class="dropfilter__open" href="#">
        <h4>{{ $values->first()->spec->name }}</h4>
        <svg>
         <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
       </button>
      </div>
      <div class="dropfilter__list">
       @foreach ($values->sortBy('sequence') as $value)
        @php
         $key = str_replace('.', '_', $value->value);
        @endphp
        <label class="dropfilter__link" for="{{ $value->id }}{{ $value->value }}">
         <input type="checkbox" wire:model="selectedSpecValues.{{ $value->spec_id }}.{{ $key }}"
          wire:change="applyFilter" id="{{ $value->id }}{{ $value->value }}">
         <h4>{{ $value->value }}</h4>
        </label>
       @endforeach
      </div>
     </div>
    @endforeach
   </div>
  </div>
  <button class="filter__close-modal" wire:click="$set('showspecfilter', false)"></button>
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
    <button class="filter__reset" id="sortClose" href="#">
     <svg>
      <line x1="18" y1="6" x2="6" y2="18"></line>
      <line x1="6" y1="6" x2="18" y2="18"></line>
     </svg>
    </button>
   </div>
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
 <x-help-button />
 <script src="/script/store/catalog.js" defer></script>
</div>
