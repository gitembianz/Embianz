<div>


 <section id="cookie-banner" style="display: none">
  <div class="container cookie__container">
   <div class="cookie__description">
    <span>@if (app()->has('label_cookie_description')){!! app('label_cookie_description') !!} @endif</span>
    <a href="{{ url('/cookie') }}">
     @if (app()->has('label_cookie_policy')){!! app('label_cookie_policy') !!} @endif
    </a>
   </div>
   <div id="cookieForm">
    <div class="cookie__form">
     <div class="cookie__form--container">
      <label>
       <input type="checkbox" id="essential-cookies" name="essential" disabled checked>
       <span>
        @if (app()->has('label_cookie_esential_title')){!! app('label_cookie_esential_title') !!} @endif
       </span>
       <p>@if (app()->has('label_cookie_esential_description')){!! app('label_cookie_esential_description') !!} @endif</p>
      </label>
      <label>
       <input type="checkbox" id="analytics-cookies" name="analytics" checked>
       <span>@if (app()->has('label_cookie_analitics_title')){!! app('label_cookie_analitics_title') !!} @endif</span>
       <p>@if (app()->has('label_cookie_analitics_description')){!! app('label_cookie_analitics_description') !!} @endif</p>
      </label>
      <label>
       <input type="checkbox" id="marketing-cookies" name="marketing" checked>
       <span>@if (app()->has('label_cookie_marketing_title')){!! app('label_cookie_marketing_title') !!} @endif</span>
       <p>@if (app()->has('label_cookie_marketing_description')){!! app('label_cookie_marketing_description') !!} @endif</p>
      </label>
     </div>
    </div>
    <div class="cookie-btns">
     <button id="accept-cookies" class="cookie__button cookie__button--accept">
      @if (app()->has('label_cookie_accept_button')){!! app('label_cookie_accept_button') !!} @endif
     </button>
     <button id="advanced-settings" class="cookie__button" type="button">
      @if (app()->has('label_cookie_advanced_button')){!! app('label_cookie_advanced_button') !!} @endif
     </button>
    </div>
   </div>
  </div>
 </section>

 <x-alert-newsletter />

 <!--------------------------Footer------------------------->
 <footer class="footer">
  <div class="footer__container container">
   <!---------------------Logo and Social--------------------->
   <div class="footer__top">
    <a class="logo" href="{{ url('/') }}">
     <img loading="eager" src="/images/store/svg/logo-light.svg" alt="logo">
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
    </div>
   </div>
   <!------------------------Subscribe------------------------>
   <div class="footer__middle">
    <h2>@if (app()->has('label_newsletter_subscribe_title')){!! app('label_newsletter_subscribe_title') !!} @endif</h2>
    <div class="footer__checkbox">
     <input type="checkbox" wire:model="ischecked" id="subscribeCheckbox" name="subscribe__checkbox">
     <label for="subscribeCheckbox">@if (app()->has('label_terms_confirm')){!! app('label_terms_confirm') !!} @endif</label>
    </div>
    <form class="subscribe" wire:submit.prevent="store">
     <input type="email" id="subscribeInput" wire:model="email" name="email" id="email"
      placeholder="@if (app()->has('label_newsletter_subscribe_placeholder')){!! app('label_newsletter_subscribe_placeholder') !!} @endif" aria-describedby="email-error" autocomplete="email">
     <button type="submit" id="subscribeSend" @if (!$ischecked || !($email && filter_var($email, FILTER_VALIDATE_EMAIL))) disabled @endif>@if (app()->has('label_newsletter_subscribe_button')){!! app('label_newsletter_subscribe_button') !!} @endif</button>

    </form>
   </div>
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
   <!------------------------Copyright------------------------>
   <span class="footer__copyright">@if (app()->has('label_footer_copyright')){!! app('label_footer_copyright') !!} @endif</span>
  </div>
 </footer>
</div>
