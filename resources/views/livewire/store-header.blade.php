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
                Încălzește-ți iarna cu stil! Descoperă confortul termic al produselor noastre la jumătate de preț. Alege
                să fii fresh în fiecare sezon!
            </p>
        </div>
    </div>
    <!------------------END-Banner(Header Top)------------------>
    <!---------------------------------------------------------->
    <!--------------------------Header-------------------------->
    <header>
        <div class="header__container container">
            <!-------------------------Logo------------------------->

            <a class="logo" href="{{ url('/') }}">
                <img src="/images/store/svg/noren-black.svg" alt="Embianz Logo">
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
                                    <div class="dropdown__item">
                                        <a class="dropdown__item--button"
                                            href="/storeproducts/{{ $subcategory->category_id }}">
                                            {{ $subcategory->name }}
                                            @if ($subcategory->category->subcategory->count() != 0)
                                                <svg>
                                                    <polyline points="9 18 15 12 9 6"></polyline>
                                                </svg>
                                            @endif
                                        </a>
                                        @if ($subcategory->category->subcategory->count() != 0)
                                            <div class="dropdown__item--list">
                                                @foreach ($subcategory->category->subcategory as $subsubCategory)
                                                    <a href="/storeproducts/{{ $subsubCategory->category_id }}">
                                                        {{ $subsubCategory->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a class="navbar__link" href="/storeproducts/{{ $category->id }}">
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
                {{-- search button --}}
                <button class="header__btn"
                    wire:click.prevent="@if ($active === false) $set('active', true) @else $set('active', false) @endif"
                    id="searchOpen">
                    <svg>
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
                <a class="logo__hidden" href="{{ url('/') }}">
                    <img src="/images/store/svg/noren-black.svg" alt="Site Logo">
                </a>
                {{-- wislist button --}}
                <button class="header__btn" wire:click="showwis" id="wishOpen">
                    @livewire('wishlist-quantity')
                    <svg>
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                        </path>
                    </svg>
                </button>
                {{-- cart button --}}
                <button class="header__btn" wire:click="showcart" id="basketOpen">
                    @if ($cart)
                        @livewire('cart-quantity', ['cart' => $cart])
                    @endif
                    <svg>
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
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
                    <input id="searchInput" wire:model.debounce.300ms="search" type="text" placeholder="Cauta...">
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
                                    <a class="search__link" href="/product/{{ $product->id }}">
                                        @if ($product->media->first() != null)
                                            <img src="/{{ $product->media->first()->path }}/{{ $product->media->first()->name }}"
                                                alt="{{ $product->media->first()->path }}">
                                        @else
                                            <img src="/images/store/default/default70.webp" alt="something wrong">
                                        @endif

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
                                        @if ($category->media->first() != null)
                                            <img src="/{{ $category->media->first()->path }}/{{ $category->media->first()->name }}"
                                                alt="{{ $category->media->first()->path }}">
                                        @else
                                            <img src="/images/store/default/default70.webp" alt="something wrong">
                                        @endif

                                        <div class="search__link--text">
                                            <div class="search__link--bottom">
                                                <h4>{{ $category->name }}</h4>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    @else
                        <span>{{ __('Niciun element gasit') }}</span>
                    @endif
                </ul>
            @endif
        </div>
        <button class="search__close--hidden" id="modalClose" type="button" wire:click.prevent="close">
        </button>
    </div>
    <!-----------------------END-Searchbar---------------------->
    <!---------------------------------------------------------->
    <!---------------------Basket (Leftbar)--------------------->
    @livewire('cart-products-list')
    <!-------------------END-Basket (Leftbar)------------------->
    <!---------------------------------------------------------->
    <!----------------------Wish (Leftbar)---------------------->
    @livewire('wishlist-products-list')
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
                        <div class="dropmenu">
                            <div class="dropmenu__button">
                                <a class="dropmenu__button--link" href="/storeproducts/{{ $category->id }}">
                                    @if ($category->media->first())
                                        <img class="cart__list--img"
                                            src="/{{ $category->media->first()->path }}{{ $category->media->first()->name }}"
                                            alt="{{ $category->media->first()->path }}">
                                    @else
                                        <img class="heart__list--img" src="/images/store/default/default70.webp"
                                            alt="something wrong">
                                    @endif
                                    <h4>{{ $category->name }}</h4>
                                </a>
                                <button class="dropmenu__open" href="#">
                                    <svg>
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                            </div>
                            <div class="dropmenu__list">
                                @foreach ($category->subcategory as $subcategory)
                                    <div class="submenu">
                                        <div class="submenu__button">
                                            <a class="submenu__button--link"
                                                href="/storeproducts/{{ $subcategory->category_id }}">
                                                @if ($subcategory->category->media->first() != null)
                                                    <img src="/{{ $subcategory->category->media->first()->path }}/{{ $subcategory->category->media->first()->name }}"
                                                        alt="{{ $subcategory->category->media->first()->path }}">
                                                @else
                                                    <img src="/images/store/default/default70.webp"
                                                        alt="something wrong">
                                                @endif
                                                <h4>{{ $subcategory->name }}</h4>
                                            </a>
                                            @if ($subcategory->category->subcategory->count() != 0)
                                                <button class="submenu__open" href="#">
                                                    <svg>
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                        @if ($subcategory->category->subcategory->count() != 0)
                                            <div class="submenu__list">
                                                @foreach ($subcategory->category->subcategory as $subsubCategory)
                                                    <a class="submenu__link"
                                                        href="/storeproducts/{{ $subsubCategory->category_id }}">
                                                        @if ($subsubCategory->category->media->first() != null)
                                                            <img src="/{{ $subsubCategory->category->media->first()->path }}/{{ $subsubCategory->category->media->first()->name }}"
                                                                alt="{{ $subsubCategory->category->media->first()->path }}">
                                                        @else
                                                            <img src="/images/store/default/default70.webp"
                                                                alt="something wrong">
                                                        @endif
                                                        <h4>{{ $subsubCategory->name }}</h4>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a class="menu__link" href="/storeproducts/{{ $category->id }}">
                            @if ($category->media->first() != null)
                                <img src="/{{ $category->media->first()->path }}/{{ $category->media->first()->name }}"
                                    alt="{{ $category->media->first()->path }}">
                            @else
                                <img src="/images/store/default/default70.webp" alt="something wrong">
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
    <script src="/script/store/header.js" async defer></script>
</div>
