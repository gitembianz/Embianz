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
    <img title="{{ app('global_site_name') }} logo" loading="eager" src="/images/store/svg/logo-dark.svg" alt="Logo">
   </a>
   <!---------------------NavMenu bar---------------------->
   <nav class="navbar__list">
    <ul class="navbar__list">
     @if (app()->has('global_show_on_header') && app('global_show_on_header') == 'true')
      <li>
       <a class="navbar__link" href="{{ route('products', ['categorySlug' => app('global_default_category')]) }}">
        @if (app()->has('label_header_allproducts'))
         {!! app('label_header_allproducts') !!}
        @endif
       </a>
      </li>
     @endif
     @foreach ($categories as $category)
      @if ($category->subcategory->count() != 0)
       <li>
        <div class="dropdown">
         <a class="dropdown__button"
          href="{{ route('products', ['categorySlug' => $category->seo_id !== null && $category->seo_id !== '' ? $category->seo_id : $category->id]) }}">
          {!! $category->name !!}
          <svg>
           <polyline points="6 9 12 15 18 9"></polyline>
          </svg>
         </a>
         <ul class="dropdown__list">
          @foreach ($category->subcategory->sortBy(function ($subcategory) {
        return $subcategory->category->sequence;
    }) as $subcategory)
           <li class="dropdown__item">
            <a class="dropdown__item--button"
             href="{{ route('products', ['categorySlug' => $subcategory->category->seo_id !== null && $subcategory->category->seo_id !== '' ? $subcategory->category->seo_id : $subcategory->category->id]) }}">
             {!! $subcategory->category->name !!}
             @if ($subcategory->category->subcategory->count() != 0)
              <svg>
               <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
             @endif
            </a>
            @if ($subcategory->category->subcategory->count() != 0)
             <div class="dropdown__item--list">
              @foreach ($subcategory->category->subcategory->sortBy(function ($subsubCategory) {
        return $subsubCategory->category->sequence;
    }) as $subsubCategory)
               <a class="dropdown__item--link"
                href="{{ route('products', ['categorySlug' => $subsubCategory->category->seo_id !== null && $subsubCategory->category->seo_id !== '' ? $subsubCategory->category->seo_id : $subsubCategory->category->id]) }}">
                {!! $subsubCategory->category->name !!}
               </a>
              @endforeach
             </div>
            @endif
           </li>
          @endforeach
         </ul>
        </div>
       </li>
      @else
       <li>
        <a class="navbar__link"
         href="{{ route('products', ['categorySlug' => $category->seo_id !== null && $category->seo_id !== '' ? $category->seo_id : $category->id]) }}">
         {!! $category->name !!}
        </a>
       </li>
      @endif
     @endforeach
    </ul>
   </nav>
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
      <img title="{{ app('global_site_name') }} logo" loading="eager" src="/images/store/svg/logo-dark.svg"
       alt="Logo">
     </a>
    </div>
    <div class="head__button__right">
     {{-- search button --}}
     <button class="header__btn" wire:click="$emit('showsearch')" id="searchOpen" aria-label="Open Searchbar button">
      <svg>
       <circle cx="11" cy="11" r="8"></circle>
       <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
      </svg>
     </button>
     @if (app()->has('global_support_on') && app('global_support_on') === 'true')
      <a href="tel: @if (app()->has('global_support_phone_number')) {!! app('global_support_phone_number') !!} @endif" class="header__btn"
       aria-label="Call">
       <svg>
        <path
         d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
        </path>
       </svg>
      </a>
     @endif


     {{-- wislist button --}}
     <button class="header__btn" wire:click="$emit('showwis')" id="wishOpen" aria-label="Open wishlist button">
      @livewire('wishlist-quantity')
      <svg>
       <path
        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
       </path>
      </svg>
     </button>
     {{-- cart button --}}
     <button class="header__btn" wire:click="$emit('showcart')" id="basketOpen" aria-label="Open cart button">
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
   </div>
  </div>
 </header>
 <!-------------------------Searchbar------------------------>
 @livewire('general-search')

 @livewire('cart-products-list')

 @livewire('wishlist-products-list')
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
    @foreach ($categories as $category)
     @if ($category->subcategory->count() != 0)
      <li class="dropmenu">
       <div class="dropmenu__button">
        <a class="dropmenu__button--link"
         href="{{ route('products', ['categorySlug' => $category->seo_id !== null && $category->seo_id !== '' ? $category->seo_id : $category->id]) }}">
         @if ($category->media->where('type', 'min')->first())
          <img title="{{ strip_tags($category->name) }}" loading="eager" class="cart__list--img"
           src="/{{ $category->media->where('type', 'min')->first()->path }}{{ $category->media->where('type', 'min')->first()->name }}"
           alt="{{ $category->media->where('type', 'min')->first()->name }} {{ strip_tags($category->name) }}">
         @endif
         <h4 style="margin-left: 7px">{!! $category->name !!}</h4>
        </a>
        <button class="dropmenu__open" href="#">
         <svg>
          <polyline points="6 9 12 15 18 9"></polyline>
         </svg>
        </button>
       </div>
       <ul class="dropmenu__list">
        @foreach ($category->subcategory->sortBy(function ($subcategory) {
          return $subcategory->category->sequence;
      }) as $subcategory)
         <li class="submenu">
          <div class="submenu__button">
           <a class="submenu__button--link"
            href="{{ route('products', ['categorySlug' => $subcategory->category->seo_id !== null && $subcategory->category->seo_id !== '' ? $subcategory->category->seo_id : $subcategory->category->id]) }}">
            @if ($subcategory->category->media->where('type', 'min')->first() != null)
             <img loading="eager" title="{{ strip_tags($subcategory->category->name) }}"
              src="/{{ $subcategory->category->media->where('type', 'min')->first()->path }}{{ $subcategory->category->media->where('type', 'min')->first()->name }}"
              alt="{{ $subcategory->category->media->where('type', 'min')->first()->name }}{{ strip_tags($subcategory->category->name) }}">
            @endif
            <h4>{!! $subcategory->category->name !!}</h4>
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
            @foreach ($category->subcategory->sortBy(function ($subcategory) {
              return $subcategory->category->sequence;
          }) as $subcategory)
             <a class="submenu__link"
              href="{{ route('products', ['categorySlug' => $subsubCategory->category->seo_id !== null && $subsubCategory->category->seo_id !== '' ? $subsubCategory->category->seo_id : $subsubCategory->category->id]) }}">
              @if ($subsubCategory->category->media->where('type', 'min')->first() != null)
               <img loading="eager" title="{{ strip_tags($subsubCategory->category->name) }}"
                src="/{{ $subsubCategory->category->media->where('type', 'min')->first()->path }}{{ $subsubCategory->category->media->where('type', 'min')->first()->name }}"
                alt="{{ $subsubCategory->category->media->where('type', 'min')->first()->name }}{{ strip_tags($subsubCategory->category->name) }}">
              @endif
              <h4>{!! $subsubCategory->category->name !!}</h4>
             </a>
            @endforeach
           </div>
          @endif
         </li>
        @endforeach
       </ul>
      </li>
     @else
      <li>
       <a class="menu__link"
        href="{{ route('products', ['categorySlug' => $category->seo_id !== null && $category->seo_id !== '' ? $category->seo_id : $category->id]) }}">
        @if ($category->media->where('type', 'min')->first() != null)
         <img loading="eager" title="{{ strip_tags($category->name) }}"
          src="/{{ $category->media->where('type', 'min')->first()->path }}{{ $category->media->where('type', 'min')->first()->name }}"
          alt="{{ $category->media->where('type', 'min')->first()->name }} {{ strip_tags($category->name) }}">
        @endif
        <h4> {!! $category->name !!}</h4>
       </a>
      </li>
     @endif
    @endforeach
    <li class="menufooter">Informații</li>
    <li class="menufooter__item"><a href="{{ url('/about') }}">Despre Noi</a></li>
    <li class="menufooter__item"><a href="{{ url('/contact') }}">Contactează-ne</a></li>
    <li class="menufooter__item"><a href="{{ url('/terms') }}">Termeni și Condiții</a></li>

    <li class="menufooter">Serviciu clienți</li>
    <li class="menufooter__item"><a href="{{ url('/cookie') }}">Politica de Cookies</a></li>
    <li class="menufooter__item"><a href="{{ url('/faq') }}">Întrebări Frecvente</a></li>
    <li class="menufooter__item"><a href="{{ url('/privacy') }}">Politica de confidențialitate</a></li>
    <li class="menufooter__item"><a href="{{ url('/sitemap.xml') }}">Hartă Site</a></li>
    <li class="menufooter__item"><a target="blank" href="https://anpc.ro/">ANPC</a></li>

   </ul>
  </div>
  <button class="menu__hidden--close" id="menuHidden"></button>
 </nav>
 <!--------------------END-Menu (Leftbar)-------------------->
 <!---------------------------------------------------------->
 <script src="/script/store/header.js" defer></script>
</div>
