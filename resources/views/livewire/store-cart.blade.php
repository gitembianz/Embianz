<div>

 <x-store-alert />
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
 <section>
  <div class="section__header container">
   <h1 class="section__title">
    @if (app()->has('label_cart_page_title'))
     {!! app('label_cart_page_title') !!}
    @endif
   </h1>
   <h2></h2>
   @if (isset($cart) && $cart->quantity_amount != 0)
    <p class="section__text">
     @if (app()->has('label_cart_page_description'))
      {!! app('label_cart_page_description') !!}
     @endif
    </p>
   @endif
  </div>
 </section>
 <section>
  <div class="basket__container container">
   <!------------------- Products ------------------>
   <div class="basket">
    @if (!isset($cart) || $cart->quantity_amount == 0)
     <span class="basket__empty">
      @if (app()->has('label_cart_empty'))
       {!! app('label_cart_empty') !!}
      @endif
     </span>
    @else
     <?php $disables = [];
     $nonquantity = [];
     $isdisabled = false; ?>
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
      <div class="basket__split">
       <div class="basket__item">
        <a style="width: 100%; display: flex; flex: 1;text-decoration: none"
         href="{{ route('product', ['product' => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== '' ? $cartItem->product->seo_id : $cartItem->product->id]) }}">
         @if ($cartItem->product->media->where('type', 'min')->first())
          <img loading="eager" class="cart__list--img" title="{{ $cartItem->product->name }}"
           src="/{{ $cartItem->product->media->where('type', 'min')->first()->path }}{{ $cartItem->product->media->where('type', 'min')->first()->name }}"
           alt="{{ $cartItem->product->media->where('type', 'min')->first()->name }} {{ $cartItem->product->name }}">
         @else
          <img title="Default image" loading="eager" class="cart__list--img" src="/images/store/default/default70.webp"
           alt="something wrong">
         @endif
         <div class="basket__text">
          <h3>{{ $cartItem->product->name }}</h3>
          <span>
           {{ number_format($cartItem->price, 2, $decimal, $mill) }}
           @if (app()->has('global_currency_primary_symbol'))
            {!! app('global_currency_primary_symbol') !!}
           @endif

          </span>
          @if ($nonquantity[$index])
           <span class="item__product--error">
            @if (app()->has('label_product_quantity_error'))
             {!! app('label_product_quantity_error') !!}
            @endif
            {{ $cartItem->product->quantity }}
           </span>
          @endif
         </div>
        </a>
        @livewire(
            'product-wishlist-button',
            [
                'productId' => $cartItem->product->id,
                'class' => 'basket__action',
                'is_in_wishlist' => $this->isInWishlist($cartItem->product->id),
            ],
            key($cartItem->product->id)
        )
        <button class="basket__delete" aria-label="Remove from cart button"
         wire:click="removeFromCart({{ $cartItem->product->id }})" onclick="removeItem(this)">
         <svg>
          <polyline points="3 6 5 6 21 6"></polyline>
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
          </path>
         </svg>
        </button>
       </div>
       <div class="basket__item">
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
       @if ($disabled[$index])
        <div class="item__product--disabled">
         <span>
          @if (app()->has('label_product_status_indisponible'))
           {!! app('label_product_status_indisponible') !!}
          @endif
         </span>
         <button class="leftbar__delete" type="button" wire:click="removeFromCart({{ $cartItem->product->id }})">
          <svg>
           <line x1="18" y1="6" x2="6" y2="18"></line>
           <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
         </button>
        </div>
       @endif
      </div>
     @endforeach
    @endif
   </div>
   @if ($cart != null && $cart->quantity_amount != 0)
    <div class="details">
     <div class="details__content">
      <h2 class="details__title">
       @if (app()->has('label_cart_page_order_details'))
        {!! app('label_cart_page_order_details') !!}
       @endif
      </h2>
      <div class="details__text">
       <h3>
        @if (app()->has('label_cart_products_tag'))
         {!! app('label_cart_products_tag') !!}
        @endif
       </h3>
       <span> {{ number_format($cart->sum_amount, 2, $decimal, $mill) }}
        @if (app()->has('global_currency_primary_symbol'))
         {!! app('global_currency_primary_symbol') !!}
        @endif
       </span>
      </div>
      @if (app()->has('global_display_delivery_price_on_cart') && app('global_display_delivery_price_on_cart') === 'true')

       <div class="details__text">
        <h3>
         @if (app()->has('label_cart_delivery_tag'))
          {!! app('label_cart_delivery_tag') !!}
         @endif
        </h3>
        <span>
         @if ($cart->delivery_price == 0)
          @if (app()->has('label_cart_delivery_free'))
           {!! app('label_cart_delivery_free') !!}
          @endif
         @else
          {{ number_format($cart->delivery_price, 2, $decimal, $mill) }} @if (app()->has('global_currency_primary_symbol'))
           {!! app('global_currency_primary_symbol') !!}
          @endif
         @endif
        </span>
       </div>
      @endif
      @if ($cart->voucher_id != null)
       <div class="details__text">
        <h3>
         @if (app()->has('label_cart_voucher_tag'))
          {!! app('label_cart_voucher_tag') !!}
         @endif
        </h3>
        <span class="voucher__choice">
         -{{ number_format($cart->voucher_value, 2, $decimal, $mill) }} @if (app()->has('global_currency_primary_symbol'))
          {!! app('global_currency_primary_symbol') !!}
         @endif
         <button wire:click="removevoucher" class="details__delete" aria-label="Remove voucher">
          <svg>
           <polyline points="3 6 5 6 21 6"></polyline>
           <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
           </path>
          </svg>
         </button>
        </span>
       </div>
      @endif
     </div>
     <div class="details__content">
      <div class="details__text">
       <h3>
        @if (app()->has('label_cart_total_tag'))
         {!! app('label_cart_total_tag') !!}
        @endif
       </h3>
       <span id="detailsTotal">
        @if (app()->has('global_display_delivery_price_on_cart') && app('global_display_delivery_price_on_cart') === 'true')
         {{ number_format($cart->final_amount, 2, $decimal, $mill) }}
        @else
         {{ number_format($cart->final_amount - app('global_delivery_price'), 2, $decimal, $mill) }}
        @endif
        @if (app()->has('global_currency_primary_symbol'))
         {!! app('global_currency_primary_symbol') !!}
        @endif
       </span>
      </div>
      @if ($message)
       <p class="voucher__error">{{ $message }}</p>
      @endif
      @if ($cart->voucher_id == null)

      <!-- CHANGE TO DYNAMIC -->

       <!-- <div class="voucher">
         <input type="text" wire:model="voucher" maxlength="100" name="voucher"
         placeholder="@if (app()->has('label_cart_voucher_placeholder')) {!! app('label_cart_voucher_placeholder') !!} @endif">
        <button type="submit" wire:click="checkvoucher">
         @if (app()->has('label_cart_voucher_apply'))
          {!! app('label_cart_voucher_apply') !!}
         @endif
        </button>
       </div> -->

       <!-- CHANGE TO DYNAMIC -->
      @endif
      @if ($aplicabble_voucher)
       <div class="voucher__question">
        <div class="voucher__question--text">
         @if (app()->has('label_cart_voucher_question'))
          {!! app('label_cart_voucher_question') !!}
         @endif
         "{{ $voucher }}"?
        </div>
        <div class="voucher__question--bundle">
         <button class="voucher__question--btn" wire:click="confirm_aplicabble">
          @if (app()->has('label_cart_voucher_question_confirm'))
           {!! app('label_cart_voucher_question_confirm') !!}
          @endif
         </button>
         <button class="voucher__question--btn" wire:click="cancel_aplicabble">
          @if (app()->has('label_cart_voucher_question_cancel'))
           {!! app('label_cart_voucher_question_cancel') !!}
          @endif
         </button>
        </div>
       </div>
      @endif
      @if (!$aplicabble_voucher)
       @if ($isdisabled)
        <a class="leftbar__button leftbar__button--long item__button--disabled">
         @if (app()->has('label_cart_voucher_question'))
          {!! app('label_cart_order') !!}
         @endif
        </a>

        <span class="item__text--disabled" id="detailsContinue">
         @if (app()->has('label_cart_voucher_question'))
          {!! app('label_cart_order_error') !!}
         @endif
        </span>
       @else
        <button id="detailsContinue" class="details__button details__continue" wire:click="continue()"
         aria-label="Continue form">
         @if (app()->has('label_cart_voucher_question'))
          {!! app('label_cart_order') !!}
         @endif
        </button>
       @endif
      @endif
     </div>
    </div>
   @endif
   <!----------------- End Basket Continue ---------------->
  </div>
 </section>
 @if ($cart != null && $cart->quantity_amount != 0)
  <div class="dlv" style="display: none">
   <span class="dlv_currency">
    @if (app()->has('global_currency_primary_symbol'))
     {!! app('global_currency_primary_symbol') !!}
    @endif
   </span>
   <span class="dlv_value">{{ number_format($cart->sum_amount, 2, $decimal, $mill) }}</span>
   @foreach ($cart->cartItems as $cartItem)
    <div class="dlv_item">
     <span class="dlv_item-id">{{ $cartItem->product->id }}</span>
     <span class="dlv_item-name">{{ $cartItem->product->name }}</span>
     <span class="dlv_item-price">{{ $cartItem->price }}</span>
     <span class="dlv_item-quantity">{{ $cartItem->quantity }}</span>
    </div>
   @endforeach
  </div>
  <script>
   function view_cart() {
    // Variables
    const dlv = document.querySelector('.dlv');
    // Check if dlv element exists
    if (!dlv) {
     console.error('Elementul cu clasa .dlv nu a fost găsit.');
     return;
    }
    // Get all Values
    const currency = dlv.querySelector('.dlv_currency').innerText.trim();
    const value = parseFloat(dlv.querySelector('.dlv_value').innerText.trim().replace(',', '.'));
    const items = [];
    // Get all items
    const dlv_items = dlv.querySelectorAll('.dlv_item');
    // Loop through each item
    dlv_items.forEach(dlv_item => {
     const item_id = dlv_item.querySelector('.dlv_item-id').innerText.trim();
     const item_name = dlv_item.querySelector('.dlv_item-name').innerText.trim();
     const item_price = parseFloat(dlv_item.querySelector('.dlv_item-price').innerText.trim().replace(',', '.'));
     const item_quantity = parseInt(dlv_item.querySelector('.dlv_item-quantity').innerText.trim(), 10);

     // Create item object
     const item = {
      item_id: item_id,
      item_name: item_name,
      price: item_price,
      quantity: item_quantity
     };

     // Push item in the array
     items.push(item);
    });

    // Create the data object
    const dlvData = {
     currency: currency,
     value: value,
     items: items
    };
    // Show the object
    // console.log(dlvData);
    return dlvData;
   };

   const dlvData = view_cart();

   dataLayer.push({
    ecommerce: null
   });
   dataLayer.push({
    event: "view_cart",
    ecommerce: {
     currency: dlvData.currency,
     value: dlvData.value,
     items: dlvData.items
    }
   });
  </script>
 @endif

 <script src="/script/store/sickycartinfo.js" defer></script>
</div>
