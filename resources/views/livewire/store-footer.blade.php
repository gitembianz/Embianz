<div>
    <div class="cookie" id="cookie-banner" wire:loading.remove>
        @if (!$cookieConsent)
            <img class="cookie--img" src="/images/store/cookie.svg" alt="cookie">
            <p class="cookie--text" id="cookieConsentText">
                We use cookies to improve your experience. By continuing to visit this site, you agree to our use of
                cookies.
            </p>
            <button class="cookie--btn" wire:click="acceptCookie">Got it!</button>
        @endif
    </div>
    <x-alert />
    <footer class="footer container">
        <div class="footer__header">
            <svg class="logo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                version="1.1" width="1280" height="1024" viewBox="0 0 1280 1024" xml:space="preserve"
                alt="ecosticle.ro">
                <defs>
                </defs>
                <g transform="matrix(1 0 0 1 640 512)" id="background-logo">
                    <rect
                        style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(255,255,255); fill-opacity: 0; fill-rule: nonzero; opacity: 1;"
                        paint-order="stroke" x="-640" y="-512" rx="0" ry="0" width="1280"
                        height="1024" />
                </g>
                <g transform="matrix(4.545629534834756 0 0 4.545629534834756 640.6479799851742 512.3702742772424)"
                    id="logo-logo">
                    <g style="" paint-order="stroke">
                        <g transform="matrix(1.4162867447099505 0 0 1.4162867447099505 0 0)">
                            <path
                                style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(26,105,26); fill-rule: nonzero; opacity: 1;"
                                paint-order="stroke" transform=" translate(-297.19335939999996, -421.5514536186913)"
                                d="M 283.0551758 419.6142578 L 328.5664063 401.78955080000003 C 324.6835938 395.84033200000005 319.1972657 391.315918 312.93554689999996 388.578125 C 294.8208008 380.6601562 273.64501959999996 388.9389648 265.72558599999996 407.0576172 C 259.4848633 421.33398439999996 263.2983399 437.5014649 274.0610352 447.5922852 C 285.0200196 457.8686524 307.2226563 461.1831055 318.4492188 450.9272461 C 308.7382813 454.7524414 298.703125 454.1420899 291.1806641 450.4614258 C 311.1738282 411.1860352 370.4550782 418.6079102 392.3867188 447.1704102 C 348.515625 436.0073243 315.2832032 493.6225586 270.27783209999996 463.2817383 C 266.39843759999997 460.66748049999995 262.8798828999999 457.49560549999995 259.8720704 453.9077149 C 253.0771485 448.57373049999995 244.96484379999998 445.5024415 235.54882819999997 445.5209961 C 246.60693369999998 449.2475586 256.16748049999995 455.2202149 261.44287119999996 467.621582 C 231.215332 477.5004883 222.7910156 445.4379883 202 439.0063477 C 216.4086914 429.1879883 235.699707 427.3403321 249.5209961 433.5288086 C 246.94091799999998 423.1108398 247.6474609 411.793457 252.2797852 401.1962891 C 263.4550782 375.6298828 293.2250977 363.9541016 318.796875 375.1323243 C 330.6523438 380.3149415 340.5292969 390.0146485 345.6152344 403.0019532 L 347.9296875 409.9741212 L 274.9882812 438.54248060000003 C 272.5532227 431.4145508 276.4277344 422.2104492 283.0551758 419.6142578 L 283.0551758 419.6142578 z"
                                stroke-linecap="round" />
                        </g>
                    </g>
                </g>
            </svg>
            <nav aria-label="Social Media Links">
                <ul class="footer__list">
                    <li>
                        <a href="#" class="footer__list--social" aria-label="Facebook">
                            <svg>
                                <rect x="2" y="2" width="20" height="20" rx="5"
                                    ry="5">
                                </rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="footer__list--social" aria-label="Twitter">
                            <svg>
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="footer__list--social" aria-label="Instagram">
                            <svg>
                                <path
                                    d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="footer__list--social" aria-label="LinkedIn">
                            <svg>
                                <path
                                    d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z">
                                </path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="footer__subscribe">
            <h2>Connect to our Newsletter</h2>
            <form wire:submit.prevent="store" class="footer__subscribe--form">
                <input type="email" wire:model="email" name="email" id="email" placeholder="Enter your email"
                    aria-describedby="email-error" autocomplete="email">
                <button type="submit">Subscribe</button>
            </form>
            @if ($response)
                <p>{{ $response }}</p>
            @endif
            <p id="email-error">
                @error('email')
                    {{ $message }}
                @enderror
            </p>
        </div>
        <div class="footer__middle">
            <ul class="footer__list">
                <li>
                    <h3>Categorii Populare</h3>
                </li>
                @foreach ($categories as $category)
                    <li><a class="footer__list--item" href="#">{{ $category }}</a></li>
                @endforeach
            </ul>
            <ul class="footer__list">
                <li>
                    <h3>Serviciu clienți</h3>
                </li>
                <li><a class="footer__list--item" href="#">Termeni și Condiții</a></li>
                <li><a class="footer__list--item" href="#">Întrebări Frecvente</a></li>
                <li><a class="footer__list--item" href="#">Politica de confidențialitate</a></li>
            </ul>
            <ul class="footer__list">
                <li>
                    <h3>Informații</h3>
                </li>
                <li><a class="footer__list--item" href="#">Contacte</a></li>
                <li><a class="footer__list--item" href="#">Despre Noi</a></li>
            </ul>
        </div>
        <span><q><i>Embianz©. All rights reserved. This material may not be reproduced, displayed, modified, or
                    distributed without the express written permission of Eztem-Corp.</i></q></span>
    </footer>

</div>
