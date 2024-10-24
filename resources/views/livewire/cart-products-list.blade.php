<div
 class="leftbar
    @if ($showcart) active @else @endif
    @if ($cartmodified) problem @endif

    @if ($aplicabble_voucher) mod @endif"
 id="basketList">
 <button class="leftbar__hidden--close" wire:click="$set('showcart', false)" id="basketHidden"></button>
 <div class="leftbar__content" id="basketContent">
  <div class="leftbar__top">
   <a id="price_change" class="leftbar__button" href="{{ url('/cart') }}">
    @if (app()->has('label_cart_title'))
     {!! app('label_cart_title') !!}
    @endif
   </a>
   <button class="leftbar__close" id="basketClose" wire:click="$set('showcart', false)">
    <svg>
     <line x1="18" y1="6" x2="6" y2="18"></line>
     <line x1="6" y1="6" x2="18" y2="18"></line>
    </svg>
   </button>
  </div>

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
  @if (!isset($cart) || $cart->quantity_amount == 0)
   <span class="leftbar__empty">
    @if (app()->has('label_cart_empty'))
     {!! app('label_cart_empty') !!}
    @endif
   </span>
  @else
   <ul class="leftbar__list">
    <?php
    $isdisabled = false;
    $disables = [];
    $nonquantity = [];
    ?>
    @foreach ($cart->cartItems as $index => $cartItem)
     <?php
     $disabled[$index] = false;
     $nonquantity[$index] = false;
     
     if ($cartItem->product->active != true || $cartItem->product->start_date > now()->format('Y-m-d') || ($cartItem->product->end_date < now()->format('Y-m-d') || ($cartItem->product->quantity < 0 && (app()->has('global_preorder') && app('global_preorder') != 'true')))) {
         $disabled[$index] = true;
         $isdisabled = true;
     }
     if (!optional($cartItem->product->product_prices->first())->value) {
         $disabled[$index] = true;
         $isdisabled = true;
     }
     
     if ($cartItem->product->quantity > 0 && $cartItem->product->quantity < $cartItem->quantity && (app()->has('global_preorder') && app('global_preorder') != 'true')) {
         $nonquantity[$index] = true;
         $isdisabled = true;
     }
     ?>

     <li id="price_change" class="leftbar__item">
      @if ($nonquantity[$index])
       <div class="basket__split">
        <div class="leftbar__link">
         @if ($cartItem->product->media->where('type', 'min')->first())
          <img loading="eager" class="cart__list--img" title="{{ $cartItem->product->name }}"
           src="/{{ $cartItem->product->media->where('type', 'min')->first()->path }}{{ $cartItem->product->media->where('type', 'min')->first()->name }}"
           alt="{{ $cartItem->product->media->where('type', 'min')->first()->name }}{{ $cartItem->product->name }}">
         @else
          <img title="Default image" loading="eager" class="cart__list--img" src="/images/store/default/default70.webp"
           alt="something wrong">
         @endif
         <div class="leftbar__link--text">
          <h4 class="leftbar__link--title">{{ $cartItem->product->name }}</h4>
          <span class="item__product--error">
           @if (app()->has('label_product_quantity_error'))
            {!! app('label_product_quantity_error') !!}
           @endif
           {{ $cartItem->product->quantity }}
          </span>

         </div>
        </div>
        <div class="basket__item" style="border-top:1px solid #333333">
         <div class="quantity" style="border-bottom: 0 !important">
          <span>
           @if (app()->has('label_product_quantity_tag'))
            {!! app('label_product_quantity_tag') !!}
           @endif
          </span>
          <div class="quantity__buttons">
           <button class="quantity__arrow @if ($cartItem->quantity == 1) disabled @endif"
            style="width: 48px; height: 48px" aria-label="Decrease quantity"
            wire:click="decrement({{ $cartItem->id }})">
            <svg>
             <circle cx="12" cy="12" r="10"></circle>
             <line x1="8" y1="12" x2="16" y2="12">
             </line>
            </svg>
           </button>
           <span class="quantity__input product__quantity">
            {{ $cartItem->quantity }}
           </span>
           <button class="quantity__arrow @if (
               $cartItem->quantity >= $cartItem->product->quantity &&
                   (app()->has('global_preorder') && app('global_preorder') != 'true')) disabled @endif"
            style="width: 48px; height: 48px" aria-label="Increase quantity"
            wire:click="increment({{ $cartItem->id }})">
            <svg>
             <circle cx="12" cy="12" r="10"></circle>
             <line x1="12" y1="8" x2="12" y2="16">
             </line>
             <line x1="8" y1="12" x2="16" y2="12">
             </line>
            </svg>
           </button>
          </div>
         </div>
         <div class="basket__subtotal">
          <span>
           @if (app()->has('label_cart_page_subtotal_tag'))
            {!! app('label_cart_page_subtotal_tag') !!}
           @endif
          </span>
          <span>
           {{ number_format($cartItem->quantity * $cartItem->price, 2, $decimal, $mill) }}
           @if (app()->has('global_currency_primary_symbol'))
            {!! app('global_currency_primary_symbol') !!}
           @endif
          </span>
         </div>
        </div>
       </div>
      @else
       <div class="basket__split">
        <div class="basket__item__mini">
         <a class="leftbar__link"
          href="{{ route('product', ['product' => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== '' ? $cartItem->product->seo_id : $cartItem->product->id]) }}">
          @if ($cartItem->product->media->where('type', 'min')->first())
           <img loading="eager" class="cart__list--img" title="{{ $cartItem->product->name }}"
            src="/{{ $cartItem->product->media->where('type', 'min')->first()->path }}{{ $cartItem->product->media->where('type', 'min')->first()->name }}"
            alt="{{ $cartItem->product->media->where('type', 'min')->first()->name }}{{ $cartItem->product->name }}">
          @else
           <img title="Default image" loading="eager" class="cart__list--img" src="/images/store/default/default70.webp"
            alt="something wrong">
          @endif
          <div class="leftbar__link--text">
           <h4 class="leftbar__link--title">{{ $cartItem->product->name }}</h4>
           <span class="leftbar__link--price">
            @php
             if (optional($cartItem->product->product_prices->first())->value) {
                 $price = number_format($cartItem->product->product_prices->first()->value, 2, $decimal, $mill);
             } else {
                 $price = null;
             }
            @endphp
            @if ($price && $price != null)
             {{ $price }} @if (app()->has('global_currency_primary_symbol'))
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
         <button class="leftbar__delete" style="border: none" type="button"
          wire:click="removeFromCart({{ $cartItem->product->id }})">
          <svg>
           <polyline points="3 6 5 6 21 6"></polyline>
           <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
         </button>
        </div>
        <div class="basket__item__mini" style="border-top:1px solid #333333">
         <div class="quantity">
          <span>
           @if (app()->has('label_product_quantity_tag'))
            {!! app('label_product_quantity_tag') !!}
           @endif
          </span>
          <div class="quantity__buttons">
           <button class="quantity__arrow @if ($cartItem->quantity == 1) disabled @endif"
            style="width: 48px; height: 48px" aria-label="Decrease quantity"
            wire:click="decrement({{ $cartItem->id }})">
            <svg>
             <circle cx="12" cy="12" r="10"></circle>
             <line x1="8" y1="12" x2="16" y2="12">
             </line>
            </svg>
           </button>
           <span class="quantity__input product__quantity">
            {{ $cartItem->quantity }}
           </span>
           <button class="quantity__arrow @if (
               $cartItem->quantity >= $cartItem->product->quantity &&
                   (app()->has('global_preorder') && app('global_preorder') != 'true')) disabled @endif"
            style="width: 48px; height: 48px" aria-label="Increase quantity"
            wire:click="increment({{ $cartItem->id }})">
            <svg>
             <circle cx="12" cy="12" r="10"></circle>
             <line x1="12" y1="8" x2="12" y2="16">
             </line>
             <line x1="8" y1="12" x2="16" y2="12">
             </line>
            </svg>
           </button>
          </div>
         </div>
         <div class="basket__subtotal">
          <span>
           @if (app()->has('label_cart_page_subtotal_tag'))
            {!! app('label_cart_page_subtotal_tag') !!}
           @endif
          </span>
          <span>
           {{ number_format($cartItem->quantity * $cartItem->price, 2, $decimal, $mill) }}
           @if (app()->has('global_currency_primary_symbol'))
            {!! app('global_currency_primary_symbol') !!}
           @endif
          </span>
         </div>
        </div>
       </div>
      @endif
      @if ($disabled[$index])
       <div class="item__product--disabled">
        <span>
         @if (app()->has('label_product_status_indisponible'))
          {!! app('label_product_status_indisponible') !!}
         @endif
        </span>
        <button class="leftbar__delete" type="button" wire:click="removeFromCart({{ $cartItem->product->id }})">
         <svg>
          <polyline points="3 6 5 6 21 6"></polyline>
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
         </svg>
        </button>
       </div>
      @endif
     </li>
    @endforeach
   </ul>

   <div class="leftbar__total" @if (app()->has('global_display_delivery_price_on_cart') && app('global_display_delivery_price_on_cart') != 'true')  @endif>

    <h5 class="leftbar__total--text">
     @if (app()->has('label_cart_products_tag'))
      {!! app('label_cart_products_tag') !!}
     @endif
     <span id="leftbarTotalPrice">
      {{ number_format($cart->sum_amount, 2, $decimal, $mill) }}
      @if (app()->has('global_currency_primary_symbol'))
       {!! app('global_currency_primary_symbol') !!}
      @endif

     </span>
    </h5>
    @if (app()->has('global_display_delivery_price_on_cart') && app('global_display_delivery_price_on_cart') === 'true')

     <h5 class="leftbar__total--text">
      @if (app()->has('label_cart_delivery_tag'))
       {!! app('label_cart_delivery_tag') !!}
      @endif
      <span id="leftbarTotalPrice">
       @if ($cart->delivery_price == 0)
        @if (app()->has('label_cart_delivery_free'))
         {!! app('label_cart_delivery_free') !!}
        @endif
       @else
        {{ number_format($cart->delivery_price, 2, $decimal, $mill) }}@if (app()->has('global_currency_primary_symbol'))
         {!! app('global_currency_primary_symbol') !!}
        @endif
       @endif
      </span>
     </h5>
    @endif
    @if ($cart->voucher_id != null)
     <h5 class="leftbar__total--text">
      @if (app()->has('label_cart_voucher_tag'))
       {!! app('label_cart_voucher_tag') !!}
      @endif
      <span class="voucher__choice">
       -{{ number_format($cart->voucher_value, 2, $decimal, $mill) }} @if (app()->has('global_currency_primary_symbol'))
        {!! app('global_currency_primary_symbol') !!}
       @endif
       <button wire:click="removevoucher" class="details__delete" aria-label="Remove voucher">
        <svg>
         <polyline points="3 6 5 6 21 6"></polyline>
         <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
        </svg>
       </button>
      </span>
     </h5>
    @endif
    @if (app()->has('global_display_delivery_price_on_cart') && app('global_display_delivery_price_on_cart') === 'true')

     <h5 class="leftbar__total--text">
      @if (app()->has('label_cart_total_tag'))
       {!! app('label_cart_total_tag') !!}
      @endif
      <span id="leftbarTotalPrice">
       {{ number_format($cart->final_amount, 2, $decimal, $mill) }}
       @if (app()->has('global_currency_primary_symbol'))
        {!! app('global_currency_primary_symbol') !!}
       @endif
      </span>
     </h5>
    @endif
    @if ($message)
     <p class="voucher__error">{{ $message }}</p>
    @endif
    @if ($cart->voucher_id == null)
     <div class="voucher">
      <input type="text" wire:model="voucher" maxlength="100" name="voucher"
       placeholder="@if (app()->has('label_cart_voucher_placeholder')) {!! app('label_cart_voucher_placeholder') !!} @endif">
      <button type="submit" wire:click="checkvoucher">
       @if (app()->has('label_cart_voucher_apply'))
        {!! app('label_cart_voucher_apply') !!}
       @endif
      </button>
     </div>
    @endif

    @if ($isdisabled)
     <a class="leftbar__button leftbar__button--long item__button--disabled">
      @if (app()->has('label_cart_order'))
       {!! app('label_cart_order') !!}
      @endif
     </a>
     <span class="item__text--disabled" id="headerContinue">
      @if (app()->has('label_cart_order_mesage'))
       {!! app('label_cart_order_mesage') !!}
      @endif
     </span>
    @else
     <a class="leftbar__button leftbar__button--long" id="headerContinue" wire:click.prevent="continue">
      @if (app()->has('label_cart_order'))
       {!! app('label_cart_order') !!}
      @endif
     </a>
    @endif
   </div>

   <script>
    document.getElementById('headerContinue').addEventListener('click', function() {
     let productsList = [];
     let products = document.querySelectorAll('.leftbar__item');
     let total = parseFloat(document.getElementById('leftbarTotalPrice').innerText.replace('RON', '')
      .trim());

     products.forEach(function(product) {
      let productName = product.querySelector('.leftbar__link--title').innerText; // Extrage numele produsului
      let productPrice = parseFloat(product.querySelector('.leftbar__link--price').innerText.replace('RON', '')
       .trim());
      let productQuantity = parseInt(product.querySelector('.leftbar__link--quantity')
       .innerText);

      productsList.push(productName + ' --- ' + productQuantity + 'buc --- ' + productPrice);
     });

     window.dataLayer = window.dataLayer || [];
     window.dataLayer.push({
      'event': 'addToCart',
      'products': productsList,
      'total': total,
      'event': 'continueToCheckout'
     });
    });
   </script>
  @endif
 </div>
 <div class="leftbar__modal">
  <div class="leftbar__modal--text">
   @if (app()->has('label_cart_voucher_question'))
    {!! app('label_cart_voucher_question') !!}
    @endif "{{ $voucher }}"?
  </div>
  <div class="leftbar__modal--bundle">
   <button class="leftbar__modal--btn" wire:click="confirm_aplicabble">
    @if (app()->has('label_cart_voucher_question_confirm'))
     {!! app('label_cart_voucher_question_confirm') !!}
    @endif
   </button>
   <button class="leftbar__modal--btn" wire:click="cancel_aplicabble">
    @if (app()->has('label_cart_voucher_question_cancel'))
     {!! app('label_cart_voucher_question_cancel') !!}
    @endif
   </button>
  </div>
 </div>
 <div class="leftbar__problem">
  <div class="leftbar__modal--text">
   @if (app()->has('label_cart_changed_mesage'))
    {!! app('label_cart_changed_mesage') !!}
   @endif
  </div>
  <div class="leftbar__modal--bundle">
   <button class="leftbar__modal--btn" wire:click="seen">
    @if (app()->has('label_cart_changed_confirm'))
     {!! app('label_cart_changed_confirm') !!}
    @endif
   </button>
  </div>
 </div>
 <script>
  document.addEventListener('livewire:load', function() {
   let observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
     if (entry.isIntersecting) {
      @this.call('pricechanged');
     }
    });
   });

   observer.observe(document.getElementById('price_change'));
  });
 </script>
</div>
