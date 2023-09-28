 <div class="cart__page container">
     <h2 class="section__title">Shopping Basket</h2>
     <div class="cart-page__list">
         @if ($cartItems->isEmpty())
             <p>No products</p>
         @else
             @foreach ($cartItems as $cartItem)
                 <article class="cart-page__item">
                     @if (count($cartItem->product->media) > 0)
                         @foreach ($cartItem->product->media as $media)
                             @if ($media->location->location == 'main')
                                 @if ($media->external)
                                     <img src="{{ $media->path }}" alt="{{ $media->path }}">
                                 @else
                                     <img src="/{{ $media->path }}{{ $media->name }}" alt="{{ $media->path }}">
                                 @endif
                                 <?php break; ?>
                             @endif
                         @endforeach
                     @else
                         <img src="/images/store/default/default.svg" alt="something wrong">
                     @endif
                     <div class="cart-page--text">
                         <h3>{{ $cartItem->product->name }}</h3>
                         <span>
                             @if ($cartItem->product->product_prices->first() !== null)
                                 {{ $cartItem->product->product_prices->first()->value }}
                                 {{ $cartItem->product->product_prices->first()->pricelist->currency->first()->name }}
                             @else
                                 price unavailable
                             @endif
                         </span>
                         {{-- <span>
                    <span>107.98 lei</span>
                    89,99 lei
                </span> --}}
                         <div class="cart-page--bundle">
                             <div class="product__count">
                                 <button wire:click="decrement({{ $cartItem->product->id }})" id="countDecrease">
                                     <svg>
                                         <line x1="5" y1="12" x2="19" y2="12"></line>
                                     </svg>
                                 </button>
                                 <input type="number" name="count" id="count" min="1" readonly
                                     value="{{ $cartItem->quantity }}">
                                 <button wire:click="increment({{ $cartItem->product->id }})" id="countIncrease">
                                     <svg>
                                         <line x1="12" y1="5" x2="12" y2="19"></line>
                                         <line x1="5" y1="12" x2="19" y2="12"></line>
                                     </svg>
                                 </button>
                             </div>
                             <button class="cart-page__delete"
                                 wire:click="removeFromCart({{ $cartItem->product->id }})">
                                 <svg>
                                     <polyline points="3 6 5 6 21 6"></polyline>
                                     <path
                                         d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                     </path>
                                 </svg>
                             </button>
                             <button class="product__item--heart @if ($cartItem->product->wishlists->where('session_id', $session_id)->isNotEmpty()) active @endif"
                                 aria-label="add to favorites"
                                 wire:click="toggleWishlist({{ $cartItem->product->id }})">
                                 <svg>
                                     <path
                                         d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                     </path>
                                 </svg>
                             </button>
                         </div>
                     </div>
                 </article>
             @endforeach
         @endif

     </div>
     <div class="cart__column">
         <div class="cart__buy">
             <h3>
                 Sumar comandă
             </h3>
             <h5>
                 Cost produse:
             </h5>
             <span>
                 629,93 Lei
             </span>
             <h5>
                 Cost livrare:
             </h5>
             <span>
                 GRATUIT
             </span>
             <h5>
                 Total:
             </h5>
             <span>
                 629,93 Lei
             </span>
             <a href="{{ route('order') }}">Continue</a>
         </div>
         <div class="cart__voucher">
             <h4>
                 Ai un voucher sau card cadou?
             </h4>
             <form action="#">
                 <input type="text" name="voucher">
                 <button>
                     <svg>
                         <polyline points="9 18 15 12 9 6"></polyline>
                     </svg>
                 </button>
             </form>
         </div>
     </div>
 </div>
