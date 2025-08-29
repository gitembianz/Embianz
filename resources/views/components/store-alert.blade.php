<div class="alertorder">
 <div class="alertorder__content">
  <svg class="alertorder__img" xmlns="http://www.w3.org/2000/svg" width="50%" height="50%" viewBox="0 0 24 24"
   fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
   class="feather feather-alert-circle">
   <circle cx="12" cy="12" r="10"></circle>
   <line x1="12" y1="8" x2="12" y2="12"></line>
   <line x1="12" y1="16" x2="12.01" y2="16"></line>
  </svg>
  <div class="alertorder__text">
   <h3 class="alertorder__title">
    @if (app()->has('label_alert_title'))
     {!! app('label_alert_title') !!}
    @endif
   </h3>
   <p class="alertorder__descr"></p>
  </div>
  <button class="alertorder__close">
   @if (app()->has('label_alert_button_close'))
    {!! app('label_alert_button_close') !!}
   @endif
  </button>
 </div>
 <script>
  window.addEventListener('alert__modal', event => {
   const alertorder = document.querySelector(".alertorder");
   const body = document.querySelector("body");
   const close = document.querySelector(".alertorder__close");
   document.querySelector(".alertorder__descr").innerText = event.detail.message;
   alertorder.classList.remove("out");
   alertorder.classList.add("active");
   body.style.overflow = "hidden";
   close.addEventListener("click", () => {
    alertorder.classList.add("out");
    alertorder.classList.remove("active");
    body.style.overflow = "auto";
   });
  });
 </script>
</div>
