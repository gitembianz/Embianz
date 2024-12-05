<header>
 <!-- Navbar -->
 <nav class="nav--header">
  <button class="button button--primary button--centered display--mobile" tooltip="Open Menu" tooltip-right
   id="menu__open">
   <svg>
    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
    <line x1="3" y1="9" x2="21" y2="9"></line>
    <line x1="9" y1="21" x2="9" y2="9"></line>
   </svg>
  </button>
  <!-- Logo-->
  <a href="{{ route('dashboard') }}" class="logo display--desktop">
   <img src="/images/dashboard/navbar/logo.png" alt="logo">
  </a>
  <button class="button button--primary button--centered" tooltip="Open Searchbar" tooltip-bottom
   onclick="focusTo('search__input')" id="search__open">
   <svg>
    <circle cx="11" cy="11" r="8"></circle>
    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
   </svg>
  </button>
  <a href="{{ route('dashboard') }}" class="logo display--mobile">
   <img src="/images/dashboard/navbar/mini-logo.png" alt="mini-logo">
  </a>
  <button class="button button--primary button--centered" tooltip="Open Notifications" tooltip-left id="notify__open">
   <svg>
    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
   </svg>
  </button>
  <button class="button button--primary button--centered" tooltip="Open Profile" tooltip-left id="profile__open">
   <svg>
    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
    <circle cx="12" cy="7" r="4"></circle>
   </svg>
  </button>
 </nav>
</header>

<aside>
 <div class="background background--right" wire:ignore id="profile__backdrop"></div>
 <div class="aside aside--right" wire:ignore id="profile">
  <div class="aside--controls">
   <button class="button button--flexed button--primary" id="profile__close">
    <svg>
     <polyline points="4 14 10 14 10 20"></polyline>
     <polyline points="20 10 14 10 14 4"></polyline>
     <line x1="14" y1="10" x2="21" y2="3"></line>
     <line x1="3" y1="21" x2="10" y2="14"></line>
    </svg>
   </button>
   <h3>Profile</h3>
  </div>

  <span class="aside--line"></span>
  <button class="button button--fill button--flexed button--primary">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 4h6v6h-6z" />
    <path d="M4 14h6v6h-6z" />
    <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
    <path d="M7 7m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
   </svg>
   <span>Profile</span>
  </button>
  <a href="{{ route('forcelogout') }}" class="button button--fill button--flexed button--primary">
   <svg>
    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
    <polyline points="16 17 21 12 16 7"></polyline>
    <line x1="21" y1="12" x2="9" y2="12"></line>
   </svg>
   <span>Logout</span>
  </a>
 </div>
</aside>

<aside>
 <div class="background background--top" wire:ignore id="search__backdrop"></div>
 <div class="aside aside--search" id="search">
  <div class="search--controls">
   <button class="button button--flexed button--centered  button--primary" id="search__close">
    <svg>
     <polyline points="4 14 10 14 10 20"></polyline>
     <polyline points="20 10 14 10 14 4"></polyline>
     <line x1="14" y1="10" x2="21" y2="3"></line>
     <line x1="3" y1="21" x2="10" y2="14"></line>
    </svg>
   </button>
   <input class="input input--long" type="text" placeholder="Search..." id="search__input">
   <button class="button button--flexed button--centered button--primary">
    <svg>
     <circle cx="11" cy="11" r="8"></circle>
     <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
    </svg>
   </button>
  </div>
  <span class="aside--line"></span>
 </div>
</aside>

<aside>
 <div class="background background--bottom" wire:ignore id="notify__backdrop"></div>
 <div class="aside aside--bottom" id="notify">
  <div class="aside--controls">
   <button class="button button--flexed button--primary" id="notify__close">
    <svg>
     <polyline points="4 14 10 14 10 20"></polyline>
     <polyline points="20 10 14 10 14 4"></polyline>
     <line x1="14" y1="10" x2="21" y2="3"></line>
     <line x1="3" y1="21" x2="10" y2="14"></line>
    </svg>
   </button>
   <h3>Notifications</h3>
  </div>
  <span class="aside--line"></span>
  <button class="button button--fill button--flexed button--primary">
   <svg>
    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
    <path d="M14 4h6v6h-6z" />
    <path d="M4 14h6v6h-6z" />
    <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
    <path d="M7 7m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
   </svg>
   <span>Profile</span>
  </button>
 </div>
</aside>

<script>
 function focusTo(targetId) {
  var targetElement = document.getElementById(targetId);
  if (targetElement) {
   targetElement.focus();
  }
 }
</script>
