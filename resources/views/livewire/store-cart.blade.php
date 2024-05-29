<div>
 <script rel="preload" src="script/store/checkout.js" as="script"></script>
 <x-store-alert />
 <section>
  <div class="section__header container">
   <h1 class="section__title">Coșul de cumpăraturi</h1>
   <h2></h2>
   <p class="section__text">
    Vezi produsele mai jos
   </p>
  </div>
 </section>
 <section>
  <div class="basket__container container">
   <!------------------------------------------------------>
   <!------------------- Basket Products ------------------>
   <div class="basket">
    @if ($cartItems->isEmpty())
     <span class="basket__empty">Coșul de cumpărături nu conține produse</span>
    @else
     <?php $disables = [];
     $nonquantity = [];
     $isdisabled = false; ?>
     @foreach ($cartItems as $index => $cartItem)
      <?php
      $disabled[$index] = false;
      $nonquantity[$index] = false;

      if ($cartItem->product->active != true || $cartItem->product->start_date > now()->format('Y-m-d') || $cartItem->product->end_date < now()->format('Y-m-d')) {
          $disabled[$index] = true;
          $isdisabled = true;
      }

      if (!optional($cartItem->product->product_prices->first())->value) {
          $disabled[$index] = true;
          $isdisabled = true;
      }

      if ($cartItem->product->quantity < $cartItem->quantity) {
          $nonquantity[$index] = true;
          $isdisabled = true;
      }

      ?>
      <div class="basket__split">
       <a class="basket__item"
        href="{{ route('product', ['product' => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== '' ? $cartItem->product->seo_id : $cartItem->product->id]) }}">
        @if ($cartItem->product->media->first())
         <img loading="eager" class="cart__list--img"
          src="/{{ $cartItem->product->media->first()->path }}{{ $cartItem->product->media->first()->name }}"
          alt="{{ $cartItem->product->media->first()->name }} {{ $cartItem->product->name }}">
        @else
         <img loading="eager" class="cart__list--img" src="/images/store/default/default70.webp" alt="something wrong">
        @endif
        <div class="basket__text">
         <h3>{{ $cartItem->product->name }}</h3>
         <span>
          {{ number_format($cartItem->price, 2, ',', '.') }}
          {{ $cart->currency->symbol }}

         </span>
         @if ($nonquantity[$index])
          <span class="item__product--error">Stoc disponibil pentru acest produs:
           {{ $cartItem->product->quantity }}</span>
         @endif
        </div>
        @livewire('product-wishlist-button', ['productId' => $cartItem->product->id, 'class' => 'basket__action', 'is_in_wishlist' => $cartItem->product->wishlists->isNotEmpty()], key($cartItem->product->id))
        <button class="basket__delete" aria-label="Remove from cart button"
         wire:click="removeFromCart({{ $cartItem->product->id }})" onclick="removeItem(this)">
         <svg>
          <polyline points="3 6 5 6 21 6"></polyline>
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
          </path>
         </svg>
        </button>
       </a>
       <div class="basket__item">
        <div class="quantity">
         <span>Cantitatea</span>

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
          <button class="quantity__arrow @if ($cartItem->quantity >= $cartItem->product->quantity) disabled @endif"
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
         <span>Subtotal:</span>
         <span>
          {{ number_format($cartItem->quantity * $cartItem->price, 2, ',', '.') }}
          {{ $cart->currency->symbol }}
         </span>
        </div>
       </div>
       @if ($disabled[$index])
        <div class="item__product--disabled">
         <span>Produs Indisponibil</span>
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
   <!----------------- End Basket Products ---------------->
   <!------------------------------------------------------>
   <!------------------- Basket Continue ------------------>
   @if (!$cartItems->isEmpty())
    <div class="details">
     <div class="details__content">
      <h2 class="details__title">Detalii comandă</h2>
      <div class="details__text">
       <h3>Produse:</h3>
       <span> {{ number_format($cart->sum_amount, 2, ',', '.') }}
        {{ $cart->currency->symbol }}</span>
      </div>
      <div class="details__text">
       <h3>Livrare:</h3>
       <span>
        @if ($cart->delivery_price == 0)
         Gratuit
        @else
         {{ $cart->delivery_price }} {{ $cart->currency->symbol }}
        @endif
       </span>
      </div>
      @if ($cart->voucher_id != null)
       <div class="details__text">
        <h3>Voucher:</h3>
        <span class="voucher__choice">
         -{{ number_format($cart->voucher_value, 2, ',', '.') }} {{ $cart->currency->symbol }}
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
       <h3>Total:</h3>
       <span id="detailsTotal">
        {{ number_format($cart->final_amount, 2, ',', '.') }} {{ $cart->currency->symbol }}
       </span>
      </div>
      @if ($message)
       <p class="voucher__error">{{ $message }}</p>
      @endif
      @if ($cart->voucher_id == null)
       <div class="voucher">
        <input type="text" wire:model="voucher" maxlength="100" name="voucher"
         placeholder="Ai un voucher sau card cadou?">
        <button type="submit" wire:click="checkvoucher">
         Aplică
        </button>
       </div>
      @endif
      @if ($aplicabble_voucher)
       <div class="voucher__question">
        <div class="voucher__question--text">Dorești să activezi voucher-ul
         "{{ $voucher }}"?</div>
        <div class="voucher__question--bundle">
         <button class="voucher__question--btn" wire:click="confirm_aplicabble">Da</button>
         <button class="voucher__question--btn" wire:click="cancel_aplicabble">Nu</button>
        </div>
       </div>
      @endif
      @if (!$aplicabble_voucher)

       @if ($isdisabled)
        <a class="leftbar__button leftbar__button--long item__button--disabled">Continuă</a>

        <span class="item__text--disabled" id="detailsContinue">Cantitatea anumitor produse nu
         mai este disponibilă, sau ai cel puțin un produs indisponibil adaugat in coș!</span>
       @else
        <button id="detailsContinue" class="details__button details__continue" wire:click="continue()"
         aria-label="Continue form">Continuă</button>
       @endif
      @endif

     </div>

    </div>
   @endif
   <!----------------- End Basket Continue ---------------->
   <!------------------------------------------------------>
  </div>
 </section>
 @if (!$cartItems->isEmpty())
  <div class="dlv" style="display: none">
   <span class="dlv_currency">{{ $cart->currency->symbol }}</span>
   <span class="dlv_value">{{ number_format($cart->sum_amount, 2, ',', '.') }}</span>
   @foreach ($cartItems as $cartItem)
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


 <!---------------------------------------------------------->
 <!--------------------- support button --------------------->
 <x-help-button />
 <!------------------- End support button ------------------->
 <!---------------------------------------------------------->
 <script src="/script/store/checkout.js" defer></script>
</div>
