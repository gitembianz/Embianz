<div class="newsletter">
 <div class="newsletter__content">
  <img class="newsletter__img" src="/images/store/svg/noren.svg" alt="logo">
  <div class="newsletter__text">
   <h3 class="newsletter__title">Ceva nu a mers bine!</h3>
   <p class="newsletter__descr">Va rog sa revizuiti detaliile comenzii, deoarece ceva nu a mers bine, voucherul expirat
    sau cantitatea produselor nu mai este valabila.</p>
  </div>
  <button class="newsletter__close">
   Inchide
  </button>
 </div>
 <script>
  function newsletterToggle() {
   const newsletter = document.querySelector(".newsletter");
   const body = document.querySelector("body");
   const close = document.querySelector(".newsletter__close");
   newsletter.classList.remove("out");
   newsletter.classList.add("active");
   body.style.overflow = "hidden";
   close.addEventListener("click", () => {
    newsletter.classList.add("out");
    newsletter.classList.remove("active");
    body.style.overflow = "auto";
   });
  }

  window.addEventListener('alert__modal', event => {
   newsletterToggle();
  });
 </script>
</div>
