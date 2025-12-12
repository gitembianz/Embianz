<div>
    <x-store-alert />

    <!--------------------Banner(Header Top)-------------------->
    @if (app()->has('global_header_top_text') && app('global_header_top_text') != '')
        <div class="banner">
            <div class="banner__container container">
                <p>
                    {!! app('global_header_top_text') !!}
                </p>
            </div>
        </div>
    @endif

    <!--------------------------Header-------------------------->
    <header>
        <div class="header__container container">
            <!-------------------------Logo------------------------->
            <a class="logo" href="{{ url('/') }}">
                <img
                    title="{{ app('global_site_name') }} logo"
                    loading="eager"
                    src="/images/store/svg/logo-dark.svg"
                    alt="Logo"
                >
            </a>

            @if (app()->has('global_one_product_page_system') && app('global_one_product_page_system') != 'true')
                <!---------------------NavMenu bar---------------------->
                <nav class="navbar__list">
                    <ul class="navbar__list">
                        @if (app()->has('global_show_on_header') && app('global_show_on_header') == 'true')
                            <li>
                                <a class="navbar__link"
                                   href="{{ route('products', ['categorySlug' => app('global_default_category')]) }}">
                                    @if (app()->has('label_header_allproducts'))
                                        {!! app('label_header_allproducts') !!}
                                    @endif
                                </a>
                            </li>
                        @endif

                        {{-- CATEGORIES PLACEHOLDER (desktop) --}}
                        <ul id="header-categories" class="navbar__list">
                            {{-- JS will inject category <li> elements here --}}
                        </ul>

                        @if (app()->has('global_header_display_blog') && app('global_header_display_blog') === 'true')
                            <li>
                                <a class="navbar__link" href="{{ route('blog') }}">
                                    @if (app()->has('label_blog_page'))
                                        {!! app('label_blog_page') !!}
                                    @endif
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            @endif

            <!---------------------Right-Buttons--------------------->
            <div class="header__buttons">
                <div class="head__button__left">
                    <button class="header__btn" id="menuOpen" aria-label="Open burger menu button">
                        <svg>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <a class="logo__hidden" href="{{ url('/') }}">
                        <img
                            title="{{ app('global_site_name') }} logo"
                            loading="eager"
                            src="/images/store/svg/logo-dark.svg"
                            alt="Logo"
                        >
                    </a>
                </div>

                <div class="head__button__right">
                    {{-- search button – now plain button, JS will handle opening --}}
                    @if (app()->has('global_display_search_button') && app('global_display_search_button') === 'true')
                        <button class="header__btn" id="searchOpen" aria-label="Open Searchbar button">
                            <svg>
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    @endif

                    @if (app()->has('global_support_on') && app('global_support_on') === 'true')
                        <a href="tel:@if (app()->has('global_support_phone_number')){!! app('global_support_phone_number') !!}@endif"
                           class="header__btn"
                           aria-label="Call">
                            <svg>
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                        </a>
                    @endif

                    {{-- wishlist button – static wrapper, quantity via JS later --}}
                    <button class="header__btn" id="wishOpen" aria-label="Open wishlist button">
                        <span id="wishlist-quantity">0</span>
                        <svg>
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                            </path>
                        </svg>
                    </button>

                    {{-- cart button – static wrapper, quantity via JS later --}}
                    <button class="header__btn" id="basketOpen" aria-label="Open cart button">
                        <span id="cart-quantity">0</span>
                        <svg>
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

{{-- Search Overlay (static, API-powered) --}}
<div class="search" id="searchList">
    <div class="search__container container" id="searchContent">
        <div class="search__top">
            <div class="search__input">
                <button class="search__button" id="searching" aria-label="go to search page">
                    <svg>
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
                <input id="searchInput" 
                       name="search" 
                       maxlength="100"
                       type="text" 
                       autocomplete="off"
                       placeholder="@if (app()->has('label_breadcrumbs_search')) {!! app('label_breadcrumbs_search') !!} @endif">
            </div>
            <button class="search__close" type="button" id="searchClose" aria-label="close search component">
                <svg>
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        {{-- JS will insert <ul class="search__list"> here when results come --}}
    </div>
    <button class="search__close--hidden" id="modalClose" type="button" aria-label="Close hidden general searchbar"></button>
</div>


    {{-- Cart products list placeholder --}}
    <div id="cart-products-list-container"></div>

    {{-- Wishlist products list placeholder --}}
    <div id="wishlist-products-list-container"></div>

    <!----------------------Menu (Leftbar)---------------------->
    <nav class="menu" id="menuList">
        <div class="menu__content" id="menuContent">
            <div class="menu__top">
                <button class="menu__close" id="menuClose" href="#">
                    Închide Meniul
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <ul class="menu__list">
                @if (app()->has('global_one_product_page_system') && app('global_one_product_page_system') != 'true')
                    {{-- MOBILE CATEGORIES PLACEHOLDER --}}
                    <div id="mobile-categories-container"></div>
                @endif

                @if (app()->has('global_header_display_blog') && app('global_header_display_blog') === 'true')
                    <li>
                        <a class="menu__link" href="{{ route('blog') }}">
                            @if (app()->has('label_blog_page'))
                                {!! app('label_blog_page') !!}
                            @endif
                        </a>
                    </li>
                @endif

                <li class="menufooter">Informații</li>
                {{-- static pages rendered on server are OK if same for everyone --}}
                @if (isset($staticpages) && $staticpages)
                    @foreach ($staticpages as $page)
                        @if ($page['sequence'] % 2 == 0)
                            <li class="menufooter__item">
                                <a href="{{ url($page['route']) }}">{{ $page['name'] }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif

                <li class="menufooter">Serviciu clienți</li>
                @if (isset($staticpages) && $staticpages)
                    @foreach ($staticpages as $page)
                        @if ($page['sequence'] % 2 != 0)
                            <li class="menufooter__item">
                                <a href="{{ url($page['route']) }}">{{ $page['name'] }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif

                <li class="menufooter__item"><a href="{{ url('/sitemap.xml') }}">Hartă Site</a></li>
                <li class="menufooter__item"><a target="blank" href="https://anpc.ro/">ANPC</a></li>
            </ul>
        </div>
        <button class="menu__hidden--close" id="menuHidden"></button>
    </nav>
    <!--------------------END-Menu (Leftbar)-------------------->
    <!---------------------------------------------------------->
<script src="/script/store/header-api.js" defer></script>
<script src="/script/store/header.js" defer></script>

</div>
