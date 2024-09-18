<div>
 <section>
  <div class="section__header container">
   <h1 class="section__title">
    @if (app()->has('label_wishlist_page_title'))
     {!! app('label_wishlist_page_title') !!}
    @endif
   </h1>
   <h2></h2>
   <p class="section__text">
    @if (app()->has('label_wishlist_page_description'))
     {!! app('label_wishlist_page_description') !!}
    @endif
   </p>
  </div>
 </section>
 <section>
  <div class="basket container">
   <!------------------- Products ------------------>
   @if ($wishlistitems->isEmpty())
    <span class="basket__empty">
     @if (app()->has('label_wishlist_empty'))
      {!! app('label_wishlist_empty') !!}
     @endif
    </span>
   @else
    <?php
    $disables = [];
    ?>
    @foreach ($wishlistitems as $index => $item)
     <?php
     $disabled[$index] = false;
     if ($item->product->active != true || $item->product->start_date > now()->format('Y-m-d') || $item->product->end_date < now()->format('Y-m-d')) {
         $disabled[$index] = true;
     } ?>
     <div class="basket__item">
      <a
       href="{{ route('product', ['product' => $item->product->seo_id !== null && $item->product->seo_id !== '' ? $item->product->seo_id : $item->product->id]) }}"
       class="basket__link">
       @if ($item->product->media->where('type', 'min')->first() != null)
        <img title="{{ $item->product->name }}" loading="eager"
         src="/{{ $item->product->media->where('type', 'min')->first()->path }}{{ $item->product->media->where('type', 'min')->first()->name }}"
         alt="{{ $item->product->name }}">
       @else
        <img title="Default image" loading="eager" src="/images/store/default/default70.webp" alt="something wrong">
       @endif
       <div class="basket__text">
        <h3>
         {{ $item->product->name }}
        </h3>
        <span>
         @if ($item->product->product_prices->first() !== null)
          {{ $item->product->product_prices->first()->value }}
          @if (app()->has('global_currency_primary_symbol'))
           {!! app('global_currency_primary_symbol') !!}
          @endif
         @else
          @if (app()->has('label_product_status_indisponible'))
           {!! app('label_product_status_indisponible') !!}
          @endif
         @endif
        </span>
       </div>
      </a>
      @if ($item->product->product_prices->first() !== null && $item->product->quantity > 1)
       <button class="basket__delete" wire:click="addToCart({{ $item->product->id }}, {{ $index }})"
        aria-label="add to cart">
        <svg>
         <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
         <line x1="3" y1="6" x2="21" y2="6"></line>
         <path d="M16 10a4 4 0 0 1-8 0"></path>
        </svg>
       </button>
      @endif
      <button class="basket__delete" wire:click="removeFromWishlist({{ $item->product->id }})"
       aria-label="Remove from wishlist">
       <svg>
        <polyline points="3 6 5 6 21 6"></polyline>
        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
        </path>
       </svg>
      </button>
      @if ($disabled[$index])
       <div class="item__product--disabled">
        <span>
         @if (app()->has('label_product_status_indisponible'))
          {!! app('label_product_status_indisponible') !!}
         @endif
        </span>
        <button class="basket__delete" type="button" wire:click="removeFromWishlist({{ $item->product->id }})">
         <svg>
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
         </svg>
        </button>
       </div>
      @endif
     </div>
     @if ($message === $index)
      <p class="leftbar__message">
       @if (app()->has('label_wishlist_add_to_cart'))
        {!! app('label_wishlist_add_to_cart') !!}
       @endif
      </p>
      <script>
       setTimeout(function() {
        @this.removemessage();
       }, 2500);
      </script>
     @endif
    @endforeach
   @endif
  </div>
 </section>

</div>
