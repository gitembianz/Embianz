<div>

 @if (!$cookieConsent)
  <section id="cookie-banner" wire:loading.remove class="hidden">
   <div class="container cookie__container">
    <div class="cookie__description">
     <span>
      This website utilizes cookies to enhance your browsing experience and provide you with the best
      possible service on our platform. See Cookies Policy
     </span>
     <a href="{{ url('/cookie') }}">
      See Cookies Policy
     </a>
    </div>
    <div id="cookieForm">
     <div class="cookie__form @if ($advance) show @endif">
      <div class="cookie__form--container">
       <label>
        <input type="checkbox" name="essential" disabled checked>
        <span>
         Essential Cookies (Required)
        </span>
        <p>
         These are essential cookies that ensure the proper functioning of the website and
         the
         preservation of your preferences (e.g., language, region).
        </p>
       </label>
       <label>
        <input type="checkbox" name="analytics">
        <span>
         Analytical Cookies
        </span>
        <p>
         These cookies encompass performance cookies and visitor analysis cookies.
        </p>
       </label>
       <label>
        <input type="checkbox" name="marketing">
        <span>
         Marketing Cookies
        </span>
        <p>
         These cookies are used for marketing purposes.
        </p>
       </label>
      </div>
     </div>
     <div class="cookie-btns">
      <button wire:click="acceptCookie" class="cookie__button cookie__button--accept">
       Accept
      </button>
      <button wire:click="advancecookie" id="advanced-settings" class="cookie__button" type="button">
       Advanced
      </button>
     </div>
    </div>
   </div>
  </section>
 @endif

 <x-alert-newsletter />

 <!--------------------------------------------------------->
 <!--------------------------Footer------------------------->
 <footer class="footer">
  <div class="footer__container container">
   <!--------------------------------------------------------->
   <!---------------------Logo and Social--------------------->
   <div class="footer__top">
    <a class="logo" href="{{ url('/') }}">
     <img src="/images/store/svg/noren-white.svg" alt="logo">
    </a>
    <div class="social__list">
     {{-- <a href="#" class="social__item">
                        <svg>
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5">
                            </rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a> --}}
     <a href="https://www.facebook.com/norenRomania" class="social__item" aria-label="open our Facebook page">
      <svg>
       <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
      </svg>
     </a>
     {{-- <a href="#" class="social__item">
                        <svg>
                            <path
                                d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
                            </path>
                        </svg>
                    </a>
                    <a href="#" class="social__item">
                        <svg>
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z">
                            </path>
                            <rect x="2" y="9" width="4" height="12"></rect>
                            <circle cx="4" cy="4" r="2"></circle>
                        </svg>
                    </a> --}}
    </div>
   </div>
   <!-------------------END-Logo and Social------------------->
   <!--------------------------------------------------------->
   <!------------------------Subscribe------------------------>
   <div class="footer__middle">
    <h2>Aboneaza-te la newsletter-ul nostru</h2>
    <div class="footer__checkbox">
     <input type="checkbox" wire:model="ischecked" id="subscribeCheckbox" name="subscribe__checkbox">
     <label for="subscribeCheckbox">
      Sunt de acord cu <a href="{{ url('/terms') }}">Termeni si
       conditiile</a> abonarii la newsletter privind stocarea si prelucrarea datelor cu caracter
      personal.</label>
    </div>
    <form class="subscribe" wire:submit.prevent="store">
     <input type="email" id="subscribeInput" wire:model="email" name="email" id="email"
      placeholder="Introduceți adresa dvs. de email" aria-describedby="email-error" autocomplete="email">
     <button type="submit" id="subscribeSend" @if (!$ischecked || !($email && filter_var($email, FILTER_VALIDATE_EMAIL))) disabled @endif>Trimite</button>

    </form>
   </div>
   <!----------------------END-Subscribe---------------------->
   <!--------------------------------------------------------->
   <!-----------------------Quick Links----------------------->
   <div class="footer__bottom">
    <div class="footer__list">
     <h3 class="footer__title">Serviciu clienți</h3>
     <a class="footer__link" href="{{ url('/cookie') }}">Politica de Cookies</a>
     <a class="footer__link" href="{{ url('/faq') }}">Întrebări Frecvente</a>
     <a class="footer__link" href="{{ url('/privacy') }}">Politica de confidențialitate</a>
     <a class="footer__link" href="{{ url('/') }}">Hartă Site</a>
     <a class="footer__link" target="blank" href="https://anpc.ro/">ANPC</a>

    </div>
    <div class="footer__list">
     <h3 class="footer__title">Informații</h3>
     <a class="footer__link" href="{{ url('/terms') }}">Termeni si conditii</a>
     <a class="footer__link" href="{{ url('/contact') }}">Contactează-ne</a>
     <a class="footer__link" href="{{ url('/about') }}">Despre Noi</a>
    </div>
   </div>
   <!---------------------END-Quick Links--------------------->
   <!--------------------------------------------------------->
   <!------------------------Copyright------------------------>
   <span class="footer__copyright">
    Embianz©. All rights reserved. This material may not be reproduced, displayed,
    modified, or distributed without the express written permission of Eztem-Corp.
   </span>
   <!----------------------END-Copyright---------------------->
   <!--------------------------------------------------------->
  </div>
 </footer>
 <!------------------------END-Footer----------------------->
 <!--------------------------------------------------------->
</div>
