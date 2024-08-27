  
  <script>
   document.addEventListener('livewire:load', function() {
    let observer = new IntersectionObserver((entries) => {
     entries.forEach(entry => {
      if (entry.isIntersecting) {
       window.livewire.find('<?php echo e($_instance->id); ?>').call('loadMore');
      }
     });
    });

    observer.observe(document.getElementById('last_record'));
   });
  </script>
<?php /**PATH /var/www/html/resources/views/components/admin-lazyload.blade.php ENDPATH**/ ?>