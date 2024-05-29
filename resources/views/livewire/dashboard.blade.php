<div class="status" wire:poll.30000ms>
 <a href="{{ route('all_products') }}" class="status__item">
  Produse<span>({{ $activeProductsCount }})</span>
 </a>
 <a href="{{ route('category') }}" class="status__item">
  Categories<span>({{ $activeCategoriesCount }})</span>
 </a>
 <a href="{{ route('carts') }}" class="status__item">
  Carts<span>({{ $cartCount }})</span>
 </a>
 <a href="{{ route('orders') }}" class="status__item">
  Orders<span>({{ $orderCount }})</span>
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

  function playSoundcart() {
   let audio = new Audio('/sounds/cart.mp3');
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

</div>
