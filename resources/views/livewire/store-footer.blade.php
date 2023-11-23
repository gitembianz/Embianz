<div>
    @if (!$cookieConsent)
        <div class="cookie" wire:loading.remove>
            <div class="cookie__container container">
                <img class="cookie__img" src="/images/store/cookie.svg" alt="cookie">
                <p class="cookie__text" id="cookieConsentText">
                    We use cookies to improve your experience. By continuing to visit this site, you agree to our use of
                    cookies.
                </p>
                <button class="cookie__btn" wire:click="acceptCookie">Accept</button>
            </div>
        </div>
    @endif
    <x-alert />
    <!--------------------------------------------------------->
    <!--------------------------Footer------------------------->
    <footer class="footer">
        <div class="footer__container container">
            <!--------------------------------------------------------->
            <!---------------------Logo and Social--------------------->
            <div class="footer__top">
                <a class="logo" href="{{ url("/") }}">
                    <img src="/images/store/logo.svg" alt="logo">
                </a>
                <div class="social__list">
                    <a href="#" class="social__item">
                        <svg>
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5">
                            </rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a>
                    <a href="#" class="social__item">
                        <svg>
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                    </a>
                    <a href="#" class="social__item">
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
                    </a>
                </div>
            </div>
            <!-------------------END-Logo and Social------------------->
            <!--------------------------------------------------------->
            <!------------------------Subscribe------------------------>
            <div class="footer__middle">
                <h3>Aboneaza-te la newsletter-ul nostru</h3>
                <form class="subscribe" wire:submit.prevent="store">
                    <input type="email" wire:model="email" name="email" id="email"
                        placeholder="Introduceți adresa dvs. de email" aria-describedby="email-error"
                        autocomplete="email">
                    <button type="submit">Trimite</button>
                </form>
            </div>
            <!----------------------END-Subscribe---------------------->
            <!--------------------------------------------------------->
            <!-----------------------Quick Links----------------------->
            <div class="footer__bottom">
                <div class="footer__list">
                    <h3 class="footer__title">Categorii Populare</h3>
                    <a class="footer__link" href="#"></a>
                    @foreach ($categories as $category)
                        <a class="footer__link" href="#">{{ $category }}</a>
                    @endforeach
                </div>
                <div class="footer__list">
                    <h3 class="footer__title">Serviciu clienți</h3>
                    <a class="footer__link" href="#">Termeni și Condiții</a>
                    <a class="footer__link" href="#">Întrebări Frecvente</a>
                    <a class="footer__link" href="#">Politica de confidențialitate</a>
                </div>
                <div class="footer__list">
                    <h3 class="footer__title">Informații</h3>
                    <a class="footer__link" href="#">Contacte</a>
                    <a class="footer__link" href="#">Despre Noi</a>
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
