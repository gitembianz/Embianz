<div class="newsletter">
 <div class="newsletter__content">
  <img class="newsletter__img" src="/images/store/svg/thankyou.svg" alt="thank you">
  <div class="newsletter__text">
   <h3 class="newsletter__title">Bun venit în Comunitatea Noastră!</h3>
   <p class="newsletter__descr">Mulțumim mult că te-ai abonat la newsletter-ul nostru! Apreciem interesul tău și suntem
    încântați să facem parte din călătoria ta online. Te vom ține la curent cu cele mai recente știri, oferte speciale
    și actualizări. Dacă dorești să ne contactezi sau ai întrebări, nu ezita să ne scrii</p>
  </div>
  <button class="newsletter__close">
   Inchide
  </button>
 </div>
</div>
<script>
 window.addEventListener('newsletterToggle', event => {
  const newsletter = document.querySelector(".newsletter");
  const body = document.querySelector("body");
  const close = document.querySelector(".newsletter__close");
  newsletter.classList.remove("out");
  newsletter.classList.add("active");
  body.style.overflow = "hidden";
  close.addEventListener("click", () => {
   newsletter.classList.add("out");
   newsletter.classList.remove("active");
   body.style.overflow = "auto"; // Restaurează comportamentul de scroll
  });
 });
</script>
