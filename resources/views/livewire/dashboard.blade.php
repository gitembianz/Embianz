<nav class="nav--home" wire:poll.60000ms>
 <a href="{{ route('all_products') }}" class="button button--fill button--flexed button--secondary">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M3 21l18 0" />
     <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" />
     <path d="M5 21l0 -10.15" />
     <path d="M19 21l0 -10.15" />
     <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" />
  </svg>
  Products ({{ $activeProductsCount }})
 </a>
 <a href="{{ route('category') }}" class="button button--fill button--flexed button--secondary">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M14 4h6v6h-6z" />
     <path d="M4 14h6v6h-6z" />
     <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
     <path d="M7 7m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
  </svg>
  Categories ({{ $activeCategoriesCount }})
 </a>
 <a href="{{ route('carts') }}" class="button button--fill button--flexed button--secondary">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
     <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
     <path d="M17 17h-11v-14h-2" />
     <path d="M6 5l14 1l-1 7h-13" />
  </svg>
  Carts ({{ $cartCount }})
 </a>
 <a href="{{ route('orders') }}" class="button button--fill button--flexed button--secondary">
    <svg>
     <path stroke="none" d="M0 0h24v24H0z" fill="none" />
     <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
     <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
     <path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" />
     <path d="M3 9l4 0" />
  </svg>
  Orders ({{ $orderCount }})
 </a>

 <script>
  let userInteracted = false;

  function setUserInteracted() {
   userInteracted = true;
  }

  document.addEventListener('click', setUserInteracted);
  document.addEventListener('keydown', setUserInteracted);
  document.addEventListener('touchstart', setUserInteracted);

  document.addEventListener('livewire:load', function() {
   userInteracted = true;
   Livewire.on('cartCountUpdated', function(newCartCount) {
    if (userInteracted) {
     playSoundcart();
    }
   });
   Livewire.on('orderCountUpdated', function(newOrderCount) {
    if (userInteracted) {
     playSoundorder();
    }
   });
  });

  var audio = new Audio('/sounds/cart.mp3');
  function playSoundcart() {
   audio.play().catch(function(error) {
    console.log('Error playing sound:', error);
   });
  }

  function playSoundorder() {
   let audio = new Audio('/sounds/order.mp3');
   audio.play().catch(function(error) {
    console.log('Error playing sound:', error);
   });
  }
 </script>

</nav>
