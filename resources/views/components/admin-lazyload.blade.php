  {{-- Load More Automatic --}}
  <script>
   document.addEventListener('livewire:load', function() {
    let observer = new IntersectionObserver((entries) => {
     entries.forEach(entry => {
      if (entry.isIntersecting) {
       @this.call('loadMore');
      }
     });
    });

    observer.observe(document.getElementById('last_record'));
   });
  </script>
