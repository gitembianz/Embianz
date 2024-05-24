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
  document.addEventListener('livewire:load', function() {
   Livewire.on('cartCountUpdated', function(newCartCount) {
    playSoundcart();
   });
   Livewire.on('orderCountUpdated', function(newCartCount) {
    playSoundorder();
   });
  });

  function playSoundcart() {
   let audio = new Audio('/sounds/cart.mp3');
   audio.play();
  }

  function playSoundorder() {
   let audio = new Audio('/sounds/order.mp3');
   audio.play();
  }
 </script>
</div>
