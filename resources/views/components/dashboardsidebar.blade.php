<main>
 <div class="sidebar">
  <!-- Sidebar List of Items -->
  <ul class="sidebar__list">
   <!-- Sidebar Button in the right -->
   <li class="sidebar__open">
    <svg>
     <polyline points="9 18 15 12 9 6"></polyline>
    </svg>
   </li>
   <li>
    <a class="sidebar__item @if ($active == 'dashboard') active @endif" href="{{ route('dashboard') }}">
     <button class="sidebar__icon">
      <svg>
       <rect x="3" y="3" width="7" height="7"></rect>
       <rect x="14" y="3" width="7" height="7"></rect>
       <rect x="14" y="14" width="7" height="7"></rect>
       <rect x="3" y="14" width="7" height="7"></rect>
      </svg>
     </button>
     <span>{{ __('Dashbord') }}</span>
    </a>
   </li>
   <li>
    <a class="sidebar__item @if ($active == 'category') active @endif" href="{{ route('category') }}">
     <button class="sidebar__icon">
      <svg>
       <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
       <polyline points="2 17 12 22 22 17"></polyline>
       <polyline points="2 12 12 17 22 12"></polyline>
      </svg>
     </button>
     <span>Categories</span>
    </a>
   </li>
   <li>
    <a class="sidebar__item @if ($active == 'product') active @endif" href="{{ route('all_products') }}">
     <button class="sidebar__icon">
      <svg>
       <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
       <path
        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
       </path>
       <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
       <line x1="12" y1="22.08" x2="12" y2="12"></line>
      </svg>
     </button>
     <span>Products</span>
    </a>
   </li>
   <li>
    <a class="sidebar__item @if ($active == 'voucher') active @endif" href="{{ route('vouchers') }}">
     <button class="sidebar__icon">
      <svg>
       <line x1="19" y1="5" x2="5" y2="19"></line>
       <circle cx="6.5" cy="6.5" r="2.5"></circle>
       <circle cx="17.5" cy="17.5" r="2.5"></circle>
      </svg>
     </button>
     <span>Vouchers</span>
    </a>
   </li>
   <li>
    <a class="sidebar__item @if ($active == 'account') active @endif" href="{{ route('accounts') }}">
     <button class="sidebar__icon">
      <svg>
       <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
       <circle cx="9" cy="7" r="4"></circle>
       <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
       <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
      </svg>
     </button>
     <span>Accounts</span>
    </a>
   </li>
   <li>
    <a class="sidebar__item @if ($active == 'cart') active @endif" href="{{ route('carts') }}">
     <button class="sidebar__icon">
      <svg>
       <circle cx="9" cy="21" r="1"></circle>
       <circle cx="20" cy="21" r="1"></circle>
       <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
      </svg>
     </button>
     <span>Carts</span>
    </a>
   </li>
   <li>
    <a class="sidebar__item @if ($active == 'order') active @endif" href="{{ route('orders') }}">
     <button class="sidebar__icon">
      <svg>
       <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
       <line x1="3" y1="6" x2="21" y2="6"></line>
       <path d="M16 10a4 4 0 0 1-8 0"></path>
      </svg>
     </button>
     <span>Orders</span>
    </a>
   </li>
   <li>
    <a class="sidebar__item @if ($active == 'price') active @endif" href="{{ route('pricelists') }}">
     <button class="sidebar__icon">
      <svg>
       <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z">
       </path>
       <line x1="7" y1="7" x2="7.01" y2="7"></line>
      </svg>
     </button>
     <span>Price List</span>
    </a>
   </li>
   <li>
    <a class="sidebar__item @if ($active == 'spec') active @endif" href="{{ route('specs') }}">
     <button class="sidebar__icon">
      <svg>
       <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
       <polyline points="14 2 14 8 20 8"></polyline>
       <line x1="16" y1="13" x2="8" y2="13"></line>
       <line x1="16" y1="17" x2="8" y2="17"></line>
       <polyline points="10 9 9 9 8 9"></polyline>
      </svg>
     </button>
     <span>Specs</span>
    </a>
   </li>
    <li>
    <a class="sidebar__item @if ($active == 'wishlist') active @endif" href="{{ route('wishlists') }}">
     <button class="sidebar__icon">
      <svg>
    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
</svg>
     </button>
     <span>Wishlists</span>
    </a>
   </li>
     <li>
    <a class="sidebar__item @if ($active == 'session') active @endif" href="{{ route('sessions') }}">
     <button class="sidebar__icon">
      <svg>
    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
</svg>
     </button>
     <span>Sessions</span>
    </a>
   </li>
   <li class="dropdown">
    <a class="sidebar__item @if ($active == 'store_settings' || $active == 'payment' || $active == 'scripts' || $active == 'currency') active @endif dropdown-button" href="#"
     style="z-index: 99999;">
     <button class="sidebar__icon">
      <svg>
       <circle cx="12" cy="12" r="3"></circle>
       <path
        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
       </path>
      </svg>
     </button>
     <span>Settings</span>
    </a>
    <div class="dropdown-list"
     style="width: 100%;margin-top: -10px;border-radius: 10px;padding: 20px 0 10px 20px;border: none;background: #35424b;">
     <a class="sidebar__subitem @if ($active == 'store_settings') active @endif" href="{{ route('storesettings') }}">
      <button class="sidebar__icon">
       <svg>
        <path
         d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z">
        </path>
       </svg>
      </button>
      <span>Store</span>
     </a>
      <a class="sidebar__subitem @if ($active == 'currency') active @endif" href="{{ route('currencies') }}">
      <button class="sidebar__icon">
       <svg>
        <line x1="12" y1="1" x2="12" y2="23"></line>
        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
       </svg> 
      </button>
      <span>Currency</span>
     </a>
     <a class="sidebar__subitem @if ($active == 'scripts') active @endif" href="{{ route('customscripts') }}">
      <button class="sidebar__icon">
       <svg>
        <polyline points="16 18 22 12 16 6"></polyline>
        <polyline points="8 6 2 12 8 18"></polyline>
       </svg>
      </button>
      <span>Code</span>
     </a>
     <a class="sidebar__subitem @if ($active == 'payment') active @endif" href="{{ route('payments') }}">
      <button class="sidebar__icon">
        <svg>
    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
    <line x1="1" y1="10" x2="23" y2="10"></line>
</svg>
       {{-- --}}
      </button>
      <span>Payment</span>
     </a>
    </div>
   </li>
  </ul>
 </div>
