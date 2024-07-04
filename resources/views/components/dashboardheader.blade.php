<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{ __("Embianz") }}</title>
      <link rel="icon" type="image/x-icon" href="/favicon.png">
      <link rel="preload" href="/dist/css/dashboard.css" as="style">
      <link rel="stylesheet" href="/dist/css/dashboard.css">

      <script async>
        document.addEventListener('DOMContentLoaded', () => {
          const leftbar = document.getElementById('leftbar');
          const buttons = leftbar.querySelectorAll('a');

          // Funcția pentru inițializarea stării componentului pe baza localStorage
          function initializeComponentState() {
              const isActive = localStorage.getItem('leftbarActive') === 'true';
              if (isActive) {
                  leftbar.classList.add('active');
              } else {
                  leftbar.classList.remove('active'); // Asigură-te că clasa 'active' nu este prezentă inițial
              }
          }

          // Salvăm starea în localStorage la fiecare modificare a clasei
          function saveComponentState() {
              const isActive = leftbar.classList.contains('active');
              localStorage.setItem('leftbarActive', isActive);
          }

          // Inițializează starea componentului la încărcarea paginii
          initializeComponentState();

          // Adaugă tranziția la leftbar și butoane după un scurt interval de timp
          function addTransition() {
              leftbar.style.transition = 'all 0.25s ease';
              buttons.forEach((button) => {
                  button.style.transition = 'all 0.25s ease';
              });
          }
          setTimeout(addTransition, 500);

          // Observăm schimbările clasei și salvăm starea
          const observer = new MutationObserver(saveComponentState);
          observer.observe(leftbar, { attributes: true, attributeFilter: ['class'] });
      });
    </script>
  </head>

    <body>
