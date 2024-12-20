<div class="alertorder" id="confettialert">
 <div class="alertorder__content">
  <div class="alertorder__text">
   <p class="alertorder__descr" id="confettialert__descr"></p>
  </div>
  <button class="alertorder__close" id="confettialert__close">
   @if (app()->has('label_alert_button_close'))
    {!! app('label_alert_button_close') !!}
   @endif
  </button>
 </div>
 <script>
  window.addEventListener('confettialert__modal', event => {
   const alertorder = document.getElementById('confettialert');
   const body = document.querySelector("body");
   const close = document.getElementById("confettialert__close");
   document.getElementById("confettialert__descr").innerText = event.detail.message;
   alertorder.classList.remove("out");
   alertorder.classList.add("active");
   body.style.overflow = "hidden";
   startConfetti();
   close.addEventListener("click", () => {
    alertorder.classList.add("out");
    alertorder.classList.remove("active");
    body.style.overflow = "auto";
   });
  });
 </script>
 <script>
  const duration = 10 * 1000; // Animation duration in milliseconds
  const defaults = {
   startVelocity: 20, // Reduced for realism
   spread: 180, // Moderate spread
   ticks: 100, // Longer lifespan per particle
   gravity: 0.8, // Adds a gravity effect
   zIndex: 9999, // High z-index to ensure confetti appears on top
  };

  function randomInRange(min, max) {
   return Math.random() * (max - min) + min;
  }

  // Generate colors with simulated light and shadow
  function generateColorWithShadow() {
   const baseColor = `hsl(${randomInRange(0, 360)}, ${randomInRange(50, 90)}%, ${randomInRange(40, 60)}%)`; // Base color
   const shadowColor =
    `hsla(${randomInRange(0, 360)}, ${randomInRange(50, 90)}%, ${randomInRange(20, 40)}%, 0.5)`; // Shadowed color
   return {
    baseColor,
    shadowColor,
   };
  }

  function startConfetti() {
   const animationEnd = Date.now() + duration;

   const interval = setInterval(function() {
    const timeLeft = animationEnd - Date.now();

    if (timeLeft <= 0) {
     clearInterval(interval);
     return;
    }

    const particleCount = Math.floor(50 * (timeLeft / duration)); // Dynamic particle count

    // Generate colors
    const {
     baseColor,
     shadowColor
    } = generateColorWithShadow();

    confetti({
     ...defaults,
     particleCount,
     colors: [baseColor, shadowColor], // Add colors for light/shadow effect
     origin: {
      x: randomInRange(0.1, 0.3),
      y: Math.random() - 0.2,
     },
     scalar: randomInRange(0.8, 1.2), // Random sizes
    });

    confetti({
     ...defaults,
     particleCount,
     colors: [baseColor, shadowColor], // Add colors for light/shadow effect
     origin: {
      x: randomInRange(0.7, 0.9),
      y: Math.random() - 0.2,
     },
     scalar: randomInRange(0.8, 1.2),
    });
   }, 150); // Reduced interval for smoother animation
  }
 </script>

</div>
