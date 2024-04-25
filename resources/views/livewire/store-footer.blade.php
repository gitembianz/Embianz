<div>

 @if (!$cookieConsent)
  <section id="cookie-banner" wire:loading.remove class="hidden">
   <div class="container cookie__container">
    <div class="cookie__description">
     <span>
      Acest site web utilizează cookie-uri pentru a îmbunătăți experiența dvs. de navigare și pentru a vă oferi cel mai
      bun serviciu posibil pe platforma noastră.
     </span>
     <a href="{{ url('/cookie') }}">
      Vedeți Politica de Cookies
     </a>
    </div>
    <div id="cookieForm">
     <div class="cookie__form @if ($advance) show @endif">
      <div class="cookie__form--container">
       <label>
        <input type="checkbox" name="essential" disabled checked>
        <span>
         Cookie-uri Esențiale (Necesare)
        </span>
        <p>
         Acestea sunt cookie-uri esențiale care asigură funcționarea corectă a site-ului web și păstrarea preferințelor
         dvs. (de ex., limbă, regiune).
        </p>
       </label>
       <label>
        <input type="checkbox" name="analytics">
        <span>
         Cookie-uri Analitice
        </span>
        <p>
         Aceste cookie-uri includ cookie-uri de performanță și cookie-uri de analiză a vizitatorilor.
        </p>
       </label>
       <label>
        <input type="checkbox" name="marketing">
        <span>
         Cookie-uri de Marketing
        </span>
        <p>
         Aceste cookie-uri sunt utilizate în scopuri de marketing.
        </p>
       </label>
      </div>
     </div>
     <div class="cookie-btns">
      <button wire:click="acceptCookie" class="cookie__button cookie__button--accept">
       Acceptă
      </button>
      <button wire:click="advancecookie" id="advanced-settings" class="cookie__button" type="button">
       Avansat
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
     <img loading="lazy" src="/images/store/svg/noren-white.svg" alt="logo">
    </a>
    <div class="social__list">
     @if (app()->has('global_instagram_url') && app('global_instagram_url') != '')
      <a href="{{ app('global_instagram_url') }}" target="_blank" class="social__item"
       aria-label="open our Instagram page">
       <svg>
        <rect x="2" y="2" width="20" height="20" rx="5" ry="5">
        </rect>
        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
       </svg>
      </a>
     @endif
     @if (app()->has('global_facebook_url') && app('global_facebook_url') != '')
      <a href="{{ app('global_facebook_url') }}" target="_blank" class="social__item"
       aria-label="open our Facebook page">
       <svg>
        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
       </svg>
      </a>
     @endif
     {{-- <a href="#" class="social__item">
						<svg>
							<path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
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
    <h2>Abonează-te la newsletter-ul nostru</h2>
    <div class="footer__checkbox">
     <input type="checkbox" wire:model="ischecked" id="subscribeCheckbox" name="subscribe__checkbox">
     <label for="subscribeCheckbox">
      Sunt de acord cu <a href="{{ url('/terms') }}">Termenii și
       condițiile</a> abonării la newsletter privind stocarea și prelucrarea datelor cu caracter
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
     <a class="footer__link" href="{{ url('/sitemap.xml') }}">Hartă Site</a>
     <a class="footer__link" target="blank" href="https://anpc.ro/">ANPC</a>

    </div>
    <div class="footer__list">
     <h3 class="footer__title">Informații</h3>
     <a class="footer__link" href="{{ url('/terms') }}">Termeni și Condiții</a>
     <a class="footer__link" href="{{ url('/contact') }}">Contactează-ne</a>
     <a class="footer__link" href="{{ url('/about') }}">Despre Noi</a>
    </div>
   </div>
   <!---------------------END-Quick Links--------------------->
   <!--------------------------------------------------------->
   <!------------------------Copyright------------------------>
   <span class="footer__copyright">
    Copyright ©️ 2024 <a href="{{ url('/') }}">noren</a> | Powered by <a href="https://eztemcorp.com">Eztem
     Corp</a>
   </span>
   <!----------------------END-Copyright---------------------->
   <!--------------------------------------------------------->
  </div>
 </footer>
 <!------------------------END-Footer----------------------->
 <!--------------------------------------------------------->
</div>
