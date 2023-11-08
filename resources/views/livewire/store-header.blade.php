 <div>
     <div class="container header">
         <a href="{{ url('/') }}"><svg class="logo" xmlns="http://www.w3.org/2000/svg"
                 xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="1280" height="1024"
                 viewBox="0 0 1280 1024" xml:space="preserve" alt="ecosticle.ro">
                 <defs>
                 </defs>
                 <g transform="matrix(1 0 0 1 640 512)" id="background-logo">
                     <rect
                         style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(255,255,255); fill-opacity: 0; fill-rule: nonzero; opacity: 1;"
                         paint-order="stroke" x="-640" y="-512" rx="0" ry="0" width="1280"
                         height="1024" />
                 </g>
                 <g transform="matrix(4.545629534834756 0 0 4.545629534834756 640.6479799851742 512.3702742772424)"
                     id="logo-logo">
                     <g style="" paint-order="stroke">
                         <g transform="matrix(1.4162867447099505 0 0 1.4162867447099505 0 0)">
                             <path
                                 style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(26,105,26); fill-rule: nonzero; opacity: 1;"
                                 paint-order="stroke" transform=" translate(-297.19335939999996, -421.5514536186913)"
                                 d="M 283.0551758 419.6142578 L 328.5664063 401.78955080000003 C 324.6835938 395.84033200000005 319.1972657 391.315918 312.93554689999996 388.578125 C 294.8208008 380.6601562 273.64501959999996 388.9389648 265.72558599999996 407.0576172 C 259.4848633 421.33398439999996 263.2983399 437.5014649 274.0610352 447.5922852 C 285.0200196 457.8686524 307.2226563 461.1831055 318.4492188 450.9272461 C 308.7382813 454.7524414 298.703125 454.1420899 291.1806641 450.4614258 C 311.1738282 411.1860352 370.4550782 418.6079102 392.3867188 447.1704102 C 348.515625 436.0073243 315.2832032 493.6225586 270.27783209999996 463.2817383 C 266.39843759999997 460.66748049999995 262.8798828999999 457.49560549999995 259.8720704 453.9077149 C 253.0771485 448.57373049999995 244.96484379999998 445.5024415 235.54882819999997 445.5209961 C 246.60693369999998 449.2475586 256.16748049999995 455.2202149 261.44287119999996 467.621582 C 231.215332 477.5004883 222.7910156 445.4379883 202 439.0063477 C 216.4086914 429.1879883 235.699707 427.3403321 249.5209961 433.5288086 C 246.94091799999998 423.1108398 247.6474609 411.793457 252.2797852 401.1962891 C 263.4550782 375.6298828 293.2250977 363.9541016 318.796875 375.1323243 C 330.6523438 380.3149415 340.5292969 390.0146485 345.6152344 403.0019532 L 347.9296875 409.9741212 L 274.9882812 438.54248060000003 C 272.5532227 431.4145508 276.4277344 422.2104492 283.0551758 419.6142578 L 283.0551758 419.6142578 z"
                                 stroke-linecap="round" />
                         </g>
                     </g>
                 </g>
             </svg></a>

         <nav class="menu sidebar" id="menu" role="navigation">
             <ul class="menu__list sidebar__content container" id="menuContent">
                 <button class="sidebar__close" id="menuClose" aria-label="Close Menu">
                     <svg aria-hidden="true">
                         <line x1="18" y1="6" x2="6" y2="18"></line>
                         <line x1="6" y1="6" x2="18" y2="18"></line>
                     </svg>
                 </button>
                 @foreach ($categories as $category)
                     <li>
                         @if ($category->subcategory->count() != 0)
                             <div class="menu__item">
                                 <a href="/storeproducts/{{ $category->id }}">
                                     @if (count($category->media) > 0)
                                         @foreach ($category->media as $media)
                                             @if ($media->location->location == 'main')
                                                 @if ($media->external)
                                                     <img src="{{ $media->path }}" alt="{{ $media->path }}">
                                                 @else
                                                     <img src="/{{ $media->path }}{{ $media->name }}"
                                                         alt="{{ $media->path }}">
                                                 @endif
                                                 <?php break; ?>
                                             @endif
                                         @endforeach
                                     @else
                                         <img src="/images/store/default/default.svg" alt="something wrong">
                                     @endif
                                     {{ $category->name }}
                                 </a>
                                 <button>
                                     <svg aria-hidden="true">
                                         <polyline points="6 9 12 15 18 9"></polyline>
                                     </svg>
                                 </button>
                             </div>
                             <ul class="menu__sub" role="menu" aria-hidden="true" aria-expanded="false">
                                 <div class="menu__sub--wrapper">
                                     <div class="menu__sub--list">
                                         @foreach ($category->subcategory as $subcategory)
                                             <a class="menu__sub--item"
                                                 href="/storeproducts/{{ $subcategory->category_id }}" role="menuitem">
                                                 @if (count($subcategory->parrent->media) > 0)
                                                     @foreach ($subcategory->parrent->media as $media)
                                                         @if ($media->location->location == 'main')
                                                             @if ($media->external)
                                                                 <img src="{{ $media->path }}"
                                                                     alt="{{ $media->path }}">
                                                             @else
                                                                 <img src="/{{ $media->path }}{{ $media->name }}"
                                                                     alt="{{ $media->path }}">
                                                             @endif
                                                             <?php break; ?>
                                                         @endif
                                                     @endforeach
                                                 @else
                                                     <img src="/images/store/default/default.svg" alt="something wrong">
                                                 @endif{{ $subcategory->category }}
                                             </a>
                                         @endforeach
                                     </div>
                                 </div>
                             </ul>
                         @else
                             {{-- <div class="menu__item"> --}}
                             <a href="/storeproducts/{{ $category->id }}">
                                 @if (count($category->media) > 0)
                                     @foreach ($category->media as $media)
                                         @if ($media->location->location == 'main')
                                             @if ($media->external)
                                                 <img src="{{ $media->path }}" alt="{{ $media->path }}">
                                             @else
                                                 <img src="/{{ $media->path }}{{ $media->name }}"
                                                     alt="{{ $media->path }}">
                                             @endif
                                             <?php break; ?>
                                         @endif
                                     @endforeach
                                 @else
                                     <img src="/images/store/default/default.svg" alt="something wrong">
                                 @endif
                                 {{ $category->name }}
                             </a>
                             {{-- </div> --}}
                         @endif

                     </li>
                 @endforeach
             </ul>
         </nav>
         <div class="header__buttons">
             <button class="sidebar__open" id="menuOpen" aria-label="Open Menu">
                 <svg aria-hidden="true">
                     <line x1="3" y1="12" x2="21" y2="12"></line>
                     <line x1="3" y1="6" x2="21" y2="6"></line>
                     <line x1="3" y1="18" x2="21" y2="18"></line>
                 </svg>
             </button>
             <button class="search__open"
                 wire:click.prevent="@if ($active === false) $set('active', true) @else $set('active', false) @endif"
                 id="searchOpen" data-tooltip="apăsați „ / ” pentru a deschide" aria-label="Open Search">
                 <svg aria-hidden="true">
                     <circle cx="11" cy="11" r="8"></circle>
                     <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                 </svg>
             </button>
             <div class="cart">
                 <button class="cart__btn" style="position: relative" wire:click="cartshow" id="cartBtn"
                     aria-label="Open Cart">
                     <svg aria-hidden="true">
                         <circle cx="9" cy="21" r="1"></circle>
                         <circle cx="20" cy="21" r="1"></circle>
                         <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                     </svg>
                     @if ($total > 0)
                         <span class="alert-count" id="cartCount">{{ $total }}</span>
                     @endif
                 </button>
                 <ul class="cart__list @if ($showcart) show @endif" id="cartContent"
                     aria-label="Cart Items">
                     <li>
                         <a href="/cart" class="cart__list--item">
                             <h4 class="cart__list--name">Cos de cumparaturi</h4>
                             <svg>
                                 <circle cx="9" cy="21" r="1"></circle>
                                 <circle cx="20" cy="21" r="1"></circle>
                                 <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                             </svg>
                         </a>
                     </li>
                     @if ($cartItems->isEmpty())
                         <li class="heart__list--item">
                             <span style="color: black">Cos de cumparaturi gol</span>
                         </li>
                     @else
                         @foreach ($cartItems as $cartItem)
                             <li>
                                 <div class="cart__list--item">
                                     @if (count($cartItem->product->media) > 0)
                                         @foreach ($cartItem->product->media as $media)
                                             @if ($media->location->location == 'main')
                                                 @if ($media->external)
                                                     <img class="cart__list--img" src="{{ $media->path }}"
                                                         alt="{{ $media->path }}">
                                                 @else
                                                     <img class="cart__list--img"
                                                         src="/{{ $media->path }}{{ $media->name }}"
                                                         alt="{{ $media->path }}">
                                                 @endif
                                                 <?php break; ?>
                                             @endif
                                         @endforeach
                                     @else
                                         <img class="cart__list--img" src="/images/store/default/default.svg"
                                             alt="something wrong">
                                     @endif
                                     <a class="cart__list--text"
                                         href="/product/{{ $cartItem->product->id }}"><span>{{ $cartItem->product->name }}</span></a>
                                     <span class="cart__list--much">
                                         @php
                                             $price = $cartItem->product->product_prices->first();
                                         @endphp
                                         @if ($price)
                                             {{ $price->value }} {{ $price->pricelist->currency->name }}
                                         @else
                                             indisponibil
                                         @endif
                                     </span>
                                     <span class="cart__list--much">x {{ $cartItem->quantity }}</span>
                                     <button class="cart__list--delete"
                                         wire:click="removeFromCart({{ $cartItem->product->id }})">
                                         <svg>
                                             <line x1="18" y1="6" x2="6" y2="18">
                                             </line>
                                             <line x1="6" y1="6" x2="18" y2="18">
                                             </line>
                                         </svg>
                                     </button>
                                 </div>
                             </li>
                         @endforeach
                     @endif

                 </ul>
             </div>
             <div class="heart">
                 <button class="heart__btn" wire:click="wishlistshow" id="heartBtn" aria-label="Open heart"
                     style="position: relative">
                     <svg aria-hidden="true">
                         <path
                             d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                         </path>
                     </svg>
                     @if ($wishlistitems->count() > 0)
                         <span class="alert-count" id="wishlistCount">{{ $wishlistitems->count() }}</span>
                     @endif

                 </button>
                 <ul class="heart__list @if ($showwis) show @endif" id="heartContent"
                     aria-label="heart Items">

                     @if ($wishlistitems->isEmpty())
                         <li class="heart__list--item">
                             <span style="color: white">Fără produse favorite</span>
                         </li>
                     @else
                         <li>
                             <a href="/wislist" class="heart__list--item">
                                 <h4 class="heart__list--name">Favorite</h4>
                             </a>
                         </li>
                         @foreach ($wishlistitems as $product)
                             <li>
                                 <div class="heart__list--item">
                                     @if (count($product->media) > 0)
                                         @foreach ($product->media as $media)
                                             @if ($media->location->location == 'main')
                                                 @if ($media->external)
                                                     <img class="heart__list--img" src="{{ $media->path }}"
                                                         alt="{{ $media->path }}">
                                                 @else
                                                     <img class="heart__list--img"
                                                         src="/{{ $media->path }}{{ $media->name }}"
                                                         alt="{{ $media->path }}">
                                                 @endif
                                                 <?php break; ?>
                                             @endif
                                         @endforeach
                                     @else
                                         <img class="heart__list--img" src="/images/store/default/default.svg"
                                             alt="something wrong">
                                     @endif
                                     <a class="heart__list--text"
                                         href="/product/{{ $product->id }}"><span>{{ $product->name }}</span></a>
                                     <button class="heart__list--delete"
                                         wire:click="removeFromWishlist({{ $product->id }})">
                                         <svg>
                                             <line x1="18" y1="6" x2="6" y2="18">
                                             </line>
                                             <line x1="6" y1="6" x2="18" y2="18">
                                             </line>
                                         </svg>
                                     </button>
                                 </div>
                             </li>
                         @endforeach
                     @endif

                 </ul>
             </div>
         </div>
         <div class="search @if ($active) active @endif" id="search">
             <div class="search__content">
                 <input id="searchInput" wire:model.debounce.300ms="search" class="search__input" type="text"
                     name="search" aria-labelledby="searchInput" placeholder="cauta...">
                 <button type="button" wire:click.prevent="close" id="searchClose"
                     data-tooltip-down="press ESC to close" aria-label="Close Search">
                     <svg aria-hidden="true">
                         <line x1="18" y1="6" x2="6" y2="18"></line>
                         <line x1="6" y1="6" x2="18" y2="18"></line>
                     </svg>
                 </button>
             </div>
             @if ($search)
                 <ul class="search__container">
                     @if (count($objects) > 0 || count($cats) > 0)
                         @if (count($objects) > 0)
                             @foreach ($objects as $product)
                                 <li>
                                     <a class="search__container--item" href="/product/{{ $product->id }}">
                                         @if (count($product->media) > 0)
                                             @foreach ($product->media as $media)
                                                 @if ($media->location->location == 'search')
                                                     @if ($media->external)
                                                         <img src="{{ $media->path }}" alt="{{ $media->path }}">
                                                     @else
                                                         <img src="/{{ $media->path }}{{ $media->name }}"
                                                             alt="{{ $media->path }}">
                                                     @endif
                                                     <?php break; ?>
                                                 @endif
                                             @endforeach
                                         @else
                                             <img src="/images/store/default/default.svg" alt="something wrong">
                                         @endif
                                         <p> {{ $product->name }}</p>
                                     </a>
                                 </li>
                             @endforeach
                         @endif
                         @if (count($cats) > 0)
                             @foreach ($cats as $category)
                                 <li>
                                     <a class="search__container--item" href="/storeproducts/{{ $category->id }}">
                                         @if (count($category->media) > 0)
                                             @foreach ($category->media as $media)
                                                 @if ($media->location->location == 'search')
                                                     @if ($media->external)
                                                         <img src="{{ $media->path }}" alt="{{ $media->path }}">
                                                     @else
                                                         <img src="/{{ $media->path }}{{ $media->name }}"
                                                             alt="{{ $media->path }}">
                                                     @endif
                                                     <?php break; ?>
                                                 @endif
                                             @endforeach
                                         @else
                                             <img src="/images/store/default/default.svg" alt="something wrong">
                                         @endif
                                         <p> {{ $category->name }}</p>
                                     </a>
                                 </li>
                             @endforeach
                         @endif
                     @else
                         <li>
                             {{ __('Niciun element gasit') }}
                         </li>
                     @endif
                 </ul>
             @endif
         </div>
     </div>
 </div>
