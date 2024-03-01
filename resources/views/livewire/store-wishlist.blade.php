<div>
 <section>
  <!------------------------------------------------------>
  <!------------------- Basket Section ------------------->
  <div class="section__header container">
   <h1 class="section__title">Produse favorite</h1>
   <p class="section__text">
    Vezi produsele alese mai jos
   </p>
  </div>
  <!----------------- End Basket Section ----------------->
  <!------------------------------------------------------>
 </section>
 <section>
  <div class="favorite container">
   <!------------------------------------------------------>
   <!------------------- Basket Products ------------------>
   @if ($wishlistitems->isEmpty())
    <span class="basket__empty">Niciun produs favorit adaugat</span>
   @else
    @foreach ($wishlistitems as $product)
     <div class="basket__product">
      <div class="basket__top">
       @if ($product->media->first() != null)
        <img src="/{{ $product->media->first()->path }}{{ $product->media->first()->name }}"
         alt="{{ $product->media->first()->name }} {{ $product->name }}">
       @else
        <img src="/images/store/default/default70.webp" alt="something wrong">
       @endif
       <div>
        <a
         href="{{ route('product', ['product' => $product->seo_id !== null && $product->seo_id !== '' ? $product->seo_id : $product->id]) }}"
         class="basket__title">{{ $product->name }}</a>
        <span class="basket__price">
         @if ($product->product_prices->first() !== null)
          {{ $product->product_prices->first()->value }}
          {{ $product->product_prices->first()->pricelist->currency->name }}
         @else
          price unavailable
         @endif
        </span>
       </div>
       <button class="basket__delete" wire:click="removeFromWishlist({{ $product->id }})"
        aria-label="Remove from wishlist">
        <svg>
         <polyline points="3 6 5 6 21 6"></polyline>
         <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
         </path>
        </svg>
       </button>
       @if ($product->product_prices->first() !== null)
        <button class="basket__delete" wire:click="addToCart({{ $product->id }})" aria-label="add to cart">
         <svg>
          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <path d="M16 10a4 4 0 0 1-8 0"></path>
         </svg>
        </button>
       @endif
      </div>

     </div>
    @endforeach
   @endif
   <!----------------- End Basket Products ---------------->
   <!------------------------------------------------------>
  </div>
 </section>
 <!---------------------------------------------------------->
 <!--------------------- support button --------------------->
 <x-help-button />
 <!------------------- End support button ------------------->
 <!---------------------------------------------------------->
</div>
