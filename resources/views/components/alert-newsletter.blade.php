@if (session()->has("subscribtion"))
{{-- <div class="newsletter">
  <div class="newsletter__content">
    <img class="newsletter__img" src="https://img.freepik.com/free-vector/mention-concept-illustration_114360-231.jpg?w=826&t=st=1708524616~exp=1708525216~hmac=5a64a65434936232b5241898d2d16bbfafa7ff6d74daf2f26f723440e8783b01" alt="news">
    <div class="newsletter__text">
      <h3 class="newsletter__title">Bun venit în Comunitatea Noastră!</h3>
      <p class="newsletter__descr">Mulțumim mult că te-ai abonat la newsletter-ul nostru! Apreciem interesul tău și suntem încântați să facem parte din călătoria ta online. Te vom ține la curent cu cele mai recente știri, oferte speciale și actualizări. Dacă dorești să ne contactezi sau ai întrebări, nu ezita să ne scrii</p>
    </div>
    <button class="newsletter__close">
      Inchide Modal
      <svg>
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
    </button>
  </div>
</div> --}}

{{-- <button class="newsletter__open">open the newsletter</button> --}}

{{-- <script>
function newsletterToggle(this) {
  const newsletter = document.querySelector(".newsletter");
  const body = document.querySelector("body");
  const close = document.querySelector(".newsletter__close");

  if (!newsletter || !close) {
    return;
  } else {
    this.addEventListener("click", () => {
      newsletter.classList.add("active");
      body.style.overflow = "hidden";
    });

    close.addEventListener("click", () => {
      newsletter.classList.remove("active");
      body.style.overflow = "auto"; // Restaurează comportamentul de scroll
    });
  }
}

newsletterToggle();
</script> --}}
@endif
