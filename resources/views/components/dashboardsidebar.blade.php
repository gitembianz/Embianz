<main>
 <!-- Mobile Menu -->
 <aside id="menu__container" class="display--mobile">
  <div class="background background--left" wire:ignore id="menu__backdrop"></div>
  <div class="aside aside--left" wire:ignore id="menu">
   <div class="aside--controls">
    <button class="button button--flexed button--primary" id="menu__close">
     <svg>
      <polyline points="4 14 10 14 10 20"></polyline>
      <polyline points="20 10 14 10 14 4"></polyline>
      <line x1="14" y1="10" x2="21" y2="3"></line>
      <line x1="3" y1="21" x2="10" y2="14"></line>
     </svg>
    </button>
    <h3>Menu</h3>
   </div>
   <span class="aside--line"></span>
   {{-- dashboard --}}
   <a class="button button--fill button--flexed button--primary @if ($active == 'dashboard') button--active @endif"
    href="{{ route('dashboard') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
     <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
     <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
    </svg>
    <span>Dashboard</span>
   </a>
   {{-- categories --}}
   <a class="button button--fill button--flexed button--primary @if ($active == 'category') button--active @endif"
    href="{{ route('category') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M14 4h6v6h-6z" />
     <path d="M4 14h6v6h-6z" />
     <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
     <path d="M7 7m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
    </svg>
    <span>Categories</span>
   </a>
   {{-- products --}}
   <a class="button button--fill button--flexed button--primary @if ($active == 'product') button--active @endif"
    href="{{ route('all_products') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M3 21l18 0" />
     <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" />
     <path d="M5 21l0 -10.15" />
     <path d="M19 21l0 -10.15" />
     <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" />
    </svg>
    <span>Products</span>
   </a>
   {{-- Promotions --}}
   <a class="button button--fill button--flexed button--primary @if ($active == 'promotion') button--active @endif"
    href="{{ route('all_promotions') }}">
    <svg>
     <g id="SVGRepo_bgCarrier" stroke-width="0" />
     <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
     <g id="SVGRepo_iconCarrier">
      <path fill-rule="evenodd" clip-rule="evenodd"
       d="M20.0848 16.9323C20.5298 12.6556 20.5298 8.34432 20.0848 4.06762C19.9261 2.54257 18.1678 1.77006 16.9372 2.68478L12.8336 5.73519C11.5831 6.6647 10.0665 7.16665 8.5084 7.16665H4.6792C3.98884 7.16665 3.4292 7.72629 3.4292 8.41664V12.5833C3.4292 13.2737 3.98884 13.8333 4.6792 13.8333H5.30348L4.45812 16.9882C4.36392 17.3398 4.53573 17.7082 4.86559 17.8621L8.54674 19.5787C8.74766 19.6724 8.97974 19.6724 9.18067 19.5787C9.3816 19.485 9.53078 19.3072 9.58815 19.093L10.8508 14.3806C10.8643 14.3306 10.8723 14.2803 10.8753 14.2305C11.5727 14.4714 12.2338 14.8189 12.8336 15.2648L16.9372 18.3152C18.1677 19.2299 19.9261 18.4574 20.0848 16.9323ZM18.5928 4.22285C19.0271 8.39634 19.0271 12.6036 18.5928 16.7771C18.5545 17.1457 18.1295 17.3324 17.8321 17.1114L13.7284 14.0609C12.2193 12.9391 10.3888 12.3333 8.50839 12.3333L4.9292 12.3333L4.9292 8.66665H8.5084C10.3888 8.66665 12.2193 8.06085 13.7284 6.93902L17.8321 3.88861C18.1295 3.66752 18.5545 3.85424 18.5928 4.22285ZM9.43615 13.8929C9.12975 13.8534 8.8199 13.8333 8.50839 13.8333H6.85639L6.06989 16.7686L8.3706 17.8415L9.40196 13.9924C9.41118 13.958 9.42264 13.9248 9.43615 13.8929Z"
       fill="#000000" />
     </g>
    </svg>
    <span>Promotions</span>
   </a>
   {{-- Brands --}}
   <a class="button button--fill button--flexed button--primary @if ($active == 'brand') button--active @endif"
    href="{{ route('all_brands') }}">
    <svg>
     <path
      d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
     </path>
     <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
     <line x1="12" y1="22.08" x2="12" y2="12"></line>
    </svg>
    <span>Brands</span>
   </a>
   {{-- vouchers --}}
   <a class="button button--fill button--flexed button--primary @if ($active == 'voucher') button--active @endif"
    href="{{ route('vouchers') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
     <path d="M7 16l3 -3l3 3" />
     <path
      d="M8 13c-.789 0 -2 -.672 -2 -1.5s.711 -1.5 1.5 -1.5c1.128 -.02 2.077 1.17 2.5 3c.423 -1.83 1.372 -3.02 2.5 -3c.789 0 1.5 .672 1.5 1.5s-1.211 1.5 -2 1.5h-4z" />
    </svg>
    <span>Vouchers</span>
   </a>
   {{-- accounts --}}
   <a class="button button--fill button--flexed button--primary @if ($active == 'account') button--active @endif"
    href="{{ route('accounts') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
     <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
     <path d="M16 3.13a4 4 0 0 1 0 7.75" />
     <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
    </svg>
    <span>Accounts</span>
   </a>
   {{-- carts --}}
   <a class="button button--fill button--flexed button--primary @if ($active == 'cart') button--active @endif"
    href="{{ route('carts') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
     <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
     <path d="M17 17h-11v-14h-2" />
     <path d="M6 5l14 1l-1 7h-13" />
    </svg>
    <span>Carts</span>
   </a>
   {{-- orders --}}
   <a class="button button--fill button--flexed button--primary @if ($active == 'order') button--active @endif"
    href="{{ route('orders') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
     <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
     <path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" />
     <path d="M3 9l4 0" />
    </svg>
    <span>Orders</span>
   </a>
   {{-- pricelists --}}
   <a class="button button--fill button--flexed button--primary @if ($active == 'price') button--active @endif"
    href="{{ route('pricelists') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" />
     <path d="M14 8h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5m2 0v1.5m0 -9v1.5" />
    </svg>
    <span>Price List</span>
   </a>
   {{-- specifications --}}
   <a
    class="button button--fill button--flexed button--primary @if ($active == 'spec') button--active @endif"
    href="{{ route('specs') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
     <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
     <path d="M15 8l2 0" />
     <path d="M15 12l2 0" />
     <path d="M7 16l10 0" />
    </svg>
    <span>Specifications</span>
   </a>
   {{-- wishlists --}}
   <a
    class="button button--fill button--flexed button--primary @if ($active == 'wishlist') button--active @endif"
    href="{{ route('wishlists') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M13 5h8" />
     <path d="M13 9h5" />
     <path d="M13 15h8" />
     <path d="M13 19h5" />
     <path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
     <path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
    </svg>
    <span>Wishlists</span>
   </a>
   {{-- sessions --}}
   <a
    class="button button--fill button--flexed button--primary @if ($active == 'session') button--active @endif"
    href="{{ route('sessions') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M10 5m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
     <path d="M7 7l0 10" />
     <path d="M4 8l0 8" />
    </svg>
    <span>Sessions</span>
   </a>
   {{-- storesettings --}}
   <a
    class="button button--fill button--flexed button--primary @if ($active == 'store_settings') button--active @endif"
    href="{{ route('storesettings') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
     <path d="M12 20h-6a3 3 0 0 1 -3 -3v-2a3 3 0 0 1 3 -3h10.5" />
     <path d="M18 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
     <path d="M18 14.5v1.5" />
     <path d="M18 20v1.5" />
     <path d="M21.032 16.25l-1.299 .75" />
     <path d="M16.27 19l-1.3 .75" />
     <path d="M14.97 16.25l1.3 .75" />
     <path d="M19.733 19l1.3 .75" />
     <path d="M7 8v.01" />
     <path d="M7 16v.01" />
    </svg>
    <span>Store settings</span>
   </a>
   {{-- currencies --}}
   <a
    class="button button--fill button--flexed button--primary @if ($active == 'currency') button--active @endif"
    href="{{ route('currencies') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" />
     <path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" />
     <path
      d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598z" />
     <path d="M3 6v10c0 .888 .772 1.45 2 2" />
     <path d="M3 11c0 .888 .772 1.45 2 2" />
    </svg>
    <span>Currency</span>
   </a>
   {{-- custom scripts --}}
   <a
    class="button button--fill button--flexed button--primary @if ($active == 'scripts') button--active @endif"
    href="{{ route('customscripts') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M17 20h-11a3 3 0 0 1 0 -6h11a3 3 0 0 0 0 6h1a3 3 0 0 0 3 -3v-11a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v8" />
    </svg>
    <span>Custom Script</span>
   </a>
   {{-- payments --}}
   <a
    class="button button--fill button--flexed button--primary @if ($active == 'payment') button--active @endif"
    href="{{ route('payments') }}">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path
      d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" />
     <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" />
    </svg>
    <span>Payment</span>
   </a>
   {{-- labels --}}
   <a
    class="button button--fill button--flexed button--primary @if ($active == 'labels') button--active @endif"
    href="{{ route('labels') }}">
    <svg>
     <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
     <line x1="7" y1="7" x2="7.01" y2="7"></line>
    </svg>
    <span>Labels</span>
   </a>
   {{-- variants --}}
   <a
    class="button button--fill button--flexed button--primary @if ($active == 'variant') button--active @endif"
    href="{{ route('variants') }}">
    <svg>
     <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
     <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
    </svg>
    <span>Product Variants</span>
   </a>
  </div>
 </aside>
 <!-- Desktop Menu -->
 <div class="leftbar display--desktop active" id="leftbar">
  <button class="button button--long button--flexed button--primary" id="toggle__leftbar">
   <svg>
    <polyline points="4 14 10 14 10 20"></polyline>
    <polyline points="20 10 14 10 14 4"></polyline>
    <line x1="14" y1="10" x2="21" y2="3"></line>
    <line x1="3" y1="21" x2="10" y2="14"></line>
   </svg>
   <span>Menu</span>
  </button>
  {{-- dashboard desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'dashboard') button--secondary @endif"
   href="{{ route('dashboard') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
   </svg>
   <span>Dashboard</span>
  </a>
  {{-- categories desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'category') button--secondary @endif"
   href="{{ route('category') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 4h6v6h-6z" />
    <path d="M4 14h6v6h-6z" />
    <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
    <path d="M7 7m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
   </svg>
   <span>Categories</span>
  </a>
  {{-- products desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'product') button--secondary @endif"
   href="{{ route('all_products') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M3 21l18 0" />
    <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" />
    <path d="M5 21l0 -10.15" />
    <path d="M19 21l0 -10.15" />
    <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" />
   </svg>
   <span>Products</span>
  </a>
  {{-- promotions desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'promotion') button--secondary @endif"
   href="{{ route('all_promotions') }}">
   <svg>
    <g id="SVGRepo_bgCarrier" />
    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
    <g id="SVGRepo_iconCarrier">
     <path fill-rule="evenodd" clip-rule="evenodd"
      d="M20.0848 16.9323C20.5298 12.6556 20.5298 8.34432 20.0848 4.06762C19.9261 2.54257 18.1678 1.77006 16.9372 2.68478L12.8336 5.73519C11.5831 6.6647 10.0665 7.16665 8.5084 7.16665H4.6792C3.98884 7.16665 3.4292 7.72629 3.4292 8.41664V12.5833C3.4292 13.2737 3.98884 13.8333 4.6792 13.8333H5.30348L4.45812 16.9882C4.36392 17.3398 4.53573 17.7082 4.86559 17.8621L8.54674 19.5787C8.74766 19.6724 8.97974 19.6724 9.18067 19.5787C9.3816 19.485 9.53078 19.3072 9.58815 19.093L10.8508 14.3806C10.8643 14.3306 10.8723 14.2803 10.8753 14.2305C11.5727 14.4714 12.2338 14.8189 12.8336 15.2648L16.9372 18.3152C18.1677 19.2299 19.9261 18.4574 20.0848 16.9323ZM18.5928 4.22285C19.0271 8.39634 19.0271 12.6036 18.5928 16.7771C18.5545 17.1457 18.1295 17.3324 17.8321 17.1114L13.7284 14.0609C12.2193 12.9391 10.3888 12.3333 8.50839 12.3333L4.9292 12.3333L4.9292 8.66665H8.5084C10.3888 8.66665 12.2193 8.06085 13.7284 6.93902L17.8321 3.88861C18.1295 3.66752 18.5545 3.85424 18.5928 4.22285ZM9.43615 13.8929C9.12975 13.8534 8.8199 13.8333 8.50839 13.8333H6.85639L6.06989 16.7686L8.3706 17.8415L9.40196 13.9924C9.41118 13.958 9.42264 13.9248 9.43615 13.8929Z"
      fill="#000000" />
    </g>
   </svg>
   <span>Promotions</span>
  </a>
  {{-- brands desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'brand') button--secondary @endif"
   href="{{ route('all_brands') }}">
   <svg>
    <path
     d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
    </path>
    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
    <line x1="12" y1="22.08" x2="12" y2="12"></line>
   </svg>
   <span>Brands</span>
  </a>
  {{-- vouchers desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'voucher') button--secondary @endif"
   href="{{ route('vouchers') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
    <path d="M7 16l3 -3l3 3" />
    <path
     d="M8 13c-.789 0 -2 -.672 -2 -1.5s.711 -1.5 1.5 -1.5c1.128 -.02 2.077 1.17 2.5 3c.423 -1.83 1.372 -3.02 2.5 -3c.789 0 1.5 .672 1.5 1.5s-1.211 1.5 -2 1.5h-4z" />
   </svg>
   <span>Vouchers</span>
  </a>
  {{-- accounts desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'account') button--secondary @endif"
   href="{{ route('accounts') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
   </svg>
   <span>Accounts</span>
  </a>
  {{-- carts desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'cart') button--secondary @endif"
   href="{{ route('carts') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
    <path d="M17 17h-11v-14h-2" />
    <path d="M6 5l14 1l-1 7h-13" />
   </svg>
   <span>Carts</span>
  </a>
  {{-- orders molbile --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'order') button--secondary @endif"
   href="{{ route('orders') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
    <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
    <path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" />
    <path d="M3 9l4 0" />
   </svg>
   <span>Orders</span>
  </a>
  {{-- pricelist desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'price') button--secondary @endif"
   href="{{ route('pricelists') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" />
    <path d="M14 8h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5m2 0v1.5m0 -9v1.5" />
   </svg>
   <span>Price List</span>
  </a>
  {{-- specification desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'spec') button--secondary @endif"
   href="{{ route('specs') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
    <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
    <path d="M15 8l2 0" />
    <path d="M15 12l2 0" />
    <path d="M7 16l10 0" />
   </svg>
   <span>Specifications</span>
  </a>
  {{-- wistlist desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'wishlist') button--secondary @endif"
   href="{{ route('wishlists') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M13 5h8" />
    <path d="M13 9h5" />
    <path d="M13 15h8" />
    <path d="M13 19h5" />
    <path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
    <path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
   </svg>
   <span>Wishlists</span>
  </a>
  {{-- session desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'session') button--secondary @endif"
   href="{{ route('sessions') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M10 5m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
    <path d="M7 7l0 10" />
    <path d="M4 8l0 8" />
   </svg>
   <span>Sessions</span>
  </a>
  {{-- storesettings desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'store_settings') button--secondary @endif"
   href="{{ route('storesettings') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v2a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
    <path d="M12 20h-6a3 3 0 0 1 -3 -3v-2a3 3 0 0 1 3 -3h10.5" />
    <path d="M18 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
    <path d="M18 14.5v1.5" />
    <path d="M18 20v1.5" />
    <path d="M21.032 16.25l-1.299 .75" />
    <path d="M16.27 19l-1.3 .75" />
    <path d="M14.97 16.25l1.3 .75" />
    <path d="M19.733 19l1.3 .75" />
    <path d="M7 8v.01" />
    <path d="M7 16v.01" />
   </svg>
   <span>Store settings</span>
  </a>
  {{-- currencies desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'currency') button--secondary @endif"
   href="{{ route('currencies') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" />
    <path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" />
    <path
     d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598z" />
    <path d="M3 6v10c0 .888 .772 1.45 2 2" />
    <path d="M3 11c0 .888 .772 1.45 2 2" />
   </svg>
   <span>Currency</span>
  </a>
  {{-- custom scripts desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'scripts') button--secondary @endif"
   href="{{ route('customscripts') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M17 20h-11a3 3 0 0 1 0 -6h11a3 3 0 0 0 0 6h1a3 3 0 0 0 3 -3v-11a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v8" />
   </svg>
   <span>Custom Scripts</span>
  </a>
  {{-- payments desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'payment') button--secondary @endif"
   href="{{ route('payments') }}">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path
     d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" />
    <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" />
   </svg>
   <span>Payment</span>
  </a>
  {{-- labels desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'labels') button--secondary @endif"
   href="{{ route('labels') }}">
   <svg>
    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
    <line x1="7" y1="7" x2="7.01" y2="7"></line>
   </svg>
   <span>Labels</span>
  </a>
  {{-- variants desktop --}}
  <a
   class="button button--long button--flexed button--primary @if ($active == 'variant') button--secondary @endif"
   href="{{ route('variants') }}">
   <svg>
    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
   </svg>
   <span>Variants References</span>
  </a>
 </div>
 {{-- Script for Leftbar --}}
 <script>
  const leftbar2 = document.getElementById('leftbar');
  const buttons2 = leftbar2.querySelectorAll('a');
  const closeButton2 = leftbar2.querySelector('button');

  function initializeComponentState() {
   const isActive = localStorage.getItem('leftbarActive') === 'true';
   if (isActive) {
    leftbar2.classList.add('active');
   } else {
    leftbar2.classList.remove('active');
   }
  }

  function saveComponentState() {
   const isActive = leftbar2.classList.contains('active');
   localStorage.setItem('leftbarActive', isActive);
  }

  initializeComponentState();

  function addTransition() {
   leftbar2.style.transition = 'all 0.25s ease';
   closeButton2.style.transition = 'all 0.25s ease';
   buttons2.forEach((button) => {
    button.style.transition = 'all 0.25s ease';
   });
  }
  setTimeout(addTransition, 500);
  const observer2 = new MutationObserver(saveComponentState);
  observer2.observe(leftbar2, {
   attributes: true,
   attributeFilter: ['class']
  });
 </script>
