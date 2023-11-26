<div>
    <x-store-alert />
    <!-- This is the Header;
    the <header> tag encompasses the Logo and component-calling buttons located below,
    such as the Searchbar, Shopping Basket, WishList, and Burger Menu. The styles for
    this are stored in the "header" folder in SCSS, neatly organized to prevent confusion.
    Similarly, its JavaScript functionality is implemented in the "header.js" file. -->
    <!---------------------------------------------------------->
    <!--------------------Banner(Header Top)-------------------->
    <div class="banner">
        <div class="banner__container container">
            <p>
                Dublu confort, jumătate de preț! Ofertă limitată: 2 sticle la prețul uneia singure. Profită acum!
            </p>
        </div>
    </div>
    <!------------------END-Banner(Header Top)------------------>
    <!---------------------------------------------------------->
    <!--------------------------Header-------------------------->
    <header>
        <div class="header__container container">
            <!-------------------------Logo------------------------->
            <a class="logo" href="{{ url("/") }}">
                <img src="/images/store/logo.svg" alt="Embianz Logo">
            </a>
            <!-----------------------END-Logo----------------------->
            <!------------------------------------------------------>
            <!---------------------NavMenu bar---------------------->
            <div class="navbar__list">
                @foreach ($categories as $category)
                    @if ($category->subcategory->count() != 0)
                        <div class="dropdown">
                            <a class="dropdown__button" href="/storeproducts/{{ $category->id }}">
                                {{ $category->name }}
                                <svg>
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </a>
                            <div class="dropdown__list">
                                @foreach ($category->subcategory as $subcategory)
                                    <a class="dropdown__item"
                                        href="/storeproducts/{{ $subcategory->category_id }}">{{ $subcategory->category }}</a>
                                @endforeach

                            </div>
                        </div>
                    @else
                        <a class="navbar__link" href="/storeproducts/{{ $category->id }}"">
                            {{ $category->name }}
                        </a>
                    @endif
                @endforeach
            </div>
            <!---------------------NavMenu bar--------------------->
            <!------------------------------------------------------>
            <!---------------------Right-Buttons--------------------->
            <div class="header__buttons">
                <button class="header__btn" id="menuOpen">
                    <svg>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <button class="header__btn"
                    wire:click.prevent="@if ($active === false) $set('active', true) @else $set('active', false) @endif"
                    id="searchOpen">
                    <svg>
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
                <button class="header__btn" wire:click="$set('showcart', true)" id="basketOpen">
                    @if ($total > 0)
                        <span class="header__count" id="cartCount">{{ $total }}</span>
                    @endif
                    <svg>
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </button>
                <button class="header__btn" wire:click="$set('showwis', true)" id="wishOpen">
                    @if ($wishlistitems->count() > 0)
                        <span class="header__count" id="wishlistCount">{{ $wishlistitems->count() }}</span>
                    @endif
                    <svg>
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                        </path>
                    </svg>
                </button>
            </div>
            <!-------------------END-Right-Buttons------------------->
        </div>
    </header>
    <!------------------------END-Header------------------------>
    <!---------------------------------------------------------->
    <!-------------------------Searchbar------------------------>
    <div class="search @if ($active) active @endif" id="searchList">
        <div class="search__container container" id="searchContent">
            <div class="search__top">
                <div class="search__input">
                    <svg>
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input id="searchInput" wire:model.debounce.300ms="search" type="text"
                        placeholder="Cauta produsul perfect...">
                </div>
                <button class="search__close" type="button" id="searchClose" wire:click.prevent="close">
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            @if ($search)
                <ul class="search__list">
                    @if (count($objects) > 0 || count($cats) > 0)
                        @if (count($objects) > 0)
                            @foreach ($objects as $product)
                                <li class="search__item">
                                    <a class="search__link" href="#">
                                        @if (count($product->media) > 0)
                                            @foreach ($product->media as $media)
                                                @if ($media->location->location == "search")
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
                                        {{-- <img src="/images/store/background-bottle-min2.webp" alt="imaginea produsului"> --}}
                                        <div class="search__link--text">
                                            <div class="search__link--top">
                                                <p>{{ $product->short_description }}</p>
                                            </div>
                                            <div class="search__link--bottom">
                                                <h4>{{ $product->name }}</h4>
                                                @if ($product->product_prices->first())
                                                <span>
                                                    @php
                                                        $price = $product->product_prices->first();
                                                        $currency = $price->pricelist->currency->name;
                                                    @endphp
                                                    @if ($price)
                                                        {{ $price->value }} {{ $currency }}
                                                    @endif
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                        @if (count($cats) > 0)
                            @foreach ($cats as $category)
                                <li class="search__item">
                                    <a class="search__link" href="/storeproducts/{{ $category->id }}">
                                        @if (count($category->media) > 0)
                                            @foreach ($category->media as $media)
                                                @if ($media->location->location == "search")
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
                                        <div class="search__link--text">
                                            <div class="search__link--top">
                                                <p>{{ $category->short_description }}</p>
                                            </div>
                                            <div class="search__link--bottom">
                                                <h4>{{ $category->name }}</h4>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    @else
                        <span>{{ __("Niciun element gasit") }}</span>
                    @endif
                </ul>
            @endif
        </div>
    </div>
    <!-----------------------END-Searchbar---------------------->
    <!---------------------------------------------------------->
    <!---------------------Basket (Leftbar)--------------------->
    <div class="leftbar @if ($showcart) active @endif" id="basketList">
        <button class="leftbar__hidden--close" wire:click="$set('showcart', false)"></button>
        <div class="leftbar__content" id="basketContent">
            <div class="leftbar__top">
                <a class="leftbar__button" href="/cart">Vizualizare cos</a>
                <button class="leftbar__close" id="basketClose" wire:click="$set('showcart', false)">
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>


            <ul class="leftbar__list">
                @if ($cartItems->isEmpty())
                    <span class="leftbar__empty">Cosul de cumparaturi este gol</span>
                @else
                    <?php $total = 0; ?>
                    @foreach ($cartItems as $cartItem)
                        <li class="leftbar__item">
                            <a class="leftbar__link" href="/product/{{ $cartItem->product->id }}">
                                @if (count($cartItem->product->media) > 0)
                                    @foreach ($cartItem->product->media as $media)
                                        @if ($media->location->location == "main")
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

                                <div class="leftbar__link--text">
                                    <h4>{{ $cartItem->product->name }}</h4>
                                    <span>
                                        @php
                                            $price = $cartItem->product->product_prices->first();
                                            $currency = $price->pricelist->currency->name;
                                        @endphp
                                        @if ($price)
                                            {{ $price->value }} {{ $currency }}
                                        @else
                                            indisponibil
                                        @endif
                                        x
                                        {{ $cartItem->quantity }}
                                    </span>
                                </div>
                            </a>
                            <button class="leftbar__delete" type="button"
                                wire:click="removeFromCart({{ $cartItem->product->id }})">
                                <svg>
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </li>
                        <?php $total += $cartItem->price * $cartItem->quantity; ?>
                    @endforeach
                @endif
            </ul>

            @if (!$cartItems->isEmpty())
                <div class="leftbar__total">
                    <h5 class="leftbar__total--text">Total: <span>{{ $total }} {{ $currency }}</span></h5>
                    <a class="leftbar__button" wire:click.prevent="continue">Finalizare Comanda</a>
                </div>
            @endif
        </div>
    </div>
    <!-------------------END-Basket (Leftbar)------------------->
    <!---------------------------------------------------------->
    <!----------------------Wish (Leftbar)---------------------->
    <div class="leftbar @if ($showwis) active @endif" id="wishList">
        <button class="leftbar__hidden--close" wire:click="$set('showwis', false)"></button>
        <div class="leftbar__content" id="wishContent">
            <div class="leftbar__top">
                <a class="leftbar__button" href="/wishlist">Vizualizare favorite</a>
                <button class="leftbar__close" wire:click="$set('showwis', false)" id="wishClose" href="#">
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <ul class="leftbar__list">
                @if ($wishlistitems->isEmpty())
                    <span class="leftbar__empty">No favorites products </span>
                @else
                    @foreach ($wishlistitems as $product)
                        <li class="leftbar__item">
                            <a class="leftbar__link wishlist__link" href="/product/{{ $product->id }}">
                                @if (count($product->media) > 0)
                                    @foreach ($product->media as $media)
                                        @if ($media->location->location == "main")
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
                                <div class="leftbar__link--text">
                                    <h4>{{ $product->name }}</h4>
                                </div>
                            </a>
                            <button class="leftbar__delete" wire:click="removeFromWishlist({{ $product->id }})">
                                <svg>
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
    <!--------------------END-wish (Leftbar)-------------------->
    <!---------------------------------------------------------->
    <!----------------------Menu (Leftbar)---------------------->
    <div class="menu" id="menuList">
        <div class="menu__content" id="menuContent">
            <div class="menu__top">
                <button class="menu__close" id="menuClose" href="#">
                    Inchide Meniul
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="menu__list">
                @foreach ($categories as $category)
                    @if ($category->subcategory->count() != 0)
                        <!------------------ End Dropdown (Menu) ------------------>
                        <div class="dropmenu">
                            <div class="dropmenu__button">
                                <a class="dropmenu__button--link" href="/storeproducts/{{ $category->id }}">
                                    @if (count($category->media) > 0)
                                        @foreach ($category->media as $media)
                                            @if ($media->location->location == "main")
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
                                    <h4> {{ $category->name }}</h4>
                                </a>
                                <button class="dropmenu__open" href="#">
                                    <svg>
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                            </div>
                            <div class="dropmenu__list">
                                @foreach ($category->subcategory as $subcategory)
                                    <a class="dropmenu__link" href="#">
                                        @if (count($subcategory->parrent->media) > 0)
                                            @foreach ($subcategory->parrent->media as $media)
                                                @if ($media->location->location == "main")
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
                                        <h4>{{ $subcategory->category }}</h4>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-------------------- Dropdown (Menu) -------------------->
                        <a class="menu__link" href="/storeproducts/{{ $category->id }}">
                            @if (count($category->media) > 0)
                                @foreach ($category->media as $media)
                                    @if ($media->location->location == "main")
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
                            <h4> {{ $category->name }}</h4>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <!--------------------END-Menu (Leftbar)-------------------->
    <!---------------------------------------------------------->
</div>
