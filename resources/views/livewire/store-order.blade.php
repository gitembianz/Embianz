<div class="details container">
    <x-loading />
    <x-alert />
    @if ($back)
        <h1 class="section__title">Something went wrong!</h1>
        <div class="details__btns">
            <a href="/home">Please go back</a>
        </div>
    @else
        <div class="details__steps">
            <div class="details__steps--item active">1</div>
            <span class="details__steps--line active"></span>
            <div class="details__steps--item @if ($step > 1 || $step == 3) active @endif">2</div>
            <span class="details__steps--line @if ($step == 3) active @endif"></span>
            <div class="details__steps--item @if ($step == 3) active @endif">3</div>
        </div>

        @if ($step == 1)
            <h1 class="section__title">Delivery details</h1>

            <div class="details__tab">
                <div class="details__tab--header" style="justify-content: space-between">
                    <div>
                        <button wire:click="showindividual()"
                            class="details__tab--btn @if ($individual) active @endif"
                            data-tooltip="Get this product for your personal enjoyment">Individual</button>
                        <button wire:click="showjuridic()"
                            class="details__tab--btn @if ($juridic) active @endif"
                            data-tooltip="Purchase this item from company">Legal
                            Person
                        </button>
                    </div>
                    <div class="details__btns">
                        <a wire:click="resetForm" data-tooltip="Clear form"><svg>
                                <polyline points="1 4 1 10 7 10"></polyline>
                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                            </svg>
                        </a>
                        <a wire:click.prevent="next()" data-tooltip="Go to next step">
                            <svg>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                        @if ($step == 2)
                            <a wire:click.prevent="previous()" data-tooltip="Go to previous step">
                                <svg>
                                    <line x1="19" y1="12" x2="5" y2="12"></line>
                                    <polyline points="12 19 5 12 12 5"></polyline>
                                </svg>
                            </a>
                            <a wire:click.prevent="confirm()" data-tooltip="Confirm order">
                                <svg>
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                            </a>
                        @elseif ($step == 3)
                            <a wire:click.prevent="finish()" data-tooltip="Go back to store">
                                Thank you for your order, Shopping again
                            </a>
                        @endif

                    </div>
                </div>
                <div class="details__tab--content">
                    <div class="details__tab--pane @if ($individual) active @endif">
                        <!-- here is billing contact -->
                        <div class="details__content-add">
                            <span class="details__content-add--number">1</span>
                            <div class="details__content-add--column">
                                <span class="details__content-add--text">Billing Contact &#9998;</span>
                                <div>
                                    <input class="details__content-add--input" type="text"
                                        wire:model="individual_billing_first" placeholder="First Name">
                                    <p class="real-time-validation">
                                        @error('individual_billing_first')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" type="text"
                                        wire:model="individual_billing_last" placeholder="Last Name">
                                    <p class="real-time-validation">
                                        @error('individual_billing_last')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" type="text"
                                        wire:model="individual_billing_phone" placeholder="Phone">
                                    <p class="real-time-validation">
                                        @error('individual_billing_phone')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" type="text"
                                        wire:model="individual_billing_email" placeholder="Email">
                                    <p class="real-time-validation">
                                        @error('individual_billing_email')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                            </div>

                        </div>
                        <!-- here is billing address -->
                        <div class="details__content-add">
                            <span class="details__content-add--number">2</span>
                            <div class="details__content-add--column">
                                <span class="details__content-add--text">Billing Address &#9998;</span>
                                <div>
                                    <input class="details__content-add--input" wire:model="individual_billing_address1"
                                        type="text" placeholder="Address 1*">
                                    <p class="real-time-validation">
                                        @error('individual_billing_address1')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>

                                    <input class="details__content-add--input" wire:model="individual_billing_address2"
                                        type="text" placeholder="Address 2">
                                </div>

                                <div>
                                    <input class="details__content-add--input" wire:model="individual_billing_country"
                                        type="text" placeholder="Country">
                                    <p class="real-time-validation">
                                        @error('individual_billing_country')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>

                                    <input class="details__content-add--input" wire:model="individual_billing_county"
                                        type="text" placeholder="County">
                                </div>

                                <div>
                                    <input class="details__content-add--input" wire:model="individual_billing_city"
                                        type="text" placeholder="City">
                                    <p class="real-time-validation">
                                        @error('individual_billing_city')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="individual_billing_zipcode"
                                        type="text" placeholder="Post Code">
                                    <p class="real-time-validation">
                                        @error('individual_billing_zipcode')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="details__content--checkbox @if ($individual_identic) active @endif">
                            Shipping address is identical to Billing Address
                            <label class="checkbox">
                                <input wire:model="individual_identic" type="checkbox">
                                <span></span>
                            </label>
                        </div>

                        @if (!$individual_identic)
                            <!-- here is delivery contact -->
                            <div class="details__content-add">
                                <span class="details__content-add--number">3</span>
                                <div class="details__content-add--column">
                                    <span class="details__content-add--text">Shipping Contact &#9998;</span>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_first" type="text"
                                            placeholder="First Name">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_first')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_last" type="text"
                                            placeholder="Last Name">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_last')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_phone" type="text"
                                            placeholder="Phone">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_phone')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_email" type="text"
                                            placeholder="Email">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_email')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Here is shipping address -->
                            <div class="details__content-add">
                                <span class="details__content-add--number">4</span>
                                <div class="details__content-add--column">
                                    <span class="details__content-add--text">Shipping Address &#9998;</span>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_address1" type="text"
                                            placeholder="Address 1*">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_address1')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>

                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_address2" type="text"
                                            placeholder="Address 2">
                                    </div>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_country" type="text"
                                            placeholder="Country">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_country')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>

                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_county" type="text"
                                            placeholder="County">
                                    </div>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_city" type="text" placeholder="City">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_city')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>
                                        <input class="details__content-add--input"
                                            wire:model="individual_shipping_zipcode" type="text"
                                            placeholder="Post Code">
                                        <p class="real-time-validation">
                                            @error('individual_shipping_zipcode')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="details__tab--pane @if ($juridic) active @endif">
                        <!-- here is billing contact -->
                        <div class="details__content-add">
                            <span class="details__content-add--number">1</span>
                            <div class="details__content-add--column">
                                <span class="details__content-add--text">Juridical information &#9998;</span>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_first"
                                        type="text" placeholder="First Name">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_first')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_last"
                                        type="text" placeholder="Last Name">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_last')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_phone"
                                        type="text" placeholder="Phone">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_phone')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_email"
                                        type="text" placeholder="Email">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_email')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input"
                                        wire:model="juridic_billing_company_name" type="text"
                                        placeholder="Company Name">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_company_name')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input"
                                        wire:model="juridic_billing_registration_code" type="text"
                                        placeholder="Registration code">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_registration_code')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input"
                                        wire:model="juridic_billing_registration_number" type="text"
                                        placeholder="Registration number">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_registration_number')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_bank"
                                        type="text" placeholder="Bank">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_bank')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_account"
                                        type="text" placeholder="Account">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_account')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- here is billing address -->
                        <div class="details__content-add">
                            <span class="details__content-add--number">2</span>
                            <div class="details__content-add--column">
                                <span class="details__content-add--text">Billing Address &#9998;</span>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_address1"
                                        type="text" placeholder="Address 1*">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_address1')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>

                                    <input class="details__content-add--input" type="text"
                                        placeholder="Address 2">
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_country"
                                        type="text" placeholder="Country">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_country')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>

                                    <input class="details__content-add--input" type="text" placeholder="County">
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_city"
                                        type="text" placeholder="City">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_city')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                <div>
                                    <input class="details__content-add--input" wire:model="juridic_billing_zipcode"
                                        type="text" placeholder="Post Code">
                                    <p class="real-time-validation">
                                        @error('juridic_billing_zipcode')
                                            {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="details__content--checkbox @if ($juridic_identic) active @endif">
                            Shipping address is identical to Billing Address
                            <label class="checkbox">
                                <input wire:model="juridic_identic" type="checkbox">
                                <span></span>
                            </label>
                        </div>
                        @if (!$juridic_identic)
                            <!-- here is delivery contact -->
                            <div class="details__content-add">
                                <span class="details__content-add--number">3</span>
                                <div class="details__content-add--column">
                                    <span class="details__content-add--text">Delivery Contact &#9998;</span>
                                    <div>
                                        <input class="details__content-add--input" wire:model="juridic_shipping_first"
                                            type="text" placeholder="First Name">
                                        <p class="real-time-validation">
                                            @error('juridic_shipping_first')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>
                                        <input class="details__content-add--input" wire:model="juridic_shipping_last"
                                            type="text" placeholder="Last Name">
                                        <p class="real-time-validation">
                                            @error('juridic_shipping_last')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>
                                        <input class="details__content-add--input" wire:model="juridic_shipping_phone"
                                            type="text" placeholder="Phone">
                                        <p class="real-time-validation">
                                            @error('juridic_shipping_phone')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div>
                                        <input class="details__content-add--input" wire:model="juridic_shipping_email"
                                            type="text" placeholder="Email">
                                        <p class="real-time-validation">
                                            @error('juridic_shipping_email')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Here is shipping address -->
                            <div class="details__content-add">
                                <span class="details__content-add--number">4</span>
                                <div class="details__content-add--column">
                                    <span class="details__content-add--text">Shipping Address &#9998;</span>
                                    {{-- <input class="details__content-add--input" type="text" placeholder="First Name">
                            <input class="details__content-add--input" type="text" placeholder="Last Name">
                            <input class="details__content-add--input" type="text" placeholder="Phone">
                            <input class="details__content-add--input" type="text" placeholder="Email"> --}}
                                    <div>
                                        <input class="details__content-add--input" type="text"
                                            placeholder="Address 1*">
                                    </div>
                                    <div>
                                        <input class="details__content-add--input" type="text"
                                            placeholder="Address 2">
                                    </div>
                                    <div>
                                        <input class="details__content-add--input" type="text"
                                            placeholder="Country">
                                    </div>
                                    <div>
                                        <input class="details__content-add--input" type="text"
                                            placeholder="County">
                                    </div>
                                    <div>
                                        <input class="details__content-add--input" type="text" placeholder="City">
                                    </div>
                                    <div>

                                        <input class="details__content-add--input" type="text"
                                            placeholder="Post Code">
                                    </div>

                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <h1 class="section__title">Payment method</h1>
            <div class="details__accordion">
                @foreach ($payments as $payment)
                    @if ($payment->name == 'card')
                        @if ($payment->active)
                            <label class="details__accordion--item" for="cardonline"
                                wire:click="togglepayment('card')">
                                <div
                                    class="details__accordion-header @if ($card) active @endif">
                                    <input type="checkbox" wire:model.defer="card" id="cardonline">
                                    <div class="details__accordion-header--img">
                                        <svg>
                                            <rect x="1" y="4" width="22" height="16" rx="2"
                                                ry="2">
                                            </rect>
                                            <line x1="1" y1="10" x2="23" y2="10">
                                            </line>
                                        </svg>
                                    </div>
                                    <h4 class="details__accordion-header--text">
                                        Card Online
                                    </h4>
                                    <div class="details__accordion-header--svg">
                                        <svg viewBox="0 0 256 256">
                                            <defs>
                                            </defs>
                                            <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;"
                                                transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                                                <path
                                                    d="M 0 31.418 c 1.209 -0.622 2.591 -0.371 3.889 -0.395 c 2.973 0.078 5.953 -0.072 8.926 0.06 c 1.34 -0.006 2.489 1.107 2.674 2.405 c 0.933 4.523 1.789 9.064 2.704 13.599 c -1.514 -5.313 -5.51 -9.65 -10.207 -12.408 C 5.504 33.189 2.734 32.322 0 31.418 z"
                                                    style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;"
                                                    transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                                <path
                                                    d="M 36.483 31.023 c 2.423 0.018 4.846 0.018 7.269 0 c -1.49 9.369 -3.057 18.726 -4.511 28.101 c -2.411 0.006 -4.822 0.006 -7.239 0 C 33.39 49.744 35.024 40.399 36.483 31.023 z"
                                                    style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;"
                                                    transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                                <path
                                                    d="M 50.645 32.788 c 4.344 -2.854 10.021 -2.77 14.826 -1.191 c -0.221 2.076 -0.568 4.134 -0.927 6.186 c -2.555 -1.226 -5.534 -1.771 -8.31 -1.059 c -1.31 0.311 -2.603 1.938 -1.514 3.159 c 2.238 2.291 5.761 2.812 7.867 5.307 c 2.441 2.387 2.351 6.408 0.688 9.202 c -1.759 2.95 -5.145 4.493 -8.442 4.936 c -3.727 0.389 -7.61 0.156 -11.104 -1.286 c 0.371 -2.094 0.682 -4.2 1.059 -6.294 c 3.021 1.562 6.527 2.345 9.896 1.597 c 1.143 -0.383 2.321 -1.322 2.22 -2.662 c -0.377 -1.597 -2.046 -2.279 -3.344 -3.003 c -2.519 -1.238 -5.229 -2.758 -6.36 -5.48 C 45.793 38.759 47.653 34.733 50.645 32.788 z"
                                                    style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;"
                                                    transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                                <path
                                                    d="M 84.119 31.059 c -2.226 0.042 -4.463 -0.09 -6.689 0.066 c -1.514 0.078 -2.471 1.484 -2.956 2.776 c -3.5 8.424 -7.042 16.83 -10.548 25.248 c 2.537 -0.006 5.073 -0.006 7.61 -0.006 c 0.532 -1.406 1.047 -2.824 1.544 -4.242 c 3.099 0.018 6.198 0.012 9.303 0.012 c 0.287 1.418 0.586 2.83 0.903 4.236 c 2.238 -0.006 4.475 -0.006 6.713 -0.012 C 88.038 49.78 86.111 40.416 84.119 31.059 z M 75.192 49.121 c 1.352 -3.476 2.459 -7.054 3.973 -10.464 c 0.449 3.53 1.328 6.988 2.052 10.47 C 79.207 49.127 77.203 49.127 75.192 49.121 z"
                                                    style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;"
                                                    transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                                <path
                                                    d="M 18.78 50.21 c -1.165 -4.091 -6.151 -10.616 -10.221 -13.379 c 1.994 7.425 3.908 14.872 5.937 22.287 c 2.513 0 5.026 -0.036 7.538 0.042 c 3.997 -9.315 7.646 -18.774 11.559 -28.125 c -2.573 0.018 -5.145 0.03 -7.724 0 C 23.501 37.425 21.125 43.815 18.78 50.21"
                                                    style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;"
                                                    transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                            </g>
                                        </svg>
                                        <svg viewBox="0 0 256 256">
                                            <defs>
                                            </defs>
                                            <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;"
                                                transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                                                <path
                                                    d="M 84.259 16.068 H 5.741 C 2.57 16.068 0 18.638 0 21.809 v 6.131 v 2 V 60.06 v 2 v 6.131 c 0 3.171 2.57 5.741 5.741 5.741 h 78.518 c 3.171 0 5.741 -2.57 5.741 -5.741 V 62.06 v -2 V 29.94 v -2 v -6.131 C 90 18.638 87.43 16.068 84.259 16.068 z"
                                                    style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(59,55,55); fill-rule: nonzero; opacity: 1;"
                                                    transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                                <path
                                                    d="M 19.895 65.192 v -4.057 c 0 -1.552 -0.945 -2.568 -2.568 -2.568 c -0.811 0 -1.694 0.268 -2.3 1.15 c -0.473 -0.74 -1.15 -1.15 -2.166 -1.15 c -0.677 0 -1.355 0.205 -1.891 0.945 v -0.811 H 9.552 v 6.491 h 1.418 v -3.584 c 0 -1.15 0.607 -1.694 1.552 -1.694 c 0.945 0 1.418 0.607 1.418 1.694 v 3.584 h 1.418 v -3.584 c 0 -1.15 0.677 -1.694 1.552 -1.694 c 0.945 0 1.418 0.607 1.418 1.694 v 3.584 L 19.895 65.192 L 19.895 65.192 z M 40.928 58.701 h -2.3 V 56.74 H 37.21 v 1.962 h -1.284 v 1.284 h 1.284 v 2.978 c 0 1.489 0.607 2.363 2.229 2.363 c 0.607 0 1.284 -0.205 1.757 -0.473 l -0.41 -1.221 c -0.41 0.268 -0.882 0.339 -1.221 0.339 c -0.677 0 -0.945 -0.41 -0.945 -1.079 v -2.907 h 2.3 v -1.284 H 40.928 z M 52.965 58.559 c -0.811 0 -1.355 0.41 -1.694 0.945 v -0.811 h -1.418 v 6.491 h 1.418 v -3.655 c 0 -1.079 0.473 -1.694 1.355 -1.694 c 0.268 0 0.607 0.071 0.882 0.134 l 0.41 -1.355 C 53.634 58.559 53.232 58.559 52.965 58.559 L 52.965 58.559 z M 34.775 59.237 c -0.677 -0.473 -1.623 -0.677 -2.639 -0.677 c -1.623 0 -2.702 0.811 -2.702 2.095 c 0 1.079 0.811 1.694 2.229 1.891 l 0.677 0.071 c 0.74 0.134 1.15 0.339 1.15 0.677 c 0 0.473 -0.544 0.811 -1.489 0.811 c -0.945 0 -1.694 -0.339 -2.166 -0.677 l -0.677 1.079 c 0.74 0.544 1.757 0.811 2.773 0.811 c 1.891 0 2.978 -0.882 2.978 -2.095 c 0 -1.15 -0.882 -1.757 -2.229 -1.962 l -0.677 -0.071 c -0.607 -0.071 -1.079 -0.205 -1.079 -0.607 c 0 -0.473 0.473 -0.74 1.221 -0.74 c 0.811 0 1.623 0.339 2.032 0.544 L 34.775 59.237 L 34.775 59.237 z M 72.501 58.559 c -0.811 0 -1.355 0.41 -1.694 0.945 v -0.811 h -1.418 v 6.491 h 1.418 v -3.655 c 0 -1.079 0.473 -1.694 1.355 -1.694 c 0.268 0 0.607 0.071 0.882 0.134 l 0.41 -1.339 C 73.178 58.559 72.777 58.559 72.501 58.559 L 72.501 58.559 z M 54.383 61.947 c 0 1.962 1.355 3.379 3.45 3.379 c 0.945 0 1.623 -0.205 2.3 -0.74 l -0.677 -1.15 c -0.544 0.41 -1.079 0.607 -1.694 0.607 c -1.15 0 -1.962 -0.811 -1.962 -2.095 c 0 -1.221 0.811 -2.032 1.962 -2.095 c 0.607 0 1.15 0.205 1.694 0.607 l 0.677 -1.15 c -0.677 -0.544 -1.355 -0.74 -2.3 -0.74 C 55.738 58.559 54.383 59.985 54.383 61.947 L 54.383 61.947 L 54.383 61.947 z M 67.499 61.947 v -3.246 h -1.418 v 0.811 c -0.473 -0.607 -1.15 -0.945 -2.032 -0.945 c -1.828 0 -3.246 1.418 -3.246 3.379 s 1.418 3.379 3.246 3.379 c 0.945 0 1.623 -0.339 2.032 -0.945 v 0.811 h 1.418 V 61.947 L 67.499 61.947 z M 62.292 61.947 c 0 -1.15 0.74 -2.095 1.962 -2.095 c 1.15 0 1.962 0.882 1.962 2.095 c 0 1.15 -0.811 2.095 -1.962 2.095 C 63.04 63.971 62.292 63.089 62.292 61.947 L 62.292 61.947 z M 45.323 58.559 c -1.891 0 -3.246 1.355 -3.246 3.379 c 0 2.032 1.355 3.379 3.316 3.379 c 0.945 0 1.891 -0.268 2.639 -0.882 l -0.677 -1.016 c -0.544 0.41 -1.221 0.677 -1.891 0.677 c -0.882 0 -1.757 -0.41 -1.961 -1.552 h 4.797 c 0 -0.205 0 -0.339 0 -0.544 C 48.364 59.914 47.143 58.559 45.323 58.559 L 45.323 58.559 L 45.323 58.559 z M 45.323 59.78 c 0.882 0 1.489 0.544 1.623 1.552 h -3.379 C 43.701 60.458 44.307 59.78 45.323 59.78 L 45.323 59.78 z M 80.552 61.947 v -5.814 h -1.418 v 3.379 c -0.473 -0.607 -1.15 -0.945 -2.032 -0.945 c -1.828 0 -3.246 1.418 -3.246 3.379 s 1.418 3.379 3.246 3.379 c 0.945 0 1.623 -0.339 2.032 -0.945 v 0.811 h 1.418 V 61.947 L 80.552 61.947 z M 75.345 61.947 c 0 -1.15 0.74 -2.095 1.962 -2.095 c 1.15 0 1.962 0.882 1.962 2.095 c 0 1.15 -0.811 2.095 -1.962 2.095 C 76.085 63.971 75.345 63.089 75.345 61.947 L 75.345 61.947 z M 27.875 61.947 v -3.246 h -1.418 v 0.811 c -0.473 -0.607 -1.15 -0.945 -2.032 -0.945 c -1.828 0 -3.246 1.418 -3.246 3.379 s 1.418 3.379 3.246 3.379 c 0.945 0 1.623 -0.339 2.032 -0.945 v 0.811 h 1.418 V 61.947 L 27.875 61.947 z M 22.605 61.947 c 0 -1.15 0.74 -2.095 1.962 -2.095 c 1.15 0 1.962 0.882 1.962 2.095 c 0 1.15 -0.811 2.095 -1.962 2.095 C 23.345 63.971 22.605 63.089 22.605 61.947 z"
                                                    style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(212,212,212); fill-rule: nonzero; opacity: 1;"
                                                    transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                                <rect x="38.6" y="26.91" rx="0" ry="0" width="12.72"
                                                    height="22.86"
                                                    style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,90,0); fill-rule: nonzero; opacity: 1;"
                                                    transform=" matrix(1 0 0 1 0 0) " />
                                                <path
                                                    d="M 39.451 38.339 c 0 -4.645 2.184 -8.767 5.534 -11.43 c -2.466 -1.939 -5.576 -3.111 -8.969 -3.111 c -8.038 0 -14.541 6.503 -14.541 14.541 S 27.978 52.88 36.015 52.88 c 3.393 0 6.503 -1.172 8.969 -3.111 C 41.629 47.143 39.451 42.983 39.451 38.339 z"
                                                    style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(235,0,27); fill-rule: nonzero; opacity: 1;"
                                                    transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                                <path
                                                    d="M 68.495 38.339 c 0 8.038 -6.503 14.541 -14.541 14.541 c -3.393 0 -6.503 -1.172 -8.969 -3.111 c 3.393 -2.668 5.534 -6.786 5.534 -11.43 s -2.184 -8.767 -5.534 -11.43 c 2.461 -1.939 5.572 -3.111 8.965 -3.111 C 61.992 23.798 68.495 30.343 68.495 38.339 z"
                                                    style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(247,158,27); fill-rule: nonzero; opacity: 1;"
                                                    transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                                <path
                                                    d="M 4 68.191 V 62.06 v -2 V 29.94 v -2 v -6.131 c 0 -3.171 2.57 -5.741 5.741 -5.741 h -4 C 2.57 16.068 0 18.638 0 21.809 v 6.131 V 62.06 v 6.131 c 0 3.171 2.57 5.741 5.741 5.741 h 4 C 6.57 73.932 4 71.362 4 68.191 z"
                                                    style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(46,42,42); fill-rule: nonzero; opacity: 1;"
                                                    transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                                <div class="details__accordion-wrap"
                                    @if ($card) style="max-height: 200px" @endif>
                                    <div class="details__accordion-content">
                                        <input class="details__accordion-content--input" type="text"
                                            placeholder="Card Number">
                                        <input class="details__accordion-content--input" type="text"
                                            placeholder="Expiry Date">
                                        <input class="details__accordion-content--input" type="text"
                                            placeholder="CVV">
                                        <input class="details__accordion-content--input" type="text"
                                            placeholder="Name on Card">
                                    </div>
                                </div>
                            </label>
                        @endif
                    @endif
                    @if ($payment->name == 'cash on delivery')
                        @if ($payment->active)
                            <label class="details__accordion--item" for="rtc" wire:click="togglepayment('rtc')">
                                <div
                                    class="details__accordion-header @if ($rtc) active @endif">
                                    <input type="checkbox" wire:model.defer="rtc" id="rtc">
                                    <span class="details__accordion-header--img">
                                        <svg>
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </span>
                                    <h4 class="details__accordion-header--text">
                                        Cash on Delivery
                                    </h4>
                                </div>
                                <div class="details__accordion-wrap"
                                    @if ($rtc) style="max-height: 200px" @endif>
                                    <div class="details__accordion-content">
                                        <p class="details__accordion-content--text">You will pay when the order is
                                            delivered.<br>
                                            <span class="details__accordion-content--span">
                                                7.00 Lei represents the cost for processing the payment upon delivery.
                                                Online card
                                                payment
                                                is
                                                free.
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </label>
                        @endif
                    @endif
                    @if ($payment->name == 'invoice')
                        @if ($payment->active)
                            @if ($juridic)
                                <label class="details__accordion--item" for="invoice"
                                    wire:click="togglepayment('invoice')">
                                    <div
                                        class="details__accordion-header @if ($invoice) active @endif">
                                        <input type="checkbox" wire:model.defer="invoice" id="invoice">
                                        <span class="details__accordion-header--img">
                                            <svg>
                                                <path
                                                    d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                </path>
                                                <polyline points="22,6 12,13 2,6"></polyline>
                                            </svg>
                                        </span>
                                        <h4 class="details__accordion-header--text">
                                            Payment Order
                                        </h4>
                                    </div>
                                    <div class="details__accordion-wrap"
                                        @if ($invoice) style="max-height: 200px" @endif>
                                        <div class="details__accordion-content">
                                            <p class="details__accordion-content--text">
                                                Payment method used by legal entities. After placing the order, you will
                                                receive by
                                                email
                                                the proforma invoice with all the payment details.
                                            </p>
                                        </div>
                                    </div>
                                </label>
                            @endif
                        @endif
                    @endif
                @endforeach
            </div>
        @endif
        @if ($step == 2)
            <div class="details__tab--header" style="justify-content: space-between; width: 100%">
                <div class="details__btns">
                    <a wire:click.prevent="previous()" data-tooltip="Go to previous step">
                        <svg>
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                    </a>
                    <a wire:click.prevent="confirm()" data-tooltip="Confirm order">
                        <svg>
                            <polyline points="9 11 12 14 22 4"></polyline>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                    </a>
                </div>
                <h1 class="section__title">Check your details</h1>

            </div>
            <div class="checking__wrapper">
                <div class="checking__cart">
                    <div class="checking__cart--items">
                        @if (!$cartItems->isEmpty())
                            @foreach ($cartItems as $cartItem)
                                <div class="checking__content-item">
                                    @if (count($cartItem->product->media) > 0)
                                        @foreach ($cartItem->product->media as $media)
                                            @if ($media->location->location == 'main')
                                                @if ($media->external)
                                                    <img class="cart__list--img" src="{{ $media->path }}"
                                                        alt="{{ $media->path }}">
                                                @else
                                                    <img class="cart__list--img"
                                                        src="/{{ $media->path }}{{ $media->name }}"
                                                        alt="{{ $media->path }}">
                                                @endif
                                                <?php break; ?>
                                            @endif
                                        @endforeach
                                    @else
                                        <img class="cart__list--img" src="/images/store/default/default.svg"
                                            alt="something wrong">
                                    @endif
                                    <span class="cart__list--text">{{ $cartItem->product->name }}</span>
                                    <span class="cart__list--much">x {{ $cartItem->quantity }}</span>
                                    <span class="cart__list--much">
                                        @php
                                            $price = $cartItem->product->product_prices->first();
                                        @endphp
                                        @if ($price)
                                            {{ $price->value }} {{ $price->pricelist->currency->first()->name }}
                                        @else
                                            unavailable
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <!-- Here is Card Information -->
                    <div class="checking__content-complete">
                        <span class="checking__content-create--text">{{ $delivery }} &check;</span>
                    </div>
                    <div class="checking__content-price">
                        @if (!$cartItems->isEmpty())
                            <span class="checking__content-complete--text">Total Price:</span>
                            <span class="checking__content-create--text">{{ $cart->sum_amount }}
                                {{ $cart->currency->first()->name }}</span>
                        @endif
                    </div>
                </div>
                <div class="checking">
                    <!-- here is billing information -->
                    @if ($individual)
                        <div class="checking__content-complete">
                            <span class="checking__content-create--text">Billing information &check;</span>
                            <span class="checking__content-complete--text">Full Name: {{ $individual_billing_first }}
                                {{ $individual_billing_last }}</span>
                            <span class="checking__content-complete--text">Phone:
                                {{ $individual_billing_phone }}</span>
                            <span class="checking__content-complete--text">Email:
                                {{ $individual_billing_email }}</span>
                            <span class="checking__content-complete--text">Address: {{ $individual_billing_address1 }}
                                {{ $individual_billing_address2 }}</span>
                            <span class="checking__content-complete--text">Country:
                                {{ $individual_billing_country }}</span>
                            <span class="checking__content-complete--text">County:
                                {{ $individual_billing_county }}</span>
                            <span class="checking__content-complete--text">City: {{ $individual_billing_city }}</span>
                            <span class="checking__content-complete--text">Post Code:
                                {{ $individual_billing_zipcode }}</span>
                        </div>
                        <!-- here is shipping information -->
                        <div class="checking__content-complete">
                            <span class="checking__content-create--text">Shipping information
                                &check;</span>
                            <span class="checking__content-complete--text">Full Name: {{ $individual_shipping_first }}
                                {{ $individual_shipping_last }}</span>
                            <span class="checking__content-complete--text">Phone:
                                {{ $individual_shipping_phone }}</span>
                            <span class="checking__content-complete--text">Email:
                                {{ $individual_shipping_email }}</span>
                            <span class="checking__content-complete--text">Address:
                                {{ $individual_shipping_address1 }}
                                {{ $individual_shipping_address2 }}</span>
                            <span class="checking__content-complete--text">Country:
                                {{ $individual_shipping_country }}</span>
                            <span class="checking__content-complete--text">County:
                                {{ $individual_shipping_county }}</span>
                            <span class="checking__content-complete--text">City:
                                {{ $individual_shipping_city }}</span>
                            <span class="checking__content-complete--text">Post Code:
                                {{ $individual_shipping_zipcode }}</span>
                        </div>
                    @endif
                    @if ($juridic)
                        <div class="checking__content-complete">
                            <span class="checking__content-create--text">Billing information &check;</span>
                            <span class="checking__content-complete--text">Full Name: {{ $juridic_billing_first }}
                                {{ $juridic_billing_last }}</span>
                            <span class="checking__content-complete--text">Phone:
                                {{ $juridic_billing_phone }}</span>
                            <span class="checking__content-complete--text">Email:
                                {{ $juridic_billing_email }}</span>
                            <span class="checking__content-complete--text">Company:
                                {{ $juridic_billing_company_name }}</span>
                            <span class="checking__content-complete--text">Registration code:
                                {{ $juridic_billing_registration_code }}</span>
                            <span class="checking__content-complete--text">Registration number:
                                {{ $juridic_billing_registration_number }}</span>
                            <span class="checking__content-complete--text">Bank:
                                {{ $juridic_billing_bank }}</span>
                            <span class="checking__content-complete--text">Account:
                                {{ $juridic_billing_account }}</span>
                            <span class="checking__content-complete--text">Address: {{ $juridic_billing_address1 }}
                                {{ $juridic_billing_address2 }}</span>
                            <span class="checking__content-complete--text">Country:
                                {{ $juridic_billing_country }}</span>
                            <span class="checking__content-complete--text">County:
                                {{ $juridic_billing_county }}</span>
                            <span class="checking__content-complete--text">City: {{ $juridic_billing_city }}</span>
                            <span class="checking__content-complete--text">Post Code:
                                {{ $juridic_billing_zipcode }}</span>
                        </div>
                        <!-- here is shipping information -->
                        <div class="checking__content-complete">
                            <span class="checking__content-create--text">Shipping information &check;</span>
                            <span class="checking__content-complete--text">Full Name: {{ $juridic_shipping_first }}
                                {{ $juridic_shipping_last }}</span>
                            <span class="checking__content-complete--text">Phone:
                                {{ $juridic_shipping_phone }}</span>
                            <span class="checking__content-complete--text">Email:
                                {{ $juridic_shipping_email }}</span>
                            <span class="checking__content-complete--text">Address: {{ $juridic_shipping_address1 }}
                                {{ $juridic_shipping_address2 }}</span>
                            <span class="checking__content-complete--text">Country:
                                {{ $juridic_shipping_country }}</span>
                            <span class="checking__content-complete--text">County:
                                {{ $juridic_shipping_county }}</span>
                            <span class="checking__content-complete--text">City: {{ $juridic_shipping_city }}</span>
                            <span class="checking__content-complete--text">Post Code:
                                {{ $juridic_shipping_zipcode }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        @if ($step == 3)
            <h1 class="section__title">Thank you!</h1>
            <p>Thank you for your payment! 🎉 We have received it and are currently processing your order. Our team is
                working hard to prepare your product for shipment. 📦🔧</p>
            <p>Once your order is on its way, we will send you a confirmation email with tracking information. This will
                allow you to keep track of your package and know when to expect its arrival. 📩🚚</p>
            <p>Should you have any questions or require assistance, please don't hesitate to contact our customer
                support
                team. We're here to help and ensure your satisfaction. 💁‍♀️💬</p>
            <p>We appreciate your business and hope that your purchase brings you happiness. Thank you for choosing our
                products, and we look forward to serving you again in the future. 🙏😊<br>Best regards,</p>
            <h2 class="logo">Embianz</h2>
        @endif
        <div class="details__btns">
            @if ($step == 2)
                <a wire:click.prevent="previous()" data-tooltip="Go to previous step">
                    <svg>
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                </a>
                <a wire:click.prevent="confirm()" data-tooltip="Confirm order">
                    <svg>
                        <polyline points="9 11 12 14 22 4"></polyline>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                </a>
            @elseif ($step == 1)
                <a wire:click.prevent="next()" data-tooltip="Go to next step">
                    <svg>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            @elseif ($step == 3)
                <a wire:click.prevent="finish()" data-tooltip="Go back to store">
                    Thank you for your order, Shopping again
                </a>
            @endif

        </div>
    @endif

</div>
