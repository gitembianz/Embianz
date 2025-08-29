<div class="newsletter">
 <div class="newsletter__content">
  <img title="thankyou" loading="eager" class="newsletter__img" src="/images/store/svg/thankyou.svg" alt="thank you">
  <div class="newsletter__text">
   <h3 class="newsletter__title">
    @if (app()->has('label_newsletter_title'))
     {!! app('label_newsletter_title') !!}
    @endif
   </h3>
   <p class="newsletter__descr">
    @if (app()->has('label_newsletter_description'))
     {!! app('label_newsletter_description') !!}
    @endif
   </p>
  </div>
  <button class="newsletter__close" aria-label="Close Newsletter">
   @if (app()->has('label_newsletter_close_button'))
    {!! app('label_newsletter_close_button') !!}
   @endif
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
   body.style.overflow = "auto";
  });

  function handleKey(event) {
   if (event.keyCode === 27) {
    newsletter.classList.add("out");
    newsletter.classList.remove("active");
    body.style.overflow = "auto";
   }
  }

  document.addEventListener('keydown', handleKey);
 });
</script>
